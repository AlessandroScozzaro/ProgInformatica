
<?php
require_once 'lib/conn.php';

if (isset($_GET['id_dispositivo']) && isset($_GET['valore'])) {

    $id = intval($_GET['id_dispositivo']);
    $valore = floatval($_GET['valore']);

    // Recupero info dispositivo
    $stmt = $conn->prepare("
        SELECT nome, soglia_minima, soglia_massima
        FROM dispositivi
        WHERE id_dispositivo = ?
    ");
    $stmt->execute([$id]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$info) exit("ERRORE: dispositivo non trovato");

    $nome = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $info['nome']));
    $sMin = $info['soglia_minima'];
    $sMax = $info['soglia_massima'];

    $fuoriSoglia = false;
    $id_tipo = null;

    

    if (strpos($nome, "temperatura") !== false) {
        if ($sMax !== null && $valore > $sMax) {
            $fuoriSoglia = true;
            $id_tipo = 2; // temperatura alta
        } elseif ($sMin !== null && $valore < $sMin) {
            $fuoriSoglia = true;
            $id_tipo = 3; // temperatura bassa
        }
    }

    elseif (strpos($nome, "umidita") !== false) {
        if (($sMax !== null && $valore > $sMax) || ($sMin !== null && $valore < $sMin)) {
            $fuoriSoglia = true;
            $id_tipo = 4; // umidità anomala
        }
    }

    elseif (strpos($nome, "aria") !== false) {
        if ($sMax !== null && $valore > $sMax) {
            $fuoriSoglia = true;
            $id_tipo = 5; // aria scarsa
        }
    }

   
    $stmtMis = $conn->prepare("
        INSERT INTO misurazioni (id_dispositivo, valore)
        VALUES (?, ?)
    ");
    $stmtMis->execute([$id, $valore]);

    $id_misurazione = $conn->lastInsertId();

    
    if ($fuoriSoglia && $id_tipo !== null) {

        $stmtEvento = $conn->prepare("
            INSERT INTO eventi (id_dispositivo, id_tipo, id_misurazione, dettagli)
            VALUES (?, ?, ?, ?)
        ");
        $stmtEvento->execute([
            $id,
            $id_tipo,
            $id_misurazione,
            "Valore fuori soglia: $valore"
        ]);

        $id_evento = $conn->lastInsertId();

        // Notifica DB
        $stmtNotifica = $conn->prepare("
            INSERT INTO notifiche (id_evento, id_utente, tipo_notifica, testo)
            VALUES (?, 1, 'Telegram', ?)
        ");
        $stmtNotifica->execute([
            $id_evento,
            "Valore: $valore"
        ]);

        // ========== INVIA TELEGRAM AUTOMATICO ==========
        $apiToken = "7695027367:AAERhDILV39iPRRoVO3Ecpv3R2FIdlgLQXQ";
        $chatId = "-5171557407";

        $tipoAllarme = match($id_tipo) {
            2 => "🔴 TEMPERATURA ALTA",
            3 => "🔵 TEMPERATURA BASSA",
            4 => "💧 UMIDITÀ ANOMALA",
            5 => "💨 ARIA SCARSA",
            default => "⚠️ ALLARME"
        };

        $messaggioTelegram = "<b>$tipoAllarme</b>\n\n" .
                            "<b>Dispositivo:</b> " . htmlspecialchars($info['nome']) . "\n" .
                            "<b>Valore:</b> $valore\n" .
                            "<b>Soglia min:</b> $sMin\n" .
                            "<b>Soglia max:</b> $sMax\n" .
                            "<b>Ora:</b> " . date('d/m/Y H:i:s');

        sendTelegramNotification($chatId, $messaggioTelegram, $apiToken);
    }

    // Redirect semplice
    header("Location: index.php");
    exit;
}
?>
<?php
// =========================
// RECUPERO MISURAZIONI
// =========================

session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$misurazioni = [];

try {
    $stmt = $conn->prepare("
        SELECT 
            m.id_misurazione, 
            d.nome AS dispositivo, 
            m.valore, 
            m.timestamp 
        FROM misurazioni m
        JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
        ORDER BY m.timestamp DESC
    ");

    $stmt->execute();
    $misurazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Errore database: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Misurazioni</title>
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon"><i class="fas fa-microchip"></i></div>
        <div class="sidebar-brand-text mx-3">Monitor Aria</div>
    </a>
    <hr class="sidebar-divider">
    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
    <li class="nav-item"><a class="nav-link" href="stanze.php"><i class="fas fa-building"></i><span>Gestione Stanze</span></a></li>
    <li class="nav-item"><a class="nav-link" href="piantina.php"><i class="fas fa-map"></i><span>Piantina</span></a></li>
    <li class="nav-item"><a class="nav-link" href="utenti.php"><i class="fas fa-users"></i><span>Utenti</span></a></li>
    <li class="nav-item"><a class="nav-link" href="storico.php"><i class="fas fa-chart-line"></i><span>Storico dati</span></a></li>
    <li class="nav-item"><a class="nav-link" href="notifiche.php"><i class="fas fa-bell"></i><span>Notifiche</span></a></li>
    <li class="nav-item"><a class="nav-link" href="aggiungiDispositivo.php"><i class="fas fa-plus"></i><span>Aggiungi Dispositivo</span></a></li>
    <li class="nav-item active"><a class="nav-link" href="misurazioni.php"><i class="fas fa-chart-bar"></i><span>Misurazioni</span></a></li>
    <hr class="sidebar-divider d-none d-md-block">
</ul>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
    <h5 class="m-0 font-weight-bold text-primary">Sistema Monitoraggio Ambientale</h5>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#">
                <a href="logout.php"><span class="mr-2 d-none d-lg-inline text-gray-600 small">Utente</span></a>
                <i class="fas fa-user-circle fa-2x text-gray-300"></i>
            </a>
        </li>
    </ul>
</nav>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">Misurazioni</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Elenco Misurazioni</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dispositivo</th>
                        <th>Valore</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
               <?php if (!empty($misurazioni)): ?>
    <?php foreach ($misurazioni as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['id_misurazione']) ?></td>
            <td><?= htmlspecialchars($m['dispositivo']) ?></td>
            <td><?= htmlspecialchars($m['valore']) ?></td>
            <td><?= htmlspecialchars($m['timestamp']) ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="4">Nessuna misurazione disponibile</td>
    </tr>
<?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
</div>

<footer class="sticky-footer bg-white">
<div class="container my-auto">
<div class="copyright text-center my-auto">
<span>Progetto Monitoraggio Ambientale - 2026</span>
</div>
</div>
</footer>

</div>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>


<?php
require_once 'lib/conn.php';

/* =========================
   API PER DISPOSITIVO
   ========================= */
if (isset($_GET['id_dispositivo']) && isset($_GET['valore'])) {

    header("Content-Type: text/plain");

    $id = intval($_GET['id_dispositivo']);
    $valore = floatval($_GET['valore']);

    try {
        $stmt = $conn->prepare("
            INSERT INTO misurazioni (id_dispositivo, valore)
            VALUES (?, ?)
        ");
        $stmt->execute([$id, $valore]);

        // 🔥 redirect alla pagina pulita (senza parametri)
        header("Location: misurazioni.php");
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo "Errore DB";
        exit;
    }
}

/* =========================
   PARTE WEB (UTENTE)
   ========================= */
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

try {
    $stmt = $conn->prepare('
        SELECT m.id_misurazione, d.nome AS dispositivo, m.valore, m.timestamp 
        FROM misurazioni m 
        JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo 
        ORDER BY m.timestamp DESC
    ');
    $stmt->execute();
    $misurazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Errore query: ' . $e->getMessage());
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
                <?php foreach ($misurazioni as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['id_misurazione']) ?></td>
                        <td><?= htmlspecialchars($m['dispositivo']) ?></td>
                        <td><?= htmlspecialchars($m['valore']) ?></td>
                        <td><?= htmlspecialchars($m['timestamp']) ?></td>
                    </tr>
                <?php endforeach; ?>
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


http://localhost/ProgInformatica/misurazioni.php?id_dispositivo=1&valore=14.00
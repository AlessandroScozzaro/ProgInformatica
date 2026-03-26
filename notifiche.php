<?php
session_start();

// Controllo login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Connessione DB
require_once 'lib/conn.php';

$id_utente = $_SESSION['id'];
 // https://t.me/+g6FqpxXHuQQxNWU0

// Query con JOIN per avere più informazioni
$stmt = $conn->prepare("
    SELECT 
        n.id_notifica,
        n.testo,
        n.tipo_notifica,
        n.timestamp_invio,
        e.dettagli,
        d.nome AS dispositivo
    FROM notifiche n
    JOIN eventi e ON n.id_evento = e.id_evento
    JOIN dispositivi d ON e.id_dispositivo = d.id_dispositivo
    WHERE n.id_utente = :id_utente
    ORDER BY n.timestamp_invio DESC
");

$stmt->execute(['id_utente' => $id_utente]);
$notifiche = $stmt->fetchAll(PDO::FETCH_ASSOC);



$apiToken = "7695027367:AAERhDILV39iPRRoVO3Ecpv3R2FIdlgLQXQ";
$chatId = "-5171557407"; // Può essere un utente, gruppo o canale
$_SESSION['telegram_chat_id'] ?? '';
// Funzione per inviare un messaggio Telegram
function sendTelegramMessage($chatId, $message, $apiToken) {
    $url = "https://api.telegram.org/bot$apiToken/sendMessage";

    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context  = stream_context_create($options);
    file_get_contents($url, false, $context);
}

//triggeriamo l'invio del messaggio Telegram per le ultime 10 notifiche
$recentNotifiche = array_slice($notifiche, 0, 10);
if (!empty($recentNotifiche)) {
    $bigMessage = "Ultime 10 notifiche:\n\n";
    foreach ($recentNotifiche as $n) {
        $bigMessage .= "Notifica: " . $n['testo'] . "\n" .
                       "Dispositivo: " . $n['dispositivo'] . "\n" .
                       "Tipo: " . $n['tipo_notifica'] . "\n" .
                       "Evento: " . $n['dettagli'] . "\n" .
                       "Inviata il: " . date('d/m/Y H:i', strtotime($n['timestamp_invio'])) . "\n\n";
    }
    sendTelegramMessage($chatId, $bigMessage, $apiToken);
}




?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifiche</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">


<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-icon">
                <i class="fas fa-microchip"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Monitor Aria</div>
        </a>

        <hr class="sidebar-divider">

        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="stanze.php">
                <i class="fas fa-building"></i>
                <span>Gestione Stanze</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="piantina.php">
                <i class="fas fa-map"></i>
                <span>Piantina</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="utenti.php">
                <i class="fas fa-users"></i>
                <span>Utenti</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="storico.php">
                <i class="fas fa-chart-line"></i>
                <span>Storico dati</span>
            </a>
        </li>

        <li class="nav-item active">
            <a class="nav-link" href="notifiche.php">
                <i class="fas fa-bell"></i>
                <span>Notifiche</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="aggiungiDispositivo.php">
                <i class="fas fa-plus"></i>
                <span>Aggiungi Dispositivo</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">

    </ul>

    <!-- Content -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <h5 class="m-0 font-weight-bold text-primary">
                    Sistema Monitoraggio Ambientale
                </h5>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#">
                            <a href="logout.php"><span class="mr-2 d-none d-lg-inline text-gray-600 small">Utente</span></a>
                            <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Container -->
            <div class="container-fluid">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 text-gray-800">Notifiche</h1>
                    <a href="index.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-home"></i> Home
                    </a>
                </div>

                <!-- Card -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Storico notifiche
                        </h6>
                    </div>

                    <div class="card-body">

                        <?php if (empty($notifiche)): ?>
                            <div class="alert alert-info">
                                Nessuna notifica trovata.
                            </div>
                        <?php else: ?>

                            <div class="list-group">

                                <?php foreach ($notifiche as $n): ?>

                                    <div class="list-group-item">

                                        <div class="d-flex justify-content-between">
                                            <strong>
                                                <?= htmlspecialchars($n['testo']) ?>
                                            </strong>

                                            <small>
                                                <?= date('d/m/Y H:i', strtotime($n['timestamp_invio'])) ?>
                                            </small>
                                        </div>

                                        <div class="mt-2 text-muted">
                                            Dispositivo: <?= htmlspecialchars($n['dispositivo']) ?>
                                        </div>

                                        <div class="text-muted">
                                            Tipo: <?= htmlspecialchars($n['tipo_notifica']) ?>
                                        </div>

                                        <div class="text-muted small">
                                            Evento: <?= htmlspecialchars($n['dettagli']) ?>
                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Progetto Monitoraggio Ambientale - 2026</span>
                </div>
            </div>
        </footer>

    </div>

</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

</body>
</html>
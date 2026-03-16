<?php
// notifiche.php

session_start();

// Controllo autenticazione utente
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Connessione al database
require_once 'lib/conn.php'; // Assicurati che questo file contenga la connessione PDO $pdo

$user_id = $_SESSION['user_id'];

// Recupera le notifiche dell'utente
$stmt = $pdo->prepare("SELECT id, messaggio, letto, data_creazione FROM notifiche WHERE utente_id = :user_id ORDER BY data_creazione DESC");
$stmt->execute(['user_id' => $user_id]);
$notifiche = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Segna tutte le notifiche come lette
$pdo->prepare("UPDATE notifiche SET letto = 1 WHERE utente_id = :user_id AND letto = 0")->execute(['user_id' => $user_id]);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Notifiche - Monitoraggio Ambientale</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
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

        <li class="nav-item active">
            <a class="nav-link" href="notifiche.php">
                <i class="fas fa-bell"></i>
                <span>Notifiche</span>
            </a>
        </li>

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
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Utente</span>
                            <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Notifiche</h1>
                    <a href="index.php" class="btn btn-sm btn-primary"><i class="fas fa-home fa-sm fa-fw"></i> Home</a>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Ultime notifiche</h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($notifiche)): ?>
                                    <div class="alert alert-info">Non hai notifiche.</div>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($notifiche as $notifica): ?>
                                            <a href="#" class="list-group-item list-group-item-action <?= $notifica['letto'] ? '' : 'font-weight-bold' ?>">
                                                <?= htmlspecialchars($notifica['messaggio']) ?>
                                                <span class="small text-muted float-right"><?= date('d/m/Y H:i', strtotime($notifica['data_creazione'])) ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
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

</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>


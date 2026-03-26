<?php
session_start();

// Controllo login
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

require_once 'lib/conn.php';

// Query utenti
try {
    $stmt = $conn->prepare("SELECT * FROM utenti ORDER BY nome ASC");
    $stmt->execute();
    $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore query: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Utenti - Monitoraggio Ambientale</title>

<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

<div id="wrapper">

    <!-- SIDEBAR -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion">

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

        <li class="nav-item active">
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

        <li class="nav-item">
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

    <!-- CONTENT -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- TOPBAR -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <h5 class="m-0 font-weight-bold text-primary">
                    Sistema Monitoraggio Ambientale
                </h5>

                <ul class="navbar-nav ml-auto">
<<<<<<< Updated upstream
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#">
                            <a href="logout.php"><span class="mr-2 d-none d-lg-inline text-gray-600 small">Utente</span></a>
                            <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                        </a>
=======
                    <li class="nav-item">
                        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
>>>>>>> Stashed changes
                    </li>
                </ul>
            </nav>

            <!-- CONTENUTO -->
            <div class="container-fluid">

                <h1 class="h3 mb-4 text-gray-800">Utenti</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Elenco utenti registrati
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Profilo</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Ruolo</th>
                                        <th>Telegram</th>
                                    </tr>
                                </thead>

                                <tbody>

                                <?php if(count($utenti) > 0): ?>

                                    <?php foreach($utenti as $u): ?>

                                        <tr>

                                            <!-- Immagine -->
                                            <td>
                                                <?php if (!empty($u['immagine_profilo'])): ?>
                                                    <img 
                                                        src="/ProgInformatica<?= htmlspecialchars($u['immagine_profilo']) ?>" 
                                                        width="40" 
                                                        height="40"
                                                        style="border-radius:50%; object-fit:cover;"
                                                    >
                                                <?php else: ?>
                                                    <i class="fas fa-user-circle fa-2x"></i>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Nome -->
                                            <td>
                                                <?= htmlspecialchars($u['nome'] . " " . $u['cognome']) ?>
                                            </td>

                                            <!-- Email -->
                                            <td>
                                                <?= htmlspecialchars($u['email']) ?>
                                            </td>

                                            <!-- Ruolo -->
                                            <td>
                                                <span class="badge badge-<?= $u['ruolo'] == 'Proprietario' ? 'success' : 'secondary' ?>">
                                                    <?= htmlspecialchars($u['ruolo']) ?>
                                                </span>
                                            </td>

                                            <!-- Telegram -->
                                            <td>
                                                <?= htmlspecialchars($u['chiave_telegram']) ?>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Nessun utente trovato
                                        </td>
                                    </tr>

                                <?php endif; ?>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Progetto Monitoraggio Ambientale - 2026</span>
                </div>
            </div>
        </footer>

    </div>

</div>

<!-- SCRIPT -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

</body>
</html>
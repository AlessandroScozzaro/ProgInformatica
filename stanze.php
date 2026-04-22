<?php
require_once 'lib/conn.php';

$message = '';
$messageType = 'info';
if (isset($_SESSION['error']) && $_SESSION['error'] == "1") {
    echo "<script>alert('Errore durante l\'aggiornamento del dispositivo.');</script>";
    unset($_SESSION['error']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $volumetria = isset($_POST['volumetria']) ? trim($_POST['volumetria']) : '';

    if ($nome === '' || $volumetria === '') {
        $message = 'Compila tutti i campi prima di inviare.';
        $messageType = 'danger';
    } elseif (!is_numeric($volumetria) || floatval($volumetria) <= 0) {
        $message = 'La volumetria deve essere un numero maggiore di 0.';
        $messageType = 'danger';
    } else {
        $volumetria = floatval($volumetria);

        $stmt = $conn->prepare('INSERT INTO stanze (nome, volumetria) VALUES (:nome, :volumetria)');
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':volumetria', $volumetria);

        if ($stmt->execute()) {
            $message = 'Stanza aggiunta con successo.';
            $messageType = 'success';
        } else {
            $message = 'Errore durante l’aggiunta della stanza. Riprova.';
            $messageType = 'danger';
        }
    }
}

// Prendo tutte le stanze (aggiornate dopo eventuale inserimento)
$stmt = $conn->query('SELECT * FROM stanze ORDER BY nome ASC');
$stanze = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Gestione Stanze</title>
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
    <li class="nav-item active"><a class="nav-link" href="stanze.php"><i class="fas fa-building"></i><span>Gestione Stanze</span></a></li>
    <li class="nav-item"><a class="nav-link" href="piantina.php"><i class="fas fa-map"></i><span>Piantina</span></a></li>
    <li class="nav-item"><a class="nav-link" href="utenti.php"><i class="fas fa-users"></i><span>Utenti</span></a></li>
    <li class="nav-item"><a class="nav-link" href="storico.php"><i class="fas fa-chart-line"></i><span>Storico dati</span></a></li>
    <li class="nav-item"><a class="nav-link" href="notifiche.php"><i class="fas fa-bell"></i><span>Notifiche</span></a></li>
    <li class="nav-item"><a class="nav-link" href="aggiungiDispositivo.php"><i class="fas fa-plus"></i><span>Aggiungi Dispositivo</span></a></li>
    <li class="nav-item"><a class="nav-link" href="misurazioni.php"><i class="fas fa-chart-bar"></i><span>Misurazioni</span></a></li>
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
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
            </a>
        </li>
    </ul>
</nav>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">Gestione Stanze</h1>

<div class="card shadow mb-4">
    <div class="card-header">Aggiungi Nuova Stanza</div>
    <div class="card-body">
        <?php if ($message !== ''): ?>
            <div class="alert alert-<?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="stanze.php">
            <div class="form-group">
                <label for="nome">Nome stanza</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="volumetria">Volume (m³)</label>
                <input type="number" class="form-control" id="volumetria" name="volumetria" step="0.01" min="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Crea Stanza</button>
        </form>
    </div>
    <div class="card-body">
    <form action="updateStanza.php" method="post">
        <div class="form-group">
            <label for="nome">ID</label>
            <input type="text" class="form-control" id="id" name="id" required>
        </div>
        <div class="form-group">
                <label for="nome">Nome stanza</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="volumetria">Volume (m³)</label>
                <input type="number" class="form-control" id="volumetria" name="volumetria" step="0.01" min="0.01" required>
            </div>
        <button type="submit" class="btn btn-primary">Modifica Stanza</button>
    </form>
</div>
</div>

<div class="card shadow mb-4">
    <div class="card-header">Elenco Stanze</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome stanza</th>
                    <th>Volume (m³)</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($stanze as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['nome']) ?></td>
                    <td><?= htmlspecialchars($s['volumetria']) ?> m³</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
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
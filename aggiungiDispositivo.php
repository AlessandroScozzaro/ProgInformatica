<?php
require_once 'lib/conn.php';
session_start();
if (isset($_SESSION['error']) && $_SESSION['error'] == "1") {
    echo "<script>alert('Errore durante l\'aggiornamento del dispositivo.');</script>";
    unset($_SESSION['error']);
}
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$message = isset($_GET['message']) ? $_GET['message'] : '';
$messageType = isset($_GET['type']) ? $_GET['type'] : 'info';

$result = $conn->prepare("SELECT id_stanza, nome FROM stanze");
if ($result) {
    $result->execute();
}
$result2 = $conn->prepare("SELECT id_stanza, nome FROM stanze");
if ($result2) {
    $result2->execute();
}

// Prendo tutti i dispositivi con il nome della stanza
$stmt = $conn->query('SELECT d.*, s.nome AS nome_stanza FROM dispositivi d JOIN stanze s ON d.id_stanza = s.id_stanza ORDER BY d.nome ASC');
$dispositivi = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="it">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Aggiungi Dispositivo</title>
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
    <li class="nav-item active"><a class="nav-link" href="aggiungiDispositivo.php"><i class="fas fa-plus"></i><span>Aggiungi Dispositivo</span></a></li>
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

<?php if ($message !== ''): ?>
<div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
<?= htmlspecialchars($message) ?>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<?php endif; ?>

<div class="container-fluid">
<h1 class="h3 mb-4 text-gray-800">Aggiungi Nuovo Dispositivo</h1>

<div class="row justify-content-center">
<div class="col-md-6">
<div class="card shadow mb-4">
<div class="card-body">
    <form action="insertDispositivo.php" method="post">
        <div class="form-group">
            <label for="nome">Nome Dispositivo</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="stanza">Stanza</label>
            <select class="form-control" id="stanza" name="stanza" required>
                <option value="">Seleziona Stanza</option>
                <?php
                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) {
                        echo "<option value='" . $row['id_stanza'] . "'>" . $row['nome'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nessuna stanza disponibile</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo Dispositivo</label>
            <select class="form-control" id="tipo" name="tipo" required>
                <option value="Sensore">Sensore</option>
                <option value="Attuatore">Attuatore</option>
            </select>
        </div>
        <div class="form-group">
            <label for="unita_misura">Unità di Misura</label>
            <select class="form-control" id="unita_misura" name="unita_misura" required>
                <option value="">Seleziona Unità di Misura</option>
                <option value="°C">°C</option>
                <option value="%">%</option>
                <option value="Lux">Lux</option>
            </select>
        </div>
        <div class="form-group">
            <label for="soglia_minima">Soglia Minima</label>
            <input type="number" class="form-control" id="soglia_minima" name="soglia_minima" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="soglia_massima">Soglia Massima</label>
            <input type="number" class="form-control" id="soglia_massima" name="soglia_massima" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi Dispositivo</button>
    </form>
</div>
<div class="card-body">
    <form action="updateDispositivo.php" method="post">
        <div class="form-group">
            <label for="nome">ID</label>
            <input type="text" class="form-control" id="id" name="id" required>
        </div>
        <div class="form-group">
            <label for="nome">Nome Dispositivo</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="stanza">Stanza</label>
            <select class="form-control" id="stanza" name="stanza" required>
                <option value="">Seleziona Stanza</option>
                <?php
                if ($result2->rowCount() > 0) {
                    while ($row = $result2->fetch()) {
                        echo "<option value='" . $row['id_stanza'] . "'>" . $row['nome'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nessuna stanza disponibile</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo Dispositivo</label>
            <select class="form-control" id="tipo" name="tipo" required>
                <option value="Sensore">Sensore</option>
                <option value="Attuatore">Attuatore</option>
            </select>
        </div>
        <div class="form-group">
            <label for="unita_misura">Unità di Misura</label>
            <select class="form-control" id="unita_misura" name="unita_misura" required>
                <option value="">Seleziona Unità di Misura</option>
                <option value="°C">°C</option>
                <option value="%">%</option>
                <option value="Lux">Lux</option>
            </select>
        </div>
        <div class="form-group">
            <label for="soglia_minima">Soglia Minima</label>
            <input type="number" class="form-control" id="soglia_minima" name="soglia_minima" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="soglia_massima">Soglia Massima</label>
            <input type="number" class="form-control" id="soglia_massima" name="soglia_massima" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Modifica Dispositivo</button>
    </form>
</div>
</div>
</div>
</div>

<div class="row">
<div class="col-xl-12">
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Elenco Dispositivi</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome Dispositivo</th>
                    <th>Stanza</th>
                    <th>Tipo</th>
                    <th>Unità di Misura</th>
                    <th>Soglia Minima</th>
                    <th>Soglia Massima</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($dispositivi as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['nome']) ?></td>
                    <td><?= htmlspecialchars($d['nome_stanza']) ?></td>
                    <td><?= htmlspecialchars($d['tipo']) ?></td>
                    <td><?= htmlspecialchars($d['unita_misura']) ?></td>
                    <td><?= htmlspecialchars($d['soglia_minima']) ?></td>
                    <td><?= htmlspecialchars($d['soglia_massima']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
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
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
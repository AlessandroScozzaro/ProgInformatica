<?php
require_once 'lib/conn.php';

// =========================
// RECUPERO STANZE
// =========================
$stanze = [];
try {
    $stmt = $conn->prepare("SELECT * FROM stanze");
    $stmt->execute();
    $stanze = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Errore stanze: " . $e->getMessage();
}

// =========================
// RECUPERO DISPOSITIVI
// =========================
$dispositivi = [];
try {
    $stmt = $conn->prepare("
        SELECT d.*, s.nome AS stanza_nome
        FROM dispositivi d
        JOIN stanze s ON d.id_stanza = s.id_stanza
    ");
    $stmt->execute();
    $dispositivi = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Errore dispositivi: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Piantina Stanze</title>
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
    <li class="nav-item active"><a class="nav-link" href="piantina.php"><i class="fas fa-map"></i><span>Piantina</span></a></li>
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

<h1 class="h3 mb-4 text-gray-800">
Piantina Stanze
</h1>

<div class="card shadow mb-4">
<div class="card-header bg-primary text-white">
    <h6 class="m-0 font-weight-bold">Piantina Stanze</h6>
</div>
<div class="card-body">

<style>
.house-map {
    display: grid;
    grid-template-columns: repeat(4, 180px); /* Stanze più larghe */
    grid-template-rows: repeat(3, 160px);    /* Stanze più alte */
    gap: 15px;
    padding: 20px;
    background-color: #333;
    border: 6px solid #222;
    margin-top: 20px;
}

.room {
    background-color: #e0e0e0;
    border: 1px solid #aaa;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    font-size: 16px;
    font-weight: bold;
    position: relative;
    border-radius: 8px;
}

.device {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin: 6px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 18px;
    color: white;
}

.light { background-color: #f6c23e; }
.temp  { background-color: #4e73df; }
.air   { background-color: #1cc88a; }

.tooltip {
    visibility: hidden;
    background-color: black;
    color: #fff;
    padding: 4px 6px;
    border-radius: 5px;
    position: absolute;
    bottom: 110%;
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 12px;
}

.device:hover .tooltip {
    visibility: visible;
    opacity: 1;
}
</style>

<div class="house-map">

<?php
// Mappa posizioni nella griglia
$grid = [
    "Soggiorno"       => "grid-column: 1 / 3; grid-row: 1 / 3;",
    "Cucina"          => "grid-column: 3 / 5; grid-row: 1 / 2;",
    "Camera da letto" => "grid-column: 3 / 4; grid-row: 2 / 4;",
    "Bagno"           => "grid-column: 4 / 5; grid-row: 2 / 4;",
    "Corridoio"       => "grid-column: 1 / 3; grid-row: 3 / 4;"
];

foreach ($stanze as $s) {
    $nome = $s["nome"];
    $style = isset($grid[$nome]) ? $grid[$nome] : "";
    echo "<div class='room' style='$style'>$nome";

    foreach ($dispositivi as $d) {
        if ($d["stanza_nome"] == $nome) {

            // Icone e colori
            $icon = "💡";
            $class = "light";

            if (stripos($d["nome"], "temperatura") !== false) {
                $icon = "🌡️";
                $class = "temp";
            }
            if (stripos($d["nome"], "qualità") !== false) {
                $icon = "🌬️";
                $class = "air";
            }

            echo "
            <div class='device $class'>
                $icon
                <span class='tooltip'>{$d['nome']}</span>
            </div>";
        }
    }

    echo "</div>";
}
?>



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
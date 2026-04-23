<?php
session_start();
require_once 'lib/conn.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

/* ============================
   FUNZIONI
============================ */
function getLatestValue($conn, $keyword) {
    return $conn->query("
        SELECT m.valore 
        FROM misurazioni m
        JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
        WHERE d.nome LIKE '%$keyword%'
        ORDER BY m.timestamp DESC
        LIMIT 1
    ")->fetch()['valore'] ?? null;
}

function getChartData($conn, $keyword) {
    return $conn->query("
        SELECT m.valore, m.timestamp, d.nome AS origine
        FROM misurazioni m
        JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
        WHERE d.nome LIKE '%$keyword%'
        AND m.timestamp >= NOW() - INTERVAL 1 DAY
        ORDER BY m.timestamp ASC
        LIMIT 200
    ")->fetchAll(PDO::FETCH_ASSOC);
}

function getDailyAvg($conn, $keyword) {
    return $conn->query("
        SELECT AVG(m.valore) AS media
        FROM misurazioni m
        JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
        WHERE d.nome LIKE '%$keyword%'
        AND m.timestamp >= NOW() - INTERVAL 1 DAY
    ")->fetch()['media'] ?? null;
}



/* ============================
   GRAFICI
============================ */
$tempData = getChartData($conn, "temperatura");
$umData   = getChartData($conn, "umid");
$ariaData = getChartData($conn, "aria");

function extractChart($data) {
    $labels = [];
    $values = [];
    $origine = $data[0]['origine'] ?? "Sconosciuto";

    foreach ($data as $d) {
        $labels[] = date("d/m H:i", strtotime($d['timestamp']));
        $values[] = $d['valore'];
    }

    return [$labels, $values, $origine];
}

list($tempLabels, $tempValues, $tempOrigine) = extractChart($tempData);
list($umLabels, $umValues, $umOrigine)       = extractChart($umData);
list($ariaLabels, $ariaValues, $ariaOrigine) = extractChart($ariaData);

/* ============================
   MEDIE 24H
============================ */
$mediaTemp = round(getDailyAvg($conn, "temperatura"), 1);
$mediaUm   = round(getDailyAvg($conn, "umid"), 1);
$mediaAria = round(getDailyAvg($conn, "aria"), 1);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Sensori</title>
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
    <li class="nav-item active"><a class="nav-link" href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
    <li class="nav-item"><a class="nav-link" href="stanze.php"><i class="fas fa-building"></i><span>Gestione Stanze</span></a></li>
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

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
    <h5 class="m-0 font-weight-bold text-primary">Sistema Monitoraggio Ambientale</h5>
</nav>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">Dashboard Sensori</h1>

<!-- CARDS -->
<div class="row">
    <div class="col-md-3"><div class="card border-left-danger shadow mb-3"><div class="card-body">Temperatura: <?= $temp ?> °C</div></div></div>
    <div class="col-md-3"><div class="card border-left-primary shadow mb-3"><div class="card-body">Umidità: <?= $um ?> %</div></div></div>
    <div class="col-md-3"><div class="card border-left-success shadow mb-3"><div class="card-body">Aria: <?= $aria ?> ppm</div></div></div>
    <div class="col-md-3"><div class="card border-left-<?= $colore ?> shadow mb-3"><div class="card-body">Stato: <?= $stato ?></div></div></div>
</div>


<!-- GRAFICI -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow mb-3">
            <div class="card-header">Temperatura (origine: <?= $tempOrigine ?>)</div>
            <div class="card-body"><canvas id="tempChart" style="height:200px;"></canvas></div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow mb-3">
            <div class="card-header">Umidità (origine: <?= $umOrigine ?>)</div>
            <div class="card-body"><canvas id="umChart" style="height:200px;"></canvas></div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow mb-3">
            <div class="card-header">Qualità Aria (origine: <?= $ariaOrigine ?>)</div>
            <div class="card-body"><canvas id="ariaChart" style="height:200px;"></canvas></div>
        </div>
    </div>
</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    new Chart(document.getElementById("tempChart"), {
        type: 'line',
        data: {
            labels: <?= json_encode($tempLabels) ?>,
            datasets: [{
                label: "Temperatura (°C)",
                data: <?= json_encode($tempValues) ?>,
                borderColor: "red",
                tension: 0.4
            }]
        }
    });

    new Chart(document.getElementById("umChart"), {
        type: 'line',
        data: {
            labels: <?= json_encode($umLabels) ?>,
            datasets: [{
                label: "Umidità (%)",
                data: <?= json_encode($umValues) ?>,
                borderColor: "blue",
                tension: 0.4
            }]
        }
    });

    new Chart(document.getElementById("ariaChart"), {
        type: 'line',
        data: {
            labels: <?= json_encode($ariaLabels) ?>,
            datasets: [{
                label: "Qualità Aria (ppm)",
                data: <?= json_encode($ariaValues) ?>,
                borderColor: "green",
                tension: 0.4
            }]
        }
    });

});
</script>

</body>
</html>

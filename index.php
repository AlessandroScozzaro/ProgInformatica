<?php
session_start();
require_once 'lib/conn.php';

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}

// --- PRENDI DATI DAL DATABASE ---

// Temperatura
$tempResult = $conn->query("
SELECT m.valore 
FROM misurazioni m
JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
WHERE d.nome LIKE '%temperatura%'
ORDER BY m.timestamp DESC
LIMIT 1
");
$temp = $tempResult ? $tempResult->fetch() : null;

// Umidità
$umResult = $conn->query("
SELECT m.valore 
FROM misurazioni m
JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
WHERE d.nome LIKE '%umidità%'
ORDER BY m.timestamp DESC
LIMIT 1
");
$um = $umResult ? $umResult->fetch() : null;

// Qualità aria
$ariaResult = $conn->query("
SELECT m.valore 
FROM misurazioni m
JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
WHERE d.nome LIKE '%aria%'
ORDER BY m.timestamp DESC
LIMIT 1
");
$aria = $ariaResult ? $ariaResult->fetch() : null;

// Stato qualità aria
$valAria = $aria['valore'] ?? 0;
if($valAria < 50){
    $stato = "Buona";
    $colore = "success";
} elseif($valAria < 100){
    $stato = "Media";
    $colore = "warning";
} else {
    $stato = "Scarsa";
    $colore = "danger";
}

// Dati grafico qualità aria (ord. crescente)
$dati = $conn->query("
SELECT m.valore, m.timestamp
FROM misurazioni m
JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
WHERE d.nome LIKE '%aria%'
ORDER BY m.timestamp DESC
LIMIT 100
");

$valori = [];
$labels = [];
// Fetch query results into array
$datiArr = $dati ? $dati->fetchAll() : [];
// Reverse the array to maintain chronological order since we used DESC in the query
$datiArr = array_reverse($datiArr);

foreach($datiArr as $d){
    $valori[] = $d['valore'];
    $labels[] = date("H:i", strtotime($d['timestamp']));
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Dashboard Monitoraggio Ambientale</title>
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
    <li class="nav-item active"><a class="nav-link" href="#"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
    <li class="nav-item"><a class="nav-link" href="stanze.php"><i class="fas fa-building"></i><span>Gestione Stanze</span></a></li>
    <li class="nav-item"><a class="nav-link" href="piantina.php"><i class="fas fa-map"></i><span>Piantina</span></a></li>
    <li class="nav-item"><a class="nav-link" href="utenti.php"><i class="fas fa-users"></i><span>Utenti</span></a></li>
    <li class="nav-item"><a class="nav-link" href="storico.php"><i class="fas fa-chart-line"></i><span>Storico dati</span></a></li>
    <li class="nav-item"><a class="nav-link" href="notifiche.php"><i class="fas fa-bell"></i><span>Notifiche</span></a></li>
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
<h1 class="h3 mb-4 text-gray-800">Dashboard Sensori Ambientali</h1>
<div class="row">

<!-- Temperatura -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-danger shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">
<div class="col mr-2">
<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Temperatura</div>
<div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($temp['valore'] ?? '--') ?> °C</div>
</div>
<div class="col-auto"><i class="fas fa-thermometer-half fa-2x text-gray-300"></i></div>
</div>
</div>
</div>
</div>

<!-- Umidità -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-primary shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">
<div class="col mr-2">
<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Umidità</div>
<div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($um['valore'] ?? '--') ?> %</div>
</div>
<div class="col-auto"><i class="fas fa-tint fa-2x text-gray-300"></i></div>
</div>
</div>
</div>
</div>

<!-- Qualità aria -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-success shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">
<div class="col mr-2">
<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Qualità aria (MQ135)</div>
<div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($aria['valore'] ?? '--') ?> ppm</div>
</div>
<div class="col-auto"><i class="fas fa-wind fa-2x text-gray-300"></i></div>
</div>
</div>
</div>
</div>

<!-- Stato aria -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-<?= $colore ?> shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">
<div class="col mr-2">
<div class="text-xs font-weight-bold text-<?= $colore ?> text-uppercase mb-1">Stato qualità aria</div>
<div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stato ?></div>
</div>
<div class="col-auto"><i class="fas fa-smile fa-2x text-gray-300"></i></div>
</div>
</div>
</div>
</div>

</div>

<div class="row">
<div class="col-xl-12">
<div class="card shadow mb-4">
<div class="card-header py-3">
<h6 class="m-0 font-weight-bold text-primary">Andamento qualità aria (MQ135)</h6>
</div>
<div class="card-body">
<?php if (empty($valori)): ?>
    <div class="text-center text-muted" style="height: 400px; display: flex; align-items: center; justify-content: center;">
        Nessun dato disponibile per il grafico.
    </div>
<?php else: ?>
    <div style="position: relative; height: 400px; width: 100%;">
        <canvas id="airChart"></canvas>
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
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($valori)): ?>
document.addEventListener("DOMContentLoaded", function() {
    var canvasElement = document.getElementById("airChart");
    if (canvasElement) {
        var ctx = canvasElement.getContext("2d");
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: "Qualità aria (ppm)",
                    data: <?= json_encode($valori) ?>,
                    borderColor: "#1cc88a",
                    backgroundColor: "rgba(28, 200, 138, 0.1)",
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: "#1cc88a"
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 500
                    }
                }
            }
        });
    }
});
<?php endif; ?>
</script>

</body>
</html>
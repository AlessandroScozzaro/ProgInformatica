<?php
session_start();
require_once 'lib/conn.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// --- DATI SINGOLI (CARD) ---
$temp = $conn->query("
SELECT m.valore 
FROM misurazioni m
JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
WHERE d.nome LIKE '%temperatura%'
ORDER BY m.timestamp DESC
LIMIT 1
")->fetch();

$um = $conn->query("
SELECT m.valore 
FROM misurazioni m
JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
WHERE d.nome LIKE '%umidità%'
ORDER BY m.timestamp DESC
LIMIT 1
")->fetch();

$aria = $conn->query("
SELECT m.valore 
FROM misurazioni m
JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
WHERE d.nome LIKE '%aria%'
ORDER BY m.timestamp DESC
LIMIT 1
")->fetch();

// Stato aria
$valAria = $aria['valore'] ?? 0;
if ($valAria < 50) {
    $stato = "Buona";
    $colore = "success";
} elseif ($valAria < 100) {
    $stato = "Media";
    $colore = "warning";
} else {
    $stato = "Scarsa";
    $colore = "danger";
}

// --- DATI GRAFICO TEMPERATURA ---
$tempData = $conn->query("
SELECT m.valore, m.timestamp 
FROM misurazioni m
JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
WHERE d.nome LIKE '%temperatura%'
ORDER BY m.timestamp ASC
LIMIT 100
")->fetchAll(PDO::FETCH_ASSOC);

$tempLabels = [];
$tempValues = [];
foreach ($tempData as $d) {
    $tempLabels[] = date("H:i", strtotime($d['timestamp']));
    $tempValues[] = $d['valore'];
}

// --- DATI GRAFICO UMIDITÀ ---
$umData = $conn->query("
SELECT m.valore, m.timestamp 
FROM misurazioni m
JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
WHERE d.nome LIKE '%umidità%'
ORDER BY m.timestamp ASC
LIMIT 100
")->fetchAll(PDO::FETCH_ASSOC);

$umLabels = [];
$umValues = [];
foreach ($umData as $d) {
    $umLabels[] = date("H:i", strtotime($d['timestamp']));
    $umValues[] = $d['valore'];
}

// --- DATI GRAFICO ARIA ---
$ariaData = $conn->query("
SELECT m.valore, m.timestamp 
FROM misurazioni m
JOIN dispositivi d ON m.id_dispositivo = d.id_dispositivo
WHERE d.nome LIKE '%aria%'
ORDER BY m.timestamp ASC
LIMIT 100
")->fetchAll(PDO::FETCH_ASSOC);

$ariaLabels = [];
$ariaValues = [];
foreach ($ariaData as $d) {
    $ariaLabels[] = date("H:i", strtotime($d['timestamp']));
    $ariaValues[] = $d['valore'];
}
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

                    <h1 class="h3 mb-4 text-gray-800">Dashboard Sensori</h1>

                    <div class="row">
                        
                        <div class="col-md-3">
                            <div class="card border-left-danger shadow mb-3">
                                <div class="card-body">Temperatura: <?= $temp['valore'] ?? '--' ?> °C</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-primary shadow mb-3">
                                <div class="card-body">Umidità: <?= $um['valore'] ?? '--' ?> %</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-success shadow mb-3">
                                <div class="card-body">Aria: <?= $aria['valore'] ?? '--' ?> ppm</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-<?= $colore ?> shadow mb-3">
                                <div class="card-body">Stato: <?= $stato ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafici Separati -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card shadow mb-3">
                                <div class="card-header">Temperatura</div>
                                <div class="card-body"><canvas id="tempChart" style="height:200px;"></canvas></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card shadow mb-3">
                                <div class="card-header">Umidità</div>
                                <div class="card-body"><canvas id="umChart" style="height:200px;"></canvas></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card shadow mb-3">
                                <div class="card-header">Qualità Aria</div>
                                <div class="card-body"><canvas id="ariaChart" style="height:200px;"></canvas></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
            </div>
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Temperatura
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
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
            // Umidità
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
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
            // Qualità Aria
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
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
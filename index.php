<script>
if(localStorage.getItem("logged") !== "true"){
window.location.href = "login.php"
}
</script>
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
<div class="sidebar-brand-icon">
<i class="fas fa-microchip"></i>
</div>
<div class="sidebar-brand-text mx-3">Monitor Aria</div>
</a>

<hr class="sidebar-divider">

<li class="nav-item active">
<a class="nav-link" href="#">
<i class="fas fa-fw fa-tachometer-alt"></i>
<span>Dashboard</span></a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">
<i class="fas fa-chart-line"></i>
<span>Storico dati</span></a>
</li>

<hr class="sidebar-divider d-none d-md-block">

</ul>

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
<span class="mr-2 d-none d-lg-inline text-gray-600 small">
Utente
</span>
<img class="img-profile rounded-circle"
src="img/undraw_profile.svg">
</a>
</li>

</ul>

</nav>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">
Dashboard Sensori Ambientali
</h1>

<div class="row">

<!-- Temperatura -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-danger shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">

<div class="col mr-2">
<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
Temperatura
</div>
<div class="h5 mb-0 font-weight-bold text-gray-800">
21 °C
</div>
</div>

<div class="col-auto">
<i class="fas fa-thermometer-half fa-2x text-gray-300"></i>
</div>

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
<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
Umidità
</div>
<div class="h5 mb-0 font-weight-bold text-gray-800">
65 %
</div>
</div>

<div class="col-auto">
<i class="fas fa-tint fa-2x text-gray-300"></i>
</div>

</div>
</div>
</div>
</div>

<!-- MQ135 -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-success shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">

<div class="col mr-2">
<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
Qualità aria (MQ135)
</div>
<div class="h5 mb-0 font-weight-bold text-gray-800">
420 ppm
</div>
</div>

<div class="col-auto">
<i class="fas fa-wind fa-2x text-gray-300"></i>
</div>

</div>
</div>
</div>
</div>

<!-- Stato aria -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-warning shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">

<div class="col mr-2">
<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
Stato qualità aria
</div>
<div class="h5 mb-0 font-weight-bold text-gray-800">
Buona
</div>
</div>

<div class="col-auto">
<i class="fas fa-smile fa-2x text-gray-300"></i>
</div>

</div>
</div>
</div>
</div>

</div>

<div class="row">

<div class="col-xl-12">

<div class="card shadow mb-4">

<div class="card-header py-3">
<h6 class="m-0 font-weight-bold text-primary">
Andamento qualità aria (MQ135)
</h6>
</div>

<div class="card-body">
<canvas id="airChart"></canvas>
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

<script>

var ctx = document.getElementById("airChart");

new Chart(ctx, {

type: 'line',

data: {

labels: ["10:00","11:00","12:00","13:00","14:00","15:00"],

datasets: [
{
label: "MQ135 ppm",
data: [380,400,420,430,410,420],
borderColor: "#1cc88a",
fill:false
}
]

},

options: {
responsive:true,
maintainAspectRatio:false
}

});

</script>

</body>
</html>
```

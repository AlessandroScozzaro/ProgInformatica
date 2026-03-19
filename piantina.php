<script>
if(localStorage.getItem("logged") !== "true"){
window.location.href = "login.php"
}
</script>

<!DOCTYPE html>
<html lang="it">

<head>

<meta charset="utf-8">
<title>Piantina Stanze</title>

<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">
Piantina Stanze
</h1>

<div class="card shadow mb-4">

<div class="card-body">

<canvas id="mappa" width="600" height="400" style="border:1px solid #ccc;"></canvas>

</div>

</div>

</div>

<script>

var canvas = document.getElementById("mappa");
var ctx = canvas.getContext("2d");

ctx.fillStyle="#4e73df";
ctx.fillRect(50,50,200,120);
ctx.fillStyle="white";
ctx.fillText("Laboratorio",100,120);

ctx.fillStyle="#1cc88a";
ctx.fillRect(300,50,150,120);
ctx.fillStyle="white";
ctx.fillText("Ufficio",340,120);

ctx.fillStyle="#f6c23e";
ctx.fillRect(150,200,250,150);
ctx.fillStyle="white";
ctx.fillText("Magazzino",240,280);

</script>

</body>
</html>
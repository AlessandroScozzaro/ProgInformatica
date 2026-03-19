<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">

<head>

<meta charset="utf-8">
<title>Utenti</title>

<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">
Utenti e Contatti
</h1>

<div class="card shadow mb-4">

<div class="card-body">


</div>

</div>

</div>

</body>
</html>
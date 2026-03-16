<script>
if(localStorage.getItem("logged") !== "true"){
window.location.href = "login.php"
}
</script>

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

<table class="table table-bordered">

<tr>
<th>Nome</th>
<th>Email</th>
<th>Telefono</th>
</tr>

<tr>
<td>Mario Rossi</td>
<td>mario@email.com</td>
<td>333123456</td>
</tr>

<tr>
<td>Luca Bianchi</td>
<td>luca@email.com</td>
<td>345987654</td>
</tr>

<tr>
<td>Anna Verdi</td>
<td>anna@email.com</td>
<td>320111222</td>
</tr>

</table>

</div>

</div>

</div>

</body>
</html>
<?php
require_once 'lib/conn.php';

// Prendo tutte le stanze
$stmt = $conn->query("SELECT * FROM stanze");
$stanze = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">

<head>
<meta charset="utf-8">
<title>Gestione Stanze</title>

<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800">
Gestione Stanze
</h1>

<div class="card shadow mb-4">

<div class="card-header">
Elenco Stanze
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Nome stanza</th>
<th>Volume (m³)</th>
</tr>

<?php foreach($stanze as $s): ?>
<tr>
<td><?= htmlspecialchars($s['nome']) ?></td>
<td><?= $s['volumetria'] ?> m³</td>
</tr>
<?php endforeach; ?>

</table>

</div>

</div>

</div>

</body>
</html>

</div>

</div>

</div>

</body>
</html>
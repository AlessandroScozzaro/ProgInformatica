<?php
require_once 'lib/conn.php';

$message = '';
$messageType = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $volumetria = isset($_POST['volumetria']) ? trim($_POST['volumetria']) : '';

    if ($nome === '' || $volumetria === '') {
        $message = 'Compila tutti i campi prima di inviare.';
        $messageType = 'danger';
    } elseif (!is_numeric($volumetria) || floatval($volumetria) <= 0) {
        $message = 'La volumetria deve essere un numero maggiore di 0.';
        $messageType = 'danger';
    } else {
        $volumetria = floatval($volumetria);

        $stmt = $conn->prepare('INSERT INTO stanze (nome, volumetria) VALUES (:nome, :volumetria)');
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':volumetria', $volumetria);

        if ($stmt->execute()) {
            $message = 'Stanza aggiunta con successo.';
            $messageType = 'success';
        } else {
            $message = 'Errore durante l’aggiunta della stanza. Riprova.';
            $messageType = 'danger';
        }
    }
}

// Prendo tutte le stanze (aggiornate dopo eventuale inserimento)
$stmt = $conn->query('SELECT * FROM stanze ORDER BY nome ASC');
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

<h1 class="h3 mb-4 text-gray-800">Gestione Stanze</h1>

<div class="card shadow mb-4">
    <div class="card-header">Aggiungi Nuova Stanza</div>
    <div class="card-body">
        <?php if ($message !== ''): ?>
            <div class="alert alert-<?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="stanze.php">
            <div class="form-group">
                <label for="nome">Nome stanza</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="volumetria">Volume (m³)</label>
                <input type="number" class="form-control" id="volumetria" name="volumetria" step="0.01" min="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Crea Stanza</button>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header">Elenco Stanze</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome stanza</th>
                    <th>Volume (m³)</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($stanze as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['nome']) ?></td>
                    <td><?= htmlspecialchars($s['volumetria']) ?> m³</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</div>

</body>
</html>
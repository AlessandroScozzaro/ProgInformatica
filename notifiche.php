<?php
// notifiche.php

session_start();

// Controllo autenticazione utente
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Connessione al database
require_once 'lib/conn.php'; // Assicurati che questo file contenga la connessione PDO $pdo

$user_id = $_SESSION['user_id'];

// Recupera le notifiche dell'utente
$stmt = $pdo->prepare("SELECT id, messaggio, letto, data_creazione FROM notifiche WHERE utente_id = :user_id ORDER BY data_creazione DESC");
$stmt->execute(['user_id' => $user_id]);
$notifiche = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Segna tutte le notifiche come lette
$pdo->prepare("UPDATE notifiche SET letto = 1 WHERE utente_id = :user_id AND letto = 0")->execute(['user_id' => $user_id]);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Le tue notifiche</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <h1>Notifiche</h1>
    <?php if (empty($notifiche)): ?>
        <p>Non hai notifiche.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($notifiche as $notifica): ?>
                <li style="<?= $notifica['letto'] ? '' : 'font-weight:bold;' ?>">
                    <?= htmlspecialchars($notifica['messaggio']) ?>
                    <small>(<?= date('d/m/Y H:i', strtotime($notifica['data_creazione'])) ?>)</small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <a href="index.php">Torna alla home</a>
</body>
</html>
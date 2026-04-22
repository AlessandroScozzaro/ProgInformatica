<?php
require_once 'lib/conn.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $id_stanza = isset($_POST['stanza']) ? trim($_POST['stanza']) : '';
    $tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : '';
    $unita_misura = isset($_POST['unita_misura']) ? trim($_POST['unita_misura']) : '';
    $soglia_minima = isset($_POST['soglia_minima']) ? trim($_POST['soglia_minima']) : '';
    $soglia_massima = isset($_POST['soglia_massima']) ? trim($_POST['soglia_massima']) : '';

    if ($nome === '' || $id_stanza === '' || $tipo === '' || $unita_misura === '' || $soglia_minima === '' || $soglia_massima === '') {
        $message = 'Compila tutti i campi prima di inviare.';
        $messageType = 'danger';
    } elseif (!is_numeric($soglia_minima) || !is_numeric($soglia_massima)) {
        $message = 'Le soglie devono essere numeri.';
        $messageType = 'danger';
    } elseif (floatval($soglia_minima) >= floatval($soglia_massima)) {
        $message = 'La soglia minima deve essere inferiore alla massima.';
        $messageType = 'danger';
    } else {
        $soglia_minima = floatval($soglia_minima);
        $soglia_massima = floatval($soglia_massima);

        $stmt = $conn->prepare('INSERT INTO dispositivi (nome, id_stanza, tipo, unita_misura, soglia_minima, soglia_massima) VALUES (:nome, :id_stanza, :tipo, :unita_misura, :soglia_minima, :soglia_massima)');
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id_stanza', $id_stanza);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':unita_misura', $unita_misura);
        $stmt->bindParam(':soglia_minima', $soglia_minima);
        $stmt->bindParam(':soglia_massima', $soglia_massima);

        if ($stmt->execute()) {
            $message = 'Dispositivo aggiunto con successo.';
            $messageType = 'success';
        } else {
            $message = 'Errore durante l’aggiunta del dispositivo. Riprova.';
            $messageType = 'danger';
        }
    }
} else {
    header('Location: aggiungiDispositivo.php');
    exit();
}

// Redirect back to aggiungiDispositivo.php with message
header('Location: aggiungiDispositivo.php?message=' . urlencode($message) . '&type=' . $messageType);
exit();
?>
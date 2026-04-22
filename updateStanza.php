<?php
require_once 'lib/conn.php';
session_start();
$id = $_POST['id'];
$nome = $_POST['nome'];
$volumetria = $_POST['volumetria'];
try{
$stmt = $conn->prepare(
    'UPDATE stanze 
    SET nome = :nome, volumetria = :volumetria 
    WHERE id_stanza = :id'
    );
$stmt->bindParam(':volumetria', $volumetria);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':id', $id);
$stmt->execute();
    
} catch (PDOException $e) {
    echo "Errore durante l'aggiornamento del dispositivo.";
    $_SESSION['error'] = "1";
    header('Location: stanze.php');
    exit();
}
echo "Dispositivo aggiornato con successo.";
    header('Location: aggiungiDispositivo.php');
    exit();
?>
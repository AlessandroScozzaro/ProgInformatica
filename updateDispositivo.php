<?php
require_once 'lib/conn.php';
session_start();
$id = $_POST['id'];
$stanza = $_POST['stanza'];
$soglia_massima = $_POST['soglia_massima'];
$soglia_minima = $_POST['soglia_minima'];
$unita_misura = $_POST['unita_misura'];
$nome = $_POST['nome'];
$tipo = $_POST['tipo'];
try{
$stmt = $conn->prepare(
    'UPDATE dispositivi 
    SET id_stanza = :stanza, soglia_massima = :soglia_massima, soglia_minima = :soglia_minima, unita_misura = :unita_misura, nome = :nome, tipo = :tipo 
    WHERE id_dispositivo = :id'
    );
$stmt->bindParam(':stanza', $stanza);
$stmt->bindParam(':soglia_massima', $soglia_massima);
$stmt->bindParam(':soglia_minima', $soglia_minima);
$stmt->bindParam(':unita_misura', $unita_misura);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':tipo', $tipo);
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

<?php
session_start();
require_once 'lib/conn.php';
$email = $_POST['email'];
$password = $_POST['password'];
$stmt = $conn->prepare("SELECT id_utente, ruolo FROM utenti WHERE email = :email AND password = :password");
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $password);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
if($stmt->rowCount() > 0){
    $_SESSION['id'] = $stmt->fetch()['id_utente'];
    $_SESSION['ruolo'] = $stmt->fetch()['ruolo'];
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
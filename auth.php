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
$row = $stmt->fetch();
if($stmt->rowCount() > 0){
    $_SESSION['id'] = $row['id'];
    $_SESSION['ruolo'] = $row['ruolo'];
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
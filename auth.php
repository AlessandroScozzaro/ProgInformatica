<?php
session_start();
require_once 'lib/conn.php';
$email = $_POST['email'];
$password = $_POST['password'];
$stmt = $conn->prepare("SELECT id FROM utenti WHERE email = :email AND password = :password");
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $password);
$stmt->execute();
if($stmt->rowCount() > 0){
    $_SESSION['id'] = $stmt->fetch()['id'];
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
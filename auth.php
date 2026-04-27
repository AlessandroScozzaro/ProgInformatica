<?php
session_start();
require_once 'lib/conn.php';

function sendTelegramMessage($chatId, $message, $apiToken) {
    $url = "https://api.telegram.org/bot$apiToken/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];
    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context  = stream_context_create($options);
    file_get_contents($url, false, $context);
}

$email = $_POST['email'];
$password = $_POST['password'];
$stmt = $conn->prepare("SELECT id_utente, ruolo FROM utenti WHERE email = :email AND password = :password");
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $password);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
if($stmt->rowCount() > 0){
    $user = $stmt->fetch();
    $_SESSION['id'] = $user['id_utente'];
    $_SESSION['ruolo'] = $user['ruolo'];

    // Invia notifica Telegram
    $apiToken = "7695027367:AAERhDILV39iPRRoVO3Ecpv3R2FIdlgLQXQ";
    $chatId = "-5171557407";
    $message = "Nuovo accesso al sistema:\nEmail: " . $email . "\nRuolo: " . $user['ruolo'] . "\nData: " . date('d/m/Y H:i:s');
    sendTelegramMessage($chatId, $message, $apiToken);

    header("Location: index.php");
    exit();
} else {
    header("Location: login.php");
    $_SESSION['error'] = 1;
    exit();
}
?> 
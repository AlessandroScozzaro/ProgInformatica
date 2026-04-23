<?php
$dbname = "prog_inf";
$host = "127.0.0.1";
$username = "root";
$password = "mysql";
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// =============================
// FUNZIONE PER INVIARE TELEGRAM
// =============================
function sendTelegramNotification($chatId, $message, $apiToken) {
    $url = "https://api.telegram.org/bot$apiToken/sendMessage";

    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    @file_get_contents($url, false, $context);
}
?>
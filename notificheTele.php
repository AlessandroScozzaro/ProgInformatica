<?php

// Configurazione Telegram
$apiToken = "7695027367:AAERhDILV39iPRRoVO3Ecpv3R2FIdlgLQXQ";
$chatId   = "-5171557407";

/**
 * Invia un messaggio su Telegram
 */
function sendTelegramMessage($message) {
    global $apiToken, $chatId;

    $url = "https://api.telegram.org/bot{$apiToken}/sendMessage";

    $data = [
        'chat_id' => $chatId,
        'text'    => $message
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);

} 
?>
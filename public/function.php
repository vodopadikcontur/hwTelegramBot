<?php

    require_once ('telegram.php');

    function sendMessage ($chat_id, $message, $updateId) {
        $responseParameters = [
            'chat_id' => $chat_id,
            'text' => $message
        ];
        file_get_contents($sendMessageUri . '?' . http_build_query($responseParameters));
        $requestParameters['offset'] = $updateId;
        sleep(1);
    }
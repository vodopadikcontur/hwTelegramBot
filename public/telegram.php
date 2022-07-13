<?php

    $token = '5596368700:AAG50zkDpQpGoJKZE2xxwm8ugOpZAUjQWow';

    $getUpdatesUri = sprintf('https://api.telegram.org/bot%s/getUpdates', $token);
    $sendMessageUri = sprintf('https://api.telegram.org/bot%s/sendMessage', $token);

    $dataApiPrivat = file_get_contents('https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11');
    $resultApiPrivat = json_decode($dataApiPrivat);

    $requestParameters = [
        'offset' => null
    ];

    while (true) {
        $data = file_get_contents($getUpdatesUri . '?' . http_build_query($requestParameters));
        $response = json_decode($data, true);

        foreach ($response['result'] as $update) {
            $messageUser = $update['message']['text'];
            if ($messageUser == '/start') {
                $responseParameters = [
                    'chat_id' => $update['message']['chat']['id'],
                    'text' => 'Вас приветствует бот для обмены валют, для того, чтобы узнать как работает бот, используете команду /help'
                ];
            } elseif ($messageUser == '/help') {
                $responseParameters = [
                    'chat_id' => $update['message']['chat']['id'],
                    'text' => 'Введите сумму и валют через пробел, например - 100 USD. Доступные валюты - USD, EUR, RUR, BTC'
                ];
            } else {
                $messageUser = explode(' ', $messageUser);
                $summa = $messageUser[0];
                $currency = $messageUser[1];
                foreach ($resultApiPrivat as $item) {
                    if ($item->{'ccy'} === $currency) {
                        $newMoney = $summa * $item->{'buy'};
                        $responseParameters = [
                            'chat_id' => $update['message']['chat']['id'],
                            'text' => $newMoney
                        ];
                    }
                }
            }
            file_get_contents($sendMessageUri . '?' . http_build_query($responseParameters));
            $requestParameters['offset'] = $update['update_id'] + 1;
            sleep(1);
        }
    }




<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
header( "Content-Type: text/html; Charset=utf-8" );
$user_id = 'Administrator';
$password = 'fic2116';
$sub_domain = 'k58kx';
// 送信ヘッダーを作る
$opts = array(
    'http' => array(
        'method' => 'GET',
        'header' => 'X-Cybozu-Authorization:' . base64_encode($user_id . ':' . $password) . '\r\n'
        //'header' => 'X-Cybozu-API-Token:'. 'Z91Ore3G35l0Mjq1B0FH7VqcOtElVgebJDxfv02w' .'\r\n'
    )
);
$context = stream_context_create($opts);
$contents = file_get_contents('https://' . $sub_domain . '.cybozu.com/k/v1/record.json?app=8&id=1', false, $context);
if (!$contents) {
    die('Error');
    //echo('Error');
}




require_once('./LINEBotTiny.php');



$channelAccessToken = 'KR5/LV6k4zm8mZpaw6U1fM8Isx6U+MzkgIH0EuMdYvlr8bAD2UK8uQ0aS5Q/Kn6OTgw8vxRXsYN4D9Hu0eT61tbDJdt/T7wGwY5VVLajSijR6F9X5yHT1GmDqc5HpNp57Bof/IDvzSDKj1WUmf5BCAdB04t89/1O/w1cDnyilFU=';
$channelSecret = '04106210be1640325f6f8b23e03a4506';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $message['text']//json_decode($contents)['record']['$id'] //
                            )
                        )
                    ));
                    break;
                default:
                    error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};

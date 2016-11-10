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

require_once('./LINEBotTiny.php');
include_once('./ncmb-client.php');

$channelAccessToken = 'KR5/LV6k4zm8mZpaw6U1fM8Isx6U+MzkgIH0EuMdYvlr8bAD2UK8uQ0aS5Q/Kn6OTgw8vxRXsYN4D9Hu0eT61tbDJdt/T7wGwY5VVLajSijR6F9X5yHT1GmDqc5HpNp57Bof/IDvzSDKj1WUmf5BCAdB04t89/1O/w1cDnyilFU=';
$channelSecret = '04106210be1640325f6f8b23e03a4506';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$ncmb_client = new NCMBClient("f32e333ff28afabef1915e457c432bc7271180a4d5f0645f3775643543f32d40","097090d9da0e0e3434948709a8732a594ca5567549bc4870af8d18ba8dfe62f9");
$result_user = null;

foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    $query_string = http_build_query(
                        array('where' => json_encode(array(
                            'lineID' => $message['text'] ,
                        )))
                    );
                    
                    $search_results_string = $ncmb_client->get('/users?' . $query_string); // usersには/classes付けない
                    $search_results = json_decode($search_results_string, true);
                    if (count($search_results['results']) == 0) {
                        $result_user = 'あなたは誰ですか？'."/n";
                        $result_user .= 'LINE id:'.$message['text']."/n";
                    } else {
                        $user_info = $search_results['results'][0];
                        $selected_user = $user_info['userNameKanji'];
                        $result_user =  $selected_user.'さん';
                    }
                    
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $result_user //$message['text']
                            )
                        )
                    ));
                    break;
                case 'location':
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => "あなたの居場所はそこですね"
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

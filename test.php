<?php
header( "Content-Type: text/html; Charset=utf-8" );
//echo "Hello World!¥n";
$user_id = 'Administrator';
$password = 'fic2116';
$sub_domain = 'k58kx';

//echo "Hello World!¥n";

// 送信ヘッダーを作る
$opts = array(
    'http' => array(
        'method' => 'GET',
        'header' => 'X-Cybozu-API-Token:'. 'Z91Ore3G35l0Mjq1B0FH7VqcOtElVgebJDxfv02w' .'\r\n'
        
    )
);
$context = stream_context_create($opts);
//echo $opts['http']['methods'];
//echo $context;
//echo "Hello World!¥n";

// APIを叩く
$contents = file_get_contents('https://' . $sub_domain . '.cybozu.com/k/v1/records.json?app=3', false, $context);
if (!$contents) {
    die('Error');
    //echo('Error');
}

//echo "Hello World!¥n";

var_dump(json_decode($contents));
print_r(json_decode($contents));

?>

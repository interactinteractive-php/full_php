<?php

$jsonBody = file_get_contents('php://input');
$data = json_decode($jsonBody, true);
$jsonEncode = json_encode($data['param']);
$encryptString = openssl_encrypt(html_entity_decode($jsonEncode, ENT_QUOTES), "AES-128-ECB", $data['key']);
echo $encryptString;
exit;
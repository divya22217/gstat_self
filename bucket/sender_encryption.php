<?php

 ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function jwt_token($id, $filing_no) { 
    require_once 'bootstrap.php';
    $secret = getenv('SECRET');
 
    $header = json_encode([
        'typ' => 'JWT',
        'alg' => 'HS256'
    ]);
    
    $payload = json_encode([
        'doc_id' => $id,
        'filing_no' => $filing_no
    ]);

    $base64UrlHeader = base64UrlEncode($header);
    $base64UrlPayload = base64UrlEncode($payload);

    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

    $base64UrlSignature = base64UrlEncode($signature);

    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    
    return $jwt;
}


function jwt_decode($jwt) {
    require_once 'bootstrap.php';
    $secret = getenv('SECRET');
    
    list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);

    $header = json_decode(base64UrlDecode($base64UrlHeader));
    $payload = json_decode(base64UrlDecode($base64UrlPayload));

    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

    $base64UrlSignatureCheck = base64UrlEncode($signature);
    
    if ($base64UrlSignatureCheck !== $base64UrlSignature) {
        throw new Exception('Invalid token signature');
    }
    
    return $payload;
}



$encrypted_data =  jwt_token('423147','2025107201000009');

echo $encrypted_data."<br/><br/><br/><br/><br/>";



$decrypted_data =  jwt_decode($encrypted_data);

print_R( $decrypted_data);

print_r($decrypted_data->filing_no);

die;

?>

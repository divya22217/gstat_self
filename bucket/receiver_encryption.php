<?php

 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function jwt_token($id, $filing_no) { 
    require_once 'bootstrap.php';
    $secret = getenv('SECRET');
    //$salt = bin2hex(random_bytes(16));  // 16 bytes will give a 32-character hex string

    // Create the JWT header
    $header = json_encode([
        'typ' => 'JWT',
        'alg' => 'HS256'
    ]);
    
    // Create the payload with salt
    $payload = json_encode([
        'doc_id' => $id,
        'filing_no' => $filing_no
       // 'salt' => $salt  // Add salt to the payload
    ]);

    // Base64 URL encode the header and payload
    $base64UrlHeader = base64UrlEncode($header);
    $base64UrlPayload = base64UrlEncode($payload);

    // Create the signature by hashing the header and payload (salt is part of payload)
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

    // Base64 URL encode the signature
    $base64UrlSignature = base64UrlEncode($signature);

    // Construct the JWT token
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    
    return $jwt;
}


function jwt_decode($jwt) {
    require_once 'bootstrap.php';
    $secret = getenv('SECRET');
    
    // Split the JWT into its components (header, payload, signature)
    list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);

    // Decode the Base64Url components
    $header = json_decode(base64UrlDecode($base64UrlHeader));
    $payload = json_decode(base64UrlDecode($base64UrlPayload));

    // Extract the salt from the payload (used for signature generation)
    //$salt = $payload->salt;
    
    // Recalculate the signature using the header and payload (salt is part of payload)
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

    // Base64 URL encode the recalculated signature
    $base64UrlSignatureCheck = base64UrlEncode($signature);

    // Compare the calculated signature with the provided signature
    if ($base64UrlSignatureCheck !== $base64UrlSignature) {
        throw new Exception('Invalid token signature');
    }
    
    // Return the decoded payload if the signature is valid
    return $payload;
}



$encrypted_data =  jwt_token('12123445','2025098789760003');

echo $encrypted_data."<br/>";

//$encrypted_data = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkb2NfaWQiOiIxMjEyMzQiLCJmaWxpbmdfbm8iOiIyMDI1MDk4Nzg5NzYwMDAzIiwic2FsdCI6IjU2ODYwMzFkMTExZDIyYjY0ZDYyYmIwM2FhZjNlZmI1In0.e-BRglHnXrCeMeBvp4pZhK9ya5X9ueenobhMPfffyfY";

$decrypted_data =  jwt_decode($encrypted_data);

print_R( $decrypted_data);

die;

?>

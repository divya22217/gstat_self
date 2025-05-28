<?php
//error_reporting(0);
date_default_timezone_set("Asia/Kolkata");

function jwt_token($id,$username) { 
    require './bootstrap.php';
        $secret = getenv('SECRET');
        $header = json_encode([
        'typ' => 'JWT',
        'alg' => 'HS256'
        ]);
        $selectedTime = strtotime(date('H:i:s'));
        $endTime = strtotime("+120 minutes",$selectedTime);
		
        $payload = json_encode([
            'id' => $id,
            'sub' => $username,
            'iat' => $selectedTime,
            'exp' =>$endTime,
        ]);
    $base64UrlHeader = base64UrlEncode($header);
    $base64UrlPayload = base64UrlEncode($payload);
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSignature = base64UrlEncode($signature);
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    return  $jwt;
}

//echo jwt_token('102','ravi');
?>
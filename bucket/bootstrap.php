<?php

require '../vendor_jwt/autoload.php';
use Dotenv\Dotenv;
$dotenv = new DotEnv(__DIR__);
$dotenv->load();
function base64UrlEncode($text)
{
    return str_replace(
        ['+', '/', '='],
        ['-', '_', ''],
        base64_encode($text)
    );
}

function base64UrlDecode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

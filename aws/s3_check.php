<?php

function listBuckets($accessKey, $secretKey, $endpoint, $region)
{
    // Generate the date
    $date = gmdate('Ymd\THis\Z');
    $shortDate = gmdate('Ymd');

    // Build the Canonical Request
    $method = "GET";
    $canonicalUri = "/";
    $canonicalQueryString = "";
    $canonicalHeaders = "host:{$endpoint}\nx-amz-date:{$date}\n";
    $signedHeaders = "host;x-amz-date";
    $payloadHash = hash('sha256', '');

    $canonicalRequest = "{$method}\n{$canonicalUri}\n{$canonicalQueryString}\n{$canonicalHeaders}\n{$signedHeaders}\n{$payloadHash}";

    // Build the String to Sign
    $algorithm = "AWS4-HMAC-SHA256";
    $credentialScope = "{$shortDate}/{$region}/s3/aws4_request";
    $stringToSign = "{$algorithm}\n{$date}\n{$credentialScope}\n" . hash('sha256', $canonicalRequest);

    // Calculate the Signature
    $kSecret = "AWS4{$secretKey}";
    $kDate = hash_hmac('sha256', $shortDate, $kSecret, true);
    $kRegion = hash_hmac('sha256', $region, $kDate, true);
    $kService = hash_hmac('sha256', "s3", $kRegion, true);
    $kSigning = hash_hmac('sha256', "aws4_request", $kService, true);
    $signature = hash_hmac('sha256', $stringToSign, $kSigning);

    // Build the Authorization Header
    $authorization = "{$algorithm} Credential={$accessKey}/{$credentialScope}, SignedHeaders={$signedHeaders}, Signature={$signature}";

    // Make the HTTP request
    $url = "https://{$endpoint}/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: {$authorization}",
        "x-amz-date: {$date}",
        "Host: {$endpoint}",
    ]);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return "cURL Error: " . curl_error($ch);
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        return "Connection successful. Buckets: " . $response;
    } else {
        return "Failed to connect. HTTP Code: {$httpCode}, Response: {$response}";
    }
}

// Example usage
$accessKey = "56f55cde1c45468c9128e011df03ff23";
$secretKey = "73e7fe4f4e404b938a2c497574e7ef07";
$endpoint = "10.193.41.10:13808"; // e.g., 127.0.0.1:9000 or s3.amazonaws.com
$region = "BBSR-NO-REP"; // or your region

echo listBuckets($accessKey, $secretKey, $endpoint, $region);

<?php

class S3Uploader
{
    private $accessKey;
    private $secretKey;
    private $region;
    private $bucketName;
    private $hostBase;

    public function __construct($config)
    {
        $this->accessKey = $config['aws']['access_key'];
        $this->secretKey = $config['aws']['secret_key'];
        $this->region = $config['aws']['region'];
        $this->bucketName = $config['s3']['bucket_name'];
        $this->hostBase = $config['s3']['host_base']; // Local host and port
    }

    public function uploadPDF($filePath, $keyName)
    {
        // Check if file exists
        if (!file_exists($filePath)) {
            return "File does not exist: {$filePath}";
        }

        // Get the file content and content type
        $fileContent = file_get_contents($filePath);
        $fileSize = filesize($filePath);
        $fileMimeType = mime_content_type($filePath);

        if ($fileMimeType !== 'application/pdf') {
            return "Invalid file type. Only PDF files are allowed.";
        }

        // Generate the endpoint URL using region
        $host = "{$this->hostBase}";
        $url = "http://{$host}/{$this->bucketName}/{$keyName}";

        // Create the request date
        $date = gmdate('Ymd\THis\Z'); // ISO 8601 format
        $shortDate = gmdate('Ymd');

        // Create the canonical string for signing
        $canonicalRequest = "PUT\n/{$this->bucketName}/{$keyName}\n\nhost:{$host}\n\nhost\nUNSIGNED-PAYLOAD";
        $stringToSign = "AWS4-HMAC-SHA256\n{$date}\n{$shortDate}/{$this->region}/s3/aws4_request\n" . hash('sha256', $canonicalRequest);

        // Generate the signing key
        $kDate = hash_hmac('sha256', $shortDate, "AWS4{$this->secretKey}", true);
        $kRegion = hash_hmac('sha256', $this->region, $kDate, true);
        $kService = hash_hmac('sha256', 's3', $kRegion, true);
        $signingKey = hash_hmac('sha256', 'aws4_request', $kService, true);

        // Generate the signature
        $signature = hash_hmac('sha256', $stringToSign, $signingKey);

        // Authorization header
        $authorization = "AWS4-HMAC-SHA256 Credential={$this->accessKey}/{$shortDate}/{$this->region}/s3/aws4_request, SignedHeaders=host, Signature={$signature}";

        // Initialize cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Host: {$host}",
            "X-Amz-Date: {$date}",
            "Authorization: {$authorization}",
            "Content-Type: {$fileMimeType}",
            "Content-Length: {$fileSize}",
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Check response
        if ($httpCode === 200) {
            return "PDF file uploaded successfully: {$url}";
        } else {
            return "Error uploading PDF file. HTTP Code: {$httpCode}. Response: {$response}";
        }
    }
}

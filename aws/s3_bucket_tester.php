<?php

class S3BucketTester
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
        $this->hostBase = $config['s3']['host_base'];
    }

    public function testBucketConnection()
    {
        // Generate the URL for the bucket
        $url = "http://{$this->hostBase}/{$this->bucketName}/";

        // Create the request date
        $date = gmdate('Ymd\THis\Z'); // ISO 8601 format
        $shortDate = gmdate('Ymd');

        // Create the canonical string for signing
        $canonicalRequest = "GET\n/{$this->bucketName}/\n\nhost:{$this->hostBase}\n\nhost\nUNSIGNED-PAYLOAD";
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
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Host: {$this->hostBase}",
            "X-Amz-Date: {$date}",
            "Authorization: {$authorization}",
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Check response
        if ($httpCode === 200) {
            return "Bucket is accessible. Response: {$response}";
        } else {
            return "Error connecting to bucket. HTTP Code: {$httpCode}. Response: {$response}";
        }
    }
}

// Configuration
$config = [
    'aws' => [
        'access_key' => '56f55cde1c45468c9128e011df03ff23',
        'secret_key' => '73e7fe4f4e404b938a2c497574e7ef07',
        'region' => 'INDIA',
    ],
    's3' => [
        'bucket_name' => 'Object-UAT',
        'host_base' => '10.193.41.10:13808',
    ],
];

// Instantiate and test the bucket
$bucketTester = new S3BucketTester($config);
echo $bucketTester->testBucketConnection();

<?php

// Include the Composer autoloader
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Initialize the S3 client with your credentials
$s3Client = new S3Client([
    'region' => 'INDIA',  // Region for Mumbai (India)
    'version' => 'latest',     // Use the latest version of the API
    'credentials' => [
        'key'    => '56f55cde1c45468c9128e011df03ff23',
        'secret' => '73e7fe4f4e404b938a2c497574e7ef07',
    ],
    'endpoint' => 'https://10.193.41.10:13808', // HTTPS endpoint for your S3-compatible service
    'use_path_style_endpoint' => true,          // Enable path-style endpoints for S3-compatible storage
    'http' => [
        'verify' => false, // Path to the custom CA certificate
        'timeout' => 120,        // Timeout for the request
        'connect_timeout' => 60, // Timeout for the connection
    ],
    'debug' => true, // Enable debug mode
]);

try {
    // Upload a file to S3
    $result = $s3Client->putObject([
        'Bucket' => 'Object-UAT',   // Replace with your S3 bucket name
        'Key'    => 'upload/testfile.pdf', // S3 key (path + file name)
        'SourceFile' => 'testfile.pdf', // Local file path
        'ACL'    => 'public-read',   // You can adjust this according to your requirements
    ]);

    echo "File uploaded successfully. File URL: " . $result['ObjectURL'];

} catch (AwsException $e) {
    // Output error message if fails
    echo "Error uploading file: " . $e->getMessage();
}

?>


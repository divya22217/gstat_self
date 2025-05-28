<?php

function checkS3Connection($bucketName, $endpoint = null)
{
    // Set environment variables for AWS credentials if required
    $accessKey = '56f55cde1c45468c9128e011df03ff23'; // or set manually
    $secretKey = '73e7fe4f4e404b938a2c497574e7ef07'; // or set manually

    // Construct the s3cmd command
    $command = "AWS_ACCESS_KEY_ID={$accessKey} AWS_SECRET_ACCESS_KEY={$secretKey} s3cmd ls";

    // Append the custom endpoint if provided
    if ($endpoint) {
        $command .= " --host={$endpoint} --host-bucket={$endpoint}";
    }

    // Execute the command
    $output = shell_exec($command);

    // Check the output for success or failure
    if (strpos($output, $bucketName) !== false) {
        return "Connection successful. Bucket '{$bucketName}' found.";
    } elseif (strpos($output, 'Access Denied') !== false) {
        return "Access denied. Check your credentials or permissions.";
    } else {
        return "Failed to connect to the bucket. Output: {$output}";
    }
}

// Example Usage
$bucketName = 'Object-UAT';
$endpoint = '10.193.41.10:13808'; // Custom endpoint, e.g., MinIO

echo checkS3Connection($bucketName, $endpoint);

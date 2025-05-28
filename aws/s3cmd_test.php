<?php

function uploadFileToS3($filePath, $bucketName, $keyName)
{
    // Check if file exists
    if (!file_exists($filePath)) {
        return "Error: File does not exist: {$filePath}";
    }

    // S3 destination path
    $s3Path = "s3://{$bucketName}/{$keyName}";

    // Build the s3cmd command
    $command = escapeshellcmd("s3cmd put {$filePath} {$s3Path}");

    // Execute the command
    $output = shell_exec($command);

    // Check the result
    if (strpos($output, '100%') !== false) {
        return "File uploaded successfully to: {$s3Path}";
    } else {
        return "Error uploading file. Output: {$output}";
    }
}

// Example usage
$filePath = __DIR__ . '/PassUndertaking1.pdf'; // Local file path
$bucketName = 'Object-UAT'; // S3 bucket name
$keyName = 'gstat/uploads/PassUndertaking1.pdf'; // Key (path) in the bucket

echo uploadFileToS3($filePath, $bucketName, $keyName);

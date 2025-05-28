<?php
// $config = require 'config.php';

// require 's_3_uploader.php';

// $s3Uploader = new S3Uploader($config);
// $filePath = __DIR__ . '/PassUndertaking.pdf'; // Path to your PDF file
// $keyName = 'uploads/PassUndertaking.pdf';         // Key name in the bucket

// echo $s3Uploader->uploadPDF($filePath, $keyName);


// Include the config file
$config = require 'config.php';

// Include the S3Uploader class
require 's_3_uploader.php';


// Source file path
$sourceFilePath = __DIR__ . '/PassUndertaking.pdf'; // Original file path
$tmpDir = '/tmp/'; // Temporary directory
$tmpFilePath = $tmpDir . basename($sourceFilePath); // File path in tmp directory

// Ensure the tmp directory exists with proper permissions
if (!is_dir($tmpDir)) {
    mkdir($tmpDir, 0777, true); // Create tmp directory if it doesn't exist
}

// Move the file to the tmp directory
if (!copy($sourceFilePath, $tmpFilePath)) {
    die("Failed to move file to tmp directory.");
}

// Set appropriate permissions for the file in the tmp directory
chmod($tmpFilePath, 0775); // Set file permissions (read/write for owner, read for others)


// Instantiate the S3Uploader class
$s3Uploader = new S3Uploader($config);

// Define the S3 key name
$keyName = 'gstat/uploads/' . basename($sourceFilePath); // Path in the S3 bucket

// Upload the file to S3
$result = $s3Uploader->uploadPDF($tmpFilePath, $keyName);

// Clean up the temporary file after upload
unlink($tmpFilePath);

// Output the upload result
echo $result;

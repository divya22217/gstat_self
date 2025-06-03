<?php
require_once('./S3Service.php');

try {
    // Instantiate the S3Service (bucket name is fixed inside the class)
    $s3Service = new S3Service();

    // Local file path
    $filePath = 'testfile.pdf';
    
    // Check if file exists before uploading
    if (!file_exists($filePath)) {
        throw new Exception("Error: File '$filePath' does not exist.");
    }

    // S3 key (path + file name)
    $s3Key = 'upload/testfile.pdf';

    // Attempt to upload the file
    $fileUrl = $s3Service->uploadFile($filePath, $s3Key);

    if ($fileUrl) {
        echo "File uploaded successfully. File URL: ";
    } else {
        throw new Exception("Error: File upload failed.");
    }
} catch (Exception $e) {
    // Log or display the error message
    echo "An error occurred: ";
}

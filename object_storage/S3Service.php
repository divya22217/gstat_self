<?php

// Include the Composer autoloader
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3Service
{
    private $s3Client;
    private $bucketName = 'Object-UAT'; // Fixed bucket name

    public function __construct()
    {
        // Initialize the S3 client with your credentials
        $this->s3Client = new S3Client([
            'region' => 'INDIA',  // Region for Mumbai (India)
            'version' => 'latest', // Use the latest version of the API
            'credentials' => [
                'key'    => '56f55cde1c45468c9128e011df03ff23',
                'secret' => '73e7fe4f4e404b938a2c497574e7ef07',
            ],
            'endpoint' => 'https://10.193.41.10:13808', // HTTPS endpoint for your S3-compatible service
            'use_path_style_endpoint' => true, // Enable path-style endpoints for S3-compatible storage
            'http' => [
                'verify' => false, // Path to the custom CA certificate
                'timeout' => 120, // Timeout for the request
                'connect_timeout' => 60, // Timeout for the connection
            ],
            'debug' => false, // Enable debug mode
        ]);
    }

    /**
     * Uploads a file to the S3 bucket.
     *
     * @param string $filePath Local path to the file.
     * @param string $key S3 key (path + file name).
     * @return string|null URL of the uploaded file or null on failure.
     */
    public function uploadFile(string $filePath, string $key): bool
    {
        try {
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucketName,
                'Key'    => $key,
                'SourceFile' => $filePath,
                'ACL'    => 'public-read', // Adjust this according to your requirements
            ]);

            return true ?? false;
        } catch (AwsException $e) {
           echo "Error uploading file: " . $e->getMessage();
            return false;
        }
    }


    public function uploadDynamicFile(string $pdfContent, string $key): bool
    {
        try {
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucketName,
                'Key'    => $key,
                'Body'   => $pdfContent,
                'ContentType' => 'application/pdf',
                'ACL'    => 'public-read', // Adjust the permissions as needed
            ]);
            return true ?? false;
        } catch (AwsException $e) {
            // echo "Error uploading file: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retrieves a file URL from the S3 bucket.
     *
     * @param string $key S3 key (path + file name).
     * @return string|null URL of the file or null if not found.
     */
    public function getFile($key)
    {
        try {
            $result = $this->s3Client->getObjectUrl($this->bucketName, $key);
            return $result ?? null;
        } catch (AwsException $e) {
            echo "Error retrieving file: " . $e->getMessage();
            return null;
        }
    }
   public function streamPdfFromS3(string $s3Key)
    {
        try {
            // Fetch the PDF file from S3
            $result = $this->s3Client->getObject([
                'Bucket' => $this->bucketName,
                'Key'    => $s3Key,
            ]);
            // Ensure the response contains the file body
            if (!isset($result['Body'])) {
                throw new Exception("Empty response from S3 for key: " . $s3Key);
            }

            // Set headers to stream PDF
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=\"" . basename($s3Key) . "\"");
            header("Content-Length: " . $result['ContentLength']);

            // Output the PDF content
            echo $result['Body'];
            exit;
        } catch (Aws\Exception\AwsException $e) {
		// Handle AWS SDK errors
		echo $e->getMessage();
            error_log('AWS S3 Error: ' . $e->getMessage());
            http_response_code(500);
            echo "Error: Unable to retrieve the PDF file.";
            exit;
        } catch (Exception $e) {
            // Handle other exceptions
            error_log('General Error: ' . $e->getMessage());
            http_response_code(500);
            echo "Error: Something went wrong while fetching the file.";
            exit;
        }
}
     public function getFileSizeFromS3($key) {
       $key= urldecode($key);
        try {
            $result = $this->s3Client->getObject([
                'Bucket' => $this->bucketName,
                'Key'    => $key,
            ]);

            $fileSize = $result['ContentLength'];

            if ($fileSize >= 1048576) {
                return round($fileSize / 1048576, 2) . ' MB';
            } elseif ($fileSize >= 1024) {
                return round($fileSize / 1024, 2) . ' KB';
            } else {
                return $fileSize . ' bytes';
            }
        } catch (Aws\Exception\AwsException $e) {
            return 'File not found or inaccessible: ' . $e->getMessage();
	}
     }

}


<?php
require_once('./S3Service.php');

// Instantiate the S3Service (bucket name is fixed inside the class)
$s3Service = new S3Service();
$path = "/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/apl02A/2025107201000004/2025107201000004-apl02A-1738221896.pdf";
//$path = "/aaa.pdf";
$s3Service->streamPdfFromS3("/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/apl02A/2025107201000009/2025107201000009-apl02A-1738655534.pdf");
?>

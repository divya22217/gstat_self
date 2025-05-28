<?php
require_once('../object_storage/S3Service.php');
$path = $_REQUEST['path'];
$path = urldecode($_REQUEST['path']);
if (!empty($path) && (strstr($path, 'Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/') || strstr($path, 'Efile_Document/GSTAT_Documents/ncltdoc/casedoc/'))) {
	$path = urldecode($path);
	$s3Service = new S3Service();
	$s3Service->streamPdfFromS3($path);
} else{
	//echo "not found";
$s3Service = new S3Service();
            $url1 = $s3Service->streamPdfFromS3("Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/restored_case/2025307201000174/1745227748109012173268060fe4c4aa1.pdf");
}

//require_once('./S3Service.php');

// Instantiate the S3Service (bucket name is fixed inside the class)
//$s3Service = new S3Service();
//$path = "/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/apl02A/2025107201000004/2025107201000004-apl02A-1738221896.pdf";
//$path = "/aaa.pdf";
//$s3Service->streamPdfFromS3("/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/apl02A/2025107201000004/2025107201000004-apl02A-1738221896.pdf");
?>

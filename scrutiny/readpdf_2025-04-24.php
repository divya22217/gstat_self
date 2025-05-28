<?php
require_once('../object_storage/S3Service.php');
$path = $_REQUEST['path'];

$path = urldecode($_REQUEST['path']);
//print_r($path);die;
if (!empty($path) && (strstr($path, 'Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/') || strstr($path, 'Efile_Document/gstdoc/casedoc/'))) {
	$path = urldecode($_REQUEST['path']);
	$s3Service = new S3Service();
	$url1 =$s3Service->streamPdfFromS3($path);
	
} else{
	
	$s3Service = new S3Service();
	$s3Service->streamPdfFromS3("Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/restored_case/2025307201000174/1745227748109012173268060fe4c4aa1.pdf");

}
?>

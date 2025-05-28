<?php 
require_once('object_storage/S3Service.php');
$path = $_REQUEST['path'];
$path = urldecode(base64_decode($path));
if (!empty($path) && strstr($path, '/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/orders/')) {
	$s3Service = new S3Service();
	//$s3Service->streamPdfFromS3($path); 
	//$s3Service->streamPdfFromS3('//Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/orders/2025107101000005/834113napa_4_2025_12_02_2025.pdf');
	$s3Service->streamPdfFromS3('/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/defects/2025107201000016/2025107201000016-1739377228.pdf');
} else { 
  echo 'No documnt';
}


?> 


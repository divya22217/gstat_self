<?php
require_once('../object_storage/S3Service.php');
$path = $_REQUEST['path'];

$path = urldecode($_REQUEST['path']);

if (!empty($path) && (strstr($path, 'Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/') || strstr($path, 'Efile_Document/gstdoc/casedoc/'))) {
	$path = urldecode($_REQUEST['path']);
	$s3Service = new S3Service();
	$url1 =$s3Service->streamPdfFromS3($path);

	
} else{
	$domain=rtrim($base_url, '/');           
	$url1=$domain."/gstat/superindex/api/01DSC-Signed-APL05001_1745382399891.pdf";
   
}
?>

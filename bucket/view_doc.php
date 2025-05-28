<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once('../db_inc1.php');
require_once('../object_storage/S3Service.php');

function jwt_decode($jwt) {
require_once 'bootstrap.php';
    $secret = getenv('SECRET');
    
    list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);

    $header = json_decode(base64UrlDecode($base64UrlHeader));
    $payload = json_decode(base64UrlDecode($base64UrlPayload));

    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

    $base64UrlSignatureCheck = base64UrlEncode($signature);
    
    if ($base64UrlSignatureCheck !== $base64UrlSignature) {
        throw new Exception('Invalid token signature');
    }
    
    return $payload;
}

$data = $_POST['data'];
$decrypted_data =  jwt_decode($data);
$doc_id = $decrypted_data->doc_id;
$filing_no = $decrypted_data->filing_no;


$query = "select fileupload from document_upload where documentuploadmodelid = ? and filing_no = ?";
$filepath_query=$db->prepare($query);
$filepath_query->bindParam(1, $doc_id, PDO::PARAM_STR);
$filepath_query->bindParam(2, $filing_no, PDO::PARAM_STR);
$filepath_query->execute();
$filepath = $filepath_query->fetchColumn();


if (!empty($filepath) && (strstr($filepath, '/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/') || strstr($filepath, '/Efile_Document/GSTAT_Documents/ncltdoc/casedoc/'))) {
	$s3Service = new S3Service();
	$s3Service->streamPdfFromS3($filepath);
} else{
echo "not found";
}

 ?>

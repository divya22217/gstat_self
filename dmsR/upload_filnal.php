<?php
$target_dir = "c:/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
   $file_type = array('application/pdf');
    if($file_type !== $_FILES['fileToUpload']['type']) {
        echo "File is an image - " . $_FILES['fileToUpload']['type'] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
$mime_array = array('pdf','jpg','png','jpeg','gif');
if(!in_array($imageFileType,$mime_array)) {
    echo "Sorry, only JPG, JPEG, PNG ,PDF & GIF files are allowed. ". $imageFileType;
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
$fullflepath = $target_file;
$dairyno = "1010000017672018";//set the dynamic enique diaryno.
//$filePath = "C:/ncltdoc/casedoc/1010000017672018/04/Order-Challenge in NCLT/04_order-Challange_004_15343493483.pdf";
$filePath = "/04_order-Challange_004_15343493483.pdf";
$j_key = "vVl/Az1yGsjOAG18WDeScg==";
$j_securityKey = "TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip/Q8Wns=";
$upload_url = 'http://164.100.59.89:8080/dms-ecourt/cis-order-document-uploading';
//$upload_url = 'http://efiling.nclt.gov.in:8080/dms-ecourt2/cis-order-document-uploading';
$fields = array[
    'files' => new \CurlFile($fullflepath, 'application/octet-stream', $fullflepath),
	'dairyno'=>$dairyno,
    'filePath'=>$filePath,
    'j_key'=>$j_key,
    'j_securityKey'=>$j_securityKey
]; 

$ch = curl_init();

curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_URL, $upload_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);  
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data')); 

$response = curl_exec($ch);
echo $response;
echo $fullflepath;
curl_close($ch);
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
 
?>
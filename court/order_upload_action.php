<?php
//print_r($_REQUEST);die;
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

function get_full_judge_name($judge_code, $db, $schemas){
$sql="select judge_name from $schemas.master_judge where judge_code=?";
$sth = $db->prepare($sql);
$sth->bindParam(1, $judge_code, PDO::PARAM_INT);
$sth->execute();
$jname = $sth->fetchColumn();

$sql="select judge_desg_code from $schemas.master_judge where judge_code=?";
$sth = $db->prepare($sql);
$sth->bindParam(1, $judge_code, PDO::PARAM_INT);
$sth->execute();
$jdc = $sth->fetchColumn();

$sql="select desg_name from $schemas.master_desg where desg_code=?";
$sth = $db->prepare($sql);
$sth->bindParam(1, $jdc, PDO::PARAM_INT);
$sth->execute();
$jdn = $sth->fetchColumn();

return $judge=$jname.'('.$jdn.')'.','.$judge;
}
//print_r($_REQUEST);

//print_r($_FILES);
$original_name = $_FILES['userfile']['name'];
$_FILES['userfile']['name'] = '04_order-Challange_004_'.$_FILES['userfile']['name'];
//print_r($_FILES);die;
session_start();
ob_start();
//$username= $_SESSION['district_name'];
//include("../Delhi/services/db_inc.php");//tdsatcisdb
include("../db_inc1.php");
include("../db_inc2.php");
$filing_no=$_REQUEST['filing_no'];
 $server_date= date('Y-m-d H:i:s'); //Returns IST
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

/*public function uploadToDms(){
	die('you are in my func');
}*/

$sthr=$dbonline->prepare("select * from e_case_detail  where filing_no=? ");
  $sthr->bindParam(1, $filing_no, PDO::PARAM_STR);
  $sthr->execute();
  while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	  $applno=$rowa['unique_id_no'];
  	  
  } 

if(strlen($filing_no)==16)
{

$case_no_send=$_REQUEST['case_no_send'];
$filing_no=$_REQUEST['filing_no'];
$case_type_send=$_REQUEST['case_type_send'];
$case_year_send=$_REQUEST['case_year_send'];
$dt_of_filing=$_REQUEST['dt_of_filing'];
$pet_name=$_REQUEST['pet_name'];
$res_name=$_REQUEST['res_name'];

//judge_code=$_REQUEST['judge_code'];

$remarks=$_REQUEST['remarks'];
$status=$_REQUEST['status'];
$userfile=$_REQUEST['userfile'];
$order_type=$_REQUEST['order_type'];

 $file = $_FILES['userfile'];
//print_r($file);
//die();
$name = $file['name'];

if($dt_of_filing=='') $dt_of_filing='1111-11-11';


$cas_no=ltrim($case_no_send,0);

$ds="select max(order_id) as order_id from $schemas.order_detail ";
$st = $db->prepare($ds);
$st->execute();
$jno_no=$st->fetchColumn();

if($jno_no == "" or $jno_no==0 or $jno_no =='NULL')
{
	$jno_no = 1;
}
else
{
	$jno_no = $jno_no + 1;
}

if($order_type == 'I'){
$order_date=$_REQUEST['order_date'];
$doc_type = "INTERIM-ORDER-'$order_date'";
}
else if($order_type == 'F'){
	$order_date=$_REQUEST['judge_date'];
	$doc_type = "FINAL-ORDER-'$order_date'";
}

list($day,$month,$year)=explode('/',$order_date);
$order_date_new=$year.'-'.$month.'-'.$day;

switch ($month)
{
	case 1:	$month_name="January";
       		 break;

	case 2:	$month_name="February";
       		 break;

	case 3:	$month_name="March";
       		 break;

	case 4:	$month_name="April";
       		 break;

	case 5:	$month_name="May";
       		 break;

	case 6:	$month_name="June";
       		 break;

	case 7:	$month_name="July";
       		 break;

	case 8:	$month_name="August";
       		 break;

	case 9:	$month_name="September";
       		 break;

	case 10: $month_name="October";
       		 break;

	case 11: $month_name="November";
       		 break;

	case 12: $month_name="December";
       		 break;

}

	  $pat =$_SERVER['DOCUMENT_ROOT'].'/nclt';
	   //$pat =$_SERVER['SERVER_NAME']."/"."nclt";
	  // $target_dir = "c:/xampp/htdocs/";
	  // $pat =$target_dir."nclt";
if($order_type=='O')
{
	
		$path_year ="$pat/order_files/oral/$year";
		$result_year = mkdir($path_year, 0755);
		$path =  "$pat/order_files/oral/$year/$month_name/";
		$result = mkdir($path,0755);
}
if($order_type=='F')
{
	
		$path_year ="$pat/order_files/final/$year"; 
		$result_year = mkdir($path_year, 0755);
		$path =  "$pat/order_files/final/$year/$month_name/";
		 $result = mkdir($path, 0755);
	
}

if($order_type=='I')
{
	
		  $path_year ="$pat/order_files/interim/$year";
		$result_year = mkdir($path_year, 0755);
		$path =  "$pat/order_files/interim/$year/$month_name/";
		   $result = mkdir($path,0755);
	

}


  $userfile_nm = $path."$filing_no"."_$jno_no.pdf";

 $tmp_path = $_FILES['userfile']['tmp_name'];

$mimeArray = array('application/pdf');
$mime = mime_content_type($_FILES['userfile']['tmp_name']);
if (!in_array($mime, $mimeArray))
{
"Mime type is ".$mime;
die();
} 

else 
{
   	//echo "Mime type is ".$mime;
}


    $f_size = $_FILES['userfile']['size'];

    $size_MB = round($f_size/(1024*1024),2); 

	//echo $size_MB. "MB";
 	if($size_MB > 25)
	{ 
		print "Error::file size should not be greater than 5 MB";
		die(); 
	}

	//print_r($_FILES);
//if(move_uploaded_file($_FILES['userfile']['tmp_name'], $userfile_nm)){
	//uploadToDms();
/////////////////////////////DMS INTRIGRATION /////////////////////////////

// $target_dir = "/Efile_Document/orderdocs/";
 $target_dir = "/tmp/";
 
 
$target_file = $target_dir . basename($_FILES["userfile"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
   $file_type = array('application/pdf');
    if($file_type !== $_FILES['userfile']['type']) {
        echo "File is an image - " . $_FILES['userfile']['type'] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "file already uploaded.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["userfile"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
$mime_array = array('pdf','jpg','png','jpeg','gif');
if(!in_array($imageFileType,$mime_array)) {
    echo "Sorry, only JPG, JPEG, PNG ,PDF,pdf, & GIF files are allowed. ". $imageFileType;
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "please choose the another file" ;
// if everything is ok, try to upload file
} else {
if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
$fullflepath = $target_file; 
$dairyno = $applno;//set the dynamic enique diaryno.
$filePath1 = "/Efile_Document/ncltdoc/casedoc/".$filing_no."/04/Order-Challenge in NCLT/".$_FILES['userfile']['name'];

$filePath = "/04_order-Challange_004_15343493483.pdf";
$j_key = "vVl/Az1yGsjOAG18WDeScg==";
$j_securityKey = "TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip/Q8Wns=";
$upload_url = 'http://10.247.205.245/dms-ecourt/cis-order-document-uploading';
//$upload_url = 'http://efiling.nclt.gov.in/dms-ecourt/cis-order-document-uploading';
$fields = [
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
$data = json_decode($response);
$character = json_decode($data);
//print_r($character);
$r = $character->response;
//print_r($r[0]);
//die;
 $fullflepath;
 curl_close($ch);
        echo "The file ". basename( $_FILES["userfile"]["name"]). " has been uploaded.";

$selected_filename = $_FILES['userfile']['name'];
		
$sql_doc_upl="insert into document_upload(doctype,fileupload,loginid,noofpages,subdoctype,uniqueid,filename,
docum_type,transfer_status,returnfilename,original_file,e_reference_no,filing_no,display,scrutiny,
document_filed_date,party_name,party_type,party_serial_no,
created_at,miscellaneous_ref_no,doc_level,miscellenous_no,
diary_no,courtno,fail_status,iscompleted)
values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";

$stmt_doc_up= $dbonline->prepare($sql_doc_upl);
$stmt_doc_up->execute(array('4',$filePath1,$userid,'0','10',$applno,$selected_filename,$doc_type,1,$r[0],$original_name,$applno,$filing_no,1,
'1',$server_date,'ORDER-NCLT','0','2',$server_date,'1','','','','',0,1));
		
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
 
 
////////////////////////DMS INTEGRATION END//////////////////////////////////////////////////

$data = bin2hex(file_get_contents($userfile_nm)); // This may be a problem on too large files




/*$judge_value="";
$judge ="";
$cnt_len=count($judge_code);

//include("../Delhi/services/db_inc.php");//tdsatcisdb

for($i=0;$i<$cnt_len;$i++)
{
$judge_value=$judge_value.$judge_code[$i].",";

$sql="select judge_name from $schemas.master_judge where judge_code=?";
$sth = $db->prepare($sql);
$sth->bindParam(1, $judge_code[$i], PDO::PARAM_INT);
$sth->execute();
$jname = $sth->fetchColumn();
$sql="select judge_desg_code from $schemas.master_judge where judge_code=?";
$sth = $db->prepare($sql);
$sth->bindParam(1, $judge_code[$i], PDO::PARAM_INT);
$sth->execute();
$jdc = $sth->fetchColumn();

$sql="select desg_name from $schemas.master_desg where desg_code=?";
$sth = $db->prepare($sql);
$sth->bindParam(1, $jdc, PDO::PARAM_INT);
$sth->execute();
$jdn = $sth->fetchColumn();

$judge=$jname.'('.$jdn.')'.','.$judge;
}

$judge_value=substr($judge_value,0,-1);
*/

$judge_code_main=$_REQUEST['judge_code_main'];
$cnt_len=count($judge_code_main);
for($i=0;$i<$cnt_len;$i++)
{
	$judge_value = $judge_value.$judge_code_main[$i].",";
    $judge_name = $judge_name.get_full_judge_name($judge_code_main[$i], $db, $schemas);    	
}
 $judge_value=substr($judge_value,0,-1);

 $judge_name=substr($judge_name,0,-1);

//include("db_inc.php");


$sql_jd="insert into $schemas.order_detail
(filing_no,case_type,case_no,case_year,pet_name,res_name,path,date_of_order, order_id,judge_code, entry_date,user_id,remarks,status,order_type,judge_name,display)
values
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt4= $db->prepare($sql_jd);
$stmt4->execute(array($filing_no,$case_type_send,$cas_no,$case_year_send,$pet_name,$res_name, $userfile_nm,$order_date_new, $jno_no,$judge_value,$server_date,$userid,$remarks,$status,$order_type,$judge_name,$display));


$msg="DOCUMENT SUCCESSFULLY UPLOADED";
//} else{$msg="Actually pdf did not uploaded";}
}
else
{
$msg= "Error Occured";
}
echo $msg; //print_r($fields);
$msg= base64_encode($msg);
header("Location:order_upload.php?msg=$msg");

?>
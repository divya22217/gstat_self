<?php
session_start();
ob_start();
//$username= $_SESSION['district_name'];
//include("../Delhi/services/db_inc.php");//tdsatcisdb
include("../db_inc1.php");
$filing_no=$_REQUEST['filing_no'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
if(strlen($filing_no)==16)
{
$cur_date = date("Y-m-d");
$case_no_send=$_REQUEST['case_no_send'];
$filing_no=$_REQUEST['filing_no'];
$case_type_send=$_REQUEST['case_type_send'];
$dt_of_filing=$_REQUEST['dt_of_filing'];
$pet_name=$_REQUEST['pet_name'];
$res_name=$_REQUEST['res_name'];
$judge_code=$_REQUEST['judge_code'];
$remarks=$_REQUEST['remarks'];
$status=$_REQUEST['status'];
$userfile=$_REQUEST['userfile'];
$order_type=$_REQUEST['order_type'];

$file = $_FILES['userfile'];
$name = $file['name'];

if($dt_of_filing=='') $dt_of_filing='1111-11-11';


$cas_no = substr($case_no_send,4,7);
$cas_no=ltrim($cas_no,0);
$cas_year = substr($case_no_send,11,4); 




 $ds="select max(order_id) as order_id from $schemas.order_details_delhi ";
$st = $dbh->prepare($ds);
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


$order_date=$_REQUEST['order_date'];

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

	 $pat =$_SERVER['DOCUMENT_ROOT'];
if($order_type=='O')
{
	
		$path_year ="$pat/order_files/oral/$year";
		$result_year = mkdir($path_year, 0755);
		$path =  "$pat/order_files/oral/$year/$month_name/";
		$result = mkdir($path, 0755);
	

}


if($order_type=='F')
{
	
		$path_year ="$pat/order_files/final/$year"; 
		$result_year = mkdir($path_year, 0755);
		$path =  "$pat/order_files/final/$year/$month_name/";
		$result = mkdir($path, 0755);
	
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

		
if(!move_uploaded_file($_FILES['userfile']['tmp_name'], $userfile_nm))
{
	print "Not uploded in server ..error occur";
	die();
}
else
{
	//print "Upload successful";
}


$data = bin2hex(file_get_contents($userfile_nm)); // This may be a problem on too large files




$judge_value="";
$judge ="";
$cnt_len=count($judge_code);

//include("../Delhi/services/db_inc.php");//tdsatcisdb

for($i=0;$i<$cnt_len;$i++)
{
$judge_value=$judge_value.$judge_code[$i].",";

$sql="select judge_name from $schemas.master_judge where judge_code=?";
$sth = $dbh->prepare($sql);
$sth->bindParam(1, $judge_code[$i], PDO::PARAM_INT);
$sth->execute();
$jname = $sth->fetchColumn();
$sql="select judge_desg_code from $schemas.master_judge where judge_code=?";
$sth = $dbh->prepare($sql);
$sth->bindParam(1, $judge_code[$i], PDO::PARAM_INT);
$sth->execute();
$jdc = $sth->fetchColumn();

$sql="select desg_name from $schemas.master_desg where desg_code=?";
$sth = $dbh->prepare($sql);
$sth->bindParam(1, $jdc, PDO::PARAM_INT);
$sth->execute();
$jdn = $sth->fetchColumn();

$judge=$jname.'('.$jdn.')'.','.$judge;
}

$judge_value=substr($judge_value,0,-1);



//include("db_inc.php");
$tablName="order_details_delhi";

$sql_jd="insert into $tablName
(filing_no,case_type,case_no,case_year,pet_name,res_name,path,date_of_order, order_id,judge_code, entry_date,user_id,remarks,status,order_type,judge_name,display)
values
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt4= $dbh->prepare($sql_jd);
$stmt4->execute(array($filing_no,$case_type_send,$cas_no,$cas_year,$pet_name,$res_name, $userfile_nm,$order_date_new, $jno_no,$judge_value,$cur_date,$sessionUserid,$remarks,$status,$order_type,$judge,$display));


echo$msg="DOCUMENT SUCCESSFULLY UPLOADED";
}
else
{
$msg= "Error Occured";
}
$msg= base64_encode($msg);
header("Location:order_upload.php?msg=$msg");

?>


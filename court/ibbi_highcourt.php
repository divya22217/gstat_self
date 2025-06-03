<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
session_start();
$judge =htmlentities($_REQUEST['judge']);
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];

$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	$link_scrutiny_idaccess='1';
?>
<?php
include '../inheader.php';
include '../insidebar.php';
?>

<script language="javascript">
function submitForm2()
{
 	with(document.frm)
	{


		if(case_type.value == "select")
		{
			alert("Please select Case Type");
			case_type.focus();
			return false;
		}
		if(case_no.value=="")
		{

			alert("Please Enter Case No.");
			case_no.focus();
			return false;
		}
		if(isNaN(case_no.value) == true)
		{
			alert("Please enter  numeric Case No.");
			case_no.select();
			return false;
		}
		if(case_year.value=="")
		{
			alert("Please Enter Case Year");
			case_year.focus();
			return false;
		}
		if(isNaN(case_year.value) == true)
		{
			alert("Please enter  numeric Case Year");
			case_year.select();
			return false;
		}
		if(case_year.value.length!=4)
		{
			alert("Please Enter 4 digit case year");
			case_year.select();
			return false;
		}

		action = "ibbi_highcourt.php";
		submit();
	}
}

function validate()
{
 	with(document.frm)
	{
		var fup = document.getElementById('userfile');
        	var fileName = fup.value;
        	var ext = fileName.substring(fileName.lastIndexOf('.') + 1);

    		if(ext != "pdf")
    		{
					if(ext != "Pdf")
					{
						if(ext != "PDF")
						{
					alert("Upload PDF only");
				 return false;
			 }}}

			if(otype.value=='')
       		{
    	   		alert("Please Select Order Type");
    	   		otype.focus();
			return false;
       		}

					if(order_date.value=='')
							{
								alert("Please Select Order Date");
								order_date.focus();
					return false;
							}

		action = "ibbi_highcourt.php";
		submit();
	}
}
</script>

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
	<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
	<script src="../plugins/jQueryUI/jquery-ui.js"></script>
	<script src="../plugins/jQueryUI/date.js"></script>
</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Ibbi/Highcourt Order </title>
  <div class="content-wrapper">
      <section class="content-header">
      <h1>
        <center>IBBI/HIGHCOURT ORDER UPLOADING FORM
        </center>
      </h1>

    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">

<form name="frm" method="post" action="" enctype="multipart/form-data" >

<?php
$msg =htmlentities($_REQUEST['msg']);
if($msg !='')
{
?>
<tr>
<td colspan="6"><center>
<font color='red' size='2'> <?php $msg= base64_decode($msg); echo $msg;?></font>
</td>
</center>
</tr>
<?php
}
?>
<?php
$bench_type =htmlentities($_REQUEST['bench_type']);
$case_type =$_REQUEST['case_type'];
$msg =$_REQUEST['msg'];
 $case_no =$_REQUEST['case_no'];
$case_year =$_REQUEST['case_year'];
 $sql="select * from case_type where display='Y' order by case_type_desc ASC";
?>
<tr>

<td   align="left" colspan="6">
		<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Bench:
		<select name="bench_type">
			<?php
			$st = $db->prepare("select * from $schemas.bench_location where display = 'TRUE' order by bench_location_name asc");
			$st->execute();
			while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$ctc=htmlspecialchars($row['bench_location_code']);
				if($bench_type == $ctc)
				{
					print "<option value=".htmlspecialchars($row['bench_location_code'])." selected>".htmlspecialchars($row['bench_location_name'])."</option>";
				}
				else
				{
					print "<option value=".htmlspecialchars($row['bench_location_code']).">".htmlspecialchars($row['bench_location_name'])."</option>";
				}
			}

			?>

		</select>

</font>
	<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Case Type
<select name="case_type" style="width:200px">
<option>select</option>
<?php

foreach($dbh->query($sql) as $row)
{

  $casetypecode=$row['id'];
 if($case_type == $casetypecode)
                {
		print "<option value=".htmlentities(htmlspecialchars($row['id']))." selected>".strtoupper(htmlentities(htmlspecialchars($row['case_type_desc'])))."</option>";
                }
           else
                {
                print "<option value=".htmlentities(htmlspecialchars($row['id'])).">".strtoupper(htmlentities(htmlspecialchars($row['case_type_desc'])))."</option>";
		}
 }
 ?>

</select><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Case No
		</font>	<input type="text"  maxlength="7" size="8" name="case_no" value="<?php print htmlentities(htmlspecialchars($case_no)); ?>" >
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Year
<input type="text"  maxlength="4" size="4" name="case_year" value="<?php print htmlentities(htmlspecialchars($case_year)); ?>" >
	<input type="submit" name="search" value="Go" size="20" onClick="return submitForm2();">
</td>


</tr>

<?php
if(isset($_POST['search'])){

?>

<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<?php

$sql2=$db->prepare("select * from $schemas.case_detail where location_code=? and case_type=? and case_no=? and case_year=? ");

$sql2->bindParam(1, $bench_type, PDO::PARAM_STR);
$sql2->bindParam(2, $case_type, PDO::PARAM_STR);
$sql2->bindParam(3, $case_no, PDO::PARAM_STR);
$sql2->bindParam(4, $case_year, PDO::PARAM_STR);

$sql2->execute();
while ($row1 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $filing_no =$row1['filing_no'];
   $pet_name =$row1['pet_name'];
   $res_name =$row1['res_name'];
   $party_name=$pet_name." Vs ".$res_name;



}

$sql2=$db->prepare("select * from  $schemas.roc_detail where filing_no=?");

$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);

$sql2->execute();

while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no333 =$row2['filing_no'];
}
?>
<?php
//if($filing_no333!='')
//{
	?>
<!--<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "RECORD ALREADY EXISTS IN ROC"; ?></span></font>
		</td>
		</tr>
		<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo strtoupper($party_name) ; ?></span></font>
		</td>
		</tr>-->
<?php
//exit();
//}

?>

<?php
if($filing_no=='')
{

	?>
	<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "RECORD NOT FOUND"; ?></span></font>
		</td>
		</tr>

		<?php

	exit();

}

?>
<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo strtoupper($party_name) ; ?></span></font>
		</td>
		</tr>
		<?php

?>
</table>
<br>

<table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
<tr>
 <td align="center">
<font face="Verdana" color="red">*</span> </font><font face="Verdana" size="2">Upload File:
</td>
<td align="left" colspan="5">
<input type="file" name="userfile" id="userfile">
</td>
</tr>

<tr>
 <td align="center">
<font face="Verdana" color="red">*</span> </font><font face="Verdana" size="2">Order Type:
</td>
<td>
<select name="otype">-->
<option value="">-- Select type --</option>
<?php
$st = $db->prepare("select * from master_ibbi_highcourt where display = 'TRUE' order by order_type_id asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
print "<option value=".htmlspecialchars($row['order_type_id']).">".htmlspecialchars($row['order_type_name'])."</option>";
}
?>
</select>
</td>
</tr>

<tr>
 <td align="center">
<font face="Verdana" color="red">*</span> </font><font face="Verdana" size="2">Order date:
</td>
<td align="left" colspan="5"><input type="text"  maxlength="10" size="10" name="order_date" id="order_date" class="datepickerToday" value="<?php print htmlspecialchars($order_date); ?>" </td>
</tr>

<tr>
 <td align="center">
<font face="Verdana" size="2">Remarks:
</td>
<td align="left" colspan="5">
<textarea name ="remarks" onFocus="SetBg(this);this.select()" onBlur="UnSetBg(this)" rows="1" maxlength="160" cols ="50"></textarea>
</td>
</tr>

<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no)); ?>">
<input type="hidden" name="pet_name" value="<?php print htmlentities(htmlspecialchars($pet_name)); ?>">
<input type="hidden" name="res_name" value="<?php print htmlentities(htmlspecialchars($res_name)); ?>">
 <tr>
 <td colspan="3" align="center">
<input type="submit" name="submit1" value="Submit" class="button" onClick="return validate();">
</td>
</tr>

     </form>
   </table>

  <?php
}
  ?>
</div>


                                                           <!-- start of code for form action -->
  <?php
	if(isset($_POST['submit1'])){
		print_r($_POST);
//print_r($_FILES);
//get initial two values
$filing_no=$_REQUEST['filing_no'];
$server_date= date('Y-m-d H:i:s');
 $display = 'T';


if(strlen($filing_no)==16)
{
$case_no = $_REQUEST['case_no'];
$filing_no = $_REQUEST['filing_no'];
$case_type = $_REQUEST['case_type'];
$case_year = $_REQUEST['case_year'];
$order_type = $_REQUEST['otype'];
$order_date = $_REQUEST['order_date'];
$pet_name = $_REQUEST['pet_name'];
$res_name = $_REQUEST['res_name'];
$remarks = $_REQUEST['remarks'];
$userfile = $_REQUEST['userfile'];

$file = $_FILES['userfile'];
$name = $file['name'];


//code for generating an unique order id
$ds="select max(order_id) as order_id from $schemas.ibbi_highcourt_detail";
$st = $db->prepare($ds);
$st->execute();
$order_id=$st->fetchColumn();

if($order_id == "" or $order_id==0 or $order_id =='NULL')
{
	$order_id = 1;
}
else
{
	$order_id = $order_id + 1;
}
//print_r($order_id);

//code for making separate directories
list($day,$month,$year) = explode('/',$order_date);
$order_date_new = $year.'-'.$month.'-'.$day;

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

					 Default:die('order month not received');
break;
}

	   $pat =$_SERVER['DOCUMENT_ROOT'].'nclt';
     //$pat = "c:/uploads";
//print_r($pat);die('l');
if($order_type=='1')
{
	  $path1 = "$pat/ibbi_high_courtorder";
		$result_path1 = mkdir($path1, 0755);
		$path2 = "$pat/ibbi_high_courtorder/ibbi";
		$result_path2 = mkdir($path2, 0755);
		$path_year ="$pat/ibbi_high_courtorder/ibbi/$year";
		$result_year = mkdir($path_year, 0755);
		$path =  "$pat/ibbi_high_courtorder/ibbi/$year/$month_name/";
		$result = mkdir($path,0755);
}
else if($order_type=='2')
{
	  $path1 = "$pat/ibbi_high_courtorder";
	  $result_path1 = mkdir($path1, 0755);
	  $path2 = "$pat/ibbi_high_courtorder/highcourt";
	  $result_path2 = mkdir($path2, 0755);
		$path_year ="$pat/ibbi_high_courtorder/highcourt/$year";
		$result_year = mkdir($path_year, 0755);
		$path =  "$pat/ibbi_high_courtorder/highcourt/$year/$month_name/";
		$result = mkdir($path, 0755);
}else{die('error!no order type received');}


 $userfile_nm = $path."$filing_no"."_$order_id.pdf";
 //$userfile_nm = "c:/uploads/hhh.pdf";    //working
 //echo $userfile_nm;die('RB');

 //$tmp_path = $_FILES['userfile']['tmp_name'];
 //echo $tmp_path;die('RB');
 //C:\xampp\tmp\php30F2.tmpRB


                                                                         //validations
//??????????dont need this code?????????
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
//??????????dont need this code?????????
    $f_size = $_FILES['userfile']['size'];
    $size_MB = round($f_size/(1024*1024),2);
	//echo $size_MB. "MB";
 	if($size_MB > 25)
	{
		print "Error::file size should not be greater than 5 MB";
		die();
	}


																															//move uploaded file from temp to our folder
if(move_uploaded_file($_FILES['userfile']['tmp_name'], $userfile_nm)){
$data = bin2hex(file_get_contents($userfile_nm)); // This may be a problem on too large files


                                                     //start of code for fetching unique no. from e-filling database

$get_unique_no_sql="select unique_id_no from e_case_detail where filing_no=?";
//print_r($get_unique_no_sql);die('ll');
$get_unique_no_sql = $dbo->prepare($get_unique_no_sql);
$get_unique_no_sql->bindParam(1, $filing_no, PDO::PARAM_INT);
$get_unique_no_sql->execute();
$unique_no = $get_unique_no_sql->fetchColumn();
//print_r($unique_no);die('jere');
                                                    //end of code for fetching unique no. from e-filling


                                                                    //insert data to our database

/*echo $sql_jd="insert into $schemas.ibbi_highcourt_detail
(filing_no, case_type, case_no, case_year, pet_name, res_name, path, date_of_order, order_id, ibbi_highcourt_flag, entry_date, display, user_id, remarks, unique_id_no)
values
('$filing_no', '$case_type', '$case_no', '$case_year', '$pet_name', '$res_name', '$userfile_nm', '$order_date_new', '$order_id', '$order_type', '$server_date', '$display', '$userid', '$remarks', '1234')";*/

$sql_jd="insert into $schemas.ibbi_highcourt_detail
(filing_no, case_type, case_no, case_year, pet_name, res_name, path, date_of_order, order_id, ibbi_highcourt_flag, entry_date, display, user_id, remarks, unique_id_no)
values
(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt4= $db->prepare($sql_jd);
$stmt4->execute(array($filing_no, $case_type, $case_no, $case_year, $pet_name, $res_name, $userfile_nm, $order_date_new, $order_id, $order_type, $server_date, $display, $userid, $remarks, $unique_no));

$msg = "Successfully Uploaded Document";

}else{
$msg = "failed to upload image";
}

}else{
	$msg= "Error Occured";
}

$msg= base64_encode($msg);
header("Location:ibbi_highcourt.php?msg=$msg");
}
//include '../bfooter.php';
  } ?>

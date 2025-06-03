<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc2.php");
include("../db_inc1.php");
session_start();
$bench_no='';
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
$benchlocation =htmlentities($_REQUEST['bench_location']);
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	$link_scrutiny_idaccess='1';
	
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 

?>
<?php 
include '../inheader.php';
include '../insidebar.php';
?>


<script language="javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
  return false;
}
function submitForm()
{
 	with(document.frm)
	{
		
		action = "remain_listing.php";
		submit();
	}
}

</script>

</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Remain Listing</title>
   
    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    
        <center>NOT LISTED CASES 
        </center>
     
    </section>
 <p> <center>All <font color="red">*</font></span> is mandatory Field </center></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">

  <?php 
  
  include '../bfooter1.php';
  ?>


<?php
$msg =htmlentities($_REQUEST['msg']);
if($msg !='')
{
?>
<tr>
<td colspan="6"><center>
<font color='red' size='2'> <?php echo $msg;?></font> 
</td>
</center>
</tr>
<?php
}
?>



<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css"/>
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>






	
</table>
  <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">

 


	<tr><th>
	<b>Sr.No.</b></th>
	
	<th align="left" ><b>DIARY NO</b></th>
	
	</tr>
	

	<?php


 $couu=0;
$sql1=$dbonline->prepare("select distinct(filing_no) as filing_no from document_upload  where filing_no !='NA'   and display='1' and scrutiny='0' and doc_level='11' order by filing_no DESC ");


$sql1->execute();
while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
 $filing_no =$row1['filing_no'];
 $doc_case_no='';

  $st1=$db->prepare("select * from $schemas.case_detail where filing_no =? and case_no!=?  order by filing_no DESC");
         
          $st1->bindParam(1, $filing_no, PDO::PARAM_STR);
		   $st1->bindParam(2, $doc_case_no, PDO::PARAM_STR);
		  
          $st1->execute();
          while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
			  $case_type=htmlspecialchars($row['case_type']);
           $offset=0;
                  $filing_no2 = htmlspecialchars($row['filing_no']);
 //echo "select distinct(filing_no) from delhi.objection_detail";die();
 $objdetsql = $db->prepare("select distinct(filing_no) from delhi.objection_details");
  $objdetsql->execute();
          while ($row11 = $objdetsql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
			  $filing_no3 = htmlspecialchars($row11['filing_no']);
			  //echo "H"."<br>";
			  if($filing_no2 == $filing_no3){
				  //echo $filing_no2."FOUND"."<br>";
				  $offset = 1;
				  //$array[] = $filing_no2;
				  //break;
			  }else{
				  continue;
			  }		  
		  }
if($offset == 0){
	echo $filing_no2."NOT MATCHED".$couu."<br>";

	//scrutiny doc
	$obj_sta='Y';
	$defts='Y';
	$lvl_lvl=11;
	/*echo $s ="insert into  delhi.scrutiny_doc(filing_no,notification_date,user_id,objection_status,defects,level_level)values ('$filing_no2','$server_date',
	'$sessionUserType','$obj_sta','$defts','$lvl_lvl')";
	die('j');*/
	$setscr=$db->prepare("insert into  delhi.scrutiny_doc(filing_no,notification_date,user_id,objection_status,defects,level_level) values(?,?,?,?,?,?)");
$setscr->bindParam(1, $filing_no2, PDO::PARAM_STR);
$setscr->bindParam(2, $server_date, PDO::PARAM_STR);
$setscr->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$setscr->bindParam(4, $obj_sta, PDO::PARAM_STR);
$setscr->bindParam(5, $defts, PDO::PARAM_STR);
$setscr->bindParam(6, $lvl_lvl, PDO::PARAM_STR);
$setscr->execute();
	
 $checklistsql = $db->prepare("select id from check_list_local");
  $checklistsql->execute();
  $comflg='N';
  $compdate=NULL;
  $status='NO';
  $lvl_lvl=11;
  $obj_ver_lvl=0;
  $obj_sub=0;
          while ($row112 = $checklistsql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
			  $chclistid = htmlspecialchars($row112['id']);
			  
			  /*echo $s = "insert into  $schemas.objection_details (filing_no,completed_flag,userid,entry_dt,status,case_type,objection_code,
level_level,obj_verify_level)values ('$filing_no','$comflg','$sessionUserType','$server_date','$status','$case_type','$chclistid','$lvl_lvl',
'$obj_ver_lvl') ";
	die('ZZZ');		*/  
			  $setobjdet=$db->prepare("insert into  delhi.objection_details (filing_no,completed_flag,userid,entry_dt,status,case_type,objection_code,
level_level,obj_verify_level,objection_sub_code	)values (?,?,?,?,?,?,?,?,?,?) ");
$setobjdet->bindParam(1, $filing_no2, PDO::PARAM_STR);
$setobjdet->bindParam(2, $comflg, PDO::PARAM_STR);
$setobjdet->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$setobjdet->bindParam(4, $server_date, PDO::PARAM_STR);
$setobjdet->bindParam(5, $status, PDO::PARAM_STR);
$setobjdet->bindParam(6, $case_type, PDO::PARAM_STR);
$setobjdet->bindParam(7, $chclistid, PDO::PARAM_STR);
$setobjdet->bindParam(8, $lvl_lvl, PDO::PARAM_STR);
$setobjdet->bindParam(9, $obj_ver_lvl, PDO::PARAM_STR);
$setobjdet->bindParam(10, $obj_sub, PDO::PARAM_STR);
$setobjdet->execute();
          	echo "NNNNN";           	  
		  }

}
$couu++;
?>
<?php
}
}

//print_r($array);
//echo "H";
?>
</table>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">





  <?php
}

?>
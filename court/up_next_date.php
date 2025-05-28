<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
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

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

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
function submitForm21()
{
 	with(document.frm)
	{
		action = "old_case_listing.php";
		submit();
	}
}
function submitForm1()
{
 	with(document.frm)
	{




	action = "remain_listing_action.php";
		submit();
	}
}
function un_check()
{
	for (var i = 0; i < document.frm.elements.length; i++)
	{
		var e = document.frm.elements[i];
		if ((e.name != 'allbox') && (e.type == 'checkbox'))
		{
			e.checked = document.frm.allbox.checked;
		}
	}
}
function submitForm2()
{
 	with(document.frm)
	{

 		
		
		if(next_list_date.value=="")
		{
			alert("Please Select Listing Date.");
			next_list_date.focus();
			return false;
		}
		if(purpose_id.value == "select")
		{
			alert("Please select Purpose");
			purpose_id.focus();
			return false;
		}
		var checkboxes = document.getElementsByName('bench_no');

		var selected = [];
		for (var i=0; i<checkboxes.length; i++) {
		if (checkboxes[i].checked) {selected.push(checkboxes[i].value);}
		}
		if(selected=="")
		{
		alert("Please choose From bench ");
		return false;
		}
		var filing_case = $(".checkbox").is(":checked");
		if (filing_case == false)
		{
		        alert("Please select at least one case.");
		        return false;
		}
		
		
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
<form name="frm" method="post" action="remain_listing_action.php" >

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
	
	<th align="left" width="150"><b>DIARY NO</b></th>
	<th align="left" width="150"><b>CASE NO</b></th>
	<th align="left" width="500"><B>CAUSE TITLE </b></th>
	<th align="left" width="500"><B>NEXT LIST DATE </b></th>
	
	</tr>


	<?php

	$count=0;

//$sql1=$db->prepare("select * from $schemas.case_allocation_temp where listing_date=next_list_date and listed=0 ");


$sql1=$db->prepare("select distinct(filing_no) from $schemas.case_allocation_temp where listed = 0 and next_list_date != '1111-11-11'");

$sql1->execute();

$res = $sql1->fetchAll();

$list = array();

foreach($res as $key=>$v)
{
	$sql1=$db->prepare("select * from delhi.case_allocation_temp where filing_no = '$v[filing_no]'");
	$sql1->execute();

$result = $sql1->fetchAll();
$count = 0;
$next_list_date = $result[0]['next_list_date'];
foreach($result as $a=>$b )
{
	if($next_list_date == $b['next_list_date'])
	{
		$count++;
	}
}
if($count > 1)
{
	$list[] = $result;
}
}


foreach ($list as $key=>$row1)
{

 $filing_no =$row1[0]['filing_no'];
 
 $st = $db->prepare("select * from $schemas.case_allocation_temp where filing_no=?  order by listing_date asc	");

$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
$data = $st->fetchAll();
if(count($data) <= 1){
continue;
}
 
  $new_listing_date =$row1['next_list_date'];
   $listing_date =$row1['listing_date'];
   if($new_listing_date!='')
   {
  list($Y1,$m1,$d1) =explode('-',$new_listing_date);
$new_listing_date1 =$d1.'/'.$m1.'/'.$Y1;
   }
 //$last_bench_no =$row1['last_bench_no'];
 //$last_court_no =$row1['last_court_no'];
 //$last_bench_nature =$row1['last_bench_nature'];

 $sql2=$db->prepare("select * from $schemas.case_detail  where filing_no=? ");

$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql2->execute();
	
while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $case_no =$row2['case_no'];
  $case_type =$row2['case_type'];
  $pet_name =$row2['pet_name'];
  $res_name =$row2['res_name'];
  $case_title=$pet_name."<br> VS  <br>".$res_name ;
  if($case_type > 0)
{
$ref_stQ = $db->prepare("select case_type_desc from case_type where id = ?");
$ref_stQ->bindParam(1, $case_type, PDO::PARAM_STR);
$ref_stQ->execute();
$case_type_short_name=$ref_stQ->fetchColumn();
}
  $case_year =$row2['case_year'];
   $location_code =$row2['location_code'];
   
   $ref_lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
$ref_lcode=$db->prepare($ref_lcode);
$ref_lcode->execute();
$lcodename = $ref_lcode->fetchColumn();
   
  $case_number=$case_type_short_name."/".$case_no."/"."(".$lcodename.")".$case_year;
}
$count++
?>
<tr>
<td>
<?php echo $count;
?>
</td>



<td>
<a href="../court/rr.php?filing_no=<?php echo htmlspecialchars($filing_no);?>" onClick="NewWindow(this.href,'mywin','800','600','yes','center');return false" target="blank" onFocus="this.blur()"><blink><font color ="blue" size="2"><?php echo $filing_no;?></blink></b></a>

</td>
<td>
<?php echo $case_number;
?>
</td>
<td>
<?php echo  $case_title;
?>
</td>


<td>
<?php echo $new_listing_date1;
?>
</td>



</tr>

<?php
}
?>
</tr>
</table>
  

  <?php
  } ?>
  
<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
session_start();
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
		action = "old_case_listing.php";
		submit();
	}
}
function submitForm1()
{
 	with(document.frm)
	{




	action = "old_case_listing_action.php";
		submit();
	}
}
function popsurety_listing_report(cfy)

    {
    	
    		var url = "./old_case_listing_view.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");   
    		
    }

</script>

</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Create Bench</title>
   
    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    
        <center>OLD CASE LISTING MODULE ( MAIN CAUSE LIST)
        </center>
     
    </section>
 <p> <center>All <font color="red">*</font></span> is mandatory Field </center></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">

  <?php 
  include '../bfooter1.php';
  ?>
<form name="frm" method="post" action="old_case_listing_action.php" >

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
<?php 
        $hash=htmlspecialchars($_REQUEST['hash']);
        if($hash !='')
        {
        	 $hash1=htmlspecialchars(base64_decode($hash));
        	
        	
        	
        	 $next_list_date= $hash1;
		}
		 
		
	 $hash4=htmlspecialchars($_REQUEST['hash3']);
	  if($hash4!='')
		 {
	 $hash2=htmlspecialchars(base64_decode($hash4));
	$hash5 = explode("-", $hash2);
	$msg4=$hash5[0];
	$msg5=htmlspecialchars(base64_decode($msg4));
	$next_list_date4= $hash5[1];
	 $next_list_date5=htmlspecialchars(base64_decode($next_list_date4));
		 }
		 
		
        
        
        
        ?>      
<tr>
<td align="center"><b>
<font color="red" face="Verdana, Arial, Helvetica, sans-serif" size="2">
<?php if($next_list_date5 !='')
{	?></b>
</td>
</tr>
<?php 
?>
<tr>
<td align="center"><b>
<font color="red" face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="javascript:popsurety_listing_report('<?php echo htmlspecialchars($next_list_date5);?>');" ><b><font color = 'red'  ><br><?php echo $msg5;?><?php echo $next_list_date5;?><br> CLICK HERE FOR VIEW </font></a></u>
</td>
</tr>
<?php
}
?>
<tr>
	<td align="left" colspan="7" ><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
<font color="red">*</font>Date of Listing</font>
		<input type="text"  readonly name="next_list_date" id="next_list_date"  maxlength="10" size="10" value="<?php echo  $next_list_date; ?>" onKeyup="javascript:addNumbers(this.value)" class="datepickerGreater" onFocus="SetBg(this)" >
		<b></b>
		</td>
</tr>

<tr><td colspan="8" align="center"><br><br>
	<input type="submit" name="submit1" value="CASE FOR LIST" class="button"></td></tr>
<script src="../src/calendar.js"></script>




 </td>



</tr>
</table>
  <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">
<?php
if($next_list_date!='')
{
list($d,$m,$Y) =explode('/',$next_list_date);
$list_date =$Y.'-'.$m.'-'.$d;
}
 $sql="select count(*) from $schemas.case_allocation_temp where next_list_date='$list_date'";
$sql=$db->prepare("select count(*) from $schemas.case_allocation_temp where next_list_date='$list_date' ");

$sql->execute();

 $dd  =$sql->fetchColumn();


if($dd!='')
{
?>


	<tr><th>
	<b>Sr.No.</b></th>
	<th align="left" width="150"><b>DIARY NO</b></th>
	<th align="left" width="150"><b>CASE NO</b></th>
	<th align="left" width="500"><B>CAUSE TITLE </b></th>
	<th align="left" width="500"><B>LAST LISTING DATE </b></th>
	<th align="left" width="500"><B>LAST LISTING CORAM </b></th>
	<th align="left" width="500"><B>NEW LISTING DATE </b></th>
	<th align="left" width="500"><B>NEW LISTING CORAM </b></th>
	<th align="left" width="150"><B>CURRENT PURPOSE</b></th>
	<th align="left" width="150"><B>ACTION</b></th>
	</tr>
	<?php
}
?>

	<?php
	$count=0;
$sql1=$db->prepare("select * from $schemas.case_allocation_temp where next_list_date=? ");

$sql1->bindParam(1, $list_date, PDO::PARAM_STR);
$sql1->execute();
	
while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
 $filing_no =$row1['filing_no'];
   $purpose =$row1['purpose'];
   $listed =$row1['listed'];
  $display='1';
	  $ref_stQ2 = $db->prepare("select purpose_name from $schemas.master_purpose where purpose_code = ? and display=?");
$ref_stQ2->bindParam(1, $purpose, PDO::PARAM_STR);
$ref_stQ2->bindParam(2, $display, PDO::PARAM_STR);
$ref_stQ2->execute();
$purpose_short_name=$ref_stQ2->fetchColumn();
 
 $last_listing_date =$row1['last_listing_date'];
  $new_listing_date =$row1['listing_date'];
  list($Y1,$m1,$d1) =explode('-',$new_listing_date);
$new_listing_date1 =$d1.'/'.$m1.'/'.$Y1;
 $last_bench_no =$row1['last_bench_no'];
 $last_court_no =$row1['last_court_no'];
 $last_bench_nature =$row1['last_bench_nature'];
 $new_bench_nature =$row1['bench_nature'];
 $new_court_no =$row1['court_no'];
$new_bench_no =$row1['bench_no'];
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
<?php echo $filing_no;
?>
</td>
<td>
<?php echo $case_number;
?>
</td>
<td>
<?php echo  $case_title;
?>
</td>
<td align="center">
<?php
if($last_listing_date!='')
{

if($listed=='0')
{
	
$last_list_date=$new_listing_date1;	


}
else
{
list($Y,$m,$d) =explode('-',$last_listing_date);
 $last_list_date =$d.'/'.$m.'/'.$Y;
}
	if($last_list_date =='11/11/1111')
	{
		
		$last_list_date='-';
	}
	

echo $last_list_date;
}

?>
</td>




<td align="center">

<?php 

if($listed!=0)
{
if($last_listing_date =='') $last_listing_date='1910-01-01';	
  $stat="select * from $schemas.bench where  bench_nature ='$last_bench_nature' and from_list_date='$last_listing_date' and  bench_no='$last_bench_no'";
}
if($listed==0)
{
	list($d,$m,$Y) =explode('/',$last_list_date);
 $last_list_date1 =$Y.'-'.$m.'-'.$d;
if($last_list_date1 =='--') $last_list_date1='1910-01-01';
  $stat="select * from $schemas.bench where  bench_nature ='$new_bench_nature' and from_list_date='$last_list_date1' and  bench_no='$new_bench_no'";
}
$stat=$db->prepare("$stat");
//$stat->bindParam(1, $display, PDO::PARAM_STR);
$stat->execute();
while ($row = $stat->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$bench_no= $row['bench_no'];
$court_no =$row['court_no'];
$presiding=$row['presiding'];
if($bench_no!='')
{
$stat1="select *  from $schemas.master_judge where judge_code ='$presiding'";
$stat1 = $db->prepare($stat1);
$stat1->execute();
$presiding1 = $stat1->fetch();
$gen=$presiding1['gen']."&nbsp";
echo  $presiding1['judge_name'];?>
<?php
$hon_text=$presiding1['hon_text'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$presiding1[judge_desg_code], PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ". $desg_name = $stat1->fetchColumn();

}
?><font color="Red"><b> (Presiding) </b></font>
<?php
}
?>
<br>
<?php 
if($listed!=0)
{
$stat2="select distinct(judge_code) as judge_code from $schemas.bench_judge where bench_nature='$last_bench_nature' and from_list_date='$last_listing_date' and judge_code !='$presiding' and bench_no='$last_bench_no'";
}
if($listed==0)
{
 $stat2="select distinct(judge_code) as judge_code from $schemas.bench_judge where bench_nature='$new_bench_nature' and from_list_date='$last_list_date' and judge_code !='$presiding' and bench_no='$new_bench_no'";
	
}
$stat2=$db->prepare("$stat2");
//$stat->bindParam(1, $display, PDO::PARAM_STR);
$stat2->execute();
while ($row2 = $stat2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

$judge_code =$row2['judge_code'];
$stat1="select judge_name,judge_desg_code,gen,hon_text from $schemas.master_judge where judge_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$judge_code, PDO::PARAM_STR);
$stat1->execute();
 $judge_data = $stat1->fetch();
$gen = $judge_data['gen'];
 $hon_text = $judge_data['hon_text'];

  
echo $gen." ".$judge_name = $judge_data['judge_name'];
$desg_code = $judge_data['judge_desg_code'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$desg_code, PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ".$desg_name = $stat1->fetchColumn();
}


?>
</td>
<td align="center">

 <?php
if($listed=='0')
{
$new_listing_date1='--';	
}
else
{
echo $new_listing_date1;
}
?>
</td>


<td>
<?php

if($listed!=0)
{
//new coram//
 $stat12="select * from $schemas.bench where  bench_nature ='$new_bench_nature' and from_list_date='$new_listing_date' and  bench_no='$new_bench_no'";
$stat12=$db->prepare("$stat12");
//$stat->bindParam(1, $display, PDO::PARAM_STR);
$stat12->execute();
while ($row12 = $stat12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$bench_no1= $row12['bench_no'];
if($bench_no1!=0)
{
$court_no1 =$row12['court_no'];
$presiding1=$row12['presiding'];
$stat13="select *  from $schemas.master_judge where judge_code ='$presiding1'";
$stat13 = $db->prepare($stat13);
$stat13->execute();
$presiding11 = $stat13->fetch();
$gen1=$presiding11['gen']."&nbsp";
echo  $presiding11['judge_name'];?>
<?php
$hon_text1=$presiding11['hon_text'];
$stat14="select desg_name from $schemas.master_desg where desg_code =?";
$stat14 = $db->prepare($stat14);
$stat14->bindParam(1,$presiding11[judge_desg_code], PDO::PARAM_STR);
$stat14->execute();
echo", ".$hon_text." ". $desg_name = $stat14->fetchColumn();

}
?><font color="Red"><b> (Presiding) </b></font>
<?php
}
?>
<?php 
$stat2="select distinct(judge_code) as judge_code from $schemas.bench_judge where bench_nature='$last_bench_nature' and from_list_date='$last_listing_date' and judge_code !='$presiding' and bench_no='$last_bench_no'";
$stat2=$db->prepare("$stat2");
//$stat->bindParam(1, $display, PDO::PARAM_STR);
$stat2->execute();
while ($row2 = $stat2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

$judge_code =$row2['judge_code'];
$stat1="select judge_name,judge_desg_code,gen,hon_text from $schemas.master_judge where judge_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$judge_code, PDO::PARAM_STR);
$stat1->execute();
 $judge_data = $stat1->fetch();
$gen = $judge_data['gen'];
 $hon_text = $judge_data['hon_text'];

  
echo $gen." ".$judge_name = $judge_data['judge_name'];
$desg_code = $judge_data['judge_desg_code'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$desg_code, PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ".$desg_name = $stat1->fetchColumn();
}

}
?>

</td>
<td>
<?php
echo $purpose_short_name;
?>
</td>
<td>
<?php

if($listed==1)
{
	$stat="LISTED";
}
else
{
	$stat="NOT LISTED";
}
echo $stat;
?>
</td>

</tr>

<?php
}
?>
</tr>

</table>


  <?php } ?>

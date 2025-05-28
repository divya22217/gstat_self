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





<tr>

<?php 


 $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>	
	
	<td align="left" colspan="7" ><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
<font color="red">*</font>Date of Listing</font>
		<input type="text"  readonly name="next_list_date" id="next_list_date"  maxlength="10" size="10" value="<?php echo  $next_list_date; ?>" onKeyup="javascript:addNumbers(this.value)" onchange="submitForm();UnSetBg(this);"class="datepicker" onFocus="SetBg(this)" >
		<b></b>
		</td>

	<td>
<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font color="red">*</font>Select Purpose 
<select name="purpose_id" style="width:200px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<option>select</option>
<?php
$sql2=" select * from $schemas.master_purpose ";
foreach($dbh->query($sql2) as $row)
{

  $purpose_code=$row['purpose_code'];
 if($purpose_id == $purpose_code)
                {
		print "<option value=".htmlentities(htmlspecialchars($row['purpose_code']))." selected>".strtoupper(htmlentities(htmlspecialchars($row['purpose_name'])))."</option>";
                }
           else
                {
                print "<option value=".htmlentities(htmlspecialchars($row['purpose_code'])).">".strtoupper(htmlentities(htmlspecialchars($row['purpose_name'])))."</option>";
		}
 }
 ?>
 </td>
 <td>
 <?php

/*----------------------------------------------*/

list($day,$month,$year)=explode('/',$next_list_date);
$court_date_new=$year.'-'.$month.'-'.$day;
if($court_date_new!='')
{
 $sql="select distinct(court_no) as court_no from $schemas.case_allocation_temp where (next_list_date ='$court_date_new' OR next_list_date='1111-11-11' )  and listed='0'  and list_criteria IS NULL order by court_no ASC";

//print_r($sql);die('k');	
$court_no_fil = isset($_REQUEST['court_no']) ? $_REQUEST['court_no'] :'';
?>
Filter by Court:
<select name="court_no" id="test" onchange="submitForm();UnSetBg(this);" style='width:150px;'>
<option value="">Select Court</option>
<?php
$sqlm=$db->prepare($sql);

            //$sqlm->bindParam(1, $listing_date, PDO::PARAM_STR);
           //$sqlm->bindParam(2, $list_before, PDO::PARAM_STR);
            $sqlm->execute();
		while ($row = $sqlm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{

		
$btype = htmlspecialchars($row['court_no']);
	   if($court_no_fil == $btype)
                {
				print "<option value=".htmlspecialchars($row['court_no'])." selected>".htmlspecialchars($row['court_no'])."</option>";
                }
        else
                {
                print "<option value=".htmlspecialchars($row['court_no']).">".htmlspecialchars($row['court_no'])."</option>";
	        }

}
}
/*--------------------------------------*/

 ?>

</td>
</tr>

<?php

if($next_list_date!='')
{
list($day,$month,$year)=explode('/',$next_list_date);
$court_date_new=$year.'-'.$month.'-'.$day;
 $sql2=" select * from $schemas.bench where  from_list_date ='$court_date_new' order by court_no asc";
$bench_d=$dbh->prepare($sql2);
$bench_d->execute();
if($bench_d->rowCount()>0)
{
	
	 $bench_data=$bench_d->fetchAll();
 ?>

  <input type="hidden" name="lis_date" value="<?php echo htmlspecialchars($court_date_new);?>" />   
<tr>
<th ><font face="Verdana, Arial, Helvetica, sans-serif" >&nbsp;</font></th>
<th   valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" >Court </font></th>

<th colspan="7" align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Hon'ble Justice</font></th>
</tr>
<?php
	$flag=0;
	foreach($bench_data as $row2)
	{
		$bench_code1='';
		$bench_no='';
		$flag=1;
		$court_no =$row2['court_no']; 
		$bench_code1 = $row2['bench_no'];
		?>
		<tr>
		<?php $bench_nocheck = isset($_REQUEST['bench_no']) ? $_REQUEST['bench_no'] : ''; ?>
		<td valign="top"  align="center">
		<input type="radio"  name="bench_no"  class="bench_no"  value="<?php echo $bench_code1;?>" <?php if($bench_no ==$bench_code1)echo 'checked';?>>
		</td>
		<td  valign="top" align="center"><?php  
		$sql2q=" select bench_name from $schemas.bench_nature where  bench_code ='$row2[bench_nature]'";
		$sth11= $dbh->prepare($sql2q);
		$sth11->execute();
		echo $sth11->fetchColumn(); ?>
		<?php echo '<br>Court No : '.$court_no;?>
		</td>

		<td colspan="7" align="left">
		<?php
		
		$sql="select bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm where bj.from_list_date ='$court_date_new' and bj.bench_no='$bench_code1' and jm.judge_code=bj.judge_code ";
		$sth = $dbh->prepare($sql);
		$m=0;
		$arr=[];
		foreach($dbh->query($sql) as $row)
		{
			$arr[$m]=$row['judge_code'];
			$m++;
		}
		$sql="select presiding from $schemas.bench where from_list_date ='$court_date_new'  and bench_no='$bench_code1'";
		$sth = $dbh->prepare($sql);
		$sth->execute();
		$presiding = $sth->fetchColumn();
		$arr1 = sizeof($arr);
		for($i=0;$i<$arr1;$i++)
		{
			$jcode =$arr[$i];
			$sql = "select judge_name from $schemas.master_judge where judge_code =$jcode";
			$sth = $dbh->prepare($sql);
			$sth->execute();
			$judge = $sth->fetchColumn();

			print "<font size='2' ><b>".strtoupper($judge);
			if($jcode == $presiding) print "<font color='red'><b> (PRESIDING JUDGE )</b></font>";
			print"<br>";
		}echo'</td>';
	}
	echo'</tr>';
}
}
	?>
	
</table>
  <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">

  
  <?php

$sql=$db->prepare("select count(*) from $schemas.case_allocation_temp where (next_list_date ='$court_date_new' OR next_list_date='1111-11-11' )  and listed='0' and list_criteria IS NULL ");

$sql->execute();

 $dd  =$sql->fetchColumn();


if($dd!='')
{
?>


	<tr><th>
	<b>Sr.No.</b></th>
	<th><input type="checkbox" name="allbox" onClick="un_check(this);" title="Select or Deselct ALL" style="background-color:#ccc;"/></th>
	<th align="left" width="150"><b>DIARY NO</b></th>
	<th align="left" width="150"><b>CASE NO</b></th>
	<th align="left" width="500"><B>CAUSE TITLE </b></th>
	<!--<th align="left" width="500"><B>LAST LISTING DATE </b></th>
	<th align="left" width="500"><B>LAST LISTING CORAM </b></th>-->
     <th align="left" width="500"><B>NEXT LIST DATE </b></th>
	<th align="left" width="150"><B>PURPOSE</b></th>
	<!--<th align="left" width="150"><B>ACTION</b></th>-->
	</tr>
	<?php
}
?>

	<?php

	$count=0;

if($court_no_fil!=''){
	$sql1=$db->prepare("select * from $schemas.case_allocation_temp where (next_list_date ='$court_date_new' OR next_list_date='1111-11-11' ) and listed=0 and court_no='$court_no_fil' and list_criteria IS NULL order by next_list_date desc ");
}else{
	$sql1=$db->prepare("select * from $schemas.case_allocation_temp where (next_list_date ='$court_date_new' OR next_list_date='1111-11-11' ) and listed=0 and list_criteria IS NULL  order by next_list_date desc ");
}

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
 
// $last_listing_date =$row1['last_listing_date'];
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
 $new_bench_nature =$row1['bench_nature'];
 $new_court_no =$row1['court_no'];
 $new_bench_no =$row1['bench_no'];
 $case_status='P';
 $sql2=$db->prepare("select * from $schemas.case_detail  where filing_no=?  and status=?");

$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql2->bindParam(2, $case_status, PDO::PARAM_STR);
$sql2->execute();
	
while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $case_no =$row2['case_no'];
  $case_type =$row2['case_type'];
  $dt_of_filing =$row2['dt_of_filing'];
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

$count++
?>
<tr>
<td>
<?php echo $count;
?>
</td>

<td>
<input class="checkbox" name="checkbox[<?php echo $x?>]" type="checkbox" id="checkbox[<?php echo $x?>]" value="<?php echo $filing_no; ?>" <?php if($checkbox[$x]==$filing_no)echo 'checked';?> >
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
<!--<td align="center">-->
<?php
/*
if($last_listing_date!='' )
{



list($Y,$m,$d) =explode('-',$last_listing_date);
 $last_list_date =$d.'/'.$m.'/'.$Y;

	if($last_list_date =='11/11/1111')
	{
		
		$last_list_date='-';
	}
	

echo $last_list_date;
}

if($last_listing_date=='' )
{
	
 $last_list_date=$listing_date;
list($Y,$m,$d) =explode('-',$last_list_date);
echo $last_list_date =$d.'/'.$m.'/'.$Y;
}
*/
?>
<!--</td>-->




<!--<td align="center">-->

<?php 

/*
if($listed==0)
{
	
	if($last_list_date!='' || $last_list_date=='')
	{
		
		
		
	list($d,$m,$Y) =explode('/',$last_list_date);
	$last_list_date =$Y.'-'.$m.'-'.$d;
	}
	else
	{
		$last_list_date='';
	}
	*/	
  
/*  $stat="select * from $schemas.bench where  bench_nature ='$new_bench_nature' and from_list_date='$listing_date' and  bench_no='$new_bench_no'";
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
/*
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

*/
?>
<!--</td>-->

<td>
<?php echo $new_listing_date1;
?>
</td>


<td>
<?php
echo $purpose_short_name;
?>
<!--</td>

<td>
<?php
/*
if($listed==1)
{
	$stat="LISTED";
}
else
{
	$stat="NOT LISTED";
}
echo $stat;
*/
?>
</td>

</tr>
-->
<?php
}
}
?>
</tr>
</table>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">


<?php

if($bench_d->rowCount()>0)
{

?>
<tr><td colspan="8" align="center"><br><br>
	<input type="submit" name="submit1" value="CASE FOR LIST" class="button" onClick="return submitForm2();"></td></tr>
<script src="../src/calendar.js"></script>




 </td>



</tr>

  <?php
}  } ?>
  
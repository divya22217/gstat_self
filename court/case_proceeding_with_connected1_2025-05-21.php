<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
$no =$_REQUEST['no'];


/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */ 

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include("../master/functions.php");

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
$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$location_access=$_SESSION['location'];
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	
	if($_REQUEST['no']!='')
		{
			$filing_no_link= $_REQUEST['no'];

			list($filing_no_link1,$court_no_link,$list_date_link,$list_before_link,$list_flag,$purpose_old,$bench_code1)=explode('@',$filing_no_link);
			$sqlq="select case_year,case_no,case_type,location_code,main_case_ia_no,list_with_defect,regis_date from $schemas.case_detail where filing_no=?";
			$sanR = $db->prepare($sqlq);
			$sanR->execute(array($filing_no_link1));
				$sanR_data = $sanR->fetch();
				//echo "<pre>";print_r($sanR_data);

	}
		$main_cases = main_case_type();	
		$main_case_fn = '';
		if (in_array($sanR_data['case_type'], $main_cases)){
			
		}else{
			$main_case_fn  = htmlspecialchars($sanR_data['main_case_ia_no']);
		}
	list($day,$month,$year)=explode('/',$list_date_link);
	$listdate_entire=$year.'-'.$month.'-'.$day;


?>
 <!-- Content Wrapper. Contains page content -->
  

<script language="javascript">


function SetBg(txt)
{
txt.style.backgroundColor='#ffff99';
}
function UnSetBg(txt)
{
txt.style.backgroundColor='white';
}




//  END
</script>

<style>
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding:0px;}

</style>

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper">

    <section class="content">

  <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">

     <div class="form-group">
				 <div class="col-md-12">



<?php

$msghash=(isset($_REQUEST['msghash']))?$_REQUEST['msghash']:'';
if($msghash !='')
{
$msghashz=(base64_decode($msghash));

$msghashz = explode("-", $msghashz);
$type = $msghashz[0];
$msg1 = $msghashz[1];
$case_type = $msghashz[2];
$case_year = $msghashz[3];

if(!empty($msghashz))
{
?>
<div class="alert alert-<?php echo $type;?> alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <?php echo $msg1; ?>
</div>
<?php
if($type == 'success'){
	echo "<script>setTimeout(function () { window.close();}, 5000);</script>";
	
	die;
}
}
}
?>

<table  border="2" class="table"  align="center" >

<tr>
<td valign="top" align="right" colspan="16"><center>
<b><font face="Verdana" size="3"><u>DAILY CASE PROCEEDING </u></font> </b>
</td>
</tr>

<tr><td valign="top" align="center" colspan="16">
<font face="Verdana" size="2">Fields marked with a <font color='red'>*</font> are compulsory.</font> </td>
</tr>
<tr>
<td  colspan="16" align="left">

<?php  $bench_type = isset($_REQUEST['bench_type']) ? $_REQUEST['bench_type'] :$sanR_data['location_code'];?>
<?php  $case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] :$sanR_data['case_type'];?>
<?php  $case_no = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] :$sanR_data['case_no'];?>
<?php  $case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] :$sanR_data['case_year'];?>
<?php  $list_with_defect = isset($_REQUEST['list_with_defect']) ? $_REQUEST['list_with_defect'] :$sanR_data['list_with_defect'];?>
<?php  $registration_date = isset($_REQUEST['regis_date']) ? $_REQUEST['regis_date'] :$sanR_data['regis_date'];?>
<input type="hidden" name="c_case_type" value="<?php echo $case_type;?>" />
<input type="hidden" name="c_case_year" value="<?php echo $case_no;?>" />
<input type="hidden" name="c_case_no" value="<?php echo $case_no;?>" />
<table>
<tr>
<span class="error">*</span>Bench:
<select name="bench_type" <?php if($filing_no_link!=''){ echo 'disabled="disabled"';}?>>

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
<span class="error">*</span>Case Type:
<select name="case_type" <?php if($filing_no_link!=''){ echo 'disabled="disabled"';}?>>

<?php
$st = $db->prepare("select * from case_type where status = 't' order by case_type_desc asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ctc=htmlspecialchars($row['id']);
if($case_type == $ctc)
{
print "<option value=".htmlspecialchars($row['id'])." selected>".htmlspecialchars($row['case_type_desc'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['id']).">".htmlspecialchars($row['case_type_desc'])."</option>";
}
}

?>

</select>
<font face="Verdana" size="2"><span class="error">*</span>Case No:</font>

<input type="text" id="cno" maxlength="7" autocomplete="off" size="6" name="case_no" value="<?php print htmlspecialchars(ltrim($case_no,0)); ?>"   <?php if($filing_no_link!=''){ echo 'disabled="disabled"';}?>/>&nbsp;&nbsp;
<font face="Verdana" size="2"><span class="error">*</span>Case Year:</font>
<input type="text" id="cy" maxlength="4" autocomplete="off" size="4" name="case_year" value="<?php if($case_year!=''){print htmlspecialchars($case_year);}else{ print htmlspecialchars($curYear);} ?>"  <?php if($filing_no_link!=''){ echo 'disabled="disabled"';}?>/>
<?php } ?>
</tr>
</table>
</tr>
<tr><td align="left" valign="top" colspan="1" width="40%" >

<?php



if($filing_no_link1 != '')
{
$st = $db->prepare("select * from $schemas.case_detail where filing_no=?");

$st->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
$st->execute();


while ($rw2 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$pt_name=($rw2['pet_name']);
$rs_name=($rw2['res_name']);
$status=($rw2['status']);
}
echo "<h2><font color='red'><center>";
echo ucwords(strtoupper($pt_name));
echo "</font><br><font color='blue'>Vs.</font><br><font color='red'>";
echo ucwords(strtoupper($rs_name));

echo "</center></font></h2>";
?></td>	<td align="left" valign="top" colspan="4" >
<?php
echo "<br>";
echo "<h2><font color='red'><center>";
if($status =='P'){echo htmlspecialchars("CASE IS PENDING")."</center></font></h2>";}



if( $filing_no_link1!='' and $status=='D')
{
echo "</center><font color='red'></h2>";
echo htmlspecialchars("CASE IS DISPOSED");
echo "</center></font></h2>";
echo "<br>";
$st = $db->prepare("select * from $schemas.case_disposal where filing_no=? ");
$st->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
$st->execute();
while ($rw4 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$filing_no=htmlspecialchars($rw4['filing_no']);
$disposal_date=htmlspecialchars($rw4['disposal_date']);
$disposal_nature=htmlspecialchars($rw4['disposal_nature']);
$court_no=htmlspecialchars($rw4['court_no']);
$bench_nature=htmlspecialchars($rw4['bench_nature']);
$bench_no=htmlspecialchars($rw4['bench_no']);
$remarks=htmlspecialchars($rw4['remarks']);

}

echo "<table  border='1' class='table'  align='center'> ";
echo "<tr><td >";
echo "</center><font color='blue'></h3>";
echo 'Disposal Date:'.htmlspecialchars($disposal_date);
echo "</center></font></h3>";
echo "</td></tr>";
echo "<tr><td>";
echo "</center><font color='blue'></h3>";
$dispose_court_name = get_display_court_text($db,$schemas,$court_no);
echo 'Court No.:'.htmlspecialchars($dispose_court_name);
echo "</center></font></h3>";
echo "</td></tr>";
/* echo "<tr><td>";
echo "</center><font color='blue'></h3>";
echo 'Bench:'.htmlspecialchars($bench_nature);
echo "</center></font></h3>";
echo "</td></tr>"; */
echo "<tr><td>";
echo "</center><font color='blue'></h3>";
echo 'Disposal Remarks:'.htmlspecialchars($remarks);
echo "</center></font></h3>";
echo "</td></tr>";

echo "</table>";

//==================================For IA/MA==========================================
$selected_child = selected_final_cases($schemas,$db,$filing_no_link1,1,$listdate_entire,$bench_code1,$list_flag,$court_no_link,'I',''); 
if(!empty($selected_child)){ ?>
	<tr>
	<?php 
	foreach($selected_child as $key=>$value){ ?>
		<td>
			<input class="checkbox11" type="checkbox" id="child_<?php echo $value['filing_no']; ?>" name="checkbox[]" value="<?php echo htmlspecialchars($value['filing_no']); ?>" onClick="return getform(this.id,this.value,'<?php echo $filing_no_link1; ?>','<?php echo $listdate_entire; ?>','<?php echo $bench_code1; ?>','<?php echo $list_flag; ?>','<?php echo $court_no_link; ?>','I','P');">
<?php echo htmlspecialchars("$value[case_type_short_name]/$value[case_no]($value[short_name])/$value[case_year]"); ?>
		</td>
		<td id='form_<?php echo $value['filing_no']; ?>'>
		</td>
	</tr>
<?php
	}
}
$selected_child = selected_final_cases($schemas,$db,$filing_no_link1,1,$listdate_entire,$bench_code1,$list_flag,$court_no_link,'C',''); 
if(!empty($selected_child)){ ?>
	<tr>
	<?php 
	foreach($selected_child as $key=>$value){ ?>
		<td>
			<input class="checkbox11" type="checkbox" id="child_<?php echo $value['filing_no']; ?>" name="checkbox['<?php echo $value['filing_no'] ?>']" value="<?php echo htmlspecialchars($value['filing_no']); ?>" onClick="return getform(this.id,this.value,'<?php echo $filing_no_link1; ?>','<?php echo $listdate_entire; ?>','<?php echo $bench_code1; ?>','<?php echo $list_flag; ?>','<?php echo $court_no_link; ?>','C','P');">
<?php echo htmlspecialchars("$value[case_type_short_name]/$value[case_no]($value[short_name])/$value[case_year]"); ?>
		</td>
		<td id='form_<?php echo $value['filing_no']; ?>'>
		</td>
	</tr>
<?php
	}
}
die();
}

?>
</td></tr>
<tr><td align="center" colspan="7"><b>Quorum</b></td></tr>
<tr><td align="center" colspan="7"><font face="Verdana" size ="2" ><b>
<?php

$stat="select * from $schemas.bench where  bench_nature ='$list_before_link' and from_list_date='$listdate_entire' and  bench_no='$bench_code1'";
$stat=$db->prepare("$stat");
$stat->execute();
while ($row = $stat->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$bench_no= $row['bench_no'];
$court_no =$row['court_no'];
$presiding=$row['presiding'];
$stat1="select *  from $schemas.master_judge where judge_code ='$presiding'";
$stat1 = $db->prepare($stat1);
$stat1->execute();
$presiding1 = $stat1->fetch();
//echo $gen=$presiding1['gen']."&nbsp";
echo  $presiding1['judge_name'];
$hon_text=$presiding1['hon_text'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$presiding1['judge_desg_code'], PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ".$desg_name = $stat1->fetchColumn();
}
?>
<br>

<?php
 $stat2="select distinct(judge_code) as judge_code from $schemas.bench_judge where bench_nature='$list_before_link' and from_list_date='$listdate_entire' and judge_code !='$presiding' and bench_no='$bench_code1'";
$stat2=$db->prepare("$stat2");
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
echo $judge_name = $judge_data['judge_name'];
$desg_code = $judge_data['judge_desg_code'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$desg_code, PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ".$desg_name = $stat1->fetchColumn();
echo "<br/>";
}
?>
</font>
</td></tr>
<tr>
</tr>
<?php
if($filing_no_link1 !=''){
/* $sttrw = $db->prepare("select listing_date,court_no from $schemas.case_allocation where filing_no=?");
$sttrw->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
$sttrw->execute();
$rw2w = $sttrw->fetch(PDO::FETCH_ASSOC);
if(!empty($rw2w)){
if($rw2w[listing_date] !='' && $rw2w[listing_date] !='1111-11-11'){
list($y,$m,$d) = explode("-",$rw2w[listing_date]);
$listing_date_next = $d.'/'.$m.'/'.$y;
echo '<tr><td colspan="10"  face="Verdana" style="color:blue; font-size: 14px;"> This Case already listed on date '.$listing_date_next.' in court no. '.$rw2w[court_no].' </td></tr>';

}
if($rw2w[listing_date] !='' && $rw2w[listing_date] =='1111-11-11'){

echo '<tr><td colspan="10"  face="Verdana" style="color:blue; font-size: 14px;">Listing date not Fixed </td></tr>';

}
} */

?>
<tr><td></td></tr>

<tr><td colspan="10"><?php

?>
Petitioner Legal Practitioner : &nbsp; <b>
<?php
$st12=$db->prepare("select * from e_more_representative where filing_no=? and party_flag='P' ");
	$st12->bindParam(1, $filing_no_link1, PDO::PARAM_STR);

	$st12->execute();
	while ($row12 = $st12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{

	     $adv_id = htmlspecialchars($row12['rep_code']);


	$stqq12 = $db->prepare("select rep_name from e_master_advocate where id=?");
	$stqq12->bindParam(1, $adv_id, PDO::PARAM_INT);
	$stqq12->execute();
	  $pet_advname22 = $stqq12->fetchColumn();
	 ?>
	<font face="Verdana" size ="2">
	 <?php
	  echo strtoupper($pet_advname22).'<br>';
	}
	?>


</b>

</b></td></tr>
<tr><td colspan="10">

Respondent Legal Practitioner : &nbsp; <b>
<?php
$st121=$db->prepare("select * from e_more_representative where filing_no=? and party_flag='R' ");
	$st121->bindParam(1, $filing_no_link1, PDO::PARAM_STR);

	$st121->execute();
	while ($row121 = $st121->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{

	     $res_adv_id = htmlspecialchars($row121['rep_code']);


	$stqq121 = $db->prepare("select rep_name from e_master_advocate where id=?");
	$stqq121->bindParam(1, $res_adv_id, PDO::PARAM_INT);
	$stqq121->execute();
	  $res_advname22 = $stqq121->fetchColumn();
	 ?>
	<font face="Verdana" size ="2">
	 <?php
	  echo strtoupper($res_advname22).'<br>';
	}
?>


</td></tr>
<?php  $pen_dis = '';
$query = "select * from $schemas.case_proceeding where filing_no = ? and listing_date = ? and bench_no = ? and court_no = ? order by entry_date desc limit 1";
$is_case_proceeded= $db->prepare($query);
$is_case_proceeded->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
$is_case_proceeded->bindParam(2, $listdate_entire, PDO::PARAM_STR);
$is_case_proceeded->bindParam(3, $bench_code1, PDO::PARAM_STR);
$is_case_proceeded->bindParam(4, $court_no_link, PDO::PARAM_STR);
$is_case_proceeded->execute();
$res = $is_case_proceeded->fetchAll();
 if(!empty($res)){
	$res = array_shift($res);
	$proceeded_status = (!empty($res['todays_status']))?$res['todays_status']:'';
	$next_list_date_selection_option = (!empty($res['next_list_date_selection_option']))?$res['next_list_date_selection_option']:0;
	$next_list_date_selection_type = (!empty($res['next_list_date_selection_type']))?$res['next_list_date_selection_type']:0;
	$next_list_date_selection_type_value = (!empty($res['next_list_date_selection_type_value']))?$res['next_list_date_selection_type_value']:'';
	$proceeded_next_list_date = (!empty($res['next_list_date']))?$res['next_list_date']:'';
	$next_list_court = (!empty($res['next_listing_court']))?$res['next_listing_court']:0;
	if($next_list_date_selection_option == '2'){
		$proceeded_next_list_date = '';
	}
	if($proceeded_next_list_date != ''){
		list($py,$pm,$pd) = explode('-',$proceeded_next_list_date);
		$proceeded_next_list_date_show = $pd.'/'.$pm.'/'.$py;
	}
	$proceeded_purpose = (!empty($res['purpose']))?$res['purpose']:'';
	$proceeded_next_list_purpose = (!empty($res['next_list_purpose']))?$res['next_list_purpose']:'';
	$proceeded_todays_action = (!empty($res['todays_action']))?$res['todays_action']:'';
	$proceeded_remarks = (!empty($res['remarks']))?$res['remarks']:'';
	$for_stay = (!empty($res['for_stay']))?$res['for_stay']:0;
}else{
	$proceeded_todays_action=$proceeded_next_list_purpose=$proceeded_purpose=$proceeded_next_list_date=$proceeded_status=$proceeded_next_list_date_show=$proceeded_remarks='';
		$next_list_date_selection_option = 1;
		$next_list_date_selection_type = 1;
		$next_list_date_selection_type_value = '';
} 
?>

</table>
<form name="frm" method="POST" action="case_proceeding_with_connected_action.php">
<input type="hidden" name="listing_date" value="<?php echo htmlspecialchars($listing_date); ?>">
<input type="hidden" name="judge" value="<?php echo htmlspecialchars($judgecode);?>" />
<input type="hidden" name="c_date" value="<?php echo htmlspecialchars($c_date);?>" />
<input type="hidden" name="c_case" value="<?php echo htmlspecialchars($c_case);?>" />
<input type="hidden" name="benchnature" value="<?php echo htmlspecialchars($benchnature);?>" />
<input type="hidden" name="courtno" value="<?php echo htmlspecialchars($courtno);?>" />
<input type="hidden" name="no" value="<?php echo $_REQUEST['no'];?>" />
<input type="hidden" name="list_date_link" value="<?php echo htmlspecialchars($list_date_link); ?>">
<input type="hidden" name="filing_no" value="<?php print htmlspecialchars($filing_no_link1);?>" />
<div class="table-responsive">
<table cellspacing="5px" class="table table-hover table-bordered table-stripped">
<thead>
</thead>
<tbody>
<tr>
	<td style="width:500px;text-align:center;">
	<?php 
	$st = $db->prepare("select short_name from case_type where status = 't' and id = '$case_type'");
	$st->execute();
	$case_type_name = $st->fetchColumn();
	echo "$case_type_name/$case_no/$case_year"; ?>
	</td>
	<td id='form_<?php echo $filing_no_link1; ?>'>
		<table>
		<tr><td align="right" width="150"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">*Todays Status
		</font></td>

		<td align="left"><input type="radio" name="pen_dis<?php echo $filing_no_link1; ?>" value="P" <?php if($pen_dis=="P" or $pen_dis=='') print "checked";?> onClick="return getform(this.id,'<?php echo $filing_no_link1; ?>','<?php echo $filing_no_link1; ?>','<?php echo $listdate_entire; ?>','<?php echo $bench_code1; ?>','<?php echo $list_flag; ?>','<?php echo $court_no_link; ?>','M','P');"><b>Pending</b>
		<input type="radio" name="pen_dis<?php echo $filing_no_link1; ?>" value="D" <?php if($pen_dis=="D" || $pen_dis=="X")	print "checked";?> onClick="return getform(this.id,'<?php echo $filing_no_link1; ?>','<?php echo $filing_no_link1; ?>','<?php echo $listdate_entire; ?>','<?php echo $bench_code1; ?>','<?php echo $list_flag; ?>','<?php echo $court_no_link; ?>','M','D');">
		<b>Disposal</b></td>
		</tr>
		<!--<tr><td align="right" width="150"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Stay by GSTAT
		</font></td>

		<td align="left"><input type="radio" id='for_stay_<?php echo $filing_no_link1; ?>' name="for_stay<?php echo $filing_no_link1; ?>" value="1" <?php if($for_stay=="1") print "checked";?> ><b>Yes</b>
		<input type="radio" id='for_stay_<?php echo $filing_no_link1; ?>' name="for_stay<?php echo $filing_no_link1; ?>" value="0" <?php if($for_stay=="0" or $for_stay=='')	print "checked";?> >
		<b>No</b></td>
		</tr>-->
		<?php

		//===================pending==============================================================
		if($pen_dis=='P' or $pen_dis=='')
		{?>
		<tr><td align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">* Todays Action Type</font></td>
		<?php
		if($list_with_defect == 1 && empty($registration_date) && $list_before_link == '3')
			$query = "select * from $schemas.master_action where status='P' and action_code in (45,47)";
		else if($list_with_defect == 1 && empty($registration_date) && $list_before_link != '3')
			$query = "select * from $schemas.master_action where status='P' and action_code in (44,45)";
		else
			$query = "select * from $schemas.master_action where status='P' and action_code not in (44,45,47)";

		$st= $db->prepare($query);
		$st->execute();
		?>
		<td align="left" colspan="2">
		<select name="action_type<?php echo $filing_no_link1; ?>" id="action_type<?php echo $filing_no_link1; ?>" style="width: 250px;" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
		<option value="">Select</option>
		<?php  //if($action_type=='') $action_type=3;

		while ($row2 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		$action_code=$row2['action_code'];
		?>
		<option <?php echo ($proceeded_todays_action==$action_code)?'selected':''; ?> value="<?php echo htmlspecialchars($action_code); ?>">
		<?php echo htmlspecialchars(ucwords($row2['action_type']));?></option><?php
		} ?>
		</select>
		</td>
		</tr>
		<?php
		$purpose_code='';
		$purpose_old='';
		$st= $db->prepare("select purpose from $schemas.case_allocation where filing_no=? order by listing_date desc limit 1");
		$st->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
		$st->execute();
		$purpose_old =$st->fetchColumn();
		?>

		<input type="hidden" name="purpose_old<?php echo $filing_no_link1; ?>" value="<?php echo htmlspecialchars($purpose_old); ?>">
		<tr>
		<td align="right" nowrap="nowrap"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" nowrap="nowrap">
		<span class="error">*</span>Next listing purpose</font>
		</td>

		<td colspan="2">
		<select name="purpose_code<?php echo $filing_no_link1; ?>" id='purpose_code<?php echo $filing_no_link1; ?>' style="width: 250px" onChange="return set_remove_lisitng_date('<?php echo $filing_no_link1; ?>',this.value)">
		<option value="">Select</option>
		<?php
		$display='Y';
		$st= $db->prepare("select * from $schemas.master_purpose where display=?  order by purpose_name asc");
		$st->bindParam(1, $display, PDO::PARAM_STR);
		$st->execute();
		while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		$purposecode=$row['purpose_code'];
		?>
		<option <?php echo ($proceeded_next_list_purpose==$purposecode)?'selected':''; ?> value="<?php echo htmlspecialchars($purposecode);?>" >
		<?php echo htmlspecialchars(ucwords($row['purpose_name']));?>
		</option>
		<?php
		}

		?>
		</select>


		</td>

		</tr>

		<tr id="listdate_option_element<?php echo $filing_no_link1; ?>">
		<td align="left" nowrap="nowrap"><div align="right">
		<span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Chose Option </div></td>

		<td width="50">
		<?php  $choose_option = isset($_REQUEST['choose_option']) ? $_REQUEST['choose_option'] :1;?>
		<label class="radio-inline">
	      <input type="radio" class="choose_option" name="choose_option_<?php echo $filing_no_link1; ?>" id="fixed_<?php echo $filing_no_link1; ?>" value="1" <?php echo ($next_list_date_selection_option == '1')?'checked':''; ?> onChange="return changePendingForm(this.value,<?php echo $filing_no_link1 ?>);">Fixed
	    </label>
	    <label class="radio-inline">
	      <input type="radio" class="choose_option" name="choose_option_<?php echo $filing_no_link1; ?>" id="not_fixed_<?php echo $filing_no_link1; ?>" value="2" <?php echo ($next_list_date_selection_option == '2')?'checked':''; ?> onChange="return changePendingForm(this.value,<?php echo $filing_no_link1 ?>);">Not Fixed
	    </label>
		</td>

		</tr>

		<?php if($next_list_date_selection_option != '1'){
			$style = "display:none";
		}else{
			$style_not_fixed = "display:none";
		} ?>

		<tr style="<?php echo $style; ?>" id="list_date_element<?php echo $filing_no_link1; ?>">
		<td align="left" nowrap="nowrap"><div align="right">
		<span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Next Listing Date </div></td>

		<td width="50">
		<?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :'';?>
		<input type="text" required  autocomplete="off" name="next_list_date<?php echo $filing_no_link1; ?>"  class="datepickerGreater" readonly id="datepickerGreaterR<?php echo $filing_no_link1; ?>" size="10" maxlength="10" value="<?php print htmlspecialchars($proceeded_next_list_date_show); ?>" data-date-format="dd/mm/yyyy"/>
		</td>

		</tr>

		<tr style="<?php echo $style_not_fixed; ?>" id="not_fixed_element<?php echo $filing_no_link1; ?>">
		<td align="left" nowrap="nowrap"><div align="right">
		<span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Select Option </div></td>

		<td width="50">
		<?php  $not_fixed_element = isset($_REQUEST['not_fixed_element']) ? $_REQUEST['not_fixed_element'] :1;?>
		<label class="radio-inline">
	      <input type="radio" class="not_fixed_element" name="not_fixed_element_<?php echo $filing_no_link1; ?>" id="days_<?php echo $filing_no_link1; ?>" value="1" <?php echo ($next_list_date_selection_type == '1')?'checked':''; ?>>Days
	    </label>
	    <label class="radio-inline">
	      <input type="radio" class="not_fixed_element" name="not_fixed_element_<?php echo $filing_no_link1; ?>" id="weeks_<?php echo $filing_no_link1; ?>" value="2" <?php echo ($next_list_date_selection_type == '2')?'checked':''; ?>>Weeks
	    </label>
	    <label class="radio-inline">
	      <input type="radio" class="not_fixed_element" name="not_fixed_element_<?php echo $filing_no_link1; ?>" id="month_<?php echo $filing_no_link1; ?>" value="3" <?php echo ($next_list_date_selection_type == '3')?'checked':''; ?> >Months
	    </label>
	    <?php  $not_fixed_date = isset($_REQUEST['not_fixed_date']) ? $_REQUEST['not_fixed_date'] :'';?>
	    <label class="radio-inline">
	      <input type="number" class="not_fixed_date" name="not_fixed_date_<?php echo $filing_no_link1; ?>" id="not_fixed_date_<?php echo $filing_no_link1; ?>" value="<?php echo $next_list_date_selection_type_value; ?>">
	    </label>
		</td>

		</tr>

		<tr id="list_date_court<?php echo $filing_no_link1; ?>">
		<td align="left" nowrap="nowrap"><div align="right">
		<span class="error"></span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Next Listing Court </div></td>

		<td width="50">
		<?php // $next_list_court = isset($_REQUEST['next_list_court']) ? $_REQUEST['next_list_court'] :'';?>
		<select name="next_list_court<?php echo $filing_no_link1; ?>" id='next_list_court<?php echo $filing_no_link1; ?>'>
		<option value="">Select</option>
		<?php
		$display='Y';
		$st= $db->prepare("select * from $schemas.court where court_no < 50  order by court_no asc");
		$st->execute();
		while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		$selected_court=$row['court_no'];
		?>
		<option <?php echo ($next_list_court==$selected_court)?'selected':''; ?> value="<?php echo htmlspecialchars($selected_court);?>" >
		<?php echo htmlspecialchars(ucwords($row['display_court_text']));?>
		</option>
		<?php
		}

		?>
		</select>
		</td>

		</tr>

<?php
			} 
		//=======================code for disposal=================================================
		else
		{
		?>

		<tr>
		<td align="left" nowrap="nowrap">
		<div align="right"><span class="error">*</span>Disposal Nature
		</div>
		</td>
		<?php  $disposal_nature = isset($_REQUEST['disposal_nature']) ? $_REQUEST['disposal_nature'] :'';?>
		<td colspan="2">
		<select size="1" style="width: 250" name="disposal_nature<?php echo $filing_no_link1; ?>" id="disposal_nature<?php echo $filing_no_link1; ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)" onChange="return changeForm(this.value,'<?php echo $filing_no_link1; ?>');">
		<option value="">Select</option>
		<?php
		$status='D';
		$st="select supply_disputed_questions,refile_count from e_case_detail where filing_no = ?";
		$st=$db->prepare($st);
		$st->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
		$st->execute();
		$case_detail = $st->fetch();
		$refile_count = $case_detail['refile_count'];
		$supply_disputed_questions = $case_detail['supply_disputed_questions'];
		
		if($list_with_defect == 1 && empty($registration_date)){
			if($supply_disputed_questions == '1' && $refile_count == '1'){
			$query = "select * from $schemas.master_action where status='D' and action_code in (2,3,23,38,39,41,51,52)";
			}else{
				$query = "select * from $schemas.master_action where status='D' and action_code in (2,3,23,38,39,41,52)";
			}
		}
		else{
			$query = "select * from $schemas.master_action where status='D' and action_code not in (38,52)";
		}

		$st= $db->prepare($query);
		$st->execute();
		
		while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		$actioncode = $row['action_code'];
		if($actioncode == $disposal_nature)
		{
		print "<option value=".htmlspecialchars($row['action_code'])." selected>".htmlspecialchars($row['action_type'])."</option>";
		}
		else
		{
		print "<option value=".htmlspecialchars($row['action_code']).">".htmlspecialchars($row['action_type'])."</option>";
		}
		}
		?>
		</select>
		</td>
		</tr>
		<?php  $disposal_date = isset($_REQUEST['disposal_date']) ? $_REQUEST['disposal_date'] :'';?>
		<tr id="set_disposal_date<?php echo $filing_no_link1; ?>">
		<td align="left" nowrap="nowrap">
		<div align="right"><span class="error">*</span>Disposal Date
		</div>
		</td>

		<td colspan="2">
		<input type="text"  autocomplete="off" name="disposal_date<?php echo $filing_no_link1; ?>" id='disposal_date<?php echo $filing_no_link1; ?>' style="width: 250" readonly="readonly" size="10" maxlength="10" class="datepickerToday" value="<?php //print htmlspecialchars($order_date); ?>"/>
		</td>
		</tr>

		<?php
		}// close of else condition
		?>


		<tr>


		<?php
		$st= $db->prepare("select remarks from $schemas.case_proceeding where filing_no=?");
		$st->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
		$st->execute();
		$remarks = $st->fetchColumn();
		?>

		<?php  $remarks = isset($_REQUEST['remarks']) ? $_REQUEST['remarks'] :'';?>
		<tr>
		<td width="200" align="right"><font face="Verdana" size="2">Proceeding Remark</font></td>
		<td width="400" align="left">
		<textarea class="formInput" placeholder="Your Message" id="remarks<?php echo $filing_no_link1; ?>" name="remarks<?php echo $filing_no_link1; ?>"
		maxlength="1400" cols="50" rows="2" onFocus="SetBg(this)" onBlur="UnSetBg(this)"><?php echo $proceeded_remarks; ?></textarea>
		</td>
		</tr>
		</table>
	</td>
</tr>


<?php

//==================================For IA/MA==========================================
$selected_child = selected_final_cases($schemas,$db,$filing_no_link1,1,$listdate_entire,$bench_code1,$list_flag,$court_no_link,'I',''); 
if(!empty($selected_child)){ ?>
	<tr>
	<?php 
	foreach($selected_child as $key=>$value){ ?>
		<td>
			<input class="checkbox11" type="checkbox" id="child_<?php echo $value['filing_no']; ?>" name="checkbox[]" value="<?php echo htmlspecialchars($value['filing_no']); ?>" onClick="return getform(this.id,this.value,'<?php echo $filing_no_link1; ?>','<?php echo $listdate_entire; ?>','<?php echo $bench_code1; ?>','<?php echo $list_flag; ?>','<?php echo $court_no_link; ?>','I','P');">
<?php echo htmlspecialchars("$value[case_type_short_name]/$value[case_no]($value[short_name])/$value[case_year]"); ?>
		</td>
		<td id='form_<?php echo $value['filing_no']; ?>'>
		</td>
	</tr>
<?php
	}
}


if(!empty($main_case_fn)){
$selected_child = selected_final_cases($schemas,$db,$main_case_fn,1,$listdate_entire,$bench_code1,$list_flag,$court_no_link,'I',$filing_no_link1); 
if(!empty($selected_child)){ ?>
	<tr>
	<?php 
	foreach($selected_child as $key=>$value){ ?>
		<td>
			<input class="checkbox11" type="checkbox" id="child_<?php echo $value['filing_no']; ?>" name="checkbox[]" value="<?php echo htmlspecialchars($value['filing_no']); ?>" onClick="return getform(this.id,this.value,'<?php echo $filing_no_link1; ?>','<?php echo $listdate_entire; ?>','<?php echo $bench_code1; ?>','<?php echo $list_flag; ?>','<?php echo $court_no_link; ?>','I','P');">
<?php echo htmlspecialchars("$value[case_type_short_name]/$value[case_no]($value[short_name])/$value[case_year]"); ?>
		</td>
		<td id='form_<?php echo $value['filing_no']; ?>'>
		</td>
	</tr>
<?php
	}
}
}


$selected_child = selected_final_cases($schemas,$db,$filing_no_link1,1,$listdate_entire,$bench_code1,$list_flag,$court_no_link,'C',''); 
if(!empty($selected_child)){ ?>
	<tr>
	<?php 
	foreach($selected_child as $key=>$value){ ?>
		<td>
			<input class="checkbox11" type="checkbox" id="child_<?php echo $value['filing_no']; ?>" name="checkbox['<?php echo $value['filing_no'] ?>']" value="<?php echo htmlspecialchars($value['filing_no']); ?>" onClick="return getform(this.id,this.value,'<?php echo $filing_no_link1; ?>','<?php echo $listdate_entire; ?>','<?php echo $bench_code1; ?>','<?php echo $list_flag; ?>','<?php echo $court_no_link; ?>','C','P');">
<?php echo htmlspecialchars("$value[case_type_short_name]/$value[case_no]($value[short_name])/$value[case_year]"); ?>
		</td>
		<td id='form_<?php echo $value['filing_no']; ?>'>
		</td>
	</tr>
	<?php
	$selected_con_ia = selected_final_cases($schemas,$db,$value['filing_no'],1,$listdate_entire,$bench_code1,$list_flag,$court_no_link,'I',''); 
		if(!empty($selected_con_ia)){ ?>
			<tr>
			<?php 
			foreach($selected_con_ia as $key=>$value){ ?>
				<td>
					<input class="checkbox11" type="checkbox" id="child_<?php echo $value['filing_no']; ?>" name="checkbox[]" value="<?php echo htmlspecialchars($value['filing_no']); ?>" onClick="return getform(this.id,this.value,'<?php echo $filing_no_link1; ?>','<?php echo $listdate_entire; ?>','<?php echo $bench_code1; ?>','<?php echo $list_flag; ?>','<?php echo $court_no_link; ?>','I','P');">
		<?php echo htmlspecialchars("$value[case_type_short_name]/$value[case_no]($value[short_name])/$value[case_year]"); ?>
				</td>
				<td id='form_<?php echo $value['filing_no']; ?>'>
				</td>
			</tr>
		<?php
			}
		}
	}
}
?>




<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />

<tr>
<td colspan="8" align="left" valign="top"><div align="center">
<input type="submit" name="submit1" value="Submit" class="button btn-primary" onClick="return ();">
</div></td>
</tr>





</td>
</tr>

<?php }?>

 </td></tr> 
 </tbody>
 </table>
</div>

      </form>
  </body></div></div></div>
        <!-- /.box-footer-->
      </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
<script>

function changeForm(value,filing_no){
	if(value == '46'){
		$("#disposal_date"+filing_no).val('');
		$("#disposal_date"+filing_no).attr('disabled', 'disabled');
	}
	else{
		$("#disposal_date"+filing_no).val('');
		$("#disposal_date"+filing_no).removeAttr("disabled");
	}
}

function changePendingForm(value,filing_no){
	// $("#datepickerGreaterR"+filing_no).val('');
	// $('input[name=not_fixed_element_'+filing_no+']').val('');
	// $('input[name=choose_option_'+filing_no+']').val('');
	// $("#not_fixed_date_"+filing_no).val('');
	if(value == '1'){
		$("#list_date_element"+filing_no).show();
		$("#not_fixed_element"+filing_no).hide();
	}
	else{
		$("#list_date_element"+filing_no).hide();
		$("#not_fixed_element"+filing_no).show();
	}
}

function set_remove_lisitng_date(filing_no,purpose){
	if(purpose == '19' || purpose == '27' || purpose == '28'){
		$("#list_date_element"+filing_no).hide();
		$("#listdate_option_element"+filing_no).hide();
		$("#not_fixed_element"+filing_no).hide();

		// $("#datepickerGreaterR"+filing_no).val('');
		// $('input[name=not_fixed_element_'+filing_no+']').val('');
		// $('input[name=choose_option_'+filing_no+']').val('');
		// $("#not_fixed_date_"+filing_no).val('');
		
	}
	else {
		//$("#list_date_element"+filing_no).show();
		//$("#not_fixed_date_"+filing_no).val('');
		$("#listdate_option_element"+filing_no).show();
		//$('input[name=choose_option_'+filing_no+']').val(1);
		let selected_option = $('input[name=choose_option_'+filing_no+']:checked').val();
		
		if(selected_option == '1'){
			$("#list_date_element"+filing_no).show();
			$("#not_fixed_element"+filing_no).hide();
			//$("#datepickerGreaterR"+filing_no).val('');
			//$('input[name=not_fixed_element_'+filing_no+']').val('');
		}else{
			$("#list_date_element"+filing_no).hide();
			$("#not_fixed_element"+filing_no).show();
			//$("#datepickerGreaterR"+filing_no).val('');
			//$('input[name=not_fixed_element_'+filing_no+']').val(1);
		}
		
	}
}

function addition_parameters(filing_no,action_type){
	if(action_type == '31' || action_type == '8' || action_type == '38'){
		$.ajax({
            type: 'POST',
            url: 'proceeding_ajax.php',
            data: {type:'additional_parameters',filing_no:filing_no,action_type:action_type},
            beforeSend: function(){
            	$(".additional_param").remove();
				$("#action_type"+filing_no).closest('tr').after('<tr><td colspan=2 class="loader_add_param">Loading........</td></tr>');
            },
            success: function(response){ //console.log(response);
            	$(".loader_add_param	").remove();
            	if(response === 403){
            		$(".additional_param").remove();
            	}else{
            		$("#action_type"+filing_no).closest('tr').after(response);
            	}
            },
			error: function (textStatus, errorThrown) {
				$(".additional_parameters").remove();
				console.log(textStatus);
				alert("something went wrong");
			   
			}
        });
	}else{
		$(".additional_param").remove();
	}	
}


function getform(id,filing_no,parent_filing_no,listing_date,bench_no,list_flag,court_no,child_or_connected,status){
	if(child_or_connected != 'M'){
		var checkBox = document.getElementById(id);
		var check_checkbox = checkBox.checked;
	}else{
		check_checkbox = false;
	}
	if (check_checkbox == true || child_or_connected == 'M'){
		$.ajax({
            type: 'POST',
            url: 'case_proceeding_ajax.php',
            data: {type:'get_form',checkbox_id:id,filing_no:filing_no,parent_filing_no:parent_filing_no,listing_date:listing_date,bench_no:bench_no,list_flag:list_flag,court_no:court_no,child_or_connected:child_or_connected,status:status},
            beforeSend: function(){
			$("#form_"+filing_no).html('Loading........');
            },
            success: function(response){ //console.log(response);
				$("#form_"+filing_no).html(response);
            },
			error: function (textStatus, errorThrown) {
				console.log(textStatus);
				alert("something went wrong");
			   
			}
        });
	  } else {
		$("#form_"+filing_no).html('');
	  }
		
}
</script>

  <?php
  //include '../infooter.php';
  ?>
  <?php } ?>

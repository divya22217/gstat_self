<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
include("../master/functions.php");
//include '../custom/custom_function.php';
session_start();

 //  ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);  

$bench_no='';
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$user_court = $_SESSION['user_court'];
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


$sql_prps = "select * from $schemas.master_purpose";
$sth5 = $db->prepare($sql_prps);
$sth5->execute();
$purposeAll = $sth5->fetchAll();
$main_cases = main_case_type();

?>
<?php 
include '../inheader.php';
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
		
		action = "old_case_list.php";
		submit();
	}
}
function popsurety_pending_report(cfy)

    {
    	
    		var url = "./case_gen_view.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");   
    		
    }
function submitForm21()
{
 	with(document.frm)
	{
		action = "old_case_list.php";
		submit();
	}
}
function submitForm1()
{
 	with(document.frm)
	{

	action = "old_case_list_action.php";
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

function notify_cases()
{
 	with(document.frm)
	{

		var filing_case = $(".checkbox").is(":checked");
		if (filing_case == false)
		{
		        alert("Please select at least one case.");
		        return false;
		}
		document.frm.action = 'notify_cases_action.php';
		
		submit();
	}
}

</script>

</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Old Case Listing</title>
	<script src="../bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="../dist/js/adminlte.min.js"></script>
    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
		<div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
			<div class="box box-success">
				<div class="box-body">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    
        <center><b>Old Case Listing ( All <font color="red">*</font></span> is mandatory Field )</b></center>
     
    </section>
	</br>
   
  <?php 


$msghash=$_REQUEST['msghash'];
if($msghash !='')
{
$msghashz=(base64_decode($msghash));
$msghashz = htmlentities(htmlspecialchars($msghashz));

$msghashz = explode("@", $msghashz);

$msg1 = $msghashz[0];
$case_list_date = $msghashz[1];
$from_notify = $msghashz[2];

list($cyear,$cmonth,$cday)=explode('-',$case_list_date);

	  $case_list_date_dis=$cday.'/'.$cmonth.'/'.$cyear;
}




if($msg1 !='')
{
?>
<div class="from-group row">
<center>
	<?php if(!empty($from_notify) && $from_notify = 'notify') { ?>
		<b><font color = 'red'  ><?php echo $msg1;?> </font></b>
	<?php } else {	?>
		<b><font color = 'red'  ><?php echo $msg1;?> <br/> <a href="javascript:popsurety_pending_report('<?php echo htmlspecialchars($case_list_date);?>');" > Click here to view listed cases. </a></font></b></u>
	<?php } ?>
	</center>
</div>
<?php
}
?>

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css"/>
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
<script src="../src/calendar.js"></script>


<form name="frm" method="post" action="old_case_list_action.php" >
<div class="form-group row">
	<center>
		<!--<label class="radio-inline"><input type="radio" name="b_type" value="1" checked>Daily</label>
		<label class="radio-inline"><input type="radio" name="b_type" value="2">Supplementry</label>-->
	</center>
</div>
<?php $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>
<div class="form-group row">
	<div class="col-sm-6 col-md-6">
		<label for="listing_Date" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Listing Date</label>
		<div class="col-sm-8">
			<input type="text" autocomplete="off"  name="next_list_date" id="next_list_date" class="form-control datepicker"  maxlength="10" size="10" value="<?php echo  $next_list_date; ?>" onKeyup="javascript:addNumbers(this.value)" onchange="submitForm();UnSetBg(this);" onFocus="SetBg(this)" >
		</div>
	</div>
	<!--<div class="col-sm-6 col-md-6">
		<label for="listing_Date" class="col-sm-offset-2 col-sm-2 col-form-label"><font color="red"></font></span></font>Courts</label>
		<div class="col-sm-6">
			<select class="form-control" id="courts" name="courts" onchange="submitForm();UnSetBg(this);">
				<option value="0">All</option>
				<?php 
					$sql2q=" select distinct(court_no) from $schemas.case_allocation_temp order by court_no asc";
					$sth11= $db->prepare($sql2q);
					$sth11->execute();
					$result = $sth11->fetchAll();
					foreach($result as $key => $value)
					{ 
						$display_court_value=$db->prepare("select display_court_text  from $schemas.court where court_no = ?");
						$display_court_value->bindParam(1, $value['court_no'], PDO::PARAM_STR);
						$display_court_value->execute();
						$court_name = $display_court_value->fetchColumn();
					?>
					<option value="<?php echo $value['court_no'] ?>" <?php echo ($_REQUEST['courts'] == $value['court_no'] )?'selected':''; ?>><?php echo $court_name; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>-->
</div>



<?php

if($next_list_date!='')
{
	list($day,$month,$year)=explode('/',$next_list_date);
	$court_date_new=$year.'-'.$month.'-'.$day;
	 $sql2=" select * from $schemas.bench where  from_list_date ='$court_date_new' and court_no = $user_court order by court_no asc";
	$bench_d=$db->prepare($sql2);
	 $bench_d->execute();
if($bench_d->rowCount()>0)
{
	
	 $bench_data=$bench_d->fetchAll();
 ?>
 
<div class="table-responsive"> 
	<input type="hidden" name="lis_date" value="<?php echo htmlspecialchars($court_date_new);?>" />
	<table class="table table-hovered table-bordered table-stripped">
		<thead style="background-color:#846313;color:#ffffff;">
			<th></th>
			<th>Bench No</th>
			<th>Court</th>
			<th>Hon'ble Justice</th>
			<th>Limit</th>
			<th>Avalilable</th>
		</thead>
		<tbody>
			<?php

	$flag=0;
$case_limit_avail='0';
	
	foreach($bench_data as $row2)
	{
	
	$bench_code1='';
		
		$flag=1;
		$court_no =$row2['court_no']; 
		$bench_code1 = $row2['bench_no'];
		 $limit_case = $row2['limit_case'];
		 $list_flag = $row2['list_flag'];
		  $sql1=" select count(*) from $schemas.case_allocation_temp where  listing_date ='$court_date_new' and bench_no=$bench_code1 ";
	$sql1= $db->prepare($sql1);
	$sql1->execute();
     $counter = $sql1->fetchColumn();
    $case_limit_avail =$limit_case-$counter;	
		?>
		<tr>
		<?php $bench_nocheck = isset($_REQUEST['bench_no']) ? $_REQUEST['bench_no'] : ''; ?>
		<td>
		<input type="radio"  name="bench_no"  class="bench_no"  value="<?php echo $bench_code1;?>" <?php if($bench_nocheck ==$bench_code1)echo 'checked';?> >
		</td>
		<td><?php echo $bench_code1; ?></td>
		<td><?php  
		$sql2q=" select bench_name from $schemas.bench_nature where  bench_code ='$row2[bench_nature]'";
		$sth11= $db->prepare($sql2q);
		$sth11->execute();
		 $sth11->fetchColumn(); ?>
		 <?php 
				$display_court_value=$db->prepare("select display_court_text  from $schemas.court where court_no = ?");
				$display_court_value->bindParam(1, $court_no, PDO::PARAM_STR);
				$display_court_value->execute();
				$display_court_value = $display_court_value->fetchColumn();
				 echo $display_court_value;
				echo ($list_flag == '2')?' (Supplementry)':' (Daily)';
				?>
		</td>
<?php 
if($bench_no=='')
{
	$bench_no=0;
}
	


?>
	<td>
		<?php
		$arr = array();
		$sql="select bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm where bj.from_list_date ='$court_date_new' and bj.bench_no='$bench_code1' and jm.judge_code=bj.judge_code ";
		$sth = $db->prepare($sql);
		$m=0;
		foreach($db->query($sql) as $row)
		{
			$arr[$m]=$row['judge_code'];
			$m++;
		}
		$sql="select presiding from $schemas.bench where from_list_date ='$court_date_new'  and bench_no='$bench_code1'";
		$sth = $db->prepare($sql);
		$sth->execute();
		$presiding = $sth->fetchColumn();
		$arr1 = sizeof($arr);
		for($i=0;$i<$arr1;$i++)
		{
			$jcode =$arr[$i];
			$sql = "select judge_name from $schemas.master_judge where judge_code =$jcode";
			$sth = $db->prepare($sql);
			$sth->execute();
			$judge = $sth->fetchColumn();
			?>
			<b><?php echo strtoupper($judge); ?> </b>
		<?php
			if($jcode == $presiding) { ?>
				<font color='red'><b> (PRESIDING JUSTICE )</b></font>
		<?php	} 
		echo "</br>";	} ?>
		</td>
		<td><b><?php echo strtoupper($limit_case); ?></b></td>
		<td><b><?php echo strtoupper($case_limit_avail); ?></b></td>
	</tr>
	<?php
}?>

<!--<div class="form-group row">
	<center>
		<label class="radio-inline"><input type="radio" name="b_type" value="1" checked>Daily</label>
		<label class="radio-inline"><input type="radio" name="b_type" value="2">Supplementry</label>
	</center>
</div>-->
<?php
} }
?>
		</tbody>
	</table>
</div>
<div class="table-responsive">
  <table class="table table-hovered table-bordered table-stripped std">

	<tr style="background-color:#846313;color:#ffffff;"><th>
	<b>Sr.No.</b></th>
	<th><input type="checkbox" name="allbox" onClick="un_check(this);" title="Select or Deselct ALL" style="background-color:#ccc;"/></th>
	<th align="left" width="150"><b>Diary No.</b></th>
	<th align="left" width="150"><b>Case No.</b></th>
	<th align="left" width="600"><B>Cause Title </b></th>
	<th align="left" width="150"><B>Court No. </b></th>
	<th align="left" width="150"><B>Section</b></th>
<!--	<th align="left" width="150"><B>Last Listing Date</b></th>
	
	<th align="left" width="150"><B>Last Listing Coram</b></th>
	<th align="left" width="150"><B>Last Purpose</b></th>-->
	<th align="left" width="150"><B>Next List Date</b></th>
	<th align="left" width="150"><B>Next Purpose</b></th>
	<th align="left" width="600"><B>Counsel For Parties Remark</b></th>
	
	
	
	</tr>
	<?php
$count=0;
$old_court_no = isset($_REQUEST['courts']) ? $_REQUEST['courts'] :'';
if($court_date_new){
$sql1=$db->prepare("select distinct(filing_no) from $schemas.case_allocation_temp where next_list_date='$court_date_new' and listing_date!='$court_date_new' and court_no = $user_court order by filing_no asc");
//echo "select distinct(filing_no) from $schemas.case_allocation_temp where next_list_date='$court_date_new' and listing_date!='$court_date_new' and court_no = $user_court order by filing_no asc";
$sql1->execute();
while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

  
  $filing_no =$row1['filing_no'];
  $removed_by = 0;
    $notified=$db->prepare("select count(*) as count_notified from $schemas.notified_cases where filing_no=? and old_listing_date = ? and removed_by = ?");
  $notified->bindParam(1, $filing_no, PDO::PARAM_STR);
  $notified->bindParam(2, $court_date_new, PDO::PARAM_STR);
  $notified->bindParam(3, $removed_by, PDO::PARAM_STR);
  $notified->execute();
  $is_notified = $notified->fetchColumn();
	if($is_notified){
		continue;
	}
  
	$restore_type = 5;
  $is_case_restored=$db->prepare("select count(*) as restore_check from $schemas.restored_cases where filing_no=? and disposal_date < ? and restoration_type = ?");
  $is_case_restored->bindParam(1, $filing_no, PDO::PARAM_STR);
  $is_case_restored->bindParam(2, $court_date_new, PDO::PARAM_STR);
  $is_case_restored->bindParam(3, $restore_type, PDO::PARAM_STR);
  $is_case_restored->execute();
  $is_case_restored = $is_case_restored->fetchColumn();
  if(!$is_case_restored){
	$status_pending = 'P';
  $is_case_exist=$db->prepare("select count(*) as case_check from $schemas.case_detail where filing_no=? and status = ?");
  $is_case_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
  $is_case_exist->bindParam(2, $status_pending, PDO::PARAM_STR);
  $is_case_exist->execute();
  $is_case_exist = $is_case_exist->fetchColumn();
  if(!$is_case_exist){
		continue;
   }	
 }	
  $st3=$db->prepare("select max(listing_date) as listing_date from $schemas.case_allocation_temp where filing_no=? ");
  $st3->bindParam(1, $filing_no, PDO::PARAM_STR);
  $st3->execute();

  while ($row3= $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {    
		$old_list=$row3['listing_date'];
	  
  }


$count++
?>
<tr>
<td>
<?php echo $count;
?>
</td>

<td>
<input class="checkbox" name="checkbox[<?php echo $x?>]" type="checkbox" id="checkbox[<?php echo $x?>]" value="<?php echo $filing_no."/".$old_list; ?>" <?php if($checkbox[$x]==$filing_no)echo 'checked';?> >
</td>

<td>
<?php echo $filing_no;
?>
</td>
<?php 
$st12=$db->prepare("select * from $schemas.case_detail where filing_no=? ");
                      $st12->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st12->execute();

                      while ($row12= $st12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                          $case_type=$row12['case_type'];
						  $case_no=$row12['case_no'];
						  $case_year=$row12['case_year'];
						  
						  if (in_array($case_type, $main_cases)){
								$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row12['filing_no']);
							}else{
								$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row12['main_case_ia_no']);
								if(empty($show_party_filing_no))
									$show_party_filing_no = htmlspecialchars($row12['filing_no']);
							}
						  //$location_code=$row12['location_code'];
						  $location_code=$row12['location_code'];
						  $pet_name =get_party($db,$show_party_filing_no,'P',1);
						  $res_name =get_party($db,$show_party_filing_no,'R',1);
						  $case_title=$pet_name." VS ".$res_name;
					  }
					  $lcode ="select short_name from mater_location_city where city_id ='$location_code'";
$lcode=$db->prepare($lcode);
$lcode->execute();
$lcodename = $lcode->fetchColumn();

			 if($case_type > 0)
{
$stQ = $db->prepare("select short_name from case_type where id = ?");
$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
$stQ->execute();
$case_type_short_name=$stQ->fetchColumn();
}



$case_numaa = $case_no;
$case_year1aa = $case_year;
$case_num1aa=ltrim($case_numaa,0);
$CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_no.'/'.$lcodename.'/'.$case_year);
?>
<td>
<?php echo $CASE_NO;

if (!in_array($case_type, $main_cases)){
	echo "<br/> In <br/>";
	echo $in_filing_no = main_case_no($schemas,$db,$case_type,$filing_no);
}
echo main_cases_child($schemas,$db,$filing_no,$status = 'p');
?>
</td>
<td>
<?php echo $pet_name.' Vs. '.$res_name;;
?>
</td>
<td>
<?php 
$court_no ="select court_no from $schemas.case_allocation_temp where filing_no = ?";
$court_no=$db->prepare($court_no);
$court_no->bindParam(1, $filing_no, PDO::PARAM_STR);
$court_no->execute();
$court_no = $court_no->fetchColumn();
echo $court_no;
?>
</td>
<td>
<?php
 $st2=$db->prepare("select * from e_case_detail_fees where filing_no=? ");
                      $st2->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st2->execute();
$i=0;$r='';
                      while ($row2= $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                          $E_sec_id=$row2['sec_id'];
                       
                      $st3=$db->prepare("select * from master_section_act where id=? ");
                      $st3->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                      $st3->execute();
                      
                      while ($row3 = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {
                      	  $E_add_sec_id=$row3['section_companies'];                      
                           $r.=$E_add_sec_id.',';
                       
                        
                       
                      }
                      
                      }
                      echo rtrim($r,',');

?>
</td>
<?php
$st3=$db->prepare("select max(listing_date) as listing_date from $schemas.case_proceeding where filing_no=? ");
                      $st3->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st3->execute();

                      while ($row3= $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                           $old_list=$row3['listing_date'];
						  
					  }
					  
					  
					  
					  $st13=$db->prepare("select * from $schemas.case_proceeding where filing_no=? and listing_date=?");
                      $st13->bindParam(1, $filing_no, PDO::PARAM_STR);
					  $st13->bindParam(2, $old_list, PDO::PARAM_STR);
                      $st13->execute();

                      while ($row13= $st13->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                           $bench_nature=$row13['bench_nature'];
						   $old_bench_no=$row13['bench_no'];
						   $old_purpose=$row13['purpose'];
						    $next_list_date=$row13['next_list_date'];
							 $next_list_purpose=$row13['next_list_purpose'];
							
						  
					  }
					  list($year,$month,$day)=explode('-',$old_list);
					$old_list_view =$day.'/'.$month.'/'.$year;	
					  list($year1,$month1,$day1)=explode('-',$next_list_date);
					$next_list_date_view =$day1.'/'.$month1.'/'.$year1;	
?>

<td>
<?php 
if($next_list_date == '' || $next_list_date == '1111-11-11'){
$next_list_date ="select next_list_date from $schemas.case_allocation_temp where filing_no = ?";
	$next_list_date=$db->prepare($next_list_date);
	$next_list_date->bindParam(1, $filing_no, PDO::PARAM_STR);
	$next_list_date->execute();
	$next_list_date = $next_list_date->fetchColumn();
	list($year1,$month1,$day1)=explode('-',$next_list_date);
	$next_list_date_view =$day1.'/'.$month1.'/'.$year1;	
	echo $next_list_date_view;
}else{
	echo $next_list_date_view;
}



?>
</td>


		<td>

<select name="purpose_id[<?php echo $filing_no?>]" style="width:200px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">

<?php
 //$sql2=" select * from $schemas.master_purpose where purpose_code='$next_list_purpose' ";
  $sql2=" select * from $schemas.master_purpose  where display = 'TRUE'  and status = 1";
foreach($db->query($sql2) as $row)
{

  $purpose_code=$row['purpose_code'];
 if($next_list_purpose == $purpose_code)
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
<td> <textarea rows="1" cols="10" name="case_remark[<?php echo $filing_no ?>]" class="form-control"></textarea> </td>
</tr>		
				

  <?php

}  }
} ?>
</table>
</div>
<!--<div class="form-group row">
	<center><input type="submit" name="submit1" value="Notify Cases" class="btn btn-success btn-sm" onClick="return notify_cases();"></td></tr></center>
	<br/>
</div>-->
<?php
if($bench_code1!='')
{
?>
<div class="form-group row">
	<center><input type="submit" name="submit1" value="CASE FOR LIST" class="btn btn-success btn-sm" onClick="return submitForm2();"></td></tr></center>
	<br/>
</div>
<?php 
}
?>

</div>
</div>
</div>

  

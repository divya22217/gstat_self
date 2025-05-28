<?php 
session_start();
ob_start();
include("../db_inc1.php");
//if we remove db_inc2 then it stops working
include("../db_inc2.php");
date_default_timezone_set("Asia/Kolkata");

$bench_no='';
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
//$localadmin=$_SESSION['localadmin'];
//$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
include '../inheader.php';
include '../insidebar.php';
//$userid=$_SESSION['id'];
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
<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
<!-- jvectormap -->
<link rel="stylesheet" href="../bower_components/jvectormap/jquery-jvectormap.css">
<!-- Theme style -->
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">



<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="../plugins/iCheck/all.css">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">



<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<!-- <script src="../bower_components/Chart.js/Chart.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>


<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script language="javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
  return false;
}
function submitForm()
{
 	with(document.frm)
	{
		
		action = "caseno_generation.php";
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
		action = "caseno_generation.php";
		submit();
	}
}
function submitForm1()
{
 	with(document.frm)
	{




	action = "caseno_generation_action.php";
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
		if(dt_of_filing.value=="")
		{
			alert("Please Enter Date of Filing.");
			dt_of_filing.focus();
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
    <title>Fresh Case Listing</title>
   
    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    
        <center>Fresh Case Listing
        </center>
     
    </section>
 <p> <center>All <font color="red">*</font></span> is mandatory Field </center></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">

<form name="frm" method="post" action="caseno_generation_action.php" >

<?php
$msghash=$_REQUEST['msghash'];
if($msghash !='')
{
$msghashz=(base64_decode($msghash));

$msghashz = explode("@", $msghashz);

$msg1 = $msghashz[0];
$case_list_date = $msghashz[1];

list($cyear,$cmonth,$cday)=explode('-',$case_list_date);

	  $case_list_date_dis=$cday.'/'.$cmonth.'/'.$cyear;
}




if($msg1 !='')
{
?>
<tr>
<td colspan="10"><center>

<a href="javascript:popsurety_pending_report('<?php echo htmlspecialchars($case_list_date);?>');" ><b><font color = 'red'  ><?php echo $msg;?> <br>Click Here for view <?php echo $case_list_date_dis ?></font></a></u>
 
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


 $dt_of_filing = isset($_REQUEST['dt_of_filing']) ? $_REQUEST['dt_of_filing'] :''; ?>	
	
	<td align="left" colspan="15" ><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
<font color="red">*</font>Date of filing</font>
		<input type="text"  readonly name="dt_of_filing" id="dt_of_filing"  maxlength="10" size="10" value="<?php echo  $dt_of_filing; ?>" onKeyup="javascript:addNumbers(this.value)" onchange="submitForm();UnSetBg(this);"class="datepicker" onFocus="SetBg(this)" >
		<b></b>
		</td>
	 <?php 
	 $search_case_type = isset($_REQUEST['search_case_type']) ? $_REQUEST['search_case_type'] :''; ?>	
<?php 

 $sql2=" select dept from users_cis where id='$sessionUserType'  ";
foreach($db->query($sql2) as $row)
{

   $user_reg2=$row['dept'];
}


?>

	<td>
<font face="Verdana, Arial, Helvetica, sans-serif" size="2">Case Type
<select name="search_case_type" style="width:250px" onchange="submitForm()"; onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<option value="">select</option>
<?php

if($user_reg2==4)
{
echo $sql2=" select * from case_type where id NOT IN(2,16,14)order by case_type_desc ";
}
else
{
	echo $sql2=" select * from case_type order by case_type_desc ";
}
foreach($dbh->query($sql2) as $row)
{

  $s_case_type=$row['id'];
 if($search_case_type == $s_case_type)
                {
		print "<option value=".htmlentities(htmlspecialchars($row['id']))." selected>".strtoupper(htmlentities(htmlspecialchars($row['case_type_desc'])))."</option>";
                }
           else
                {
                print "<option value=".htmlentities(htmlspecialchars($row['id'])).">".strtoupper(htmlentities(htmlspecialchars($row['case_type_desc'])))."</option>";
		}
 }
 ?>

</td>	
		
		
		</tr>

<tr>

<?php 


 $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>	
	
	<td align="left" colspan="15" ><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
<font color="red">*</font>Date of Listing</font>
		<input type="text"  readonly name="next_list_date" id="next_list_date"  maxlength="10" size="10" value="<?php echo  $next_list_date; ?>" onKeyup="javascript:addNumbers(this.value)" onchange="submitForm();UnSetBg(this);"class="datepickerGreater" onFocus="SetBg(this)" >
		<b></b>
		</td>
<?php 
 $purpose_id = isset($_REQUEST['purpose_id']) ? $_REQUEST['purpose_id'] :''; ?>	


	<td>
<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font color="red">*</font>Purpose 
<select name="purpose_id" style="width:250px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<option>select</option>
<?php
$sql2=" select * from $schemas.master_purpose where purpose_code='12' ";
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

<th colspan="6" align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Hon'ble Justice</font></th>
<th colspan="6" align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Limit </font></th>
<th colspan="6" align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Avalilable </font></th>
</tr>
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
		 
    $sql1=" select count(*) from $schemas.case_allocation_temp where  listing_date ='$court_date_new' and bench_no=$bench_code1 ";
	$sql1= $dbh->prepare($sql1);
	$sql1->execute();
     $counter = $sql1->fetchColumn();
    $case_limit_avail =$limit_case-$counter;	
		?>
		<tr>
		<?php $bench_nocheck = isset($_REQUEST['bench_no']) ? $_REQUEST['bench_no'] : ''; ?>
		<td valign="top"  align="center">
		<input type="radio"  name="bench_no"  class="bench_no"  value="<?php echo $bench_code1;?>" <?php if($bench_nocheck ==$bench_code1)echo 'checked';?> onchange="submitForm();UnSetBg(this);">
		</td>
		<td  valign="top" align="center"><?php  
		$sql2q=" select bench_name from $schemas.bench_nature where  bench_code ='$row2[bench_nature]'";
		$sth11= $dbh->prepare($sql2q);
		$sth11->execute();
		echo $sth11->fetchColumn(); ?>
		<?php echo '<br>Court No : '.$court_no;?>
		</td>
<?php 
if($bench_no=='')
{
	$bench_no=0;
	}
	


?>
		<td colspan="6" align="left">
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
		
		echo'<td>';
		print "<font size='2' ><b>".strtoupper($limit_case);
		echo'</td>';
		echo'<td colspan="12">';
		print "<font size='2' ><b>".strtoupper($case_limit_avail);
		echo'</td>';
	}
	echo'</tr>';
}
}
	?>
	
</table>
  <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">

	<tr><th>
	<b>Sr.No.</b></th>
	<th><input type="checkbox" name="allbox" onClick="un_check(this);" title="Select or Deselct ALL" style="background-color:#ccc;"/></th>
	<th align="left" width="150"><b>Diary No.</b></th>
	<th align="left" width="150"><b>Main Case</b></th>
	<th align="left" width="700"><B>Title </b></th>
	<th align="left" width="250"><B>Section</b></th>
	
	</tr>
	<?php
	$count=0;
	if($dt_of_filing!='')
	{
list($day2,$month2,$year2)=explode('/',$dt_of_filing);
$dt_filing=$year2.'-'.$month2.'-'.$day2;
	}
	
	if($dt_of_filing!=''  )
	{
		
$sql1=$db->prepare("select main_case_ia_no,ia_flag,filing_no,case_type,pet_name,res_name,dt_of_filing from $schemas.case_detail where legal_aid is null and case_no is NULL and case_year='' and dt_of_filing='$dt_filing' order by filing_no asc");
	}
	
	if($dt_of_filing!='' and $search_case_type=='' )
	{
		
$sql1=$db->prepare("select main_case_ia_no,ia_flag,filing_no,case_type,pet_name,res_name,dt_of_filing from $schemas.case_detail where legal_aid is null and case_no is NULL and case_year='' and dt_of_filing='$dt_filing'  order by filing_no asc");
	}
	
	if($dt_of_filing!='' and $search_case_type!='' )
	{
		
$sql1=$db->prepare("select main_case_ia_no,ia_flag,filing_no,case_type,pet_name,res_name,dt_of_filing from $schemas.case_detail where legal_aid is null and case_no is NULL and case_year='' and dt_of_filing='$dt_filing' and case_type='$search_case_type' order by filing_no asc");
	}
	
$sql1->execute();
while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $filing_no =$row1['filing_no'];
  $case_type =$row1['case_type'];
  $pet_name =$row1['pet_name'];
  $pet_name=strtoupper($pet_name);
  $res_name =$row1['res_name'];
  $res_name=strtoupper($res_name);
  
  $ia_flag = htmlentities($row1['ia_flag']);
  $main_case_no_ia = htmlentities($row1['main_case_ia_no']);
  
  if($ia_flag == '1'){
	  $main_case_det=$db->prepare("select listing_date,court_no,bench_nature,bench_no from $schemas.case_allocation_temp where filing_no=?");
	  $main_case_det->bindParam(1, $main_case_no_ia, PDO::PARAM_STR);
	
$main_case_det->execute();
while ($main_case_det_row = $main_case_det->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $listing_date_m = $main_case_det_row['listing_date'];
  $bench_nature_m = $main_case_det_row['bench_nature'];
  $court_no_m = $main_case_det_row['court_no'];
  $bench_no_m = $main_case_det_row['bench_no'];
  }
  list($yeari,$monthi,$dayi)=explode('-',$listing_date_m);
  $listing_date_m=$dayi.'/'.$monthi.'/'.$yeari;
  
  if($bench_nature_m!='' || $bench_nature_m!=NULL){
  $sql_bench_nature = "select bench_name from $schemas.bench_nature where bench_code =$bench_nature_m";
			$sql_bench_nature = $db->prepare($sql_bench_nature);
			$sql_bench_nature->execute();
			$bench_nature_m = $sql_bench_nature->fetchColumn();
  }
  }



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
if($ia_flag == '1'){
	echo "<font color='red'><center><b> (IA)</b></center></font>";
	}
?>
</td>
<td>
<?php
if($ia_flag == '1'){
	echo "<b>No:</b>".$main_case_no_ia."<br>";
	echo "<b>List-Date: </b>".$listing_date_m."<br>";
	echo "<b>Court No: </b>".$court_no_m."<br>";
	echo "<b>Bench Nature: </b>".$bench_nature_m."<br>";
	echo "<b>Bench No: </b>".$bench_no_m."<br>";
	}else{
		echo "------";
	}
?>
</td>
<td>
<?php echo $pet_name.' Vs. '.$res_name;;
?>
</td>
<td>
<?php
 $st2=$dbonline->prepare("select * from e_case_detail_fees where filing_no=? ");
                      $st2->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st2->execute();
$i=0;$r='';
                      while ($row2= $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                          $E_sec_id=$row2['sec_id'];
                       
                      $st3=$dbonline->prepare("select * from master_section_act where id=? ");
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

</tr>

</tr>

 </td>

  <?php

}  } ?>
</table>
  <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">


<tr><td colspan="8" align="center"><br><br>
	<input type="submit" name="submit1" value="CASE FOR LIST" class="button" onClick="return submitForm2();"></td></tr>
<script src="../src/calendar.js"></script>
  
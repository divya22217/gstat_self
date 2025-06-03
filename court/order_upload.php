<?php 
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(E_ALL);
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
require_once('../SrcCauselist/Causelist.php');
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
include '../db_inc2.php';
?>

<style>

.multipleInput-container {
     border:1px #ccc solid;
     padding:1px;
     padding-bottom:0;
     cursor:text;
     font-size:13px;
     width:100%;
     height: 75px;
     overflow: auto;
     background-color: white;
   border-radius:3px;
}
 
.multipleInput-container input {
     font-size:13px;
     width:150px;
     height:24px;
     border:0;
     margin-bottom:1px;
     outline: none
}
 
.multipleInput-container ul {
     list-style-type:none;
     padding-left: 0px !important;
}
 
li.multipleInput-email {
     float:left;
     margin-right:2px;
     margin-bottom:1px;
     border:1px #BBD8FB solid;
     padding:2px;
     background:#F3F7FD;
}
 
.multipleInput-close {
     width:16px;
     height:16px;
     background:url(close.png);
     display:block;
     float:right;
     margin:0 3px;
}
.email_search{
  width: 100% !important;
}
</style>

<script language="javascript">
$(document).ready(function(){
    $("select.int_fin").change(function(){
        var selectedCountry = $(this).children("option:selected").val();
        if(selectedCountry === 'I'){
			console.log('I');
			$("tr.order_date").show();
			$("tr.judge_date").hide();
		}else if(selectedCountry === 'F'){
			console.log('F');
			$("tr.judge_date").show();
			$("tr.order_date").hide();
		}
    });
});

function popsurety_pet_adv_name(cfy)

	{
		
			var url = "../public/details.php?filing_no="+cfy;
			 window.open(url,"_blank","directories=no, status=no,widtd=800, height=800, left=100, scrollbars=yes"); 
	}



function submitForm()
{
 	with(document.frm)
	{		
		action = "order_upload.php";
		submit();
	}
}
function submitForm11()
{
 	with(document.frm)
	{		
		action = "order_upload.php";
		submit();
	}
}

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
		
		action = "order_upload.php";
		submit();
	}
}


function addNumbers(val)
{
	var c=document.getElementById("order_date").value.length;
	if(c==2 || c==5  )
	{
		var newval = val+ "/";
		document.getElementById("order_date").value=newval;
	}

}
function validate(str)
{
	with(document.frm)
	{
	

		
             
       		 if(userfile.value=='')
       		{
    	   		alert("Please Upload the file");
    	   		userfile.focus();
			return false;
       		}

                oSelect=document.getElementById("judge_code");
		var count=0;
		for(var i=0;i<oSelect.options.length;i++)
		{
			if(oSelect.options[i].selected) { count++; }
		}
		if(count<1)
		{
			alert("Must select at least One JUDGE");
			return false;
		}

if(order_type.options[order_type.selectedIndex].value == "")
		{
			alert("Please Select Order Type");
			order_type.focus();
			return false;
		}	

		
		

var rgx = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;

		if(order_date.value=="")
		{
			alert("Please enter valid order Date");
			order_date.focus();
			return false;
		}

		if(order_date.value !="")
               	{        
               		if(!order_date.value.match(rgx))
			{
               			alert("Please enter valid order Date ");
               			order_date.select();
               			return false;
               		}
               	}


                var fup = document.getElementById('userfile');
        	var fileName = fup.value;
        	var ext = fileName.substring(fileName.lastIndexOf('.') + 1);

    		if(ext =="pdf" || ext=="Pdf" || ext=="PDF")
    		{
      	 		 return true;
    		}
    		else
    		{
       	 		alert("Upload PDF only");
        		return false;
   	 	}
                


		
	}
}
</script>

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

<!-- AdminLTE for demo purposes -->


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

<!-- AdminLTE App -->



<!--[endif]-->
<style>
    input[type=text] {
        padding: 1px;
        margin: 8px 0;
        box-sizing: border-box;
    }
    select{padding: 1px;
    margin: 8px 0;
    box-sizing: border-box; }
    /*[type=text]{padding: 5px;        margin-bottom: 5px;}*/
</style>

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Order Upload</title>
  <div class="content-wrapper">
      <section class="content-header">
      <h1>
        <center>Upload Final Order
        </center>
      </h1>
  
    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
  
<form name="frm" method="post" action="order_upload_action.php" onSubmit="return validate();" enctype="multipart/form-data" >

<?php
$msg=htmlentities($_REQUEST['msg']);
$msg = isset($msg) ? $msg : '';
if($msg !='')
{
	$msg=htmlspecialchars(base64_decode($msg));
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
<?php
$bench_type =htmlentities($_REQUEST['bench_type']);
$case_type =$_REQUEST['case_type'];
$msg =$_REQUEST['msg'];
$case_no =$_REQUEST['case_no'];
$case_year =$_REQUEST['case_year'];
$listing_date_f =$_REQUEST['listing_date'];
list($day,$month,$year)=explode('/',$listing_date_f);
$listing_date=$year.'-'.$month.'-'.$day;

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
 ?>`

</select><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Case No
		</font>	<input type="text"  maxlength="7" size="8" name="case_no" value="<?php print htmlentities(htmlspecialchars($case_no)); ?>" >
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Year
<input type="text"  maxlength="4" size="4" name="case_year" value="<?php print htmlentities(htmlspecialchars($case_year)); ?>" >
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Listing Date
<input type="text"  readonly name="listing_date" id="listing_date"  maxlength="10" size="10" value="<?php echo  $listing_date_f; ?>" onKeyup="javascript:addNumbers(this.value)" class="datepicker" onFocus="SetBg(this)">
		
	<input type="button" name="button" value="Go" size="20" onClick="return submitForm2();">
</td>


</tr>



<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<?php
	if($case_no!='' and $case_year!=''){
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
   
   	$case_no_send=$row1['case_no'];
	$pet_name=$row1['pet_name'];
	$res_name=$row1['res_name'];
	$dt_of_filing=$row1['dt_of_filing'];
	$case_type_send=$row1['case_type'];
	$case_year_send=$row1['case_year'];
	$status=$row1['status'];
}
	
    //$party_name=$pet_name." Vs ".$res_name;

//$filing_no = ucwords(strtoupper($sth->fetchColumn()));

//$pjudgename = $causelist->getpJudges($schemas, $db, $listing_date, $bench_no, $court_no, $table);
//print_r($pjudgename);
//$presiding_name = $pjudgename[0]['pjudgename'];
//print_r($presiding_name);
$presiding='';
//$judgesname = $causelist->getallJudges($schemas, $db, $listing_date, $bench_no, $court_no, $presiding, $table);
//print_r($judgesname);
?>
<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no)); ?>">
<?php
$sql2=$db->prepare("select * from  $schemas.order_daily where filing_no=? and order_date=?");

$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql2->bindParam(2, $listing_date, PDO::PARAM_STR);

$sql2->execute();
	
while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no333 =$row2['filing_no'];
}
if($filing_no == '' || $filing_no333 == '')
{
?>
	<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "RECORD NOT FOUND"; ?></span></font>
		</td>
		</tr>	
		<?php 
}
if($filing_no333!='')
{
	//get order detail
//$get_orderdetail_sql="select * from $schemas.order_daily where filing_no='$filing_no' and order_date='$list_date_db'";
      $get_orderdetail_sql="select * from $schemas.order_daily where filing_no=? and order_date=?";
      $get_orderdetail = $db->prepare($get_orderdetail_sql);
      $get_orderdetail->bindParam(1,$filing_no, PDO::PARAM_STR);
	  $get_orderdetail->bindParam(2,$listing_date, PDO::PARAM_STR);
      $get_orderdetail->execute();
      while ($god = $get_orderdetail->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		 {
			$bench_noc=$god['bench_no']; 
			$court_no=$god['court_no'];
		 }
	
	$table='case_allocation';
    $causelist = new Causelist();
	$pjudgenamenxt = $causelist->getpJudges($schemas, $db, $listing_date, $bench_noc, $court_no, $table);
	$presiding = $pjudgenamenxt[0]['presiding'];
	$presiding_name = $pjudgenamenxt[0]['pjudgename'];
	$judgesname = $causelist->getallJudges($schemas, $db, $listing_date, $bench_noc, $court_no, $presiding, $table);
	?>
<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "RECORD ALREADY EXISTS"; ?></span></font>
		</td>
		</tr>
		<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo strtoupper($party_name) ; ?></span></font>
		</td>
		</tr>
<?php 
}
?>
<tr>
<td  align="right"><font face="Verdana" size="2" color="red"><span>*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Upload File
</td>
<td align="left" colspan="5">
<input type="file" name="userfile" id="userfile">
</td></tr>

<tr>
<td  align="right"><font face="Verdana" size="2" color="red"><span>*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Corum
</td>
<td align="left" colspan="5">
<?php
 echo "<b>".$presiding_name."</b>"."<br>";
 $size = sizeof($judgesname);
 for($i=0;$i<$size;$i++)
 echo "<b>".$judgesname[$i]['alljudgesname']."</b>"."<br>";

?>
</td></tr>


<tr>
<td align="right" > <font face="Verdana, Arial, Helvetica, sans-serif" size="3">
<font face="Verdana" size="2" color="red"><span>*</span></font>Order Type</td> 
<td  align="left" colspan="5">
<select name="order_type"  style="width:120px" class="int_fin">
	<option value="">Select</option>
	
           <option value="I">Interim Order</option>
	    <option value="F">Final Order</option>
	</select>
</td>
</tr>

<tr class="order_date" style="display:none">
<td  align="right"><font face="Verdana" size="2" color="red"><span>*</span></font>Order Date</td>
<td align="left" colspan="5"><input type="text"  maxlength="10" size="10" name="order_date" id="order_date" class="datepickerToday"></td>
</tr>

<tr class="judge_date" style="display:none">
<td  align="right"><font face="Verdana" size="2" color="red"><span>*</span></font>Judgment Date</td>
<td align="left" colspan="5"><input type="text"  maxlength="10" size="10" name="judge_date" id="judge_date" class="datepickerToday"></td>
</tr>

<tr>
<td  align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><span class="error"></span>Remarks</td>
<td align="left" colspan="5">
<textarea name ="remarks" onFocus="SetBg(this);this.select()" onBlur="UnSetBg(this)" rows="1" maxlength="160" cols ="50"></textarea>
</td>
</tr>
<input type="hidden" name="case_no_send" value="<?php print htmlentities(htmlspecialchars($case_no_send)); ?>">
	<input type="hidden" name="pet_name" value="<?php print htmlentities(htmlspecialchars($pet_name)); ?>">
	<input type="hidden" name="res_name" value="<?php print htmlentities(htmlspecialchars($res_name)); ?>">
	<input type="hidden" name="dt_of_filing" value="<?php print htmlentities(htmlspecialchars($dt_of_filing)); ?>">
	<input type="hidden" name="case_type_send" value="<?php print htmlentities(htmlspecialchars($case_type_send)); ?>">
	<input type="hidden" name="case_year_send" value="<?php print htmlentities(htmlspecialchars($case_year_send)); ?>">
	<input type="hidden" name="status" value="<?php print htmlentities(htmlspecialchars($status)); ?>">
<tr>
<td colspan="6" align="center"><br><br>
<input type="submit" name="submit1" value="Submit" class="button btn-primary" onClick="return validate('upload');">
</td>
</tr>
</form>
</table>
 <?php
	}
 } ?>
 
 <script>
    function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}
(function( $ ){
     $.fn.multipleInput = function() {
          return this.each(function() {
               // list of email addresses as unordered list
               $list = $('<ul/>');
               // input
			   var input_event = $('<input type="email" name="email" id="email_search" class="email_search multiemail"/>');
               var $input = input_event.keyup(function(event) { 
                    if(event.which == 13 || event.which == 32 || event.which == 188) {                        
                         if(event.which==188){
                           var val = $(this).val().slice(0, -1);// remove space/comma from value
                         }
                         else{
                         var val = $(this).val(); // key press is space or comma                        
                         }                         
                         if(validateEmail(val)){
							 var splitemail = val.split('@');
						 var splitemail_id = splitemail[0];
                         // append to list of emails with remove button
                         $list.append($('<li class="multipleInput-email"><span>' + val + '</span></li>')
                              .append($('<a href="#" class="multipleInput-close" title="Remove"><i class="glyphicon glyphicon-remove-sign"></i></a>')
                                   .click(function(e) {
                                        $(this).parent().remove();
										$("#"+splitemail_id).remove();
                                        e.preventDefault();
                                   })
                              )
							  
                         );
		
						 $("#email_storage").append('<td id="'+splitemail_id+'"><input type="email" name="cc_new_email[]" id="cc_email_search" value="'+val+'" class="appended_email email_search multiemail"/></td>')
                         $(this).attr('placeholder', '');
                         // empty input
                         $(this).val('');
						 
                          }
                          else{
                            alert('Please enter valid email id, Thanks!');
                          }
                    }
               });
			   
			    var $input = input_event.blur(function(event) { 
						var val = $(this).val(); // key press is space or comma                        
                                                
                         if(validateEmail(val)){
							 var splitemail = val.split('@');
						 var splitemail_id = splitemail[0];
                         // append to list of emails with remove button
                         $list.append($('<li class="multipleInput-email"><span>' + val + '</span></li>')
                              .append($('<a href="#" class="multipleInput-close" title="Remove"><i class="glyphicon glyphicon-remove-sign"></i></a>')
                                   .click(function(e) {
                                        $(this).parent().remove();
										$("#"+splitemail_id).remove();
                                        e.preventDefault();
                                   })
                              )
							  
                         );
		
						 $("#email_storage").append('<td id="'+splitemail_id+'"><input type="email" name="cc_new_email[]" id="cc_email_search" value="'+val+'" class="appended_email email_search multiemail"/></td>')
                         $(this).attr('placeholder', '');
                         // empty input
                         $(this).val('');
						 $(this).css("color","black");
						 $(this).focus(); 
						 
                          }else{
							  if($(this).val() == ''){
		
								return false;
							  }
	
							 $(this).focus(); 
							  $(this).val('');
							 alert('Please enter valid email id, Thanks!');
						  }
               }); 
               // container div
               var $container = $('<div class="multipleInput-container" />').click(function() {
                    $input.focus();
               });
               // insert elements into DOM
               $container.append($list).append($input).insertAfter($(this));
               return $(this).hide();
          });
     };
})( jQuery );
$('#ccr_email').multipleInput();

/* $(document).on('focusout','#email_search',function(){
	alert("sdfds");
	$('#ccr_email').multipleInput();
}); */
</script>
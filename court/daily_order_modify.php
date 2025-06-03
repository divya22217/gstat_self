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
		action = "daily_order_modify.php";
		submit();
	}
}
function submitFormsub()
{
	with(document.frm)
	{
		
		
		
		action="daily_order_modify_action.php";
		submit();
    	
     	return true;
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
		
		action = "daily_order_modify.php";
		submit();
	}
}

function submitForm1()
{
 	with(document.frm)
	{


	action = "daily_order_modify.php";
		submit();
	}
}
</script>
<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../bower_components/jquery/dist/jquery.min.js"></script>


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

<body>
</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Daily Order Modify</title>
   
    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h4>
        <center><u>DAILY ORDER MODIFY</u>
        </center>
      </h4>
      
    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
   
<form name="frm" method="post" action="daily_order_modify_action.php" >
   <table cellspacing="0" align="center" cellpadding="2" border="0" width="100%" class="std">

  


<?php
$msg =htmlentities($_REQUEST['msg']);
$hash1=htmlspecialchars(base64_decode($msghash));
if($hash1 !='')
{
?>
<tr>
<td colspan="6"><center>
<font color='red' size='2'> <?php echo $hash1;?></font> 
</td>
</center>
</tr>
<?php
}

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

<?php 

  $bench_type  = htmlentities(htmlspecialchars($_REQUEST['bench_type']));
 $case_type  = htmlentities(htmlspecialchars($_REQUEST['case_type']));
 $case_no    = htmlentities(htmlspecialchars($_REQUEST['case_no']));
 $case_year  = htmlentities(htmlspecialchars($_REQUEST['case_year']));
  $order_date = htmlentities(htmlspecialchars($_REQUEST['order_date']));
?>
<tr>
 
<td   align="left" colspan="6">
		<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Bench:
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
		<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Case Type
<select name="case_type" style="width:200px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<option>select</option>
<?php


$sql="select * from case_type where display='Y' order by case_type_desc ASC";
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
 

</select>
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css"/>
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>

<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Case No
			<input type="text"  maxlength="7" size="5" name="case_no" value="<?php print htmlentities(htmlspecialchars($case_no)); ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Year
<input type="text"  maxlength="4" size="4" name="case_year" value="<?php print htmlentities(htmlspecialchars($case_year)); ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
	<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Order Date
	<input type="text" id="order_date" name="order_date"  class="datepicker"
 size="8" value="<?php print htmlspecialchars($order_date); ?>"/>
	
<script src="../src/calendar.js"></script>
	<input type="button" name="button" value="Go" size="20" onClick="return submitForm2();">
</td>


</tr>
<tr>
<td align="center">
  <?php 

 $sql = $db->prepare("select * from $schemas.case_detail where location_code=? and case_type=? and case_no=? and case_year=?");
  $sql->bindParam(1, $bench_type, PDO::PARAM_STR);
  $sql->bindParam(2, $case_type, PDO::PARAM_STR);
  $sql->bindParam(3, $case_no, PDO::PARAM_STR);
  $sql->bindParam(4, $case_year, PDO::PARAM_STR);
	$sql->execute();
	 while ($row = $sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
	$order_filing_no=htmlspecialchars($row['filing_no']);
	$pet_name=htmlspecialchars($row['pet_name']);
	$res_name=htmlspecialchars($row['res_name']);
	
	}
	list($day1,$month1,$year1)=explode('/',$order_date);
      	 $order_date1=$year1.'-'.$month1.'-'.$day1;
   $sql1 = $db->prepare("select * from $schemas.order_daily where filing_no=? and order_date=? ");
  $sql1->bindParam(1, $order_filing_no, PDO::PARAM_STR);
  $sql1->bindParam(2, $order_date1, PDO::PARAM_STR);
  
  $sql1->execute();
  while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
	$order_daily_filing_no=htmlspecialchars($row1['filing_no']);
	$order_of_tribunal_old=htmlspecialchars($row1['order_tribunal']);
	$order_court_no=htmlspecialchars($row1['court_no']);
	$order_bench_no=htmlspecialchars($row1['bench_no']);
	$order_bench_nature=htmlspecialchars($row1['bench_nature']);
	}
	if($order_daily_filing_no!='')
	{
	
	
	echo "<h7><font color='red'><center>";
	echo htmlspecialchars($pet_name);
	echo "</font><br><font color='blue'>Vs.</font><br><font color='red'>";
	echo htmlspecialchars($res_name);
	}
	else
	{
		?>
		<font face="Verdana" size="2" color="red"><b>
		<?php
		echo $msg="Record Not Found";
	}
?>
</b>
</font>
</td>
</tr>

<tr><td colspan="12">   </td>
</tr>
</table> 
 <table width="70%" border='0' cellpadding="1" cellspacing="3" align="center"> 
<?php 

  $sql_judge="select jm.judge_name,jm.judge_desg_code,jm.gen,jm.hon_text,jm.judge_code from $schemas.master_judge as jm,$schemas.bench as b where  b.from_list_date=?  and
 b.court_no=? and b.bench_no=? and jm.judge_code=b.presiding";

$sth101=$db->prepare($sql_judge);
$sth101->bindParam(1, $order_date1, PDO::PARAM_STR);
$sth101->bindParam(2, $order_court_no, PDO::PARAM_STR);
$sth101->bindParam(3,$order_bench_no, PDO::PARAM_STR);

$sth101->execute();

 while ($j = $sth101->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	 $judge_name=$j['judge_name'];
	$judge_desg_code=$j['judge_desg_code'];
	$presiding_code=$j['judge_code'];
	$gen=$j['gen'];
	$hon_text=$j['hon_text'];
	
	
if($judge_desg_code > 0)	
{
	$sql_desg="select desg_name from $schemas.master_desg where desg_code=? and display=? ";
	$sth14=$db->prepare($sql_desg);
	$display='TRUE';
	$sth14->bindParam(1, $judge_desg_code, PDO::PARAM_STR);
	$sth14->bindParam(2, $display, PDO::PARAM_STR);
	$sth14->execute();
	$judge_desg=$sth14->fetchColumn();
}
}    
?> 

    
<tr><td align="left" colspan="7"><b>Coram:</b></td></tr>
<tr><td align="left" colspan="7"><font face="Verdana" size ="2">
<b>
<?php echo htmlspecialchars($gen." ".$judge_name); ?><?php echo htmlspecialchars(", ".$hon_text." ".$judge_desg); ?>
</font>
</td>
</b>
</tr>
<?php 

//echo "select no_of_judges from $schemas.bench_nature where bench_code='$benchnature'";
$sql_judge_count="select no_of_judges from $schemas.bench_nature where bench_code=?";
$sth_j=$db->prepare($sql_judge_count);
$sth_j->bindParam(1, $order_bench_nature, PDO::PARAM_STR);
$sth_j->execute();
 $judge_desg=$sth_j->fetchColumn();



if($judge_desg>1)
{
	


	
$sql_j="select DISTINCT(bj.judge_code) as judge_code, bj.display,b.court_no as court_no ,b.priority as priority
	from $schemas.bench_judge as bj,$schemas.bench as b where b.to_list_date=?
	and b.court_no=? and bj.court_no=? and b.from_list_date=?
	and  bj.bench_nature=? and	bj.judge_code !=? and 
	bj.bench_nature=b.bench_nature and b.from_list_date=bj.from_list_date and
	b.to_list_date=bj.to_list_date and bj.from_list_date=? order by b.court_no ,b.priority";
	$sth_j1=$db->prepare($sql_j);
	$sth_j1->bindParam(1, $order_date1, PDO::PARAM_STR);
	$sth_j1->bindParam(2, $order_court_no, PDO::PARAM_STR);
	$sth_j1->bindParam(3, $order_court_no, PDO::PARAM_STR);
	$sth_j1->bindParam(4, $order_date1, PDO::PARAM_STR);
	$sth_j1->bindParam(5, $order_bench_nature, PDO::PARAM_STR);
	$sth_j1->bindParam(6, $presiding_code, PDO::PARAM_STR);
	$sth_j1->bindParam(7, $order_date1, PDO::PARAM_STR);
	$sth_j1->execute();

	 
	while ($b = $sth_j1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
	 $j_code=$b['judge_code'];
		$judge_desg_code_n='';

		if($j_code > 0)
		{
		$st141 = $db->prepare("select judge_name,judge_desg_code,gen,hon_text from $schemas.master_judge where
				judge_code=?");
				$st141->bindParam(1, $j_code, PDO::PARAM_STR);
				$st141->execute();

				while ($jj = $st141->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
				{
				$judgename1=$jj['judge_name'];
						$judge_desg_code_n=$jj['judge_desg_code'];
						$gen=$jj['gen'];
						$hon_text=$jj['hon_text'];
				}
		}

		if($judge_desg_code_n >0 )
		{
		$sql_desgw="select desg_name from $schemas.master_desg where desg_code=? and display=? ";
		$display='TRUE';
		$sth1141=$db->prepare($sql_desgw);
		$sth1141->bindParam(1, $judge_desg_code_n, PDO::PARAM_STR);
		$sth1141->bindParam(2, $display, PDO::PARAM_STR);
		$sth1141->execute();
		$judge_desg1=$sth1141->fetchColumn();
		}

		?><tr><td align="left" colspan="7"><font face="Verdana" size ="2"><b>
                 <?php echo htmlspecialchars($gen." ".$judgename1);?>
		<?php echo htmlspecialchars(", ".$hon_text." ".$judge_desg1); ?>
		</font>
		</td></tr>
		<?php
}	
}

//register banch code Detail here
?>
<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
		 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
</table>   
 <table width="70%" border='1' cellpadding="1" cellspacing="3" align="center" >

    
     
<tr>
		<td align="left" colspan="1" width="100%">
		</br>
		<fieldset>
  		<b>FOR APPLICANTS ADVOCATE</b>
		<BR>
		<?php
		$sql1 = $db->prepare("select * from $schemas.order_daily_advocate where filing_no=? and order_date=? and advocate_type='P' ");
  $sql1->bindParam(1, $order_filing_no, PDO::PARAM_STR);
  $sql1->bindParam(2, $order_date1, PDO::PARAM_STR);
  
  $sql1->execute();
  while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
	$advocate=htmlspecialchars($row1['advocate']);
	$advocateid=htmlspecialchars($row1['id']);
		?>
				 <input type="hidden"  readonly name="advid[]" maxlength="150" autocomplete="off" size="20"  onFocus="SetBg(this)" onBlur="UnSetBg(this)" value="<?php echo $advocateid;?>"</>

		 <input type="text"  name="adv[]" maxlength="150" autocomplete="off" size="20"  onFocus="SetBg(this)" onBlur="UnSetBg(this)" value="<?php echo $advocate;?>"</><br>
	<?php
	}
	?> 
	 
	 

	

 <script>  
 $(document).ready(function(){  
      var i=1;  
	  
      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="name[]" placeholder="Enter Advocate Name" class=""></td<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });
       
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
		   
           $('#row'+button_id+'').remove();  
      });  
      
 }); 

 </script>


	
	

                <div class="form-group">  
                      
                          <div class="table-responsive">  
<table class="table table-bordered" id="dynamic_field">  
                         
							 </table>  
	
 
	
	</fieldset>
	</td>
	</tr>
	                            
 <tr> 
  
 									
                                         
                                         <td><button type="button" name="add" id="add" class="">Add More</button></td>  
                                    </tr> 

 </table>   
 <table width="70%" border='1' cellpadding="1" cellspacing="3" align="center" >
	
	<tr>
	<td align="left"  width="80%">
		<br><fieldset>
  		<b>FOR RESPONDENTS ADVOCATE</b>
  		<br>
		<?php
		$sql1 = $db->prepare("select * from $schemas.order_daily_advocate where filing_no=? and order_date=? and advocate_type='R' ");
  $sql1->bindParam(1, $order_filing_no, PDO::PARAM_STR);
  $sql1->bindParam(2, $order_date1, PDO::PARAM_STR);
  
  $sql1->execute();
  while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
	$advocate1=htmlspecialchars($row1['advocate']);
	$advocateid1=htmlspecialchars($row1['id']);
		?>
		 <input type="hidden"  readonly name="advid1[]" maxlength="150" autocomplete="off" size="20"  onFocus="SetBg(this)" onBlur="UnSetBg(this)" value="<?php echo $advocateid1;?>"</>
		 <input type="text"  name="adv1[]" maxlength="150" autocomplete="off" size="20"  onFocus="SetBg(this)" onBlur="UnSetBg(this)" value="<?php echo $advocate1;?>"</><br>
	<?php
	}
	?> 
		
	
	
	<script>  
 $(document).ready(function(){  
      var j=10000;  
      $('#add1').click(function(){  
           j++;  
           $('#dynamic_field1').append('<tr id="row1'+j+'"><td><input type="text" name="rname[]" value="" placeholder="Enter Advocate Name" class="" /><button type="button" name="remove" id="'+j+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
	
           var button_id1 = $(this).attr("id");  

           $('#row1'+button_id1+'').remove();
		   
      });  
     
 });  
 </script>
			
	     <div class="form-group">   
	
                      
                          <div class="table-responsive">  
<table class="table table-bordered" id="dynamic_field1">  
                                    
     </table> 
</fieldset>
	</td>
	</tr>
							 <tr>
<td><button type="button" name="add1" id="add1" value="1" class="">Add More</button></td> 
	</tr> 
	
	
	
	
</table>





<table width="60%" border='0' cellpadding="1" cellspacing="2" align="center"> 
  <script language="javascript" type="text/javascript">
 tinymce.init({
gecko_spellcheck : true,
width : "640",
    selector: "textarea",
    theme: "modern",
 //menubar: "edit insert view format table",

menu : {
    edit: { title: 'Edit', items: 'undo redo  | cut copy paste selectall | searchreplace' },
    insert: { title: 'Insert', items: 'edit image charmap pagebreak insertdatetime hr  ' },
    view: { title: 'View', items: 'preview fullscreen' },
     format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript | removeformat' },
    table: { title: 'Table', items: 'inserttable tableprops deletetable | cell row column' }
  },

    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak lineheight",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        " template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent |  image fontselect fontsizeselect",
    toolbar2: " lineheightselect  preview | forecolor backcolor  ",
removed_menuitems: 'newdocument' ,

    image_advtab: true,
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}
    ]
});
</script>
  <tr>
		
		 <td align="center" colspan="2">
	<br>
  		<legend><b><u>ORDER</u></b></legend>
 	
	                   
		                        <!-- block -->
		                       
		                            <div class="navbar navbar-inner block-header">
		                              
		                            <div class="block-content collapse in">
		                   <textarea name="order_of_tribunal" align="justify" rows="8" cols="80" onFocus="SetBg(this)" onBlur="UnSetBg(this)"><?php if($order_of_tribunal=="" and $order_of_tribunal_old!=''){echo $order_of_tribunal_old= trim($order_of_tribunal_old) ;} else {echo $order_of_tribunal; } ?></textarea>
		                          
		                       
		                        <!-- /block -->
		                   
		                </div>		
		<!--/.fluid-container>
        <script src="vendors/bootstrap-wysihtml5/lib/js/wysihtml5-0.3.0.js"></script>
       <!--  <script src="vendors/jquery-1.9.1.min.js"></script> 
        <script src="bootstrap/js/bootstrap.min.js"></script>>
		<script src="vendors/bootstrap-wysihtml5/src/bootstrap-wysihtml5.js"></script>

		<!--  <script src="vendors/ckeditor/ckeditor.js"></script>-->
	<!-- 	<script src="vendors/ckeditor/adapters/jquery.js"></script> -->


		 </td>
</tr>

<?php
if($item =='')
{
?>		 
<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars($filing_no98); ?>"/>
<?php
}

?>

 <input type="hidden" name="order_date1" value="<?php print htmlentities(htmlspecialchars($order_date1)); ?>">

 <input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($order_filing_no)); ?>">
 <tr><td colspan="15" align="center">
<input id="submit1" type="button"  name="submit1" value="Submit" 
 onClick="return submitFormsub();" />
 </td></tr>		
</form>
 <?php 

 include '../bfooter1.php'; 
  ?>

  <?php } ?>
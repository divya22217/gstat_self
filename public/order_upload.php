<?php
$case_type =$_REQUEST['case_type'];
$msg =$_REQUEST['msg'];
$case_no =$_REQUEST['case_no'];
$case_year =$_REQUEST['case_year'];

$schemas=htmlspecialchars($_SESSION['schema_name']);
include("../db_inc1.php");
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>NCLT | Dashboard</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
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
<script language="javascript">
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
<style>
table {
    border-collapse: collapse;
    border: 2px solid green;
}
</style>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

	<?php include("../includes/header.php");
	include '../insidebar.php';
	?>

	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content">
<?php

ob_start();

//session_start();

//session_cache_expire( 20 );
session_start(); // NEVER FORGET TO START THE SESSION!!!

//session_regenerate_id();   //- added by ankur on 09072013

//$cook=rand(1,10000);
$cook = sha1(microtime());

setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
setcookie ("nalsacookie","",time()-3600,"","",TRUE,TRUE);

$inactive = 600;





//Generate a key, print a form:
$key = sha1(microtime());
$_SESSION['csrf'] = $key;



?>

<form name="frm" method="post" enctype="multipart/form-data" action="order_upload_action.php" onSubmit="return validate();">
<table  border="2" cellspacing="2" cellpadding="2" align="center" class="table">
  <tr><td colspan="6" align="right" valign="top">
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><b>
        <u><B>ORDER & FINAL ORDER UPLOADING FORM</B></u></b></font></div>
    </td>
  </tr>
 <tr>
    <td colspan="6" align="right" valign="top">
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Fields
        marked with a <span class="error">*</span> are compulsory</font></div>
    </td>
  </tr>
<?php $msg =base64_decode($_REQUEST['msg']);
	if($_REQUEST['msg'] != "")
	{
	        ?>
		<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <? echo "$msg"; ?></span></font>
		</td>
		</tr>
		<?
	}?>
	<br>
	

    
   <input type="hidden" name="act" value="download">






<?php
$search_by="cno";
if($search_by=="cno")
{
$sql="select * from case_type where display='Y' order by case_type_desc ASC";
?>
<tr>
 
<td   align="left" colspan="6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		<span class="error">*</span>Bench:
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


		<span class="error">*</span>Case Type
<select name="case_type" style="width:200px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
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

</select><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><span class="error">*</span>Case No
			<input type="text"  maxlength="7" size="8" name="case_no" value="<?php print htmlentities(htmlspecialchars($case_no)); ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><span class="error">*</span>Case Year
<input type="text"  maxlength="4" size="4" name="case_year" value="<?php print htmlentities(htmlspecialchars($case_year)); ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
	<input type="button" name="button" value="Go" size="20" onClick="return submitForm2();">
</td>


</tr>

<?php
	$bench_type  = htmlentities(htmlspecialchars($_REQUEST['bench_type']));
 $case_type  = htmlentities(htmlspecialchars($_REQUEST['case_type']));
 $case_no    = htmlentities(htmlspecialchars($_REQUEST['case_no']));
 $case_year  = htmlentities(htmlspecialchars($_REQUEST['case_year']));
/*$clen = strlen($case_type);
$clength = 3-$clen;
for($c=0;$c<$clength;$c++)
$case_type = "0".$case_type;

$clen = strlen($case_no);
$clength = 7-$clen;
for($c=0;$c<$clength;$c++)
$case_no = "0".$case_no;
if($case_no == 000000)
$case_no='';	

$chr=4;// char for first hard code digit of filing no
$c_no=$chr.$case_type.$case_no.$case_year;
$sql="select filing_no from $schemas.case_detail where case_no=?";
$sth = $dbh->prepare($sql);
$sth->bindParam(1, $c_no, PDO::PARAM_STR);*/

	$sth = $db->prepare("select * from $schemas.case_detail where location_code=? and case_type=? and case_no=? and case_year=?");
	$parm = array($bench_type,$case_type,$case_no,$case_year);

	$sth->execute($parm);
	$rw2 = $sth->fetch();
extract($rw2);
//$filing_no = ucwords(strtoupper($sth->fetchColumn()));
?>
<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no)); ?>">
<?php
}?>
<tr>
	<td colspan="6">
	<font size="2" color="black">
	<b><u><center>VIEW CASE DETAILS</center></u></b>
	</font>
	</td>
</tr>


<tr>
	<td colspan="6">
	<font size="2" color="RED"><center>

<?php

$length=strlen($filing_no);

if($length ==16)
{
?>
<a href="javascript:popsurety_pet_adv_name('<?php echo  base64_encode($filing_no.'/'.$schemas); ?>');" >

<br>

Click to view case details :<?php echo htmlspecialchars($filing_no);?><br><br></a>
</center>
<?
}
else
{
echo " No record exist";
}
?></font>
</td>


</tr>


<tr>
<td  align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><span class="error">*</span>Upload File
</td>
<td align="left" colspan="5">
<input type="file" name="userfile" id="userfile">
</td></tr>





<tr>
<td  align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><span class="error">*</span>Member Name
</td>
<td align="left" colspan="5">
<?php
$sql="select * from $schemas.master_judge where display='TRUE' order by judge_code asc";
?>
<select name="judge_code[]" id="judge_code" style="width: 400px; height: 300px" onFocus="SetBg(this)" onBlur="UnSetBg(this)" multiple>
<?php

foreach($dbh->query($sql) as $row)
{

  $j_code=$row['judge_code'];
  $j_name=$row['judge_name'];
$judge_desg_code=$row['judge_desg_code'];


$q="select desg_name from $schemas.master_desg where desg_code =?";
$r=$dbh->prepare($q);
$r->bindParam(1, $judge_desg_code, PDO::PARAM_INT);
$r->execute();
$desg_name =$r->fetchColumn();




	
 ?>	
	<option value="<?php echo $j_code;?>" 
	<?php 
	if(in_array($j_code, $j_type))
	{  
		print "selected"; 
	} 
	?>>
	<?php echo strtoupper($j_name).' '.strtoupper($desg_name);?></option>
	<?
 }
 ?>

</select>
</td></tr>


<tr>
<td align="right" > <font face="Verdana, Arial, Helvetica, sans-serif" size="2">
<span class="error">*</span>Order Type </font></td> 
<td  align="left" colspan="5">
<select name="order_type"  style="width:120px" >
	<option value="">Select</option>
	
            <!--<option value="D" <?php if($order_type == "D") { print " selected"; } ?>>Daily Order</option>-->
            <option value="O" <?php if($order_type == "O") { print " selected"; } ?>>Oral Order</option>
	    <option value="F" <?php if($order_type == "F") { print " selected"; } ?>>Final Order</option>
	</select>
</td>
</tr>
<tr>
<td  align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><span class="error">*</span>Order Date</td>
<td align="left" colspan="5"><input type="text"  maxlength="10" size="10" name="order_date" id="order_date" value="<?php print htmlentities(htmlspecialchars($order_date)); ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)" onKeyup="javascript:addNumbers(this.value)">         <b>(DD/MM/YYYY)</b></td>
</tr>
<tr>
<td  align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><span class="error"></span>Remarks</td>
<td align="left" colspan="5">
<textarea name ="remarks" onFocus="SetBg(this);this.select()" onBlur="UnSetBg(this)" rows="1" cols ="50"></textarea>
</td>
</tr>
<tr>
<td colspan="6" align="center"><br><br>
<input type="submit" name="submit1" value="Submit" class="button btn-primary" onClick="return validate('upload');">



<?php
$sql_detail="select * from $schemas.case_detail where filing_no=?";
$sth = $dbh->prepare($sql_detail);
$sth->bindParam(1,$filing_no, PDO::PARAM_STR);
$sth->execute();
while ($rr = $sth-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{
	$case_no_send=$rr['case_no'];
	$pet_name=$rr['pet_name'];
	$res_name=$rr['res_name'];
	$dt_of_filing=$rr['dt_of_filing'];
	$case_type_send=$rr['case_type'];
	$status=$rr['status'];
        $pet_type = htmlspecialchars($rr['pet_type']);
        $res_type = htmlspecialchars($rr['res_type']);
        $pet_org_type = htmlentities(htmlspecialchars($rr['pet_org_type']));
        $res_org_type = htmlentities($rr['res_org_type']);

   if($pet_type =='2')
 {
 	if($pet_org_type > 0)
 	{
 
 		$stv= $db->prepare("select org_name from $schemas.master_licensor where org_code=?");
 		 $stv->bindParam(1, $pet_org_type, PDO::PARAM_INT);
 		 $stv->execute();
 		 $pet_org_name = $stv->fetchColumn();
 
 	}
 }
 
if($pet_org_type > 0 and $pet_type ==4)
 {
 	
 	
 	$stvv = $dbh->prepare("select org_name from $schemas.master_licensee where org_code=?");
 	$stvv->bindParam(1, $pet_org_type, PDO::PARAM_INT);
 	$stvv->execute();
 	$pet_org_name = $stvv->fetchColumn();
 		
 
 }


if($pet_name !='')
 {
$pet_name =$pet_name;
 }
 else 
{
$pet_name =$pet_org_name;
}



if($res_type =='2')
 {
 if($res_org_type > 0)
 {
         $st1 = $dbh->prepare("select org_name from $schemas.master_licensor where org_code=?");
 	$st1->bindParam(1, $res_org_type, PDO::PARAM_INT);
 	$st1->execute();
 	$res_org_name = $st1->fetchColumn();
 }
 		
 
 }
 
 
 if($res_type =='4')
 {
 if($res_org_type > 0)
 {
 $stk = $dbh->prepare("select org_name from $schemas.master_licensee where org_code=?");
 	$stk->bindParam(1, $res_org_type, PDO::PARAM_INT);
 	$stk->execute();
 	$res_org_name = $stk->fetchColumn();
 }
 
 }


if($res_org_type > 0)
 {$res_name =$res_org_name;}
 else{
 $res_name = strtoupper($res_name);
 }
?>

	<input type="hidden" name="case_no_send" value="<?php print htmlentities(htmlspecialchars($case_no_send)); ?>">
	<input type="hidden" name="pet_name" value="<?php print htmlentities(htmlspecialchars($pet_name)); ?>">
	<input type="hidden" name="res_name" value="<?php print htmlentities(htmlspecialchars($res_name)); ?>">
	<input type="hidden" name="dt_of_filing" value="<?php print htmlentities(htmlspecialchars($dt_of_filing)); ?>">
	<input type="hidden" name="case_type_send" value="<?php print htmlentities(htmlspecialchars($case_type_send)); ?>">
	<input type="hidden" name="status" value="<?php print htmlentities(htmlspecialchars($status)); ?>">

<?php
}

?>

<input type="hidden" name="csrf" value="<? echo htmlentities(htmlspecialchars($key))?>" >
	
<!--input  type="button" onClick="location.href='logout.php?csrf=<? echo htmlentities(htmlspecialchars($key));?>'" value="Logout" /-->

<!--input  type="button" onClick="location.href='login_status.php?coder=<?php echo $_SESSION['coder1'];?>'" value="Back" /-->
</td></tr>

</table>
	</div>

	</section>
	<?php include '../includes/footer.php'; ?>
	<div class="control-sidebar-bg"></div>

</div>
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
<!-- ChartJS >
<script src="../bower_components/Chart.js/Chart.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) >
<script src="../dist/js/pages/dashboard2.js"></script>

<script src="../bower_components/moment/min/moment.min.js"></script>

<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 >
<script src="../plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->

<!-- AdminLTE App -->

</body>
</html>

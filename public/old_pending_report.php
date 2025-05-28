<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");//database connection
session_start();

?>



<!DOCTYPE html>
<!-- saved from url=(0019)http://nclt.gov.in/ -->
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link id="Link1" rel="shortcut icon" href="http://nclt.gov.in/image/favicon.ico">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>NCLT</title>
<!-- Bootstrap -->
<link href="../APTEL_files/bootstrap.min.css" rel="stylesheet">
<!-- Important Owl stylesheet -->
<link rel="stylesheet" href="../APTEL_files/owl.carousel.css">	 
<!-- Default Theme -->
<link rel="stylesheet" href="../APTEL_files/owl.theme.css">
<link href="../APTEL_files/style3.css" rel="stylesheet">
<link href="../APTEL_files/ticker.css" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="../plugins/jQueryUI/jquery-ui.css">
<link href="../APTEL_files/font-awesome.min.css" rel="stylesheet">
<script type="text/javascript" src="../APTEL_files/html5.js.download"></script>
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script> 	
<script src="../plugins/jQueryUI/jquery-ui.js"></script> 	
<script src="../plugins/jQueryUI/date.js"></script> 
<script language="javascript">
function change(id, newClass)
{
identity=document.getElementById(id);
identity.className=newClass;
}
function printPage()
{
change("testdiv","hidden");
window.print();
}

function submitForm()
{
with(document.frm)
{
if(schemaname.value == "")
{
alert("Choose Office Location....");
schemaname.focus();
return false;
}
if(next_list_date.value == "")
{
alert("SELECT START DATE....");
next_list_date.value='';
next_list_date.focus();
return false;
}

if(next_list_date1.value == "")
{
alert("SELECT END DATE....");
next_list_date1.value='';
next_list_date1.focus();
return false;
}

action="<?php echo $_SERVER[SCRIPT_NAME];?>";
submit();
document.frm.submit1.disabled = true;
document.frm.submit1.value = 'Please Wait...';
return true;
}
}

function change(){
with(document.frm){
action="<?php echo $_SERVER[SCRIPT_NAME];?>";
submit();

}
}

</script>

</head>
<body>

<?php include("./header.php");?> 	




<!--================= slider and Chairman message section ===================== -->
<div class="container margin-top-30">	
<form  method="post"  name="frm">

<div class="row">
<h4 style="text-align:center"><u>PENDING REPORT</u></h4>
</div>


<div class="row">
<div class="col-sm-3">
<div class="form-group">
<label for="email">SELECT BENCH :</label>
<select name = "schemaname" class="form-control"  id="schemaname"  onchange="change();">
<?php
$schemas = isset($_REQUEST['schemaname']) ? $_REQUEST['schemaname'] :''; 
$sql="select * from mater_location_city order by city_name ASC";
$schemaName = $db-> prepare($sql);												
$schemaName -> execute();				
?>	
<option> Select </option>
<?php
while ($row =$schemaName->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{
$ctc=$row['schema_name'];
if($schemas == $ctc)
{
print "<option value=".htmlspecialchars($row['schema_name'])." selected>".htmlspecialchars($row['city_name'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['schema_name']).">".htmlspecialchars($row['city_name'])."</option>";
}
}
?>	
</select>
</div>
</div>

<div class="col-sm-3">
<div class="form-group">
<label for="email">FROM DATE:</label>
<?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>
<input type="text" class="form-control" name="next_list_date" id="from" readonly="readonly"
autocomplete="off" maxlength="10" value="<?php print htmlspecialchars(htmlentities($next_list_date)); ?>" />
</div>
</div>


<div class="col-sm-3">
<div class="form-group">
<label for="email">TO DATE:</label>
<?php  $next_list_date1 = isset($_REQUEST['next_list_date1']) ? $_REQUEST['next_list_date1'] :''; ?>
<input type="text"  class="form-control" name="next_list_date1" id="to" readonly="readonly"
autocomplete="off" maxlength="10" value="<?php print htmlspecialchars(htmlentities($next_list_date1)); ?>" />
</div>
</div>


<div class="col-sm-3">
<div class="form-group">
<label for="email">&nbsp;</label>
<input id="submit1" type="button" class="form-control btn btn-primary" id="cryptstr"  name="submit1" value="SEARCH" onClick="return submitForm();" />
</div>
</div>
</div>


<?php
if($next_list_date!=''){
list($day,$month,$year)=explode('/',$next_list_date);
$list_cdate=$year.'-'.$month.'-'.$day;

list($day,$month,$year)=explode('/',$next_list_date1);
$list_cdate1=$year.'-'.$month.'-'.$day;

$stnq = $db->prepare("select * from $schemas.case_detail where status ='P' and dt_of_filing  between ? and ? ");
$stnq->bindParam(1, $list_cdate, PDO::PARAM_STR);
$stnq->bindParam(2, $list_cdate1, PDO::PARAM_STR);
$stnq->execute();
?>

<table class="table">
<?php if($stnq->rowCount() =='0' && $next_list_date!='')
{?>
<tr>
<td colspan="5"> 
<div class="alert alert-danger">
<strong>!!!</strong> No Record Found.
</div>
</td></tr>	
<?php } ?>	
<?php
if($stnq->rowCount() > 0)
{
?>




<tr>
<th>SR. No</th>
<th>Case No</th>
<th>Diary No</th>
<th>Party Details</th>
<th>Filing Date</th>

</tr>
<tbody>
<?php $iii=1;
while ($rw2 = $stnq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
extract($rw2);
if($filing_no98 !='')
{
if($case_type > 0)
{
$stQ = $db->prepare("select short_name,case_nature from master_case_type where case_type_code = ?");
$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
$stQ->execute();
$case_type_data=$stQ->fetch();
$case_type_short_name =$case_type_data['short_name'];
$case_type_nature =$case_type_data['case_nature'];
}



$case_numaa = substr($case_no,4,7);
$case_num1aa=ltrim($case_numaa,0);
$case_year1aa = substr($case_no,11,4);


}
list($yy,$mm,$dd) = explode("-",$dt_of_filing);
$dt_of_filing = $dd.'/'.$mm.'/'.$yy;
$hash=base64_encode($filing_no.'/'.$schemas);

?>

<tr>
<td><?php echo $iii;?></td>
<td><a href="javascript:popsurety_pet_adv_name('<?php print htmlspecialchars($hash); ?>');">
<?php 
if($case_no !='')
{
htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_num1aa.'/'.$case_year1aa);
}

?>
</a>
</td>

<td>
<a href="javascript:popsurety_pet_adv_name('<?php print htmlspecialchars($hash); ?>');">
<?php
echo $filing_no;
?>
</td>

<td>
<?php 
echo "<h7><font color='red'>";
echo strtoupper(html_entity_decode($pet_name));
echo "</font><br><font color='blue'>VS</font><br><font color='red'>";
echo strtoupper(html_entity_decode($res_name));
echo "</font></h7>";
?>
</td>

<td><?php echo htmlspecialchars($dt_of_filing);?></td>


</tr>

<?php 

$iii++;
}// case_detail loop 
//while loop end all query....

?>


</tbody>

<?php }}?>	
</table>		

</form>
	  

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="../APTEL_files/owl.carousel.js.download"></script>
<script src="../APTEL_files/main.js.download"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../APTEL_files/bootstrap.min.js.download"></script>
<script src="../APTEL_files/ticker.js.download"></script>
<script type="text/javascript" src="../APTEL_files/font-modify.js.download"></script>
<script type="text/javascript" src="../APTEL_files/jquery.faded.js.download"></script> 
<script type="text/javascript" src="../APTEL_files/jquery.faded-options.js.download"></script>    
<script type="text/javascript" src="../APTEL_files/imagepreloader.js.download"></script>
<script type="text/javascript" src="../APTEL_files/load-window.js.download"></script>


</div></body></html>

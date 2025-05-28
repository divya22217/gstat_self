<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include("../db_inc2.php");
?>
<!DOCTYPE html>
<!-- saved from url=(0019)http://nclt.gov.in/ -->
<html lang="en">
<head>
<title>NCLT || <?php
$urlll = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
$url_var = explode('/' , $urlll);
echo ucwords(end($url_var)); ?></title>

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

action="case_status.php";
submit();
document.frm.submit1.disabled = true;
document.frm.submit1.value = 'Please Wait...';
return true;
}
}

function change()
{
with(document.frm)
{
action="case_status.php";
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
<h4 style="text-align:center;font-weight:bold;"><u>CASE STATUS REPORT</u></h4>
</div>


<div class="row">
<div class="col-sm-6">
<div class="form-group">
<label for="email">SELECT BENCH :</label>
<select name = "schemaname" class="form-control"  id="schemaname">
<?php
 $loc_id = isset($_REQUEST['schemaname']) ? $_REQUEST['schemaname'] :''; 

/*if($loc_id == '10'){ $loc_code_adjust = '1'; } elseif($loc_id == '11'){ $loc_code_adjust = '2'; }
elseif($loc_id == '9'){ $loc_code_adjust = '9'; }elseif($loc_id == '8'){ $loc_code_adjust = '8'; }
elseif($loc_id == '7'){ $loc_code_adjust = '7'; }elseif($loc_id == '6'){ $loc_code_adjust = '6'; }
elseif($loc_id == '5'){ $loc_code_adjust = '5'; }elseif($loc_id == '4'){ $loc_code_adjust = '4'; }
elseif($loc_id == '3'){ $loc_code_adjust = '3'; }elseif($loc_id == '2'){ $loc_code_adjust = '3'; }
elseif($loc_id == '1'){ $loc_code_adjust = '4'; }*/

if($loc_id!=''){
$get_city_id_sql = $db->prepare("select city_id from mater_location_bench where location_id = ?");
$get_city_id_sql->bindParam(1, $loc_id, PDO::PARAM_STR);
$get_city_id_sql->execute();
$city_id=$get_city_id_sql->fetchColumn();	

$get_schema_sql = $db->prepare("select schema_name from mater_location_city where city_id = ?");
$get_schema_sql->bindParam(1, $city_id, PDO::PARAM_STR);
$get_schema_sql->execute();
$schemas=$get_schema_sql->fetchColumn();
}

$sql="select * from mater_location_bench order by location_id DESC";
$schemaName = $db-> prepare($sql);												
$schemaName -> execute();				
?>	
<option value="" > Select </option>
<?php
while ($row =$schemaName->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{
$ctc=$row['location_id'];
if($loc_id == $ctc)
{
print "<option value=".htmlspecialchars($row['location_id'])." selected>".htmlspecialchars($row['bench_name'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['location_id']).">".htmlspecialchars($row['bench_name'])."</option>";
}
}
?>	
</select>
</div>
</div>



<div class="col-sm-6">
<div class="form-group">
<label for="email">SEARCH BY :</label>
<?php 
$search_wises = array(
'filing_date'=>'FILING DATE WISE',
"case_wise"=>'CASE NO WISE ',
"diary_wise"=>'DAIRY NO. WISE',
"party_wise"=>'PARTY NAME WISE'
);
$search_by = isset($_REQUEST['search_by']) ? $_REQUEST['search_by'] :'filing_date'; 
?>
<select name = "search_by" class="form-control"  onchange="change();">
<?php 
foreach($search_wises as $key => $search_wise) {?>
<option value="<?php echo $key;?>" <?php if($search_by==$key) echo 'selected';?>><?php echo $search_wise?></option>
<?php }?>
</select>

</div>
</div>



</div><!-----row--->

<div class="row">

<!------------------------>
<!------------------------>
<!------------------------>

<?php if($search_by =='filing_date'){?>
<div class="col-sm-4">
<div class="form-group">
<label for="email">FROM DATE:</label>
<?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>
<input type="text" class="form-control" name="next_list_date" id="from" readonly="readonly"
autocomplete="off" maxlength="10" value="<?php print htmlspecialchars(htmlentities($next_list_date)); ?>" />
</div>
</div>


<div class="col-sm-4">
<div class="form-group">
<label for="email">TO DATE:</label>
<?php  $next_list_date1 = isset($_REQUEST['next_list_date1']) ? $_REQUEST['next_list_date1'] :''; ?>
<input type="text"  class="form-control" name="next_list_date1" id="to" readonly="readonly"
autocomplete="off" maxlength="10" value="<?php print htmlspecialchars(htmlentities($next_list_date1)); ?>" />
</div>
</div>


<div class="col-sm-4">
<div class="form-group">
<label for="email">&nbsp;</label>
<input id="submit1" type="button" class="form-control btn btn-primary" id="cryptstr"  name="submit1" value="SEARCH" onClick="submitForm()" />
</div>
</div>
</div>
<?php 
$list_cdate = isset( $_REQUEST['list_cdate'] )? $_REQUEST['list_cdate']: false;
 if($next_list_date!='')
 {
list($day,$month,$year)=explode('/',$next_list_date);
$list_cdate=$year.'-'.$month.'-'.$day;
 }
  if($next_list_date1!='')
 {
list($day,$month,$year)=explode('/',$next_list_date1);
$list_cdate1=$year.'-'.$month.'-'.$day;
 }

if($list_cdate!='' and $list_cdate1!='')
{
$stnq = $db->prepare("select * from $schemas.case_detail where dt_of_filing  between ? and ?");
$stnq->bindParam(1, $list_cdate, PDO::PARAM_STR);
$stnq->bindParam(2, $list_cdate1, PDO::PARAM_STR);
$stnq->execute();

if($stnq->rowCount() =='0'){

}
} 
}

//}

?>


<!------------------------>
<!------------------------>
<!------------------------>
<?php //diary wise
if($search_by =='diary_wise'){?>
<div class="col-sm-4">
<div class="form-group">
<label for="email">DAIRY NO :</label>
<?php  $dairy_no = isset($_REQUEST['dairy_no']) ? $_REQUEST['dairy_no'] :''; ?>
<input type="text" class="form-control" id="dairy_no" onkeypress="return isNumberKey(event)" maxlength="16" size="5" autocomplete="off" name="dairy_no" value="<?php print htmlspecialchars(htmlentities($dairy_no)); ?>" style="color:#2E2E2E; width:180px;"/>
</div>
</div>


<!--<div class="col-sm-4">
<div class="form-group">
<label for="email">DAIRY YEAR :</label>
<?php  //$dairy_year = isset($_REQUEST['dairy_year']) ? $_REQUEST['dairy_year'] :''; ?>
<input type="text" class="form-control" id="dairy_year" onkeypress="return isNumberKey(event)" maxlength="4" autocomplete="off" size="5" name="dairy_year" value="<?php //echo htmlspecialchars(htmlentities($dairy_year));?>" />
</div>
</div>-->


<div class="col-sm-4">
<div class="form-group">
<label for="email">&nbsp;</label>
<input id="submit1" type="button" class="form-control btn btn-primary"   name="submit2" value="SEARCH" onClick="return submitForm();" />
</div>
</div>
</div>
<?php 

if($dairy_no!='')
{
 $diary_no   =   htmlspecialchars(htmlentities($_REQUEST['dairy_no']));
//$diary_year =   htmlspecialchars(htmlentities($_REQUEST['dairy_year']));
$diarynumber=   $diary_no;
$stnq = $db->prepare("select * from $schemas.case_detail where filing_no =? ");
$stnq->bindValue(1,"$diarynumber", PDO::PARAM_INT);
$stnq->execute();
  if($stnq->rowCount() =='0'){
    $noscrutiny = 1;
      //echo $j = "select * from $schemas.scrutiny where filing_no ='$diarynumber' ";
    $gdfs = $db->prepare("select * from $schemas.scrutiny where filing_no =? ");
    $gdfs->bindValue(1,"$diarynumber", PDO::PARAM_INT);
    $gdfs->execute();
  }
}   
}

?>



<!------------------------>
<!------------------------>
<!------------------------>
<?php //case no wise
if($search_by =='case_wise'){?>

<div class="col-sm-3">
<div class="form-group">
<label for="email">CASE TYPE :</label>
<?php  $case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] :''; ?>
<select name="case_type" class="form-control" id="case_type" >
<?php
$st = $db->prepare("select * from case_type where status='t' order by case_type_desc_cis asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ctc=htmlspecialchars($row['id']);
if($case_type == $ctc)
{
print "<option value=".htmlspecialchars($row['id'])." selected>".htmlspecialchars($row['case_type_desc_cis'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['id']).">".htmlspecialchars($row['case_type_desc_cis']).'-'."</option>";
}
}
?>
</select>
</div>
</div>

<div class="col-sm-3">
<div class="form-group">
<label for="email">CASE NO :</label>
<?php  $case_no = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] :''; ?>
<input type="text" id="cno" class="form-control"  onkeypress="return isNumberKey(event)" maxlength="7" size="6" autocomplete="off" name="case_no" value="<?php print $case_no; ?>" />
</div>
</div>

<div class="col-sm-3">
<div class="form-group">
<label for="email">CASE YEAR:</label>
<?php  $case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] :''; ?>
<input type="text" id="case_year" class="form-control"  onkeypress="return isNumberKey(event)" maxlength="4" autocomplete="off" size="5" name="case_year" value="<?php echo htmlspecialchars(htmlentities($case_year));?>" style="color:#2E2E2E; width:139px;" />
</div>
</div>


<div class="col-sm-3">
<div class="form-group">
<label for="email">&nbsp;</label>
<input id="submit1" type="button" class="form-ścontrol btn btn-primary" id="cryptstr"  name="submit1" value="SEARCH" onClick="return submitForm();" />
</div>
</div>
</div>
<?php 

/*echo $casenumber ="4".str_pad($case_type,3, "0",STR_PAD_LEFT).str_pad($case_no,7,"0",STR_PAD_LEFT).$case_year; 

$stn = $db->prepare("select filing_no from $schemas.case_detail where case_no=? ");
$stn->bindParam(1, $casenumber, PDO::PARAM_INT);
$stn->execute();
$filing_no = $stn->fetchColumn();
$stnq = $db->prepare("select * from $schemas.case_detail where filing_no=?");
$stnq->bindParam(1,$filing_no,PDO::PARAM_INT);
$stnq->execute(); */
//echo $stnq="select * from $schemas.case_detail where case_no='$case_no' and case_year='$case_year' and case_type='$case_type'";
if($case_no!='' and $case_year!='')
{
$stnq = $db->prepare("select * from $schemas.case_detail where case_no=? and case_year=? and case_type=?");
$stnq->bindParam(1,$case_no,PDO::PARAM_INT);
$stnq->bindParam(2,$case_year,PDO::PARAM_INT);
$stnq->bindParam(3,$case_type,PDO::PARAM_INT);
$stnq->execute();
}
} 
?>


<!------------------------>
<!------------------------>
<!------------------------>
<?php //party_wise
if($search_by =='party_wise'){?>
<div class="col-sm-6">
<div class="form-group">
<label for="email">PARTY NAME :</label>
<?php  $namee = isset($_REQUEST['namee']) ? $_REQUEST['namee'] :''; ?>
<input type="text"  maxlength="20" class="form-control"  size="20" autocomplete="off" name="namee" value="<?php print htmlspecialchars(htmlentities($namee)); ?>" />
</div>
</div>


<div class="col-sm-6">
<div class="form-group">
<label for="email">&nbsp;</label>
<input id="submit1" type="button" class="form-control btn btn-primary" id="cryptstr"  name="submit1" value="SEARCH" onClick="return submitForm();" />
</div>
</div>
</div>

<?php 

if($namee!='' and $loc_id!='')
{
	
 $name =htmlentities(htmlspecialchars(strtoupper($_REQUEST['namee'])));
 $stnq = $db->prepare("select * from $schemas.case_detail  where pet_name like ? or res_name like ? ");
 $stnq->bindValue(1,"%$name%", PDO::PARAM_STR);
 $stnq->bindValue(2,"%$name%", PDO::PARAM_STR);
 $stnq->execute();


} 
}?>


<!--------------------------->
<!--------------------------->
<!--------------------------->

<table class="table">


<?php
if(isset($stnq)) 
{


 if($stnq->rowCount() =='0')
{?>
<tr>
<td colspan="5"> 
<div class="alert alert-danger">
<strong>!!!</strong> No Record Found In Case Detail.
</div>
</td>
</tr>	
<?php } 



?>	
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
//if($filing_no98 !='')
//{
 $case_type=$rw2['case_type'];
$case_no=$rw2['case_no'];
$case_year=$rw2['case_year'];
 $location_code=$rw2['location_code'];

if($case_type > 0 && $case_no!='' && $case_year!='' && $location_code!='')
{
$lcodec ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
$lcodec=$db->prepare($lcodec);
$lcodec->execute();
$lcodenamec = $lcodec->fetchColumn();

$stQc = $db->prepare("select short_name from case_type where id = ?");
$stQc->bindParam(1, $case_type, PDO::PARAM_STR);
$stQc->execute();
$case_type_short_namec=$stQc->fetchColumn();
}

if($case_type > 0 && $case_no!='' && $case_year!='' )
{
	

$stQc = $db->prepare("select short_name from case_type where id = ?");
$stQc->bindParam(1, $case_type, PDO::PARAM_STR);
$stQc->execute();
$case_type_short_namec=$stQc->fetchColumn();
}


$case_numaac = $case_no;
$case_year1aac = $case_year;
		$case_num1aac=$case_numaac;

$CASE_NOc = htmlspecialchars(strtoupper($case_type_short_namec).'/'.$case_num1aac.'('.$lcodenamec.')'.$case_year1aac);


/*if($case_type > 0)
{
//echo $sql="select short_name from case_type where id = '$case_type'";
$stQ = $db->prepare("select short_name from case_type where id = ?");
$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
$stQ->execute();
$case_type_data=$stQ->fetch();
$case_type_short_name =$case_type_data['short_name'];
//$case_type_nature =$case_type_data['case_nature'];
}



$case_numaa = substr($case_no,4,7);
$case_num1aa=ltrim($case_numaa,0);
$case_year1aa = substr($case_no,11,4);*/


//}
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
echo $CASE_NOc;
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
}
}// case_detail loop 
//while loop end all query....
}?>
</tbody>


<!------------------------- Separeate table for cases at user scrutiny or ar level--------------------------->
<?php
if(isset($gdfs)) 
{

 if($gdfs->rowCount() =='0')
{?>
<tr>
<td colspan="5"> 
<div class="alert alert-danger">
<strong>!!!</strong> No Record Found In Scrutiny too.
</div>
</td>
</tr>	
<?php } 



?>	
<?php
if($gdfs->rowCount() > 0)
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
while ($rw2 = $gdfs->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
extract($rw2);

$filing_no;
$gdfecd=$dbonline->prepare("select * from e_case_detail where filing_no=?");
		$gdfecd->bindParam(1, $filing_no, PDO::PARAM_STR);
		$gdfecd->execute();
		
		while ($rgdfecd = $gdfecd->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
            $dt_of_filing=$rgdfecd['dt_of_filing']; 
            $CASE_NOc='NA';
        }

				$gdfecdl=$db->prepare("select * from e_case_detail_local where filing_no=?");
				$gdfecdl->bindParam(1, $filing_no, PDO::PARAM_STR);
				$gdfecdl->execute();
				
				while ($rgdfecdl = $gdfecdl->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
				{
					$pet_name = htmlspecialchars($rgdfecdl['pet_name']);
					$res_name = htmlspecialchars($rgdfecdl['res_name']);
						}
      
list($yy,$mm,$dd) = explode("-",$dt_of_filing);
$dt_of_filing = $dd.'/'.$mm.'/'.$yy;
$scrtok = 1;
$hash=base64_encode($filing_no.'/'.$schemas.'/'.$scrtok);

?>

<tr>
<td><?php echo $iii;?></td>
<td><a href="javascript:popsurety_pet_adv_name('<?php print htmlspecialchars($hash); ?>');">
<?php 
if($case_no !='')
{
echo $CASE_NOc;
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
}
}// case_detail loop 
//while loop end all query....
}?>
</tbody>

</table>		
</div>
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

<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();
$judge =htmlentities($_REQUEST['judge']);
//print_r($judge);
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
	
	
	if($_REQUEST[hash2]!=''){
		$hashfrom_view = base64_decode($_REQUEST[hash2]);
		list($preciding,$bench_code1,$court_no1,$list_date,$location_code) =explode("/",$hashfrom_view);
		list($ly,$lm,$ld) =explode("-",$list_date);
		$from_list_date1 = $ld.'/'.$lm.'/'.$ly;
	}

?>
<?php 
//include '../inheader.php';
//include '../insidebar.php';

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
		action = "modify_bench.php";
		submit();
	}
}
function submitForm1()
{
 	with(document.frm)
	{

		action = "modify_bench_action.php";
		submit();
	}
}
</script>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Create Bench</title>
    <link rel="stylesheet" href="../src/calendar.css">
    <style type="text/css">
        html {
            font: 500 14px "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #333;
            height: 100%;
        }

        body {
            height: 100%;
            margin: 0;
        }

        a {
            text-decoration: none;
        }

        ul,
        ol,
        li {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #demo {
            width: 400px;
            margin: 30px auto;
        }

        p {
            margin: 0;
        }

        input {
            margin: 10px 0;
            height: 28px;
            width: 200px;
            padding: 0 6px;
            border: 1px solid #ccc;
            outline: none;
        }

    </style>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <center>Modify Bench Priority
        </center>
      </h1>
      
    </section>
 <!--p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p-->
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">

  
<form name="frm" method="post" action="bench_action.php" >

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


<tr style="display:none;">
<td align="left">
<font color="red">*</font></span>Listing Date</font>
</td>
<td style="text-align: left;">
    <div id="demo" >
       
       <!--  <div id="one"></div> -->

        <?php $from_list_date = isset($_REQUEST['from_list_date']) ? $_REQUEST['from_list_date'] :$from_list_date1; ?>

        <input type="text" name="from_list_date" id="from_list_date" placeholder="Listing Date" onChange="javascript:submitForm();"
		value="<?php echo $from_list_date; ?>"
		>
        <div id="two"></div>
        
    </div>
    <script src="../bower_components/bootstrap/dist/js/jquery.min.js"></script>
    <script src="../src/calendar.js"></script>
<?php 

$sth = $db->prepare("select title,startdate from $schemas.calendar");
$sth->execute();
while ($rw2 = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$date=$rw2['startdate'];
	$dd=explode("-",$date);
        $datenew=$dd[0]."+'-'+".$dd[1]."+'-'+".$dd[2];
	$title=$rw2['title'];
	$datformat="{date:".$datenew.", value:'".$title."'},";
	$datformat1=$datformat1.$datformat;
	 	
}
$datformat1=rtrim($datformat1,",");
$newdata="[".$datformat1."];";
?>          
    <script>
        var now = new Date();
        var year = now.getFullYear();
        var month = now.getMonth() + 1;
        var date = now.getDate();

       
        var data =<?php echo $newdata; ?>;
 		

        // inline
        var $ca = $('#one').calendar({
            // view: 'month',
            width: 320,
            height: 320,
            // startWeek: 0,
            // selectedRang: [new Date(), null],
            data: data,
            monthArray: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            date: new Date(2016, 9, 31),
            onSelected: function (view, date, data) {
                console.log('view:' + view)
                console.log('date:' + date)
                console.log('data:' + (data || '?'));
            },
            viewChange: function (view, y, m) {
                console.log(view, y, m)

            }
        });

        // picker
        $('#two').calendar({
            trigger: '#from_list_date',
            // offset: [0, 1],
            zIndex: 999,
            data: data,
            onSelected: function (view, date, data) {
                console.log('event: onSelected')
            },
            onClose: function (view, date, data) {
                console.log('event: onClose')
                console.log('view:' + view)
                console.log('date:' + date)
                console.log('data:' + (data || '?'));
            }
        });

        // Dynamic elements
        var $demo = $('#demo');
        var UID = 1;
        $('#add').click(function () {
            $demo.append('<input id="input-' + UID + '"><div id="ca-' + UID + '"></div>');
            $('#ca-' + UID).calendar({
                trigger: '#input-' + UID++
            })
        })
    </script>

</td>
</tr>

<tr style="display:none;">
<?php $old_court_no = isset($_REQUEST['old_court_no']) ? $_REQUEST['old_court_no'] :$court_no1; ?>
<td align="left" ><font color="red">*</font></span>Court No.</font></td>
<td>   
<input onkeypress="return isNumberKey(event)" type="text" autocomplete="off"  maxlength="3" size="2" name="old_court_no" value="<?php echo htmlspecialchars(htmlentities($old_court_no)); ?>">
</td>
</tr>






<tr style="display:none;">
  <td  align="left"><font face="Verdana" size="2" ><font color="red">*</font></span> Bench</font></td>
  <td align="left">
  <?php $bench_location = isset($_REQUEST['bench_location']) ? $_REQUEST['bench_location'] :$location_code; ?>
<select name="bench_location"  id="test"  style='width:180px;' onChange="javascript:submitForm();">
<option value="">-select-</option>
<?php
$sqlm1=$db->prepare("select * from $schemas.bench_location where display=? ");
$display='Y';
$sqlm1->bindParam(1, $display, PDO::PARAM_STR);
$sqlm1->execute();
while ($row1 = $sqlm1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$bench_location1 =$row1['bench_location_code'];
?>   
<option value="<?php echo htmlspecialchars($row1['bench_location_code']);?>" <?php if($bench_location==$bench_location1)echo "selected"; ?>><?php echo htmlspecialchars($row1['bench_location_name']); ?></option>
<?php 
}
?>
</select>
</td>
</tr>
<tr style="display:none;">
<td align="left"><font face="Verdana" size="2" ><font color="red">*</font></span> Bench Nature</font></td>
  <td align="left">
  <?php  $bench_code = isset($_REQUEST['bench_code']) ? $_REQUEST['bench_code'] :$bench_code1; ?>
<select name="bench_code" id="test"  style='width:150px;' onChange="javascript:submitForm();">
<option value="">-select-</option>
<?php
$sqlm=$db->prepare("select * from $schemas.bench_nature where display=? order by bench_code ASC");
$display='Y';
$sqlm->bindParam(1, $display, PDO::PARAM_STR);
$sqlm->execute();
while ($row = $sqlm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

//$bench_code =$_POST['bench_code'];
?>   
<option value="<?php echo htmlspecialchars($row['bench_code']);?>" <?php if($bench_code==$row['bench_code']){ echo "selected"; } ?>><?php echo htmlspecialchars($row['bench_name']); ?></option>
<?php 
}
?>
</select>
</td>
</tr>
<tr style="display:none;">
<td align="left">
<font color="red">
<?php  
if($benchlocation ==1)
{
$court_no =1;
}
//$bench_code =htmlentities($_POST['bench_code']);
if($bench_code >0)
{

$sql="select no_of_judges from $schemas.bench_nature where bench_code = ? ";
$sth = $db->prepare($sql);
$sth->bindParam(1, $bench_code, PDO::PARAM_STR);
$sth->execute();
$no_of_judge = $sth->fetchColumn();
echo"<b>NUMBER OF JUDGES:</b>   ". htmlspecialchars($no_of_judge)."<br>";

}
?>

</font>
</td>
<td>
<input type="hidden" maxlength="2" size="4" name="judge_count" value="<?php echo htmlspecialchars(htmlentities($no_of_judge)); ?>">
</td>
</tr>

<tr style="display:none;">
<td colspan="3">
<hr>
<b><i><font face="verdana" color='red'><center>

<?php
if($_REQUEST['bench_code']==3)
{
?>
Registrar 
<?php
}
else
{
?>
CORAM 
<?php
}
?>
</center></font></i></b>
</td>
</tr>
<?php

if($_REQUEST['bench_code']==3)
{
	$name='Registrar';
}
else {
	$name='Select';
}
$m=0;



/*select all judge from the list */
if($bench_code>0 && $old_court_no >0 && $bench_location >0){
	list($dd,$mm,$yy)= explode('/',$from_list_date);
	$from_list_date=$yy.'-'.$mm.'-'.$dd;
$check_bench = $db->prepare("select * from $schemas.bench where bench_nature=? and court_no=? and location_code=? and from_list_date=? ");
$check_bench->execute(array($bench_code,$old_court_no,$bench_location,$from_list_date)); 
//echo $check_bench->rowCount();
if($check_bench->rowCount()==0)
{
	$check_status = "bench not found";
}
if($check_bench->rowCount()>0)
{
	$bench_data = $check_bench->fetch();
	//print_r($bench_data);	
	extract($bench_data);
	$chech_judge = $db->prepare("select judge_code from $schemas.bench_judge where bench_no=? and from_list_date=?");
	$chech_judge->execute(array($bench_no,$from_list_date));
	if($chech_judge->rowCount()>0 && $chech_judge->rowCount()==$no_of_judge)
	{
		$chech_judge = $chech_judge->fetchAll();		
		//print_r($chech_judge);	
	}	
}
}


$check_n_allo = $db->prepare("select * from $schemas.case_allocation where listing_date =? and bench_no=?");
$check_n_allo->execute(array($from_list_date,$bench_no));
if($check_n_allo->rowCount()>0){
$check_status ="you cant modify this bench ! you have allready listed case on this bench";

?>

<tr style="display:none;"><td colspan="2" align="center">
<?php echo $check_status;?>
 </td></tr>


<?php
}
else{



if(!empty($chech_judge)){
	$j=0;
	foreach($chech_judge as $juddd){
		//print_r($juddd);
		
	?>
	
	<tr style="display:none;">
<td  align="right" nowrap="nowrap"><font color="red">*</font></span><?php echo $name; ?></font></td>
<td align="left">
<?php
  $judge[$j] = isset($_REQUEST['judge'][$j]) ? $_REQUEST['judge'][$j] :$juddd[judge_code];
//print_r($judge[$j]);
  ?>
<select name="judge[<?php echo $j?>]"  style='width:300px;' >

<option value="">-select-</option>
<?php
$display='TRUE';
$rcode=$_REQUEST['bench_code'];
if($rcode==3)
{
$sqlf=$db->prepare("select * from $schemas.master_judge where display=? and judge_code=? order by judge_desg_code ASC");	
$sqlf->bindParam(1, $display, PDO::PARAM_STR);
$sqlf->bindParam(2, $rcode, PDO::PARAM_STR);
?>
<?php
}
if($rcode !=3)
{
	$rcode=3;
	$sqlf=$db->prepare("select * from $schemas.master_judge where display=? and judge_code<>? order by judge_desg_code ASC");
	$sqlf->bindParam(1, $display, PDO::PARAM_STR);
	$sqlf->bindParam(2, $rcode, PDO::PARAM_STR);
}
if($benchlocation ==1 and $bench_code ==1)
{
$sqlf=$db->prepare("select * from $schemas.master_judge where judge_desg_code=1 and display='TRUE'");
}


$sqlf->execute();
while ($row = $sqlf->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
?>
<option value="<?php echo htmlspecialchars($row['judge_code']);?>" <?php if($judge[$j]==$row[judge_code])echo 'selected' ;?>> <?php echo htmlspecialchars($row['judge_name']); ?></option>
<?php
}
?>
</select>
</td>
</tr>
	
	
	
<?php
$j++;	
	}
}


?>
<?php
if($no_of_judge >1)
{
?>
<tr style="display:none;">
<td  align="right" nowrap="nowrap"><font color="red">*</font></span><b><i>Select Presiding Judge</b></i></font></td>
<td align="left">
<select name="presiding"  style='width:300px;' >>

<?php
$m=0;
for($i=0;$i<$no_of_judge;$i++)
{
$m++; 
?>
<option value="<?php echo htmlspecialchars($i);?>" <?php if($presiding ==$i)echo 'selected' ;?>> Judge <?php echo htmlspecialchars($m);?></option>"; 
<?php
}
?>
</select>
</td>
</tr>
<?php
}
?>
<tr style="display:none;">
<td colspan="3">
<hr>
</td>
</tr>


<tr style="display:none;">
<td align="left" ><font color="red">*</font></span>New Court No.</font>
<input type="hidden"    name="bench_no" value="<?php echo $bench_no; ?>" >

</td>
<td> 
  
<input onkeypress="return isNumberKey(event)" type="text"  autocomplete="off"  maxlength="3" size="2" name="court_no" value="<?php echo htmlspecialchars(htmlentities($court_no)); ?>">
</td>
</tr>
<tr style="display:none;">
<td align="left" ><font color="red">*</font></span>Limit of Case.</font></td>
<td>   
<input onkeypress="return isNumberKey(event)" type="text"  autocomplete="off"  maxlength="3" size="2" name="limit_case" value="<?php echo htmlspecialchars(htmlentities($limit_case)); ?>">
</td>
</tr>
<tr style="display:none;">
<td align="left" ><font face="Verdana" size="2">Bench Header Remarks</font></td>
<td>   
<input onkeypress="return isNumberKey(event)" type="text" autocomplete="off"   maxlength="100" size="50" name="bench_remarks" value="<?php echo htmlspecialchars(htmlentities($detail)); ?>">
</td>
</tr>
<tr style="display:none;">
<td align="left">
<font face="Verdana" size="2"> Time(Display In Cause List) </font>
</td>
<td align="left">
<input type="text" autocomplete="off" name ="detail" maxlength="100"  size="2"  value="<?php echo htmlspecialchars(htmlentities($from_time)); ?>" >
</td>
</tr>

</table>
<table cellspacing="0" align="center" cellpadding="2" border="1"  width="90%" class="std">  

<tr style="display:none;">
<td  align="center" nowrap="nowrap"><font face="Verdana" size="2" ><b><i>Purpose </b></i></font></td>
</td><td  align="center" nowrap="nowrap"><font face="Verdana" size="2" ><b><i>Priority </b></i></font></td>
</tr>
<?php
$check_p_priority = $db->prepare("select purpose,priority from $schemas.bench_purpose_priority where 
from_date=? and bench_no=? and court_no=? order by purpose ASC");
$param = array($from_list_date,$bench_no,$old_court_no);
$check_p_priority->execute($param);
if($check_p_priority->rowCount()>0)
{
$check_p_prioritys = $check_p_priority->fetchAll();		
//print_r($check_p_prioritys);	
}	

if(!empty($check_p_prioritys)){
$tt=0;
foreach($check_p_prioritys  as $pror)
{
extract($pror);
$sqlf=$db->prepare("select purpose_name,purpose_code from $schemas.master_purpose where purpose_code=?");
$sqlf->execute(array($purpose));
extract($sqlf->fetch());	
?>
<tr >
<td align="center"> <?php echo $purpose_name;?></td>
<td align="center">
<input type="text" autocomplete="off" name ="purpose_priority[<?php echo $tt?>]
" maxlength="5" size="2"  value="<?php  echo htmlspecialchars(htmlentities($priority)); ?>" >
<input type="hidden" name="purpose_code[<?php echo $tt?>]" value="<?php echo htmlspecialchars($purpose_code); ?>" />
</td>
</tr>
<?php 
$tt++;
}
}
?>
<tr>
<td colspan="3" align="center">
<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<input type="submit" name="submit1" class="btn btn-primary" value="Submit" class="button" onClick="return submitForm1();">     
</td>
</tr>

<?php } ?>
</form>    
</table>
        
<?php
} //session condition end 
 // include '../infooter.php';
  ?>   

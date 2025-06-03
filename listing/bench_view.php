<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();

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



	// This code not use next time .......	Schema session create Hear....


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
		action = "create_bench.php";
		submit();
	}
}
</script>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Calendar demo</title>
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
        <center>Bench View/Update
        </center>
      </h1>
      
    </section>

   <?php 
 echo  $sth = $db->prepare("select bench.bench_nature,bench.bench_no,bench.court_no,bench.from_list_date,bench.to_list_date,bench.presiding,bj.judge_code from bench inner join bench.judge bj on bench.bench_no=bj.bench_no");
	$sth->execute();
	
	while ($rw2 = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$bench_nature=$rw2['bench_nature'];
		$bench_no=$rw2['bench_no'];
		$court_no=$rw2['court_no'];
		$from_list_date=$rw2['from_list_date'];
		$to_list_date=$rw2['to_list_date'];
		$presiding=$rw2['presiding'];
		$judge_code=$rw2['judge_code'];
			
	}
	echo $bench_nature;
	
	
?>
    
 <?php die();?>   
 <p> <h4><center>All <font color="red">*</font> is mandatory Field </center></h4></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">  
<!-- <form name="frm" method="post" action="bench_action.php" > -->
<form name="frm" method="post" >

  <?php  $bench_location= isset($_REQUEST['bench_location']) ? $_REQUEST['bench_location'] :'';?>


<tr>
      <td  align="left"><font face="Verdana" size="2" ><span class="error">*</span> Bench Nature</font></td>
  <td align="left">
<select name="bench_code" id="test"  style='width:150px;' onChange="javascript:submitForm();">
<option value="">-select-</option>
<?php
$sqlm=$db->prepare("select * from $schemas.bench_nature where display=? order by bench_code ASC");
$display='Y';
            $sqlm->bindParam(1, $display, PDO::PARAM_STR);
            $sqlm->execute();
			while ($row = $sqlm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{

$bench_code =$_POST['bench_code'];
?>   
<option value="<?php echo htmlspecialchars($row['bench_code']);?>" <?php if($bench_code==$row['bench_code']){ echo "selected"; } ?>><?php echo htmlspecialchars($row['bench_name']); ?></option>
<?php 
}
?>
</select>
</td>

<td align="left">
<font color="red">
<?php  
$bench_code =$_POST['bench_code'];

$court_no =$_POST['court_no'];
if($bench_code >0)
{
if($bench_code!='7'){ 
$sql="select no_of_judges from $schemas.bench_nature where bench_code = ? ";
$sth = $db->prepare($sql);
$sth->bindParam(1, $bench_code, PDO::PARAM_STR);
$sth->execute();
$no_of_judge = $sth->fetchColumn();
echo"<b>NUMBER OF JUDGES:</b>   ". htmlspecialchars($no_of_judge)."<br>";
}
}
?>

</font>
</td>
<td>
<input type="hidden" maxlength="2" size="4" name="judge_count" value="<?php echo htmlspecialchars(htmlentities($no_of_judge)); ?>">
</td>
</tr>
<tr>
  <td  align="left"><font face="Verdana" size="2" ><span class="error">*</span> Bench</font></td>
  <td align="left">
<select name="bench_location"  id="test"  style='width:180px;' >
<option value="">-select-</option>
<?php
$sqlm1=$db->prepare("select * from $schemas.bench_location where display=? ");
$display='Y';
            $sqlm1->bindParam(1, $display, PDO::PARAM_STR);
            $sqlm1->execute();
			while ($row1 = $sqlm1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{

$bench_location =$row1['id'];
?>   
<option value="<?php echo htmlspecialchars($row1['bench_location_code']);?>" <?php if($bench_location==$row1['id']){ echo "selected"; } ?>><?php echo htmlspecialchars($row1['bench_location_name']); ?></option>
<?php 
}
?>
</select>
</td>

</tr>
<tr>
<td align="left">
<font face="Verdana" size="2"><span class="error">*</span>Listing Date</font>
</td>
<td style="text-align: left;">
    <div id="demo" >
       
       <!--  <div id="one"></div> -->

        
        <input type="text" name="from_list_date" id="from_list_date" placeholder="Listing Date">
        <div id="two"></div>
        
        
        <!-- <p><button type="button" id="add">Add input</button></p> -->
    </div>
    <script src="../bower_components/bootstrap/dist/js/jquery.min.js"></script>
    <script src="../src/calendar.js"></script>
<?php 

$sth = $db->prepare("select title,startdate from calendar");
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

<!--  <input name="from_list_date" placeholder="DD/MM/YYYY" size="17" 
 value="<?php echo htmlspecialchars(htmlentities($from_list_date));?>"> -->
</td>
</tr>
<tr>
<td align="left" ><font face="Verdana" size="2"><span class="error">*</span>Court No.</font></td>
<td>   
<input onkeypress="return isNumberKey(event)" type="text" autocomplete="off"  maxlength="3" size="2" name="court_no" value="<?php echo htmlspecialchars(htmlentities($court_no)); ?>">
</td>
</tr>

<?php

$m=0;
for($i=0;$i<$no_of_judge;$i++)
{
$m++;
?>
<tr>
<td  align="right" nowrap="nowrap"><font face="Verdana" size="2" ><span class="error">*</span>Select Judge<?php echo htmlspecialchars($m);?></font></td>
<td align="left">
<select name="judge[]"  style='width:300px;'>>
<?php


$sqlf=$db->prepare("select * from $schemas.master_judge where display=? order by judge_desg_code ASC");
$display='TRUE';
            $sqlf->bindParam(1, $display, PDO::PARAM_STR);
            $sqlf->execute();
			while ($row = $sqlf->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{
           		$judge_code =$row['judge_code'];
				
				
?> 
<option value="<?php echo htmlspecialchars($row['judge_code']);?>"> <?php echo htmlspecialchars($row['judge_name']); ?></option>
<?php 
}
?>
</select>
</td>
</tr>
<?php
}


?>
<?php
if($no_of_judge >1)
{
?>
<tr>
<td  align="right" nowrap="nowrap"><font face="Verdana" size="2" ><span class="error">*</span><b><i>Select Presiding Judge</b></i></font></td>
<td align="left">
<select name="presiding"  id="test"  style='width:180px;' >
<option value="">-select-</option>
<?php
$sqlm1=$db->prepare("select * from $schemas.master_judge  ");

           
            $sqlm1->execute();
			while ($row1 = $sqlm1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{

$presiding_code =$row1['judge_code'];
?>   
<option value="<?php echo htmlspecialchars($row1['judge_code']);?>" <?php if($presiding==$row1['judge_code']){ echo "selected"; } ?>><?php echo htmlspecialchars($row1['judge_name']); ?></option>
<?php 
}
?>
</select>
</td>
</tr>
<?php
}
?>
<tr>
<td colspan="3">
<hr>
</td>
</tr>
</table>
<table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">  

<tr>
<td  align="center" nowrap="nowrap"><font face="Verdana" size="2" ><b><i>Purpose </b></i></font></td>
</td><td  align="center" nowrap="nowrap"><font face="Verdana" size="2" ><b><i>Priority </b></i></font></td>
<?php
if($m!=0)
	{

$sqlf=$db->prepare("select * from $schemas.master_purpose where display=? order by purpose_code ASC");
$display='TRUE';
            $sqlf->bindParam(1, $display, PDO::PARAM_STR);
            $sqlf->execute();
			while ($row = $sqlf->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{
           		$purpose_name =$row['purpose_name'];
				$purpose_priority =$row['purpose_priority'];
				$purpose_code =$row['purpose_code'];
?> 

</tr>
<tr>
<td align="center">
<?php
echo $purpose_name;?>
<td align="center">
<input type="text" autocomplete="off" name ="purpose_priority[]
" maxlength="5" size="2"  value="<?php echo htmlspecialchars(htmlentities($purpose_priority)); ?>" >
</td>
<input type="hidden" name="purpose_code[]" value="<?php echo htmlspecialchars($purpose_code); ?>" />
<?php
			}
	}

			?>

</td>


</tr>
<tr>
<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<td colspan="3" align="center">
<input type="submit" name="submit1" value="Submit" class="button" onClick="return validate();">     
</td></tr>
   </form>    
 <?php 
  include '../infooter.php';
  ?>          
<?php
}
?>

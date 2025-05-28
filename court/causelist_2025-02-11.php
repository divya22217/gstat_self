

<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
//session_start();
include '../inheader.php';
//include '../insidebar.php';

$next_list_date =$_REQUEST['next_list_date'];

$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$user_court = $_SESSION['user_court'];
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}

//setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


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
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<title>Draft Casue List</title>
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script language="javascript">
function submitForm3()
{
 	with(document.frm)
	{		
	 action = "causelist.php";
	 submit();
	}
}
function goFinal1()
{
 	with(document.frm)
	{


 		/*if(listflag.options[listflag.selectedIndex].value == "")
 		{
 			alert("Please Select Type  ");
 			listflag.focus();
 			return false;
 		}
 		if(list_before.options[list_before.selectedIndex].value == "")
 		{
 			alert("Please Select Bench Nature  ");
 			list_before.focus();
 			return false;
 		}*/
		/*
 		if(courtno.options[courtno.selectedIndex].value == "")
 		{
 			alert("Please Select Court No.  ");
 			courtno.focus();
 			return false;
 		}
		*/
		
 		action="generate_cause_list.php";
 		submit();
 	    	document.frm.submit11.disabled = true;  
 	     	document.frm.submit11.value = 'Please Wait...';  
 	     	return true;


 		}
 	}
</script>
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>




<div class="content-wrapper">
	<div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
		<div class="box box-success">
		<div class="box-header">
			<div class="form-group">
				<center><a href="../index.php"><font face="Verdana" color="red" size="3"><center><b>BACK</b></center></font></a></center>
				</br>
				<font face="Verdana" color="red" size="3"><center><b>Cause List Report</b></center></font>
			</div>
			
		</div>
			<div class="box-body">

<form name="frm" method="post" action="generate_cause_list.php">
<?php 
$causelist_type = isset($_REQUEST['cause_list_type'])?$_REQUEST['cause_list_type']:'1';
?>
<!--<div class="form-group row">
	<center>
		<label class="radio-inline"><input type="radio" name="cause_list_type" value="3" <?php echo ($causelist_type == '3')?'checked':''; ?>>Priority</label>
		<label class="radio-inline"><input type="radio" name="cause_list_type" value="1" <?php echo ($causelist_type == '1')?'checked':''; ?>>Daily</label>
		<label class="radio-inline"><input type="radio" name="cause_list_type" value="2" <?php echo ($causelist_type == '2')?'checked':''; ?> >Supplementry</label>
	</center>
</div>-->

<input type="hidden" name="cause_list_type" value='1'>

<div class="form-group row">
	<label for="listing_Date" class="col-sm-offset-2 col-sm-2 form-label"><font color="red">*</font></span></font>Date:(DD/MM/YYYY)</label>
	<div class="col-sm-6">
		<input type="text" id="next_list_date" name="next_list_date" autocomplete="off"  class="form-control datepicker" size="10" value="<?php print htmlspecialchars($next_list_date); ?>"/>
	</div>
</div>

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
            trigger: '#next_list_date',
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

<?php

/* if($next_list_date !='')
{ 
 list($d,$m,$Y) =explode('/',$next_list_date);
$listing_date =$Y.'-'.$m.'-'.$d; */


$sql="select * from $schemas.court where court_no = $user_court order by court_no ASC";

	
?>

<div class="form-group row">
	<label for="listing_Date" class="col-sm-offset-2 col-sm-2 col-form-label"><font color="red">*</font></span></font>Court</label>
	<div class="col-sm-6">
		<select name="court_no" id="test" class="form-control">
<?php
$sqlm=$db->prepare($sql);

            //$sqlm->bindParam(1, $listing_date, PDO::PARAM_STR);
           //$sqlm->bindParam(2, $list_before, PDO::PARAM_STR);
            $sqlm->execute();
		while ($row = $sqlm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{

                print "<option value=".htmlspecialchars($row['court_no']).">".htmlspecialchars($row['display_court_text'])."</option>";

			}
?>
		</select>
	</div>
</div>

<div class="form-group row">
	<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
	<center><input type="button" id="submit11" type="button" class="btn btn-sm btn-success"  name="submit11" value="Submit" onClick="return goFinal1();" /></center>
</div>


<?php //}?>
</form>

</div>
</div>
</div>
</div>


  

<?php
}
?>

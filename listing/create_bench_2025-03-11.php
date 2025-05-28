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

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$_SESSION['user'];
$location_id = $_SESSION['location'];
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
//include '../insidebar.php';

?>

    <style>
        /* Style for the holiday tooltip */
        .holiday-tooltip {
            background-color: #000000;
            color: white;
            padding: 5px;
            border-radius: 4px;
            position: absolute;
            display: none;
            z-index: 1000;
        }

        /* Highlight holidays */
        .ui-datepicker .holiday a {
            background-color: #f39c12;
            color: white;
        }
    </style>


<script language="javascript">
$(document).ready(function(){
	var executed = false;
	console.log(executed);
	//alert('here');
    $("select.rohit").change(function(){
		executed = false; 
		if(($(".bisht option:selected").val()=='2' || $(".bisht option:selected").val()=='4' || $(".bisht option:selected").val()=='7') && !executed){
        var selectedCountry = $(".rohit option:selected").val();
		$.ajax({ url: 'setpresiding.php',
         data: {action: selectedCountry},
         type: 'post',
         success: function(output) {
                      $('input[name="presiding"]').val(output);
                  }
});
		
        executed = true;
		console.log(executed);
		//alert(selectedCountry);
		}
    });
});
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
function submitForm1()
{
 	with(document.frm)
	{

if(bench_location.value == "")
        	{
        	alert("Please Select Bench !!!!!");
        	bench_location.focus();
        	return false;
        	}

if(bench_code.value == "")
        	{
        	alert("Please Select Bench Nature!!!!!");
        	bench_code.focus();
        	return false;
        	}
var flds1=document.getElementsByName('judge[]');
		for (var i=0;i<flds1.length;i++)
		{
		 	if(flds1[i].value=='')
			{
				alert("Please Select Quorum");
				flds1[i].focus();
				return false;
			}
			
		}
if(bench_code.value ==7 && no_of_judge1.value=="" ){
	alert("Please provide number of members !!!!");
        	no_of_judge1.focus();
        	return false;
}		
if(from_list_date.value == "")
        	{
        	alert("Please Select Listing Date!!!!!");
        	from_list_date.focus();
        	return false;
        	}
/* if(to_list_date.value == "")
        	{
        	alert("Please Select To Date!!!!!");
        	to_list_date.focus();
        	return false;
        	} */
if(court_no.value == "")
        	{
        	alert("Please Enter Court No!!!!!");
        	court_no.focus();
        	return false;
        	}

if(isNaN(court_no.value) == true)
			{
				alert("Please Enter Numeric Court No.");
				court_no.select();
				return false;
			}

if(vdo_cnfr_lnk.value == "")
        	{
        	alert("Please Enter Video Conference Link!!!");
        	limit_case.focus();
        	return false;
        	}

if(meet_pwd.value == "")
        	{
        	alert("Please Enter Meeting Password!!!!");
        	limit_case.focus();
        	return false;
        	}

if(limit_case.value == "")
        	{
        	alert("Please Enter Limit of Case!!!!!");
        	limit_case.focus();
        	return false;
        	}

if(isNaN(limit_case.value) == true)
			{
				alert("Please Enter Numeric for Limit Case");
				limit_case.select();
				return false;
			}

if(limit_case.value!= "")
        	{
if(limit_case.value<1)
        	{
        	alert("Please Enter Valid Limit of Case!!!!!");
        	limit_case.focus();
        	return false;
        	}
}
	action = "bench_action.php";
		submit();
	}
}

function bench_popup() {
    var myWindow = window.open("bench_composition_delete.php", "", "width=1200,height=700");
}

function bench_tab() {
    window.open("bench_composition_delete.php");
}
</script>

</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Create Bench</title>
   
    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
		<div class="box box-success">
			<div class="box-body">
    <section class="content-header">
      <h1>
        <center>Bench Management
        </center>
      </h1>
      
    </section>
 <p> <h4><center>All fields with <font color="red">*</font></span> mark are mandatory. </center></h4></p>
    

  
<form name="frm" method="post" action="bench_action.php" >

<?php
$msg =!empty($_REQUEST['msg'])?htmlentities($_REQUEST['msg']):'';
$msg = urldecode($msg);
if($msg !='')
{
?>
<div class="form-group row">
	<center>
	<font style='font-weight:bold' color='red' size='4'> <?php echo $msg."<a href='bench_composition_delete.php' >Click to View Bench Report</a>";?></font> 
	</center>
</div>
<?php
}
$b_type = (isset($_REQUEST['b_type']))?$_REQUEST['b_type']:'1';
?>

<!-- <div class="form-group row">
	<center>
		<label class="radio-inline"><input type="radio" name="b_type" value="3" <?php echo ($b_type == '3')?'checked':''; ?>>Priority</label>
		<label class="radio-inline"><input type="radio" name="b_type" value="1" <?php echo ($b_type == '1')?'checked':''; ?>>Daily</label>
		<label class="radio-inline"><input type="radio" name="b_type" value="2" <?php echo ($b_type == '2')?'checked':''; ?>>Supplementry</label>
	</center>
</div> -->

<input type="hidden" name="b_type" value="1">


<div class="form-group row">
	<div class="col-sm-6 col-md-6">
		<label for="bench" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Bench</label>
		<div class="col-sm-8">
			<select name="bench_location"  id="test" class="form-control"  onChange="javascript:submitForm();">
				<!--<option value="">-select-</option>-->
				<?php
				$sqlm1=$db->prepare("select * from $schemas.bench_location where display=? AND city_id = ? ");
				$display='Y';
				$bench_display = 'TRUE';
				$sqlm1->bindParam(1, $bench_display, PDO::PARAM_STR);
				$sqlm1->bindParam(2, $location_id, PDO::PARAM_STR);
				$sqlm1->execute();
				while ($row1 = $sqlm1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
				{
				$bench_location1 =$row1['bench_location_code'];
				?>   
				<option value="<?php echo htmlspecialchars($row1['bench_location_code']);?>" <?php if($benchlocation==$bench_location1){ echo "selected"; } ?>><?php echo htmlspecialchars($row1['bench_location_name']); ?></option>
				<?php 
				}
				?>
			</select>
		</div>
	</div>
	<div class="col-sm-6 col-md-6">
		<label for="bench" class="col-sm-4 col-form-label"><font color="red">*</font></span><span id="chnage_ty">Bench Nature</span></font></label>
		<div class="col-sm-8">
			<select name="bench_code" class="bisht form-control" id="test"   onChange="javascript:submitForm();">
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
			<?php  
				
				if($benchlocation ==1)
				{
				$court_no =1;
				}
				$bench_code =htmlentities($_POST['bench_code']);

				if($bench_code >0 and $bench_code!=7)
				{

				$sql="select no_of_judges from $schemas.bench_nature where bench_code = ? ";
				$sth = $db->prepare($sql);
				$sth->bindParam(1, $bench_code, PDO::PARAM_STR);
				$sth->execute();
				$no_of_judge = $sth->fetchColumn();
				if($bench_code!=3){
				echo"<font color='red'><b>NUMBER OF Members:</b>   ". htmlspecialchars($no_of_judge)."<br></font>";
				}

				}

				if($bench_code==7){
				echo "<font ><b>NUMBER OF MEMBERS:</b><input type='text' class='form-control' required='required'onblur='javascript:submitForm();' name='no_of_judge1' value='$_POST[no_of_judge1]'></font>";
				$aaa = $db->prepare("select count(*) from $schemas.master_judge where display='TRUE' and 	judge_desg_code!=6");
				$aaa->execute();
				$dd  =$aaa->fetchColumn();
				if($_POST[no_of_judge1]<=$dd){
				$no_of_judge = $_POST[no_of_judge1];
				}else{ $no_of_judge=$dd;}
				}

				?>

				<input type="hidden" maxlength="2" size="4" name="judge_count" value="<?php echo htmlspecialchars(htmlentities($no_of_judge)); ?>">
		</div>
	</div>
</div>
<div class="form-group row">

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
Quorum 
<?php
}
?>
</center></font></i></b>
</div>
<?php
	if($_REQUEST['bench_code']==3)
	{
		$name='Registrar';
	}
	else {
		$name='Select Member';
	}
	$m=0;



	$arr = array();
	for($i=0;$i<$no_of_judge;$i++)
	{
	$m++;
	$ii=$i+1;
?>
	<div class="form-group row" id="t1">
	<label for="bench" class="col-sm-2 col-form-label"><font color="red">*</font></span></font><?php echo $name; ?></label>
	<div class="col-sm-10">
		<select class="rohit form-control" name="judge[]"  required="required">
		<?php if($_REQUEST['bench_code'] != 3)
		{
			echo '<option value="">-select-</option>';
		} 
				$display='TRUE';
				 $rcode=$_REQUEST['bench_code'];
				
				if($rcode==1)
				{
					
				$sqlf=$db->prepare("select * from $schemas.master_judge where judge_desg_code!='5' and display=? order by judge_desg_code desc");	
				$sqlf->bindParam(1, $display, PDO::PARAM_STR);
				
				}
				if($rcode ==2)
				{
					if($ii == '1'){
						$judge_code=2;
						$sqlf=$db->prepare("select * from $schemas.master_judge where display=? and judge_desg_code = ? order by judge_desg_code desc");
						$sqlf->bindParam(1, $display, PDO::PARAM_STR);
						$sqlf->bindParam(2, $judge_code, PDO::PARAM_STR);
					}else{
						$judge_code=4;
						$sqlf=$db->prepare("select * from $schemas.master_judge where display=? and judge_desg_code = ? order by judge_desg_code desc");
						$sqlf->bindParam(1, $display, PDO::PARAM_STR);
						$sqlf->bindParam(2, $judge_code, PDO::PARAM_STR);
					}

				}
				if($rcode==3)
				{
					
				$sqlf=$db->prepare("select * from $schemas.master_judge where judge_desg_code='5' and display=? and court = ? order by judge_desg_code desc");	
				$sqlf->bindParam(1, $display, PDO::PARAM_STR);
				$sqlf->bindParam(2, $user_court, PDO::PARAM_STR);
				//$sqlf->bindParam(2, $rcode, PDO::PARAM_STR);
				}

				if($rcode ==4 || $rcode ==7)
				{
					if($ii == '1'){
						$judge_code=2;
						$sqlf=$db->prepare("select * from $schemas.master_judge where display=? and judge_desg_code = ? order by judge_desg_code desc");
						$sqlf->bindParam(1, $display, PDO::PARAM_STR);
						$sqlf->bindParam(2, $judge_code, PDO::PARAM_STR);
					}else{
						$sqlf=$db->prepare("select * from $schemas.master_judge where display=? and judge_desg_code != 5 order by judge_desg_code desc");
						$sqlf->bindParam(1, $display, PDO::PARAM_STR);
					}

				}

				
				$sqlf->execute();
				$datata = $sqlf->fetchAll();
				//if($ii!=1){
				//shuffle($datata);
				//}
				//$move = $datata[$i];
				//unset($datata[$i]);
				//array_unshift($datata, $move);


				foreach ($datata  as $row )
				{			

				switch($benchlocation){
					case 1:
					switch($bench_code){
						case 3:
							$showjudge=false;
						break;		
						case 7:
							$showjudge=true;
							$first_rec_j=true;
							$show_bench_j=true;
						break;
						default:
						$showjudge=true;
						$first_rec_j=true;
						$show_bench_j=true;
					}	
					break;
					case 2:
					switch($bench_code){		
						case 3:
							$showjudge=false;
						break;		
						break;
						case 7:
							$showjudge=true;
							$first_rec_j=true;
							$show_bench_j=true;
						break;
						default:
						$showjudge=false;
						$first_rec_j=false;
						$show_bench_j=false;
					}
					break;
					default:
				}


				$judge_code =$row['judge_code'];
				 $judge_desg_code =$row['judge_desg_code'];	
				 $desgsthname='';
				 $des_ql = $db->prepare("select desg_name from $schemas.master_desg where desg_code=? order by desg_code desc");
				  $des_ql->execute(array($judge_desg_code));
				 $desgsthname = $des_ql->fetchColumn();
				
				if($showjudge==true)
				{
					if($show_bench_j==true)
					{?>
						<option value="<?php echo htmlspecialchars($row['judge_code']);?>"> <?php echo htmlspecialchars($row['judge_name']).' '.$desgsthname; ?></option>
					<?php
					}
					else
					{
					if($first_rec_j==true&&$ii==1)
					{
						if($judge_desg_code==1)
						{
						?>
						<option value="<?php echo htmlspecialchars($row['judge_code']);?>"> <?php echo htmlspecialchars($row['judge_name']).' '.$desgsthname; ?></option>
						<?php 
						} 
					}
					else
					{
						if($judge_desg_code!=1)
						{?>

						<option value="<?php echo htmlspecialchars($row['judge_code']);?>"> <?php echo htmlspecialchars($row['judge_name']).' '.$desgsthname; ?></option>
						<?php
						}
					}
					}	
							
				}
				else
				{
					if($judge_desg_code!=1)
					{
					?>
					<option value="<?php echo htmlspecialchars($row['judge_code']);?>"> <?php echo htmlspecialchars($row['judge_name']).' '.$desgsthname; ?></option>	
					<?php 
					}
				}
				}
				?>
				</select>
				</div>
				</div>
				<?php
				}
				?>

					
				<?php
				if($no_of_judge >1)
				{
				?>
				<div class="form-group row">
					<label for="bench" class="col-sm-2 col-form-label"><font color="red">*</font></span></font><b><i>Presiding Member</b></i></label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="presiding" readonly>

					</div>
				</div>
				<?php
				}
				?>
		<hr>
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css"/>
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
<script src="../src/calendar.js"></script>
<div class="form-group row">
	<div class="col-sm-6 col-md-6">
		<label for="bench" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Listing Date</label>
		<div class="col-sm-8">
			<input type="text" autocomplete="off" id="from_list_date" name="from_list_date"  class="datepicker form-control" size="10" value="<?php print htmlspecialchars($from_list_date); ?>"/>
		</div>
		<div id="holiday-tooltip" class="holiday-tooltip"></div>

	</div>
	<div class="col-sm-6 col-md-6">
		<label for="bench" class="col-sm-4 col-form-label"></span></font>Time(Display In Cause List):</label>
		<div class="col-sm-8">
			<div class="bootstrap-timepicker" style="z-index:1 !important;">
				<div class="form-group">


				<div class="input-group" >

				<input type="text" class="form-control timepicker" name="details">

				<div class="input-group-addon">
				  <i class="fa fa-clock-o"></i>
				</div>
				</div>
				<!-- /.input group -->
				</div>
			</div>
		</div>
	
	</div>

<div class="form-group row">
	<div class="col-sm-6 col-md-6">
		<label for="bench" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Video Conference Link</label>
		<div class="col-sm-8">
			<input  class="form-control" type="text" required autocomplete="off"   name="vdo_cnfr_lnk" value="<?php echo htmlspecialchars(htmlentities($vdo_cnfr_lnk)); ?>">
		</div>
	
	</div>
	<div class="col-sm-6 col-md-6">
		<label for="bench" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Meeting Password</label>
		<div class="col-sm-8">
			<input  class="form-control" type="text" required  name="meet_pwd" value="<?php echo htmlspecialchars(htmlentities($meet_pwd)); ?>">
		</div>
	
	</div>
</div>


	<div class="form-group row">
		<div class="col-sm-12 col-md-12">
			<label for="bench" class="col-sm-2 col-form-label">Custom Text</label>
			<div class="col-sm-10">
				<textarea name ="custom_text" rows="3" class="form-control" cols ="50"><?php echo htmlspecialchars(htmlentities($custom_text));?></textarea>
			</div>
		</div>
	</div>
	<!--<div class="col-sm-6 col-md-6">
		<label for="bench" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>To</label>
		<div class="col-sm-8">
			<input type="text" autocomplete="off" id="to_list_date" name="to_list_date"  class="datepicker form-control" size="10" value="<?php print htmlspecialchars($from_list_date); ?>"/>
		</div>
	</div>-->
</div>

<div class="form-group row">
	<div class="col-sm-6 col-md-6">
		<label for="bench" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Court No.</label>
		<div class="col-sm-8">
<?php
	if($_SESSION['menuaccess_codeall'] == 11)
		$query = "select * from $schemas.court order by court_no";
	else
		$query = "select * from $schemas.court where court_no = $user_court order by court_no"; 

	$courts = $db->prepare($query);
	$courts->execute();
	$courts = $courts->fetchAll();
	
if($bench_code == 3 && $location_id == 10) { ?>
			<select class="form-control" name='court_no'>
				<option value='5' <?php echo ($court_no == 5)?'selected':''; ?>>Registrar</option>
			</select>
<?php } else { ?>
			<select class="form-control" name='court_no'>
			<?php foreach($courts as $k=>$court){
				if($court['court_no'] == '5'){
					continue;
				}
				echo "<option value='$court[court_no]'>$court[display_court_text]</option>";
			}
			echo "</select>";
 }  ?>
		</div>
	</div>
	<div class="col-sm-6 col-md-6">
		<label for="bench" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Limit of Case.</label>
		<div class="col-sm-8">
			<input onkeypress="return isNumberKey(event)" class="form-control" type="number" autocomplete="off"  maxlength="3" size="2" name="limit_case" value="<?php echo htmlspecialchars(htmlentities($limit_case)); ?>">
		</div>
	
	</div>
</div>

<div class="form-group row">
	<div class="col-sm-12 col-md-12">
		<label for="bench" class="col-sm-2 col-form-label">Bench Header Remarks</label>
		<div class="col-sm-10">
			<textarea name ="bench_remarks" rows="3" class="form-control" cols ="50"><?php echo htmlspecialchars(htmlentities($bench_remarks));?></textarea>
		</div>
	</div>
</div>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hovered" cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">  

<tr>
<td  align="center" nowrap="nowrap"><font face="Verdana" size="2" ><b><i>Purpose </b></i></font></td>
</td><td  align="center" nowrap="nowrap"><font face="Verdana" size="2" ><b><i>Priority </b></i></font></td>
<?php
if($m!=0)
	{

$sqlf=$db->prepare("select * from $schemas.master_purpose where display=? order by purpose_priority ASC");
$display=TRUE;
            $sqlf->bindParam(1, $display, PDO::PARAM_STR);
            $sqlf->execute();
			$pp = $sqlf->fetchAll();
			foreach($pp as $row)
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

 </table>
</div>
<div class="form-group row">
<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<center><input type="submit" name="submit1" value="Submit" class="btn btn-sm btn-success" onClick="return submitForm1();"></center>     
</div>
  
     </form>    
  
</div>
</div>
</div>
  <?php 
  
  include '../bfooter.php';
  ?>

<script>
$('#t1 select').on('change', function() {
$('option').prop('disabled', false);
$('#t1 select').each(function() {
var val = this.value;
$('#t1 select').not(this).find('option').filter(function() {
return this.value === val;
}).prop('disabled', true);
});
}).change();
 $('.timepicker').timepicker({      showInputs: false , minuteStep: 5   })

var holidays =  {};
$.ajax({
		type: "POST",
		url: '../get_holidays.php',
		dataType: 'json',
		success: function (response) {
			var holidays = response;
			initializeDatePicker(holidays);
		},
		error: function (textStatus, errorThrown) {
		  console.log(textStatus);
		   alert(errorThrown);
		}

	});



    function initializeDatePicker(holidays) {
    $("#from_list_date").datepicker({
        beforeShowDay: function(date) {
            var dateString = $.datepicker.formatDate("yy-mm-dd", date); 
            if (holidays[dateString]) {
                
                var holiday = holidays[dateString];
                return [true, "holiday", holiday.description];
            }
            return [true, ""];
        }
    });

     
            $("#from_list_date").on("mouseenter", ".holiday", function() {
                var date = $(this).data("date");
                var holidayDesc = holidays[date] ? holidays[date].description : '';
                if (holidayDesc) {
                    var offset = $(this).offset();
                    $("#holiday-tooltip")
                        .text(holidayDesc)
                        .css({ top: offset.top + 30, left: offset.left })
                        .show();
                }
            }).on("mouseleave", ".holiday", function() {
                $("#holiday-tooltip").hide();
            });
}

   

</script>



  <?php } ?>
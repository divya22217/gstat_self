<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
$no =$_REQUEST['no'];

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();
$filing_no= $_REQUEST[filing_no];
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
	$filing_no_next=$_REQUEST['filing_no_next'];
	$hash1=htmlspecialchars(base64_decode($filing_no_next));
	$hash1 = explode("-", $hash1);
	$filing_no_fou=$hash1[0];
	$token_fou= $hash1[1];
	$location_access=$_SESSION['location'];
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";

?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <section class="content">

  <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">

     <div class="form-group">
				 <div class="col-md-12">

<script language="javascript">
function submitForm3()
{
 	with(document.frm)
	{
	 action = "case_proceeding_with_connected1.php";
	 submit();
	}
}

function submitForm1(obj)
{
	//code for hiding date picker
	var selectBox = obj;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    var textarea = document.getElementById("list_date_element");
	//var date_pick = document.getElementById("datepickerGreaterR");
    if(selected === '19'){
		//date_pick.value= "09/09/1999";
        textarea.style.visibility = "hidden";
		$('#datepickerGreaterR').attr('value','09/09/1999');
		//$("#datepickerGreaterR").val("09/09/1999 ");
		//var r=$("#datepickerGreaterR").val();
		return false;
    }else{
		$("#datepickerGreaterR").val("");
	}

with(document.frm)
{
/*if(prt_type=='1')
{*/
if(case_type.value == "select")
{
alert("Select Case Type");
case_type.focus();
return false;
}
if(case_no.value == "")
{
alert("Enter Case Number");
case_no.value='';
case_no.focus();
return false;
}

if(case_year.value == "")
{
alert("Enter 4 digit Case Registration Year");
case_year.focus();
return false;
}


action = "<?php echo $_SERVER['REQUEST_URI'];?>";
submit();
}

}
function submitForm()
{
with(document.frm)
{

action = "<?php echo $_SERVER['REQUEST_URI'];?>";
submit();
}
}
function run()
{
with(document.frm)
{


action = "case_proceeding_with_connected.php";
submit();
}
}
function validate()
{
	alert('sad');
return false;
with(document.frm)
{

if(case_type.value == "select")
{
alert("Select Case Type");
case_type.focus();
return false;
}
if(case_no.value == "")
{
alert("Enter  Case Number");
case_no.focus();
return false;
}
if(isNaN(case_no.value) == true)
{
alert("Please Enter Numeric Case No.");
case_no.select();
return false;
}
if(case_year.value == "")
{
alert("Enter 4 digit Case Registration Year");
case_year.focus();
return false;
}
if(case_year.value < 4)
{
alert("Enter 4 digit Case Registration Year");
case_year.focus();
return false;
}
if(isNaN(case_year.value) == true)
{
alert("Please Enter Numeric Case Year");
case_year.select();
return false;
}

//alert(pen_dis.value);
var status_value=0;
for (i=0;i< pen_dis.length;i++)
{
if(pen_dis[i].checked ==true)
{
status_value = pen_dis[i].value;
}

}

if(status_value=='P')
{

if(action_type.options[action_type.selectedIndex].value == "")
{
alert("Please select Action Type");
action_type.focus();
return false;
}

if(purpose_code.options[purpose_code.selectedIndex].value == "")
{
alert("Please select purpose of next hearing   ");
purpose_code.focus();
return false;
}



if(criteria.options[criteria.selectedIndex].value == "")
{
alert("Please Select Criteria");
criteria.focus();
return false;
}
if(criteria.value=='FD')
{
if(next_list_date.value =="")
{
alert("Please enter valid list it on Date ");
next_list_date.focus();
return false;
}

}

}

if(status_value=='D')
{
//code for disposal validation

if(disposal_nature.options[disposal_nature.selectedIndex].value == "")
{
alert("Please Select Disposal Nature");
disposal_nature.focus();
return false;
}
if(disposal_date.value =="")
{
alert("Please enter valid Disposal Date ");
disposal_date.value='';
disposal_date.focus();
return false;
}

}//close status_value=2



action="case_proceeding_with_connected_action.php";
submit();
document.frm.submit1.disabled = true;
document.frm.submit1.value = 'Please Wait...';
return true;
}

}
function addNumbers(val)
{
var c=document.getElementById("next_list_date").value.length;
if(c==2 || c==5  )
{
var newval = val+ "/";
document.getElementById("next_list_date").value=newval;
}

}
function addNumbers1(val)
{
var c=document.getElementById("disposal_date").value.length;
if(c==2 || c==5  )
{
var newval = val+ "/";
document.getElementById("disposal_date").value=newval;
}

}
function popsurety_pet_adv_name(cfy)

{

var url = "fram_advocate.php?filing_no="+cfy;
var width = 800;
var height = 800;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',resizable=1';

window.open(url,"print",params);
}
function popsurety_amicus_curiae(cfy)

{

var url = "amicus_curiae.php?filing_no="+cfy;
var width = 800;
var height = 800;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',resizable=1';

window.open(url,"print",params);
}
function popsurety_evidence_ref(cfy)

{

var url = "evidence_ref.php?filing_no="+cfy;
var width = 800;
var height = 800;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',resizable=1';

window.open(url,"print",params);
}




function SetBg(txt)
{
txt.style.backgroundColor='#ffff99';
}
function UnSetBg(txt)
{
txt.style.backgroundColor='white';
}


function popdashboard()
{

var url = "dashboard.php";
var width = 800;
var height = 1300;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',scrollbars=1';

window.open(url,"print",params);
}


function popdashboard1()
{

var url = "dashboard1.php";
var width = 800;
var height = 1300;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',scrollbars=1';

window.open(url,"print",params);
}

//  END
</script>

<style>
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding:0px;}

</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">



<table  border="2" class="table"  align="center" >
<tr>
<td valign="top" align="right" colspan="16"><center>
<b><font face="Verdana" size="3"><u>DAILY CASE  </u></font> </b>
</td>
</tr>

<tr><td valign="top" align="center" colspan="16">
<font face="Verdana" size="2">Fields marked with a <font color='red'>*</font> are compulsory.</font> </td>
</tr>



<form name="frm" method="POST" action="rr_action.php">
<input type="hidden" name="listing_date" value="<?php echo htmlspecialchars($listing_date); ?>">




<?php

$msghash=$_REQUEST['msghash'];
if($msghash !='')
{
$msghashz=(base64_decode($msghash));

$msghashz = explode("-", $msghashz);

$msg1 = $msghashz[0];
$case_type = $msghashz[1];
$case_year = $msghashz[2];

if($msghashz != '')
{
?>
<tr>
<td height="30" align="center" cellpadding="0" colspan="16">
<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
<span class="error"> <b> <?php echo htmlspecialchars($msg1); ?></span></font>
</td>
</tr>
<?php
}
}
?>

<input type="hidden" name="judge" value="<?php echo htmlspecialchars($judgecode);?>" />
<input type="hidden" name="c_date" value="<?php echo htmlspecialchars($c_date);?>" />
<input type="hidden" name="c_case" value="<?php echo htmlspecialchars($c_case);?>" />
<input type="hidden" name="benchnature" value="<?php echo htmlspecialchars($benchnature);?>" />
<input type="hidden" name="courtno" value="<?php echo htmlspecialchars($courtno);?>" />
<input type="hidden" name="no" value="<?php echo $_REQUEST[no];?>" />
<input type="hidden" name="list_date_link" value="<?php echo htmlspecialchars($list_date_link); ?>">

<tr>
<td align="left" valign="top" colspan="1" width="40%" >
Listing Date
</td>
<td align="left" valign="top" colspan="1" width="40%" >
Next Listing Date
</td>
</tr>

<tr>

<?php


if($filing_no!= '')
{
$st = $db->prepare("select * from $schemas.case_allocation_temp where filing_no=? order by listing_date asc	");

$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
$data = $st->fetchAll();

?>

<input type="hidden" name="filing_no" value="<?php print htmlspecialchars($filing_no_link1);?>" />
<?php

foreach ($data as $key=>$rw2)
{

	$next_key = $key+1;

 $lis_date=htmlspecialchars($rw2['listing_date']);
 $next_listing_date = $data[$next_key]['listing_date'];
 $next_date=htmlspecialchars($rw2['next_list_date']);
?>

<td>
<a href="javascript:void(0);" onClick ="return update_next_list_date('<?php echo $filing_no; ?>','<?php echo $lis_date; ?>','<?php echo $next_listing_date; ?>')"><?php echo  $lis_date?></a>
</td>
<td>
<?php echo  $next_date?>
</td>
</tr>
<?php 
}
}


?>





<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />







</td>
</tr>

<?php }?>

 </td></tr> </table>


      </form>
  </body></div></div></div>
        <!-- /.box-footer-->
      </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <?php
  //include '../infooter.php';
  ?>
  
 <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body" id="modalbody">

		<div class="form-group row">
			<label  class="label-control col-sm-2">Filing NO</label>
			<div class="col-sm-10">
				<input type="text" value="" id="filing_no" name="filing_no">
			</div>
		</div>
		<div class="form-group row">
			<label  class="label-control col-sm-2">List Date</label>
			<div class="col-sm-10">
				<input type="text" value="" id="list_date" name="list_date">
			</div>
		</div>
		<div class="form-group row">
			<label  class="label-control col-sm-2">Next List Date</label>
			<div class="col-sm-10">
				<input type="text" value="" id="next_list_date" name="next_list_date">
			</div>
		</div>
		<div class="form-group row">
			<center><input type="submit" value="Update" name="update_next_date" onClick="return update_next_listing_data_new();" class="btn btn-primary"></center>
		</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>

function update_next_list_date(filing_no,list_date,next_list_date)
{
	$("#filing_no").val(filing_no);
	$("#list_date").val(list_date);
	$("#next_list_date").val(next_list_date);
	$("#myModal").modal('show');
}

function update_next_listing_data_new()
{
	var filing_no = $("#filing_no").val();
	var list_date = $("#list_date").val();
	var next_list_date = $("#next_list_date").val();
	$.ajax({
            type: 'POST',
            url: 'update_next_listing_date.php', 
            data: {filing_no:filing_no,list_date:list_date,next_list_date:next_list_date}
        })
        .done(function(data){
            
            alert('next list date updated for  '+filing_no+' is '+next_list_date);
			$("#myModal").modal('hide');
			location.reload(true);
			
            
        })
        .fail(function() {
        
            // just in case posting your form failed
            alert( "Posting failed." );
            
        });
} 
</script>

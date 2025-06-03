<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">

<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include("../db_inc1.php");
include '../classes/masters.class.php';

 $item = htmlspecialchars($_REQUEST['itemno']);
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{

	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}
else {

	function get_main_case_filing_no($schemas,$db,$filing_no){
	
	$main_case_filing_no = $db->prepare("select ia_ma_filing_no from $schemas.case_detail where filing_no=?");
	$main_case_filing_no->bindParam(1, $filing_no, PDO::PARAM_INT);
	$main_case_filing_no->execute();
	$main_case_filing_no = $main_case_filing_no->fetchColumn();
	return $main_case_filing_no;
}
	
	$date = htmlspecialchars(date("d/m/Y"));
	$date1 = htmlspecialchars(date("F j, Y g:i a"));
	$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] :'';
	$year=htmlspecialchars(date("Y"));
	$msg_ip .= "User IP : ".$_SERVER["REMOTE_ADDR"]."\r\n"; //Sender's IP
	
	
$frm = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $frm;//csrf

$schemas=htmlspecialchars($_SESSION['schema_name']);

?>
<html>
<head>
<script>
function addMore() {
	$("<DIV>").load("input.php", function() {
			$("#product").append($(this).html());
	});	
}
function deleteRow() {
	$('DIV.product-item').each(function(index, item){
		jQuery(':checkbox', this).each(function () {
            if ($(this).is(':checked')) {
				$(item).remove();
            }
        });
	});
}
</script>
<title>daily order</title>
<style type="text/css">
div.hidden 
{
display: none;
}
@media print
{
h1 {page-break-after:always;}

.no-print, .no-print *
    {
        display: none !important;
    }
}


</style>
<style type="text/css" media="print">
.dontprint
{ display: none; }
</style>
<SCRIPT src="http://code.jquery.com/jquery-2.1.1.js"></SCRIPT>
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
</head>
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

function submitForm1()
{
	with(document.frm)
	{
		if(prt_type.options[prt_type.selectedIndex].value==1){
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
		}



		if(prt_type.options[prt_type.selectedIndex].value==2){
		if(dairyNo.value=='')
		{
		alert("Please Enter Dairy No... ");
		dairyNo.focus();
		return false;
		}
		if(dairyyear.value=='')
		{
		alert("Please Enter Diary Year... ");
		dairyyear.focus();
		return false;
		}
	}

		action="order_creation_bulk_ind.php";
		submit();
	}

}

function submitFormo()
{
	with(document.frm)
	{
		
		
		if(next_list_date.value == "")
		{
			alert("Enter ORDER DATE....");
			next_list_date.value='';
			next_list_date.focus();
			return false;
		}
		if(courtno.value == "select")
		{
			alert("Select Court No..");
			courtno.focus();
			return false;
		}

		
		action="order_creation_bulk_ind.php";
		submit();
	}

}
function submitFormsub()
{
	with(document.frm)
	{
		
		
		/*if(author_name.value == '')
		{
			alert("Please Select the Member Name");
			author_name.value='';
			author_name.focus();
			return false;
		}
			*/	
		action="order_creation_bulk_ind_action.php";
		submit();
    	//document.frm.submit1.disabled = true;  
     	//document.frm.submit1.value = 'Please Wait...';  
     	return true;
	}

}

function submitFormsub2()
{
	with(document.frm)
	{
		
		
		
	
		
     	
	}

}


</script>

<script language="javascript">




function SetBg(txt)
{
      txt.style.backgroundColor='#ffff99';
}
function UnSetBg(txt)
{
       txt.style.backgroundColor='white';
}

var $textArea = $("#textarea-container");

// Re-size to fit initial content.
resizeTextArea($textArea);

// Remove this binding if you don't want to re-size on typing.
$textArea.off("keyup.textarea").on("keyup.textarea", function() {
    resizeTextArea($(this));
});

function resizeTextArea($element) {
    //$element.height($element[0].scrollHeight);
}
function submitForm()
{
	with(document.frm)
	{
		action="order_creation_bulk_ind.php";
		submit();
	}
}



//  END
</script>
<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>             <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
<body>

<div class="container-fluid">

<?php

$msghash=$_REQUEST['msghash'];
if($msghash !='')
{
$msghashz=(base64_decode($msghash));

$msghashz = explode("-", $msghashz);
$type = $msghashz[0];
$msg1 = $msghashz[1];

if(!empty($msghashz))
{
?>
<div class="alert alert-<?php echo $type;?> alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <?php echo $msg1; ?>
</div>
<?php
if($type == 'success'){
	echo "<script>setTimeout(function () { window.close();}, 5000);</script>";
	
	die;
}
}
}
?>

<form name="frm" method="post" action="order_creation_bulk_ind_action.php">



<table width="100%"  border="0" cellspacing="1" cellpadding="1" align="center">
<tr>
	<td valign="top" align="center" colspan="15">
					<b><font face="Verdana" size="3"><u>ORDER CREATION</u></font> </b>
	</td>
	</tr>


  <tr>
    <td valign="top" colspan="16" align="center"><font face="Verdana" size="2">Fields marked with a <span class="error">*</span> are compulsory</font>
    </td></tr>
<?php

if($_REQUEST[no]!='')
	{
		$filing_no_link= $_REQUEST[no];
	
		list($filing_no_link1,$court_no_link,$list_date_link,$list_before_link,$list_flag,$purpose_old,$bench_code1,$bench_nature1,$item_no)=explode('@',$filing_no_link);
		
		$sqlq="select case_year,case_no,case_type,location_code from $schemas.case_detail where filing_no=?";
		$sanR = $db->prepare($sqlq);
		$sanR->execute(array($filing_no_link1));
			$sanR_data = $sanR->fetch();
		
			//print_r($sanR_data);
			
}

if($item >0)
	
{ 



$upload_flag =0;
 $sql="select *  from $schemas.order_daily where flag='N' and item_no=?";
 $hscquery = $db -> prepare($sql);
 $hscquery->bindParam(1,$item, PDO::PARAM_STR);
 $hscquery -> execute();
  while ($row = $hscquery->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
  {
   $upload_flag=1;
 $modify_filing_no =$row['filing_no'];
   $applicant1=$row['applicant1'];
    $applicant2=$row['applicant2'];
    $applicant3=$row['applicant3'];
 $applicant4=$row['applicant4'];
 $applicant5=$row['applicant5'];
 $applicant6=$row['applicant6'];
 $applicant7=$row['applicant7'];
 $applicant8=$row['applicant8'];
 $applicant9=$row['applicant9'];
 $applicant10=$row['applicant10'];
$respondent1=$row['respondent1'];
$respondent2=$row['respondent2'];
$respondent3=$row['respondent3'];
$respondent4=$row['respondent4'];
$respondent5=$row['respondent5'];
$respondent6=$row['respondent6'];
$respondent7=$row['respondent7'];
$respondent8=$row['respondent8'];
$respondent9=$row['respondent9'];
$respondent10=$row['respondent10'];
$order_of_tribunal=$row['order_tribunal'];
  }
}
/*if($upload_flag==0)
{
echo " This order is already uploaded in website";
}*/
?>


  
    
<?php
$case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] : $sanR_data['case_type'];
$case_no = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] : $sanR_data['case_no'];
$case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] : $sanR_data['case_year'];
	$bench_type = isset($_REQUEST['bench_type']) ? $_REQUEST['bench_type'] : $sanR_data['location_code'];
	$bench_location = $sanR_data['bench_location'];
if($item =='')
{
?>



<tr>
<td  colspan="15" align="center">
<br>
<font face="Verdana, Arial, Helvetica, sans-serif" size="3"> 
<!--font face="Verdana" size="2"><span class="error">*</span>Search Case:</font>
<?php $prt_type = isset($_REQUEST['prt_type']) ? $_REQUEST['prt_type'] :'1'; ?>
<select id="prt_type" name="prt_type" onchange="javascript:submitForm();" class="frm-field required" >
<option value="1" <?php if($prt_type == 1) { print " selected"; } ?> >Case No. Wise</option>
<option value="2" <?php if($prt_type == 2) { print " selected"; } ?> >Diary No. Wise</option>
</select>
</font-->

<?php if($prt_type =='1')
{
	?>
<font face="Verdana" size="3"><b>
Case No. :</b>

	<?php
	 $st = $db->prepare("select * from case_type where id = ?");
	 $st->bindParam(1, $case_type, PDO::PARAM_STR);
	 $st->execute();
	while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
	 $case_type_name=htmlspecialchars($row['short_name']);
	
	}?>
	<b><font face="Verdana" size="3">
	<?php
echo $case_number=$case_type_name."/".$case_no.$case_year;
}
	?>
	</b>
	</font>

</td>
</tr>
</table>
<?php
}
?>
<table width="100%"  border="0" cellspacing="1" cellpadding="1" align="center">
<?php 

if($prt_type == '1')
{

	$stcn = $db->prepare("select * from $schemas.case_detail where location_code=? and case_type=? and case_no=? and case_year=?");
		$parm = array($bench_type,$case_type,$case_no,$case_year);
}

if($prt_type == '2')
{
	$c_no = $hsc.str_pad($dairyNo, 6,'0',STR_PAD_LEFT).$dairyyear;
	$stcn = $db->prepare("select * from $schemas.case_detail where filing_no=?");	
	
}

$stcn->execute($parm);
while ($rw2 = $stcn->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

	 $filing_no98 = htmlspecialchars($rw2['filing_no']);
	$pet_name=htmlspecialchars($rw2['pet_name']);
	$res_name=htmlspecialchars($rw2['res_name']);
	$ci_cri=htmlspecialchars($rw2['ci_cri']);
	$status=htmlspecialchars($rw2['status']);
	
}

if($filing_no98 !='')
{
	?>
	
	<tr><td align="center"><br><font color='red'>
	<?php 
	echo $pet_name;
	?>
	</td>
	</tr>
	<tr><td align="center"><br><font color='blue'>
	Vs.
	</td>
	</tr>
	<tr><td align="center"><br><font color='red'>
	<?php 
	echo $res_name;
	?>
	</td>
	</tr>
	</font>
	</td>	<td align="left" valign="top" colspan="1" width="40%">
	<?php 
/* 	echo "<br>";
	echo "<h7><font color='red'><center>";
	if($status =='P'){ htmlspecialchars("CASE IS PENDING")."</center></font></h7>";}
	
	if($status=='D')
	{
	echo "</center><font color='red'><h6>";
	 htmlspecialchars("CASE IS DISPOSED");
	echo "</center></font></h7>";
	echo "<br>";
	
	
		$st = $db->prepare("select * from $schemas.case_disposal where filing_no=? ");
		$st->bindParam(1, $filing_no, PDO::PARAM_INT);
		$st->execute();
		while ($rw4 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
			$filing_no=htmlspecialchars($rw4['filing_no']);
			$disposal_date=htmlspecialchars($rw4['disposal_date']);
			$disposal_nature=htmlspecialchars($rw4['disposal_nature']);
		}
		echo "</center><font color='blue'><h7>";
		echo 'Disposal Date'.htmlspecialchars($disposal_date);
		echo 'Disposal Nature('.htmlspecialchars($disposal_nature).')';
		echo "</center></font></h7>";
		echo "<br>";	
	
	} */
	
	?>
	<?php 
	list($day,$month,$year)=explode('/',$list_date_link);
      	$list_date_order=$year.'-'.$month.'-'.$day;
	$st = $db->prepare("select * from $schemas.order_daily where flag='N' and filing_no=? and order_date=? ");
		$st->bindParam(1, $filing_no98, PDO::PARAM_INT);
		$st->bindParam(2, $list_date_order, PDO::PARAM_INT);
		$st->execute();
		while ($rw4 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
			$filing_no_order=htmlspecialchars($rw4['filing_no']);
	
		}
	?>
	</b>
	</font>
	</td>
	</tr>

<?php
if($item =='')
{
?>
<?php  $author_name = isset($_REQUEST['author_name']) ? $_REQUEST['author_name'] :''; ?>
<?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :$list_date_link; ?>
<tr><td colspan="14"><!--font color="red">*</font>
<font size="1">ORDER DATE:</font-->


<input type="hidden" id="next_list_date" autocomplete="off" maxlength="10" name="next_list_date" data-lang="en" data-years="2000-2035" data-format="DD/MM/YYYY" 
readonly="readonly" size="8" value="<?php print htmlspecialchars($next_list_date); ?>" />



<?php  $benchnature = isset($_REQUEST['benchnature']) ? $_REQUEST['benchnature'] :$bench_nature1; ?>
<input type="hidden" id="benchnature"  name="benchnature" value="<?php print htmlspecialchars($benchnature); ?>" />
<!--font color="red">*</font><font size="1">BENCH NATURE:</font>

<select id="in_benchnature" size="1" name="benchnature" style="width:90px;" onchange="javascript:submitForm();">
             <option value="">Select</option>
<?php
$display='TRUE';
$st= $db->prepare("select * from $schemas.bench_nature  where  display=? ");
$st->bindParam(1, $display, PDO::PARAM_STR);

$st->execute();

while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))

    
	  {
		$btype = htmlspecialchars($row['bench_code']);
	   if($benchnature == $btype)
                {
				print "<option value=".htmlspecialchars($row['bench_code'])." selected>".htmlspecialchars($row['bench_name'])."</option>";
                }
        else
                {
                print "<option value=".htmlspecialchars($row['bench_code']).">".htmlspecialchars($row['bench_name'])."</option>";
				}
	  } 
?>

</select-->


<?php  $courtno = isset($_REQUEST['courtno']) ? $_REQUEST['courtno'] :$court_no_link; ?>
<input type="hidden" id="courtno" name="courtno" value="<?php print htmlspecialchars($courtno); ?>" />
<!--font color="red">*</font><font size="1">Court No.:</font>
<select id="in_courtno" size="1" name="courtno" style="width:40px;">
             <option value="">Select</option>
      	<?php
      	list($day,$month,$year)=explode('/',$next_list_date);
      	$list_cdate=$year.'-'.$month.'-'.$day;
      	
      	$st= $db->prepare("select distinct(court_no) as court_no,bench_no from $schemas.bench where from_list_date=?
      		and bench_nature= ?	order by court_no asc");
      	$st->bindParam(1, $list_cdate, PDO::PARAM_STR);
      	$st->bindParam(2, $benchnature, PDO::PARAM_STR);
      	$st->execute();
      	
      	
	while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$court_code=htmlspecialchars($row['court_no']);
		$court_no=htmlspecialchars($row['court_no']);
		$bench_no=htmlspecialchars($row['bench_no']);
		//if($court_name=='59') { $court_name="Registrar Court"; }
		
		if($courtno == $court_no)
		{
			print "<option value=".htmlspecialchars($row['court_no'])." selected>".htmlspecialchars($court_no)."</option>";
		}
		else
		{
			print "<option value=".htmlspecialchars($row['court_no']).">".htmlspecialchars($court_no)."</option>";
		}
	} 
	?>       
             
             
             </select>
<input type="button"  size=5 name="go" id="gobtn" value="GO" onClick="javascript:submitFormo();"> </td>      
    -->    
     </td>
     </tr>

<?php
}
?>
<?php

if( ($next_list_date !='' and $courtno !='') || ($item >0))
{
?>     
 <tr><td colspan="12">   </td>
</tr>
</table> 
 <table width="100%" border='0' cellpadding="1" cellspacing="3" align="center"> 
<?php 

$sql_judge="select jm.judge_name,jm.judge_desg_code,jm.gen,jm.hon_text,jm.judge_code from $schemas.master_judge as jm,$schemas.bench as b where  b.from_list_date=? and b.bench_no=? and
 b.court_no=? and jm.judge_code=b.presiding";
$sth101=$db->prepare($sql_judge);
$sth101->bindParam(1, $list_cdate, PDO::PARAM_STR);
$sth101->bindParam(2, $bench_code1, PDO::PARAM_STR);
$sth101->bindParam(3, $courtno, PDO::PARAM_STR);
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
<?php echo htmlspecialchars($hon_text." "); ?><?php echo htmlspecialchars($gen." ".$judge_name.", ".$judge_desg); ?>
</font>
</td>
</b>
</tr>
<?php 
//echo "select no_of_judges from $schemas.bench_nature where bench_code='$benchnature'";
$sql_judge_count="select no_of_judges from $schemas.bench_nature where bench_code=?";
$sth_j=$db->prepare($sql_judge_count);
$sth_j->bindParam(1, $benchnature, PDO::PARAM_STR);
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
	$sth_j1->bindParam(1, $list_cdate, PDO::PARAM_STR);
	$sth_j1->bindParam(2, $courtno, PDO::PARAM_STR);
	$sth_j1->bindParam(3, $courtno, PDO::PARAM_STR);
	$sth_j1->bindParam(4, $list_cdate, PDO::PARAM_STR);
	$sth_j1->bindParam(5, $benchnature, PDO::PARAM_STR);
	$sth_j1->bindParam(6, $presiding_code, PDO::PARAM_STR);
	$sth_j1->bindParam(7, $list_cdate, PDO::PARAM_STR);
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
               <?php echo htmlspecialchars($hon_text." "); ?>
                 <?php echo htmlspecialchars($gen." ".$judgename1.", ".$judge_desg1);?>
		</font>
		</td></tr>
		<?php
}	
}

//register banch code Detail here
?>
</table>   
<br/>  
 <table width="100%" border='0' cellpadding="1" cellspacing="3" align="center"> 
<tr>
	<td><b>Item No : </b><span style="padding-left:10px;"><input type="number" class="form-control" style="width:50px;" name="item_no_in_causelist" value="<?php echo $item_no; ?>"/></span></td>
</tr>
    
     
<tr>
		<td align="left" colspan="1" width="100%">
		</br>
		<fieldset>
  		<legend><b>FOR APPLICANTS ADVOCATE</b></legend>
		
	 <!--<input type="text"  name="name[]" maxlength="150" autocomplete="off" size="30"  onFocus="SetBg(this)" onBlur="UnSetBg(this)"/><br>-->
	<?php 
	$case_type_array = array(2,3,5,6);
		if (in_array($case_type, $case_type_array)){
		$main_case_filing_no = get_main_case_filing_no($schemas,$db,$filing_no_link1);
	}else{
		$main_case_filing_no = $filing_no_link1;
	}
		$party_flag = 'P';
		$display = 'TRUE';
		$query="select * from e_more_representative where filing_no=? and party_flag= ? and display= ?";
		$st12=$db->prepare($query);
		$st12->bindParam(1, $main_case_filing_no, PDO::PARAM_STR);
		$st12->bindParam(2, $party_flag, PDO::PARAM_STR);
		$st12->bindParam(3, $display, PDO::PARAM_STR);
		$st12->execute();
		$advocate_pet = $st12->fetchAll(); 
		?>
		<select class="form-control" id="master_judges" name="master_judges[]" multiple>
	<?php	if(count($advocate_pet) > 0) {
	?>
	<?php	foreach($advocate_pet as $key=>$value)
		{
			$rep_code = $value['rep_code'];
			$stqq12 = $db->prepare("select rep_name from e_master_advocate where id=?");
			$stqq12->bindParam(1, $rep_code, PDO::PARAM_INT);
			$stqq12->execute();
			$pet_advname22 = $stqq12->fetchColumn();
		?>
		
			<option value="<?php echo $pet_advname22; ?>"><?php echo $pet_advname22; ?></option>
	<?php	}  }?>
		</select>
	

                <div class="form-group">  
                      
                          <div class="table-responsive">  
<table class="table table-bordered" id="dynamic_field">  
                         
							 </table>  
	
 
	
	</fieldset>
	</td>
	</tr>
	                            
 <tr> 
  
 									
                                         
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
                                    </tr> 
	 <div class="container">  
       
 
	<tr>
	<td>
	
	</td>
	</tr>
	
	<tr>
	<td align="left"  width="90%">
		<fieldset>
  		<legend><b><br/>FOR RESPONDENTS ADVOCATE</b></legend>
  		
	<!--<input type="text"  name="rname[]" maxlength="150" autocomplete="off" size="30"  onFocus="SetBg(this)" onBlur="UnSetBg(this)"/><br>-->
	<?php
		$party_flag = "R";
		$display = "TRUE";
		$query="select * from e_more_representative where filing_no=? and party_flag= ? and display= ?";
		$st12=$db->prepare($query);
		$st12->bindParam(1, $main_case_filing_no, PDO::PARAM_STR);
		$st12->bindParam(2, $party_flag, PDO::PARAM_STR);
		$st12->bindParam(3, $display, PDO::PARAM_STR);
		$st12->execute();
		$advocate_pet = $st12->fetchAll(); 
			?>

		<select class="form-control" id="master_judges" name="r_master_judges[]" multiple>
	<?php	if(count($advocate_pet) > 0) {
	?>
	<?php	foreach($advocate_pet as $key=>$value)
		{
			$rep_code = $value['rep_code'];
			$stqq12 = $db->prepare("select rep_name from e_master_advocate where id=?");
			$stqq12->bindParam(1, $rep_code, PDO::PARAM_INT);
			$stqq12->execute();
			$pet_advname22 = $stqq12->fetchColumn();
		?>
		
			<option value="<?php echo $pet_advname22; ?>"><?php echo $pet_advname22; ?></option>
	<?php	}  }?>
		</select>
	
	 
	 <div>  
        
                <div class="form-group">  
                      
                          <div class="table-responsive">  
<table class="table table-bordered" id="dynamic_field1">  
                                    
</table>  
	<tr>
<td><button type="button" name="add1" id="add1" value="1" class="btn btn-success">Add More</button></td> 
	</tr> 
	
	
	</fieldset>
	</td>
	</tr>
	<tr>
	<td>
	
	</td>
	</tr>

<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars($modify_filing_no); ?>"/>
<input type="hidden" name="item_no" value="<?php echo htmlspecialchars($item); ?>"/>
<input type="hidden" id="bench_code1" name="order_bench_code" value="<?php print htmlspecialchars($bench_code1); ?>" />
	</font>
	
<!--<tr><td colspan="14">
<br><font color="red">*</font>
<font >Per:</font>
 

<select name="author_name">
<option value="">Select </option>
<?php 

/* $sql_judge="select jm.judge_name,jm.judge_desg_code,jm.judge_code from $schemas.master_judge as jm,$schemas.bench as b where  b.from_list_date=? and b.bench_no=? and
 b.court_no=? and jm.judge_code=b.presiding";
$sth101=$db->prepare($sql_judge);
$sth101->bindParam(1, $list_cdate, PDO::PARAM_STR);
$sth101->bindParam(2, $bench_code1, PDO::PARAM_STR);
$sth101->bindParam(3, $courtno, PDO::PARAM_STR);
$sth101->execute();
 while ($j = $sth101->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$judge_name=$j['judge_name'];
	$judge_desg_code=$j['judge_desg_code'];
	$presiding_code=$j['judge_code'];
	
	
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
echo '<option value="'.$judge_name." ".$judge_desg.'">'.$judge_name." ".$judge_desg.'</option>';


//echo "select no_of_judges from $schemas.bench_nature where bench_code='$benchnature'";
$sql_judge_count="select no_of_judges from $schemas.bench_nature where bench_code=?";
$sth_j=$db->prepare($sql_judge_count);
$sth_j->bindParam(1, $benchnature, PDO::PARAM_STR);
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
	$sth_j1->bindParam(1, $list_cdate, PDO::PARAM_STR);
	$sth_j1->bindParam(2, $courtno, PDO::PARAM_STR);
	$sth_j1->bindParam(3, $courtno, PDO::PARAM_STR);
	$sth_j1->bindParam(4, $list_cdate, PDO::PARAM_STR);
	$sth_j1->bindParam(5, $benchnature, PDO::PARAM_STR);
	$sth_j1->bindParam(6, $presiding_code, PDO::PARAM_STR);
	$sth_j1->bindParam(7, $list_cdate, PDO::PARAM_STR);
	$sth_j1->execute();

	 
	while ($b = $sth_j1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
	$j_code=$b['judge_code'];
		$judge_desg_code_n='';

		if($j_code > 0)
		{
		$st141 = $db->prepare("select judge_name,judge_desg_code from $schemas.master_judge where
				judge_code=?");
				$st141->bindParam(1, $j_code, PDO::PARAM_STR);
				$st141->execute();

				while ($jj = $st141->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
				{
				$judgename1=$jj['judge_name'];
						$judge_desg_code_n=$jj['judge_desg_code'];
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
echo '<option value="'.$judgename1." ".$judge_desg1.'">'.$judgename1." ".$judge_desg1.'</option>';
}	
} */

?>
	




</select>
<td>

</tr>	-->
 	<script language="javascript" type="text/javascript">
 tinymce.init({
gecko_spellcheck : true,
width : "1000",
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
    ],
	//font_formats: 'Bookman Old Style=bookman-old-style;Lato=lato;Arial=arial,helvetica,sans-serif;',
});
</script>	
<tr>
		
		 <td align="center" colspan="2">
		<br>
  		<legend><b><u>ORDER</u></b></legend>
 	
  </br>
 <div class="span12" id="content">
		                    <div class="row-fluid">
		                        <!-- block -->
		                        <div class="block">
		                            <div class="navbar navbar-inner block-header">
		                                <div class="muted pull-left"></div>
		                            </div>
		                            <div class="block-content collapse in">
		                      <textarea  name="order_of_tribunal" 
		                                cols="60" rows="20"><?php echo htmlspecialchars($order_of_tribunal);?></textarea>
		                            </div>
		                        </div>
		                        <!-- /block -->
		                    </div>
		                </div>		
		<!--/.fluid-container>
        <script src="vendors/bootstrap-wysihtml5/lib/js/wysihtml5-0.3.0.js"></script>
       <!--  <script src="vendors/jquery-1.9.1.min.js"></script> 
        <script src="bootstrap/js/bootstrap.min.js"></script>>
		<script src="vendors/bootstrap-wysihtml5/src/bootstrap-wysihtml5.js"></script>

		<!--  <script src="vendors/ckeditor/ckeditor.js"></script>-->
	<!-- 	<script src="vendors/ckeditor/adapters/jquery.js"></script> -->


		 </td>

<?php
if($item =='')
{
?>		 </tr>
<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars($filing_no98); ?>"/>
<?php
}

?>
</td>
</tr>
<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>"/>
<tr><td colspan="15" align="center">
<input id="submit1" type="button"  name="submit1" value="Submit" 
 onClick="return submitFormsub();" />
 </td></tr>		 


</form>
</table>
</div>


<?php } ?>
 <script>  
 $(document).ready(function(){  
      var j=10000;  
      $('#add1').click(function(){  
           j++;  
           $('#dynamic_field1').append('<tr id="row1'+j+'"><td><input type="text" name="rname[]" value="" placeholder="Enter Advocate Name" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+j+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
	
           var button_id1 = $(this).attr("id");  

           $('#row1'+button_id1+'').remove();
		   
      });  
  
      var i=1;  
	  
      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="name[]" placeholder="Enter Advocate Name" class="form-control name_list"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });
       
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
		   
           $('#row'+button_id+'').remove();  
      });  
      
 }); 
 </script>
</body>

</html>

<?php 
}
}
 ?>

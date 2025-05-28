<?php
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(E_ALL);
require_once('../SrcCauselist/Causelist.php');
$causelist = new Causelist();
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include("../db_inc1.php");

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
<meta charset="utf-8">
	<title>Daily Order Creation</title>
	<script src="../ckeditor/ckeditor.js"></script>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="Try the latest sample of CKEditor 4 and learn more about customizing your WYSIWYG editor with endless possibilities.">
<script type="text/javascript">
      //on full load of the window...
      window.onload = function()
      {
         //....replace the textarea with name 'editor1'
         CKEDITOR.replace("editor");
      };
   </script>
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
p.padding {
    padding-left: 0.0cm;
	padding-right: 0.0cm;
	padding-top: 0cm;
}
body {
        height: 900px;
        width: 780px;
        /* to centre page on screen*/
        margin-left: auto;
        margin-right: auto;
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
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>             
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
<body>

<form name="frm" method="post" action="order_creation_bulk_ind_action.php">
<table width="100%"  border="0" cellspacing="1" cellpadding="1" align="center">
<tr>
	<td valign="top" align="center" colspan="16">
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
		
		//print_r($filing_no_link);die;
	
		list($filing_no_link1,$court_no_link,$list_date_link,$list_before_link,$list_flag,$purpose_old,$bench_code1,$bench_nature1,$item_no)=explode('@',$filing_no_link);
		
		$sqlq="select case_year,case_no,case_type,location_code from $schemas.case_detail where filing_no=?";
		$sanR = $db->prepare($sqlq);
		$sanR->execute(array($filing_no_link1));
			$sanR_data = $sanR->fetch();
			//print_r($sanR_data);
			
}

if($_REQUEST[no_nor]!='')
	{
		$filing_no_link= $_REQUEST[no_nor];
		
		//print_r($filing_no_link);die;
	
		list($filing_no_link1,$court_no_link,$list_date_link,$list_before_link,$list_flag,$purpose_old,$bench_code1,$bench_nature1,$item_no)=explode('@',$filing_no_link);
		
		$sqlq="select case_year,case_no,case_type,location_code from $schemas.case_detail where filing_no=?";
		$sanR = $db->prepare($sqlq);
		$sanR->execute(array($filing_no_link1));
			$sanR_data = $sanR->fetch();
			//print_r($sanR_data);
			
}
$filing_no = $filing_no_link1;
$main_case_no = $causelist->get_case_no($filing_no, $db, $schemas);
/* start code to get main case no of IA */
 $get_ia_main_filing_no_sql = "select main_case_ia_no from $schemas.case_detail where filing_no=? and ia_flag='TRUE'";
 $get_ia_main_filing_no = $db->prepare($get_ia_main_filing_no_sql);
 $get_ia_main_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
 $get_ia_main_filing_no->execute();
 $ia_main_filingno = '';
 while ($row_gimfn = $get_ia_main_filing_no->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
	 $ia_main_filingno=$row_gimfn['main_case_ia_no'];
 }
 if($ia_main_filingno!=''){
     $ia_main_case_no = $causelist->get_case_no($ia_main_filingno, $db, $schemas);
 }
/* stop code to get main case no of IA */

/* start code to get all IA of main case */
 $ia_filingno_case_no = array();
 $get_all_ia_sql = "select filing_no from $schemas.case_detail where main_case_ia_no=? and ia_flag='TRUE'";
 $get_all_ia = $db->prepare($get_all_ia_sql);
 $get_all_ia->bindParam(1, $filing_no, PDO::PARAM_STR);
 $get_all_ia->execute();
 $ia_filingno = '';
 while ($row_gai = $get_all_ia->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
	  $ia_filingno=$row_gai['filing_no'];
	    if($ia_filingno!=''){
  $get_iacase_type = $causelist->get_incase_type($ia_filingno,$db, $schemas);
  $get_iacase = $causelist->get_case_no($ia_filingno,$db, $schemas);
  array_push($ia_filingno_case_no, array("case_no" => $get_iacase, "fil_no" => $ia_filingno, "case_type" => $get_iacase_type));
  //print_r($in_case_no);
}
	 /*if($ia_filingno!=''){
		$ia_filingno_case_no[] = $causelist->get_case_no($ia_filingno, $db, $schemas);
		 }*/
 }

/* stop code to get mall IA of main case */

/*start of code to get in_rst_cases and main case of CA*/
$in_case_nor=array();
 
$get_in_filing_no_sql = "select in_filingno from e_case_detail where filing_no=?";
$get_in_filing_no = $db->prepare($get_in_filing_no_sql);
$get_in_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
$get_in_filing_no->execute();
$in_filingno = '';
while ($row_gifn = $get_in_filing_no->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $in_filingno=$row_gifn['in_filingno'];
  if($in_filingno!=''){
  $get_incase_type = $causelist->get_incase_type($in_filingno,$db, $schemas);
  $get_incase = $causelist->get_case_no($in_filingno,$db, $schemas);
  array_push($in_case_nor, array("case_no" => $get_incase, "fil_no" => $in_filingno, "incase_type" => $get_incase_type));
  //print_r($in_case_nor);
}
}
/*stop of code to get in_rst_cases and main case of CA*/ 

/*start of code to get all CA of CP*/
$in_case_nocp=array();
 
$get_in_filing_no_sql = "select filing_no from e_case_detail where in_filingno=?";
$get_in_filing_no = $db->prepare($get_in_filing_no_sql);
$get_in_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
$get_in_filing_no->execute();
$in_filingno = '';
while ($row_gifn = $get_in_filing_no->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $in_filingno=$row_gifn['filing_no'];
  if($in_filingno!=''){
  $get_incase_type = $causelist->get_incase_type($in_filingno,$db, $schemas);
  $get_incase = $causelist->get_case_no($in_filingno,$db, $schemas);
  array_push($in_case_nocp, array("case_no" => $get_incase, "fil_no" => $in_filingno, "incase_type" => $get_incase_type));
  //print_r($in_case_no);
}
}
/*stop of code to get all CA of CP*/ 

/*start of code to get all Connected Cases*/
$connected_cases=array();
 
$get_conn_cases_sql = "select conn_filing_no from $schemas.connected_cases where filing_no=?";
$get_conn_cases = $db->prepare($get_conn_cases_sql);
$get_conn_cases->bindParam(1, $filing_no, PDO::PARAM_STR);
$get_conn_cases->execute();
$conn_filing_no = '';
while ($row_gcc = $get_conn_cases->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $conn_filing_no=$row_gcc['conn_filing_no'];
  if($conn_filing_no!=''){
  $get_incase_type = $causelist->get_incase_type($conn_filing_no,$db, $schemas);
  $get_incase = $causelist->get_case_no($conn_filing_no,$db, $schemas);
  array_push($connected_cases, array("case_no" => $get_incase, "fil_no" => $conn_filing_no, "incase_type" => $get_incase_type));
}
}
//print("<pre>".print_r($connected_cases,true)."</pre>");
/*end of code to get all Connected Cases*/

/*if($item >0)
	
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
}*/
/*if($upload_flag==0)
{
echo " This order is already uploaded in website";
}*/
?>


  
    
<?php
$item='';
$case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] : $sanR_data['case_type'];
$case_no = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] : $sanR_data['case_no'];
$case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] : $sanR_data['case_year'];
$bench_type = isset($_REQUEST['bench_type']) ? $_REQUEST['bench_type'] : $sanR_data['location_code'];
if($item =='')
{
?>

<tr>

<br>
<!--font face="Verdana" size="2"><span class="error">*</span>Search Case:</font>
<?php $prt_type = isset($_REQUEST['prt_type']) ? $_REQUEST['prt_type'] :'1'; ?>
<select id="prt_type" name="prt_type" onchange="javascript:submitForm();" class="frm-field required" >
<option value="1" <?php if($prt_type == 1) { print " selected"; } ?> >Case No. Wise</option>
<option value="2" <?php if($prt_type == 2) { print " selected"; } ?> >Diary No. Wise</option>
</select>
</font-->
<?php
 if($prt_type =='1')
{
	?>
    <td>
    	<?php
    	if(empty($in_case_nor) && $ia_main_filingno == ''){
    		?>
<font color="#900C3F" face="verdana" size="2"><?php echo '<b>'.$main_case_no.'</b>' ?></font>
<input type="hidden" id="all_filingno" name="all_filingno" value="<?php echo $filing_no; ?>">

<?php 
}
  if(!empty($in_case_nor)){ 
 $len_array = count($in_case_nor);
 for($i=0;$i<$len_array;$i++) {
   if($in_case_nor[$i]['case_no']!=''){
   	?>
<font color="#900C3F" face="verdana" size="2"><?php echo '<b>'.$in_case_nor[$i]['case_no'].'</b>';?>
</font>
<input type="hidden" id="all_filingno" name="all_filingno" value="<?php echo $in_case_nor[$i]['fil_no']; ?>">
<?php
		}	
 }
}

 /* if(!empty($in_case_nocp)){ 
 $len_array = count($in_case_nocp);
 //echo "<hr>";
 for($i=0;$i<$len_array;$i++) {
   if($in_case_nocp[$i]['case_no']!=''){
	   
      //if($case_type != 16)
//echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size='2'>Incp</font>";
      //else
echo "<br>";?>
<br>

<font color="#900C3F" face="verdana" size="2"><?php echo '<b>'.$in_case_nocp[$i]['case_no'].'</b>';?>
</font>
<input type="hidden" id="all_filingno" name="all_filingno[]" value="<?php echo $in_case_nocp[$i]['fil_no']; ?>">
<?php
		}	
 }
}*/

  if($ia_main_filingno!=''){ 
	 ?>
<font color="#900C3F" face="verdana" size="2"><?php echo '<b>'.$ia_main_case_no.'</b>';?>
</font>
<input type="hidden" id="all_filingno" name="all_filingno" value="<?php echo $ia_main_filingno; ?>">
<?php
$ia_main_filingno = '';	
 }

  /*if(!empty($ia_filingno_case_no)){ 
 $len_array = count($ia_filingno_case_no);
 //echo "<hr>";
 for($i=0;$i<$len_array;$i++) {
   if($ia_filingno_case_no[$i]['case_no']!=''){
	   
      //if($case_type != 16)
//echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size='2'>Incp</font>";
      //else
echo "<br>";?>
<br>

<font color="#900C3F" face="verdana" size="2"><?php echo '<b>'.$ia_filingno_case_no[$i]['case_no'].'</b>';?>
</font>
<input type="hidden" id="all_filingno" name="all_filingno[]" value="<?php echo $ia_filingno_case_no[$i]['case_no']; ?>">
<?php
		}	
 }
}*/


  /*if(!empty($connected_cases)){ 
  $len_array = count($connected_cases);
  echo "<br>";
  //echo "<hr>";
 echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size='2'><b>with</b></font>";
 for($i=0;$i<$len_array;$i++) {
   if($connected_cases[$i]['case_no']!=''){

?>
<br>

<font color="#900C3F" face="verdana" size="2"><?php echo '<b>'.$connected_cases[$i]['case_no'].'</b>';?>
</font>
<input type="hidden" id="all_filingno" name="all_filingno[]" value="<?php echo $connected_cases[$i]['fil_no']; ?>">
<br>
<?php
		}	
 }
}*/
?>
</td>
<td>
ITEM NO:
	<?php echo '<b>'.$item_no.'</b>';?>
	</td>
	<?php
}
	?>

<input type="hidden" id="item_no1"  name="item_no1" value="<?php print htmlspecialchars($item_no); ?>" />
</tr>
</table>
<?php
}
?>
<table width="100%"  border="0" cellspacing="1" cellpadding="1" align="center">
<?php 

$filing_no98 = $filing_no;

if($filing_no98 !='')
{
	/*if($pet_type =='2')
	{
		if($pet_code > 0)
		{
				
			$stn = $db->prepare("select org_name from $schemas.master_licensor where org_code=?");
			 $stn->bindParam(1, $pet_code, PDO::PARAM_INT);
			 $stn->execute();
			 $pet_org_name = $stn->fetchColumn();
			
				
		}
	}
		
		
		
	if($pet_code > 0 and $pet_type ==4)
	{
		$stm = $db->prepare("select org_name from $schemas.master_licensee where org_code=?");
		$stm->bindParam(1, $pet_code, PDO::PARAM_INT);
		$stm->execute();
		$pet_org_name = $stm->fetchColumn();
			
			
	}
		
	if($res_type =='2')
	{
	if($res_code > 0)
	{
	
		$stnm = $db->prepare("select org_name from $schemas.master_licensor where org_code=?");
		$stnm->bindParam(1, $res_code, PDO::PARAM_INT);
		$stnm->execute();
		$res_org_name = $stnm->fetchColumn();
			
		
		
	}
	}
			
		if($res_type =='4')
		{
		if($res_code > 0)
		{
			$stmn = $db->prepare("select org_name from $schemas.master_licensee where org_code=?");
			$stmn->bindParam(1, $res_code, PDO::PARAM_INT);
			$stmn->execute();
			$res_org_name = $stmn->fetchColumn();
			
		}
		}*/
	
	?>
	
<td align="center"><br><font color='red'><?php 
     	if(!empty($in_case_nor)){
	  $len_array = count($in_case_nor);
     for($i=0;$i<$len_array;$i++) {
	 $petnamec = $causelist->getPetname($in_case_nor[$i]['fil_no'], $schemas, $db, $db); 
     $resnamec = $causelist->getResname($in_case_nor[$i]['fil_no'], $schemas, $db, $db);
   }	 
	}
	else {
     $petnamec = $causelist->getPetname($filing_no, $schemas, $db, $db); 
     $resnamec = $causelist->getResname($filing_no, $schemas, $db, $db); 
	}
     //$dit = 'And';
     
     $petpartyc = '';
     if($case_type == 14 || $case_type == 15){
      $petpartyc = $causelist->getPetParty($filing_no, $db);
     }
     ?>
     <center><font face="Verdana" size ="2"><?php echo strtoupper($petnamec).'<br>';
                                          if(!empty($petpartyc)){
                                            foreach($petpartyc as $key => $value)
                                            {
                                              echo '<center><b>And</b></center><br>';
                                              print_r(strtoupper($value));
                                              echo '<br>';
                                            }
                                          }
                                          echo '<center><font color="red">Vs.</font></center><br>'.strtoupper($resnamec) ?></font></center>
     </font></td>
	<!--<tr>
    <td align="left" valign="top" colspan="1" width="40%">
	<?php 
	//echo "<br>";
	//echo "<h7><font color='red'><center>";
	//if($status =='P'){ htmlspecialchars("CASE IS PENDING")."</center></font></h7>";}    //no echo
	
	//if($status=='D')
	//{
	//echo "</center><font color='red'><h6>";
	//echo htmlspecialchars("CASE IS DISPOSED");
	//echo "</center></font></h7>";
	//echo "<br>";
	
	//Disposed Date
	
	
		//$st = $db->prepare("select * from $schemas.case_disposal where filing_no=? ");
		//$st->bindParam(1, $filing_no, PDO::PARAM_INT);
		//$st->execute();
		//while ($rw4 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		//{
			//$filing_no=htmlspecialchars($rw4['filing_no']);
			//$disposal_date=htmlspecialchars($rw4['disposal_date']);
			//$disposal_nature=htmlspecialchars($rw4['disposal_nature']);
		//}
		//if($disposal_date && $disposal_nature){
		//echo "</center><font color='blue'><h7>";
		//echo 'Disposal Date'.htmlspecialchars($disposal_date);
		//echo 'Disposal Nature('.htmlspecialchars($disposal_nature).')';
		//echo "</center></font></h7>";
		//echo "<br>";	
		//}
	
	//}
	
	?>
	</td>
	</tr>-->
	<?php 
	/*list($day,$month,$year)=explode('/',$list_date_link);
      	$list_date_order=$year.'-'.$month.'-'.$day;
	$st = $db->prepare("select * from $schemas.order_daily where flag='N' and filing_no=? and order_date=? ");
		$st->bindParam(1, $filing_no98, PDO::PARAM_INT);
		$st->bindParam(2, $list_date_order, PDO::PARAM_INT);
		$st->execute();
		while ($rw4 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
			$filing_no_order=htmlspecialchars($rw4['filing_no']);
	
		}*/
	
	/*
	if($filing_no_order!='')
	{
		$mm="PLEASE GO TO THE MODIFY OPTION THIS CASE ALREADY FOUND IN DAILY ORDER ";
	
	}
	?>
	</tr>
	<tr>
	<td colspan="5" >
	<br>
	<br>
	<b><font color="BLUE">
	<?php
echo $mm;
die();	?>
<tr><td colspan="14">
<font size="2">
Pet. Advocate Name:
<?php

if($pet_adv > 0)
{
	$sth =$db->prepare("select adv_name from $schemas.master_advocate where adv_code=?");
	$sth->bindParam(1, $pet_adv, PDO::PARAM_STR);
	$sth->execute();
	$pet_adv_name = $sth->fetchColumn();
}

echo htmlentities(htmlspecialchars(strtoupper($pet_adv_name)));
?>
</br>Additional Advocate(Pet.):&nbsp;
<?php 
$patyf='P';

$sql13 = "select distinct(adv_code) from $schemas.additional_advocate where
filing_no=? and party_flag=?";
$sql= $db->prepare($sql13);
$sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql->bindParam(2, $partyf, PDO::PARAM_STR);
$sql->execute();
while ($row = $sql -> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{  
	$adv_code1=$row['adv_code'];
	if($adv_code1 > 0)
	{
	$sth =$db->prepare("select adv_name from $schemas.master_advocate where adv_code=?");
	$sth->bindParam(1, $adv_code1, PDO::PARAM_STR);
	$sth->execute();
	$adv_name = $sth->fetchColumn();
	}
	
echo htmlspecialchars(strtoupper($adv_name)).','; 
}
?>
</font>
</td>
</tr>
<tr><td colspan="14">
<font size="2">
<?php 
if($res_adv > 0)
{
	$sthres =$db->prepare("select adv_name from $schemas.master_advocate where adv_code=?");
	$sthres->bindParam(1, $res_adv, PDO::PARAM_STR);
	$sthres->execute();
	$res_adv_name = $sthres->fetchColumn();
}
?>
Respondent Advocate:&nbsp;<?php echo htmlspecialchars(strtoupper($res_adv_name));
?>
</br>Additional Advocate(Res.):&nbsp;
<?php 
$patyf1='R';

$sql13 = "select distinct(adv_code) from $schemas.additional_advocate where
filing_no=? and party_flag=?";
$sql= $db->prepare($sql13);
$sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql->bindParam(2, $patyf1, PDO::PARAM_STR);
$sql->execute();
while ($row = $sql -> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{  
	$adv_code11=$row['adv_code'];
	if($adv_code11 > 0)
	{
	$sth =$db->prepare("select adv_name from $schemas.master_advocate where adv_code=?");
	$sth->bindParam(1, $adv_code11, PDO::PARAM_STR);
	$sth->execute();
	$adv_name11 = $sth->fetchColumn();
	}
	
echo htmlspecialchars(strtoupper($adv_name11)).','; 

}

?>
</font>
</td>
</tr>
<?php */  ?>

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



<?php  //$benchnature = isset($_REQUEST['benchnature']) ? $_REQUEST['benchnature'] :$bench_nature1; 
$benchnature = $bench_nature1;
?>
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


<?php  //$courtno = isset($_REQUEST['courtno']) ? $_REQUEST['courtno'] :$court_no_link;
$courtno=$court_no_link;
 ?>
<input type="hidden" id="courtno" name="courtno" value="<?php print htmlspecialchars($courtno); ?>" />
<!--font color="red">*</font><font size="1">Court No.:</font>
<select id="in_courtno" size="1" name="courtno" style="width:40px;">
             <option value="">Select</option>
      	<?php
      	list($day,$month,$year)=explode('/',$list_date_link);
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
<tr><td align="left" colspan="7"><font face="Verdana" size ="2" ><b>
<?php
list($day,$month,$year)=explode('/',$nd);
$fd=$year.'-'.$month.'-'.$day;
list($day,$month,$year)=explode('/',$list_date_link);
$listdate_entire=$year.'-'.$month.'-'.$day;
$stat="select * from $schemas.bench where  bench_nature ='$list_before_link' and from_list_date='$listdate_entire' and  bench_no='$bench_code1'";
$stat=$db->prepare("$stat");
$stat->execute();
while ($row = $stat->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$bench_no= $row['bench_no'];
$court_no =$row['court_no'];
$presiding=$row['presiding'];
$stat1="select *  from $schemas.master_judge where judge_code ='$presiding'";
$stat1 = $db->prepare($stat1);
$stat1->execute();
$presiding1 = $stat1->fetch();
echo $gen=$presiding1['gen']."&nbsp";
echo  $presiding1['judge_name'];
$hon_text=$presiding1['hon_text'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$presiding1[judge_desg_code], PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ".$desg_name = $stat1->fetchColumn();
}
?>
<br>

<?php
 $stat2="select distinct(judge_code) as judge_code from $schemas.bench_judge where bench_nature='$list_before_link' and from_list_date='$listdate_entire' and judge_code !='$presiding' and bench_no='$bench_code1'";
$stat2=$db->prepare("$stat2");
$stat2->execute();
while ($row2 = $stat2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$judge_code =$row2['judge_code'];
$stat1="select judge_name,judge_desg_code,gen,hon_text from $schemas.master_judge where judge_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$judge_code, PDO::PARAM_STR);
$stat1->execute();
$judge_data = $stat1->fetch();
$gen = $judge_data['gen'];
$hon_text = $judge_data['hon_text'];
echo $gen." ".$judge_name = $judge_data['judge_name'];
$desg_code = $judge_data['judge_desg_code'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$desg_code, PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ".$desg_name = $stat1->fetchColumn();
}
?>
</font>
</td></tr>

</table>   
 <table width="100%" border='0' cellpadding="1" cellspacing="3" align="center"> 

    
     
<tr>
		<td align="left" colspan="1" width="100%">
		</br>
		<fieldset>
  		<legend><b>FOR APPLICANTS ADVOCATE / REPRESENTATIVE</b></legend>
		
	 <input type="text"  name="name[]" maxlength="150" autocomplete="off" size="30"  onFocus="SetBg(this)" onBlur="UnSetBg(this)"/><br>
	
	

 <script>  
 $(document).ready(function(){  
      var i=1;  
	  
      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="name[]" placeholder="Enter Advocate Name" class="form-control name_list"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });
       
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
		   
           $('#row'+button_id+'').remove();  
      });  
      /*$('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#frm').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#frm')[0].reset();  
                }  
           });  
      }); */ 
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
  		<legend><b>FOR RESPONDENTS ADVOCATE / REPRESENTATIVE</b></legend>
  		
	 <input type="text"  name="rname[]" maxlength="150" autocomplete="off" size="30"  onFocus="SetBg(this)" onBlur="UnSetBg(this)"/><br>
	
	
	 
	 <div class="container">  
        
                <div class="form-group">  
                      
                          <div class="table-responsive">  
<table class="table table-bordered" id="dynamic_field1">  
                                    
      
		
  <td><div>
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
      /*$('#submit1').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#frm').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#frm')[0].reset();  
                }  
           });  
      }); */ 
 });  
 </script>
	</td>				
  </tr>
  <tr> 
  
 									
                                   
 
 
  <tr>
<td>  
				
	</td>								
									
									
									</tr>  
									
									
									
                               </table>  
	<tr>
<td><button type="button" name="add1" id="add1" value="1' class="btn btn-success">Add More</button></td> 
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
<!---	
<tr><td colspan="14">
<br><font color="red">*</font>
<!--font size="1">ORDER DATE:</font
<font >Per:</font>
 

<select name="author_name">
<option value="">Select </option>
<?php 
/*
$sql_judge="select jm.judge_name,jm.judge_desg_code,jm.judge_code from $schemas.master_judge as jm,$schemas.bench as b where  b.from_list_date=? and b.bench_no=? and
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
}
*/
?>
	




</select>
<td>

</tr>	
-->
<tr>
		
		 <td align="center" colspan="2">
		<br>
  		<legend><b><u>ORDER</u></b></legend>
 	
  </br>
<main>
	<div class="adjoined-bottom">
		<div class="grid-container">
			<div class="grid-width-100">
				<textarea name="editor"></textarea>
			</div>
		</div>
	</div>
</main>	
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



</table>



<?php } ?>
</form>
</body>
</html>

<?php 
}
}
 ?>

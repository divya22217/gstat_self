<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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

	/*
	if($main_id =='9999' and $localadmin =='0')
	{
		if($_SESSION['menuaccess_codeall'] !='2')
		{
			echo "Access Problem.....";
			header("Location: ../index.php");
			die();
		}
	}*/
	

	$filing_no_next=$_REQUEST['filing_no_next'];
	$hash1=htmlspecialchars(base64_decode($filing_no_next));
	$hash1 = explode("-", $hash1);
	$miscellaneous_no = $hash1[0];
	$filing_no_fou=$hash1[1];
	
	$token_fou= $hash1[2];
	
	$hash=$_REQUEST['doc_hash'];
	$hash = htmlspecialchars(base64_decode($hash));
	if(!$hash || $hash == '')
	{
		echo "You can't access this page.....";
		header("Location: ../login.php?aa=100");
		die();
	}
	/* if($hash != $_SESSION['random_key'])
	{
		echo "You can't access this page.....";
		header("Location: ../login.php?aa=100");
		die();
	} */

if($_SESSION['qqcc'] != $token_fou)	
{
	echo "Access Problem.....";
	header("Location: ../login.php?aa=100");
	die();
}
	if($token_fou =='')
	{
		echo "Access Problem.....";
		header("Location: ../login.php?aa=100");
		die();
	}
	
	

	// This code not use next time .......	Schema session create Hear....

	$location_access=$_SESSION['location'];
	$sessionUserType=htmlspecialchars($_SESSION['id']);


	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";

 
include '../inheader.php';


?>
<?php 

$form2 = sha1( uniqid('auth', true) );
$_SESSION['form2_scruniny'] = $form2;
?>
<body onload="myFunction()">
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   
    <!-- Main content -->
    <section class="content">
      <?php 
        		$hash=$_REQUEST['hash'];
        		
        		if($hash !='')
        		{
        		 
        			$hash1=htmlspecialchars(base64_decode($hash));
        			$hash1 = explode("/", $hash1);
        			$massage=$hash1[0];
        			$filing_no_backpage = $hash1[1];
        			$filing_no_backpage_print=htmlspecialchars(base64_decode($filing_no_backpage));
        			
        			echo "<center></br><font color='red' size='4'>".htmlspecialchars($msg).'</br>';
        		
        		 } 
         ?>
		 
      <!-- Default box -->
      <div class="col-md-12">
      	<div style='text-align:center;'><a href="../scrutiny/document_scrutiny.php" class="text-danger font-weight-bold">
        << BACK << </a>
        </div>
      <div class="box box-success">
        <div class="box-header with-border">
          <h4 class="box-title">Case Scurtiny &nbsp;&nbsp;
 Document Reference No : <?php echo htmlspecialchars($miscellaneous_no);?>
 &nbsp;&nbsp;
 <?php 
 
	$get_case_info=$db->prepare("select case_type,filing_no,main_case_ia_no from $schemas.case_detail where filing_no=?");
	$get_case_info->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
	$get_case_info->execute();
	$get_case_info = $get_case_info->fetchAll();
	$get_case_info = array_shift($get_case_info);
	$case_type = $get_case_info['case_type'];
	$scrutinized_filing_no = $get_case_info['filing_no'];
	$filing_no_ia_ma = $get_case_info['main_case_ia_no'];
	
	if($filing_no_ia_ma == ''){
		$final_filing_no = $scrutinized_filing_no;
	}else{
		$final_filing_no = $filing_no_ia_ma;
	}
	
	$st1=$db->prepare("select filing_no,dt_of_filing,case_type_nclat,location_id,act_id from e_case_detail where filing_no=? and location_id=?");
	$st1->bindParam(1, $final_filing_no, PDO::PARAM_STR);
	$st1->bindParam(2, $location_access, PDO::PARAM_STR);
	$st1->execute();

	while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{	
	    $filing_no = htmlspecialchars($row['filing_no']);

		$dt_of_filing = htmlspecialchars($row['dt_of_filing']);
		$case_type=htmlspecialchars($row['case_type_nclat']);
		
		//$case_no=$row['case_no'];
		$location_id=$row['location_id'];
		$act_id=$row['act_id'];
	}
	$stqq = $db->prepare("select act_name from master_act where act_id=?");
	$stqq->bindParam(1, $act_id, PDO::PARAM_INT);
	$stqq->execute();
	$act_name_all = $stqq->fetchColumn();
	
	$E_party_flag1='P';
	  $E_party_serial_no1='1';
	
		$st33=$db->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st33->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st33->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$st33->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$st33->execute();
		
		while ($row = $st33->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$E_party_flagP=$row['party_flag']; 
			$E_party_serial_noP=$row['party_serial_no']; //0
			$E_nameP=$row['name']; //0
			$E_party_org_typeP=$row['party_org_type']; //0
			$E_party_org_contact_personP=$row['party_org_contact_person']; 
			$E_party_addressP=$row['party_address']; 
			$E_pinP=$row['pin']; //0
			$E_state_codeP=$row['state_code']; //0
			$E_district_codeP=$row['district_code']; //0
			$E_nationalityP=$row['nationality']; 
			$E_emailP=$row['email']; 
			$E_mobileP=$row['mobile']; 
			$E_representative_codeP=$row['representative_code']; //0
			$E_aadhar_noP=$row['aadhar_no']; //0
			 $E_cin_noP=$row['cin_no']; //0
		}	
	
		$E_party_flag1='R';
		$E_party_serial_no1='1';
		$st34=$db->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st34->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st34->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$st34->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$st34->execute();
		while ($row = $st34->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$E_party_flagR=$row['party_flag'];
			$E_party_serial_noR=$row['party_serial_no']; //0
			$E_nameR=$row['name']; //0
			$E_party_org_typeR=$row['party_org_type']; //0
			$E_party_org_contact_personR=$row['party_org_contact_person'];
			$E_party_addressR=$row['party_address'];
			$E_pinR=$row['pin']; //0
			$E_state_codeR=$row['state_code']; //0
			$E_district_codeR=$row['district_code']; //0
			$E_nationalityR=$row['nationality'];
			$E_emailR=$row['email'];
			$E_mobileR=$row['mobile'];
			$E_representative_codeR=$row['representative_code']; //0
			$E_aadhar_noR=$row['aadhar_no']; //0
			$E_cin_noR=$row['cin_no']; //0
		}
	
	?>
	<font color="#0000FF" >
      <?php if($E_nameP!=''){echo htmlspecialchars_decode(strtoupper($E_nameP));} ; ?></font>
      <font color="#FF0000" size="2">&nbsp;&nbsp;Vs.&nbsp;&nbsp;</font><font color="#0000FF" >
      <?php if($E_nameR !=''){echo htmlspecialchars_decode(strtoupper($E_nameR));} ; ?></font>
 </h4>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
         
 <div class="form-group">
				 <div class="col-md-12">
             
       <script>
       function defect_submit()
       {
       	
       	let headingElements = document.getElementsByClassName('statuscheck');
		let noElements = Array.from(headingElements).filter(element => element.value.includes('NO'));
		let countEmptyComment = 0;
		let idDataPairs = noElements.map(element => ({
		    id: element.id,
		    dataId: element.getAttribute('data-id')
		}));
		idDataPairs.forEach(pair => {
		        let textarea = document.getElementById(`comment_${pair.dataId}`);
		        if (textarea && textarea.value.trim() === '') {
		            countEmptyComment++;
		            textarea.style.border = '2px solid red';
		        }
		});
		if(countEmptyComment > 0){
		    alert("please fill highligheted fields");
		    return false;
		}
       	
        with(document.form2)
       	{	
        		var validformat=/^\d{2}\/\d{2}\/\d{4}$/ 
        			if (!validformat.test(notification_date.value))
        			{	
        			alert("Invalid Date Format. Correct Date Format (dd/mm/yyyy)")
        			return false; 
        			}

        		if(searchby.options[searchby.selectedIndex].value == "0")
         		{
         			alert("Please Select Defect/ Defect Free  ");
         			searchby.focus();
         			return false;
         		}

        		var status1="";
        		//alert('self submit');	
        			var tnl=document.getElementsByName("status");
        		for(i=0;i<tnl.length;i++)
            		{
        	        
                	var val= tnl[i].value;
                     var status1=status1+val+','
                       }
        		if(!document.getElementById('agree').checked)
        		 {
        		     alert('You must agree to the terms first.');
        		     //agree.focus();
        		     return false;
        		 }

        	
       	 action = "varify_document_scrutiny_action.php?test="+status1;
       	submit();
       	document.form2.submit_final.disabled = true;  
      	document.form2.submit_final.value = 'Please Wait...';  
      	return true;
       	}
       }
       </script> 
        <script>
/* function myFunction() {

	 with(document.form2)
   	{ 

		 var tnl = document.getElementById("status");
         
	        for(i=0;i<tnl.length;i++){
	            if(tnl[i].selected == true){
	                alert(tnl[i].value);
	            }
	        }


	
   	}
}
 */


function myFunction(){
	
	var tnl=document.getElementsByName("status");
	
    var val1=""
 
   for (i = 0; i < tnl.length; i++) {

		var val = tnl[i].value;
			if (val == 'NO') {
				val1 = 'NO';
				document.getElementById("befornotification1").style.display = 'block';
				$(".rr").show();
				var selected_defects = $('#cause_no').val(); 
				 console.log(selected_defects);
				if(selected_defects.includes('6')){
					
		            $("#scrutiny_docs").show();
				}else{
					$("#scrutiny_docs").hide();
				}
				
			}


	}
    if(val1=="")
    {
    	val1='YES';
    	$(".rr").hide();
    	$("#scrutiny_docs").hide();
    }

	 if (window.XMLHttpRequest) {
         
         xmlhttp = new XMLHttpRequest();
     } else {
      
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
     }
     xmlhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
             document.getElementById("befornotification1").innerHTML = this.responseText;
         }
     };
     xmlhttp.open("GET","notificationdate.php?val="+val1,true);
     xmlhttp.send();

  
	document.getElementById("befornotification1").style.display ='block';
	document.getElementById("befornotification").style.display ='none';

} 



</script>   
<script type="text/javascript" src="accordion.js"></script> 
<script type="text/javascript" src="jquery.min.js"></script>
<link href="demo.css" rel="stylesheet">
<style>
button.accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

button.accordion.active, button.accordion:hover {
    background-color: #ddd; 
}

div.panel {
    padding: 0 18px;
    display: none;
    background-color: white;
}
</style>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  }
}
</script>    
       <?php 
       $remove_defact = sha1( uniqid('auth', true) );
       $_SESSION['remove_defact'] = $remove_defact;
       
      
       ?>       
              
  <form name="form2" method="post" action="varify_document_scrutiny_action.php" >
  <input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2);?>" />
   <input type="hidden" name="filing_no_next" value="<?php echo htmlspecialchars($filing_no_next);?>" />
   <table width="100%" >
     
			<tr><td colspan="6">
			<?php 
			 $tokenno=$filing_no_fou;	  

	$ll='22';
	$ll1='N';
	/* $stqq = $db->prepare("select count(filing_no) from $schemas.scrutiny_doc where filing_no=? and defects =? and level_level =?");
	$stqq->bindParam(1, $tokenno, PDO::PARAM_INT);
	$stqq->bindParam(2, $ll1, PDO::PARAM_INT);
	$stqq->bindParam(3, $ll, PDO::PARAM_INT);
	$stqq->execute();
	$filing_no_foundxcc = $stqq->fetchColumn();
	

	  if($filing_no_foundxcc > '0')
	  {
	  	 
	  	echo "<font color='red'><br></br><b>Scrutiny Already Completed !!!</b></font>";
	  	 
	  } */
  
	  
?>
			</td></tr>
        </table>


 <?php 
 

 $ll='1';

 
 $stqq = $db->prepare("select miscellaneous_no from $schemas.scrutiny_doc where miscellaneous_no=? and level_level=?");
 $stqq->bindParam(1, $miscellaneous_no, PDO::PARAM_STR);
 $stqq->bindParam(2, $ll, PDO::PARAM_STR);
 $stqq->execute();

$filing_norevari_var = $stqq->fetchColumn();
 if($filing_norevari_var !='')
 {
	
 
 $ll='1';

  
 $stqq = $db->prepare("select scrutinu_comp,defects,notification_date from $schemas.scrutiny_doc where miscellaneous_no=? and level_level=?");
 $stqq->bindParam(1, $miscellaneous_no, PDO::PARAM_STR);
 $stqq->bindParam(2, $ll, PDO::PARAM_STR);
 $stqq->execute();
 while ($row = $stqq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
 	 $scrutinu_comp_q = htmlspecialchars($row['scrutinu_comp']);
 	 $defects_q = htmlspecialchars($row['defects']);
 	 $notification_datez=date('Y-m-d',strtotime($row['notification_date']));
 }
 $filing_no=$tokenno;

 ?>

<tr>
    <td colspan="8">
    <style>
.tbl-accordion {
  margin: 0 auto;
  width: 900px;
  border: 1px solid #d9d9d9;
}
.tbl-accordion thead {
  background: #d9d9d9;
}
.tbl-accordion .tbl-accordion-nested {
  width: 100%;
}
.tbl-accordion .tbl-accordion-nested tr:nth-child(even) {
  background-color: #eeeeee;
}
.tbl-accordion .tbl-accordion-nested td, .tbl-accordion .tbl-accordion-nested th {
  padding: 10px;
  border-bottom: 1px solid #d9d9d9;
}
.tbl-accordion .tbl-accordion-nested .tbl-accordion-section {
  background: #333;
  color: #fff;
  cursor: pointer;
}


.defect-wrap {
    padding: 10px;
    margin: 32px;
    border: 1px solid #cccccc;
    overflow: hidden;
    display: block;
    background: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.dfct-list select {
    width: 502px;
    height: 184px;
    padding: 10px;
    background: #284984;
    color: #fff;
}
.dfct-list select option {
    border-bottom: 1px solid #ffffff45;
    font-size: 13px;
    padding: 3px;
}
.dfct-check label {
    font-weight: 500;
}
	
</style>
  
  <script>
$('.tbl-accordion-nested').each(function()
		  {
	  var thead = $(this).find('thead');
	  var tbody = $(this).find('tbody');
	  
	  tbody.hide();
	  thead.click(function(){
	    tbody. slideToggle();
	  })
	})
	
  </script>
  
<table cellpadding="0" cellspacing="0" class="tbl-accordion">
	<tbody>
		<tr>
			<td colspan="3">
				<table cellpadding="0" cellspacing="0" border='1' class="tbl-accordion-nested">
				<thead>
					<tr>
						<td><b> Case Detail </b></td>
						<td><b>Documents </b></td>
						<td><b> Payment Details </b></td>
					</tr>



					<tr>


						<?php

						$sthr = $db->prepare("select * from e_case_detail  where filing_no=? ");
						$sthr->bindParam(1, $tokenno, PDO::PARAM_STR);
						$sthr->execute();
						while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
							$fileupload_uniqueid = $rowa['unique_id_no'];
						}
						$display = '1';
						$scrutiny = '0';
						$form_status = "C";


						?>
						<input type="hidden" name="form_status" value="<?php echo htmlspecialchars(htmlentities($form_status)); ?>" />
						<?php

						$st = $db->prepare("select *  from document_upload where filing_no=? and scrutiny=? and display=?  and miscellenous_no = ?");
						$st->bindParam(1, $tokenno, PDO::PARAM_STR);
						$st->bindParam(2, $scrutiny, PDO::PARAM_STR);
						$st->bindParam(3, $display, PDO::PARAM_STR);
						$st->bindParam(4, $miscellaneous_no, PDO::PARAM_STR);
						$st->execute();
						while ($rowa = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
							$miscellaneous_ref_no = $rowa['miscellaneous_ref_no'];
							$fil_no = $rowa['filing_no'];
							$sub_doc_type = $rowa['subdoctype'];

							$defect_docs = $rowa['defect_docs'];
							$defect_docs = explode(',', $defect_docs);
							$cause_no = $rowa['obj_tabs'];
							$explode_cause_no = explode(',', $cause_no);
							$cross_objection_doc = $rowa['crossobjectiondoc'];


							$document_filed_date = $rowa['document_filed_date'];
							$path = $rowa['fileupload'];
							$returnfilename = $rowa['returnfilename'];

							list($returnfilename, $ext) = explode('.', $returnfilename);
							$returnfilename1 = $returnfilename;



							$stqq = $db->prepare("select e_document_name from e_document_type  where e_document_type=?");
							$stqq->bindParam(1, $sub_doc_type, PDO::PARAM_INT);
							$stqq->execute();

							$e_document_name_print = $stqq->fetchColumn();
						}


						?>
				</thead>
				<tbody>

					<!--start of code for case no. -->

					<?php
					//echo "select case_type, case_no, case_year, location_code from $schemas.case_detail where filing_no='$filing_no'";
					$casenosql = $db->prepare("select case_type, case_no, case_year, location_code from $schemas.case_detail where filing_no='$filing_no_fou'");
					$casenosql->execute();
					$row = $casenosql->fetch();
					$case_no = htmlspecialchars($row['case_no']);
					//echo $case_no;
					$case_no = ltrim($case_no, 0);
					//echo $case_no;
					$casetype = htmlspecialchars($row['case_type']);
					//echo $casetype;
					$locode = htmlspecialchars($row['location_code']);
					//echo $locode;
					$case_year = htmlspecialchars($row['case_year']);
					//echo $case_year;

					$casetypesql = $db->prepare("select short_name from case_type where id = '$casetype'");
					$casetypesql->execute();
					$case_type_short_name = $casetypesql->fetchColumn();
					$case_type_short_name = strtoupper($case_type_short_name);
					//echo $case_type_short_name;
					if ($locode == '') {
						$locode = 0;
					}
					//echo $lcodename;

					$case_no_final = $case_type_short_name . '/' . $case_no . '/' . $case_year;
					//echo $case_no_final;


					//$case_no_final = 0999988888;

					?>

					<td>
						<a href="javascript:void(0);" onclick="previewCIS('<?php echo $filing_no; ?>')" style="cursor: pointer">
							<font color="#900C3F" size="3">&nbsp;&nbsp;&nbsp;&nbsp;View</font>
						</a>
					</td>

					<td>
						<a onclick="OpenDMSForm('3','<?php echo $filing_no_fou; ?>','','<?php echo $miscellaneous_no; ?>')" style="cursor: pointer">

							<font color="#900C3F" size="3">&nbsp;&nbsp;
								&nbsp;&nbsp;View
						</a>

					</td>
					<td>
						<a onclick="OpenPreviewReceipt('<?php echo $filing_no; ?>')" style="cursor: pointer">
							<font color="#900C3F" size="3">
								View
						</a>
					</td>



			</tr>

			<?php

			//echo "select *  from document_upload where filing_no='$filing_no_fou' and scrutiny='$scrutiny' and display='$display'  ";
			$get_mis_no_doc = $db->prepare("select *  from document_upload where filing_no=? and miscellaneous_ref_no=? and scrutiny=? and display=?  ");
			$get_mis_no_doc->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
			$get_mis_no_doc->bindParam(2, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			$get_mis_no_doc->bindParam(3, $scrutiny, PDO::PARAM_STR);
			$get_mis_no_doc->bindParam(4, $display, PDO::PARAM_STR);
			$get_mis_no_doc->execute();
			$doc_c = 1;
			while ($row_mnd = $get_mis_no_doc->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			$miscellaneous_ref_no = $row_mnd['miscellaneous_ref_no'];
			$doc_id = $row_mnd['documentuploadmodelid'];
			if ($miscellaneous_ref_no) { ?>
			<tr>
				<td>
					<?php echo $doc_c . "). " . $miscellaneous_ref_no; ?>
				</td>
			</tr>
			<?php
			$doc_c++;
			} else { ?>
			<tr>
				<td>
					<?php echo "Not Found"; ?>
				</td>
			</tr>
			<?php }
			}

			?>

			<?php



			if ($ccase == '4') {
			if ($subdoctype == '17') {
			$subdocname = 'Report';
			$form_type = 'R';
			}
			if ($subdoctype == '33') {
			$subdocname = 'Order';
			$form_type = 'O';
			}

			//echo "select *  from document_upload where filing_no='$filing_no_fou' and scrutiny='$scrutiny' and display='$display'  ";
			$get_mis_no_doc = $db->prepare("select *  from document_upload where filing_no=? and miscellaneous_ref_no=? and scrutiny=? and display=? and subdoctype=? and party_type IN (select party_flag from e_master_govt_body) ");
			$get_mis_no_doc->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
			$get_mis_no_doc->bindParam(2, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			$get_mis_no_doc->bindParam(3, $scrutiny, PDO::PARAM_STR);
			$get_mis_no_doc->bindParam(4, $display, PDO::PARAM_STR);
			$get_mis_no_doc->bindParam(5, $subdoctype, PDO::PARAM_STR);
			$get_mis_no_doc->execute();
			$doc_c = 1;
			while ($row_mnd = $get_mis_no_doc->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			$miscellaneous_ref_no = $row_mnd['miscellaneous_ref_no'];
			$report_party_type = $row_mnd['party_type'];
			$doc_id = $row_mnd['documentuploadmodelid'];
			if ($miscellaneous_ref_no) { ?>
				<tr>
					<td>
						<?php echo $miscellaneous_ref_no . " (" . $subdocname . ")"; ?>
					</td>
				</tr>
			<?php
				$doc_c++;
			} else { ?>
				<tr>
					<td>
						<?php echo "Not Found"; ?>
					</td>
				</tr>
			<?php }
			}
			}
			?>



			</tbody>
			</table>
		</td></tr>
	</table>
  </td>
</tr>	
 </div>
 </div>
   </div>
 <div class="box-footer">
    <style>
    .greenText{ background-color:green; }

.blueText{ background-color:blue; }


    </style>

      <div class="main">
		<div class="accordion">
		
<?php 

?>		
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12" id="objection_tab">		
     <div class="accordion-section">
				<div id="accordion-1" class="accordion-section-content">
		 <table border="1" >
  <tr><td colspan="12"></td></tr>
  <tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
  <td width="3%">Sr. No</td>
  <td width="67%">Description</td>
  <td width="6%">As per scrutiny clerk</td>
  <td width="16%">Comments of scrutiny clerk, if any</td>
  <td width="6%">As per registrar</td>
  <td width="16%">Comments of registrar, if any</td>
  </tr>
 <tr><td colspan="12">
 
    <?php $status=$_REQUEST['status'];

 ?>

 <?php 
 
 

 $ll='1';
 $lls='0';
 $flag="";

 
 
 
 
// echo "select * from $schemas.objection_details where filing_no= '$filing_no' and entry_dt=$notification_datez and level_level=$ll and
 		//objection_sub_code=$lls ";  die;

 
 $stv = $db->prepare("select * from $schemas.document_objection_details where miscellaneous_no= ? and date(entry_dt)=? and level_level=? and
 		objection_sub_code=? order by edt asc");
 $stv->bindParam(1, $miscellaneous_no, PDO::PARAM_STR);
 $stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
 $stv->bindParam(3, $ll, PDO::PARAM_STR);
 $stv->bindParam(4, $lls, PDO::PARAM_STR);
 $stv->execute();
 
 $i=0;
 $j=1;
 $check_list_count = 1;
 while ($rowcc = $stv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
 
 
 	$objection_codeobbj=$rowcc['objection_code'];
 	$objection_codeststus=$rowcc['status_registrar'];
 	$objection_codeststus_sc = $rowcc['status'];
 	$objection_comment_sc = $rowcc['comments'];
 	if($objection_codeststus=='NO')
 	{
 		 $flag='NO';
 	}
 	$comments=$rowcc['comment_registrar'];
 
 	$sth=$db->prepare("select * from check_list_local where id=? order by id ASC ");
 	//$sth->bindParam(1, $location_access, PDO::PARAM_STR);
 	$sth->bindParam(1, $objection_codeobbj, PDO::PARAM_STR);
 	$sth->execute();
 
 	while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 	{
 			
 			
 		$id_check=$rowa['id'];
 		$check_list=$rowa['check_list'];
 	}
 	?>
 <tr>
 
  <td width="5%">
 
  <?php echo htmlspecialchars($check_list_count);?>
   
  </td>

  
  <td width="60%" ><font color=" #1c2833 "><?php echo htmlspecialchars($check_list);?></font>
  </td>
  <td width="5%"> <?php echo $objection_codeststus_sc; ?></td>
  <td width="5%"> <?php echo $objection_comment_sc; ?></td>
  <?php 
  if($status[$j]=='NO')
  {
  	$dhiraj ="OK";
  }

  ?>
 
  <td width="7%">

<?php
 $status[$j] = isset($_REQUEST['status'][$j]) ? $_REQUEST['status'][$j] : $objection_codeststus; ?>
				 <select name="status" class="statuscheck" data-id="<?php echo $id_check; ?>" id="objection_status_<?php echo $id_check; ?>" style="
    background-color: silver;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onchange="myFunction()" >
 				 	<option value="YES" <?php  if($objection_codeststus=='YES') echo "selected"; ?> >YES</option>
 				 	<option value="NO" <?php  if($objection_codeststus=='NO') echo "selected"; ?> >NO</option>
 				 	
                 </select>           
  
  <td width="20%">
 <?php
 $comment[$j] = isset($_REQUEST['comment'][$j]) ? $_REQUEST['comment'][$j] : $comments; ?> 
   <textarea name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" style="background-color: silver;" class="comment" data-id="<?php echo $id_check; ?>" id="comment_<?php echo $id_check; ?>">
<?php echo $comments; ?></textarea>
  </td>

  <?php 
  //echo $j;
  ?>
  </tr>
 
<input type="hidden" name="id_check[]" maxlength="3" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($id_check.','.'gen'));?>"  size="2"/>

<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars(htmlentities($filing_no));?>"/>  
  
 <?php
$j++;
$i++;
//}
$check_list_count++;
}
 ?>
 </td></tr>
					

    </table>
   
  </div></div>
  
  </div>
  <div class="" id="pdf_tab">
	<div class="main">
		<div class="accordion">
			<div id="view_pdf">

			</div>
		</div>
	</div>
</div>
</div>
 <?php 
 
 
 $ll='1';
 $lls='1';
 $flag="";
 
 
 

 
 $stv = $db->prepare("select count(*) from $schemas.document_objection_details where miscellaneous_no= ? and entry_dt=? and level_level=? and
 		objection_sub_code=?");
 $stv->bindParam(1, $miscellaneous_no, PDO::PARAM_STR);
 $stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
 $stv->bindParam(3, $ll, PDO::PARAM_STR);
 $stv->bindParam(4, $lls, PDO::PARAM_STR);
 $stv->execute();
  $filing_count_zzz = $stv->fetchColumn();
 
 if($filing_count_zzz > '0')
 {
 
 ?> 
  
   <div class="accordion-section">
				<a class="accordion-section-title" href="#accordion-2"><?php if($case_type!=15 && $case_type!=14){echo htmlspecialchars($act_name_all);} else if($case_type==15){echo 'Second Motion';}
				else if($case_type==14){echo 'First Motion';}?></a>
				<div id="accordion-2" class="accordion-section-content">	
			 <table border="1" >	
	 <tr><td colspan="12"></td></tr>
  <tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
  <td width="3%">Sr. No</td><td width="67%">Description</td><td width="6%">Defect Free</td><td width="16%">Comments</td>
  </tr>
 <tr><td colspan="12">
 
    <?php $status=$_REQUEST['status'];
 
 ?>

 <?php 
 
 $flag1="";
  $display='TRUE';

  //find to open scrutiny master local
  if($act_id =='1' OR $act_id =='2')
  { $act_id='1';}
  

 $ll='1';
  $lls='1';
 
  
  
 //$sql ="select * from $schemas.objection_details where miscellaneous_no='$miscellaneous_no' and date(entry_dt)='$notification_datez' and level_level='$ll' andobjection_sub_code='$lls' and  status <>'' order by cast(objection_code as integer) ASC ";


			
  $stv = $db->prepare("select * from $schemas.document_objection_details where miscellaneous_no= ? and date(entry_dt)=? and level_level=? and
  		objection_sub_code=? and  status <>''  order by cast(objection_code as integer)");
  $stv->bindParam(1, $miscellaneous_no, PDO::PARAM_STR);
  $stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
  $stv->bindParam(3, $ll, PDO::PARAM_STR);
  $stv->bindParam(4, $lls, PDO::PARAM_STR);
  $stv->execute();
	
  $i=0;
  $j=1;
  while ($rowcc = $stv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  
  
  	 $objection_codeobbj=$rowcc['objection_code'];
    $objection_codeststus=$rowcc['status'];
  	if($objection_codeststus=='NO')
  	{
  		 $flag1='NO';
  	}
  	
  		$comments=$rowcc['comments'];
  
 

while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
 $id_check=$rowa['id_serno'];
 $check_list=$rowa['name'];
 $display_all=$rowa['display'];
}
?> 
  <tr>
 
  <td width="5%">
 
  <?php echo htmlspecialchars($j);?>
   
  </td>

 <?php 
 if($display_all  != '0')
 {
?>
  <td width="60%" ><font color="#1c2833"><b>
  <?php } ?>
   <?php 
 if($display_all  == '0')
 {
?>
  <td width="60%" >
  <?php } ?>
  
  <?php echo htmlspecialchars($check_list);?> 
  <?php 
 if($display_all  != '0')
 {
?></b></font>
<?php } ?>
  </td>

 
  <td width="7%">

 <?php 
 //if($display_all  == '0')
 //{
?>
<?php
 $status[$j] = isset($_REQUEST['status'][$j]) ? $_REQUEST['status'][$j] : $objection_codeststus; ?>
				 <select name="status" id="status"  onchange="myFunction()" style="
    background-color: silver;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;"   >
 				 	<option value="YES" <?php  if($objection_codeststus=='YES') echo "selected"; ?> >YES</option>
 				 	<option value="NO" <?php  if($objection_codeststus=='NO') echo "selected"; ?> ><font color="red">NO</font></option>
 				 	
                 </select>    
                 <?php // }
 ?>       
  </td>
  
  <td width="20%">
   <?php 
// if($display_all == '0')
// {
?>
 <?php
 $comment[$j] = isset($_REQUEST['comment'][$j]) ? $_REQUEST['comment'][$j] : $comments; ?> 
   <textarea name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" style="background-color: silver;">
<?php echo htmlspecialchars($comments);?></textarea>

<?php 
 
$comments="";
 //} 
 ?>
  </td>

  
  </tr>
 
<input type="hidden" name="id_check[]" maxlength="3" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($id_check.",".'IBC1'));?>"  size="2"/>

<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars(htmlentities($filing_no));?>"/>  
  
 <?php
$j++;
$i++;
}

?>
 
 </td></tr>	</table>
 	</div></div>
<?php } ?>	

<div class="rr defect-wrap">

		<div class="dfct-list">

			<span class="rr">

				<font face="Verdana" size="2" color="red">*

			</span> </font>

			<font face="Verdana, Arial, Helvetica, sans-serif" size="4" color="red">

				Defects in <span style="font-size:12px;">For Multiple Defects Select

					(ctrl+left mouse click)</span></font>

			</span><br>

			<select style="display:none;" class="rr"

				name="cause_no[]" id="cause_no" onFocus="SetBg(this)"

				onChange="get_document(this.value,'<?php echo $filing_no; ?>');"

				onBlur="UnSetBg(this)" multiple>

				<?php

							
							if($cross_objection_doc == '1'){
							
								$query = "select l.role from e_case_detail ecd
											inner join loginmodel l on ecd.loginid=l.loginid
											where ecd.filing_no=?";
								$cross_tabs_query = $db->prepare($query);
								$cross_tabs_query->bindParam(1, $filing_no, PDO::PARAM_STR);
								$cross_tabs_query->execute();
								$cross_tab_role = $cross_tabs_query->fetchColumn();
								$condition = '';
								if($cross_tab_role == '23')
									$condition = " and cause_no in (1,6)";
							
								$st1 = $db->prepare("select * from cross_objection_tabs where is_active = true $condition order by id");
							}else{
								$st1 = $db->prepare("select * from cross_objection_tabs where is_active = true and cause_no = 6 order by id");
							}

							$st1->execute();

					while ($row = $st1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

						$e_name = $row['cause_name'];

						$cause_no = $row['cause_no'];

						if (!is_array($explode_cause_no)) {

							$explode_cause_no = [];

						}

								

				if($cross_objection_doc != '1'){

								?>


				<option value="<?php echo $cause_no; ?>" selected 

					<?php

								if (in_array($cause_no, $explode_cause_no)) {   $sc_marked_defects .= $e_name.' , ';   }?>>

					<?php echo strtoupper($e_name); ?></option> <?php

							}else{
								?>


				<option value="<?php echo $cause_no; ?>" 

					<?php

								if (in_array($cause_no, $explode_cause_no)) {   $sc_marked_defects .= $e_name.' , ';        print "selected";}?>>

					<?php echo strtoupper($e_name); ?></option> <?php
							}

				}

							?>

			</select>

			<?php 

								

			$query = "select documentuploadmodelid,fileupload,filename,docum_type from document_upload where filing_no = ? and miscellenous_no = ?";
			$docs_query = $db->prepare($query);
			$docs_query->bindParam(1, $filing_no, PDO::PARAM_STR);
			$docs_query->bindParam(2, $miscellaneous_no, PDO::PARAM_STR);
			$docs_query->execute();
			$docs = $docs_query->fetchAll();

			if(!empty($docs)){
				$show_docs = "display:inherit";
			}else{
				$show_docs = "display:none";
			}

			?>

		</div>

		<div class="dfct-check">

			<div id="scrutiny_docs" style=<?php echo $show_docs; ?>>

				<?php
			foreach($docs as $k=>$v){ ?>
				<input type="checkbox" id="doc_<?php echo $v['documentuploadmodelid'] ?>" name="docs[]" value="<?php echo $v['documentuploadmodelid'] ?>" <?php  echo (in_array($v['documentuploadmodelid'], $defect_docs))?'checked':''; ?>>
			  <label for="vehicle1"> <?php echo $v['docum_type'].'  /  '.$v['filename']; ?></label><br>
				
			<?php }
			?>

		

			</div>

		</div>

	</div>
			
				<table>
				
		<tr><td colspan="2" style="padding-top:5px;">
Verified Date: <input id="in_notification_date" type="text" name="notification_date" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($cur_date1));?>" size="10" maxlength="10"  style="margin-top: 2px;"/>
</br>
<input type="checkbox" value="0" id="agree" name="agree" required="required"> 
<b><font color="red">Are You Sure</font></b>
</td>
<td style="display: block;padding-top:5px;" colspan="4" id="befornotification1" >
<td style="display: block" colspan="4" id="befornotification">
	

	<select id="in_searchby" name="searchby" style="display: block;background-color: red">
	    	  
	    		
	    		<?php 
	    		
	    	
	    		
	    		if($flag=='NO' || $flag1=='NO'){?>
	    		<option value="1">Case Is Defective</option>	
	   			<?php }else { ?>
	   			<option value="2">Defects Free</option>
	         <?php } ?>
	   </select>
	 </td><td>
&nbsp;&nbsp;&nbsp;&nbsp;
 	<input id="submit_final" type="submit" name="submit_final"
 class="submit" value="Proceed Further" style="
    background-color: green;
    color: #FFFFFF;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onClick="return defect_submit();"/>

 </td></tr> </table>
			
          <?php }  ?>                         
      </form> 
        
        <!-- /.box-footer-->
      </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <script >
  	function get_document(defect_type,filing_no){
		 var selected_defects = $('#cause_no').val(); 
		 console.log(selected_defects);
		if(selected_defects.includes('6')){
			
            $('#scrutiny_docs').css('display','inherit');
		}else{
			$('#scrutiny_docs').css('display','none');
		}
	}

 function OpenDMSForm(url,val1,val2,val3,val4,val5,val6,val7,val8,val9,val10)

 {

document.getElementById("frm").action=url;
document.getElementById("itemno1").value=val1;
document.getElementById("applno1").value=val2;
document.getElementById("courtno1").value=val3;
document.getElementById("caseno1").value=val4;
document.getElementById("casetype1").value=val5;
document.getElementById("partyname1").value=val6;
document.getElementById("title1").value=val7;
document.getElementById("status1").value=val8;
document.getElementById("j_key1").value=val9;
document.getElementById("j_securityKey1").value=val10;
document.getElementById("frm").submit();

 }
 </script>
  
   <form action="" method="POST" target="_blank" id="frm">
    <input type="hidden" id="itemno1" name="itemno"  value=""/>
    <input type="hidden" id="applno1" name="applno" value=""/>
    <input type="hidden" id="courtno1" name="courtno" value=""/>
    <input type="hidden" id="caseno1" name="caseno" value="" />
    <input type="hidden" id="casetype1" name="casetype" value=""/>
    <input type="hidden" id="partyname1" name="partyname" value="">
    <input type="hidden" id="title1"name="title" value="">
    <input type="hidden" id="status1" name="status" value="">	
    <input type="hidden" id="j_key1"name="j_key" value=""> 
    <input type="hidden" id="j_securityKey1" name="j_securityKey" value="">
   
</form>
    <!-- Modal -->
<div id="iframemodal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg"  >

    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal_title">PDF</h4>
      </div>
      <div class="modal-body" id="modal_body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
  <?php 
  include '../infooter.php';
  ?>
  <script>
			function OpenDMSForm(step, filing_no, dms_type, misc_no) {
				document.getElementById("step").value = step;
				document.getElementById("filing_no").value = filing_no;
				document.getElementById("dms_type").value = dms_type;
				document.getElementById("misc_no").value = misc_no;
				document.getElementById("frm_dms").submit();
			}

			function previewCIS(filing_no) {
				document.getElementById("filling_no").value = filing_no;
				document.getElementById("previewCIS").submit();
			}

			function OpenPreviewReceipt(filing_no) {
				document.getElementById("fillingNu").value = filing_no;
				document.getElementById("previewReceipt").submit();
			}
		</script>

		<form action="https://uat-efiling.gstat.gov.in/dmsgstat/dashboard" method="POST" target="_blank" id="frm_dms">
			<input type="hidden" id="step" name="step" value="" />
			<input type="hidden" id="filing_no" name="filing_no" value="" />
			<input type="hidden" id="dms_type" name="dms_type" value="" />
			<input type="hidden" id="misc_no" name="misc_no" value="" />
		</form>
		<form action="https://uat-efiling.gstat.gov.in/efiling/CISCasePreview.drt" method="POST" target="_blank" id="previewCIS">
			<input type="hidden" id="filling_no" name="filingNo" value="" />
		</form>

		<form action="https://uat-efiling.gstat.gov.in/efiling/previewReceipt.drt" method="POST" target="_blank" id="previewReceipt">
			<input type="hidden" id="fillingNu" name="filingNo" value="" />
		</form>
  <?php } ?>

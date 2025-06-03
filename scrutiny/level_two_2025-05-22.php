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
$schema_id = $_SESSION['schema_idccc'];
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
	function display_filing_no($filing_no_display){
		$lastFour =  substr($filing_no_display,-4);
		$lastFive = substr($filing_no_display,-9,-4);
		$left = substr($filing_no_display,-16,-9);
		return $dis_fil_no = $left.'/<b>'.$lastFive.'/'.$lastFour.'</b>';

	}
	
	$ccase=$_REQUEST['ccase'];
	$filing_no_next=$_REQUEST['filing_no_next'];
	$hash1=htmlspecialchars(base64_decode($filing_no_next));
	$hash1 = explode("-", $hash1);
	$filing_no_fou=$hash1[0];
	
	$token_fou= $hash1[1];
	$subdoctype=$_REQUEST['subdoctype'];
	if($ccase=='2')
	{
		$miscellaneous_ref_no_post= $hash1[2];
	}

	if($ccase=='4')
	{
		$miscellaneous_ref_no_post= $hash1[2];
	}
	if($ccase=='3')
	{
		$ia_id=$hash1[2];

	}

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

	<style>
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
	<div style='text-align:center;'><a href="../index.php" class="text-danger font-weight-bold">
    << BACK << </a>
    </div>
	<div class="box">
	<div class="box-header with-border">
	<h4 class="box-title">Case Scurtiny &nbsp;&nbsp;
	Dairy No :
	<?php if($ccase=='3'){echo htmlspecialchars($ref_no_ia);}else{echo display_filing_no($filing_no_fou);}?>
	&nbsp;&nbsp;
	<?php 

	if($ccase=='1')
	{
		$st1=$db->prepare("select * from e_case_detail where filing_no=? and location_id=? order by filing_no DESC");
		$st1->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
		$st1->bindParam(2, $location_access, PDO::PARAM_STR);
	}
	if($ccase=='2')
	{

		$st1=$db->prepare("select * from $schemas.case_detail where filing_no=?  order by filing_no DESC");
		$st1->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
	}
	if($ccase=='4')
	{

		$st1=$db->prepare("select * from e_case_detail where filing_no=?  order by filing_no DESC");
		$st1->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
	}
	if($ccase=='3')
	{
		$st1=$db->prepare("select * from e_case_detail where filing_no=? and location_id=? order by filing_no DESC");
		$st1->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
		$st1->bindParam(2, $location_access, PDO::PARAM_STR);
	}
	$st1->execute();
	while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{

		$filing_no = htmlspecialchars($row['filing_no']);
		$pet_name = htmlspecialchars($row['pet_name']);
		$res_name = htmlspecialchars($row['res_name']);
		$dt_of_filing = htmlspecialchars($row['dt_of_filing']);
		$case_type=htmlspecialchars($row['case_type_nclat']);
		$e_reference_no =  htmlspecialchars($row['e_reference_no']);
		$case_no=$row['case_no'];
		$location_id=$row['location_id'];
		$act_id=$row['act_id'];
		$boofficefound = $row['boofficefound'];
		if($boofficefound == '1')
			$juridection_text = 'Wrong jurisdiction selection';

		$defect_docs = $row['defect_docs'];
		$defect_docs = explode(',', $defect_docs);
	}
	$stqq = $db->prepare("select act_name from master_act where act_id=?");
	$stqq->bindParam(1, $act_id, PDO::PARAM_INT);
	$stqq->execute();
	$act_name_all = $stqq->fetchColumn();

	$E_party_flag1='P';
	$E_party_serial_no1='1';

	    //echo $tt = "select * from e_cases_party where filing_no='$filing_no' and party_flag='$E_party_flag1' and party_serial_no='$E_party_serial_no1'";

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
		<font color="#cb9d2b">
		<?php if($E_nameP!=''){

			$pet_name= htmlspecialchars_decode($E_nameP,ENT_NOQUOTES);
			$res_name= htmlspecialchars_decode($E_nameR,ENT_NOQUOTES);


			echo strtoupper($pet_name);} ; ?></font>
			<font color="#cb9d2b">&nbsp; <span style="color:#191f87;">Vs.</span> &nbsp;</font>

			<font color="#cb9d2b">
			<?php if($res_name !=''){echo strtoupper($res_name);} ; ?></font>
			</h4>


			<?php
			if(!empty($juridection_text)){ ?>
				<div style="
				background: #fff6ce;
				margin: 30px 40px;
				padding: 10px 20px;
				border-radius: 10px;
				border: 2px solid #dd4b39;
				color: #000;
				"> <h4 style="
				margin: 0 0 5px;
				font-size: 17px;
				font-weight: bold;
				color: #e92f18;
				"> <?php echo  $juridection_text; ?></h4></div>
			<?php } ?>



			<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
			title="Collapse">
			<i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
			title="Remove">
			<i class="fa fa-times"></i></button>
			</div>
			</div>
			<div class="box-body">

			<div class="form-group">
			<div class="row">

			<script>
			function defect_submit() {
                                    
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

				with(document.form2) {

					var current_status = $("#in_searchby").val();
                                        //current_status = $.trim(current_status);
					if (current_status == '1') {
						var cause_number = $("#cause_no").val();
						if (cause_number == '') {
							alert("please select any defect in Option");
							return false;
						}
						if(cause_number.includes('6')){
							var selected_docs = [];
							$('#scrutiny_docs input:checked').each(function() {
							    selected_docs.push($(this).attr('name'));
							});
							if (typeof selected_docs === 'undefined' || selected_docs.length === 0) {
							    alert("please select as least one document");
			                    return false;
							}
						}
					}

					var validformat = /^\d{2}\/\d{2}\/\d{4}$/
					if (!validformat.test(notification_date.value)) {
						alert("Invalid Date Format. Correct Date Format (dd/mm/yyyy)")
						return false;
					}
					if (form_status.value == 'F') {
						if (auto_manual.options[auto_manual.selectedIndex].value == "") {
							alert("Please Select AUTMOATIC/MANUAL  ");
							auto_manual.focus();
							return false;
						}
					}
					if (searchby.options[searchby.selectedIndex].value == "0") {
						alert("Please Select Defect/ Defect Free  ");
						searchby.focus();
						return false;
					}

					var status1 = "";
                                        //alert('self submit');	
					var tnl = document.getElementsByName("status");
					for (i = 0; i < tnl.length; i++) {

						var val = tnl[i].value;
						var status1 = status1 + val + ','
					}
					if (!document.getElementById('agree').checked) {
						alert('You must agree to the terms first.');
                                            //agree.focus();
						return false;
					}


					action = "level_two_action.php?test=" + status1 + "&notification_date=" +
					notification_date.value;
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


			function myFunction() {

				var tnl = document.getElementsByName("status");

				var val1 = ""

				for (i = 0; i < tnl.length; i++) {

					var val = tnl[i].value;
					if (val == 'NO') {
						val1 = 'NO';
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
				if (val1 == "") {
					val1 = 'YES';
					$(".rr").hide();
					$("#scrutiny_docs").hide();
				}

				if (val1 == 'YES') {
					$("#auto_man").show();
				} else {
					$("#auto_man").hide();
				}

				if (window.XMLHttpRequest) {

					xmlhttp = new XMLHttpRequest();
				} else {

					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("befornotification1").innerHTML = this
						.responseText;
					}
				};
				xmlhttp.open("GET", "notificationdate.php?val=" + val1, true);
				xmlhttp.send();


				document.getElementById("befornotification1").style.display = 'block';
				document.getElementById("befornotification").style.display = 'none';

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

			button.accordion.active,
			button.accordion:hover {
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
					if (panel.style.maxHeight) {
						panel.style.maxHeight = null;
					} else {
						panel.style.maxHeight = panel.scrollHeight + "px";
					}
				}
			}

			function draft_checklist() {

                                    //for gen
				var current_status = $("#in_searchby").val();
                                    //current_status = $.trim(current_status);
				if (current_status == '1') {
					var cause_number = $("#cause_no").val();
					if (cause_number == '') {
						alert("please select any defect in Option");
						return false;
					}
				}

				var comment = [];
				$.each($(".comment"), function() {
					comment.push($(this).val());
				});
				var cause_no = [];
				$.each($("#cause_no"), function() {
					cause_no.push($(this).val());
				});
				console.log(cause_no);
				var status = [];
				$.each($(".statuscheck option:selected"), function() {
					status.push($(this).val());
				});

				var id_checklist = $('input[name="id_check_draft[]"]').map(function() {
					return this.value
				}).get()

				console.log(id_checklist);
				var comment_other = [];
				$.each($(".comment_other"), function() {
					comment_other.push($(this).val());
				});
				console.log(comment_other);
                                    //for others
				var statusother = [];
				$.each($(".statuscheckother option:selected"), function() {
					statusother.push($(this).val());
				});
				console.log(statusother);

				var id_checklist_other = $('input[name="id_check_draft_other[]"]').map(function() {
					return this.value
				}).get()
				console.log(id_checklist_other);

				var obj_sub_name = $('#draft_id').text();

				if (obj_sub_name == 'First Motion' || obj_sub_name == 'Second Motion' ||
					obj_sub_name == 'IBC Act') {


					obj_sub_code = 1;
			} else {
				obj_sub_code = 0;
			}

			var status = JSON.stringify(status);
			var checklist = JSON.stringify(id_checklist);
			var comment = JSON.stringify(comment);
			var comment_other = JSON.stringify(comment_other);
			var statusother = JSON.stringify(statusother);
			var cause_no = JSON.stringify(cause_no);
			var id_checklist_other = JSON.stringify(id_checklist_other);

			var obj_sub_code = JSON.stringify(obj_sub_code);
			var filing_no = JSON.stringify(filing_no);

			var filing_no = document.getElementById("draft_filing_no").value;
			var scrutiny_level_value = document.getElementById("scrutiny_level_value").value;

                                    //var filing_no = filing_no.toString();
			var user_id = document.getElementById("user_id").value;



			var miscellaneous_ref_no_post = document.getElementById("miscellaneous_ref_no_post")
			.value;
			var miscellaneous_ref_no_post = JSON.stringify(miscellaneous_ref_no_post);



			var c_case = JSON.stringify(c_case);

			var ccase = document.getElementById("ccase").value;

			var subdoctype = JSON.stringify(subdoctype);

			var subdoctype = document.getElementById("subdoctype").value;

			var request = $.ajax({
				type: "POST",
				url: "draft_checklist_save.php",
				data: {
					status: status,
					checklist: checklist,
					filing_no: filing_no,
					user_id: user_id,
					obj_sub_code: obj_sub_code,
					statusother: statusother,
					id_checklist_other: id_checklist_other,
					scrutiny_level_value: scrutiny_level_value,
					miscellaneous_ref_no_post: miscellaneous_ref_no_post,
					ccase: ccase,
					subdoctype: subdoctype,
					comment: comment,
					comment_other: comment_other,
					cause_no: cause_no
				},
				cache: false,
			});

			request.done(function(data) {
				console.log(data);
				$('#ajaxButton').html(data);
				$(".alert").show();
				$(".alert").fadeOut(5000, function() {
                                            // Animation complete.
				});
			});

			request.fail(function(jqXHR, textStatus) {
				console.log('Sorry: ' + textStatus);
			});

		}
		</script>
		<?php 
		$remove_defact = sha1( uniqid('auth', true) );
		$_SESSION['remove_defact'] = $remove_defact;


		?>

		<form name="form2" method="post" action="level_two_action.php">
		<input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2);?>" />
		<input type="hidden" name="filing_no_next"
		value="<?php echo htmlspecialchars($filing_no_next);?>" />
		<table width="100%">

		<tr>
		<td colspan="6">
		<?php 
		$tokenno=$filing_no_fou;	  
		if($cccase=='1')
		{
			$ll='2';
			$ll1='N';
			$stqq = $db->prepare("select count(filing_no) from $schemas.scrutiny where filing_no=? and defects =? and level_level =?");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_INT);
			$stqq->bindParam(2, $ll1, PDO::PARAM_INT);
			$stqq->bindParam(3, $ll, PDO::PARAM_INT);
			$stqq->execute();
			$filing_no_foundxcc = $stqq->fetchColumn();


			if($filing_no_foundxcc > '0')
			{

				echo "<font color='red'><br></br><b>Scrutiny Already Completed !!!</b></font>";
	  //	die();

			}
		}

		?>
		</td>
		</tr>
		</table>

		<?php

		$ccase=$_REQUEST['ccase'];
		if($ccase==2)
		{

			$form_status="C";
		}

		if($ccase==4)
		{

			$form_status="R";
		}
		elseif($ccase==1)
		{	
			$form_status="F";
		}elseif($ccase==3){
			$form_status="I";	
		}
		?>
		<input type="hidden" name="form_status"
		value="<?php echo htmlspecialchars(htmlentities($form_status));?>" />
		<?php 
		if($form_status=="F")
		{
			$ll='1';

		}
		if($form_status=="C")
		{
			$ll='11';
		}
		if($form_status=="R")
		{
			$ll='4';
		}
		if($form_status=="I")
		{
			$ll='111';
		}

		if($form_status=="F")
		{
			$stqq = $db->prepare("select filing_no from $schemas.scrutiny where filing_no=? and level_level=?");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
			$stqq->bindParam(2, $ll, PDO::PARAM_STR);
			$stqq->execute();
			$filing_norevari_var = $stqq->fetchColumn();
		}
		if($form_status=="I")
		{
 //$sql="select filing_no from $schemas.scrutiny_ia where filing_no='$tokenno' and level_level='$ll'";
			$stqq = $db->prepare("select filing_no from $schemas.scrutiny_ia where filing_no=? and level_level=?");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
			$stqq->bindParam(2, $ll, PDO::PARAM_STR);
			$stqq->execute();
			$filing_norevari_var = $stqq->fetchColumn();
		}
		if($form_status=="C")
		{
			$sql="select filing_no from $schemas.scrutiny_doc where filing_no='$tokenno' and level_level='$ll'";
			$stqq = $db->prepare("select filing_no from $schemas.scrutiny_doc where filing_no=? and level_level=?");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
			$stqq->bindParam(2, $ll, PDO::PARAM_STR);
			$stqq->execute();
			$filing_norevari_var = $stqq->fetchColumn();
		}
		if($form_status=="R")
		{

			if($subdoctype =='17')
			{
					//$subdocname='Report';
				$form_type='R';

			}										


			if($subdoctype =='33')
			{
					//$subdocname='Order';
				$form_type='O';

			}	 

	 ///$form_type='R';
			$stqq = $db->prepare("select filing_no from $schemas.scrutiny_doc where filing_no=? and level_level=? and miscellaneous_ref_no=? and form_type=?");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
			$stqq->bindParam(2, $ll, PDO::PARAM_STR);
			$stqq->bindParam(3, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			$stqq->bindParam(4, $form_type, PDO::PARAM_STR);
			$stqq->execute();
			$filing_norevari_var = $stqq->fetchColumn();
		}


		if($form_status=="F")
		{
			$stqq = $db->prepare("select scrutinu_comp,defects,notification_date from $schemas.scrutiny where filing_no=? and level_level=?");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
			$stqq->bindParam(2, $ll, PDO::PARAM_STR);
			$stqq->execute();
			while ($row = $stqq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$scrutinu_comp_q = htmlspecialchars($row['scrutinu_comp']);
				$defects_q = htmlspecialchars($row['defects']);
				$notification_datez=$row['notification_date'];
			}
		} 

		if($form_status=="I")
		{

			$stqq = $db->prepare("select scrutinu_comp,defects,notification_date from $schemas.scrutiny_ia where filing_no=? and level_level=?");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
			$stqq->bindParam(2, $ll, PDO::PARAM_STR);
			$stqq->execute();
			while ($row = $stqq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$scrutinu_comp_q = htmlspecialchars($row['scrutinu_comp']);
				$defects_q = htmlspecialchars($row['defects']);
				$notification_datez=$row['notification_date'];
			}

		}  

		if($form_status=="C")
		{

			$stqq = $db->prepare("select scrutinu_comp,defects,notification_date from $schemas.scrutiny_doc where filing_no=? and level_level=? and miscellaneous_ref_no=?");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
			$stqq->bindParam(2, $ll, PDO::PARAM_STR);
			$stqq->bindParam(3, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			$stqq->execute();
			while ($row = $stqq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$scrutinu_comp_q = htmlspecialchars($row['scrutinu_comp']);
				$defects_q = htmlspecialchars($row['defects']);
				$notification_datez=$row['notification_date'];
			}

		} 

		if($form_status=="R")
		{

			$stqq = $db->prepare("select scrutinu_comp,defects,notification_date from $schemas.scrutiny_doc where filing_no=? and level_level=? and miscellaneous_ref_no=? and form_type=?");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
			$stqq->bindParam(2, $ll, PDO::PARAM_STR);
			$stqq->bindParam(3, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			$stqq->bindParam(4, $form_type, PDO::PARAM_STR);
			$stqq->execute();
			while ($row = $stqq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$scrutinu_comp_q = htmlspecialchars($row['scrutinu_comp']);
				$defects_q = htmlspecialchars($row['defects']);
				$notification_datez=$row['notification_date'];
			}

		} 



		if($filing_norevari_var !='')
		{

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

			.tbl-accordion .tbl-accordion-nested td,
			.tbl-accordion .tbl-accordion-nested th {
				padding: 10px;
				border-bottom: 1px solid #d9d9d9;
			}

			.tbl-accordion .tbl-accordion-nested .tbl-accordion-section {
				background: #333;
				color: #fff;
				cursor: pointer;
			}
			</style>

			<script>
			$('.tbl-accordion-nested').each(function() {
				var thead = $(this).find('thead');
				var tbody = $(this).find('tbody');

				tbody.hide();
				thead.click(function() {
					tbody.slideToggle();
				})
			})
			</script>

			<table class="tbl-accordion table table-bordered">
			<tbody>
                        <tr class="bg-primary">
                        <th colspan="3" style="padding: 8px;">Remark By Nodal Officer</th>
                        </tr>
                        <tr>
                        <td colspan="3" style="padding: 8px;">
                        <?php
                        // Prepare and execute the query
                        $NodalResponse = $db->prepare("SELECT remarks FROM gst_ecase_validation_for_apl0204 WHERE filingno = ?");
                        $NodalResponse->bindParam(1, $filing_no, PDO::PARAM_STR);
                        $NodalResponse->execute();
                        
                        // Fetch the single column value
                        $remarks = $NodalResponse->fetchColumn();
                        
                        // Output remarks if available
                        if ($remarks) {
                        echo htmlspecialchars($remarks, ENT_QUOTES, 'UTF-8');
                        } else {
                        echo "No Remark Found";
                        }
                        ?>
                        </td>
                        </tr>
			<tr>
			<td colspan="3">
			<table cellpadding="0" cellspacing="0" border="1"
			class="tbl-accordion-nested">
			<thead>
			<tr>
			<td><b> Case Detail </b></td>

			<td><b>Documents </b></td>

			<td><b> Payment Details </b></td>
			</tr>

			<tr>
			<!--<td colspan="1" class="tbl-accordion-section">Fresh</td>-->

			<?php 

			$ccase=$_REQUEST['ccase'];

			if($ccase==2)
			{
				$display='1';
				$scrutiny='0';
				$form_status="C";
			}


			if($ccase==4)
			{
				$display='1';
				$scrutiny='0';
				$form_status="R";
			}
			elseif($ccase==1)
			{
				$display='1';
				$scrutiny='0';
				$form_status="F";
			}elseif($ccase==3)
			{
				$display='1';
				$scrutiny='0';
				$form_status="I";
			}
			?>
			<input type="hidden" name="form_status"
			value="<?php echo htmlspecialchars(htmlentities($form_status));?>" />
			<?php 

			$st=$db->prepare("select *  from document_upload where filing_no=? and scrutiny=? and display=?  ");
			$st->bindParam(1, $tokenno, PDO::PARAM_STR);
			$st->bindParam(2, $scrutiny, PDO::PARAM_STR);
			$st->bindParam(3, $display, PDO::PARAM_STR);
			$st->execute();
			while ($rowa = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$fil_no=$rowa['filing_no'];
				$sub_doc_type=$rowa['subdoctype'];


				$document_filed_date=$rowa['document_filed_date'];
				$path =$rowa['fileupload'];    
				$returnfilename =$rowa['returnfilename']; 

				list($returnfilename,$ext)=explode('.',$returnfilename);
				$returnfilename1=$returnfilename;		 



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
		   /*
		   $casenosql=$db->prepare("select case_type, case_no, case_year, location_code from $schemas.case_detail where filing_no='$filing_no'");
                 $casenosql->execute(); 
				 $row = $casenosql->fetch();
				 $case_no = htmlspecialchars($row['case_no']);
				 $case_no = ltrim($case_no,0);
				 $casetype = htmlspecialchars($row['case_type']);
				 $locode = htmlspecialchars($row['location_code']);
				 $case_year = htmlspecialchars($row['case_year']);
				
				 $casetypesql = $db->prepare("select case_type_desc from case_type where id = '$casetype'");
                 $casetypesql->execute();
                 $case_type_short_name=$casetypesql->fetchColumn();
				 $case_type_short_name = strtoupper($case_type_short_name);
				
				 $lcodesql ="select short_name from $schemas.bench_location where bench_location_code ='$locode'";
                 $lcodesql=$db->prepare($lcodesql);
                 $lcodesql->execute();
                 $lcodename = $lcodesql->fetchColumn();
				 
				 $case_no_final = $case_type_short_name.'/'.$case_no.'('.$lcodename.')'.$case_year;
				*/
			?>															<td>
			<a onclick="previewCIS('<?php echo $filing_no; ?>');"
			style="cursor: pointer">

			<font color="#900C3F" size="3">&nbsp;&nbsp;
			&nbsp;&nbsp;View
			</a>
			<!-- <a target="_blank" href="https://efiling.nclat.gov.in/previewCIS.drt?filingNo=<?php //echo $filing_no ?>">
			<font color="#900C3F" size="3">&nbsp;&nbsp;View
			</a> -->
			</td>

			<td>


			<!-- step, filing_no, cause_title, case_no, court_no, item_no -->
			<a onclick="OpenDMSForm('3','<?php echo $filing_no; ?>','fresh','')"
			style="cursor: pointer">

			<font color="#900C3F" size="3">&nbsp;&nbsp;
			&nbsp;&nbsp;View
			</a>

			</td>

			<!-- <td>
			<a target="_blank" href="https://efiling.nclat.gov.in/previewReceipt.drt?filingNo=<?php echo $filing_no ?>">
			<font color="#900C3F" size="3">&nbsp;&nbsp;View
			</a>
			</td> -->

			<td>
			<!-- step, filing_no, cause_title, case_no, court_no, item_no -->
			<a onclick="OpenPreviewReceipt('<?php echo $filing_no; ?>')"
			style="cursor: pointer">
			<font color="#900C3F" size="3">
			View
			</a>
			</td>
			</tr>


			<?php
			if($ccase == '2') {
						//echo "select *  from document_upload where filing_no='$filing_no_fou' and scrutiny='$scrutiny' and display='$display'  ";
				$get_mis_no_doc=$db->prepare("select *  from document_upload where filing_no=? and miscellaneous_ref_no=? and scrutiny=? and display=?  ");
				$get_mis_no_doc->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
				$get_mis_no_doc->bindParam(2, $miscellaneous_ref_no_post, PDO::PARAM_STR);
				$get_mis_no_doc->bindParam(3, $scrutiny, PDO::PARAM_STR);
				$get_mis_no_doc->bindParam(4, $display, PDO::PARAM_STR);
				$get_mis_no_doc->execute();
				$doc_c = 1;
				while ($row_mnd = $get_mis_no_doc->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
				{
					$miscellaneous_ref_no =$row_mnd['miscellaneous_ref_no'];
					$doc_id=$row_mnd['documentuploadmodelid'];
					if($miscellaneous_ref_no){?>
						<tr>
						<td><?php echo $doc_c."). ".$miscellaneous_ref_no; ?></td>
						</tr>
						<?php
						$doc_c++;
					}else{?>
						<tr>
						<td><?php echo "Not Found";?></td>
						</tr>
						<?php } 
					}
				}
				?>


				<?php
				if($ccase == '4') {

					if($subdoctype =='17')
					{
						$subdocname='Report';

					}
					if($subdoctype =='33')
					{
						$subdocname='Order';

					}



						//echo "select *  from document_upload where filing_no='$filing_no_fou' and scrutiny='$scrutiny' and display='$display'  ";
					$get_mis_no_doc=$db->prepare("select *  from document_upload where filing_no=? and miscellaneous_ref_no=? and scrutiny=? and display=? and subdoctype=? and party_type IN (select party_flag from e_master_govt_body) ");
					$get_mis_no_doc->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
					$get_mis_no_doc->bindParam(2, $miscellaneous_ref_no_post, PDO::PARAM_STR);
					$get_mis_no_doc->bindParam(3, $scrutiny, PDO::PARAM_STR);
					$get_mis_no_doc->bindParam(4, $display, PDO::PARAM_STR);
					$get_mis_no_doc->bindParam(5, $subdoctype, PDO::PARAM_STR);
					$get_mis_no_doc->execute();
					$doc_c = 1;
					while ($row_mnd = $get_mis_no_doc->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
					{
						$miscellaneous_ref_no =$row_mnd['miscellaneous_ref_no'];
						$report_party_type =$row_mnd['party_type'];
						$doc_id=$row_mnd['documentuploadmodelid'];
						if($miscellaneous_ref_no){?>
							<tr>
							<td><?php echo $miscellaneous_ref_no." (".$subdocname.")"; ?>
							</td>
							</tr>
							<?php
							$doc_c++;
						}else{?>
							<tr>
							<td><?php echo "Not Found";?></td>
							</tr>
							<?php } 
						}
					}
					?>



					</tbody>
					</table>







					</tbody>
					</table>
					</td>
					</tr>
					</table>
					</td>
					</tr>
					</div>
					</div>
					</div>
					<div class="box-footer">
					<style>
					.greenText {
						background-color: green;
					}

					.blueText {
						background-color: blue;
					}
					</style>
					<div class="main">
					<div class="accordion">
					<?php

					$sth = $db->prepare("select remarks from check_list_enter where e_reference_no = ? ");
					$sth->bindParam(1, $e_reference_no, PDO::PARAM_STR);
					$sth->execute();
					$user_remarks = $sth->fetchColumn();
					$user_remarks_array = explode('&', $user_remarks);

					?>
					<div class="accordion-section">
					<a class="accordion-section-title"
					href="#accordion-1"><?php echo htmlspecialchars("General");?></a>
					<div id="accordion-1" class="table table-bordered accordion-section-content">

					<table class="table table-bordered">
					<tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
					<td width="3%">Sr. No</td>
					<td width="67%">Description</td>
					<td width="16%">As per appellant</td>
					<td width="16%">Comments of appellant, if any</td>
					<td width="6%">As per scrutiny clerk</td>
					<td width="16%">Comments of scrutiny clerk, if any</td>
					<td width="6%">As per registrar</td>
					<td width="16%">Comments of registrar clerk, if any</td>

					</tr>
					<tr>
					<td colspan="12">

                                                    <?php //$status=$_REQUEST['status'];

                                                    ?>

                                                    <?php 


                                                    if($form_status=="C")
                                                    {
                                                    	$ll='11';
                                                    	$lls='0';
                                                    	$flag="";
                                                    }
                                                    if($form_status=="R")
                                                    {
                                                    	$ll='4';
                                                    	$lls='0';
                                                    	$flag="";
                                                    }
                                                    if($form_status=="F")
                                                    {
                                                    	$ll='1';
                                                    	$lls='0';
                                                    	$flag="";
                                                    	$flag_reg="";

                                                    }
                                                    if($form_status=="I")
                                                    {
                                                    	$ll='111';
                                                    	$lls='0';
                                                    }


                                                    if($form_status=="C"){


                                                    	$chk_old_doc = $db->prepare("select filing_no from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and objection_sub_code=? and 	miscellaneous_ref_no=?");
                                                    	$chk_old_doc->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    	$chk_old_doc->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    	$chk_old_doc->bindParam(3, $ll, PDO::PARAM_STR);
                                                    	$chk_old_doc->bindParam(4, $lls, PDO::PARAM_STR);
                                                    	$chk_old_doc->bindParam(5, $miscellaneous_ref_no_post, PDO::PARAM_STR);
                                                    	$chk_old_doc->execute();
                                                    	$filing_no_chk_old_doc = $chk_old_doc->fetchColumn();

                                                    	if($filing_no_chk_old_doc=='' || $filing_no_chk_old_doc==NULL){



                                                    		$stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and
                                                    			objection_sub_code=? order by cast(objection_code as integer) ASC ");
                                                    		$stv->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    		$stv->bindParam(3, $ll, PDO::PARAM_STR);
                                                    		$stv->bindParam(4, $lls, PDO::PARAM_STR);

                                                    	}else{


                                                    		$stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and
                                                    			objection_sub_code=? and 	miscellaneous_ref_no=? order by cast(objection_code as integer) ASC ");
                                                    		$stv->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    		$stv->bindParam(3, $ll, PDO::PARAM_STR);
                                                    		$stv->bindParam(4, $lls, PDO::PARAM_STR);
                                                    		$stv->bindParam(5, $miscellaneous_ref_no_post, PDO::PARAM_STR);
                                                    	}
                                                    }
                                                    else{

                                                    	if($form_status=="R")
                                                    	{


                                                    		$stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and
                                                    			objection_sub_code=?  and miscellaneous_ref_no=? and form_type=? order by cast(objection_code as integer) ASC ");
                                                    		$stv->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    		$stv->bindParam(3, $ll, PDO::PARAM_STR);
                                                    		$stv->bindParam(4, $lls, PDO::PARAM_STR); 
                                                    		$stv->bindParam(5, $miscellaneous_ref_no_post, PDO::PARAM_STR);
                                                    		$stv->bindParam(6, $form_type, PDO::PARAM_STR); 




                                                    	}
                                                    }
                                                    if($form_status!='C' && $form_status!='R')
                                                    {

 //$ee = "select * from $schemas.objection_details where filing_no= '$filing_no' and entry_dt='$notification_datez' and level_level='$ll' and
///objection_sub_code='$lls' order by cast(objection_code as integer) ASC ";	
                                                    	$stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and date(entry_date)=? and level_level=? and
                                                    		objection_sub_code=? order by cast(objection_code as integer) ASC ");
                                                    	$stv->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    	$stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    	$stv->bindParam(3, $ll, PDO::PARAM_STR);
                                                    	$stv->bindParam(4, $lls, PDO::PARAM_STR);
                                                    }

                                                    $stv->execute();
 //echo "pppp";
                                                    $i=0;
                                                    $j=1;
 //$ttt = 0;
                                                    while ($rowcc = $stv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                                    {

 //echo $ttt++;


                                                    	$objection_codeobbj=$rowcc['objection_code'];

                                                    	$cause_no = $rowcc['scrutiny_correction'];

                                                    	$explode_cause_no = explode(',',$cause_no);
                                                    	$objection_codeststus=trim($rowcc['status']);
                                                    	$objection_codeststus_reg=trim($rowcc['status_registrar']);

                                                    	if($objection_codeststus=='NO')
                                                    	{
                                                    		$flag='NO';
                                                    	}
                                                    	if($objection_codeststus=='NA')
                                                    	{
                                                    		$flag='NA';
                                                    	}
                                                    	if($objection_codeststus_reg=='NO')
                                                    	{
                                                    		$flag_reg='NO';
                                                    	}
                                                    	elseif($objection_codeststus_reg=='NA')
                                                    	{
                                                    		$flag_reg='NA';
                                                    	}else{
                                                    		$flag_reg='YES';
                                                    	}
                                                    	$comments=$rowcc['doc_comments'];
                                                    	$comment_registrar=$rowcc['comment_registrar'];
 //echo "iiii";											
                                                    	if($case_type == '1')
                                                    		$check_list_table = 'check_list_local';
                                                    	else
                                                    		$check_list_table = 'check_list_local_ia';

                                                    	$sth=$db->prepare("select * from check_list_local where id=? order by id ASC ");
 	//$sth->bindParam(1, $location_access, PDO::PARAM_STR);
                                                    	$sth->bindParam(1, $objection_codeobbj, PDO::PARAM_STR);
                                                    	$sth->execute();
 //echo "wwww";
                                                    	while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                                    	{


                                                    		$id_check=$rowa['id'];
                                                    		$check_list=$rowa['check_list'];
                                                    	}
                                                    	$obj_sub_code = 0;


                                                    	$level_level =1;


                                                    	$draft_status='CM0';

			//check draft record exist or not



                                                    	if($form_status=='R')
                                                    	{

                                                    		$check_record_exist = $db->prepare("select display from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and level_level=? and form_type=? and status!=?");
                                                    		$check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(2, $objection_codeobbj, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(3, $obj_sub_code, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(4, $userid, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(5, $level_level, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(6, $form_type, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(7, $draft_status, PDO::PARAM_STR);
                                                    	}

                                                    	else
                                                    	{
                                                    		$check_record_exist = $db->prepare("select display from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and level_level=?  and status!=?");
                                                    		$check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(2, $objection_codeobbj, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(3, $obj_sub_code, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(4, $userid, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(5, $level_level, PDO::PARAM_STR);
						//$check_record_exist->bindParam(6, $form_type, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(6, $draft_status, PDO::PARAM_STR); 



                                                    	}

                                                    	$check_record_exist->execute();
                                                    	$display_for_chk= $check_record_exist->fetchColumn();

                                                    	if($display_for_chk){



                                                    		if($form_status=='R')
                                                    		{
                                                    			$sql="select status,comment,level_level,scrutiny_correction from $schemas.draft_objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code'and user_id='$userid' and level_level='$level_level' and form_type='$form_type' and status!='$draft_status'";
                                                    		}
                                                    		if($form_status!='R')
                                                    		{
                                                    			$sql="select status,comment,level_level,filing_no,scrutiny_correction from $schemas.draft_objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code'and user_id='$userid' and level_level='$level_level'  and status!='$draft_status'";
                                                    		}
                                                    		$stq=$db->prepare($sql);
                                                    		$stq->execute();
                                                    		while ($rowa = $stq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                                    		{
                                                    			$objection_fil=$rowa['filing_no'];
                                                    			$objection_codeststus_reg=$rowa['status'];
                                                    			$cause_no = $rowa['scrutiny_correction'];
                                                    			$explode_cause_no = explode(',',$cause_no);
                                                    			$objection_codeststus=trim($objection_codeststus);
                                                    			$comment_registrar=$rowa['comment'];

                                                    			$level=$rowa['level_level'];
                                                    		}
                                                    	}





                                                    	?>
                                                    	<tr>

                                                    	<td width="5%">

                                                    	<?php echo htmlspecialchars($id_check);?>

                                                    	</td>


                                                    	<td width="60%">
                                                    	<font color=" #1c2833 "><?php echo htmlspecialchars($check_list);?>
                                                    	</font>
                                                    	</td>

                                                    	<?php $user_checklist_point = $user_remarks_array[$i];
                                                    	$user_checklist_point = explode(',', $user_checklist_point);
                                                    	$user_checklist_remark = $user_checklist_point[1];
                                                    	$user_checklist_serial_status = explode('-', $user_checklist_point[0]);
                                                    	$user_checklist_serail_no = $user_checklist_serial_status[0];
                                                    	$user_checklist_status = $user_checklist_serial_status[1];
                                                    	$user_status = ($user_checklist_status == 1)?'YES':(($user_checklist_status == 2)?'NO':'NA');
                                                    	?>

                                                    	<td width="7%">



                                                    	

                                                    	<?php $user_status = ($user_status != '' || $user_status != 'NA') ? $user_status : "YES"; ?>
                                                    	<p><?php echo $user_status; ?></p>
                                                    	
                                                    	</td>

                                                    	<td width="20%">
                                                    	<?php
                                                    	$user_checklist_remark_show = '';
                                                    	if ($user_checklist_remark != '') {
                                                    		$user_checklist_remark_show = $user_checklist_remark;
                                                    	}

                                                    	?>
                                                    	<p><?php echo $user_checklist_remark_show; ?></p>
                                                    	
                                                    	</td>
                                                    	<?php 
  /*if($status[$j]=='NO')
  {
  	$dhiraj ="OK";
  }*/

                                                    	?>

                                                    	<td width="7%">

                                                    	<?php

 //$status[$j] = isset($_REQUEST['status'][$j]) ? $_REQUEST['status'][$j] : $objection_codeststus; ?>
                                                    	<?php echo $objection_codeststus; ?>

                                                    		<td width="20%">
                                                    		
                                                    		<p><?php echo $comments; ?></p>
 
                                                    		</td>

                                                    		<td width="7%">

                                                    		<?php $objection_codeststus_reg = (!empty($objection_codeststus_reg) && $objection_codeststus_reg != 'NA') ? $objection_codeststus_reg : "YES"; ?>

                                                    		<select name="status" class="statuscheck" data-id="<?php echo $id_check; ?>" id="objection_status_<?php echo $id_check; ?>" style="
                                                    		background-color: silver;
                                                    		color: #000000;
                                                    		padding: 7px 7px;
                                                    		margin: 2px 0;
                                                    		border: none;
                                                    		border-radius: 4px;
                                                    		cursor: pointer;" onchange="myFunction()">

                                                    		<option value="NO"
                                                    		<?php  if($objection_codeststus_reg=='NO') {echo 'selected'; }?>>
                                                    		NO</option>
                                                    		<option value="YES"
                                                    		<?php  if($objection_codeststus_reg=='YES'){ echo 'selected';} ?>>
                                                    		YES</option>
                                                    		<?php if($id_check == '4' || $id_check == '15') { ?>
                                                    			<option value="NA"
                                                    			<?php  if($objection_codeststus_reg=='NA') {echo 'selected'; }?>>
                                                    			NA</option>
                                                    			<?php } ?>

                                                    			</select>
                                                    			<input type="hidden" id="id_check_draft"
                                                    			value='<?php echo htmlspecialchars($id_check);?>'
                                                    			name="id_check_draft[]">

                                                    			<td width="20%">
                                                    			<?php

                                                    			if($comment_registrar!='' || $objection_fil!='')
                                                    			{


                                                    				$comments_reg=$comment_registrar;
                                                    			}
 //$comment[$j] = isset($_REQUEST['comment'][$j]) ? $_REQUEST['comment'][$j] : $comment; ?>
                                                    			<textarea name="comment[]" onChange="makeUppercase(this)" cols="30"
                                                    			rows="1" style="background-color: silver;" class="comment" data-id="<?php echo $id_check; ?>" id="comment_<?php echo $id_check; ?>"><?php  echo htmlspecialchars($comments_reg);?></textarea>
                                                    			</td>

                                                    			<?php 
  //echo $j;
                                                    			?>
                                                    			</tr>

                                                    			<input type="hidden" name="id_check[]" maxlength="3" readonly="readonly"
                                                    			value="<?php echo htmlspecialchars(htmlentities($id_check.','.'gen'));?>"
                                                    			size="2" />

                                                    			<input type="hidden" name="filing_no" id="draft_filing_no"
                                                    			value="<?php echo htmlspecialchars(htmlentities($filing_no));?>" />
                                                    			<input type="hidden" name="scrutiny_level_value" id="scrutiny_level_value"
                                                    			value="1" />
                                                    			<input type="hidden" name="filing_no"
                                                    			value="<?php echo htmlspecialchars(htmlentities($filing_no));?>" />
                                                    			<input type="hidden" name="ref_no_ia"
                                                    			value="<?php echo htmlspecialchars(htmlentities($ref_no_ia));?>" />
                                                    			<input type="hidden" name="ia_id"
                                                    			value="<?php echo htmlspecialchars(htmlentities($ia_id));?>" />
                                                    			<input type="hidden" name="user_id" id="user_id"
                                                    			value="<?php echo htmlspecialchars(htmlentities($userid));?>" />
                                                    			<input type="hidden" name="ccase" id="ccase"
                                                    			value="<?php echo htmlspecialchars(htmlentities($ccase));?>" />
                                                    			<input type="hidden" name="subdoctype" id="subdoctype"
                                                    			value="<?php echo htmlspecialchars(htmlentities($subdoctype));?>" />
                                                    			<input type="hidden" name="miscellaneous_ref_no_post"
                                                    			id="miscellaneous_ref_no_post"
                                                    			value="<?php echo htmlspecialchars(htmlentities($miscellaneous_ref_no_post));?>" />
                                                    			<input type="hidden" name="report_party_type" id="report_party_type"
                                                    			value="<?php echo htmlspecialchars(htmlentities($report_party_type));?>" />
                                                    			<?php
                                                    			$j++;
                                                    			$i++;
//}

                                                    		}
                                                    		?>
                                                    		</td>
                                                    		</tr>


                                                    		</table>

                                                    		</div>
                                                    		</div>
                                                    		<?php 

                                                    		if($form_status=="C")
                                                    		{
                                                    			$ll='11';
                                                    			$lls='1';
                                                    			$flag="";
                                                    		}
                                                    		if($form_status=="R")
                                                    		{
                                                    			$ll='4';
                                                    			$lls='1';
                                                    			$flag="";
                                                    		}
                                                    		if($form_status=="F")
                                                    		{
                                                    			$ll='1';
                                                    			$lls='1';
                                                    			$flag1="";

                                                    		}
                                                    		if($form_status=="I")
                                                    		{
                                                    			$ll='111';
                                                    			$lls='1';
                                                    		}

                                                    		$stv = $db->prepare("select count(*) from $schemas.objection_details where filing_no= ? and date(entry_date)=? and level_level=? and	objection_sub_code=? ");
                                                    		$stv->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    		$stv->bindParam(3, $ll, PDO::PARAM_STR);
                                                    		$stv->bindParam(4, $lls, PDO::PARAM_STR);
                                                    		$stv->execute();
                                                    		$filing_count_zzz = $stv->fetchColumn();


                                                    		if($filing_count_zzz > '0')
                                                    		{

                                                    			?>

                                                    			<div class="accordion-section">
                                                    			<a class="accordion-section-title" href="#accordion-2" id="draft_id"><?php if($case_type!=15 && $case_type!=14){echo htmlspecialchars($act_name_all);} else if($case_type==15){echo 'Second Motion';}
                                                    			else if($case_type==14){echo 'First Motion';}?></a>
                                                    			<div id="accordion-2" class="accordion-section-content">
                                                    			<table class="table table-bordered">
                                                    			<tr>
                                                    			<td colspan="12"></td>
                                                    			</tr>
                                                    			<tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
                                                    			<td width="3%">Sr. No1</td>
                                                    			<td width="67%">Description</td>
                                                    			<td width="6%">Defect Free</td>
                                                    			<td width="16%">Comments</td>
                                                    			</tr>
                                                    			<tr>
                                                    			<td colspan="12">

                                                    <?php //$status=$_REQUEST['status'];

                                                    ?>

                                                    <?php 

                                                    $flag1="";
                                                    $display='TRUE';

  //find to open scrutiny master local
                                                    if($act_id =='1' OR $act_id =='2')
                                                    	{ $act_id='1';}

                                                    if($form_status=="C")
                                                    {
                                                    	$ll='11';
                                                    	$lls='1';
                                                    }
                                                    if($form_status=="F")
                                                    {
                                                    	$ll='1';
                                                    	$lls='1';

                                                    }
                                                    if($form_status=="I")
                                                    {
                                                    	$ll='111';
                                                    	$lls='1';

                                                    }

                                                    if($form_status=="R")
                                                    {
                                                    	$ll='4';
                                                    	$lls='1';
                                                    }

                                                    if($form_status=="C"){

                                                    	$chk_old_doc = $db->prepare("select filing_no from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and objection_sub_code=? and 	miscellaneous_ref_no=?");
                                                    	$chk_old_doc->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    	$chk_old_doc->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    	$chk_old_doc->bindParam(3, $ll, PDO::PARAM_STR);
                                                    	$chk_old_doc->bindParam(4, $lls, PDO::PARAM_STR);
                                                    	$chk_old_doc->bindParam(5, $miscellaneous_ref_no_post, PDO::PARAM_STR);
                                                    	$chk_old_doc->execute();
                                                    	$filing_no_chk_old_doc = $chk_old_doc->fetchColumn();

                                                    	if($filing_no_chk_old_doc=='' || $filing_no_chk_old_doc==NULL){
                                                    		$stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and
                                                    			objection_sub_code=? order by cast(objection_code as integer) ASC ");
                                                    		$stv->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    		$stv->bindParam(3, $ll, PDO::PARAM_STR);
                                                    		$stv->bindParam(4, $lls, PDO::PARAM_STR);

                                                    	}else{
                                                    		$stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and
                                                    			objection_sub_code=? and 	miscellaneous_ref_no=? order by cast(objection_code as integer) ASC ");
                                                    		$stv->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    		$stv->bindParam(3, $ll, PDO::PARAM_STR);
                                                    		$stv->bindParam(4, $lls, PDO::PARAM_STR);
                                                    		$stv->bindParam(5, $miscellaneous_ref_no_post, PDO::PARAM_STR);
                                                    	}
                                                    }else{

                                                    	if($form_status=="R")
                                                    	{


                                                    		$stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and
                                                    			objection_sub_code=?  and miscellaneous_ref_no=? and form_type=? order by cast(objection_code as integer) ASC ");
                                                    		$stv->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    		$stv->bindParam(3, $ll, PDO::PARAM_STR);
                                                    		$stv->bindParam(4, $lls, PDO::PARAM_STR); 
                                                    		$stv->bindParam(5, $miscellaneous_ref_no_post, PDO::PARAM_STR);
                                                    		$stv->bindParam(6, $form_type, PDO::PARAM_STR); 




                                                    	}
                                                    }
                                                    if($form_status!='C' && $form_status!='R')
                                                    {


 //$ee = "select * from $schemas.objection_details where filing_no= '$filing_no' and entry_dt='$notification_datez' and level_level='$ll' and
///objection_sub_code='$lls' order by cast(objection_code as integer) ASC ";	
                                                    	$stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and
                                                    		objection_sub_code=? order by cast(objection_code as integer) ASC ");
                                                    	$stv->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    	$stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
                                                    	$stv->bindParam(3, $ll, PDO::PARAM_STR);
                                                    	$stv->bindParam(4, $lls, PDO::PARAM_STR);
                                                    }

                                                    $stv->execute();

                                                    $i=0;
                                                    $j=1;
                                                    while ($rowcc = $stv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                                    {


                                                    	$objection_codeobbj=$rowcc['objection_code'];
                                                    	$cause_no = $rowcc['scrutiny_correction'];
                                                    	$explode_cause_no = explode(',',$cause_no);
                                                    	$objection_codeststus=$rowcc['status'];
                                                    	$objection_codeststus=trim($objection_codeststus);
                                                    	if($objection_codeststus=='NO')
                                                    	{
                                                    		$flag1='NO';
                                                    	}

                                                    	if($objection_codeststus=='NA')
                                                    	{
                                                    		$flag1='NA';
                                                    	}

                                                    	$comment_other=$rowcc['comments'];

                                                    	if($case_type!=14 || $case_type!='15')
                                                    	{
                                                    		$sth=$db->prepare("select * from master_scrutiny_local where  id_serno=?  order by id_serno ASC ");
  //$sth->bindParam(1, $location_access, PDO::PARAM_STR);
                                                    		$sth->bindParam(1, $objection_codeobbj, PDO::PARAM_STR);
                                                    		$sth->execute();
                                                    	}
                                                    	if($case_type==14 || $case_type=='15')
                                                    	{
                                                    		$sth=$db->prepare("select * from master_scrutiny_local where  id_serno=?  and case_type=? order by id_serno ASC ");
  //$sth->bindParam(1, $location_access, PDO::PARAM_STR);
                                                    		$sth->bindParam(1, $objection_codeobbj, PDO::PARAM_STR);
                                                    		$sth->bindParam(2, $case_type, PDO::PARAM_STR);
                                                    		$sth->execute();
                                                    	}

                                                    	while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                                    	{
                                                    		$id_check=$rowa['id_serno'];
                                                    		$check_list=$rowa['name'];
                                                    		$display_all=$rowa['display'];
                                                    	}
                                                    	$obj_sub_code = 1;
                                                    	$level_level = 1;

                                                    	$draft_status='CM1';

			//check draft record exist or not



                                                    	if($form_status=='R')
                                                    	{

                                                    		$check_record_exist = $db->prepare("select display from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and level_level=? and form_type=? and status!=?");
                                                    		$check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(2, $objection_codeobbj, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(3, $obj_sub_code, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(4, $userid, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(5, $level_level, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(6, $form_type, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(7, $draft_status, PDO::PARAM_STR);
                                                    	}

                                                    	else
                                                    	{
                                                    		$check_record_exist = $db->prepare("select display from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and level_level=?  and status!=?");
                                                    		$check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(2, $objection_codeobbj, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(3, $obj_sub_code, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(4, $userid, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(5, $level_level, PDO::PARAM_STR);
						//$check_record_exist->bindParam(6, $form_type, PDO::PARAM_STR);
                                                    		$check_record_exist->bindParam(6, $draft_status, PDO::PARAM_STR); 



                                                    	}

                                                    	$check_record_exist->execute();
                                                    	$display_for_chk= $check_record_exist->fetchColumn();

                                                    	if($display_for_chk){



                                                    		if($form_status=='R')
                                                    		{
                                                    			$sql="select status,comment,level_level,scrutiny_correction from $schemas.draft_objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code'and user_id='$userid' and level_level='$level_level' and form_type='$form_type' and status!='$draft_status'";
                                                    		}
                                                    		if($form_status!='R')
                                                    		{
                                                    			$sql="select status,comment,level_level,scrutiny_correction from $schemas.draft_objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code'and user_id='$userid' and level_level='$level_level'  and status!='$draft_status'";
                                                    		}
                                                    		$stq=$db->prepare($sql);
                                                    		$stq->execute();
                                                    		while ($rowa = $stq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                                    		{
                                                    			$objection_codeststus=$rowa['status'];

                                                    			$cause_no = $rowa['scrutiny_correction'];
                                                    			$explode_cause_no = explode(',',$cause_no);
 //$objection_fil=$rowa['filing_no'];
                                                    			$objection_codeststus=trim($objection_codeststus);
                                                    			$comment_other=$rowa['comment'];
                                                    			$level=$rowa['level_level'];
                                                    		}
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
                                                    		<td width="60%">
                                                    		<font color="#1c2833"><b>
                                                    		<?php } ?>
                                                    		<?php 
                                                    		if($display_all  == '0')
                                                    		{
                                                    			?>
                                                    			<td width="60%">
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
 //$status[$j] = isset($_REQUEST['status'][$j]) ? $_REQUEST['status'][$j] : $objection_codeststus; ?>
                                                    				<select name="status" id="status" class="statuscheckother"
                                                    				onchange="myFunction()" style="
                                                    				background-color: silver;
                                                    				color: #000000;
                                                    				padding: 7px 7px;
                                                    				margin: 2px 0;
                                                    				border: none;
                                                    				border-radius: 4px;
                                                    				cursor: pointer;">
                                                    				<option value="NO"
                                                    				<?php  if($objection_codeststus=='NO') {echo 'selected'; }?>>
                                                    				NO</option>
                                                    				<option value="YES"
                                                    				<?php  if($objection_codeststus=='YES') echo "selected"; ?>>
                                                    				YES</option>

                                                    				<option value="NA"
                                                    				<?php  if($objection_codeststus=='NA') echo "selected"; ?>>
                                                    				NA</option>
                                                    				</select>
                                                    				<input type="hidden" id="id_check_draft_other"
                                                    				value='<?php echo htmlspecialchars($id_check);?>'
                                                    				name="id_check_draft_other[]">
                                                    <?php // }
                                                    ?>
                                                    </td>

                                                    <td width="20%">
                                                    <?php 
// if($display_all == '0')
// {
                                                    ?>
                                                    <?php
 //$comment[$j] = isset($_REQUEST['comment'][$j]) ? $_REQUEST['comment'][$j] : $comment; ?>
                                                    <textarea name="comment[]" class="comment_other"
                                                    onChange="makeUppercase(this)" cols="30" rows="1"
                                                    style="background-color: silver;">
                                                    <?php echo htmlspecialchars($comment_other);?></textarea>



                                                    <?php 

                                                    $comments="";
 //} 
                                                    ?>
                                                    </td>


                                                    </tr>

                                                    <input type="hidden" name="id_check[]" maxlength="3" readonly="readonly"
                                                    value="<?php echo htmlspecialchars(htmlentities($id_check.",".'IBC1'));?>"
                                                    size="2" />

                                                    <input type="hidden" name="filing_no"
                                                    value="<?php echo htmlspecialchars(htmlentities($filing_no));?>" />

                                                    <?php
                                                    $j++;
                                                    $i++;
                                                }

                                                ?>

                                                </td>
                                                </tr>
                                                </table>
                                                </div>
                                                </div>
                                                <?php } ?>


                                                <div class="rr defect-wrap">
                                    <div class="dfct-list">
                                        <span class="rr">
                                            <font face="Verdana" size="2" color="red">*
                                        </span> </font>
                                        <font face="Verdana, Arial, Helvetica, sans-serif" size="4" color="red">
                                            Defects in <span style="font-size:12px;">For Multiple Defects Select
                                                (ctrl+left mouse click)</span></font>
                                        </span><br />
                                        <select style="display:none;" class="rr" name="cause_no[]" id="cause_no"
                                            onChange="get_document(this.value,'<?php echo $filing_no; ?>');"
                                            style="width: 620px; height: 200px" onFocus="SetBg(this)"
                                            onBlur="UnSetBg(this)" multiple>
                                            <?php

                                                if($form_status=='C')
                                                {
                                                	$st1=$db->prepare("select * from scrutiny_status where id='6' ");
                                                }
                                                else
                                                {
                                                	$st1=$db->prepare("select * from scrutiny_status where is_active = true order by id  ");
                                                }
                                                $st1->execute();
                                                while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                                {

                                                	$e_name=$row['cause_name'];
                                                	$cause_no=$row['cause_no'];
                                                	?>

                                            <option value="<?php echo $cause_no;?>" <?php 
                                                	if(in_array($cause_no,$explode_cause_no))
                                                	{  
                                                		$sc_marked_defects .= $e_name.' ,  ';
                                                		print "selected"; 
                                                	} 
                                                	?>>
                                                <?php echo strtoupper($e_name);?></option>
                                            <?php
                                                }
                                                ?>
                                        </select>
                                        <?php
														$query = "select documentuploadmodelid,fileupload,filename,docum_type from document_upload where filing_no = ? and from_gst = false";
															$docs_query = $db->prepare($query);
															$docs_query->bindParam(1, $filing_no, PDO::PARAM_STR);
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
                                            <input type="checkbox" id="doc_<?php echo $v['documentuploadmodelid'] ?>"
                                                name="docs[]" value="<?php echo $v['documentuploadmodelid'] ?>"
                                                <?php  echo (in_array($v['documentuploadmodelid'], $defect_docs))?'checked':''; ?>>
                                            <label for="vehicle1">
                                                <?php echo $v['docum_type'].'  /  '.$v['filename']; ?></label><br>

                                            <?php }
																	?>
                                        </div>
                                    </div>
                                </div>
                                                

                                            <table  style="margin: 0 0 30px 30px;">

                                                <tr>
                                                <td><?php echo (!empty(trim($sc_marked_defects)))?'Defects in : '.$sc_marked_defects:''; ?></td>
                                                <td colspan="2" style="padding-top:5px;">
                                                Notification Date: <input id="in_notification_date" type="text"
                                                name="notification_date" readonly="readonly"
                                                value="<?php echo htmlspecialchars(htmlentities($cur_date1));?>"
                                                size="10" maxlength="10" style="margin-top: 2px;" />

                                                <input type="checkbox" value="0" id="agree" name="agree"
                                                required="required">
                                                <b>
                                                <font color="red">Re- Scrutiny Verify</font>
                                                </b>
                                                </td>
                                                <div class="rr">

                                                </div>
                                                </table>


                                                <table width="70%" align="center">
                                                <tr>
                                                <br>
                                                <td style="display: block;padding-top:5px;" colspan="4" id="befornotification1">
                                                <td style="display: block" colspan="4" id="befornotification">


                                                <select id="in_searchby" name="searchby"
                                                style="display: block;background-color: red">


                                                <?php 


                                                if($flag=='NO' || $flag1=='NO'){?>
                                                	<option value="1">DEFECTIVE</option>
                                                	<?php }else { ?>
                                                		<option value="2">DEFECT FREE</option>
                                                		<?php } ?>
                                                		</select>
                                                		</td>

                                                		<td>  
                                                		<button type="button"  onClick="fn_return_cases('<?php echo $filing_no; ?>')" style="
                                                		background-color: green;
                                                		color: #FFFFFF;
                                                		padding: 7px 7px;
                                                		margin: 2px 0;
                                                		border: none;
                                                		border-radius: 4px;
                                                		cursor: pointer;">RETURN TO SCRUTINY CLERK</button>
                                                		</td>

                                                		<td>
                                                		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                		<input id="submit_final" type="submit" name="submit_final" class="submit"
                                                		value="UPDATE SCRUTINY" style="
                                                		background-color: green;
                                                		color: #FFFFFF;
                                                		padding: 7px 7px;
                                                		margin: 2px 0;
                                                		border: none;
                                                		border-radius: 4px;
                                                		cursor: pointer;" onClick="return defect_submit();" />

                                                		</td>
                                                		<!--<td> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<input id="save_draft"
                                                		type="button" name="save_draft" class="submit" value="Save As Draft"
                                                		style="
                                                		background-color: green;
                                                		color: #FFFFFF;
                                                		padding: 5px 5px;
                                                		margin: 16px 15;
                                                		border: none;
                                                		border-radius: 4px;
                                                		cursor: pointer;	
                                                		" onClick="return draft_checklist();" />

                                                		</td>-->
                                                		</tr>
                                                		</table>

                                                		<?php }  ?>
                                                		</form>

                                                		<!-- /.box-footer-->
                                                		</div>
                                                		</div>
                                                		<div class="alert alert-success" style="display: none;">

                                                		<div class="demo-box" id="ajaxButton"></div>

                                                		</div>
                                                		<!-- /.box -->
                                                		</section>
                                                		<!-- /.content -->
                                                		</div>
                                                		<!-- /.content-wrapper -->

                                                		<script>
                                                		function OpenDMSForm(step, filing_no, dms_type, misc_no) {
                                                			document.getElementById("step").value = step;
                                                			document.getElementById("filingNb").value = filing_no;
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

                                                		function get_document(defect_type,filing_no){
									 var selected_defects = $('#cause_no').val(); 
									 console.log(selected_defects);
									if(selected_defects.includes('6')){
										
							            $('#scrutiny_docs').css('display','inherit');
									}else{
										$('#scrutiny_docs').css('display','none');
									}
								}
                                                		</script>
                                                		<form action="https://uat-efiling.gstat.gov.in/dmsgstat/dashboard" method="POST" target="_blank" id="frm_dms">
                                                		<input type="hidden" id="step" name="step" value="" />
                                                		<input type="hidden" id="filingNb" name="filing_no" value="" />
                                                		<input type="hidden" id="dms_type" name="dms_type" value="" />
                                                		<input type="hidden" id="misc_no" name="misc_no" value="" />
                                                		</form>
                                                		<form action="https://uat-efiling.gstat.gov.in/efiling/CISCasePreview.drt" method="POST" target="_blank" id="previewCIS">
                                                		<input type="hidden" id="filling_no" name="filingNo" value="" />
                                                		</form>

                                                		<form action="https://uat-efiling.gstat.gov.in/efiling/previewReceipt.drt" method="POST" target="_blank"
                                                		id="previewReceipt">
                                                		<input type="hidden" id="fillingNu" name="filingNo" value="" />
                                                		</form>
                                                		<div id="return_group_modal" class="modal fade" role="dialog">
                                                		<div class="modal-dialog">
                                                		<!-- Modal content-->
                                                		<div class="modal-content">
                                                		<div class="modal-header">
                                                		<button type="button" class="close" data-dismiss="modal">&times;</button>
                                                		<h4 class="modal-title">Return Cases</h4>
                                                		</div>

			<!-- //pdfForm -->
			<form id="return_final_form_id" name="return_final_form_id" method="post">
			<input type="hidden" name="hidden_filling_no11111" id="hidden_filling_no11111" value="">
			<div class="modal-body">
			<p> <textarea class="form-control" name="remark_return_cases" id="remark_return_cases"></textarea>
			</p>

			</div>
			</form>
			<div class="modal-footer">
			<div id="footer_dsc">
			<button type="button" id="upload_return_file" class="btn btn-success">Update</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>
			</div>

			</div>
			</div>
			<?php 
			include '../infooter.php';
			?>


			<script>
			function fn_return_cases(filing_no) {
				$("#return_group_modal").modal('show');
				$("#hidden_filling_no11111").val(filing_no);
			}



			$(document).on('click', '#upload_return_file', function(e) {
				if (confirm('Are you sure ?')) {
					var id_check = $('input[name="id_check[]"]').map(function() {
						return this.value
					}).get()
					console.log(id_check);
					var cause_no = [];
					$.each($("#cause_no option:selected"), function() {
						cause_no.push($(this).val());
					});
					console.log(cause_no);
					var comment = [];
					$.each($(".comment"), function() {
						comment.push($(this).val());
					});
					var status1 = "";
					var tnl = document.getElementsByName("status");
					for (i = 0; i < tnl.length; i++) {
						var val = tnl[i].value;
						var status1 = status1 + val + ','
					}
					if (!document.getElementById('agree').checked) {
						alert('You must agree to the terms first.');
						//agree.focus();
						return false;
					}
					var remark_return_cases = $("#remark_return_cases").val();
					if (remark_return_cases == '') {
						alert('Please Enter Remark');
					} else {
						var data = {};
						data['action'] = 'return_cases_sc';
						data['filing_no'] = $("#hidden_filling_no11111").val();
						data['status'] = status1;
						data['comment'] = comment;
						data['cause_no'] = cause_no;
						data['id_check'] = id_check;
						data['remark_return_cases'] = $("#remark_return_cases").val();
						console.log(data);
						//return false;
						$.ajax({
							type: "POST",
							url: "../ajax/return_ajax.php",
							data: data,
							dataType: 'html',
							success: function(data11) {
								if(data11 == 1) { 
									alert('Case returned sucessfully.');
									location.href = 'https://uat-cis.gstat.gov.in/gstat/index.php';
								} else { 
									alert('Something error.');
									console.log("Something error.");
								}
							},
							error: function(request, error) {
								alert('Something error.');
								console.log("Something error.");
							}
						});
					}
				}
			});

			function autoResizeTextarea(textarea) {
			    textarea.style.height = 'auto'; // Reset height
			    textarea.style.height = textarea.scrollHeight + 'px'; // Set new height based on content
			}

			document.addEventListener('DOMContentLoaded', function () {
			    const textareas = document.querySelectorAll('.comment');

			    textareas.forEach(textarea => {
			        // Expand on page load
			        autoResizeTextarea(textarea);

			        // Expand as user types
			        textarea.addEventListener('input', () => autoResizeTextarea(textarea));
			    });
			});



			</script>
			<?php } ?>

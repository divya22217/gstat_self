<?php

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include '../custom/custom_function.php';
session_start();

$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$username = $_SESSION['actual_username'];

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */


$schemas = htmlspecialchars($_SESSION['schema_name']);

$userid = $_SESSION['id'];

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
$c_case = $_REQUEST['ccase'];

$subdoctyp = $_REQUEST['subdoctyp'];


function remove_path($file, $path = UPLOAD_PATH)
{
	if (strpos($file, $path) !== FALSE) {
		return substr($file, strlen($path));
	}
}

setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key = $_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
	die("#2E2E2Eirecting to login.php");
}

if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {

	function display_filing_no($filing_no_display)
	{
		$lastFour =  substr($filing_no_display, -4);
		$lastFive = substr($filing_no_display, -9, -4);
		$left = substr($filing_no_display, -16, -9);
		return $dis_fil_no = $left . '/<b>' . $lastFive . '/' . $lastFour . '</b>';
	}
	$filing_no_next = $_REQUEST['filing_no_next'];
	$subdoctype = $_REQUEST['subdoctype'];

	$hash1 = htmlspecialchars(base64_decode($filing_no_next));
	$hash1 = explode("-", $hash1);
	$filing_no_fou = $hash1[0];

	$token_fou = $hash1[1];
	$case_type_of_case = $hash1[2];

	if ($c_case == '2') {
		$miscellaneous_ref_no_post = $hash1[2];
	}
	if ($c_case == '4') {
		$miscellaneous_ref_no_post = $hash1[2];
	}

	if ($c_case == '3') {
		$ia_id = $hash1[2];
	}

	if ($_SESSION['qqcc'] != $token_fou) {
		echo "Access Problem.....";
		header("Location: ../login.php?aa=100");
		die();
	}
	if ($token_fou == '') {
		echo "Access Problem.....";
		header("Location: ../login.php?aa=100");
		die();
	}



	// This code not use next time .......	Schema session create Hear....

	$location_access = $_SESSION['location'];
	$sessionUserType = htmlspecialchars($_SESSION['id']);


	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 = "$curDay/$curMonth/$curYear";


	include '../inheader.php';
	//include '../insidebar.php';

	?>
	<?php

	$form2 = sha1(uniqid('auth', true));
	$_SESSION['form2_scruniny'] = $form2;
	?>


	<script>
		/*$( window ).load(function() {
	  alert('sadsad');
console.log( "window loaded" );
myFunction();

});
  */
	</script>

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
	$hash = $_REQUEST['hash'];

	if ($hash != '') {

		$hash1 = htmlspecialchars(base64_decode($hash));
		$hash1 = explode("/", $hash1);
		$massage = $hash1[0];
		$filing_no_backpage = $hash1[1];
		$filing_no_backpage_print = htmlspecialchars(base64_decode($filing_no_backpage));

		echo "<center></br><font color='red' size='4'>" . htmlspecialchars($msg) . '</br>';
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
	Diary/Filing No : <?php echo display_filing_no($filing_no_fou); ?>
	&nbsp;&nbsp;
	<?php

	$main_cases = main_case_type();
	$st1 = $db->prepare("select a.filing_no,a.filingnumberia,a.dt_of_filing,a.case_type_nclat as case_type,
		a.case_no,a.act_id,a.patially_defect_remark,a.patially_defective,b.act_name,a.e_reference_no,a.boofficefound,a.remark_return_cases,a.defect_docs from e_case_detail as a
		left join master_act as b on b.act_id = a.act_id
		where a.filing_no=? and a.location_id=? ");
	$st1->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
	$st1->bindParam(2, $location_access, PDO::PARAM_STR);
	$st1->execute();
	while ($row = $st1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

		$filing_no = htmlspecialchars($row['filing_no']);
		$main_filing_no = htmlspecialchars($row['filingnumberia']);
		$dt_of_filing = htmlspecialchars($row['dt_of_filing']);
		$case_type = htmlspecialchars($row['case_type']);
		$case_no = $row['case_no'];
		$act_id = $row['act_id'];
		$patially_defect_remark = htmlspecialchars($row['patially_defect_remark']);
		$patially_defective = htmlspecialchars($row['patially_defective']);
		$act_name_all =  htmlspecialchars($row['act_name']);
		$e_reference_no =  htmlspecialchars($row['e_reference_no']);
		$boofficefound = $row['boofficefound'];
		$remark_return_cases = $row['remark_return_cases'];
		if($boofficefound == '1')
			$juridection_text = 'Wrong jurisdiction selection';

		$defect_docs = $row['defect_docs'];
		$defect_docs = explode(',', $defect_docs);
	}

	if (in_array($case_type, $main_cases)) {
		$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($filing_no);
	} else {
		$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($main_filing_no);
		if (empty($show_party_filing_no))
			$show_party_filing_no = htmlspecialchars($filing_no);
	}
	$pet_name = $res_name = '';
	$petitioner = main_party($db,$show_party_filing_no,'P');
	$respondent = main_party($db,$show_party_filing_no,'R');
	if(!empty($petitioner)){
		$pet_name = $petitioner['name'];
	}
	if(!empty($respondent)){
		$res_name = $respondent['name'];
	}

	?>
	<font color="#e8b90e">
	<?php
	$E_nameP = htmlspecialchars_decode($pet_name, ENT_NOQUOTES);
	$E_nameR = htmlspecialchars_decode($res_name, ENT_NOQUOTES);

	echo strtoupper($E_nameP) . '&nbsp; <span style="color:#191f87;">Vs.</span> &nbsp;' . strtoupper($E_nameR); ?></font>
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
	<?php }

	$remark_return_data = $remark_return_cases;
	if (!empty($remark_return_data) && $remark_return_data != '') { ?>
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
		">Return Remark :</h4> <?php echo  $remark_return_data; ?></div>
		<?php }  ?>

		<div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
		<i class="fa fa-minus"></i></button>
		<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
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


												//var r = $( "#in_searchby" ).val();	

				var current_status = $("#in_searchby").val();
												//current_status = $.trim(current_status);
				if (current_status == '1') {
					var cause_number = $("#cause_no").val();
					if (cause_number == '') {
						alert("please select any Error in Option");
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
				if (!document.getElementById('agree').checked) {
					alert('You must agree to the terms first.');
													//agree.focus();
					return false;
				}


				action = "level_one_action.php?test=" + status1;
				submit();
				document.form2.submit_final.disabled = true;
				document.form2.submit_final.value = 'Please Wait...';
				return true;
			}
		}

		function draft_checklist() {

			var current_status = $("#in_searchby").val();
											//current_status = $.trim(current_status);
			if (current_status == '1') {
				var cause_number = $("#cause_no").val();
				if (cause_number == '') {
					alert("please select  Error in Option");
					return false;
				}
			}

											//for gen
			var status = [];
			$.each($(".statuscheck option:selected"), function() {
				status.push($(this).val());
			});

			var id_checklist = $('input[name="id_check_draft[]"]').map(function() {
				return this.value
			}).get()

			var comment = [];
			$.each($(".comment"), function() {
				var comt_val = $(this).val();
				newTemp = comt_val.replace('"', "'");
				comment.push(newTemp);
												//comment.push($(this).val());
			});

											//for others
			var statusother = [];
			$.each($(".statuscheckother option:selected"), function() {
				statusother.push($(this).val());
			});

			var id_checklist_other = $('input[name="id_check_draft_other[]"]').map(function() {
				return this.value
			}).get()

			var comment_other = [];
			$.each($(".comment_other"), function() {
				comment_other.push($(this).val());
			});
											//alert(comment_other);
			var cause_no = [];
			$.each($("#cause_no"), function() {
				cause_no.push($(this).val());
			});
			console.log(cause_no);
			var obj_sub_name = $('#draft_id').text();
			if (obj_sub_name == 'First Motion' || obj_sub_name == 'Second Motion' || obj_sub_name == 'IBC Act' || obj_sub_name == 'Company Petition') {
				obj_sub_code = 1;
			} else {
				obj_sub_code = 0;
			}
			var cause_no = JSON.stringify(cause_no);
			var comment = JSON.stringify(comment);
			var comment_other = JSON.stringify(comment_other);
			var status = JSON.stringify(status);
			var checklist = JSON.stringify(id_checklist);

			var statusother = JSON.stringify(statusother);
			var id_checklist_other = JSON.stringify(id_checklist_other);

			var obj_sub_code = JSON.stringify(obj_sub_code);
			var filing_no = JSON.stringify(filing_no);

			var filing_no = document.getElementById("draft_filing_no").value;





			var miscellaneous_ref_no_post = document.getElementById("miscellaneous_ref_no_post").value;
			var miscellaneous_ref_no_post = JSON.stringify(miscellaneous_ref_no_post);

			var scrutiny_level_value = document.getElementById("scrutiny_level_value").value;

			var c_case = JSON.stringify(c_case);

			var c_case = document.getElementById("c_case").value;
			var subdoctype = JSON.stringify(subdoctype);

			var subdoctype = document.getElementById("subdoctype").value;
			var scrutiny_level_value = document.getElementById("scrutiny_level_value").value;

											//var filing_no = filing_no.toString();
			var user_id = document.getElementById("user_id").value;

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
					comment: comment,
					comment_other: comment_other,
					miscellaneous_ref_no_post: miscellaneous_ref_no_post,
					c_case: c_case,
					subdoctype: subdoctype,
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

			if (val1 == "") {
				val1 = 'YES';
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
			xmlhttp.open("GET", "notificationdate.php?val=" + val1, true);
			xmlhttp.send();

			var menuaccess = '<?php echo $_SESSION['menuaccess_codeall']; ?>';

			if (val1 === 'YES' && menuaccess != '2') {
				$("#save_draft").css('display', 'none');
				$("#submit_final").css('display', 'none');
			} else {
				$("#save_draft").css('display', 'block');
				$("#submit_final").css('display', 'block');
			}

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
		</script>
		<?php
		$remove_defact = sha1(uniqid('auth', true));
		$_SESSION['remove_defact'] = $remove_defact;


		?>

		<form name="form2" method="post" action="level_one_action.php">
		<input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2); ?>" />
		<input type="hidden" name="filing_no_next" value="<?php echo htmlspecialchars($filing_no_next); ?>" />
		<input type="hidden" name="case_type" value="<?php echo htmlspecialchars($case_type_of_case); ?>" />
		<table width="100%">

		<tr>
		<td colspan="6">
		<?php



		$tokenno = $filing_no_fou;


		?>
		</td>
		</tr>
		</table>

		<?php

		if ($tokenno != '') {
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

			<table cellpadding="0" cellspacing="0" class="tbl-accordion">
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
			<td colspan="2">
			<table cellpadding="0" cellspacing="0" border='1' class="tbl-accordion-nested">
			<thead>
			<tr>
			<td><b> Case Detail </b></td>

			<td><b>Documents </b></td>

			<td><b> Payment Details </b></td>
			</tr>
			<tr>
			<?php
			$ccase = $_REQUEST['ccase'];
			if ($ccase == 2) {
				$display = '1';
				$scrutiny = '0';
				$form_status = "C";
			}
			if ($ccase == 4) {
				$display = '1';
				$scrutiny = '0';
				$form_status = "R";
			} elseif ($ccase == 1 || $ccase == 5) {
				$display = '1';
				$scrutiny = '0';
				$form_status = "F";
			} elseif ($ccase == 3) {
				$display = '1';
				$scrutiny = '0';
				$form_status = "I";
			}
			?>
			<input type="hidden" name="form_status" value="<?php echo htmlspecialchars(htmlentities($form_status)); ?>" />
			<?php

			$st = $db->prepare("select filing_no, subdoctype,document_filed_date,fileupload,returnfilename from document_upload where filing_no=? and scrutiny=? and display=?  and (miscellaneous_ref_no IS NULL or miscellaneous_ref_no = '')");
			$st->bindParam(1, $tokenno, PDO::PARAM_STR);
			$st->bindParam(2, $scrutiny, PDO::PARAM_STR);
			$st->bindParam(3, $display, PDO::PARAM_STR);
			$st->execute();
			while ($rowa = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
				$fil_no = $rowa['filing_no'];
				$sub_doc_type = $rowa['subdoctype'];


				$document_filed_date = $rowa['document_filed_date'];
				$path = $rowa['fileupload'];
				$returnfilename = $rowa['returnfilename'];

				list($returnfilename, $ext) = explode('.', $returnfilename);
				$returnfilename1 = $returnfilename;



				$stqq = $db->prepare("select e_document_name from e_document_type  where e_document_type=?");
				$stqq->bindParam(1, $sub_doc_type, PDO::PARAM_INT);
				$stqq->execute();

				$e_document_name_print = $stqq->fetchColumn(); ?>
				<?php	}


				?>
				</thead>
				<tbody>
				<tr>

				<td>

				<a onclick="previewCIS('<?php echo $filing_no; ?>');" style="cursor: pointer">

				<font color="#900C3F" size="3">&nbsp;&nbsp;
				&nbsp;&nbsp;View
				</a>

				<!-- <a target="_blank" href="https://efiling.nclat.gov.in/previewCIS.drt?filingNo=<?php //echo $filing_no ?>">
				<font color="#900C3F" size="3">&nbsp;&nbsp;View
				</a> -->
				</td>
				<td>


				<!-- step, filing_no, cause_title, case_no, court_no, item_no -->
				<a onclick="OpenDMSForm('3','<?php echo $filing_no; ?>','fresh','')" style="cursor: pointer">

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
				<a onclick="OpenPreviewReceipt('<?php echo $filing_no; ?>')" style="cursor: pointer">
				<font color="#900C3F" size="3">  
				View </a>
				</td>

				</tr>
				<tr>
				</thead>
				<tbody>

				<!--start of code for case no. -->

				<?php
				if ($ccase == '2') {
																				//echo "select case_type, case_no, case_year, location_code from $schemas.case_detail where filing_no='$filing_no'";
					$casenosql = $db->prepare("select case_type, case_no, case_year, location_code from $schemas.case_detail where filing_no='$filing_no'");
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

					$casetypesql = $db->prepare("select case_type_desc from case_type where id = '$casetype'");
					$casetypesql->execute();
					$case_type_short_name = $casetypesql->fetchColumn();
					$case_type_short_name = strtoupper($case_type_short_name);
																				//echo $case_type_short_name;
					if ($locode == '') {
						$locode = 0;
					}
					$lcodesql = "select short_name from $schemas.bench_location where bench_location_code ='$locode'";
					$lcodesql = $db->prepare($lcodesql);
					$lcodesql->execute();
					$lcodename = $lcodesql->fetchColumn();
																				//echo $lcodename;

					$case_no_final = $case_type_short_name . '/' . $case_no . '(' . $lcodename . ')' . $case_year;
																				//echo $case_no_final;


																				//$case_no_final = 0999988888;
				}
				?>

				</tr>

				<?php
				if ($ccase == '2') {
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
							<td><?php echo $doc_c . "). " . $miscellaneous_ref_no; ?></td>
							</tr>
							<?php
							$doc_c++;
						} else { ?>
							<tr>
							<td><?php echo "Not Found"; ?></td>
							</tr>
							<?php }
						}
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
								<td><?php echo $miscellaneous_ref_no . " (" . $subdocname . ")"; ?></td>
								</tr>
								<?php
								$doc_c++;
							} else { ?>
								<tr>
								<td><?php echo "Not Found"; ?></td>
								</tr>
								<?php }
							}
						}
						?>



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

						$scr = $db->prepare("select level_level from $schemas.scrutiny where filing_no = ?");
						$scr->bindParam(1,$filing_no,PDO::PARAM_STR);
						$scr->execute();
						$previous_scrutiny_level = $scr->fetchColumn();


						?>
						<div class="col-sm-12 col-md-12 col-lg-12" id="objection_tab">
						<div class="accordion-section">
						<a class="accordion-section-title" href="#accordion-1"><?php echo htmlspecialchars("General"); ?></a>
						<div id="accordion-1" class="accordion-section-content">


						<table class="table table-bordered"> 
						<tr style="background-color:#6D6968; color: #FFFFFF;">
						<td width="3%">Sr. No</td>
						<td width="67%">Description</td>
						<td width="16%">As per appellant</td>
						<td width="16%">Comments of appellant, if any</td>
						<?php if($previous_scrutiny_level == '2') { ?>
							<td width="16%">As per registrar</td>
							<td width="16%">Comments of registrar, if any</td>
						<?php } ?>
						<td width="6%">As per scrutiny clerk</td>
						<td width="16%">Comments of scrutiny clerk, if any</td>


						</tr>
						<tr>
						<td colspan="12">
						<?php


						$display = 'TRUE';



						if($case_type == '1'){
							$sth = $db->prepare("select * from check_list_local order by id ASC ");
						}else{
							$sth = $db->prepare("select * from check_list_local_ia order by id ASC ");
						}
															//$sth->bindParam(1, $location_access, PDO::PARAM_STR);
						$sth->execute();
						$i = 0;
						$j = 1;
						while ($rowa = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

							$id_check = $rowa['id'];
							$obj_sub_code = 0;
							$level_level = 0;

																//check draft record exist or not
							if ($c_case == 1 || $c_case == 3 || $c_case == 5 || $c_case == 8) {
								$frm_type = 'NA';
																	//$frm_type1='O';
								$level_level = '0';

								$check_record_exist = $db->prepare("select display from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and level_level=? and form_type = ?");
								$check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
								$check_record_exist->bindParam(2, $id_check, PDO::PARAM_STR);
								$check_record_exist->bindParam(3, $obj_sub_code, PDO::PARAM_STR);
								$check_record_exist->bindParam(4, $userid, PDO::PARAM_STR);
								$check_record_exist->bindParam(5, $level_level, PDO::PARAM_STR);
								$check_record_exist->bindParam(6, $frm_type, PDO::PARAM_STR);
																	//$check_record_exist->bindParam(7, $frm_type1, PDO::PARAM_STR);
								$check_record_exist->execute();
								$display_for_chk = $check_record_exist->fetchColumn();
								if ($display_for_chk) {
									$sql = "select status,comment  as doc_comments,scrutiny_correction,status as status_registrar,comment as comment_registrar from $schemas.draft_objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code'and user_id='$userid' and level_level='$level_level' and  form_type = '$frm_type'";
								} else {

									$sql = "select status,doc_comments,scrutiny_correction,status_registrar,comment_registrar from $schemas.objection_details where filing_no='$filing_no' and objection_code='$id_check'";
								}
							}



							if ($c_case == 2) {
								$frm_type = 'NA';
																	//$frm_type1='O';
								$level_level = '0';

								$check_record_exist = $db->prepare("select display from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and level_level=? and miscellaneous_ref_no=? and form_type = ?");
								$check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
								$check_record_exist->bindParam(2, $id_check, PDO::PARAM_STR);
								$check_record_exist->bindParam(3, $obj_sub_code, PDO::PARAM_STR);
								$check_record_exist->bindParam(4, $userid, PDO::PARAM_STR);
								$check_record_exist->bindParam(5, $level_level, PDO::PARAM_STR);
								$check_record_exist->bindParam(6, $miscellaneous_ref_no_post, PDO::PARAM_STR);
								$check_record_exist->bindParam(7, $frm_type, PDO::PARAM_STR);
								$check_record_exist->execute();
								$display_for_chk = $check_record_exist->fetchColumn();
								if ($display_for_chk) {
									$sql = "select status,comment as doc_comments, status as status_registrar,comment as comment_registrar  from $schemas.draft_objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code'and user_id='$userid' and level_level='$level_level' and miscellaneous_ref_no='$miscellaneous_ref_no_post' and  form_type = '$frm_type'";
								} else {

									$sql = "select status,doc_comments,status_registrar,comment_registrar from $schemas.objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code' and miscellaneous_ref_no='miscellaneous_ref_no_post' and form_type = '$frm_type'";
								}
							}


							if ($c_case == 4) {
																	//$form_type='R';
								if ($subdoctype == '17') {
																		//$subdocname='Report';
									$form_type = 'R';
								}
								if ($subdoctype == '33') {
																		//$subdocname='Order';
									$form_type = 'O';
								}
								$check_record_exist = $db->prepare("select display from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and level_level=? and miscellaneous_ref_no=? and form_type=?");
								$check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
								$check_record_exist->bindParam(2, $id_check, PDO::PARAM_STR);
								$check_record_exist->bindParam(3, $obj_sub_code, PDO::PARAM_STR);
								$check_record_exist->bindParam(4, $userid, PDO::PARAM_STR);
								$check_record_exist->bindParam(5, $level_level, PDO::PARAM_STR);
								$check_record_exist->bindParam(6, $miscellaneous_ref_no_post, PDO::PARAM_STR);
								$check_record_exist->bindParam(7, $form_type, PDO::PARAM_STR);

								$check_record_exist->execute();

								$display_for_chk = $check_record_exist->fetchColumn();

								if ($display_for_chk) {
									$sql = "select status,comment  as doc_comments, status as status_registrar,comment as comment_registrar from $schemas.draft_objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code'and user_id='$userid' and level_level='$level_level' and miscellaneous_ref_no='$miscellaneous_ref_no_post' and form_type='$form_type'";
								} else {

									$sql = "select status,doc_comments,comment_registrar,status_registrar from $schemas.objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code' and miscellaneous_ref_no='$miscellaneous_ref_no_post' and form_type='$form_type' ";
								}
							}

							$stq = $db->prepare($sql);
																//$stq->bindParam(1, $filing_no, PDO::PARAM_INT);
																//$stq->bindParam(2, $id_check, PDO::PARAM_INT);
							$stq->execute();
																//$status = $stq->fetchColumn();

							while ($row_stq = $stq->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
								$status = $row_stq['status'];
								$status = trim($status);
								$cause_no = $row_stq['scrutiny_correction'];
								$explode_cause_no = explode(',', $cause_no);
								$comment1 = $row_stq['doc_comments'];
								$objection_codeststus_reg=trim($row_stq['status_registrar']);
								$comment_registrar=$row_stq['comment_registrar'];
							}

							$check_list = $rowa['check_list'];

							?>
							<tr>

							<td width="5%">

							<?php echo htmlspecialchars($id_check); ?>

							</td>


							<td width="60%">
							<font color=" #1c2833 "><?php echo htmlspecialchars($check_list); ?></font>



							<?php $user_checklist_point = $user_remarks_array[$i];
							$user_checklist_point = explode(',', $user_checklist_point);
							$user_checklist_remark = $user_checklist_point[1];
							$user_checklist_serial_status = explode('-', $user_checklist_point[0]);
							$user_checklist_serail_no = $user_checklist_serial_status[0];
							$user_checklist_status = $user_checklist_serial_status[1];
							$user_status = ($user_checklist_status == 1)?'YES':(($user_checklist_status == 2)?'NO':'NA');
							?>

							<td width="7%">

								<p><?php echo $user_status; ?></p>
							
							</td>

							<td width="20%">

							<?php
							$user_checklist_remark_show = '';
							if ($user_checklist_remark != '') {
								$user_checklist_remark_show = $user_checklist_remark;
							}

							?>
							<p><?php echo htmlspecialchars($user_checklist_remark_show); ?></p>
							
							</td>

							

							<?php if($previous_scrutiny_level == '2') { ?>

							<td width="7%">

								<p><?php echo $objection_codeststus_reg; ?></p>
							
							</td>

							<td width="20%">

							<p><?php echo htmlspecialchars($comment_registrar); ?></p>
							
							</td>

							<?php } ?>

							<td width="7%">


							<select name="status" class="statuscheck" onchange="myFunction()" data-id="<?php echo $id_check; ?>" id="objection_status_<?php echo $id_check; ?>" style="
							background-color: silver;
							color: #000000;
							padding: 7px 7px;
							margin: 2px 0;
							border: none;
							border-radius: 4px;
							cursor: pointer;">

							<?php $status = ($status != '' || $status != 'NA') ? $status : "YES"; ?>
							<option value="YES" <?php if (trim($status) == 'YES') echo "selected"; ?>>YES</option>
							<option value="NO" <?php if (trim($status) == 'NO') echo "selected"; ?>>
							<font color="red">NO</font>
							</option>
							<?php if($case_type == '1' && ($id_check == '4' || $id_check == '15')) { ?>
								<option value="NA" <?php if (trim($status) == 'NA') echo "selected"; ?>>NA</option>
								<?php } ?>

								</select>
								<input type="hidden" id="id_check_draft" value='<?php echo htmlspecialchars($id_check); ?>' name="id_check_draft[]">
								</td>

								<td width="20%">
								<?php
								$comment = '';
								if ($comment1 != '') {
									$comment = $comment1;
								}

								?>
								<textarea class="comment"  name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" data-id="<?php echo $id_check; ?>" id="comment_<?php echo $id_check; ?>" style="background-color: silver;"><?php echo htmlspecialchars($comment); ?></textarea>
								</td>




								</tr>

								<input type="hidden" name="id_check[]" id="id_check" maxlength="3" readonly="readonly" value="<?php echo htmlspecialchars(htmlentities($id_check . ',' . 'gen')); ?>" size="2" />

								<input type="hidden" name="filing_no" id="draft_filing_no" value="<?php echo htmlspecialchars(htmlentities($filing_no)); ?>" />
								<input type="hidden" name="scrutiny_level_value" id="scrutiny_level_value" value="0" />
								<input type="hidden" name="ref_no_ia" value="<?php echo htmlspecialchars(htmlentities($ref_no_ia)); ?>" />
								<input type="hidden" name="ia_id" value="<?php echo htmlspecialchars(htmlentities($ia_id)); ?>" />
								<input type="hidden" name="user_id" id="user_id" value="<?php echo htmlspecialchars(htmlentities($userid)); ?>" />
								<input type="hidden" name="c_case" id="c_case" value="<?php echo htmlspecialchars(htmlentities($c_case)); ?>" />
								<input type="hidden" name="miscellaneous_ref_no_post" id="miscellaneous_ref_no_post" value="<?php echo htmlspecialchars(htmlentities($miscellaneous_ref_no_post)); ?>" />
								<input type="hidden" name="report_party_type" id="report_party_type" value="<?php echo htmlspecialchars(htmlentities($report_party_type)); ?>" />
								<input type="hidden" name="subdoctype" id="subdoctype" value="<?php echo htmlspecialchars(htmlentities($subdoctype)); ?>" />


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

							<table>
							<tr>
							<td>
							<?php

							if ($c_case == 1 || $c_case == 4 || $c_case == 5) {

								$st2 = $db->prepare("select * from e_case_detail_fees where filing_no=? ");
								$st2->bindParam(1, $tokenno, PDO::PARAM_STR);
								$st2->execute();
								$i = 0;
								while ($row2 = $st2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
									$E_sec_id = $row2['sec_id'];
									$E_act_id = $row2['act_id'];
									$inter_act_id = $row2['inter_act_id'];


									?>
									<input type="hidden" name='found_all[]' value='<?php echo $E_sec_id; ?>' />
									<?php
								}
								?>
								</td>
								</tr>


								</table>
								<?php

								if ($E_sec_id == '33') {
									$E_sec_id = '32';
								}
								if ($E_sec_id == '31') {
									$E_sec_id = '32';
								}


								if ($E_sec_id == '34' or $E_sec_id == '35' or $E_sec_id == '36' or $case_type == '14' or $case_type == '15') {



									?>

									<div class="accordion-section">
									<a class="accordion-section-title" href="#accordion-2" id="draft_id"><?php if ($case_type != 15 && $case_type != 14) {																					echo htmlspecialchars($act_name_all);							} else if ($case_type == 15) {										echo 'Second Motion';									} else if ($case_type == 14) {										echo 'First Motion';										} ?></a>
									<div id="accordion-2" class="accordion-section-content">

									<table border="1">
									<tr>
									<td colspan="12"></td>
									</tr>
									<tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
									<td width="3%">Sr. No</td>
									<td width="67%">Description</td>
									<td width="6%">Defect Free</td>
									<td width="16%">Comments</td>
									</tr>
									<tr>
									<td colspan="12">

									<?php
																//$status=$_REQUEST['status'];

									?>

									<?php


									$display = 'TRUE';

									if ($case_type != '15' || $case_type != '14') {

										$sth = $db->prepare("select * from master_scrutiny_local where  link_id=?  order by id_serno ASC ");

										$sth->bindParam(1, $E_sec_id, PDO::PARAM_STR);
										$sth->execute();
									}
									if ($case_type == '15' || $case_type == '14') {
										$sth = $db->prepare("select * from master_scrutiny_local where  case_type=?  order by id_serno ASC ");
																	//$sth->bindParam(1, $location_access, PDO::PARAM_STR);
										$sth->bindParam(1, $case_type, PDO::PARAM_STR);
										$sth->execute();
									}


																//}
									$i = 0;
									$j = 1;
									while ($rowa = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										$id_check = $rowa['id_serno'];
										$check_list = $rowa['name'];
										$display_all = $rowa['display'];
										$obj_sub_code = 1;
										$level_level = 0;

																	//check draft record exist or not

										if ($c_case != 4) {

																		//$frm_type='R';
																		//$frm_type1='O';
											$check_record_exist = $db->prepare("select display from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and level_level=? and form_type IS NULL");
											$check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
											$check_record_exist->bindParam(2, $id_check, PDO::PARAM_STR);
											$check_record_exist->bindParam(3, $obj_sub_code, PDO::PARAM_STR);
											$check_record_exist->bindParam(4, $userid, PDO::PARAM_STR);
											$check_record_exist->bindParam(5, $level_level, PDO::PARAM_STR);
																		//$check_record_exist->bindParam(6, $frm_type, PDO::PARAM_STR);
																		// $check_record_exist->bindParam(7, $frm_type1, PDO::PARAM_STR);
											$check_record_exist->execute();
											$display_for_chk = $check_record_exist->fetchColumn();
											if ($display_for_chk) {
												$sql = "select status,comment,scrutiny_correction from $schemas.draft_objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code' and user_id='$userid' and level_level='$level_level' and form_type IS NULL";
											} else {

												$sql = "select status,doc_comments,scrutiny_correction from $schemas.objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code' and form_type IS NULL";
											}
										}

										if ($c_case == 4) {
																		//$form_type='R';
											if ($subdoctype == '17') {
																			//$subdocname='Report';
												$form_type = 'R';
											}
											if ($subdoctype == '33') {
																			//$subdocname='Order';
												$form_type = 'O';
											}

											$check_record_exist = $db->prepare("select display from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and level_level=? and miscellaneous_ref_no=? and form_type=?");
											$check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
											$check_record_exist->bindParam(2, $id_check, PDO::PARAM_STR);
											$check_record_exist->bindParam(3, $obj_sub_code, PDO::PARAM_STR);
											$check_record_exist->bindParam(4, $userid, PDO::PARAM_STR);
											$check_record_exist->bindParam(5, $level_level, PDO::PARAM_STR);
											$check_record_exist->bindParam(6, $miscellaneous_ref_no_post, PDO::PARAM_STR);
											$check_record_exist->bindParam(7, $form_type, PDO::PARAM_STR);

											$check_record_exist->execute();

											$display_for_chk = $check_record_exist->fetchColumn();
											if ($display_for_chk) {
												$sql = "select status,comment,scrutiny_correction from $schemas.draft_objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code'and user_id='$userid' and level_level='$level_level' and miscellaneous_ref_no='$miscellaneous_ref_no_post' and form_type='$form_type'";
											} else {

												$sql = "select status,doc_comments,scrutiny_correction from $schemas.objection_details where filing_no='$filing_no' and objection_code='$id_check' and objection_sub_code='$obj_sub_code' and miscellaneous_ref_no='$miscellaneous_ref_no_post' and form_type='$form_type' ";
											}
										}

										$stq = $db->prepare($sql);
																	//$stq->bindParam(1, $filing_no, PDO::PARAM_INT);
																	//$stq->bindParam(2, $id_check, PDO::PARAM_INT);
										$stq->execute();
																	//$status = $stq->fetchColumn();
										while ($row_stq = $stq->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
											$status = $row_stq['status'];
											$comment_other = $row_stq['comment'];
											$cause_no = $row_stq['scrutiny_correction'];
											$explode_cause_no = explode(',', $cause_no);
										}


										?>
										<tr>

										<td width="5%">

										<?php echo htmlspecialchars($j); ?>

										</td>


										<td width="60%">
										<font color=" #1c2833 "><?php echo htmlspecialchars($check_list); ?></font>



										<td width="7%">

										<?php

																	//if($display_all  == '0')
																	// {
										?>
										<select name="status" id="status" class="statuscheckother" onchange="myFunction()" style="
										background-color: silver;
										color: #000000;
										padding: 7px 7px;
										margin: 2px 0;
										border: none;
										border-radius: 4px;
										cursor: pointer;">
										<option value="YES" <?php if (trim($status) == 'YES') echo "selected"; ?>>YES</option>
										<option value="NO" <?php if (trim($status) == 'NO') echo "selected"; ?>>
										<font color="red">NO</font>
										</option>
										<option value="NA" <?php if (trim($status) == 'NA') echo "selected"; ?>>NA</option>

										</select>
										<input type="hidden" id="id_check_draft_other" value='<?php echo htmlspecialchars($id_check); ?>' name="id_check_draft_other[]">
										<?php
																	//}
										?>
										</td>

										<td width="20%">
										<?php
																	//if($display_all == '0')
																	// {
										?>
										<textarea class="comment_other" name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" style="background-color: silver;"><?php echo htmlspecialchars($comment_other); ?></textarea>
										<?php
																	// } 
										?>
										</td>


										</tr>

										<input type="hidden" name="id_check[]" maxlength="3" readonly="readonly" value="<?php echo htmlspecialchars(htmlentities($id_check . "," . 'IBC1')); ?>" size="2" />

										<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars(htmlentities($filing_no)); ?>" />

										<?php
										$j++;
										$i++;
									}
								}
								?>



								<?php
							}

													//aaa
							?>
							</table>
							</div>
							<div class="" id="pdf_tab">
							<div class="main">
							<div class="accordion">
							<div id="view_pdf">

							</div>
							</div>
							</div>
							</div>
							

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

													if ($c_case == 2) {

														$st1 = $db->prepare("select * from scrutiny_status where id='6'");

													} else {

														$st1 = $db->prepare("select * from scrutiny_status where is_active = true order by id");

													}

													$st1->execute();

													while ($row = $st1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

														$e_name = $row['cause_name'];

														$cause_no = $row['cause_no'];

														if (!is_array($explode_cause_no)) {

															$explode_cause_no = [];

														}

														?>

										<option value="<?php echo $cause_no; ?>"

											<?php

														if (in_array($cause_no, $explode_cause_no)) {   $sc_marked_defects .= $e_name.' , ';        print "selected";}?>>

											<?php echo strtoupper($e_name); ?></option> <?php

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

										<input type="checkbox"

											id="doc_<?php echo $v['documentuploadmodelid'] ?>" name="docs[]"

											value="<?php echo $v['documentuploadmodelid'] ?>"

											<?php  echo (in_array($v['documentuploadmodelid'], $defect_docs))?'checked':''; ?>>

										<label for="vehicle1">

											<?php echo $v['docum_type'].'  /  '.$v['filename']; ?></label><br>

										<?php }

								?>

									</div>

								</div>

							</div>



							<table style="margin: 0 0 30px 30px;">

							<tr>

							<td><?php echo (!empty(trim($sc_marked_defects)))?'Defects in : '.$sc_marked_defects:''; ?></td>
							<td>
							Notification Date: <input id="in_notification_date" type="text" name="notification_date" readonly="readonly" value="<?php echo htmlspecialchars(htmlentities($cur_date1)); ?>" size="10" maxlength="10" />
							</td>
							<td>
							<input type="checkbox" value="0" id="agree" name="agree" required="required">
							<b>
							<font color="red">Scrutiny Completed</font>
							</b>
							</td>
							<?php if ($patially_defective == '1') { ?>
								<td>
								<input type="checkbox" value="1" id="list_with_defect" name="list_with_defect">
								<b>
								<font color="red">List With Defects</font>
								</b>
								</td>
								<td><button type='button' class='btn btn-success' data-toggle="modal" data-target="#myModal_defect_remak">View Defect Remark</a></td>
								<?php } ?>
								</tr>
								<div class="rr">

								</div>
								</table>

								<table width="70%" align="center">


								<td style="display: block;padding-top: 6px;" colspan="4" id="befornotification1"></td>
								<td style="display: block; " id="befornotification">


								<select id="in_searchby" name="searchby" style="display: block">

								<option value="1">DEFECT FREE</option>
								<option value="2">DEFECTIVE</option>


								</select>
								</td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<?php
								if ($_SESSION['menuaccess_codeall'] == '20')
									$button_text = "Reject";
								else
									$button_text = "Proceed Further";
								?>
								<td> <input id="submit_final" type="submit" name="submit_final" class="submit" value="<?php echo $button_text; ?>" style="
								background-color: green;
								color: #FFFFFF;
								padding: 5px 5px;
								margin: 16px 15;
								border: none;
								border-radius: 4px;
								cursor: pointer;	
								" onClick="return defect_submit();" />

								</td>
								<!--<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="save_draft" type="button" name="save_draft" class="submit" value="SAVE AS DRAFT" style="
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


								<?php } ?>

								</form>




								<!-- /.box-footer-->
								</div>
								</div>
								<!-- /.box -->
								<div class="alert alert-success" style="display: none;">

								<div class="demo-box" id="ajaxButton"></div>

								</div>
								</section>
								<!-- /.content -->
								</div>




								<script>
								function viewpdf_new(pdfpath) {
									$("#objection_tab").removeClass("col-sm-12 col-md-12 col-lg-12").addClass("col-sm-6 col-md-6 col-lg-6");
									var loader = "<center><img src='../loader/loader.gif'></img></center>";
									var height = $("#accordion-1").height();
									height = height + 20;
									$.ajax({
										type: "POST",
										url: "../scrutiny/test_pdf.php",
										data: {
											path: pdfpath
										},
										beforeSend: function() {

											$("#view_pdf").css('height', height);
											$("#view_pdf").html(loader);
										},
										success: function(data) {

											$("#view_pdf").css('height', height);
											$("#view_pdf").html(loader);
											$("#view_pdf").html(data);
						//alert("success");
										},
										error: function(textStatus, errorThrown) {
											$("#view_pdf").html('');
											alert("error");
										}

									});
								}

								$(document).on('click', "#close_pdf_new", function() {
									$("#pdf_tab").removeClass("col-sm-6 col-md-6 col-lg-6");
									$("#objection_tab").removeClass("col-sm-6 col-md-6 col-lg-6").addClass("col-sm-12 col-md-12 col-lg-12");
									$("#view_pdf").css('height', '1');
									$("#view_pdf").html('');
								});

								document.getElementById("cause_no").addEventListener("change", load_documents());

								function load_documents() {
									var filing_no = '<?php echo $filing_no_fou; ?>';
								}
								</script>
								<form action="" method="POST" target="_blank" id="frm">
								<input type="hidden" id="itemno1" name="itemno" value="" />
								<input type="hidden" id="applno1" name="applno" value="" />
								<input type="hidden" id="courtno1" name="courtno" value="" />
								<input type="hidden" id="caseno1" name="caseno" value="" />
								<input type="hidden" id="casetype1" name="casetype" value="" />
								<input type="hidden" id="partyname1" name="partyname" value="">
								<input type="hidden" id="title1" name="title" value="">
								<input type="hidden" id="status1" name="status" value="">
								<input type="hidden" id="j_key1" name="j_key" value="">
								<input type="hidden" id="j_securityKey1" name="j_securityKey" value="">

								</form>


								<!-- Modal -->
								<div id="myModal_defect_remak" class="modal fade" role="dialog">
								<div class="modal-dialog">

								<!-- Modal content-->
								<div class="modal-content">
								<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Defect Remark</h4>
								</div>
								<div class="modal-body">
								<p><?php echo $patially_defect_remark; ?></p>
								</div>
								<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
								</div>

								</div>
								</div>
								<!-- /.content-wrapper -->


								<?php
								include '../infooter.php';
								?>
								<script>
								function OpenDMSForm(step, filing_no, dms_type, misc_no) {
									document.getElementById("step").value = step;
									document.getElementById("filingNb").value = filing_no;
									document.getElementById("dms_type").value = dms_type;
									document.getElementById("misc_no").value = misc_no;
									document.getElementById("frm_dms").submit();

								}
								function previewCIS(filing_no){
									document.getElementById("filling_no").value = filing_no;
									document.getElementById("previewCIS").submit();
								}

								function OpenPreviewReceipt(filing_no){
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

								<form action="https://uat-efiling.gstat.gov.in/efiling/previewReceipt.drt" method="POST" target="_blank" id="previewReceipt">
								<input type="hidden" id="fillingNu" name="filingNo" value="" />
								</form>
								<?php } ?>

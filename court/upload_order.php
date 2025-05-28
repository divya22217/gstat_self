
<?php 
session_start();
ob_start();
include("../db_inc1.php");
include("../db_inc2.php");
include("../formkey/formkey.class.php");
date_default_timezone_set("Asia/Kolkata");

$bench_no='';
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];

$schemas=htmlspecialchars($_SESSION['schema_name']);

include '../inheader.php';
//include '../insidebar.php';

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
	
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 

?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<title>Order Upload</title>

<script src="../bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->
<script src="../dist/js/adminlte.min.js"></script>

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css"/>
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
<script src="../src/calendar.js"></script>
<script src="../js/sweetalert.min.js"></script>
</head>

<body>   
<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
			<div class="box box-success">
				<div class="box-body">
    <!-- Content Header (Page header) -->
					<section class="content-header">
						<center><b>Order Upload</b></center>
					</section>
					<?php
						$msghash=$_REQUEST['hash'];
						if($msghash !='')
						{
							$msg1=(base64_decode($msghash));
						?>
						<div class="form-group row">
						<center>
							<a href="javascript:void(0);" ><b><font color = 'red'  ><?php echo $msg1;?></font></b></a></u>
						</center>
						</div>
						<?php
						}
						?>
							<div class="form-inline" id="search_form">
						  <div class="form-group">
							<?php 
							$dispaly_Case_type = 't';
								$search_case_types = $db->prepare("select * from case_type where status=? order by id asc");
								$search_case_types->bindParam(1, $dispaly_Case_type, PDO::PARAM_INT);
								$search_case_types->execute();
								$search_case_types = $search_case_types->fetchAll();
							?>
							<label>Case Type : </label>
							<select class="form-control" id="search_case_type">
								<option value=''>Select Case Type</option>
								<?php foreach($search_case_types as $key=>$search_case_type) {?>
								<option value="<?php echo $search_case_type['id']; ?>"><?php echo $search_case_type['case_type_desc']; ?></option>
								<?php } ?>
							</select>
						  </div>
						  <div class="form-group">
							<label>Case No : </label>
							<input type="number" class="form-control" placeholder="Enter Case Number" onKeyPress="return number_validation(this.id,7)" name="case_no" id="case_no" autocomplete="off" required="required">
						  </div>
						  <div class="form-group">
							<label>Case Year : </label>
							<input type="number" class="form-control" placeholder="Enter Case Year" onKeyPress="return number_validation(this.id,4)" name="case_year" id="case_year" autocomplete="off" required="required">
						  </div>
						  <button type="button" class="btn btn-primary" onClick="return search_data();">search</button>
						  <button type="button" class="btn btn-warning" onClick="window.location.reload();">Reset</button>
						
						 <div id="show_order_detail">
						 </div>
						 <br/>
						 <div id="show_case_detail">
							
						 </div>
						 <br/>
						 
						 
						 <div id="order_form"  class="panel-group">
							
						 </div>
				
				</div>
			</div>
		</div>
	<script src="../src/calendar.js"></script>
	<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script>
	
	/* $(document).ready(function(){
		$('#info_modal').modal({
		backdrop: 'static',
		keyboard: false
	})
	}); */

	function agree(){
		$('#info_modal').modal('hide');
	}
	
	function number_validation(element_id,number_length){
	  //called when key is pressed in textbox
	  $("#"+element_id).keypress(function (e) {
		var filing_no = $("#"+element_id).val();
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
		if(filing_no.length >= number_length){
			return false;
		}
	   });
	};


// File type validation
/* $(document).on("change","#upload_order",function() {
    var file = this.files[0];
    var fileType = file.type;
    var match = ['application/pdf'];
    if(!((fileType == match[0]))){
        swal('','Sorry, only PDF files are allowed to upload.','warning');
        $("#upload_order").val('');
        return false;
    }
}); */

function IsValidJSONString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function search_data(){
	var case_type = $("#search_case_type").val();
	var case_no = $("#case_no").val();
	var case_year = $("#case_year").val();
	if(case_type == '' || case_no == '' || case_year == ''){
		swal("","please enter all details","warning");
		return false;
	}
	$("#show_case_detail").html("<tr><td colspan='7'><center>loading......<center></td></tr>");
	$.ajax({
	type: "POST",
	url: "upload_order_ajax.php",
	data: {type:'search_case',case_type:case_type,case_no:case_no,case_year:case_year},
	success: function (data) {
		 var IsValidJSON = IsValidJSONString(data);
		 if(IsValidJSON){
			 var obj = JSON.parse(data);
			if(obj.status == '0'){
				$("#show_case_detail").html("");
				swal("",obj.message,"warning"); 
				return false;
			 } 
		 }else{
		   $("#show_case_detail").html(data);
		  // $("#enter_case_info").css("display","none");
		   $("#case_no").attr("disabled","disabled");
		   $("#case_year").attr("disabled","disabled");
		   $("#search_case_type").attr("disabled","disabled");
		   search_order_data();
		 }
	},
	error: function (textStatus, errorThrown) {
	  console.log(textStatus);
	   alert(errorThrown);
	}

}); 
	
}

function get_basic_info(element){
	if($(element).prop("checked") == true){
		var case_no = $("#case_no").val();
		var case_year = $("#case_year").val();
		var case_type = $("#search_case_type").val();
		var regis_date = $("#regis_date").val();
		var main_case_filing_no = $('#main_case_filing_no').val();
			$.ajax({
			type: "POST",
			url: "upload_order_ajax.php",
			data: {type:'order_form',case_no:case_no,case_year:case_year,case_type:case_type},
			success: function (data) {
				$("#order_form").html(data);
			},
			error: function (textStatus, errorThrown) {
			   alert("error");
			}

		});
	}
	else if($(element).prop("checked") == false){
		$("#order_form").html("");
	}
}

function getbench(listing_date_id,div_id){
	listing_date = $("#"+listing_date_id).val();
	if(listing_date === '')
	{
		alert("please enter listing date");
		return false;
	}
	 $("#"+div_id).html("<center>loading......<center>");
	$.ajax({
	type: "POST",
	url: "../listing/getbench_by_listing_date.php",
	data: {listing_date:listing_date},
	success: function (data) {
	   $("#"+div_id).html(data);
	   $("#custom_coram_div").removeClass('hide');
	   //alert("success");
	},
	error: function (textStatus, errorThrown) {
	   alert("error");
	}

});
}
	
function set_custom_coram(element){
	if($(element).prop("checked") == true){
		$("#enter_coram_count").html('Number Of judges : <input type="number" id="number_of_judges" name="number_of_judges" onFocusOut="return set_judges(this.id)" required>');
	}
	else if($(element).prop("checked") == false){
		$("#enter_coram_count").html('');
	}
}

	
function set_judges(id){
	$("#coram").html('');
	var no_of_judges = $("#"+id).val();
	var judges = $("#judge_clone").html();
	var i;
	for (i = 1; i <= no_of_judges; i++) {
	  $("#coram").append(judges);
	  } 
}	

function search_order_data(){
	var case_type = $("#search_case_type").val();
	var case_no = $("#case_no").val();
	var case_year = $("#case_year").val();
	if(case_type == '' || case_no == '' || case_year == ''){
		swal("","please enter all details","warning");
		return false;
	}
	$("#show_order_detail").html("<tr><td colspan='7'><center>loading......<center></td></tr>");
	$.ajax({
	type: "POST",
	url: "../court/upload_order_ajax.php",
	data: {type:'search_order',case_type:case_type,case_no:case_no,case_year:case_year},
	success: function (data) {
		 var IsValidJSON = IsValidJSONString(data);
		 if(IsValidJSON){
			 var obj = JSON.parse(data);
			if(obj.status == '0'){
				$("#show_order_detail").html("");
				swal("",obj.message,"warning"); 
				return false;
			 } 
		 }else{
		   $("#show_order_detail").html(data);
		  // $("#enter_case_info").css("display","none");
		   /* $("#case_no").attr("disabled","disabled");
		   $("#case_year").attr("disabled","disabled");
		   $("#search_case_type").attr("disabled","disabled"); */
		 }
	},
	error: function (textStatus, errorThrown) {
	  console.log(textStatus);
	   alert(errorThrown);
	}

}); 
	
}

function view_order(pdfpath)
  {
	var loader = "<center><img src='../loader/loader.gif'></img></center>";
	   $.ajax({
            type: "POST",
            url: "../scrutiny/readpdf_file.php",
            data: {path:pdfpath},
			beforeSend: function() {
				$("#view_order_body").html(loader);
				$("#view_order").modal('show');
			},
            success: function (data) {
			$("#view_order_body").html(data);
			   //alert("success");
            },
            error: function (textStatus, errorThrown) {
				$("#view_order_body").html('');
				$("#view_order").modal('hide');
               alert("error");
            }

        });
  }
  
  function get_child_or_connected(listing_date,filing_no){
	  getbench('order_date','benches_new');
	var loader = "<center><img src='../loader/loader.gif'></img></center>";
	   $.ajax({
            type: "POST",
            url: "upload_order_ajax.php",
            data: {type:'get_child_or_connected',filing_no:filing_no,listing_date:listing_date},
			beforeSend: function() {
				$("#child_or_connected").html(loader);
			},
            success: function (data) {
			$("#child_or_connected").html(data);
			   //alert("success");
            },
            error: function (textStatus, errorThrown) {
				$("#child_or_connected").html('');
               alert("error");
            }

        });
  }
		
</script>

<div id="view_order" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:90%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Order</h4>
      </div>
      <div class="modal-body" id="view_order_body" style="height:500px;">
        <p>Loading.........</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="info_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body" id="view_listing_modal_body">
        <p><b>This Module should be used only when proceeding done by court master & order to be uploaded by steno/other users</b></p>
		<input type="checkbox" onClick="return agree();" id="agree"> I agree
      </div>
      <div class="modal-footer">
        <a href="../index.php"><button type="button" class="btn btn-default">Close</button></a>
      </div>
    </div>

  </div>
</div>
</body>

<?php } ?>
  
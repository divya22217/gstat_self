<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");

/*  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */ 

include("../db_inc1.php");
include("../inheader.php");
include("../custom/custom_function.php");

$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] :'';
$year=htmlspecialchars(date("Y"));
$msg_ip .= "User IP : ".$_SERVER["REMOTE_ADDR"]."\r\n"; //Sender's IP
$user = $_SESSION['user'];
$username = $_SESSION['user_actual_name'];

if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}
else
 {
	
	
	
function remove_path($file, $path = UPLOAD_PATH) {
if(strpos($file, $path) !== FALSE) {
return substr($file, strlen($path));
}
}

$schemas=htmlspecialchars($_SESSION['schema_name']);
$user_id=$_SESSION['id'];
?>





	

	<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
	<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
	<script src="../plugins/jQueryUI/jquery-ui.js"></script>
	<script src="../plugins/jQueryUI/date.js"></script>
	<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	
<link rel="stylesheet" type="text/css" href="../includes/highslide/highslide.css" />
<script type="text/javascript" src="../includes/highslide/highslide-with-html1.js"></script>
</head>

	<div class="content-wrapper">
	<!-- Content Header (Page header) -->
		<section class="content">
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
				<input type="text" class="form-control" placeholder="Enter Case Number" name="case_no" id="case_no" autocomplete="off" required="required">
			  </div>
			  <div class="form-group">
				<label>Case Year : </label>
				<input type="number" class="form-control" placeholder="Enter Case Year" onKeyPress="return number_validation(this.id,4)" name="case_year" id="case_year" autocomplete="off" required="required">
			  </div>
			  
			  <button type="button" class="btn btn-primary" onClick="return search_data();">search</button>
			  <button type="button" class="btn btn-warning" onClick="window.location.reload();">Reset</button>
			</div> 
			
			<div id="show_case_detail">
			
			</div>

		</section>
		</div>
		
<script>
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
	var order_date = $("#order_date").val();
	if(case_type == '' || case_no == '' || case_year == ''){
		swal("","please enter all details","warning");
		return false;
	}
	$("#show_case_detail").html("<tr><td colspan='7'><center>loading......<center></td></tr>");
	$.ajax({
	type: "POST",
	url: "upload_order_ajax.php",
	data: {type:'search_case_to_restore',case_type:case_type,case_no:case_no,case_year:case_year},
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
		 }
	},
	error: function (textStatus, errorThrown) {
	  console.log(textStatus);
	   alert(errorThrown);
	}

}); 
	
}

function search_restored_application(filing_no){
	var case_type = $("#search_restored_case_type").val();
	var case_no = $("#restored_case_no").val();
	var case_year = $("#restored_case_year").val();
	if(case_type == '' || case_no == '' || case_year == ''){
		swal("","please enter all details","warning");
		return false;
	}
	$("#restoration_form_2").html("<tr><td colspan='7'><center>loading......<center></td></tr>");
	$.ajax({
	type: "POST",
	url: "upload_order_ajax.php",
	data: {type:'search_restored_application',case_type:case_type,case_no:case_no,case_year:case_year,filing_no:filing_no},
	success: function (data) {
		 var IsValidJSON = IsValidJSONString(data);
		 if(IsValidJSON){
			 var obj = JSON.parse(data);
			if(obj.status == '0'){
				$("#restoration_form_2").html("");
				swal("",obj.message,"warning"); 
				return false;
			 } 
		 }else{
		   $("#restoration_form_2").html(data);
		  // $("#enter_case_info").css("display","none");
		   $("#restored_case_no").attr("disabled","disabled");
		   $("#restored_case_year").attr("disabled","disabled");
		   $("#search_restored_case_type").attr("disabled","disabled");
		 }
	},
	error: function (textStatus, errorThrown) {
	  console.log(textStatus);
	   alert(errorThrown);
	}

}); 
	
}

function get_restoration_form(restoration_reason,filing_no){
	$("#restoration_form").html('');
	$.ajax({
	type: "POST",
	url: "upload_order_ajax.php",
	data: {type:'get_form_on_restoration_selection',restoration_reason:restoration_reason,filing_no:filing_no},
	success: function (data) {
		 var IsValidJSON = IsValidJSONString(data);
		 if(IsValidJSON){
			 var obj = JSON.parse(data);
			if(obj.status == '0'){
				swal("",obj.message,"warning"); 
				return false;
			 } 
		 }else{
		   $("#restoration_form").html(data);
		 }
	},
	error: function (textStatus, errorThrown) {
	  console.log(textStatus);
	   alert(errorThrown);
	}

}); 
}

$(document).ready(function(e){

    // Submit form data via Ajax
    $(document).on('submit','#submit_restoration_form', function(e){
		e.preventDefault();
		var formdata = new FormData(this);
		swal({
            title: "Are you sure ??",
            text: "Do you want to restore case", 
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
		.then((willDelete) => { 
             if (willDelete) {
				$.ajax({
					type: "POST",
					url: "upload_order_ajax.php",
					data: formdata,
					contentType: false,
					cache: false,
					processData:false,
					dataType: 'json',
					beforeSend:function(){ 
						$('#submit_restoration_form_btn').attr("disabled","disabled");
						$('#submit_restoration_form').css("opacity",".5");
					},
					success: function (response) {
						$('#submit_restoration_form').css("opacity","");
						$("#submit_restoration_form_btn").removeAttr("disabled");
						if(response.status == 0){
							swal('',response.message,'warning');
						}
						else{
							swal('',response.message,'success');
							location.reload(true);
						}
					},
					error: function (textStatus, errorThrown) {
					  console.log(textStatus);
					   alert(errorThrown);
					}

				}); 
				return false;
			 }else{
			 }
		});			
    });
	
	
	 
});
</script>
<!-- /////////////////////////////code by preeti starts here ////////////////////////////// -->
<script>
	function fileValidation(e) {
    console.log(e.target.files[0]);
    var fileInput =
      document.getElementById('upload_order');

    var filePath = fileInput.value;
      // Allowing file type
      var allowedExtensions =
        /(\.pdf)$/i;

      if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type ,Only .pdf files are allowed');
        fileInput.value = '';
        return false;
      }
    }
	</script>
<!-- ///////////////////////////////////code by preeti ends here////////////////// -->
<script src="../js/sweetalert.min.js"></script>
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

<?php } ?>
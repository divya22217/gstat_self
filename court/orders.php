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
//include '../insidebar.php';
//include '../classes/Editcase.class.php';
	//$edit_Case_obj = new Editcase();

$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] :'';
$year=htmlspecialchars(date("Y"));
$msg_ip .= "User IP : ".$_SERVER["REMOTE_ADDR"]."\r\n"; //Sender's IP
$user = $_SESSION['user'];
$username = $_SESSION['user_actual_name'];
$user_id=$_SESSION['id'];
if($user_id == '77' || $user_id == '194')
 {
	
	
	
function remove_path($file, $path = UPLOAD_PATH) {
if(strpos($file, $path) !== FALSE) {
return substr($file, strlen($path));
}
}

$schemas=htmlspecialchars($_SESSION['schema_name']);

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
				$dispaly_city = true;
					$cities = $db->prepare("select * from mater_location_city where display=? and city_id = ? order by city_id desc");
					$cities->bindParam(1, $dispaly_city, PDO::PARAM_BOOL);
					$cities->bindParam(2, $_SESSION['location'], PDO::PARAM_STR);
					$cities->execute();
					$cities = $cities->fetchAll();
				?>
				<label>Location : </label>
				<select class="form-control" id="location">
					<?php foreach($cities as $key=>$city) {?>
					<option value="<?php echo $city['schema_name'].'/'.$city['city_id']; ?>"><?php echo $city['city_name']; ?></option>
					<?php } ?>
				</select>
			  </div>
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
			  <!--<div class="form-group">
				<label>Order Date : </label>
				<input type="text" id="order_date" name="order_date" class="datepicker"  size="8" autocomplete="off" maxlength="10" />
			  </div>-->
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
	var location = $("#location").val();
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
	data: {type:'search_order_to_update',case_type:case_type,case_no:case_no,case_year:case_year,order_date:order_date,location_info:location},
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
  
  function delete_order_dialog(filing_no,item_no){
	  $("#order_crud").modal('show');
			$.ajax({
			type: "POST",
			url: "upload_order_ajax.php",
			data: {type:'delete_order_dialog',filing_no:filing_no,item_no:item_no},
			success: function (response) {
					var IsValidJSON = IsValidJSONString(response);
					 if(IsValidJSON){
						 var obj = JSON.parse(response);
						if(obj.status == '0'){
							$("#order_crud_body").html("");
							$("#order_crud").modal('hide');
							swal("",obj.message,"warning"); 
							return false;
						 } 
					 }else{
					   $("#order_crud_body").html(response);
					   
					  
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
		$(document).on('submit','#delete_order_form', function(e){
			e.preventDefault();
			var location = $("#location").val();
			var postData = $(this).serialize()+ "&location_info=" + location;
	
			swal({
            title: "Are you sure want to delete ?",
            text: "", 
            icon: "warning",
            buttons: true,
            dangerMode: false,
			})
			.then((willDelete) => { 
				 if (willDelete) {	
					$.ajax({
						type: "POST",
						url: 'upload_order_ajax.php',
						data: postData,
						dataType: 'json',
						beforeSend:function(){ 
							$('#delete_order_btn').attr("disabled","disabled");
							$('#delete_order_from').css("opacity",".5");
						},
						success: function (response) {
							$('#delete_order_from').css("opacity","");
							$("#delete_order_btn").removeAttr("disabled");
							if(response.status == 0){
								swal('',response.message,'warning');
								$("#order_crud").modal('hide');
							}
							else{
								swal('',response.message,'success');
								$("#order_crud").modal('hide');
								search_data();
								
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
	
 function edit_order_dialog(filing_no,item_no){
	  $("#order_crud").modal('show');
	  var location = $("#location").val();
			$.ajax({
			type: "POST",
			url: "upload_order_ajax.php",
			data: {type:'edit_order_dialog',filing_no:filing_no,item_no:item_no,location_info:location},
			success: function (response) {
					var IsValidJSON = IsValidJSONString(response);
					 if(IsValidJSON){
						 var obj = JSON.parse(response);
						if(obj.status == '0'){
							$("#order_crud_body").html("");
							$("#order_crud").modal('hide');
							swal("",obj.message,"warning"); 
							return false;
						 } 
					 }else{
					   $("#order_crud_body").html(response);
					   var order_date = $("#order_date").val();
					   getbench(order_date);
					  
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
		$(document).on('submit','#update_order_form', function(e){
			e.preventDefault();
			var location = $("#location").val();
			var postData = $(this).serialize()+ "&location_info=" + location;
	
			swal({
            title: "Are you sure want to update ?",
            text: "", 
            icon: "warning",
            buttons: true,
            dangerMode: false,
			})
			.then((willDelete) => { 
				 if (willDelete) {	
					$.ajax({
						type: "POST",
						url: 'upload_order_ajax.php',
						data: postData,
						dataType: 'json',
						beforeSend:function(){ 
							$('#update_order_btn').attr("disabled","disabled");
							$('#update_order_form').css("opacity",".5");
						},
						success: function (response) {
							$("#update_order_form").css("opacity","");
							$("#update_order_btn").removeAttr("disabled");
							if(response.status == 0){
								swal('',response.message,'warning');
								$("#order_crud").modal('hide');
							}
							else{
								swal('',response.message,'success');
								$("#order_crud").modal('hide');
								search_data();
								
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

function getbench(listing_date){
	if(listing_date === '')
	{
		alert("please enter listing date");
		return false;
	}
	 $("#benches").html("<center>loading......<center>");
	$.ajax({
	type: "POST",
	url: "../listing/getbench_by_listing_date.php",
	data: {listing_date:listing_date},
	success: function (data) {
		if (!$.trim(data)){
			$("#benches").html('<center><b style="color:red;">Bench  not found</b></center>');
		}else{
	   $("#benches").html(data);
		}
	   //alert("success");
	},
	error: function (textStatus, errorThrown) {
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

<div id="order_crud" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:90%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Order</h4>
      </div>
      <div class="modal-body" id="order_crud_body" >
        <p>Loading.........</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script src="../js/sweetalert.min.js"></script>
<?php 
} else {
	echo "you Can't access this page";
	header("Location:index.php");
	die;
}?>
<?php

 /* ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL); */

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include "../db_inc1.php";
require_once '../custom/custom_function.php';
$schemas = htmlspecialchars($_SESSION['schema_name']);
 $location_code = $_SESSION['location'];
 $user_id=htmlspecialchars($_SESSION['id']);
$user_type = $_SESSION['menuaccess_codeall'];
$bench_loc  = isset($_REQUEST['bench_loc']) ? $_REQUEST['bench_loc'] : '';
$search_case_type  = isset($_REQUEST['search_case_type']) ? $_REQUEST['search_case_type'] : '';
$zone_loc  = isset($_REQUEST['zone_loc']) ? $_REQUEST['zone_loc'] : $schemas;
if($zone_loc != 'delhi'){
	$bench_loc = '';
}


 

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", true, true);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    include '../inheader.php';
    //include '../insidebar.php';
    ?>
<link rel="stylesheet" type="text/css" href="../datatable/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../datatable/css/buttons.dataTables.min.css">

<link href="../dist/css/sweetalert.css" rel="stylesheet"/>
<script src="../dist/js/sweetalert.min.js"></script>
<script src="../dist/js/sweetalert-dev.min.js"></script>
<style>
.load_container {
    background: rgba(0, 0, 0, 0.50);
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    z-index: 99;
    left: 0;
}

.load_container .loader {
    display: block;
    width: 60px;
    height: 60px;
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    margin: auto;
}

.datatable-scroll-wrap {
    width: 100%;
    overflow-y: auto;
    padding-top: 20px;
}

.table>thead>tr>th {
    white-space: nowrap;
    font-size: 12px;
    text-transform: uppercase;
    background: #f0f0f0;
    border-top: 1px solid #ccc !important;
    border-bottom: 1px solid #ccc;
    border-left: 1px solid #ccc;
}

.table>thead>tr>th:last-child {
    border-right: 1px solid #ccc;
}

table.dataTable td {
    font-size: 13px;
    border-left: 1px solid #f4f4f4;
}

table.dataTable td:last-child {
    border-right: 1px solid #f4f4f4;
}

.btn-light {
    background-color: #00a65a;
    border-color: #008d4c;
    color: #fff;
}

.btn-light:hover,
.btn-light:active,
.btn-light.hover {
    background-color: #008d4c;
    color: #fff;
}

.dataTables_length {
    margin-top: 7px;
    margin-left: 20px;
}

.dataTables_filter {
    margin-top: 7px;
}

p {
    display: block;
    padding: 9.5px;
    margin: 0 0 10px;
    font-size: 13px;
    line-height: 1.42857143;
    color: #333;
    word-break: break-all;
    word-wrap: break-word;
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>


<?php
if($bench_loc == ''){
	$b_qyery = '';
}else{
$b_qyery = 'and cd.location_code = ? ';
}

if($search_case_type == ''){
	$ct_qyery = '';
}else{
$ct_qyery = "and cd.case_type = $search_case_type ";
}


$query = "select cd.case_no,cd.case_year,cd.case_type,cd.location_code,ct.case_type_desc as case_type_name,bl.short_name, count(*)
from $zone_loc.case_detail as cd
left join case_type as ct on ct.id = cd.case_type
left join $zone_loc.bench_location as bl on bl.bench_location_code = cd.location_code
where cd.location_code is not null and cd.case_no is not null and cd.case_no != '' $b_qyery $ct_qyery
group by cd.case_no,cd.case_year,cd.case_type,cd.location_code,ct.case_type_desc,bl.short_name
HAVING count(*) > 1 order by cd.location_code,cd.case_year,cd.case_type,cd.case_no";
$data = $db->prepare($query);
if($bench_loc != ''){
$data->bindParam(1, $bench_loc, PDO::PARAM_STR);
}
$data->execute();
$all_data = $data->fetchAll();

?>

<div class="load_container">
    <img class="loader" src="../loading-indicator.gif">
</div>
<?php 


 
?>
<div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">


                <h2>
                    <center> Duplicate Cases</center>
                </h2>
                <hr>
				
				<form name="list_type_frm"  method="post">
                    <table class="table no-margin">
                        <thead>
                            <tr>
							<?php if($user_id == '9') { ?>
							<td valign="top" align="center" colspan="12">
								Bench : <select name='zone_loc' id='zone_loc' onchange="javascript:submitForm3();">
								<?php 
									 $query = "select * from mater_location_city";
									$res = $db->prepare($query);
									$res->execute();
									$all_zones = $res->fetchAll();
									foreach($all_zones as $zone) { ?>
										<option value='<?php echo $zone['schema_name']; ?>' <?php echo ($zone_loc == $zone['schema_name'])?'selected':''; ?> ><?php echo $zone['city_name']; ?></option>
								<?php	} 
								?>
                                </select>
                                </td>
							<?php } else { ?>
									<td valign="top" align="center" colspan="12">
								Bench : <select name='zone_loc' id='zone_loc' onchange="javascript:submitForm3();">
								<?php 
									 $query = "select * from mater_location_city";
									$res = $db->prepare($query);
									$res->execute();
									$all_zones = $res->fetchAll();
									foreach($all_zones as $zone) { 
										if($schemas == $zone['schema_name']){
									?>
										<option value='<?php echo $zone['schema_name']; ?>' <?php echo ($zone_loc == $zone['schema_name'])?'selected':''; ?> ><?php echo $zone['city_name']; ?></option>
									<?php	} }
								?>
                                </select>
                                </td>
							<?php } ?>
								
								<td valign="top" align="center" colspan="12">
								Case Type : <select name='search_case_type' id='search_case_type' onchange="javascript:submitForm3();">
								<option value="" <?php echo ($search_case_type == '')?'selected':''; ?> >ALL</option>
								<?php 
									 $query = "select * from case_type where status = 't'";
									$res = $db->prepare($query);
									$res->execute();
									$all_case_types = $res->fetchAll();
									foreach($all_case_types as $case_types) { ?>
										<option value='<?php echo $case_types['id']; ?>' <?php echo ($search_case_type == $case_types['id'])?'selected':''; ?> ><?php echo $case_types['case_type_desc']; ?></option>
								<?php	} 
								?>
                                </select>
                                </td>
								
							   <?php if($zone_loc == 'delhi') { ?>
                                <td valign="top" align="center" colspan="12">
								Location : <select name='bench_loc' id='bench_loc' onchange="javascript:submitForm3();">
								<option value="" <?php echo ($bench_loc == '')?'selected':''; ?> >ALL</option>
								<?php 
									 $query = "select * from $zone_loc.bench_location";
									$res = $db->prepare($query);
									$res->execute();
									$all_schema = $res->fetchAll();
									foreach($all_schema as $scm) { ?>
										<option value='<?php echo $scm['city_id']; ?>' <?php echo ($bench_loc == $scm['bench_location_code'])?'selected':''; ?> ><?php echo $scm['bench_location_name']; ?></option>
								<?php	} 
								?>
                                </select>
                                </td>
							   <?php } ?>
								
                            </tr>
							
							<tr align='center'><td><a href='duplicate_details.php' target='_blank'>View Detailed Report</a></td></tr>

                            </tbody>
                    </table>
                </form>
				
				
                <table id="case_details" class='table'>
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Case No</th>
                            <th>Case Year</th>
                            <th>Case Type</th>
                            <th>Location</th>
							<th>Count</th>
							<th>View</th>
                         
                        </tr>
                    </thead>
                    <tbody>
					<?php
					$sn = 0;
						foreach($all_data as $key=>$val){
								$sn++;
						?>
							<tr>
								<td><?php echo $sn; ?></td>
								<td><?php echo $val['case_no']; ?></td>
								<td><?php echo $val['case_year']; ?></td>
								<td><?php echo $val['case_type_name']; ?></td>
								<td><?php echo $val['short_name']; ?></td>
								<td><?php echo $val['count']; ?></td>
								<td>
									<button type='button' class='btn btn-sm btn-primary' id='view_cases_<?php echo $sn; ?>' onClick="return view_cases('<?php echo $sn; ?>','<?php echo $val['case_no']; ?>','<?php echo $val['case_year']; ?>','<?php echo $val['case_type']; ?>','<?php echo $val['location_code']; ?>','<?php echo $zone_loc; ?>');">View Cases</button>
								</td>
						
							</tr>
						<?php	} 
					?>
					</tbody>
					</table>
            </div>
        </div>
    </section>
</div>



<?php include '../infooter.php';?>
<script src="../datatable/js/jquery.dataTables.min.js"></script>
<script src="../datatable/js/dataTables.buttons.min.js"></script>
<script src="../datatable/js/buttons.flash.min.js"></script>
<script src="../datatable/js/jszip.min.js"></script>
<script src="../datatable/js/pdfmake.min.js"></script>
<script src="../datatable/js/vfs_fonts.js"></script>
<script src="../datatable/js/buttons.html5.min.js"></script>
<script src="../datatable/js/buttons.print.min.js"></script>
<!-- Theme JS files -->
<script src="../datatable/js/datatables_extension_buttons_html5.js"></script>
<script>

function submitForm3() {
    with(document.list_type_frm) {
        action = "duplicate.php";
        submit();
    }
}

$(document).ready(function() {
    $('#case_details').DataTable({
        "pageLength": -1,
		"lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
        buttons: {
            dom: {
                button: {
                    className: 'btn btn-light'
                }
            },
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        }
    });
});

function view_cases(sn,case_no,case_year,case_type,location_code,schema){
	   $.ajax({
            type: "POST",
            url: "duplicate_case_ajax.php",
            data: {type:'view_cases',sn:sn,case_no:case_no,case_year:case_year,case_type:case_type,location_code:location_code,schema:schema},
			beforeSend: function() {
				$("#view_cases_modal_body").html('loading....');
				$("#view_cases_modal").modal('show');
			},
            success: function (data) {
			$("#view_cases_modal_body").html(data);
			   //alert("success");
            },
            error: function (textStatus, errorThrown) {
				$("#view_cases_modal_body").html('');
				$("#view_cases_modal").modal('hide');
               alert("error");
            }

        });
}

function edit_case(filing_no,case_type,location_code,schema){
	   $.ajax({
            type: "POST",
            url: "duplicate_case_ajax.php",
            data: {type:'change_case_no_form',filing_no:filing_no,case_type:case_type,schema:schema,location_code:location_code},
			beforeSend: function() {
				$("#change_case_no_modal_body").html('loading....');
				$("#change_case_no_modal").modal('show');
			},
            success: function (data) {
			$("#change_case_no_modal_body").html(data);
			   //alert("success");
            },
            error: function (textStatus, errorThrown) {
				$("#change_case_no_modal_body").html('');
				$("#change_case_no_modal").modal('hide');
               alert("error");
            }

        });
}


function remove_case(filing_no,case_type,location_code,schema){
	swal({   
        title: "Are You Sure",
		text: "Do you want to remove this case "+filing_no+" permenantely",  
        type: "warning",   
        showCancelButton: true,   
        closeOnConfirm: false, 
		confirmButtonText: "Yes, Remove Case",
       // showLoaderOnConfirm: true,
        animation: "slide-from-top",   
		}, 
        function(isConfirm){  
             if (isConfirm) {
				$.ajax({
				type: "POST",
				url: "duplicate_case_ajax.php",
				data: {type:"remove_case",filing_no:filing_no,case_type:case_type,schema:schema,location_code:location_code},
				dataType: 'json',
				success: function (response) {
					if(response.status == 1){
						$("#rm_case"+filing_no).remove();
					  swal('',response.message,'success');
					}else{
					  swal('Oops!!',response.message,'error');
					}
				},
				error: function (textStatus, errorThrown) {
					console.log(textStatus);
				   swal("Oops!!",errorThrown,'error');
				}

				});
			 }else{
			 }
		});
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
	
	function closeModal(modalid){
		$("#"+modalid).modal('hide');
	}
	
	$(document).ready(function(e){

    // Submit form data via Ajax
    $(document).on('submit','#update_case_no_form', function(e){
		e.preventDefault();
		var formdata = new FormData(this);
		swal({   
        title: "Are You Sure",
		text: "Do you want to update case no",  
        type: "info",   
        showCancelButton: true,   
        closeOnConfirm: false, 
		confirmButtonText: "Yes, Update",
       // showLoaderOnConfirm: true,
        animation: "slide-from-top",   
		}, 
        function(isConfirm){  
             if (isConfirm) {
				$.ajax({
					type: "POST",
					url: "duplicate_case_ajax.php",
					data: formdata,
					contentType: false,
					cache: false,
					processData:false,
					dataType: 'json',
					beforeSend:function(){ 
						$('#update_case_no_button').attr("disabled","disabled");
						$('#update_case_no_form').css("opacity",".5");
					},
					success: function (response) {
						$('#update_case_no_form').css("opacity","");
						$("#update_case_no_button").removeAttr("disabled");
						if(response.status == 0){
							swal('',response.message,'warning');
						}
						else{
							swal('',response.message,'success');
							location.reload(true);
						}
					},
					error: function (textStatus, errorThrown) {
						$('#update_case_no_form').css("opacity","");
						$("#update_case_no_button").removeAttr("disabled");
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


$('.load_container').fadeOut(500);
</script>

<div class="modal fade" id="view_cases_modal" role="dialog">
    <div class="modal-dialog" style="width:100%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View</h4>
        </div>
        <div class="modal-body" id="view_cases_modal_body">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger view_cases_modal_class" onClick="return closeModal('view_cases_modal');">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
  <div class="modal fade change_case_no_modal_class" id="change_case_no_modal" role="dialog">
    <div class="modal-dialog" style="width:80%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Change Case No</h4>
        </div>
        <div class="modal-body" id="change_case_no_modal_body">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger change_case_no_modal_class" onClick="return closeModal('change_case_no_modal');">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<?php }?>
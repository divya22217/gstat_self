<?php
/*
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
*/
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include "../db_inc1.php";
require_once '../custom/custom_function.php';
$user_type = $_SESSION['menuaccess_codeall'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
 $location_code = $_SESSION['location'];
 $user_id=htmlspecialchars($_SESSION['id']);
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
	
	function display_filing_no($filing_no_display){
      $lastFour =  substr($filing_no_display,-4);
      $lastFive = substr($filing_no_display,-9,-4);
      $left = substr($filing_no_display,-16,-9);
      return $dis_fil_no = $left.'/<b>'.$lastFive.'/'.$lastFour.'</b>';

  }
    include '../inheader.php';
    //include '../insidebar.php';
    ?>
<link rel="stylesheet" type="text/css" href="../datatable/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../datatable/css/buttons.dataTables.min.css">
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
								Location : <select name='bench_loc' onchange="javascript:submitForm3();">
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
							
							<tr align='center'><td><a href='duplicate.php' target='_blank'>View List Of Duplicate Only</a></td></tr>

                            </tbody>
                    </table>
                
				
				
                <table id="case_details" class='table'>
                    <thead>
                        <tr>
                            <th>Sr. No</th>
							<th>Filing No</th>
                            <th>Case No</th>
                            <th>Location</th>
                            <th>Pet Name</th>
                            <th>Res Name</th>
                            <th>Filing Date</th>
                            <!--<th>Registration Date</th>-->
                        </tr>
                    </thead>
                    <tbody>
					<?php
					$sn = 0;
						foreach($all_data as $key=>$val){
							$key_plus = $key+1;
								$q = "select filing_no,dt_of_filing,regis_date,pet_name,res_name from $zone_loc.case_detail where case_type = ? and case_year = ? and case_no = ? and location_code = ?";
								$sub_data = $db->prepare($q);
								
								$sub_data->bindParam(1, $val['case_type'], PDO::PARAM_STR);
								$sub_data->bindParam(2, $val['case_year'], PDO::PARAM_STR);
								$sub_data->bindParam(3, $val['case_no'], PDO::PARAM_STR);
								$sub_data->bindParam(4, $val['location_code'], PDO::PARAM_STR);
								
								$sub_data->execute();
								$sub_data = $sub_data->fetchAll();
								foreach($sub_data as $k=>$dup_data){
									$k_plus = $k+1;
								$sn++;
						?>
							<tr>
								<td><?php echo $sn; ?></td>
								<td><?php echo display_filing_no($dup_data['filing_no']); ?></td>
								<td><?php echo $val['case_type_name'].'/'.$val['case_no'].'('.$val['short_name'].')'.'/'.$val['case_year']; ?></td>
								<td><?php echo $val['short_name']; ?></td>
								<td><?php echo $dup_data['pet_name']; ?></td>
								<td><?php echo $dup_data['res_name']; ?></td>
								<td><?php echo date('d/m/Y',strtotime($dup_data['dt_of_filing'])); ?></td>
								<!--<td><?php echo $dup_data['regis_date']; ?></td>-->
							</tr>
						<?php	} }
					?>
					</tbody>
					</table>
					</form>
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
        action = "duplicate_details.php";
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
	
	function submitForm3() {
	alert("sdfdsf");
    with(document.list_type_frm) {
        action = "dup.php";
        submit();
    }
}
});



$('.load_container').fadeOut(500);
</script>
<?php }?>
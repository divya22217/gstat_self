<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
$server_date = date('Y-m-d');
include "../db_inc1.php";
include "../db_inc2.php";
include_once '../custom/custom_function.php';
$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];
$sessionUserType = htmlspecialchars($_SESSION['id']);
$location_access = $_SESSION['location'];
$schema_id = $_SESSION['schema_idccc'];
//print_r($_SESSEION);

/*  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */  

function display_filing_no($filing_no_display)
{
    $lastFour = substr($filing_no_display, -4);
    $lastFive = substr($filing_no_display, -9, -4);
    $left = substr($filing_no_display, -16, -9);
    return $dis_fil_no = $left . '/<b>' . $lastFive . '/' . $lastFour . '</b>';

}

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    // header("Location: ./login.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", true, true);
$_SESSION['csrf'] = md5(uniqid(rand(), true));
$key = $_SESSION['csrf'];
// At the top of the page we check to see whether the user is logged in or not
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
    die("#2E2E2Eirecting to login.php");
}
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    $sessionUserType = htmlspecialchars($_SESSION['id']);
    $curYear = htmlspecialchars(date("Y"));
    $curMonth = htmlspecialchars(date("m"));
    $curDay = htmlspecialchars(date("d"));
    $cur_date = "$curYear-$curMonth-$curDay";
    $cur_date1 = "$curDay/$curMonth/$curYear";
    $link_scrutiny_idaccess = '1';
    include '../inheader.php';
    //include '../insidebar.php';
	
	function get_court($db,$schemas){
	$courts = $db->prepare("select * from $schemas.court order by court_no");
	$courts->execute();
	$courts = $courts->fetchAll();
	return $courts;
}

function main_case_court_no_from_note($db,$schemas,$main_case_number){
	$select = $db->prepare("select first_court_no from $schemas.scrutiny where filing_no = ?");
	$select->bindParam(1, $main_case_number, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
}

function main_case_court_no_from_allocation($db,$schemas,$main_case_number){
	$select = $db->prepare("select court_no from $schemas.case_allocation_temp where filing_no = ? limit 1");
	$select->bindParam(1, $main_case_number, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
}

function get_display_court_text($db,$schemas,$court_no){
	$select = $db->prepare("select causelist_court_text from $schemas.court where court_no = ?");
	$select->bindParam(1, $court_no, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
}
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
</style>
<div class="load_container">
    <img class="loader" src="../loading-indicator.gif">
</div>
<div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <?php
 $c_case = isset($_REQUEST['c_case']) ? $_REQUEST['c_case'] : '1';
    //print_r($_SESSION);
    ?>
                <form name="frm" method="post">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                       <!-- <th>
                                            <input type="radio" name="c_case" value="1" onChange="submitForm3();"
                                                <?php if ($c_case == 1) {echo 'checked';}?>><b>Main Case</b>&nbsp;&nbsp;
                                        </th>
                                        <th>
                                            <input type="radio" name="c_case" value="2" onChange="submitForm3();"
                                                <?php if ($c_case == 2) {echo 'checked';}?>><b>Document scrutiny
                                                for court</b>&nbsp;&nbsp;
                                        </th>
                                        <th>
                                            <input type="radio" name="c_case" value="3" onChange="submitForm3();"
                                                <?php if ($c_case == 3) {echo 'checked';}?>><b>IA</b>
                                        </th>-->
                                        <!-- <th>
                                            <input type="radio" name="c_case" value="4" onChange="submitForm3();"
                                                <?php if ($c_case == 4) {echo 'checked';}?>><b>Reports</b>
                                        </th> -->
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </form>
                <!-- Fresh Defective Start --->
                <?php 
if(isset($c_case) &&  $c_case == '1' &&  $c_case != '' ) { 
?>
                <table id="example1" class="table" >
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Date Of Filing</th>
                            <th>Case Type</th>
                            <th>Diary No.</th>
                            <th>Main Case Diary No.</th>
                            <th>Title Of Case</th>
                            <th>Subject</th>
							<!--<th>Documents</th>-->
							<th>Main Case Court No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
					$case_data = generated_computation_note($db, $schemas, $type='main');
					$main_cases = main_case_type_for_comp_note();
					$appeals = main_case_type();
					if (!empty($case_data) && is_array($case_data)) {
						$sn = 1;
						foreach ($case_data as $key=>$row) {
							$filing_no = htmlspecialchars($row['filing_no']);
							$main_filing_no = htmlspecialchars($row['filingnumberia']);
							$dt_of_filing = htmlspecialchars($row['dt_of_filing']);
							$case_type = htmlspecialchars($row['case_type_nclat']);
							$patially_defective = htmlspecialchars($row['patially_defective']);
							if (in_array($case_type, $appeals)){
							$show_party_filing_no = htmlspecialchars($row['filing_no']);
							}else{
								$show_party_filing_no = htmlspecialchars($row['filingnumberia']);
								if(empty($show_party_filing_no))
									$show_party_filing_no = htmlspecialchars($row['filing_no']);
							}
							if($case_type == '35' && !empty($main_filing_no)){
								$check_main_case_status = main_case_comp_note_status($db,$main_filing_no,$location_access); 	
								if($check_main_case_status == '0'){
									//continue;
								}
							}
							if (in_array($case_type, $main_cases))
							{
								$connected_ia = get_all_withount_case_no_generated_IA($db,$filing_no,$location_access,$ia_case_type='35');
								if(!empty($connected_ia)){
									foreach($connected_ia as $key=>$row_ia){
										$filing_no_ias[] = htmlspecialchars($row_ia['filing_no']);
									}
									$all_ia = count($filing_no_ias);
									$implode_filing_no = implode("','",$filing_no_ias);
									$count_all_ias_status = check_all_ias_status($db,$implode_filing_no,$location_access);
									if($count_all_ias_status == '0' || $count_all_ias_status != $all_ia){
										//continue;
									}
								}
							 }
							$pet_name = get_party($db,$show_party_filing_no,$party_flag='P',$party_serial_no=1);
							$res_name = get_party($db,$show_party_filing_no,$party_flag='R',$party_serial_no=1);
							$case_title = $pet_name." <b>VS</b> ".$res_name;
							$case_type_display = htmlspecialchars($row['case_type_desc_cis']);
							$filing_date_all = fn_date_formate($dt_of_filing);
							$subject_id = $row['subjectia'];
							$doc_count = count_doc($db,$filing_no,$scrutiny=1,$display=1);
							if($patially_defective == 1){
								$bc_color = '#f1df61';
							}else{
								$bc_color = '#BDFCC9';
							}
							$tr_short = '';
							if($case_type == '40'){
								$old_case_info = get_old_case_info($db,$filing_no);
								if(!empty($old_case_info)){
									if(!empty($old_case_info)){
										$transfer_case_type = $old_case_info['transfer_case_type'];
										if($transfer_case_type == '32'){
											$tr_short = ' (Company)';
										}else if($transfer_case_type == '33'){
											$tr_short = ' (Ins.)';
										}else if($transfer_case_type == '34'){
											$tr_short = ' (Compt.)';
										}else{
											$tr_short = '';
										}
									}
								}
							}
								?>
										<tr style="background-color: <?php echo $bc_color; ?>;">
											<td><?php echo $sn; ?></td>
											<td><?php echo fn_date_formate($dt_of_filing); ?> </td>
											<td><?php echo $case_type_display.$tr_short; ?></td>
											<td><?php echo display_filing_no($filing_no); ?></td>
											<td><?php echo (!empty($main_filing_no) && $main_filing_no != 'NA')?display_filing_no($main_filing_no):'NA';?>
											<td><?php echo $case_title; ?> </td>
											<td>
												<div class="sparkbar" data-color="#00a65a" data-height="20">
													<?php echo get_subject($db,$subject_id); ?></div>
											</td>
											<td><a  onclick="OpenDMSForm('2','<?php echo $filing_no; ?>','<?php echo $case_title; ?>','','','')" style="cursor: pointer"> View Docs</a></td>
											<td>
												<?php
												$court_no_of_main_case = '';
												if(!empty($main_filing_no)){
													$get_from_proceeding = main_case_court_no_from_allocation($db,$schemas,$main_filing_no);
													if(!empty($get_from_proceeding)){
														$court_no_of_main_case = $get_from_proceeding;
													}else{
													$main_case_court_no = main_case_court_no_from_note($db,$schemas,$main_filing_no);
													if(!empty($main_case_court_no)){
														$court_no_of_main_case = $main_case_court_no;
													}
													}
													if(!empty($court_no_of_main_case)){
														echo get_display_court_text($db,$schemas,$court_no_of_main_case);
													}
												}
												?>
											</td>
											<td>
											<?php 
											$get_computation_note = computation_note_details($db,$schemas,$filing_no);
											$get_computation_note = array_shift($get_computation_note);
											$remarks = get_remarks($db,$schemas,$filing_no);
											$btn_text = 'Generate Computation Note';
											if(!empty($get_computation_note)){ ?>
												<h3><span class="label label-info">
												<a style="color: #FFFFFF;" href="javascript:void(0);" onClick="return show_note('<?php echo $filing_no; ?>','<?php echo $case_type; ?>','../ajax/computation_note.php');" >Show Note</a>
												</span></h3>
										<?php	}	
										?>
											</td>
										</tr>
										
										<?php
									if (in_array($case_type, $main_cases))
									{
									 $connected_ia = get_defect_free_connected_IA($db,$filing_no,$location_access,$ia_case_type='35');
									 if(!empty($connected_ia)){
										foreach($connected_ia as $key=>$row){
											$main_parent_filing_no = $filing_no;
											$direct_parent_filing_no = htmlspecialchars($row['filingnumberia']);
											$filing_no_ia = htmlspecialchars($row['filing_no']);
											$dt_of_filing_ia = htmlspecialchars($row['dt_of_filing']);
											$case_type_ia = htmlspecialchars($row['case_type_nclat']);
											$pet_name = get_party($db,$direct_parent_filing_no,$party_flag='P',$party_serial_no=1);
											$res_name = get_party($db,$direct_parent_filing_no,$party_flag='R',$party_serial_no=1);
											$case_title = $pet_name." <b>VS</b> ".$res_name;
											$case_type_display = fn_case_type_name($dbh, $case_type_ia);
											$subject_id = $row['subjectia'];
											$doc_count_ia = count_doc($db,$filing_no_ia,$scrutiny=1,$display=1);
											?>
											<tr style="background-color: #BDFCC9;">
												<td></td>
												<td><?php echo fn_date_formate($dt_of_filing_ia); ?></td>
												<td><?php echo $case_type_display; ?></td>
												<td><?php echo display_filing_no($filing_no_ia);?></td>
												<td><?php echo (!empty($main_parent_filing_no) && $main_parent_filing_no != 'NA')?display_filing_no($main_parent_filing_no):'NA';?>
												<td><?php echo $case_title; ?> </td>
												<td><?php echo get_subject($db,$subject_id); ?> </td>
												<td><a  onclick="OpenDMSForm('2','<?php echo $filing_no_ia; ?>','<?php echo $case_title; ?>','','','')" style="cursor: pointer"> View Docs</a></td>
												<td>
												<?php
												$court_no_of_main_case = '';
												if(!empty($main_parent_filing_no)){
													$get_from_proceeding = main_case_court_no_from_allocation($db,$schemas,$main_parent_filing_no);
													if(!empty($get_from_proceeding)){
														$court_no_of_main_case = $get_from_proceeding;
													}else{
													$main_case_court_no = main_case_court_no_from_note($db,$schemas,$main_parent_filing_no);
													if(!empty($main_case_court_no)){
														$court_no_of_main_case = $main_case_court_no;
													}
													}
													if(!empty($court_no_of_main_case)){
														echo get_display_court_text($db,$schemas,$court_no_of_main_case);
													}
												}
												?>
											</td>
												<td>
													
												</td>
											</tr>
											<?php
										}
									 }
									}
									$sn++;
								?>		
										<?php
							}
						}
?>
                    </tbody>

                </table>
                <?php } ?>
                <!-- Fresh Defective End --->



                <!-- Document scrutiny for court Start --->
                <?php 
if(isset($c_case) &&  $c_case == '2' &&  $c_case != '' &&  ($_SESSION['menuaccess_codeall'] =='2' || $_SESSION['menuaccess_codeall'] =='11')) { 
?>
                <table id="example" class="display" >
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Date Of Filing</th>
                            <th>Diary No.</th>
                            <th>Miscellaneous No.</th>
                            <th>Case No.</th>
                            <th>Title Of Case</th>
                            <th>Section</th>
                            <th>Action By</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
$total_records = document_defective_cases($db,$dbonline,$schemas);
$limit =500;
$total_pages = ceil(count($total_records)/$limit);  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit; 
    $case_data = document_defective_cases($db,$dbonline,$schemas,$limit,$start_from);
    if (!empty($case_data) && is_array($case_data)) {
        $ii = 1;
        foreach ($case_data as $row_sc) {
              $query_q = "select  case_no, case_year, location_code,filing_no,dt_of_filing,case_type,pet_name,res_name from $schemas.case_detail where 
             filing_no ='".$row_sc['filing_no']."' and case_no!=''
            ";
                $query = $db->prepare($query_q);
                $query->execute();
                $row = $query->fetch();
            $filing_no = htmlspecialchars($row['filing_no']);
            $ia_filing_no = htmlspecialchars($row_sc['miscellaneous_ref_no']);
            $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
            $case_title = $row['pet_name'].' VS '. $row['pet_name'];
            $filing_date_all = fn_date_formate($dt_of_filing);
            $case_no = $row['case_no'];
            $case_no = ltrim($case_no,0);
            $casetype = $row['case_type'];
            $casetypeii = fn_case_type_name($db, $casetype);
            $locode = $row['location_code'];
            $case_year = $row['case_year'];
            $main_case_no =  fn_getCaseNo($db,$schemas,$filing_no);
                ?>
                    <tr style="background-color: #f8c6bf;">
                        <td><?php echo $ii; ?></td>
                        <td><?php echo fn_date_formate($dt_of_filing); ?> </td>
                        <td><?php echo display_filing_no($filing_no); ?></td>
                        <td><?php echo display_filing_no($ia_filing_no); ?></td>
                        <td><?php echo $main_case_no; ?></td>
                        <td><?php echo $case_title; ?> </td>
                        <td>
                            <div class="sparkbar" data-color="#00a65a" data-height="20">
                                <?php echo fn_section($dbonline, $filing_no); ?></div>
                        </td>
                        <td>
                            <h3><span class="label label-info">
                                    <?php 
                                if($_SESSION['menuaccess_codeall'] =='2') { 
echo '<a style="color: red;">Rejected by A.R</a>';
                                } else { 
                                    echo '<a style="color: red;">Action Taken</a>';
                                } ?>
                                </span></h3>
                        </td>
                    </tr>
                    <?php
$ii++;
        }
    }?>
                    </tbody>
                </table>

                <tr>
                <td colspan="7">
                    <div align="center">
                        <ul class='pagination text-center' id="pagination">
                        <?php 
                        $page_no = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
                        if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++):  
                            $clas_active='';
                            if($page_no == $i) { 
                                $clas_active = 'active';
                            }
			if($i == 1):?>
                            <li class='<?php echo $clas_active; ?>' id="<?php echo $i;?>"><a
                                    href='defective_cases.php?c_case=<?php echo $c_case;?>&page=<?php echo $i;?>'><?php echo $i;?></a></li>
                            <?php else:?>
                            <li class='<?php echo $clas_active; ?>' id="<?php echo $i;?>"><a href='defective_cases.php?c_case=<?php echo $c_case;?>&page=<?php echo $i;?>'><?php echo $i;?></a>
                            </li>
                            <?php endif;?>
                            <?php endfor;endif;?>
                        </ul>
                    </div>
                </td>
            </tr> 




                <?php } ?>
                <!-- Document scrutiny for court End --->



                <!-- IA Start --->
                <?php 
if(isset($c_case) &&  $c_case == '3' &&  $c_case != '' &&  ($_SESSION['menuaccess_codeall'] =='2' || $_SESSION['menuaccess_codeall'] =='11')) { 
?>
                <table  id="example" class="display" >
                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Main Filing No.</th>
                            <th>Date of Filing</th>
                            <th>IA No.</th>
                            <th>Title Of Case</th>
                            <th>Section</th>
                            <th>Date of Scrutiny</th>
                        </tr>
                    </thead>
                    <?php
    $case_data = ia_defective_cases($db,$dbonline,$schemas);
    if (!empty($case_data) && is_array($case_data)) {
        $ii = 1;
        foreach ($case_data as $row_sc) {
             $query_q = "select a.filing_no,a.ia_filing_no,a.dt_of_filing,b.case_title from e_ia_details as a
           inner join e_case_detail as b ON a.filing_no = b.filing_no
           where a.payment_status='TRUE' and a.scrutiny='0' 
           and a.doc_status='0' and a.ia_filing_no IS NOT NULL and a.ia_filing_no!='' and 
           
            a.ia_id = '".$row_sc['ia_id']."' and a.filing_no  = '".$row_sc['filing_no']."'
            ";
                $query = $dbonline->prepare($query_q);
                $query->execute();
                $row = $query->fetch();
            $filing_no = htmlspecialchars($row['filing_no']);
            $ia_filing_no = htmlspecialchars($row['ia_filing_no']);
            $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
            $case_title = $row['case_title'];
            $filing_date_all = fn_date_formate($dt_of_filing);
                ?>
                    <tr style="background-color: #f8c6bf;">
                        <td><?php echo $ii; ?></td>
                        <td><?php echo display_filing_no($filing_no); ?></td>
                        <td><?php echo fn_date_formate($dt_of_filing); ?> </td>

                        <td><?php echo display_filing_no($ia_filing_no); ?></td>
                        <td><?php echo $case_title; ?> </td>
                        <td>
                            <div class="sparkbar" data-color="#00a65a" data-height="20">
                                <?php echo fn_section($dbonline, $filing_no); ?></div>
                        </td>
                        <td>
                            <h3><span class="label label-info">



                                    <?php 
                                if($_SESSION['menuaccess_codeall'] =='2') { 
echo '<a style="color: red;">Rejected by A.R</a>';
                                } else { 
                                    echo '<a style="color: red;">Action Taken</a>';
                                } ?>

                                </span></h3>
                        </td>
                    </tr>


                    <?php
$ii++;
            
        }

    }?>




                    <tbody>
                    </tbody>
                </table>
                <?php } ?>
                <!-- IA End --->
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

</div>
<script type="text/javascript" language="javascript">
function submitForm3() {
    with(document.frm) {
        action = "defective_cases.php";
        submit();
    }
}
</script>
<script>
$(document).ready(function() {

    $('#example').DataTable({
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

$('.load_container').fadeOut(500);
</script>

<?php }?>

<!-- computation note modal -->
<div id="comp_note" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:90%;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="comp_note_body">
        <p>Some text in the modal.</p>
      </div>
    </div>

  </div>
</div>

<div id="add_remark" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:50%;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="add_remark_mody">
        <p>Some text in the modal.</p>
      </div>
    </div>

  </div>
</div>

<!-- computation note modal -->
<div id="view_doc" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:90%;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="view_doc_body">
        <p>Some text in the modal.</p>
      </div>
    </div>

  </div>
</div>

<script>
function OpenDMSForm(step, filing_no, cause_title, case_no, court_no, item_no) {
	document.getElementById("step").value = step;
	document.getElementById("case_filing_no").value = filing_no;
	document.getElementById("cause_title").value = cause_title;
	document.getElementById("case_no").value = case_no;
	document.getElementById("court_no").value = court_no;
	document.getElementById("item_no").value = item_no;
	document.getElementById("frm_dms").submit();

}
</script>

<form action="https://efiling.nclat.gov.in/dmsnclat/dashboard" method="POST" target="_blank" id="frm_dms">
	<input type="hidden" id="step" name="step" value="" />
	<input type="hidden" id="case_filing_no" name="case_filing_no" value="" />
	<input type="hidden" id="cause_title" name="cause_title" value="" />
	<input type="hidden" id="case_no" name="case_no" value="" />
	<input type="hidden" id="court_no" name="court_no" value="" />
	<input type="hidden" id="item_no" name="item_no" value="" />
</form>

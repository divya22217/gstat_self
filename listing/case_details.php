<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include "../db_inc1.php";
include '../db_inc2.php';
require_once '../custom/custom_function.php';
$schemas = htmlspecialchars($_SESSION['schema_name']);
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", true, true);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    include '../inheader.php';
    include '../insidebar.php';
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

<div class="load_container">
    <img class="loader" src="../loading-indicator.gif">
</div>

<script language="javascript">
function submitForm() {
    with(document.frm) {
        action = "man_generate_case_no.php";
        submit();
    }
}
</script>
<?php 

?>
<div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">

                <?php 
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
 $filing_no = isset($_REQUEST['filing_no']) ? $_REQUEST['filing_no'] : '';
$case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] : '';
$case_number = isset($_REQUEST['case_number']) ? $_REQUEST['case_number'] : '';
$case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] : '';
$case_status = isset($_REQUEST['case_status']) ? $_REQUEST['case_status'] : '';
$party_name = isset($_REQUEST['party_name']) ? $_REQUEST['party_name'] : '';
$court_no_search = isset($_REQUEST['court_no_search']) ? $_REQUEST['court_no_search'] : 'All'; 
            ?>
                <form name="search_frm" method="post" id="search_frm">

                    <input type="hidden" name="page_no" id="page_no" value="<?php echo $page_no; ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <h3>Custom Search</h3>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Enter Diary No.</label>
                                <input type="text" name="filing_no" id="filing_no" class="form-control"
                                    value="<?php echo $filing_no; ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Case Type</label>
                                <select required="required" class="form-control" name="case_type" id="case_type">
                                    <option value="">Select</option>
                                    <?php 
    $case_type_data = case_type($db);
    if (!empty($case_type_data)) {
foreach ($case_type_data as $val) { ?>
                                    <option <?php echo ($case_type == $val['id']) ? 'selected' : '';?>
                                        value="<?php echo $val['id']; ?>"><?php echo  $val['case_type_desc']; ?>
                                    </option>
                                    <?php 
                                }
                            } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Enter Case No.</label>
                                <input type="text" name="case_number" id="case_number" class="form-control"
                                    value="<?php echo $case_number; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Case Year</label>
                                <select class="form-control" name="case_year" id="case_year">
                                    <option value="">Select</option>
                                    <?php echo year_list($case_year); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Enter Party Name.</label>
                                <input type="text" name="party_name" id="party_name" class="form-control"
                                    value="<?php echo $party_name; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Case Status <?php echo $case_status; ?></label>
                                <select class="form-control" name="case_status" id="case_status">
                                    <option <?php echo ($case_status == 'All') ? 'selected' : '';?> value="All">All
                                    </option>
                                    <option <?php echo ($case_status == 'P') ? 'selected' : '';?> value="P">Pending
                                    </option>
                                    <option <?php echo ($case_status == 'D') ? 'selected' : '';?> value="D">Disposed
                                    </option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Court</label>
                                <select class="form-control" name="court_no_search" id="court_no_search">

                                    <option <?php echo ($court_no_search == '1') ? 'selected' : '';?> value="1">Court 1
                                    </option>
                                    <option <?php echo ($court_no_search == '2') ? 'selected' : '';?> value="2">Court 2
                                    </option>
                                    <option <?php echo ($court_no_search == '3') ? 'selected' : '';?> value="3">Court 3
                                    </option>
                                    <option <?php echo ($court_no_search == '4') ? 'selected' : '';?> value="4">Court 4
                                    </option>
                                    <option <?php echo ($court_no_search == '5') ? 'selected' : '';?> value="5">Court 5
                                    </option>
                                    <option <?php echo ($court_no_search == '6') ? 'selected' : '';?> value="6">Court 6
                                    </option>
                                    <option <?php echo ($court_no_search == '7') ? 'selected' : '';?> value="7">Court 7
                                    </option>
                                    <option <?php echo ($court_no_search == '8') ? 'selected' : '';?> value="8">Court 8
                                    </option>
                                    <option <?php echo ($court_no_search == '9') ? 'selected' : '';?> value="9">Court 9
                                    </option>
                                    <option <?php echo ($court_no_search == 'All') ? 'selected' : '';?> value="All">All
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Case Limit
                                    <?php $case_limit = isset($_REQUEST['case_limit']) ? $_REQUEST['case_limit'] : '';?></label>
                                <select class="form-control" name="case_limit" id="case_limit">
                                    <option <?php echo ($case_limit == '1000') ? 'selected' : '';?> value="1000">
                                        1000 Case
                                    </option>
                                    <option <?php echo ($case_limit == '500') ? 'selected' : '';?> value="500">500 Case
                                    </option>
                                    <option <?php echo ($case_limit == '2000') ? 'selected' : '';?> value="2000">2000
                                        Case
                                    </option>
                                    <option <?php echo ($case_limit == 'All') ? 'selected' : '';?> value="All">All Case
                                    </option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group ">
                                <label>&nbsp;</label>
                                <button type="button" onclick="fn_search_custom()"
                                    class="btn btn-primary">Search</button>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>


        <div class="box">
            <div class="box-header with-border">
                <!-- Content Header (Page header) -->
                <h2>
                    <center>Case List</center>
                </h2>
                <hr>
                <table id="case_details" class='table'>
                    <thead>
                        <tr>
                            <th>Sr. NO.</th>
                            <th>DIARY NO.</th>
                            <th >CA Deatils</th>
                            <th>CASE No</th>
                            <th>Case Type</th>
                            <th>Main Case & Diary No</th>
                            <th>Last listing Date & Court</th>
                            <th>DATE OF FILING</th>
                            <th>Status</th>
                            <th>Filed By</th>
                            <th>Party Name</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
$where_custom = '';
 if($filing_no!= '') { 
     $where_custom .= " and filing_no = "."'$filing_no'";
 }
if($case_number!= '') { 
    $where_custom .= " and case_no = "."'$case_number'";
}
if($case_year!= '') { 
    $where_custom .= " and case_year = "."'$case_year'";
}
if($case_type!= '') { 
    $where_custom .= " and case_type = "."'$case_type'";
}
if($party_name!= '') { 
    $where_custom .= " and pet_name like "."'%$party_name%'"." OR res_name like "."'%$party_name%'";
}
if($case_status!= '' && $case_status != 'All') { 
    $where_custom .= " and status = "."'$case_status'";
}
 $where_custom1111  = trim(ltrim($where_custom,' and '),' ');
if($where_custom1111 != '') { 
    $where_custom1111 =   ' where '.$where_custom1111;
}
$ii = 1;
    $sql1 = $db->prepare("SELECT CASE WHEN trim(case_no) SIMILAR TO '[0-9]+' 
    THEN CAST(trim(case_no) AS integer) ELSE NULL END   as case_noo,case_year,filing_no,dt_of_filing,case_no,case_type,pet_name,res_name,location_code,status from $schemas.case_detail   $where_custom1111 order by case_year asc,case_noo asc ");
    $sql1->execute();
    $all_data = $sql1->fetchAll();
    $limit = 1000;
    if($case_limit != '') {
    $limit = $case_limit;
    } 

$case_lilit_qw = '';
$total_pages = '';  
    $page  = '';
    $start_from = ''; 
if($case_limit != 'All') { 
    $total_pages = ceil(count($all_data)/$limit);  
    $page  = isset($_GET["page"]) ? $_GET["page"] : 1;
    $start_from = ($page-1) * $limit; 
$case_lilit_qw = ' limit '. $limit .' OFFSET '. $start_from; 
}
$sql1 = $db->prepare("SELECT CASE WHEN trim(case_no) SIMILAR TO '[0-9]+' 
THEN CAST(trim(case_no) AS integer) ELSE NULL END   as case_noo,case_year,filing_no,dt_of_filing,case_no,case_type,pet_name,res_name,location_code,status from $schemas.case_detail  $where_custom1111  order by case_year asc,case_noo asc $case_lilit_qw ");
$sql1->execute();

$all_data = $sql1->fetchAll();

//print_r($all_data);


    foreach($all_data  as $row1) {
        $filing_no = $row1['filing_no'];
        $dof = $row1['dt_of_filing'];
        list($year, $month, $day) = explode('-', $dof);
        $dof = $day . '/' . $month . '/' . $year;
        $case_no = $row1['case_no'];
        $case_year = $row1['case_year'];
        $case_type = $row1['case_type'];
        $pet_name = $row1['pet_name'];
        $res_name = $row1['res_name'];
        $status = $row1['status'];
        $case_title = $pet_name . "<br> VS  <br>" . $res_name;
        $case_type_short_name = '';
        if ($case_type > 0) {
            $ref_stQ = $db->prepare("select case_type_desc from case_type where id = ?");
            $ref_stQ->bindParam(1, $case_type, PDO::PARAM_STR);
            $ref_stQ->execute();
            $case_type_short_name = $ref_stQ->fetchColumn();
        }
        $case_no =  fn_getCaseNo($db, $schemas, $filing_no);
        $list_court_no =   fn_getlastListdateCourt_no($db, $schemas, $filing_no);
        $court_no = '';
        $listing_date = '';
        if(!empty($list_court_no)) { 
$court_no = $list_court_no['court_no'];
$listing_date = $list_court_no['listing_date'];
        }

        $get_in_filing_no_sql = $dbonline->prepare("select in_filingno from e_case_detail where filing_no=?");
        $get_in_filing_no_sql->bindParam(1, $filing_no, PDO::PARAM_INT);
        $get_in_filing_no_sql->execute();
        $in_filingno = $get_in_filing_no_sql->fetchColumn();
        $main_case_number = '';
        $main_diary_no = '';
        if ($in_filingno != '') {
            $main_case_number =   fn_getCaseNo($db, $schemas, $in_filingno);
            $main_diary_no =$in_filingno; 
        }
       // $filied_by = '';
       $filied_by =   fn_getUserDetails($dbo,$filing_no);


       $in_case_nocp=array();
 
       $get_in_filing_no_sql = "select case_title,filing_no,dt_of_filing from e_case_detail where in_filingno = ? and status != 'I'";
       $get_in_filing_no = $dbonline->prepare($get_in_filing_no_sql);
       $get_in_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
       $get_in_filing_no->execute();
       $data_in_ch = $get_in_filing_no->fetchAll();
       $in_filingno = '';
       if(!empty($data_in_ch) && is_array($data_in_ch)) { 
        foreach ($data_in_ch as $row_gifn)
        {
          $in_filingno=$row_gifn['filing_no'];
          if($in_filingno!=''){
          $get_incase =  fn_getCaseNo($db, $schemas, $in_filingno);
          array_push($in_case_nocp, array('case_title'=>$row_gifn['case_title'],'dt_of_filing'=>$row_gifn['dt_of_filing'],"case_no" => $get_incase, "filing_no" => $in_filingno));
          //print_r($in_case_no);
        }
        }
       }
       if($court_no_search == 'All') {  ?>
                        <tr>
                            <td><?php echo $ii;?></td>
                            <td><?php echo $filing_no ?></td>
                            <td><?php 
                            
                            if(!empty($in_case_nocp) && is_array($in_case_nocp)) {
                                foreach ($in_case_nocp as $key => $value) {
                                    echo '<p  style="width: 350px;">';
                                    echo ' <b>Case Title : </b> '.$value['case_title'].'<br>';
                                    echo ' <b>Date Of Filing : </b> '.$value['dt_of_filing'].'<br>';
                                    echo ' <b>Case No : </b> '.$value['case_no'].'<br>';
                                    echo ' <b>Diary No : </b> '.$value['filing_no'].'<br>';
                                    echo '</p>';
                                }

                            }
                            
                            ?></td>
                            <td><?php echo $case_no; ?></td>
                            <td><?php echo $case_type_short_name; ?></td>
                            <td><?php echo $main_case_number; ?>
                                <br>
                                <br>
                                <?php echo $main_diary_no; ?></td>
                            <td><?php   if($court_no != '' && $case_no != '')  { echo $listing_date.'<br>'; echo 'Court No:'.$court_no;  }?>
                            </td>
                            <td><?php echo $dof; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $filied_by; ?></td>
                            <td><?php echo $case_title; ?></td>
                        </tr>
                        <?php $ii++; } else if($court_no_search != 'All' && $court_no_search == $court_no) { ?>
                        <tr>
                            <td><?php echo $ii;?></td>
                            <td><?php echo $filing_no ?></td>

                            <td><?php 
                            
                            if(!empty($in_case_nocp) && is_array($in_case_nocp)) {
                                foreach ($in_case_nocp as $key => $value) {
                                    echo '<p  style="width: 350px;">';
                                    echo ' <b>Case Title : </b> '.$value['case_title'].'<br>';
                                    echo ' <b>Date Of Filing : </b> '.$value['dt_of_filing'].'<br>';
                                    echo ' <b>Case No : </b> '.$value['case_no'].'<br>';
                                    echo ' <b>Diary No : </b> '.$value['filing_no'].'<br>';
                                    echo '</p>';
                                }

                            }
                            
                            ?></td>
                            <td><?php echo $case_no; ?></td>
                            <td><?php echo $case_type_short_name; ?></td>
                            <td><?php echo $main_case_number; ?>
                                <br>
                                <br>
                                <?php echo $main_diary_no; ?></td>
                            <td><?php   if($court_no != '' && $case_no != '')  { echo $listing_date.'<br>'; echo 'Court No:'.$court_no;  }?>
                            </td>
                            <td><?php echo $dof; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $filied_by; ?></td>
                            <td><?php echo $case_title; ?></td>
                        </tr>
                        <?php $ii++; }             
}
    ?>
                    <tbody>
                </table>


                <div align="center">
                    <ul class='pagination text-center' id="pagination">
                        <?php
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++):  
$clas_active='';
if($page == $i) { 
    $clas_active = 'active';
}
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
$filing_no = isset($_REQUEST['filing_no']) ? $_REQUEST['filing_no'] : '';
$case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] : '';
$case_number = isset($_REQUEST['case_number']) ? $_REQUEST['case_number'] : '';
$case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] : '';
$case_status = isset($_REQUEST['case_status']) ? $_REQUEST['case_status'] : '';
$party_name = isset($_REQUEST['party_name']) ? $_REQUEST['party_name'] : '';
$court_no_search = isset($_REQUEST['court_no_search']) ? $_REQUEST['court_no_search'] : 'All';
			if($i == 1):?>
                        <li class='<?php echo $clas_active; ?>' id="<?php echo $i;?>"><a
                                href='case_details.php?page=<?php echo $i;?>&filing_no=<?php echo $filing_no?>&case_type=<?php echo $case_type?>&case_number=<?php echo $case_number?>&case_year=<?php echo $case_year?>&case_status=<?php echo $case_status?>&court_no_search=<?php echo $court_no_search?>&party_name=<?php echo $party_name?>'><?php echo $i;?></a>
                        </li>
                        <?php else:?>
                        <li class='<?php echo $clas_active; ?>' id="<?php echo $i;?>"><a
                                href='case_details.php?page=<?php echo $i;?>&filing_no=<?php echo $filing_no?>&case_type=<?php echo $case_type?>&case_number=<?php echo $case_number?>&case_year=<?php echo $case_year?>&case_status=<?php echo $case_status?>&court_no_search=<?php echo $court_no_search?>&party_name=<?php echo $party_name?>'><?php echo $i;?></a>
                        </li>
                        <?php endif;?>
                        <?php endfor;endif;?>
                    </ul>
                </div>

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
function fn_search_custom() {
    $('.load_container').fadeIn(200);
    var case_limit = $("#search_frm #case_limit").val();
    var case_year = $("#search_frm #case_year").val();
    if (case_year == '' && case_limit == 'All') {
        alert('Please Select Year');
        $('.load_container').fadeOut(200);
    }
    with(document.search_frm) {
        action = "case_details.php";
        submit();
    }
}
$(document).ready(function() {
    $('#case_details').DataTable({
        "pageLength": 1000,
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
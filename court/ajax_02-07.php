<?php
require_once('../includes/helper.php');
deny_direct_access();
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php";
include "../custom/custom_function.php";
include '../parse_pdf/vendor/autoload.php';
require_once('../object_storage/S3Service.php');
$s3Service = new S3Service();
 //ini_set('display_errors', 1);
 //ini_set('display_startup_errors', 1);
 //error_reporting(E_ALL);
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
function commonFunction($db, $schema, $location, $data)
{
    if ($data['search_by'] == '1') {
        $filing_no = $data['dairy_no'] . $data['dairy_year'];
        $location = $location;
        if (!is_numeric($filing_no)) {
            echo 'Enter numeric Filing No';
            die;
        } else if (!is_numeric($location)) {
            echo 'Enter numeri Location No';
            die;
        }
        $where = array('location_code' => $location, 'filing_no' => $filing_no);
        $where_likes = array();
    } else if ($data['search_by'] == '2') {
        if (!is_numeric($data['case_type'])) {
            echo 'Enter numeric Filing No';
            die;
        } else if (!is_numeric($data['case_no'])) {
            echo 'Enter numeri Case No';
            die;
        } else if (!is_numeric($data['case_year'])) {
            echo 'Enter numeri case Year No';
            die;
        }
        $where = array('case_type' => $data['case_type'], 'case_no' => $data['case_no'], 'case_year' => $data['case_year']);
        $where_likes = array();
    }
    $getData = get_data($db, $schema . '.case_detail', $where, $where_likes, 'ia_ma_filing_no,case_title,status,filing_no,pet_name,res_name,case_no,case_year,case_type,defect_listed', 'filing_no desc limit 1');
    return $getData;
}

function get_mobile_no_email_id($db, $filing_no)
{
    $st = $db->prepare("select  name,email,mobile FROM public.e_cases_party where filing_no = '$filing_no'");
    $st->execute();
    $data_all = $st->fetchAll();
    $st_rep = $db->prepare("SELECT rep_name as name,email,mobile FROM public.e_more_representative as a Inner JOIN public.e_master_advocate as b ON a.rep_code = b.id where  filing_no = '$filing_no'");
    $st_rep->execute();
    $data_all_rep = $st_rep->fetchAll();
    $merge_array = array_merge($data_all, $data_all_rep);
    return $merge_array;
}


function fn_summon_notice_status($schemas, $db, $filing_no)
{
    $summon_notice_status = 'summon_notice_status + 1';
    $sql1 = $db->prepare("update $schemas.case_detail set summon_notice_status = summon_notice_status + 1 where filing_no = ?");
    $sql1->bindParam(1, $filing_no, PDO::PARAM_INT);
    return $sql1->execute();
}

function get_case_types($db)
{
    $data_main = array();
    $st = $db->prepare("select * from case_type where display='TRUE' order by case_type_desc asc");
    $st->execute();
    $i = 0;
    while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $tmp = array();
        if ($row['display'] == 'TRUE') {
            $tmp['case_type_desc'] = $row['case_type_desc'];
            $tmp['act_id'] = $row['act_id'];
            $tmp['inter_act_id'] = $row['inter_act_id'];
            $tmp['display'] = $row['display'];
            $tmp['short_name'] = $row['short_name'];
            $tmp['id'] = $row['id'];
            $data_main[$i] = $tmp;
            $i++;
        }
    }
    return $data_main;
}

function get_data($db, $table_name, $where = array(), $where_like = array(), $column = '*', $order_by = null, $order_column = null, $limit = null)
{
    $data_main = array();
    $where_as = '';
    $where_con_like = '';
    if (is_array($where) && !empty($where)) {
        foreach ($where as $key => $val) {
            if ($key != '' && $val != '') {
                $where_as .= $key . '=' . "'$val'" . ' and ';
            }
        }
        $where_as = rtrim($where_as, ' and ');
        $where_con = '';
        if ($where_as != '') {
            $where_con = 'where ' . $where_as;
        }
    }
    $where_as_like = '';
    if (is_array($where_like) && !empty($where_like)) {
        foreach ($where_like as $key1 => $val1) {
            if ($key1 != '' && $val1 != '') {
                $where_as_like .= $key1 . ' LIKE ' . "'%$val1'" . ' and ';
            }
        }
        $where_as_like = rtrim($where_as_like, ' and ');
        $where_con_like = '';
        if ($where_as_like != '') {
            $where_con_like = ' and ' . $where_as_like;
        }
    }
    $where_con = $where_con . $where_con_like;

    $order_by_con = '';
    if ($order_by != null) {
        $order_by_con = 'ORDER BY ' . $order_column . ' ' . $order_by;
    }
    $limit_con = '';
    if ($limit != '') {
        $limit_con = $limit;
    }
    $query = "select $column from $table_name $where_con $order_by_con $limit_con";
    $query_prepare = $db->prepare($query);
    $query_prepare->execute();
    $i = 0;
    while ($row = $query_prepare->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $data_main[] = $row;
    }
    return $data_main;
}

function fn_delete_process($db, $schemas, $filing_no, $remarks_delete, $user_id)
{
    $db->beginTransaction();
    try {
        $case_detail_delete1 = "delete from $schemas.case_detail where filing_no = '$filing_no' ";
        $case_detail_delete = $db->prepare($case_detail_delete1);
        $case_detail_delete->execute();
        $case_allocation_temp_delete1 = "delete from $schemas.case_allocation_temp where filing_no = '$filing_no' ";
        $case_allocation_temp_delete = $db->prepare($case_allocation_temp_delete1);
        $case_allocation_temp_delete->execute();
        $case_allocation1 = "delete from $schemas.case_allocation where filing_no = '$filing_no' ";
        $case_allocation = $db->prepare($case_allocation1);
        $case_allocation->execute();
        $case_proceeding1 = "delete from $schemas.case_proceeding where filing_no = '$filing_no' ";
        $case_proceeding = $db->prepare($case_proceeding1);
        $case_proceeding->execute();
        $delete1 = "delete from $schemas.case_selected_list where filing_no = '$filing_no' ";
        $delete1 = $db->prepare($delete1);
        $delete1->execute();
        $delete2 = "delete from $schemas.connected_cases where filing_no = '$filing_no' ";
        $delete2 = $db->prepare($delete2);
        $delete2->execute();
        $delete3 = "delete from $schemas.draft_objection_details where filing_no = '$filing_no' ";
        $delete3 = $db->prepare($delete3);
        $delete3->execute();
        $delete4 = "delete from $schemas.notice_creation_details where filing_no = '$filing_no' ";
        $delete4 = $db->prepare($delete4);
        $delete4->execute();
        $delete5 = "delete from $schemas.objection_details where filing_no = '$filing_no' ";
        $delete5 = $db->prepare($delete5);
        $delete5->execute();
        $delete6 = "delete from $schemas.objection_details_his where filing_no = '$filing_no' ";
        $delete6 = $db->prepare($delete6);
        $delete6->execute();
        $delete7 = "delete from $schemas.order_daily where filing_no = '$filing_no' ";
        $delete7 = $db->prepare($delete7);
        $delete7->execute();
        $delete8 = "delete from $schemas.order_daily_advocate where filing_no = '$filing_no' ";
        $delete8 = $db->prepare($delete8);
        $delete8->execute();
        $delete9 = "delete from $schemas.order_detail where filing_no = '$filing_no' ";
        $delete9 = $db->prepare($delete9);
        $delete9->execute();
        $delete61 = "delete from $schemas.sc_scrutiny_doc_his where filing_no = '$filing_no' ";
        $delete61 = $db->prepare($delete61);
        $delete61->execute();
        $delete62 = "delete from $schemas.scrutiny where filing_no = '$filing_no' ";
        $delete62 = $db->prepare($delete62);
        $delete62->execute();
        $delete63 = "delete from $schemas.scrutiny_his where filing_no = '$filing_no' ";
        $delete63 = $db->prepare($delete63);
        $delete63->execute();
        $delete64 = "delete from $schemas.transfer_cases where filing_no = '$filing_no' ";
        $delete64 = $db->prepare($delete64);
        $delete64->execute();
        $query_e_case = "update public.e_back_log_cases set final_status = '0', backlog_complete = '0'  where filing_no = '$filing_no'";
        $query_update_status = $db->prepare($query_e_case);
        $query_update_status->execute();

        $location = file_get_contents('https://geolocation-db.com/json/');
        $location_address = $location . 'System Ip Addes' . $_SERVER['REMOTE_ADDR'];
        $date_current = date('Y-m-d H:i:s');
        $insert_log_data1 = "INSERT INTO lucknow.backlog_delete_entery(filing_no, remarks, created_date, created_by, created_ip) VALUES ('$filing_no', '$remarks_delete', '$date_current', '$user_id', '$location_address')";
        $insert_log_data = $db->prepare($insert_log_data1);
        $insert_log_data->execute();

        echo 'update sucessfully';
        $db->commit();
    } catch (PDOException $ex) {
        echo $ex;
        $db->rollBack();
        echo $ex;
        die;
    }
}


function callApiAsync($url) {
    $command = "curl -s $url > /dev/null &";
    exec($command);
}

$case_type_data = get_case_types($db);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {

    if ($_POST['action'] == 'delete_cis_data') {
        $filing_no = $_POST['filing_no'];
        $remarks_delete = $_POST['remarks_delete'];
        $schemas = $_SESSION['schema_name'];
        $user_is_s = $_SESSION['id'];
        $data_respoonse = fn_delete_process($db, $schemas, $filing_no, $remarks_delete, $user_is_s);
        echo $data_respoonse;
    } else if ($_POST['action'] == 'select_type_order') {
        $case_no = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] : '';
        $case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] : date('Y');
        $case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] : '';
        $filing_no = isset($_REQUEST['filing_no']) ? $_REQUEST['filing_no'] : '';

        if ($_POST['type_id'] == '1') {
        ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label>Enter Filing No :</label>
            <input type="text" name="dairy_no" id="dairy_no" value="<?php echo $filing_no; ?>" required="required"
                class="form-control required">
        </div>

        <div class="col-sm-4" style="margin-top: 25PX;">
            <label>&nbsp;</label>
            <button type="button" onclick="fn_search_by_case_order()" class="btn btn-primary">Go</button>
        </div>
    </div>
</div>
<?php } else if ($_POST['type_id'] == '2') { ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-3">
            <label>Select Case Type :</label>
            <select required="required" class="form-control" name="case_type" id="case_type">
                <option value="">Select Case Type</option>
                <?php if (!empty($case_type_data)) {
                                foreach ($case_type_data as $val) {
                                    $selected = '';
                                    if ($val['id'] == $case_type) {
                                        $selected = 'selected';
                                    }
                                    echo '<option ' . $selected . ' value="' . $val['id'] . '">' . $val['case_type_desc'] . '(' . $val['short_name'] . ')' . '</option>';
                                }
                            } ?>
            </select>
        </div>
        <div class="col-sm-3">
            <label>Enter Case No.</label>
            <input type="text" name="case_no" id="case_no" value="<?php echo $case_no; ?>"
                class="form-control required">
        </div>
        <div class="col-sm-3">
            <label>Select Case Year</label>
            <select class="form-control required" name="case_year" id="case_year">
                <option value="">Select Year</option>
                <?php echo year_list($case_year); ?>
            </select>
        </div>
        <div class="col-sm-3" style="margin-top: 25PX;">
            <label>&nbsp;</label>
            <button type="button" onclick="fn_search_by_case_order()" class="btn btn-primary">Go</button>
        </div>
    </div>
</div>
<?php }
    } else if ($_POST['action'] == 'search_filing_order') {
        $schema = $_SESSION['schema_name'];
        $location = $_SESSION['location'];
        $data  = commonFunction($db, $schema, $location, $data);

        if (!empty($data) && is_array($data)) {
            $data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $data[0]['case_type']));

            $filing_no = $data[0]["filing_no"];

        ?>




<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444444;color:#ffffff;">
        <tr role="row">
            <th>Filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>
        </tr>
    </thead>
    <tbody>

        <tr>
            <td><?php echo $data[0]["filing_no"]; ?></td>
            <td><?php echo $data_case_type[0]['short_name'] . '/' . $data[0]["case_no"] . '/' . $data[0]["case_year"]; ?>
            </td>
            <td><?php if ($data[0]["case_title"] != '') {
                                echo $data[0]["case_title"];
                            } else {
                                echo $data[0]["pet_name"] . ' VS ' . $data[0]["res_name"];
                            } ?>
            </td>
        </tr>
    </tbody>
</table>

<form method="post" id="case_order_form_id" enctype="multipart/form-data" name="case_order_form_id">
    <div class="modal-body with-padding">
        <div class="form-group">
            <div class="row" style="    margin-bottom: 25px;">
                <input type='hidden' name='filing_no_hi' id='filing_no_hi' value="<?php echo $data[0]["filing_no"]; ?>">
                <input type='hidden' name='case_no_hi' id='case_no_hi' value="<?php echo $data[0]["case_no"]; ?>">
                <input type='hidden' name='case_year_hi' id='case_year_hi' value="<?php echo $data[0]["case_year"]; ?>">
                <input type='hidden' name='case_type_hi' id='case_type_hi' value="<?php echo $data[0]["case_type"]; ?>">
                <div class="col-sm-6">
                    <label>Order Type :</label>
                    <select required="required" class="required form-control" name="order_type" id="order_type">
                        <option value="">Select Search BY</option>
                        <option value="F">Final Order</option>
                        <option value="D">Intrim Order</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label>Order Date :</label>
                    <input type="date" name="order_date" id="order_date" class="form-control required">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label>Upload :</label>
                    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control required">
                </div>
                <div class="col-sm-6" style="margin-top: 25PX;">
                    <label>&nbsp;</label>
                    <button type="button" onclick="fn_upload_order()" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </div>

    </div>

</form>



<?php

            //print_r($_REQUEST);
            $schemas = htmlspecialchars($_SESSION['schema_name']);
            $order_data = get_data($db, $schema . '.order_daily', array('filing_no' => $filing_no));

            // print_r($order_data);
            ?>
<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444444;color:#ffffff;">
        <tr role="row">
            <th>Sr. No. </th>
            <th>Order Date</th>
            <th>Order Type</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($order_data) && is_array($order_data)) {
                        $i = 1;
                        foreach ($order_data as $val) {

                            $order_type = 'Intrim Order';
                            if ($val['order_type'] == 'F') {
                                $order_type = 'Final Order';
                            }
                    ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo date('d-m-Y', strtotime($val["order_date"])); ?></td>
            <td><?php echo $order_type; ?></td>
            <td><a href="../order_view.php?path=<?php echo base64_encode($val["pdf_path"]); ?>" target="_blank"><i
                        class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Download</a></td>
        </tr>
        <?php $i++;
                        }
                    } ?>
    </tbody>
</table>
<?php
        } else {
            echo '1';
        }
    } else if ($_POST['action'] == 'select_type') {
        if ($_POST['type_id'] == '1') {
        ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label>Enter Filing No :</label>
            <input type="text" name="dairy_no" id="dairy_no" required="required" class="form-control required">
        </div>

        <div class="col-sm-4" style="margin-top:20px;">
            <label>&nbsp;</label>
            <button type="button" onclick="fn_search_by_case_dfr()" class="button btn btn-primary">Search</button>
        </div>
    </div>
</div>
<?php } else if ($_POST['type_id'] == '2') { ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label>Select Case Type :</label>
            <select required="required" class="form-control" name="case_type" id="case_type">
                <option value="">Select Case Type</option>
                <?php if (!empty($case_type_data)) {
                                foreach ($case_type_data as $val) {
                                    echo '<option value="' . $val['id'] . '">' . $val['case_type_desc'] . '(' . $val['short_name'] . ')' . '</option>';
                                }
                            } ?>
            </select>
        </div>
        <div class="col-sm-4">
            <label>Enter Case No.</label>
            <input type="number" name="case_no" id="case_no" class="form-control required">
        </div>
        <div class="col-sm-4">
            <label>Select Case Year</label>
            <select class="form-control required" name="case_year" id="case_year">
                <option value="">Select Year</option>
                <?php echo year_list(date('Y')); ?>
            </select>
        </div>
    </div>
    <div class="" style="margin-top:25px; margin-left:25%;">
        <label>&nbsp;</label>
        <button type="button" onclick="fn_search_by_case_dfr()" class="button btn btn-primary">Search</button>
    </div>
</div>
<?php }
    } else if ($_POST['action'] == 'application_filing_search') {
        $schema = $_SESSION['schema_name'];
        $location = $_SESSION['location'];
        $data  = commonFunction($db, $schema, $location, $_POST);
        ?>


<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444444;color:#ffffff;">
        <tr role="row">
            <th>S.No</th>
            <th>Filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>
            <th>Case Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>


        <?php
                if (!empty($data) && is_array($data)) {
                    $ii = 1;
                    foreach ($data as $value) {
                        $filing_no = $value['filing_no'];
                        $case_no = $value['case_no'];
                        $case_year = $value['case_year'];
                        $status = $value['status'];
                        $data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $value['case_type']));

                        $ia_ma_filing_no = $value['ia_ma_filing_no'];

                        if ($ia_ma_filing_no != '') {

                            $case_type_data = $db->prepare("select case_no,case_year,case_type from  $schema.case_detail where filing_no = ?");
                            $case_type_data->bindParam(1, $ia_ma_filing_no, PDO::PARAM_STR);
                            $case_type_data->execute();
                            $parent_data = $case_type_data->fetch();

                            $p_case_year = $parent_data['case_year'];
                            $p_case_no = $parent_data['case_no'];
                            $p_case_type = $parent_data['case_type'];

                            $p_data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $p_case_type));

                            $case_status = ($value['status'] != 'D') ? 'Pending' : 'Disposed';

                ?>
        <tr style="background-color:<?php echo $color; ?>">
            <td><?php echo $ii; ?></td>
            <td> <a href="https://efilingreat.up.gov.in/previewCIS.in?filingNo=<?php echo $filing_no; ?>&caseType=2"
                    target="_blank"> <?php echo $filing_no; ?> </td>
            <td> <?php echo $data_case_type[0]['short_name'] . '/' . $case_no . '/' . $case_year . ' <br/> IN <br/> ' . $p_data_case_type[0]['short_name'] . '/' . $p_case_no . '/' . $p_case_year ?>
            </td>
            <td> <span
                    style="color: red;"><?php echo $value['pet_name'] . ' </span> vs <span style="color: red;">' . $value['res_name']; ?></span>
            </td>

            <td> <?php echo $case_status; ?> </td>
            <td>

                <?php if ($value['status'] != 'D') {
                                    ?>
                <button class="btn btn-primary"
                    onclick="generate_popup('<?php echo $filing_no; ?>','<?php echo $case_no; ?>','<?php echo $case_year; ?>','<?php echo $value['case_type']; ?>')">
                    Disposed Application </button>
                <?php } else {
                                        echo 'Disposed';
                                    } ?>
            </td>

        </tr>
        <?php $ii++;
                        }
                    }
                } else {
                    echo "<input type = 'hidden' name='filiii_no' id='filiii_no' value='" . $filiddfd . "'>";
                    echo '<tr> <td colspan="5"> <p  style="font-size: large; text-align: center; color: red;"> Data not found! </p> </td> </tr>';
                } ?>

    </tbody>
</table>

<?php $ii++;
    } else if ($_POST['action'] == 'deleted_filing_order') {
        $schema = $_SESSION['schema_name'];
        $location = $_SESSION['location'];
        $data = commonFunction($db, $schema, $location, $_POST);

    ?>


<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444444;color:#ffffff;">
        <tr role="row">
            <th>S.No</th>
            <th>Filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>


        <?php
                if (!empty($data) && is_array($data)) {
                    $ii = 1;
                    foreach ($data as $value) {
                        $filing_no = $value['filing_no'];
                        $case_no = $value['case_no'];
                        $case_year = $value['case_year'];
                        $status = $value['status'];
                        $data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $value['case_type']));

                ?>
        <tr style="background-color:<?php echo $color; ?>">
            <td><?php echo $ii; ?></td>
            <td> <?php echo $filing_no; ?> </td>
            <td> <?php echo $data_case_type[0]['short_name'] . '/' . $case_no . '/' . $case_year; ?></td>
            <td> <span
                    style="color: red;"><?php echo $value['pet_name'] . ' </span> vs <span style="color: red;">' . $value['res_name']; ?></span>
            </td>
            <td>
                <button class="btn btn-primary" onclick="generate_popup('<?php echo $filing_no; ?>')">
                    Delete Order </a>
            </td>

        </tr>
        <?php $ii++;
                    }
                } else {
                    echo "<input type = 'hidden' name='filiii_no' id='filiii_no' value='" . $filiddfd . "'>";
                    echo '<tr> <td colspan="8"> <p  style="font-size: large; text-align: center; color: red;"> Data not found! </p> </td> </tr>';
                } ?>

    </tbody>
</table>

<?php $ii++;
    } else if ($_POST['action'] == 'search_filing') {
        $schema = $_SESSION['schema_name'];
        $location = $_SESSION['location'];
        $data = commonFunction($db, $schema, $location, $_POST);

        if (!empty($data) && is_array($data)) {
            $ii = 1;
            foreach ($data as $value) {
                $filing_no = $value['filing_no'];
                $case_no = $value['case_no'];
                $case_year = $value['case_year'];
                $status = $value['status'];
        ?>


<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444444;color:#ffffff;">
        <tr role="row">
            <th>S.No</th>
            <th>Filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>
            <?php if ($status == 'D') { ?>
            <th>Last Listing Date</th>
            <th>Disposed Date</th>
            <th>Remark</th>
            <th>Status</th>
            <?php } else {
                            ?>
            <th>Listing Date</th>
            <th>Listing Purpose</th>
            <th>Next Listing Date</th>
            <th>Next Listing Purpose</th>
            <?php } ?>
            <th>Bench</th>
            <th>Court No</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>


        <?php
                        $data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $value['case_type']));
                        $case_proceeding = get_data($db, $schema . '.case_proceeding', array('filing_no' => $filing_no), array(), '*', 'listing_date desc limit 1');
                        $fgdfdf = 0;
                        if (!empty($case_proceeding)) {
                            $no = 1;
                            foreach ($case_proceeding as $value_proceeding) {
                        ?>
        <tr style="background-color:<?php echo $color; ?>">
            <td><?php echo $no;

                                        $case_type_data = $db->prepare("select purpose_name  from  $schema.master_purpose where purpose_code= ?");
                                        $case_type_data->bindParam(1, $value_proceeding['purpose'], PDO::PARAM_STR);
                                        $case_type_data->execute();
                                        $purpose_name = $case_type_data->fetchColumn();

                                        $case_type_data_next = $db->prepare("select purpose_name  from  $schema.master_purpose where purpose_code= ?");
                                        $case_type_data_next->bindParam(1, $value_proceeding['next_list_purpose'], PDO::PARAM_STR);
                                        $case_type_data_next->execute();
                                        $purpose_name_next = $case_type_data_next->fetchColumn();

                                        $case_proce = $db->prepare("select * from  $schema.case_allocation_temp where filing_no = ?");
                                        $case_proce->bindParam(1, $filing_no, PDO::PARAM_STR);
                                        $case_proce->execute();
                                        $case_proce_data = $case_proce->fetch();

                                        //  print_r($case_proce_data);

                                        $court_nono = $case_proce_data['court_no'];
                                        $bench_nature_nature = $case_proce_data['bench_nature'];

                                        //echo "select * from $schema.bench_nature where bench_code='$bench_nature_nature' order by display_priority ASC";
                                        $sqlm_bench = $db->prepare("select * from $schema.bench_nature where bench_code=? order by display_priority ASC");
                                        $sqlm_bench->bindParam(1, $bench_nature_nature, PDO::PARAM_STR);
                                        $sqlm_bench->execute();
                                        $sqlm_bench_data = $sqlm_bench->fetch();

                                        $bench_name = $sqlm_bench_data['bench_name'];

                                        ?></td>
            <td> <?php echo $filing_no; //  echo ltrim(substr( $filing_no,7,5),0).'/'.substr($filing_no,12,4);                                        
                                            ?>
            </td>
            <td> <?php echo $data_case_type[0]['short_name'] . '/' . $case_no . '/' . $case_year; ?></td>
            <td> <span
                    style="color: red;"><?php
                                                                    // echo $value['case_title'];
                                                                    echo $value['pet_name'] . ' </span> vs <span style="color: red;">' . $value['res_name']; ?></span>
            </td>

            <?php if ($status == 'D') {

                                        $case_disposal = get_data($db, $schema . '.case_disposal', array('filing_no' => $filing_no), array(), '*', 'filing_no desc limit 1');
                                        //print_r($case_disposal);
                                    ?>
            <td> <?php echo date('d/m/Y', strtotime($value_proceeding['listing_date'])); ?></td>
            <td><?php echo date('d/m/Y', strtotime($case_disposal[0]['disposal_date'])); ?></td>
            <td><?php echo $case_disposal[0]['remarks'] ?></td>
            <td>Disposed</td>
            <td><?php echo $bench_name; ?></td>
            <td> <?php echo $court_nono; ?></td>
            <td>
                <button class="btn btn-primary" onclick="generate_popup_resotre('<?php echo $filing_no; ?>')"> Re-Stored
                    Cases </a>
            </td>

            <?php } else { ?>
            <td> <?php echo date('d/m/Y', strtotime($value_proceeding['listing_date'])); ?></td>
            <td> <?php echo $purpose_name; ?></td>
            <td> <?php echo date('d/m/Y', strtotime($value_proceeding['next_list_date'])); ?></td>
            <td> <?php echo $purpose_name_next; ?></td>
            <td><?php echo $bench_name; ?></td>
            <td> <?php echo $court_nono; ?></td>
            <td>
                <button class="btn btn-primary"
                    onclick="generate_popup('<?php echo $value_proceeding['purpose']; ?>','<?php echo $filing_no; ?>','<?php echo $value_proceeding['listing_date']; ?>','<?php echo $value_proceeding['next_list_purpose']; ?>','<?php echo htmlspecialchars(htmlentities($value_proceeding['remarks'])); ?>','<?php echo $value_proceeding['next_list_date']; ?>','<?php echo $bench_nature_nature; ?>','<?php echo $court_nono; ?>')">
                    Edit </a>
            </td>
            <?php } ?>
        </tr>
        <?php $no++;
                            }
                        } else {

                            echo "<input type = 'hidden' name='filiii_no' id='filiii_no' value='" . $filiddfd . "'>";

                            echo '<tr> <td colspan="8"> <p  style="font-size: large; text-align: center; color: red;"> Data not found! </p> </td> </tr>';
                        } ?>

    </tbody>
</table>

<?php $ii++;
            }
        } else {
            echo '1';
        }
    } else if ($_POST['action'] == 'date_search_filing') {
        $schema = $_SESSION['schema_name'];
        $location = $_SESSION['location'];
        $check_date = $_POST['check_date'];
        ?>


<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444444;color:#ffffff;">
        <tr role="row">
            <th>S.No</th>
            <th>Filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>

            <th>Listing Date</th>
            <th>Listing Purpose</th>
            <th>Next Listing Date</th>
            <th>Next Listing Purpose</th>
            <th>Bench</th>
            <th>Court No</th>
        </tr>
    </thead>
    <tbody>
        <?php
                $case_proceeding = get_data($db, $schema . '.case_proceeding', array('next_list_date' => $check_date), array(), '*', ' asc ', ' court_no');

                $query = "select DISTINCT(a.filing_no),e.bench_no,g.purpose_name,d.purpose_name purpose_name_next,a.next_list_date,a.listing_date,e.court_no,b.status,b.pet_name,b.res_name,b.case_no,b.case_year,b.case_type,e.bench_nature,f.bench_name,c.short_name
		from lucknow.case_proceeding a
				inner join $schema.case_detail b ON b.filing_no=a.filing_no
				inner join public.case_type c ON c.id=b.case_type
				inner join $schema.master_purpose d ON d.purpose_code=a.next_list_purpose
				inner join $schema.master_purpose g ON g.purpose_code=a.purpose
				inner join $schema.case_allocation_temp e ON e.filing_no=a.filing_no
				left join $schema.bench_nature f ON f.bench_code=e.bench_nature
				where a.next_list_date=? order by court_no asc;";
                $rs = $db->prepare($query);
                $rs->bindParam(1, $check_date, PDO::PARAM_STR);
                $rs->execute();
                if ($rs->rowCount() > 0) {
                    $no = 1;
                    while ($data = $rs->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                        $case_no = $data['case_no'];
                        $bench_no = $data['bench_no'];
                        $listing_date = $data['listing_date'];
                        $filing_no = $data['filing_no'];
                        $case_year = $data['case_year'];
                        $status = $data['status'];
                        $court_nono = $data['court_no'];
                        $bench_nature_nature = $data['bench_nature'];
                        $bench_name = $data['bench_name'];
                        $short_name = $data['short_name'];
                        $pet_name = $data['pet_name'];
                        $res_name = $data['res_name'];
                        $purpose_name = $data['purpose_name'];
                        $purpose_name_next = $data['purpose_name_next'];
                        $next_list_date = $data['next_list_date'];

                        echo '
				<tr style="background-color:' . $color . '">
					<td>' . $no . '</td>
					<td>' . $filing_no . '</td>
					<td>' . $short_name . '/' . $case_no . '/' . $case_year . '</td>
					<td><span style="color: red;">' . $pet_name . ' </span> vs <span style="color: red;">' . $res_name . '</span></td>
					<td>' . date('d/m/Y', strtotime($listing_date)) . '</td>
					<td>' . $purpose_name . '</td>
					<td>' . date('d/m/Y', strtotime($next_list_date)) . '</td>
					<td>' . $purpose_name_next . '</td>
					<td>' . $bench_name . '</td>
					<td>' . $court_nono . '</td>
				</tr>';
                        $no++;
                    }
                } else {
                    echo "<input type = 'hidden' name='filiii_no' id='filiii_no' value='" . $filing_no . "'>";
                    echo '<tr> <td colspan="8"> <p  style="font-size: large; text-align: center; color: red;"> Data not found! </p> </td> </tr>';
                }
                ?>

    </tbody>
</table>

<?php

    } else if ($_POST['action'] == 'change_advocate') {


        $schema = $_SESSION['schema_name'];
        $location = $_SESSION['location'];
        $data = commonFunction($db, $schema, $location, $_POST);
    ?>

<form method="post" id="change_advocate_form_id" name="change_advocate_form_id">
    <table id="example2" class="table table-bordered table-hover dataTable" role="grid"
        aria-describedby="example2_info">
        <thead style="background-color:#444444;color:#ffffff;">
            <tr role="row">
                <th>Sr.No</th>
                <th>Filing No</th>
                <th>Case No</th>
                <th>Cause Title</th>
            </tr>
        </thead>
        <tbody>


            <?php
                    $filing_no = '';
                    $case_no = '';
                    $cause_tutle = '';
                    if (!empty($data) && is_array($data)) {
                        $ii = 1;
                        foreach ($data as $value) {

                            $filing_no = $value['filing_no'];
                            $case_no = $value['case_no'];
                            $case_year = $value['case_year'];
                            $status = $value['status'];
                            $case_type = $value['case_type'];
                            $defect_listed = $value['defect_listed'];
                            $cause_tutle = $value['pet_name'] . ' VS ' . $value['res_name'];

                            $data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $value['case_type']));
                            //$case_proceeding = get_data($db, $schema . '.case_proceeding', array('filing_no' => $filing_no), array(), '*', 'listing_date desc limit 1');
                            $fgdfdf = 0;

                    ?>
            <input type="hidden" name="filing_no_change" id="filing_no_change" value="<?php echo $filing_no; ?>">
            <tr style="background-color:<?php echo $color; ?>">
                <td><?php echo $ii; ?></td>
                <td> <?php echo $filing_no; ?> </td>
                <td> <?php $ad_d = '';
                                        if ($defect_listed == 1) {
                                            $ad_d = 'D';
                                        }
                                        echo $data_case_type[0]['short_name'] . ' ' . $ad_d . '/' . $case_no . '/' . $case_year; ?>
                </td>
                <td> <span
                        style="color: red;"><?php echo $value['pet_name'] . ' </span> vs <span style="color: red;">' . $value['res_name']; ?></span>
                </td>
            </tr>
            <?php $no++;
                            $ii++;
                        } ?>
            <?php } else {
                        echo '<tr><td colspan="6" style="text-align: center;">No Data Found </td></tr>';
                    } ?>

        </tbody>
    </table>

    <input type="hidden" id="cause_no_<?php echo $filing_no; ?>" value="<?php echo $case_no; ?>"
        name="cause_no_<?php echo $filing_no; ?>">
    <input type="hidden" id="cause_title_<?php echo $filing_no; ?>" value="<?php echo $cause_tutle; ?>"
        name="cause_title_<?php echo $filing_no; ?>">

    <input type="hidden" id="flag_type_flag_type" value="P" name="flag_type_flag_type">


    <?php
            $advocate_list = "SELECT a.remarks,b.rep_name,b.id,a.rep_code,b.bar_council_number,a.party_flag,c.name FROM public.e_more_representative as a inner join e_master_advocate as b on a.rep_code = b.id inner join e_cases_party as c on a.party_code = c.id where a.display = 'true' and  a.filing_no = '$filing_no' and  b.rep_name !='' ";
            $advocate_list = $db->prepare($advocate_list);
            $advocate_list->execute();
            $advocate_list = $advocate_list->fetchAll();
            //  print_r($advocate_list);

            ?>
    <table id="example2" class="table table-bordered table-hover dataTable" role="grid"
        aria-describedby="example2_info">
        <thead style="background-color:#444444;color:#ffffff;">
            <tr role="row">
                <th>Sr.No</th>
                <th>Party Name</th>
                <th>Party Type</th>
                <th>Advoacte Name</th>
                <th>Council Number</th>

                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php

                    if (!empty($advocate_list) && is_array($advocate_list)) {
                        $ii = 1;
                        foreach ($advocate_list as $value) {
                            $flag_party = 'Respondent';
                            if ($value['party_flag'] == 'P') {
                                $flag_party = 'Applicant/Appellant`s ';
                            }

                    ?>
            <tr style="background-color:<?php echo $color; ?>">
                <td><?php echo $ii; ?></td>
                <td> <?php echo $value['name']; ?></td>
                <td> <?php echo $flag_party; ?></td>
                <td> <?php echo $value['rep_name']; ?> </td>
                <td> <?php echo $value['bar_council_number']; ?></td>
                <td> <?php echo $value['remarks']; ?></td>
            </tr>
            <?php $ii++;
                        }
                    } ?>
        </tbody>
    </table>




    <div class="modal-body with-padding">


        <div class="form-group">
            <div class="row">
                <div class="col-sm-12">
                    <input type="radio" checked onclick="fn_get_party('P')" name="select_party_type"
                        id="select_party_type1" value="1">
                    <label> Applicant/Appellant`s </label>
                    &nbsp;&nbsp;&nbsp;
                    <input type="radio" onclick="fn_get_party('R')" name="select_party_type" id="select_party_type2"
                        value="2">
                    <label> Respondent</label>
                    &nbsp;
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label> Select Party :</label>
                    <select onchange="fn_showing_data(this.value,'<?php echo $filing_no; ?>')"
                        class="form-control required" id="select_party_option" name="select_party_option">
                        <option value="">select Party</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="after_select_data">


        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button type="button" onclick="fn_save_update_case_no()" name="form_data" class="btn btn-primary">
            Submit
        </button>
    </div>
</form>
<script>
$(document).ready(function() {

    fn_get_party('P');
});
</script>

<?php

    } else if ($_POST['action'] == 'search_filing_case_modify') {
        $schema = $_SESSION['schema_name'];
        $location = $_SESSION['location'];
        $data = commonFunction($db, $schema, $location, $_POST);
    ?>


<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444444;color:#ffffff;">
        <tr role="row">
            <th>Sr.No</th>
            <th>Filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>
            <!-- <th>Select Type</th> -->
            <th>Action</th>
        </tr>
    </thead>
    <tbody>


        <?php

                if (!empty($data) && is_array($data)) {
                    $ii = 1;
                    foreach ($data as $value) {

                        $filing_no = $value['filing_no'];
                        $case_no = $value['case_no'];
                        $case_year = $value['case_year'];
                        $status = $value['status'];
                        $case_type = $value['case_type'];
                        $defect_listed = $value['defect_listed'];

                        $data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $value['case_type']));
                        //    $case_proceeding = get_data($db, $schema . '.case_proceeding', array('filing_no' => $filing_no), array(), '*', 'listing_date desc limit 1');
                        $fgdfdf = 0;

                ?>
        <tr style="background-color:<?php echo $color; ?>">
            <td><?php echo $ii;

                                ?></td>
            <td>



                <?php echo $filing_no; //  echo ltrim(substr( $filing_no,7,5),0).'/'.substr($filing_no,12,4);                                          
                                ?>
            </td>
            <td> <?php
                                    $ad_d = '';
                                    if ($defect_listed == 1) {
                                        $ad_d = 'D';
                                    }

                                    echo $data_case_type[0]['short_name'] . ' ' . $ad_d . '/' . $case_no . '/' . $case_year;
                                    ?></td>


            <td> <span
                    style="color: red;"><?php
                                                            // echo $value['case_title'];
                                                            echo $value['pet_name'] . ' </span> vs <span style="color: red;">' . $value['res_name']; ?></span>
            </td>



            <td>

                <?php if ($case_type == '22') { ?>
                <button type="button"
                    onclick="fn_change_caseno_entry('<?php echo $filing_no; ?>','<?php echo $defect_listed; ?>','<?php echo $case_type; ?>')"
                    class="btn btn-primary">Submit</button>
                <?php } else if ($defect_listed == 1) { ?>
                <button type="button"
                    onclick="fn_change_caseno_entry('<?php echo $filing_no; ?>','<?php echo $defect_listed; ?>','<?php echo $case_type; ?>')"
                    class="btn btn-primary">Submit</button>
                <?php } else {
                                    echo 'Already done. ';
                                } ?>
            </td>

        </tr>
        <?php $no++;

                        $ii++;
                    }
                } else {
                    echo '<tr><td colspan="6" style="text-align: center;">No Data Found </td></tr>';
                } ?>

    </tbody>
</table>

<div id="fn_change_case_no_div">
</div>
<?php
    } else if ($_POST['action'] == 'update_case_type_change') {

        $schemas = $_SESSION['schema_name'];
        $filing_no = $_REQUEST['filing_no'];
        $case_type_change = $_REQUEST['case_type_change'];
        $remarks_changes = $_REQUEST['remarks_changes'];
        $defect_listed = $_REQUEST['defect_listed'];
        $case_type_case_np = $_REQUEST['case_type_case_np'];
        if ($defect_listed == 0 && $case_type_change == '1') {
            echo 'Already Defect Free';
            die;
        } else if ($case_type_change == '2' && $case_type_case_np != '22') {
            echo 'Already changed Misc. No to Appeal';
            die;
        } else {
            $data_ca = $db->prepare("select filing_no,case_no,case_year,case_type,defect_listed from $schemas.case_detail where filing_no=? ");
            $data_ca->bindParam(1, $filing_no, PDO::PARAM_STR);
            $data_ca->execute();
            $data_case_details = $data_ca->fetch();
            //print_r($data_case_details);
            $exit_filing_no = $data_case_details['filing_no'];
            $case_noaa = $data_case_details['case_no'];
            $reg_no_old = $data_case_details['case_no'];
            $case_year = $data_case_details['case_year'];
            $case_type = $data_case_details['case_type'];
            $defect_listed_old = $data_case_details['defect_listed'];
            $change_cases = '';
            //  die;
            $case_typessss = '1';
            if ($exit_filing_no != '') {
                $db->beginTransaction();
                try {
                    if ($case_type_change == '2') {
                        $change_cases = 'Appeal';
                        $year = date('Y');
                        $year_upadte_new = date('Y');
                        $stqq = $db->prepare("select reg_no from $schemas.case_type_reg where reg_year=? and case_type=?");
                        $stqq->bindParam(1, $year, PDO::PARAM_STR);
                        $stqq->bindParam(2, $case_typessss, PDO::PARAM_STR);
                        $stqq->execute();
                        $reg_no = $stqq->fetchColumn();
                        if ($reg_no == 0) {
                            $reg_no = 1;
                        } else {
                            $reg_no = $reg_no + 1;
                        }
                        $st12x1 = $db->prepare("update $schemas.case_type_reg set reg_no=? where case_type=? and reg_year=? ");
                        $st12x1->bindParam(1, $reg_no, PDO::PARAM_STR);
                        $st12x1->bindParam(2, $case_typessss, PDO::PARAM_STR);
                        $st12x1->bindParam(3, $year, PDO::PARAM_STR);
                        $st12x1->execute();
                        $defect_listed_new = $defect_listed_old;
                    }
                    if ($case_type_change == '1') {
                        $change_cases = 'Appeal';
                        $defect_listed_new = '0';
                        $reg_no = $reg_no_old;
                        $year_upadte_new = $case_year;
                    }
                    if ($case_type_change == '3') {
                        $change_cases = 'Appeal no chang';
                        $defect_listed_new = '0';
                        $reg_no = $reg_no_old;
                        $year_upadte_new = $case_year;
                    }
                    $created_datetime = date('Y-m-d H:i:s');
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $st = $db->prepare("insert into $schemas.case_no_update(filing_no,old_data,new_data,created_date,user_id,created_datetime,ip) values(?,?,?,?,?,?,?)");
                    $sessionUserType = htmlspecialchars($_SESSION['id']);
                    $server_date = date('Y-m-d');
                    $new_data = array('case_no' => $reg_no, 'case_year' => $year_upadte_new, 'case_type' => '1', 'defect_listed' => $defect_listed_new, 'remarks_msg' => $remarks_changes);
                    $old_data = array('case_no' => $reg_no_old, 'case_year' => $case_year, 'case_type' => $case_type, 'defect_listed' => $defect_listed_old);
                    $new_data = json_encode($new_data);
                    $old_data = json_encode($old_data);
                    $st->bindParam(1, $filing_no, PDO::PARAM_STR);
                    $st->bindParam(2, $old_data, PDO::PARAM_STR);
                    $st->bindParam(3, $new_data, PDO::PARAM_STR);
                    $st->bindParam(4, $server_date, PDO::PARAM_STR);
                    $st->bindParam(5, $sessionUserType, PDO::PARAM_STR);
                    $st->bindParam(6, $created_datetime, PDO::PARAM_STR);
                    $st->bindParam(7, $ip, PDO::PARAM_STR);
                    $st->execute();
                    $change_remark_case_no = $remarks_changes;
                    $st22222 = "update $schemas.case_detail set case_no=?,case_year=?,case_type=?, change_case=?,defect_listed=?,old_case_no = '$reg_no_old',old_case_year = '$case_year',old_case_type = '$case_type',change_remark_case_no = '$change_remark_case_no' where filing_no=?";
                    $st1111 = $db->prepare($st22222);
                    $year_case = date('Y');
                    $st1111->bindParam(1, $reg_no, PDO::PARAM_STR);
                    $st1111->bindParam(2, $year_upadte_new, PDO::PARAM_STR);
                    $st1111->bindParam(3, $case_typessss, PDO::PARAM_STR);
                    $st1111->bindParam(4, $change_cases, PDO::PARAM_STR);
                    $st1111->bindParam(5, $defect_listed_new, PDO::PARAM_STR);
                    $st1111->bindParam(6, $filing_no, PDO::PARAM_STR);
                    $st1111->execute();
                    $new_case_appel = 'Appeal ' . $reg_no . ' / ' . $year_upadte_new;
                    echo 'New ' . $new_case_appel . ' Sucessfully Updated';
                    $db->commit();
                } catch (PDOException $ex) {
                    $db->rollBack();
                    echo $ex;
                }
            } else {
                echo 'Not Data Exit. Please Try again';
            }
        }
        die;
    } else if ($_POST['action'] == 'filing_case_listed') {
        $schema = $_SESSION['schema_name'];
        $location = $_SESSION['location'];
        $data = commonFunction($db, $schema, $location, $_POST);
    ?>


<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444444;color:#ffffff;">
        <tr role="row">
            <th>Sr.No</th>
            <th>Filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>
        </tr>
    </thead>
    <tbody>
        <?php
                if (!empty($data) && is_array($data)) {
                    $ii = 1;

                    // print_r($data);
                    foreach ($data as $value) {
                        $filing_no = $value['filing_no'];
                        $case_no = $value['case_no'];
                        $case_year = $value['case_year'];
                        $status = $value['status'];
                        $defect_listed = $value['defect_listed'];
                        $data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $value['case_type']));
                        $fgdfdf = 0;
                ?>
        <tr style="background-color:<?php echo $color; ?>">
            <td><?php echo $ii;

                                ?></td>
            <td>
                <input type="hidden" name="filing_name" id="filing_name" value="<?php echo $filing_no; ?>">
                <?php echo $filing_no; //  echo ltrim(substr( $filing_no,7,5),0).'/'.substr($filing_no,12,4);                                        
                                ?>
            </td>
            <td> <?php

                                    $ad_d = '';
                                    if ($defect_listed == 1) {
                                        $ad_d = 'D';
                                    }

                                    if ($case_no != '') {
                                        echo $data_case_type[0]['short_name'] . '/' . $case_no . '/' . $case_year;
                                    } else {
                                        echo 'Appeal D ' . ltrim(substr($filing_no, 7, 5), 0) . '/' . substr($filing_no, 12, 4);
                                    }
                                    ?></td>


            <td> <span
                    style="color: red;"><?php
                                                            echo $value['pet_name'] . ' </span> vs <span style="color: red;">' . $value['res_name']; ?></span>
            </td>
        </tr>
        <?php $no++;

                        $ii++;
                    }
                } else {
                    echo '<tr><td colspan="4">No Data Found</td></tr>';
                } ?>
    </tbody>
</table>
<?php if (!empty($data) && is_array($data)) { ?>
<div class="box-body" id="case_no_update_div" style="display:block">
    <form method="post" id="urgent_case_listed_form_id" name="urgent_case_listed_form_id">
        <input type="hidden" name="change_filing_no" id="change_filing_no">
        <div class="modal-body with-padding">
            <div class="form-group">
                <div class="row">

                    <div class="col-sm-4">
                        <label>Listing Date.</label>
                        <input type="text" id="from_list_date" name="from_list_date" readonly="true"
                            class="form-control required datepicker" size="10"
                            value="<?php print htmlspecialchars($from_list_date); ?>" />
                    </div>
                    <div class="col-sm-4">
                        <label>&nbsp;</label>
                        &nbsp;
                    </div>

                    <div class="col-sm-4">
                        <label>&nbsp;</label>
                        &nbsp;
                    </div>



                </div>
            </div>


            <div class="form-group">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-check">
                            <strong> Not To Be Listed.</strong> <input type="radio" onchange="fn_judge_list('1','yes')"
                                class="form-check-input required" id="not_to_be_listed_yes" name="not_to_be_listed"
                                value="1">
                            <label class="form-check-label" for="not_to_be_listed_yes">YES</label>
                            <input type="radio" onchange="fn_judge_list('1','no')" class="form-check-input required"
                                id="not_to_be_listed_no" name="not_to_be_listed" value="0" checked>
                            <label class="form-check-label" for="not_to_be_listed_no">NO</label>
                        </div>
                    </div>
                    <div id="div_judge_1"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-check">
                            <strong> To Be Listed.</strong>
                            <input type="radio" onchange="fn_judge_list('2','yes')" class="form-check-input required"
                                id="to_be_listed_yes" name="to_be_listed" value="1">
                            <label class="form-check-label" for="to_be_listed_yes">YES</label>
                            <input type="radio" onchange="fn_judge_list('2','no')" class="form-check-input required"
                                id="to_be_listed_no" name="to_be_listed" value="0" checked>
                            <label class="form-check-label" for="to_be_listed_no">NO</label>
                        </div>
                    </div>
                    <div id="div_judge_2"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">


                    <div class="col-sm-8">
                        <label>Remarks.</label>
                        <textarea rows="6" cols="80" type="text" id="remarks" name="remarks"
                            class="form-control required"></textarea>
                    </div>
                    <div class="col-sm-4" style="margin-top: 119px;">
                        <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <button type="button" onclick="fn_urgent_list_case()" class="btn btn-primary">List Case
                            Number</button>
                    </div>

                </div>
            </div>

        </div>

    </form>
</div>
<?php } ?>
<?php
    } else if ($_POST['action'] == 'search_filing_delete') {
        $schema = $_SESSION['schema_name'];
        $location = $_SESSION['location'];
        $data = commonFunction($db, $schema, $location, $_POST);
    ?>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>

<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444444;color:#ffffff;">
        <tr role="row">
            <th>Sr.No</th>
            <th>Filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
                if (!empty($data) && is_array($data)) {
                    $ii = 1;
                    foreach ($data as $value) {

                        $filing_no = $value['filing_no'];
                        $case_no = $value['case_no'];
                        $case_year = $value['case_year'];
                        $status = $value['status'];
                        $defect_listed = $value['defect_listed'];

                        $data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $value['case_type']));
                        $fgdfdf = 0;

                ?>
        <tr style="background-color:<?php echo $color; ?>">
            <td><?php echo $ii;

                                ?></td>
            <td>
                <input type="hidden" name="filing_name" id="filing_name" value="<?php echo $filing_no; ?>">
                <?php echo $filing_no; ?>
            </td>
            <td> <?php

                                    $ad_d = '';
                                    if ($defect_listed == 1) {
                                        $ad_d = 'D';
                                    }

                                    if ($case_no != '') {
                                        echo $data_case_type[0]['short_name'] . '/' . $case_no . '/' . $case_year;
                                    } else {
                                        echo 'Appeal D ' . ltrim(substr($filing_no, 7, 5), 0) . '/' . substr($filing_no, 12, 4);
                                    }
                                    ?></td>


            <td> <span
                    style="color: red;"><?php echo $value['pet_name'] . ' </span> vs <span style="color: red;">' . $value['res_name']; ?></span>
            </td>
            <td>
                <input type="button" onclick="fn_delete_entry('<?php echo $filing_no; ?>')"
                    class="btn btn-sm btn-success" value="Delete Filing No">
            </td>

        </tr>
        <?php $no++;

                        $ii++;
                    }
                } else {
                    echo '<tr><td colspan="4">No Data Found</td></tr>';
                } ?>





    </tbody>
</table>

<?php
    } else if ($_POST['action'] == 'search_filing_case_list') {
        $schema = $_SESSION['schema_name'];
        $location = $_SESSION['location'];
        $data = commonFunction($db, $schema, $location, $_POST);
    ?>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>

<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444444;color:#ffffff;">
        <tr role="row">
            <th>Sr.No</th>
            <th>Filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>
        </tr>
    </thead>
    <tbody>
        <?php
                if (!empty($data) && is_array($data)) {
                    $ii = 1;
                    foreach ($data as $value) {

                        $filing_no = $value['filing_no'];
                        $case_no = $value['case_no'];
                        $case_year = $value['case_year'];
                        $status = $value['status'];
                        $defect_listed = $value['defect_listed'];

                        $data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $value['case_type']));
                        $fgdfdf = 0;

                ?>
        <tr style="background-color:<?php echo $color; ?>">
            <td><?php echo $ii;

                                ?></td>
            <td>
                <input type="hidden" name="filing_name" id="filing_name" value="<?php echo $filing_no; ?>">
                <?php echo $filing_no; ?>
            </td>
            <td> <?php

                                    $ad_d = '';
                                    if ($defect_listed == 1) {
                                        $ad_d = 'D';
                                    }

                                    if ($case_no != '') {
                                        echo $data_case_type[0]['short_name'] . '/' . $case_no . '/' . $case_year;
                                    } else {
                                        echo 'Appeal D ' . ltrim(substr($filing_no, 7, 5), 0) . '/' . substr($filing_no, 12, 4);
                                    }
                                    ?></td>


            <td> <span
                    style="color: red;"><?php echo $value['pet_name'] . ' </span> vs <span style="color: red;">' . $value['res_name']; ?></span>
            </td>

        </tr>
        <?php $no++;

                        $ii++;
                    }
                } else {
                    echo '<tr><td colspan="4">No Data Found</td></tr>';
                } ?>





    </tbody>
</table>
<?php if (!empty($data) && is_array($data)) { ?>
<div class="box-body" id="case_no_update_div" style="display:block">
    <form method="post" id="urgent_case_lis_form_id" name="urgent_case_lis_form_id">
        <input type="hidden" name="change_filing_no" id="change_filing_no">
        <div class="modal-body with-padding">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-4">
                        <label> Bench :</label>
                        <select required="required" class="form-control" name="bench_location_name"
                            id="bench_location_name" onChange="javascript:submitForm();">
                            <?php
                                        $sqlm1 = $db->prepare("select * from $schema.bench_location where display=?");
                                        $display = 'Y';
                                        $sqlm1->bindParam(1, $display, PDO::PARAM_STR);
                                        $sqlm1->execute();
                                        while ($row1 = $sqlm1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                                            $bench_location1 = $row1['bench_location_code'];
                                        ?>
                            <option value="<?php echo htmlspecialchars($row1['bench_location_code']); ?>"
                                <?php if ($benchlocation == $bench_location1) {
                                                                                                                                echo "selected";
                                                                                                                            } ?>>
                                <?php echo htmlspecialchars($row1['bench_location_name']); ?></option>
                            <?php
                                        }
                                        ?>
                        </select>



                    </div>
                    <div class="col-sm-4">
                        <label>Bench Nature.</label>

                        <select name="bench_code" class="form-control" id="bench_code" required="required" class=""
                            onChange="fn_select_coram(this.value);">
                            <option value="">-select-</option>
                            <?php

                                        $sqlm = $db->prepare("select * from $schema.bench_nature where display=? order by display_priority ASC");
                                        $display = 'Y';
                                        $sqlm->bindParam(1, $display, PDO::PARAM_STR);
                                        $sqlm->execute();
                                        while ($row = $sqlm->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

                                        ?>
                            <option value="<?php echo htmlspecialchars($row['bench_code']); ?>">
                                <?php echo htmlspecialchars($row['bench_name']); ?></option>
                            <?php
                                        }
                                        ?>
                        </select>

                        <?php

                                    ?>
                        <input type="hidden" maxlength="2" size="4" name="judge_count"
                            value="<?php echo htmlspecialchars(htmlentities($no_of_judge)); ?>">

                    </div>
                    <div class="col-sm-4">
                        <label>Court No.</label>
                        <?php $court_no_data = isset($_REQUEST['court_no']) ? $_REQUEST['court_no'] : '1'; ?>

                        <select name="court_no" id="court_no" class="form-control required">
                            <option value="">-select Court-</option>
                            <option <?php if ($court_no_data == '1') {
                                                    echo 'selected';
                                                } ?> value="1">Court 1
                            </option>
                            <option <?php if ($court_no_data == '2') {
                                                    echo 'selected';
                                                } ?> value="2">Court 2
                            </option>
                            <option <?php if ($court_no_data == '3') {
                                                    echo 'selected';
                                                } ?> value="3">Court 3
                            </option>
                            <option <?php if ($court_no_data == '4') {
                                                    echo 'selected';
                                                } ?> value="4">Court 4
                            </option>
                        </select>


                    </div>
                </div>
            </div>
            <div id="div_judge"></div>
            <div class="form-group">
                <div class="row">

                    <div class="col-sm-12">
                        <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <button type="button" onclick="fn_urgent_list_case()" class="btn btn-primary">List Case
                            Number</button>
                    </div>

                </div>
            </div>

        </div>

    </form>
</div>
<?php } ?>

<div id="fn_change_case_no_div">
</div>
<?php
    } else if ($_POST['action'] == 'selct_judge_name') {

        $bench_code = $_POST['bench_code'];
        $schema = $_SESSION['schema_name'];

        if ($bench_code > 0 and $bench_code != 7) {
            $sql = "select no_of_judges from $schema.bench_nature where bench_code = ? ";
            $sth = $db->prepare($sql);
            $sth->bindParam(1, $bench_code, PDO::PARAM_STR);
            $sth->execute();
            $no_of_judge = $sth->fetchColumn();
            if ($bench_code != 3) {
                echo "<font color='red'><b>NUMBER OF JUDGES:</b>   " . htmlspecialchars($no_of_judge) . "<br></font>";
            }
        }

        if ($bench_code == 7) {
            echo "<font ><b>NUMBER OF JUDGES:</b><input type='text' required='required'onblur='javascript:submitForm();' name='no_of_judge1' value='$_POST[no_of_judge1]'></font>";
            $aaa = $db->prepare("select count(*) from $schema.master_judge where display='TRUE' and 	judge_desg_code!=6");
            $aaa->execute();
            $dd = $aaa->fetchColumn();
            if ($_POST['no_of_judge1'] <= $dd) {
                $no_of_judge = $_POST['no_of_judge1'];
            } else {
                $no_of_judge = $dd;
            }
        }
    ?>
<div class="form-group">
    <div class="row">
        <?php
                for ($i = 0; $i < $no_of_judge; $i++) {
                    $m++;
                    $ii = $i + 1;
                ?>

        <div class="col-sm-4">
            <label> <?php if ($i == '0') { ?>CORAM : <?php } ?></label>

            <select required="required" onchange="fn_disabled_judge(this.value)" class="rohit form-control" class=""
                name="judge_name[]" style='width:300px;' required="required">
                <option value="">-select-</option>

                <?php
                            $display = 'TRUE';
                            $sqlf = $db->prepare("select * from $schema.master_judge where display=?  order by seniority asc");
                            $sqlf->bindParam(1, $display, PDO::PARAM_STR);
                            $sqlf->execute();
                            $datata = $sqlf->fetchAll();
                            foreach ($datata as $key => $row) {
                                $selected_data = '';
                                $judge_code = $row['judge_code'];

                                $judge_desg_code = $row['judge_desg_code'];
                                $desgsthname = '';
                                $des_ql = $db->prepare("select desg_name from $schema.master_desg where desg_code=? order by desg_code desc");
                                $des_ql->execute(array($judge_desg_code));
                                $desgsthname = $des_ql->fetchColumn();
                            ?>
                <option value="<?php echo htmlspecialchars($row['judge_code']); ?>">
                    <?php echo htmlspecialchars($row['judge_name']) . ' (' . $desgsthname . ')'; ?>
                </option>
                <?php
                            }
                            ?>
            </select>
        </div>
        <?php } ?>
        <div class="col-sm-4">
            <label>Listing Date.</label>
            <script src="../plugins/jQueryUI/jquery-ui.js"></script>
            <script src="../plugins/jQueryUI/date.js"></script>
            <input type="text" id="from_list_date" name="from_list_date" readonly="true"
                class="form-control required datepicker" size="10"
                value="<?php print htmlspecialchars($from_list_date); ?>" />
            <script src="../src/calendar.js"></script>
        </div>


        <div class="col-sm-4">
            <label>Purpose.</label>

            <?php $sql2 = " select * from $schema.master_purpose where display = true "; ?>
            <select name="purpose_id" id="purpose_id" class="form-control">
                <option value="">Select Purpose</option>
                <?php

                        foreach ($db->query($sql2) as $row) {
                            $purpose_code = $row['purpose_code'];
                            if ($purpose_id == $purpose_code) {
                                print "<option value=" . htmlentities(htmlspecialchars($row['purpose_code'])) . " selected>" . strtoupper(htmlentities(htmlspecialchars($row['purpose_name']))) . "</option>";
                            } else {
                                print "<option value=" . htmlentities(htmlspecialchars($row['purpose_code'])) . ">" . strtoupper(htmlentities(htmlspecialchars($row['purpose_name']))) . "</option>";
                            }
                        } ?>
                <option value="others">Other</option>
            </select>
        </div>


        <div class="col-sm-4">
            <label>Remarks.</label>
            <textarea type="text" id="remarks" name="remarks" class="form-control required"></textarea>
        </div>



    </div>
</div>

<?php
    } else if ($_POST['action'] == 'update_urgent_case_list') {
        $server_date = date('Y-m-d');
        $sessionUserType = htmlspecialchars($_SESSION['id']);
        $location_code = $_SESSION['location'];
        $listt_date = $_REQUEST['from_list_date'];
        list($d, $m, $Y) = explode('/', $listt_date);
        $listt_date = $Y . '-' . $m . '-' . $d;
        $schemas = $_SESSION['schema_name'];

        $court_no = $_POST['court_no'];
        $bench_nature = $_POST['bench_nature'];
        $check_case_bench = "select * from $schemas.bench where from_list_date=? and court_no = ? and bench_nature = ?";
        $check_case_bench = $db->prepare($check_case_bench);
        $check_case_bench->bindParam(1, $listt_date, PDO::PARAM_STR);
        $check_case_bench->bindParam(2, $court_no, PDO::PARAM_STR);
        $check_case_bench->bindParam(3, $bench_nature, PDO::PARAM_STR);
        $check_case_bench->execute();
        $data_bench = $check_case_bench->fetch();
        $bench_value = $data_bench['id'];
        if (empty($data_bench) && $data_bench['id'] == '') {
            $bench_value = '1';
        }
        $case_list_type = 'fresh';
        $b_nature = $_POST['bench_nature'];
        $court_no = $_POST['court_no'];
        $filing_no = $_POST['filing_no'];
        $purpose_id = $_POST['purpose_id'];
        if ($filing_no != '') {
            $check_list = "select legal_aid from $schemas.case_detail where filing_no =? ";
            $check_list = $db->prepare($check_list);
            $check_list->bindParam(1, $filing_no, PDO::PARAM_STR);
            $check_list->execute();
            $check_list_legal = $check_list->fetchColumn();
            if ($check_list_legal != 'A') {
                $insert = "insert into $schemas.case_allocation_temp(case_list_type,filing_no,listing_date,purpose,entry_date,deal_cd,connected,priority_serial,bench_nature,bench_no,
        court_no,list_flag,listed) values('fresh','$filing_no','$listt_date','$purpose_id','$server_date','$sessionUserType','N','999','$b_nature','$bench_value','$court_no','1','1')";
                try {
                    $query_insert = $db->prepare($insert);
                    $query_insert->execute();
                    $newst7 = "update $schemas.case_detail set group_scrutiny = '2', legal_aid ='A',location_code ='$location_code' where filing_no='$filing_no' ";
                    $query_insertdddd = $db->prepare($newst7);
                    $query_insertdddd->execute();
                    echo $msg = " CASE LISTED successfully";
                } catch (PDOException $ex) {
                    echo $ex->get;
                }
            } else {
                echo 'Already Listed. Please check & try again';
            }
        }
    } else if ($_POST['action'] == 'update_case_proced') {

        $schemas = $_SESSION['schema_name'];
        $filing_no = isset($_REQUEST['filing_no']) ? $_REQUEST['filing_no'] : '';
        $listing_date = isset($_REQUEST['listing_date']) ? $_REQUEST['listing_date'] : date('Y-m-d');
        $listing_purpose = isset($_REQUEST['listing_purpose']) ? $_REQUEST['listing_purpose'] : '';
        $next_listing_purpose = isset($_REQUEST['next_listing_purpose']) ? $_REQUEST['next_listing_purpose'] : '12';
        $next_listing_date = isset($_REQUEST['next_listing_date']) ? $_REQUEST['next_listing_date'] : '1999-12-12';
        $remarks = isset($_REQUEST['remarks']) ? $_REQUEST['remarks'] : 'no';
        $listed = date('Y-m-d');
        $user_id = $_SESSION['id'];
        $court_no = isset($_REQUEST['court_no']) ? $_REQUEST['court_no'] : '0';
        $bench_nature = isset($_REQUEST['bench_nature']) ? $_REQUEST['bench_nature'] : '0';
        $actual_next_listing_date = isset($_REQUEST['actual_next_listing_date']) ? $_REQUEST['actual_next_listing_date'] : '';
        $remarks_update = $_REQUEST['remarks_update'];
        $sql_temp_bench = "select max(id) as max_id from $schemas.bench where  from_list_date = '$next_listing_date' and court_no = '$court_no' and bench_nature = '$bench_nature'  ";
        $sql_temp_bench = $db->prepare($sql_temp_bench);
        $sql_temp_bench->execute();
        $last_insert_id_bench = $sql_temp_bench->fetchColumn();
        if ($last_insert_id_bench == '') {
            $last_insert_id_bench = '1';
        }
        if ($actual_next_listing_date == '') {
            $st_case_proceeding = "update $schemas.case_proceeding set next_list_purpose = '$next_listing_purpose', update_remarks_change = '$remarks_update', bench_nature='$bench_nature', court_no='$court_no', bench_no='$last_insert_id_bench', next_list_date=?,entry_date=?,user_id=?,remarks=? where filing_no=? and listing_date=? and purpose = ?";
            try {
                $db->beginTransaction();
                $st_case_proceeding = $db->prepare($st_case_proceeding);
                $st_case_proceeding->bindParam(1, $next_listing_date, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(2, $listed, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(3, $user_id, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(4, $remarks, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(5, $filing_no, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(6, $listing_date, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(7, $listing_purpose, PDO::PARAM_STR);
                $st_case_proceeding->execute();
                try {
                    $st22222 = "update $schemas.case_allocation_temp set purpose = '$next_listing_purpose', bench_nature='$bench_nature', court_no='$court_no', bench_no='$last_insert_id_bench', listing_date = '$next_listing_date', next_list_date=?,entry_date=? where filing_no=? ";
                    $st1111 = $db->prepare($st22222);
                    $st1111->bindParam(1, $next_listing_date, PDO::PARAM_STR);
                    $st1111->bindParam(2, $listed, PDO::PARAM_STR);
                    $st1111->bindParam(3, $filing_no, PDO::PARAM_STR);
                    $st1111->execute();
                    $db->commit();
                    echo 'Sucessfully Update';
                } catch (PDOException $ex) {
                    $db->rollBack();
                    echo $ex;
                }
            } catch (PDOException $ex) {
                $db->rollBack();
                echo $ex;
            }
        } else {

            $db->beginTransaction();
            try {
                $action_type = '0';
                $pen_dis = 'P';
                $curdate = date('Y-m-d');
                $sessionUserType = $_SESSION['id'];
                $criteria = '0';
                $bench_nooo = '1';
                $check_case_allocation_query = "select filing_no from $schemas.case_proceeding where filing_no=? and listing_date=?";
                $check_case_allocation_query = $db->prepare($check_case_allocation_query);
                $check_case_allocation_query->bindParam(1, $filing_no, PDO::PARAM_STR);
                $check_case_allocation_query->bindParam(2, $next_listing_date, PDO::PARAM_STR);
                $check_case_allocation_query->execute();
                $get_filing_no = $check_case_allocation_query->fetchColumn();
                $sql_temp_bench_ff = "select max(id) as max_id from $schemas.bench where  from_list_date = '$actual_next_listing_date' and court_no = '$court_no' and bench_nature = '$bench_nature'  ";
                $sql_temp_bench_ff = $db->prepare($sql_temp_bench_ff);
                $sql_temp_bench_ff->execute();
                $last_insert_id_benchss = $sql_temp_bench_ff->fetchColumn();
                if ($last_insert_id_benchss == '') {
                    $last_insert_id_benchss = '1';
                }

                $case_list_type = 'old';
                $listed = '1';
                try {
                    if ($get_filing_no == '') {
                        try {
                            $sty = "insert into $schemas.case_proceeding
                            (filing_no,listing_date,purpose,court_no,bench_nature,bench_no,
                            next_list_purpose,
                            next_list_date,todays_action,todays_status,entry_date,remarks,user_id,update_remarks_change)
                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                            $st13 = $db->prepare($sty);
                            $st13->bindParam(1, $filing_no, PDO::PARAM_STR);
                            $st13->bindParam(2, $next_listing_date, PDO::PARAM_STR);
                            $st13->bindParam(3, $next_listing_purpose, PDO::PARAM_STR);
                            $st13->bindParam(4, $court_no, PDO::PARAM_STR);
                            $st13->bindParam(5, $bench_nature, PDO::PARAM_STR);
                            $st13->bindParam(6, $bench_nooo, PDO::PARAM_STR);
                            $st13->bindParam(7, $next_listing_purpose, PDO::PARAM_STR);
                            $st13->bindParam(8, $actual_next_listing_date, PDO::PARAM_STR);
                            $st13->bindParam(9, $action_type, PDO::PARAM_STR);
                            $st13->bindParam(10, $pen_dis, PDO::PARAM_STR);
                            $st13->bindParam(11, $curdate, PDO::PARAM_STR);
                            $st13->bindParam(12, $remarks, PDO::PARAM_STR);
                            $st13->bindParam(13, $sessionUserType, PDO::PARAM_STR);
                            $st13->bindParam(14, $remarks_update, PDO::PARAM_STR);
                            $st13->execute();
                        } catch (PDOException $ex) {
                            $db->rollBack();
                            echo $ex . 'Stap 1';
                            die;
                        }
                    } else {
                        try {
                            $update_case_allocation_sql = "update $schemas.case_proceeding set
                 filing_no=?,listing_date=?,purpose=?,court_no=?,bench_nature=?,bench_no=?,next_list_purpose=?,next_list_criteria=?, next_list_date=?, todays_action=?, todays_status=?, entry_date=?, remarks=?, user_id=?,update_remarks_change=? where filing_no=? and listing_date=?";
                            $update_case_allocation_result = $db->prepare($update_case_allocation_sql);
                            $update_case_allocation_result->bindParam(1, $filing_no, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(2, $next_listing_date, PDO::PARAM_INT);
                            $update_case_allocation_result->bindParam(3, $next_listing_purpose, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(4, $court_no, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(5, $bench_nature, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(6, $bench_nooo, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(7, $next_listing_purpose, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(8, $criteria, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(9, $actual_next_listing_date, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(10, $action_type, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(11, $pen_dis, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(12, $curdate, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(13, $remarks, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(14, $sessionUserType, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(15, $remarks_update, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(16, $filing_no, PDO::PARAM_STR);
                            $update_case_allocation_result->bindParam(17, $next_listing_date, PDO::PARAM_STR);
                            $update_case_allocation_result->execute();
                        } catch (PDOException $ex) {
                            echo $ex . 'Stap 2';
                            $db->rollBack();
                            die;
                        }
                    }
                    $list_flag = '1';
                    try {
                        $sql_pr2 = "delete from $schemas.case_allocation  where listing_date='$next_listing_date' and court_no = '$court_no'  and bench_nature = '$bench_nature' and filing_no = '$filing_no' ";
                        $sth = $db->prepare($sql_pr2);
                        $sth->execute();
                        if ($sth->execute()) {
                            try {
                                $insert_final_case = "insert into $schemas.case_allocation(case_list_type,filing_no,listing_date,purpose,entry_date,deal_cd,
                                connected,priority_serial,bench_nature,bench_no,
                                court_no,list_flag,listed) values('old','$filing_no','$next_listing_date','$next_listing_purpose','$curdate',
                                '$sessionUserType','N','999','$bench_nature','$last_insert_id_bench','$court_no','$list_flag','1')";
                                $sth1_ins = $db->prepare($insert_final_case);
                                $sth1_ins->execute();
                            } catch (PDOException $ex) {
                                $db->rollBack();
                                echo $ex;
                            }
                        }
                    } catch (PDOException $ex) {
                        $db->rollBack();
                        echo $msg = $ex;
                    }
                    $st = "update $schemas.case_allocation_temp set purpose=?,deal_cd=?,entry_date=?,next_list_date=?,listed=?,listing_date=?,bench_no=?,case_list_type=? where filing_no=? ";
                    $st = $db->prepare($st);
                    $st->bindParam(1, $next_listing_purpose, PDO::PARAM_STR);
                    $st->bindParam(2, $sessionUserType, PDO::PARAM_INT);
                    $st->bindParam(3, $curdate, PDO::PARAM_STR);
                    $st->bindParam(4, $actual_next_listing_date, PDO::PARAM_STR);
                    $st->bindParam(5, $listed, PDO::PARAM_STR);
                    $st->bindParam(6, $actual_next_listing_date, PDO::PARAM_STR);
                    $st->bindParam(7, $last_insert_id_benchss, PDO::PARAM_STR);
                    $st->bindParam(8, $case_list_type, PDO::PARAM_STR);
                    $st->bindParam(9, $filing_no, PDO::PARAM_STR);
                    $st->execute();
                    echo 'Sucessfully Update';
                    $db->commit();
                } catch (PDOException $ex) {
                    $db->rollBack();
                    echo $ex . 'Stap 3';
                }
            } catch (PDOException $ex) {
                $db->rollBack();
                echo $ex;
            }
        }
    } else if ($_POST['action'] == 'update_case_restore') {

        $schemas = $_SESSION['schema_name'];
        $filing_no = $_REQUEST['filing_no'];
        $listing_date = $_REQUEST['listing_date'];
        $listing_purpose = $_REQUEST['listing_purpose'];
        $next_listing_purpose = $_REQUEST['next_listing_purpose'];
        $next_listing_date = $_REQUEST['next_listing_date'];
        $remarks = $_REQUEST['remarks'];
        $listed = date('Y-m-d');
        $user_id = $_SESSION['id'];
        $today_status = 'D';

        $data_ca_alaa = $db->prepare("select filing_no,bench_nature,court_no from $schemas.case_allocation_temp where filing_no=?");
        $data_ca_alaa->bindParam(1, $filing_no, PDO::PARAM_STR);
        $data_ca_alaa->execute();
        $exit_filing_no111 = $data_ca_alaa->fetch();

        // print_r($exit_filing_no111);
        $bench_nature_nature12 = $exit_filing_no111['bench_nature'];
        $court_no1231 = $exit_filing_no111['court_no'];

        $check_case_bench = "select id from $schemas.bench where from_list_date=? and court_no = ? and bench_nature = ?";
        $check_case_bench = $db->prepare($check_case_bench);
        $check_case_bench->bindParam(1, $next_listing_date, PDO::PARAM_STR);
        $check_case_bench->bindParam(2, $court_no1231, PDO::PARAM_STR);
        $check_case_bench->bindParam(3, $bench_nature_nature12, PDO::PARAM_STR);
        $check_case_bench->execute();
        $bench_no11 = $check_case_bench->fetchColumn();
        if ($bench_no11 == '') {
            $bench_no11 = '1';
        }
        //  echo $bench_no11;

        $data_ca = $db->prepare("select filing_no from $schemas.case_proceeding where filing_no=? and todays_status=? ");
        $data_ca->bindParam(1, $filing_no, PDO::PARAM_STR);
        $data_ca->bindParam(2, $today_status, PDO::PARAM_STR);
        $data_ca->execute();
        $exit_filing_no = $data_ca->fetchColumn();

        $data_ca_al = $db->prepare("select filing_no from $schemas.case_allocation_temp where filing_no=?");
        $data_ca_al->bindParam(1, $filing_no, PDO::PARAM_STR);
        $data_ca_al->execute();
        $exit_filing_no1 = $data_ca_al->fetchColumn();

        $data_ca2 = $db->prepare("select filing_no from $schemas.case_disposal where filing_no=?");
        $data_ca2->bindParam(1, $filing_no, PDO::PARAM_STR);
        $data_ca2->execute();
        $exit_filing_no2 = $data_ca2->fetchColumn();

        if ($exit_filing_no != '' && $exit_filing_no1 != '' && $exit_filing_no2 != '') {
            try {
                $db->beginTransaction();
                $todays_status = 'D';
                $up_todays_status = 'P';
                $st_case_proceeding = "update $schemas.case_proceeding set next_list_date=?,next_list_purpose=?,todays_status=?,entry_date=?,user_id=?,remarks=?
        where filing_no=? and todays_status=?";
                $st_case_proceeding = $db->prepare($st_case_proceeding);
                $st_case_proceeding->bindParam(1, $next_listing_date, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(2, $next_listing_purpose, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(3, $up_todays_status, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(4, $listed, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(5, $user_id, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(6, $remarks, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(7, $filing_no, PDO::PARAM_STR);
                $st_case_proceeding->bindParam(8, $todays_status, PDO::PARAM_STR);
                $st_case_proceeding->execute();
                try {
                    $st22222 = "update $schemas.case_allocation_temp set listing_date = ?,next_list_date=?,entry_date=?,purpose=?,bench_no = '$bench_no11' where filing_no=?";
                    $st1111 = $db->prepare($st22222);
                    $st1111->bindParam(1, $next_listing_date, PDO::PARAM_STR);
                    $st1111->bindParam(2, $next_listing_date, PDO::PARAM_STR);
                    $st1111->bindParam(3, $listed, PDO::PARAM_STR);
                    $st1111->bindParam(4, $next_listing_purpose, PDO::PARAM_STR);
                    $st1111->bindParam(5, $filing_no, PDO::PARAM_STR);
                    $st1111->execute();

                    $st22222_case_d = "update $schemas.case_detail set status = ? where filing_no= ? ";
                    $st22222_case_dd = $db->prepare($st22222_case_d);
                    $st22222_case_dd->bindParam(1, $up_todays_status, PDO::PARAM_STR);
                    $st22222_case_dd->bindParam(2, $filing_no, PDO::PARAM_STR);
                    $st22222_case_dd->execute();

                    $st_case_disposal = "delete from $schemas.case_disposal where filing_no=?";
                    $st_case_disposal = $db->prepare($st_case_disposal);
                    $st_case_disposal->bindParam(1, $filing_no, PDO::PARAM_STR);
                    $st_case_disposal->execute();
                    $db->commit();
                    echo 'Sucessfully Update';
                } catch (PDOException $ex) {
                    $db->rollBack();
                    echo $ex;
                }
            } catch (PDOException $ex) {
                $db->rollBack();
                echo $ex;
            }
        } else {
            echo 'Not Data Exit. Please Try again';
        }

        die;
    } else if ($_POST['action'] == 'update_case_no_change') {

        // print_r($_REQUEST);

        $schemas = $_SESSION['schema_name'];
        $filing_no = $_REQUEST['filing_no'];
        $old_case_type = $_REQUEST['old_case_type'];
        $old_case_no = $_REQUEST['old_case_no'];
        $old_case_year = $_REQUEST['old_case_year'];
        $new_case_type = $_REQUEST['new_case_type'];
        $new_case_no = $_REQUEST['new_case_no'];
        $new_case_year = $_REQUEST['new_case_year'];
        $data_ca = $db->prepare("select filing_no,case_no,case_year,case_type from $schemas.case_detail where filing_no=? ");
        $data_ca->bindParam(1, $filing_no, PDO::PARAM_STR);
        $data_ca->execute();
        $data_case_details = $data_ca->fetch();

        //print_r($data_case_details);
        $exit_filing_no = $data_case_details['filing_no'];
        $reg_no = $data_case_details['case_no'];
        $reg_no_old = $data_case_details['case_no'];
        $case_year = $data_case_details['case_year'];
        $case_type = $data_case_details['case_type'];

        $change_cases = 'Diary';
        if ($_REQUEST['old_case_type_change'] == '1') {
            $change_cases = 'Appeal';
            $year = date('Y');
            $case_type = '1';
            if ($reg_no == '') {
                $stqq = $db->prepare("select reg_no from $schemas.case_type_reg where reg_year=? and case_type=?");
                $stqq->bindParam(1, $year, PDO::PARAM_STR);
                $stqq->bindParam(2, $case_type, PDO::PARAM_STR);
                $stqq->execute();
                $reg_no = $stqq->fetchColumn();
                if ($reg_no == 0) {
                    $reg_no = 1;
                } else {
                    $reg_no = $reg_no + 1;
                }

                $st12x1 = $db->prepare("update $schemas.case_type_reg set reg_no=? where case_type=? and reg_year=? ");
                $st12x1->bindParam(1, $reg_no, PDO::PARAM_STR);
                $st12x1->bindParam(2, $case_type, PDO::PARAM_STR);
                $st12x1->bindParam(3, $year, PDO::PARAM_STR);
                $st12x1->execute();
            }
        }

        //  die;
        if ($exit_filing_no != '') {
            try {
                $db->beginTransaction();
                $st = $db->prepare("insert into $schemas.case_no_update(filing_no,old_data,new_data,created_date,user_id)
values(?,?,?,?,?)");
                $sessionUserType = htmlspecialchars($_SESSION['id']);
                $server_date = date('Y-m-d');
                $new_data = array('case_no' => $reg_no, 'case_year' => date('Y'), 'case_type' => '1');
                $old_data = array('case_no' => $reg_no_old, 'case_year' => $case_year, 'case_type' => $case_type);
                $new_data = json_encode($new_data);
                $old_data = json_encode($old_data);
                $st->bindParam(1, $filing_no, PDO::PARAM_STR);
                $st->bindParam(2, $old_data, PDO::PARAM_STR);
                $st->bindParam(3, $new_data, PDO::PARAM_STR);
                $st->bindParam(4, $server_date, PDO::PARAM_STR);
                $st->bindParam(5, $sessionUserType, PDO::PARAM_STR);
                $st->execute();
                try {
                    $st22222 = "update $schemas.case_detail set case_no=?,case_year=?,case_type=?, change_case=? where filing_no=?";
                    $st1111 = $db->prepare($st22222);
                    $year_case = date('Y');
                    $st1111->bindParam(1, $reg_no, PDO::PARAM_STR);
                    $st1111->bindParam(2, $year_case, PDO::PARAM_STR);
                    $st1111->bindParam(3, $case_type, PDO::PARAM_STR);
                    $st1111->bindParam(4, $change_cases, PDO::PARAM_STR);
                    $st1111->bindParam(5, $filing_no, PDO::PARAM_STR);
                    $st1111->execute();

                    $db->commit();
                    echo 'Sucessfully Update';
                } catch (PDOException $ex) {
                    $db->rollBack();
                    echo $ex;
                }
            } catch (PDOException $ex) {
                $db->rollBack();
                echo $ex;
            }
        } else {
            echo 'Not Data Exit. Please Try again';
        }

        die;
    } else if ($_POST['action'] == 'select_judge_list') {
        $schemas = $_SESSION['schema_name'];
        $to_be_listed = $_POST['to_be_listed'];
        if ($_POST['status'] == 'yes') {
        ?>
<div class="col-sm-4">
    <label>Coram 1</label>
    <select class="form-control required" name="judge_name_<?php echo $to_be_listed; ?>[]"
        id="judge_name_id_1<?php echo $to_be_listed; ?>">
        <option value="">-select-</option>
        <?php
                    $display = 'TRUE';
                    $sqlf = $db->prepare("select * from $schemas.master_judge where display=?  order by seniority asc");
                    $sqlf->bindParam(1, $display, PDO::PARAM_STR);
                    $sqlf->execute();
                    $datata = $sqlf->fetchAll();
                    foreach ($datata as $key => $row) {
                        $judge_desg_code = $row['judge_desg_code'];
                        $des_ql = $db->prepare("select desg_name from $schemas.master_desg where desg_code=? order by desg_code desc");
                        $des_ql->execute(array($judge_desg_code));
                        $desgsthname = $des_ql->fetchColumn();
                    ?>
        <option value="<?php echo htmlspecialchars($row['judge_code']); ?>">
            <?php echo $row['hon_text'] . ' ' . htmlspecialchars($row['judge_name']) . ' (' . $desgsthname . ')'; ?>
        </option>
        <?php
                    }
                    ?>
    </select>
</div>


<div class="col-sm-4">
    <label>Coram 2</label>
    <select class="form-control required" name="judge_name_<?php echo $to_be_listed; ?>[]"
        id="judge_name_id_1<?php echo $to_be_listed; ?>">
        <option value="">-select-</option>
        <?php
                    $display = 'TRUE';
                    $sqlf = $db->prepare("select * from $schemas.master_judge where display=?  order by seniority asc");
                    $sqlf->bindParam(1, $display, PDO::PARAM_STR);
                    $sqlf->execute();
                    $datata = $sqlf->fetchAll();
                    foreach ($datata as $key => $row) {
                        $judge_desg_code = $row['judge_desg_code'];
                        $des_ql = $db->prepare("select desg_name from $schemas.master_desg where desg_code=? order by desg_code desc");
                        $des_ql->execute(array($judge_desg_code));
                        $desgsthname = $des_ql->fetchColumn();
                    ?>
        <option value="<?php echo htmlspecialchars($row['judge_code']); ?>">
            <?php echo $row['hon_text'] . ' ' . htmlspecialchars($row['judge_name']) . ' (' . $desgsthname . ')'; ?>
        </option>
        <?php } ?>
    </select>

</div>
<?php
        }
    } else if ($_POST['action'] == 'save_case_selected_listed') {

        //print_r($_REQUEST);
        $schemas = $_SESSION['schema_name'];
        $sessionUserType = htmlspecialchars($_SESSION['id']);

        $filing_no = $_POST['filing_no'];
        $listing_date = $_POST['from_list_date'];
        $remarks = $_POST['remarks'];
        $not_to_be_listed = $_POST['not_to_be_listed'];
        $to_be_listed = $_POST['to_be_listed'];
        $not_to_be_listed_judge = implode(',', $_POST['not_to_be_listed_judge']);
        $to_be_listed_judge = implode(',', $_POST['to_be_listed_judge']);
        $created_on = date('Y-m-d H:i:s');

        list($d, $m, $Y) = explode('/', $listing_date);
        $listing_date = $Y . '-' . $m . '-' . $d;
        try {
            $selected_list = "select count(id) from $schemas.case_selected_list where filing_no =? and listing_date = ? ";
            $selected_list = $db->prepare($selected_list);
            $selected_list->bindParam(1, $filing_no, PDO::PARAM_STR);
            $selected_list->bindParam(2, $listing_date, PDO::PARAM_STR);
            $selected_list->execute();
            $selected_listed = $selected_list->fetchColumn();
            if ($selected_listed <= '0') {
                $insert = "insert into $schemas.case_selected_list(filing_no, to_be_listed_judge, not_to_be_listed_judge,  to_be_listed, not_to_be_listed, created_by, created_on, remarks, ip, listing_date)
                     values('$filing_no','$to_be_listed_judge','$not_to_be_listed_judge','$to_be_listed','$not_to_be_listed','$sessionUserType','$created_on','$remarks','121.0.01','$listing_date')";
                try {
                    $query_insert = $db->prepare($insert);
                    $query_insert->execute();
                    echo $msg = "Case Selected successfully";
                } catch (PDOException $ex) {
                    echo $ex;
                }
            } else {
                $update = "update $schemas.case_selected_list set  to_be_listed_judge = '$to_be_listed_judge',
                not_to_be_listed_judge = '$not_to_be_listed_judge',  to_be_listed = '$to_be_listed', not_to_be_listed = '$not_to_be_listed',
                modified_by = '$sessionUserType', modified_on = '$created_on', remarks = '$remarks', ip = '121.0.01' where filing_no = '$filing_no' and  listing_date = '$listing_date' ";
                try {
                    $query_update = $db->prepare($update);
                    $query_update->execute();
                    echo $msg = " CASE Selected successfully";
                } catch (PDOException $ex) {
                    echo $ex;
                }
            }
        } catch (PDOException $ex) {
            echo $ex;
        }
        die;
    } else if ($_REQUEST['action'] == 'upload_order') {
        $filing_no = $_REQUEST['filing_no'];
        $order_id = $_REQUEST['order_id'];
        $order_date = $_REQUEST['order_date'];
        $court_no = $_REQUEST['court_no'];
        $order_type = $_REQUEST['order_type'];
        $dsc_radion = $_REQUEST['dsc_radion'];
        $schemas = $_SESSION['schema_name'];
        $type = $_REQUEST['type'];
        $modified_by = $_SESSION['id'];
        $date_of_order = date('Y-m-d');
        $db->beginTransaction();
        try {
        $order_query = "select filing_no,order_upload_date,order_date,order_type,pdf_path,court_no from $schemas.order_daily where item_no = ?";
        $order_query_ex = $db->prepare($order_query);
        $order_query_ex->bindParam(1, $order_id, PDO::PARAM_STR);
        $order_query_ex->execute();
        $order_data = $order_query_ex->fetch();

        if($type == '3'){
            $flag = 'Y';
            $send_to_steno = 2;
            $query = "select disposal_nature from $schemas.case_disposal where filing_no = ? order by id desc limit 1";
                            $disposal_detail = $db->prepare($query);
                            $disposal_detail->bindParam(1,$filing_no,PDO::PARAM_STR);
                            $disposal_detail->execute();
                            $dis_nature = $disposal_detail->fetchColumn();

                            if($dis_nature == '46'){

                                $disposal_date = date('Y-m-d');

                                $query_dis = "update $schemas.case_disposal set disposal_date = ? where filing_no = ?";
                                $disposal_query = $db->prepare($query_dis);
                                $disposal_query->bindParam(1, $disposal_date, PDO::PARAM_STR);
                                $disposal_query->bindParam(2, $filing_no, PDO::PARAM_STR);
                                $disposal_query->execute();

                                $query_status = "update $schemas.case_detail set status = 'D' where filing_no = ?";
                                $case_status = $db->prepare($query_status);
                                $case_status->bindParam(1, $filing_no, PDO::PARAM_STR);
                                $case_status->execute();
                            

                                $query_order = "update $schemas.order_daily set modified_by = ?, flag = ?, send_by_member = ?, order_date = ?,  order_upload_date = now() where item_no = ?";
            }else{
                $query_order = "update $schemas.order_daily set modified_by = ?, flag = ?, send_by_member = ? , order_upload_date=now() where item_no = ?";
            }
            $update_order = $db->prepare($query_order);
                            $update_order->bindParam(1, $modified_by, PDO::PARAM_STR);
                            $update_order->bindParam(2, $flag, PDO::PARAM_STR);
                            $update_order->bindParam(3, $send_to_steno, PDO::PARAM_STR); $sn = 4;
                            if($dis_nature == '46'){
                                $update_order->bindParam($sn, $date_of_order, PDO::PARAM_STR); $sn++;
                            }
                             $update_order->bindParam($sn, $order_id, PDO::PARAM_STR);
                            $update_order->execute();

                                $case_de = "select a.case_no,a.case_year,a.pet_name,a.res_name,ct.short_name as case_type_name, mlc.short_name from $schemas.case_detail as a left join case_type as ct on ct.id = a.case_type left join mater_location_city as mlc on mlc.city_id = a.location_code where filing_no = ?";
                                $case_de = $db->prepare($case_de);
                                $case_de->bindParam(1, $filing_no, PDO::PARAM_STR);
                                $case_de->execute();
                                $filing_detail = $case_de->fetch();
                                $datata = date('d-m-Y', strtotime($date_new));
                                $case_num = $filing_detail['case_type_name'].'/'.$filing_detail['case_no'] . '/' .$filing_detail['short_name'].'/'. $filing_detail['case_year'];
                                $pet_name = $filing_detail['pet_name'];
                                $res_name = $filing_detail['res_name'];
                                $subject = "Copy of order :" . $order_date . " in the case " . $case_num;
                                
                                $order_type = ($order_data['order_type']=='F')?"Final Order":"Daily Order";
                                $order_path = $order_data['pdf_path'];
                                $order_date = $order_data['order_date'];
                                $court_no = $order_data['court_no'];
                                $bench_name = $_SESSION['bench_name'];
                                $msg_bench_name = "Court No ".$court_no.", GSTAT, ".$bench_name;

                                $msg = $email_text = "The ".$order_type." pronounced on ".$order_date." in ".$case_num." by ".$msg_bench_name." is attached for your reference. The order has been uploaded into the GSTAT portal as well. This is a computer-generated message, please do not reply. GSTAT-GSTN";

                                //$msg = "The ".$order_type". pronounced on ".$order_date." in ".$case_num." by ".$msg_bench_name."is available on registered email or GSTAT portal. Pease login to GSTAT portal to view the same. This is a computer-generated message, please do not reply. "

                                fn_sms($db, '18', $order_path, $filing_no, $subject, $msg, $email_text,$order_path);
                    $db->commit();
				
				$url = "https://efiling.gstat.gov.in/getdata04apl.drt?filingNo=$filing_no";
				callApiAsync($url);

                                echo 'sucessfully Order Upload.';
                        die;
                            
        }


        $valid_extensions = array('pdf');
        $path = 'Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/orders/'.$filing_no.'/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        if (!empty($_FILES['file'])) {
            $img = $_FILES['file']['name'];
            $tmp = $_FILES['file']['tmp_name'];
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            if ($dsc_radion == 'with_dsc') {
                $ext = 'pdf';
            }
            $count = explode(".", $img);
            if (count($count) < 3) {
                $final_image = rand(1000, 1000000) . $img;
                if (in_array($ext, $valid_extensions)) {
                    $save_path = $path . strtolower($final_image);
                    if ($s3Service->uploadFile($tmp, $save_path)) {
                        $path11 = $path;
                        $save_path = $path11 . strtolower($final_image);
                        

                        if($type == '1'){
                            $flag = 'Y';
                            $send_to_steno = 2;
                        }
                        else{
                            $flag = 'N';
                            $send_to_steno = 1;
                        }

                        
                        try {

                            $query = "select disposal_nature from $schemas.case_disposal where filing_no = ? order by id desc limit 1";
                            $disposal_detail = $db->prepare($query);
                            $disposal_detail->bindParam(1,$filing_no,PDO::PARAM_STR);
                            $disposal_detail->execute();
                            $dis_nature = $disposal_detail->fetchColumn();

                            if($dis_nature == '46' && $type == '1'){

                                $disposal_date = date('Y-m-d');

                                $query_dis = "update $schemas.case_disposal set disposal_date = ? where filing_no = ?";
                                $disposal_query = $db->prepare($query_dis);
                                $disposal_query->bindParam(1, $disposal_date, PDO::PARAM_STR);
                                $disposal_query->bindParam(2, $filing_no, PDO::PARAM_STR);
                                $disposal_query->execute();

                                $query_status = "update $schemas.case_detail set status = 'D' where filing_no = ?";
                                $case_status = $db->prepare($query_status);
                                $case_status->bindParam(1, $filing_no, PDO::PARAM_STR);
                                $case_status->execute();
                            

                                $query_order = "update $schemas.order_daily set modified_by = ?, flag = ?, pdf_path = ?, filename = ?,order_type=?, send_by_member = ?, order_date = ?,  order_upload_date = now(), pdf_hash = ? where item_no = ?";
                            }else{
                                $query_order = "update $schemas.order_daily set modified_by = ?, flag = ?, pdf_path = ?, filename = ?,order_type=?, send_by_member = ? , order_upload_date=now(), pdf_hash = ? where item_no = ?";
                            }
			    $pdf_hash = hash_file('md5', $save_path);
                            $update_order = $db->prepare($query_order);
                            $update_order->bindParam(1, $modified_by, PDO::PARAM_STR);
                            $update_order->bindParam(2, $flag, PDO::PARAM_STR);
                            $update_order->bindParam(3, $save_path, PDO::PARAM_STR);
                            $update_order->bindParam(4, $final_image, PDO::PARAM_STR);
                            $update_order->bindParam(5, $order_type, PDO::PARAM_STR); 
                            $update_order->bindParam(6, $send_to_steno, PDO::PARAM_STR); $sn = 7;
                            if($dis_nature == '46'){
                                $update_order->bindParam($sn, $date_of_order, PDO::PARAM_STR); $sn++;
                            }
                            $update_order->bindParam($sn, $pdf_hash, PDO::PARAM_STR); $sn++;
			    $update_order->bindParam($sn, $order_id, PDO::PARAM_STR);
                            $update_order->execute();

                            if($type == '1'){
                                $case_de = "select a.case_no,a.case_year,a.pet_name,a.res_name,ct.short_name as case_type_name, mlc.short_name from $schemas.case_detail as a left join case_type as ct on ct.id = a.case_type left join mater_location_city as mlc on mlc.city_id = a.location_code where filing_no = ?";
                                $case_de = $db->prepare($case_de);
                                $case_de->bindParam(1, $filing_no, PDO::PARAM_STR);
                                $case_de->execute();
                                $filing_detail = $case_de->fetch();
                                $datata = date('d-m-Y', strtotime($date_new));
                                $case_num = $filing_detail['case_type_name'].'/'.$filing_detail['case_no'] . '/' .$filing_detail['short_name'].'/'. $filing_detail['case_year'];
                                $pet_name = $filing_detail['pet_name'];
                                $res_name = $filing_detail['res_name'];

                                $subject = "Copy of order :" . $order_date . " in the case " . $case_num;
                                $order_type = ($order_type=='F')?"Final Order":"Daily Order";
                                $order_path = $save_path;
                                $order_date = $order_data['order_date'];
                                $court_no = $order_data['court_no'];
                                $bench_name = $_SESSION['bench_name'];
                                $msg_bench_name = "Court No ".$court_no.", GSTAT, ".$bench_name;

                                $msg = $email_text = "The ".$order_type." pronounced on ".$order_date." in ".$case_num." by ".$msg_bench_name." is attached for your reference. The order has been uploaded into the GSTAT portal as well. This is a computer-generated message, please do not reply. GSTAT-GSTN";


                                //$msg = "The ".$order_type". pronounced on ".$order_date." in ".$case_num." by ".$msg_bench_name."is available on registered email or GSTAT portal. Pease login to GSTAT portal to view the same. This is a computer-generated message, please do not reply. "
                            }



                            fn_sms($db, '18', $order_path, $filing_no, $subject, $msg, $email_text,$order_path);
                            

                            try {
        //                         $parser = new \Smalot\PdfParser\Parser();
        //                         $pdf = $parser->parseFile($save_path);
        //                         $order_tribunal = $pdf->getText();
        //                         $update_order = $db->prepare("update $schemas.order_daily set order_tribunal = ? where item_no = ?");
        //                         $update_order->bindParam(1, $order_tribunal, PDO::PARAM_STR);
								// $update_order->bindParam(2, $order_id, PDO::PARAM_STR);
        //                         $update_order->execute();
                            } catch (PDOException $ex) {
                                $db->rollBack();
                                echo $ex;
                            }
                        } catch (PDOException $ex) {
                            $db->rollBack();
                            echo $ex;
                            $entry_date = date('Y-m-d H:i:s');
                        }
                        $db->commit();
                        if($type == '1'){
				$url = "https://efiling.gstat.gov.in/getdata04apl.drt?filingNo=$filing_no";
				callApiAsync($url);
				echo 'sucessfully Order Upload.';
			}
            else{
                echo 'Order Upload and sent to steno to publish';
            }

                        die;
                    } else {
                        echo 'Something Error. Please try again.';
                    }
                } else {
                    echo 'invalid Document. Only upload PDF file.';
                }
            } else {
                echo 'invalid Document. Only upload PDF file..';
            }
        }
        } catch (Exception $e) {
        $db->rollBack();
        echo $e;
        echo "Transaction failed: " . $e->getMessage();
        die;
        }
    } else if ($_REQUEST['action'] == 'get_party_by_filing_no') {

        $filing_no = $_REQUEST['filing_no'];
        $party_type = $_REQUEST['party_type'];
        $case_de = "SELECT * FROM public.e_cases_party where filing_no = '$filing_no' and party_flag = '$party_type' ";
        $case_de = $db->prepare($case_de);
        $case_de->execute();
        $filing_detail = $case_de->fetchAll();
        echo '<option value="">Select Party</option>';
        if (!empty($filing_detail) && is_array($filing_detail)) {
            foreach ($filing_detail as $val) {
                echo "<option value='" . $val['id'] . "'>" . $val['name'] . "</option>";
            }
        }
    } else if ($_REQUEST['action'] == 'disposed_appliaction') {
        $schemas = $_SESSION['schema_name'];
        $filing_no = $_POST['filing_no'];
        $case_no = $_POST['case_no'];
        $case_type = $_POST['case_type'];
        $case_year = $_POST['case_year'];
        $disposal_date = $_POST['disposal_date'];
        $disposal_nature = $_POST['disposal_nature'];
        $remarks_update = $_POST['remarks_update'];
        $user_id = $_SESSION['id'];
        $curdate = date('Y-m-d');
        $statusx = 'D';
        $db->beginTransaction();
        try {
            $st = "insert into $schemas.case_disposal (filing_no,disposal_date,disposal_nature,case_type,case_no,case_year,remarks,entry_date,user_id) VALUES(?,?,?,?,?,?,?,?,?)";
            $st = $db->prepare($st);
            $st->bindParam(1, $filing_no, PDO::PARAM_STR);
            $st->bindParam(2, $disposal_date, PDO::PARAM_STR);
            $st->bindParam(3, $disposal_nature, PDO::PARAM_STR);
            $st->bindParam(4, $case_type, PDO::PARAM_STR);
            $st->bindParam(5, $case_no, PDO::PARAM_STR);
            $st->bindParam(6, $case_year, PDO::PARAM_STR);
            $st->bindParam(7, $remarks_update, PDO::PARAM_STR);
            $st->bindParam(8, $curdate, PDO::PARAM_STR);
            $st->bindParam(9, $user_id, PDO::PARAM_INT);
            if ($st->execute()) {
                $std = "update $schemas.case_detail set status =? where filing_no=?";
                $std = $db->prepare($std);
                $std->bindParam(1, $statusx, PDO::PARAM_STR);
                $std->bindParam(2, $filing_no, PDO::PARAM_STR);
                $std->execute();
                $msg = "Case is disposed successfully";
                $db->commit();
                $msghash1 = $msg . '-' . $case_type . '-' . $case_year;
                $msghash = base64_encode($msghash1);
                echo 'CASE IS DISPOSED SUCCESSFULLY';
            }
        } catch (PDOException $ex) {
            echo $ex;
            $db->rollBack();
        }
    } else if ($_REQUEST['action'] == 'delete_order_datewise') {
        $schemas = $_SESSION['schema_name'];
        $filing_no = $_POST['filing_no'];
        $order_date = $_POST['order_date'];
        $remarks_update = $_POST['remarks_update'];
        $user_id = $_SESSION['id'];
        try {
            $case_order = "SELECT filing_no,registry_data FROM $schemas.order_daily where filing_no = '$filing_no' and order_date = '$order_date' and flag = 'Y' ";
            $case_order = $db->prepare($case_order);
            $case_order->execute();
            $filing_detail = $case_order->fetch();
            if (!empty($filing_detail['filing_no']) && $filing_detail['filing_no'] != '') {
                $remarks_update12 = $remarks_update . ' Dleted ON : ' . date('Y-m-d H:i:s') . ' Delete By ' . $user_id . '  N  ' . $filing_detail['registry_data'];
                $case_order_update = "update $schemas.order_daily set flag = 'N',registry_data = '$remarks_update12' where filing_no = '$filing_no' and order_date = '$order_date' and flag = 'Y' ";
                $case_order_update = $db->prepare($case_order_update);
                $case_order_update->execute();
                echo 'Order Deleted. ';
            } else {
                echo 'Order Not Exits. Try again.';
            }
        } catch (PDOException $ex) {
            echo $ex;
        }
    } else if ($_REQUEST['action'] == 'showing_data') {

        $filing_no = $_POST['filing_no'];
        $party_value = $_POST['party_value'];
        $party_flag = $_POST['party_flag'];

        //  print_r($_POST);

        $case_de_select = "SELECT b.id FROM e_more_representative as a
        inner join e_master_advocate as b on a.rep_code = b.id
        inner join e_cases_party as c on a.party_code = c.id
        where a.filing_no = '$filing_no' and b.rep_name !=''  and a.display = 'true' ";
        $case_de_select1 = $db->prepare($case_de_select);
        $case_de_select1->execute();
        $filing_detail_select = $case_de_select1->fetchAll();
        $adv_code = '';
        if (!empty($filing_detail_select) && is_array($filing_detail_select)) {
            foreach ($filing_detail_select as $val_adv) {
                $adv_code .= $val_adv['id'] . ',';
            }
        }


        $adv_code = rtrim($adv_code, ',');
        $adv_data_in = '';
        if ($adv_code != '') {
            $adv_data_in = " id not in($adv_code) and ";
        }




        $case_de_select_in = "SELECT b.id FROM e_more_representative as a
        inner join e_master_advocate as b on a.rep_code = b.id
        inner join e_cases_party as c on a.party_code = c.id
        where a.filing_no = '$filing_no' and b.rep_name !='' and c.id = '$party_value' and a.party_flag = '$party_flag' and a.display = 'true' ";
        $case_de_select_in = $db->prepare($case_de_select_in);
        $case_de_select_in->execute();
        $filing_detail_select_in = $case_de_select_in->fetchAll();
        $adv_data_not_in = '';
        if (!empty($filing_detail_select_in) && is_array($filing_detail_select_in)) {
            foreach ($filing_detail_select_in as $val_adv) {
                $adv_data_not_in .= $val_adv['id'] . ',';
            }
        }
        $adv_data_not_in = rtrim($adv_data_not_in, ',');
        $adv_data_not_in_in = '';
        if ($adv_data_not_in != '') {
            $adv_data_not_in_in = " id in($adv_data_not_in) and ";
        }





        $case_de = "SELECT LTRIM(rep_name,' ') AS rep_nameaa,rep_name,id,bar_council_number FROM e_master_advocate where  $adv_data_in rep_name != '' and  id not in(41922,207441,90104,12507,8097) and signupid is not null and display = 'true' order by rep_nameaa asc  ";
        $case_de = $db->prepare($case_de);
        $case_de->execute();
        $filing_detail = $case_de->fetchAll();

        $case_de12 = "SELECT LTRIM(rep_name,' ') AS rep_nameaa,rep_name,id,bar_council_number FROM e_master_advocate where $adv_data_not_in_in rep_name != '' and id not in(41922,207441,90104,12507,8097) and signupid is not null and display = 'true' order by rep_nameaa asc ";
        $case_de12 = $db->prepare($case_de12);
        $case_de12->execute();
        $filing_detail123 = $case_de12->fetchAll();

        ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <input type="radio" checked onclick="fn_change_adv('A')" name="select_change_type" id="select_change_type1"
                value="1">
            <label> Add Advocate</label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" onclick="fn_change_adv('U')" name="select_change_type" id="select_change_type2"
                value="2">
            <label> Update Advocate</label>
            &nbsp;
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-sm-6 add_advocate_list_div">
            <label> Select Advocate :</label>
            <select class="form-control required" id="add_advocate_list" name="add_advocate_list">
                <option value="">Select Advocate</option>
                <?php if (!empty($filing_detail) && is_array($filing_detail)) {
                            foreach ($filing_detail as $val) {
                                $pet_name = ltrim($val['rep_name'], ' ');
                                if ($pet_name != '') {
                        ?>
                <option value="<?php echo $val['id']; ?>">
                    <?php echo $val['rep_name']; ?>(<?php echo $val['bar_council_number']; ?>)</option>
                <?php }
                            }
                        } ?>
            </select>
        </div>

        <div class="col-sm-6 update_advocate_list_div" style="display:none">
            <label> Select Advocate :</label>
            <select class="adv-multi-select form-control required" id="updateadd_advocate_list"
                name="updateadd_advocate_list" multiple>

                <?php if (!empty($filing_detail123) && is_array($filing_detail123)) {
                            foreach ($filing_detail123 as $val) {
                                $pet_name = ltrim($val['rep_name'], ' ');
                                if ($pet_name != '') {
                        ?>
                <option value="<?php echo $val['id']; ?>">
                    <?php echo $val['rep_name']; ?>(<?php echo $val['bar_council_number']; ?>)</option>
                <?php }
                            }
                        } ?>
            </select>
        </div>
        <div class="col-sm-6 update_advocate_list_div" id="update_advocate_list_div" style="display:none">
            <label> Select Change Advocate :</label>
            <select class="form-control required" id="update_advocate_list" name="update_advocate_list">
                <option value="">Change Advocate</option>
                <?php if (!empty($filing_detail) && is_array($filing_detail)) {
                            foreach ($filing_detail as $val) {
                                $pet_name = ltrim($val['rep_name'], ' ');
                                if ($pet_name != '') {  ?>
                <option value="<?php echo $val['id']; ?>">
                    <?php echo $val['rep_name']; ?>(<?php echo $val['bar_council_number']; ?>)</option>
                <?php }
                            }
                        } ?>
            </select>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label> Remarks :</label>
            <textarea name="remarks_changes" id="remarks_changes" required="required" class="form-control"></textarea>
        </div>
    </div>
</div>
<?php
    } else if ($_REQUEST['action'] == 'update_add_advocte') {
        $select_party_type = $_POST['select_party_type'];
        $select_change_type = $_POST['select_change_type'];
        $filing_no = $_POST['filing_no'];
        $select_party_option = $_POST['select_party_option'];
        $add_advocate_list = $_POST['add_advocate_list'];
        $updateadd_advocate_list = $_POST['updateadd_advocate_list'];
        $update_advocate_list = $_POST['update_advocate_list'];
        $remarks_changes = $_POST['remarks_changes'];

        $update_advocate_list = $_POST['update_advocate_list'];

        $party_flag = 'R';
        if ($select_party_type == '1') {
            $party_flag = 'P';
        }

        if ($select_change_type == '1') {
            try {
                $case_de = "SELECT e_reference_no FROM public.e_case_detail where filing_no = '$filing_no' ";
                $case_de = $db->prepare($case_de);
                $case_de->execute();
                $e_reference_no = $case_de->fetchColumn();
                $user_id = $_SESSION['id'];
                $modify_date = date('Y-m-d');
                if ($e_reference_no != '') {
                    $max_id = "SELECT max(id) as max_id FROM public.e_more_representative ";
                    $max_id = $db->prepare($max_id);
                    $max_id->execute();
                    $max_id = $max_id->fetchColumn();
                    $max_id = $max_id + 1;
                    $party_flag = 'R';
                    if ($select_party_type == '1') {
                        $party_flag = 'P';
                    }
                    $insert_query = "INSERT INTO public.e_more_representative(id,filing_no, e_reference_no, party_flag, party_serial_no, rep_code, display, user_id, modify_date, party_code,remarks)
                    VALUES ('$max_id','$filing_no', '$e_reference_no', '$party_flag', '1', '$add_advocate_list', 'true', '$user_id', '$modify_date', '$select_party_option','$remarks_changes')";
                    $insert_query = $db->prepare($insert_query);
                    $insert_query->execute();
                    echo 'Successfully Add Advocate';
                }
            } catch (PDOException $ex) {
                echo $ex;
            }
        } else if ($select_change_type == '2') {
            try {

                if (!empty($updateadd_advocate_list) && is_array($updateadd_advocate_list)) {
                    $count = 0;

                    $insert_data = false;
                    foreach ($updateadd_advocate_list as $val_id) {
                        $display = '';
                        if ($count > 0) {
                            $display = ",display='false'";
                        }
                        $max_id = "SELECT count(id) as max_id FROM public.e_more_representative where filing_no = '$filing_no' and rep_code = '$val_id' ";
                        $max_id = $db->prepare($max_id);
                        $max_id->execute();
                        $max_id = $max_id->fetchColumn();
                        if ($max_id > 0) {
                            $update_query = "update public.e_more_representative set rep_code = '$update_advocate_list', remarks = '$remarks_changes' $display where filing_no = '$filing_no' and rep_code = '$val_id'  ";
                            $update_query = $db->prepare($update_query);
                            $update_query->execute();
                            $count++;
                            $insert_data = true;
                        } else {
                            echo 'Not assigned this advocate, Please try again';
                        }
                    }

                    if ($insert_data) {
                        echo 'Successfully Update Advocate';
                    } else {
                        echo 'Somthing went wrong';
                    }
                } else {
                    echo 'please select Advocate.';
                }
            } catch (PDOException $ex) {
                echo $ex;
            }
        }
    }
}

?>

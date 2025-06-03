<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php";
include "../db_inc2.php";
//include "../custom//custom_function.php";
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
$schema = $_SESSION['schema_name'];

function fn_getCaseNo($db, $schemas, $filing_no) {
    try {
        $get_data = $db->prepare("select c.case_type_desc,b.short_name,a.case_year,a.case_no from $schemas.case_detail as a
        join $schemas.bench_location as b ON b.bench_location_code = a.location_code
        join case_type as c ON c.id = a.case_type
        where a.filing_no = ? order by a.dt_of_filing asc");
        $get_data->bindParam(1, $filing_no, PDO::PARAM_STR);
        $get_data->execute();
        $case_data = $get_data->fetch();
        $case_number = '';
        if ($case_data['case_no'] != '') {
            $case_number = $case_data['case_type_desc'] . "/" . $case_data['case_no'] . "(" . $case_data['short_name'] . ")" . $case_data['case_year'];
        }
        return $case_number;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function year_list($year) {
    for ($i = 2019; $i >= 1970; $i--) {?>
<option <?php if ($year == $i) {
        echo 'selected';
    }?> value="<?php echo $i; ?>"><?php echo $i ?></option>
<?php }
}
function fn_summon_notice_status($schemas, $db, $filing_no) {
    $summon_notice_status = 'summon_notice_status + 1';
    $sql1 = $db->prepare("update $schemas.case_detail set summon_notice_status = summon_notice_status + 1 where filing_no = ?");
    $sql1->bindParam(1, $filing_no, PDO::PARAM_INT);
    return $sql1->execute();
}

function case_type($db) {
    $data_main = array();
    $st = $db->prepare("select * from case_type where display='TRUE' order by id desc");
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

function get_data($db, $table_name, $where = array(), $where_like = array(), $column = '*', $order_by = null, $order_column = null, $limit = null) {
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

$case_type_data = case_type($db);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    if ($_POST['action'] == 'select_type') {
        if ($_POST['type_id'] == '1') {
            ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label>Enter Diary No :</label>
            <input type="text" name="dairy_no" id="dairy_no" required="required" class="form-control required">
        </div>
        <div class="col-sm-4" style="margin-top: 25PX;">
            <label>&nbsp;</label>
            <button type="button" onclick="fn_search_by_case_dfr()" class="btn btn-primary">Search</button>
        </div>
    </div>
</div>
<?php } else if ($_POST['type_id'] == '2') {?>
<div class="form-group">
    <div class="row">
        <!-- <div class="col-sm-3">
            <label>Select Case Type :</label>
            <select required="required" class="form-control" name="case_type" id="case_type">
                <option value="">Select Case Type</option>
                <?php if (!empty($case_type_data)) {
            foreach ($case_type_data as $val) {
                echo '<option value="' . $val['id'] . '">' . $val['case_type_desc'] . '(' . $val['short_name'] . ')' . '</option>';
            }
        }?>
            </select>
        </div> -->
        <div class="col-sm-3">
            <label>Enter Case No.</label>
            <input type="text" name="case_no" id="case_no" class="form-control required">
        </div>
        <div class="col-sm-3">
            <label>Select Case Year</label>
            <select class="form-control required" name="case_year" id="case_year">
                <option value="">Select Year</option>
                <?php echo year_list(date('Y')); ?>
            </select>
        </div>
        <div class="col-sm-3" style="margin-top: 25PX;">
            <label>&nbsp;</label>
            <button type="button" onclick="fn_search_by_case_dfr()" class="btn btn-primary">Search</button>
        </div>
    </div>
</div>
<?php }
    } else if ($_POST['action'] == 'search_filing') {
        $schema = $_SESSION['schema_name'];

        $schemas = $_SESSION['schema_name'];

        if ($_POST['search_by'] == '1') {
            $filing_no = $_POST['dairy_no'] . $_POST['dairy_year'];
            //'location_code' => $_SESSION['location'],
            $where = array('filing_no' => $filing_no);
            $where_likes = array();
        } else if ($_POST['search_by'] == '2') {
            //'case_type' => $_POST['case_type'], 'location_code' => $_SESSION['location'],
            $where = array('case_no' => $_POST['case_no'], 'case_year' => $_POST['case_year']);
            $where_likes = array();
        }
        $data = get_data($db, $schema . '.case_detail', $where, $where_likes, '*', 'DESC', 'filing_no');

        ?>

<div class="col-md-12">
    <div class="col-md-6">
        <div class="row add_panel">
            <h2 id="message_results"></h2>
            <div class="clearfix"></div>
        </div>
    </div>
</div>


<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead>
        <tr role="row">
            <th>S.No</th>
            <th>filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>
            <th>Listing Details</th>
            <th>Date Of Filing</th>
            <th>Date Of Registration</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data) && is_array($data)) {
            $ii = 1;
            foreach ($data as $value) {
                $filing_no = $value['filing_no'];
                ?>
        <tr>
            <td><?php echo $ii; ?></td>
            <td> <?php echo $filing_no; ?></td>
            <td> <?php echo fn_getCaseNo($db, $schema, $filing_no); ?></td>
            <td>
                <span
                    style="color: red;"><?php echo $value['pet_name'] . ' </span> vs <span style="color: red;">' . $value['res_name']; ?></span>
            </td>


            <td>
                <table class="table table-bordered">

                    <?php
try {
                    $query_case_all = $db->prepare("select b.purpose_name,a.filing_no,a.listing_date,a.court_no from $schemas.case_allocation as a  join $schemas.master_purpose as b ON b.purpose_code = a.purpose
            where a.filing_no = ? order by listing_date asc ");
                    $query_case_all->bindParam(1, $filing_no, PDO::PARAM_STR);
                    $query_case_all->execute();
                    $value_allocation_first = $query_case_all->fetch();
                } catch (PDOException $ex) {
                    echo $ex;
                    die;
                }
                ?>
                    <tr>
                        <th colspan="5" style="text-align: center; ">
                            <div class="modal-header-inner">
                                <h4 class="modal-title" id="case_title_status"> First Hearing Data </h4>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td> <b>Hearing&nbsp;Date </b> </td>
                        <td> <?php if ($value_allocation_proc['next_list_date'] != '') {echo date('d/m/Y', strtotime($value_allocation_first['listing_date']));}?>
                        </td>
                        <td> &nbsp; </td>
                        <td> <b>Court No. </b> </td>
                        <td> <?php echo $value_allocation_first['court_no']; ?> </td>
                    </tr>
                    <tr>
                        <td> <b>Stage&nbsp;Of&nbsp;Case </b> </td>
                        <td><?php echo $value_allocation_first['purpose_name']; ?></td>
                        <td colspan="3"> </td>
                    </tr>

                    <?php
try {
                    $query_case_all_last = $db->prepare("select b.purpose_name,a.filing_no,a.listing_date,a.court_no from $schemas.case_allocation as a  join $schemas.master_purpose as b ON b.purpose_code = a.purpose
            where a.filing_no = ? order by listing_date desc");
                    $query_case_all_last->bindParam(1, $filing_no, PDO::PARAM_STR);
                    $query_case_all_last->execute();
                    $value_allocation_last = $query_case_all_last->fetch();
                } catch (PDOException $ex) {
                    echo $ex;
                    die;
                }
                ?>
                    <tr>
                        <th colspan="5" style="text-align: center; ">
                            <div class="modal-header-inner">
                                <h4 class="modal-title" id="case_title_status"> Last Hearing Data </h4>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td> <b>Hearing&nbsp;Date </b> </td>
                        <td> <?php if ($value_allocation_proc['next_list_date'] != '') {echo date('d/m/Y', strtotime($value_allocation_last['listing_date']));}?>
                        </td>
                        <td> &nbsp; </td>
                        <td> <b>Court No. </b> </td>
                        <td> <?php echo $value_allocation_last['court_no']; ?> </td>
                    </tr>
                    <tr>
                        <td> <b>Stage&nbsp;Of&nbsp;Case </b> </td>
                        <td><?php echo $value_allocation_last['purpose_name']; ?></td>
                        <td colspan="3"> </td>
                    </tr>
                    <?php
try {
                    $query_case_all_proce = $db->prepare("select a.remarks,b.purpose_name,a.filing_no,a.next_list_date,a.court_no from $schemas.case_proceeding as a  join $schemas.master_purpose as b ON b.purpose_code = a.next_list_purpose
            where a.filing_no = ? order by listing_date desc ");
                    $query_case_all_proce->bindParam(1, $filing_no, PDO::PARAM_STR);
                    $query_case_all_proce->execute();
                    $value_allocation_proc = $query_case_all_proce->fetch();
                } catch (PDOException $ex) {
                    echo $ex;
                    die;
                }
                ?>
                    <tr>
                        <th colspan="5" style="text-align: center; ">
                            <div class="modal-header-inner">
                                <h4 class="modal-title" id="case_title_status"> Next Hearing Data </h4>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td> <b>Hearing&nbsp;Date </b> </td>
                        <td> <?php if ($value_allocation_proc['next_list_date'] != '') {echo date('d/m/Y', strtotime($value_allocation_proc['next_list_date']));}?>
                        </td>
                        <td> &nbsp; </td>
                        <td> <b>Court No. </b> </td>
                        <td> <?php echo $value_allocation_proc['court_no']; ?> </td>
                    </tr>
                    <tr>
                        <td> <b>Remarks </b> </td>
                        <td><?php echo $value_allocation_proc['remarks']; ?></td>

                        <td> </td>
                        <td> <b>Stage Of Case </b> </td>
                        <td><?php echo $value_allocation_proc['purpose_name']; ?></td>
                    </tr>


                    <tr>
                        <th colspan="5" style="text-align: center; ">
                            <div class="modal-header-inner">
                                <h4 class="modal-title" id="case_title_status"> History Case Hearing</h4>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Hearing Date</th>
                        <th>Court No</th>
                        <th>Purpose</th>
                    </tr>
                    <?php
try {
                    $query_case_all = $db->prepare("select b.purpose_name,a.filing_no,a.listing_date,a.court_no from $schemas.case_allocation as a  join $schemas.master_purpose as b ON b.purpose_code = a.purpose
            where a.filing_no = ? order by listing_date asc ");
                    $query_case_all->bindParam(1, $filing_no, PDO::PARAM_STR);
                    $query_case_all->execute();
                    $value_allocation = $query_case_all->fetchAll();
                } catch (PDOException $ex) {
                    echo $ex;
                    die;
                }
                if (!empty($value_allocation) && is_array($value_allocation)) {
                    $i = 1;
                    foreach ($value_allocation as $val) {?>
                    <tr>
                        <td> <?php echo $i; ?> </td>
                        <td><?php echo date('d/m/Y', strtotime($val['listing_date'])); ?></td>
                        <td><?php echo $val['court_no']; ?> </td>
                        <td><?php echo $val['purpose_name']; ?> </td>

                    </tr>
                    <?php  $i++;  }} else {echo '  <tr>
                        <td colspan="4">No Data  </td>

                        </tr>';}?>

                </table>

            </td>



            <td> <?php echo $value['dt_of_filing']; ?></td>
            <td> <?php echo $value['regis_date']; ?></td>
            <td>
                <a onclick="edit_case_no('<?php echo $filing_no; ?>','<?php echo $value['case_no']; ?>','<?php echo $value['case_type']; ?>','<?php echo $value['case_year']; ?>')"
                    class="model_form btn btn-primary" style="margin-bottom: 18px;">
                    <i class="fa fa-edit"></i>
                </a>

            </td>
        </tr>
        <?php $ii++;

            }
        } else {
            echo '<tr> <td colspan="8"> <p  style="font-size: large; text-align: center; color: red;"> Data not found! </p> </td> </tr>';
        }
        ?>

    </tbody>
</table>

<?php $ii++;

    } else if ($_POST['action'] == 'update_case_no_f') {
       // print_r($_REQUEST);
        $filing_no = $_REQUEST['filing_no'];
        $case_no = $_REQUEST['case_no'];
        $case_year = $_REQUEST['case_year'];
        $court_no = $_REQUEST['court_no'];
        $bench_type = $_REQUEST['bench_type'];
        $case_type = $_REQUEST['case_type'];
        $old_case_no = $_REQUEST['old_case_no'];
        $old_case_type = $_REQUEST['old_case_type'];
        $old_case_year = $_REQUEST['old_case_year'];
        $schemas = htmlspecialchars($_SESSION['schema_name']);
        $check_filing_no = "select filing_no,case_no,case_year,ia_flag from $schemas.case_detail where filing_no=?";
        $sql_query = $db->prepare($check_filing_no);
        $sql_query->bindParam(1, $filing_no, PDO::PARAM_STR);
        $sql_query->execute();
        $case_data = $sql_query->fetch();

     //   print_r($case_data);

        if ($case_data['filing_no'] != '') {

            $location_code = " and location_code='$bench_type' and manual_court_no='$court_no'";
            if ($case_year > '2019') {
                echo 'Something Error. please try again 1';
                die;
            }

            $ia_flag = $case_data['ia_flag'];
            if ($ia_flag == 1) {
                $iacasetype = '4';
                $st1 = "select * from $schemas.case_detail where case_no='$case_no'  and case_year='$case_year'  and case_type='$iacasetype' $location_code ";
            } else {
                $st1 = "select * from $schemas.case_detail where case_type='$case_type' and case_no='$case_no'  and case_year='$case_year' $location_code ";
            }
            $madate_sql = $db->prepare($st1);
            $madate_sql->execute();
            if ($madate_sql->rowCount() > 0) {
                echo 'CASE NO ALREADY EXISTS';
                die();
            }

            $db->beginTransaction();
            $case_no_update = false;
            $messgae = 'Something Wrong';
            $user_id = $_SESSION['id'];
            $manual_date = date('Y-m-d H:i:s');
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            try {
                // echo "INSERT INTO $schemas.case_manual_update(
                //     filing_no, case_no, case_year, bench_type, case_type, court_no, regis_date, created_by, created_on, ip)
                //     VALUES ('$filing_no','$case_no','$case_year','$bench_type','$case_type','$court_no',
                // '$regis_date2','$user_id','$manual_date','$ipAddress')";
                $regis_date2 = '2020-12-12';
                $insert_manual_data = $db->prepare("INSERT INTO $schemas.case_manual_update(
                    filing_no, case_no, case_year, bench_type, case_type, court_no, regis_date, created_by, created_on, ip, old_case_no, old_case_year,old_case_type,form_type)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
                $insert_manual_data->execute(array($filing_no, $case_no, $case_year, $bench_type, $case_type, $court_no,
                    $regis_date2, $user_id, $manual_date, $ipAddress, $old_case_no, $old_case_year, $old_case_type, '1'));

                $st2 = "update $schemas.case_detail set case_no=?,case_year=?,location_code=?,manual_court_no=?,manual_user_id=?,manual_date=?,case_type=? where filing_no=?";
                $st2 = $db->prepare($st2);
                $st2->bindParam(1, $case_no, PDO::PARAM_STR);
                $st2->bindParam(2, $case_year, PDO::PARAM_STR);
                $st2->bindParam(3, $bench_type, PDO::PARAM_STR);
                $st2->bindParam(4, $court_no, PDO::PARAM_STR);
                $st2->bindParam(5, $user_id, PDO::PARAM_STR);
                $st2->bindParam(6, $manual_date, PDO::PARAM_STR);
                $st2->bindParam(7, $case_type, PDO::PARAM_STR);
                $st2->bindParam(8, $filing_no, PDO::PARAM_STR);
                $st2->execute();
                $msg = "Case is Successfully Submitted!!!";
                $msghash1 = $msg;
                $msghash = base64_encode($msghash1);
                $messgae =  $msghash1;
                $case_no_update = true;
            } catch (PDOException $th) {
                $case_no_update = false;
                $messgae = $th;
            }
            echo $messgae;
            if ($case_no_update) {
                $db->commit();
            } else {
                $db->rollBack();
            }

        } else {
            echo 'This diary no is not exit on CIS';
        }

    }

}

?>
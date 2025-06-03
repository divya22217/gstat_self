<?php
 $schemas = $_REQUEST['schema_name'];
 $is_valid_schema = ctype_alpha($schemas);
 if (!$is_valid_schema && $_REQUEST['action'] != 'show_case_years') {
     echo 'something went wrong';
     die;
 }
?><style>
    .accordion {
        margin-top: 30px;
    }

    .accordion .card-header h2 {
        font-size: 17px;
    }

    .accordion .card-header h2 {
        color: #fff;
    }

    .accordion .card-header.collapsed h2 {
        color: #fff;
    }

    .accordion .card-header {
        padding-right: 40px;
        position: relative;
        cursor: pointer;
        background: #4493cc;
    }

    .accordion .card-header::after {
        content: '_';
        font-size: 30px;
        color: #fff;
        position: absolute;
        right: 15px;
        top: -14px;
    }

    .accordion .card-header.collapsed::after {
        content: '+';
        top: -1px;
        right: 13px;
    }

    .accordion .card-body {
        font-size: 15px;
        color: #6f6f6f;
    }
</style>


<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php";
include "../db_inc2.php";
include "../master/functions.php";
function fn_judge_list($db, $schemas, $from_list_date, $bench_id)
{
    $coram_name = '';
    /* $sql = "select presiding  from $schemas.bench where from_list_date ='$from_list_date' and bench_no=? ";
    $sth_judge = $db->prepare($sql);
	$sth_judge->bindParam(1, $bench_id, PDO::PARAM_STR);
    $sth_judge->execute();
    $presiding_judge = $sth_judge->fetchColumn();
	
	$sql = "select hon_text,md.desg_name,jm.judge_name,bj.judge_code,jm.judge_desg_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm
 Join
 $schemas.master_desg md ON jm.judge_desg_code = md.desg_code
 where bj.from_list_date ='$from_list_date' and bj.bench_no=? and jm.judge_code=bj.judge_code and bj.judge_code = '$presiding_judge' ";
    $sth_judge = $db->prepare($sql);
	$sth_judge->bindParam(1, $bench_id, PDO::PARAM_STR);
    $sth_judge->execute();
    $judge_data = $sth_judge->fetch();
   

		if($judge_data['judge_desg_code'] == '6'  || $judge_data['judge_desg_code'] == '7'){

        $coram_name .= "<font size='2' >" . $judge_data['hon_text'] . ' ' . $judge_data['judge_name'] . ' (' . $judge_data['desg_name'] . ')';
        $coram_name .= "<br>";
		$sql = "select hon_text,md.desg_name,jm.judge_name,bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm
 Join
 $schemas.master_desg md ON jm.judge_desg_code = md.desg_code
 where bj.from_list_date ='$from_list_date' and bj.bench_no=? and jm.judge_code=bj.judge_code and bj.judge_code != '$presiding_judge' order by jm.judge_desg_code asc";
		}else{
		$sql = "select hon_text,md.desg_name,jm.judge_name,bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm
 Join
 $schemas.master_desg md ON jm.judge_desg_code = md.desg_code
 where bj.from_list_date ='$from_list_date' and bj.bench_no=? and jm.judge_code=bj.judge_code order by jm.judge_desg_code asc";
		} */
    $coram_name_chairperson = '';
    $sql = "select hon_text,md.desg_name,jm.judge_name,bj.judge_code,jm.judge_desg_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm
 Join
 $schemas.master_desg md ON jm.judge_desg_code = md.desg_code
 where bj.from_list_date ='$from_list_date' and bj.bench_no=? and jm.judge_code=bj.judge_code order by jm.judge_desg_code asc";
    $sth_judge = $db->prepare($sql);
    $sth_judge->bindParam(1, $bench_id, PDO::PARAM_STR);
    $sth_judge->execute();
    $judge_data = $sth_judge->fetchAll();
    //$coram_name = '';
    foreach ($judge_data as $value) {
        if ($value['judge_desg_code'] == '1' || $value['judge_desg_code'] == '6'  || $value['judge_desg_code'] == '7' || $value['judge_desg_code'] == '8') {
            $coram_name_chairperson .= "<font size='2' >" . $value['hon_text'] . ' ' . $value['judge_name'] . ' (' . $value['desg_name'] . ')';
            $coram_name_chairperson .= "<br>";
        } else {
            $coram_name .= "<font size='2' >" . $value['hon_text'] . ' ' . $value['judge_name'] . ' (' . $value['desg_name'] . ')';
            $coram_name .= "<br>";
        }
    }
    $final_coram = $coram_name_chairperson . $coram_name;
    return $final_coram;
}


function get_presidind_id($schemas, $db, $listdate_entire, $bench_id)
{
    $sql2 = "select from_time, presiding,court_no, bench_nature from $schemas.bench where  from_list_date =? and id = ?  order by court_no asc";
    $bench_d = $db->prepare($sql2);
    $bench_d->bindParam(1, $listdate_entire, PDO::PARAM_STR);
    $bench_d->bindParam(2, $bench_id, PDO::PARAM_STR);
    $bench_d->execute();
    $bench_data = $bench_d->fetch();
    $presiding = $bench_data['presiding'];
    return $presiding;
}
function year_last_three($db, $schema)
{
    $data = array();
    $case_detail_year = $db->prepare("select DISTINCT(case_year) from $schema.case_detail order by case_year DESC");
    $case_detail_year->execute();
    $i = 0;
    while ($row = $case_detail_year->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $data[$i] = $row['case_year'];
        $i++;
    }
    return $data;
}
function fn_getChild($db, $schemas, $filing_no)
{
    $mainCno = "select a.filing_no,a.case_no,a.case_year,b.short_name,cast(a.case_no as int) as case_nooo from $schemas.case_detail as a inner join case_type as b on a.case_type=b.id where a.main_case_ia_no= ? order by case_nooo,a.case_year asc";
    $mainCrs = $db->prepare($mainCno);
    $mainCrs->bindParam(1, $filing_no, PDO::PARAM_STR);
    $mainCrs->execute();
    $data_ia_main = $mainCrs->fetchAll();
    return $data_ia_main;
    //$case_no = '<br> IN <br> '.$data_ia_main['short_name'] . '/' . $data_ia_main['case_no'] . '/' . $data_ia_main['case_year'];
}

function case_type($db)
{
    $status = 't';
    $data_main = array();
    $st = $db->prepare("select * from case_type where status = ? order by id ASC");
    $st->bindParam(1, $status, PDO::PARAM_STR);
    $st->execute();
    $i = 0;
    while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $tmp = array();
        if ($row['status'] == 't') {
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

function case_details($db, $schema, $case_status, $case_year)
{
    $case_status111 = 'status = ' . "'$case_status'" . ' and ';
    if ($case_status == 'All') {
        $case_status111 = '';
    }
    $case_detail_year_co = $db->prepare("select count(filing_no) as total_case_no from $schema.case_detail where $case_status111 case_year =  '" . $case_year . "'");
    $case_detail_year_co->execute();
    $total_case = $case_detail_year_co->fetchColumn();
    return $total_case;
}
function case_details_view($db, $schema, $case_status, $case_year, $case_type)
{
    $case_status111 = 'status = ' . "'$case_status'" . ' and ';
    if ($case_status == 'All') {
        $case_status111 = '';
    }
    $case_detail_year_co = $db->prepare("select cast(case_no as int) as case_non,* from $schema.case_detail where $case_status111 case_year =  '" . $case_year . "' and case_type = '" . $case_type . "' order by case_non asc");
    $case_detail_year_co->execute();
    $total_case = $case_detail_year_co->fetchAll();
    return $total_case;
}
function case_type_details($db, $schema, $case_status, $case_year, $case_type)
{
    $case_status111 = 'status = ' . "'$case_status'" . ' and ';
    if ($case_status == 'All') {
        $case_status111 = '';
    }
    $case_detail_year_co = $db->prepare("select count(filing_no) as total_case_no from $schema.case_detail where $case_status111 case_year =  '" . $case_year . "' and case_type = '" . $case_type . "'");
    $case_detail_year_co->execute();
    $total_case = $case_detail_year_co->fetchColumn();
    return $total_case;
}


function get_all_ias($db, $schema, $filing_no, $ia = 35)
{
    $mainCno = "select a.filing_no,a.dt_of_filing,a.is_defective,a.scrutiny_level,a.scrutiny,b.case_no,b.case_year,c.case_type_desc,
b.status,b.regis_date
from e_case_detail as a
left join $schema.case_detail as b on b.filing_no = a.filing_no
left join case_type as c on c.id = a.case_type_nclat
where a.case_type_nclat not in (32,33,34,40) and a.filingnumberia = ? and a.filing_no != 'NA' and a.filing_no != '' and a.filing_no is not null order by a.filing_no asc";
    $mainCrs = $db->prepare($mainCno);
    //$mainCrs->bindParam(1, $ia, PDO::PARAM_STR);
    $mainCrs->bindParam(1, $filing_no, PDO::PARAM_STR);
    $mainCrs->execute();
    $data_ia_main = $mainCrs->fetchAll();
    return $data_ia_main;
}
function get_connected_cases_main($db, $schema, $filing_no, $status = 'C', $display = true)
{
    $mainCno = "select cs.conn_filing_no as filing_no,a.dt_of_filing,a.is_defective,a.scrutiny_level,a.scrutiny,b.case_no,b.case_year,c.case_type_desc,
				b.status,b.regis_date
				from $schema.connected_cases as cs
				left join e_case_detail as a on a.filing_no = cs.conn_filing_no
				left join $schema.case_detail as b on b.filing_no = a.filing_no
				left join case_type as c on c.id = a.case_type_nclat
				where cs.filing_no = ? and cs.status = ? and cs.display = ? and a.filing_no != 'NA' and a.filing_no != '' and a.filing_no is not null order by a.filing_no asc";
    $mainCrs = $db->prepare($mainCno);
    $mainCrs->bindParam(1, $filing_no, PDO::PARAM_STR);
    $mainCrs->bindParam(2, $status, PDO::PARAM_STR);
    $mainCrs->bindParam(3, $display, PDO::PARAM_STR);
    $mainCrs->execute();
    $data_ia_main = $mainCrs->fetchAll();
    return $data_ia_main;
}

function year_list($year, $start_from = 2016)
{
    for ($i = date('Y'); $i >= $start_from; $i--) { ?>
        <option <?php if ($year == $i) {
                    echo 'selected';
                } ?> value="<?php echo $i; ?>"><?php echo $i ?></option>
    <?php }
}

function court_name($db, $schema, $court_no)
{

    $st = $db->prepare("select display_court_text from $schema.court where court_no = ? ");
    $st->bindParam(1, $court_no, PDO::PARAM_STR);
    $st->execute();
    $court_name = $st->fetchColumn();
    return $court_name;
}

if ($_POST['action'] == 'case_total_details') {
    if ($_POST['city_id'] == '1') {
    ?>
        <table>
            <tr>
                <th>Years</th>
                <th>Pending Cases</th>
                <th>Disposed Cases</th>
                <th>Total Cases</th>
            </tr>
            <?php
            $year_list = year_last_three($db, $schemas);
            foreach ($year_list as $years) {
            ?>
                <tr>
                    <td><?php echo $years; ?></td>
                    <td><a href="#" data-toggle="modal" data-target="#view_case"
                            onclick="fn_case_type('<?php echo $_POST['city_id']; ?>','<?php echo $years; ?>', 'P')"><?php echo case_details($db, $schemas, 'P', $years); ?></a>
                    </td>
                    <td><a href="#" data-toggle="modal" data-target="#view_case"
                            onclick="fn_case_type('<?php echo $_POST['city_id']; ?>','<?php echo $years; ?>', 'D')"><?php echo case_details($db, $schemas, 'D', $years); ?></a>
                    </td>
                    <td><a href="#" data-toggle="modal" data-target="#view_case"
                            onclick="fn_case_type('<?php echo $_POST['city_id']; ?>','<?php echo $years; ?>', 'All')"><?php echo case_details($db, $schemas, 'All', $years); ?></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php
    } else if ($_POST['city_id'] == '2') {
    ?>
        <table>
            <tr>
                <th>Years</th>
                <th>Pending Cases</th>
                <th>Disposed Cases</th>
                <th>Total Cases</th>
            </tr>

            <tr>
                <td colspan="5" style="text-align: center"> Under Processing ..........</td>
            </tr>
        </table>

    <?php }
} else if ($_POST['action'] == 'case_type_details') {
    if ($_POST['city_id'] == '1') {
    ?>
        <table class="table">
            <tr>
                <th colspan="5" style="text-align: center">
                    GSTAT
                </th>
            </tr>
            <tr>
                <th>Sr. No.</th>
                <th>Case Type/IA</th>
                <th>Total Case</th>
            </tr>
            <?php $case_type_data = case_type($db);
            if (!empty($case_type_data)) {
                $i = 1;
                $case_type_details = 0;
                foreach ($case_type_data as $val) {
                    $case_type_details = case_type_details($db, $schemas, $_POST['case_status'], $_POST['year'], $val['id']);
                    $case_type_details_cout = $case_type_details_cout + $case_type_details;
            ?>
                    <tr>
                        <td><?php echo $i; ?>.</td>
                        <td><?php echo $val['case_type_desc']; ?></td>
                        <td>
                            <button
                                onclick="fn_case_type_details('<?php echo $_POST['city_id']; ?>','<?php echo $_POST['year']; ?>', '<?php echo $_POST['case_status']; ?>', '<?php echo $val['id']; ?>')"
                                class="btn btn-success" data-toggle="modal"
                                data-target="#view_case_details_details"><?php echo $case_type_details; ?></button>
                        </td>
                    </tr>
                <?php $i++;
                } ?>
                <tr>
                    <td colspan="2" style="text-align: center"> Total Cases</td>
                    <td><?php echo $case_type_details_cout; ?></td>

                </tr>
            <?php } else {
                echo '<tr> <td colspan="5"> Data Not Found!</td></tr>';
            } ?>


        </table>

    <?php }
} else if ($_POST['action'] == 'case_type_details_details') {
    if ($_POST['city_id'] == '1') {
        $case_status12 = $_POST['case_status'];
        $case_status_name = 'Registration/Disposed';
        if ($case_status12 == 'D') {
            $case_status_name = 'Disposed';
        } else if ($case_status12 == 'P') {
            $case_status_name = 'Registration';
        }
        $case_details = case_details_view($db, $schemas, $_POST['case_status'], $_POST['year'], $_POST['case_type']);
    ?>
        <table class="table">
            <tr>
                <th colspan="5" style="text-align: center">
                    GSTAT
                </th>
            </tr>
            <tr>
                <th>S.No</th>
                <th>Filing No.</th>
                <th>Case No.</th>
                <th>Title</th>
                <th style="width:16%">Date of <?php echo $case_status_name; ?></th>
                <th>Current Status</th>
            </tr>
            <?php
            if (!empty($case_details)) {
                $iee = 1;
                //  print_r($case_details);
                foreach ($case_details as $val) {
                    $case_type_data = case_type($db);
                    $case_type_name = '';
                    $case_type_short_name = '';
                    if (!empty($case_type_data)) {
                        $i = 1;
                        $case_type_details = 0;
                        foreach ($case_type_data as $val_case) {
                            $cae_type_name = $val['id'];
                            if ($val_case['id'] == $val['case_type']) {
                                $case_type_name = $val_case['case_type_desc'];
                                $case_type_short_name = $val_case['short_name'];
                            }
                        }
                    }
                    $date_re_dispo = (!empty($val['regis_date'])) ? date('d/m/Y', strtotime($val['regis_date'])) : '';
                    if ($val['status'] == 'D') {
                        try {
                            $filin_no = $val['filing_no'];
                            $query_q11 = $db->prepare("select * from $schemas.case_disposal where filing_no = ? ");
                            $query_q11->bindParam(1, $filin_no, PDO::PARAM_STR);
                            $query_q11->execute();
                            $valuewwww = $query_q11->fetch();
                        } catch (PDOException $ex) {
                            echo $ex;
                            die;
                        }
                        $date_re_dispo = (!empty($valuewwww['disposal_date']) && $valuewwww['disposal_date'] != '1111-11-11') ? date('d/m/Y', strtotime($valuewwww['disposal_date'])) : '';
                    }
                    $main_fiing = $val['filing_no'];
                    $case_nnoo = "<a href='#' onclick='fn_case_details($main_fiing)' > " . $case_type_short_name . '/' . $val['case_no'] . '/' . $val['case_year'] . '</a>';
                    if ($val['main_case_ia_no'] != '' or $val['main_case_ia_no'] != null) {
                        $iama_no = (int) $val['ia_ma_filing_no'];
                        $mainCno = 'select case_no,case_year,short_name from $schemas.case_detail inner join case_type on case_type=id where filing_no= ?;';
                        $mainCrs = $db->prepare($mainCno);
                        $mainCrs->bindParam(1, $iama_no, PDO::PARAM_STR);
                        $mainCrs->execute();
                        $data_ia_main = $mainCrs->fetch();
                        $case_nnoo .= "<br> IN <br> <a href='#' onclick='fn_case_details($iama_no)' > " . $data_ia_main['short_name'] . '/' . $data_ia_main['case_no'] . '/' . $data_ia_main['case_year'] . '</a>';
                    }

                    // Main File Child
                    //if ($val['case_type'] == '1' or $val['case_type'] == '2' or $val['case_type'] == '3') {
                    $data_chield = fn_getChild($db, $schemas, $val['filing_no']);
                    if (!empty($data_chield) && is_array($data_chield)) {
                        foreach ($data_chield as $chil) {
                            // print_r($chil);
                            $child_fil = $chil['filing_no'];
                            $case_nnoo .= '<br> ' . " <a href='#' onclick='fn_case_details($child_fil)' > " . $chil['short_name'] . '/' . $chil['case_no'] . '/' . $chil['case_year'] . '</a>';
                        }
                    }
                    //}

            ?>
                    <tr>
                        <td><?php echo $iee; ?>.</td>

                        <td style="text-align: center;">
                            <?php $val['filing_no']; ?>
                        </td>

                        <td style="text-align: center;">
                            <?php echo $case_nnoo; ?>

                        </td>
                        <td><?php echo $val['pet_name'] . ' <font style="color:red"> VS </font>' . $val['res_name']; ?></td>
                        <td><?php echo $date_re_dispo; ?></td>
                        <td><?php
                            if ($val['status'] == 'P') {
                                echo 'Pending';
                            } else {
                                echo 'Disposed';
                            } ?></td>
                    </tr>
            <?php $iee++;
                }
            } else {
                echo '<tr> <td colspan="5" style="text-align: center"> Data Not Found!</td></tr>';
            } ?>

        </table>

        <?php }
} else if ($_POST['action'] == 'communication_list') {
    $schemas = htmlspecialchars($_SESSION['schema_name']);
    $defect_cases = 'N';
    $status = '1';
    $status = '1';
    $commu_flag = '1';
    $_SESSION['qqcc'] = rand();
    $qq1cc = $_SESSION['qqcc'];
    try {

        //and commu_flag = ?
        //   echo "select * from $schemas.scrutiny where level_level='$status' and commu_flag = '$commu_flag'";
        $st = $db->prepare("select * from $schemas.scrutiny where level_level=? and commu_flag = ?");
        $st->bindParam(1, $status, PDO::PARAM_STR);
        $st->bindParam(2, $commu_flag, PDO::PARAM_STR);
        $st->execute();
        while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $total_count_sup = fn_check_conut_coomunication($db, $schemas, 'S', $row['filing_no']);
            $total_count_pres = fn_check_conut_coomunication($db, $schemas, 'P', $row['filing_no']);
            if ($total_count_pres == $total_count_sup) {
                $filing_no_check = htmlspecialchars($row['filing_no']);
                $notif_date = htmlspecialchars($row['notification_date']);
                $remark_message_level_1 = htmlspecialchars($row['remark_message_level_1']);
                list($year, $month, $day) = explode('-', $notif_date);
                $notif_date = $day . '/' . $month . '/' . $year;
                $defects = $row['defects'];
                $scrut_comp3 = '3';
                try {
                    $st1 = $db->prepare("select * from e_case_detail_local  where  filing_no=? AND (scrutiny_comp3=? OR scrutiny_comp3 IS NULL) and case_type  IN('2','3','7','9','16','1','14','15','19','23','25','29') order by dt_of_filing asc");
                    $st1->bindParam(1, $filing_no_check, PDO::PARAM_STR);
                    $st1->bindParam(2, $scrut_comp3, PDO::PARAM_STR);
                    $st1->execute();
                    $SER_COUT = 1;
                    while ($row = $st1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                        $filing_no = htmlspecialchars($row['filing_no']);
                        $pet_name = htmlspecialchars($row['pet_name']);
                        $res_name = htmlspecialchars($row['res_name']);
                        $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
                        $case_type = htmlspecialchars($row['case_type']);
                        list($year, $month, $day) = explode('-', $dt_of_filing);
                        $filing_date_all = $day . '/' . $month . '/' . $year;
        ?>
                        <tr style="background-color: #BDFCC9;">

                            <td><?php echo htmlspecialchars($SER_COUT); ?></td>
                            <td><?php echo htmlspecialchars($filing_date_all); ?></td>
                            <td><?php echo htmlspecialchars($filing_no); ?></td>

                            <td><?php echo htmlspecialchars_decode(strtoupper($pet_name)) . '&nbsp; Vs. &nbsp;' . htmlspecialchars_decode(strtoupper($res_name)); ?>
                            </td>
                            <td><?php

                                try {
                                    $st2 = $dbonline->prepare("select * from e_case_detail_fees where filing_no=? ");
                                    $st2->bindParam(1, $filing_no_check, PDO::PARAM_STR);
                                    $st2->execute();

                                    $i = 0;
                                    $r = '';
                                    while ($row2 = $st2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                                        $E_sec_id = $row2['sec_id'];
                                        try {
                                            $st3 = $dbonline->prepare("select * from master_section_act where id=? ");
                                            $st3->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                                            $st3->execute();

                                            while ($row3 = $st3->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                                                $E_add_sec_id = $row3['section_companies'];
                                                $r .= $E_add_sec_id . ',';
                                            }
                                        } catch (PDOException $ex) {
                                            echo $ex;
                                        }
                                    }
                                } catch (PDOException $ex) {
                                    echo $ex;
                                }
                                echo rtrim($r, ','); ?></td>
                            <td>
                                <?php $filing_nosend = $filing_no . '-' . $qq1cc;
                                $filing_no_send = base64_encode($filing_nosend); ?>
                                <h3 style="margin-top:5px;"> <span class="label label-info">
                                        <a style="color: #FFFFFF;"
                                            href="./scrutiny/communication_varify_cases.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny
                                            Verify</a>
                                    </span></h3>
                            </td>
                        </tr>
    <?php
                        $SER_COUT++;
                    }
                } catch (PDOException $ex) {
                    echo $ex;
                }
            }
        }
    } catch (PDOException $ex) {
        echo $ex;
    }
} else if ($_POST['action'] == 'case_status_search') {
    $schemas = $schemas;
    // print_r($_POST);

    $answer = $_POST['answer'];
    if ($_SESSION['vercode'] != $answer or empty($answer)) {
        echo  $meaasge = "Captch Value is incorrect/Empty, kindly try again";
        die;
    }

    //$column = ' a.case_no as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,b.short_name,a.main_case_ia_no,a.backlog ';
    $column = ' cast(a.case_no as int) as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,b.short_name,a.main_case_ia_no,a.backlog ';
    $global_where = " where a.case_no != '' and a.status != 'W'";
    $global_where2 = " and a.case_no != '' and a.status != 'W'";
    $case_year = $_POST['case_year'];
    $case_type = $_POST['case_type'];
    $case_number = $_POST['case_number'];
    $diary_no = $_POST['diary_no'];
    $party_name = $_POST['party_name'];
    $advocate_name = $_POST['advocate_name'];
    $select_party = $_POST['select_party'];
    $crn_no = $_POST['crn_no'];
    //print_r($_POST);

    $case_data = array();
    try {
        if ($_POST['search_by'] == '1') {
            $whwre = '';
            if ($case_year != 'All') {
                $whwre = "  and a.filing_no like '$case_year%'";
            }

            $query_q = $db->prepare("select $column from $schemas.case_detail as a join case_type as b ON b.id = a.case_type
        where a.filing_no = ? and a.status != 'W' $global_where2  $whwre  order by a.case_year asc, case_non asc, a.regis_date desc ");
            $query_q->bindParam(1, $diary_no, PDO::PARAM_STR);
        } else if ($_POST['search_by'] == '2') {
            $whwre = $where_status = '';
            if ($case_year != 'All') {
                $whwre = "and a.case_year = '$case_year'";
            }
            if (!empty($_POST['status_search']) && $_POST['status_search'] != 'all') {
                $where_status = "and a.status = ?";
            }
            $query_q = $db->prepare("select $column from $schemas.case_detail as a join case_type as b ON b.id = a.case_type  where a.case_type = ? and a.status != 'W' $global_where2 $whwre $where_status  order by a.case_year asc, case_non asc, a.regis_date desc ");
            $query_q->bindParam(1, $case_type, PDO::PARAM_STR);
            if ($where_status != '')
                $query_q->bindParam(2, $_POST['status_search'], PDO::PARAM_STR);
        } else if ($_POST['search_by'] == '3') {

            $whwre = '';
            if ($case_year != 'All') {
                $whwre = "and a.case_year = '$case_year'";
            }
            $query_q = $db->prepare("select $column from $schemas.case_detail  as a join case_type as b ON b.id = a.case_type where a.case_type = ? and a.case_no = ?  and a.status != 'W' $global_where2 $whwre  order by a.case_year asc, case_non asc, a.regis_date desc ");
            $query_q->bindParam(1, $case_type, PDO::PARAM_STR);
            $query_q->bindParam(2, $case_number, PDO::PARAM_STR);
        } else if ($_POST['search_by'] == '4') {
            if ($select_party == '1') {
                $query_q = $db->prepare("select $column from $schemas.case_detail   as a join case_type as b ON b.id = a.case_type  $global_where and (a.pet_name like '%$party_name%' or a.res_name like '%$party_name%') and a.case_year = '$case_year'  and a.status != 'W'  order by a.case_year asc, case_non asc, a.regis_date desc ");
            } else if ($select_party == '2') {
                $whwre = '';
                if ($case_year != 'All') {
                    $whwre = " and filing_no like '$case_year%' ";
                }
                $st_party = $dbonline->prepare("SELECT filing_no FROM public.e_cases_party where (filing_no != 'NA' OR filing_no is not null) and  name like '%$party_name%' $whwre ");
                $st_party->execute();
                $filing_no_data = $st_party->fetchAll();
                $fiing_nos = '';
                if (!empty($filing_no_data) && is_array($filing_no_data)) {
                    foreach ($filing_no_data as $val) {
                        $filiddd = $val["filing_no"];
                        $fiing_nos .= "'$filiddd'" . ',';
                    }
                }
                $fiing_nos = rtrim($fiing_nos, ',');

                $filinf_noss = " in( $fiing_nos)";
                if ($fiing_nos == '') {
                    $filinf_noss = " = 'sssssss' ";
                }

                $query_q = $db->prepare("select $column from $schemas.case_detail   as a join case_type as b ON b.id = a.case_type $global_where and a.filing_no  $filinf_noss  and a.status != 'W'  order by a.case_year asc, case_non asc, a.regis_date desc ");
            }
        } else if ($_POST['search_by'] == '5') {
            $whwre = '';
            if ($case_year != 'All') {
                $whwre = " and filing_no like '$case_year%' ";
            }
            $st_party = $dbonline->prepare("SELECT (a.rep_code),a.filing_no FROM
            public.e_more_representative as a
                        join e_master_advocate as b ON a.rep_code = b.id
                         where (a.filing_no != 'NA' AND a.filing_no is not null) and
                         a.display = true and (b.rep_name != 'NA' OR b.rep_name is not null) and b.rep_name like '%$advocate_name%'  $whwre ");

            $st_party->execute();
            $filing_no_data = $st_party->fetchAll();
            $fiing_nos = '';
            if (!empty($filing_no_data) && is_array($filing_no_data)) {
                foreach ($filing_no_data as $val) {
                    $filiddd = $val["filing_no"];
                    $fiing_nos .= "'$filiddd'" . ',';
                }
            }
            $fiing_nos = rtrim($fiing_nos, ',');

            $filinf_noss = "in( $fiing_nos)";
            if ($fiing_nos == '') {
                $filinf_noss = " = 'sssssss' ";
            }
            $query_q = $db->prepare("select $column from $schemas.case_detail   as a join case_type as b ON b.id = a.case_type $global_where and a.filing_no $filinf_noss   and a.status != 'W'  order by a.case_year asc, case_non asc, a.regis_date desc ");
        } else if ($_POST['search_by'] == '6') {

            //  print_r($_POST);
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
            $select_judge = $_POST['select_judge'];
            // $crn_filing_no = '365360'.substr($crn_no,6,16);

            // echo "select d.short_name,c.order_date, cast(a.case_no as int) as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_title,a.case_type,a.pet_name,a.res_name,ia_ma_filing_no  
            // from $schemas.order_daily as c
            // inner join $schemas.case_detail as a on a.filing_no = c.filing_no
            // join case_type as d ON d.id = a.case_type
            // inner join $schemas.bench_judge as b on c.bench_id = b.bench_no
            // where order_date between '$from_date' and '$to_date' and b.judge_code = '$select_judge'
            // order by c.order_date desc";
            $query_q = $db->prepare("
            select distinct(c.listing_date) as order_date,d.short_name, cast(a.case_no as int) as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog  
from $schemas.case_allocation as c
inner join $schemas.case_detail as a on a.filing_no = c.filing_no
join case_type as d ON d.id = a.case_type
inner join $schemas.bench_judge as b on c.bench_no = b.bench_no
where case_non != '' and a.status != 'W' listing_date between '$from_date' and '$to_date' and b.judge_code = '$select_judge'
            
            
            
            order by c.listing_date desc ");
            // $query_q->bindParam(1, $crn_filing_no, PDO::PARAM_STR);
        }
        $query_q->execute();
        $case_data = $query_q->fetchAll();
    } catch (PDOException $ex) {
        echo $ex;
        die;
    }
    // print_r($case_data);
    ?>

    <table class="table">
        <tr>
            <th colspan="5" style="text-align: center">
                <div class="form_heading">GSTAT </div>
            </th>
        </tr>
        <tr>
            <th style="width:5%">Sr.&nbsp;No.</th>
            <th style="width:5%">Filing No.</th>
            <th style="width:16%">Case No.</th>
            <th style="width:45%">Case Title</th>
            <th style="width:16%">Registration&nbsp;Date</th>
            <th>Action</th>
        </tr>
        <?php

        //  print_r($case_data);
        if (!empty($case_data) && is_array($case_data)) {
            $i = 1;
            foreach ($case_data as $value) {
                // Main File Child
                $case_no_child = '';
                //if ($value['case_type'] == '1' or $value['case_type'] == '2' or $value['case_type'] == '3') {
                $data_chield = fn_getChild($db, $schemas, $value['filing_no']);
                if (!empty($data_chield) && is_array($data_chield)) {
                    foreach ($data_chield as $chil) {
                        $case_no_child .= '<br> ' . $chil['short_name'] . '/' . $chil['case_no'] . '/' . $chil['case_year'];
                    }
                }
                //   }

                // Child Parent Condition
                if ($value['main_case_ia_no'] != '' or $value['main_case_ia_no'] != null) {
                    $iama_no = $value['main_case_ia_no'];
                    $mainCno = "select case_no,case_year,short_name from $schemas.case_detail inner join case_type on case_type=id where filing_no= ?;";
                    $mainCrs = $db->prepare($mainCno);
                    $mainCrs->bindParam(1, $iama_no, PDO::PARAM_STR);
                    $mainCrs->execute();
                    $data_ia_main = $mainCrs->fetch();
                    $case_no_child .= '<br>IN<br> ' . $data_ia_main['short_name'] . '/' . $data_ia_main['case_no'] . '/' . $data_ia_main['case_year'];
                }

                if ($value['main_case_ia_no'] != '' or $value['main_case_ia_no'] != null)
                    $party_fn = $value['main_case_ia_no'];
                else
                    $party_fn = $value['filing_no'];

                $pet_name = get_party($db, $party_fn, 'P', 1);
                $res_name = get_party($db, $party_fn, 'R', 1);

        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value['filing_no']; ?></td>
                    <td style="text-align: center;"><?php echo $value['short_name'] . ' - ' . $value['case_no'] . '/' . $value['case_year'];
                                                    echo $case_no_child;
                                                    ?></td>
                    <td><?php echo $pet_name . ' VS ' . $res_name; ?></td>
                    <td><?php if (!empty($value['regis_date']) && $value['backlog'] != '1') {
                            echo date('d/m/Y', strtotime($value['regis_date']));
                        }  ?></td>
                    <td><button type="button" onclick="fn_case_details('<?php echo $value['filing_no']; ?>')"
                            class="btn btn-success"><i class="fa fa-eye"></i> View</button></td>
                </tr>
            <?php
                $i++;
            }
        } else {
            ?>
            <tr>
                <td colspan="5"> No Data Found
                <td>
            </tr>
        <?php
        }
        die;
    } else if ($_POST['action'] == 'select_case_status') {
        $case_type_data = case_type($db);
        if ($_POST['search_by'] == '1') { ?>
            <div class="form-group">
                <label>Enter Filing No.</label>
                <input type="text" name="diary_no" id="diary_no" required="required" class="form-control required">
            </div>
        <?php

        } else if ($_POST['search_by'] == '2') {
            $case_type_status = (isset($_POST['select_status']) && !empty($_POST['select_status'])) ? $_POST['select_status'] : 'all';
        ?>
            <div class="form-group">
                <label>Select Case Type</label>
                <select required="required" class="form-control" name="case_type" id="case_type" onChange="return get_case_years(this.value);">
                    <option value="">Select</option>
                    <?php if (!empty($case_type_data)) {
                        foreach ($case_type_data as $val) {
                            echo '<option value="' . $val['id'] . '">' . $val['case_type_desc'] . '</option>';
                        }
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Select Status</label>
                <select required="required" class="form-control" name="select_status" id="select_status">
                    <option value="all" <?php echo ($case_type_status == 'all') ? 'selected' : ''; ?>>All</option>
                    <option value="P" <?php echo ($case_type_status == 'P') ? 'selected' : ''; ?>>Pending</option>
                    <option value="D" <?php echo ($case_type_status == 'D') ? 'selected' : ''; ?>>Dispose</option>
                </select>
            </div>
        <?php } else if ($_POST['search_by'] == '3') { ?>
            <div class="form-group">

                <label>Select Case Type</label>
                <select required="required" class="form-control" name="case_type" id="case_type" onChange="return get_case_years(this.value);">
                    <option value="">Select</option>
                    <?php if (!empty($case_type_data)) {
                        foreach ($case_type_data as $val) {
                            echo '<option value="' . $val['id'] . '">' . $val['case_type_desc'] . '</option>';
                        }
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Enter Case No.</label>
                <input type="text" name="case_number" id="case_number" class="form-control required">
            </div>
        <?php
        } else if ($_POST['search_by'] == '4') { ?>

            <div class="form-group">
                <label>Select Type</label>
                <select required="required" class="form-control" name="select_party" id="select_party">
                    <option value="1">Main Party</option>
                    <option value="2">Addtional Party</option>
                </select>
            </div>
            <div class="form-group">
                <label>Party Name</label>
                <input type="text" name="party_name" id="party_name" class="form-control required">
            </div>
            <?php
            $data_party_app = parties($dbonline, 'P');
            $petitioners = json_encode($data_party_app);
            ?>
            <script>
                $(function() {
                    var availableTags = '';
                    $("#party_name").autocomplete({
                        source: <?php echo $petitioners; ?>
                    });
                });
            </script>

        <?php
        } else if ($_POST['search_by'] == '5') { ?>

            <div class="form-group">
                <label>Legal Representative Name</label>
                <input type="text" name="advocate_name" id="advocate_name" class="form-control required">
            </div>

            <?php
            $data_party_adv = fn_advocate($dbonline);
            $advocates_data = json_encode($data_party_adv);
            ?>
            <script>
                $(function() {
                    var availableTags = '';
                    $("#advocate_name").autocomplete({
                        source: <?php echo $advocates_data; ?>

                    });
                });
            </script>

        <?php
        } else if ($_POST['search_by'] == '6') {



            $chairperison_list = "SELECT * FROM $schemas.master_judge where judge_desg_code in (1,6,7,8) order by judge_desg_code desc";
            $chairperison_list = $db->prepare($chairperison_list);
            $chairperison_list->execute();
            $chairperison_list = $chairperison_list->fetchAll();

            $more_judge_list = "SELECT * FROM $schemas.master_judge where judge_desg_code not in (1,6,7,8) order by judge_desg_code asc";
            $more_judge_list = $db->prepare($more_judge_list);
            $more_judge_list->execute();
            $more_judge_list = $more_judge_list->fetchAll();

            $judge_list = array_merge($chairperison_list, $more_judge_list);
            //  print_r($judge_list);

        ?>

            <div class="form-group">
                <label>Select Judge</label>
                <select required="required" class="form-control" name="select_judge" id="select_judge">
                    <option value="">Select Judge</option>
                    <?php
                    if (!empty($judge_list) && is_array($judge_list)) {
                        foreach ($judge_list as $val_j) {  ?>
                            <option value="<?php echo $val_j['judge_code']; ?>"><?php echo $val_j['judge_name']; ?></option>
                    <?php  }
                    }
                    ?>

                </select>
            </div>
            <div class="form-group">
                <label>From Date</label>
                <input type="date" name="from_date" id="from_date" class="form-control required">
            </div>
            <div class="form-group">
                <label>To Date</label>
                <input type="date" name="to_date" id="to_date" class="form-control required">
            </div>

        <?php
        }

        if ($_POST['search_by'] != '6') {
        ?>
            <div class="form-group">
                <label>Select Case Year</label>
                <select class="form-control required" name="case_year" id="case_year">
                    <option value="">Select</option>
                    <option value="All">All</option>
                    <?php echo year_list(date('Y')); ?>
                </select>
            </div>

        <?php }

        $_SESSION['salt'] = sha1(microtime());
        $saltbb = $_SESSION['salt'];
        ?>
        <input name="salt" type="hidden"
            value="<?php echo htmlspecialchars(htmlentities($saltbb)); ?>" />

        <div class="form-group captcha-div">
            <label><input name="answer" id="answer" type="text" placeholder="Captcha" class="form-control required input"
                    size="18" maxlength="6" autocomplete="off"></label>
            <img src="captcha.php" class="captcha" alt="captcha" />
            <a href="javascript:void(0);">
                <img class="refresh" src="assets/img/icon_refresh.png">
            </a>
        </div>

        <div class="form-group text-right">
            <label>&nbsp;</label>
            <button type="button" onclick="fn_search_by_case_dfr()" class="btn btn-primary"><i class="fa fa-search"></i>
                Search</button>
        </div>

        <script>
            $(".refresh").click(function() {
                $(".captcha").attr("src", "captcha.php?_=" + ((new Date()).getTime()));
            });
        </script>
    <?php
    } else if ($_POST['action'] == 'case_status_case_details') {

        $schemas = $schemas;
        $column = ' cast(a.case_no as int) as case_non, a.dt_of_filing,a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,b.short_name,a.status,a.backlog ';
        $filing_no = $_POST['filing_no'];
        try {
            $query_q = $db->prepare("select $column from $schemas.case_detail as a join case_type as b ON b.id = a.case_type
     where a.filing_no = ? order by a.case_year asc, case_non asc, a.regis_date desc ");
            $query_q->bindParam(1, $filing_no, PDO::PARAM_STR);
            $query_q->execute();
            $value = $query_q->fetch();
        } catch (PDOException $ex) {
            echo $ex;
            die;
        }

        if ($value['main_case_ia_no'] != '' or $value['main_case_ia_no'] != null)
            $party_fn = $value['main_case_ia_no'];
        else
            $party_fn = $value['filing_no'];

        $pet_name = get_party($db, $party_fn, 'P', 1);
        $res_name = get_party($db, $party_fn, 'R', 1);

    ?>

        <table class="table">
            <tr>
                <th colspan="5" style="text-align: center"> <?php echo $pet_name; ?><br> <span style="color:red">
                        VS <br> </span> <?php echo $res_name; ?></th>
            </tr>
        </table>

        <div class="accordion" id="accordionpopup">
            <div class="card">
                <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    <h2 class="mb-0">1. Case&nbsp;Detail (<?php echo 'Filing No. ' . $value['filing_no']; ?>) </h2>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionpopup">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td> <b>Filing&nbsp;No</b> </td>
                                <td><?php echo $value['filing_no']; ?></td>
                                <td> </td>
                                <td> <b>Date&nbsp;Of&nbsp;Filing</b> </td>
                                <td> <?php echo ($value['backlog'] != '1' && (!empty($value['dt_of_filing']))) ? date('d/m/Y', strtotime($value['dt_of_filing'])) : ''; ?> </td>
                            </tr>
                            <tr>
                                <td> <b>Case&nbsp;No</b> </td>
                                <td> <?php echo $value['short_name'] . ' - ' . $value['case_no'] . '/' . $value['case_year']; ?>
                                </td>
                                <td></td>
                                <td> <b>Registration&nbsp;Date </b></td>
                                <td> <?php echo ($value['backlog'] != '1' && (!empty($value['regis_date']))) ? date('d/m/Y', strtotime($value['regis_date'])) : ''; ?> </td>
                            </tr>
                            <tr>
                                <td> <b>Status </b></td>
                                <td> <?php echo ($value['status'] == 'W') ? '' : (($value['status'] == 'D') ? 'Disposed' : (($value['status'] == 'T') ? 'Transferred' : 'Pending')); ?> </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header collapsed" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <h2 class="mb-0"> 2. Party&nbsp;Details</h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionpopup">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td colspan="2" style="width:35%">
                                    <table class="table" style="width:100%">
                                        <tr>
                                            <th>Sr.&nbsp;No.</th>
                                            <th>applicant/appellant`s&nbsp;Name</th>
                                        </tr>
                                        <?php
                                        $i_p = 1;
                                        $party_pet = fn_party_type($dbonline, $value['filing_no'], 'P');
                                        if (!empty($party_pet) && is_array($party_pet)) {
                                            foreach ($party_pet as $val_pet) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $i_p; ?></td>
                                                    <td><?php echo $val_pet; ?></td>
                                                </tr>
                                        <?php $i_p++;
                                            }
                                        } else {
                                            echo '<td colspan="2"> No Data </td>';
                                        } ?>
                                    </table>
                                </td>
                                <td colspan="3" style="width:50%">
                                    <table class="table">
                                        <tr>
                                            <th>Sr.&nbsp;No.</th>
                                            <th>Respodent&nbsp;Name</th>
                                        </tr>
                                        <?php
                                        $i_r = 1;
                                        $party_res = fn_party_type($dbonline, $value['filing_no'], 'R');
                                        if (!empty($party_res) && is_array($party_res)) {
                                            foreach ($party_res as $val_res) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $i_r; ?></td>
                                                    <td><?php echo $val_res; ?></td>
                                                </tr>
                                        <?php $i_r++;
                                            }
                                        } else {
                                            echo '<td colspan="2"> No Data </td>';
                                        } ?>
                                    </table>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

            <!--- Third Teb --->
            <div class="card">
                <div class="card-header collapsed" id="heading3" data-toggle="collapse" data-target="#collapse3"
                    aria-expanded="true" aria-controls="collapse3">
                    <h2 class="mb-0">3. &nbsp;Legal Representative</h2>
                </div>
                <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionpopup">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td colspan="2" style="width:35%">
                                    <table class="table" style="width:100%">
                                        <tr>
                                            <th>Sr.&nbsp;No.</th>
                                            <th>applicant/appellant`s&nbsp;Legal Representative&nbsp;Name</th>
                                        </tr>
                                        <?php
                                        $i_ap = 1;
                                        $adv_pet = fn_advocate_type($dbonline, $value['filing_no'], 'P');
                                        if (!empty($adv_pet) && is_array($adv_pet)) {
                                            foreach ($adv_pet as $val_pet_adv) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $i_ap; ?></td>
                                                    <td><?php echo $val_pet_adv; ?></td>
                                                </tr>
                                        <?php $i_ap++;
                                            }
                                        } else {
                                            echo '<td colspan="2"> No Data </td>';
                                        } ?>
                                    </table>
                                </td>
                                <td colspan="3" style="width:50%">
                                    <table class="table" style="width:100%">
                                        <tr>
                                            <th>Sr.&nbsp;No.</th>
                                            <th>Respodent&nbsp;Legal Representative&nbsp;Name</th>
                                        </tr>
                                        <?php
                                        $i_ar = 1;
                                        $adv_res = fn_advocate_type($dbonline, $value['filing_no'], 'R');
                                        if (!empty($adv_res) && is_array($adv_res)) {
                                            foreach ($adv_res as $val_res_adv) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $i_ar; ?></td>
                                                    <td><?php echo $val_res_adv; ?></td>
                                                </tr>
                                        <?php $i_ar++;
                                            }
                                        } else {
                                            echo '<td colspan="2"> No Data </td>';
                                        } ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!--- 4th Teb --->
            <div class="card">
                <div class="card-header collapsed" id="heading4" data-toggle="collapse" data-target="#collapse4"
                    aria-expanded="true" aria-controls="collapse4">
                    <h2 class="mb-0">4. First&nbsp;Hearing&nbsp;Details </h2>
                </div>
                <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionpopup">
                    <div class="card-body">
                        <table class="table">

                            <?php
                            try {
                                $query_case_all = $db->prepare("select bench_no,a.remarks,b.purpose_name,a.filing_no,a.listing_date,a.court_no from $schemas.case_proceeding as a  left join $schemas.master_purpose as b ON b.purpose_code = a.purpose
            where a.filing_no = ? order by listing_date asc");
                                $query_case_all->bindParam(1, $filing_no, PDO::PARAM_STR);
                                $query_case_all->execute();
                                $value_allocation_first = $query_case_all->fetch();
                            } catch (PDOException $ex) {
                                echo $ex;
                                die;
                            }
                            if ($value_allocation_first['listing_date'] != '' && $value_allocation_first['court_no'] != '') {


                                $coram_name = fn_judge_list($db, $schemas, $value_allocation_first['listing_date'], $value_allocation_first['bench_no']);
                            ?>

                                <tr>

                                    <td> <b>Court&nbsp;No. </b> </td>
                                    <td> <?php echo court_name($db, $schemas, $value_allocation_first['court_no']); ?> </td>
                                    <td> &nbsp; </td>
                                    <td> <b>Hearing&nbsp;Date </b> </td>
                                    <td> <?php echo (!empty($value_allocation_first['listing_date']) && $value_allocation_first['listing_date'] != '1111-11-11') ? date('d/m/Y', strtotime($value_allocation_first['listing_date'])) : ''; ?> </td>

                                </tr>
                                <tr>
                                    <td> <b>Coram </b> </td>
                                    <td> <?php echo  $coram_name; ?> </td>
                                    <td> &nbsp; </td>

                                    <td> <b>Stage&nbsp;Of&nbsp;Case </b> </td>
                                    <td><?php echo $value_allocation_first['purpose_name']; ?></td>

                                </tr>
                            <?php } else {
                                echo '<tr>  <th colspan="5" style="text-align: center; ">No Data </th></tr>';
                            } ?>

                        </table>
                    </div>
                </div>
            </div>


            <!--- 5th Teb --->
            <div class="card">
                <div class="card-header collapsed" id="heading5" data-toggle="collapse" data-target="#collapse5"
                    aria-expanded="true" aria-controls="collapse5">
                    <h2 class="mb-0">5. Last&nbsp;Hearing&nbsp;Details</h2>
                </div>
                <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordionpopup">
                    <div class="card-body">
                        <table class="table">
                            <?php

                            try {
                                $query_case_all_last = $db->prepare("select bench_no,a.remarks, b.purpose_name,a.filing_no,a.listing_date,a.court_no from $schemas.case_proceeding as a  left join $schemas.master_purpose as b ON b.purpose_code = a.purpose
        where a.filing_no = ? order by listing_date desc");
                                $query_case_all_last->bindParam(1, $filing_no, PDO::PARAM_STR);
                                $query_case_all_last->execute();
                                $value_allocation_last = $query_case_all_last->fetch();
                            } catch (PDOException $ex) {
                                echo $ex;
                                die;
                            }
                            if ($value_allocation_last['listing_date'] != '' && $value_allocation_last['court_no'] != '') {
                                $coram_name1 = fn_judge_list($db, $schemas, $value_allocation_last['listing_date'], $value_allocation_last['bench_no']);
                            ?>

                                <tr>

                                    <td> <b>Court&nbsp;No. </b> </td>
                                    <td> <?php echo court_name($db, $schemas, $value_allocation_last['court_no']); ?> </td>
                                    <td> &nbsp; </td>
                                    <td> <b>Hearing&nbsp;Date </b> </td>
                                    <td> <?php echo (!empty($value_allocation_first['listing_date']) && $value_allocation_first['listing_date'] != '1111-11-11') ? date('d/m/Y', strtotime($value_allocation_last['listing_date'])) : ''; ?> </td>

                                </tr>
                                <tr>

                                    <td> <b>Coram</b> </td>
                                    <td> <?php echo $coram_name1; ?> </td>
                                    <td> &nbsp; </td>
                                    <td> <b>Stage&nbsp;Of&nbsp;Case </b> </td>
                                    <td><?php echo $value_allocation_last['purpose_name']; ?></td>

                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>

            <!--- 6th Teb --->
            <div class="card">
                <div class="card-header collapsed" id="heading6" data-toggle="collapse" data-target="#collapse6"
                    aria-expanded="true" aria-controls="collapse6">
                    <h2 class="mb-0">6. Next&nbsp;Hearing&nbsp;Details</h2>
                </div>
                <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#accordionpopup">
                    <div class="card-body">
                        <table class="table">
                            <?php
                            try {
                                $query_case_all_proce = $db->prepare("select bench_no,a.remarks,a.todays_status,b.purpose_name,a.filing_no,a.next_list_date,a.court_no,a.next_list_purpose from $schemas.case_proceeding as a  left join $schemas.master_purpose as b ON b.purpose_code = a.next_list_purpose
            where a.filing_no = ? order by listing_date desc ");
                                $query_case_all_proce->bindParam(1, $filing_no, PDO::PARAM_STR);
                                $query_case_all_proce->execute();
                                $value_allocation_proc = $query_case_all_proce->fetch();
                            } catch (PDOException $ex) {
                                echo $ex;
                                die;
                            }
                            if ($value_allocation_proc['next_list_date'] != '' && $value_allocation_proc['court_no'] != '') {

                                //  $coram_name11 = fn_judge_list($db, $schemas, $value_allocation_proc['next_list_date'],$value_allocation_proc['bench_no']);

                            ?>
                                <tr>
                                    <td> <b>Hearing&nbsp;Date </b> </td>
                                    <td> <?php
                                            if ($value_allocation_proc['next_list_date'] != '1111-11-11' && (!empty($value_allocation_proc['next_list_date']))) {
                                                echo date('d/m/Y', strtotime($value_allocation_proc['next_list_date']));
                                            }
                                            ?> </td>
                                    <td> &nbsp; </td>
                                    <td> <b>Court&nbsp;No. </b> </td>
                                    <td> <?php echo court_name($db, $schemas, $value_allocation_proc['court_no']); ?> </td>
                                </tr>


                                <tr>
                                    <td> <b>Proceedings&nbsp;Summary </b> </td>
                                    <td><?php echo $value_allocation_proc['remarks']; ?></td>

                                    <td> </td>
                                    <td> <b>Stage&nbsp;Of&nbsp;Case </b> </td>
                                    <td><?php
                                        if ($value_allocation_proc['todays_status'] == 'D') {
                                            echo "Disposed";
                                        } else {
                                            echo $value_allocation_proc['purpose_name'];
                                        }

                                        ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>

            <!--- 7th Teb --->
            <div class="card">
                <div class="card-header collapsed" id="heading7" data-toggle="collapse" data-target="#collapse7"
                    aria-expanded="true" aria-controls="collapse7">
                    <h2 class="mb-0">7. Case&nbsp;History</h2>
                </div>
                <div id="collapse7" class="collapse" aria-labelledby="heading7" data-parent="#accordionpopup">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Sr.&nbsp;No.</th>
                                <th>Hearing&nbsp;Date</th>
                                <th>Court&nbsp;No</th>
                                <th>Purpose</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            try {
                                $query_case_all = $db->prepare("select b.purpose_name,a.filing_no,a.listing_date,a.court_no from $schemas.case_proceeding as a  left join $schemas.master_purpose as b ON b.purpose_code = a.purpose
            where a.filing_no = ? order by listing_date desc ");
                                $query_case_all->bindParam(1, $filing_no, PDO::PARAM_STR);
                                $query_case_all->execute();
                                $value_allocation = $query_case_all->fetchAll();
                            } catch (PDOException $ex) {
                                echo $ex;
                                die;
                            }
                            if (!empty($value_allocation) && is_array($value_allocation)) {
                                $iee = 1;
                                foreach ($value_allocation as $val) { ?>
                                    <tr>
                                        <td> <?php echo $iee; ?> </td>
                                        <td><?php echo (!empty($val['listing_date'])) ? date('d/m/Y', strtotime($val['listing_date'])) : ''; ?></td>
                                        <td><?php echo court_name($db, $schemas, $val['court_no']); ?> </td>
                                        <td><?php echo $val['purpose_name']; ?> </td>
                                        <td>
                                            <button type="button"
                                                onclick="fn_case_details_hearing('<?php echo $val['filing_no']; ?>','<?php echo $val['listing_date']; ?>')"
                                                class="btn btn-success"><i class="fa fa-eye"></i> View</button>
                                        </td>
                                    </tr>
                            <?php $iee++;
                                }
                            } else {
                                echo ' No Data';
                            } ?>
                        </table>
                    </div>
                </div>
            </div>


            <!--- 7th Teb --->
            <div class="card">
                <div class="card-header collapsed" id="heading8" data-toggle="collapse" data-target="#collapse8" aria-expanded="true" aria-controls="collapse8">
                    <h2 class="mb-0">8. Order&nbsp;History</h2>
                </div>
                <div id="collapse8" class="collapse" aria-labelledby="heading8" data-parent="#accordionpopup">
                    <div class="card-body">
                        <?php
                        //print_r($_REQUEST);
                        //$schemas=htmlspecialchars($_SESSION['schema_name']);
                        // $order_data = get_data($db, $schema . '.order_daily', array('filing_no' => $filing_no)); 
                        try {
                            $flag = 'Y';
                            $order_data1 = $db->prepare("select * from $schemas.order_daily where filing_no = ? and flag = ? order by order_date desc ");
                            $order_data1->bindParam(1, $filing_no, PDO::PARAM_STR);
                            $order_data1->bindParam(2, $flag, PDO::PARAM_STR);
                            $order_data1->execute();
                            $order_data = $order_data1->fetchAll();
                        } catch (PDOException $ex) {
                            echo $ex;
                            die;
                        }
                        // print_r($order_data);
                        ?>
                        <table class="table">
                            <thead>
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
                                        $order_text_color = 'dimgrey';
                                        $order_type = 'Daily Order';
                                        if ($val['order_type'] == 'D') {
                                            $order_type = 'Daily Order';
                                        }
                                        if ($val['order_type'] == 'DC') {
                                            $order_type = 'Daily Order (Corrected';
                                        }
                                        if ($val['order_type'] == 'J') {
                                            $order_text_color = 'red';
                                            $order_type = 'Final Order /Judgement';
                                        }
                                        if ($val['order_type'] == 'JC') {
                                            $order_text_color = 'red';
                                            $order_type = 'Final Order /Judgement (Corrected)';
                                        }
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo (!empty($val["order_date"])) ? date('d-m-Y', strtotime($val["order_date"])) : ''; ?></td>
                                            <td style="color:<?php echo $order_text_color; ?>;"><?php echo $order_type; ?></td>
                                            <td><a href="scrutiny/readpdf.php?path=<?php echo urlencode($val["pdf_path"]); ?>" target="_blank"><i class="fa fa-file-pdf" style="color:#ba290e;" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Download</a></td>
                                        </tr>
                                <?php $i++;
                                    }
                                } else {
                                    echo ' <tr><td colspan="4">No Data </td></tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!--- 7th Teb --->
            <div class="card">
                <div class="card-header collapsed" id="heading9" data-toggle="collapse" data-target="#collapse9" aria-expanded="true" aria-controls="collapse9">
                    <h2 class="mb-0">9. IA's/Other applications&nbsp;</h2>
                </div>
                <div id="collapse9" class="collapse" aria-labelledby="heading9" data-parent="#accordionpopup">
                    <div class="card-body">
                        <?php
                        //print_r($_REQUEST);
                        //$schemas=htmlspecialchars($_SESSION['schema_name']);
                        // $order_data = get_data($db, $schema . '.order_daily', array('filing_no' => $filing_no)); 
                        try {
                            $all_ias = get_all_ias($db, $schemas, $filing_no);
                        } catch (PDOException $ex) {
                            echo $ex;
                            die;
                        }
                        // print_r($order_data);
                        ?>
                        <table class="table">
                            <thead>
                                <tr role="row">
                                    <th>Sr. No. </th>
                                    <th>Filing No</th>
                                    <th>Case No</th>
                                    <th>Date of filing</th>
                                    <th>Registration date</th>


                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($all_ias) && is_array($all_ias)) {
                                    $i = 1;
                                    foreach ($all_ias as $val) {
                                        if (!empty($val['status'])) {
                                            $status_text_color = 'green';
                                            $status = $val['status'];
                                            $status = ($status == 'P') ? 'Pending' : (($status == 'D') ? 'Disposed' : '');
                                        } else {
                                            $scrutiny_level = $val['scrutiny_level'];
                                            $is_defective = $val['is_defective'];
                                            $status_text_color = 'red';
                                            $status = ($is_defective == '1') ? 'Defective' : 'Under Scrutiny';
                                        }
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $val['filing_no']; ?></td>
                                            <td><?php echo (!empty($val['case_no'])) ? $val['case_type_desc'] . ' - ' . $val['case_no'] . '/' . $val['case_year'] : ''; ?></td>
                                            <td><?php echo (!empty($val['dt_of_filing'])) ? date('d-m-Y', strtotime($val["dt_of_filing"])) : ''; ?></td>
                                            <td><?php echo (!empty($val['regis_date'])) ? date('d-m-Y', strtotime($val["regis_date"])) : ''; ?></td>

                                            <td style="color:<?php echo $status_text_color; ?>;"><?php echo $status; ?></td>
                                        </tr>
                                <?php $i++;
                                    }
                                } else {
                                    echo ' <tr><td colspan="4">No Data </td></tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--- 7th Teb --->
            <div class="card">
                <div class="card-header collapsed" id="heading10" data-toggle="collapse" data-target="#collapse10" aria-expanded="true" aria-controls="collapse10">
                    <h2 class="mb-0">10. Connected&nbsp;Cases</h2>
                </div>
                <div id="collapse10" class="collapse" aria-labelledby="heading10" data-parent="#accordionpopup">
                    <div class="card-body">
                        <?php
                        //print_r($_REQUEST);
                        //$schemas=htmlspecialchars($_SESSION['schema_name']);
                        // $order_data = get_data($db, $schema . '.order_daily', array('filing_no' => $filing_no)); 
                        try {
                            $all_connected_cases = get_connected_cases_main($db, $schemas, $filing_no);
                        } catch (PDOException $ex) {
                            echo $ex;
                            die;
                        }
                        // print_r($order_data);
                        ?>
                        <table class="table">
                            <thead>
                                <tr role="row">
                                    <th>Sr. No. </th>
                                    <th>Filing No</th>
                                    <th>Case No</th>
                                    <th>Date of filing</th>
                                    <th>Registration date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($all_connected_cases) && is_array($all_connected_cases)) {
                                    $i = 1;
                                    foreach ($all_connected_cases as $val) {
                                        if (!empty($val['status'])) {
                                            $status_text_color = 'green';
                                            $status = $val['status'];
                                            $status = ($status == 'P') ? 'Pending' : (($status == 'D') ? 'Disposed' : '');
                                        }
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $val['filing_no']; ?></td>
                                            <td><?php echo (!empty($val['case_no'])) ? $val['case_type_desc'] . ' - ' . $val['case_no'] . '/' . $val['case_year'] : ''; ?></td>
                                            <td><?php echo (!empty($val['dt_of_filing'])) ? date('d-m-Y', strtotime($val["dt_of_filing"])) : ''; ?></td>
                                            <td><?php echo (!empty($val['regis_date'])) ? date('d-m-Y', strtotime($val["regis_date"])) : ''; ?></td>

                                            <td style="color:<?php echo $status_text_color; ?>;"><?php echo $status; ?></td>
                                        </tr>
                                <?php $i++;
                                    }
                                } else {
                                    echo ' <tr><td colspan="4">No Data </td></tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>




        </div>

        <!--- End Table -->
    <?php
    } else if ($_POST['action'] == 'case_status_case_details_hearing') {
        $schemas = $schemas;
        $column = ' cast(a.case_no as int) as case_non, a.dt_of_filing,a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,b.short_name';
        $filing_no = $_POST['filing_no'];
        $listing_date = $_POST['listing_date'];
        try {
            $query_q = $db->prepare("select $column from $schemas.case_detail as a join case_type as b ON b.id = a.case_type
    where a.filing_no = ? order by a.case_year asc, case_non asc, a.regis_date desc ");
            $query_q->bindParam(1, $filing_no, PDO::PARAM_STR);
            $query_q->execute();
            $value = $query_q->fetch();
        } catch (PDOException $ex) {
            echo $ex;
            die;
        }

    ?>
        <table class="table">
            <tr>
                <th colspan="5" style="text-align: center">
                    Daily Status
                    <br>
                    GSTAT
                    <br>
                    Case No : <?php echo $value['short_name'] . ' - ' . $value['case_no'] . '/' . $value['case_year']; ?>
                    <br>
                    Diary No : <?php echo $value['filing_no'] ?>
                    <br>
                    <?php echo $value['pet_name']; ?> <span style="color:red"> VS </span> <?php echo $value['res_name']; ?>
                    <br>
                    Listing Date : <?php echo (!empty($listing_date)) ? date('d/m/Y', strtotime($listing_date)) : ''; ?>
                    <br>
                </th>
            </tr>

            <tr>
                <th colspan="5" style="text-align: center">

                    <div class="modal-header-inner">
                        <h4 class="modal-title" id="case_title_status"> Hearing Details </h4>
                    </div>
                </th>
            </tr>
            <?php

            try {
                $query_case_all = $db->prepare("select bench_no,a.remarks,b.purpose_name,a.filing_no,a.listing_date,a.court_no from $schemas.case_proceeding as a  left join $schemas.master_purpose as b ON b.purpose_code = a.purpose
           where a.filing_no = ? and a.listing_date = ? order by a.listing_date desc ");
                $query_case_all->bindParam(1, $filing_no, PDO::PARAM_STR);
                $query_case_all->bindParam(2, $listing_date, PDO::PARAM_STR);
                $query_case_all->execute();
                $value_allocat = $query_case_all->fetch();
            } catch (PDOException $ex) {
                echo $ex;
                die;
            }

            // print_r($value_allocat);

            $coram_name1_ll = fn_judge_list($db, $schemas, $value_allocat['listing_date'], $value_allocat['bench_no']);

            ?>
            <tr>

                <td> <b>Court No. </b> </td>
                <td> <?php echo court_name($db, $schemas, $value_allocat['court_no']); ?> </td>
                <td> &nbsp; </td>
                <td> <b>Date </b> </td>
                <td> <?php echo (!empty($value_allocat['listing_date'])) ? date('d/m/Y', strtotime($value_allocat['listing_date'])) : ''; ?> </td>

            </tr>

            <tr>
                <td> <b>Coram </b> </td>
                <td colspan="4"> <?php echo $coram_name1_ll; ?> </td>

            </tr>
            <tr>
                <td> <b> Proceedings Summary </b> </td>
                <td><?php echo $value_allocat['remarks']; ?></td>
                <td> </td>
                <td> <b>Stage Of Case </b> </td>
                <td><?php echo $value_allocat['purpose_name']; ?></td>
            </tr>
        </table>
        <?php
    } else if ($_POST['action'] == 'case_status_datewise') {
        //print_r($_REQUEST);
        if (isset($_POST['listing_date'])) {

            $listing_date = $_POST['listing_date'];
            list($day, $month, $year) = explode('/', $listing_date);
            $court_date_new = $year . '-' . $month . '-' . $day;
            $date_to = $_POST['date_to'];
            list($day1, $month1, $year1) = explode('/', $date_to);
            $date_to12 = $year1 . '-' . $month1 . '-' . $day1 . ' 23:59:59';
            $court = $_POST['court'];
            if ($court == '0') {
                echo "please select court";
                die;
            }
            if ($court == 'all') {
                $court_query = '';
            } else {
                $court_query = " and court_no = '$court'";
            }
            $sql2 = " select from_list_date,court_no,list_flag from $schemas.bench where  (from_list_date BETWEEN '$court_date_new' AND '$date_to12') $court_query group by (from_list_date,court_no,list_flag) order by from_list_date asc,court_no,list_flag asc";

            $bench_d = $dbh->prepare($sql2);
            $bench_d->execute();
            $bench_data = $bench_d->fetchAll();
            if (!empty($bench_data) && is_array($bench_data)) {

        ?>
                <table cellspacing="0" align="center" class="table no-margin table-bordered table-striped table-hover"
                    cellpadding="2" border="1" width="95%" class="std">
                    <input type="hidden" name="lis_date" value="<?php echo htmlspecialchars($court_date_new); ?>" />
                    <tr>

                        <th>
                            <font face="Verdana, Arial, Helvetica, sans-serif">Sr. No.</font>
                        </th>
                        <th>
                            <font face="Verdana, Arial, Helvetica, sans-serif">Listing Date</font>
                        </th>

                        <th align="left">
                            <font face="Verdana, Arial, Helvetica, sans-serif">Court No </font>
                        </th>

                        <th align="left">
                            <font face="Verdana, Arial, Helvetica, sans-serif">View</font>
                        </th>



                    </tr>
                    <?php
                    $flag = 0;
                    $case_limit_avail = '0';
                    $aa = 1;
                    foreach ($bench_data as $row2) {
                        $flag = 1;
                        $court_no = $row2['court_no'];
                        $listind_date_date = $row2['from_list_date'];
                        $list_flag = $row2['list_flag'];
                        if ($list_flag == '0' || $list_flag == '' || $list_flag == null) {
                            $list_flag = 1;
                        }

                        $filing_count = " select count(filing_no) as filing_count from $schemas.case_allocation where court_no = '$court_no' and listing_date= '$listind_date_date' ";

                        $filing_count = $dbh->prepare($filing_count);
                        $filing_count->execute();
                        $filing_count_life =  $filing_count->fetchColumn();
                        if ($filing_count_life != '0') {

                    ?>
                            <tr>

                                <td valign="top" align="center">
                                    <?php echo $aa; ?>
                                </td>
                                <td valign="top" align="center">
                                    <?php echo (!empty($listind_date_date)) ? date('d/m/Y', strtotime($listind_date_date)) : ''; ?>
                                </td>

                                <?php
                                echo '<td>';
                                print "<font size='2' ><b>" . (court_name($db, $schemas, $court_no));
                                echo '</td>';


                                ?>
                                <td>


                                    <?php if ($filing_count_life == '0') { ?>
                                        <a href="javascript:void(0)" onclick="my_function_f('<?php echo date('d/m/Y', strtotime($listind_date_date)); ?>')" class="btn btn-primary"><i class="fa fa-eye"></i> View</a>
                                    <?php } else {   ?>
                                        <a onclick="return generate_cause_list_fn('<?php echo date('d/m/Y', strtotime($listind_date_date)); ?>','<?php echo $listind_date_date; ?>','<?php echo $schemas; ?>','<?php echo $court_no; ?>','<?php echo $list_flag; ?>');" href="javascript::void(0);" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i> View</a>
                                    <?php } ?>

                    <?php echo '</td>';

                            $aa++;
                        }
                    }
                    echo '</tr></table>';
                }
            }
        } else if ($_POST['action'] == 'get_court') {
            $st_party = $dbonline->prepare("select * from $schemas.court");
            $st_party->execute();
            $courts = $st_party->fetchAll(); ?>
                    <label>Select Court</label>
                    <select required="required" class="form-control" name="court" id="court">
                        <option value="0">Select Court</option>
                        <option value="all">All</option>
                        <?php
                        foreach ($courts as $k => $court) {
                            echo "<option value='$court[court_no]'>$court[display_court_text]</option>";
                        }
                        echo "</select>";
                        die;
                    } else if ($_POST['action'] == 'show_case_years') {
                        $case_type = $_POST['case_type'];
                        if ($case_type == '36' || $case_type == '61')
                            $start_from = 1999;
                        else
                            $start_from = 2016;
                        ?>
                        <option value="">Select</option>
                        <option value="All">All</option>
                    <?php echo year_list(date('Y'), $start_from);
                        die;
                    } else if ($_POST['action'] == 'return_cases_sc') {

                    
                        $filing_no = $_POST['filing_no'];
                        $remark_return_cases = $_POST['remark_return_cases'];
                        $is_return = 1;
                        try { 
                            $st1x = $db->prepare("update e_case_detail set remark_return_cases= ?,is_return = ?,scrutiny_level=0,is_defective=0 where filing_no=? ");
                            $st1x->bindParam(1, $remark_return_cases, PDO::PARAM_STR);
                            $st1x->bindParam(2, $is_return, PDO::PARAM_STR);
                            $st1x->bindParam(3, $filing_no, PDO::PARAM_STR);
                            if($st1x->execute()) { 
                               echo 1;
                            } else { 
                                echo  0;  
                            }
                        } catch(PDOException $ex) { 
                            echo $ex;
                        }
                      
                    }else if ($_POST['action'] == 'courts') {
                       
                        $st_party = $dbonline->prepare("select * from $schemas.court order by court_no");
                        $st_party->execute();
                        $courts = $st_party->fetchAll(); ?>
                                    <?php
                                    foreach ($courts as $k => $court) {
                                        echo "<option value='$court[court_no]'>$court[display_court_text]</option>";
                                    }
                                    die;
                    }



                    function parties($dbonline, $type)
                    {
                        //echo "SELECT name FROM public.e_cases_party where filing_no = '$filing_no' and party_flag = 'P' ";
                        $st_party = $dbonline->prepare("SELECT name FROM public.e_cases_party where (filing_no != 'NA' OR filing_no is not null) and  name != 'NA' ");
                        $st_party->execute();
                        $data_party = $st_party->fetchAll();

                        $main_data = array();
                        if (!empty($data_party) && is_array($data_party)) {
                            foreach ($data_party as $val) {
                                $main_data[] = $val['name'];
                            }
                        }
                        return $main_data;
                    }
                    function fn_advocate($dbonline)
                    {
                        $st_party = $dbonline->prepare("SELECT distinct(a.rep_code), b.rep_name,b.bar_council_number FROM public.e_more_representative as a
    join e_master_advocate as b ON a.rep_code = b.id
     where (a.filing_no != 'NA' OR a.filing_no is not null) and  a.display = true and (b.rep_name != 'NA' OR b.rep_name is not null) ");
                        $st_party->execute();
                        $data_party = $st_party->fetchAll();
                        $main_data = array();
                        if (!empty($data_party) && is_array($data_party)) {
                            foreach ($data_party as $val) {
                                //.'('.$val['bar_council_number'].')'
                                $main_data[] = $val['rep_name'];
                            }
                        }
                        return $main_data;
                    }


                    function fn_party_type($dbonline, $filing_no, $type)
                    {
                        //echo "SELECT name FROM public.e_cases_party where filing_no = '$filing_no' and party_flag = 'P' ";
                        $st_party = $dbonline->prepare("SELECT name FROM public.e_cases_party where filing_no = '$filing_no' and party_flag = '$type' ");
                        $st_party->execute();
                        $data_party = $st_party->fetchAll();
                        $main_data = array();
                        if (!empty($data_party) && is_array($data_party)) {
                            foreach ($data_party as $val) {
                                $main_data[] = $val['name'];
                            }
                        }
                        return $main_data;
                    }
                    function fn_advocate_type($dbonline, $filing_no, $type)
                    {
                        $st_party = $dbonline->prepare("SELECT distinct(a.rep_code), b.rep_name,b.bar_council_number FROM public.e_more_representative as a
    join e_master_advocate as b ON a.rep_code = b.id
     where  a.display = true and (b.rep_name != 'NA' OR b.rep_name is not null) and a.filing_no = '$filing_no' and a.party_flag = '$type' ");
                        $st_party->execute();
                        $data_party = $st_party->fetchAll();
                        $main_data = array();
                        if (!empty($data_party) && is_array($data_party)) {
                            foreach ($data_party as $val) {
                                //.'('.$val['bar_council_number'].')'
                                $main_data[] = $val['rep_name'];
                            }
                        }
                        return $main_data;
                    }



                    ?>

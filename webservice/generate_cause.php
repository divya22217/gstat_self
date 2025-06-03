<?php
 $schemas = 'delhi';
$counttime = 0;
$pagec = 1;
$listbefore = $_REQUEST["list_before"];
$list_date = $_REQUEST['next_list_date'];
$court_no = $court_nono = $_REQUEST['court_no'];
$list_flag = 1;

if ($list_flag == 1) {
    $causelisthead = "DAILY CAUSE LIST";
}
if ($list_flag == 2) {
    $causelisthead = "SUPPLEMENTRY CAUSE LIST";
}
if ($list_flag == 3) {
    $causelisthead = "VACATION CAUSE LIST";
}

list($day, $month, $year) = explode('/', $list_date);
$next_list_date11 = $year . '-' . $month . '-' . $day;
$print_header = "Y";
$printtop = "Y";
$serialno = 1;
$list_date = $list_date;
$print_flag_right_data = 'N';
list($day, $month, $year) = explode('/', $list_date);
$listdate_entire = $year . '-' . $month . '-' . $day;
$print_count = 1;

$sr_no = 1;
if ($listbefore > 0 and $court_no > 0 and ($listdate_entire != '--' OR $listdate_entire != '')) {


    $benchloop1 = $db->prepare("select * from $schemas.bench b ,$schemas.bench_nature bn
where b.from_list_date= ? and  b.bench_nature=? and bn.bench_code =? and
b.bench_nature=bn.bench_code and b.court_no =? order by b.court_no, b.priority asc");
    $benchloop1->bindParam(1, $listdate_entire, PDO::PARAM_STR);
    $benchloop1->bindParam(2, $listbefore, PDO::PARAM_STR);
    $benchloop1->bindParam(3, $listbefore, PDO::PARAM_STR);
    $benchloop1->bindParam(4, $court_no, PDO::PARAM_STR);
}

if ($listbefore > 0 and $court_no == '' and ($listdate_entire != '--' OR $listdate_entire != '')) {
    $benchloop1 = $db->prepare("select * from $schemas.bench b ,$schemas.bench_nature bn where
b.from_list_date=?	and b.bench_nature=? and bn.bench_code =? order by b.court_no, b.priority asc");

    $benchloop1->bindParam(1, $listdate_entire, PDO::PARAM_STR);
    $benchloop1->bindParam(2, $listbefore, PDO::PARAM_STR);
    $benchloop1->bindParam(3, $listbefore, PDO::PARAM_STR);

}

if ($listbefore == 0 and $court_no > 0 and ($listdate_entire != '--' OR $listdate_entire != '')) {
    $benchloop1 = $db->prepare("select * from $schemas.bench b ,$schemas.bench_nature bn
where b.from_list_date=? and b.bench_nature=bn.bench_code and b.court_no =?
order by b.court_no, b.priority asc");

    $benchloop1->bindParam(1, $listdate_entire, PDO::PARAM_STR);
    $benchloop1->bindParam(2, $court_no, PDO::PARAM_STR);
}

if ($listbefore == 0 and $court_no == '' and ($listdate_entire != '--' OR $listdate_entire != '')) {
    $benchloop1 = $db->prepare("select * from $schemas.bench b ,$schemas.bench_nature bn where 
b.from_list_date=? and b.bench_nature=bn.bench_code order by b.court_no,
b.priority asc");

    $benchloop1->bindParam(1, $listdate_entire, PDO::PARAM_STR);
}
$benchloop1->execute();
while ($row_loop1 = $benchloop1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {


    $from_time = $row_loop1['from_time'];
    $detail = $row_loop1['detail'];
    $bench_code1 = $row_loop1['bench_no'];
    $b_nature = $row_loop1['bench_nature'];
    $curYear = date('Y');


    $frmdate = $year . '-' . $month . '-' . $day;
    $listdate = date('l \t\h\e jS F Y', mktime(0, 0, 0, $month, $day, $year));
}

$todate = $next_list_date11;

$sql_time = "select from_time from $schemas.bench  where court_no=? and bench_no=?and (? between from_list_date and to_list_date);";
$sth10 = $db->prepare($sql_time);
$sth10->bindParam(1, $court_no, PDO::PARAM_STR);
$sth10->bindParam(2, $bench_no1, PDO::PARAM_STR);
$sth10->bindParam(3, $todate, PDO::PARAM_STR);
$sth10->execute();
$from_time = $sth10->fetchColumn();
$main_array = array();
$header = 0;
$sql_desg = "select name from initilization";
$sth14 = $db->prepare($sql_desg);
$sth14->execute();
$name_ins = $sth14->fetchColumn();
$main_array['intilize_name'] = $name_ins;
$dateCol = 'detail';
$sql_time = "select $dateCol from $schemas.bench  where court_no=? and
bench_no=? and (? between from_list_date and to_list_date);";
$sth10 = $db->prepare($sql_time);
$sth10->bindParam(1, $court_no, PDO::PARAM_STR);
$sth10->bindParam(2, $bench_no1, PDO::PARAM_STR);
$sth10->bindParam(3, $todate, PDO::PARAM_STR);
$sth10->execute();
$detail123 = $sth10->fetchColumn();
$main_array['intilize_name'] = $name_ins;
$main_array['detail'] = $detail123;
$main_array['date_cause_date'] = $listdate;

$main_array['causelisthead'] = $causelisthead;

$sql = "select bench_no  from $schemas.bench where bench_nature='$listbefore' and court_no='$court_no' and from_list_date='$listdate_entire'";
$mm_ar = 0;
foreach ($db->query($sql) as $row) {
    $tem_mani_array = array();
    $bench_code1 = $row['bench_no'];
    $sql_judge = "select jm.judge_name,jm.judge_desg_code,jm.judge_code from $schemas.master_judge as jm,
$schemas.bench as b where  b.from_list_date=? and b.bench_no=? and
b.court_no=? and jm.judge_code=b.presiding";
    $sth101 = $db->prepare($sql_judge);
    $sth101->bindParam(1, $todate, PDO::PARAM_STR);
    $sth101->bindParam(2, $bench_no1, PDO::PARAM_STR);
    $sth101->bindParam(3, $court_no, PDO::PARAM_STR);
    $sth101->execute();
    while ($j = $sth101->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $judge_name = $j['judge_name'];
        $judge_desg_code = $j['judge_desg_code'];
        $presiding_code = $j['judge_code'];

        /*query for display of judge*/
// $jj = "select display from $schemas.bench_judge where display='true' and judge_code =$presiding_code  and from_list_date=$todate and bench_no=$bench_no1 ";
        $jj = "select display from $schemas.bench_judge where display='true' and judge_code =?  and from_list_date=? and bench_no=? ";
        $jj = $db->prepare($jj);
        $jj->bindParam(1, $presiding_code, PDO::PARAM_STR);
        $jj->bindParam(2, $todate, PDO::PARAM_STR);
        $jj->bindParam(3, $bench_no1, PDO::PARAM_STR);
        $jj->execute();
        $jjj = $jj->fetchColumn();


        if ($judge_desg_code > 0) {
            $sql_desg = "select desg_name from $schemas.master_desg where desg_code=? and display=? ";
            $sth14 = $db->prepare($sql_desg);
            $display = 'TRUE';
            $sth14->bindParam(1, $judge_desg_code, PDO::PARAM_STR);
            $sth14->bindParam(2, $display, PDO::PARAM_STR);
            $sth14->execute();
            $judge_desg = $sth14->fetchColumn();
        }
    }
    $case_exist = 0;
    $case_count = "select count(*) as count  from $schemas.case_allocation where listing_date='$todate'
 and bench_no='$bench_code1' and court_no='$court_no'";
    $sth15 = $db->prepare($case_count);
    $sth15->execute();
    $case_exist = $sth15->fetchColumn();
        if ($case_exist > 0) {
            $tem_mani_array['court_no'] = $court_no;
            $tem_mani_array['corem'] = 'CORAM';
            list($day, $month, $year) = explode('/', $nd);
            $fd = $year . '-' . $month . '-' . $day;

            $stat = "select * from $schemas.bench where  bench_nature ='$listbefore' and from_list_date='$listdate_entire' and  bench_no='$bench_code1'";
            $stat = $db->prepare("$stat");
            //$stat->bindParam(1, $display, PDO::PARAM_STR);
            $stat->execute();
            while ($row = $stat->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                $from_time = $row['from_time'];
                $bench_remarks = $row['detail'];
                $bench_no = $row['bench_no'];
                $court_no = $row['court_no'];
                $presiding = $row['presiding'];
                $stat1 = "select *  from $schemas.master_judge where judge_code ='$presiding'";
                $stat1 = $db->prepare($stat1);
                $stat1->execute();
                $presiding1 = $stat1->fetch();
                $gen = $presiding1['gen'] . "&nbsp";
                $judge_name = $presiding1['judge_name'];
                $hon_text = $presiding1['hon_text'];
                $stat1 = "select desg_name from $schemas.master_desg where desg_code =?";
                $stat1 = $db->prepare($stat1);
                $stat1->bindParam(1, $presiding1[judge_desg_code], PDO::PARAM_STR);
                $stat1->execute();
                 $judge_details1 = $gen . ' ' . $judge_name . ", " . $hon_text . " " . $desg_name = $stat1->fetchColumn();
            }
            $tem_mani_array['judge_details1'] = $judge_details1;
            $stat2 = "select distinct(judge_code) as judge_code from $schemas.bench_judge where bench_nature='$listbefore' and from_list_date='$listdate_entire' and judge_code !='$presiding' and bench_no='$bench_code1'";
            $stat2 = $db->prepare("$stat2");
            $stat2->execute();
            while ($row2 = $stat2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

                $judge_code = $row2['judge_code'];
                $stat1 = "select judge_name,judge_desg_code,gen,hon_text from $schemas.master_judge where judge_code =?";
                $stat1 = $db->prepare($stat1);
                $stat1->bindParam(1, $judge_code, PDO::PARAM_STR);
                $stat1->execute();
                $judge_data = $stat1->fetch();
                $gen = $judge_data['gen'];
                $hon_text = $judge_data['hon_text'];
                $judge_name = $judge_data['judge_name'];
                $desg_code = $judge_data['judge_desg_code'];
                $stat1 = "select desg_name from $schemas.master_desg where desg_code =?";
                $stat1 = $db->prepare($stat1);
                $stat1->bindParam(1, $desg_code, PDO::PARAM_STR);
                $stat1->execute();
                $judge_details2 = $gen . " " . $judge_name . ", " . $hon_text . " " . $desg_name = $stat1->fetchColumn();
            }
            $tem_mani_array['judge_details2'] = $judge_details2;
            $tem_mani_array['bench_remarks'] = $bench_remarks;


        }
    if ($b_nature > 0) {
        $sql_purpose = "select distinct(purpose), priority from $schemas.bench_purpose_priority where
from_date='$todate' and  bench_no='$bench_code1'  and 
bench_nature='$b_nature' order by priority ASC";

        $sth_j12 = $db->prepare($sql_purpose);
        $sth_j12->execute();
        $court_nono;
        $ppp = 0;
        $pp = 0;
        while ($row = $sth_j12->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $temp_purpose = array();
            $purpose_code = $row['purpose'];
            $serial_no = 1;
            if ($purpose_code > 0) {
                $sql_count = "select count(*) as count from $schemas.case_allocation where purpose='$purpose_code' and 
listing_date='$todate' and bench_no='$bench_code1' and 
court_no='$court_nono'";
                $sth1r = $db->prepare($sql_count);
                $sth1r->execute();
                $count_filing = $sth1r->fetchColumn();
            }
            if ($count_filing > 0) {
                $sql_purpose_name = "select purpose_name from $schemas.master_purpose where 
purpose_code='$purpose_code' ";
                $sth4x = $db->prepare($sql_purpose_name);
//$sth4x->bindParam(1, $purpose_code, PDO::PARAM_STR);
                $sth4x->execute();
                $purpose_name = $sth4x->fetchColumn();
            }
            if ($purpose_name != '') {
                $temp_purpose['purpose_name'] = $purpose_name;
                $purpose_name = '';


                $count = 0;
                $countzz = 1;
                $sql_allocation = "select a.filing_no ,a.remarks  from $schemas.case_allocation a,
$schemas.case_detail d where d.status =? and  a.purpose=? and a.listing_date=?
and a.bench_no=? and a.court_no=?  and
a.filing_no=d.filing_no order by  a.priority_serial,d.case_no,d.case_year asc";

                $status = 'P';
                $sth_j12c = $db->prepare($sql_allocation);
                $sth_j12c->bindParam(1, $status, PDO::PARAM_STR);
                $sth_j12c->bindParam(2, $purpose_code, PDO::PARAM_STR);
                $sth_j12c->bindParam(3, $todate, PDO::PARAM_STR);
//$sth_j12c->bindParam(4, $list_flag, PDO::PARAM_STR);
                $sth_j12c->bindParam(4, $bench_code1, PDO::PARAM_STR);
                $sth_j12c->bindParam(5, $court_nono, PDO::PARAM_STR);
                $sth_j12c->execute();

                while ($row1 = $sth_j12c->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

                    $temp_purpose_details = array();
                    $filing_no = $row1['filing_no'];

                    $hc_dc_caseno = $row1['hc_dc_caseno'];
                    $remarkss = $row1['remarks'];

                    $sql_cd = "select a.pet_type,a.location_code,a.res_type,a.legal_aid ,
a.oa_ref_no,a.case_no, a.case_type,a.case_year, a.pet_name,a.res_name,a.pet_adv_name,
a.res_adv_name from $schemas.case_detail as a  where a.filing_no=?
and status =? order by case_type,case_no ASC";

                    $status = 'P';
                    $sth_j12cc = $db->prepare($sql_cd);
                    $sth_j12cc->bindParam(1, $filing_no, PDO::PARAM_STR);
                    $sth_j12cc->bindParam(2, $status, PDO::PARAM_STR);
                    $sth_j12cc->execute();

                    while ($row2 = $sth_j12cc->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                        $count++;
                        $oa_ref_no = $row2['oa_ref_no'];

                        if ($oa_ref_no != '') {
                            $ref_newst31 = "select case_type,case_no,case_year,location_code from $schemas.case_detail where filing_no='$oa_ref_no' order by case_type asc";
                            $ref_newst31 = $db->prepare($ref_newst31);
                            $ref_newst31->execute();
                            $ref_resultset1 = $ref_newst31->fetch();
                            extract($ref_resultset1);

                            $ref_lcode = "select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
                            $ref_lcode = $db->prepare($ref_lcode);
                            $ref_lcode->execute();
                            $ref_lcodename = $ref_lcode->fetchColumn();
                            if ($case_type > 0) {
                                $ref_stQ = $db->prepare("select short_name from case_type where id = ?");
                                $ref_stQ->bindParam(1, $case_type, PDO::PARAM_STR);
                                $ref_stQ->execute();
                                $ref_case_type_short_name = $ref_stQ->fetchColumn();
                            }


                            $ref_case_numaa = $case_no;
                            $ref_case_year1aa = $case_year;
                            $ref_case_num1aa = ltrim($case_numaa, 0);

                            $ref_CASE_NO = htmlspecialchars(strtoupper($ref_case_type_short_name) . '/' . $ref_case_num1aa . '(' . $ref_lcodename . ')' . $ref_case_year1aa);


                        }


                        $case_no = $row2['case_no'];
                        $case_type = $row2['case_type'];
                        $case_year = $row2['case_year'];
                        $pet_name = $row2['pet_name'];
                        $res_name = $row2['res_name'];
                        $location_code = $row2['location_code'];

                        $lcode = "select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
                        $lcode = $db->prepare($lcode);
                        $lcode->execute();
                        $lcodename = $lcode->fetchColumn();
                        $noaddfilingno = $row2['filing_no'];
                        if ($case_type > 0) {
                            $stQ = $db->prepare("select short_name from case_type where id = ?");
                            $stQ->bindParam(1, $case_type, PDO::PARAM_STR);
                            $stQ->execute();
                            $case_type_short_name = $stQ->fetchColumn();
                        }
                        $case_numaa = $case_no;
                        $case_year1aa = $case_year;
                        $case_num1aa = ltrim($case_numaa, 0);

                        $CASE_NO = htmlspecialchars(strtoupper($case_type_short_name) . '/' . $case_num1aa . '(' . $lcodename . ')' . $case_year1aa);
                        $temp_purpose_details['sr_no'] = $sr_no;
                       // include '../db_inc2.php';
                        $stqq = $dbo->prepare("select count(filing_no) from e_case_detail  where filing_no=?");
                        $stqq->bindParam(1, $filing_no, PDO::PARAM_INT);
                        $stqq->execute();
                        $filing_norevari = $stqq->fetchColumn();

                        if ($filing_norevari > '0') {
                            $sthr = $dbo->prepare("select * from e_case_detail  where filing_no=? ");
                            $sthr->bindParam(1, $filing_no, PDO::PARAM_STR);
                            $sthr->execute();
                            while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                                $fileupload = $rowa['unique_id_no'];
                            }
                            $serial_no = $sr_no - 1;
                            $dfdfdfd = '';
                            $temp_purpose_details['case_no'] = $CASE_NO;
                        }
                        if ($ref_CASE_NO != '') {
                            $temp_purpose_details['ref_CASE_NO'] = 'In ' . $CASE_NO;
                        }
                        $st2 = $dbo->prepare("select * from e_case_detail_fees where filing_no=? ");
                        $st2->bindParam(1, $filing_no, PDO::PARAM_STR);
                        $st2->execute();
                        $i = 0;
                        $r = '';
                        while ($row2 = $st2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                            $E_sec_id = $row2['sec_id'];

                            $st3 = $dbo->prepare("select * from master_section_act where id=? ");
                            $st3->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                            $st3->execute();

                            while ($row3 = $st3->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                                $E_add_sec_id = $row3['section_companies'];
                                $r .= $E_add_sec_id . ',';
                            }
                        }

                        $temp_purpose_details['scetion'] = rtrim($r, ',');

                        $name_of_parties = '';
                        if ($case_type == '14' || $case_type == '15') {
                            $counter2 = 2;
                            if ($counter2 == '2') {
                                $counter2 = $counter2 + 1;
                            }
                            $dit = "And";
                            $party_ser = '1';
                            $part_flag = 'P';
                            //$sql="select * from e_cases_party where filing_no='$filing_no' and party_serial_no!='$party_ser' and party_flag='$part_flag'";
                            $st22 = $dbo->prepare("select * from e_cases_party where filing_no=? and party_serial_no!=? and party_flag=?");
                            $st22->bindParam(1, $filing_no, PDO::PARAM_STR);
                            $st22->bindParam(2, $party_ser, PDO::PARAM_STR);
                            $st22->bindParam(3, $part_flag, PDO::PARAM_STR);
                            $st22->execute();
                            $counter = 1;
                            while ($row22 = $st22->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                                $pet_party = $row22['name'];
                                if ($counter == '1' && $counter2 == '2') {
                                    $name_of_parties = $pet_name . $dit . $pet_party;
                                    $counter = $counter + 1;
                                } else {
                                    $name_of_parties = $dit . $pet_party;
                                }
                            }
                        } else {
                            $dit = "Vs";
                            $name_of_parties = $pet_name . $dit . $res_name;
                        }
                        $temp_purpose_details['name_of_parties'] = $name_of_parties;
                        $st12 = $dbo->prepare("select * from e_more_representative where filing_no=? and party_flag='P' and display='t'");
                        $st12->bindParam(1, $filing_no, PDO::PARAM_STR);

                        $st12->execute();
                        while ($row12 = $st12->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                            $adv_id = htmlspecialchars($row12['rep_code']);
                            $stqq12 = $dbo->prepare("select rep_name from e_master_advocate where id=?");
                            $stqq12->bindParam(1, $adv_id, PDO::PARAM_INT);
                            $stqq12->execute();
                            $pet_advname22 = $stqq12->fetchColumn();

                            $temp_purpose_details['advocat_name'][] = $pet_advname22;
                        }
                        $st121 = $dbo->prepare("select * from e_more_representative where filing_no=? and party_flag='R' and display='t'");
                        $st121->bindParam(1, $filing_no, PDO::PARAM_STR);

                        $st121->execute();
                        while ($row121 = $st121->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

                            $res_adv_id = htmlspecialchars($row121['rep_code']);
                            $stqq121 = $dbo->prepare("select rep_name from e_master_advocate where id=?");
                            $stqq121->bindParam(1, $res_adv_id, PDO::PARAM_INT);
                            $stqq121->execute();
                            $res_advname22 = $stqq121->fetchColumn();
                            $temp_purpose_details['advocat_name'][] = $res_advname22;
                        }
                        $temp_purpose['purpose_deatils'][] = $temp_purpose_details;
                        $sr_no++;
                    }
                }
                $tem_mani_array['purpose'][$ppp] = $temp_purpose;
                $ppp++;
            }
            $pp++;
        }
    }
    $main_array['court_history'][$mm_ar] = $tem_mani_array;
    $mm_ar++;
}
$msg =  json_encode($main_array);
?>


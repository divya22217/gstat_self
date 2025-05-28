<?php

class Causelist
{
    public $causelistTiltle;
    public $causelistTable;
    public $causelistType;
    public $courtNo;
    public $listdate;
    public $corum = 'CORUM';
    public $causelistRegion;
    public $sNo;
    public $v1;

    public function setCauselistTitle($ctitle)
    {
        $this->causelistTiltle = $ctitle;
    }
    public function getCauselistTitle()
    {
        return $this->causelistTiltle . "<br/>";
    }

    public function setTable($table)
    {
        $this->causelistTable = $table;
    }
    public function getTable()
    {
        return $this->causelistTable;
    }

    public function setCauselistregion($cregion)
    {
        $this->causelistRegion = $cregion;
    }
    public function getCauselistregion()
    {
        return $this->causelistRegion . "<br/>";
    }

    public function setCauselistType($ctype)
    {
        if ($ctype == 'D') {
            $ctype = 'DAILY CAUSE LIST';
        } else if ($ctype == 'S') {
            $ctype = 'SUPPLEMENTRY CAUSE LIST';
        }
        $this->causelistType = $ctype;
    }

    public function getCauselistType()
    {
        return $this->causelistType . "<br/>";
    }

    public function setCourtno($court_no)
    {
        $this->courtNo = $court_no;
    }
    public function getCourtno()
    {
        return $this->courtNo;
    }
    public function setCourtdate($listdate)
    {
        list($day, $month, $year) = explode('/', $listdate);
        $this->listdate = $day . '.' . $month . '.' . $year;
    }
    public function getCourtdate()
    {
        return $this->listdate;
    }

    public function getpJudges($schemas, $db, $listdate_entire, $bench_code1, $court_no, $table)
    {
        $results = array();
        $stat = "select * from $schemas.bench b, $schemas.$table t where b.from_list_date='$listdate_entire' and  b.bench_no='$bench_code1' and t.listing_date='$listdate_entire' and t.bench_no='$bench_code1' order by b.bench_no asc";
        $stat = $db->prepare($stat);
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
            $gen = $presiding1['gen'] . "&nbsp"; //echo
            $presjudgename = $presiding1['judge_name']; //echo
            $hon_text = $presiding1['hon_text'];
            $stat1 = "select desg_name from $schemas.master_desg where desg_code =?";
            $stat1 = $db->prepare($stat1);
            $stat1->bindParam(1, $presiding1['judge_desg_code'], PDO::PARAM_STR);
            $stat1->execute();
            $desg_name = $stat1->fetchColumn(); //echo

            $results[] = array(
                "pjudgename" => $gen . $presjudgename . ", " . $hon_text . " " . $desg_name,
                "presiding" => $presiding,
            );
        }
        return $results;
    }

    public function getallJudges($schemas, $db, $listdate_entire, $bench_code1, $court_no, $presiding, $table)
    {
        $results = array();
        if ($presiding == '') {
            $presiding = 0;
        }

        $stat2 = "select distinct(b.judge_code) as judge_code, b.bench_no from $schemas.bench_judge b, $schemas.$table t where b.from_list_date='$listdate_entire' and b.judge_code !='$presiding' and b.bench_no='$bench_code1' and t.listing_date='$listdate_entire' and t.bench_no='$bench_code1' order by b.bench_no asc";

        $stat2 = $db->prepare($stat2);
        //$stat->bindParam(1, $display, PDO::PARAM_STR);
        $stat2->execute();
        $results = array();
        while ($row2 = $stat2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

            $judge_code = $row2['judge_code'];
            $stat1 = "select judge_name,judge_desg_code,gen,hon_text from $schemas.master_judge where judge_code =?";
            $stat1 = $db->prepare($stat1);
            $stat1->bindParam(1, $judge_code, PDO::PARAM_STR);
            $stat1->execute();
            $judge_data = $stat1->fetch();
            $gen = $judge_data['gen'];
            $hon_text = $judge_data['hon_text'];

            $judge_name = $judge_data['judge_name']; //echo
            $desg_code = $judge_data['judge_desg_code'];
            $stat1 = "select desg_name from $schemas.master_desg where desg_code =?";
            $stat1 = $db->prepare($stat1);
            $stat1->bindParam(1, $desg_code, PDO::PARAM_STR);
            $stat1->execute();
            $desg_name = $stat1->fetchColumn();

            $results[] = array(
                "alljudgesname" => $gen . " " . $judge_name . ", " . $hon_text . " " . $desg_name,
                "desg_code" => $desg_code,
            );
        }
        return $results;
    }

    public function get_incase_type($in_filing_no, $db, $schemas)
    {
        $get_incase_type_sql = "select case_type from $schemas.case_detail where filing_no=? and case_no!=''";
        $get_incase_type = $db->prepare($get_incase_type_sql);
        $get_incase_type->bindParam(1, $in_filing_no, PDO::PARAM_STR);
        $get_incase_type->execute();
        return $get_incase_type->fetchColumn();
    }

    public function get_case_no($filing_no, $db, $schemas)
    {
        $query = "select ct.short_name||'/'||cd.case_no||'('||mlc.short_name||')/'||cd.case_year from $schemas.case_detail as cd
        inner join mater_location_city as mlc on mlc.city_id = cd.location_code
        inner join case_type as ct on ct.id = cd.case_type where filing_no = ?";
        $get_case_detail = $db->prepare($query);
        $get_case_detail->bindParam(1, $filing_no, PDO::PARAM_STR);
        $get_case_detail->execute();
        $case_number = $get_case_detail->fetchColumn();
        return $case_number;

    }

    public function getSections($filing_no, $dbonline)
    {
        $st2 = $dbonline->prepare("select * from e_case_detail_fees where filing_no=? ");
        $st2->bindParam(1, $filing_no, PDO::PARAM_STR);
        $st2->execute();
        $i = 0;
        $r = '';
        while ($row2 = $st2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $E_sec_id = $row2['sec_id'];

            $st3 = $dbonline->prepare("select * from master_section_act where id=? ");
            $st3->bindParam(1, $E_sec_id, PDO::PARAM_STR);
            $st3->execute();

            while ($row3 = $st3->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                $E_add_sec_id = $row3['section_companies'];
                $r .= $E_add_sec_id . ',';
            }

        }
        return rtrim($r, ',');
    }

    public function getPurpose($todate, $bench_code1, $court_nono, $purpose_code, $db, $schemas)
    {

        $sql_purpose_name = "select purpose_name from $schemas.master_purpose where purpose_code='$purpose_code' ";
        $sth4x = $db->prepare($sql_purpose_name);
        $sth4x->execute();
        return $purpose_name = $sth4x->fetchColumn();

    }

    public function setSno($sno)
    {
        $this->sNo = $sno;
    }

    public function setV1($v1)
    {
        $this->v1 = $v1;
    }

    public function getSno($purpose, $v1)
    {
        if ($purpose == 12) {
            return $this->sNo++;
        } else {
            return $this->v1++;
        }
    }

    public function roundNearestHundredUp($number)
    {
        return ceil($number / 100) * 100;
    }

    public function getPetname($filing_no, $schemas, $db, $dbonline)
    {

        $sql_cd = "select a.pet_type,a.location_code,a.res_type,a.legal_aid , a.oa_ref_no,a.case_no, a.case_type,a.case_year, a.pet_name,a.res_name,a.pet_adv_name, a.res_adv_name from $schemas.case_detail as a  where a.filing_no=? and case_no!='NULL' order by case_type,case_no ASC";
        $sth_j12cc = $db->prepare($sql_cd);
        $sth_j12cc->bindParam(1, $filing_no, PDO::PARAM_STR);
        $sth_j12cc->execute();

        while ($row2 = $sth_j12cc->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $pet_name = $row2['pet_name'];
            if ($pet_name) {
                return $pet_name;
            } else {
                return;
            }

        }
    }

    public function getPetParty($filing_no, $dbonline)
    {
        $party_ser = '1';
        $part_flag = 'P';
        $st22 = $dbonline->prepare("select * from e_cases_party where filing_no=? and party_serial_no!=? and party_flag=?");
        $st22->bindParam(1, $filing_no, PDO::PARAM_STR);
        $st22->bindParam(2, $party_ser, PDO::PARAM_STR);
        $st22->bindParam(3, $part_flag, PDO::PARAM_STR);
        $st22->execute();

        $pet_party = array();
        while ($row22 = $st22->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $pet_partyn = $row22['name'];
            if ($pet_partyn) {
                $pet_party[] = $pet_partyn;
            } else {
                return;
            }

        }
        return $pet_party;
    }

    public function getResname($filing_no, $schemas, $db, $dbonline)
    {

        $sql_cd = "select a.pet_type,a.location_code,a.res_type,a.legal_aid , a.oa_ref_no,a.case_no, a.case_type,a.case_year, a.pet_name,a.res_name,a.pet_adv_name, a.res_adv_name from $schemas.case_detail as a  where a.filing_no=? and case_no!='NULL' order by case_type,case_no ASC";
        $sth_j12cc = $db->prepare($sql_cd);
        $sth_j12cc->bindParam(1, $filing_no, PDO::PARAM_STR);
        $sth_j12cc->execute();

        while ($row2 = $sth_j12cc->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $res_name = $row2['res_name'];
            if ($res_name) {
                return $res_name;
            } else {
                return '';
            }

        }
    }

    public function getFileupload($filing_no, $dbonline, $case_type)
    {
        if ($case_type == 4) {
            $sthr = $dbonline->prepare("select filing_no from e_ia_details  where ia_filing_no=? ");
            $sthr->bindParam(1, $filing_no, PDO::PARAM_STR);
            $sthr->execute();
            $filing_no = $sthr->fetchColumn();
        }
        $fileupload = '';
        $stqq = $dbonline->prepare("select unique_id_no from e_case_detail  where filing_no=?");
        $stqq->bindParam(1, $filing_no, PDO::PARAM_INT);
        $stqq->execute();
        $data = $stqq->fetchAll();
        if (!empty($data) && is_array($data)) {
            $fileupload = $data['0']['unique_id_no'];
        }
        return $fileupload;
    }

    public function getProceedingDetails($filing_no, $schemas, $db, $todate, $bench_code1)
    {
        $st1 = "select am.action_type from $schemas.case_proceeding as cp INNER JOIN $schemas.master_action as am ON cp.filing_no='$filing_no' and cp.todays_action=am.action_code and cp.listing_date='$todate' and cp.bench_no='$bench_code1'";
        $sth = $db->prepare($st1);
        $sth->execute();
        $action_name = $sth->fetchColumn();

        if ($action_name == '') {
            $chk_status = '';
            $sql_pr = "select status from $schemas.case_detail where filing_no='$filing_no'";
            $sthc12 = $db->prepare($sql_pr);
            $sthc12->execute();
            $chk_status = $sthc12->fetchColumn();

            if ($chk_status == 'D') {
                $dis_date = '';
                $dis_date_sql = "select disposal_date,disposal_nature from $schemas.case_disposal where filing_no=?";
                $dis_date_sql = $db->prepare($dis_date_sql);
                $dis_date_sql->bindParam(1, $filing_no, PDO::PARAM_STR);
                $dis_date_sql->execute();
                $dis_date_res = $dis_date_sql->fetch();
                $dis_date = $dis_date_res['disposal_date'];
                $dis_nat_code = $dis_date_res['disposal_nature'];

                list($year, $month, $day) = explode('-', $dis_date);
                $dis_date = "($day/$month/$year)";

                $dis_nat_name = '';
                $tod_act_disn_sql = "select action_type from $schemas.master_action where action_code='$dis_nat_code' and display='TRUE'";
                $tod_act_disn_sql = $db->prepare($tod_act_disn_sql);
                $tod_act_disn_sql->execute();
                $dis_nat_name = $tod_act_disn_sql->fetchColumn();
                return $dis_nat_name . "<br>" . $dis_date;
            }
        } else {

            $next_date = '';
            $st_date = "select next_list_date from $schemas.case_proceeding where filing_no='$filing_no' and listing_date='$todate' and bench_no='$bench_code1'";
            $sth1 = $db->prepare($st_date);
            $sth1->execute();
            $next_date = $sth1->fetchColumn();

            $display_data = '';
            $display_data1 = '';
            if ($next_date == '' || $next_date == '1111-11-11') {
                $display_data1 = $action_name;
            } else {
                list($year, $month, $day) = explode('-', $next_date);
                $display_data = "($day/$month/$year)";
            }
            if ($action_name != '' and ($display_data1 != '') || ($display_data != '')) {
                return $action_name . "<br>" . $display_data;
            }
        }
    }

    public function getOrderDetails($filing_no, $schemas, $db, $todate, $bench_code1)
    {
        $st33 = $db->prepare("select * from $schemas.order_daily where filing_no='$filing_no' and order_date='$todate' and bench_no='$bench_code1'");
        $st33->execute();
        $count = $st33->rowCount();
        if ($count == 0) {
            return;
        } else {
            return 1;
        }
    }

    public function fn_case_no_data($dbonline, $arr_data, $snoc, $courtnoc, $petnamec, $resnamec)
    {
        if (!empty($arr_data) && is_array($arr_data)) {
            foreach ($arr_data as $value) {
                if ($value['case_no'] != '') {
                    $fileupload = $this->getFileupload($value['fil_no'], $dbonline, $value['incase_type']);
                    echo "<br>";
                    $this->fn_url_link($snoc, $fileupload, $courtnoc, $value['case_no'], $petnamec, $resnamec);
                }
            }

        }

    }

    public function fn_url_link($snoc, $fileupload, $courtnoc, $case_no, $petnamec, $resnamec)
    {

        ?>  <br>
           <a onclick="OpenDMSForm('http://efiling.nclt.gov.in/dms-ecourt/ecourt-search-within-dms','<?php echo $snoc; ?>','<?php echo $fileupload; ?>','<?php echo $courtnoc; ?>','<?php echo $case_no; ?>','','<?php echo $petnamec ?>','<?php echo $petnamec . "   "; ?>Vs.<?php echo "   " . $resnamec; ?>','P','vVl/Az1yGsjOAG18WDeScg==','!TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip/Q8Wns=')"
                style="cursor: pointer">

                <font color="#900C3F" face="verdana" size="2">
                    <?php echo '<b>' . $case_no . '</b>'; ?>
                </font>
            </a>


            <?php
}

}

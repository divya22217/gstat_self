<?php




function timelineCases($dbonline, $location_id, $payment_accept, $filing_no, $app_pet, $limit = null, $from_date, $to_date, $start_from = null, $filing_no_yes = null, $date_range = null, $tab_type = null)
{
    $cav_type = 60;
    $cis_user_id = $_SESSION['id'];
    $date_range1 = '';
    if ($date_range != null) {
        $date = explode('/', $date_range);
        $date = $date[2] . '-' . $date['1'] . '-' . $date[0];
        if ($tab_type != null && $tab_type == 'cases_pending') {
            $date_range1 = "and  dt_of_filing between '1990-01-01 00:00:00' and '$date' ";
        } else if ($tab_type != null && $tab_type == 'filed_cases') {
            $date_range1 = "and  dt_of_filing = '$date' ";
        } else {
            $date_range1 = "and  dt_of_filing = '$date' ";
        }
    }

    if (!empty($from_date) and !empty($to_date)) {
        $filter_date = "and (dt_of_filing::timestamp::date between '$from_date' and '$to_date')";
    }

    $filing_check = '';
    if ($filing_no_yes != null || $filing_no_yes != '') {
        $filing_check = ' and cd.filing_no = ' . "'$filing_no_yes'";
    }

    $limit_1 = '';
    if ($limit != null) {
        $limit_1 = 'limit ' . $limit . ' OFFSET ' . $start_from;
    }
    $case_type = '';
    if ($app_pet == '') {
        $case_type = "";
    } else {
        $case_type = " AND case_type_nclat  = '$app_pet'";
    }
    $zero = 0;
    $is_return = 0;

    //     $query_q = "SELECT cd.filing_no,cd.dt_of_filing,cd.case_type,cd.case_title,cd.case_type_nclat,cd.filingnumberia,cd.subjectia,cd.casetypeiacontempt,cd.contempt_case_year,cd.contempt_case_no,cd.gst_casetransfer_level,cd.cis_user_id,cd.court,
    //     od.filing_through,od.apl0402_not_found
    //     FROM public.e_case_detail cd
    //     LEFT JOIN public.e_order_details od  ON od.filing_no=cd.filing_no
    //     WHERE cd.location_id='$location_id' AND cd.payment_accept='$payment_accept' AND cd.filing_no !='$filing_no' AND LENGTH(cd.filing_no) = 16 AND cd.back_log='N' 
    //     AND cd.scrutiny_level = '$zero' AND cd.is_defective ='$zero' AND cd.is_refiled = '$zero' AND cd.case_type_nclat !='$cav_type' 
    //     AND (cis_user_id='$cis_user_id' OR cd.cis_user_id IS NULL)  AND cd.is_return = '$is_return'
    //     $case_type
    //     $filing_check
    //     $date_range1";
    //     if (isset($filter_date) && !empty($filter_date)) {
    //         $query_q .= $filter_date;
    //     }
    //     $query_q .= "order by dt_of_filing $limit_1";


    //    echo $query_q;

    //  AND (cis_user_id=? OR cd.cis_user_id IS NULL)
    $query_q = "SELECT cd.filing_no,cd.dt_of_filing,cd.case_type,cd.case_title,cd.case_type_nclat,cd.filingnumberia,cd.subjectia,cd.casetypeiacontempt,cd.contempt_case_year,cd.contempt_case_no,cd.gst_casetransfer_level,cd.cis_user_id,cd.court,
    od.filing_through,od.apl0402_not_found
    FROM public.e_case_detail cd
    LEFT JOIN public.e_order_details od  ON od.filing_no=cd.filing_no
    WHERE cd.location_id=? AND cd.payment_accept=? AND cd.filing_no !=? AND LENGTH(cd.filing_no) = 16 AND cd.back_log='N' 
    AND cd.scrutiny_level = ? AND cd.is_defective = ? AND cd.is_refiled = ? AND cd.case_type_nclat != ? 
    AND cd.is_return = ?
    $case_type
    $filing_check
    $date_range1";
    if (isset($filter_date) && !empty($filter_date)) {
        $query_q .= $filter_date;
    }
    $query_q .= "order by dt_of_filing $limit_1";



    //echo $query_q; 
    try {
        $query = $dbonline->prepare($query_q);
        $query->bindParam(1, $location_id, PDO::PARAM_STR);
        $query->bindParam(2, $payment_accept, PDO::PARAM_STR);
        $query->bindParam(3, $filing_no, PDO::PARAM_STR);
        $query->bindParam(4, $zero, PDO::PARAM_STR);
        $query->bindParam(5, $zero, PDO::PARAM_STR);
        $query->bindParam(6, $zero, PDO::PARAM_STR);
        $query->bindParam(7, $cav_type, PDO::PARAM_STR);
        // $query->bindParam(8, $cis_user_id, PDO::PARAM_STR);
        $query->bindParam(8, $is_return, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function getDateTimeline($db, $schemas, $days, $date)
{
    $query = "WITH RECURSIVE working_days AS (
        SELECT '$date'::date AS check_date,
               0 AS working_day_count  
        UNION ALL
        SELECT (check_date + INTERVAL '1 day')::DATE, 
               working_day_count + CASE 
                                      WHEN (check_date + INTERVAL '1 day') IN (SELECT holiday_date FROM delhi.holidays)
                                      THEN 0 
                                      ELSE 1
                                    END
        FROM working_days
        WHERE working_day_count < $days 
    )
    SELECT TO_CHAR(check_date, 'YYYY-MM-DD')
    FROM working_days
    WHERE working_day_count = $days
    LIMIT 1";
    $next_date_query = $db->prepare($query);
    $next_date_query->execute();
   return $next_date_query->fetchColumn();
}

function checkDateTime($db, $schemas, $days, $date)
{
    $currenDate  = date('Y-m-d');
    $next_date =  getDateTimeline($db, $schemas, $days, $date);
    //  return $next_date;
    $return = '';
    $username = $_SESSION['menuaccess_codeall'];
    $uesre_name = 'AR/DR/JR/R';
    if($username == 11) { 
   $uesre_name = 'Member';
    }
    //echo ($currenDate) .'>=.'. ($next_date);
    
    if (strtotime($currenDate) >= strtotime($next_date)) {
        $return = "<br><span style='display: inline-block;
    background: #ee0a0a;
    color: #fff;
    font-size: 13px;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 5px;
    margin-top: 15px;
    border: 1px solid #f9d5d5;
    border-bottom: 1px solid #736363;'>Case delay notification forwarded to $uesre_name </span>";
    }
    return $return;
}


function fn_document_ar_sc($dbonline, $menuaccess_codeall, $dash_yes, $limit = null, $start_from = null)
{
    if ($menuaccess_codeall == '2') {
        $doc_level = '';
        $doc_level_done = null;
    }
    $scrutiny_where = '';
    if ($menuaccess_codeall == '11') {
        $doc_level = '11';
        $doc_level_done = '22';
        $scrutiny_value = '0';
        $display = '1';
        if ($dash_yes == '1') {
            $scrutiny_where .= ' and scrutiny = ' . "'$scrutiny_value'" . ' and  display = ' . "'$display'";
        }
    }

    $limit_1 = '';
    if ($limit != null) {
        $limit_1 = 'limit ' . $limit . ' OFFSET ' . $start_from;
    }
    try {
        $query = $dbonline->prepare("select distinct(miscellaneous_ref_no) as miscellaneous_ref_no,filing_no,scrutiny,display,cast(filing_no as BIGINT) as filing_noaaa from document_upload  where
        (doc_level=? or doc_level=?) and miscellaneous_ref_no IS NOT NULL and party_type NOT IN (select party_flag from e_master_govt_body)  $scrutiny_where order by filing_noaaa desc $limit_1 ");
        $query->bindParam(1, $doc_level, PDO::PARAM_STR);
        $query->bindParam(2, $doc_level_done, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function fn_ia_ar_fresh_cases($db, $schemas, $limit = null, $start_from = null)
{
    $status = '111';
    try {
        $query = $db->prepare("select * from $schemas.scrutiny_ia where level_level=?");
        $query->bindParam(1, $status, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function defect_free_cases_for_note($db, $schemas, $user_court, $from_date, $to_date, $type = 'main')
{
    $defects = 'N';
    $icna = 0;
    $one = 1;
    $display = TRUE;
    if (!empty($from_date) and !empty($to_date)) {
        $filter_date = "and (ecd.dt_of_filing between ? and ?)";
    }

    if (!empty($user_court))
        $user_court_query = " and ecd.court = '$user_court'";

    if ($type == 'main') {
        $query =     "select s.filing_no,ecd.case_type,ecd.dt_of_filing,ecd.case_title,ecd.case_type_nclat,ecd.filingnumberia,ecd.subjectia,ecd.patially_defective,ct.case_type_desc as case_type_desc_cis , ecd.list_with_defect
					from $schemas.scrutiny as s 
					inner join e_case_detail ecd on ecd.filing_no = s.filing_no
					left join case_type ct on ct.id= ecd.case_type_nclat
					where ecd.display = ? and ecd.scrutiny_level = 2 and ecd.case_no_generated = ? and ecd.summary_note = 1 $user_court_query";
        if (isset($filter_date) && !empty($filter_date)) {
            $query .= $filter_date;
        }
        $query .= " order by s.notification_date desc";
    }
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $display, PDO::PARAM_STR);
    $stmt->bindParam(2, $icna, PDO::PARAM_STR);
    if (isset($filter_date) && !empty($filter_date)) {
        $stmt->bindParam(3, $from_date, PDO::PARAM_STR);
        $stmt->bindParam(4, $to_date, PDO::PARAM_STR);
    }
    $stmt->execute();
    $res = $stmt->fetchAll();
    return $res;
}

function fn_scrutiny_correction($dbonline, $filing_no)
{

    $query = $dbonline->prepare("select scrutiny_status from e_case_detail where filing_no=? ");
    $query->bindParam(1, $filing_no, PDO::PARAM_STR);

    $query->execute();
    $sc_correc = $query->fetchColumn();
    return $sc_correc;
}

function fn_case_type_counter($db, $schemas, $case_year, $case_status, $act_id)
{
    $where_custom = '';
    if ($case_status != '' && $case_status != 'All') {
        $where_custom .= " and status = " . "'$case_status'";
    }
    $where_act_id = '';
    if ($act_id != '' && $act_id != 'All') {
        $where_act_id .= " and act_id = " . "'$act_id'";
    }
    try {
        $query = $db->prepare("select case_type, count(case_type) as total_count from $schemas.case_detail
    where case_year  = ? $where_custom
    group by case_type order by case_type asc");
        $query->bindParam(1, $case_year, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        $data_val = array();
        if (!empty($data) && is_array($data)) {
            $total_registered = 0;
            foreach ($data as $val) {
                $query1 = $db->prepare("select case_type_desc_cis, short_name from case_type  where id  = ? $where_act_id ");
                $query1->bindParam(1, $val['case_type'], PDO::PARAM_STR);
                $query1->execute();
                $data_case = $query1->fetch();
                if ($data_case['case_type_desc_cis'] != '') {
                    $temp_arr = array();
                    $temp_arr['case_type'] = $val['case_type'];
                    $temp_arr['y'] = $val['total_count'];
                    $temp_arr['name'] = $data_case['case_type_desc_cis'];
                    $total_registered = $total_registered + $val['total_count'];
                    $temp_arr['total_count_aa'] = $total_registered;
                    $data_val[] = $temp_arr;
                }
            }
        }
        return $data_val;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function ar_fresh_cases($db, $dbonline, $schemas, $app_pet, $limit = null, $start_from = null)
{
    $status = '1';
    $scrut_comp3 = '3';
    $case_type = '';
    if ($app_pet == 'A') {
        $case_type = " and b.case_type  IN('13','5','6','8','10','11','12','18','20','21','22','24','26','27','28','31')";
    } else if ($app_pet == 'P') {
        $case_type = " and b.case_type  IN('2','3','7','9','16','1','14','15','19','23','25','29','30')";
    } else if ($app_pet == 'All') {
        $case_type = " and b.case_type  IN('2','3','7','9','16','1','14','15','19','23','25','29','30','13','5','6','8','10','11','12','18','20','21','22','24','26','27','28','31')";
    }

    try {
        $query = $db->prepare("select distinct(a.filing_no),a.notification_date,a.defects,a.filing_no,b.scrutiny_comp3, b.pet_name,b.res_name,b.dt_of_filing,b.dt_of_filing,b.case_type from delhi.scrutiny as a
        JOIN e_case_detail_local as b on a.filing_no = b.filing_no
        where a.level_level=? and  (b.scrutiny_comp3=? OR b.scrutiny_comp3 IS NULL)
        $case_type ");
        $query->bindParam(1, $status, PDO::PARAM_STR);
        $query->bindParam(2, $scrut_comp3, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
    } catch (PDOException $ex) {
        echo $ex;
    }

    return $data;
}

function case_type($db)
{
    $data_main = array();
    $st = $db->prepare("select * from case_type where status='t' order by id ASC");
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

function get_case_type($db, $status, $id)
{
    $data_main = array();
    $st = $db->prepare("select short_name from case_type where status=? and id = ?");
    $st->bindParam(1, $status, PDO::PARAM_STR);
    $st->bindParam(2, $id, PDO::PARAM_STR);
    $st->execute();
    $data = $st->fetchColumn();
    return $data;
}

function year_list($year)
{
    for ($i = date('Y'); $i >= 1970; $i--) { ?>
        <option <?php if ($year == $i) {
                    echo 'selected';
                } ?> value="<?php echo $i; ?>"><?php echo $i ?></option>
<?php }
}

function fn_getUserDetails($dbonline, $filing_no)
{
    try {
        $get_data = $dbonline->prepare("select c.mobilenumber,c.firstname,c.lastname,c.email,c.loginidgenerated,b.loginid,a.filing_no
        from public.e_case_detail as a
                 join public.loginmodel as b ON b.loginid = a.loginid
                 right join public.e_sign_up as c ON b.loginidgenerated = c.loginidgenerated
        where a.filing_no = ? limit 1");
        $get_data->bindParam(1, $filing_no, PDO::PARAM_STR);
        $get_data->execute();
        $case_data = $get_data->fetch();
        $name_data = '';
        if ($case_data['filing_no'] != '') {
            $name_data = ' <b>Name : </b> ' . $case_data['firstname'] . ' ' . $case_data['lastname'] . "<br> <b>Email :</b> " . $case_data['email'] . "<br> <b>Mobile No :</b> " . $case_data['mobilenumber'];
        }
        return $name_data;
    } catch (PDOException $ex) {
        return $ex;
    }
}



function fn_getlastListdateCourt_no($db, $schemas, $filing_no)
{
    try {
        $get_data = $db->prepare("select filing_no,listing_date,court_no from $schemas.case_allocation
        where filing_no = ? order by listing_date desc limit 1");
        $get_data->bindParam(1, $filing_no, PDO::PARAM_STR);
        $get_data->execute();
        $case_data = $get_data->fetch();
        $list_court_no = array();
        if ($case_data['filing_no'] != '') {
            $list_court_no = array('listing_date' => date('d/m/Y', strtotime($case_data['listing_date'])), 'court_no' => $case_data['court_no']);
            //' <b>Listing Date : </b> '.date('d/m/Y',strtotime($case_data['listing_date'])) . "<br> <b>Court No :</b> " . $case_data['court_no'];
        }
        return $list_court_no;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function fn_getCaseNo($db, $schemas, $filing_no)
{
    try {
        $get_data = $db->prepare("select c.case_type_desc_cis,b.short_name,a.case_year,a.case_no from $schemas.case_detail as a
        join $schemas.bench_location as b ON b.bench_location_code = a.location_code
        join case_type as c ON c.id = a.case_type
        where a.filing_no = ? order by a.dt_of_filing asc");
        $get_data->bindParam(1, $filing_no, PDO::PARAM_STR);
        $get_data->execute();
        $case_data = $get_data->fetch();
        $case_number = '';
        if ($case_data['case_no'] != '') {
            $case_number = $case_data['case_type_desc_cis'] . "/" . $case_data['case_no'] . "(" . $case_data['short_name'] . ")" . $case_data['case_year'];
        }
        return $case_number;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function document_defective_cases($db, $dbonline, $schemas, $limit = null, $start_from = null)
{
    $limit_1 = '';
    if ($limit != null) {
        $limit_1 = 'limit ' . $limit . ' OFFSET ' . $start_from;
    }

    $query_q = "select miscellaneous_ref_no,filing_no,id_sec_doc,defects,objection_status from $schemas.scrutiny_doc  where  objection_status='Y' and  defects='Y' and  level_level='22' and
   miscellaneous_ref_no is not null  and filing_no is not null
    order by notification_date desc $limit_1";
    try {
        $query = $db->prepare($query_q);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function ia_defective_cases($db, $dbonline, $schemas)
{
    $query_q = "select * from $schemas.scrutiny_ia  where  objection_status='Y' and  defects='Y' and  level_level='222' and
     ia_id is not null  and filing_no is not null
     order by notification_date desc";
    try {
        $query = $db->prepare($query_q);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function ia_cases($dbonline, $schemas, $date_range = null)
{
    $iscrutiny = 0;
    $ia_level = 0;
    $pstatus = 'TRUE';
    $date_range1 = '';
    if ($date_range != null) {
        $date = explode('/', $date_range);
        $date = $date[2] . '-' . $date['1'] . '-' . $date[0];
        $date_range1 = "and  dt_of_filing between '1990-01-01 00:00:00' and '$date' ";
    }
    $query_q = "select distinct(ia_filing_no) from e_ia_details where payment_status=? and
     scrutiny=? and doc_status=? and ia_filing_no IS NOT NULL and ia_filing_no!=''
     $date_range1
     order by ia_filing_no asc";
    try {
        $query = $dbonline->prepare($query_q);
        $query->bindParam(1, $pstatus, PDO::PARAM_STR);
        $query->bindParam(2, $iscrutiny, PDO::PARAM_STR);
        $query->bindParam(3, $ia_level, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}



function defective_cases($dbonline, $user_court, $location_id, $from_date, $to_date, $payment_accept, $filing_no, $app_pet, $limit = null, $start_from = null, $filing_no_yes = null, $date_range = null)
{
    $cav_type = 60;
    $filing_check = '';
    if ($filing_no_yes != null || $filing_no_yes != '') {
        $filing_check = ' and filing_no = ' . "'$filing_no_yes'";
    }

    $date_range1 = '';
    if ($date_range != null) {
        $date = explode('/', $date_range);
        $date = $date[2] . '-' . $date['1'] . '-' . $date[0];
        $date_range1 = "and  dt_of_filing between '1990-01-01 00:00:00' and '$date' ";
    }

    $limit_1 = '';
    if ($limit != null) {
        $limit_1 = 'limit ' . $limit . ' OFFSET ' . $start_from;
    }

    if (!empty($from_date) and !empty($to_date)) {
        $filter_date = "and (dt_of_filing::timestamp::date between '$from_date' and '$to_date')";
    }
    $case_type = '';
    if ($app_pet == '') {
        $case_type = "";
    } else {
        $case_type = " AND case_type_nclat  = '$app_pet'";
    }

    if (!empty($user_court))
        $user_court_query = " and court = '$user_court'";

    $query_q = "select filing_no,dt_of_filing,case_type,case_title,case_type_nclat,filingnumberia,subjectia from public.e_case_detail
    where location_id=? and payment_accept=? and filing_no !=? and
    is_defective ='1' and scrutiny_level = '2' and list_with_defect = '0'
	$case_type
	$filing_check
    $date_range1
    $user_court_query";
    if (isset($filter_date) && !empty($filter_date)) {
        $query_q .= $filter_date;
    }
    $query_q .= " order by defect_date $limit_1";


    //echo $query_q;
    try {
        $query = $dbonline->prepare($query_q);
        $query->bindParam(1, $location_id, PDO::PARAM_STR);
        $query->bindParam(2, $payment_accept, PDO::PARAM_STR);
        $query->bindParam(3, $filing_no, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function fresh_cases($dbonline, $location_id, $payment_accept, $filing_no, $app_pet, $limit = null, $from_date, $to_date, $start_from = null, $filing_no_yes = null, $date_range = null, $tab_type = null)
{
    $cav_type = 60;
    $cis_user_id = $_SESSION['id'];
    $date_range1 = '';
    if ($date_range != null) {
        $date = explode('/', $date_range);
        $date = $date[2] . '-' . $date['1'] . '-' . $date[0];
        if ($tab_type != null && $tab_type == 'cases_pending') {
            $date_range1 = "and  dt_of_filing between '1990-01-01 00:00:00' and '$date' ";
        } else if ($tab_type != null && $tab_type == 'filed_cases') {
            $date_range1 = "and  dt_of_filing = '$date' ";
        } else {
            $date_range1 = "and  dt_of_filing = '$date' ";
        }
    }

    if (!empty($from_date) and !empty($to_date)) {
        $filter_date = "and (dt_of_filing::timestamp::date between '$from_date' and '$to_date')";
    }

    $filing_check = '';
    if ($filing_no_yes != null || $filing_no_yes != '') {
        $filing_check = ' and cd.filing_no = ' . "'$filing_no_yes'";
    }

    $limit_1 = '';
    if ($limit != null) {
        $limit_1 = 'limit ' . $limit . ' OFFSET ' . $start_from;
    }
    $case_type = '';
    if ($app_pet == '') {
        $case_type = "";
    } else {
        $case_type = " AND case_type_nclat  = '$app_pet'";
    }
    $zero = 0;
    $is_return = 0;
    $query_q = "SELECT cd.filing_no,cd.dt_of_filing,cd.case_type,cd.case_title,cd.case_type_nclat,cd.filingnumberia,cd.subjectia,cd.casetypeiacontempt,cd.contempt_case_year,cd.contempt_case_no,cd.gst_casetransfer_level,cd.cis_user_id,cd.court,
    od.filing_through,od.apl0402_not_found,gtv.gst_casetransfer_level as transfer_case_level,gtv.is_transfered
    FROM public.e_case_detail cd
    LEFT JOIN public.e_order_details od  ON od.filing_no=cd.filing_no
     LEFT JOIN public.gst_ecase_validation_for_Apl0204 gtv  ON gtv.filingno=cd.filing_no
    WHERE cd.location_id=? AND cd.payment_accept=? AND cd.filing_no !=? AND LENGTH(cd.filing_no) = 16 AND cd.back_log='N' 
    AND cd.scrutiny_level = ? AND cd.is_defective = ? AND cd.is_refiled = ? AND cd.case_type_nclat != ? 
    AND (cis_user_id=? OR cd.cis_user_id IS NULL)  AND cd.is_return = ?
    $case_type
    $filing_check
    $date_range1";
    if (isset($filter_date) && !empty($filter_date)) {
        $query_q .= $filter_date;
    }
    $query_q .= "order by dt_of_filing $limit_1";
    //echo $query_q; 
    try {
        $query = $dbonline->prepare($query_q);
        $query->bindParam(1, $location_id, PDO::PARAM_STR);
        $query->bindParam(2, $payment_accept, PDO::PARAM_STR);
        $query->bindParam(3, $filing_no, PDO::PARAM_STR);
        $query->bindParam(4, $zero, PDO::PARAM_STR);
        $query->bindParam(5, $zero, PDO::PARAM_STR);
        $query->bindParam(6, $zero, PDO::PARAM_STR);
        $query->bindParam(7, $cav_type, PDO::PARAM_STR);
        $query->bindParam(8, $cis_user_id, PDO::PARAM_STR);
        $query->bindParam(9, $is_return, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}


function return_cases_cases($dbonline, $location_id, $payment_accept, $filing_no, $app_pet, $limit = null, $from_date, $to_date, $start_from = null, $filing_no_yes = null, $date_range = null, $tab_type = null)
{
    $cav_type = 60;
    $cis_user_id = $_SESSION['id'];
    $date_range1 = '';
    if ($date_range != null) {
        $date = explode('/', $date_range);
        $date = $date[2] . '-' . $date['1'] . '-' . $date[0];
        if ($tab_type != null && $tab_type == 'cases_pending') {
            $date_range1 = "and  dt_of_filing between '1990-01-01 00:00:00' and '$date' ";
        } else if ($tab_type != null && $tab_type == 'filed_cases') {
            $date_range1 = "and  dt_of_filing = '$date' ";
        } else {
            $date_range1 = "and  dt_of_filing = '$date' ";
        }
    }

    if (!empty($from_date) and !empty($to_date)) {
        $filter_date = "and (dt_of_filing::timestamp::date between '$from_date' and '$to_date')";
    }

    $filing_check = '';
    if ($filing_no_yes != null || $filing_no_yes != '') {
        $filing_check = ' and filing_no = ' . "'$filing_no_yes'";
    }

    $limit_1 = '';
    if ($limit != null) {
        $limit_1 = 'limit ' . $limit . ' OFFSET ' . $start_from;
    }
    $case_type = '';
    if ($app_pet == '') {
        $case_type = "";
    } else {
        $case_type = " AND case_type_nclat  = '$app_pet'";
    }
    $zero = 0;
    $is_return = 1;
    $query_q = "SELECT cd.filing_no,cd.dt_of_filing,cd.case_type,cd.case_title,cd.case_type_nclat,cd.filingnumberia,cd.subjectia,cd.casetypeiacontempt,cd.contempt_case_year,cd.contempt_case_no,cd.gst_casetransfer_level,cd.cis_user_id,cd.court,
    od.filing_through,od.apl0402_not_found
    FROM public.e_case_detail cd
    LEFT JOIN public.e_order_details od  ON od.filing_no=cd.filing_no
    WHERE cd.location_id=? AND cd.payment_accept=? AND cd.filing_no !=? AND LENGTH(cd.filing_no) = 16 AND cd.back_log='N' 
    AND cd.scrutiny_level = ? AND cd.is_defective = ? AND cd.is_return = ? AND cd.case_type_nclat != ? 
    AND (cis_user_id=? OR cd.cis_user_id IS NULL) 
    $case_type
    $filing_check
    $date_range1";
    if (isset($filter_date) && !empty($filter_date)) {
        $query_q .= $filter_date;
    }
    $query_q .= "order by dt_of_filing desc $limit_1";
    //echo $query_q; 
    try {
        $query = $dbonline->prepare($query_q);
        $query->bindParam(1, $location_id, PDO::PARAM_STR);
        $query->bindParam(2, $payment_accept, PDO::PARAM_STR);
        $query->bindParam(3, $filing_no, PDO::PARAM_STR);
        $query->bindParam(4, $zero, PDO::PARAM_STR);
        $query->bindParam(5, $zero, PDO::PARAM_STR);
        $query->bindParam(6, $is_return, PDO::PARAM_STR);
        $query->bindParam(7, $cav_type, PDO::PARAM_STR);
        $query->bindParam(8, $cis_user_id, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}



function fresh_cases_ar($dbonline, $user_court, $location_id, $payment_accept, $filing_no, $app_pet, $limit = null, $from_date, $to_date, $start_from = null, $filing_no_yes = null, $date_range = null, $tab_type = null)
{
    $cav_type = 60;
    $cis_user_id = $_SESSION['id'];
    if ($date_range != null) {
        $date = explode('/', $date_range);
        $date = $date[2] . '-' . $date['1'] . '-' . $date[0];
        if ($tab_type != null && $tab_type == 'cases_pending') {
            $date_range1 = "and  dt_of_filing between '1990-01-01 00:00:00' and '$date' ";
        } else if ($tab_type != null && $tab_type == 'filed_cases') {
            $date_range1 = "and  dt_of_filing = '$date' ";
        } else {
            $date_range1 = "and  dt_of_filing = '$date' ";
        }
    }

    if (!empty($from_date) and !empty($to_date)) {
        $filter_date = "and (dt_of_filing::timestamp::date between '$from_date' and '$to_date')";
    }

    $filing_check = '';
    if ($filing_no_yes != null || $filing_no_yes != '') {
        $filing_check = ' and filing_no = ' . "'$filing_no_yes'";
    }

    $limit_1 = '';
    if ($limit != null) {
        $limit_1 = 'limit ' . $limit . ' OFFSET ' . $start_from;
    }
    $case_type = '';
    if ($app_pet == '') {
        $case_type = "";
    } else {
        $case_type = " AND case_type_nclat  = '$app_pet'";
    }

    if (!empty($user_court))
        $user_court_query = " and court = '$user_court'";

    $zero = 0;
    $one = 1;
    $query_q = "select filing_no,dt_of_filing,case_type,case_title,case_type_nclat,filingnumberia,subjectia,casetypeiacontempt,contempt_case_year,contempt_case_no from public.e_case_detail
    where location_id=? and payment_accept=? and filing_no !=? and LENGTH(filing_no) = 16 and back_log='N' and 
    scrutiny_level = ? and is_defective = ? and is_refiled = ? and case_no_generated = 0  and case_type_nclat != ?
    $case_type
    $filing_check
    $date_range1
    $user_court_query";
    if (isset($filter_date) && !empty($filter_date)) {
        $query_q .= $filter_date;
    }
    $query_q .= "order by dt_of_filing $limit_1";


    //echo $query_q; 
    try {
        $query = $dbonline->prepare($query_q);
        $query->bindParam(1, $location_id, PDO::PARAM_STR);
        $query->bindParam(2, $payment_accept, PDO::PARAM_STR);
        $query->bindParam(3, $filing_no, PDO::PARAM_STR);
        $query->bindParam(4, $one, PDO::PARAM_STR);
        $query->bindParam(5, $zero, PDO::PARAM_STR);
        $query->bindParam(6, $zero, PDO::PARAM_STR);
        $query->bindParam(7, $cav_type, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function refiled_cases_ar($dbonline, $user_court, $location_id, $payment_accept, $filing_no, $app_pet, $limit = null, $from_date, $to_date, $start_from = null, $filing_no_yes = null, $date_range = null, $tab_type = null)
{
    $cav_type = 60;
    $date_range1 = '';
    if ($date_range != null) {
        $date = explode('/', $date_range);
        $date = $date[2] . '-' . $date['1'] . '-' . $date[0];
        if ($tab_type != null && $tab_type == 'cases_pending') {
            $date_range1 = "and  dt_of_filing between '1990-01-01 00:00:00' and '$date' ";
        } else if ($tab_type != null && $tab_type == 'filed_cases') {
            $date_range1 = "and  dt_of_filing = '$date' ";
        } else {
            $date_range1 = "and  dt_of_filing = '$date' ";
        }
    }

    if (!empty($from_date) and !empty($to_date)) {
        $filter_date = "and (dt_of_filing::timestamp::date between '$from_date' and '$to_date')";
    }

    $filing_check = '';
    if ($filing_no_yes != null || $filing_no_yes != '') {
        $filing_check = ' and filing_no = ' . "'$filing_no_yes'";
    }

    $limit_1 = '';
    if ($limit != null) {
        $limit_1 = 'limit ' . $limit . ' OFFSET ' . $start_from;
    }
    $case_type = '';
    if ($app_pet == '') {
        $case_type = "";
    } else {
        $case_type = " AND case_type_nclat  = '$app_pet'";
    }
    if (!empty($user_court))
        $user_court_query = " and court = '$user_court'";
    $zero = 0;
    $one = 1;
    $query_q = "select filing_no,dt_of_filing,case_type,case_title,case_type_nclat,filingnumberia,subjectia,casetypeiacontempt,contempt_case_year,contempt_case_no from public.e_case_detail
    where location_id=? and payment_accept=? and filing_no !=? and LENGTH(filing_no) = 16 and back_log='N' and 
    scrutiny_level = ? and is_defective = ? and is_refiled = ? and case_no_generated = 0  and case_type_nclat != ?
    $case_type
    $filing_check
    $date_range1
    $user_court_query";
    if (isset($filter_date) && !empty($filter_date)) {
        $query_q .= $filter_date;
    }
    $query_q .= "order by dt_of_filing $limit_1";


    //echo $query_q; 
    try {
        $query = $dbonline->prepare($query_q);
        $query->bindParam(1, $location_id, PDO::PARAM_STR);
        $query->bindParam(2, $payment_accept, PDO::PARAM_STR);
        $query->bindParam(3, $filing_no, PDO::PARAM_STR);
        $query->bindParam(4, $one, PDO::PARAM_STR);
        $query->bindParam(5, $zero, PDO::PARAM_STR);
        $query->bindParam(6, $one, PDO::PARAM_STR);
        $query->bindParam(7, $cav_type, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function fn_case_type_name($db, $case_type)
{
    try {
        $query = $db->prepare("select case_type_desc from case_type where id = ? ");
        $query->bindParam(1, $case_type, PDO::PARAM_STR);
        $query->execute();
        $short_name = $query->fetchColumn();
        return $short_name;
    } catch (PDOException $ex) {
        echo $ex;
    }
}
function fn_date_formate($date)
{
    //list($year, $month, $day) = explode('-', $dt_of_filing);
    //$filing_date_all = $day . '/' . $month . '/' . $year;
    $date = date('d/m/Y h:i A', strtotime($date));
    if ($date == '11/11/1111' or $date == '//') {
        $date = "";
    }
    return $date;
}
function fn_case_party($dbonline, $party_type, $filing_no)
{
    $party_serial_no = '1';
    try {
        $query = $dbonline->prepare("select name from e_cases_party where filing_no=? and party_flag=? and party_serial_no= ? ");
        $query->bindParam(1, $filing_no, PDO::PARAM_STR);
        $query->bindParam(2, $party_type, PDO::PARAM_STR);
        $query->bindParam(3, $party_serial_no, PDO::PARAM_STR);
        $query->execute();
        $party_name = $query->fetchColumn();
        return $party_name;
    } catch (PDOException $ex) {
        echo $ex;
    }
}
function fn_document($dbonline, $filing_no)
{
    $scrutiny = '0';
    $display = 't';
    $query = $dbonline->prepare("select count(*) from document_upload where filing_no=? and scrutiny=? and display=? and (miscellaneous_ref_no IS NULL or miscellaneous_ref_no = '')");
    $query->bindParam(1, $filing_no, PDO::PARAM_STR);
    $query->bindParam(2, $scrutiny, PDO::PARAM_STR);
    $query->bindParam(3, $display, PDO::PARAM_STR);
    $query->execute();
    $document_count = $query->fetchColumn();
    return $document_count;
}

function fn_section($dbonline, $filing_no)
{
    $query = $dbonline->prepare("select * from e_case_detail_fees where filing_no=? ");
    $query->bindParam(1, $filing_no, PDO::PARAM_STR);
    $query->execute();
    $r = '';
    while ($row = $query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $sec_id = $row['sec_id'];
        if ($sec_id > '0') {
            $st3 = $dbonline->prepare("select * from master_section_act where id=? ");
            $st3->bindParam(1, $sec_id, PDO::PARAM_STR);
            $st3->execute();
            while ($row3 = $st3->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                $E_add_sec_id = $row3['section_companies'];
                $r .= $E_add_sec_id . ',';
            }
        }
    }
    $section = rtrim($r, ',');
    return $section;
}

function fn_defective_objection($db, $dbonline, $schemas, $filing_no, $server_date)
{
    $obj_status = 'NO';
    // $st21 = $db->prepare("select distinct(entry_dt) from $schemas.objection_details where filing_no=? and status=? ");
    // $st21->bindParam(1, $filing_no, PDO::PARAM_STR);
    // $st21->bindParam(2, $obj_status, PDO::PARAM_STR);
    // $st21->execute();
    // $obj_entry_date = $st21->fetchColumn();
    $ss = "select max(document_filed_date) as document_filed_date  from document_upload where filing_no='$filing_no'  and scrutiny=0 and display='1' ";
    $st11 = $dbonline->prepare($ss);
    $st11->execute();
    while ($row11 = $st11->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $uploaded_date = htmlspecialchars($row11['document_filed_date']);
    }
    //if document uploaded in between 7 days
    $d1 = $uploaded_date; //25-11-2-17
    if ($d1 == '') {
        // $d3 = $server_date;
        // $d2 = $obj_entry_date; //23-11-2017
        // $datetime1 = new DateTime($d2);
        // $datetime2 = new DateTime($d3);
        // $interval = $datetime1->diff($datetime2);
        // $kk1 = $interval->format('%R%a');
        $upload_check = 0;
    } else {
        // $d2 = $obj_entry_date; //23-11-2017
        // $datetime1 = new DateTime($d2);
        // $datetime2 = new DateTime($d1); //document uploaded
        // $interval = $datetime1->diff($datetime2);
        // $kk = $interval->format('%R%a');
        // $kk3 = $kk;
        $upload_check = 1;
    }
    return $upload_check;
}

function get_party($db, $filing_no, $party_flag, $party_serial_no)
{
    try {
        $others = '';
        $query = "select count(*) from e_cases_party where filing_no = ? and party_flag = ?";
        $party_count = $db->prepare($query);
        $party_count->bindParam(1, $filing_no, PDO::PARAM_STR);
        $party_count->bindParam(2, $party_flag, PDO::PARAM_STR);
        $party_count->execute();
        $nos = $party_count->fetchColumn();

        if ($nos == 2) {
            $others = ' & Anr.';
        } elseif ($nos > 1 && $nos != 2) {
            $others = '& Ors.';
        }

        $query = "select name from e_cases_party where filing_no = ? and party_flag = ? and party_serial_no = ? limit 1";
        $party = $db->prepare($query);
        $party->bindParam(1, $filing_no, PDO::PARAM_STR);
        $party->bindParam(2, $party_flag, PDO::PARAM_STR);
        $party->bindParam(3, $party_serial_no, PDO::PARAM_STR);
        $party->execute();
        $name = $party->fetchColumn();
        return $name . $others;
    } catch (PDOException $ex) {
        echo $ex;
    }
}


function main_case_type()
{
    $main_case_type = array(1,11);
    return $main_case_type;
}

function main_case_type_for_comp_note()
{
    $main_case_type = array(1);
    return $main_case_type;
}

function get_fresh_connected_IA($db, $filing_no, $location_id, $case_type, $payment_accept = 'Y', $backlog = 'N', $scrutiny_level = 0, $is_defective = 0, $is_refiled = 0)
{
    $na = 'NA';
    $cav_type = 60;
    /* $query = "select filing_no,dt_of_filing,case_type,case_title,case_type_nclat,filingnumberia,subjectia from public.e_case_detail
    where filingnumberia = ? and location_id=? and payment_accept=? and filing_no !=? and LENGTH(filing_no) = 16 and back_log=? and 
    scrutiny_level = ? and is_defective = ? and is_refiled = ? and case_type_nclat = ? and case_no_generated = ?"; */
    $query = "select filing_no,dt_of_filing,case_type,case_title,case_type_nclat,filingnumberia,subjectia from public.e_case_detail
    where filingnumberia = ? and location_id=? and payment_accept=? and filing_no !=? and LENGTH(filing_no) = 16 and back_log=? and 
    scrutiny_level = ? and is_defective = ?  and case_type_nclat = ? and case_no_generated = ? and case_type_nclat != ?";
    try {
        $IA = $db->prepare($query);
        $IA->bindParam(1, $filing_no, PDO::PARAM_STR);
        $IA->bindParam(2, $location_id, PDO::PARAM_STR);
        $IA->bindParam(3, $payment_accept, PDO::PARAM_STR);
        $IA->bindParam(4, $na, PDO::PARAM_STR);
        $IA->bindParam(5, $backlog, PDO::PARAM_STR);
        $IA->bindParam(6, $scrutiny_level, PDO::PARAM_STR);
        $IA->bindParam(7, $is_defective, PDO::PARAM_STR);
        //$IA->bindParam(8, $is_refiled, PDO::PARAM_STR);
        $IA->bindParam(8, $case_type, PDO::PARAM_STR);
        $IA->bindParam(9, $scrutiny_level, PDO::PARAM_STR);
        $IA->bindParam(10, $cav_type, PDO::PARAM_STR);
        $IA->execute();
        $all_fresh_IA = $IA->fetchAll();
        return $all_fresh_IA;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function get_defect_free_connected_IA($db, $filing_no, $location_id, $case_type, $payment_accept = 'Y', $backlog = 'N', $scrutiny_level = 1, $is_defective = 0)
{
    $cav_type = 60;
    $na = 'NA';
    $zero = 0;
    $query = "select filing_no,dt_of_filing,case_type,case_title,case_type_nclat,filingnumberia,subjectia from public.e_case_detail
    where filingnumberia = ? and location_id=? and payment_accept=? and filing_no !=? and LENGTH(filing_no) = 16 and back_log=? and 
    scrutiny_level = ? and is_defective = ? and case_type_nclat = ? and case_no_generated = ? and case_type_nclat != ?";
    try {
        $IA = $db->prepare($query);
        $IA->bindParam(1, $filing_no, PDO::PARAM_STR);
        $IA->bindParam(2, $location_id, PDO::PARAM_STR);
        $IA->bindParam(3, $payment_accept, PDO::PARAM_STR);
        $IA->bindParam(4, $na, PDO::PARAM_STR);
        $IA->bindParam(5, $backlog, PDO::PARAM_STR);
        $IA->bindParam(6, $scrutiny_level, PDO::PARAM_STR);
        $IA->bindParam(7, $is_defective, PDO::PARAM_STR);
        $IA->bindParam(8, $case_type, PDO::PARAM_STR);
        $IA->bindParam(9, $zero, PDO::PARAM_STR);
        $IA->bindParam(10, $cav_type, PDO::PARAM_STR);
        $IA->execute();
        $all_fresh_IA = $IA->fetchAll();
        return $all_fresh_IA;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function main_case_comp_note_status($db, $main_filing_no, $location_id, $payment_accept = 'Y', $backlog = 'N', $scrutiny_level = 1, $is_defective = 0)
{
    $na = 'NA';
    $query = "select count(*) from public.e_case_detail
    where filing_no = ? and location_id=? and payment_accept=? and filing_no !=? and LENGTH(filing_no) = 16 and 
    scrutiny_level = ? AND is_defective = ? and case_no_generated = ?";
    try {
        $IA = $db->prepare($query);
        $IA->bindParam(1, $main_filing_no, PDO::PARAM_STR);
        $IA->bindParam(2, $location_id, PDO::PARAM_STR);
        $IA->bindParam(3, $payment_accept, PDO::PARAM_STR);
        $IA->bindParam(4, $na, PDO::PARAM_STR);
        $IA->bindParam(5, $scrutiny_level, PDO::PARAM_STR);
        $IA->bindParam(6, $is_defective, PDO::PARAM_STR);
        $IA->bindParam(7, $scrutiny_level, PDO::PARAM_STR);
        $IA->execute();
        $all_fresh_IA = $IA->fetchColumn();
        return $all_fresh_IA;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function check_all_ias_status($db, $all_ia, $location_id, $payment_accept = 'Y', $backlog = 'N', $scrutiny_level = 1, $is_defective = 0)
{
    $cav_type = 60;
    $na = 'NA';
    $zero = 0;
    $query = "select count(*) from public.e_case_detail
    where filing_no in ('$all_ia') and location_id=? and payment_accept=? and filing_no !=? and LENGTH(filing_no) = 16 and 
    scrutiny_level = ? AND is_defective = ? and case_no_generated = ? and case_type_nclat != ?";
    try {
        $IA = $db->prepare($query);
        $IA->bindParam(1, $location_id, PDO::PARAM_STR);
        $IA->bindParam(2, $payment_accept, PDO::PARAM_STR);
        $IA->bindParam(3, $na, PDO::PARAM_STR);
        $IA->bindParam(4, $scrutiny_level, PDO::PARAM_STR);
        $IA->bindParam(5, $is_defective, PDO::PARAM_STR);
        $IA->bindParam(6, $zero, PDO::PARAM_STR);
        $IA->bindParam(7, $cav_type, PDO::PARAM_STR);
        $IA->execute();
        $all_fresh_IA = $IA->fetchColumn();
        return $all_fresh_IA;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function get_all_withount_case_no_generated_IA($db, $filing_no, $location_id, $case_type)
{
    $cav_type = 60;
    $na = 'NA';
    $zero = 0;
    $query = "select filing_no,dt_of_filing,case_type,case_title,case_type_nclat,filingnumberia,subjectia from public.e_case_detail
    where filingnumberia = ? and location_id=? and filing_no !=? and LENGTH(filing_no) = 16 and 
    case_type_nclat = ? and case_no_generated = ? and case_type_nclat != ? and is_defective = 0";

    try {
        $IA = $db->prepare($query);
        $IA->bindParam(1, $filing_no, PDO::PARAM_STR);
        $IA->bindParam(2, $location_id, PDO::PARAM_STR);
        $IA->bindParam(3, $na, PDO::PARAM_STR);
        $IA->bindParam(4, $case_type, PDO::PARAM_STR);
        $IA->bindParam(5, $zero, PDO::PARAM_STR);
        $IA->bindParam(6, $cav_type, PDO::PARAM_STR);
        $IA->execute();
        $all_fresh_IA = $IA->fetchAll();
        return $all_fresh_IA;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function get_subject($db, $subject_id)
{
    try {
        $query = "select subject_name from master_subject_ia where subject_id = ?";
        $subject = $db->prepare($query);
        $subject->bindParam(1, $subject_id, PDO::PARAM_STR);
        $subject->execute();
        $subject_name = $subject->fetchColumn();
        return $subject_name;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function computation_note_details($db, $schemas, $filing_no)
{
    try {
        $query = "select * from $schemas.computational_note where filing_no = ?";
        $comp_note = $db->prepare($query);
        $comp_note->bindParam(1, $filing_no, PDO::PARAM_STR);
        $comp_note->execute();
        $comp_note_details = $comp_note->fetchAll();
        return $comp_note_details;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function display_date($date, $current_format)
{
    if ($current_format == 'dd/mm/yy') {
        list($day, $month, $year) = explode('/', $date);
        $new_date = $year . '-' . $month . '-' . $day;
    }
    if ($current_format == 'yy-mm-dd') {
        list($year, $month, $day) = explode('-', $date);
        $new_date = $day . '/' . $month . '/' . $year;
    }
    if ($current_format == 'dd.mm.yy') {
        if ($date == '9999-01-01')
            return '';
        list($year, $month, $day) = explode('-', $date);
        $new_date = $day . '.' . $month . '.' . $year;
    }
    if ($current_format == 'dd/mm/yyyy') {
        list($day, $month, $year) = explode('/', $date);
        $new_date = $year . '-' . $month . '-' . $day;
    }

    return $new_date;
}

function get_scrutiny_detail($db, $schemas, $column = '*', $filing_no)
{
    try {
        $query = "select $column from $schemas.scrutiny where filing_no = ?";
        $scrutiny = $db->prepare($query);
        $scrutiny->bindParam(1, $filing_no, PDO::PARAM_STR);
        $scrutiny->execute();
        $scrutiny_details = $scrutiny->fetchAll();
        return $scrutiny_details;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function get_case_type_detail($db, $case_type)
{
    try {
        $query = "select * from case_type where id = ?";
        $scrutiny = $db->prepare($query);
        $scrutiny->bindParam(1, $case_type, PDO::PARAM_STR);
        $scrutiny->execute();
        $scrutiny_details = $scrutiny->fetchAll();
        return $scrutiny_details;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function get_case_detail($db, $location_id, $filing_no)
{
    $query = "select a.filing_no, a.case_year,a.subjectia, b.case_type_desc as case_type_desc_cis, c.subject_name,a.scrutiny_level,a.case_no_generated,a.is_defective from e_case_detail as a 
	left join case_type as b on b.id = a.case_type_nclat
	left join master_subject_ia as c on c.subject_id = a.subjectia where a.filing_no = ? and a.location_id = ?";

    try {
        $data = $db->prepare($query);
        $data->bindParam(1, $filing_no, PDO::PARAM_STR);
        $data->bindParam(2, $location_id, PDO::PARAM_STR);
        $data->execute();
        $data = $data->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function get_completed_case_detail($db, $location_id, $filing_no)
{
    $query = "select * from e_case_detail where filing_no = ? and location_id = ?";

    try {
        $data = $db->prepare($query);
        $data->bindParam(1, $filing_no, PDO::PARAM_STR);
        $data->bindParam(2, $location_id, PDO::PARAM_STR);
        $data->execute();
        $data = $data->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function get_remarks($db, $schemas, $filing_no)
{
    $query = "select remarks,enrty_date from $schemas.computational_note_remark where filing_no = ?";
    try {
        $data = $db->prepare($query);
        $data->bindParam(1, $filing_no, PDO::PARAM_STR);
        $data->execute();
        $data = $data->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function generated_computation_note($db, $schemas)
{
    $cav_type = 60;
    $icna = 0;
    $one = 1;
    $query = "select s.filing_no,ecd.case_type,ecd.dt_of_filing,ecd.case_title,ecd.case_type_nclat,ecd.filingnumberia,ecd.patially_defective,ecd.subjectia,ct.case_type_desc as case_type_desc_cis 
					from $schemas.computational_note as s 
					left join e_case_detail ecd on ecd.filing_no = s.filing_no
					left join case_type ct on ct.id= ecd.case_type_nclat
					where s.in_registrar = ? and ecd.is_defective = ? and ecd.scrutiny_level = ? and ecd.case_no_generated = ? and ecd.case_type_nclat != ? order by s.entry_date asc";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $one, PDO::PARAM_STR);
    $stmt->bindParam(2, $icna, PDO::PARAM_STR);
    $stmt->bindParam(3, $one, PDO::PARAM_STR);
    $stmt->bindParam(4, $icna, PDO::PARAM_STR);
    $stmt->bindParam(5, $cav_type, PDO::PARAM_STR);
    $stmt->execute();
    $res = $stmt->fetchAll();
    return $res;
}

function get_cases_for_no_generation($db, $user_court, $schemas, $from_date, $to_date, $type = 'main')
{
    $cav_type = 60;
    $defects = 'N';
    $icna = 0;
    $one = 1;
    $scrutiny_level = 2;
    $display = TRUE;
    $case_no_generated = FALSE;
    if (!empty($from_date) and !empty($to_date)) {
        $filter_date = "and (ecd.dt_of_filing between ? and ?)";
    }
    if (!empty($user_court))
        $user_court_query = " and ecd.court = '$user_court'";

    $query =     "select s.filing_no,ecd.case_type,ecd.dt_of_filing,ecd.case_title,ecd.case_type_nclat,ecd.filingnumberia,ecd.subjectia,ecd.patially_defective,ct.case_type_desc as case_type_desc_cis ,s.from_type, ecd.list_with_defect
				from $schemas.case_no_generation as s 
				inner join e_case_detail ecd on ecd.filing_no = s.filing_no
				left join case_type ct on ct.id= ecd.case_type_nclat
				where s.is_case_no_generated = FALSE and ecd.display = TRUE and (ecd.is_defective = ? OR (ecd.is_defective = 1 AND ecd.list_with_defect = 1)) and ecd.scrutiny_level = ? and ecd.case_no_generated = ? and ecd.case_type_nclat != ? $user_court_query";
    if (isset($filter_date) && !empty($filter_date)) {
        $query .= $filter_date;
    }
    $query .= " order by s.entry_date asc";

    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $icna, PDO::PARAM_STR);
    $stmt->bindParam(2, $scrutiny_level, PDO::PARAM_STR);
    $stmt->bindParam(3, $icna, PDO::PARAM_STR);
    $stmt->bindParam(4, $cav_type, PDO::PARAM_STR);
    if (isset($filter_date) && !empty($filter_date)) {
        $stmt->bindParam(5, $from_date, PDO::PARAM_STR);
        $stmt->bindParam(6, $to_date, PDO::PARAM_STR);
    }
    $stmt->execute();
    $res = $stmt->fetchAll();
    return $res;
}

function get_case_no($db, $schemas, $filing_no, $for_sms = '')
{
    if ($for_sms != '') {
        $query = "select ct.short_name||'/'||cd.case_no||'/'||mlc.short_name||'/'||cd.case_year as case_num
from $schemas.case_detail as cd
left join case_type as ct on ct.id = cd.case_type
left join mater_location_city as mlc on mlc.city_id = cd.location_code
where cd.filing_no = ?";
    } else {
        $query = "select ct.case_type_desc||'/'||cd.case_no||'/'||mlc.short_name||'/'||cd.case_year as case_num
from $schemas.case_detail as cd
left join case_type as ct on ct.id = cd.case_type
left join mater_location_city as mlc on mlc.city_id = cd.location_code
where cd.filing_no = ?";
    }
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
    $stmt->execute();
    $res = $stmt->fetchColumn();
    return $res;
}


function fn_sms($db_online, $type, $filename, $filing_no, $subject, $message, $mail_message, $path = '')
{
    $path = urlencode($path);
    $year = date('Y');
    $pdfpath = '';
    $templateid = '';
    if ($type == '4') {
        $pdfpath = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/gstat/scrutiny/readpdf.php?path=' . $path;
    } else if ($type == '2') {
        $pdfpath = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/gstat/scrutiny/readpdf.php?path=' . $path;
    } else if ($type == '8') {
        $pdfpath = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/gstat/scrutiny/readpdf.php?path=' . $path;
    } else if ($type == '10') {
        $pdfpath = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/gstat/scrutiny/readpdf.php?path=' . $path;
    }
    if($type == '6'){
        $templateid = '1007696179121187014';  // case lisitng
    }
    if ($type == '1' || $type == '9') {
        $templateid = '1007510203486791880';  // register
    }
    if ($type == '2' || $type == '10' || $type == '8') {
        $templateid = '1007547774127031355';  // for defect 
    }
    if ($type == '11') {
        $templateid = '1007055116230192707';  // additional doc defect free
    }
    if ($type == '14') {
        $templateid = '1007536297539085528';  // list with defect
    }
    if ($type == '15') {
        $templateid = '1007559790011044702';  // appeal dispose
    }
    if ($type == '16') {
        $templateid = '1007983580652354969';  // applaction dispose
    }
    if ($type == '20') {
        $templateid = '1007874067296690469';  // Orders
    }



    if ($filing_no != '') {
        $check_sql = $db_online->prepare("select rep_code from e_more_representative where filing_no =? and party_flag='P' and party_serial_no='1' order by id asc limit 1");
        $check_sql->bindParam(1, $filing_no, PDO::PARAM_STR);
        $check_sql->execute();
        $pet_adv_code = $check_sql->fetchColumn();
        $pet_adv_name = '';
        $pet_adv_mobile = '';
        $pet_adv_email = '';
        if ($pet_adv_code != '') {
            $stqq12 = $db_online->prepare("select rep_name,email,mobile from e_master_advocate where id=? ");
            $stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
            $stqq12->execute();
            $advocate_data = $stqq12->fetchAll();
            $pet_adv_name = $advocate_data[0]['rep_name'];
            $pet_adv_mobile = $advocate_data[0]['mobile'];
            $pet_adv_email = $advocate_data[0]['email'];
        }

        $adv_party = 'R';
        $adv_party_serial = '1';
        $check_sql1 = $db_online->prepare("select rep_code from e_more_representative where filing_no =? and party_flag=? and party_serial_no=? order by id asc limit 1");
        $check_sql1->bindParam(1, $filing_no, PDO::PARAM_STR);
        $check_sql1->bindParam(2, $adv_party, PDO::PARAM_STR);
        $check_sql1->bindParam(3, $adv_party_serial, PDO::PARAM_STR);
        $check_sql1->execute();
        $res_adv_code = $check_sql1->fetchColumn();
        $res_adv_name = '';
        $res_adv_email = '';
        $res_adv_mobile = '';
        if ($res_adv_code != '') {
            $stqq12 = $db_online->prepare("select rep_name,email,mobile from e_master_advocate where id=? ");
            $stqq12->bindParam(1, $res_adv_code, PDO::PARAM_INT);
            $stqq12->execute();
            $advocate_data = $stqq12->fetchAll();
            $res_adv_name = $advocate_data[0]['rep_name'];
            $res_adv_email = $advocate_data[0]['email'];
            $res_adv_mobile = $advocate_data[0]['mobile'];
        }


        $pet_flag = 'P';
        $pet_serial = '1';
        $pet_email = '';
        $pet_mobile = '';
        $pet_name = '';
        $sthr2 = $db_online->prepare("select name,email,mobile from e_cases_party where filing_no =? and party_flag=? and party_serial_no=? ");
        $sthr2->bindParam(1, $filing_no, PDO::PARAM_STR);
        $sthr2->bindParam(2, $pet_flag, PDO::PARAM_STR);
        $sthr2->bindParam(3, $pet_serial, PDO::PARAM_STR);
        $sthr2->execute();
        while ($row1 = $sthr2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $pet_email = $row1['email'];
            $pet_mobile = $row1['mobile'];
            $pet_name = $row1['name'];
        }

        $res_flag = 'R';
        $res_serial = '1';
        $res_email = '';
        $res_mobile = '';
        $res_name = '';
        $sthr3 = $db_online->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=?");
        $sthr3->bindParam(1, $filing_no, PDO::PARAM_STR);
        $sthr3->bindParam(2, $res_flag, PDO::PARAM_STR);
        $sthr3->bindParam(3, $res_serial, PDO::PARAM_STR);
        $sthr3->execute();
        while ($row3 = $sthr3->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $res_email = $row3['email'];
            $res_mobile = $row3['mobile'];
            $res_name = $row3['name'];
        }

        // code for BO
        $bo_email = $bo_mobile = '';
        $sthr2 = $db_online->prepare("select email,mobilenumber from gst_ecase_assign_tobo_office where filingno =?");
        $sthr2->bindParam(1, $filing_no, PDO::PARAM_STR);
        $sthr2->execute();
        $bo_data = $sthr2->fetch();
        $bo_email = $bo_data['email'];
        $bo_mobile = $bo_data['mobilenumber'];
        // code for BO end

        // code for NODAL
        $nodal_email = $nodal_mobile = '';
        if($type == '1' || $type == '2' || $type == '14'){
            $sthr2 = $db_online->prepare("select ecd.gst_state_id,ecd.gst_stateorcentral,esu.mobilenumber,esu.email from e_case_detail as ecd 
inner join e_sign_up as esu on esu.gst_stateorcentral = ecd.gst_stateorcentral AND esu.gst_state_id = ecd.gst_state_id
inner join loginmodel as lm on lm.loginidgenerated = esu.loginidgenerated 
where ecd.filing_no = ? and lm.role in (31,32)");
            $sthr2->bindParam(1, $filing_no, PDO::PARAM_STR);
            $sthr2->execute();
            $nodal_data = $sthr2->fetch();
            $nodal_email = $nodal_data['email'];
            $nodal_mobile = $nodal_data['mobilenumber'];
        }
        // code for NODAL end

        $cur_date = date('Y-m-d');

        if ($pet_adv_code == '') {
            $pet_adv_code = '0';
        }
        if ($res_adv_code == '') {
            $res_adv_code = '0';
        }
        // if ($filename != '') {
        //     $mail_message = $mail_message . '.  <a download="download" href="' . $pdfpath . '" target = "_blank"> click here </a>';
        // }
        $query = "insert into sms(filing_no,msg,subject,email_text,pet_name,pet_email,pet_mobile,pet_adv_code,pet_adv_name,
		pet_adv_email,pet_adv_mob_no,res_name,res_email,res_mobile,res_adv_code,res_adv_name,res_adv_email,res_adv_mob_no,
		send_flag,pdf_path,file_name,entry_date,sms_flag,templateid,bo_mobile,bo_email,nodal_mobile,nodal_email) 
		VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        try {
            $zero = 0;
            $sql_query = $db_online->prepare($query);
            $sql_query->bindParam(1, $filing_no, PDO::PARAM_STR);
            $sql_query->bindParam(2, $message, PDO::PARAM_STR);
            $sql_query->bindParam(3, $subject, PDO::PARAM_STR);
            $sql_query->bindParam(4, $mail_message, PDO::PARAM_STR);
            $sql_query->bindParam(5, $pet_name, PDO::PARAM_STR);
            $sql_query->bindParam(6, $pet_email, PDO::PARAM_STR);
            $sql_query->bindParam(7, $pet_mobile, PDO::PARAM_STR);
            $sql_query->bindParam(8, $pet_adv_code, PDO::PARAM_STR);
            $sql_query->bindParam(9, $pet_adv_name, PDO::PARAM_STR);
            $sql_query->bindParam(10, $pet_adv_email, PDO::PARAM_STR);
            $sql_query->bindParam(11, $pet_adv_mobile, PDO::PARAM_STR);
            $sql_query->bindParam(12, $res_name, PDO::PARAM_STR);
            $sql_query->bindParam(13, $res_email, PDO::PARAM_STR);
            $sql_query->bindParam(14, $res_mobile, PDO::PARAM_STR);
            $sql_query->bindParam(15, $res_adv_code, PDO::PARAM_STR);
            $sql_query->bindParam(16, $res_adv_name, PDO::PARAM_STR);
            $sql_query->bindParam(17, $res_adv_email, PDO::PARAM_STR);
            $sql_query->bindParam(18, $res_adv_mobile, PDO::PARAM_STR);
            $sql_query->bindParam(19, $zero, PDO::PARAM_STR);
            $sql_query->bindParam(20, $filename, PDO::PARAM_STR);
            $sql_query->bindParam(21, $filename, PDO::PARAM_STR);
            $sql_query->bindParam(22, $cur_date, PDO::PARAM_STR);
            $sql_query->bindParam(23, $type, PDO::PARAM_STR);
            $sql_query->bindParam(24, $templateid, PDO::PARAM_STR);
            $sql_query->bindParam(25, $bo_mobile, PDO::PARAM_STR);
            $sql_query->bindParam(26, $bo_email, PDO::PARAM_STR);
            $sql_query->bindParam(27, $nodal_mobile, PDO::PARAM_STR);
            $sql_query->bindParam(28, $nodal_email, PDO::PARAM_STR);
            $sql_query->execute();
        } catch (PDOException $ex) {
            echo $ex;
        }
    }
    return 1;
}

function act_impugned($db, $filing_no)
{
    $query = "select a.act_id,b.impugned_order_date,c.act_name,b.order_type from e_case_detail as a 
			left join e_case_detail_fees as b on b.filing_no = a.filing_no 
			left join master_act as c on c.act_id = a.act_id
			where a.filing_no = ? order by b.impugned_order_date asc";
    try {
        $sql_query = $db->prepare($query);
        $sql_query->bindParam(1, $filing_no, PDO::PARAM_STR);
        $sql_query->execute();
        $res = $sql_query->fetchAll();
        return $res;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function count_doc($db, $filing_no, $scrutiny, $display)
{
    $query = "select count(*) as count from document_upload where filing_no = ? and scrutiny = ? and display = ?";
    try {
        $sql_query = $db->prepare($query);
        $sql_query->bindParam(1, $filing_no, PDO::PARAM_STR);
        $sql_query->bindParam(2, $scrutiny, PDO::PARAM_STR);
        $sql_query->bindParam(3, $display, PDO::PARAM_STR);
        $sql_query->execute();
        $res = $sql_query->fetchColumn();
        return $res;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function refiled_cases($dbonline, $location_id, $payment_accept, $filing_no, $app_pet, $limit = null, $from_date, $to_date, $start_from = null, $filing_no_yes = null, $date_range = null, $tab_type = null)
{
    $cav_type = 60;
    $date_range1 = '';
    if ($date_range != null) {
        $date = explode('/', $date_range);
        $date = $date[2] . '-' . $date['1'] . '-' . $date[0];
        if ($tab_type != null && $tab_type == 'cases_pending') {
            $date_range1 = "and  rd.refiling_date between '1990-01-01 00:00:00' and '$date' ";
        } else if ($tab_type != null && $tab_type == 'filed_cases') {
            $date_range1 = "and  rd.refiling_date = '$date' ";
        } else {
            $date_range1 = "and  rd.refiling_date = '$date' ";
        }
    }

    $filing_check = '';
    if ($filing_no_yes != null || $filing_no_yes != '') {
        $filing_check = ' and ecd.filing_no = ' . "'$filing_no_yes'";
    }

    $limit_1 = '';
    if ($limit != null) {
        $limit_1 = 'limit ' . $limit . ' OFFSET ' . $start_from;
    }
    $case_type = '';
    if ($app_pet == '') {
        $case_type = "";
    } else {
        $case_type = " AND ecd.case_type_nclat  = '$app_pet'";
    }
    if (!empty($from_date) and !empty($to_date)) {
        $filter_date = "and (rd.refiling_date::timestamp::date between '$from_date' and '$to_date')";
    }
    $zero = 0;
    $one = 1;
    $query_q = "SELECT ecd.filing_no,ecd.dt_of_filing,ecd.case_type,ecd.case_title,ecd.case_type_nclat,ecd.filingnumberia,ecd.subjectia,ecd.patially_defective,ecd.patially_defect_remark,rd.refiling_date,ecd.cis_user_id,ecd.court,
    od.filing_through,od.apl0402_not_found
    from public.e_case_detail as ecd
    LEFT JOIN public.e_order_details od  ON od.filing_no=ecd.filing_no
    left join get_refiling_date as rd on rd.filing_no = ecd.filing_no and rd.location_id = ?    
    where ecd.location_id=? and ecd.payment_accept=? and ecd.filing_no !=? and LENGTH(ecd.filing_no) = 16 and ecd.back_log='N' and 
    ecd.scrutiny_level = ? and ecd.is_defective = ? and ecd.is_refiled = ? and ecd.case_type_nclat != ?
    $case_type
    $filing_check
    $date_range1";
    if (isset($filter_date) && !empty($filter_date)) {
        $query_q .= $filter_date;
    }
    $query_q .= "order by ecd.dt_of_filing $limit_1";

    //echo $query_q; 
    try {
        $query = $dbonline->prepare($query_q);
        $query->bindParam(1, $location_id, PDO::PARAM_STR);
        $query->bindParam(2, $location_id, PDO::PARAM_STR);
        $query->bindParam(3, $payment_accept, PDO::PARAM_STR);
        $query->bindParam(4, $filing_no, PDO::PARAM_STR);
        $query->bindParam(5, $zero, PDO::PARAM_STR);
        $query->bindParam(6, $zero, PDO::PARAM_STR);
        $query->bindParam(7, $one, PDO::PARAM_STR);
        $query->bindParam(8, $cav_type, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function get_party_completed_info($db, $filing_no, $party_flag, $party_serial_no)
{
    try {
        $query = "select * from e_cases_party where filing_no = ? and party_flag = ? and party_serial_no = ? limit 1";
        $party = $db->prepare($query);
        $party->bindParam(1, $filing_no, PDO::PARAM_STR);
        $party->bindParam(2, $party_flag, PDO::PARAM_STR);
        $party->bindParam(3, $party_serial_no, PDO::PARAM_STR);
        $party->execute();
        $data = $party->fetchAll();
        $data = array_shift($data);
        return $data;
    } catch (PDOException $ex) {
        echo $ex;
    }
}


function get_scrutiny_info($db, $schemas, $filing_no)
{
    try {
        $query = "select * from $schemas.scrutiny where filing_no = ?";
        $party = $db->prepare($query);
        $party->bindParam(1, $filing_no, PDO::PARAM_STR);
        $party->execute();
        $data = $party->fetchAll();
        $data = array_shift($data);
        return $data;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function impugned_order_details($db, $schemas, $filing_no)
{
    try {
        $query = "select * from $schemas.impugned_order_details where filing_no = ? order by id";
        $data = $db->prepare($query);
        $data->bindParam(1, $filing_no, PDO::PARAM_STR);
        $data->execute();
        $data = $data->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function fictitious_info($db, $schemas, $fictitious_filing_no)
{
    try {
        $query = "select a.case_type,a.case_year,a.case_no,b.short_name from e_not_found as a 
			left join case_type as b on b.id = a.case_type
			where a.fictitious_filing_no = ?";
        $data = $db->prepare($query);
        $data->bindParam(1, $fictitious_filing_no, PDO::PARAM_STR);
        $data->execute();
        $data = $data->fetchAll();
        $data = array_shift($data);
        return $data;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function filed_caveat($dbonline, $schemas, $location_id, $cav_report, $payment_accept, $filing_no, $app_pet, $limit = null, $start_from = null, $cavetor_search = null, $from_date = '', $to_date = '', $date_range = null, $tab_type = null)
{
    $cav_type = 60;
    $date_range1 = '';
    /* if ($date_range != null) {
        $date = explode('/', $date_range);
        $date = $date[2] . '-' . $date['1'] . '-' . $date[0];
        if ($tab_type != null && $tab_type == 'cases_pending') {
            $date_range1 = "and  dt_of_filing between '1990-01-01 00:00:00' and '$date' ";
        } else if ($tab_type != null && $tab_type == 'filed_cases') {
            $date_range1 = "and  dt_of_filing = '$date' ";
        } else {
            $date_range1 = "and  dt_of_filing = '$date' ";
        }
    } */

    $limit_1 = '';
    if ($limit != null) {
        $limit_1 = 'limit ' . $limit . ' OFFSET ' . $start_from;
    }

    $zero = 0;
    if ($cav_report == 2) {
        $case_no_generated = 0;
        $is_defective = 1;
        $sc_level = 1;
    } else if ($cav_report == 3) {
        $case_no_generated = 1;
        $is_defective = 0;
        $sc_level = 1;
    } else {
        $case_no_generated = 0;
        $is_defective = 0;
        $sc_level = 0;
    }

    if ($from_date == '') {
        $from_date = '1999-01-01';
    } else {
        list($day, $month, $year) = explode('/', $from_date);
        $from_date = $year . '-' . $month . '-' . $day;
    }
    if ($to_date == '') {
        $to_date = '2050-01-01';
    } else {
        list($day_to, $month_to, $year_to) = explode('/', $to_date);
        $to_date = $year_to . '-' . $month_to . '-' . $day_to;
    }
    if ($cav_report == 3) {
        $date_search = " and (ecd.dt_of_filing between ? and ? )";
    } else {
        $date_search = " and (dt_of_filing between ? and ? )";
    }

    //$cavetor_search = '';
    if ($cav_report != 3) {

        if ($cavetor_search != null || $cavetor_search != '') {
            $cavetor_search = strtolower($cavetor_search);
            $query_q = "select a.filing_no,a.dt_of_filing,a.case_type,a.case_title,a.case_type_nclat,a.filingnumberia,a.is_refiled,a.is_defective,a.case_no_generated from e_cases_party as b
				left join e_case_detail as a on a.filing_no = b.filing_no
			where a.location_id=? and a.payment_accept=? and a.filing_no !=? and LENGTH(a.filing_no) = 16 and a.back_log='N' and 
			a.scrutiny_level = ? and a.is_defective = ? and case_no_generated = ? and a.case_type_nclat = ? and LOWER(b.name) like '%$cavetor_search%' and b.party_flag = 'C'
			$date_search order by a.dt_of_filing desc $limit_1";
        } else {
            $query_q = "select filing_no,dt_of_filing,case_type,case_title,case_type_nclat,filingnumberia,is_refiled,is_defective,case_no_generated from public.e_case_detail
			where location_id=? and payment_accept=? and filing_no !=? and LENGTH(filing_no) = 16 and back_log='N' and 
			scrutiny_level = ? and is_defective = ? and case_no_generated = ? and case_type_nclat = ? $date_search
			order by dt_of_filing desc $limit_1";
        }
    } else {

        if ($cavetor_search != null || $cavetor_search != '') {
            $cavetor_search = strtolower($cavetor_search);
            $query_q = "select ecd.filing_no,ecd.dt_of_filing,ecd.case_type,ecd.case_title,ecd.case_type_nclat,ecd.filingnumberia,ecd.is_refiled,
		ecd.is_defective,ecd.case_no_generated from e_case_detail as ecd
		inner join e_cases_party as ecp on ecp.filing_no = ecd.filing_no
		left join $schemas.case_detail as cd on cd.filing_no = ecd.filing_no 
		where ecd.location_id=? and ecd.payment_accept=? and ecd.filing_no !=? and LENGTH(ecd.filing_no) = 16 and ecd.back_log='N' and 
		ecd.scrutiny_level = ? and ecd.is_defective = ? and ecd.case_no_generated = ? and ecd.case_type_nclat = ? $date_search
		and LOWER(ecp.name) like '%$cavetor_search%' and ecp.party_flag = 'C'
		order by cast(cd.case_year as integer),cast(cd.case_no as integer) $limit_1";
        } else {
            $query_q = "select ecd.filing_no,ecd.dt_of_filing,ecd.case_type,ecd.case_title,ecd.case_type_nclat,ecd.filingnumberia,ecd.is_refiled,
		ecd.is_defective,ecd.case_no_generated from e_case_detail as ecd
		left join $schemas.case_detail as cd on cd.filing_no = ecd.filing_no 
		where ecd.location_id=? and ecd.payment_accept=? and ecd.filing_no !=? and LENGTH(ecd.filing_no) = 16 and ecd.back_log='N' and 
		ecd.scrutiny_level = ? and ecd.is_defective = ? and ecd.case_no_generated = ? and ecd.case_type_nclat = ? $date_search
		order by cast(cd.case_year as integer),cast(cd.case_no as integer) $limit_1";
        }
    }


    //echo $query_q; 
    try {
        $query = $dbonline->prepare($query_q);
        $query->bindParam(1, $location_id, PDO::PARAM_STR);
        $query->bindParam(2, $payment_accept, PDO::PARAM_STR);
        $query->bindParam(3, $filing_no, PDO::PARAM_STR);
        $query->bindParam(4, $sc_level, PDO::PARAM_STR);
        $query->bindParam(5, $is_defective, PDO::PARAM_STR);
        $query->bindParam(6, $case_no_generated, PDO::PARAM_STR);
        $query->bindParam(7, $cav_type, PDO::PARAM_STR);
        $query->bindParam(8, $from_date, PDO::PARAM_STR);
        $query->bindParam(9, $to_date, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        return $ex;
    }
}

function get_cavetor_info($db, $filing_no, $p_flag = 'C')
{
    try {
        $query = "select * from e_cases_party where filing_no = ? and party_flag = ?";
        $data = $db->prepare($query);
        $data->bindParam(1, $filing_no, PDO::PARAM_STR);
        $data->bindParam(2, $p_flag, PDO::PARAM_STR);
        $data->execute();
        $data = $data->fetchAll();
        return $data;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function main_case_comp_note_status_copy($db, $main_filing_no, $location_id, $payment_accept = 'Y', $backlog = 'N', $scrutiny_level = 0, $is_defective = 0)
{
    $na = 'NA';
    $query = "select count(*) from public.e_case_detail
    where filing_no = ? and location_id=? and payment_accept=? and filing_no !=? and LENGTH(filing_no) = 16 and 
    scrutiny_level = ? AND is_defective = ? and case_no_generated = ?";
    try {
        $IA = $db->prepare($query);
        $IA->bindParam(1, $main_filing_no, PDO::PARAM_STR);
        $IA->bindParam(2, $location_id, PDO::PARAM_STR);
        $IA->bindParam(3, $payment_accept, PDO::PARAM_STR);
        $IA->bindParam(4, $na, PDO::PARAM_STR);
        $IA->bindParam(5, $scrutiny_level, PDO::PARAM_STR);
        $IA->bindParam(6, $is_defective, PDO::PARAM_STR);
        $IA->bindParam(7, $scrutiny_level, PDO::PARAM_STR);
        $IA->execute();
        $all_fresh_IA = $IA->fetchColumn();
        return $all_fresh_IA;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function main_case_comp_note_refiled_status($db, $main_filing_no, $location_id, $payment_accept = 'Y', $backlog = 'N', $scrutiny_level = 0, $is_defective = 0)
{
    $na = 'NA';
    $is_refiled = 1;
    $query = "select count(*) from public.e_case_detail
    where filing_no = ? and location_id=? and payment_accept=? and filing_no !=? and LENGTH(filing_no) = 16 and 
    case_no_generated = ? and is_refiled = ?";
    try {
        $IA = $db->prepare($query);
        $IA->bindParam(1, $main_filing_no, PDO::PARAM_STR);
        $IA->bindParam(2, $location_id, PDO::PARAM_STR);
        $IA->bindParam(3, $payment_accept, PDO::PARAM_STR);
        $IA->bindParam(4, $na, PDO::PARAM_STR);
        $IA->bindParam(5, $scrutiny_level, PDO::PARAM_STR);
        $IA->bindParam(6, $is_refiled, PDO::PARAM_STR);
        //$IA->bindParam(7, $scrutiny_level, PDO::PARAM_STR);
        $IA->execute();
        $all_fresh_IA = $IA->fetchColumn();
        return $all_fresh_IA;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function all_party($db, $filing_no, $party_flag)
{
    try {
        $query = "select name from e_cases_party where filing_no = ? and party_flag = ? order by party_serial_no";
        $party = $db->prepare($query);
        $party->bindParam(1, $filing_no, PDO::PARAM_STR);
        $party->bindParam(2, $party_flag, PDO::PARAM_STR);
        $party->execute();
        $name = $party->fetchAll();
        return $name;
    } catch (PDOException $ex) {
        echo $ex;
    }
}


function get_old_case_info($db, $filing_no)
{
    try {
        $query = "select transfer_nclat_filing_no,transfer_bench_location,transfer_case_number,transfer_case_type from e_case_detail where filing_no = ?";
        $transferred_case = $db->prepare($query);
        $transferred_case->bindParam(1, $filing_no, PDO::PARAM_STR);
        $transferred_case->execute();
        $transferred_case = $transferred_case->fetch();
        return $transferred_case;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function main_party($db, $filing_no, $party_flag)
{
    try {
        if ($party_flag == 'P')
            $party_flag_query = " and party_flag in ('P','A')";
        else
            $party_flag_query = " and party_flag in ('R','D')";

        $query = "select name from e_cases_party where filing_no = ? and party_serial_no = 1 $party_flag_query ";
        $party = $db->prepare($query);
        $party->bindParam(1, $filing_no, PDO::PARAM_STR);
        $party->execute();
        $party_name = $party->fetch();
        return $party_name;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function save_defective_case_detail($db, $schemas, $filing_no, $get_case_detail, $pet_name, $res_name, $case_year, $reg_no, $regis_date, $userid, $location_id, $status = 'P')
{
    $defect_year = $case_year;
    $nullabel = null;
    $defect_regis_date = $regis_date;
    $list_with_defect = 1;
    $filing_no = $get_case_detail['filing_no'];
    $filing_year = $get_case_detail['case_year'];
    $dt_of_filing = date('Y-m-d', strtotime($get_case_detail['dt_of_filing']));
    $case_type = $get_case_detail['case_type_nclat'];
    $main_case_ia_no = $get_case_detail['filingnumberia'];
    $is_partially_defective = $get_case_detail['patially_defective'];
    $transfer_case_type = $get_case_detail['transfer_case_type'];
    $transfer_case_no = $get_case_detail['transfer_case_number'];
    $transfer_case_filing_no = $get_case_detail['transfer_nclat_filing_no'];
    $transfer_case_location = $get_case_detail['transfer_bench_location'];


    $query = "insert into $schemas.case_detail (filing_no,case_no,pet_name,res_name,loginid,status,regis_date,
        dt_of_filing,case_type,entry_date,case_year,filing_year,filing_no_old,location_code,main_case_ia_no,is_partially_defective,
        transfrred_case_filing_no,transfrred_case_location,transfrred_case_type,transfrred_case_type_short,transfrred_case_no,list_with_defect,list_with_defect_regis_date,defect_no,defect_year)
        values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,now(),?,?)";
    $ins = $db->prepare($query);
    $ins->bindParam(1, $filing_no, PDO::PARAM_STR);
    $ins->bindParam(2, $reg_no, PDO::PARAM_STR);
    $ins->bindParam(3, $pet_name, PDO::PARAM_STR);
    $ins->bindParam(4, $res_name, PDO::PARAM_STR);
    $ins->bindParam(5, $userid, PDO::PARAM_STR);
    $ins->bindParam(6, $status, PDO::PARAM_STR);
    $ins->bindParam(7, $nullabel, PDO::PARAM_STR);
    $ins->bindParam(8, $dt_of_filing, PDO::PARAM_STR);
    $ins->bindParam(9, $case_type, PDO::PARAM_STR);
    $ins->bindParam(10, $regis_date, PDO::PARAM_STR);
    $ins->bindParam(11, $case_year, PDO::PARAM_STR);
    $ins->bindParam(12, $filing_year, PDO::PARAM_STR);
    $ins->bindParam(13, $filing_no, PDO::PARAM_STR);
    $ins->bindParam(14, $location_id, PDO::PARAM_STR);
    $ins->bindParam(15, $main_case_ia_no, PDO::PARAM_STR);
    $ins->bindParam(16, $is_partially_defective, PDO::PARAM_STR);
    $ins->bindParam(17, $transfer_case_filing_no, PDO::PARAM_STR);
    $ins->bindParam(18, $transfer_case_location, PDO::PARAM_STR);
    $ins->bindParam(19, $transfer_case_type, PDO::PARAM_STR);
    $ins->bindParam(20, $tr_short, PDO::PARAM_STR);
    $ins->bindParam(21, $transfer_case_no, PDO::PARAM_STR);
    $ins->bindParam(22, $list_with_defect, PDO::PARAM_STR);
//    $ins->bindParam(23, $defect_regis_date, PDO::PARAM_STR);
    $ins->bindParam(23, $nullabel, PDO::PARAM_STR);
    $ins->bindParam(24, $defect_year, PDO::PARAM_STR);
    $res = $ins->execute();
    return $res;
}

function update_defective_e_case_detail($db, $filing_no, $location_id)
{
    $one = 1;
    $query = "update e_case_detail set list_with_defect = ?, allow_refiling = 0 where filing_no = ? and location_id = ?";
    $up = $db->prepare($query);
    $up->bindParam(1, $one, PDO::PARAM_STR);
    $up->bindParam(2, $filing_no, PDO::PARAM_STR);
    $up->bindParam(3, $location_id, PDO::PARAM_STR);
    $res = $up->execute();
    return $res;
}

function get_first_level_scrutiny($db, $schemas, $filing_no)
{
    try {

        $query = "select defects from $schemas.scrutiny where filing_no = ?";
        $scrutiny = $db->prepare($query);
        $scrutiny->bindParam(1, $filing_no, PDO::PARAM_STR);
        $scrutiny->execute();
        $scrutiny = $scrutiny->fetchColumn();
        return $scrutiny;
    } catch (PDOException $ex) {
        echo $ex;
    }
}

function fn_judge_list($db, $schemas, $from_list_date, $bench_id, $presiding)
{

    $sql = "select hon_text,md.desg_name,jm.judge_name,bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm
 Join
 $schemas.master_desg md ON jm.judge_desg_code = md.desg_code
 where bj.from_list_date ='$from_list_date' and bj.bench_no='$bench_id' and jm.judge_code=bj.judge_code ";
    $sth_judge = $db->prepare($sql);
    $sth_judge->execute();
    $judge_data = $sth_judge->fetchAll();
    $coram_name = '';
    foreach ($judge_data as $value) {
        $coram_name .= "<font size='2' >" . $value['hon_text'] . ' ' . $value['judge_name'] . ' (' . $value['desg_name'] . ')';
        //   if ($value['judge_code'] == $presiding) {
        //       $coram_name .= "<font color='red'> (Honorable Judges)</font>";
        //   }
        $coram_name .= "<br>";
    }
    return $coram_name;
}

function get_bench_name($db, $location_id)
{
     $sql = "select a.city_name,b.state_name,a.short_name from mater_location_city as a
left join master_states as b on b.state_id = a.city_id
where a.city_id = ?";
    $bench_query = $db->prepare($sql);
    $bench_query->bindParam(1, $location_id, PDO::PARAM_STR);
    $bench_query->execute();
    return $bench_query->fetch();
}

function categories_of_case($db,$filing_no){
    $sql = "select a.filing_no,a.case_under_dispute,b.category_of_case_under_dispute 
from gst_category_case_under_dispute as a
inner join master_category_of_case_under_dispute as b on a.case_under_dispute = b.id
where a.filing_no = ?";
    $categories = $db->prepare($sql);
    $categories->bindParam(1, $filing_no, PDO::PARAM_STR);
    $categories->execute();
    return $categories->fetchAll();
}

function save_document_uplaod($db,$doctype,$pp_path,$user_id,$subdoctype,$e_reference_no,$filename,$docum_type,$returnfilename,$filename_original,$filing_no,$dsiplay,$scrutiny,$party_name,$listing_date,$order_type){
    $doc_query = "insert into document_upload(doctype,fileupload,loginid,noofpages
                            ,subdoctype,uniqueid,filename,
                            docum_type,transfer_status,returnfilename,original_file,e_reference_no,
                            filing_no,display,scrutiny,
                            document_filed_date,party_name,party_type,party_serial_no,
                            created_at,miscellaneous_ref_no,doc_level,miscellenous_no,
                            diary_no,courtno,fail_status,iscompleted,listing_date,order_type)
                            values (?,?,?,0,?,?,?,?,false,?,?,?,?,?,?,now(),?,0,2,now(),1,9,'','','',false,true,?,?)";
            $doc_query_ex = $db->prepare($doc_query);
            $doc_query_ex->bindParam(1, $doctype, PDO::PARAM_STR);
            $doc_query_ex->bindParam(2, $pp_path, PDO::PARAM_STR);
            $doc_query_ex->bindParam(3, $user_id, PDO::PARAM_STR);
            $doc_query_ex->bindParam(4, $subdoctype, PDO::PARAM_STR);
            $doc_query_ex->bindParam(5, $e_reference_no, PDO::PARAM_STR);
            $doc_query_ex->bindParam(6, $filename, PDO::PARAM_STR);
            $doc_query_ex->bindParam(7, $docum_type, PDO::PARAM_STR);
            $doc_query_ex->bindParam(8, $returnfilename, PDO::PARAM_STR);
            $doc_query_ex->bindParam(9, $filename_original, PDO::PARAM_STR);
            $doc_query_ex->bindParam(10, $e_reference_no, PDO::PARAM_STR);
            $doc_query_ex->bindParam(11, $filing_no, PDO::PARAM_STR);
            $doc_query_ex->bindParam(12, $dsiplay, PDO::PARAM_STR);
            $doc_query_ex->bindParam(13, $scrutiny, PDO::PARAM_STR);
            $doc_query_ex->bindParam(14, $party_name, PDO::PARAM_STR);
            $doc_query_ex->bindParam(15, $listing_date, PDO::PARAM_STR);
            $doc_query_ex->bindParam(16, $order_type, PDO::PARAM_STR);
            $doc_query_ex->execute();
}

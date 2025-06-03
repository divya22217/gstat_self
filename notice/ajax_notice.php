<?php
require_once('../includes/helper.php');
deny_direct_access();
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php";
require "../vendor/autoload.php";
require_once('../object_storage/S3Service.php');
use Dompdf\Dompdf;
$dompdf = new Dompdf();
//ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL); 

session_start();

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
$schema = $_SESSION['schema_name'];
$location_id = $_SESSION['location'];

function get_order_detail($db,$filing_no){
    $query = "select order_number,order_date from e_order_details where filing_no = ?";
    $query_ex = $db->prepare($query);
    $query_ex->bindParam(1,$filing_no,PDO::PARAM_STR);
    $query_ex->execute();
    $data = $query_ex->fetch();
    return $data;
}

function get_last_hearing_details($db,$schema,$filing_no){
    $query = "select a.filing_no,a.court_no,a.bench_no,a.listing_date from $schema.case_proceeding as a
inner join (select filing_no,max(listing_date) as list_date from $schema.case_proceeding where filing_no = ? group by filing_no) as b
on b.filing_no = a.filing_no AND b.list_date = a.listing_date
where a.filing_no = ?";
    $query_ex = $db->prepare($query);
    $query_ex->bindParam(1,$filing_no,PDO::PARAM_STR);
    $query_ex->bindParam(2,$filing_no,PDO::PARAM_STR);
    $query_ex->execute();
    return $query_ex->fetch();
}

function coram($db,$schema,$listing_date,$court_no,$bench_no){
//     $query = "select b.judge_name,c.desg_name from $schema.bench as a
// inner join $schema.master_judge as b on b.judge_code = a.presiding
// inner join $schema.master_desg as c on c.desg_code = b.judge_desg_code
// where a.from_list_date = ? and a.court_no = ? and a.bench_no = ?";
$query = "select STRING_AGG(qrm.queoram , ' ,') as qrmm from (select concat('Hon''ble ',b.judge_name,'(',c.desg_name,')') as queoram from delhi.bench as a
inner join delhi.bench_judge as bm on bm.from_list_date = a.from_list_date and 
bm.court_no = a.court_no and bm.bench_no = a.bench_no
inner join delhi.master_judge as b on b.judge_code = bm.judge_code
inner join delhi.master_desg as c on c.desg_code = b.judge_desg_code
where a.from_list_date = ? and a.court_no = ? and a.bench_no = ?) as qrm";
    $query_ex = $db->prepare($query);
    $query_ex->bindParam(1,$listing_date,PDO::PARAM_STR);
    $query_ex->bindParam(2,$court_no,PDO::PARAM_STR);
    $query_ex->bindParam(3,$bench_no,PDO::PARAM_STR);
    $query_ex->execute();
    return $query_ex->fetchColumn();
}

function get_standard_notice_date($db,$schema,$filing_no){
    $query = "select * from $schema.notice_creation_details where filing_no = ? and notice_type = 3 and digital_sign_status = 1 order by id desc limit 1";
    $query_ex = $db->prepare($query);
    $query_ex->bindParam(1,$filing_no,PDO::PARAM_STR);
    $query_ex->execute();
    return $query_ex->fetch();
}

function notice_type($db,$schema,$notice_type){
    $query = "select name from summon_type where id = ?";
    $query_ex = $db->prepare($query);
    $query_ex->bindParam(1,$notice_type,PDO::PARAM_STR);
    $query_ex->execute();
    return $query_ex->fetchColumn();
}

function location_name($db,$location){
    $query = "select short_name from mater_location_city where city_id = ?";
    $query_ex = $db->prepare($query);
    $query_ex->bindParam(1,$location,PDO::PARAM_STR);
    $query_ex->execute();
    return $query_ex->fetchColumn();
}

function generated_notices($db,$schemas,$filing_no,$notice_type){
    $query = "select to_party_id,id,notice_type from $schemas.notice_creation_details where filing_no = ? and notice_type = ?";
    $query_ex = $db->prepare($query);
    $query_ex->bindParam(1,$filing_no,PDO::PARAM_STR);
    $query_ex->bindParam(2,$notice_type,PDO::PARAM_STR);
    $query_ex->execute();
    return $query_ex->fetchAll();
}

function year_list($year)
{
    for ($i = date('Y'); $i >= 1970; $i--) { ?>
<option <?php if ($year == $i) {
                    echo 'selected';
                } ?> value="<?php echo $i; ?>"><?php echo $i ?></option>
<?php }
}

    

function fn_sms_notice($db_online, $schema, $type, $filing_no, $email_subject, $mobile_msg, $email_text, $notice_id)
{

    try {
        $query_defeect = "select id from $schema.notice_creation_details  where sr_no_notice = '$notice_id' limit 1 ";
        $query_defeect1 = $db_online->prepare($query_defeect);
        $query_defeect1->execute();
        $sr_no_notice_id = $query_defeect1->fetchColumn();
    } catch (PDOException $ex) {
        echo $ex;
        die;
    }

try {
        $ia_ma_filing_data = "select main_case_ia_no from $schema.case_detail  where filing_no = ? limit 1 ";
        $ia_ma_filing_data = $db_online->prepare($ia_ma_filing_data);
        $ia_ma_filing_data->bindParam(1, $filing_no, PDO::PARAM_STR);
        $ia_ma_filing_data->execute();
        $ia_ma_filing_no = $ia_ma_filing_data->fetchColumn();
        if ($ia_ma_filing_no != '') {
            $filing_no = $ia_ma_filing_no;
        }
    } catch (PDOException $ex) {
        echo $ex;
        die;
    }

    if ($filing_no != '') {
        if ($type == '112') {
            $pdfpath = '';
        }
        $data_main = array();

        try {
            $adv_data = $db_online->prepare("select b.rep_name as name,b.email,b.mobile,a.party_flag from e_more_representative as a
        left join e_master_advocate as b ON a.rep_code = b.id
        where filing_no = '$filing_no' and a.rep_code != '0'
        order by a.id asc");
            $adv_data->execute();
            $advocate_arr = $adv_data->fetchAll();
            $data_main = array_merge($advocate_arr, $data_main);
        } catch (PDOException $ex) {
            echo $ex;
            // die;
        }
        try {
            $party_data = $db_online->prepare("select name,email,mobile,party_flag from e_cases_party
        where filing_no = '$filing_no' ");
            $party_data->execute();
            $party_arr = $party_data->fetchAll();
            $data_main = array_merge($party_arr, $data_main);
        } catch (PDOException $ex) {
            echo $ex;
            // die;
        }

        //------------------------Add Representative---------------------------//
         try {
            $bo_data = $db_online->prepare("select name,email,mobilenumber as mobile from gst_ecase_assign_tobo_office  where filingno = '$filing_no' ");
            $bo_data->execute();
            $bo_arr = $bo_data->fetchAll();
            $data_main = array_merge($bo_arr, $data_main);
        } catch (PDOException $ex) {
            echo $ex;
            // die;
        }
        try {
            $nodal_data = $db_online->prepare("select b.rep_name as name,b.email,b.mobile,a.party_flag from e_more_representative_gst_nodal as a
        left join e_master_advocate as b ON  b.id = a.rep_code
        where filing_no = '$filing_no' and a.rep_code != '0'
        order by a.id asc");
            $nodal_data->execute();
            $nodal_arr = $nodal_data->fetchAll();
            $data_main = array_merge($nodal_arr, $data_main);
        } catch (PDOException $ex) {
            echo $ex;
            // die;
        }
        //-----------------------------Representative----------------------//
       
        $cur_date = date('Y-m-d');

       // $link = '';
       // if ($type == '112') {
         //   $link = base64_encode($sr_no_notice_id);
	// }
	 $link = base64_encode($sr_no_notice_id);
        $email_text = $email_text . '.  <a download="download" href="https://uat-cis.gstat.gov.in/gstat/notice_view.php?notice_id=' . $link . '" target = "_blank"> click here </a> This is a computer generated message, Please do not reply';
        if (!empty($data_main) && is_array($data_main)) {
            foreach ($data_main as $val) {
                $mobile = $val['mobile'];
                $email_id = $val['email'];
                $name = $val['name'];
                $party_flag = $val['party_flag'];
                $query = "insert into sms(filing_no,mobile,mobile_msg,email_id,email_subject,email_text,
                name,sms_flag,send_flag,entry_date,sms_type,party_flag,pdf_path)
                VALUES('$filing_no','$mobile','$mobile_msg','$email_id','$email_subject',
                '$email_text','$name','N',0,'$cur_date','$type','$party_flag',$notice_pdf)";
                try {
                    $sql_query = $db_online->prepare($query);
                    $sql_query->execute();
                } catch (PDOException $ex) {
                    echo $ex;
                }
            }
        }
    }
}

function summon_formate($db, $party_ids, $summon_type, $schemas, $data)
{
    $filing_no = $data['filing_no'];
    $query = "select * from e_cases_party where id in ($party_ids)";
    try {
        $query_prepare = $db->prepare($query);
        $query_prepare->execute();
        $dataadddd = $query_prepare->fetchAll();
        $party_details = '';
        if (!empty($dataadddd)) {
            if($summon_type != '6'){
                $sss = 1;
                foreach ($dataadddd as $valla) {
                    $party_details .= $sss . '. ' . $valla['name'] . '<br> Regd. ' . $valla['party_address1'] . ',' . $valla['pin'] . '<br><br>';
                    $sss++;
                }
            }else{
                $sss = 1;
                foreach ($dataadddd as $valla) {
                    $party_details .= $sss . '. ' . $valla['name'] . '<br> Regd. ' . $valla['party_address1'] . ',' . $valla['pin'] . '<br>'.$valla['email'].'<br/>'.$valla['mobile'].'<br/><br>';
                    $sss++;
                }
            }
        }
    } catch (PDOException $ex) {
        echo $ex;
    }

    $case_type_name = '';
    $case_type_desc = '';
    $case_type_id = $data['case_type'];
    try {
        $query = "SELECT case_type_desc,short_name FROM public.case_type where id = ?";
        $query_prepare = $db->prepare($query);
        $query_prepare->bindParam(1, $case_type_id, PDO::PARAM_STR);
        $query_prepare->execute();
        $case_type_data = $query_prepare->fetch();

        $case_type_name = $case_type_data['short_name'];
        $case_type_desc = $case_type_data['case_type_desc'];
    } catch (PDOException $ex) {
        echo $ex;
        $case_type_name = '';
        $case_type_desc = '';
    }

    try {
        $query_crn = "select b.crn_number from public.e_case_detail as a join public.e_order_details as b on a.e_reference_no = b.e_reference_no where a.filing_no = ?";
        $query_crn = $db->prepare($query_crn);
        $query_crn->bindParam(1, $filing_no, PDO::PARAM_STR);
        $query_crn->execute();
        $complaint_no = $query_crn->fetchColumn();
    } catch (PDOException $ex) {
        echo $ex;
        $complaint_no = '';
    }


    if ($complaint_no != '') {
        $complaint_no . '<b>Appellate Authority ARN/CRN. ' . $complaint_no . '</b>';
    }


    $defectd = '';
    try {
        $query_defeect = "select list_with_defect,regis_date,main_case_ia_no,location_code from $schemas.case_detail  where filing_no = ?";
        $query_defeect1 = $db->prepare($query_defeect);
        $query_defeect1->bindParam(1, $filing_no, PDO::PARAM_STR);
        $query_defeect1->execute();
        $defect_listed = $query_defeect1->fetch();
        $location_code = $defect_listed['location_code'];
        if ($defect_listed['list_with_defect'] == 1 && empty($defect_listed['regis_date'])) {
            $defectd = ' D';
        }
    } catch (PDOException $ex) {
        echo $ex;
        die;
    }

    $ia_ma_filing_no = $defect_listed['main_case_ia_no'];
    $case_no_child = '';
    // Child Parent Condition
    if ($ia_ma_filing_no != '') {
        $iama_no = $ia_ma_filing_no;
        $mainCno = 'select case_no,case_year,short_name from $schemas.case_detail inner join case_type on case_type=id where filing_no= ?;';
        $mainCrs = $db->prepare($mainCno);
        $mainCrs->bindParam(1, $iama_no, PDO::PARAM_STR);
        $mainCrs->execute();
        $data_ia_main = $mainCrs->fetch();
        $case_no_child .= '<br>IN<br> ' . $data_ia_main['short_name'] . '/' . $data_ia_main['case_no'] . '/' . $data_ia_main['case_year'];
    }
    //


    if ($summon_type == '1') {
        
        $notice_html_data = $data['notice_html_data'];
        $listing_date = $get_coram = '';
        $messahe_data = '<!DOCTYPE html>
        <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tribunal</title>

    <style>
    .watermark {
        opacity: 0.09;
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 0;
        margin: auto;
        width: 500px;
    }
    </style>

</head>

<body style="font-size:16px; font-family: Arial, Helvetica, sans-serif; line-height: 1.2; padding-left: 50px; margin: 0;">
<img src="receipt_watermark.png" class="watermark">
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>GSTAT</b></p>
    <p style="text-align:center; line-height: 0.1;font-size: 22px;"><b>'. $_SESSION['bench_name'] .'</b></p>
    <hr style="border-top: 1px solid black;">
    <div style="padding-left: 80px;padding-right: 50px;">
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; text-align: right;width: 60%; color:red;">
                        Sl. No. ' . $data['sr_no_notice'] . '
                    </td>
                    <td style="vertical-align: top; color:red;">
                        Dated ' . date("d/m/Y") . '
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0; font-size: 18px;"><u><b>Notice</b></u></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px; margin-bottom:0px;"><b>' . $case_type_name . ' No.   ' . $defectd  . '  ' . $data['case_no'] . '/'.location_name($db,$location_code).'/' . $data['case_year'] . $case_no_child . '</b></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px; margin-bottom: 0px;margin-top: 10px;;"><b>Filing No. ' . $filing_no . '</b></p><table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['plaintif'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">... Appellant/Applicant</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0;">VS</p>
		<table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['defendant'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">..........Respondent</p>
                    </td>
                </tr>
            </tbody>
        </table>




        <div style="text-align: justify; margin-top: 30px;">
            <p style="font-size: 18px; line-height: 1.4;"><b>To,<br>
                   ' . $party_details . '
                </b></p>
            <p>' . $notice_html_data . '</p>
        </div>

        <table style="line-height: 1.2; margin-bottom: 15px; margin-top: 50px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 100%;"></td>
                    <td style="vertical-align: top; text-align: center;">
                        <p style="line-height: 1.4;"><b>Regards,</b><br/><b>Registrar<br>
                                <span style="white-space: nowrap; font-size: 14px;">GSTAT,</span><br>
                                '. $_SESSION['bench_name'] .'
                            </b></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p  style="line-height: 1.4; text-indent: 50px;color:red;text-align:center;font-size: 14px;"><b><u><i>'.$_SESSION['user_actual_name'].'</i></u></b></p>
    </div>

</body>
</html>';
        // echo $messahe_data;
return array('notice_html'=>$messahe_data,'coram'=>array(),'listing_date'=>''); 
    } else if ($summon_type == '3') {
        $hon = "Hon'ble";
        $filing_no = $data['filing_no'];
        $order_detail = get_order_detail($db,$filing_no);
        
        $order_no = $order_detail['order_number'];
        $order_date = (!empty($order_detail['order_date']))?$order_detail['order_date']:'';
        $last_hearing_details = get_last_hearing_details($db,$schemas,$filing_no);
        
        if(!empty($last_hearing_details)){
            $listing_date = $last_hearing_details['listing_date'];
            $list_date = (!empty($last_hearing_details['listing_date']))?date('dS-F-Y',strtotime($last_hearing_details['listing_date'])):'';
            $court_no = $last_hearing_details['court_no'];
            $bench_no = $last_hearing_details['bench_no'];
        $get_coram = coram($db,$schemas,$listing_date,$court_no,$bench_no);
        
        //$judge_name = $get_coram['judge_name'];
        //$desg_name = $get_coram['desg_name'];
        }
        $case_fixed_hearing_on = date('dS-F-Y', strtotime($data['case_fixed_hearing_on']));
        $case_fixed_hearing_on = explode('-', $case_fixed_hearing_on);
        $hearing_court_date = date('dS-F-Y', strtotime($data['hearing_court_date']));
        $hearing_court_date = explode('-', $hearing_court_date);
        $against_order_date = date('dS-F-Y', strtotime($data['against_order_date']));
        $against_order_date = explode('-', $against_order_date);

        $subject_regards = '';
        if ($ia_ma_filing_no == '') {
            $subject_regards = ' u/s 44 of the U.P. Real Estate ( regulation & development ) Act 2016 filed against the order dated <b>' . ltrim($against_order_date[0], 0) . '  day of  ' . $against_order_date[1] . ' ' . $against_order_date[2] . '</b> passed by the U.P. Real Estate Regulatory Authority.';
        }


        $messahe_data = '<!DOCTYPE html>
        <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tribunal</title>
    <style>
    .watermark {
        opacity: 0.09;
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 0;
        margin: auto;
        width: 500px;
    }
    </style>
</head>

<body style="font-size:16px; font-family: Arial, Helvetica, sans-serif; line-height: 1.2; padding-left: 50px; margin: 0;">
<img src="receipt_watermark.png" class="watermark">
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>GSTAT</b></p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>'.$_SESSION['bench_name'].'</b></p>
    <p style="text-align:center; line-height: 0.1;"><u><b></b></u></p>
    <hr style="border-top: 1px solid black;">
    <div style="padding-left: 80px;padding-right: 50px;">
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;color:red;">
                        Sl. No. ' . $data['sr_no_notice'] . '
                    </td>
                    <td style="vertical-align: top; text-align: right;color:red;">
                        Dated ' . date('d/m/Y') . '
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0; font-size: 18px;"><u><b>Notice</b></u></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px; margin-bottom:0px;"><b>' . $case_type_name . ' No.   ' . $defectd  . '  ' . $data['case_no'] . '/'.location_name($db,$location_code).'/' . $data['case_year'] . $case_no_child . '</b></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px;margin-bottom: 0px;margin-top: 10px;;"><b> Filing No.   ' . $data['filing_no'] . '</b></p>
       <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['plaintif'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">... Appellant/Applicant</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0;">VS</p>
		<table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['defendant'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">..........Respondent</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: justify; margin-top: 30px;">
            <p style="font-size: 18px; line-height: 1.4;"><b>To,<br> ' . $party_details . '</b></p>
            <p style="line-height: 1.4;">Subject- Appeal before Goods and services Tax Appellate Tribunal constituted under section 109 of the Central Goods and Services Tax Act, 2017 filed against the order no '.$order_no.' dated '.$order_date.' passed by Appellate/Revisional Authority under Section 107/108 of the Act-Regarding </p>
                <p style="line-height: 1.4;">You are hereby informed that the said appeal, (a copy/link of the same has already been sent on your e-mail/phone), was taken up for hearing on '.$list_date.'. You are therefore directed to be present before the bench of '.$get_coram.', GSTAT-Bench('.$_SESSION['bench_name'].') either in person or through Authorized representative on date and time specified below. Please note that in the event of failure to appear on the appointed date and time as aforesaid may entail passing of an ex parte order in the said matter.</p>
            
            <p  style="line-height: 1.4;"><b>'. ltrim($case_fixed_hearing_on[0], 0) . '  day of  ' . $case_fixed_hearing_on[1] . ' ' . $case_fixed_hearing_on[2] . '</b>.</p>
        </div>
        <table style="line-height: 1.2; margin-bottom: 15px; margin-top: 50px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 100%;"></td>
                    <td style="vertical-align: top; text-align: center;">
                        <p style="line-height: 1.4;"><b>Registrar<br><span style="white-space: nowrap; font-size: 14px;">GSTAT,</span><br> '.$_SESSION['bench_name'].' </b></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p  style="line-height: 1.4; text-indent: 50px;color:red;text-align:center;font-size: 14px;"><b><u><i>'.$_SESSION['user_actual_name'].'</i></u></b></p>
    </div>
</body>
</html>';
        // echo $messahe_data;
        return array('notice_html'=>$messahe_data,'coram'=>$get_coram,'listing_date'=>$listing_date); 
        //return $messahe_data;
    }else if ($summon_type == '4') {
        $hon = "Hon'ble";
        $filing_no = $data['filing_no'];
        $order_detail = get_order_detail($db,$filing_no);
        
        $order_no = $order_detail['order_number'];
        $order_date = (!empty($order_detail['order_date']))?$order_detail['order_date']:'';
        $last_hearing_details = get_last_hearing_details($db,$schemas,$filing_no);
        $standard_notice_data = get_standard_notice_date($db,$schemas,$filing_no);
        
        $before_bench_old = $standard_notice_data['before_bench'];
        $notice_date_old = (!empty($standard_notice_data['send_date']))?date('d/m/Y',strtotime($standard_notice_data['send_date'])):'';
        if(!empty($last_hearing_details)){
            $listing_date = $last_hearing_details['listing_date'];
            $list_date = (!empty($last_hearing_details['listing_date']))?date('dS-F-Y',strtotime($last_hearing_details['listing_date'])):'';
            $court_no = $last_hearing_details['court_no'];
            $bench_no = $last_hearing_details['bench_no'];
        $get_coram = coram($db,$schemas,$listing_date,$court_no,$bench_no);
        
        //$judge_name = $get_coram['judge_name'];
        //$desg_name = $get_coram['desg_name'];
        }
        
        $case_fixed_hearing_on = date('dS-F-Y', strtotime($data['case_fixed_hearing_on']));
        $case_fixed_hearing_on = explode('-', $case_fixed_hearing_on);
        $hearing_court_date = date('dS-F-Y', strtotime($data['hearing_court_date']));
        $hearing_court_date = explode('-', $hearing_court_date);
        $against_order_date = date('dS-F-Y', strtotime($data['against_order_date']));
        $against_order_date = explode('-', $against_order_date);

        $subject_regards = '';
        if ($ia_ma_filing_no == '') {
            $subject_regards = '';
        }


        $messahe_data = '<!DOCTYPE html>
        <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tribunal</title>
    <style>
    .watermark {
        opacity: 0.09;
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 0;
        margin: auto;
        width: 500px;
    }
    </style>
</head>

<body style="font-size:16px; font-family: Arial, Helvetica, sans-serif; line-height: 1.2; padding-left: 50px; margin: 0;">
<img src="receipt_watermark.png" class="watermark">
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>GSTAT</b></p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>'.$_SESSION['bench_name'].'</b></p>
    <p style="text-align:center; line-height: 0.1;"><u><b></b></u></p>
    <hr style="border-top: 1px solid black;">
    <div style="padding-left: 80px;padding-right: 50px;">
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;color:red;">
                        Sl. No. ' . $data['sr_no_notice'] . '
                    </td>
                    <td style="vertical-align: top; text-align: right;color:red;">
                        Dated ' . date('d/m/Y') . '
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0; font-size: 18px;"><u><b>Notice</b></u></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px; margin-bottom:0px;"><b>' . $case_type_name . ' No.   ' . $defectd  . '  ' . $data['case_no'] . '/'.location_name($db,$location_code).'/' . $data['case_year'] . $case_no_child . '</b></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px;margin-bottom: 0px;margin-top: 10px;;"><b> Filing No.   ' . $data['filing_no'] . '</b></p>
       <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['plaintif'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">... Appellant/Applicant</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0;">VS</p>
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['defendant'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">..........Respondent</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: justify; margin-top: 30px;">
            <p style="font-size: 18px; line-height: 1.4;"><b>To,<br> ' . $party_details . '</b></p>
            <p style="line-height: 1.4;">Subject- Appeal before Goods and services Tax Appellate Tribunal constituted under section 109 of the Central Goods and Services Tax Act, 2017 filed against the order no '.$order_no.' dated '.$order_date.' passed by Appellate/Revisional Authority under Section 107/108 of the Act-Regarding </p>
                <p style="line-height: 1.4;">Whereas you have failed to appear before the bench of '.$before_bench_old.', GSTAT-Bench('.$_SESSION['bench_name'].') either in person or through Authorized representative on the appointed date and time communicated vide this office notice dated '.$notice_date_old.'</p>
                <p style="line-height: 1.4;">The bench is pleased to accord yet another opportunity on '.ltrim($case_fixed_hearing_on[0], 0) . '  day of  ' . $case_fixed_hearing_on[1] . ' ' . $case_fixed_hearing_on[2].' for appearance in person or through Authorized representative before the bench of '.$get_coram.', GSTAT-Bench('.$_SESSION['bench_name'].'). Please note that in the event of failure to appear on appointed date and Time as aforesaid may entail passing of an ex parte order in the said matter.</p>
            
            <p  style="line-height: 1.4;"><b>'. ltrim($case_fixed_hearing_on[0], 0) . '  day of  ' . $case_fixed_hearing_on[1] . ' ' . $case_fixed_hearing_on[2] . '</b>.</p>
        </div>
        <table style="line-height: 1.2; margin-bottom: 15px; margin-top: 50px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 100%;"></td>
                    <td style="vertical-align: top; text-align: center;">
                        <p style="line-height: 1.4;"><b>Registrar<br><span style="white-space: nowrap; font-size: 14px;">GSTAT,</span><br> '.$_SESSION['bench_name'].' </b></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p  style="line-height: 1.4; text-indent: 50px;color:red;text-align:center;font-size: 14px;"><b><u><i>'.$_SESSION['user_actual_name'].'</i></u></b></p>
    </div>
</body>
</html>';
        // echo $messahe_data;
        return array('notice_html'=>$messahe_data,'coram'=>$get_coram,'listing_date'=>$listing_date); 
        //return $messahe_data;
    }else if ($summon_type == '5') {
        $hon = "Hon'ble";
        $filing_no = $data['filing_no'];
        $order_detail = get_order_detail($db,$filing_no);
        
        $order_no = $order_detail['order_number'];
        $order_date = (!empty($order_detail['order_date']))?$order_detail['order_date']:'';
        $last_hearing_details = get_last_hearing_details($db,$schemas,$filing_no);
        $standard_notice_data = get_standard_notice_date($db,$schemas,$filing_no);
        
        $before_bench_old = $standard_notice_data['before_bench'];
        $listing_date_old = (!empty($standard_notice_data['listing_date']))?date('d/m/Y',strtotime($standard_notice_data['listing_date'])):'';
        $notice_date_old = (!empty($standard_notice_data['send_date']))?date('d/m/Y',strtotime($standard_notice_data['send_date'])):'';
        if(!empty($last_hearing_details)){
            $listing_date = $last_hearing_details['listing_date'];
            $list_date = (!empty($last_hearing_details['listing_date']))?date('dS-F-Y',strtotime($last_hearing_details['listing_date'])):'';
            $court_no = $last_hearing_details['court_no'];
            $bench_no = $last_hearing_details['bench_no'];
        $get_coram = coram($db,$schemas,$listing_date,$court_no,$bench_no);
        
        //$judge_name = $get_coram['judge_name'];
        //$desg_name = $get_coram['desg_name'];
        }
        
        $case_fixed_hearing_on = date('dS-F-Y', strtotime($data['case_fixed_hearing_on']));
        $case_fixed_hearing_on = explode('-', $case_fixed_hearing_on);
        $hearing_court_date = date('dS-F-Y', strtotime($data['hearing_court_date']));
        $hearing_court_date = explode('-', $hearing_court_date);
        $against_order_date = date('dS-F-Y', strtotime($data['against_order_date']));
        $against_order_date = explode('-', $against_order_date);

        $subject_regards = '';
        if ($ia_ma_filing_no == '') {
            $subject_regards = '';
        }


        $messahe_data = '<!DOCTYPE html>
        <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tribunal</title>
    <style>
    .watermark {
        opacity: 0.09;
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 0;
        margin: auto;
        width: 500px;
    }
    </style>
</head>

<body style="font-size:16px; font-family: Arial, Helvetica, sans-serif; line-height: 1.2; padding-left: 50px; margin: 0;">
<img src="receipt_watermark.png" class="watermark">
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>GSTAT</b></p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>'.$_SESSION['bench_name'].'</b></p>
    <p style="text-align:center; line-height: 0.1;"><u><b></b></u></p>
    <hr style="border-top: 1px solid black;">
    <div style="padding-left: 80px;padding-right: 50px;">
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;color:red;">
                        Sl. No. ' . $data['sr_no_notice'] . '
                    </td>
                    <td style="vertical-align: top; text-align: right;color:red;">
                        Dated ' . date('d/m/Y') . '
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0; font-size: 18px;"><u><b>Notice</b></u></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px; margin-bottom:0px;"><b>' . $case_type_name . ' No.   ' . $defectd  . '  ' . $data['case_no'] . '/'.location_name($db,$location_code).'/' . $data['case_year'] . $case_no_child . '</b></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px;margin-bottom: 0px;margin-top: 10px;;"><b> Filing No.   ' . $data['filing_no'] . '</b></p>
       <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['plaintif'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">... Appellant/Applicant</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0;">VS</p>
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['defendant'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">..........Respondent</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: justify; margin-top: 30px;">
            <p style="font-size: 18px; line-height: 1.4;"><b>To,<br> ' . $party_details . '</b></p>
            <p style="line-height: 1.4;">Subject- Appeal before Goods and services Tax Appellate Tribunal constituted under section 109 of the Central Goods and Services Tax Act, 2017 filed against the order no '.$order_no.' dated '.$order_date.' passed by Appellate/Revisional Authority under Section 107/108 of the Act-Regarding </p>
                <p style="line-height: 1.4;">Whereas the said matter  was fixed for hearing before the the bench of  '.$before_bench_old.', GSTAT-Bench('.$_SESSION['bench_name'].') on '.$listing_date_old.' communicated vide this office notice dated  '.$notice_date_old.'</p>
                <p style="line-height: 1.4;">Whereas the said matter could not be taken on the appointed date and time, you are here by directed to appear in person or through Authorized representative before the bench of '.$get_coram.', GSTAT-Bench('.$_SESSION['bench_name'].') at '.ltrim($case_fixed_hearing_on[0], 0) . '  day of  ' . $case_fixed_hearing_on[1] . ' ' . $case_fixed_hearing_on[2].' . Please note that in the event of failure to appear on appointed date and Time as aforesaid may entail passing of an ex parte order in the said matter.</p>
        </div>
        <table style="line-height: 1.2; margin-bottom: 15px; margin-top: 50px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 100%;"></td>
                    <td style="vertical-align: top; text-align: center;">
                        <p style="line-height: 1.4;"><b>Registrar<br><span style="white-space: nowrap; font-size: 14px;">GSTAT,</span><br> '.$_SESSION['bench_name'].' </b></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p  style="line-height: 1.4; text-indent: 50px;color:red;text-align:center;font-size: 14px;"><b><u><i>'.$_SESSION['user_actual_name'].'</i></u></b></p>
    </div>
</body>
</html>';
        // echo $messahe_data;
        return array('notice_html'=>$messahe_data,'coram'=>$get_coram,'listing_date'=>$listing_date);  
        //return $messahe_data;
    }else if ($summon_type == '8') {
        $hon = "Hon'ble";
        $filing_no = $data['filing_no'];
        $order_detail = get_order_detail($db,$filing_no);
        $time_to_present = $data['time_to_present'];
        $ask_document_person = $data['ask_document_person'];
        $name_of_documents = $data['name_of_documents'];
        
        $time_to_present = (!empty($time_to_present))?date('h:i A',strtotime($time_to_present)):'';
        
        $order_no = $order_detail['order_number'];
        $order_date = (!empty($order_detail['order_date']))?$order_detail['order_date']:'';
        $last_hearing_details = get_last_hearing_details($db,$schemas,$filing_no);
        $standard_notice_data = get_standard_notice_date($db,$schemas,$filing_no);
        
        $before_bench_old = $standard_notice_data['before_bench'];

        if(!empty($last_hearing_details)){
            $listing_date = $last_hearing_details['listing_date'];
            $list_date = (!empty($last_hearing_details['listing_date']))?date('dS-F-Y',strtotime($last_hearing_details['listing_date'])):'';
            $court_no = $last_hearing_details['court_no'];
            $bench_no = $last_hearing_details['bench_no'];
        $get_coram = coram($db,$schemas,$listing_date,$court_no,$bench_no);
        
        //$judge_name = $get_coram['judge_name'];
        //$desg_name = $get_coram['desg_name'];
        }


        
        $hearing_date_show = date('d/m/Y', strtotime($data['case_fixed_hearing_on']));
        $case_fixed_hearing_on = date('dS-F-Y', strtotime($data['case_fixed_hearing_on']));
        $case_fixed_hearing_on = explode('-', $case_fixed_hearing_on);
        $hearing_court_date = date('dS-F-Y', strtotime($data['hearing_court_date']));
        $hearing_court_date = explode('-', $hearing_court_date);
        $against_order_date = date('dS-F-Y', strtotime($data['against_order_date']));
        $against_order_date = explode('-', $against_order_date);

        $subject_regards = '';
        if ($ia_ma_filing_no == '') {
            $subject_regards = '';
        }

        $dynamic_text = '';

        if($ask_document_person == 1){
            $dynamic_text = '<p style="line-height: 1.4;">Whereas the bench of '.$get_coram.', GSTAT-Bench('.$_SESSION['bench_name'].')in connection with aforesaid matter requires you to submit the following document/s on or before '.$hearing_date_show.'. You are, accordingly, hereby directed to submit/produce before the said bench the said document/s physically.</p>';
        }
        if($ask_document_person == 2){
            $dynamic_text = '<p style="line-height: 1.4;">Whereas the bench of  '.$get_coram.', GSTAT-Bench('.$_SESSION['bench_name'].') in connection with aforesaid matter requires your presence before the said Bench in person on '.$hearing_date_show.' and '.$time_to_present.'</p>';
        }
        if($ask_document_person == 3){
            $dynamic_text = '<p style="line-height: 1.4;">Whereas the bench of  '.$get_coram.', GSTAT-Bench('.$_SESSION['bench_name'].') in connection with aforesaid matter requires your presence before the said Bench in person on '.$hearing_date_show.' and '.$time_to_present.' and </p>
                <p style="line-height: 1.4;">Whereas the bench of '.$get_coram.', GSTAT-Bench('.$_SESSION['bench_name'].')in connection with aforesaid matter requires you to submit the following document/s on or before '.$hearing_date_show.'. You are, accordingly, hereby directed to submit/produce before the said bench the said document/s physically.</p>';
        }


        $messahe_data = '<!DOCTYPE html>
        <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tribunal</title>
    <style>
    .watermark {
        opacity: 0.09;
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 0;
        margin: auto;
        width: 500px;
    }
    </style>
</head>

<body style="font-size:16px; font-family: Arial, Helvetica, sans-serif; line-height: 1.2; padding-left: 50px; margin: 0;">
<img src="receipt_watermark.png" class="watermark">
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>GSTAT</b></p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>'.$_SESSION['bench_name'].'</b></p>
    <p style="text-align:center; line-height: 0.1;"><u><b></b></u></p>
    <hr style="border-top: 1px solid black;">
    <div style="padding-left: 80px;padding-right: 50px;">
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;color:red;">
                        Sl. No. ' . $data['sr_no_notice'] . '
                    </td>
                    <td style="vertical-align: top; text-align: right;color:red;">
                        Dated ' . date('d/m/Y') . '
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0; font-size: 18px;"><u><b>Notice</b></u></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px; margin-bottom:0px;"><b>' . $case_type_name . ' No.   ' . $defectd  . '  ' . $data['case_no'] . '/'.location_name($db,$location_code).'/' . $data['case_year'] . $case_no_child . '</b></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px;margin-bottom: 0px;margin-top: 10px;;"><b> Filing No.   ' . $data['filing_no'] . '</b></p>
       <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['plaintif'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">... Appellant/Applicant</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0;">VS</p>
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['defendant'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">..........Respondent</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: justify; margin-top: 30px;">
            <p style="font-size: 18px; line-height: 1.4;"><b>To,<br> ' . $party_details . '</b></p>
            <p style="line-height: 1.4;">Subject- Appeal before Goods and services Tax Appellate Tribunal constituted under section 109 of the Central Goods and Services Tax Act, 2017 filed against the order no '.$order_no.' dated '.$order_date.' passed by Appellate/Revisional Authority under Section 107/108 of the Act-Regarding </p>'.$dynamic_text.'
                 <p style="line-height: 1.4;">Please note that failure to appear before the bench as aforesaid may entail action in terms of Section ___ of Bharatiya Nyaya Sanhita, 2024.</p>
                 <div>Name of the Requested Document:</div><div>'.$name_of_documents.'</div>
        </div>
        <table style="line-height: 1.2; margin-bottom: 15px; margin-top: 50px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 100%;"></td>
                    <td style="vertical-align: top; text-align: center;">
                        <p style="line-height: 1.4;"><b>Registrar<br><span style="white-space: nowrap; font-size: 14px;">GSTAT,</span><br> '.$_SESSION['bench_name'].' </b></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p  style="line-height: 1.4; text-indent: 50px;color:red;text-align:center;font-size: 14px;"><b><u><i>'.$_SESSION['user_actual_name'].'</i></u></b></p>
    </div>
</body>
</html>';
        // echo $messahe_data;
        return array('notice_html'=>$messahe_data,'coram'=>$get_coram,'listing_date'=>$listing_date);  
        //return $messahe_data;
    }else if ($summon_type == '6') {
        $hon = "Hon'ble";
        $filing_no = $data['filing_no'];
        $order_detail = get_order_detail($db,$filing_no);
        
        $order_no = $order_detail['order_number'];
        $order_date = (!empty($order_detail['order_date']))?$order_detail['order_date']:'';
        $last_hearing_details = get_last_hearing_details($db,$schemas,$filing_no);
        $standard_notice_data = get_standard_notice_date($db,$schemas,$filing_no);
        
        $before_bench_old = $standard_notice_data['before_bench'];
        $listing_date_old = (!empty($standard_notice_data['listing_date']))?date('d/m/Y',strtotime($standard_notice_data['listing_date'])):'';
        $notice_date_old = (!empty($standard_notice_data['send_date']))?date('d/m/Y',strtotime($standard_notice_data['send_date'])):'';
        if(!empty($last_hearing_details)){
            $listing_date = $last_hearing_details['listing_date'];
            $list_date = (!empty($last_hearing_details['listing_date']))?date('dS-F-Y',strtotime($last_hearing_details['listing_date'])):'';
            $court_no = $last_hearing_details['court_no'];
            $bench_no = $last_hearing_details['bench_no'];
        $get_coram = coram($db,$schemas,$listing_date,$court_no,$bench_no);
        
        //$judge_name = $get_coram['judge_name'];
        //$desg_name = $get_coram['desg_name'];
        }
        
        $case_fixed_hearing_on = date('dS-F-Y', strtotime($data['case_fixed_hearing_on']));
        $case_fixed_hearing_on = explode('-', $case_fixed_hearing_on);
        $hearing_court_date = date('dS-F-Y', strtotime($data['hearing_court_date']));
        $hearing_court_date = explode('-', $hearing_court_date);
        $against_order_date = date('dS-F-Y', strtotime($data['against_order_date']));
        $against_order_date = explode('-', $against_order_date);

        $subject_regards = '';
        if ($ia_ma_filing_no == '') {
            $subject_regards = '';
        }


        $messahe_data = '<!DOCTYPE html>
        <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tribunal</title>
    <style>
    .watermark {
        opacity: 0.09;
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 0;
        margin: auto;
        width: 500px;
    }
    </style>
</head>

<body style="font-size:16px; font-family: Arial, Helvetica, sans-serif; line-height: 1.2; padding-left: 50px; margin: 0;">
<img src="receipt_watermark.png" class="watermark">
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 1.0; color: #666; font-size: 12px;">&nbsp;</p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>GSTAT</b></p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>'.$_SESSION['bench_name'].'</b></p>
    <p style="text-align:center; line-height: 0.1;"><u><b></b></u></p>
    <hr style="border-top: 1px solid black;">
    <div style="padding-left: 80px;padding-right: 50px;">
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;color:red;">
                        Sl. No. ' . $data['sr_no_notice'] . '
                    </td>
                    <td style="vertical-align: top; text-align: right;color:red;">
                        Dated ' . date('d/m/Y') . '
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0; font-size: 18px;"><u><b>Notice</b></u></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px; margin-bottom:0px;"><b>' . $case_type_name . ' No.   ' . $defectd  . '  ' . $data['case_no'] . '/'.location_name($db,$location_code).'/' . $data['case_year'] . $case_no_child . '</b></p>
        <p style="text-align:center; line-height: 1.0; font-size: 22px;margin-bottom: 0px;margin-top: 10px;;"><b> Filing No.   ' . $data['filing_no'] . '</b></p>
       <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['plaintif'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">... Appellant/Applicant</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0;">VS</p>
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 60%;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $data['defendant'] . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">..........Respondent</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: justify; margin-top: 30px;">
            <p style="font-size: 18px; line-height: 1.4;"><b>To,<br> ' . $party_details . '</b></p>
            <p style="line-height: 1.4;">Subject- Appeal before Goods and services Tax Appellate Tribunal constituted under section 109 of the Central Goods and Services Tax Act, 2017 filed against the order no '.$order_no.' dated '.$order_date.' passed by Appellate/Revisional Authority under Section 107/108 of the Act-Regarding </p>
                <p style="line-height: 1.4;">Whereas the bench of '.$hon . ' '.$judge_name.'('.$desg_name.'), GSTAT-Bench('.$_SESSION['bench_name'].') in connection with aforesaid matter requires your presence before the said Bench in person on '.ltrim($case_fixed_hearing_on[0], 0) . '  day of  ' . $case_fixed_hearing_on[1] . ' ' . $case_fixed_hearing_on[2].' and/or 
                    Whereas the bench of '.$get_coram.', GSTAT-Bench('.$_SESSION['bench_name'].') in connection with aforesaid matter requires you to submit the following document/s on or before  '.ltrim($case_fixed_hearing_on[0], 0) . '  day of  ' . $case_fixed_hearing_on[1] . ' ' . $case_fixed_hearing_on[2].'. You are, accordingly, hereby directed to submit/produce before the said bench the said document/s physically. 
                    </p>
                <p style="line-height: 1.4;">Please note that failure to appear before the bench as aforesaid may entail action in terms of Section ___ of Bharatiya Nyaya Sanhita, 2024.</p>
        </div>
        <table style="line-height: 1.2; margin-bottom: 15px; margin-top: 50px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; width: 100%;"></td>
                    <td style="vertical-align: top; text-align: center;">
                        <p style="line-height: 1.4;"><b>Registrar<br><span style="white-space: nowrap; font-size: 14px;">GSTAT,</span><br> '.$_SESSION['bench_name'].' </b></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p  style="line-height: 1.4; text-indent: 50px;color:red;text-align:center;font-size: 14px;"><b><u><i>'.$_SESSION['user_actual_name'].'</i></u></b></p>
    </div>
</body>
</html>';
        // echo $messahe_data;
        return array('notice_html'=>$messahe_data,'coram'=>$get_coram,'listing_date'=>$listing_date);  
        //return $messahe_data;
    }
}

function fn_summon_notice_status($schemas, $db, $filing_no)
{
    $summon_notice_status = 'summon_notice_status + 1';
    $sql1 = $db->prepare("update $schemas.case_detail set summon_notice_status = summon_notice_status + 1 where filing_no = ?");
    $sql1->bindParam(1, $filing_no, PDO::PARAM_INT);
    return $sql1->execute();
}

function case_type($db)
{
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

$case_type_data = case_type($db);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    if ($_POST['action'] == 'select_type') {
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
                                    $selected = '';
                                    if ($val['id'] == '1') {
                                        $selected = 'selected';
                                    }
                                    echo '<option ' . $selected . ' value="' . $val['id'] . '">' . $val['case_type_desc'] . '(' . $val['short_name'] . ')' . '</option>';
                                }
                            } ?>
            </select>
        </div>
        <div class="col-sm-4">
            <label>Enter Case No.</label>
            <input type="text" name="case_no" value="" id="case_no" class="form-control required">
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
    } else if ($_POST['action'] == 'search_filing') {
        $schema = $_SESSION['schema_name'];
        if ($_POST['search_by'] == '1') {
            $filing_no = $_POST['dairy_no'] . $_POST['dairy_year'];
            $where = array('location_code' => $_SESSION['location'], 'filing_no' => $filing_no);
            $where_likes = array();
        } else if ($_POST['search_by'] == '2') {
            $where = array('location_code' => $_SESSION['location'], 'case_type' => $_POST['case_type'], 'case_no' => $_POST['case_no'], 'case_year' => $_POST['case_year']);
            $where_likes = array();
        }
        $data = get_data($db, $schema . '.case_detail', $where, $where_likes, '*', 'DESC', 'filing_no');

        if (!empty($data) && is_array($data)) {
            $ii = 1;

            foreach ($data as $value) {
                $filiddfd = $value['filing_no'];
            ?>

<div class="col-md-12">
    <div class="col-md-6">
        <div class="row add_panel">
            <a onclick="generate_notice_popup('<?php echo $filiddfd; ?>')" class="model_form btn btn-primary"
                style="margin-bottom: 18px;">
                <i class="glyphicon glyphicon-plus"></i> Generate Notice</a>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row add_panel">
            <h2 id="message_results"></h2>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php

                $data_case_type = get_data($db, 'public.case_type', array('display' => 'TRUE', 'id' => $value['case_type']));
                ?>

<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444; color:#fff;">
        <tr role="row">
            <th>Filing No</th>
            <th>Case No</th>
            <th>Cause Title</th>
            <th>Date of Filing</th>
        </tr>
    </thead>
    <tbody>
        <tr role="row">
            <td> <?php echo $value['filing_no']; ?></td>
            <td> <?php echo $data_case_type[0]['case_type_desc'] . '/' . $value['case_no'] . '/' .location_name($db,$value['location_code']). '/'.$value['case_year']; ?>
            </td>

            <td>
                <span
                    style="color: red;"><?php echo $value['pet_name'] . ' </span> vs <span style="color: red;">' . $value['res_name']; ?></span>
            </td>
            <td> <?php echo date('d/m/Y', strtotime($value['dt_of_filing'])); ?></td>
        </tr>
    </tbody>

</table>


<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead style="background-color:#444; color:#fff;">
        <tr role="row">
            <th>S.No</th>
            <th>Notice Type</th>
            <th>Case No</th>
            <th>Date of Notice</th>
            <th style="width: 180px;">Action</th>
        </tr>
    </thead>
    <tbody>


        <?php

                        // $data_notice = get_data($db, $schema . '.notice_creation_details', array('filing_no' => $value['filing_no']), array(), '*', 'id desc');
                        $fgdfdf = 0;
                        $query_notice = "select to_party_id,digital_sign_status,notice_type,type_formate,today_date,id from  $schema.notice_creation_details where filing_no = '" . $value['filing_no'] . "' and  digital_sign_status NOT IN(3) order by id desc";
                        $query_prepare_notice = $db->prepare($query_notice);
                        $query_prepare_notice->execute();
                        $data_notice = $query_prepare_notice->fetchAll();

                        if (!empty($data_notice)) {
                            $no = 1;
                            foreach ($data_notice as $value_notice) {

                                $exp = explode(',', $value_notice['to_party_id']);
                                // print_r($exp);
                                $option = '';
                                if (!empty($exp)) {
                                    foreach ($exp as $valll) {
                                        $query = "select * from e_cases_party where filing_no = '" . $value['filing_no'] . "' and id = '" . $valll . "'";
                                        $query_prepare = $db->prepare($query);
                                        $query_prepare->execute();
                                        $i = 0;
                                        while ($row = $query_prepare->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                                            $option .= $row["name"];
                                        }
                                    }
                                }
                                $digital_sign_status = $value_notice['digital_sign_status'];
                                $notice_type = $value_notice['notice_type'];
                                $type_formate = $value_notice['type_formate'];

                                $notice = 'Notice';
                                if (in_array($notice_type, array('3', '4', '5'))) {
                                    $notice = 'Notice';
                                }
                                $color = '';
                                if ($digital_sign_status == '1') {
                                    $color = 'green;color:white;';
                                }
                                $color = '';
                        ?>
        <tr style="background-color:<?php echo $color; ?>">
            <td><?php echo $no; ?></td>
            <td> <?php echo $type_formate; ?></td>
            <td> <?php echo $data_case_type[0]['case_type_desc'] . '/' . $value['case_no'] . '/' .location_name($db,$value['location_code']).'/'. $value['case_year']; ?>
            </td>


            <td> <?php echo date('d/m/Y', strtotime($value_notice['today_date'])); ?></td>
            <td>
                <a href="../notice_view.php?notice_id=<?php echo base64_encode($value_notice['id']); ?>" target="_blank"
                    class="text-primary"><i class="fa fa-eye"></i> View <?php echo $notice; ?></a> |
                <button onclick="fn_trash_notice('<?php echo $value_notice['id']; ?>')" class="text-danger btn-none"> <i
                        class="fa fa-trash"></i> Delete</button>


            </td>
        </tr>
        <?php $no++;
                            }
                        } else {

                            echo "<input type = 'hidden' name='filiii_no' id='filiii_no' value='" . $filiddfd . "'>";
                            echo '<tr> <td colspan="8"> <p  style="font-size: large; text-align: center; color: red;"> Summon/Notice not generated! </p> </td> </tr>';
                        } ?>

    </tbody>
</table>

<?php $ii++;
            }
        } else {
            echo '1';

            //echo '<tr> <td colspan="8"> <p  style="font-size: large; text-align: center; color: red;"> Data not found! </p> </td> </tr>';
        }
    } else if ($_POST['action'] == 'generate_action') {
        $schema = $_SESSION['schema_name'];
        $filing_no = $_POST['filing_no'];
        $party_flag = $_POST['party_flag'];

        $data = get_data($db, $schema . '.case_detail', array('filing_no' => $_POST['filing_no']));
        $location = '0';
        if (!empty($data) && is_array($data)) {
            $location = '0';
            if ($data[0]['location'] != '') {
                $location = $data[0]['location'];
            }
            $to_party_id = '';
            if (!empty($_POST['to_party_id']) && is_array($_POST['to_party_id'])) {
                $to_party_id = implode(',', $_POST['to_party_id']);
            }
            $to_party_id = rtrim($to_party_id, ',');

            $summon_fff = notice_type($db,$schema,$_POST['notice_type']);
            
            $file_name = $data[0]['filing_no'] . $_POST['notice_type'] . $to_party_id . '-R1-' . $summon_fff . date('YmdHis');

            //  echo "select * from e_cases_party where id = '$to_party_id' and  party_flag='R' and filing_no = '" . $data[0]['filing_no'] . "'";

            //echo "select * from e_cases_party where id IN($to_party_id) and  party_flag='$party_flag' and filing_no = '" . $data[0]['filing_no'] . "'";
            try {
                $st_e_cases_party = $db->prepare("select * from e_cases_party where id IN($to_party_id) and  party_flag='$party_flag' and filing_no = '" . $data[0]['filing_no'] . "'");
                $st_e_cases_party->execute();
                $i = 0;
                $data_main_res = array();
                while ($row_e_cases_party = $st_e_cases_party->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                    $tmp = array();
                    if ($row_e_cases_party['party_flag'] == 'R') {
                        $tmp['name'] = $row_e_cases_party['name'];
                        $tmp['address'] = $row_e_cases_party['party_address1'];
                        $tmp['pincode'] = $row_e_cases_party['pin'];
                        $data_main_res[$i] = $tmp;
                        $i++;
                    }
                }
            } catch (PDOException $ex) {
                echo $ex;
            }


            $year_notice = date('Y');
            $sr_no_notice = 1;
            try {
                $query_defeect = "select max(sr_no) from $schema.notice_creation_details  where sr_no_year = '$year_notice' ";
                $query_defeect1 = $db->prepare($query_defeect);
                $query_defeect1->execute();
                $sr_no_notice = $query_defeect1->fetchColumn();
                if ($sr_no_notice) {
                    $sr_no_notice = $sr_no_notice + 1;
                } else {
                    $sr_no_notice = '1';
                }
            } catch (PDOException $ex) {
                echo $ex;
                die;
            }
            $notice_no = strlen($sr_no_notice);
            switch ($notice_no) {
                case "1":
                    $add_zero = "000";
                    break;
                case "2":
                    $add_zero = "00";
                    break;
                case "3":
                    $add_zero = "0";
                    break;
                default:
                    $add_zero = "";
            }
            $query_loc = "select short_name from mater_location_city where city_id = ?";
            $location_short_name_query = $db->prepare($query_loc);
            $location_short_name_query->bindParam(1,$location_id,PDO::PARAM_STR);
            $location_short_name_query->execute();
            $location_short_name = $location_short_name_query->fetchColumn();

            $sr_no_noticeqqq = $add_zero . $sr_no_notice . '/GSTAT/'.$location_short_name.'/' . $year_notice;
            $compilence = (isset($_POST['compilence']))?$_POST['compilence']:0;
            $ask_document_person = (isset($_POST['ask_document_person']))?$_POST['ask_document_person']:0;
            $time_to_present = (isset($_POST['time_to_present']))?$_POST['time_to_present']:'';
            $name_of_documents = (isset($_POST['name_of_documents']))?$_POST['name_of_documents']:'';
            $notice_html_data = (isset($_POST['notice_html_data']))?$_POST['notice_html_data']:'';

            if ($_POST['notice_type'] == '3') {
                $arra_data = array('sr_no_notice' => $sr_no_noticeqqq, 'sr_no' => $sr_no_notice, 'sr_no_year' => $year_notice, 'filing_no' => $filing_no, 'case_type' => $data[0]['case_type'], 'case_no' => $data[0]['case_no'], 'case_year' => $data[0]['case_year'], 'plaintif' => $data[0]['pet_name'], 'defendant' => $data[0]['res_name'], 'defendant_select' => $data_main_res[0]['name'], 'address' => $data_main_res[0]['address'], 'pincode' => $data_main_res[0]['pincode'], 'case_fixed_hearing_on' => $_POST['case_fixed_hearing_on'], 'hearing_court_date' => $_POST['hearing_court_date'], 'against_order_date' => $_POST['against_order_date'],'compilence' => false,'ask_document_person'=>$ask_document_person,'time_to_present'=>$time_to_present,'name_of_documents'=>$name_of_documents,'notice_html_data'=>$notice_html_data);
            } else if ($_POST['notice_type'] == '1' || $_POST['notice_type'] == '6') {
                
                $arra_data = array('sr_no_notice' => $sr_no_noticeqqq, 'sr_no' => $sr_no_notice, 'sr_no_year' => $year_notice, 'filing_no' => $filing_no, 'case_type' => $data[0]['case_type'], 'case_no' => $data[0]['case_no'], 'case_year' => $data[0]['case_year'], 'plaintif' => $data[0]['pet_name'], 'defendant' => $data[0]['res_name'], 'defendant_select' => $data_main_res[0]['name'], 'address' => $data_main_res[0]['address'], 'pincode' => $data_main_res[0]['pincode'],'case_fixed_hearing_on' => $_POST['case_fixed_hearing_on'],'compilence' => false,'ask_document_person'=>$ask_document_person,'time_to_present'=>$time_to_present,'name_of_documents'=>$name_of_documents,'notice_html_data'=>$notice_html_data);
            }
            else if ($_POST['notice_type'] == '4' || $_POST['notice_type'] == '5') {
                 $arra_data = array('sr_no_notice' => $sr_no_noticeqqq, 'sr_no' => $sr_no_notice, 'sr_no_year' => $year_notice, 'filing_no' => $filing_no, 'case_type' => $data[0]['case_type'], 'case_no' => $data[0]['case_no'], 'case_year' => $data[0]['case_year'], 'plaintif' => $data[0]['pet_name'], 'defendant' => $data[0]['res_name'], 'defendant_select' => $data_main_res[0]['name'], 'address' => $data_main_res[0]['address'], 'pincode' => $data_main_res[0]['pincode'], 'case_fixed_hearing_on' => $_POST['case_fixed_hearing_on'], 'hearing_court_date' => $_POST['hearing_court_date'], 'against_order_date' => $_POST['against_order_date'],'compilence' => $_POST['compilence'],'ask_document_person'=>$ask_document_person,'time_to_present'=>$time_to_present,'name_of_documents'=>$name_of_documents,'notice_html_data'=>$notice_html_data);
            }else if ($_POST['notice_type'] == '8') {
                 $arra_data = array('sr_no_notice' => $sr_no_noticeqqq, 'sr_no' => $sr_no_notice, 'sr_no_year' => $year_notice, 'filing_no' => $filing_no, 'case_type' => $data[0]['case_type'], 'case_no' => $data[0]['case_no'], 'case_year' => $data[0]['case_year'], 'plaintif' => $data[0]['pet_name'], 'defendant' => $data[0]['res_name'], 'defendant_select' => $data_main_res[0]['name'], 'address' => $data_main_res[0]['address'], 'pincode' => $data_main_res[0]['pincode'], 'case_fixed_hearing_on' => $_POST['case_fixed_hearing_on'], 'hearing_court_date' => $_POST['hearing_court_date'], 'against_order_date' => $_POST['against_order_date'],'compilence' => false,'ask_document_person'=>$ask_document_person,'time_to_present'=>$time_to_present,'name_of_documents'=>$name_of_documents,'notice_html_data'=>$notice_html_data);
            }

            // print_r($arra_data);

            $file_name_dat = $file_name . '.pdf';
            $data_notice = summon_formate($db, $to_party_id, $_REQUEST['notice_type'], $schema, $arra_data);
            $html_pdf = $data_notice['notice_html'];
            //---------Notice PDF creation---------------//
            try{
             $dompdf->loadHtml($html_pdf);
			  $dompdf->setPaper('A4');
			  $dompdf->render();
			  $outputff = $dompdf->output();
			  $upload_dir = "/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/notice/".$_POST['notice_type'];
			  $pp_path = $upload_dir."/$file_name_dat";
			  $save_path = $upload_dir."/$file_name_dat";
			  if (!file_exists($save_path)) {
					mkdir($upload_dir, 0777, true);
	                   	}
			 $save_file = file_put_contents($pp_path, $outputff);
			  
			  $s3Service = new S3Service();
			  $s3Service->uploadDynamicFile($outputff, $save_path);
            }catch (PDOException $e) {
                error_log("Error generating or uploading PDF: " . $e->getMessage());
                echo $msg = '>Error in generating or uploading PDF' . $e; die;
            }

            //----------Notice PDF creation--------------//
            $before_bench = (!empty($data_notice['coram']))?$data_notice['coram']:'';
            $listing_date = (!empty($data_notice['listing_date']))?$data_notice['listing_date']:null;
            $blank = '';
            $query = "INSERT INTO $schema.notice_creation_details (sr_no_notice,sr_no,sr_no_year,notice_html,file_name,type_formate,filing_no, case_no, case_type, case_year, location,send_date, notice_type, notice_date, today_date, user_id, to_party_id, seal_of_court_date,whereason_date,written_statement_date,appear_court_date,petion_againts_desc,before_bench,listing_date,compilence,ask_document_person,time_to_present,name_of_documents,notice_html_data,pdf_path) values
            (?,?,?,?,?,?,?,?,?,?,?,now(),?,now(),now(),?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

            try {
                $db->beginTransaction(); 
                $query_insert = $db->prepare($query);
                $query_insert->bindParam(1,$sr_no_noticeqqq,PDO::PARAM_STR);
                $query_insert->bindParam(2,$sr_no_notice,PDO::PARAM_STR);
                $query_insert->bindParam(3,$year_notice,PDO::PARAM_STR);
                $query_insert->bindParam(4,$html_pdf,PDO::PARAM_STR);
                $query_insert->bindParam(5,$file_name_dat,PDO::PARAM_STR);
                $query_insert->bindParam(6,$summon_fff,PDO::PARAM_STR);
                $query_insert->bindParam(7,$data[0]['filing_no'],PDO::PARAM_STR);
                $query_insert->bindParam(8,$data[0]['case_no'],PDO::PARAM_STR);
                $query_insert->bindParam(9,$data[0]['case_type'],PDO::PARAM_STR);
                $query_insert->bindParam(10,$data[0]['case_year'],PDO::PARAM_STR);
                $query_insert->bindParam(11,$location,PDO::PARAM_STR);
                $query_insert->bindParam(12,$_POST['notice_type'],PDO::PARAM_STR);
                $query_insert->bindParam(13,$_SESSION['id'],PDO::PARAM_STR);
                $query_insert->bindParam(14,$to_party_id,PDO::PARAM_STR);
                $query_insert->bindParam(15,$blank,PDO::PARAM_STR);
                $query_insert->bindParam(16,$blank,PDO::PARAM_STR);
                $query_insert->bindParam(17,$blank,PDO::PARAM_STR);
                $query_insert->bindParam(18,$blank,PDO::PARAM_STR);
                $query_insert->bindParam(19,$blank,PDO::PARAM_STR);
                $query_insert->bindParam(20,$before_bench,PDO::PARAM_STR);
                $query_insert->bindParam(21,$listing_date,PDO::PARAM_STR);
                $query_insert->bindParam(22,$compilence,PDO::PARAM_STR);
                $query_insert->bindParam(23,$ask_document_person,PDO::PARAM_STR);
                $query_insert->bindParam(24,$time_to_present,PDO::PARAM_STR);
                $query_insert->bindParam(25,$name_of_documents,PDO::PARAM_STR);
                $query_insert->bindParam(26,$notice_html_data,PDO::PARAM_STR);
                $query_insert->bindParam(27,$save_path,PDO::PARAM_STR);
                if ($query_insert->execute() == '1') {

                    $subject = "Notice Sr. No. :" . $sr_no_noticeqqq;
                    $email_text = "Dear User, you can get the copy of notice by the link below. ";
                    $msg555 = "Dear User, you can get the notice in registered mail id. please check your mail id. ";
                    fn_sms_notice($db, $schema, 'N', $data[0]['filing_no'], $subject, $msg555, $email_text, $sr_no_noticeqqq);
                }
                $db->commit();
                 echo json_encode(array('party_ids' => $to_party_id, 'filing_no' => $data[0]['filing_no'], 'file_name' => $file_name, 'schema' => $schema, 'notice_type' => $_POST['notice_type'], 'data' => $arra_data));
            } catch (PDOException $ex) {
                $db->rollBack();
                echo $msg = '>Failed to run query' . $ex;
            }
        }
    } else if ($_POST['action'] == 'notice_formate_div') {

        if ($_POST['notice_type'] == '3' || $_POST['notice_type'] == '4' || $_POST['notice_type'] == '5' || $_POST['notice_type'] == '6' || $_POST['notice_type'] == '8') {
            ?>
<div class="form-group">
    <?php if($_POST['notice_type'] == '4'){ ?>
        <div class="row">
            <div class="col-sm-12">
                <label>Was the person present in 1st notice hearing :</label>
                <label class="radio-inline">
                  <input type="radio" class="compilence" value='1'  name="compilence" onchange="return complience(this.value);"> Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" class="compilence" value='0' name="compilence" checked onchange="return complience(this.value);"> No
                </label>
            </div>
        </div>
    <?php } ?>
    <?php if($_POST['notice_type'] == '5'){ ?>
        <div class="row">
            <div class="col-sm-12">
                <label>Was the court able to hear the person :</label>
                <label class="radio-inline">
                  <input type="radio" value='1' class="compilence" name="compilence" onchange="return complience(this.value);"> Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" value='0' class="compilence" name="compilence" checked onchange="return complience(this.value);"> No
                </label>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-sm-6">
            <label>Select the Respondent :</label>
            <select required="required" class="form-control" name="to_party_id" id="to_party_id" multiple>
            </select>
        </div>
        <div class="col-sm-6">
            <label>Next hearing on :</label>
            <input type="date" autocomplete="off" name="case_fixed_hearing_on" id="case_fixed_hearing_on"
                required="required" class="form-control">
        </div>
    </div>
    <?php if($_POST['notice_type'] == '8'){ ?>
        <div class="row">
            <div class="col-sm-6">
                <label>Select time :</label>
                <input type="time" name="time_to_present" id="time_to_present" class="form-control">
            </div>
            <div class="col-sm-6">
                <label>Select option :</label>
                <select required="required" class="form-control" name="ask_document_person" id="ask_document_person">
                    <option value='1'>Requiring Only Document</option>
                    <option value='2'>Requiring Only Person</option>
                    <option value='3'>Requiring Both document and person</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
            <label> List of Documents :</label>
            <textarea name="name_of_documents" rows="10" cols="75" id="name_of_documents" autocomplete="off"
                required="required"></textarea>
        </div>
        </div>
    <?php } ?>
</div>


<script>

</script>
<?php } else if ($_POST['notice_type'] == '1') {
        ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label>Select the Respondent :</label>
            <select required="required" class="form-control" name="to_party_id" id="to_party_id" multiple>
            </select>
        </div>

    </div>
</div>


<div class="form-group">
    <div class="row">

        <div class="col-sm-12">
            <label> Notice Data :</label>
            <textarea name="notice_html_data" rows="10" cols="75" id="notice_html_data" autocomplete="off"
                required="required"></textarea>
        </div>
    </div>
</div>


<script>

</script>
<?php }
    } else if ($_POST['action'] == 'party_drpdown') {
        $party_flag = $_POST['party_flag'];
        $filing_no = $_POST['filing_no'];
        $notice_type = $_POST['notice_type'];
        $query12 = "select main_case_ia_no, case_type from $schema.case_detail where filing_no = ? ";
        $query_prepare12 = $db->prepare($query12);
        $query_prepare12->bindParam(1, $filing_no, PDO::PARAM_STR);
        $query_prepare12->execute();
        $data_case_type =  $query_prepare12->fetch();
        $party_ids = '0';
        if(in_array($notice_type,array(3,4,5))){
            $get_notice_stored = generated_notices($db,$schema,$filing_no,$notice_type);
        
          
            foreach($get_notice_stored as $k=>$v){
                if($k == '0')
                    $party_ids = $v['to_party_id'];
                else
                    $party_ids .= ','.$v['to_party_id'];
            }   
        }

        $case_arr = array('2', '3', '4','5','6','7','8','9','10');


        if (in_array($data_case_type['case_type'], $case_arr)) {

            $filing_no12 = $data_case_type['main_case_ia_no'];
            $query = "select * from e_cases_party where filing_no = ? and party_flag = ? and id not in ($party_ids)";
        } else {
            $query = "select * from e_cases_party where filing_no = ? and party_flag = ? and id not in ($party_ids)";
        }
        //echo $query;
        $query_prepare = $db->prepare($query);
         if (in_array($data_case_type['case_type'], $case_arr)) {
            $query_prepare->bindParam(1, $filing_no12, PDO::PARAM_STR);
        }else{
            $query_prepare->bindParam(1, $filing_no, PDO::PARAM_STR);
        }
        $query_prepare->bindParam(2, $party_flag, PDO::PARAM_STR);
        $query_prepare->execute();
        $i = 0;
        $option = '';
        while ($row = $query_prepare->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $option .= '<option value=' . $row["id"] . '>' . $row["name"] . '</option>';
            echo '<br>';
        }
        echo $option;
    } else if ($_POST['action'] == 'trash_notice') {
        try {
            $notice_id = $_POST['notice_id'];
            $query = "update $schema.notice_creation_details set digital_sign_status = '3'  where id = ?  ";
            $query_prepare = $db->prepare($query);
            $query_prepare->bindParam(1,$notice_id,PDO::PARAM_INT);
            $query_prepare->execute();

            echo 'Notice Deleted';
        } catch (PDOException $ex) {
            echo $ex;
        }
    } else if ($_POST['action'] == 'search_document_list') {
        $schema = $_SESSION['schema_name'];
        if ($_POST['search_by'] == '1') {
            $filing_no = $_POST['dairy_no'] . $_POST['dairy_year'];
            $where = array('location_code' => $_SESSION['location'], 'filing_no' => $filing_no);
            $where_likes = array();
        } else if ($_POST['search_by'] == '2') {
            $where = array('location_code' => $_SESSION['location'], 'case_type' => $_POST['case_type'], 'case_no' => $_POST['case_no'], 'case_year' => $_POST['case_year']);
            $where_likes = array();
        }
        $data_case_details = get_data($db, $schema . '.case_detail', $where, $where_like, $column = 'filing_no,case_type');
        //print_r($data_case_details);
        ?>
<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
    <thead>
        <tr role="row">
            <th>S.No</th>
            <th>Document Type</th>
            <th>Filed By</th>
            <th>Date Of Filing</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        <?php
                $filing_no = $data_case_details['0']['filing_no'];
                $case_type = $data_case_details['0']['case_type'];
                $login = 'lalit';
                $password = 'pachauri';
                $url = '164.100.59.174/restservice/rest/document/getDocumentUploadList?filing_no=' . $filing_no . '&case_type=' . $case_type . '';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                $headers = array(
                    'Content-Type:application/json',
                    'Authorization: Basic ' . base64_encode("$login:$password"),
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $result = curl_exec($ch);
                curl_close($ch);
                //echo $result;
                $data121212 = json_decode($result, true);
                if (!empty($data121212)) {
                    $no = 1;
                    foreach ($data121212 as $value) {
                        $path_latest = $value['filing_no'] . '**' . $value['filename'] . '**' . $value['fileupload'] . '**' . $value['documentuploadmodelid'];
                ?>
        <tr>
            <td>
                <?php
                                //print_r($value);
                                echo $no; ?></td>
            <td> <?php echo $value['docum_type']; ?></td>
            <td> <?php echo $value['filed_by']; ?></td>
            <td> <?php echo $value['dt_of_filing']; ?></td>
            <td>
                <a onclick="view_document('<?php echo $value['docum_type']; ?>','<?php echo base64_encode($path_latest); ?>')"
                    class="model_form btn btn-primary" style="margin-bottom: 18px;"> View </a>
            </td>
        </tr>
        <?php $no++;
                    }
                } else {
                    echo '<tr> <td colspan="8"> <p  style="font-size: large; text-align: center; color: red;"> Data not found! </p> </td> </tr>';
                } ?>

    </tbody>
</table>

<?php $ii++;
    }
}

?>

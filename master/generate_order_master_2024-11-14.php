<?php


// include "../db_inc1.php";
// include "../db_inc2.php";

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

function generate_pdf_html($schemas, $db, $dbonline, $order_id, $bench_nature, $static_text, $presiding,$user_actual_name=null,$menuaccess_codeall=null)
{
    $pdf_html = '';
    $pdf_html .= "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><meta http-equiv='X-UA-Compatible' content='ie=edge'>";
    $pdf_html .= "<title>Tribunal</title>";
    $pdf_html .= "</head><body style='font-size:16px; line-height: 1.2; padding-left: 99.21pt; padding-right: 14.17pt;'>";
    $pdf_html .= "<p style='text-align:center; line-height: 1.0;margin-bottom: 0;'><b>GSTAT</b></p>";

    $sql2q = " select bench_name from $schemas.bench_nature where  bench_code = ?";
    $sth11 = $db->prepare($sql2q);
    $sth11->bindParam(1, $bench_nature, PDO::PARAM_STR);
    $sth11->execute();
    $bench_nature_name = $sth11->fetchColumn();

    $stng = $db->prepare("select * from $schemas.order_daily where item_no=? ");
    $stng->bindParam(1, $order_id, PDO::PARAM_INT);
    $stng->execute();
    while ($rw2 = $stng->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $filing_no = htmlspecialchars($rw2['filing_no']);
        $courtno = htmlspecialchars($rw2['court_no']);
        $bench_no = htmlspecialchars($rw2['bench_no']);
        $flag = htmlspecialchars($rw2['flag']);
        $next_list_dateold = htmlspecialchars($rw2['order_date']);
        $entry_date = $rw2['entry_date'];
    }
    $pdf_html .= "<p style='text-align:center; line-height: 1.0; margin-bottom: 10px; margin-top: 10px;'><u><b>".$bench_nature_name." Court No. " . $courtno . "</b></u></p>";
    list($year, $month, $day) = explode('-', $next_list_dateold);
    $next_list_date = $day . '/' . $month . '/' . $year;
    list($day, $month, $year) = explode('/', $next_list_date);
    $next_list_date9 = $year . '-' . $month . '-' . $day;
    list($yy, $mm, $dd) = explode("-", $entry_date);
    $delivered_date = $dd . "/" . $mm . "/" . $yy;

    $sql_desg = "select name from initilization";
    $sth14 = $db->prepare($sql_desg);
    $sth14->execute();
    $name_ins = $sth14->fetchColumn();

    $sql_cd = "select * from $schemas.case_detail as a  where a.filing_no=? ";

    $sth_j12cc = $db->prepare($sql_cd);
    $sth_j12cc->bindParam(1, $filing_no, PDO::PARAM_STR);
    $sth_j12cc->execute();

    while ($row2 = $sth_j12cc->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

        $case_no = $row2['case_no'];
        $case_type = $row2['case_type'];
        $case_year = $row2['case_year'];
        $pet_name = $row2['pet_name'];
        $res_name = $row2['res_name'];
        $case_status = $row2['status'];
        $case_type_appeal = $row2['case_type'];

        $ia_ma_filing_no = $row2['main_case_ia_no'];
        $location_code = htmlspecialchars($row2['location_code']);
        $st1 = $db->prepare("select * from mater_location_city where city_id=?");
        $st1->execute(array($location_code));
        $bech_data = $st1->fetch();
        $bench_location_name = $bech_data['city_name'];
		$bench_location_short_name = $bech_data['short_name'];
        if ($case_type > 0) {
            $stQ = $db->prepare("select short_name from case_type where id = ?");
            $stQ->bindParam(1, $case_type, PDO::PARAM_STR);
            $stQ->execute();
            $case_type_short_name = $stQ->fetchColumn();
        }
    }


    $case_no_child = '';

//     if ($case_type == '1' or $case_type == '2' or $case_type == '3') {
//        // $data_chield = $this->fn_getChild($db, $filing_no);

// echo $mainCno = "select filing_no,case_no,case_year,short_name,cast(case_no as int) as case_nooo from lucknow.case_detail inner join case_type on case_type=id where ia_ma_filing_no= '$ia_ma_filing_no' and status = 'P' order by case_nooo,case_year asc";
//      $mainCno = "select filing_no,case_no,case_year,short_name,case_no from lucknow.case_detail inner join case_type on case_type=id where ia_ma_filing_no= ? and status = 'P' order by case_no,case_year asc";
//     $mainCrs = $db->prepare($mainCno);
//     $mainCrs->bindParam(1, $ia_ma_filing_no, PDO::PARAM_STR);
//     $mainCrs->execute();
//     $data_chield = $mainCrs->fetchAll();
//    // return $data_ia_main;

//         if (!empty($data_chield) && is_array($data_chield)) {
//             foreach ($data_chield as $chil) {
//                 $case_no_child .= '<br> ' . $chil['short_name'] . '/' . $chil['case_no'] . '/' . $chil['case_year'];
//             }
//         }
//     }

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
    $case_num = htmlspecialchars(strtoupper($case_type_short_name) . '/' . $case_no . '/' . $case_year);
    if ($case_no > 0) {
        $pdf_html .= "<p style='text-align:center; line-height: 1.0;  margin-bottom: 10px;'><b>" .htmlspecialchars(strtoupper($case_type_short_name) . '/' . $case_no . '/' .$bench_location_short_name.'/'. $case_year) . $case_no_child. "</b></p>";
    } else {
        $pdf_html .= "<p style='text-align:center; line-height: 1.0;  margin-bottom: 10px;'> <b> Filing No" . ltrim(substr($filing_no, 5, 6), '0') . '/' . substr($filing_no, 11, 4) . "</b></p>";
    }
    $pdf_html .= " <table style='line-height: 1.0; width: 100%;'><tr>";
    $pdf_html .= " <td style='width: 100%;'>" . htmlspecialchars(strtoupper($pet_name)) . "</td>";
    $pdf_html .= " <td style='text-align: right; vertical-align: bottom; font-weight: bold;'>.............Appellant</td></tr><tr><td colspan='3' style='text-align: center;'><p style='line-height:1;'><b>Versus</b></p></td></tr><tr>";
    $pdf_html .= " <td style='width: 100%;'>" . htmlspecialchars(strtoupper($res_name)) . "</td>";
    $pdf_html .= " <td style='text-align: right; vertical-align: bottom; font-weight: bold;'>.............Respondent</td></tr></table>";
// Advocate Code
    $st141gi = $db->prepare("select * from $schemas.order_daily where item_no=? ");
    $st141gi->bindParam(1, $order_id, PDO::PARAM_STR);
    $st141gi->execute();
    $jj = $st141gi->fetch();
    $order_tribunal_print = $jj['order_tribunal'];
    $author_name = $jj['author_name'];

    $pdf_html .= " <table style='line-height: 1.2; margin-bottom: 15px; margin-top: 10px; width: 100%;'><tbody><tr> <td style='vertical-align: top; width: 60%;'><b>Counsel for Appellant</b><br>";
    $stng1 = $db->prepare("select * from $schemas.order_daily_advocate where advocate_type ='P' and filing_no=? and order_date=? ");
    $stng1->bindParam(1, $filing_no, PDO::PARAM_INT);
    $stng1->bindParam(2, $next_list_dateold, PDO::PARAM_INT);
    $stng1->execute();
    while ($rw21 = $stng1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $advocate = htmlspecialchars($rw21['advocate']);
        $advocate_type = htmlspecialchars($rw21['advocate_type']);
        if ($advocate_type == 'P') {
            $pdf_html .= strtoupper($advocate) . '<br>';
        }
    }

    $pdf_html .= "</td><td style='vertical-align: top; text-align: right;'> <b>Counsel for Respondent</b><br>";

    $stng1 = $db->prepare("select * from $schemas.order_daily_advocate where advocate_type ='R' and filing_no=? and order_date=? ");
    $stng1->bindParam(1, $filing_no, PDO::PARAM_INT);
    $stng1->bindParam(2, $next_list_dateold, PDO::PARAM_INT);
    $stng1->execute();
    while ($rw21 = $stng1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $advocate1 = htmlspecialchars($rw21['advocate']);
        $advocate_type = htmlspecialchars($rw21['advocate_type']);
        if ($advocate_type == 'R') {
            $pdf_html .= strtoupper($advocate1) . '<br>';
        }
    }
    $pdf_html .= "</td> </tr></tbody></table>";

    $sql = "select jm.gen,md.desg_name,jm.judge_name,bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm Join $schemas.master_desg md ON jm.judge_desg_code = md.desg_code where bj.from_list_date ='$next_list_date9' and  bj.bench_no='$bench_no'  and bj.court_no='$courtno'  and jm.judge_code=bj.judge_code order by seniority asc";
    $sth_judge = $db->prepare($sql);
    $sth_judge->execute();
    $judge_data = $sth_judge->fetchAll();
    $coram_name = '';
    foreach ($judge_data as $value) {
        $coram_name .= 'Hon’ble '.$value['gen']. ' '. $value['judge_name'] . ', ' . $value['desg_name'] . '';
        $coram_name .= "<br>";
    }
    // $pdf_html .= "<p style='line-height: 1.2; margin-bottom: 10px; margin-top: 30px;'><u><b>CORAM</b></u></p>";

if($next_list_date9 == '2021-12-01') { 
    $pdf_html .= ""; 
} else { 
    $pdf_html .= "<p style='line-height: 1.2;'><u><b>" . $coram_name . "</b></u></p>";
}


//   apl-04 start


        if($case_status == 'D' && $case_type_appeal == '1'){
                $query = "select personal_hearing,brief_order,order_status,det_amount_tax,det_amount_interest,det_amount_penalty,det_amount_fees,det_amount_others,det_amount_refund from $schemas.order_daily where filing_no  =?  and order_date = ? order by item_no desc limit 1";
                $order_data_query = $db->prepare($query);
                $order_data_query->bindParam(1, $filing_no, PDO::PARAM_STR);
                $order_data_query->bindParam(2, $next_list_dateold, PDO::PARAM_STR);
                $order_data_query->execute();
                $order_data = $order_data_query->fetch();
                if(!empty($order_data)){
                    $personal_hearing = $order_data['personal_hearing'];
                    $brief_order = $order_data['brief_order'];
                    $order_status = ($order_data['order_status']==1)?'Confirmed':(($order_data['order_status']==2?'Modified':(($order_data['order_status']==1)?'Rejected':'')));
                    $det_amount_tax = $order_data['det_amount_tax'];
                    $det_amount_interest = $order_data['det_amount_interest'];
                    $det_amount_penalty = $order_data['det_amount_penalty'];
                    $det_amount_fees = $order_data['det_amount_fees'];
                    $det_amount_others = $order_data['det_amount_others'];
                    $det_amount_refund = $order_data['det_amount_refund'];
                    list($taxa,$taxb,$taxc,$taxd,$taxe) = explode("||",$det_amount_tax);
                    list($interesta,$interestb,$interestc,$interestd,$intereste) = explode("||",$det_amount_interest);
                    list($penaltya,$penaltyb,$penaltyc,$penaltyd,$penaltye) = explode("||",$det_amount_penalty);
                    list($feesa,$feesb,$feesc,$feesd,$feese) = explode("||",$det_amount_fees);
                    list($othersa,$othersb,$othersc,$othersd,$otherse) = explode("||",$det_amount_others);
                    list($refunda,$refundb,$refundc,$refundd,$refunde) = explode("||",$det_amount_refund);
                }

                $query = "select ecd.e_reference_no,ecd.dt_of_filing::timestamp::date as filing_date, esu.name as filed_by from e_case_detail as ecd 
                left join loginmodel as lm on lm.loginid = ecd.loginid
                left join e_sign_up as esu on esu.loginidgenerated = lm.loginidgenerated 
                where ecd.filing_no = ?";
                $filing_data_query = $db->prepare($query);
                $filing_data_query->bindParam(1, $filing_no, PDO::PARAM_STR);
                $filing_data_query->execute();
                $filing_data = $filing_data_query->fetch();
                if(!empty($filing_data)){
                    $e_reference_no = $filing_data['e_reference_no'];
                    $filing_date = date('d/m/Y',strtotime($filing_data['filing_date']));
                    $filed_by = $filing_data['filed_by'];
                }

                $query = "select gst_number,crn_number,order_date,ordertype,order_number from e_order_details where filing_no = ?";
                $gst_detail = $db->prepare($query);
                $gst_detail->bindParam(1,$filing_no,PDO::PARAM_STR);
                $gst_detail->execute();
                $gst_data = $gst_detail->fetch();
                $gst_no = $gst_data['gst_number'];
                $crn_number = $gst_data['crn_number'];
                $order_type = $gst_data['ordertype'];
                $order_number = $gst_data['order_number'];
                $order_date = $gst_data['order_date'];

                $query = "select name,party_address1 from e_cases_party where filing_no = ? and party_flag in ('P','A') and party_serial_no = 1 limit 1";
                $gst_detail = $db->prepare($query);
                $gst_detail->bindParam(1,$filing_no,PDO::PARAM_STR);
                $gst_detail->execute();
                $gst_data = $gst_detail->fetch();
                $appellant_address = $gst_data['party_address1'];
                $applicant_name = $gst_data['name'];

                $query = "select nclt_txn_id,txn_amount from txn_details where filing_no = ? order by created_at limit 1";
                $transaction_query = $db->prepare($query);
                $transaction_query->bindParam(1,$filing_no,PDO::PARAM_STR);
                $transaction_query->execute();
                $transaction_data = $transaction_query->fetch();
                $txn_id = $transaction_data['nclt_txn_id'];
                $txn_amount = $transaction_data['txn_amount'];


                $query = "select user_type from master_user_roles_r where id = ?";
                $desg_query = $db->prepare($query);
                $desg_query->bindParam(1,$menuaccess_codeall,PDO::PARAM_STR);
                $desg_query->execute();
                $desgignation = $desg_query->fetchColumn();

                $query = "select disputeamountcentral,disputeamountstate,disputeamountintegrated,disputeamountcess,disputeamounttotal,flag from gst_demand_admitted_dispute where filing_no = ?";
                $dispute_query = $db->prepare($query);
                $dispute_query->bindParam(1,$filing_no,PDO::PARAM_STR);
                $dispute_query->execute();
                $dispute_data = $dispute_query->fetchAll();
                if(!empty($dispute_data)){
                    foreach($dispute_data as $k=>$v){
                        $flag = $v['flag'];
                        switch($flag){
                            case "tax":
                                $central_dis_tax = $v['disputeamountcentral'];
                                $state_dis_tax = $v['disputeamountstate'];
                                $intg_dis_tax = $v['disputeamountintegrated'];
                                $cess_dis_tax = $v['disputeamountcess'];
                                $total_dis_tax = $v['disputeamounttotal'];
                                break;
                            case "interest":
                                $central_dis_int = $v['disputeamountcentral'];
                                $state_dis_int = $v['disputeamountstate'];
                                $intg_dis_int = $v['disputeamountintegrated'];
                                $cess_dis_int = $v['disputeamountcess'];
                                $total_dis_int = $v['disputeamounttotal'];
                                break;
                            case "penalty":
                                $central_dis_pen = $v['disputeamountcentral'];
                                $state_dis_pen = $v['disputeamountstate'];
                                $intg_dis_pen = $v['disputeamountintegrated'];
                                $cess_dis_pen = $v['disputeamountcess'];
                                $total_dis_pen = $v['disputeamounttotal'];
                                break;
                            case "fees":
                                $central_dis_fee = $v['disputeamountcentral'];
                                $state_dis_fee = $v['disputeamountstate'];
                                $intg_dis_fee = $v['disputeamountintegrated'];
                                $cess_dis_fee = $v['disputeamountcess'];
                                $total_dis_fee = $v['disputeamounttotal'];
                                break;
                            case "other":
                                $central_dis_oth = $v['disputeamountcentral'];
                                $state_dis_oth = $v['disputeamountstate'];
                                $intg_dis_oth = $v['disputeamountintegrated'];
                                $cess_dis_oth = $v['disputeamountcess'];
                                $total_dis_oth = $v['disputeamounttotal'];
                                break;
                            case "refund":
                                $central_dis_rfn = $v['disputeamountcentral'];
                                $state_dis_rfn = $v['disputeamountstate'];
                                $intg_dis_rfn = $v['disputeamountintegrated'];
                                $cess_dis_rfn = $v['disputeamountcess'];
                                $total_dis_rfn = $v['disputeamounttotal'];
                                break;
                            default:
                                echo "";
                        }
                    }
                }
                $pdf_html .= '<div class="row red"> <div class="col-md-12"> <div class="text-center"><p>Form GST APL-04</p> <p>[See rules 113(1) & 115]</p> <p>Summary of the demand after issue of order by the Appellate Authority, Tribunal or Court</p> </div> </div> <div class="col-md-6"><b>Order no. :</b> <span>'.$order_number.'</span></div> <div class="col-md-6 text-right"><b>Date of order :</b> <span>'.$next_list_dateold.'</span></div> <div class="col-md-12"> <table class="table table-striped table-bordered"> <tbody> <tr> <td><b>1.</b></td> <td colspan="10">GSTIN/Temporary ID/UIN - <span>'. $gst_no.'</span> </td> </tr> <tr> <td><b>2.</b></td> <td colspan="10">Name of the appellant - <span>'. $applicant_name.'</span> </td> </tr> <tr> <td><b>3.</b></td> <td colspan="10">Address of the appellant - <span>'. $appellant_address.'</span> </td> </tr> <tr> <td><b>4.</b></td> <td colspan="4">Order appealed against - <span>'.$crn_number.'</span> </td> <td colspan="3">Number - <span>'.$order_number.'</span></td> <td colspan="3">Date - <span>'.$order_date.'</span></td> </tr> <tr> <td><b>5.</b></td> <td colspan="4">Appeal no. - <span>'. $case_num .'</span></td> <td colspan="6">Date - <span>'. $filing_date.'</span></td> </tr> <tr> <td><b>6.</b></td> <td colspan="10">Personal Hearing -  '.$personal_hearing.'</td> </tr> <tr> <td><b>7.</b></td> <td colspan="10">Order in brief - '.$brief_order.'</td> </tr> <tr> <td><b>8.</b></td> <td colspan="10">Status of order - '.$order_status.' </td> </tr> <tr> <td><b>9.</b></td> <td colspan="10">Amount of demand confirmed:</td> </tr> <tr> <td><b>Particulars</b></td> <td colspan="2">Central tax</td> <td colspan="2">State/UT tax</td> <td colspan="2">Integrated tax</td> <td colspan="2">Cess</td> <td colspan="2">Total</td> </tr> <tr> <td>&nbsp;</td> <td>Disputed Amount</td> <td>Determined Amount</td> <td>Disputed Amount</td> <td>Determined Amount</td> <td>Disputed Amount</td> <td>Determined Amount</td> <td>Disputed Amount</td> <td>Determined Amount</td> <td>Disputed Amount</td> <td>Amount</td> </tr> <tr> <td><b>1</b></td> <td>2</td> <td>3</td> <td>4</td> <td>5</td> <td>6</td> <td>7</td> <td>8</td> <td>9</td> <td>10</td> <td>11</td> </tr> <tr> <td><b>(a) Tax</b></td> <td>'.$central_dis_tax.'</td> <td>'.$taxa.'</td> <td>'.$state_dis_tax.'</td> <td>'.$taxb.'</td> <td>'.$intg_dis_tax.'</td> <td>'.$taxc.'</td> <td>'.$cess_dis_tax.'</td> <td>'.$taxd.'</td> <td>'.$total_dis_tax.'</td> <td id="determined_tax">'.$taxe.'</td> </tr> <tr> <td><b>(b) Interest</b></td> <td>'.$central_dis_int.'</td> <td> '.$interesta.'</td> <td>'.$state_dis_int.'</td> <td> '.$interestb.'</td> <td>'.$intg_dis_int.'</td> <td> '.$interestc.'</td> <td>'.$cess_dis_int.'</td> <td> '.$interestd.'</td> <td>'.$total_dis_int.'</td> <td id="determined_interest">'.$intereste.'</td> </tr> <tr> <td><b>(c) Penalty</b></td> <td>'.$central_dis_pen.'</td> <td>'.$penaltya.'</td> <td>'.$state_dis_pen.'</td> <td>'.$penaltyb.'</td> <td>'.$intg_dis_pen.'</td> <td>'.$penaltyc.'</td> <td>'.$cess_dis_pen.'</td> <td>'.$penaltyd.'</td> <td>'.$total_dis_pen.'</td> <td id="determined_penalty">'.$penaltye.'</td> </tr> <tr> <td><b>(d) Fees</b></td> <td>'.$central_dis_fee.'</td> <td> '.$feesa.'</td> <td>'.$state_dis_fee.'</td> <td>'.$feesb.'</td> <td>'.$intg_dis_fee.'</td> <td>'.$feesc.'</td> <td>'.$cess_dis_fee.'</td> <td>'.$feesd.'</td> <td>'.$total_dis_fee.'</td> <td id="determined_fees">'.$feese.'</td> </tr> <tr> <td><b>(e) Others</b></td> <td>'.$central_dis_oth.'</td> <td>'.$othersa.'</td> <td>'.$state_dis_oth.'</td> <td> '.$othersb.'</td> <td>'.$intg_dis_oth.'</td> <td> '.$othersc.'</td> <td>'.$cess_dis_oth.'</td> <td> '.$othersd.'</td> <td>'.$total_dis_oth.'</td> <td id="determined_others">'.$otherse.'</td> </tr> <tr> <td><b>(f) Refund</b></td> <td>'.$central_dis_rfn.'</td> <td> '.$refunda.'</td> <td>'.$state_dis_rfn.'</td> <td> '.$refundb.'</td> <td>'.$intg_dis_rfn.'</td> <td> '.$refundc.'</td> <td>'.$cess_dis_rfn.'</td> <td> '.$refundd.'</td> <td>'.$total_dis_rfn.'</td> <td id="determined_refund">'.$refunde.'</td> </tr> </tbody> </table> <p>Place :'.strtoupper($schemas).'</p> <p>Date :</p> </div> <div class="col-md-12 text-right"> <p><span>Signature</span></p> <p><span>'.strtoupper($schemas).'  '.$user_actual_name.'</span></p> <p><span>Designation : '.$desgignation.'</span></p> <p><span>Jurisdiction :</span></p> </div> </div>';
        } 
//    apl-02 and apl-04 end
        
     $pdf_html .= "<p style='text-align:center; line-height: 1.2; margin-bottom: 20px; margin-top: 30px;'><u><b>ORDER</b></u></p>";
    if ($order_tribunal_print != '') {
        $za = htmlspecialchars_decode($order_tribunal_print);
        $pdf_html .= "<div style='text-align: justify;'><p style='line-height: 1.4;'>" . $za . "</p></div>";
    }

   
    $sql = "select jm.gen,md.desg_name,jm.judge_name,bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm Join $schemas.master_desg md ON jm.judge_desg_code = md.desg_code where bj.from_list_date ='$next_list_date9' and  bj.bench_no='$bench_no'  and bj.court_no='$courtno'  and jm.judge_code=bj.judge_code order by seniority desc";
    $sth_judge = $db->prepare($sql);
    $sth_judge->execute();
    $judge_data = $sth_judge->fetchAll();
    $coram_name_bottom = '';
    foreach ($judge_data as $value) {
        $coram_name_bottom .= '('.$value['judge_name'] .')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }



   $coeram_final =  rtrim($coram_name_bottom,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
  
   if($next_list_date9 == '2021-12-01') { 
    $pdf_html .= ""; 
} else { 
    $pdf_html .= "<p style='text-align: right; margin-top: 50px;'>" . $coeram_final . "</p>";
}
  

    $pdf_html .= "<p style='margin-top: 10px;'><b>Dated: " . date('d.m.Y', strtotime($next_list_date9)) . "</b><br> " . htmlspecialchars(strtoupper($_SESSION['user'])) . "</p>";

    $pdf_html .= "</body></html>";
    return $pdf_html;

}



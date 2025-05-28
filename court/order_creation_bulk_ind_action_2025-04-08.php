<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php";
date_default_timezone_set("Asia/Kolkata");
$server_date = date('Y-m-d'); //Returns IST
ob_flush();
ob_start();
  //  ini_set('display_errors', 1);
  //  ini_set('display_startup_errors', 1);
  // error_reporting(E_ALL);

//print_r($_REQUEST); die;

include '../master/generate_order_master.php';


 function prname()
	{
		return strtoupper("GSTAT");
	}

// At the top of the page we check to see whether the user is logged in or not
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
    die("Redirecting to login.php");
}
if (($_SESSION['user']) == '') {
    echo "you Can't access this page";
} else {
//echo "</pre>"; print_r($_POST);
    $sessionUserType = htmlspecialchars($_SESSION['id']);
    $schemas = htmlspecialchars($_SESSION['schema_name']);
    $order_to_update = (isset($_POST['action_type']) && $_POST['action_type'] != '') ? $_POST['action_type'] : '';
    if ($order_to_update == 'update') {
        $order_tribunal = $_POST['order_of_tribunal'];
        $order_id = $_POST['order_id'];
        $next_list_date = $_POST['next_list_date'];
        $pet_advocates = $_POST['pet_advocates'];
        $res_advocates = $_POST['res_advocates'];
        $filing_no = $_POST['filing_no'];
        $order_date = $_POST['order_date'];
        $demand_quantified = (!empty($_POST['demand_quantified']))?htmlspecialchars($_POST['demand_quantified']):0;
        $remand_order = (!empty($_POST['remand_order']))?htmlspecialchars($_POST['remand_order']):0;
        $authority_name = (!empty($_POST['authority_name']))?htmlspecialchars($_POST['authority_name']):0;
        $direction_subject = $_POST['direction_subject'];
        $personal_hearing = htmlspecialchars($_POST['personal_hearing']);
        $brief_order = htmlspecialchars($_POST['brief_order']);
        $order_status = (!empty($_POST['order_status']))?htmlspecialchars($_POST['order_status']):0;
        $central_determined_tax = htmlspecialchars($_POST['central_determined_tax']);
        $state_determined_tax = htmlspecialchars($_POST['state_determined_tax']);
        $integrated_determined_tax = htmlspecialchars($_POST['integrated_determined_tax']);
        $cees_determined_tax = htmlspecialchars($_POST['cees_determined_tax']);
        $total_determined_tax = $central_determined_tax+$state_determined_tax+$integrated_determined_tax+$cees_determined_tax;
        $det_amount_tax = $central_determined_tax.'||'. $state_determined_tax.'||'. $integrated_determined_tax.'||'. $cees_determined_tax.'||'. $total_determined_tax;
        

        $central_determined_interest = htmlspecialchars($_POST['central_determined_interest']);
        $state_determined_interest = htmlspecialchars($_POST['state_determined_interest']);
        $integrated_determined_interest = htmlspecialchars($_POST['integrated_determined_interest']);
        $cees_determined_interest = htmlspecialchars($_POST['cees_determined_interest']);
        $total_determined_interest = $central_determined_interest+$state_determined_interest+$integrated_determined_interest+$cees_determined_interest;
        $det_amount_interest = $central_determined_interest.'||'. $state_determined_interest.'||'. $integrated_determined_interest.'||'. $cees_determined_interest.'||'. $total_determined_interest;

        $central_determined_penalty = htmlspecialchars($_POST['central_determined_penalty']);
        $state_determined_penalty = htmlspecialchars($_POST['state_determined_penalty']);
        $integrated_determined_penalty = htmlspecialchars($_POST['integrated_determined_penalty']);
        $cees_determined_penalty = htmlspecialchars($_POST['cees_determined_penalty']);
        $total_determined_penalty = $central_determined_penalty+$state_determined_penalty+$integrated_determined_penalty+$cees_determined_penalty;
        $det_amount_penalty = $central_determined_penalty.'||'. $state_determined_penalty.'||'. $integrated_determined_penalty.'||'. $cees_determined_penalty.'||'. $total_determined_penalty;

        $central_determined_fees = htmlspecialchars($_POST['central_determined_fees']);
        $state_determined_fees = htmlspecialchars($_POST['state_determined_fees']);
        $integrated_determined_fees = htmlspecialchars($_POST['integrated_determined_fees']);
        $cees_determined_fees = htmlspecialchars($_POST['cees_determined_fees']);
        $total_determined_fees = $central_determined_fees+$state_determined_fees+$integrated_determined_fees+$cees_determined_fees;
        $det_amount_fees = $central_determined_fees.'||'. $state_determined_fees.'||'. $integrated_determined_fees.'||'. $cees_determined_fees.'||'. $total_determined_fees;

        $central_determined_others = htmlspecialchars($_POST['central_determined_others']);
        $state_determined_others = htmlspecialchars($_POST['state_determined_others']);
        $integrated_determined_others = htmlspecialchars($_POST['integrated_determined_others']);
        $cees_determined_others = htmlspecialchars($_POST['cees_determined_others']);
        $total_determined_others = $central_determined_others+$state_determined_others+$integrated_determined_others+$cees_determined_others;
        $det_amount_others = $central_determined_others.'||'. $state_determined_others.'||'. $integrated_determined_others.'||'. $cees_determined_others.'||'. $total_determined_others;

        $central_determined_refund = htmlspecialchars($_POST['central_determined_refund']);
        $state_determined_refund = htmlspecialchars($_POST['state_determined_refund']);
        $integrated_determined_refund = htmlspecialchars($_POST['integrated_determined_refund']);
        $cees_determined_refund = htmlspecialchars($_POST['cees_determined_refund']);
        $total_determined_refund = $central_determined_refund+$state_determined_refund+$integrated_determined_refund+$cees_determined_refund;
        $det_amount_refund = $central_determined_refund.'||'. $state_determined_refund.'||'. $integrated_determined_refund.'||'. $cees_determined_refund.'||'. $total_determined_refund;

        $order_in_brief_napa = htmlspecialchars($_POST['order_in_brief_napa']);
        $napa_order = (!empty($_POST['napa_order']))?htmlspecialchars($_POST['napa_order']):0;

         try {
        $db->beginTransaction();
        $delete_advocate = $db->prepare("delete from $schemas.order_daily_advocate where order_id = ?");
        $delete_advocate->bindParam(1, $order_id, PDO::PARAM_STR);
        $delete_advocate->execute();
        
        $applicanat_advt = isset($_REQUEST['master_judges']) ? $_REQUEST['master_judges'] : array();
        $respondent_advt = isset($_REQUEST['r_master_judges']) ? $_REQUEST['r_master_judges'] :  array();

        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] :  array();
        $rname = isset($_REQUEST['rname']) ? $_REQUEST['rname'] :  array();

        $name   = array_merge($applicanat_advt, $name);
        $rname  = array_merge($respondent_advt, $rname);
        

        $namecount = count($name);
        $rnamecount = count($rname);

       

        for ($k = 0; $k < $namecount; $k++) {
                $party_type1 = 'P';
                $st3 = $db->prepare("insert into $schemas.order_daily_advocate(filing_no,order_date,advocate,advocate_type,adv_serial,entry_date,user_id,order_id)
values(?,?,?,?,?,?,?,?)");
                $st3->bindParam(1, $filing_no, PDO::PARAM_STR);
                $st3->bindParam(2, $next_list_date, PDO::PARAM_STR);
                $st3->bindParam(3, $name[$k], PDO::PARAM_STR);
                $st3->bindParam(4, $party_type1, PDO::PARAM_STR);
                $st3->bindParam(5, $k, PDO::PARAM_STR);
                $st3->bindParam(6, $server_date, PDO::PARAM_STR);
                $st3->bindParam(7, $sessionUserType, PDO::PARAM_STR);
                $st3->bindParam(8, $order_id, PDO::PARAM_STR);

                $st3->execute();
            }

            for ($m = 0; $m < $rnamecount; $m++) {
                $party_type1 = 'R';
                $st4 = $db->prepare("insert into $schemas.order_daily_advocate(filing_no,order_date,advocate,advocate_type,adv_serial,entry_date,user_id,order_id)
values(?,?,?,?,?,?,?,?)");
                $st4->bindParam(1, $filing_no, PDO::PARAM_STR);
                $st4->bindParam(2, $next_list_date, PDO::PARAM_STR);
                $st4->bindParam(3, $rname[$m], PDO::PARAM_STR);
                $st4->bindParam(4, $party_type1, PDO::PARAM_STR);
                $st4->bindParam(5, $m, PDO::PARAM_STR);
                $st4->bindParam(6, $server_date, PDO::PARAM_STR);
                $st4->bindParam(7, $sessionUserType, PDO::PARAM_STR);
                $st4->bindParam(8, $order_id, PDO::PARAM_STR);
                $st4->execute();
            }


         $igst_determine = (isset($_POST['igst_determine']) && $_POST['igst_determine'] != '||')?explode("||", $_POST['igst_determine']):array();
        $gst_case_summary = (isset($_POST['gst_case_summary']) && $_POST['gst_case_summary'] != '||')?explode("||", $_POST['gst_case_summary']):array();        

        if(!empty($igst_determine)){
            $start_igst = min($igst_determine);
            $end_igst = min($igst_determine);
            for ($i=$start_igst; $i <= $end_igst; $i++) { 
                $id = $i;
                $dtrpttaxamt = (isset($_POST['dtrpttaxamt_'.$i]) && !empty($_POST['dtrpttaxamt_'.$i]))?$_POST['dtrpttaxamt_'.$i]:0;
                $dtrptinterestamt =(isset($_POST['dtrptinterestamt_'.$i]) && !empty($_POST['dtrptinterestamt_'.$i]))?$_POST['dtrptinterestamt_'.$i]:0;
                $dtrptpenalityamt =(isset($_POST['dtrptpenalityamt_'.$i]) && !empty($_POST['dtrptpenalityamt_'.$i]))?$_POST['dtrptpenalityamt_'.$i]:0;
                $dtrptothersamt = (isset($_POST['dtrptothersamt_'.$i]) && !empty($_POST['dtrptothersamt_'.$i]))?$_POST['dtrptothersamt_'.$i]:0;
                $total_igst = $dtrpttaxamt+$dtrptinterestamt+$dtrptpenalityamt+$dtrptothersamt;
                if(empty($total_igst))
                    $total_igst = 0;

                $query = "update gst_integrated_tax set dtrpttaxamt_court = ?, dtrptinterestamt_court = ?, dtrptpenalityamt_court = ?, dtrptothersamt_court = ?, dtrtotalamt_court = ? where filing_no = ? and id = ?";
                //$fnnn = '2024107201000004';
                //echo '1.   '.$dtrpttaxamt.'  2.  '.$dtrptinterestamt.'   3.  '.$dtrptpenalityamt.'   4.   '.$dtrptothersamt.'   5.  '.$total_igst;
                $st = $db->prepare($query);
                $st->bindParam(1, $dtrpttaxamt, PDO::PARAM_STR);
                $st->bindParam(2, $dtrptinterestamt, PDO::PARAM_STR);
                $st->bindParam(3, $dtrptpenalityamt, PDO::PARAM_STR);
                $st->bindParam(4, $dtrptothersamt, PDO::PARAM_STR);
                $st->bindParam(5, $total_igst, PDO::PARAM_STR);
                $st->bindParam(6, $filing_no, PDO::PARAM_STR);
                $st->bindParam(7, $id, PDO::PARAM_STR);
                $st->execute();

            }
        }

        if(!empty($gst_case_summary)){
            $start_gst = min($gst_case_summary);
            $end_gst = min($gst_case_summary);
            for ($i=$start_gst; $i <= $end_gst; $i++) { 
                $id = $i;
                $issue_order = $_POST['issue_order_'.$i]; 
                //echo $filing_no.'sdf sdf';
                $query = "update gst_case_detail_case_summery set issue_order_by_court = ? where filing_no = ? and id = ?";
                $st = $db->prepare($query);
                $st->bindParam(1, $issue_order, PDO::PARAM_STR);
                $st->bindParam(2, $filing_no, PDO::PARAM_STR);
                $st->bindParam(3, $id, PDO::PARAM_STR);
                $st->execute();  
            }
        }


        $static_text = 'GSTAT';

        $bench_id = $_REQUEST['bench_id'];
        $sql2 = "select presiding,bench_nature from $schemas.bench where id = '$bench_id' ";
		$bench_d = $db->prepare($sql2);
		$bench_d->execute();
		$bench_data = $bench_d->fetch();
		$presiding = $bench_data['presiding'];
        $bench_nature = $bench_data['bench_nature'];

        $sql2 = "select order_ref_no from $schemas.order_daily where filing_no = ? and item_no = ? ";
        $order_ref_no = $db->prepare($sql2);
        $order_ref_no->bindParam(1, $filing_no, PDO::PARAM_STR);
        $order_ref_no->bindParam(2, $order_id, PDO::PARAM_STR);
        $order_ref_no->execute();
        $order_ref_no = $order_ref_no->fetchColumn();

       
        $update_order = $db->prepare("update $schemas.order_daily set order_tribunal= ?, personal_hearing=?, brief_order = ?, order_status = ?, det_amount_tax = ?, det_amount_interest = ?, det_amount_penalty = ?, det_amount_fees = ?, det_amount_others = ?, det_amount_refund = ?, direction_subject=? , authority_name = ?, demand_quantified = ?, remand_order = ?, napa_order = ?, order_in_brief_napa = ? where item_no = ? and filing_no = ?");
            $update_order->bindParam(1, $order_tribunal, PDO::PARAM_STR);
            $update_order->bindParam(2, $personal_hearing, PDO::PARAM_STR);
            $update_order->bindParam(3, $brief_order, PDO::PARAM_STR);
            $update_order->bindParam(4, $order_status, PDO::PARAM_STR);
            $update_order->bindParam(5, $det_amount_tax, PDO::PARAM_STR);
            $update_order->bindParam(6, $det_amount_interest, PDO::PARAM_STR);
            $update_order->bindParam(7, $det_amount_penalty, PDO::PARAM_STR);
            $update_order->bindParam(8, $det_amount_fees, PDO::PARAM_STR);
            $update_order->bindParam(9, $det_amount_others, PDO::PARAM_STR);
            $update_order->bindParam(10, $det_amount_refund, PDO::PARAM_STR);
            $update_order->bindParam(11, $direction_subject, PDO::PARAM_STR);
            $update_order->bindParam(12, $authority_name, PDO::PARAM_STR);
            $update_order->bindParam(13, $demand_quantified, PDO::PARAM_STR);
            $update_order->bindParam(14, $remand_order, PDO::PARAM_STR);
            $update_order->bindParam(15, $napa_order, PDO::PARAM_STR);
            $update_order->bindParam(16, $order_in_brief_napa, PDO::PARAM_STR);
            $update_order->bindParam(17, $order_id, PDO::PARAM_STR);
            $update_order->bindParam(18, $filing_no, PDO::PARAM_STR);
            $update_order->execute();



        $pdf_html = generate_pdf_html($schemas, $db, $db, $order_id, $bench_nature, $static_text,$presiding,$order_ref_no,$_SESSION["user_actual_name"],$_SESSION['menuaccess_codeall']);
        if (!empty($pdf_html)) {
            $update_order = $db->prepare("update $schemas.order_daily set order_html = ? where item_no = ?");
            $update_order->bindParam(1, $pdf_html, PDO::PARAM_STR);
            $update_order->bindParam(2, $order_id, PDO::PARAM_STR);
            $update_order->execute();
        }
        $db->commit();
        echo " Draft Order Updated";die;
        }catch(Exception $e){
            $db->rollBack();
            echo $e->getMessage(); 
            echo " Something went wrong";die;
        }

    } else {

        $applicanat_advt = isset($_REQUEST['master_judges']) ? $_REQUEST['master_judges'] : array();
        $respondent_advt = isset($_REQUEST['r_master_judges']) ? $_REQUEST['r_master_judges'] :  array();

        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] :  array();
        $rname = isset($_REQUEST['rname']) ? $_REQUEST['rname'] :  array();

        $name   = array_merge($applicanat_advt, $name);
        $rname  = array_merge($respondent_advt, $rname);
        

        $namecount = count($name);
        $rnamecount = count($rname);

        $date = htmlspecialchars(date("d/m/Y"));
        $date1 = htmlspecialchars(date("F j, Y g:i a"));
        $year = htmlspecialchars(date("Y"));

        $author_name = '';
		$order_of_tribunal = $_REQUEST['order_of_tribunal'];
		$bench_id = $_REQUEST['bench_id'];
		$next_list_date = $_REQUEST['next_list_date'];

         $personal_hearing = htmlspecialchars($_POST['personal_hearing']);
        $brief_order = htmlspecialchars($_POST['brief_order']);
        $order_status = (!empty($_POST['order_status']))?htmlspecialchars($_POST['order_status']):0;
        $central_determined_tax = htmlspecialchars($_POST['central_determined_tax']);
        $state_determined_tax = htmlspecialchars($_POST['state_determined_tax']);
        $integrated_determined_tax = htmlspecialchars($_POST['integrated_determined_tax']);
        $cees_determined_tax = htmlspecialchars($_POST['cees_determined_tax']);
        $total_determined_tax = $central_determined_tax+$state_determined_tax+$integrated_determined_tax+$cees_determined_tax;
        $det_amount_tax = $central_determined_tax.'||'. $state_determined_tax.'||'. $integrated_determined_tax.'||'. $cees_determined_tax.'||'. $total_determined_tax;
        

        $central_determined_interest = htmlspecialchars($_POST['central_determined_interest']);
        $state_determined_interest = htmlspecialchars($_POST['state_determined_interest']);
        $integrated_determined_interest = htmlspecialchars($_POST['integrated_determined_interest']);
        $cees_determined_interest = htmlspecialchars($_POST['cees_determined_interest']);
        $total_determined_interest = $central_determined_interest+$state_determined_interest+$integrated_determined_interest+$cees_determined_interest;
        $det_amount_interest = $central_determined_interest.'||'. $state_determined_interest.'||'. $integrated_determined_interest.'||'. $cees_determined_interest.'||'. $total_determined_interest;

        $central_determined_penalty = htmlspecialchars($_POST['central_determined_penalty']);
        $state_determined_penalty = htmlspecialchars($_POST['state_determined_penalty']);
        $integrated_determined_penalty = htmlspecialchars($_POST['integrated_determined_penalty']);
        $cees_determined_penalty = htmlspecialchars($_POST['cees_determined_penalty']);
        $total_determined_penalty = $central_determined_penalty+$state_determined_penalty+$integrated_determined_penalty+$cees_determined_penalty;
        $det_amount_penalty = $central_determined_penalty.'||'. $state_determined_penalty.'||'. $integrated_determined_penalty.'||'. $cees_determined_penalty.'||'. $total_determined_penalty;

        $central_determined_fees = htmlspecialchars($_POST['central_determined_fees']);
        $state_determined_fees = htmlspecialchars($_POST['state_determined_fees']);
        $integrated_determined_fees = htmlspecialchars($_POST['integrated_determined_fees']);
        $cees_determined_fees = htmlspecialchars($_POST['cees_determined_fees']);
        $total_determined_fees = $central_determined_fees+$state_determined_fees+$integrated_determined_fees+$cees_determined_fees;
        $det_amount_fees = $central_determined_fees.'||'. $state_determined_fees.'||'. $integrated_determined_fees.'||'. $cees_determined_fees.'||'. $total_determined_fees;

        $central_determined_others = htmlspecialchars($_POST['central_determined_others']);
        $state_determined_others = htmlspecialchars($_POST['state_determined_others']);
        $integrated_determined_others = htmlspecialchars($_POST['integrated_determined_others']);
        $cees_determined_others = htmlspecialchars($_POST['cees_determined_others']);
        $total_determined_others = $central_determined_others+$state_determined_others+$integrated_determined_others+$cees_determined_others;
        $det_amount_others = $central_determined_others.'||'. $state_determined_others.'||'. $integrated_determined_others.'||'. $cees_determined_others.'||'. $total_determined_others;

        $central_determined_refund = htmlspecialchars($_POST['central_determined_refund']);
        $state_determined_refund = htmlspecialchars($_POST['state_determined_refund']);
        $integrated_determined_refund = htmlspecialchars($_POST['integrated_determined_refund']);
        $cees_determined_refund = htmlspecialchars($_POST['cees_determined_refund']);
        $total_determined_refund = $central_determined_refund+$state_determined_refund+$integrated_determined_refund+$cees_determined_refund;
        $det_amount_refund = $central_determined_refund.'||'. $state_determined_refund.'||'. $integrated_determined_refund.'||'. $cees_determined_refund.'||'. $total_determined_refund;

        $igst_determine = (isset($_POST['igst_determine']) && $_POST['igst_determine'] != '||')?explode("||", $_POST['igst_determine']):array();
        $gst_case_summary = (isset($_POST['gst_case_summary']) && $_POST['gst_case_summary'] != '||')?explode("||", $_POST['gst_case_summary']):array();
        $direction_subject = $_POST['direction_subject'];
        $authority_name = (!empty($_POST['authority_name']))?htmlspecialchars($_POST['authority_name']):0;
        $demand_quantified = (!empty($_POST['demand_quantified']))?htmlspecialchars($_POST['demand_quantified']):0;
        $remand_order = (!empty($_POST['remand_order']))?htmlspecialchars($_POST['remand_order']):0;
        $order_in_brief_napa = htmlspecialchars($_POST['order_in_brief_napa']);
        $napa_order = (!empty($_POST['napa_order']))?htmlspecialchars($_POST['napa_order']):0;
        $filing_no = htmlspecialchars($_REQUEST['filing_no']);

        try {
        $db->beginTransaction();

	if(!empty($igst_determine)){
            $start_igst = min($igst_determine);
            $end_igst = min($igst_determine);
            for ($i=$start_igst; $i <= $end_igst; $i++) { 
                $id = $i;
                $dtrpttaxamt = (isset($_POST['dtrpttaxamt_'.$i]) && !empty($_POST['dtrpttaxamt_'.$i]))?$_POST['dtrpttaxamt_'.$i]:0;
                $dtrptinterestamt =(isset($_POST['dtrptinterestamt_'.$i]) && !empty($_POST['dtrptinterestamt_'.$i]))?$_POST['dtrptinterestamt_'.$i]:0;
                $dtrptpenalityamt =(isset($_POST['dtrptpenalityamt_'.$i]) && !empty($_POST['dtrptpenalityamt_'.$i]))?$_POST['dtrptpenalityamt_'.$i]:0;
                $dtrptothersamt = (isset($_POST['dtrptothersamt_'.$i]) && !empty($_POST['dtrptothersamt_'.$i]))?$_POST['dtrptothersamt_'.$i]:0;
                $total_igst = $dtrpttaxamt+$dtrptinterestamt+$dtrptpenalityamt+$dtrptothersamt;
                if(empty($total_igst))
                    $total_igst = 0;

                $query = "update gst_integrated_tax set dtrpttaxamt_court = ?, dtrptinterestamt_court = ?, dtrptpenalityamt_court = ?, dtrptothersamt_court = ?, dtrtotalamt_court = ? where filing_no = ? and id = ?";
                //$fnnn = '2024107201000004';
                //echo '1.   '.$dtrpttaxamt.'  2.  '.$dtrptinterestamt.'   3.  '.$dtrptpenalityamt.'   4.   '.$dtrptothersamt.'   5.  '.$total_igst;
                $st = $db->prepare($query);
                $st->bindParam(1, $dtrpttaxamt, PDO::PARAM_STR);
                $st->bindParam(2, $dtrptinterestamt, PDO::PARAM_STR);
                $st->bindParam(3, $dtrptpenalityamt, PDO::PARAM_STR);
                $st->bindParam(4, $dtrptothersamt, PDO::PARAM_STR);
                $st->bindParam(5, $total_igst, PDO::PARAM_STR);
                $st->bindParam(6, $filing_no, PDO::PARAM_STR);
                $st->bindParam(7, $id, PDO::PARAM_STR);
                $st->execute();

            }
        }

        if(!empty($gst_case_summary)){
            $start_gst = min($gst_case_summary);
            $end_gst = min($gst_case_summary);
            for ($i=$start_gst; $i <= $end_gst; $i++) { 
                $id = $i;
                $issue_order = $_POST['issue_order_'.$i]; 
                //echo $filing_no.'sdf sdf';
                $query = "update gst_case_detail_case_summery set issue_order_by_court = ? where filing_no = ? and id = ?";
                $st = $db->prepare($query);
                $st->bindParam(1, $issue_order, PDO::PARAM_STR);
                $st->bindParam(2, $filing_no, PDO::PARAM_STR);
                $st->bindParam(3, $id, PDO::PARAM_STR);
                $st->execute();  
            }
        }

    

		
		$sql2 = "select from_time, presiding,court_no, bench_nature from $schemas.bench where  from_list_date ='$next_list_date' and bench_no = '$bench_id'  order by court_no asc";
		$bench_d = $db->prepare($sql2);
		$bench_d->execute();
		$bench_data = $bench_d->fetch();
		$benchnature = $bench_data["bench_nature"];
		$courtno = $bench_data['court_no'];
		$court_time = $bench_data['from_time'];
		$presiding = $bench_data['presiding'];
        $order_bench_code = $bench_id;
        $entry_date = date('Y-m-d');
      
		$sql2_check = "select item_no from $schemas.order_daily where  order_date ='$next_list_date' and filing_no = '$bench_id'  order by court_no asc";
		$sql2_check = $db->prepare($sql2_check);
		$sql2_check->execute();
		$order_data = $sql2_check->fetch();
        if (!empty($order_data) && is_array($order_data)) {
            echo "Order is already created on this date";
        } else {
            $order_no = 'Z';
            $second_part = 'NA';

            $sql="select gst_state_id from e_case_detail where filing_no = ?";
            $filing_data=$db->prepare($sql);
            $filing_data->bindParam(1, $filing_no, PDO::PARAM_STR);
            $filing_data->execute();
            $filing_data=$filing_data->fetch();
            $gst_state_id = $filing_data['gst_state_id'];

            $list_month = date('m', strtotime($next_list_date));
            $list_year = date('y', strtotime($next_list_date));
            $list_year_full = date('Y', strtotime($next_list_date));

            $month_list = ltrim($list_month, "0");

            $sql="select order_series from $schemas.order_sequence where order_month = ? and order_year = ?";
            $order_sequence=$db->prepare($sql);
            $order_sequence->bindParam(1, $month_list, PDO::PARAM_STR);
            $order_sequence->bindParam(2, $list_year_full, PDO::PARAM_STR);
            $order_sequence->execute();
            $order_sequence_save = $order_sequence=$order_sequence->fetchColumn();

            $updated_order_sequence = $order_sequence_save+1;
            $order_sequence = sprintf("%06d", $updated_order_sequence);

        $order_ref_no = $order_no.$second_part.$gst_state_id.$list_month.$list_year.$order_sequence;
         "</br>";
        $order_ref_no_series = $list_month.$list_year.$order_sequence;
            $st = $db->prepare("insert into $schemas.order_daily(filing_no,order_date,order_tribunal,
user_id,flag,bench_nature,court_no,author_name,entry_date,pet_advocate,res_advocate,bench_no,personal_hearing,brief_order,order_status,det_amount_tax,det_amount_interest,det_amount_penalty,det_amount_fees,det_amount_others,det_amount_refund,demand_quantified,authority_name,direction_subject,order_ref_no,order_ref_no_series,remand_order,napa_order,order_in_brief_napa)
values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");


			$display = 'N';
			$applicant_advocate = '';
			$respondent_advocate = '';
            $st->bindParam(1, $filing_no, PDO::PARAM_STR);
            $st->bindParam(2, $next_list_date, PDO::PARAM_STR);
            $st->bindParam(3, $order_of_tribunal, PDO::PARAM_STR);
            $st->bindParam(4, $sessionUserType, PDO::PARAM_STR);
            $st->bindParam(5, $display, PDO::PARAM_STR);
            $st->bindParam(6, $benchnature, PDO::PARAM_STR);
            $st->bindParam(7, $courtno, PDO::PARAM_STR);
            $st->bindParam(8, $author_name, PDO::PARAM_STR);
            $st->bindParam(9, $entry_date, PDO::PARAM_STR);
            $st->bindParam(10, $applicant_advocate, PDO::PARAM_STR);
            $st->bindParam(11, $respondent_advocate, PDO::PARAM_STR);
            $st->bindParam(12, $order_bench_code, PDO::PARAM_STR);
            $st->bindParam(13, $personal_hearing, PDO::PARAM_STR);
            $st->bindParam(14, $brief_order, PDO::PARAM_STR);
            $st->bindParam(15, $order_status, PDO::PARAM_STR);
            $st->bindParam(16, $det_amount_tax, PDO::PARAM_STR);
            $st->bindParam(17, $det_amount_interest, PDO::PARAM_STR);
            $st->bindParam(18, $det_amount_penalty, PDO::PARAM_STR);
            $st->bindParam(19, $det_amount_fees, PDO::PARAM_STR);
            $st->bindParam(20, $det_amount_others, PDO::PARAM_STR);
            $st->bindParam(21, $det_amount_refund, PDO::PARAM_STR);
            $st->bindParam(22, $demand_quantified, PDO::PARAM_STR);
            $st->bindParam(23, $authority_name, PDO::PARAM_STR);
            $st->bindParam(24, $direction_subject, PDO::PARAM_STR);
            $st->bindParam(25, $order_ref_no, PDO::PARAM_STR);
            $st->bindParam(26, $order_ref_no_series, PDO::PARAM_STR);
            $st->bindParam(27, $remand_order, PDO::PARAM_STR);
            $st->bindParam(28, $napa_order, PDO::PARAM_STR);
            $st->bindParam(29, $order_in_brief_napa, PDO::PARAM_STR);
            $st->execute();

             $query = "update $schemas.order_sequence set order_series = ? where order_month = ? and order_year = ?";
                $st = $db->prepare($query);
                $st->bindParam(1, $updated_order_sequence, PDO::PARAM_STR);
                $st->bindParam(2, $month_list, PDO::PARAM_STR);
                $st->bindParam(3, $list_year_full, PDO::PARAM_STR);
                $st->execute();
           // $order_id = $order_model_obj->getOrderByDateAndFiling($filing_no, $next_list_date);
		  
		   $sql="select item_no from $schemas.order_daily where filing_no = ? and order_date = ? order by item_no desc";
		   $order_data=$db->prepare($sql);
		   $order_data->bindParam(1, $filing_no, PDO::PARAM_STR);
		   $order_data->bindParam(2, $next_list_date, PDO::PARAM_STR);
		   $order_data->execute();
		   $order_id=$order_data->fetchColumn();


		   
		   for ($k = 0; $k < $namecount; $k++) {
                $party_type1 = 'P';
                $st3 = $db->prepare("insert into $schemas.order_daily_advocate(filing_no,order_date,advocate,advocate_type,adv_serial,entry_date,user_id,order_id)
values(?,?,?,?,?,?,?,?)");
                $st3->bindParam(1, $filing_no, PDO::PARAM_STR);
                $st3->bindParam(2, $next_list_date, PDO::PARAM_STR);
                $st3->bindParam(3, $name[$k], PDO::PARAM_STR);
                $st3->bindParam(4, $party_type1, PDO::PARAM_STR);
                $st3->bindParam(5, $k, PDO::PARAM_STR);
                $st3->bindParam(6, $server_date, PDO::PARAM_STR);
                $st3->bindParam(7, $sessionUserType, PDO::PARAM_STR);
                $st3->bindParam(8, $order_id, PDO::PARAM_STR);

                $st3->execute();
            }

            for ($m = 0; $m < $rnamecount; $m++) {
                $party_type1 = 'R';
                $st4 = $db->prepare("insert into $schemas.order_daily_advocate(filing_no,order_date,advocate,advocate_type,adv_serial,entry_date,user_id,order_id)
values(?,?,?,?,?,?,?,?)");
                $st4->bindParam(1, $filing_no, PDO::PARAM_STR);
                $st4->bindParam(2, $next_list_date, PDO::PARAM_STR);
                $st4->bindParam(3, $rname[$m], PDO::PARAM_STR);
                $st4->bindParam(4, $party_type1, PDO::PARAM_STR);
                $st4->bindParam(5, $m, PDO::PARAM_STR);
                $st4->bindParam(6, $server_date, PDO::PARAM_STR);
                $st4->bindParam(7, $sessionUserType, PDO::PARAM_STR);
                $st4->bindParam(8, $order_id, PDO::PARAM_STR);
                $st4->execute();
            }
            $static_text = 'GSTAT';
            $pdf_html = generate_pdf_html($schemas, $db, $db, $order_id, $benchnature, $static_text,$presiding,$order_ref_no,$_SESSION["user_actual_name"],$_SESSION['menuaccess_codeall']);
            if (!empty($pdf_html)) {
                $update_order = $db->prepare("update $schemas.order_daily set order_html = ? where item_no = ?");
                $update_order->bindParam(1, $pdf_html, PDO::PARAM_STR);
                $update_order->bindParam(2, $order_id, PDO::PARAM_STR);
                $update_order->execute();
            }
            $db->commit();
            echo "<H1>Order Created</H1>";die;
        }
        }catch(Exception $e){
            $db->rollBack();
            echo $e->getMessage() . " on line " . $e->getLine() . "\n"; 
            echo " Something went wrong";die;
        }
    }
    

 }



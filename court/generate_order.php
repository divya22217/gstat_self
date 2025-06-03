<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">

<?php
date_default_timezone_set("Asia/Kolkata");
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include "../db_inc1.php";
include '../db_inc2.php';
include("../custom/custom_function.php");

$item = htmlspecialchars($_REQUEST['itemno']);
/* ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL); */
//print_r($_REQUEST);
//die;
// At the top of the page we check to see whether the user is logged in or not
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {

    die("Redirecting to login.php");
}
if (($_SESSION['user']) == '') {
    echo "you Can't access this page";
} else {

    function master_states($db){
        $query = "select * from master_states where state_id != 0";
        $st = $db->prepare($query);
        $st->execute();
        $states = $st->fetchAll();
        return $states;

    }

    $date = htmlspecialchars(date("d/m/Y"));
    $date1 = htmlspecialchars(date("F j, Y g:i a"));
    $msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] : '';
    $year = htmlspecialchars(date("Y"));
    $msg_ip .= "User IP : " . $_SERVER["REMOTE_ADDR"] . "\r\n"; //Sender's IP

    $frm = md5(uniqid('auth', true));

/*** set the session form token ***/
    $_SESSION['form_token'] = $frm; //csrf

    $schemas = htmlspecialchars($_SESSION['schema_name']);

    ?>
<html>

<head>
    <script>
    function addMore() {
        $("<DIV>").load("input.php", function() {
            $("#product").append($(this).html());
        });
    }

    function deleteRow() {
        $('DIV.product-item').each(function(index, item) {
            jQuery(':checkbox', this).each(function() {
                if ($(this).is(':checked')) {
                    $(item).remove();
                }
            });
        });
    }
    </script>
    <title>daily order</title>
    <style type="text/css">
    div.hidden {
        display: none;
    }

    .hiden {
        display: none;
    }

    .mce-panel {
        width: 900px !important;
        margin: 0 auto !important;

    }

    .submit-btn {
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
        margin: 0 auto;
        display: block;
        margin-top: 20px;
    }
    .snCom {font-weight:bold;}

    @media print {
        h1 {
            page-break-after: always;
        }

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
    </style>
    <style type="text/css" media="print">
    .dontprint {
        display: none;
    }
    </style>
    <!-- <SCRIPT src="http://code.jquery.com/jquery-2.1.1.js"></SCRIPT> -->
    <script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
</head>
<script language="javascript">
function change(id, newClass) {
    identity = document.getElementById(id);
    identity.className = newClass;

}

function printPage() {
    change("testdiv", "hidden");
    window.print();
}

function submitForm1() {
    with(document.frm) {
        if (prt_type.options[prt_type.selectedIndex].value == 1) {
            if (case_type.value == "select") {
                alert("Select Case Type");
                case_type.focus();
                return false;
            }
            if (case_no.value == "") {
                alert("Enter Case Number");
                case_no.value = '';
                case_no.focus();
                return false;
            }

            if (case_year.value == "") {
                alert("Enter 4 digit Case Registration Year");
                case_year.focus();
                return false;
            }
        }



        if (prt_type.options[prt_type.selectedIndex].value == 2) {
            if (dairyNo.value == '') {
                alert("Please Enter GR No... ");
                dairyNo.focus();
                return false;
            }
            if (dairyyear.value == '') {
                alert("Please Enter Diary Year... ");
                dairyyear.focus();
                return false;
            }
        }

        action = "order_creation_bulk_ind.php";
        submit();
    }

}

function submitFormo() {
    with(document.frm) {


        if (next_list_date.value == "") {
            alert("Enter ORDER DATE....");
            next_list_date.value = '';
            next_list_date.focus();
            return false;
        }
        if (courtno.value == "select") {
            alert("Select Court No..");
            courtno.focus();
            return false;
        }


        action = "order_creation_bulk_ind1.php";
        submit();
    }

}

function submitFormsub() {
    with(document.frm) {


        /*if(author_name.value == '')
        {
            alert("Please Select the Member Name");
            author_name.value='';
            author_name.focus();
            return false;
        }
            */
        action = "order_creation_bulk_ind_action.php";
        submit();
        //document.frm.submit1.disabled = true;
        //document.frm.submit1.value = 'Please Wait...';
        return true;
    }

}

function submitFormsub2() {
    with(document.frm) {






    }

}
</script>

<script language="javascript">
function SetBg(txt) {
    txt.style.backgroundColor = '#ffff99';
}

function UnSetBg(txt) {
    txt.style.backgroundColor = 'white';
}

var $textArea = $("#textarea-container");

// Re-size to fit initial content.
resizeTextArea($textArea);

// Remove this binding if you don't want to re-size on typing.
$textArea.off("keyup.textarea").on("keyup.textarea", function() {
    resizeTextArea($(this));
});

function resizeTextArea($element) {
    //$element.height($element[0].scrollHeight);
}

function submitForm() {
    with(document.frm) {
        action = "order_creation_bulk_ind.php";
        submit();
    }
}



//  END
</script>
<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>

<body>

    <div class="container-fluid">

        <?php

    $msghash = isset($_REQUEST['msghash']) ? $_REQUEST['msghash'] : '';
    if ($msghash != '') {
        $msghashz = (base64_decode($msghash));

        $msghashz = explode("-", $msghashz);
        $type = $msghashz[0];
        $msg1 = $msghashz[1];

        if (!empty($msghashz)) {
            ?>
        <div class="alert alert-<?php echo $type; ?> alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $msg1; ?>
        </div>
        <?php
if ($type == 'success') {
                echo "<script>setTimeout(function () { window.close();}, 5000);</script>";

                die;
            }
        }
    }
    ?>

        <form name="frm" method="post" action="order_creation_bulk_ind_action.php">



            <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
                <tr>
                    <td valign="top" align="center" colspan="15">
                        <b>
                            <font face="Verdana" size="3"><u>ORDER CREATION</u></font>
                        </b>
                    </td>
                </tr>


                <tr>
                    <td valign="top" colspan="16" align="center">
                        <font face="Verdana" size="2">Fields marked with a <span class="error">*</span> are compulsory
                        </font>
                    </td>
                </tr>
            </table>
            <?php

    $request_no = isset($_REQUEST['no']) ? $_REQUEST['no'] : '';
    if ($request_no != '') {
        $filing_no_link = $request_no;
        //list($filing_no_link1,$court_no, $bench_id, $list_date_link) = explode('@', $filing_no_link);
        list($filing_no_link1,$court_no_link,$list_date_link,$list_before_link,$list_flag,$purpose_old,$bench_code1,$item_no)=explode('@',$filing_no_link);
        $sqlq = "select regis_date,case_year,case_no,case_type,location_code,status from $schemas.case_detail where filing_no=?";
        $sanR = $db->prepare($sqlq);
        $sanR->execute(array($filing_no_link1));
        $sanR_data = $sanR->fetch();

    }



    $case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] : $sanR_data['case_type'];
    $case_no = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] : $sanR_data['case_no'];
    $case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] : $sanR_data['case_year'];
    $bench_type = isset($_REQUEST['bench_type']) ? $_REQUEST['bench_type'] : $sanR_data['location_code'];
    $bench_location = $sanR_data['location_code'];
    $case_status = $sanR_data['status'];
    $case_type_appeal = $sanR_data['case_type'];
    $registration_date = $sanR_data['regis_date'];
	
	list($day, $month, $year) = explode('/', $list_date_link);
    $list_date_order = $year . '-' . $month . '-' . $day;

    $order_date = $db->prepare("select flag from $schemas.order_daily where filing_no = ? and order_date = ?");
    $order_date->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
    $order_date->bindParam(2, $list_date_order, PDO::PARAM_STR);
    $order_date->execute();
    $status = $order_date->fetchColumn();
    if($status == 'Y'){
        echo "Order already uploaded"; die;
    }

    $st = $db->prepare("select * from case_type where id = ?");
    $st->bindParam(1, $case_type, PDO::PARAM_STR);
    $st->execute();
    while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $case_type_name = htmlspecialchars($row['short_name']);

    }
	
	$st = $db->prepare("select short_name from mater_location_city where city_id = ?");
    $st->bindParam(1, $bench_location, PDO::PARAM_STR);
    $st->execute();
	$bench_location_short_name = $st->fetchColumn();


    $sql2 = "select from_time, presiding,court_no, bench_nature from $schemas.bench where  from_list_date ='$list_date_order' and bench_no = '$bench_code1' and court_no = '$court_no_link'  order by court_no asc";
    $bench_d = $db->prepare($sql2);
    
    $bench_d->execute();
    $bench_data = $bench_d->fetch();
    $bench_nature = $bench_data["bench_nature"];
    $court_no = $bench_data['court_no'];
    $court_time = $bench_data['from_time'];
    $presiding = $bench_data['presiding'];

    $sql2q = " select bench_name from $schemas.bench_nature where  bench_code ='$bench_nature'";
    $sth11 = $db->prepare($sql2q);
    $sth11->execute();
    $bench_nature_name = $sth11->fetchColumn();

     $query = "select order_tribunal,item_no from $schemas.order_daily where filing_no  =?  and order_date = ? order by item_no desc limit 1";
    $order_query = $db->prepare($query);
    $order_query->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
    $order_query->bindParam(2, $list_date_order, PDO::PARAM_STR);
    $order_query->execute();
    $order = $order_query->fetch();
    if(!empty($order)){
        $action_type = 'update';
        $order_tribunal = $order['order_tribunal'];
        $item_no = $order['item_no'];
    }
    else{
        $action_type = 'order_generate';
        $order_tribunal = $item_no = '';
    }

   
    $coram_name = fn_judge_list($db,$schemas,$list_date_order,$bench_code1,$presiding);
    $case_num =  $case_type_name . "/" . $case_no . "/" . $case_year ;

    echo '<div class="text-center table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <tr>
                    <td colspan="8" class="text-center" style="font-size:14px; border: 0px solid #5a5a5a !important;">
                 
                    <div class="font-weight-bold">GSTAT</div>
                    <div> ' . $bench_nature_name . ' Court No. ' . $court_no . '</div>

                    <div face="Verdana" size="3" > Case No. ' . $case_number = $case_type_name . "/" . $case_no . "/" .$bench_location_short_name.'/'. $case_year . '</div>
                    <div class="text-danger small font-weight-bold">Date & Time : ' . date("d-m-Y", strtotime($list_date_order)) . ' ' . $court_time . '</div>
                    <div class="text-success font-weight-bold font-size-18">CORAM ' . rtrim($coram_name, ', ') . '</div>
                </td>
                    </tr> </table> </div>
                    <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
                    ';

    $stcn = $db->prepare("select * from $schemas.case_detail where filing_no= '$filing_no_link1' ");
    $stcn->execute();
    while ($rw2 = $stcn->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $filing_no98 = htmlspecialchars($rw2['filing_no']);
        $pet_name = htmlspecialchars($rw2['pet_name']);
        $res_name = htmlspecialchars($rw2['res_name']);
         $status = htmlspecialchars($rw2['status']);
        $cause_title11 = $pet_name . ' VS ' . $res_name;
    }

    if ($filing_no98 != '') {
        ?>

            <table class="table table-bordered table-hover text-center">
                <tr>
                    <td align="center"><br>
                        <font color='red'>
                            <?php
echo html_entity_decode($cause_title11);
        ?> </font>
                    </td>

                </tr>
                <tr>
                    <td colspan="12"> </td>
                </tr>
            </table>

            <table width="100%" border='0' cellpadding="1" cellspacing="3" align="center">
                <tr>
                    <td align="left" colspan="1" width="100%">
                        </br>
                        <fieldset>
                            <legend><b>FOR appellant`s authorized representative </b></legend>

                            <?php
        $party_flag = 'P';
        $display = 'TRUE';
        $query = "select distinct(rep_code) from e_more_representative where filing_no=? and party_flag= ? and display= ?";
        $st12 = $db->prepare($query);
        $st12->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
        $st12->bindParam(2, $party_flag, PDO::PARAM_STR);
        $st12->bindParam(3, $display, PDO::PARAM_STR);
        $st12->execute();
        $advocate_pet = $st12->fetchAll();

        ?>

                            <?php   if (count($advocate_pet) > 0) {
                foreach ($advocate_pet as $key => $value) {
                $rep_code = $value['rep_code'];
                $stqq12 = $db->prepare("select rep_name from e_master_advocate where id=?");
                $stqq12->bindParam(1, $rep_code, PDO::PARAM_INT);
                $stqq12->execute();
                $pet_advname22 = $stqq12->fetchColumn();

                if($pet_advname22 != '') { 
                ?>
                            <input type="text" id="master_judges" name="master_judges[]"
                                value="<?php echo $pet_advname22; ?>" placeholder="Enter Legal Representative Name"
                                class="form-control name_list">

                            <?php   } }}?>



                            <div class="form-group">

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dynamic_field">

                                    </table>
                                </div>
                            </div>


                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
                </tr>


                <tr>
                    <td align="left" width="90%">
                        <fieldset>
                            <legend><b><br />For respondent's authorized representative</b></legend>

                            <?php
$party_flag = "R";
        $display = "TRUE";
         $query = "select distinct(rep_code)  from e_more_representative where filing_no=? and party_flag= ? and display= ?";
        $st12 = $db->prepare($query);
        $st12->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
        $st12->bindParam(2, $party_flag, PDO::PARAM_STR);
        $st12->bindParam(3, $display, PDO::PARAM_STR);
        $st12->execute();
        $advocate_pet = $st12->fetchAll();
        ?>


                            <?php   if (count($advocate_pet) > 0) {
                foreach ($advocate_pet as $key => $value) {
                $rep_code = $value['rep_code'];
                $stqq12 = $db->prepare("select rep_name from e_master_advocate where id=?");
                $stqq12->bindParam(1, $rep_code, PDO::PARAM_INT);
                $stqq12->execute();
                $pet_advname22 = $stqq12->fetchColumn();

                if($pet_advname22 != '') { 
                ?><input type="text" id="master_judges" name="r_master_judges[]" value="<?php echo $pet_advname22; ?>"
                                placeholder="Enter Legal Representative Name" class="form-control name_list">
                            <?php } }}?>



                            <div>

                                <div class="form-group">

                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dynamic_field1">

                                        </table>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <td><button type="button" name="add1" id="add1" value="1" class="btn btn-success">Add
                            More</button></td>
                </tr>


            </table>



            <?php 

                $query = "select disposal_nature,disposal_date from $schemas.case_disposal where filing_no = ? order by id desc limit 1";
                $disposal_data_query = $db->prepare($query);
                $disposal_data_query->bindParam(1,$filing_no_link1,PDO::PARAM_STR);
                $disposal_data_query->execute();
                $case_disposal_data = $disposal_data_query->fetch();


            if((!empty($registration_date) && $case_status == 'D' && ($case_type_appeal == '1' || $case_type_appeal == '11')) || (!empty($registration_date) && !empty($case_disposal_data) && $case_disposal_data['disposal_nature'] == '46')) { 

                $query = "select personal_hearing,brief_order,order_status,det_amount_tax,det_amount_interest,det_amount_penalty,det_amount_fees,det_amount_others,det_amount_refund,demand_quantified,authority_name,direction_subject,order_ref_no,remand_order,napa_order,order_in_brief_napa from $schemas.order_daily where filing_no  =?  and order_date = ? order by item_no desc limit 1";

                $order_data_query = $db->prepare($query);
                $order_data_query->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
                $order_data_query->bindParam(2, $list_date_order, PDO::PARAM_STR);
                $order_data_query->execute();
                $order_data = $order_data_query->fetch();
                if(!empty($order_data)){
                    $personal_hearing = $order_data['personal_hearing'];
                    $brief_order = $order_data['brief_order'];
                    $order_status = $order_data['order_status'];
                    $det_amount_tax = $order_data['det_amount_tax'];
                    $det_amount_interest = $order_data['det_amount_interest'];
                    $det_amount_penalty = $order_data['det_amount_penalty'];
                    $det_amount_fees = $order_data['det_amount_fees'];
                    $det_amount_others = $order_data['det_amount_others'];
                    $det_amount_refund = $order_data['det_amount_refund'];
                    $direction_subject = $order_data['direction_subject'];
                    $authority_name = $order_data['authority_name'];
                    $demand_quantified = $order_data['demand_quantified'];
                    $order_ref_no = $order_data['order_ref_no'];
                    $remand_order = $order_data['remand_order'];
                    $napa_order = $order_data['napa_order'];
                    $order_in_brief_napa = $order_data['order_in_brief_napa'];
                    
                    list($taxa,$taxb,$taxc,$taxd,$taxe) = explode("||",$det_amount_tax);
                    list($interesta,$interestb,$interestc,$interestd,$intereste) = explode("||",$det_amount_interest);
                    list($penaltya,$penaltyb,$penaltyc,$penaltyd,$penaltye) = explode("||",$det_amount_penalty);
                    list($feesa,$feesb,$feesc,$feesd,$feese) = explode("||",$det_amount_fees);
                    list($othersa,$othersb,$othersc,$othersd,$otherse) = explode("||",$det_amount_others);
                    list($refunda,$refundb,$refundc,$refundd,$refunde) = explode("||",$det_amount_refund);

                    $query = "select * from $schemas.napa_panelty_imposed where filing_no = ? and order_date = ? and item_no = ?";
                    $st = $db->prepare($query);
                    $st->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
                    $st->bindParam(2, $list_date_order, PDO::PARAM_STR);
                    $st->bindParam(3, $order_ref_no, PDO::PARAM_STR);
                    $st->execute();
                    $napa_panelty_imposed = $st->fetch();

                    $query = "select * from $schemas.napa_igst_penalty where filing_no = ? and order_date = ? and item_no = ? order by id";
                    $st = $db->prepare($query);
                    $st->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
                    $st->bindParam(2, $list_date_order, PDO::PARAM_STR);
                    $st->bindParam(3, $order_ref_no, PDO::PARAM_STR);
                    $st->execute();
                    $napa_igst_penalty = $st->fetchAll();

                }else{
                    $demand_quantified = 1;
                    $remand_order = 0;
                    $order_ref_no = '';
                }

                $query = "select ecd.e_reference_no,ecd.dt_of_filing::timestamp::date as filing_date, esu.name as filed_by from e_case_detail as ecd 
                left join loginmodel as lm on lm.loginid = ecd.loginid
                left join e_sign_up as esu on esu.loginidgenerated = lm.loginidgenerated 
                where ecd.filing_no = ?";
                $filing_data_query = $db->prepare($query);
                $filing_data_query->bindParam(1, $filing_no_link1, PDO::PARAM_STR);
                $filing_data_query->execute();
                $filing_data = $filing_data_query->fetch();
                if(!empty($filing_data)){
                    $e_reference_no = $filing_data['e_reference_no'];
                    $filing_date = date('d/m/Y',strtotime($filing_data['filing_date']));
                    $filed_by = $filing_data['filed_by'];
                }

                $query = "select gst_number,crn_number,order_date,ordertype,order_number from e_order_details where filing_no = ?";
                $gst_detail = $db->prepare($query);
                $gst_detail->bindParam(1,$filing_no_link1,PDO::PARAM_STR);
                $gst_detail->execute();
                $gst_data = $gst_detail->fetch();
                $gst_no = $gst_data['gst_number'];
                $crn_number = $gst_data['crn_number'];
                $order_type = $gst_data['ordertype'];
                $order_number = $gst_data['order_number'];
                $order_date = $gst_data['order_date'];

                $query = "select name,email,mobile,party_address1 from e_cases_party where filing_no = ? and party_flag in ('P','A') and party_serial_no = 1 limit 1";
                $gst_detail = $db->prepare($query);
                $gst_detail->bindParam(1,$filing_no_link1,PDO::PARAM_STR);
                $gst_detail->execute();
                $gst_data = $gst_detail->fetch();
                $appellant_address = $gst_data['party_address1'];
                $applicant_name = $gst_data['name'];
                $applicant_email = $gst_data['email'];
                $applicant_mobile = $gst_data['mobile'];

                $query = "select name,email,mobile,party_address1 from e_cases_party where filing_no = ? and party_flag in ('R','D') and party_serial_no = 1 limit 1";
                $gst_detail = $db->prepare($query);
                $gst_detail->bindParam(1,$filing_no_link1,PDO::PARAM_STR);
                $gst_detail->execute();
                $gst_data_res = $gst_detail->fetch();
                $respondent_address = $gst_data['party_address1'];
                $respondent_name = $gst_data_res['name'];
                $respondent_email = $gst_data_res['email'];
                $respondent_mobile = $gst_data_res['mobile'];

                $query = "select nclt_txn_id,txn_amount from txn_details where filing_no = ? order by created_at limit 1";
                $transaction_query = $db->prepare($query);
                $transaction_query->bindParam(1,$filing_no_link1,PDO::PARAM_STR);
                $transaction_query->execute();
                $transaction_data = $transaction_query->fetch();
                $txn_id = $transaction_data['nclt_txn_id'];
                $txn_amount = $transaction_data['txn_amount'];


                $query = "select user_type from master_user_roles_r where id = ?";
                $desg_query = $db->prepare($query);
                $desg_query->bindParam(1,$_SESSION['menuaccess_codeall'],PDO::PARAM_STR);
                $desg_query->execute();
                $desgignation = $desg_query->fetchColumn();

                $query = "select a.id,a.dtrtaxamt,a.dtrinterestamt,a.dtrpenalityamt,a.dtrfeesamt,a.dtrothersamt,a.dtrrefundamt,
                a.dtrtotalamt,a.dispttaxamt,a.disptinterestamt,a.disptpenalityamt,a.disptfeesamt,a.disptothersamt,a.disptrefundamt,
                a.dispttotalamt,a.place_state_id,b.state_name,a.dtrpttaxamt_court,a.dtrptinterestamt_court,a.dtrptpenalityamt_court,a.dtrptothersamt_court,a.dtrtotalamt_court,a.admtothersamt,a.admtpenalityamt,a.admtinterestamt,a.admttaxamt from gst_integrated_tax as a
                inner join master_states as b on b.state_id = cast(a.place_state_id as integer)
                where a.filing_no = ? order by a.id";
                //$fnn = '2024107201000026';
                $igst_integrated_query = $db->prepare($query);
                $igst_integrated_query->bindParam(1,$filing_no_link1,PDO::PARAM_STR);
                $igst_integrated_query->execute();
                $igst_integrated = $igst_integrated_query->fetchAll();

                $igst_integrated_ids = array_column($igst_integrated, 'id');

                $min_igst_integrated = min($igst_integrated_ids);
                $max_igst_integrated = max($igst_integrated_ids);
                //print_r($igst_integrated);

                $query = "select a.id,a.asdeterminedappellate,a.issuerelatedto,b.summary_name,a.issue_order_by_court FROM gst_case_detail_case_summery as a
                    inner join master_gst_case_summery as b on b.id = a.issuerelatedto where a.filing_no = ?";
                $gst_case_summary_query = $db->prepare($query);
                $gst_case_summary_query->bindParam(1,$filing_no_link1,PDO::PARAM_STR);
                $gst_case_summary_query->execute();
                $gst_case_summary = $gst_case_summary_query->fetchAll();

                $gst_case_summary_ids = array_column($gst_case_summary, 'id');

                $min_gst_case_summary = min($gst_case_summary_ids);
                $max_gst_case_summary = max($gst_case_summary_ids);

                $query = "select listing_date from $schemas.case_proceeding where filing_no = ? order by listing_date desc";
                $hearing_date_query = $db->prepare($query);
                $hearing_date_query->bindParam(1,$filing_no_link1,PDO::PARAM_STR);
                $hearing_date_query->execute();
                $hearing_dates = $hearing_date_query->fetchAll();

                

                $query = "select disputeamountcentral,disputeamountstate,disputeamountintegrated,disputeamountcess,disputeamounttotal,flag from gst_demand_admitted_dispute where filing_no = ?";
                $dispute_query = $db->prepare($query);
                $dispute_query->bindParam(1,$filing_no_link1,PDO::PARAM_STR);
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

                ?>
            <input type="hidden" name="igst_determine"
                value="<?php echo $min_igst_integrated; ?>||<?php echo $max_igst_integrated; ?>">
            <input type="hidden" name="gst_case_summary"
                value="<?php echo $min_gst_case_summary; ?>||<?php echo $max_gst_case_summary; ?>">
            <div class="row red">
                <div class="col-md-12">
                    <div class="text-center">
                        <p>Form GST APL-04A</p>
                        <p>[See rules 113(1) & 115]</p>
                        <p>Summary of the order and demand after issue of order by the GST Appellate Tribunal</p>
                    </div>
                </div>
                <div class="col-md-12" style="font-size:16px;"><b>Is it a remand order:</b>
                    <input type="radio" name="remand_order" id="rmd-y" value="1" onclick="remandOrder(1)"
                        <?php echo ($remand_order == 1)?'checked':''; ?>>Yes
                    <input type="radio" name="remand_order" id="rmd-n" value="0" onclick="remandOrder(0)"
                        <?php echo ($remand_order == 0)?'checked':''; ?>>No
                </div>
                <div class="col-md-6"><b>Order no. :</b> <span><?php echo $order_ref_no; ?></span></div>
                <div class="col-md-6 text-right"><b>Date of order :</b> <span><?php echo date('d/m/Y',strtotime($list_date_link)); ?></span>
                </div>

                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="serialNo">
                        <tbody>
                            <tr>
                                <td><b>1.</b></td>
                                <td colspan="10">GSTIN/Temporary ID/UIN - <span><?php echo $gst_no; ?></span>
                                </td>
                            </tr>

                            <tr>
                                <td><b>2.</b></td>
                                <td colspan="4">Appeal Case Reference no. - <span><?php echo $case_num; ?></span></td>
                                <td colspan="6">Date - <span><?php echo $filing_date; ?></span></td>
                            </tr>

                            <tr>
                                <td><b>3.</b></td>
                                <td colspan="10">Details of the appellant -
                                    <span><?php echo $applicant_name. ' , ' .$applicant_email. ' , ' .$applicant_mobile; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td><b>4.</b></td>
                                <td colspan="10">Details of the Respondent -
                                    <span><?php echo $respondent_name. ' , ' .$respondent_email. ' , ' .$respondent_mobile; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td><b>5.</b></td>
                                <td colspan="10">Order appealed against - <span><?php echo $crn_number; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>5.1</td>
                                <td colspan="9"><b>Order Type -</b> <span><?php echo $order_type; ?></span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>5.2</td>
                                <td colspan="5"><b>Ref Number -</b> <span><?php echo $order_number; ?></span></td>
                                <td colspan="4"><b>Date -</b> <span><?php echo $order_date; ?></span></td>
                            </tr>

                            <tr>
                                <td><b>6.</b></td>
                                <td colspan="10">Personal Hearing - <?php  
                                                foreach ($hearing_dates as $key => $hearing_date) {
                                                    echo date('d/m/Y',strtotime($hearing_date['listing_date']))."   ";
                                                }
                                             ?></td>
                            </tr>
                            <tr class="remandTable hiden">
                                <td><b>7.</b></td>
                                <td colspan="10">Status of Order under Appeal -
                                    <select name="order_status" class="form-control select">
                                        <option value='0' <?php echo ($order_status == 0)?'selected':''; ?>>Select
                                            Status</option>
                                        <option value="1" <?php echo ($order_status == 1)?'selected':''; ?>>Confirmed –
                                            Order under Appeal is confirmed</option>
                                        <option value="2" <?php echo ($order_status == 2)?'selected':''; ?>>Modified –
                                            Order under Appeal is modified</option>
                                        <option value="3" <?php echo ($order_status == 3)?'selected':''; ?>>Reject -
                                            Order under Appeal is annulled</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><b>8.</b></td>
                                <td colspan="10">Order in brief: (Free text, Max 2500 characters)) <textarea
                                        name="brief_order" class="form-control"
                                        placeholder="Type here"><?php echo $brief_order; ?></textarea></td>
                            </tr>

                            <tr>
                                <td colspan="11">
                                    <div class="text-center"><b>Summary of Order</b></div>
                                </td>
                            </tr>
                            <?php if($case_type_appeal != 11)  { ?>
                            <tr>
                                <td><b>9.</b></td>
                                <td colspan="10">If demand order then whether demand quantified:
                                    <input type="radio" name="demand_quantified" id="q1-y" value="1"
                                        onclick="toggleContent(1)"
                                        <?php echo ($demand_quantified == 1)?'checked':''; ?>>Yes
                                    <input type="radio" name="demand_quantified" id="q1-n" value="0"
                                        onclick="toggleContent(0)"
                                        <?php echo ($demand_quantified == 0)?'checked':''; ?>>No
                                </td>
                            </tr>
                            <tr class="hideTable">
                                <td colspan="11">
                                    <div class="text-center"><b>Section-I</b></div>
                                </td>
                            </tr>
                            <tr class="hideTable">
                                <td><b>Particulars</b></td>
                                <td colspan="2">Central tax</td>
                                <td colspan="2">State/UT tax</td>
                                <td colspan="2">Integrated tax</td>
                                <td colspan="2">Cess</td>
                                <td colspan="2">Total</td>
                            </tr>
                            <tr class="hideTable">
                                <td>&nbsp;</td>
                                <td>Disputed Amount</td>
                                <td>Determined Amount</td>
                                <td>Disputed Amount</td>
                                <td>Determined Amount</td>
                                <td>Disputed Amount</td>
                                <td>Determined Amount</td>
                                <td>Disputed Amount</td>
                                <td>Determined Amount</td>
                                <td>Disputed Amount</td>
                                <td>Determined Amount</td>
                            </tr>
                            <tr class="hideTable">
                                <td><b>1</b></td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                                <td>9</td>
                                <td>10</td>
                                <td>11</td>
                            </tr>
                            <tr class="hideTable">
                                <td><b>(a) Tax</b></td>
                                <td><?php echo $central_dis_tax; ?></td>
                                <td><input type="number" id="" name="central_determined_tax" class="tax form-control"
                                        value="<?php echo $taxa; ?>"></td>
                                <td><?php echo $state_dis_tax; ?></td>
                                <td><input type="number" id="" name="state_determined_tax" class="tax form-control"
                                        value="<?php echo $taxb; ?>"></td>
                                <td><?php echo $intg_dis_tax; ?></td>
                                <td><input type="number" id="" name="integrated_determined_tax" class="tax form-control"
                                        value="<?php echo $taxc; ?>"></td>
                                <td><?php echo $cess_dis_tax; ?></td>
                                <td><input type="number" id="" name="cees_determined_tax" class="tax form-control"
                                        value="<?php echo $taxd; ?>"></td>
                                <td><?php echo $total_dis_tax; ?></td>
                                <td id="determined_tax"></td>
                            </tr>
                            <tr class="hideTable">
                                <td><b>(b) Interest</b></td>
                                <td><?php echo $central_dis_int; ?></td>
                                <td><input type="number" id="" name="central_determined_interest"
                                        class="interest form-control" value="<?php echo $interesta; ?>"></td>
                                <td><?php echo $state_dis_int; ?></td>
                                <td><input type="number" id="" name="state_determined_interest"
                                        class="interest form-control" value="<?php echo $interestb; ?>"></td>
                                <td><?php echo $intg_dis_int; ?></td>
                                <td><input type="number" id="" name="integrated_determined_interest"
                                        class="interest form-control" value="<?php echo $interestc; ?>"></td>
                                <td><?php echo $cess_dis_int; ?></td>
                                <td><input type="number" id="" name="cees_determined_interest"
                                        class="interest form-control" value="<?php echo $interestd; ?>"></td>
                                <td><?php echo $total_dis_int; ?></td>
                                <td id="determined_interest"></td>
                            </tr>
                            <tr class="hideTable">
                                <td><b>(c) Penalty</b></td>
                                <td><?php echo $central_dis_pen; ?></td>
                                <td><input type="number" id="" name="central_determined_penalty"
                                        class="penalty form-control" value="<?php echo $penaltya; ?>"></td>
                                <td><?php echo $state_dis_pen; ?></td>
                                <td><input type="number" id="" name="state_determined_penalty"
                                        class="penalty form-control" value="<?php echo $penaltyb; ?>"></td>
                                <td><?php echo $intg_dis_pen; ?></td>
                                <td><input type="number" id="" name="integrated_determined_penalty"
                                        class="penalty form-control" value="<?php echo $penaltyc; ?>"></td>
                                <td><?php echo $cess_dis_pen; ?></td>
                                <td><input type="number" id="" name="cees_determined_penalty"
                                        class="penalty form-control" value="<?php echo $penaltyd; ?>"></td>
                                <td><?php echo $total_dis_pen; ?></td>
                                <td id="determined_penalty"></td>
                            </tr>
                            <tr class="hideTable">
                                <td><b>(d) Fees</b></td>
                                <td><?php echo $central_dis_fee; ?></td>
                                <td><input type="number" id="" name="central_determined_fees" class="fees form-control"
                                        value="<?php echo $feesa; ?>"></td>
                                <td><?php echo $state_dis_fee; ?></td>
                                <td><input type="number" id="" name="state_determined_fees" class="fees form-control"
                                        value="<?php echo $feesb; ?>"></td>
                                <td><?php echo $intg_dis_fee; ?></td>
                                <td><input type="number" id="" name="integrated_determined_fees"
                                        class="fees form-control" value="<?php echo $feesc; ?>"></td>
                                <td><?php echo $cess_dis_fee; ?></td>
                                <td><input type="number" id="" name="cees_determined_fees" class="fees form-control"
                                        value="<?php echo $feesd; ?>"></td>
                                <td><?php echo $total_dis_fee; ?></td>
                                <td id="determined_fees"></td>
                            </tr>
                            <tr class="hideTable">
                                <td><b>(e) Others</b></td>
                                <td><?php echo $central_dis_oth; ?></td>
                                <td><input type="number" id="" name="central_determined_others"
                                        class="others form-control" value="<?php echo $othersa; ?>"></td>
                                <td><?php echo $state_dis_oth; ?></td>
                                <td><input type="number" id="" name="state_determined_others"
                                        class="others form-control" value="<?php echo $othersb; ?>"></td>
                                <td><?php echo $intg_dis_oth; ?></td>
                                <td><input type="number" id="" name="integrated_determined_others"
                                        class="others form-control" value="<?php echo $othersc; ?>"></td>
                                <td><?php echo $cess_dis_oth; ?></td>
                                <td><input type="number" id="" name="cees_determined_others" class="others form-control"
                                        value="<?php echo $othersd; ?>"></td>
                                <td><?php echo $total_dis_oth; ?></td>
                                <td id="determined_others"></td>
                            </tr>
                            <tr class="hideTable">
                                <td><b>(f) Refund</b></td>
                                <td><?php echo $central_dis_rfn; ?></td>
                                <td><input type="number" id="" name="central_determined_refund"
                                        class="refund form-control" value="<?php echo $refunda; ?>"></td>
                                <td><?php echo $state_dis_rfn; ?></td>
                                <td><input type="number" id="" name="state_determined_refund"
                                        class="refund form-control" value="<?php echo $refundb; ?>"></td>
                                <td><?php echo $intg_dis_rfn; ?></td>
                                <td><input type="number" id="" name="integrated_determined_refund"
                                        class="refund form-control" value="<?php echo $refundc; ?>"></td>
                                <td><?php echo $cess_dis_rfn; ?></td>
                                <td><input type="number" id="" name="cees_determined_refund" class="refund form-control"
                                        value="<?php echo $refundd; ?>"></td>
                                <td><?php echo $total_dis_rfn; ?></td>
                                <td id="determined_refund"></td>
                            </tr>
                            <?php if(!empty($igst_integrated)){ ?>
                            <tr class="hideTable">
                                <td colspan="11">
                                    <div class="text-center"><b>Section-II</b></div>
                                </td>
                            </tr>
                            <tr class="hideTable">
                                <td colspan="3">Place of Supply/Name of State/UT</td>
                                <td>Demand</td>
                                <td>Tax</td>
                                <td>Interest</td>
                                <td>Penalty</td>
                                <td colspan="2">Other</td>
                                <td colspan="2">Total</td>
                            </tr>
                            <tr class="hideTable">
                                <td colspan="3">(1)</td>
                                <td>(2)</td>
                                <td>(3)</td>
                                <td>(4)</td>
                                <td>(5)</td>
                                <td colspan="2">(6)</td>
                                <td colspan="2">(7)</td>
                            </tr>
                            <?php
                                        
                        foreach ($igst_integrated as $key => $value) {  
                            if($key == '0'){
                                $disputed_amount_tax = $value['dtrtaxamt'];
                                $disputed_amount_interest = $value['dtrinterestamt'];
                                $disputed_amount_penalty = $value['dtrpenalityamt'];
                                $disputed_amount_other = $value['dtrothersamt'];
                            }
                            $disputed_amount_tax = $disputed_amount_tax - $value['admttaxamt'];
                            $disputed_amount_interest = $disputed_amount_interest - $value['admtinterestamt'];
                            $disputed_amount_penalty = $disputed_amount_penalty - $value['admtpenalityamt'];
                            $disputed_amount_others = $disputed_amount_other - $value['admtothersamt'];
                            $disputed_amount_total = $disputed_amount_tax+$disputed_amount_interest+$disputed_amount_penalty+$disputed_amount_others;
                        ?>

                            <tr class="hideTable">
                                <td colspan="3" rowspan="2"><?php echo $value['state_name'] ?></td>
                                <td>Amount in dispute</td>
                                <td><?php echo $disputed_amount_tax; ?></td>
                                <td><?php echo $disputed_amount_interest; ?></td>
                                <td><?php echo $disputed_amount_penalty; ?></td>
                                <td colspan="2"><?php echo $disputed_amount_others; ?></td>
                                <td colspan="2">
                                    <?php echo $disputed_amount_total; ?>
                                </td>
                            </tr>
                            <tr class="hideTable">
                                <td>Amount Determined</td>
                                <td><input type="number" id="dtrpttaxamt_<?php echo $value['id'] ?>"
                                        name="dtrpttaxamt_<?php echo $value['id'] ?>"
                                        class="section2_<?php echo $value['id']; ?> form-control"
                                        onkeyup="updateDeterminedAmountSectionTwo('<?php echo $value[id]; ?>')"
                                        value="<?php echo $value['dtrpttaxamt_court']; ?>"></td>
                                <td><input type="number" id="dtrptinterestamt_<?php echo $value['id'] ?>"
                                        name="dtrptinterestamt_<?php echo $value['id'] ?>"
                                        class="section2_<?php echo $value['id']; ?> form-control"
                                        onkeyup="updateDeterminedAmountSectionTwo('<?php echo $value[id]; ?>')"
                                        value="<?php echo $value['dtrptinterestamt_court']; ?>"></td>
                                <td><input type="number" id="dtrptpenalityamt_<?php echo $value['id'] ?>"
                                        name="dtrptpenalityamt_<?php echo $value['id'] ?>"
                                        class="section2_<?php echo $value['id']; ?> form-control"
                                        onkeyup="updateDeterminedAmountSectionTwo('<?php echo $value[id]; ?>')"
                                        value="<?php echo $value['dtrptpenalityamt_court']; ?>"></td>
                                <td colspan="2"><input type="number" id="dtrptothersamt_<?php echo $value['id'] ?>"
                                        name="dtrptothersamt_<?php echo $value['id'] ?>"
                                        class="section2_<?php echo $value['id']; ?> form-control"
                                        onkeyup="updateDeterminedAmountSectionTwo('<?php echo $value[id]; ?>')"
                                        value="<?php echo $value['dtrptothersamt_court']; ?>"></td>
                                <td colspan="2" id="dtrtotal_<?php echo $value['id']?>">
                                    <?php echo $value['dtrtotalamt_court'] ?></td>
                            </tr>

                            <?php   } }  ?>
                            <tr>
                                <td><b>10.</b></td>
                                <td colspan="10">For Other orders and Demand orders which are not quantified:</td>
                            </tr>
                            <tr>
                                <td colspan="3">Issues as raised by proper officer</td>
                                <td colspan="4">Issues as determined by Appellate/Revisional authority</td>
                                <td colspan="4">Order by GST Appellate Tribunal</td>
                            </tr>
                            <?php foreach ($gst_case_summary as $key => $value) { ?>
                            <tr>
                                <td colspan="3"><?php echo $value['summary_name']; ?></td>
                                <td colspan="4"><?php echo $value['asdeterminedappellate']; ?></td>
                                <td colspan="4"><input type="text" id="issue_order_<?php echo $value['id']; ?>"
                                        name="issue_order_<?php echo $value['id']; ?>" class="form-control"
                                        value="<?php echo $value['issue_order_by_court'] ?>"></td>
                            </tr>
                            <?php } } ?>
                            <tr class="remandTable11">
                                <td><b>11.</b></td>
                                <td colspan="10">If remanded with directions:</span>
                                </td>
                            </tr>
                            <tr class="remandTable11">
                                <td></td>
                                <td colspan="10">a) Remanded to: (specify authority to whom remanded.)
                                    <select name="authority_name" class="form-control select" style="width:300px;">
                                        <option value='0' selected>Select Authority</option>
                                        <option value="1" <?php echo ($authority_name == 1)?'selected':''; ?>>
                                            Adjudicating Authority</option>
                                        <option value="2" <?php echo ($authority_name == 2)?'selected':''; ?>>Appellate
                                            Authority</option>
                                        <option value="3" <?php echo ($authority_name == 3)?'selected':''; ?>>Revisional
                                            Authority</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="remandTable11">
                                <td></td>
                                <td colspan="10">b) Directions subject to which remanded, if any: (Free text, Max 1000
                                    words)
                                    <textarea name="direction_subject" class="form-control"
                                        placeholder="Type here"><?php echo $direction_subject;  ?></textarea>
                                </td>
                            </tr>

                            <?php if($case_type_appeal == 11)  { ?>
                            <tr class="napatable">
                                <td colspan="11">
                                    <div class="text-center"><b>Section-III (Anti-profiterring)</b></div>
                                </td>
                            </tr>
                            <!--<tr class="napatable">
                                <td><b>12.</b></td>
                                <td colspan="10">Order In brief 
                                    <textarea name="order_in_brief_napa" class="form-control"
                                        placeholder="Type here"><?php echo $order_in_brief_napa;  ?></textarea>
                                </td>
                            </tr>-->
                            <tr class="napatable">
                                <td><b>12.</b></td>
                                <td colspan="10">Type of order -
                                    <select name="napa_order" class="form-control select" id="napaOption">
                                        <option value='0' <?php echo ($napa_order == 0)?'selected':''; ?>>Select
                                        </option>
                                        <option value="1" <?php echo ($napa_order == 1)?'selected':''; ?>>Reduction in
                                            Price</option>
                                        <option value="2" <?php echo ($napa_order == 2)?'selected':''; ?>>Return to
                                            Recipient of Amount not passed on, along with interest</option>
                                        <option value="3" <?php echo ($napa_order == 3)?'selected':''; ?>>Deposit in
                                            Consumer Welfare Fund/s</option>
                                        <option value="4" <?php echo ($napa_order == 4)?'selected':''; ?>>Penalty
                                            Imposed(Amount to be specified): </option>
                                        <option value="5" <?php echo ($napa_order == 5)?'selected':''; ?>>Cancellation
                                            of Registration</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="napaSubTable hiden">
                                <td>&nbsp;</td>
                                <td colspan="10">
                                    <table class="table table-striped table-bordered">
                                        <tr class="">
                                            <td>&nbsp;</td>
                                            <td>Central tax</td>
                                            <td>State/UT tax</td>
                                            <td>Integrated tax</td>
                                            <td>Cess</td>
                                            <td>Total</td>
                                        </tr>
                                        <tr class="">
                                            <td>(f) Penalty Determined </td>
                                            <td><input type="number" id="napa_center_penalty" name="napa_center_penalty" class="form-control napa_penalty" value="<?php echo $napa_panelty_imposed['central_tax']; ?>" onkeyup="napa_penalty();" ></td>
                                            <td><input type="number" id="napa_state_penalty" name="napa_state_penalty" class="form-control napa_penalty" value="<?php echo $napa_panelty_imposed['state_tax']; ?>" onkeyup="napa_penalty();"></td>
                                            <td><input type="number" id="napa_intg_penalty" name="napa_intg_penalty" class="form-control napa_penalty" value="<?php echo $napa_panelty_imposed['integrated_tax']; ?>" onkeyup="napa_penalty();"></td>
                                            <td><input type="number" id="napa_cess_penalty" name="napa_cess_penalty" class="form-control napa_penalty" value="<?php echo $napa_panelty_imposed['cess']; ?>" onkeyup="napa_penalty();"></td>
                                            <td><label id="napa_total_penalty" name="napa_total_penalty" class="form-control napa_penalty"><?php echo $napa_panelty_imposed['total_tax'] ?></label>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                            <tr class="napaSubTable hiden">
                                <td>&nbsp;</td>
                                <td colspan="10">
                                    <div>
                                        <table class="table table-striped table-bordered" id="igstTable">
                                            <tr class="">
                                                <td>Place of Supply/ Name of State/UT (of IGST Penalty specfied above)
                                                </td>
                                                <td>Amount</td>
                                            </tr>
                                            
<?php
    $states = master_states($db);
    if(!empty($napa_igst_penalty)){
      foreach($napa_igst_penalty as $k=>$v){ ?>
            <tr><td>
            <select name="tax_state_id[]" class="form-control select">
                <option value='0' >Select</option>
                <?php foreach ($states as $key => $value) { ?>
                <option value="<?php echo $value['state_id'] ?>" <?php echo ($value['state_id'] == $v['state_id'])?'selected':''; ?> ><?php echo $value['state_name']; ?></option>
               <?php } ?>
            </select> 
        
            </td>
            <td><input type="number" id=""  name="tax_amount[]" class="form-control" value="<?php echo $v['tax_amount']; ?>">
            </td>
        </tr>

<?php    } ?>
    </div>

    </td>
</tr>
<?php }else{


?>                                                  <tr class="">
                                                    <td>
                                                    <select name="tax_state_id[]" class="form-control select">
                                                        <option value='0' >Select</option>
                                                        <?php foreach ($states as $key => $value) { ?>
                                                        <option value='<?php echo $value['state_id'] ?>'><?php echo $value['state_name']; ?></option>
                                                       <?php } ?>
                                                    </select>
                                                </td>
                                                <td><input type="number" id="" name="tax_amount[]" class="form-control" value="">
                                                </td>
                                            <?php } ?>
                                            </tr>
                                        </table>
                                        <div class="btn btn-sm btn-primary add-row" style="float:right">Add rows</div>
                                    </div>

                                </td>
                            </tr>


                            <?php  } ?>

                        </tbody>
                    </table>
                    <p>Place : <?php echo strtoupper($_SESSION['schema_name']); ?></p>
                    <p>Date :</p>
                </div>
                <div class="col-md-12 text-right">
                    <p><span>Signature</span></p>
                    <p><span><?php echo strtoupper($_SESSION['schema_name']).'  '.$_SESSION['user_actual_name']; ?></span>
                    </p>
                    <p><span>Designation : <?php echo $desgignation; ?></span></p>
                    <p><span>Jurisdiction :</span></p>
                </div>
            </div>
            <?php } ?>


            <tr>
                <td colspan="2">
                    <h3 class="text-center"><u>ORDER</u></h3>
                    <div class="span12" id="content">
                        <div class="row-fluid">

                            <div class="block">
                                <div class="navbar navbar-inner block-header">
                                    <div class="muted pull-left"></div>
                                </div>
                                <div class="block-content collapse in">
                                    <textarea name="order_of_tribunal" id="order_tribunal" cols="60"
                                        rows="20"><?php echo $order_tribunal; ?></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="15" align="center" class="text-center">
                    <input id="submit1" class="btn btn-primary submit-btn" type="button" name="submit1" value="Submit"
                        onClick="return submitFormsub();" />
                </td>
            </tr>
            <input type="hidden" name="action_type" value="<?php echo $action_type; ?>" />
            <input type="hidden" name="order_id" value="<?php echo $item_no; ?>" />
            <input type="hidden" name="next_list_date" value="<?php echo htmlspecialchars($list_date_order); ?>" />
            <input type="hidden" name="bench_id" value="<?php echo htmlspecialchars($bench_code1); ?>" />

            <input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
            <input type="hidden" name="filing_no" value="<?php echo htmlspecialchars($filing_no98); ?>" />

            </table>
    </div>

    </form>
</body>

</html>





<script language="javascript" type="text/javascript">
tinymce.init({
    gecko_spellcheck: true,
    width: "640",
    selector: "textarea#order_tribunal",
    theme: "modern",
    //menubar: "edit insert view format table",

    menu: {
        edit: {
            title: 'Edit',
            items: 'undo redo  | cut copy paste selectall | searchreplace'
        },
        insert: {
            title: 'Insert',
            items: 'edit image charmap pagebreak insertdatetime hr  '
        },
        view: {
            title: 'View',
            items: 'preview fullscreen'
        },
        format: {
            title: 'Format',
            items: 'bold italic underline strikethrough superscript subscript | removeformat'
        },
        table: {
            title: 'Table',
            items: 'inserttable tableprops deletetable | cell row column'
        }
    },

    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak lineheight",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        " template paste textcolor colorpicker textpattern",
        "code"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent |  image fontselect fontsizeselect | code",
    toolbar2: " lineheightselect  preview | forecolor backcolor  ",
    removed_menuitems: 'newdocument',

    image_advtab: true,
    templates: [{
            title: 'Test template 1',
            content: 'Test 1'
        },
        {
            title: 'Test template 2',
            content: 'Test 2'
        }
    ]
});

$(document).ready(function() {
    var j = 10000;
    $('#add1').click(function() {
        j++;
        $('#dynamic_field1').append('<tr id="row1' + j +
            '"><td><input type="text" name="rname[]" value="" placeholder="Enter Legal Representative Name" class="form-control name_list" /></td><td><button type="button" name="remove" id="' +
            j + '" class="btn btn-danger btn_remove">X</button></td></tr>');
    });
    $(document).on('click', '.btn_remove', function() {

        var button_id1 = $(this).attr("id");

        $('#row1' + button_id1 + '').remove();

    });

    var i = 1;

    $('#add').click(function() {
        i++;
        $('#dynamic_field').append('<tr id="row' + i +
            '"><td><input type="text" name="name[]" placeholder="Enter Legal Representative Name" class="form-control name_list"></td><td><button type="button" name="remove" id="' +
            i + '" class="btn btn-danger btn_remove">X</button></td></tr>');
    });

    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");

        $('#row' + button_id + '').remove();
    });


    // Function to update sum on focus out
    function updateTaxDeterminedSum() {
        const elements = document.querySelectorAll('.tax');
        let sum = 0;

        elements.forEach(element => {
            sum += parseFloat(element.value) || 0; // Convert value to number or default to 0 if NaN
        });

        document.getElementById('determined_tax').textContent = sum;
    }

    // Add event listeners to each input for 'focusout'
    const inputs_tax = document.querySelectorAll('.tax');

    inputs_tax.forEach(input => {
        input.addEventListener('keyup', updateTaxDeterminedSum);
    });

    function updateInterestDeterminedSum() {
        const elements = document.querySelectorAll('.interest');
        let sum = 0;

        elements.forEach(element => {
            sum += parseFloat(element.value) || 0; // Convert value to number or default to 0 if NaN
        });

        document.getElementById('determined_interest').textContent = sum;
    }

    // Add event listeners to each input for 'focusout'
    const inputs_interest = document.querySelectorAll('.interest');

    inputs_interest.forEach(input => {
        input.addEventListener('keyup', updateInterestDeterminedSum);
    });

    function updatePenaltyDeterminedSum() {
        const elements = document.querySelectorAll('.penalty');
        let sum = 0;

        elements.forEach(element => {
            sum += parseFloat(element.value) || 0; // Convert value to number or default to 0 if NaN
        });

        document.getElementById('determined_penalty').textContent = sum;
    }

    // Add event listeners to each input for 'focusout'
    const inputs_penalty = document.querySelectorAll('.penalty');

    inputs_penalty.forEach(input => {
        input.addEventListener('keyup', updatePenaltyDeterminedSum);
    });

    function updateFeesDeterminedSum() {
        const elements = document.querySelectorAll('.fees');
        let sum = 0;

        elements.forEach(element => {
            sum += parseFloat(element.value) || 0; // Convert value to number or default to 0 if NaN
        });

        document.getElementById('determined_fees').textContent = sum;
    }

    // Add event listeners to each input for 'focusout'
    const inputs_fees = document.querySelectorAll('.fees');

    inputs_fees.forEach(input => {
        input.addEventListener('keyup', updateFeesDeterminedSum);
    });

    function updateOthersDeterminedSum() {
        const elements = document.querySelectorAll('.others');
        let sum = 0;

        elements.forEach(element => {
            sum += parseFloat(element.value) || 0; // Convert value to number or default to 0 if NaN
        });

        document.getElementById('determined_others').textContent = sum;
    }

    // Add event listeners to each input for 'focusout'
    const inputs_others = document.querySelectorAll('.others');

    inputs_others.forEach(input => {
        input.addEventListener('keyup', updateOthersDeterminedSum);
    });

    function updateRefundDeterminedSum() {
        const elements = document.querySelectorAll('.refund');
        let sum = 0;

        elements.forEach(element => {
            sum += parseFloat(element.value) || 0; // Convert value to number or default to 0 if NaN
        });

        document.getElementById('determined_refund').textContent = sum;
    }

    // Add event listeners to each input for 'focusout'
    const inputs_refund = document.querySelectorAll('.refund');

    inputs_refund.forEach(input => {
        input.addEventListener('keyup', updateRefundDeterminedSum);
    });

    // Initial sum calculation
    updateTaxDeterminedSum();
    updateRefundDeterminedSum();
    updateOthersDeterminedSum();
    updateFeesDeterminedSum();
    updatePenaltyDeterminedSum();
    updateInterestDeterminedSum();








});

window.onload = function() {
    toggleContent('<?php echo $demand_quantified; ?>');
    remandOrder('<?php echo $remand_order; ?>');
    
};

// Show Hide Table
function toggleContent(val) {
    //alert(val);
    if (val == '1') {
        // alert("sdf");
        $('.hideTable').removeClass('hiden');
        $('.hideTable').find('input').prop('disabled', false);
    } else {
        // alert("sdf sdf");
        $('.hideTable').addClass('hiden');
        $('.hideTable').find('input').val('').prop('disabled', true);
    }
}

function remandOrder(val) {
    if (val == '1') {
        $('.remandTable').addClass('hiden');
        $('.remandTable11').removeClass('hiden');
        $('.remandTable').find('input').val('').prop('disabled', true);

    } else {
        $('.remandTable').removeClass('hiden');
        $('.remandTable11').addClass('hiden');
        $('.remandTable').find('input').prop('disabled', false);
    }
    updateSerialNumbers();
}


$("#napaOption").change(function() {
    if ($(this).val() === "4") {
        $('.napaSubTable').removeClass('hiden');
        $('.napaSubTable').find('input').prop('disabled', false);
        $('.napaSubTable').find('label').prop('disabled', false);
    } else {
        $('.napaSubTable').addClass('hiden');
        $('.napaSubTable').find('input').val('').prop('disabled', true);
        $('.napaSubTable').find('label').html('').prop('disabled', true);
    }
});

if($("#napaOption").val() === "4"){
    $('.napaSubTable').removeClass('hiden');
    $('.napaSubTable').find('input').prop('disabled', false);
    $('.napaSubTable').find('label').prop('disabled', false);
}


$(".add-row").click(function() {
    var rowCount = $("#igstTable tbody tr").length + 1;
    $.ajax({
                type: "POST",
                url: 'order_ajax.php',
                data: {action:'get_states'},
                beforeSend: function() {
                   // $("#comp_note").modal('show');
                    //$("#comp_note_body").html('loading....');
                },
                success: function (data) {
                   $("#igstTable tbody").append(data)
                   //alert("success");
                },
                error: function (textStatus, errorThrown) {
                    //$("#comp_note_body").html('');
                   alert("error");
                }

            });
    var newRow = `
                <tr>
                    <td>
                        <select name="" class="form-control select">
                            <option value='0' selected>Select</option>
                            <option value='1'>Option 1</option>
                            <option value='2'>Option 2</option>
                            <option value='3'>Option 3</option>
                        </select>
                    </td>
                    <td><input type="number" id="" name="" class="form-control" value="" style="display: inline-block; width: 92%;">
                    <span class="btn btn-sm btn-danger remove-row rounded" style="float:right">-</span>
                    </td> 
                </tr>
            `;
    //$("#igstTable tbody").append(newRow);
});

$(document).on("click", ".remove-row", function() {
    $(this).closest("tr").remove();
});




// Call the function on page load

function napa_penalty(){
    const elements = document.querySelectorAll('.napa_penalty');
    let sum = 0;

    elements.forEach(element => {
        sum += parseFloat(element.value) || 0; // Convert value to number or default to 0 if NaN
    });

    document.getElementById('napa_total_penalty').textContent = sum;
}



function updateDeterminedAmountSectionTwo(id) {
    const elements = document.querySelectorAll('.section2_' + id);
    let sum = 0;

    elements.forEach(element => {
        sum += parseFloat(element.value) || 0; // Convert value to number or default to 0 if NaN
    });

    document.getElementById('dtrtotal_' + id).textContent = sum;
}
</script>

<?php
}
}
?>

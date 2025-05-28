<?php
$generate_id = explode('-', base64_decode($_REQUEST['generate_id']));
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
$schemas = $_SESSION['schema_name'];
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    $schema = $_SESSION['schema_name'];
    function get_data($db, $table_name, $where = array(), $where_like = array(), $column = '*', $order_by = null, $order_column = null, $limit = null)
    {
        $data_main = array();
        $where_as = '';
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



    $data = get_data($db, $schema . '.case_detail', array('filing_no' => $generate_id[0]));
    $data_notice = get_data($db, $schema . '.notice_creation_details', array('filing_no' => $generate_id[0], 'id' => $generate_id[1]));
    $to_party_id = explode(',', $data_notice[0]['to_party_id']);
    //  $data_e_cases_party = get_data($dbo, 'public.e_cases_party', array('id' => $data_notice[0]['to_party_id']));



    if ($generate_id[2] == '121' && is_array($to_party_id) && !empty($to_party_id)) {

        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Commericial Court Application</title>

        </head>
        <body style="font-size:16px">

        <script>
            function change(id, newClass) {
                identity = document.getElementById(id);
                identity.className = newClass;

            }

            function printPage() {
                change("testdiv", "true");
                window.print();
            }
        </script>
        <div id="testdiv" class="pr-hide"><a href="javascript:printPage();">
                <font color="red" size="1">Print</font></a>
        </div>

        <?php foreach ($to_party_id as $party_value) {
            $data_e_cases_party = get_data($dbo, 'public.e_cases_party', array('id' => $party_value));
            ?>
            <p style="font-family:Arial, Helvetica, sans-serif; text-align:center; font-weight:bold; font-size:14px; line-height: 1.4;">

                SUMMONS TO APPEAR IN PERSON(O.5,R.3 CPC)<br>
                In the Court of the Judge commercial court<br>
                At <?php echo $schema; ?> </p>


            <p style="text-align:center; font-weight:bold;">Commercial Original
                Suit/00000<?php echo $data[0]['case_no']; ?>/<?php echo $data[0]['case_year']; ?></p>
            <p style="text-align:center; font-weight:bold;"><?php echo $data[0]['pet_name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
                V/S
                &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $data[0]['res_name']; ?>.</p>

            <p style="margin-bottom:0;">To</p>
            <p style="font-weight:bold; margin-left:50px; margin-top:10px;">
                <?php echo $data_e_cases_party[0]['name'] . '(' . $data_e_cases_party[0]['party_org_contact_person'] . ')' ?>
                <br>
                <?php echo $data_e_cases_party[0]['party_address1']; ?><br>
                <?php echo $data_e_cases_party[0]['pin']; ?>
            </p>

            <p style="text-indent:100px; margin-top:30px; margin-left:50px;">
                WHEREAS,<b><?php echo $data_notice[0]['whereas_description']; ?>,</b> has instituted a suit against you
                for (<b><?php echo $data_notice[0]['summoned_description']; ?></b>) you are hereby summoned to appear in
                this Court in person on the <b><?php echo $data_notice[0]['answer_clame_date']; ?>
                    ,</b> to answer the claim; and you are directed to produce on that day all the documents upon which
                you intend to rely in support of your defence. Take notice that, in default of your appearance on the
                day before mentioned, the suit will be heard and determined in your absence.</p>

            <p style="text-indent:50px; margin-top:30px;"> GIVEN under my hand the Seal of the Court this
                <b> <?php echo $data_notice[0]['seal_of_court_date']; ?> </b></p>

            <p style="text-align:right; margin-top:60px;">
                Judge</p>

            <div style="page-break-before: always;"></div>

        <?php } ?>


        </body>
        </html>
    <?php }


    function summon_formate($summon_type, $schemas, $data)
    {

        $case_type_name = '';
        $case_type_id = $data['case_type'];
        try {
         $query = "SELECT short_name FROM public.case_type where id = '$case_type_id'";
         $query_prepare = $dbo->prepare($query);
         $query_prepare->execute();
         $case_type_name = $query_prepare->fetchColumn();   
        } catch (PDOException $ex) {
         echo $ex;
         $case_type_name = '';
       }

        if ($summon_type == '1') {
            $such_answer_date = date('d-F-Y', strtotime($data['such_answer_date']));
            $date_explode = explode('-', $such_answer_date);
            $seal_of_court_date = date('d-F-Y', strtotime($data['seal_of_court_date']));
            $seal_of_court_date = explode('-', $seal_of_court_date);
            $msgg = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Summons</title>
        </head>
        <body style="font-size:16px; font-family: Arial, Helvetica, sans-serif;">
        <p><b>Form – 2</b></p>
        <p style="text-align:center; line-height: 1.4;">
            <span style="font-size: 20px;"><b>Summons for Settlement of Issues</b></span><br>
            (Order V Rules 3 & 5)
        </p>
        <p style="text-align:center; line-height: 1.6;">
            IN THE COURT OF <b> Trial and Disposal of Commercial Disputes </b><br>
            AT <br> <b>' . $schemas . '</b><br>
            '.$case_type_name.' NO <b>' . $data['case_no'] . '</b> OF <b> ' . $data['case_year'] . '</b>
        </p>
        <p>Between</p>
        <table style="border:none; padding:0; margin:0; border-spacing:0;">
            <tr>
                <td>
                    <p>' . $data['plaintif'] . '</p>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td style="padding-left:60px;">...plaintiff</td>
            </tr>
        </table>
        </p>
        <p style="text-align:center;">AND</p>
        <table style="border:none; padding:0; margin:0; border-spacing:0;">
            <tr>
                <td>
                    <p>' . $data['defendant'] . '</p>

                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td style="padding-left:60px;" align="center">...Defendant</td>
            </tr>
        </table>
        <p>To<br><b>' . $data['defendant'] . '</b><br>
        <b>' . $data['address'] . ',' . $data['pincode'] . '</b></p>
        <p style="text-align:justify;">
            Whereas the plaintiff has instituted as Suit against you for recover of money, you are hereby summoned to
            appear in this court in person or by a pleader duly instructed and able to answer all material questions
            relating to the Suit or who shall be accompanied by some person able to answer all such questions on
            the <b> ' . $date_explode['0'] . '</b> day of
            <b>' . $date_explode['1'] . ' ' . $date_explode['2'] . '</b> at 10-30 0’clocks in forenoon to
            answer the claim, and further you are directed to file with in 30 days of service of the summon a Written
            Statement of your defense and to produce on the said da all documents in your possession or power upon which
            you base your defense.
        </p>
        <p style="text-align:justify; text-indent:60px;">
            TAKE note that in default of appearance and to file your Written Statement within 30 days the suit will be
            heard and determined in your absence.
        </p>
        <div style="text-align:justify; text-indent:60px;">Given under my hand seal of the court this
            <b>' . $seal_of_court_date['0'] . ' </b> day of
            <b>' . $seal_of_court_date['1'] . ' ' . $seal_of_court_date['2'] . ' </b> .</p>
            <p>&nbsp;</p>
            <p style="text-align:right; font-size: 18px;"><b>Seal</b></p>
            <p>&nbsp;</p>
            <p>NOTICE:</p>
            <ol style="line-height: 1.6;">
                <li>Should you appeared your witness will not attend on their own accord you can have summons for this
                    court
                    to compel the attendance of any witness and production of any document that you have write to call
                    upon
                    the witness to produce on applying to the court and an depositing the necessary expenses.
                </li>
                <li>If you admit the claim you should pay the money into the court of the suit to avoid execution of the
                    decree, which may be against your person or property or both.
                </li>
            </ol>
        </div>
        </body>
        </html>';
            return $msgg;
        }
        else if ($summon_type == '2') {
            $seal_of_court_date = date('d-F-Y', strtotime($data['seal_of_court_date']));
            $seal_of_court_date = explode('-', $seal_of_court_date);
            $messahe_data = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Summons</title>
</head>
<body style="font-size:16px; font-family: Arial, Helvetica, sans-serif;">
<p style="text-align:center; margin-top: 60px;">Summons in a Summary Suit</p>
<p style="text-align:center;">
    IN THE COURT OF THE  <b> Trial and Disposal of Commercial Disputes </b><br>
    AT <b>' . $schemas . '</b>
</p>
<p style="text-align:center;">
    Order 37 Rule 2<br>
    '.$case_type_name.' No. <b> ' . $data['case_no'] . ' </b> Of  ' . $data['case_year'] . '  </p>
<p>&nbsp;</p>
<p>Between</p>
<p> ' . $data['plaintif'] . ' .......Plaintiff</p>
<p>AND</p>
<p>' . $data['defendant'] . ' .......Defendant</p>
  <p>To<br><b>' . $data['defendant'] . '</b><br>
        <b>' . $data['address'] . ',' . $data['pincode'] . '</b></p>
<p style="text-align:justify; margin-top: 30px;">
    Whereas  <b> ' . $data['whereas_description'] . ' </b> has instituted a suit against you under Order (37) of the Code of Civil Procedure, 1908
    for Rs <b> ' . $data['civil_procedure_amount'] . ' </b>  and interest you are hereby summoned to cause an appearance to be entered for you, within 10 days
    from the service hereof in default whereof the Plaintiff will be entitled after the expiration of the said period of
    10 days to obtain a decree for any sum not exceeding the sum of Rs <b> ' . $data['exceeding_amount'] . ' </b>  and the sum of Rs <b> ' . $data['cost_together_amount'] . ' </b>  for costs
    together with such interest, if any as the Court may order.
</p>
<p style="text-align:justify;">
    If you cause an appearance to be entered for you the plaintiff will thereafter serve upon you a summons for judgment
    at the hearing of which you will be entitled to move the Court for leave to defend the Suit. Leave to defend may be
    obtained if you satisfy the Court by affidavit or otherwise that there is any defence to the suit on merits or that
    it is reasonable you should be allowed to defend.
</p>
<p>&nbsp;</p>
<p>GIVEN under my hand the Seal of the Court this <b> ' . $seal_of_court_date[0] . ' </b> day of <b> ' . $seal_of_court_date[1] . ' ' . $seal_of_court_date[2] . ' </b></p>
<p style="text-align:right; font-size: 18px; margin-top:80px;"><b>Seal</b></p>
</body>
</html>';
            return $messahe_data;
        }
        else if ($summon_type == '3') {
            $seal_of_court_date = date('d-F-Y', strtotime($data['seal_of_court_date']));
            $seal_of_court_date = explode('-', $seal_of_court_date);

            $whereason_date = date('d-F-Y', strtotime($data['whereason_date']));
            $whereason_date = explode('-', $whereason_date);

            $appear_court_date = date('d-F-Y', strtotime($data['appear_court_date']));
            $appear_court_date = explode('-', $appear_court_date);

            $written_statement_date = date('d-F-Y', strtotime($data['written_statement_date']));
            $written_statement_date = explode('-', $written_statement_date);


            $messahe_data = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Summons</title>
</head>
<body style="font-size:16px; font-family: Arial, Helvetica, sans-serif;">
<p style="text-align:center; line-height: 1.4;">
    <span style="font-size: 24px;">In the Court of the <b> Trial and Disposal of Commercial Disputes </b>
</p>
<p style="text-align:center; line-height: 1.6;">AT <b>' . $schemas . '</b> </p>
<p style="text-align:center; line-height: 1.6;">  '.$case_type_name.' NO.  <b> ' . $data['case_no'] . ' </b> Of  ' . $data['case_year'] . '</p>

<p>&nbsp;</p>
<p>Between</p>
<p> ' . $data['plaintif'] . '    ...........Petitioner</p>
<p style="text-align:center;">AND</p>
<p>' . $data['defendant'] . '   ...........Respondent</p>
<p>To</p>
<p>To<br><b>' . $data['defendant'] . '</b><br>
        <b>' . $data['address'] . ',' . $data['pincode'] . '</b></p>
<p>&nbsp;</p>
<p style="text-align:justify;">
    <b>WHEREAS</b> on <b> ' . $whereason_date[0] . ' </b> day of <b> ' . $whereason_date[1] . ' ' . $whereason_date[2] . ' </b> the above name of the petitioner
    filed a petition against the Respondent of <b> ' . $data['petion_againts_desc'] . '</b> 
    You are hereby required to appear in the Court on the  <b> ' . $appear_court_date[0] . ' </b> day of <b> ' . $appear_court_date[1] . ' ' . $appear_court_date[2] . ' </b> at 10-30 a.m. the forenoon in person or by pleader duly instructs and able to answer all
    material question relating to the above proceeding.
</p>
<p style="text-align:justify; text-indent:60px;">
    <b>AND</b> also Notice that in default of your appearance on the aforesaid day the issues will be entitled and that
    petition heard and determined in your absence. You shall also bring with you or send by your pleader any document
    which the petitioner desires inspect any document on which your intend to rely in support of defence. You are
    required to file written statement in this court before the <b> ' . $written_statement_date[0] . ' </b> day of <b> ' . $written_statement_date[1] . ' ' . $written_statement_date[2] . ' </b>
</p>
<p>GIVEN under my hand and the Seal of the Court  <b> ' . $seal_of_court_date[0] . ' </b> day of <b> ' . $seal_of_court_date[1] . ' ' . $seal_of_court_date[2] . ' </b> </p>
<p>&nbsp;</p>
<p style="text-align:right; font-size: 18px;"><b>JUDGE</b></p>
</body>
</html>';
            return $messahe_data;

        }
    }

     echo summon_formate($_REQUEST['notice_type'], $_REQUEST['schema'], $_REQUEST['dataaa']);


} ?>
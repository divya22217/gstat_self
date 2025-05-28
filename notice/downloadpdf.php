<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
error_reporting(E_ALL);

$schema = $_SESSION['schema_name'];

include("../db_inc1.php");
include("../db_inc2.php");
function summon_formate($dbo, $party_ids, $summon_type, $schemas, $data)
{
    $query = "select * from e_cases_party where id in($party_ids)";
   try {
       $query_prepare = $dbo->prepare($query);
       $query_prepare->execute();
       $dataadddd = $query_prepare->fetchAll();
       $party_details = '';
       if (!empty($dataadddd)) {
           $sss = 1;
           foreach ($dataadddd as $valla) {
               $party_details .= $sss . '. ' . $valla['name'] . '<br>' . $valla['party_address1'] . ',' . $valla['pin'] . '<br><br>';
               $sss++;
           }
       }
   } catch (PDOException $ex) {
       echo $ex;
   }


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
        $such_answer_date = date('dS-F-Y', strtotime($data['such_answer_date']));
        $date_explode = explode('-', $such_answer_date);
        $seal_of_court_date = date('dS-F-Y', strtotime($data['seal_of_court_date']));
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
        <p>Form – 2</p>
        <p style="text-align:center; line-height: 1.4;">
            <span style="font-size: 20px;">Summons for Settlement of Issues</span><br>
            (Order V Rules 3 & 5)
        </p>
        <p style="text-align:center; line-height: 1.6;">
            IN THE COURT OF  Trial and Disposal of Commercial Disputes <br>
            AT <br> ' . ucwords($schemas) . '<br>
            '.$case_type_name.' NO ' . $data['case_no'] . ' OF  ' . $data['case_year'] . '
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
        <p>To<br>' . $party_details . ' </p>
        <p style="text-align:justify;">
            Whereas the plaintiff has instituted as Suit against you for recover of money, you are hereby summoned to
            appear in this court in person or by a pleader duly instructed and able to answer all material questions
            relating to the Suit or who shall be accompanied by some person able to answer all such questions on
            the  ' . ltrim($date_explode['0'], 0) . ' day of
            ' . $date_explode['1'] . ' ' . $date_explode['2'] . ' at 10-30 0’clocks in forenoon to
            answer the claim, and further you are directed to file with in 30 days of service of the summon a Written
            Statement of your defense and to produce on the said da all documents in your possession or power upon which
            you base your defense.
        </p>
        <p style="text-align:justify; text-indent:60px;">
            TAKE note that in default of appearance and to file your Written Statement within 30 days the suit will be
            heard and determined in your absence.
        </p>
        <div style="text-align:justify; text-indent:60px;">Given under my hand seal of the court this
            ' . ltrim($seal_of_court_date['0'], 0) . '  day of
            ' . $seal_of_court_date['1'] . ' ' . $seal_of_court_date['2'] . '  .</p>
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
    } else if ($summon_type == '2') {
        $seal_of_court_date = date('dS-F-Y', strtotime($data['seal_of_court_date']));
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
    IN THE COURT OF THE   Trial and Disposal of Commercial Disputes <br>
    AT ' . ucwords($schemas) . '
</p>
<p style="text-align:center;">
    Order 37 Rule 2<br>
    '.$case_type_name.' No. ' . $data['case_no'] . '  Of  ' . $data['case_year'] . '  </p>
<p>&nbsp;</p>
<p>Between</p>
<p> ' . $data['plaintif'] . ' .......Plaintiff</p>
<p>AND</p>
<p>' . $data['defendant'] . ' .......Defendant</p>
  <p>To<br> ' . $party_details . '  </p>
<p style="text-align:justify; margin-top: 30px;">
    Whereas  ' . $data['whereas_description'] . '  has instituted a suit against you under Order (37) of the Code of Civil Procedure, 1908
    for Rs  ' . $data['civil_procedure_amount'] . '   and interest you are hereby summoned to cause an appearance to be entered for you, within 10 days
    from the service hereof in default whereof the Plaintiff will be entitled after the expiration of the said period of
    10 days to obtain a decree for any sum not exceeding the sum of Rs  ' . $data['exceeding_amount'] . '   and the sum of Rs ' . $data['cost_together_amount'] . '   for costs
    together with such interest, if any as the Court may order.
</p>
<p style="text-align:justify;">
    If you cause an appearance to be entered for you the plaintiff will thereafter serve upon you a summons for judgment
    at the hearing of which you will be entitled to move the Court for leave to defend the Suit. Leave to defend may be
    obtained if you satisfy the Court by affidavit or otherwise that there is any defence to the suit on merits or that
    it is reasonable you should be allowed to defend.
</p>
<p>&nbsp;</p>
<p>GIVEN under my hand the Seal of the Court this ' . ltrim($seal_of_court_date[0], 0) . '  day of  ' . $seal_of_court_date[1] . ' ' . $seal_of_court_date[2] . ' </p>
<p style="text-align:right; font-size: 18px; margin-top:80px;"><b>Seal</b></p>
</body>
</html>';
        return $messahe_data;
    } else if ($summon_type == '3') {
        $seal_of_court_date = date('dS-F-Y', strtotime($data['seal_of_court_date']));
        $seal_of_court_date = explode('-', $seal_of_court_date);

        $whereason_date = date('dS-F-Y', strtotime($data['whereason_date']));
        $whereason_date = explode('-', $whereason_date);

        $appear_court_date = date('dS-F-Y', strtotime($data['appear_court_date']));
        $appear_court_date = explode('-', $appear_court_date);

        $written_statement_date = date('dS-F-Y', strtotime($data['written_statement_date']));
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
    <span style="font-size: 18px;">In the Court of the  Trial and Disposal of Commercial Disputes </span>
</p>
<p style="text-align:center; line-height: 1.6;">AT ' . ucwords($schemas) . ' <br> '.$case_type_name.' NO.   ' . $data['case_no'] . '  Of  ' . $data['case_year'] . '</p>
<p>Between</p>
<p> ' . $data['plaintif'] . '    ...........Petitioner</p>
<p style="text-align:center;">AND</p>
<p>' . $data['defendant'] . '   ...........Respondent</p>
<p>To<br>' . $party_details . ' </p>

<p style="text-align:justify;">
    <b>WHEREAS</b> on  ' . ltrim($whereason_date[0], 0) . '  day of  ' . $whereason_date[1] . ' ' . $whereason_date[2] . '  the above name of the petitioner
    filed a petition against the Respondent of  ' . $data['petion_againts_desc'] . ' 
    You are hereby required to appear in the Court on the  ' . ltrim($appear_court_date[0], 0) . '  day of  ' . $appear_court_date[1] . ' ' . $appear_court_date[2] . '  at 10-30 a.m. the forenoon in person or by pleader duly instructs and able to answer all
    material question relating to the above proceeding.
</p>
<p style="text-align:justify; text-indent:60px;">
    <b>AND</b> also Notice that in default of your appearance on the aforesaid day the issues will be entitled and that
    petition heard and determined in your absence. You shall also bring with you or send by your pleader any document
    which the petitioner desires inspect any document on which your intend to rely in support of defence. You are
    required to file written statement in this court before the  ' . ltrim($written_statement_date[0], 0) . '  day of ' . $written_statement_date[1] . ' ' . $written_statement_date[2] . ' 
</p>
<p>GIVEN under my hand and the Seal of the Court  ' . ltrim($seal_of_court_date[0], 0) . '  day of ' . $seal_of_court_date[1] . ' ' . $seal_of_court_date[2] . '  </p>
<p>&nbsp;</p>
<p style="text-align:right; font-size: 18px;"><b>JUDGE</b></p>
</body>
</html>';
        return $messahe_data;

    } else if ($summon_type == '4') {
        $such_answer_date = date('dS-F-Y', strtotime($data['appear_court_date']));
        $date_explode = explode('-', $such_answer_date);
        $seal_of_court_date = date('dS-F-Y', strtotime($data['seal_of_court_date']));
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
        <p>Form – 2</p>
        <p style="text-align:center; line-height: 1.4;">
            <span style="font-size: 20px;">NOTICE TO SHOW CAUSE WHY EXECUTION SHOULD NOT ISSUE</span><br>
            ((Order 21, Rule 22 &amp; 16 of the Code of Civil Procedure)
        </p>
        <p style="text-align:center; line-height: 1.6;">
            IN THE COURT OF  Trial and Disposal of Commercial Disputes <br>
            AT <br> ' . ucwords($schemas) . '<br>
            '.$case_type_name.' No. ' . $data['case_no'] . ' OF  ' . $data['case_year'] . '<br>
              Siut No. ' . $data['case_no'] . ' OF  ' . $data['case_year'] . '<br>
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
                <td style="padding-left:60px;">... Decree Holder</td>
            </tr>
        </table>
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
                <td style="padding-left:60px;" align="center">...Judgment Debtor</td>
            </tr>
        </table>
        <p>To<br>' . $party_details . ' </p>
         <p style="text-align:justify;">
           WHEREAS ' . $data['whereas_description'] . 'has made.
        </p>
        
        <p style="text-align:justify;">
        Application in this Court for Execution of Decree in Suit No.  ' . $data['case_no'] . ' OF  ' . $data['case_year'] . ' of( on
the allegation that the said Decree has been transferred to him by assignment or without assignment
this is to give Notice that you are to appear before this court on the day ' . ltrim($date_explode['0'], 0) . ' day of
            ' . $date_explode['1'] . ' ' . $date_explode['2'] . ' to show Case why Execution should not be granted.
        </p>
        
        <div style="text-align:justify; text-indent:60px;">Given under my hand seal of the court this
            ' . ltrim($seal_of_court_date['0'], 0) . '  day of
            ' . $seal_of_court_date['1'] . ' ' . $seal_of_court_date['2'] . '  .</p>
            <p>&nbsp;</p>
            <p style="text-align:right; font-size: 18px;"><b>SUPERINTEDENT</b></p>
            <p>&nbsp;</p>
           
        </div>
        </body>
        </html>';
        return $msgg;
    } else if ($summon_type == '5') {
        $such_answer_date = date('dS-F-Y', strtotime($data['appear_court_date']));
        $date_explode = explode('-', $such_answer_date);
        $seal_of_court_date = date('dS-F-Y', strtotime($data['seal_of_court_date']));
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
        <p>Form – 2</p>
        <p style="text-align:center; line-height: 1.4;">
            <span style="font-size: 20px;">Summons for Settlement of Issues</span><br>
            (Order V Rules 3 & 5)
        </p>
        <p style="text-align:center; line-height: 1.6;">
            IN THE COURT OF  Trial and Disposal of Commercial Disputes <br>
            AT <br> ' . ucwords($schemas) . '<br>
            '.$case_type_name.' NO ' . $data['case_no'] . ' OF  ' . $data['case_year'] . '
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
        <p>To<br>' . $party_details . ' </p>
        <p style="text-align:justify;">
            Whereas the ' . $data['whereas_description'] . ' has instituted as Suit against you for   ' . $data['recover_of_money'] . ' , you are hereby summoned to
            appear in this court in person or by a pleader duly instructed and able to answer all material questions
            relating to the Suit or who shall be accompanied by some person able to answer all such questions on
            the  ' . ltrim($date_explode['0'], 0) . ' day of
            ' . $date_explode['1'] . ' ' . $date_explode['2'] . ' at 10:30 O’clock. In the forenoon to answer the claim, and further you are hereby directed to file
within 30 days of Service of this Summon a written statement of your defence and to produce on the
said day all documents in your possession or power upon which you base your defence.
        </p>
        <p style="text-align:justify; text-indent:60px;">
            TAKE notice that in default of your appearance and to file your written statement within 30 days the suit
will be heard and determined in your absence
        </p>
        <div style="text-align:justify; text-indent:60px;">Given under my hand seal of the court this
            ' . ltrim($seal_of_court_date['0'], 0) . '  day of
            ' . $seal_of_court_date['1'] . ' ' . $seal_of_court_date['2'] . '  .</p>
            <p>&nbsp;</p>
            <p style="text-align:right; font-size: 18px;"><b>JUDGE</b></p>
            <p>&nbsp;</p>
            <p>NOTICE:</p>
            <ol style="line-height: 1.6;">
                <li>Should you appeared your witness will not attend on their own accord you can have
summons from this court to compel the attendance of any witness and production of any document
that you have aright to call necessary expenses.
                </li>
                <li>If you admit the claim you should pay the money into court together with cost of the suit to avoid
execution of the decree, which may ne against your person or property or both.
                </li>
            </ol>
        </div>
        </body>
        </html>';
        return $msgg.$party_details;
    }
}


$html_pdf = summon_formate($dbo, $_REQUEST['party_ids'], $_REQUEST['notice_type'], $_REQUEST['schema'], $_REQUEST['dataaa']);
$file_pointer = $_SERVER['DOCUMENT_ROOT'] . '/commercialcourttelengana/notice/summon/' . date('Y');
if (!is_dir($file_pointer)) {
    mkdir($file_pointer, 0777);
}
require('TCPDF/tcpdf.php');
$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set default monospaced font
$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$tcpdf->SetMargins(10, 10, 10, 10);
$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$tcpdf->setPrintHeader(false);
$tcpdf->setPrintFooter(false);
$tcpdf->setListIndentWidth(3);
$tcpdf->AddPage();
$tcpdf->writeHTML($html_pdf, true, false, false, false, '');
$file_name = $_REQUEST['file_name'];
$file_name = $file_pointer . '/' . $file_name . '.pdf';
$tcpdf->Output($file_name, 'F');

?>
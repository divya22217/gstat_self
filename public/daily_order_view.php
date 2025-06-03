<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php"; //database connection

if (empty($_SESSION['user']) and $_SESSION['location'] == '') {

    die("Redirecting to login.php");
}
if (($_SESSION['user']) == '') {
    echo "you Can't access this page";
} else {
    $hash = $_REQUEST['filing_no'];
    $hash = htmlspecialchars(base64_decode($hash));
    list($item_no, $filing_no, $schemas) = explode("/", $hash);
    if ($item_no != '') {
        $date = htmlspecialchars(date("d/m/Y"));
        $date1 = htmlspecialchars(date("F j, Y g:i a"));
        $msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] : '';
        $year = htmlspecialchars(date("Y"));
        $month = htmlspecialchars(date("m"));
        $days = htmlspecialchars(date("d"));
        $tdate = "$date/$month/$year";
        $msg_ip .= "User IP : " . $_SERVER["REMOTE_ADDR"] . "\r\n"; //Sender's IP
        ?>



<div class="tiledBackground">
    <?php
$party_type1 = 'R';
        $get_order = $db->prepare("select a.order_date,a.filing_no,order_html,b.case_no,b.case_type,b.case_year,c.short_name from $schemas.order_daily as a inner join $schemas.case_detail as b on a.filing_no = b.filing_no inner join public.case_type as c on c.id = b.case_type where item_no = ? ");
        $get_order->bindParam(1, $item_no, PDO::PARAM_STR);
        $get_order->execute();
        $pdf_html = $get_order->fetch();
        $order_date = date('d_m_Y', strtotime($pdf_html['order_date']));
        $filename12 = $pdf_html['short_name'] . '_' . $pdf_html['case_no'] . '_' . $pdf_html['case_year'] . '_' . $order_date;
        $filename13 = str_replace(' ', '_', $filename12);
        $filename = str_replace('.', '', $filename13) . '.doc';
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=" . basename($filename));
        header("Content-Description: File Transfer");
        @readfile($filename);
        echo $pdf_html['order_html'];
        ?>

    </body>

    </html>

    <?php }?>
    <?php }?>
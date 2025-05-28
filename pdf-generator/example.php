<?php
require('mc_table.php');
include("../includes/include.inc.php");



$pdf=new PDF_MC_Table();
$pdf->AddPage();
$pdf->SetFont('Arial','',14);
//Table with 20 rows and 4 columns
$pdf->SetWidths(array(80,50,30,0));
srand(microtime()*1000000);
$result11=mysql_query("select * from gws_invoice_details where invoice_id='201503041' order by id asc ");
while($row = mysql_fetch_array($result11))
{
    $pdf->Row(array($row[description],$row[qty],$row[unit_price],"KASllo"));
	}
$pdf->Output();
?>
	<?php
	//print_r($_REQUEST);die;
	require_once('../SrcCauselist/Causelist.php');
	require_once("../db_inc1.php");
	require_once("../db_inc2.php");
	require_once('../fpdf/fpdf.php');
	require_once('../pdf_generator.php');
	if(isset($_REQUEST['checkbox'])){
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	$list_cdate = $_REQUEST['next_list_date'];
	list($d,$m,$Y) =explode('/',$list_cdate);
    $list_cdate =$Y.'-'.$m.'-'.$d;
	$court_no = $_REQUEST['court_no'];
 	//print_r($_REQUEST['checkbox']);
 	$checkbox=$_REQUEST['checkbox'];
 	$l=sizeof($checkbox);
 	//echo $l;
	$countpdf = 0;
	$causelist = new Causelist();
 	 for($i=0;$i<$l;$i++)
		{
	   $filing_no_fin=$checkbox[$i];
	   $listing_date_fin=$list_cdate;
	   $newst7 ="update $schemas.order_daily set flag ='F' where filing_no='$filing_no_fin' and order_date='$listing_date_fin'";
	   $db->query($newst7) or die("case no not updated");
	   //------------- generate order -----------------------//
if($schemas=='delhi') 
	{
	$bench_name="New Delhi";	
	}
$filing_no = $filing_no_fin;
//$case_type = '16';
$list_date_db = $listing_date_fin;
$courtnoc= $court_no;
//$bench_noc = '2';
$table = 'case_allocation';

//get case type
$get_casetype =$db->prepare("select case_type from $schemas.case_detail where filing_no =?");
$get_casetype->bindParam(1, $filing_no, PDO::PARAM_STR);
$get_casetype->execute();
$case_type = $get_casetype->fetchColumn();

//get order detail
//$get_orderdetail_sql="select * from $schemas.order_daily where filing_no='$filing_no' and order_date='$list_date_db'";
$get_orderdetail_sql="select * from $schemas.order_daily where filing_no=? and order_date=?";
      $get_orderdetail = $db->prepare($get_orderdetail_sql);
      $get_orderdetail->bindParam(1,$filing_no, PDO::PARAM_STR);
	  $get_orderdetail->bindParam(2,$list_date_db, PDO::PARAM_STR);
      $get_orderdetail->execute();
      while ($god = $get_orderdetail->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		 {
			$bench_noc=$god['bench_no'];
	        $item_no=$god['item_no'];		
		 }
		 //echo $bench_noc;die;
 
/* start code to get main case no of IA */
 /*$get_ia_main_filing_no_sql = "select main_case_ia_no from $schemas.case_detail where filing_no=? and ia_flag='TRUE'";
 $get_ia_main_filing_no = $db->prepare($get_ia_main_filing_no_sql);
 $get_ia_main_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
 $get_ia_main_filing_no->execute();
 $ia_main_filingno = '';
 while ($row_gimfn = $get_ia_main_filing_no->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
	 $ia_main_filingno=$row_gimfn['main_case_ia_no'];
 }
 if($ia_main_filingno!=''){
     $ia_main_case_no = $causelist->nclt_case_no($ia_main_filingno, $db, $schemas);
 }*/
/* stop code to get main case no of IA */

/* start code to get all IA of main case */
 /*$ia_filingno_case_no = array();
 $get_all_ia_sql = "select filing_no from $schemas.case_detail where main_case_ia_no=? and ia_flag='TRUE'";
 $get_all_ia = $db->prepare($get_all_ia_sql);
 $get_all_ia->bindParam(1, $filing_no, PDO::PARAM_STR);
 $get_all_ia->execute();
 $ia_filingno = '';
 while ($row_gai = $get_all_ia->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
	  $ia_filingno=$row_gai['filing_no'];

	 if($ia_filingno!=''){
		$ia_filingno_case_no[] = $causelist->nclt_case_no($ia_filingno, $db, $schemas);
		 }
 }*/

/* stop code to get mall IA of main case */

/*start of code to get in_rst_cases and main case of CA*/
$in_case_nor=array();
 
$get_in_filing_no_sql = "select in_filingno from e_case_detail where filing_no=?";
$get_in_filing_no = $dbonline->prepare($get_in_filing_no_sql);
$get_in_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
$get_in_filing_no->execute();
$in_filingno = '';
while ($row_gifn = $get_in_filing_no->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $in_filingno=$row_gifn['in_filingno'];
  if($in_filingno!=''){
  $get_incase_type = $causelist->get_incase_type($in_filingno,$db, $schemas);
  $get_incase = $causelist->nclt_case_no($in_filingno,$db, $schemas);
  array_push($in_case_nor, array("case_no" => $get_incase, "fil_no" => $in_filingno, "incase_type" => $get_incase_type));
  //print_r($in_case_no);
}
}
/*stop of code to get in_rst_cases and main case of CA*/ 

/*start of code to get all CA of CP*/
/*$in_case_nocp=array();
 
$get_in_filing_no_sql = "select filing_no from e_case_detail where in_filingno=?";
$get_in_filing_no = $dbonline->prepare($get_in_filing_no_sql);
$get_in_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
$get_in_filing_no->execute();
$in_filingno = '';
while ($row_gifn = $get_in_filing_no->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $in_filingno=$row_gifn['filing_no'];
  if($in_filingno!=''){
  $get_incase_type = $causelist->get_incase_type($in_filingno,$db, $schemas);
  $get_incase = $causelist->nclt_case_no($in_filingno,$db, $schemas);
  array_push($in_case_nocp, array("case_no" => $get_incase, "fil_no" => $in_filingno, "incase_type" => $get_incase_type));
  //print_r($in_case_no);
}
}*/
/*stop of code to get all CA of CP*/ 

/*start of code to get all Connected Cases*/
/*$connected_cases=array();
 
$get_conn_cases_sql = "select conn_filing_no from $schemas.connected_cases where filing_no=?";
$get_conn_cases = $db->prepare($get_conn_cases_sql);
$get_conn_cases->bindParam(1, $filing_no, PDO::PARAM_STR);
$get_conn_cases->execute();
$conn_filing_no = '';
while ($row_gcc = $get_conn_cases->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $conn_filing_no=$row_gcc['conn_filing_no'];
  if($conn_filing_no!=''){
  $get_incase_type = $causelist->get_incase_type($conn_filing_no,$db, $schemas);
  $get_incase = $causelist->nclt_case_no($conn_filing_no,$db, $schemas);
  array_push($connected_cases, array("case_no" => $get_incase, "fil_no" => $conn_filing_no, "incase_type" => $get_incase_type));
}
}*/
//print("<pre>".print_r($connected_cases,true)."</pre>");

/*end of code to get all Connected Cases*/
// Instanciation of inherited class
$pdf = new PDF();
$pdf->setbench_name($bench_name);
$sectionc = $causelist->getSections($filing_no, $dbonline);	
$pdf->setsection($sectionc);
$case_nom = $causelist->nclt_case_no($filing_no, $db, $schemas);
$pdf->setcase_no($case_nom);
$pdf->setcourt_no($courtnoc);
$pdf->setitem_no($item_no);

if(!empty($in_case_nor)){
	$len_array = count($in_case_nor);
	for($i=0;$i<$len_array;$i++) {
	$petnamec = $causelist->getPetname($in_case_nor[$i]['fil_no'], $schemas, $db, $dbonline); 
	$resnamec = $causelist->getResname($in_case_nor[$i]['fil_no'], $schemas, $db, $dbonline);
	}	 
}
else {
	$petnamec = $causelist->getPetname($filing_no, $schemas, $db, $dbonline); 
	$resnamec = $causelist->getResname($filing_no, $schemas, $db, $dbonline); 
}
//$dit = 'And';

$petpartyc = '';
if($case_type == 14 || $case_type == 15){
	$petpartyc = $causelist->getPetParty($filing_no, $dbonline);
}
//$pdf->setpetname($petnamec);
//$pdf->setresname($resnamec);

$pjudgenamenxt = $causelist->getpJudges($schemas, $db, $list_date_db, $bench_noc, $courtnoc, $table);
$presiding = $pjudgenamenxt[0]['presiding'];
$presiding_name = $pjudgenamenxt[0]['pjudgename'];
//$presiding_name = ltrim($presiding_name, $presiding_name[0]);
//$pdf->setpname($presiding_name);

$judgesnamenxt = $causelist->getallJudges($schemas, $db, $list_date_db, $bench_noc, $courtnoc, $presiding, $table);

//get advocates
    $advocates=array();
	//echo $t = "select * from $schemas.order_daily_advocate where filing_no='$filing_no' and order_date='$list_date_db' ";
	$stng1 = $db->prepare("select * from $schemas.order_daily_advocate where filing_no=? and order_date=? ");
	$stng1->bindParam(1, $filing_no, PDO::PARAM_INT);
	$stng1->bindParam(2, $list_date_db, PDO::PARAM_INT);
	$stng1->execute();
	while ($rw21 = $stng1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$advocate = htmlspecialchars($rw21['advocate']);
		$advocate_type = htmlspecialchars($rw21['advocate_type']);
        if($advocate && $advocate_type)		
		array_push($advocates, array("atype" => $advocate_type, "aname" => $advocate));
	}
	//print_r($advocates);die;

//get from order daily
$get_daily_order_sql = "select * from $schemas.order_daily where filing_no=?";
$get_daily_order = $db->prepare($get_daily_order_sql);
$get_daily_order->bindParam(1, $filing_no, PDO::PARAM_STR);
$get_daily_order->execute();
while ($row_gdo = $get_daily_order->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	 $order_tribunal = $row_gdo['order_tribunal'];
}
//$pdf->setorderbody($order_tribunal);

    $subheading = 'In the matter of:';
    $pdf->AddPage();
	$pdf->SetFont('Arial','BU',15);
	$pdf->Cell(0,10,$subheading,0,1,'C');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',15);
	$pdf->Cell(30,10,$petnamec,0,0);
	$pdf->Cell(0,10,'...Petitioner',0,0,'R');
	$pdf->Ln(8);
	$pdf->Cell(30);
	$pdf->Cell(30,10,'Vs',0,0);
	$pdf->Ln(8);
	$pdf->Cell(30,10,$resnamec,0,0);
	$pdf->Cell(0,10,'...Respondent',0,0,'R');
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(0,10,'Coram:',0,1);
	$pdf->Ln(0);
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(0,10,$presiding_name,0,1);
	$pdf->Ln(1);
	foreach($judgesnamenxt as $i => $item) {
	$pdf->Cell(0,10,$judgesnamenxt[$i]['alljudgesname'],0,1);
	$pdf->Ln(1);
	}
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(0,10,'For Petitioner(s):',0,1);
	$pdf->Ln(1);
	$pdf->SetFont('Arial','',12);
	foreach($advocates as $i => $item) {
		if($advocates[$i]['atype'] == 'P')
	$pdf->Cell(0,10,$advocates[$i]['aname'],0,1);
	$pdf->Ln(1);
	}
	$pdf->Ln(1);
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(0,10,'For Respondent(s):',0,1);
	$pdf->Ln(1);
	$pdf->SetFont('Arial','',12);
		foreach($advocates as $i => $item) {
		if($advocates[$i]['atype'] == 'R')
	$pdf->Cell(0,10,$advocates[$i]['aname'],0,1);
	$pdf->Ln(1);
	}
	$pdf->SetFont('Arial','BU',15);
	$pdf->Cell(0,10,'Order',0,1,'C');
	$pdf->Ln(1);
	
	//$pdf=new PDF_HTML();
	//$pdf->AddPage();
    $pdf->SetFont('Arial','',15);
    $pdf->WriteHTML(iconv('UTF-8', 'windows-1252', html_entity_decode($order_tribunal)));
	$pdf->Ln(30);
	$pdf->SetFont('Arial','B',8);
	foreach($judgesnamenxt as $i => $item) {
	$pdf->Cell(0,10,$judgesnamenxt[$i]['alljudgesname'],0,0);
	//$pdf->Ln(1);
	}
	$pdf->Cell(0,10,$presiding_name,0,0,'R');
	$pdf->Ln(20);

	//$pdf->AliasNbPages();
	
    $pdf->AliasNbPages();
    //$pdf->AddPage();
    //$pdf->SetFont('Times','',12);
    //for($i=1;$i<=40;$i++)
    //$pdf->Cell(0,10,'Printing line number '.$i,0,1);
    $filename = 'order_for_'.$filing_no.'.pdf';
	$filepath=$_SERVER['DOCUMENT_ROOT']."/nclt/order/".$filename;
	$filepathdb = "/order/".$filename;
	
	//insert path pdf
	//echo $r = "update $schemas.order_daily set path='$filepath' where filing_no='$filing_no' and order_date='$list_date_db'";die;
	$ins_path=$db-> prepare("update $schemas.order_daily set path='$filepathdb' where filing_no='$filing_no' and order_date='$list_date_db'");
	$ins_path->execute();
	
	$pdf->Output($filepath,'F');
	
	//$pdf2->Output();
    unset($var); 

		}
	$message = 'Successfuly done';	
	$msg1=htmlspecialchars($message);
	$hash=base64_encode($msg1);
	header("Location:daily_order_report.php?hash=$hash");	
	//echo "<tr><td colspan='7'><center><font color='red' size='2'>Successfully Finalised.</font></td></center></tr>";
	exit();
}
?>
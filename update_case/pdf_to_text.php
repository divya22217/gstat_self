<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  
include("../db_inc1.php");
include("../db_inc2.php");
include '../pdftotext/autoload.php';
$parser = new \Smalot\PdfParser\Parser();
date_default_timezone_set("Asia/Kolkata");
$schema='delhi';

function get_order_text($parser,$path){
		
		try{	
		$order_pdf  = $parser->parseFile($path);
		$order_text = $order_pdf->getText();
		}
		catch(Exception $e){
			$order_text = 'NA';
		}finally{
			if($order_text == ''){
				$order_text = 'NA';
			}
		}
		return $order_text;
	}
ini_set('max_execution_time', 3000);
/* try{
				  
$db->beginTransaction(); */  // begin transaction
	
$orders = $db->prepare("select item_no,filing_no,pdf_path,order_date from $schema.order_daily order by item_no");
$orders->execute();
$orders = $orders->fetchAll();

if(!empty($orders)){
	foreach($orders as $k=>$order){
		$path = $order['pdf_path'];
		//$path = '..'.$path;
		$filing_no = $order['filing_no'];
		$item_no = $order['item_no'];
		$order_text = html_entity_decode(get_order_text($parser,$path));
		echo $order_text;
		echo "<br/>";
		echo $k.'    '.$filing_no.'      '.$item_no.'       '.$path.'<br/>';
		try{
		$order_update = $db->prepare("update $schema.order_daily set order_html = ? where filing_no = ? and item_no = ?");
		$order_update->bindParam(1, $order_text, PDO::PARAM_STR);
		$order_update->bindParam(2, $filing_no, PDO::PARAM_STR);
		$order_update->bindParam(3, $item_no, PDO::PARAM_STR);
		$res = $order_update->execute();
		}catch (PDOException $ex) {
            echo $ex;
        }
		echo $res."<br/><br/><br/>";
	}
}
/* $db->commit();
}catch(Excetion $e){
	$db->rollBack();
	echo 'Message: '.$e->getMessage();
} */

?>
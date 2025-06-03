<?php 
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  
include("../db_inc1.php");
include("../db_inc2.php");

$filing_no = $_POST['filing_no'];
$next_listing_date = $_POST['next_listing_date'];
$listing_date = $_POST['listing_date'];
$next_list_purpose = $_POST['next_list_purpose'];
list($d,$m,$y) = explode('/',$next_listing_date);
$next_listing_date = $y.'-'.$m.'-'.$d;
$schemas=htmlspecialchars($_SESSION['schema_name']);
$sessionUserType=htmlspecialchars($_SESSION['id']);
$curdate=date('Y-m-d');


if($sessionUserType){
	try{
		$db->beginTransaction();  // begin transaction
		$st="insert into $schemas.case_allocation_his_temp (select * from $schemas.case_allocation_temp where filing_no=?)";
		$st=$db->prepare($st);
		$st->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st->execute();

		$recused_list_purpose = 28;
		$st1="insert into $schemas.case_proceeding_his (select * from $schemas.case_proceeding where listing_date = ? and next_list_purpose = ? and filing_no = ? order by listing_date desc limit 1
		)";
		$st1=$db->prepare($st1);
		$st1->bindParam(1, $listing_date, PDO::PARAM_STR);
		$st1->bindParam(2, $recused_list_purpose, PDO::PARAM_STR);
		$st1->bindParam(3, $filing_no, PDO::PARAM_STR);
		$st1->execute();

		$listed='0';

		 $st="update $schemas.case_allocation_temp set next_list_date=?,listed=?,purpose=? where filing_no=?";

		$st=$db->prepare($st);
		$st->bindParam(1, $next_listing_date, PDO::PARAM_STR);
		$st->bindParam(2, $listed, PDO::PARAM_STR);
		$st->bindParam(3, $next_list_purpose, PDO::PARAM_STR);
		$st->bindParam(4, $filing_no, PDO::PARAM_STR);
		$st->execute();

		$update_proceeding="update $schemas.case_proceeding set next_list_date=? , next_list_purpose =? where listing_date=? and next_list_purpose = ? and filing_no = ?";
		$update_proceeding=$db->prepare($update_proceeding);
		$update_proceeding->bindParam(1, $next_listing_date, PDO::PARAM_STR);
		$update_proceeding->bindParam(2, $next_list_purpose, PDO::PARAM_STR);
		$update_proceeding->bindParam(3, $listing_date, PDO::PARAM_STR);
		$update_proceeding->bindParam(4, $recused_list_purpose, PDO::PARAM_STR);
		$update_proceeding->bindParam(5, $filing_no, PDO::PARAM_STR);
		$res_p = $update_proceeding->execute();

		
		$db->commit();
		echo $res_p;
	}catch(Exception $e){
				$db->rollBack();
				$response = array( 
				'status' => 0, 
				'message' => 'some error occurred.' 
				);
			}
}
?>
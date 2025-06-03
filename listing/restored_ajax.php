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
$restored_id = $_POST['restored_id'];
list($d,$m,$y) = explode('/',$next_listing_date);
$next_listing_date = $y.'-'.$m.'-'.$d;
$schemas=htmlspecialchars($_SESSION['schema_name']);
$sessionUserType=htmlspecialchars($_SESSION['id']);
$username = $_SESSION['actual_username'];
$curdate=date('Y-m-d');
$ip=htmlspecialchars($_SERVER["REMOTE_ADDR"]);


if($sessionUserType){
	try{
		
		$db->beginTransaction();  // begin transaction
		$st="insert into $schemas.case_allocation_his_temp (select * from $schemas.case_allocation_temp where filing_no=?)";
		$st=$db->prepare($st);
		$st->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st->execute();

		$st1="insert into $schemas.case_proceeding_his (select * from $schemas.case_proceeding where listing_date = ? and filing_no = ?)";
		$st1=$db->prepare($st1);
		$st1->bindParam(1, $listing_date, PDO::PARAM_STR);
		$st1->bindParam(2, $filing_no, PDO::PARAM_STR);
		$st1->execute();

		$listed='0';

		 $st="update $schemas.case_allocation_temp set next_list_date=?,listed=?,purpose=? where filing_no=?";

		$st=$db->prepare($st);
		$st->bindParam(1, $next_listing_date, PDO::PARAM_STR);
		$st->bindParam(2, $listed, PDO::PARAM_STR);
		$st->bindParam(3, $next_list_purpose, PDO::PARAM_STR);
		$st->bindParam(4, $filing_no, PDO::PARAM_STR);
		$st->execute();

		$update_proceeding="update $schemas.case_proceeding set next_list_date=? , next_list_purpose =? where listing_date=? and filing_no = ?";
		$update_proceeding=$db->prepare($update_proceeding);
		$update_proceeding->bindParam(1, $next_listing_date, PDO::PARAM_STR);
		$update_proceeding->bindParam(2, $next_list_purpose, PDO::PARAM_STR);
		$update_proceeding->bindParam(3, $listing_date, PDO::PARAM_STR);
		$update_proceeding->bindParam(4, $filing_no, PDO::PARAM_STR);
		$res_p = $update_proceeding->execute();
		
		$is_restored = 1;
		$update_notified="update $schemas.restored_cases set is_restored=? , updated_at =now(),updated_by = ?,
							updated_by_username = ?, updated_by_ip = ? where id=? and filing_no = ?";
		$update_notified=$db->prepare($update_notified);
		$update_notified->bindParam(1, $is_restored, PDO::PARAM_STR);
		$update_notified->bindParam(2, $sessionUserType, PDO::PARAM_STR);
		$update_notified->bindParam(3, $username, PDO::PARAM_STR);
		$update_notified->bindParam(4, $ip, PDO::PARAM_STR);
		$update_notified->bindParam(5, $restored_id, PDO::PARAM_INT);
		$update_notified->bindParam(6, $filing_no, PDO::PARAM_INT);
		$res_p = $update_notified->execute();

		
		$db->commit();
		echo $res_p;
	}catch(Exception $e){
		echo 'Message: ' .$e->getMessage();
				$db->rollBack();
				$response = array( 
				'status' => 0, 
				'message' => 'some error occurred.' 
				);
			}
}
?>

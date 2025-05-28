<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
	include("../db_inc1.php");
	date_default_timezone_set("Asia/Kolkata");
	$curdate=date('Y-m-d h:i:s');
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	$user_id=htmlspecialchars($_SESSION['id']);
	$data = $_POST;
	$type = htmlspecialchars($data['type_remark']);
	$listing_date = htmlspecialchars($data['listing_date_remark']);
	$court_no = htmlspecialchars($data['court_no_remark']);
	$remark = htmlspecialchars($data['cause_list_remark']);
	$remark_footer = htmlspecialchars($data['cause_list_remark2']);
	$list_flag = htmlspecialchars($data['list_flag_remark']);
	if($type == 'add'){
		$st="insert into $schemas.causelist_remark
		(listing_date,court_no,list_flag,remark,remark_footer,user_id)
		VALUES
		(?,?,?,?,?,?)";
		$st=$db->prepare($st);
		$st->bindParam(1, $listing_date, PDO::PARAM_STR);
		$st->bindParam(2, $court_no, PDO::PARAM_STR);
		$st->bindParam(3, $list_flag, PDO::PARAM_STR);
		$st->bindParam(4, $remark, PDO::PARAM_INT);
		$st->bindParam(5, $remark_footer, PDO::PARAM_STR);
		$st->bindParam(6, $user_id, PDO::PARAM_INT);
		$st->execute();
		die;
	}
	if($type == 'update'){
		$st="insert into $schemas.causelist_remark_his (select * from $schemas.causelist_remark where listing_date = ? and court_no = ? and list_flag = ?)";
		$st=$db->prepare($st);
		$st->bindParam(1, $listing_date, PDO::PARAM_STR);
		$st->bindParam(2, $court_no, PDO::PARAM_STR);
		$st->bindParam(3, $list_flag, PDO::PARAM_STR);
		$st->execute();
		
		$st="update $schemas.causelist_remark set remark = ? ,remark_footer = ?, updated_by = ? , updated_at = ? where listing_date = ? and court_no = ? and list_flag = ?";
		$st=$db->prepare($st);
		$st->bindParam(1, $remark, PDO::PARAM_STR);
		$st->bindParam(2, $remark_footer, PDO::PARAM_STR);
		$st->bindParam(3, $user_id, PDO::PARAM_INT);
		$st->bindParam(4, $curdate, PDO::PARAM_INT);
		$st->bindParam(5, $listing_date, PDO::PARAM_STR);
		$st->bindParam(6, $court_no, PDO::PARAM_STR);
		$st->bindParam(7, $list_flag, PDO::PARAM_STR);
		$st->execute();
		
		die;
	}
 ?>
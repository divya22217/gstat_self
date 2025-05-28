<?php
session_start();
ob_start();
include("../db_inc1.php");
$schemas=htmlspecialchars($_SESSION['schema_name']);
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 
$next_list_date=$_REQUEST['next_list_date'];
list($d,$m,$Y) =explode('/',$next_list_date);
 $list_date =$Y.'-'.$m.'-'.$d;
 $listt_date=$_REQUEST['lis_date'];
 //$list_flag=$_REQUEST['b_type'];
 
$sessionUserType=htmlspecialchars($_SESSION['id']);
 $bench_no=$_REQUEST['bench_no'];
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */

  $checkbox=$_REQUEST['checkbox'];

  //list($filing_no,$max_list_date)=explode("/",$checkbox[0]);
  //print_r($max_list_date);die();

 $purpose_code=$_REQUEST['purpose_id'];


  $sql2="select *  from $schemas.bench where from_list_date='$listt_date' and bench_no='$bench_no' ";

foreach($db->query($sql2) as $row2)
	{	
	   $court_no =$row2['court_no'];
	  $bench_nature =$row2['bench_nature'];
	  $list_flag = $row2['list_flag'];
	}
 
 
 
	 $sql="select max(priority_serial) as priority_serial from $schemas.case_allocation_temp where listing_date='$listt_date' ";
foreach($db->query($sql) as $row)
	{	
	  $priority_serial1 =$row['priority_serial'];
	}
	 
	if($priority_serial1=='' || $priority_serial1==0)
	{
		$priority_serial1=1;
	}
	else
	{
		$priority_serial1=$priority_serial1+1;
	}
	
	$recused_filing_no = '';
	$is_found_recused = 0;
	$bench_judges="select string_agg(cast(judge_code as varchar),',') as judge_codes from $schemas.bench_judge where from_list_date = ? and bench_no = ?";
	$bench_judges = $db->prepare($bench_judges);
	$bench_judges->bindParam(1, $listt_date, PDO::PARAM_STR);
	$bench_judges->bindParam(2, $bench_no, PDO::PARAM_STR);
	$bench_judges->execute();
	$coram = $bench_judges->fetchColumn();
	$coram_array = explode(',',$coram);


 $l=sizeof($checkbox);
  $total_recused = 0;
 for($i=0;$i<$l;$i++)
 {
	  //$purpose1=$purpose_code[$i];
	  $filing_no=$checkbox[$i];
	  list($filing_no,$max_list_date) = explode("/",$filing_no);
	$purpose1=$purpose_code[$filing_no];
	
	
	$is_deleted = 0;
	$recused_judges="select judge_code from $schemas.recused_case_judge where filing_no = ? and is_deleted= ?";
	$recused_judges = $db->prepare($recused_judges);
	$recused_judges->bindParam(1, $filing_no, PDO::PARAM_STR);
	$recused_judges->bindParam(2, $is_deleted, PDO::PARAM_STR);
	$recused_judges->execute();
	$recused_judges = $recused_judges->fetchAll();
	
	/* echo "<pre>"; print_r($coram_array);
	echo "<pre>"; print_r($recused_judges);
	die; */
	
	if(!empty($recused_judges)){
		$find_in_recused = 0;
		foreach($recused_judges as $key=>$recuse_judge){
			if (in_array($recuse_judge['judge_code'], $coram_array))
			{
			  $is_found_recused = 1;
			  $total_recused = $total_recused+1;
			  $find_in_recused = 1;	
			  if($recused_filing_no != ''){
			  $recused_filing_no .= ','.$filing_no;
			  }else{
				$recused_filing_no .= $filing_no;
			  }
			  break;
			}
		}
		if($find_in_recused){
			continue;
		}
	}
	
	try{
	 $sql_pr1="insert into $schemas.case_allocation_his_temp select * from $schemas.case_allocation_temp where next_list_date='$listt_date' and filing_no='$filing_no'";
    $sth1=$db->prepare($sql_pr1);
	 $sth1->execute();
	 
	 $l_w_d = 1;
	$list_with_defect_query="select list_with_defect from $schemas.case_detail where filing_no = ? and list_with_defect = ? and regis_date is null";
	$list_with_defect_data = $db->prepare($list_with_defect_query);
	$list_with_defect_data->bindParam(1, $filing_no, PDO::PARAM_STR);
	$list_with_defect_data->bindParam(2, $l_w_d, PDO::PARAM_STR);
	$list_with_defect_data->execute();
	$list_with_defect_data = $list_with_defect_data->fetchColumn();
	
	if(!empty($list_with_defect_data)){
		$list_with_defect = 1;
	}else{
		$list_with_defect = 0;
	}
	
	 $listed = 1;
	$case_remark = $_POST['case_remark'][$filing_no];
	 $st3="update  $schemas.case_allocation_temp set bench_no=?,court_no=?,listed=?,listing_date =?,bench_nature=?,purpose=?, remarks = ?, priority_serial = ?, list_flag = ?, list_with_defect = ? where  filing_no=? and next_list_date=?";
	 $sth1=$db->prepare($st3);
	 $sth1->bindParam(1, $bench_no, PDO::PARAM_STR);
	 $sth1->bindParam(2, $court_no, PDO::PARAM_STR);
	 $sth1->bindParam(3, $listed, PDO::PARAM_STR);
	 $sth1->bindParam(4, $listt_date, PDO::PARAM_STR); 
	 $sth1->bindParam(5, $bench_nature, PDO::PARAM_STR);
	 $sth1->bindParam(6, $purpose1, PDO::PARAM_STR);
	 $sth1->bindParam(7, $case_remark, PDO::PARAM_STR);
	 $sth1->bindParam(8, $priority_serial1, PDO::PARAM_STR);
	 $sth1->bindParam(9, $list_flag, PDO::PARAM_STR);
	 $sth1->bindParam(10, $list_with_defect, PDO::PARAM_STR);
	 $sth1->bindParam(11, $filing_no, PDO::PARAM_STR);
	 $sth1->bindParam(12, $listt_date, PDO::PARAM_STR);
	 $sth1->execute();
	 
	 $q="insert into $schemas.case_proceeding_his select * from $schemas.case_proceeding where filing_no='$filing_no'  and next_list_date='$listt_date'";
    $copy_table=$db->prepare($q);
	 $copy_table->execute();
	 
	 $qq="update  $schemas.case_proceeding set next_list_purpose=? where filing_no=? and next_list_date=?";
	 $up_case_proceeding=$db->prepare($qq);
	 $up_case_proceeding->bindParam(1, $purpose1, PDO::PARAM_STR);
	 $up_case_proceeding->bindParam(2, $filing_no, PDO::PARAM_STR);
	 $up_case_proceeding->bindParam(3, $listt_date, PDO::PARAM_STR);
	 $up_case_proceeding->execute();
		}
	catch(\Exception $e){
		//die('k');
		echo "Caught", $e->getMessage(),"\n";
		//die();
	}
	
 
	
}
if($total_recused == '0'){
	$msg= "ALL CASES LISTED SUCCESSFULLY";
}
else {
	$total_listed = ($l-$total_recused);
	$msg = "$total_listed/$l CASES LISTED SUCCESSFULLY, Following $total_recused Case(s) couldn't be listed due to the Judge(s) in the bench having recused himself from the case:  $recused_filing_no ";
}
$msghash1 =$msg."@".$listt_date;
$msghash=base64_encode($msghash1);
header("Location:./old_case_list.php?msghash=$msghash");

		
?>

<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<?php
//session_start();
//ob_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);  

include("../db_inc1.php");
$schemas=htmlspecialchars($_SESSION['schema_name']);
$next_list_date=$_REQUEST['next_list_date'];
list($day,$month,$year)=explode('/',$next_list_date);
$date_new=$year.'-'.$month.'-'.$day;
$court_no = $_REQUEST['court'];
$user_id=htmlspecialchars($_SESSION['id']);
$username = htmlspecialchars($_SESSION['user_actual_name']);
$location_id = $_SESSION['location'];

date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); 
include("../master/functions.php");
//include("../custom/custom_function.php");

require "../vendor/autoload.php";
use Dompdf\Dompdf;

function getRecordByType($dbh,$list_flag,$court_num,$location_code,$listing_date,$schemas,$table){
	$query="select * from $schemas.$table where listing_date = ? and court_no = ? and list_flag = ?";		
	//echo "select * from $schemas.case_allocation_temp where listing_date = '$listing_date' and location_code = '$location_code' and court_no = '$court_num' and list_flag = '$list_flag'"; die;
	$data=$dbh->prepare($query);
	$data->bindParam(1, $listing_date, PDO::PARAM_STR);
	//$data->bindParam(2, $location_code, PDO::PARAM_STR);
	$data->bindParam(2, $court_num, PDO::PARAM_STR);
	$data->bindParam(3, $list_flag, PDO::PARAM_STR);
	$data->execute();
	$data = $data->fetchAll();
	return $data;
}

function save_pdf($dbh,$schemas,$listing_date,$court_num,$list_flag,$save_path,$filename,$location_code,$server_date){
	$data = getRecordByType($dbh,$list_flag,$court_num,$location_code,$listing_date,$schemas,"case_allocation");
	//echo "<pre>"; print_r($data); 
	if(empty($data)){
		$query="insert into $schemas.causelistpdf (listing_date,court_no,list_flag,path,filename,location_code,created_at) values (?,?,?,?,?,?,?)";		
		$save=$dbh->prepare($query);
		$save->bindParam(1, $listing_date, PDO::PARAM_STR);
		$save->bindParam(2, $court_num, PDO::PARAM_STR);
		$save->bindParam(3, $list_flag, PDO::PARAM_STR);
		$save->bindParam(4, $save_path, PDO::PARAM_STR);
		$save->bindParam(5, $filename, PDO::PARAM_STR);
		$save->bindParam(6, $location_code, PDO::PARAM_STR);
		$save->bindParam(7, $server_date, PDO::PARAM_STR);
		$save->execute();
	}
	return;
}

 



//$date = '2019-02-28';

/* $masters = new Masters($schemas);
$bench="select distinct(location_code) from $schemas.bench where from_list_date = ? order by location_code";
$bench=$dbh->prepare($bench);
$bench->bindParam(1, $date_new, PDO::PARAM_STR);
$bench->execute();
$locations = $bench->fetchAll(); */




/* if (!file_exists('../casedoc/causelistpdf')) {
    mkdir('../casedoc/causelistpdf', 0777, true);
}
foreach($locations as $key=>$location)
{
	$location_code = $location['location_code'];
	$bench_zone = $masters->getSingleByCondition('bench_location_name','bench_location','TRUE','bench_location_code',$location_code);
	if (!file_exists('../casedoc/causelistpdf/'.$bench_zone)) {
		mkdir('../casedoc/causelistpdf/'.$bench_zone, 0777, true);
	}
	$court_no="select distinct(court_no) from $schemas.bench where from_list_date = ? and location_code = ?";
	$court_no=$dbh->prepare($court_no);
	$court_no->bindParam(1, $date_new, PDO::PARAM_STR);
	$court_no->bindParam(2, $location_code, PDO::PARAM_STR);
	$court_no->execute();
	$court_numbers = $court_no->fetchAll();
	if(!empty($court_numbers)){
		if (!file_exists('../casedoc/causelistpdf/'.$bench_zone.'/'.$date_new)) {
			mkdir('../casedoc/causelistpdf/'.$bench_zone.'/'.$date_new, 0777, true);
		}
		foreach($court_numbers as $key_court=>$court_number)
		{
			$court_num = $court_number['court_no'];
			if (!file_exists('../casedoc/causelistpdf/'.$bench_zone.'/'.$date_new.'/courts/'.$court_num)) {
				mkdir('../casedoc/causelistpdf/'.$bench_zone.'/'.$date_new.'/courts/'.$court_num, 0777, true);
			}
			 $data = getRecordByType($dbh,1,$court_num,$location_code,$date_new,$schemas,"case_allocation_temp");
			  if(!empty($data)){
				if (!file_exists('../casedoc/causelistpdf/'.$bench_zone.'/'.$date_new.'/courts/'.$court_num.'/daily')) {
					mkdir('../casedoc/causelistpdf/'.$bench_zone.'/'.$date_new.'/courts/'.$court_num.'/daily', 0777, true);
				} 
				

				$dompdf = new Dompdf();
				$html = generate_cause_list_html($dbh,$db,$data,$court_num,$date_new,$schemas);
			
				
				$filename = $date_new."_COURT_NO_".$court_num."_daily"."_".$location_code.'.pdf';
				$save_path = 'casedoc/causelistpdf/'.$bench_zone.'/'.$date_new.'/courts/'.$court_num.'/daily/';
				$path = '../casedoc/causelistpdf/'.$bench_zone.'/'.$date_new.'/courts/'.$court_num.'/daily/'.$filename;
				try{
				$dompdf->loadHtml($html);

				$dompdf->setPaper('A4');


				$dompdf->render();
				$output = $dompdf->output();
				
				file_put_contents($path, $output);
				save_pdf($dbh,$schemas,$date_new,$court_num,1,$save_path,$filename,$location_code,$server_date);
				}	catch(Exception $e) {
					echo $filename;
					
					echo "<br/>";
				  echo 'Message: ' .$e->getMessage();
				  echo "<br/>";
				  continue;
				}			

				
				
			} 

			$data = getRecordByType($dbh,2,$court_num,$location_code,$date_new,$schemas,"case_allocation_temp");
			  if(!empty($data)){
				if (!file_exists('../casedoc/causelistpdf/'.$bench_zone.'/'.$date_new.'/courts/'.$court_num.'/supplementry')) {
					mkdir('../casedoc/causelistpdf/'.$bench_zone.'/'.$date_new.'/courts/'.$court_num.'/supplementry', 0777, true);
				} 

				$dompdf = new Dompdf();
				$htmll = generate_cause_list_html($dbh,$db,$data,$court_num,$date_new,$schemas);
				
				$filename = $date_new."_COURT_NO_".$court_num."_supplementry"."_".$location_code.'.pdf';
				$save_path = 'casedoc/causelistpdf/'.$bench_zone.'/'.$date_new.'/courts/'.$court_num.'/supplementry/';
				$path = '../casedoc/causelistpdf/'.$bench_zone.'/'.$date_new.'/courts/'.$court_num.'/supplementry/'.$filename;
				try{
				$dompdf->loadHtml($htmll);

				$dompdf->setPaper('landscape');

				$dompdf->render();
				$output = $dompdf->output();
				file_put_contents($path, $output);
				save_pdf($dbh,$schemas,$date_new,$court_num,2,$save_path,$filename,$location_code,$server_date);
				}	catch(Exception $e) {
					echo $filename;
					
					echo "<br/>";
				  echo 'Message: ' .$e->getMessage();
				  echo "<br/>";
				  continue;
				}			
				
			}		
		}
	}
} */


try	{
	$db->beginTransaction();  // begin transaction
   
	$main_cases = main_case_type();	
    // A set of queries; if one fails, an exception should be thrown
    $sql_pr2="delete from $schemas.case_allocation  where listing_date='$date_new' and court_no = '$court_no'";
	
	$delete_cal = $db->prepare("delete from $schemas.case_allocation  where listing_date=? and court_no = ?");
	$delete_cal->bindParam(1, $date_new, PDO::PARAM_STR);
	$delete_cal->bindParam(2, $court_no, PDO::PARAM_STR);
	$rec = $delete_cal->execute();
	if($rec) {
	$insert_cal = $db->prepare("insert into $schemas.case_allocation select * from $schemas.case_allocation_temp where listing_date=? and court_no = ?");
	$insert_cal->bindParam(1, $date_new, PDO::PARAM_STR);
	$insert_cal->bindParam(2, $court_no, PDO::PARAM_STR);
	$insert_cal->execute();
	}else{
		echo "something went wrong"; die;
	}
 // $sql_pr1="insert into $schemas.case_allocation select * from $schemas.case_allocation_temp where listing_date='$date_new' and court_no = '$court_no'";

//   $db->exec($sql_pr2);
   
    /* $sth=$db->prepare($sql_pr2);
	$sth->execute();
	 
	  $sth1=$db->prepare($sql_pr1);
	 $sth1->execute(); */
  
   
	 $sql_cal="select a.filing_no,a.listing_date,a.bench_no,a.list_flag,a.court_no,a.bench_nature,b.purpose_name from $schemas.case_allocation as a
	 	left join $schemas.master_purpose as b on b.purpose_code = a.purpose
	 	 where a.listing_date=? and a.court_no = ?";
	 $get_rec = $db->prepare($sql_cal);
	$get_rec->bindParam(1, $date_new, PDO::PARAM_STR);
	$get_rec->bindParam(2, $court_no, PDO::PARAM_STR);
	$get_rec->execute();
	$all_records = $get_rec->fetchAll();
   foreach($all_records as $k=>$f)
   {
	    $filing_no =$f['filing_no'];
		$listing_date = $f['listing_date'];
		$bench_no = $f['bench_no'];
		$list_flag = $f['list_flag'];
		$court_no = $f['court_no'];
		$bench_nature = $f['bench_nature'];
		$purpose_name = $f['purpose_name'];
		$sql_fn="select filing_no,case_no,main_case_ia_no,case_type from $schemas.case_detail where filing_no=?";
		 $get_fn_rec = $db->prepare($sql_fn);
		$get_fn_rec->bindParam(1, $filing_no, PDO::PARAM_STR);
		$get_fn_rec->execute();
		$filing_details = $get_fn_rec->fetch();
		if(!empty($filing_details)){
			$main_case_fn = '';
			$main_case_ia_no = $filing_details['main_case_ia_no'];
			$selected_case_type = $filing_details['case_type'];
			if (in_array($selected_case_type, $main_cases)){
				$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($filing_no);
			}else{
				$main_case_fn = $show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($main_case_ia_no);
				if(empty($show_party_filing_no))
					$show_party_filing_no = htmlspecialchars($filing_no);
			}
			$pet_name =get_party($db,$show_party_filing_no,'P',1);
			$res_name =get_party($db,$show_party_filing_no,'R',1); 
		}else{
			$pet_name = $res_name = '';
		}
		
		$update_party_details = $db->prepare("update $schemas.case_allocation set pet_name = ?,  res_name = ? 
												where listing_date = ? and court_no = ? and filing_no = ? and bench_no = ?");
		$update_party_details->bindParam(1, $pet_name, PDO::PARAM_BOOL);
		$update_party_details->bindParam(2, $res_name, PDO::PARAM_BOOL);
		$update_party_details->bindParam(3, $date_new, PDO::PARAM_STR);
		$update_party_details->bindParam(4, $court_no, PDO::PARAM_STR);
		$update_party_details->bindParam(5, $filing_no, PDO::PARAM_STR);
		$update_party_details->bindParam(6, $bench_no, PDO::PARAM_STR);
		$update_party_details->execute();
		
		$save_logs = save_log_and_delete($schemas,$db,$filing_no,$listing_date,$bench_no,$court_no,$list_flag);
		
		$selected_child = selected_child_cases($schemas,$db,$filing_no,1,$listing_date,$bench_no,$list_flag,$court_no,''); 
		
		$true = TRUE;
		$advocate_list = $db->prepare("select distinct(a.rep_code) as rep_code,a.party_flag,b.rep_name,a.remarks,c.party_serial_no from e_more_representative as a 
			inner join e_master_advocate as b on b.id = a.rep_code 
			inner join e_cases_party as c on c.id = a.party_code
			where a.filing_no = ? and a.show_in_causelist = ?
			UNION ALL
			select distinct(a.rep_code) as rep_code,a.party_flag,b.rep_name,a.remarks,c.party_serial_no from e_more_representative_gst_nodal as a 
			inner join e_master_advocate as b on b.id = a.rep_code 
			left join e_cases_party as c on c.id = a.party_code
			where a.filing_no = ? and a.show_in_causelist = ?");
		$advocate_list->bindParam(1, $filing_no, PDO::PARAM_STR);
		$advocate_list->bindParam(2, $true, PDO::PARAM_STR);
		$advocate_list->bindParam(3, $filing_no, PDO::PARAM_STR);
		$advocate_list->bindParam(4, $true, PDO::PARAM_STR);
		$advocate_list->execute();
		$advocate_list = $advocate_list->fetchAll();
		
		if(!empty($advocate_list)){
			$delete_adv="delete from $schemas.causelist_advocate where filing_no=? and listing_date = ? and bench_no = ? and court_no = ? and list_flag = ?";
			 $delete_adv = $db->prepare($delete_adv);
			$delete_adv->bindParam(1, $filing_no, PDO::PARAM_STR);
			$delete_adv->bindParam(2, $listing_date, PDO::PARAM_STR);
			$delete_adv->bindParam(3, $bench_no, PDO::PARAM_STR);
			$delete_adv->bindParam(4, $court_no, PDO::PARAM_STR);
			$delete_adv->bindParam(5, $list_flag, PDO::PARAM_STR);
			$delete_adv->execute();
			
			foreach($advocate_list as $k=>$adv){
				$adv_id = $adv['rep_code'];
				$adv_name = $adv['rep_name'];
				$adv_type = $adv['party_flag'];
				$adv_remark = $adv['remarks'];
				$party_serial = $adv['party_serial_no'];
				$ins_adv = $db->prepare("insert into $schemas.causelist_advocate (filing_no,listing_date,court_no,bench_no,list_flag,bench_nature,
													adv_id,adv_name,adv_type,remarks,user_id,username,party_serial) values (?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$ins_adv->bindParam(1, $filing_no, PDO::PARAM_STR);
				$ins_adv->bindParam(2, $listing_date, PDO::PARAM_STR);
				$ins_adv->bindParam(3, $court_no, PDO::PARAM_STR);
				$ins_adv->bindParam(4, $bench_no, PDO::PARAM_STR);
				$ins_adv->bindParam(5, $list_flag, PDO::PARAM_STR);
				$ins_adv->bindParam(6, $bench_nature, PDO::PARAM_STR);
				$ins_adv->bindParam(7, $adv_id, PDO::PARAM_STR);
				$ins_adv->bindParam(8, $adv_name, PDO::PARAM_STR);
				$ins_adv->bindParam(9, $adv_type, PDO::PARAM_STR);
				$ins_adv->bindParam(10, $adv_remark, PDO::PARAM_STR);
				$ins_adv->bindParam(11, $user_id, PDO::PARAM_STR);
				$ins_adv->bindParam(12, $username, PDO::PARAM_STR);
				$ins_adv->bindParam(13, $party_serial, PDO::PARAM_STR);
				$ins_adv->execute();
			}
		}
		 /* if(empty($selected_child)){
		 $selected_child = generate_case_no_new($schemas,$db,$filing_no,$status = 'P');
		} */
		$count_child_cases = count($selected_child);
		if(!empty($selected_child) && $selected_child[0]['filing_no']!='NA'){
			foreach($selected_child as $key=>$value){
				$child_filing_no = $value['filing_no'];
				$ins = insert_final_child_cases($schemas,$db,$filing_no,$child_filing_no,$listing_date,$bench_no,$list_flag,$court_no,$bench_nature,$user_id,$username,'I','');
			}
		}
		if(!empty($main_case_fn)){
			$save_logs = save_log_and_delete($schemas,$db,$main_case_fn,$listing_date,$bench_no,$court_no,$list_flag);
			$selected_child = selected_child_cases($schemas,$db,$main_case_fn,1,$listing_date,$bench_no,$list_flag,$court_no,$filing_no);
			if(!empty($selected_child) && $selected_child[0]['filing_no']!='NA'){
				
				foreach($selected_child as $key=>$value){
					$child_filing_no = $value['filing_no'];
					
					$ins = insert_final_child_cases($schemas,$db,$main_case_fn,$child_filing_no,$listing_date,$bench_no,$list_flag,$court_no,$bench_nature,$user_id,$username,'I',$filing_no);
				}
			}
		}
		$status='C';
		$stxx= $db->prepare("select * from $schemas.connected_cases where filing_no=? and status=?");
		$stxx->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stxx->bindParam(2, $status, PDO::PARAM_STR);
		$stxx->execute();
		$connected_cases = $stxx->fetchAll();
		if(!empty($connected_cases)){
			foreach($connected_cases as $key=>$conn_case){
				$child_filing_no = $conn_case['conn_filing_no'];
				$sql_fn="select filing_no,case_no,main_case_ia_no,case_type from $schemas.case_detail where filing_no=?";
				 $get_fn_rec = $db->prepare($sql_fn);
				$get_fn_rec->bindParam(1, $child_filing_no, PDO::PARAM_STR);
				$get_fn_rec->execute();
				$filing_details = $get_fn_rec->fetch();
				if(!empty($filing_details)){
					$main_case_ia_no = $filing_details['main_case_ia_no'];
					$selected_case_type = $filing_details['case_type'];
					if (in_array($selected_case_type, $main_cases)){
						$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($child_filing_no);
					}else{
						$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($main_case_ia_no);
						if(empty($show_party_filing_no))
							$show_party_filing_no = htmlspecialchars($filing_no);
					}
					$pet_name =get_party($db,$show_party_filing_no,'P',1);
					$res_name =get_party($db,$show_party_filing_no,'R',1); 
				}else{
					$pet_name = $res_name = '';
				}
				$save_logs = save_log_and_delete($schemas,$db,$child_filing_no,$listing_date,$bench_no,$court_no,$list_flag);
				$ins = insert_final_child_cases($schemas,$db,$filing_no,$child_filing_no,$listing_date,$bench_no,$list_flag,$court_no,$bench_nature,$user_id,$username,'C','',$pet_name,$res_name);
				$selected_child = selected_child_cases($schemas,$db,$child_filing_no,1,$listing_date,$bench_no,$list_flag,$court_no,''); 
				$true = TRUE;
				$advocate_list = $db->prepare("select distinct(a.rep_code) as rep_code,a.party_flag,b.rep_name,a.remarks,c.party_serial_no from e_more_representative as a 
													inner join e_master_advocate as b on b.id = a.rep_code 
													inner join e_cases_party as c on c.id = a.party_code
													where a.filing_no = ? and a.show_in_causelist = ?");
				$advocate_list->bindParam(1, $child_filing_no, PDO::PARAM_STR);
				$advocate_list->bindParam(2, $true, PDO::PARAM_STR);
				
				$advocate_list->execute();
				$advocate_list = $advocate_list->fetchAll();
				
				if(!empty($advocate_list)){
					$delete_adv="delete from $schemas.causelist_advocate where filing_no=? and listing_date = ? and bench_no = ? and court_no = ? and list_flag = ?";
					 $delete_adv = $db->prepare($delete_adv);
					$delete_adv->bindParam(1, $child_filing_no, PDO::PARAM_STR);
					$delete_adv->bindParam(2, $listing_date, PDO::PARAM_STR);
					$delete_adv->bindParam(3, $bench_no, PDO::PARAM_STR);
					$delete_adv->bindParam(4, $court_no, PDO::PARAM_STR);
					$delete_adv->bindParam(5, $list_flag, PDO::PARAM_STR);
					$delete_adv->execute();
					
					foreach($advocate_list as $k=>$adv){
						$adv_id = $adv['rep_code'];
						$adv_name = $adv['rep_name'];
						$adv_type = $adv['party_flag'];
						$adv_remark = $adv['remarks'];
						$party_serial = $adv['party_serial_no'];
						$ins_adv = $db->prepare("insert into $schemas.causelist_advocate (filing_no,listing_date,court_no,bench_no,list_flag,bench_nature,
															adv_id,adv_name,adv_type,remarks,user_id,username,party_serial) values (?,?,?,?,?,?,?,?,?,?,?,?,?)");
						$ins_adv->bindParam(1, $child_filing_no, PDO::PARAM_STR);
						$ins_adv->bindParam(2, $listing_date, PDO::PARAM_STR);
						$ins_adv->bindParam(3, $court_no, PDO::PARAM_STR);
						$ins_adv->bindParam(4, $bench_no, PDO::PARAM_STR);
						$ins_adv->bindParam(5, $list_flag, PDO::PARAM_STR);
						$ins_adv->bindParam(6, $bench_nature, PDO::PARAM_STR);
						$ins_adv->bindParam(7, $adv_id, PDO::PARAM_STR);
						$ins_adv->bindParam(8, $adv_name, PDO::PARAM_STR);
						$ins_adv->bindParam(9, $adv_type, PDO::PARAM_STR);
						$ins_adv->bindParam(10, $adv_remark, PDO::PARAM_STR);
						$ins_adv->bindParam(11, $user_id, PDO::PARAM_STR);
						$ins_adv->bindParam(12, $username, PDO::PARAM_STR);
						$ins_adv->bindParam(13, $party_serial, PDO::PARAM_STR);
						$ins_adv->execute();
					}
				}
				 /* if(empty($selected_child)){
				 $selected_child = generate_case_no_new($schemas,$db,$filing_no,$status = 'P');
				} */
				$count_child_cases = count($selected_child);
				if(!empty($selected_child) && $selected_child[0]['filing_no']!='NA'){
					
					foreach($selected_child as $key=>$value){
						$child_filing_no_conn = $value['filing_no'];
						
						$ins = insert_final_child_cases($schemas,$db,$child_filing_no,$child_filing_no_conn,$listing_date,$bench_no,$list_flag,$court_no,$bench_nature,$user_id,$username,'I','');
					}
				}
			}
		}
	   
	   //send sms
	  /* SMS  Level 3 case number*/
	  
	  
	  
$check_sql =$db->prepare("select rep_code from e_more_representative where filing_no =? and party_flag='P' and party_serial_no='1'");
$check_sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$check_sql->execute();
 $pet_adv_code= $check_sql->fetchColumn();


if($pet_adv_code=='')
{
	$pet_adv_code='0';
}
if($pet_adv_code >0)
{
$stqq12 = $db->prepare("select rep_name from e_master_advocate where id=?");
$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq12->execute();
$pet_adv_name = $stqq12->fetchColumn();	

 
$stqq11 = $db->prepare("select email from e_master_advocate where id=?");
$stqq11->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq11->execute();
$pet_adv_email = $stqq11->fetchColumn();
	
$stqq23 = $db->prepare("select mobile from e_master_advocate where id=?");
$stqq23->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq23->execute();
$pet_adv_mobile = $stqq23->fetchColumn();	

	
}

 $adv_party='R';
$adv_party_serial='1';
$check_sql1 =$db->prepare("select rep_code from e_more_representative where filing_no =? and party_flag=? and party_serial_no=?");
$check_sql1->bindParam(1, $filing_no, PDO::PARAM_STR);
$check_sql1->bindParam(2, $adv_party, PDO::PARAM_STR);
$check_sql1->bindParam(3, $adv_party_serial, PDO::PARAM_STR);
$check_sql1->execute();
$res_adv_code= $check_sql1->fetchColumn();
if($res_adv_code=='')
{
	$res_adv_code='0';
}
if($res_adv_code >0)
{
$stqq121 = $db->prepare("select rep_name from e_master_advocate where id=?");
$stqq121->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq121->execute();
$res_adv_name = $stqq121->fetchColumn();	

 
$stqq122 = $db->prepare("select email from e_master_advocate where id=?");
$stqq122->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq122->execute();
 $res_adv_email = $stqq122->fetchColumn();	
$stqq123 = $db->prepare("select mobile from e_master_advocate where id=?");
$stqq123->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq123->execute();
$res_adv_mobile = $stqq123->fetchColumn();	

	
}

$pet_flag='P';
$pet_serial='1';
$sthr2=$db->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=? ");
  $sthr2->bindParam(1, $filing_no, PDO::PARAM_STR);
  $sthr2->bindParam(2, $pet_flag, PDO::PARAM_STR);
  $sthr2->bindParam(3, $pet_serial, PDO::PARAM_STR);
  $sthr2->execute();
  while ($row1 = $sthr2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$pet_email=$row1['email'];
	$pet_mobile=$row1['mobile'];
	$pet_name=$row1['name'];
  }

$res_flag='R';
$res_serial='1';
  $sthr3=$db->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=?");
  $sthr3->bindParam(1, $filing_no, PDO::PARAM_STR);
  $sthr3->bindParam(2, $res_flag, PDO::PARAM_STR);
  $sthr3->bindParam(3, $res_serial, PDO::PARAM_STR);
  $sthr3->execute();
  while ($row3 = $sthr3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$res_email=$row3['email'];
	$res_mobile=$row3['mobile'];
	$res_name=$row3['name'];
  }
 
	
  $sql2="select * from $schemas.case_detail where filing_no='$filing_no'";
   foreach($db->query($sql2) as $f2)
   {
	   $case_case_type =$f2['case_type'];
	   $case_case_no =$f2['case_no'];
	   $case_case_year =$f2['case_year'];
   }
   
   
   
   
   
   
 $lcode ="select short_name from mater_location_city where city_id ='$location_id'";
$lcode=$db->prepare($lcode);
$lcode->execute();
$lcodename = $lcode->fetchColumn(); 


 $lcode1 ="select bench_location_name from $schemas.bench_location where bench_location_code ='$location_id'";
$lcode1=$db->prepare($lcode1);
$lcode1->execute();
$sms_lcodename = $lcode1->fetchColumn(); 

if($case_case_type > 0)
{
$stQ = $db->prepare("select short_name from case_type where id = ?");
$stQ->bindParam(1, $case_case_type, PDO::PARAM_STR);
$stQ->execute();
$case_type_short_name=$stQ->fetchColumn();
}
  $case_numaa = $case_case_no;
$case_year1aa = $case_case_year;
		$case_num1aa=ltrim($case_numaa,0); 
   
$CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_num1aa.'/'.$case_year1aa); 
  
$subject="Application/Petition registered with Case No:".$CASE_NO;


$sql21="select * from $schemas.case_allocation_temp where filing_no='$filing_no' and listing_date='$date_new' and court_no = '$court_no'";
   foreach($db->query($sql21) as $f21)
   {
	   $sms_court_no =$f21['court_no'];
	   
   }
   
 //   $query = "select display_court_text from $schemas.court where court_no = ?";
	// $stmt = $db->prepare($query);
 //    $stmt->bindParam(1, $sms_court_no, PDO::PARAM_STR);
	// $stmt->execute();
	// $sms_court_no = $stmt->fetchColumn();



	   
	   list($Y,$m,$d) =explode('-',$date_new);
$date_new1 =$d.'/'.$m.'/'.$Y;
	   
	$sql = "select a.city_name,b.state_name from mater_location_city as a
left join master_states as b on b.state_id = a.city_id
where a.city_id = ?";
    $bench_query = $db->prepare($sql);
    $bench_query->bindParam(1, $location_id, PDO::PARAM_STR);
    $bench_query->execute();
    $bench_detail =  $bench_query->fetch();
    $bench_name = $bench_detail['city_name'];
    $state_name = $bench_detail['state_name'];

 // $email_text="Case titled ".$pet_name." Vs ".$res_name." is filed at NCLAT and alloted case Number :".$case_type_short_name."/".$case_num1aa."/".$case_year1aa." will be listed on date :".$date_new1." before the Bench( ".$sms_court_no.") This is a computer generated message, Please do not reply";
 // $sms_detail=$case_type_short_name."/".$case_num1aa."/".$case_year1aa." [".$pet_name." Vs ".$res_name."]";
 // $sms_detail = (strlen($sms_detail) > 30) ? substr($sms_detail,0,25).'...' : $sms_detail;
 // $msg555="Case Number:".$sms_detail." to be listed on date: ".$date_new1." before (".$sms_court_no.")" ;
    $var2 = " Court $sms_court_no, $bench_name, $state_name";
 $email_text = "Your case number ".$case_type_short_name."/".$case_num1aa."/".$case_year1aa." will be listed before the ".$sms_court_no.", GSTAT, ".$bench_name."  on ".$date_new1." for ".$purpose_name." stage. This is a computer-generated message, please do not reply.";
 $msg555 = "Your case number ".$case_type_short_name."/".$case_num1aa."/".$case_year1aa." will be listed before the ".$var2." on ".$date_new1." for ".$purpose_name." stage. This is a computer-generated message, please do not reply. GSTAT-GSTN";
$sdsdsds = fn_sms($db, '6', '', $filing_no, $subject, $msg555, $email_text);
 

  /* echo $email_text; 

 $sql ="insert into sms(filing_no,case_number,msg,pet_adv_name,pet_adv_mob_no,pet_adv_email,pet_mobile,res_mobile,pet_name,res_name,res_adv_code,pet_adv_code,res_adv_name,res_adv_mob_no,subject,email_text,res_adv_email,pet_email,res_email,send_flag,entry_date,sms_flag,listing_date) 
VALUES('$filing_no','$CASE_NO','$msg555','$pet_adv_name','$pet_adv_mobile','$pet_adv_email','$pet_mobile','$res_mobile','$pet_name','$res_name','$res_adv_code','$pet_adv_code','$res_adv_name','$res_adv_mobile','$subject','$email_text','$res_adv_email','$pet_email','$res_email','0','$server_date','L','$date_new')";

$st = $db->prepare($sql);
$st->execute();    */	   
   }
  $db->commit();
 $msg= "SUCCESSFULLY CASE LISTED";
 echo $msg;
 die;
//header("Location:generate_cause_list.php?msg=Successfully  Case Listed");
}catch(Exception $e){
	echo $e;
	$db->rollBack();
	die;
	//header("Location:../index.php");
}
?>

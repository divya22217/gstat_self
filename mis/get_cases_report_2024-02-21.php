<?php 

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include("../formkey/formkey.class.php");
require_once("../classes/Pagination.class.php");
require_once("../custom/custom_function.php");
session_start();

 /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */  

$user = $_SESSION['user'];
$location_id = $_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

$pdfpath=$_SERVER['HTTP_HOST'].'/casedoc/defects/';

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}



setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$form_key = new formKey();
$main_case_type = array(32,33,34);

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	
	function get_cause_title($dbonline,$filing_no){
		$st=$dbonline->prepare("select case_title,case_type,dt_of_filing,patially_defective,filingnumberia,filing_new as wrongly_status from e_case_detail where  filing_no=?");
		$st->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st->execute();
		$data = $st->fetchAll();
		$data = array_shift($data);
		return $data;
	}
	
	function cause_title($dbonline,$filing_no){
		$E_party_flag1='P';
		$E_party_serial_no1='1';
		$pet_name=$dbonline->prepare("select name from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$pet_name->bindParam(1, $filing_no, PDO::PARAM_STR);
		$pet_name->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$pet_name->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$pet_name->execute();
		$pet_name = $pet_name->fetchColumn();
		
		$E_party_flag1='R';
		$E_party_serial_no1='1';
		$res_name=$dbonline->prepare("select name from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$res_name->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res_name->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$res_name->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$res_name->execute();
		$res_name = $res_name->fetchColumn();
		
		$cause_title = $pet_name."<br/><b>VS</b><br/> ".$res_name;
		return $cause_title;
		
		
	}
	
	
	function get_main_filing_no($dbonline,$ia_ma_filing_no){
	$main_case_filing_no = $dbonline->prepare("select filingumberia from e_case_detail where filing_no = ?");
	$main_case_filing_no->bindParam(1, $ia_ma_filing_no, PDO::PARAM_INT);
	$main_case_filing_no->execute();
	$fn = $main_case_filing_no->fetchColumn();
	return $fn;
}
	
	function generate_case_no($schemas,$db,$filing_no){
		$case_no = '';
		$case_detail = $db->prepare("select a.case_no,a.case_year,b.short_name as case_type_name,c.short_name as loc,a.loginid,a.transfrred_case_type_short from $schemas.case_detail as a
										 left join case_type as b on b.id = a.case_type 
										 left join $schemas.bench_location as c on c.city_id = a.location_code where filing_no=?");
		$case_detail->bindParam(1, $filing_no, PDO::PARAM_INT);
		$case_detail->execute();
		$case_detail = $case_detail->fetchAll();
		if(!empty($case_detail)){
			$case_detail = array_shift($case_detail);
			if(!empty($case_detail['transfrred_case_type_short'])){
			$tr_short = " ($case_detail[transfrred_case_type_short])";
			}
			$case_no = $case_detail['case_type_name'].$tr_short.'/'.$case_detail['case_no'].'('.$case_detail['loc'].")/".$case_detail['case_year'];
			if($case_detail['loginid'] == '194' || $case_detail['loginid'] == '81'){
				$case_no .= " <em style='color:green;'>R</em>";
			}
		}
		return $case_no;
	}
	
	function ia_date_of_filing($dbo,$filing_no){
		$dttt = $dbo->prepare("select dt_of_filing::TIMESTAMP::DATE from e_case_detail where filing_no = ?");
        $dttt->bindParam(1, $filing_no, PDO::PARAM_STR);
        $dttt->execute();
		$dt_of_filing = $dttt->fetchColumn();
		return $dt_of_filing;
	}
	
	function get_case_type_name($db,$filing_no){
		$case_detail = $db->prepare("select b.case_type_desc from e_case_detail as a
										 left join case_type as b on b.id = a.case_type_nclat 
										 where filing_no=?");
		$case_detail->bindParam(1, $filing_no, PDO::PARAM_INT);
		$case_detail->execute();
		$case_detail = $case_detail->fetchColumn();
		return $case_detail;
	}
	
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$validatekey = $form_key->validate();
	//var_dump($validatekey);
	if($validatekey){
		 $user_id = $_POST['user_id'];
		if($sessionUserType == $user_id){
			$selected_type = $_POST['radioValue'];
			if($selected_type){

				$table = "scrutiny";
				$column = 'user_id';
				$date_column = 'notification_date';
				$from_date = $_POST['from_date'];
				$end_date = $_POST['end_date'];
				$search_filing_no = (isset($_POST['search_filing_no']) && !empty($_POST['search_filing_no']))?$_POST['search_filing_no']:'';
				$defect_status = $_POST['defect_status'];
				$item_per_page = $_POST['item_per_page'];
				if (!is_numeric($search_filing_no) && !empty($search_filing_no)) {
					echo "Invalid Input";
					die();
					}
				//$item_per_page = 5;
				if($defect_status == 0){
					$objection_status_query = '';
				}else{
				$objection_status = (isset($defect_status) && ($defect_status == 1))?'Y':'N';
				//$objection_status_query = 'and objection_status = ?';
				$objection_status_query = 'where objection_status = ?';
				}
				$perPage = new PerPage($item_per_page);
				$paginationlink = "get_cases_report.php?page=";
				$page = 1;
				if(!empty($_GET["page"])) {
				$page = $_GET["page"];
				}
				
				if($search_filing_no != ''){
					$search_query = " AND filing_no = '$search_filing_no'";
				}else{
					$search_query = '';
				}

				$start = ($page-1)*$perPage->perpage;
				if($start < 0) $start = 0;
				$count = $start +1;
				
				if(empty($_GET["rowcount"])) {
					if($from_date == '' || $end_date == ''){
						 if($objection_status_query == '' and $search_filing_no != ''){
								$search_query = " where filing_no = '$search_filing_no'";
							}
						//$count_query = "select count(*) as count from $schemas.$table where  $column=? $objection_status_query $search_query";
						$count_query = "select count(*) as count from $schemas.$table $objection_status_query $search_query";
						$st_count=$db->prepare($count_query);
						//$st_count->bindParam(1, $user_id, PDO::PARAM_STR);
						if($defect_status != 0){
						$st_count->bindParam(1, $objection_status, PDO::PARAM_STR);
						}
					}else{
						if($objection_status_query != ''){
							$objection_status_query = 'and objection_status = ?';
						}
						$fromdate = date('Y-m-d',strtotime($from_date));
						$enddate = date('Y-m-d',strtotime($end_date));
						//$count_query = "select count(*) as count from $schemas.$table where  $column=? and $date_column between ? and ? $objection_status_query $search_query";
						$count_query = "select count(*) as count from $schemas.$table where  $date_column between ? and ? $objection_status_query $search_query";
						$st_count=$db->prepare($count_query);
						//$st_count->bindParam(1, $user_id, PDO::PARAM_STR);
						$st_count->bindParam(1, $fromdate, PDO::PARAM_STR);
						$st_count->bindParam(2, $enddate, PDO::PARAM_STR);
						if($defect_status != 0){
						$st_count->bindParam(3, $objection_status, PDO::PARAM_STR);
						}
					}
					$st_count->execute();
					$_GET["rowcount"] = $st_count->fetchColumn();
				}
					
				$perpageresult = $perPage->getAllPageLinks($_GET["rowcount"], $paginationlink,$pagination_setting='');					
				
				
				if($from_date == '' || $end_date == ''){
					//$query = "select filing_no,notification_date,objection_status,compliance_date,varifyed_userid from $schemas.$table where  $column=?  $objection_status_query $search_query order by $date_column desc limit $perPage->perpage offset $start";
					$query = "select filing_no,notification_date,objection_status,compliance_date,varifyed_userid from $schemas.$table  $objection_status_query $search_query order by $date_column desc limit $perPage->perpage offset $start";
					$st=$db->prepare($query);
					//$st->bindParam(1, $user_id, PDO::PARAM_STR);
					if($defect_status != 0){
					$st->bindParam(1, $objection_status, PDO::PARAM_STR);
					}

				}else{
					if($objection_status_query != ''){
							$objection_status_query = 'and objection_status = ?';
						}
					$fromdate = date('Y-m-d',strtotime($from_date));
					$enddate = date('Y-m-d',strtotime($end_date));
					//$query = "select filing_no,notification_date,objection_status,compliance_date,varifyed_userid from $schemas.$table where  $column=? and $date_column between ? and ? $objection_status_query $search_query order by $date_column desc limit $perPage->perpage offset $start";
					$query = "select filing_no,notification_date,objection_status,compliance_date,varifyed_userid from $schemas.$table where  $date_column between ? and ? $objection_status_query $search_query order by $date_column desc limit $perPage->perpage offset $start";
					$st=$db->prepare($query);
					//$st->bindParam(1, $user_id, PDO::PARAM_STR);
					$st->bindParam(1, $fromdate, PDO::PARAM_STR);
					$st->bindParam(2, $enddate, PDO::PARAM_STR);
					if($defect_status != 0){
					$st->bindParam(3, $objection_status, PDO::PARAM_STR);
					}
				}

				$st->execute();
				$data = $st->fetchAll();
				//echo "<pre>"; print_r($data); die("sdfd");
				//$count = 1;
				foreach($data as $key=>$value){
					$filing_no = $value['filing_no'];
					$get_casuse_title = get_cause_title($dbo,$filing_no);
					$is_partially_defective = $get_casuse_title['patially_defective'];
					$case_type_check = $get_casuse_title['case_type'];
					if(!empty($get_casuse_title['filingumberia']) && $get_casuse_title['filingumberia'] != 'NA'){
						$main_filing_no = $get_casuse_title['filingumberia'];
					}else{
						$main_filing_no = $filing_no;
					}
					$get_date_of_filing = $get_casuse_title['dt_of_filing'];
					if($get_date_of_filing < '2019-09-18'){
							continue;
						}
					$status_text = ($get_casuse_title['wrongly_status'] == 'W')?'Wrongly Updated':'';
					$cause_title = cause_title($dbo,$main_filing_no);
					$varifyed_userid = $value['varifyed_userid'];
					$case_type_name = get_case_type_name($db,$value['filing_no']);
					$case_no = generate_case_no($schemas,$db,$value['filing_no']);
					$get_computation_note = computation_note_details($db,$schemas,$value['filing_no']);
					$get_computation_note = array_shift($get_computation_note);
					echo "<tr>";
					echo "<td>$count</td>";
					echo "<td>$value[filing_no] <font style='color:red;'>".$status_text."</font></td>";
					echo "<td>$case_no</td>";
					echo "<td>$case_type_name</td>";
					echo "<td>$cause_title</td>";
					echo "<td>".date('d/m/Y',strtotime($get_date_of_filing))."</td>";
					echo "<td>".date('d/m/Y',strtotime($value[$date_column]))."</td>"; ?>
					<td>
					<?php if(!empty($get_computation_note)){ ?>
								<h3><span class="label label-info">
								<a style="color: #FFFFFF;" href="javascript:void(0);" onClick="return show_note('<?php echo $value['filing_no']; ?>','<?php echo '1'; ?>','../ajax/computation_note.php');" >Show Note</a>
								</span></h3>
						<?php	}	?>
					</td>
				<?php	if($is_partially_defective == 1){
						$defect_status = 'Partially Defective';
						$defect_status_color = 'black';
						} else{		
						$defect_status = ($value['objection_status'] == 'N')?'Defect Free':'Defective';
						$defect_status_color = ($value['objection_status'] == 'N')?'green':'red';
						}
					
					if(strtoupper($defect_status) == strtoupper('Defective')){ 
						$path = $_SERVER['HTTP_HOST'].'/nclat';	
						$count_scrutiny = $dbh->prepare("select defect_pdf_path from $schemas.scrutiny where filing_no =?");
						$count_scrutiny->bindParam(1, $value['filing_no'], PDO::PARAM_STR);
						$count_scrutiny->execute();
						$defect_path = $count_scrutiny->fetchColumn();
					?>
						<td style='color:<?php echo $defect_status_color; ?>'><a href="javascript:void(0);" onClick="view_pdf('<?php echo urlencode($defect_path); ?>')">Defect Pdf</a>
						<br/>
						<?php echo date('d/m/Y',strtotime($value['notification_date'])); ?>
						</td> 
					<?php }else{
					echo "<td style='color:$defect_status_color;'>$defect_status</td>";
					}
					
					echo "</tr>";
					$count++;
				}
				if(!empty($perpageresult)) {
				echo '<tr><td id="pagination" colspan="6">' . $perpageresult . '</td></tr>';
				}

			}else{
				echo "please check radio button";
			}
		}
		else{
			echo "user mismatch";
		} 
	}else{
		echo "Token mismatch";
	}
	
}

?>

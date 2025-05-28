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

  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);   

$user = $_SESSION['user'];
$location_id = $_SESSION['location'];

$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];


if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}



setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$form_key = new formKey();


// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}


if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	function get_cause_title($db,$filing_no){
		$st=$db->prepare("select case_title,case_type,dt_of_filing,patially_defective,filingnumberia,filing_new as wrongly_status from e_case_detail where  filing_no=?");
		$st->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st->execute();
		$data = $st->fetchAll();
		$data = array_shift($data);
		return $data;
	}
	
	function cause_title($db,$filing_no){
		$E_party_flag1='P';
		$E_party_serial_no1='1';
		$pet_name=$db->prepare("select name from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$pet_name->bindParam(1, $filing_no, PDO::PARAM_STR);
		$pet_name->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$pet_name->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$pet_name->execute();
		$pet_name = $pet_name->fetchColumn();
		
		$E_party_flag1='R';
		$E_party_serial_no1='1';
		$res_name=$db->prepare("select name from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$res_name->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res_name->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$res_name->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$res_name->execute();
		$res_name = $res_name->fetchColumn();
		
		$cause_title = $pet_name."<br/><b>VS</b><br/> ".$res_name;
		return $cause_title;
		
		
	}
	
	
	function get_main_filing_no($db,$ia_ma_filing_no){
	$main_case_filing_no = $db->prepare("select filingumberia from e_case_detail where filing_no = ?");
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
			
		}
		return $case_no;
	}
	
	function ia_date_of_filing($db,$filing_no){
		$dttt = $db->prepare("select dt_of_filing::TIMESTAMP::DATE from e_case_detail where filing_no = ?");
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
	

	if($validatekey){
				$item_per_page = $_POST['item_per_page'];
				$perPage = new PerPage($item_per_page);
				$paginationlink = "refiling_exceed_cases.php.php?page=";
				$page = 1;
				if(!empty($_GET["page"])) {
				$page = $_GET["page"];
				}
				$start = ($page-1)*$perPage->perpage;
				if($start < 0) $start = 0;
				$count = $start +1;

				$selected_option = $_POST['radioValue'];

				if($selected_option == '1'){

						$query = "select count(*) as count from e_case_detail as ecd   where ecd.is_defective = 1 and ecd.scrutiny_level = 2 and ecd.allow_refiling = 0  and ecd.place_of_supply_accepted = 1 and ecd.location_id = ?";

						// and ecd.refile_count = 1 and ecd.supply_disputed_questions = 2 and ecd.list_with_defect = 0 
				 		
				 		$st = $db->prepare($query);
				 		$st->bindParam(1, $location_id, PDO::PARAM_STR);
						$st->execute();
						$total_records = $_GET["rowcount"] = $st->fetchColumn();

						$perpageresult = $perPage->getAllPageLinks($_GET["rowcount"], $paginationlink,$pagination_setting='');

				 		$query = "select ecd.filing_no,ecd.allow_refiling_date,date(ecd.dt_of_filing) as dt_of_filing,ecd.supply_disputed_questions from e_case_detail as ecd   where  ecd.is_defective = 1 and ecd.scrutiny_level = 2  and ecd.allow_refiling = 0 and ecd.place_of_supply_accepted = 1 and ecd.location_id = ? order by ecd.dt_of_filing desc limit $perPage->perpage offset $start";
				 		
				 		$st = $db->prepare($query);
				 		$st->bindParam(1, $location_id, PDO::PARAM_STR);
						$st->execute();
						$data = $st->fetchAll();
						 
						
						
						//echo "<pre>"; print_r($data); die("sdfd");
						//$count = 1;
						if(!empty($total_records)){
							foreach($data as $key=>$value){
								$filing_no = $value['filing_no'];
								$get_casuse_title = get_cause_title($db,$filing_no);
								$case_type = $case_type_check = $get_casuse_title['case_type'];

								if(!empty($get_casuse_title['filingnumberia']) && $get_casuse_title['filingnumberia'] != 'NA'){
									$main_filing_no = $get_casuse_title['filingnumberia'];
								}else{
									$main_filing_no = $filing_no;
								}
								$get_date_of_filing = $get_casuse_title['dt_of_filing'];
								
								
								$cause_title = cause_title($db,$main_filing_no);
								
								$case_type_name = get_case_type_name($db,$value['filing_no']);
								
								
								echo "<tr>";
								echo "<td>$count</td>";
								echo "<td>$value[filing_no]</font></td>";
								echo "<td>$case_type_name</td>";
								echo "<td>$cause_title</td>";
								echo "<td>".date('d/m/Y h:i A',strtotime($get_date_of_filing))."</td>";
								?>
								<td>
			                    <!--<a class="label label-info" style="color: #FFFFFF;" href="javascript:void(0);" onClick="return register_with_defect('<?php echo $filing_no; ?>','<?php echo $case_type; ?>','../ajax/computation_note.php');" >List case with defect</a>-->

			                    <a class="label label-danger" style="color: #FFFFFF;" href="javascript:void(0);" onClick="return enable_scrutiny('<?php echo $filing_no; ?>','<?php echo $case_type; ?>','../ajax/computation_note.php');" >Revert back to scrutiny</a>
								</td>
								</tr>
								<?php
								$count++;
							}
						}else{ ?>
							<tr><td colspan="6" align="center">No Record Found</td></tr>
				<?php   }
						if(!empty($perpageresult)) {
						echo '<tr><td id="pagination" colspan="6">' . $perpageresult . '</td></tr>';
						}

					
				
			}


	}else{
		echo "Token mismatch";
	}

	?>
	<script type="text/javascript">
		function enable_scrutiny(filing_no,case_type,url,type){
		let schemas = '<?php echo $schemas; ?>';
		swal({
		title: "Are you sure ?",
		input: "text",
		text: "If yes then case will forwarded to scrutiny reporter", 
		icon: "warning",
		buttons: true,
		dangerMode: false,
		})
		.then((willDelete) => { 
			 if (willDelete) {	
				$.ajax({
					type: "POST",
					url: url,
					data: {action:'enable_scrutiny',filing_no:filing_no},
					/* contentType: false,
					cache: false,
					processData:false, */
					dataType: 'json',
					beforeSend:function(){ 
						/* $('#remark_btn').attr("disabled","disabled");
						$('#remark_form').css("opacity",".5"); */
					},
					success: function (response) {
						if(response.status == 0){
							swal('',response.message,'warning');
						}
						else{
							swal('',response.message,'success');
							setTimeout(function(){ location.reload(true); }, 3000);
						}
					},
					error: function (textStatus, errorThrown) {
					  console.log(textStatus);
					   alert(errorThrown);
					}

				}); 
				return false;
			 }else{
			 }
		});
	}
	</script>
<?php	
}

?>

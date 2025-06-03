
<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */
include("../db_inc1.php");
include("../db_inc2.php");
date_default_timezone_set("Asia/Kolkata");
$schema=htmlspecialchars($_SESSION['schema_name']);
$location_name = strtoupper($schema);
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	
	$location_id = $_SESSION['location'];
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	
	function get_case_detail($db,$schema,$case_no,$case_year,$case_type,$location_id){
		$case_detail = $db->prepare("select filing_no,status,dt_of_filing,pet_name,res_name,case_no,case_year,case_type,regis_date,main_case_ia_no from $schema.case_detail where case_type = ? and case_no = ? and case_year = ? and location_code = ?");
		$case_detail->bindParam(1, $case_type, PDO::PARAM_INT);
		$case_detail->bindParam(2, $case_no, PDO::PARAM_INT);
		$case_detail->bindParam(3, $case_year, PDO::PARAM_INT);
		$case_detail->bindParam(4, $location_id, PDO::PARAM_INT);
		$case_detail->execute();
		$case_detail = $case_detail->fetchAll();
		return $case_detail;
	}
	
	function display_date($date){
		return date('d/m/Y',strtotime($date));
	}
	
	function get_short_name($db,$table,$search_column_name,$condtion_column_name,$condtion_column_value){
		$short_name = $db->prepare("select $search_column_name from $table where $condtion_column_name = ? ");
		$short_name->bindParam(1, $condtion_column_value, PDO::PARAM_INT);
		$short_name->execute();
		$short_name = $short_name->fetchColumn();
		return $short_name;
	}
	
	function get_parties($dbonline,$filing_no,$type){
		$parties = $dbonline->prepare("select id,name,email,mobile from e_cases_party where filing_no = ? and party_flag = ?");
		$parties->bindParam(1, $filing_no, PDO::PARAM_STR);
		$parties->bindParam(2, $type, PDO::PARAM_STR);
		$parties->execute();
		$parties = $parties->fetchAll();
		return $parties;
	}
	
	function get_party_by_id($dbonline,$party_id){
		$parties = $dbonline->prepare("select id,name,email,mobile,filing_no,party_address1,party_address2,pin,state_code,district_code,party_org_contact_person from e_cases_party where id = ?");
		$parties->bindParam(1, $party_id, PDO::PARAM_STR);
		$parties->execute();
		$parties = $parties->fetchAll();
		return $parties;
	}
	
	function get_e_reference_no($dbonline,$filing_no){
		$e_reference_no = $dbonline->prepare("select e_reference_no from e_case_detail where filing_no = ? ");
		$e_reference_no->bindParam(1, $filing_no, PDO::PARAM_STR);
		$e_reference_no->execute();
		$e_reference_no = $e_reference_no->fetchColumn();
		return $e_reference_no;
	}
	
	function get_max_party_serial($dbonline,$filing_no,$party_flag){
		$max_party_serial = $dbonline->prepare("select max(party_serial_no) from e_cases_party where filing_no = ? and party_flag = ?");
		$max_party_serial->bindParam(1, $filing_no, PDO::PARAM_STR);
		$max_party_serial->bindParam(2, $party_flag, PDO::PARAM_STR);
		$max_party_serial->execute();
		$max_party_serial = $max_party_serial->fetchColumn();
		return $max_party_serial;
	}

	$data = $_POST;
	$type = (isset($data['type']) && $data['type'] != '')?$data['type']:'';
	$location_id = $_SESSION['location'];
	$user_id=htmlspecialchars($_SESSION['id']);
	if($type != ''){
		if($type == 'search_case'){
			$search_location_data = $data['search_location'];
			$explode_loc = explode('/',$search_location_data);
			$search_location = $explode_loc[0];
			$location_id = $explode_loc[1];
			$case_no = $data['case_no'];
			$case_year = $data['case_year'];
			$case_type = $data['case_type'];
			if(!is_numeric($case_no)||!is_numeric($case_year) ||!is_numeric($case_type) ){
				echo "Invalid Input";
				die;
			}
			$case_detail = get_case_detail($db,$search_location,$case_no,$case_year,$case_type,$location_id);
			if(empty($case_detail)){
				$response = array( 'status' => 0, 'message' => 'Case Not Found');
				echo json_encode($response); die;
			}
			if(!empty($case_detail) && count($case_detail) > 1){
				$response = array( 'status' => 0, 'message' => 'Something went wrong');
				echo json_encode($response); die;
			}
			if(!empty($case_detail) && count($case_detail) == 1){
				$case_detail = array_shift($case_detail);
					 $filing_no = $case_detail['filing_no'];
					 $case_no = $case_detail['case_no'];
					 $case_year = $case_detail['case_year'];
					 $case_type = $case_detail['case_type'];
					
					 //$case_type_array = array(2,3,5,6,7);
					 $get_case_type=$db->prepare("select case_type  from $search_location.case_detail where filing_no=?");
					 $get_case_type->bindParam(1, $filing_no, PDO::PARAM_STR);
					 $get_case_type->execute();
					 $get_case_type = $get_case_type->fetchColumn();
					 
					 $get_court_no=$db->prepare("select court_no  from $search_location.case_proceeding where filing_no=? order by listing_date desc limit 1");
					 $get_court_no->bindParam(1, $filing_no, PDO::PARAM_STR);
					 $get_court_no->execute();
					 $get_court_no = $get_court_no->fetchColumn();
					 if(empty($get_court_no)){
						$get_court_no=$db->prepare("select court_no  from $search_location.case_allocation_temp where filing_no=? order by listing_date desc limit 1");
						 $get_court_no->bindParam(1, $filing_no, PDO::PARAM_STR);
						 $get_court_no->execute();
						 $get_court_no = $get_court_no->fetchColumn();
						 
						 if(empty($get_court_no)){
							$get_court_no=$db->prepare("select first_court_no  from $search_location.scrutiny where filing_no=? limit 1");
						 $get_court_no->bindParam(1, $filing_no, PDO::PARAM_STR);
						 $get_court_no->execute();
						 $get_court_no = $get_court_no->fetchColumn();
						 }
					 
					 }
					 if(empty($get_court_no)){
						$get_court_no = '';
					 }

					/* $query = "select *  from document_upload where filing_no=? and scrutiny=? and display=?  order by documentuploadmodelid";
					$display='1';
					$scrutiny='1';
					$form_status="F";
					$st=$dbonline->prepare($query);
					 $st->bindParam(1, $filing_no, PDO::PARAM_STR);
					 $st->bindParam(2, $scrutiny, PDO::PARAM_STR);
					 $st->bindParam(3, $display, PDO::PARAM_STR);
					 $st->execute();
					 $res = $st->fetchAll(); */
					
					
				?>
				 <input type="hidden" value="<?php echo $filing_no; ?>" id="filing_no" name="filing_no">
				 <input type="hidden" value="<?php echo $case_detail['regis_date']; ?>" id="regis_date" name="regis_date">
				 <input type="hidden" value="<?php echo $case_detail['main_case_ia_no']; ?>" id="main_case_filing_no" name="main_case_filing_no">
				<div class="table-responsive">
				<table id="title" class="table table-hover table-bordered">
					<thead>
						<th>Diary No</th>
						<th>Case No</th>
						<th>Title</th>
						<th>Date Of Filing</th>
						<th>Date Of Registration</th>
						<th>View Doc</th>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $filing_no; ?></td>
							<td><?php echo get_short_name($db,'case_type','short_name','id',$case_type).'/'.$case_no.'/'.$case_year; ?></td>
							<td><?php echo $case_detail['pet_name']."  VS  ".$case_detail['res_name']; ?></td>
							<td><?php echo display_date($case_detail['dt_of_filing']); ?></td>
							<td><?php echo display_date($case_detail['regis_date']); ?></td>
							<td><a  onclick="OpenDMSForm('2','<?php echo $filing_no; ?>','<?php echo $case_detail['pet_name']."  VS  ".$case_detail['res_name']; ?>','<?php echo $case_no; ?>','<?php echo $get_court_no; ?>','')" style="cursor: pointer"> View Docs</a></td>
						</tr>
					</tbody>
				 </table>
				 </div>
				 <!--<div class="table-responsive">
					<table id="title" class="table table-hover table-bordered">
						<thead>
							<th>Sr No</th>
							<th>Uploaded By</th>
							<th>Nature Of Document</th>
							<th>Upload Date</th>
							<th>View Pdf</th>
						</thead>
						<tbody>
						<?php foreach($res as $key=>$rowa)
						{		
							$fil_no=$rowa['filing_no'];
							$sub_doc_type=$rowa['subdoctype'];	
							$document_filed_date=$rowa['document_filed_date'];
							$path =$rowa['fileupload'];    
							$returnfilename =$rowa['returnfilename']; 
							list($returnfilename,$ext)=explode('.',$returnfilename);
							$returnfilename1=$returnfilename;		 
							$stqq = $dbonline->prepare("select e_document_name from e_document_type  where e_document_type=?");
							$stqq->bindParam(1, $sub_doc_type, PDO::PARAM_INT);
							$stqq->execute();		
							$e_document_name_print = $stqq->fetchColumn();
							?>
							<tr>
								<td><?php echo $key+1; ?></td>
								<td><?php echo $rowa['party_name']; ?></td>
								<td><?php echo $rowa['docum_type']; ?></td>
								<td><?php if($document_filed_date =='11/11/1111' OR $document_filed_date =='//'){$document_filed_date="";}else {echo htmlspecialchars(date('d/m/Y',strtotime($document_filed_date)));}?></td>
								<td>
									<a title="<?php echo $e_document_name_print; ?>"  href="javascript::void(0);" onClick="return viewpdf('<?php echo urlencode($path); ?>');" style="cursor: pointer">
										<font color="red" size="6"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></font>
									</a>
								</td>
							</tr>
							<?php 
							}	
							?>
							</tbody>
					</table>
				 </div> -->
				<?php 
			} ?>
			<script>
            function OpenDMSForm(step, filing_no, cause_title, case_no, court_no, item_no) {
                document.getElementById("step").value = step;
                document.getElementById("case_filing_no").value = filing_no;
                document.getElementById("cause_title").value = cause_title;
                document.getElementById("case_no").value = case_no;
                document.getElementById("court_no").value = court_no;
                document.getElementById("item_no").value = item_no;
                document.getElementById("frm_dms").submit();

            }
            </script>

            <form action="https://uat-efiling.gstat.gov.in/dmsgstat/dashboard" method="POST" target="_blank" id="frm_dms">
                <input type="hidden" id="step" name="step" value="" />
                <input type="hidden" id="case_filing_no" name="case_filing_no" value="" />
                <input type="hidden" id="cause_title" name="cause_title" value="" />
                <input type="hidden" id="case_no" name="case_no" value="" />
                <input type="hidden" id="court_no" name="court_no" value="" />
                <input type="hidden" id="item_no" name="item_no" value="" />
            </form>
			
			<!--<form action="http://164.100.59.89/dmsnclat/dashboard" method="POST" target="_blank" id="frm_dms">
                <input type="hidden" id="step" name="step" value="" />
                <input type="hidden" id="case_filing_no" name="case_filing_no" value="" />
                <input type="hidden" id="cause_title" name="cause_title" value="" />
                <input type="hidden" id="case_no" name="case_no" value="" />
                <input type="hidden" id="court_no" name="court_no" value="" />
                <input type="hidden" id="item_no" name="item_no" value="" />
            </form>-->
	<?php	}

	}

}

?>
<script>
$("#state").select2();
$("#district").select2();
</script>

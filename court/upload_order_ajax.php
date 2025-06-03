<?php

//  ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);  
include("../db_inc1.php");
include("../db_inc2.php");
//include '../classes/Editcase.class.php';
include("../master/functions.php");
//$edit_Case_obj = new Editcase();
date_default_timezone_set("Asia/Kolkata");
$schema = htmlspecialchars($_SESSION['schema_name']);
$location_name = strtoupper($schema);
$location_id = $_SESSION['location'];
$user_id = $_SESSION['id'];
$username = $_SESSION['user_actual_name'];
$user_court = $_SESSION['user_court'];
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
	function get_case_detail($db, $schema, $case_no, $case_year, $case_type, $location_id, $main_case_filing_no = '')
	{
		if (!empty($main_case_filing_no)) {
			$query = "select filing_no,status,dt_of_filing,pet_name,res_name,case_no,case_year,case_type,regis_date,main_case_ia_no from $schema.case_detail where case_type = ? and case_no = ? and case_year = ? and location_code = ? and main_case_ia_no = ?";
		} else {
			$query = "select filing_no,status,dt_of_filing,pet_name,res_name,case_no,case_year,case_type,regis_date,main_case_ia_no from $schema.case_detail where case_type = ? and case_no = ? and case_year = ? and location_code = ?";
		}
		$case_detail = $db->prepare($query);
		$case_detail->bindParam(1, $case_type, PDO::PARAM_INT);
		$case_detail->bindParam(2, $case_no, PDO::PARAM_INT);
		$case_detail->bindParam(3, $case_year, PDO::PARAM_INT);
		$case_detail->bindParam(4, $location_id, PDO::PARAM_INT);
		if (!empty($main_case_filing_no)) {
			$case_detail->bindParam(5, $main_case_filing_no, PDO::PARAM_STR);
		}
		$case_detail->execute();
		$case_detail = $case_detail->fetchAll();
		return $case_detail;
	}

	function display_date($date)
	{
		return date('d/m/Y', strtotime($date));
	}

	function get_short_name($db, $table, $search_column_name, $condtion_column_name, $condtion_column_value)
	{
		$short_name = $db->prepare("select $search_column_name from $table where $condtion_column_name = ? ");
		$short_name->bindParam(1, $condtion_column_value, PDO::PARAM_INT);
		$short_name->execute();
		$short_name = $short_name->fetchColumn();
		return $short_name;
	}

	function get_orders($db, $schema, $filing_no, $order_date, $flag)
	{
		if (empty($order_date))
			$query = "select item_no,filing_no,order_date,order_upload_date,pdf_path,order_type,court_no from $schema.order_daily where filing_no = ? and flag = ? order by order_date desc";
		else
			$query = "select item_no,filing_no,order_date,order_upload_date,pdf_path,order_type,court_no from $schema.order_daily where filing_no = ? and flag = ? and order_date = ? order by order_date desc";
		$orders = $db->prepare($query);
		$orders->bindParam(1, $filing_no, PDO::PARAM_INT);
		$orders->bindParam(2, $flag, PDO::PARAM_INT);
		if (!empty($order_date))
			$orders->bindParam(3, $order_date, PDO::PARAM_INT);
		$orders->execute();
		$orders = $orders->fetchAll();
		return $orders;
	}

	function bench_judges($db, $schema, $judge_code = '')
	{
		//$display = 'TRUE';
		if ($judge_code == '')
			$query = "select * from $schema.master_judge order by judge_code";
		else
			$query = "select * from $schema.master_judge where judge_code = '$judge_code'";
		$bench_nature = $db->prepare($query);
		//$bench_nature->bindParam(1, $display, PDO::PARAM_INT);
		$bench_nature->execute();
		$bench_nature = $bench_nature->fetchAll();
		return $bench_nature;
	}

	function get_coram($db, $schemas, $listing_date, $court_no, $bench_nature, $bench_no)
	{
		$query = "select judge_code from $schemas.bench_judge where from_list_date = ? and court_no = ? and bench_nature = ? and bench_no = ?";
		$coram = $db->prepare($query);
		$coram->bindParam(1, $listing_date, PDO::PARAM_STR);
		$coram->bindParam(2, $court_no, PDO::PARAM_STR);
		$coram->bindParam(3, $bench_nature, PDO::PARAM_STR);
		$coram->bindParam(4, $bench_no, PDO::PARAM_STR);
		$coram->execute();
		$coram = $coram->fetchAll();
		return $coram;
	}

	function get_disposal_data($db, $schema, $filing_no)
	{
		$query = "select * from $schema.case_disposal where filing_no = ? order by disposal_date desc limit 1";
		$disposal_data = $db->prepare($query);
		$disposal_data->bindParam(1, $filing_no, PDO::PARAM_STR);
		$disposal_data->execute();
		$disposal_data = $disposal_data->fetch();
		return $disposal_data;
	}

	function get_restoration_reasons($db)
	{
		$display = TRUE;
		$query = "select * from restore_case_type where display = ? order by id";
		$restiration_reason = $db->prepare($query);
		$restiration_reason->bindParam(1, $display, PDO::PARAM_BOOL);
		$restiration_reason->execute();
		$restiration_reason = $restiration_reason->fetchAll();
		return $restiration_reason;
	}

	function get_case_detail_by_filing_no($db, $schema, $filing_no)
	{
		$query = "select * from $schema.case_detail where filing_no = ?";
		$case_detail = $db->prepare($query);
		$case_detail->bindParam(1, $filing_no, PDO::PARAM_STR);
		$case_detail->execute();
		$case_detail = $case_detail->fetch();
		return $case_detail;
	}

	function get_listing_detail($db, $schema, $filing_no, $listing_date_new)
	{
		$query = "select * from $schema.case_allocation where filing_no = ? and listing_date = ?";
		$case_detail = $db->prepare($query);
		$case_detail->bindParam(1, $filing_no, PDO::PARAM_STR);
		$case_detail->bindParam(2, $listing_date_new, PDO::PARAM_STR);
		$case_detail->execute();
		$case_detail = $case_detail->fetch();
		return $case_detail;
	}

	function last_proceeding_info($db, $schema, $filing_no)
	{
		$query = "select * from $schema.case_proceeding where filing_no = ? order by listing_date desc limit 1";
		$proceeding_info = $db->prepare($query);
		$proceeding_info->bindParam(1, $filing_no, PDO::PARAM_STR);
		$proceeding_info->execute();
		$proceeding_info = $proceeding_info->fetch();
		return $proceeding_info;
	}

	function last_listing_info($db, $schema, $filing_no){
		$query = "select listing_date,bench_no from $schema.case_allocation_temp where filing_no = ? order by listing_date desc limit 1";
		$proceeding_info = $db->prepare($query);
		$proceeding_info->bindParam(1, $filing_no, PDO::PARAM_STR);
		$proceeding_info->execute();
		$proceeding_info = $proceeding_info->fetch();
		return $proceeding_info;
	}  

	function get_judge_by_bench($db,$schema,$bench_listing_date,$listing_bench_no){
		$query = "select STRING_AGG(judge_code::varchar,',') as judges from $schema.bench_judge where from_list_date = ? and bench_no = ?";
		$proceeding_info = $db->prepare($query);
		$proceeding_info->bindParam(1, $bench_listing_date, PDO::PARAM_STR);
		$proceeding_info->bindParam(2, $listing_bench_no, PDO::PARAM_STR);
		$proceeding_info->execute();
		$proceeding_info = $proceeding_info->fetchColumn();
		return $proceeding_info;
	}

	function already_recused($db,$schema,$filing_no){
		$query = "select STRING_AGG(judge_code::varchar,',') as judges from $schema.recused_case_judge where filing_no = ? and is_deleted = 0";
		$proceeding_info = $db->prepare($query);
		$proceeding_info->bindParam(1, $filing_no, PDO::PARAM_STR);
		$proceeding_info->execute();
		$proceeding_info = $proceeding_info->fetchColumn();
		return $proceeding_info;
	}

	$data = $_POST;
	$type = $data['type'];
	if ($type == 'search_case') {
		$case_no =	htmlspecialchars($data['case_no'], ENT_QUOTES, 'UTF-8');
		$case_year =	htmlspecialchars($data['case_year'], ENT_QUOTES, 'UTF-8');
		$case_type = htmlspecialchars($data['case_type'], ENT_QUOTES, 'UTF-8');
		// $case_no = $data['case_no'];
		// $case_year = $data['case_year'];
		// $case_type = $data['case_type'];
		$case_detail = get_case_detail($db, $schema, $case_no, $case_year, $case_type, $location_id);
		if (empty($case_detail)) {
			$response = array('status' => 0, 'message' => 'Case Not Found');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) > 1) {
			$response = array('status' => 0, 'message' => 'Something went wrong');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) == 1) {
			$case_detail = array_shift($case_detail);
			/* if($case_detail['status'] == 'D'){
					$response = array( 'status' => 0, 'message' => 'Case is disposed');
					echo json_encode($response); die;
				}else{ */
			$filing_no = $case_detail['filing_no'];
			$case_no = $case_detail['case_no'];
			$case_year = $case_detail['case_year'];
			$case_type = $case_detail['case_type'];
?>
			<input type="hidden" value="<?php echo $filing_no; ?>" id="filing_no" name="filing_no">
			<input type="hidden" value="<?php echo $case_detail['regis_date']; ?>" id="regis_date" name="regis_date">
			<input type="hidden" value="<?php echo $case_detail['ia_ma_filing_no']; ?>" id="main_case_filing_no" name="main_case_filing_no">
			<table id="title" class="table table-hover table-bordered">
				<thead>
					<th>Diary No</th>
					<th>Case No</th>
					<th>Title</th>
					<th>Date Of Filing</th>
					<th>Status</th>
					<th>Select Case</th>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $filing_no; ?></td>
						<td><?php echo get_short_name($db, 'case_type', 'short_name', 'id', $case_type) . '/' . $case_no . '(' . get_short_name($db, "$schema.bench_location", 'short_name', 'city_id', $location_id) . ")/" . $case_year; ?></td>
						<td><?php echo $case_detail['pet_name'] . "  VS  " . $case_detail['res_name']; ?></td>
						<td><?php echo display_date($case_detail['dt_of_filing']); ?></td>
						<td><?php echo ($case_detail['status'] == 'D') ? 'Disposed' : 'Pending'; ?></td>
						<td><input type="checkbox" name="is_case" id="is_case" onClick="get_basic_info(this);" style="background-color:#ccc;" /></td>
					</tr>
				</tbody>
			</table>
		<?php
			//}
		}
	}

	if ($type == 'order_form') {
		$case_no = $data['case_no'];
		$case_year = $data['case_year'];
		$case_type = $data['case_type'];
		if (!is_numeric($case_no) || !is_numeric($case_year) || !is_numeric($case_type)) {
			echo "Invalid Input";
			die;
		}
		$case_detail = get_case_detail($db, $schema, $case_no, $case_year, $case_type, $location_id);
		if (empty($case_detail)) {
			$response = array('status' => 0, 'message' => 'Case Not Found');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) > 1) {
			$response = array('status' => 0, 'message' => 'Something went wrong');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) == 1) {
			$case_detail = array_shift($case_detail);
		?>
			<div style="display:none;" id="judge_clone">
				<div class="form-group">
					<label class="control-label col-sm-3" for="email">Judge :</label>
					<div class="col-sm-9">
						<select class="form-control" id="coram_judge" name="coram_judge[] ">
							<?php
							$bench_nature = bench_judges($db, $schema);
							foreach ($bench_nature as $key => $value) { ?>
								<option value="<?php echo $value['judge_code']; ?>"><?php echo $value['judge_name'] ?></option>
							<?php }
							?>
						</select>
					</div>
				</div>
			</div>
			<form class="form-horizontal" method="POST" enctype="multipart/form-data" action="upload_order_action.php" id="search_form">
				<div class="row" id="benches_new" style="padding:25px;"></div>
				<input type="hidden" value="<?php echo $case_type; ?>" name="case_type_order">
				<input type="hidden" value="<?php echo $case_no; ?>" name="case_no_order">
				<input type="hidden" value="<?php echo $case_year; ?>" name="case_year_order">
				<!--<div class="row hide" id='custom_coram_div'>
							<div class="col-sm-4 col-lg-4">
								<div class="form-group">
								  <label class="control-label col-sm-11" for="email">Do you want to enter custom coram : <input type="checkbox" name="custom_coram" id="custom_coram" onClick="set_custom_coram(this);" style="background-color:#ccc;"/></label>
								  <div class="col-sm-1 col-lg-1">
									
								  </div>
								</div>
							</div>
						
						<div class="col-sm-8" id="enter_coram_count">
							
						</div>
						</div>
						<div class="row" id="coram">
							
							
						</div>
						<br/>-->
				<div class="row">
					<div class="col-sm-4 col-lg-4">
						<div class="form-group">
							<label class="control-label col-sm-4" for="email">Listing Date:</label>
							<div class="col-sm-8 col-lg-8">
								<input type="text" class="form-control datepicker" onChange="return get_child_or_connected(this.value,'<?php echo $case_detail['filing_no']; ?>');" autocomplete="off" id="order_date" placeholder="Enter order Date" name="order_date" required>
							</div>
						</div>
					</div>
					<div class="col-sm-4 col-lg-4">
						<div class="form-group">
							<label class="control-label col-sm-4" for="email">Court No:</label>
							<div class="col-sm-8">
								<select class='form-control' name='court_no' style="width:257px;">
									<option value="">Select</option>
									<?php
									echo "select * from $schema.court where court_no = $user_court order by court_no asc";
									$st = $db->prepare("select * from $schema.court where court_no = $user_court order by court_no asc");
									$st->execute();
									while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										$reg_court_no = $row['court_no'];
									?>
										<option value="<?php echo htmlspecialchars($reg_court_no); ?>">
											<?php echo htmlspecialchars($row['display_court_text']); ?>
										</option>
									<?php
									}

									?>
								</select>
							</div>
						</div>
					</div>
					<!--<div class="col-sm-4 col-lg-4">
						<div class="form-group">
							<label class="control-label col-sm-4" for="email">Upload Date:</label>
							<div class="col-sm-8 col-lg-8">
								<input type="text" class="form-control datepicker" autocomplete="off" id="upload_date" placeholder="Enter Upload Date" name="upload_date">
							</div>
						</div>
					</div>-->
				</div>
				<div class="row">
					<div class="col-sm-4 col-lg-4">
						<div class="form-group">
							<label class="control-label col-sm-3" for="email">Upload Order:</label>
							<div class="col-sm-9 col-lg-9">
								<input type="file" class="form-control" name="upload_order" id="upload_order" onclick="fileValidation(event)">
							</div>
						</div>
					</div>
					<div class="col-sm-4 col-lg-4">
						<div class="form-group">
							<label class="control-label col-sm-3" for="email">Order Type:</label>
							<div class="col-sm-9 col-lg-9">
								<select id="order_type" name="order_type" class="form-control" style="width:257px;">
									<option value='D'>Daily Order</option>
									<option value='DC'>Daily Order (Corrected)</option>
									<option value='J'>Final Order /Judgement</option>
									<option value='JC'>Final Order /Judgement (Corrected)</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-4 col-lg-4">
						<div class="form-group">
							<label class="control-label col-sm-4" for="email">Order/Judgement Authored by:</label>
							<div class="col-sm-8">
								<select class='form-control' name='author_by' style="width:257px;">
									<option value="0">Select</option>
									<?php
									$st = $db->prepare("select * from $schema.master_judge where display = true order by judge_desg_code;");
									$st->execute();
									while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										$judge_code = $row['judge_code'];
									?>
										<option value="<?php echo htmlspecialchars($judge_code); ?>">
											<?php echo htmlspecialchars($row['judge_name']); ?>
										</option>
									<?php
									}

									?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div id="proceeding_info">

				</div>
				<div class="row" id="child_or_connected" style="margin-left:30px;">

				</div>

				<div class="row">
					<center>
						<input type="submit" name="submit" id="submit" value="Upload" class="btn btn-sm btn-success">
					</center>
				</div>

			</form>

		<?php	}
	}

	if ($type == 'search_order') {
		$case_no = $data['case_no'];
		$case_year = $data['case_year'];
		$case_type = $data['case_type'];

		if (!is_numeric($case_no) || !is_numeric($case_year) || !is_numeric($case_type)) {
			echo "Invalid Input";
			die;
		}
		//$order_date = $data['order_date'];
		$order_date = '';
		if (!empty($order_date)) {
			list($d, $m, $y) = explode('/', $order_date);
			$order_date = "$y-$m-$d";
		}
		$case_detail = get_case_detail($db, $schema, $case_no, $case_year, $case_type, $location_id);
		if (empty($case_detail)) {
			$response = array('status' => 0, 'message' => 'Case Not Found');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) > 1) {
			$response = array('status' => 0, 'message' => 'Something went wrong');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) == 1) {
			$case_detail = array_shift($case_detail);
			/* if($case_detail['status'] == 'D'){
					$response = array( 'status' => 0, 'message' => 'Case is disposed');
					echo json_encode($response); die;
				}else{ */
			$filing_no = $case_detail['filing_no'];
			$case_no = $case_detail['case_no'];
			$case_year = $case_detail['case_year'];
			$case_type = $case_detail['case_type'];
			if (!is_numeric($case_no) || !is_numeric($case_year) || !is_numeric($case_type) || !is_numeric($filing_no)) {
				echo "Invalid Input";
				die;
			}
			$orders = get_orders($db, $schema, $filing_no, $order_date, $flag = 'Y');
		?>
			<div class='table-responsive'>
				<table id="title" class="table table-hover table-bordered">
					<thead>
						<th>SN</th>
						<th>Filing No</th>
						<th>Case No</th>
						<th>Order Date</th>
						<th>Order Type</th>
						<th>Action</th>
					</thead>
					<tbody>
						<?php
						if (!empty($orders)) {
							foreach ($orders as $key => $order) {
								if ($order['order_type'] == 'D') {
									$order_type = 'Daily Order';
								}
								if ($order['order_type'] == 'DC') {
									$order_type = 'Daily Order (Corrected';
								}
								if ($order['order_type'] == 'J') {
									$order_type = 'Final Order /Judgement';
								}
								if ($order['order_type'] == 'JC') {
									$order_type = 'Final Order /Judgement (Corrected)';
								}
						?>
								<tr id="item_no_<?php echo $order['item_no'] ?>">
									<td><?php echo ($key + 1); ?></td>
									<td><?php echo $filing_no; ?></td>
									<td><?php echo get_short_name($db, 'case_type', 'short_name', 'id', $case_type) . '/' . $case_no . '(' . get_short_name($db, 'mater_location_city', 'short_name', 'city_id', $location_id) . ")/" . $case_year; ?></td>
									<td><?php echo display_date($order['order_date']); ?></td>
									<td id="order_type_<?php echo $order['item_no']; ?>"><?php echo $order_type ?></td>
									<td>
										<a href="../scrutiny/readpdf.php?path=<?php  echo urlencode($order['pdf_path']); ?>" target="_blank"  style="cursor: pointer"> View</a>
									</td>
								</tr>
							<?php }
						} else { ?>
							<tr>
								<td colspan='7' style='color:red;text-align:center;'>No Record Found</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php
			//}
		}
	}

	if ($type == 'search_order_to_correct') {
		$case_no = $data['case_no'];
		$case_year = $data['case_year'];
		$case_type = $data['case_type'];
		$order_date = $data['order_date'];
		if (!is_numeric($case_no) || !is_numeric($case_year) || !is_numeric($case_type)) {
			echo "Invalid Input";
			die;
		}
		if (!empty($order_date)) {
			list($d, $m, $y) = explode('/', $order_date);
			$order_date = "$y-$m-$d";
		}
		$case_detail = get_case_detail($db, $schema, $case_no, $case_year, $case_type, $location_id);
		if (empty($case_detail)) {
			$response = array('status' => 0, 'message' => 'Case Not Found');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) > 1) {
			$response = array('status' => 0, 'message' => 'Something went wrong');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) == 1) {
			$case_detail = array_shift($case_detail);
			/* if($case_detail['status'] == 'D'){
					$response = array( 'status' => 0, 'message' => 'Case is disposed');
					echo json_encode($response); die;
				}else{ */
			$filing_no = $case_detail['filing_no'];
			$case_no = $case_detail['case_no'];
			$case_year = $case_detail['case_year'];
			$case_type = $case_detail['case_type'];
			if (!is_numeric($case_no) || !is_numeric($case_year) || !is_numeric($case_type) || !is_numeric($filing_no)) {
				echo "Invalid Input";
				die;
			}
			$orders = get_orders($db, $schema, $filing_no, $order_date, $flag = 'Y');
		?>
			<div class='table-responsive'>
				<table id="title" class="table table-hover table-bordered">
					<thead>
						<th>SN</th>
						<th>Filing No</th>
						<th>Case No</th>
						<th>Order Date</th>
						<th>Upload Date</th>
						<th>Order Type</th>
						<th>Order</th>
						<th>Action</th>
					</thead>
					<tbody>
						<?php
						if (!empty($orders)) {
							foreach ($orders as $key => $order) {
								$order_type = ($order['order_type'] == 'D') ? 'Detailed' : 'Short';
								$order_type_change = ($order['order_type'] == 'D') ? 'Short' : 'Detailed';
								$change_to = ($order['order_type'] == 'D') ? 'S' : 'D';
						?>
								<tr id="item_no_<?php echo $order['item_no'] ?>">
									<td><?php echo ($key + 1); ?></td>
									<td><?php echo $filing_no; ?></td>
									<td><?php echo get_short_name($db, 'case_type', 'short_name', 'id', $case_type) . '/' . $case_no . '(' . get_short_name($db, 'mater_location_city', 'short_name', 'city_id', $location_id) . ")/" . $case_year; ?></td>
									<td><?php echo display_date($order['order_date']); ?></td>
									<td><?php echo ($order['order_upload_date'] == '9999-01-01' || empty($order['order_upload_date'])) ? '' : display_date($order['order_upload_date']); ?></td>
									<td id="order_type_<?php echo $order['item_no']; ?>"><?php echo $order_type ?></td>
									<td>
										<a href="javascript::void(0)" onClick="return view_order('<?php echo urlencode($order['pdf_path']); ?>');" style="cursor: pointer"> PDF</a>
									</td>
									<td>
										<button type="button" onclick="fn_final_order('<?php echo $order['item_no']; ?>','<?php echo $filing_no; ?>','<?php echo $order['court_no']; ?>','<?php echo $order['order_date']; ?>')" class="btn btn-sm btn-success">Upload Corrected Order</button>
									</td>
								</tr>
							<?php }
						} else { ?>
							<tr>
								<td colspan='7' style='color:red;text-align:center;'>No Record Found</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php
			//}
		}
	}

	if ($type == 'search_order_to_update') {
		if ($user_id != '77' && $user_id != '194') {
			$response = array('status' => 0, 'message' => 'unauthorised access');
			echo json_encode($response);
			die;
		}
		$explode_loc = explode('/', htmlspecialchars($data['location_info']));
		$schema_name = $explode_loc[0];
		$location_id = $explode_loc[1];
		$case_no = htmlspecialchars($data['case_no']);
		$case_year = htmlspecialchars($data['case_year']);
		$case_type = htmlspecialchars($data['case_type']);
		//$order_date = $data['order_date'];
		$order_date = '';
		if (!empty($order_date)) {
			list($d, $m, $y) = explode('/', $order_date);
			$order_date = "$y-$m-$d";
		}
		$case_detail = get_case_detail($db, $schema_name, $case_no, $case_year, $case_type, $location_id);
		if (empty($case_detail)) {
			$response = array('status' => 0, 'message' => 'Case Not Found');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) > 1) {
			$response = array('status' => 0, 'message' => 'Something went wrong');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) == 1) {
			$case_detail = array_shift($case_detail);
			/* if($case_detail['status'] == 'D'){
					$response = array( 'status' => 0, 'message' => 'Case is disposed');
					echo json_encode($response); die;
				}else{ */
			$filing_no = $case_detail['filing_no'];
			$case_no = $case_detail['case_no'];
			$case_year = $case_detail['case_year'];
			$case_type = $case_detail['case_type'];
			$orders = get_orders($db, $schema_name, $filing_no, $order_date, $flag = 'Y');
		?>
			<div class='table-responsive'>
				<table id="title" class="table table-hover table-bordered">
					<thead>
						<th>SN</th>
						<th>Filing No</th>
						<th>Case No</th>
						<th>Order Date</th>
						<th>Order Type</th>
						<th>Order</th>
						<?php if ($user_id == '77' || $user_id == '194') { ?>
							<th>Action</th>
						<?php } ?>
					</thead>
					<tbody>
						<?php
						if (!empty($orders)) {
							foreach ($orders as $key => $order) {
								if ($order['order_type'] == 'D') {
									$order_type = 'Daily Order';
								}
								if ($order['order_type'] == 'DC') {
									$order_type = 'Daily Order (Corrected';
								}
								if ($order['order_type'] == 'J') {
									$order_type = 'Final Order /Judgement';
								}
								if ($order['order_type'] == 'JC') {
									$order_type = 'Final Order /Judgement (Corrected)';
								}
						?>
								<tr id="item_no_<?php echo $order['item_no'] ?>">
									<td><?php echo ($key + 1); ?></td>
									<td><?php echo $filing_no; ?></td>
									<td><?php echo get_short_name($db, 'case_type', 'short_name', 'id', $case_type) . '/' . $case_no . '(' . get_short_name($db, 'mater_location_city', 'short_name', 'city_id', $location_id) . ")/" . $case_year; ?></td>
									<td><?php echo display_date($order['order_date']); ?></td>
									<td id="order_type_<?php echo $order['item_no']; ?>"><?php echo $order_type ?></td>
									<td>
										<a href="javascript::void(0)" onClick="return view_order('<?php echo urlencode($order['pdf_path']); ?>');" style="cursor: pointer"> PDF</a>
									</td>
									<?php if ($user_id == '77' || $user_id == '194') { ?>
										<td>
											<button type='button' id='edit_order_<?php echo $order['item_no']; ?>' class='btn btn-primary btn-sm' onClick="return edit_order_dialog('<?php echo $filing_no; ?>','<?php echo $order['item_no']; ?>')">Edit</button>
											<button type='button' id='delete_order_<?php echo $order['item_no']; ?>' class='btn btn-danger btn-sm' onClick="return delete_order_dialog('<?php echo $filing_no; ?>','<?php echo $order['item_no']; ?>')">Remove</button>
										</td>
									<?php }								?>
								</tr>
							<?php }
						} else { ?>
							<tr>
								<td colspan='7' style='color:red;text-align:center;'>No Record Found</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php
			//}
		}
	}

	if ($type == 'delete_order_dialog') {
		$display = true;
		$filing_no = htmlspecialchars($data['filing_no']);
		$item_no = htmlspecialchars($data['item_no']);
		$dtl_type = $db->prepare("select * from order_deletion_updation_catg where display = ? order by id");
		$dtl_type->bindParam(1, $display, PDO::PARAM_BOOL);
		$dtl_type->execute();
		$dtl_type = $dtl_type->fetchAll();
		$token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		?>
		<form method='POST' id='delete_order_form'>
			<input type="hidden" value='<?php echo $token; ?>' name='csrf_token'>
			<input type="hidden" value='<?php echo $filing_no; ?>' name='filing_no'>
			<input type="hidden" value='<?php echo $item_no; ?>' name='item_no'>
			<input type="hidden" value='delete_order' name='type'>
			<div class="form-group">
				<label>*Deletion Reason : </label>
				<select name='deletion_type' id='deletion_type_<?php echo $item_no; ?>' required>
					<option value=''>*Deletion Reason</option>
					<?php foreach ($dtl_type as $k => $val) { ?>
						<option value='<?php echo $val['id']; ?>'><?php echo $val['delete_type']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label>*Details: </label>
				<textarea cols='30' rows='2' name='to_be_delete' id='to_be_delete' required></textarea>
			</div>
			<input type="submit" value='Delete' name='delete_order_btn' id='delete_order_btn' class='btn btn-danger btn-sm'>
		</form>
	<?php
		die;
	}


	if ($type == 'edit_order_dialog') {
		$display = true;
		$explode_loc = explode('/', htmlspecialchars($data['location_info']));
		$schema_name = $explode_loc[0];
		$location_id = $explode_loc[1];
		$filing_no = htmlspecialchars($data['filing_no']);
		$item_no = htmlspecialchars($data['item_no']);
		$dtl_type = $db->prepare("select * from order_deletion_updation_catg where display = ? order by id");
		$dtl_type->bindParam(1, $display, PDO::PARAM_BOOL);
		$dtl_type->execute();
		$dtl_type = $dtl_type->fetchAll();
		$token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		$order_detail = $db->prepare("select item_no,order_date,bench_no,court_no,bench_nature,pdf_path,order_type,entry_date,order_upload_date,judge_codes,deleted_at,author_by from $schema_name.order_daily where item_no = ? and filing_no = ?");
		$order_detail->bindParam(1, $item_no, PDO::PARAM_BOOL);
		$order_detail->bindParam(2, $filing_no, PDO::PARAM_BOOL);
		$order_detail->execute();
		$order_detail = $order_detail->fetch();
		$current_order_date = $order_detail['order_date'];
		$order_type = $order_detail['order_type'];
		$bench_no = $order_detail['bench_no'];
		$author_by = $order_detail['author_by'];
		list($order_year, $order_month, $order_dt) = explode('-', $current_order_date);
		$order_date_show = "$order_dt/$order_month/$order_year";
		//echo "<pre>"; print_r($order_detail);
	?>
		<form method='POST' id='update_order_form'>
			<div id='benches'>

			</div>
			<input type="hidden" value='<?php echo $token; ?>' name='csrf_token'>
			<input type="hidden" value='<?php echo $filing_no; ?>' name='filing_no'>
			<input type="hidden" value='<?php echo $item_no; ?>' name='item_no'>
			<input type="hidden" value='update_order' name='type'>
			<div class="form-inline" id="search_form">

				<div class="form-group">
					<label>*Order Date : </label>
					<input type="text" id="order_date" name="order_date" value='<?php echo $order_date_show;  ?>' class="datepicker" required onchange="return getbench(this.value);" size="8" autocomplete="off" maxlength="10" />
				</div>
				<div class="form-group">
					<label>Order Type: </label>
					<select name='order_type' id='order_type<?php echo $item_no; ?>'>
						<option value='D' <?php echo ($order_type == 'D') ? 'selected' : ''; ?>>Daily Order</option>
						<option value='DC' <?php echo ($order_type == 'DC') ? 'selected' : ''; ?>>Daily Order (Corrected)</option>
						<option value='J' <?php echo ($order_type == 'J') ? 'selected' : ''; ?>>Final Order /Judgement</option>
						<option value='JC' <?php echo ($order_type == 'JC') ? 'selected' : ''; ?>>Final Order /Judgement (Corrected)</option>
					</select>
				</div>

				<div class="form-group">
					<label>*Updation Reason: </label>
					<select name='updation_type' id='updation_type_<?php echo $item_no; ?>' required>
						<option value=''>Select Updation Type</option>
						<?php foreach ($dtl_type as $k => $val) { ?>
							<option value='<?php echo $val['id']; ?>'><?php echo $val['delete_type']; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>*To Be Update: </label>
					<textarea cols='30' rows='2' name='to_be_update' id='to_be_update' required></textarea>
				</div>
				<div class="form-group">
					<label>Coram : </label>
					<?php
					$qry11 = $db->prepare("select *  from $schema_name.bench_judge where bench_no=? and 
						from_list_date=?");
					$qry11->bindParam(1, $bench_no, PDO::PARAM_STR);
					$qry11->bindParam(2, $current_order_date, PDO::PARAM_STR);
					$qry11->execute();
					$alljudge = $qry11->fetchAll();
					$count_judge = count($alljudge);
					if ($count_judge > 0) {
						foreach ($alljudge as $row2) {
							$cnt++;

							//if($cnt >1){ echo "&";}
							$jcode = $row2['judge_code'];
							$sql1 = "select judge_name from $schema_name.master_judge where judge_code =? ";
							$sth = $db->prepare($sql1);
							$sth->bindParam(1, $jcode, PDO::PARAM_STR);
							$sth->execute();
							$judge = $sth->fetchColumn();
							echo htmlspecialchars($judge) . ",";
					?>
					<?php
						}
					} else {
						echo "Coram not selected";
					}					?>
				</div>
				<div class="row">
					<div class="col-sm-4 col-lg-4">
						<div class="form-group">
							<label class="control-label col-sm-4" for="email">Order/Judgement Authored by:</label>
							<div class="col-sm-8">
								<select class='form-control' name='author_by' style="width:257px;">
									<option value="0">Select</option>
									<?php
									$st = $db->prepare("select * from $schema.master_judge where display = true order by judge_desg_code;");
									$st->execute();
									while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										$judge_code = $row['judge_code'];
									?>
										<option value="<?php echo htmlspecialchars($judge_code); ?>" <?php echo ($author_by == $judge_code) ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($row['judge_name']); ?>
										</option>
									<?php
									}

									?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<center><input type="submit" value='Update' name='update_order_btn' id='update_order_btn' class='btn btn-danger btn-sm'></center>
			</div>
			</div>
		</form>
		<?php
		die;
	}

	if ($type == 'delete_order') {
		try {
			$db->beginTransaction();
			$response = array();
			$explode_loc = explode('/', htmlspecialchars($data['location_info']));
			$schemas = $explode_loc[0];
			$location_id = $explode_loc[1];
			$token = htmlspecialchars($_POST['csrf_token']);
			$filing_no = htmlspecialchars($_POST['filing_no']);
			$item_no = htmlspecialchars($_POST['item_no']);
			$deletion_type = htmlspecialchars($_POST['deletion_type']);
			$username = htmlspecialchars($_SESSION['user_actual_name']);
			$ip = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
			$to_be_delete = trim(htmlspecialchars($data['to_be_delete']));
			if ($token != $_SESSION['csrf_token']) {
				$response['status'] = 0;
				$response['message'] = 'Token Mismatch';
				echo json_encode($response);
				die;
			}
			if ($deletion_type == '') {
				$response['status'] = 0;
				$response['message'] = 'Please select deletion or updataion type';
				echo json_encode($response);
				die;
			}

			if ($to_be_delete == '') {
				$response['status'] = 0;
				$response['message'] = 'Please enter deletion details';
				echo json_encode($response);
				die;
			}

			$order_info = $db->prepare("select order_date from $schemas.order_daily where filing_no =? and item_no = ?");
			$order_info->bindParam(1, $filing_no, PDO::PARAM_BOOL);
			$order_info->bindParam(2, $item_no, PDO::PARAM_BOOL);
			$order_info->execute();
			$order_info = $order_info->fetchAll();
			if (!empty($order_info)) {
				$operation = 'D';
				$order_info = array_shift($order_info);
				$order_date = $order_info['order_date'];
				$order_log = $db->prepare("insert into $schemas.order_logs (filing_no,order_id,order_date,delete_update_type,entry_date,deleted_at,user_id,username,ip_address,operation,to_be_update) values 
												(?,?,?,?,now(),now(),?,?,?,?,?)");
				$order_log->bindParam(1, $filing_no, PDO::PARAM_STR);
				$order_log->bindParam(2, $item_no, PDO::PARAM_STR);
				$order_log->bindParam(3, $order_date, PDO::PARAM_STR);
				$order_log->bindParam(4, $deletion_type, PDO::PARAM_STR);
				$order_log->bindParam(5, $user_id, PDO::PARAM_STR);
				$order_log->bindParam(6, $username, PDO::PARAM_STR);
				$order_log->bindParam(7, $ip, PDO::PARAM_STR);
				$order_log->bindParam(8, $operation, PDO::PARAM_STR);
				$order_log->bindParam(9, $to_be_delete, PDO::PARAM_STR);
				$save_log = $order_log->execute();

				$order_his_log = $db->prepare("insert into $schemas.order_daily_his (select * from $schemas.order_daily where filing_no = ? and item_no = ?)");
				$order_his_log->bindParam(1, $filing_no, PDO::PARAM_STR);
				$order_his_log->bindParam(2, $item_no, PDO::PARAM_STR);
				$order_history = $order_his_log->execute();

				if ($order_history && $save_log) {
					$delete_order = $db->prepare("delete from $schemas.order_daily where filing_no = ? and item_no = ?");
					$delete_order->bindParam(1, $filing_no, PDO::PARAM_STR);
					$delete_order->bindParam(2, $item_no, PDO::PARAM_STR);
					$is_deleted = $delete_order->execute();
				}

				if ($is_deleted) {
					$response['status'] = 1;
					$response['message'] = 'Order deleted';
				} else {
					$response['status'] = 1;
					$response['message'] = 'something went wrong!';
					echo json_encode($response);
					die;
				}
			} else {
				$response['status'] = 0;
				$response['message'] = 'data not found';
				echo json_encode($response);
				die;
			}
			$db->commit();
			echo json_encode($response);
			die;
		} catch (Exception $e) {
			$db->rollBack();
			$response = array(
				'status' => 0,
				'message' => 'some error occurred.'
			);
			echo json_encode($response);
			die;
		}
	}


	if ($type == 'update_order') {
		try {
			//echo "<pre>"; print_r($data); die;
			$db->beginTransaction();
			$response = array();
			$explode_loc = explode('/', htmlspecialchars($data['location_info']));
			$schemas = $explode_loc[0];
			$location_id = $explode_loc[1];
			$token = htmlspecialchars($_POST['csrf_token']);
			$filing_no = htmlspecialchars($_POST['filing_no']);
			$item_no = htmlspecialchars($_POST['item_no']);
			$updation_type = htmlspecialchars($_POST['updation_type']);
			$username = htmlspecialchars($_SESSION['user_actual_name']);
			$ip = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
			$bench_no = (isset($data['bench_no'])) ? htmlspecialchars($data['bench_no']) : 0;
			$new_order_date_ex = htmlspecialchars($data['order_date']);
			list($day, $month, $year) = explode('/', $new_order_date_ex);
			$new_order_date = "$year-$month-$day";
			$new_order_type = htmlspecialchars($data['order_type']);
			$to_be_update = trim(htmlspecialchars($data['to_be_update']));
			$author_by = $data['author_by'];

			if ($token != $_SESSION['csrf_token']) {
				$response['status'] = 0;
				$response['message'] = 'Token Mismatch';
				echo json_encode($response);
				die;
			}
			if ($updation_type == '') {
				$response['status'] = 0;
				$response['message'] = 'Please select updataion type';
				echo json_encode($response);
				die;
			}

			if ($to_be_update == '') {
				$response['status'] = 0;
				$response['message'] = 'Please enter what to be update';
				echo json_encode($response);
				die;
			}

			$order_info = $db->prepare("select order_date from $schemas.order_daily where filing_no =? and item_no = ?");
			$order_info->bindParam(1, $filing_no, PDO::PARAM_BOOL);
			$order_info->bindParam(2, $item_no, PDO::PARAM_BOOL);
			$order_info->execute();
			$order_info = $order_info->fetchAll();

			if (!empty($order_info)) {
				$operation = 'U';
				$order_info = array_shift($order_info);
				$order_date = $order_info['order_date'];
				$order_log = $db->prepare("insert into $schemas.order_logs (filing_no,order_id,order_date,delete_update_type,entry_date,updated_at,user_id,username,ip_address,operation,to_be_update) values 
												(?,?,?,?,now(),now(),?,?,?,?,?)");
				$order_log->bindParam(1, $filing_no, PDO::PARAM_STR);
				$order_log->bindParam(2, $item_no, PDO::PARAM_STR);
				$order_log->bindParam(3, $order_date, PDO::PARAM_STR);
				$order_log->bindParam(4, $updation_type, PDO::PARAM_STR);
				$order_log->bindParam(5, $user_id, PDO::PARAM_STR);
				$order_log->bindParam(6, $username, PDO::PARAM_STR);
				$order_log->bindParam(7, $ip, PDO::PARAM_STR);
				$order_log->bindParam(8, $operation, PDO::PARAM_STR);
				$order_log->bindParam(9, $to_be_update, PDO::PARAM_STR);
				$save_log = $order_log->execute();

				$order_his_log = $db->prepare("insert into $schemas.order_daily_his (select * from $schemas.order_daily where filing_no = ? and item_no = ?)");
				$order_his_log->bindParam(1, $filing_no, PDO::PARAM_STR);
				$order_his_log->bindParam(2, $item_no, PDO::PARAM_STR);
				$order_history = $order_his_log->execute();

				if ($order_history && $save_log) {
					$update_order = $db->prepare("update $schemas.order_daily set order_date = ? , order_type = ?, updated_date = now(), updated_by = ?, updated_by_username = ? , author_by = ? where filing_no = ? and item_no = ?");
					$update_order->bindParam(1, $new_order_date, PDO::PARAM_STR);
					$update_order->bindParam(2, $new_order_type, PDO::PARAM_STR);
					$update_order->bindParam(3, $user_id, PDO::PARAM_STR);
					$update_order->bindParam(4, $username, PDO::PARAM_STR);
					$update_order->bindParam(5, $author_by, PDO::PARAM_STR);
					$update_order->bindParam(6, $filing_no, PDO::PARAM_STR);
					$update_order->bindParam(7, $item_no, PDO::PARAM_STR);
					$is_updated = $update_order->execute();
					if ($bench_no != '0') {
						$bench_info = $db->prepare("select * from $schemas.bench where from_list_date =? and bench_no = ?");
						$bench_info->bindParam(1, $new_order_date, PDO::PARAM_BOOL);
						$bench_info->bindParam(2, $bench_no, PDO::PARAM_BOOL);
						$bench_info->execute();
						$bench_info = $bench_info->fetch();
						$bench_nature = $bench_info['bench_nature'];
						$court_no = $bench_info['court_no'];
						$get_coram = get_coram($db, $schemas, $new_order_date, $court_no, $bench_nature, $bench_no);
						$saved_coram = array_map(function ($element) {
							return $element['judge_code'];
						}, $get_coram);
						$saved_coram = implode(',', $saved_coram);
						$update_order_bench = $db->prepare("update $schemas.order_daily set bench_no = ? , bench_nature = ?, court_no = ?, judge_codes = ? where filing_no = ? and item_no = ?");
						$update_order_bench->bindParam(1, $bench_no, PDO::PARAM_STR);
						$update_order_bench->bindParam(2, $bench_nature, PDO::PARAM_STR);
						$update_order_bench->bindParam(3, $court_no, PDO::PARAM_STR);
						$update_order_bench->bindParam(4, $saved_coram, PDO::PARAM_STR);
						$update_order_bench->bindParam(5, $filing_no, PDO::PARAM_STR);
						$update_order_bench->bindParam(6, $item_no, PDO::PARAM_STR);
						$is_updated = $update_order_bench->execute();
					}
				}

				if ($is_updated) {
					$response['status'] = 1;
					$response['message'] = 'Order details updated';
				} else {
					$response['status'] = 1;
					$response['message'] = 'something went wrong!';
					echo json_encode($response);
					die;
				}
			} else {
				$response['status'] = 0;
				$response['message'] = 'data not found';
				echo json_encode($response);
				die;
			}
			$db->commit();
			echo json_encode($response);
			die;
		} catch (Exception $e) {
			$db->rollBack();
			$response = array(
				'status' => 0,
				'message' => 'some error occurred.'
			);
			echo json_encode($response);
			die;
		}
	}


	if ($type == 'search_case_to_restore') {

		$case_no = htmlspecialchars($data['case_no']);
		$case_year = htmlspecialchars($data['case_year']);
		$case_type = htmlspecialchars($data['case_type']);
		$saarch_type = htmlspecialchars($data['search_type']);
		if (!is_numeric($case_year) || !is_numeric($case_type)) {
			echo "Invalid Input";
			die;
		}

		$case_detail = get_case_detail($db, $schema, $case_no, $case_year, $case_type, $location_id);
		if (empty($case_detail)) {
			$response = array('status' => 0, 'message' => 'Case Not Found');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) > 1) {
			$response = array('status' => 0, 'message' => 'Something went wrong');
			echo json_encode($response);
			die;
		}
		if (!empty($case_detail) && count($case_detail) == 1) {
			$case_detail = array_shift($case_detail);
			if ($case_detail['status'] != 'D') {
				$response = array('status' => 0, 'message' => "Case is not disposed you can't restore this case");
				echo json_encode($response);
				die;
			}
			$filing_no = $case_detail['filing_no'];
			$case_no = $case_detail['case_no'];
			$case_year = $case_detail['case_year'];
			$case_type = $case_detail['case_type'];
			$disposed_data = get_disposal_data($db, $schema, $filing_no);
			if (!empty($disposed_data)) {
				$disposed_date = $disposed_data['disposal_date'];
			}
			$pet_name = get_party($db, $filing_no, $party_flag = 'P', $party_serial_no = 1);
			$res_name = get_party($db, $filing_no, $party_flag = 'R', $party_serial_no = 1);
			$title = $pet_name . ' VS ' . $res_name;
			$restoratoin_reasons = get_restoration_reasons($db);

		?>
			<form action="upload_order_ajax.php" method="POST" id="submit_restoration_form">
				<input type="hidden" value='submit_restore' id="submit_restore" name="type">
				<div class='table-responsive'>
					<table id="title" class="table table-hover table-bordered">
						<thead>
							<th>Filing No</th>
							<th>Case No</th>
							<th>Cause Title</th>
							<th>Date of filing</th>
							<th>Registration Date</th>
							<th>Disposed Date</th>
							<th>Restoration Reason</th>
						</thead>
						<tbody>
							<tr id="filing_no_<?php echo $filing_no; ?>">
								<td><?php echo $filing_no; ?></td>
								<td><?php echo get_short_name($db, 'case_type', 'short_name', 'id', $case_type) . '/' . $case_no . '/' . get_short_name($db, 'mater_location_city', 'short_name', 'city_id', $location_id) . "/" . $case_year; ?></td>
								<td><?php echo $title; ?></td>
								<td><?php echo display_date($case_detail['dt_of_filing']); ?></td>
								<td><?php echo display_date($case_detail['regis_date']); ?></td>
								<td><?php echo (!empty($disposed_date)) ? display_date($disposed_date) : ''; ?></td>

								<td>
									<select id='restoration_reason' name='restoration_reason' class='form-control' onchange="return get_restoration_form(this.value,'<?php echo $filing_no; ?>');" required>
										<option value=''>Select reason</option>
										<?php foreach ($restoratoin_reasons as $k => $reasons) { ?>
											<option value='<?php echo $reasons['id']; ?>'><?php echo $reasons['restore_type']; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
						<?php } else { ?>
							<tr>
								<td colspan='7' style='color:red;text-align:center;'>No Record Found</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					<div id='restoration_form'></div>
				</div>
			</form>
			<?php

		}

		if ($type == 'search_restored_application') {
			$case_no = $data['case_no'];
			$case_year = $data['case_year'];
			$case_type = $data['case_type'];
			$main_case_filing_no = $data['filing_no'];
			$case_detail = get_case_detail($db, $schema, $case_no, $case_year, $case_type, $location_id, $main_case_filing_no);
			if (empty($case_detail)) {
				$response = array('status' => 0, 'message' => 'Case Not Found or case is not filed in above case');
				echo json_encode($response);
				die;
			}
			if (!empty($case_detail) && count($case_detail) > 1) {
				$response = array('status' => 0, 'message' => 'Something went wrong');
				echo json_encode($response);
				die;
			}
			if (!empty($case_detail) && count($case_detail) == 1) {
				$case_detail = array_shift($case_detail);

				$filing_no = $case_detail['filing_no'];
				$case_no = $case_detail['case_no'];
				$case_year = $case_detail['case_year'];
				$case_type = $case_detail['case_type'];

				$pet_name = get_party($db, $main_case_filing_no, $party_flag = 'P', $party_serial_no = 1);
				$res_name = get_party($db, $main_case_filing_no, $party_flag = 'R', $party_serial_no = 1);
				$title = $pet_name . ' VS ' . $res_name;

			?>
				<div class='table-responsive'>
					<table id="title" class="table table-hover table-bordered">
						<thead>
							<th>Filing No</th>
							<th>Case No</th>
							<th>Cause Title</th>
							<th>Date of filing</th>
							<th>Registration Date</th>
						</thead>
						<tbody>
							<tr id="filing_no_<?php echo $filing_no; ?>">
								<td><?php echo $filing_no; ?></td>
								<td><?php echo get_short_name($db, 'case_type', 'short_name', 'id', $case_type) . '/' . $case_no . '/' . get_short_name($db, 'mater_location_city', 'short_name', 'city_id', $location_id) . "/" . $case_year; ?></td>
								<td><?php echo $title; ?></td>
								<td><?php echo display_date($case_detail['dt_of_filing']); ?></td>
								<td><?php echo display_date($case_detail['regis_date']); ?></td>
							</tr>
						</tbody>
					</table>
					<div class='row'>
						<input type="hidden" value='<?php echo $main_case_filing_no; ?>' id="filing_no_to_restore" name="filing_no_to_restore">
						<input type="hidden" value='<?php echo $filing_no; ?>' id="restoration_filing_no" name="restoration_filing_no">
						<div class="col-sm-4 col-lg-4">
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Upload order copy</label>
								<div class="col-sm-8 col-lg-8">
									<input type="file" class="form-control" name="upload_order" id="upload_order" onclick="fileValidation(event)" required>
								</div>
							</div>
						</div>

						<div class="col-sm-4 col-lg-4">
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Details</label>
								<div class="col-sm-8 col-lg-8">
									<textarea cols='25' rows='3' name='details' id='details' class='form-control' required></textarea>
								</div>
							</div>
						</div>
					</div>
					<br />
					<div class="row">
						<center><button type="submit" id="submit_restoration_form_btn" name="submit_restoration_form_btn" class="btn btn-sm btn-primary">Save</button></center>
					</div>
				<?php }
		}

		if ($type == 'get_form_on_restoration_selection') {
			$filing_no = $data['filing_no'];
			$restoration_reason = $data['restoration_reason'];
			if ($restoration_reason == '') {
				$response = array('status' => 0, 'message' => 'Please select reason to restore case');
				echo json_encode($response);
				die;
			}
			if (empty($filing_no)) {
				$response = array('status' => 0, 'message' => 'Something went wrong!!');
				echo json_encode($response);
				die;
			} else if (empty($restoration_reason)) {
				$response = array('status' => 0, 'message' => 'Something went wrong!');
				echo json_encode($response);
				die;
			} else {

				?>

					<?php if ($restoration_reason == 3) {
						$disposed_data = get_disposal_data($db, $schema, $filing_no);
						if (!empty($disposed_data)) {
							$disposed_date = $disposed_data['disposal_date'];
							$last_proceeding_info = last_proceeding_info($db, $schema, $filing_no);
							if (!empty($last_proceeding_info)) {
								$last_proceeding_date = $last_proceeding_info['listing_date'];
								if ($last_proceeding_date == $disposed_date) { ?>
									<div class='table-responsive'>
										<table class='table table-responsive table-bordered'>
											<input type="hidden" value='<?php echo $filing_no; ?>' id="filing_no_to_restore" name="filing_no_to_restore">
											<thead>
												<th>Disposal Date</th>
												<th>Authorized Person Name</th>
												<th>Authorized Person Designation</th>
												<th>Details</th>
												<th>Upload pdf</th>
												<th>Action</th>
											</thead>
											<tbody>
												<tr>
													<td><?php echo date('d/m/Y', strtotime($disposed_date)); ?></td>
													<td>
														<textarea cols='25' rows='3' name='auth_person_name' id='auth_person_name' class='form-control' required></textarea>

													</td>
													<td>
														<textarea cols='25' rows='3' name='auth_person_desg' id='auth_person_desg' class='form-control' required></textarea>

													</td>
													<td>
														<textarea cols='25' rows='3' name='details' id='details' class='form-control' required></textarea>

													</td>
													<td>
														<input type="file" class="form-control" name="upload_order" id="upload_order">

													</td>
													<td>
														<button type="submit" id="revert_case_status" name="revert_case_status" class="btn btn-sm btn-primary">Revert Case Status</button>
													</td>
												</tr>
											</tbody>
										</table>
							<?php	} else {
									$response = array('status' => 0, 'message' => 'Last proceeding and disposed date are not same!!');
									echo json_encode($response);
									die;
								}
							} else {
								$response = array('status' => 0, 'message' => 'Something wrong!!');
								echo json_encode($response);
								die;
							}
						} else {
							$response = array('status' => 0, 'message' => 'Something wrong!');
							echo json_encode($response);
							die;
						}
					}
					if ($restoration_reason == 2) { ?>
							<div class='table-responsive'>
								<input type="hidden" value='<?php echo $filing_no; ?>' id="filing_no_to_restore" name="filing_no_to_restore">
								<div class='row'>
									<div class="col-sm-4 col-lg-4">
										<div class="form-group">
											<label class="control-label col-sm-4" for="email">Upload order copy</label>
											<div class="col-sm-8 col-lg-8">
												<input type="file" class="form-control" name="upload_order" id="upload_order" onclick="fileValidation(event)" required>
											</div>
										</div>
									</div>

									<div class="col-sm-4 col-lg-4">
										<div class="form-group">
											<label class="control-label col-sm-4" for="email">Details</label>
											<div class="col-sm-8 col-lg-8">
												<textarea cols='25' rows='3' name='details' id='details' class='form-control' required></textarea>
											</div>
										</div>
									</div>
								</div>
								<br />
								<div class="row">
									<center><button type="submit" id="submit_restoration_form_btn" name="submit_restoration_form_btn" class="btn btn-sm btn-primary">Save</button></center>
								</div>
							<?php }
						if ($restoration_reason == 1 || $restoration_reason == 4) { ?>
								<div class='table-responsive'>
									<div class="form-inline" id="search_restoration_form">
										<div class="form-group">
											<?php
											$dispaly_Case_type = 't';
											if ($restoration_reason == 1) {
												$case_type_id = 6;
											} else {
												$case_type_id =  9;
											}
											$search_case_types = $db->prepare("select * from case_type where status=? and id = ?");
											$search_case_types->bindParam(1, $dispaly_Case_type, PDO::PARAM_INT);
											$search_case_types->bindParam(2, $case_type_id, PDO::PARAM_INT);
											$search_case_types->execute();
											$search_case_types = $search_case_types->fetchAll();
											?>
											<label>Case Type : </label>
											<select class="form-control" id="search_restored_case_type">
												<?php foreach ($search_case_types as $key => $search_case_type) { ?>
													<option value="<?php echo $search_case_type['id']; ?>"><?php echo $search_case_type['case_type_desc']; ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label>Case No : </label>
											<input type="text" class="form-control" placeholder="Enter Case Number" name="restored_case_no" id="restored_case_no" autocomplete="off" required="required">
										</div>
										<div class="form-group">
											<label>Case Year : </label>
											<input type="number" class="form-control" placeholder="Enter Case Year" onKeyPress="return number_validation(this.id,4)" name="restored_case_year" id="restored_case_year" autocomplete="off" required="required">
										</div>
										<button type="button" class="btn btn-primary" onClick="return search_restored_application('<?php echo $filing_no; ?>');">search</button>
									</div>
								<?php } ?>

								<?php if ($restoration_reason == 1 || $restoration_reason == 4) { ?>
									<div id='restoration_form_2'>

									</div>
								</div>
							<?php }
							}
						}

						if ($type == 'submit_restore') {
							try {

								$db->beginTransaction();  // begin transaction
								$filing_no_to_restore = htmlspecialchars($data['filing_no_to_restore']);
								$restoration_reason = htmlspecialchars($data['restoration_reason']);
								$restoration_filing_no = htmlspecialchars($data['restoration_filing_no']);
								$details = trim(htmlspecialchars($data['details']));
								$get_main_case_details = get_case_detail_by_filing_no($db, $schema, $filing_no_to_restore);
								if (empty($get_main_case_details)) {
									$response = array('status' => 0, 'message' => 'something went wrong!');
									echo json_encode($response);
									die;
								}
								if (empty($restoration_reason) || empty($details)) {
									$response = array('status' => 0, 'message' => 'Please upload order and enter details');
									echo json_encode($response);
									die;
								}
								if ($restoration_reason == '1' || $restoration_reason == '4') {
									$get_restored_app_details = get_case_detail_by_filing_no($db, $schema, $restoration_filing_no);
									if (empty($get_restored_app_details)) {
										$response = array('status' => 0, 'message' => 'something went wrong!!');
										echo json_encode($response);
										die;
									}
								} else {
									$restoration_filing_no = '';
								}
								$disposed_data = get_disposal_data($db, $schema, $filing_no_to_restore);
								if (empty($disposed_data)) {
									$response = array('status' => 0, 'message' => 'something went wrong!!!');
									echo json_encode($response);
									die;
								} else {
									$disposed_date = $disposed_data['disposal_date'];
								}

								$username = htmlspecialchars($_SESSION['actual_username']);
								$ip = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
								$uploadDir = 'Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/restored_case/' . $filing_no_to_restore;
								$saveUploadDir = 'Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/restored_case/' . $filing_no_to_restore;


								$uploadedFile = '';
								$uploadStatus = 1;

								if (!empty($_FILES["upload_order"]["name"])) {

									// File path config 
									$fileName = basename($_FILES["upload_order"]["name"]);
									$uniquesavename = time() . uniqid(rand()) . '.pdf';
									$targetFilePath = $uploadDir . '/' . $uniquesavename;
									$acttual_file = $uploadDir . '/' . $fileName;
									$saveTargetFilePath = $saveUploadDir . '/' . $uniquesavename;

									$fileType = pathinfo($acttual_file, PATHINFO_EXTENSION);
									/////////////////////////////code by preeti starts here //////////////////////////////


									$fileName = $_FILES["upload_order"]["name"];
									$filesize = $_FILES["upload_order"]["size"];
									$filemimetype = $_FILES['upload_order']['type'];
									$fileextension = pathinfo($_FILES["upload_order"]["name"], PATHINFO_EXTENSION);
									$count = substr_count($fileName, '.');

									if (($count > 1) || ($filesize > 20000000) || ($filemimetype != 'application/pdf')) {

										$uploadStatus = 0;
										$response['status'] = 0;
										$response['message'] = 'Sorry, there was an error uploading your file.';
										echo json_encode($response);
										die;
									}


									///////////////////////////////////code by preeti ends here//////////////////
									// Allow certain file formats 
									$allowTypes = array('pdf');
									if (in_array($fileType, $allowTypes)) {
										// Upload file to the server 
										if (!file_exists($uploadDir)) {
											mkdir($uploadDir, 0777, true);
										}
										require_once('../object_storage/S3Service.php');
										$s3Service = new S3Service();
										if ($s3Service->uploadFile($_FILES["upload_order"]["tmp_name"], $targetFilePath)) {
											$uploadedFile = $fileName;
										} else {
											$uploadStatus = 0;
											$response['status'] = 0;
											$response['message'] = 'Sorry, there was an error uploading your file.';
											echo json_encode($response);
											die;
										}
									} else {
										$uploadStatus = 0;
										$response['status'] = 0;
										$response['message'] = 'Sorry, only PDF files are allowed to upload.';
										echo json_encode($response);
										die;
									}
								} else {
									$uploadStatus = 0;
								}
								$restore_type_text = 'Restore Case';
								if ($restoration_reason == 3) {
									if (empty($_FILES["upload_order"]["name"])) {
										$uploadStatus = 1;
										$saveTargetFilePath = '';
									}
									$restore_type_text = 'Revert case status';
									$auth_person_desg = trim($data['auth_person_desg']);
									$auth_person_name = trim($data['auth_person_name']);
									if (empty($auth_person_desg) || empty($auth_person_name)) {
										$response = array('status' => 0, 'message' => 'Please enter authorized person name and designation!!');
										echo json_encode($response);
										die;
									}
								} else {
									$auth_person_desg = '';
									$auth_person_name = '';
								}

								if ($uploadStatus == 1) {

									//if ($restoration_reason != 5) {

										$last_proceeding_date = $db->prepare("select listing_date from $schema.case_proceeding where filing_no = ? order by listing_date desc limit 1");
										$last_proceeding_date->bindParam(1, $filing_no_to_restore, PDO::PARAM_STR);
										$last_proceeding_date->execute();
										$last_proceeding_date = $last_proceeding_date->fetchColumn();

										if ($last_proceeding_date != $disposed_date) {
											$response['status'] = 0;
											$response['message'] = 'last procceding date and disposal date not same';
											echo json_encode($response);
											die;
										}

										$disposal_log = $db->prepare("insert into $schema.case_disposal_his (select * from $schema.case_disposal where filing_no = ?)");
										$disposal_log->bindParam(1, $filing_no_to_restore, PDO::PARAM_STR);
										$disposal_log = $disposal_log->execute();

										if ($restoration_reason == 3) {
											$proceeding_log = $db->prepare("insert into $schema.case_proceeding_his (select * from $schema.case_proceeding where filing_no = ? and listing_date = ?)");
											$proceeding_log->bindParam(1, $filing_no_to_restore, PDO::PARAM_STR);
											$proceeding_log->bindParam(2, $last_proceeding_date, PDO::PARAM_STR);
											$proceeding_log = $proceeding_log->execute();
										}


										$case_detail_log = $db->prepare("INSERT INTO $schema.case_detail_log(filing_no, case_no, pet_name, pet_address, pet_email, pet_mobile,  pet_phone, pet_fax, petadvname, pet_pin,
													loginid, payment_reference_no,reference_no, schema_id, bench, sub_bench, pet_fathername, pet_occupation, pet_capacity, pet_description,
													res_name, res_address, res_pin,  res_mobile, res_phone, res_fathername, res_occupation, res_description,  res_capacity, res_adv_name, res_email,
													res_fax, amount_payment, from_document, to_document, section, prays, oa_ref_no, status, regis_date, scrutiny_completed, dt_of_filing,
													pet_type, res_type, case_type, entry_date, case_year, filing_year, e_reference_no, pet_adv, res_adv, pet_state, res_state, pet_district,
													res_district, pet_code, res_code, pet_adv_name, level_level, location_code, legal_aid, filing_no_new, filing_no_old, collength,
													ia_flag,main_case_ia_no,court_no,auto_manual,registrar_date,manual_court_no,manual_user_id,manual_date, backlog,is_partially_defective,
													partially_defect_free_date,transfrred_case_filing_no,transfrred_case_location,
													transfrred_case_type,transfrred_case_type_short,transfrred_case_no,alter_date, alter_login_id, ip_address, changes_type)
													select  filing_no, case_no, pet_name, pet_address, pet_email, pet_mobile,  pet_phone, pet_fax, petadvname, pet_pin,
													loginid, payment_reference_no,reference_no, schema_id, bench, sub_bench, pet_fathername, pet_occupation, pet_capacity, pet_description,
													res_name, res_address, res_pin,  res_mobile, res_phone, res_fathername, res_occupation, res_description, res_capacity, res_adv_name, res_email,
													res_fax, amount_payment, from_document, to_document, section, prays, oa_ref_no, status, regis_date, scrutiny_completed, dt_of_filing,
													pet_type, res_type, case_type, entry_date, case_year, filing_year, e_reference_no, pet_adv, res_adv, pet_state, res_state, pet_district,
													res_district, pet_code, res_code, pet_adv_name, level_level, location_code, legal_aid, filing_no_new, filing_no_old, collength,
													ia_flag,main_case_ia_no,court_no,auto_manual,registrar_date,manual_court_no,manual_user_id,manual_date, backlog,is_partially_defective,
													partially_defect_free_date,transfrred_case_filing_no,transfrred_case_location,
													transfrred_case_type,transfrred_case_type_short,transfrred_case_no, now(), ?, ?, ? from $schema.case_detail where filing_no=?");
										$case_detail_log->bindParam(1, $user_id, PDO::PARAM_STR);
										$case_detail_log->bindParam(2, $ip, PDO::PARAM_STR);
										$case_detail_log->bindParam(3, $restore_type_text, PDO::PARAM_STR);
										$case_detail_log->bindParam(4, $filing_no_to_restore, PDO::PARAM_STR);
										$case_detail_log = $case_detail_log->execute();

										$status = 'P';
										$update_status = $db->prepare("update $schema.case_detail set status = ? where filing_no = ?");
										$update_status->bindParam(1, $status, PDO::PARAM_STR);
										$update_status->bindParam(2, $filing_no_to_restore, PDO::PARAM_STR);
										$update_status = $update_status->execute();

										$delete_disposal = $db->prepare("delete from $schema.case_disposal where filing_no = ?");
										$delete_disposal->bindParam(1, $filing_no_to_restore, PDO::PARAM_STR);
										$delete_disposal = $delete_disposal->execute();

										if ($restoration_reason == 3) {
											$delete_proceeding = $db->prepare("delete from $schema.case_proceeding where filing_no = ? and listing_date = ?");
											$delete_proceeding->bindParam(1, $filing_no_to_restore, PDO::PARAM_STR);
											$delete_proceeding->bindParam(2, $disposed_date, PDO::PARAM_STR);
											$delete_proceeding = $delete_proceeding->execute();
										}
									//}


									$save_restore = $db->prepare("insert into $schema.restored_cases (filing_no,restoration_filing_no,restoration_type,details,filename,
													order_copy_path,disposal_date,entry_date,user_id,username,ip,auth_person_name,auth_person_desg) values 
												(?,?,?,?,?,?,?,now(),?,?,?,?,?)");
									$save_restore->bindParam(1, $filing_no_to_restore, PDO::PARAM_STR);
									$save_restore->bindParam(2, $restoration_filing_no, PDO::PARAM_STR);
									$save_restore->bindParam(3, $restoration_reason, PDO::PARAM_STR);
									$save_restore->bindParam(4, $details, PDO::PARAM_STR);
									$save_restore->bindParam(5, $filename, PDO::PARAM_STR);
									$save_restore->bindParam(6, $saveTargetFilePath, PDO::PARAM_STR);
									$save_restore->bindParam(7, $disposed_date, PDO::PARAM_STR);
									$save_restore->bindParam(8, $user_id, PDO::PARAM_STR);
									$save_restore->bindParam(9, $username, PDO::PARAM_STR);
									$save_restore->bindParam(10, $ip, PDO::PARAM_STR);
									$save_restore->bindParam(11, $auth_person_name, PDO::PARAM_STR);
									$save_restore->bindParam(12, $auth_person_desg, PDO::PARAM_STR);
									$save_restore = $save_restore->execute();
								} else {
									$response['status'] = 0;
									$response['message'] = 'Please upload order';
									echo json_encode($response);
									die;
								}

								$msg = 'Case restored';
								if ($restoration_reason == 3) {
									$msg = 'Case status reverted';
								}
								$db->commit();
								$response = array(
									'status' => 1,
									'message' => $msg
								);
								echo json_encode($response);
							} catch (Exception $e) {
								echo 'Message: ' . $e->getMessage();
								$db->rollBack();
								$response = array(
									'status' => 0,
									'message' => 'some error occurred.'
								);
								echo json_encode($response);
							}
						}


						if ($type == 'search_case_to_recuse_judge') {
							$case_no = $data['case_no'];
							$case_year = $data['case_year'];
							$case_type = $data['case_type'];
							$saarch_type = $data['search_type'];

							if (!is_numeric($case_no) || !is_numeric($case_year) || !is_numeric($case_type)) {
								echo "Invalid Input";
								die;
							}

							$case_detail = get_case_detail($db, $schema, $case_no, $case_year, $case_type, $location_id);
							if (empty($case_detail)) {
								$response = array('status' => 0, 'message' => 'Case Not Found');
								echo json_encode($response);
								die;
							}
							if (!empty($case_detail) && count($case_detail) > 1) {
								$response = array('status' => 0, 'message' => 'Something went wrong');
								echo json_encode($response);
								die;
							}
							if (!empty($case_detail) && count($case_detail) == 1) {
								$case_detail = array_shift($case_detail);

								$filing_no = $case_detail['filing_no'];
								$case_no = $case_detail['case_no'];
								$case_year = $case_detail['case_year'];
								$case_type = $case_detail['case_type'];

								$already_recused_judges = already_recused($db,$schema,$filing_no);
								//$disposed_data = get_disposal_data($db, $schema, $filing_no);
								$listing_data = last_listing_info($db, $schema, $filing_no);
								if(!empty($listing_data)){
									$bench_listing_date = $listing_data['listing_date'];
									$listing_bench_no = $listing_data['bench_no'];
									$judge_ids = get_judge_by_bench($db,$schema,$bench_listing_date,$listing_bench_no);
									$mj = $db->prepare("select * from $schema.master_judge where judge_code in ($judge_ids) and judge_code not in ($already_recused_judges) order by judge_desg_code asc");
									$mj->execute();
									$master_judges = $mj->fetchAll();
								}else{
									$mj = $db->prepare("select * from $schema.master_judge where judge_code not in ($already_recused_judges) order by judge_desg_code asc");
									$mj->execute();
									$master_judges = $mj->fetchAll();
								}

								$pet_name = get_party($db, $filing_no, $party_flag = 'P', $party_serial_no = 1);
								$res_name = get_party($db, $filing_no, $party_flag = 'R', $party_serial_no = 1);
								$title = $pet_name . ' VS ' . $res_name;

								



							?>
							<div class='table-responsive'>
								<table id="title" class="table table-hover table-bordered">
									<thead>
										<th>Filing No</th>
										<th>Case No</th>
										<th>Cause Title</th>
										<th>Date of filing</th>
										<th>Registration Date</th>
										<th>Judges</th>
										<th>Action</th>
									</thead>
									<tbody>
										<tr id="filing_no_<?php echo $filing_no; ?>">
											<td><?php echo $filing_no; ?></td>
											<td><?php echo get_short_name($db, 'case_type', 'short_name', 'id', $case_type) . '/' . $case_no . '/' . get_short_name($db, 'mater_location_city', 'short_name', 'city_id', $location_id) . "/" . $case_year; ?></td>
											<td><?php echo $title; ?></td>
											<td><?php echo display_date($case_detail['dt_of_filing']); ?></td>
											<td><?php echo display_date($case_detail['regis_date']); ?></td>

											<td>
												<select id='master_judge_<?php echo $filing_no; ?>' required name='master_judge[]' multiple="multiple" class='form-control select_judges'>
													<option value=''>Select Judge</option>
													<?php foreach ($master_judges as $k => $judge) { ?>
														<option value='<?php echo $judge['judge_code']; ?>'><?php echo $judge['judge_name']; ?></option>
													<?php } ?>
												</select>
											</td>
											<td>
												<button type='button' id='recuse_judge_<?php echo $filing; ?>' class='btn btn-primary btn-sm' onClick="return recuse_judge('<?php echo $filing_no; ?>')">Recuse</button>
											</td>
										</tr>
									<?php } else { ?>
										<tr>
											<td colspan='7' style='color:red;text-align:center;'>No Record Found</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							</div>
							<?php
							$is_deleted = 0;

							$sql1 = $db->prepare("select rcj.filing_no,rcj.judge_code,rcj.id,mj.judge_name,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.regis_date,a.main_case_ia_no
								from $schema.recused_case_judge as rcj 
								left join $schema.case_detail as a  on a.filing_no = rcj.filing_no
								left join $schema.master_judge as mj  on mj.judge_code = rcj.judge_code
								where rcj.is_deleted = ? and rcj.filing_no = ? order by rcj.id asc");
							$sql1->bindParam(1, $is_deleted, PDO::PARAM_INT);
							$sql1->bindParam(2, $filing_no, PDO::PARAM_INT);
							$sql1->execute();
							$recused_judges = $sql1->fetchAll();

							if (!empty($recused_judges)) {
							?>
								<div class='table-responsive'>
									<table id="title" class="table table-hover table-bordered">
										<thead>
											<th>SN</th>
											<th>Recused Judge(s)</th>
											<th>Action</th>
										</thead>
										<tbody>
											<?php
											foreach ($recused_judges as $kk => $judge) {
												$sn = $kk + 1;
												$judge_name = $judge['judge_name'];
											?>
												<tr>
													<td><?php echo $sn; ?></td>
													<td>
														<?php echo $judge_name; ?>
													</td>
													<td>
														<button type='button' name='remove_recuse_judge_<?php echo $filing; ?>' id='remove_recuse_judge_<?php echo $filing; ?>' class='btn btn-danger btn-sm' onClick="return remove_recuse_judge('<?php echo $filing_no; ?>','<?php echo $judge['id'] ?>')">Remove</button>
													</td>
												</tr>
											<?php } ?>
										</tbody>
								</div>
							<?php
							}
							die;
						}

						if ($type == 'recuse_submit') {
							$ip = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
							$is_deleted = 0;
							$filing_no = $data['filing_no'];
							$judge_codes = $data['judge_code'];
							try{
								$db->beginTransaction();
								foreach($judge_codes as $k=>$judge_code){
									$check_alredy_recused = $db->prepare("select count(*) from $schema.recused_case_judge where filing_no = ? and judge_code = ? and is_deleted = ?");
									$check_alredy_recused->bindParam(1, $filing_no, PDO::PARAM_STR);
									$check_alredy_recused->bindParam(2, $judge_code, PDO::PARAM_STR);
									$check_alredy_recused->bindParam(3, $is_deleted, PDO::PARAM_STR);
									$check_alredy_recused->execute();
									$check_alredy_recused = $check_alredy_recused->fetchColumn();
									if ($check_alredy_recused > 0) {
										$response = array(
											'status' => 0,
											'message' => 'Case already recused for selected judge'
										);
										echo json_encode($response);
										die;
									}
									$save_recuse = $db->prepare("insert into $schema.recused_case_judge (filing_no,judge_code,user_id,username,ip,entry_date)
													values (?,?,?,?,?,now())");
									$save_recuse->bindParam(1, $filing_no, PDO::PARAM_STR);
									$save_recuse->bindParam(2, $judge_code, PDO::PARAM_STR);
									$save_recuse->bindParam(3, $user_id, PDO::PARAM_STR);
									$save_recuse->bindParam(4, $username, PDO::PARAM_STR);
									$save_recuse->bindParam(5, $ip, PDO::PARAM_STR);
									$save_recuse->execute();
									if ($save_recuse) {
										$response = array(
											'status' => 1,
											'message' => 'Case recused for selected judge'
										);
									} else {
										$response = array(
											'status' => 0,
											'message' => 'Something went wrong'
										);
									}
								}
								$db->commit();
								echo json_encode($response);
								die;
							} catch (Exception $e) {
								$db->rollBack();
								$response = array(
									'status' => 0,
									'message' => 'some error occurred.'
								);
								echo json_encode($response);
								die;
							}
							
						}

						if ($type == 'remove_recuse_judge') {
							$ip = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
							$is_deleted = 1;
							$filing_no = $data['filing_no'];
							$recuse_id = $data['recuse_id'];

							$dlt_recuse = $db->prepare("update $schema.recused_case_judge set is_deleted = ?, deleted_by= ?, deleted_at = now(), 
											deleted_by_username = ?, deleted_by_ip = ? where filing_no = ? and id = ?");
							$dlt_recuse->bindParam(1, $is_deleted, PDO::PARAM_STR);
							$dlt_recuse->bindParam(2, $user_id, PDO::PARAM_STR);
							$dlt_recuse->bindParam(3, $username, PDO::PARAM_STR);
							$dlt_recuse->bindParam(4, $ip, PDO::PARAM_STR);
							$dlt_recuse->bindParam(5, $filing_no, PDO::PARAM_STR);
							$dlt_recuse->bindParam(6, $recuse_id, PDO::PARAM_STR);
							$dlt_recuse->execute();
							if ($dlt_recuse) {
								$response = array(
									'status' => 1,
									'message' => 'Case removed from recused for selected judge'
								);
							} else {
								$response = array(
									'status' => 0,
									'message' => 'Something went wrong'
								);
							}
							echo json_encode($response);
							die;
						}

						if ($type == 'get_child_or_connected') {
							$filing_no = $data['filing_no'];
							$listing_date = $data['listing_date'];
							if (!empty($listing_date)) {
								list($day, $month, $year) = explode('/', $listing_date);
								$listing_date_new = "$year-$month-$day";
							} else {
								$listing_date_new = '';
							}
							$case_detail = get_case_detail_by_filing_no($db, $schema, $filing_no);
							$get_listing_detail = get_listing_detail($db, $schema, $filing_no, $listing_date_new);
							$child_cases = selected_final_cases($schema, $db, $filing_no, 1, $listing_date_new, $get_listing_detail['bench_no'], $get_listing_detail['list_flag'], $get_listing_detail['court_no'], $child_or_connected = 'I', '');
							if (!empty($case_detail['main_case_ia_no'])) {
								$child_cases_of_main = selected_final_cases($schema, $db, $case_detail['main_case_ia_no'], 1, $listing_date_new, $get_listing_detail['bench_no'], $get_listing_detail['list_flag'], $get_listing_detail['court_no'], $child_or_connected = 'I', $filing_no);
								$child_cases = array_merge($child_cases, $child_cases_of_main);
							}
							$connected_cases =  selected_final_cases($schema, $db, $filing_no, 1, $listing_date_new, $get_listing_detail['bench_no'], $get_listing_detail['list_flag'], $get_listing_detail['court_no'], $child_or_connected = 'C', '');
							$child_or_conn_cases = array_merge($child_cases, $connected_cases);
							foreach ($connected_cases as $k => $conn_case) {
								$conn_child_cases = selected_final_cases($schema, $db, $conn_case['filing_no'], 1, $listing_date_new, $get_listing_detail['bench_no'], $get_listing_detail['list_flag'], $get_listing_detail['court_no'], $child_or_connected = 'I', '');

								if (!empty($conn_child_cases)) {
									$child_or_conn_cases = array_merge($child_or_conn_cases, $conn_child_cases);
								}
							}
							?>
							<div class="col-sm-12 col-lg-12">
								<div class="form-group">
									<?php
									if (!empty($child_or_conn_cases)) {
										echo "<br/>Select Case To Upload same order with same details:<br/>";
										foreach ($child_or_conn_cases as $key => $child_case) {
											echo "<input type='checkbox' name='child_cases[]' value='$child_case[filing_no]' id='child_cases_$child_case[filing_no]'>   $child_case[case_type_short_name]/$child_case[case_no]/$child_case[short_name]/$child_case[case_year]<br/>";
										}
									}

									?>
								</div>
							</div>
					<?php
						}
					}

					?>

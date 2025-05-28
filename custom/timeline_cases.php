<!-- echo htmlspecialchars_decode(strtoupper($E_nameP)) . "&nbsp;<font color='blue' size='2'><br> Vs. <br> </font>&nbsp;" . htmlspecialchars_decode(strtoupper($E_nameR)); -->
<?php $filing_no_no = isset($_REQUEST['filing_no']) ? $_REQUEST['filing_no'] : '';
$filing_no_no = isset($_REQUEST['filing_no']) ? $_REQUEST['filing_no'] : '';
if (!is_numeric($filing_no_no) && !empty($filing_no_no)) {
	echo "Invalid Input";
	die();
}
$selected_case_type = isset($_REQUEST['selected_case_type']) ? $_REQUEST['selected_case_type'] : '';
$from_date = (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date'])) ? date('Y-m-d', strtotime($_REQUEST['from_date'])) : '';
$to_date = (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date'])) ? date('Y-m-d', strtotime($_REQUEST['to_date']))  : '';
$from_date_display = (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date'])) ? $_REQUEST['from_date'] : '';
$to_date_display = (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date'])) ? $_REQUEST['to_date'] : '';
$case_types = case_type($db);
$main_cases = main_case_type();
?>
<div class="box-body">
	<div class="table-responsive">
		<table class="table no-margin table-bordered">





			<tr>
				<th>Sr No.</th>
				<th>Date Of Filing</th>
				<th>Case Type</th>
				<th>Diary No.</th>
				<th>Main Case Diary No.</th>
				<th>Title Of Case</th>
				<th>Action</th>
			</tr>
			</thead>
			<tbody>
				<?php
			
				if ($_SESSION['menuaccess_codeall'] == '11') {
					$daysasign = 7;
				} else {
					$daysasign = 14;
				}
				$limit = 100;
				$total_recordss = timelineCases($db, $location_access, 'Y', 'NA',  $selected_case_type, '', $from_date, $to_date, 0, $filing_no_no);
				$total_pages = ceil(count($total_recordss) / $limit);
				if (isset($_GET["page"])) {
					$page  = $_GET["page"];
				} else {
					$page = 1;
				};
				$start_from = ($page - 1) * $limit;
				$sn = $start_from + 1;


				$case_data = timelineCases($db, $location_access, 'Y', 'NA', $selected_case_type, $limit, $from_date, $to_date, $start_from, $filing_no_no);
				if (!empty($case_data) && is_array($case_data)) {
					$i = 1;
					foreach ($case_data as $row) {
						$case_type = htmlspecialchars($row['case_type_nclat']);
						if (in_array($case_type, $main_cases)) {
							$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row['filing_no']);
						} else {
							$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row['filingnumberia']);
							if (empty($show_party_filing_no))
								$show_party_filing_no = htmlspecialchars($row['filing_no']);
						}
						$filing_no = htmlspecialchars($row['filing_no']);
						$main_filing_no = htmlspecialchars($row['filingnumberia']);
						//$fictitious_filing_no = htmlspecialchars($row['fictitious_filing_no']);
						$dt_of_filing = htmlspecialchars($row['dt_of_filing']);
						$contempt_case_no = htmlspecialchars($row['contempt_case_no']);
						$contempt_case_year = htmlspecialchars($row['contempt_case_year']);
						$casetypeiacontempt = htmlspecialchars($row['casetypeiacontempt']);
						$pet_name = get_party($db, $show_party_filing_no, $party_flag = 'P', $party_serial_no = 1);
						$res_name = get_party($db, $show_party_filing_no, $party_flag = 'R', $party_serial_no = 1);
						$case_title = $pet_name . " <b>VS</b> " . $res_name;
						$subject_id = $row['subjectia'];
						$case_type_display = fn_case_type_name($db, $case_type);
						$tr_short = '';
						if ($case_type == '40') {
							$old_case_info = get_old_case_info($db, $filing_no);
							if (!empty($old_case_info)) {
								if (!empty($old_case_info)) {
									$transfer_case_type = $old_case_info['transfer_case_type'];
									if ($transfer_case_type == '32') {
										$tr_short = ' (Company)';
									} else if ($transfer_case_type == '33') {
										$tr_short = ' (Ins.)';
									} else if ($transfer_case_type == '34') {
										$tr_short = ' (Compt.)';
									} else {
										$tr_short = '';
									}
								}
							}
						}

						try {
							$st_asign_date = $db->prepare("select created_at from gst_ecase_assignsuboffice_validation where filingno = ?");
							$st_asign_date->bindParam(1, $filing_no, PDO::PARAM_STR);
							$st_asign_date->execute();
							$data_assigndate = $st_asign_date->fetch();
						} catch (PDOException $ex) {
							echo $ex;
						}

						$date_assign_formate =  $dt_of_filing;
						if (!empty($data_assigndate) && is_array($data_assigndate)) {
							$check_date_asign = getDateTimeline($db, $schemas, $daysasign, $data_assigndate['created_at']);
							$date_assign_formate = $data_assigndate['created_at'];
						} else {
							$st_asign_date = $db->prepare("select created_at from gst_ecase_assign_tobo_office_validate where filingno = ?");
							$st_asign_date->bindParam(1, $filing_no, PDO::PARAM_STR);
							$st_asign_date->execute();
							$data_assigndate = $st_asign_date->fetch();
							if (!empty($data_assigndate) && is_array($data_assigndate)) {
								$date_assign_formate = $data_assigndate['created_at'];
								$check_date_asign = getDateTimeline($db, $schemas,$daysasign, $data_assigndate['created_at']);
							} else {
								$check_date_asign = getDateTimeline($db, $schemas, $daysasign, $dt_of_filing);
								$date_assign_formate = $dt_of_filing;
							}
						}
						$currenDate  = date('Y-m-d');
						if (strtotime($currenDate) >= strtotime($check_date_asign)) {
							if (($row['filing_through'] == 2 || ($row['filing_through'] == 1 &&  $row['apl0402_not_found'] == 1)) && $row['gst_casetransfer_level'] != 4) {
							} else {
				?>
								<tr style='background-color:#9fbef4'>
									<td><?php echo $sn; ?></td>
									<td><?php echo fn_date_formate($dt_of_filing); ?></td>
									<td><?php echo $case_type_display . $tr_short; ?></td>
									<td>
										<?php echo display_filing_no($filing_no);
										echo "<br><span style='color:red'>";
										echo "(No.of Docs - " . fn_document($db, $filing_no) . ")";
										echo "</span>";
										?>
									</td>
									<td><?php echo (!empty($main_filing_no) && $main_filing_no != 'NA') ? display_filing_no($main_filing_no) : 'NA'; ?>
									</td>
									<td><?php echo $case_title; ?> </td>


									<td>
										<?php $filing_no_send = base64_encode($filing_no . '-' . $qq1cc . '-' . $case_type . '-' . $direct_parent_filing_no . '-' . $direct_parent_filing_no);
										
										
										
// if ($_SESSION['menuaccess_codeall'] == '11') {
// 	$role_isds = '3';
// 	$st1_msg = $db->prepare("select * from  tbl_timeline_message where filing_no =? and created_role = ? ");
// 	$st1_msg->bindParam(1, $filing_no, PDO::PARAM_STR);
// 	$st1_msg->bindParam(2, $role_isds, PDO::PARAM_STR);
// 	$st1_msg->execute();
// 	$case_type_edetail_msg = $st1_msg->fetchAll();
	
// 	if(!empty($case_type_edetail_msg) && is_array($case_type_edetail_msg)) { 
// 	echo "<br><span style='display: inline-block;
// 		background: #a77272;
// 		color: #fff;
// 		font-size: 13px;
// 		font-weight: bold;
// 		padding: 5px 10px;
// 		border-radius: 5px;
// 		margin-top: 15px;
// 		border: 1px solid #f9d5d5;
// 		border-bottom: 1px solid #736363;'>";
// 		foreach($case_type_edetail_msg as $val) { 
// 	echo $val['message'] .'<b>'.$val['created_at'].'</b>';
// 		}
// 		echo "</span>";
// 	}
// 	} 
										
										?>




										<button type="button" onClick="fn_return_cases('<?php echo $filing_no; ?>')" style="
                                                		background-color: green;
                                                		color: #FFFFFF;
                                                		padding: 7px 7px;
                                                		margin: 2px 0;
                                                		border: none;
                                                		border-radius: 4px;
                                                		cursor: pointer;">Send Reminder</button>
										<h4>
											<?php
											if ($_SESSION['menuaccess_codeall'] == '11')  { 
												if ($row['court'] == '' || $row['cis_user_id'] == '') {
													if (($row['filing_through'] == 2 || ($row['filing_through'] == 1 &&  $row['apl0402_not_found'] == 1)) && $row['gst_casetransfer_level'] != 4) {
													} else {
														echo $check_date_timeline =  checkDateTime($db, $schemas, 14, $date_assign_formate);
													}
												} else {
													echo $check_date_timeline =  checkDateTime($db, $schemas, 14, $date_assign_formate);
												}
											}
										 ?>
										</h4>
									</td>
								</tr>
					<?php $sn++;
							}
						}
					} ?>

				<?php
				} else { ?>
					<tr>
						<td colspan="7">
							<font color="red" size="2"> </font>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<div id="return_group_modal" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Timeline Cases</h4>
					</div>

					<!-- //pdfForm -->
					<form id="return_final_form_id" name="return_final_form_id" method="post">
						<input type="hidden" name="hidden_filling_no11111" id="hidden_filling_no11111" value="">
						<div class="modal-body">
							<p> <textarea class="form-control" name="remark_return_cases" id="remark_return_cases"></textarea>
							</p>

						</div>
					</form>
					<div class="modal-footer">
						<div id="footer_dsc">
							<button type="button" id="upload_return_timeline" class="btn btn-success">Update</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>

			</div>
		</div>


<script>function fn_return_cases(filing_no) {
				$("#return_group_modal").modal('show');
				$("#hidden_filling_no11111").val(filing_no);
			}
		
			
			
			
			</script>
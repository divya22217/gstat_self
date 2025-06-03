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

// calculation changes done  by Divya 29-04-25

function calculateDays($db, $tracking_date, $label)
{
	
    $today = new DateTime();
    $trackingDate = new DateTime($tracking_date);
    // retreived public holidays
    $public_holidays_query = $db->prepare("SELECT holiday_date FROM delhi.holidays WHERE status = true 
        ORDER BY holiday_date ASC
    ");
    $public_holidays_query->execute();
    $holidays = $public_holidays_query->fetchAll(PDO::FETCH_COLUMN);

    // calculating working days (excluding holidays) filing date to till date
    $currentDate = clone $today;
    $direction = $today < $trackingDate ? 1 : -1;
    $workingDays = 0;

    while ($currentDate->format('Y-m-d') !== $trackingDate->format('Y-m-d')) {
        $currentDate->modify($direction . ' day');
        if (!in_array($currentDate->format('Y-m-d'), $holidays)) {
            $workingDays += $direction;
        }
    }

    // due date = tracking date + 7 working days
    $dueDate = clone $trackingDate;
    $addedDays = 0;
    while ($addedDays < 7) {
        $dueDate->modify('+1 day');
        if (!in_array($dueDate->format('Y-m-d'), $holidays)) {
            $addedDays++;
        }
    }
    // Calculation of  working days between today and due date
    $adjustedDays = 0;
    $currentDate = clone $today;
    $direction2 = $today < $dueDate ? 1 : -1;

    while ($currentDate->format('Y-m-d') !== $dueDate->format('Y-m-d')) {
        $currentDate->modify($direction2 . ' day');
        if (!in_array($currentDate->format('Y-m-d'), $holidays)) {
            $adjustedDays += $direction2;
        }
    }

    $daysSinceFiling = abs($workingDays);
    
    if ($adjustedDays > 0) {
        return "<button class='label label-default' style='color:green !important;font-weight:bold;'>$label Pending for $daysSinceFiling working Day(s) since the Date of filing</button>";
    } elseif ($adjustedDays < 0) {
        return "<button class='label label-default' style='color:red !important;font-weight:bold;'>$label overdue by " . abs($adjustedDays) . " Day(s),<br> as the due date is calculated as 7 working days from the filing date, and it has now been $daysSinceFiling Day(s)</button>";
    } else {
        return "<button class='label label-default'>0 Day(s) $label Pending</button>";
    }
}
// END of Code for changes done by Divya 29-04-25

/**
 * Handles scrutiny or verification cases.
 */
function handleCase($db, $filing_no, $dt_of_filing, $tableName, $label1, $label2)
{
    $response = $db->prepare("SELECT created_at FROM $tableName WHERE filingno = ?");
    $response->bindParam(1, $filing_no, PDO::PARAM_STR);
    $response->execute();
    $date_data = $response->fetch();

    $formattedDate = !empty($date_data) && is_array($date_data)
        ? (new DateTime($date_data['created_at']))->format('Y-m-d')
        : (new DateTime($dt_of_filing))->format('Y-m-d');
    $label = !empty($date_data) ? $label1 : $label2;


    if (!empty($date_data) && is_array($date_data)) {
        $createdDate = (new DateTime($date_data['created_at']))->format('Y-m-d');
        handleOnlineCase($db, $filing_no, $createdDate);
    } else {
        echo calculateDays($db, $formattedDate, $label);
    }
}



	/**
 * Handles online cases.
 */
function handleOnlineCase($db, $filing_no, $dt_of_filing)
{
    $response = $db->prepare("SELECT created_at FROM gst_ecase_assign_tobo_office WHERE filingno = ?");
    $response->bindParam(1, $filing_no, PDO::PARAM_STR);
    $response->execute();
    $date_data = $response->fetch();

    if (!empty($date_data) && is_array($date_data)) {
        $formattedDate = (new DateTime($dt_of_filing))->format('Y-m-d');
        echo calculateDays($db, $formattedDate, "Scrutiny");
    } else {
        handleSubOfficeCase($db, $filing_no, $dt_of_filing);
    }
}

/**
 * Handles cases assigned to sub-offices.
 */
function handleSubOfficeCase($db, $filing_no, $dt_of_filing)
{
    $response = $db->prepare("SELECT created_at FROM gst_ecase_assignsuboffice WHERE filingno = ?");
    $response->bindParam(1, $filing_no, PDO::PARAM_STR);
    $response->execute();
    $date_data_res = $response->fetch();

    if (!empty($date_data_res) && is_array($date_data_res)) {
        $formattedDate = (new DateTime($dt_of_filing))->format('Y-m-d');
        $createdDate = (new DateTime($date_data_res['created_at']))->format('Y-m-d');
        echo calculateDays($db, $createdDate, "Assign By Sub Officer");
        echo calculateDays($db, $formattedDate, "Scrutiny");
    } else {
        $formattedDate = (new DateTime($dt_of_filing))->format('Y-m-d');
        echo calculateDays($db, $formattedDate, "Assign By Nodal Officer");
        echo calculateDays($db, $formattedDate, "Scrutiny");
    }
}

// End of day calculation function developed by Pavan 08/01/2024
?>
<div class="box-body">
	<div class="table-responsive">
		<table class="table no-margin table-bordered">
			<tr>
				<td colspan="16" align="left">
					<font face="Verdana" size="2"> </font>
					<font face="Verdana" size="2">Case Types:
						<select id="selected_case_type" name="selected_case_type" onchange="javascript:submitForm3();" class="frm-field required">
							<option value="" <?php echo ($selected_case_type == '') ? 'selected' : ''; ?>>All</option>
							<?php foreach ($case_types as $k => $case_type) { ?>
								<option value="<?php echo $case_type['id']; ?>" <?php echo ($case_type['id'] == $selected_case_type) ? 'selected' : ''; ?>><?php echo $case_type['case_type_desc']; ?></option>
							<?php } ?>
						</select>


						<font face="Verdana" size="2">Diary/Filing No:
							<input type="number" name="filing_no" id="filing_no" value="<?php echo $filing_no_no;  ?>">

							<font face="Verdana" size="2"> </font>
							<font face="Verdana" size="2">From filing date:
								<input type='date' id="from_date" name="from_date" value="<?php echo $from_date_display; ?>">


								<font face="Verdana" size="2">To filing date:
									<input type='date' id="to_date" name="to_date" value="<?php echo $to_date_display; ?>">


									<input type="button" onclick="submitForm3()" value="Search">
									<input type="button" onclick="reset_case()" value="Reset">
				</td>
			</tr>


			<tr>
				<td colspan="16" align="left">
					<font face="Verdana" size="2"> </font>

				</td>
			</tr>

			<tr>
				<td colspan="12">
					<?php

					?>

					<?php
					$total_records = fresh_cases($db, $location_access, 'Y', 'NA', $selected_case_type, '', $from_date, $to_date);
					if ($selected_case_type == '') {
						$show_total = 'cases';
					} else {
						$show_total = fn_case_type_name($db, $selected_case_type);
					}
					?>
					<p>
						<font color="red">Total <?php echo $show_total; ?> pending for scrutiny:</font><?php echo count($total_records); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th>Sr No.</th>
				<th>Date Of Filing</th>
				<th>Case Type</th>
				<th>Diary/Filing No.</th>
				<th>Main Case Diary/Filing No.</th>
				<th>Title Of Case</th>
				<th>Categories</th>
				<?php if($selected_case_type == 1 ){ ?>
					<th>Days For Scrutiny</th>
				<?php  } ?>
				<th>Action</th>
			</tr>
			</thead>
			<tbody>
<?php
				$limit = 100;
				$total_recordss = fresh_cases($db, $location_access, 'Y', 'NA',  $selected_case_type, '', $from_date, $to_date, 0, $filing_no_no);
				$total_pages = ceil(count($total_recordss) / $limit);
				if (isset($_GET["page"])) {
					$page  = $_GET["page"];
				} else {
					$page = 1;
				};
				$start_from = ($page - 1) * $limit;
				$sn = $start_from + 1;
				$case_data = fresh_cases($db, $location_access, 'Y', 'NA', $selected_case_type, $limit, $from_date, $to_date, $start_from, $filing_no_no);
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

						$categories = categories_of_case($db,$filing_no);



						// New Code Ravindar 23/12/2024
						try {
							$st_asign_date = $db->prepare("select created_at from gst_ecase_assignsuboffice_validation where filingno = ?");
							$st_asign_date->bindParam(1, $filing_no, PDO::PARAM_STR);
							$st_asign_date->execute();
							$data_assigndate = $st_asign_date->fetch();
						} catch (PDOException $ex) {
							echo $ex;
						}


						$date_assign_formate =  $dt_of_filing;
						if (!empty($data_assigndate) && is_array($data_assigndate)) {;
							$check_date_asign = getDateTimeline($db, $schemas, 10, $data_assigndate['created_at']);
							$date_assign_formate = $data_assigndate['created_at'];
						} else {
							$st_asign_date = $db->prepare("select created_at from gst_ecase_assign_tobo_office_validate where filingno = ?");
							$st_asign_date->bindParam(1, $filing_no, PDO::PARAM_STR);
							$st_asign_date->execute();
							$data_assigndate = $st_asign_date->fetch();
							if (!empty($data_assigndate) && is_array($data_assigndate)) {
								$date_assign_formate = $data_assigndate['created_at'];
								$check_date_asign = getDateTimeline($db, $schemas, 10, $data_assigndate['created_at']);
							} else {

								$check_date_asign = getDateTimeline($db, $schemas, 10, $dt_of_filing);
								$date_assign_formate = $dt_of_filing;
							}
						}




						$msg_remarks_re =  '';

						if ($_SESSION['menuaccess_codeall'] == '2') {
							$role_isds = '3';
							$st1_msg = $db->prepare("select * from  tbl_timeline_message where filing_no =? ");
							$st1_msg->bindParam(1, $filing_no, PDO::PARAM_STR);
							//$st1_msg->bindParam(2, $role_isds, PDO::PARAM_STR);
							$st1_msg->execute();
							$case_type_edetail_msg = $st1_msg->fetchAll();

							if (!empty($case_type_edetail_msg) && is_array($case_type_edetail_msg)) {
								$msg_remarks_re .=  "<br><span style='display: inline-block;
														background: #a77272;
														color: #fff;
														font-size: 13px;
														font-weight: bold;
														padding: 5px 10px;
														border-radius: 5px;
														margin-top: 15px;
														border: 1px solid #f9d5d5;
														border-bottom: 1px solid #736363;'>";
								foreach ($case_type_edetail_msg as $val) {
									$msg_remarks_re .=  $val['message'] . '<b>' . $val['created_at'] . '</b>.<br>';
								}
								$msg_remarks_re .=   "</span>";
							}
						}

						//  End 
				?>
						<tr style="background-color: #9fbef4;">
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
								<?php
									foreach ($categories as $key => $value) {
										echo ++$key.".  ".$value['category_of_case_under_dispute']."</br>";
									}
								 ?>
							</td>
							<!-------Condition for Appeal-------->
							<?php if($selected_case_type == 1){ ?>
							<td>
                                <?php
                                // start of day calculation function developed by Pavan 08/01/2024
								//Changes Done BY Divya 29-04-25
                                if (!in_array($case_type, $main_cases) && (empty($main_filing_no) || $main_filing_no === 'NA' || strlen($main_filing_no) !== 16)) {
                                    if (empty($row['court']) && empty($row['cis_user_id'])) {
                                        if ($row['filing_through'] == 2) {
                                            // Case Filing Through Type 2
                                            handleCase($db, $filing_no, $dt_of_filing, "gst_ecase_validation_for_apl0204", "Scrutiny", "Verification By Nodal Officer");
                                        } else if ($row['filing_through'] == 1 && in_array($row['apl0402_not_found'], [1, 2])) {
                                            // Filing Through Type 1
                                            handleCase($db, $filing_no, $dt_of_filing, "gst_ecase_validation_for_apl0204", "Scrutiny", "Verification By Nodal Officer");
                                        } else {
                                            // Online Case
                                            handleOnlineCase($db, $filing_no, $dt_of_filing);
                                        }
                                    } else {
                                        // Scrutiny Check
                                        handleCase($db, $filing_no, $dt_of_filing, "gst_ecase_validation_for_apl0204", "Scrutiny", "Verification By Nodal Officer");
                                    }
									echo $check_date_timeline =  checkDateTime($db, $schemas, 10, $dt_of_filing);
									if($row['gst_casetransfer_level_verify']== 4 && $row['is_transfered_verify'=='t']){
									
										echo $verification_msg .='<span class="label label-warning">
															<span style="color: #000;">Verification Completed</span>
														</span>';
									}
                                } else {
                                    if (empty($row['court']) && empty($row['cis_user_id'])) {
                                        if ($row['filing_through'] == 2) {
                                            // Case Filing Through Type 2
                                            handleCase($db, $filing_no, $dt_of_filing, "gst_ecase_validation_for_apl0204", "Scrutiny", "Verification By Nodal Officer");
                                        } else if ($row['filing_through'] == 1 && in_array($row['apl0402_not_found'], [1, 2])) {
                                            // Filing Through Type 1
                                            handleCase($db, $filing_no, $dt_of_filing, "gst_ecase_validation_for_apl0204", "Scrutiny", "Verification By Nodal Officer");
                                        } else {
                                            // Online Case
                                            handleOnlineCase($db, $filing_no, $dt_of_filing);
                                        }
										echo $check_date_timeline =  checkDateTime($db, $schemas, 10, $dt_of_filing);
										
                                    } else {
                                        // Scrutiny Check
                                        handleCase($db, $filing_no, $dt_of_filing, "gst_ecase_validation_for_apl0204", "Scrutiny", "Verification By Nodal Officer");
										echo $check_date_timeline =  checkDateTime($db, $schemas, 10, $dt_of_filing);
                                    }
									
                                }
								
								//changes done on 30-04-25
                                //End of day calculation function developed by Pavan 08/01/2024
                                ?>
                            </td>
							<?php }?>
							<!-------Condition for Appeal-------->

							<!--<td> <?php echo fn_section($db, $filing_no); ?></td>-->
							<?php
							$main_case_for_backlog = '';
							if (!in_array($case_type, $main_cases) && (empty($main_filing_no) || $main_filing_no == 'NA') && strlen($main_filing_no) != '16') {
								if (!empty($casetypeiacontempt) && !empty($casetypeiacontempt) && !empty($casetypeiacontempt)) {
									$case_type_backlog = get_case_type($db, $status = 't', $casetypeiacontempt);
									$main_case_for_backlog = $case_type_backlog . '/' . $contempt_case_no . '/' . $contempt_case_year;
								}
							?>
								<td>

									<span><?php echo $main_case_for_backlog; ?><span>
											<h4>
												<span class="label label-info">
													<a style="color: #FFFFFF;" href="javascript::void(0);">Enter Backlog Case</a>
												</span>
												<br /><br />
												<?php $filing_no_send = base64_encode($filing_no . '-' . $qq1cc . '-' . $case_type . '-' . $direct_parent_filing_no . '-' . $direct_parent_filing_no); ?>
												<?php
												if ($row['court'] == '' && $row['cis_user_id'] == '') {
													if (($row['filing_through'] == 2 || ($row['filing_through'] == 1 &&  $row['apl0402_not_found'] == 1)) && $row['gst_casetransfer_level'] != 4) {
														if ($row['transfer_case_level'] != 4 && $row['is_transfered'] != true) {
												?>
														<span class="label label-warning">
															<span style="color: #000;">Verification Pending</span>
														</span>
														<br /><br />
														<span class="label label-default">
															<a class="disabled" style="color:#000;" href="#" onclick="assignToMyself('<?php echo $_SESSION['id']; ?>','<?php echo $filing_no_send; ?>','<?php echo $_SESSION['user_court']; ?>')">Self Assign</a>
														</span>
														<br /><br />
														<span class="label label-default ">
															<a class="disabled" style="color: #000;"  onclick="alert_fn('./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>')" href="javascript:void(0)">Scrutiny</a>
														</span>
														<?php
														} else {
														
														
													?>
															<span class="label label-info">
																<a style="color: #FFFFFF;" href="#" onclick="assignToMyself('<?php echo $_SESSION['id']; ?>','<?php echo $filing_no_send; ?>','<?php echo $_SESSION['user_court']; ?>')">Self Assign</a>
															</span>
															<br /><br />
															<span class="label label-default ">
																<a class="disabled" style="color: #000;"  onclick="alert_fn('./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>')" href="javascript:void(0)">Scrutiny</a>
															</span>
															<?php //	echo $msg_remarks_re;
														       // echo $check_date_timeline =  checkDateTime($db, $schemas, 7, $date_assign_formate); ?>
														<?php
														}
													} else {
														 ?>
														<span class="label label-info">
															<a style="color: #FFFFFF;" href="#" onclick="assignToMyself('<?php echo $_SESSION['id']; ?>','<?php echo $filing_no_send; ?>','<?php echo $_SESSION['user_court']; ?>')">Self Assign</a>
														</span>
														<br /><br />
														<span class="label label-default ">
															<a class="disabled" style="color: #000;"  onclick="alert_fn('./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>')" href="javascript:void(0)">Scrutiny</a>
														</span>
														<?php														//	echo $msg_remarks_re;
														//echo $check_date_timeline =  checkDateTime($db, $schemas, 7, $date_assign_formate); ?>

													<?php }
												} else {
													?>
													<span class="label label-info">
														<a style="color: #FFFFFF;"  onclick="alert_fn('./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>')" href="javascript:void(0)">Scrutiny</a>
													</span>
													<?php //echo $msg_remarks_re; 
													//echo $check_date_timeline =  checkDateTime($db, $schemas, 7, $date_assign_formate); ?>

												<?php } ?>
											</h4>
								</td>
							<?php	} else { ?>
					<td>
	<?php $filing_no_send = base64_encode($filing_no . '-' . $qq1cc . '-' . $case_type . '-' . $direct_parent_filing_no . '-' . $direct_parent_filing_no); ?>
	<h4>
	
		<?php if ($row['court'] == '' || $row['cis_user_id'] == '') {
			if (($row['filing_through'] == 2 || ($row['filing_through'] == 1 &&  $row['apl0402_not_found'] == 1)) && $row['gst_casetransfer_level'] != 4) {
				if ($row['transfer_case_level'] != 4 && $row['is_transfered'] != true) {
					?>
					<span class="label label-warning">
						<span style="color: #000;">Verification Pending</span>
					</span>
					<br /><br />
					<span class="label label-default">
						<a class="disabled" style="color: #000;" href="#" onclick="assignToMyself('<?php echo $_SESSION['id']; ?>','<?php echo $filing_no_send; ?>','<?php echo $_SESSION['user_court']; ?>')">Self Assign</a>
					</span>
					<br /><br />
					<span class="label label-default ">
						<a class="disabled" style="color: #000;" href="./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
					</span>
					<?php
				}else{
						if ($row['transfer_case_level'] == 4 && $row['is_transfered'] == true) {
		
							echo $verification_msg ='<span class="label label-default" style="background:green;color:white !important;font-weight:bold;">
												Verified </span>';
										}?>
										<br /><br />
							<span class="label label-info">
							<a style="color: #FFFFFF;" href="#" onclick="assignToMyself('<?php echo $_SESSION['id']; ?>','<?php echo $filing_no_send; ?>','<?php echo $_SESSION['user_court']; ?>')">Self Assign</a>
						</span>
						<br /><br />
					<span class="label label-default ">
						<a class="disabled" style="color: #000;" href="./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
					</span>
					<?php
				}
			} else {
				if ($row['transfer_case_level'] == 4 && $row['is_transfered'] == true) {
	
					echo $verification_msg ='<span class="label label-default" style="background:green;color:white !important;font-weight:bold;">
										Verified </span>';
								}?>
								<br /><br />
				<span class="label label-info">
					<a style="color: #FFFFFF;" href="#" onclick="assignToMyself('<?php echo $_SESSION['id']; ?>','<?php echo $filing_no_send; ?>','<?php echo $_SESSION['user_court']; ?>')">Self Assign</a>
				</span>
				<br /><br />
				<span class="label label-default ">
					<a class="disabled" style="color: #000;" href="./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
				</span>
				
			<?php }
		} else { if ($row['transfer_case_level'] == 4 && $row['is_transfered'] == true) {
	
			echo $verification_msg ='<span class="label label-default" style="background:green;color:white !important;font-weight:bold;">
								Verified </span>';
						}?>
						<br /><br />
			<span class="label label-info">
				<a style="color: #FFFFFF;" href="./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
			</span>
			
		<?php } ?>
	</h4>
</td>	
							<?php } ?>
						</tr>
					
				
					<?php $sn++;
					} ?>
					<tr>
						<td colspan="7">
							<div align="center">
								<ul class='pagination text-center' id="pagination">
									<?php if (!empty($total_pages)) : for ($i = 1; $i <= $total_pages; $i++) :

											$clas_active = '';
											if ($_REQUEST['page'] == $i) {
												$clas_active = 'active';
											}

											if ($i == 1) : ?>
												<li class='<?php echo $clas_active; ?>' id="<?php echo $i; ?>"><a href='index.php?page=<?php echo $i; ?>&selected_case_type=<?php echo $selected_case_type; ?>&c_case=<?php echo $c_case; ?>'><?php echo $i; ?></a></li>
											<?php else : ?>
												<li class='<?php echo $clas_active; ?>' id="<?php echo $i; ?>"><a href='index.php?page=<?php echo $i; ?>&selected_case_type=<?php echo $selected_case_type; ?>&c_case=<?php echo $c_case; ?>'><?php echo $i; ?></a></li>
											<?php endif; ?>
									<?php endfor;
									endif; ?>
								</ul>
							</div>
						</td>
					</tr>
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


<script> 
function alert_fn(url) { 
	swal({
		title: "Are you sure ??",
		text: "This is a place of supply issue. Please scrutinize/check place of supply jurisdiction first, once verified then proceed for rest defect checklist next", 
		icon: "warning",
		buttons: true,
		dangerMode: false,
		})
		.then((willDelete) => { 
			 if (willDelete) {	
				window.location.href=url;
				return false;
			 }
		});
}
</script>

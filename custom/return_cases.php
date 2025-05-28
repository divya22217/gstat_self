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
					$total_records = return_cases_cases($db, $location_access, 'Y', 'NA', $selected_case_type, '', $from_date, $to_date);
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
				<th>Action</th>
			</tr>
			</thead>
			<tbody>
				<?php
				$limit = 100;
				$total_recordss = return_cases_cases($db, $location_access, 'Y', 'NA',  $selected_case_type, '', $from_date, $to_date, 0, $filing_no_no);
				$total_pages = ceil(count($total_recordss) / $limit);
				if (isset($_GET["page"])) {
					$page  = $_GET["page"];
				} else {
					$page = 1;
				};
				$start_from = ($page - 1) * $limit;
				$sn = $start_from + 1;
				$case_data = return_cases_cases($db, $location_access, 'Y', 'NA', $selected_case_type, $limit, $from_date, $to_date, $start_from, $filing_no_no);
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
                                                            <a class="disabled" style="color: #000;" href="./scrutiny/level_one.php?ccase=1&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
                                                        </span>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <span class="label label-info">
                                                            <a style="color: #FFFFFF;" href="#" onclick="assignToMyself('<?php echo $_SESSION['id']; ?>','<?php echo $filing_no_send; ?>','<?php echo $_SESSION['user_court']; ?>')">Self Assign</a>
                                                        </span>
                                                        <br /><br />
                                                        <span class="label label-default ">
                                                            <a class="disabled" style="color: #000;" href="./scrutiny/level_one.php?ccase=1&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
                                                        </span>
                                                    <?php }
                                                } else {
                                                    ?>
                                                    <span class="label label-info">
                                                        <a style="color: #FFFFFF;" href="./scrutiny/level_one.php?ccase=1&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
                                                    </span>
                                                <?php } ?>
                                            </h4>
								</td>
							<?php	} else { ?>
								<td>
									<?php $filing_no_send = base64_encode($filing_no . '-' . $qq1cc . '-' . $case_type . '-' . $direct_parent_filing_no . '-' . $direct_parent_filing_no); ?>
										<h4>
                                        <!-- <span class="label label-info">
                                            <a style="color: #FFFFFF;" href="./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
                                        </span> -->
                                        <?php if ($row['court'] == '' || $row['cis_user_id'] == '') {
                                            if (($row['filing_through'] == 2 || ($row['filing_through'] == 1 &&  $row['apl0402_not_found'] == 1)) && $row['gst_casetransfer_level'] != 4) {
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
                                                    <a class="disabled" style="color: #000;" href="./scrutiny/level_one.php?ccase=1&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
                                                </span>

                                            <?php
                                            } else {
                                            ?>
                                                <span class="label label-info">
                                                    <a style="color: #FFFFFF;" href="#" onclick="assignToMyself('<?php echo $_SESSION['id']; ?>','<?php echo $filing_no_send; ?>','<?php echo $_SESSION['user_court']; ?>')">Self Assign</a>
                                                </span>
                                                <br /><br />
                                                <span class="label label-default ">
                                                    <a class="disabled" style="color: #000;" href="./scrutiny/level_one.php?ccase=1>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
                                                </span>
                                            <?php }
                                        } else { ?>
                                            <span class="label label-info">
                                                <a style="color: #FFFFFF;" href="./scrutiny/level_one.php?ccase=1&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
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
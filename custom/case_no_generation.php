<?php 
	  $user_court = $_SESSION['user_court'];
	  $from_date = (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']))? date('Y-m-d',strtotime($_REQUEST['from_date'])) :'';
	  $to_date = (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))?date('Y-m-d',strtotime($_REQUEST['to_date']))  :'';
	  $from_date_display = (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']))?$_REQUEST['from_date'] :'';
	  $to_date_display = (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))?$_REQUEST['to_date'] :'';
?>

<div class="box-body">
	<table>
		<tr>
            <td colspan="16" align="left">
            
                    <font face="Verdana" size="2"> </font>
                	<font face="Verdana" size="2">From filing date:
                    <input type='date' id="from_date" name="from_date" value="<?php echo $from_date_display; ?>">
					

                    <font face="Verdana" size="2">To filing date:</font>
                    <input type='date' id="to_date" name="to_date" value="<?php echo $to_date_display; ?>">


                    <input type="button" onclick="submitForm3()" value="Search" >
					<input type="button" onclick="reset_case()" value="Reset" >
            </td>
        </tr>
    </table>

    <div class="table-responsive">
                <table id='example' class="table no-margin table-bordered" >
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Date Of Filing</th>
                            <th>Case Type</th>
                            <th>Diary/Filing No.</th>
							<th>Main Case Diary/Filing No.</th>
                            <th>Title Of Case</th>
                            <th>From Court</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
    $case_data = get_cases_for_no_generation($db, $user_court, $schemas,$from_date,$to_date, $type='main');

	$main_cases = main_case_type();
    if (!empty($case_data) && is_array($case_data)) {
		$sn = 1;
        foreach ($case_data as $key=>$row) {
            $filing_no = htmlspecialchars($row['filing_no']);
			$main_filing_no = htmlspecialchars($row['filingnumberia']);
            $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
            $case_type = htmlspecialchars($row['case_type_nclat']);
            $from_court = ($row['from_type'] == 'P')?'Yes':'';
            $from_type = $row['from_type'];
            $list_with_defect = $row['list_with_defect'];
			if (in_array($case_type, $main_cases)){
			$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row['filing_no']);
			}else{
				$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row['filingnumberia']);
				if(empty($show_party_filing_no))
					$show_party_filing_no = htmlspecialchars($row['filing_no']);
			}
			$pet_name = get_party($db,$show_party_filing_no,$party_flag='P',$party_serial_no=1);
			$res_name = get_party($db,$show_party_filing_no,$party_flag='R',$party_serial_no=1);
			$case_title = $pet_name." <b>VS</b> ".$res_name;
            $case_type_display = htmlspecialchars($row['case_type_desc_cis']);
            $filing_date_all = fn_date_formate($dt_of_filing);
			$patially_defective = htmlspecialchars($row['patially_defective']);
			$subject_id = $row['subjectia'];
			if($patially_defective == '1' || $from_type == 'P'){
				$back_color = '#f1df61';
			}else{
				$back_color = '#9fbef4';
			}
			$before_filing = '';
			if($list_with_defect == 1){
				$before_filing = 'T-';
			}
			
			$tr_short = '';
			if($case_type == '40'){
				$old_case_info = get_old_case_info($db,$filing_no);
				if(!empty($old_case_info)){
					if(!empty($old_case_info)){
						$transfer_case_type = $old_case_info['transfer_case_type'];
						if($transfer_case_type == '32'){
							$tr_short = ' (Company)';
						}else if($transfer_case_type == '33'){
							$tr_short = ' (Ins.)';
						}else if($transfer_case_type == '34'){
							$tr_short = ' (Compt.)';
						}else{
							$tr_short = '';
						}
					}
				}
			}
                ?>
                        <tr style="background-color: <?php echo $back_color; ?>;">
                            <td><?php echo $sn; ?></td>
                            <td><?php echo fn_date_formate($dt_of_filing); ?> </td>
                            <td><?php echo $case_type_display.$tr_short; ?></td>
                            <td><?php echo $before_filing.display_filing_no($filing_no);?></td>
							<td><?php echo (!empty($main_filing_no) && $main_filing_no != 'NA')?display_filing_no($main_filing_no):'NA';?>
                            <td><?php echo $case_title; ?> </td>
                            <td style="color:red;"><?php echo $from_court; ?></td>
                            <td>
									<h4><span class="label label-info">
									<a style="color: #FFFFFF;" href="javascript:void(0);" onClick="return generate_case_no('<?php echo $filing_no; ?>','<?php echo $case_type; ?>','ajax/computation_note.php');" >Generate Case No</a>
									</span></h4>
							</td>
							<!--<td>
								<h4><span class="label label-info">
									<a style="color: #FFFFFF;" href="javascript:void(0);" onClick="return show_note('<?php echo $filing_no; ?>','<?php echo $case_type; ?>','ajax/computation_note.php');" >Show Note</a>
									</span></h4>
							</td>-->
                        </tr>
						
						<?php
					$sn++;
				?>		
                        <?php
            }
        }
?>
                    </tbody>

                </table>
</div>
</div>

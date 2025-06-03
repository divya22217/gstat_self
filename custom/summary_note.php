<?php 
  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
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
                            <th>Diary No.</th>
                            <th>Main Case Diary No.</th>
                            <th>Title Of Case</th>
							<th>Documents</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
    $case_data = defect_free_cases_for_note($db, $schemas,$user_court,$from_date,$to_date, $type='main');
	$main_cases = main_case_type_for_comp_note();
	$appeals = main_case_type();
    if (!empty($case_data) && is_array($case_data)) {
		$sn = 1;
        foreach ($case_data as $key=>$row) {
            $filing_no = htmlspecialchars($row['filing_no']);
			$main_filing_no = htmlspecialchars($row['filingnumberia']);
            $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
            $case_type = htmlspecialchars($row['case_type_nclat']);
			$patially_defective = htmlspecialchars($row['patially_defective']);
			if (in_array($case_type, $appeals)){
			$show_party_filing_no = htmlspecialchars($row['filing_no']);
			}else{
				$show_party_filing_no = htmlspecialchars($row['filingnumberia']);
				if(empty($show_party_filing_no))
					$show_party_filing_no = htmlspecialchars($row['filing_no']);
			}
			$pet_name = get_party($db,$show_party_filing_no,$party_flag='P',$party_serial_no=1);
			$res_name = get_party($db,$show_party_filing_no,$party_flag='R',$party_serial_no=1);
			$case_title = $pet_name." <b>VS</b> ".$res_name;
            $case_type_display = htmlspecialchars($row['case_type_desc_cis']);
            $filing_date_all = fn_date_formate($dt_of_filing);
			$subject_id = $row['subjectia'];
			$list_with_defect = $row['list_with_defect'];
			$get_computation_note = computation_note_details($db,$schemas,$filing_no);
			$remarks = get_remarks($db,$schemas,$filing_no);
			$get_computation_note = array_shift($get_computation_note);
			if(!empty($get_computation_note) && $get_computation_note['is_approved'] == '0' && !empty($remarks)){
				$bc_color = '#f3e7ca';
			}else{
				$bc_color = '#9fbef4';#f3e7ca
			}
			if($patially_defective == '1'){
				$bc_color = '#f1df61';
			}
			
			$before_filing = '';
			if($list_with_defect == 1){
				$before_filing = 'T-';
			}

			$doc_count = count_doc($db,$filing_no,$scrutiny=1,$display=1);
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
					<tr style="background-color: <?php echo $bc_color; ?>;">
								<td><?php echo $sn; ?></td>
								<td><?php echo fn_date_formate($dt_of_filing); ?></td>
								<td><?php echo $case_type_display; ?></td>
								<td><?php echo $before_filing.display_filing_no($filing_no);?></td>
								<td><?php echo (!empty($main_filing_no) && $main_filing_no != 'NA')?display_filing_no($main_filing_no):'NA';?>
								<td><?php echo $case_title; ?> </td>
								<td><a  onclick="OpenDMSForm('2','<?php echo $filing_no; ?>','<?php echo $case_title; ?>','','','')" style="cursor: pointer"> View Docs</a></td>
								<td>
									<h4><span class="label label-info">
								<a style="color: #FFFFFF;" href="javascript:void(0);" onClick="return comp_note('<?php echo $filing_no; ?>','<?php echo $case_type; ?>','ajax/computation_note.php');" >Generate Summary Note</a>
								</span></h4>
								</td>
							</tr>
			<?php $sn++; continue;
				}
				
			}
			
?>
                    </tbody>

                </table>
</div>
</div>

</form>

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

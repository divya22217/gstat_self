<!-- echo htmlspecialchars_decode(strtoupper($E_nameP)) . "&nbsp;<font color='blue' size='2'><br> Vs. <br> </font>&nbsp;" . htmlspecialchars_decode(strtoupper($E_nameR)); -->
<?php $filing_no_no = isset($_REQUEST['filing_no'])? $_REQUEST['filing_no'] :''; 
	  $selected_case_type = isset($_REQUEST['selected_case_type'])? $_REQUEST['selected_case_type'] :'';
	  $from_date = (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']))? date('Y-m-d',strtotime($_REQUEST['from_date'])) :'';
	  $to_date = (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))?date('Y-m-d',strtotime($_REQUEST['to_date']))  :'';
	  $from_date_display = (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']))?$_REQUEST['from_date'] :'';
	  $to_date_display = (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))?$_REQUEST['to_date'] :'';
	  $case_types = case_type($db);
	  $main_cases = main_case_type_for_comp_note();
	  $appeals = main_case_type();
	  function get_defective_date($db,$schemas,$filing_no,$table='scrutiny'){
			$compliance_date=$db->prepare("select notification_date  from $schemas.$table where filing_no= ? order by notification_date desc limit 1");
			$compliance_date->bindParam(1, $filing_no, PDO::PARAM_STR);
			$compliance_date->execute();
			$compliance_date = $compliance_date->fetchColumn();
			return $compliance_date;
		}
		
		function get_refile_date($db,$schemas,$filing_no){
			$compliance_date=$db->prepare("select max(date) from scrutiny_history where filing_no = ?");
			$compliance_date->bindParam(1, $filing_no, PDO::PARAM_STR);
			$compliance_date->execute();
			$compliance_date = $compliance_date->fetchColumn();
			return $compliance_date;
		}

		function get_scruitny_days_gap($db,$schemas,$filing_no,$cur_date,$table='scrutiny'){
			
			$days_gap = '';
			$compliance_date = get_defective_date($db,$schemas,$filing_no,$table);
			if(!empty($compliance_date)){
				$datetime1 = new DateTime($compliance_date);
				$datetime2 = new DateTime($cur_date);
				$interval = $datetime1->diff($datetime2);
				$days_gap= $interval->format('%R%a');
			}else{
				$days_gap = '';
			}
			return $days_gap;
		}
?>
<div class="box-body">
    <div class="table-responsive">
        <table class="table no-margin table-bordered">
			<tr>
                <td colspan="16" align="left">
                    <font face="Verdana" size="2"> </font>
                    <font face="Verdana" size="2">Case Types:
                        <select id="selected_case_type" name="selected_case_type" onchange="javascript:submitForm3();"
                            class="frm-field required">
							<option value="" <?php echo ($selected_case_type == '')?'selected':''; ?>>All</option>
							<?php foreach($case_types as $k=>$case_type) { ?>
                            <option value="<?php echo $case_type['id']; ?>" <?php echo ($case_type['id'] == $selected_case_type)?'selected':''; ?>><?php echo $case_type['case_type_desc']; ?></option>
							<?php } ?>
                        </select>
						

                        <font face="Verdana" size="2">Diary/Filing No:
                        <input type="number" name="filing_no" id="filing_no" value="<?php echo $filing_no_no;  ?>" >

                        <font face="Verdana" size="2"> </font>
                    	<font face="Verdana" size="2">From filing date:
                        <input type='date' id="from_date" name="from_date" value="<?php echo $from_date_display; ?>">
						

                        <font face="Verdana" size="2">To filing date:
                        <input type='date' id="to_date" name="to_date" value="<?php echo $to_date_display; ?>">


                        <input type="button" onclick="submitForm3()" value="Search" >
						<input type="button" onclick="reset_case()" value="Reset" >
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
$total_records = refiled_cases($db, $location_access, 'Y', 'NA', $selected_case_type, '',$from_date,$to_date);
if($selected_case_type == ''){
	$show_total = 'cases';
}else{
	$show_total = fn_case_type_name($db, $selected_case_type);
}
    ?>
<p> <font color="red">Total <?php echo $show_total; ?> pending for scrutiny:</font><?php echo count($total_records); ?> </p>
                </td>
            </tr>
            <tr>
                <th>Sr No.</th>
                <th>Date Of Filing</th>
				<th>Date Of Refiling</th>
                <th>Case Type</th>
                <th>Diary/Filing No.</th>
                <th>Main Case Diary/Filing No.</th>
                <th>Title Of Case</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                <?php
$limit = 100;
$total_recordss = refiled_cases($db, $location_access, 'Y', 'NA',  $selected_case_type, '',$from_date,$to_date,0,$filing_no_no);
$total_pages = ceil(count($total_recordss)/$limit);  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit; 
$sn = $start_from+1;
$case_data = refiled_cases($db, $location_access, 'Y', 'NA', $selected_case_type, $limit,$from_date,$to_date,$start_from,$filing_no_no);

if (!empty($case_data) && is_array($case_data)) {
    $i = 1;
    foreach ($case_data as $row) {
        $filing_no = htmlspecialchars($row['filing_no']);
		$main_filing_no = htmlspecialchars($row['filingnumberia']);
        $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
        $case_type = htmlspecialchars($row['case_type_nclat']);
        $patially_defect_remark = htmlspecialchars($row['patially_defect_remark']);
        $patially_defective = htmlspecialchars($row['patially_defective']);
		
		if (in_array($case_type, $appeals)){
		$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row['filing_no']);
		}else{
			$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row['filingnumberia']);
			if(empty($show_party_filing_no))
				$show_party_filing_no = htmlspecialchars($row['filing_no']);
		}
		$pet_name = get_party($db,$show_party_filing_no,$party_flag='P',$party_serial_no=1);
		$res_name = get_party($db,$show_party_filing_no,$party_flag='R',$party_serial_no=1);
        $case_title = $pet_name." <b>VS</b> ".$res_name;
		$subject_id = $row['subjectia'];
        $case_type_display = fn_case_type_name($db, $case_type);
		$days_gap = get_scruitny_days_gap($db,$schemas,$filing_no,$cur_date);
	   $defect_date = get_defective_date($db,$schemas,$filing_no);
	   $remodify_date = get_refile_date($db,$schemas,$filing_no);
		if (in_array($case_type, $appeals)){
			$direct_parent_filing_no = htmlspecialchars($row['filing_no']);
		}else{
			$direct_parent_filing_no = htmlspecialchars($row['filingnumberia']);
		}
		if($patially_defective == '1'){
			$back_color = '#9fbef4';
		}else{
			$back_color = '#f3e7ca';
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
                    <td><?php echo fn_date_formate($dt_of_filing); ?></td>
					<td><?php echo (!empty($remodify_date))?fn_date_formate($remodify_date):''; ?></td>
                    <td><?php echo $case_type_display.$tr_short; ?>
						<br/><a href="javascript:void(0);" data-toggle="tooltip" style="color:#1017da;" title="Days Elapsed : <?php echo $days_gap; ?>"><b>(Defect Date : <?php echo $defect_date; ?>)</b></a>
					</td>
                    <td>
                        <?php echo display_filing_no($filing_no);
        echo "<br><span style='color:red'>";
        echo "(No.of Docs - " . fn_document($db, $filing_no) . ")";
        echo "</span>";
        ?>
                    </td>
					<td><?php echo (!empty($main_filing_no) && $main_filing_no != 'NA')?display_filing_no($main_filing_no):'NA';?>
                    <td><?php echo $case_title; ?> </td>
                    <!--<td> <?php echo fn_section($db, $filing_no); ?></td>-->
                    <td>
                        <?php $filing_no_send = base64_encode($filing_no . '-' . $qq1cc. '-'. $case_type .'-'. $direct_parent_filing_no. '-'. $direct_parent_filing_no);?>
                        <?php
                                if (($row['court'] == '' || $row['court']!='') && $row['cis_user_id'] == '') {
                                    if (($row['filing_through'] == 2 || ($row['filing_through'] == 1 &&  $row['apl0402_not_found'] == 1)) && $row['gst_casetransfer_level'] != 4) {

                                ?>
                                        <h3>
                                            <span class="label label-warning">
                                                <span style="color: #000;">Verification Pending</span>
                                            </span>
                                            <br /><br />
                                            <span class="label label-default">
                                                <a class="disabled" style="color: #000;" href="#" onclick="assignToMyself('<?php echo $_SESSION['id']; ?>','<?php echo $filing_no_send; ?>','<?php echo $_SESSION['user_court']; ?>')">Self Assign</a>
                                            </span>
                                            <br /><br />
                                            <span class="label label-default ">
                                                <a class="disabled" style="color: #000;" href="./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars('1'); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
                                            </span>
                                        </h3>
                                    <?php
                                    } else {
                                    ?>
                                        <h3>
                                            <span class="label label-info">
                                                <a style="color: #FFFFFF;" href="#" onclick="assignToMyself('<?php echo $_SESSION['id']; ?>','<?php echo $filing_no_send; ?>','<?php echo $_SESSION['user_court']; ?>')">Self Assign</a>
                                            </span>
                                            <br /><br />
                                            <span class="label label-default ">
                                                <a class="disabled" style="color: #000;" href="./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars('1'); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
                                            </span>
                                        </h3>
                                    <?php }
                                } else {
                                    ?>
                                    <h3>
                                        <span class="label label-info">
                                            <a style="color: #FFFFFF;"
                                                href="./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars('1'); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
                                        </span>
                                    </h3>
                                <?php } ?>

                    </td>
                </tr>
				<?php
					/* if (in_array($case_type, $main_cases))
					{
					 $connected_ia = get_fresh_connected_IA($db,$filing_no,$location_access,$case_type='35');
					 if(!empty($connected_ia)){
						foreach($connected_ia as $key=>$row){
							$main_parent_filing_no = $filing_no;
							$direct_parent_filing_no = htmlspecialchars($row['filingnumberia']);
							$filing_no_ia = htmlspecialchars($row['filing_no']);
							$dt_of_filing_ia = htmlspecialchars($row['dt_of_filing']);
							$case_type_ia = htmlspecialchars($row['case_type_nclat']);
							$pet_name = get_party($db,$main_parent_filing_no,$party_flag='P',$party_serial_no=1);
							$res_name = get_party($db,$main_parent_filing_no,$party_flag='R',$party_serial_no=1);
							$case_title = $pet_name." <b>VS</b> ".$res_name;
							$case_type_display = fn_case_type_name($db, $case_type_ia);
							$subject_id = $row['subjectia'];
							$remodify_date_ia = get_refile_date($db,$schemas,$filing_no_ia);
							?>
							<tr style="background-color: #BDFCC9;">
								<td></td>
								<td><?php echo fn_date_formate($dt_of_filing_ia); ?></td>
								<td><?php echo (!empty($remodify_date_ia))?fn_date_formate($remodify_date_ia):''; ?></td>
								<td><?php echo $case_type_display; ?></td>
								<td>
									<?php echo display_filing_no($filing_no_ia);
									echo "<br><span style='color:red'>";
									echo "(No.of Docs - " . fn_document($db, $filing_no_ia) . ")";
									echo "</span>";
									?>
								</td>
								<td><?php echo (!empty($direct_parent_filing_no) && $direct_parent_filing_no != 'NA')?display_filing_no($direct_parent_filing_no):'NA';?>
								<td><?php echo $case_title; ?> </td>
								<td><?php echo get_subject($db,$subject_id); ?> </td>
								<!--<td> <?php echo fn_section($db, $filing_no_ia); ?></td>-->
								<td>
									<?php $filing_no_send = base64_encode($filing_no_ia . '-' . $qq1cc. '-'. $case_type_ia .'-'. $direct_parent_filing_no. '-'. $main_parent_filing_no);?>
									<h3>
										<span class="label label-info">
											<a style="color: #FFFFFF;"
												href="./scrutiny/level_one.php?ccase=<?php echo htmlspecialchars('1'); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
										</span>
									</h3>
								</td>
							</tr>
							<?php
						}
					 }
					} */
				?>
                <?php $sn++;}?>
<tr>
<td colspan="7">
                <div align="center">
<ul class='pagination text-center' id="pagination">
<?php if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++):  

$clas_active='';
if($_REQUEST['page'] == $i) { 
    $clas_active = 'active';
}

			if($i == 1):?>
            <li class='<?php echo $clas_active; ?>'  id="<?php echo $i;?>"><a href='index.php?page=<?php echo $i;?>&selected_case_type=<?php echo $selected_case_type; ?>&c_case=<?php echo $c_case; ?>'><?php echo $i;?></a></li> 
			<?php else:?>
			<li class='<?php echo $clas_active; ?>' id="<?php echo $i;?>"><a href='index.php?page=<?php echo $i;?>&selected_case_type=<?php echo $selected_case_type; ?>&c_case=<?php echo $c_case; ?>'><?php echo $i;?></a></li>
		<?php endif;?>			
<?php endfor;endif;?>  
</ul>
</div>
</td>
</tr>
                <?php
} else {?>
                <tr>
                    <td colspan="7">
                        <font color="red" size="2"> </font>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>

<script>
	   $(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
</script>

<?php $filing_no_no = isset($_REQUEST['filing_no'])? $_REQUEST['filing_no'] :''; 
$user_court = $_SESSION['user_court'];
 if (!is_numeric($filing_no_no) && !empty($filing_no_no)) {
    echo "Invalid Input";
    die();
        }
	  $selected_case_type = isset($_REQUEST['selected_case_type'])? $_REQUEST['selected_case_type'] :'';
	  $from_date = (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']))? date('Y-m-d',strtotime($_REQUEST['from_date'])) :'';
      $to_date = (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))?date('Y-m-d',strtotime($_REQUEST['to_date']))  :'';
      $from_date_display = (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']))?$_REQUEST['from_date'] :'';
      $to_date_display = (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))?$_REQUEST['to_date'] :'';
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
                <th>Sr No.</th>
                <th>Date Of Filing</th>
                <th>Case Type</th>
                <th>Diary/Filing No.</th>
				<th>Main Case Diary/Filing No.</th>
                <th>Title Of Case</th>
                <th></th>
            </tr>
            </thead>

            <?php 
	$main_cases = main_case_type();
    $total_records = defective_cases($db, $user_court, $location_access,$from_date,$to_date, 'Y', 'NA', $selected_case_type, '',$filing_no_no);
$limit = 100;
$total_pages = ceil(count($total_records)/$limit);  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit; 
$sn = $start_from+1;
$case_data = defective_cases($db, $user_court, $location_access,$from_date,$to_date, 'Y', 'NA', $selected_case_type, $limit,$start_from,$filing_no_no);
if (!empty($case_data) && is_array($case_data)) {
    $ii = 1;
    foreach ($case_data as $row) {
        $filing_no = htmlspecialchars($row['filing_no']);
        $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
        $case_type = htmlspecialchars($row['case_type_nclat']);
		if (in_array($case_type, $main_cases)){
			$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row['filing_no']);
		}else{
			$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row['filingnumberia']);
			if(empty($show_party_filing_no))
				$show_party_filing_no = htmlspecialchars($row['filing_no']);
		}
		$main_filing_no = htmlspecialchars($row['filingnumberia']);
		$pet_name = get_party($db,$show_party_filing_no,$party_flag='P',$party_serial_no=1);
		$res_name = get_party($db,$show_party_filing_no,$party_flag='R',$party_serial_no=1);
		$case_title = $pet_name." <b>VS</b> ".$res_name;
        $case_type_display = fn_case_type_name($db, $case_type);
        $filing_date_all = fn_date_formate($dt_of_filing);
        // $E_nameP =  fn_case_party($db,'P',$filing_no);
        // $E_nameR =  fn_case_party($db,'R',$filing_no); 
        $upload_check =  fn_defective_objection($db, $db, $schemas, $filing_no, $server_date);
		$sc_correction =  fn_scrutiny_correction($db, $filing_no);
		$subject_id = $row['subjectia'];
		$scrutiny_info = get_scrutiny_info($db,$schemas,$filing_no);
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
        //if ($upload_check != '0') {
            ?>


            <tr style="background-color: #FFD580;">
                <td><?php echo $sn; ?></td>
                <td><?php echo fn_date_formate($dt_of_filing); ?> </td>
                <td><?php echo $case_type_display.$tr_short; ?></td>
                <td><?php echo display_filing_no($filing_no); ?></td>
				<td><?php echo (!empty($main_filing_no) && $main_filing_no != 'NA')?display_filing_no($main_filing_no):'NA';?>
                <td><?php   echo $case_title; ?> </td>
                <td>
                    <h4><span class="label label-info">
                            <?php
                $filing_nosend = $filing_no . '-' . $qq1cc. '-'. $case_type .'-'. $direct_parent_filing_no. '-'. $direct_parent_filing_no;
            $filing_no_send = base64_encode($filing_nosend);
			$path = $_SERVER['HTTP_HOST'].'/nclat';	
             if ($upload_check != '0'  and  ($sc_correction=='' ) ){
                ?>
                    
                            <?php
            } else { ?>

                            <a style="color: #FFFFFF;" target="_blank"
                                href="./scrutiny/readpdf.php?path=<?php  echo urlencode($scrutiny_info['defect_pdf_path']); ?>">Defect Pdf</a>

                            <?php
} ?>
                        </span></h4>
                </td>

            <!--    <td>
                
                    <h4><span class="label label-info">
                    <a style="color: #FFFFFF;" href="javascript:void(0);" onClick="return register_with_defect('<?php echo $filing_no; ?>','<?php echo $case_type; ?>','ajax/computation_note.php');" >List case with defect</a>
                    </span></h4>
                
                </td>  -->
				
            </tr>


            <?php
$sn++;
       // }
  
} ?>
            <tr>
                <td colspan="7">
                    <div align="center">
                        <ul class='pagination text-center' id="pagination">
                            <?php 
                            
                            $page_no = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
                            if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++):  
                            $clas_active='';
                            if($page_no == $i) { 
                                $clas_active = 'active';
                            }
			if($i == 1):?>
                            <li class='<?php echo $clas_active; ?>' id="<?php echo $i;?>"><a
                                    href='index.php?c_case=<?php echo $c_case;?>&page=<?php echo $i;?>'><?php echo $i;?></a></li>
                            <?php else:?>
                            <li class='<?php echo $clas_active; ?>' id="<?php echo $i;?>"><a href='index.php?c_case=<?php echo $c_case;?>&page=<?php echo $i;?>'><?php echo $i;?></a>
                            </li>
                            <?php endif;?>
                            <?php endfor;endif;?>
                        </ul>
                    </div>
                </td>
            </tr> 
            <?php 
} else { 
    ?>
            <tr>
                <td colspan="7">
                    <font color="red" size="2"> Data Not Found </font>
                </td>
            </tr>

            <?php }
?>

            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div id="iframemodal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg"  >

    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal_title">PDF</h4>
      </div>
      <div class="modal-body" id="modal_body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>

  function view_pdf(pdfpath)
  {
	  var loader = "<center><img src='../loader/loader.gif'></img></center>";
	  $.ajax({
            type: "POST",
            url: "scrutiny/readpdf_file.php",
            data: {path:pdfpath},
			beforeSend: function() {
				$("#modal_body").html(loader);
				$("#iframemodal").modal('show');
			},
            success: function (data) {
			$("#modal_body").html(data);
			   //alert("success");
            },
            error: function (textStatus, errorThrown) {
				$("#modal_body").html('');
				$("#iframemodal").modal('hide');
               alert("error");
            }

        });
	  $("#modal_body").html('');
	   //var path = pdfpath+filing_no+"-"+count+".pdf";
	  //alert(path);
	 //var frame = "<iframe  src=https://docs.google.com/viewer?url="+path+"&embedded=true style='width:100%;height:500px;'></iframe>";
	 /* var frame = "<iframe  src=http://"+pdfpath+" style='width:100%;height:500px;' allowfullscreen></iframe>";
	 $("#modal_body").html(frame);
	 $("#iframemodal").modal('show'); */
  }

  </script>

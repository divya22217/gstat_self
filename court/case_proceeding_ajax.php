
<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include("../master/functions.php");

$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$username = $_SESSION['user_actual_name'];


if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$data = $_POST;
	//echo "<pre>";print_r($data);
	$filing_no = $data['filing_no'];
	$parent_filing_no = $data['parent_filing_no'];
	$listing_date = $data['listing_date'];
	$bench_no = $data['bench_no'];
	$list_flag = $data['list_flag'];
	$child_or_connected = $data['child_or_connected'];
	$pen_dis = $data['status'];
	$for_stay = $data['for_stay'];
	$court_no = $data['court_no'];
	$checkbox_id = $data['checkbox_id'];

	$st="select supply_disputed_questions,refile_count from e_case_detail where filing_no = ?";
	$st=$db->prepare($st);
	$st->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st->execute();
	$case_detail = $st->fetch();
	$refile_count = $case_detail['refile_count'];
	$supply_disputed_questions = $case_detail['supply_disputed_questions'];

	$st="select regis_date,list_with_defect from $schemas.case_detail where filing_no = ?";
	$st=$db->prepare($st);
	$st->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st->execute();
	$case_detail = $st->fetch();
	$list_with_defect = $case_detail['list_with_defect'];
	$registration_date = $case_detail['regis_date'];

	$query="select bench_nature from $schemas.bench where from_list_date = ? and bench_no = ?";
	$st=$db->prepare($query);
	$st->bindParam(1, $listing_date, PDO::PARAM_STR);
	$st->bindParam(2, $bench_no, PDO::PARAM_STR);
	$st->execute();
	$list_before_link = $st->fetchColumn();
	
	
	$st="select * from $schemas.case_disposal where filing_no = ? order by entry_date desc limit 1";
	$st=$db->prepare($st);
	$st->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st->execute();
	$disposal_data = $st->fetchAll();
	
	if(!empty($disposal_data)){
		echo "<table><tr><td style='color:red;'>Case Disposed</td></tr></table>";
		die;
	}
	//echo "select * from $schemas.case_proceeding where filing_no = '$filing_no' and listing_date = '$listing_date' and bench_no = '$bench_no' and court_no = '$court_no' order by entry_date desc limit 1"; die;
	$query = "select * from $schemas.case_proceeding where filing_no = ? and listing_date = ? and bench_no = ? and court_no = ? order by entry_date desc limit 1";
	$is_case_proceeded= $db->prepare($query);
	$is_case_proceeded->bindParam(1, $filing_no, PDO::PARAM_STR);
	$is_case_proceeded->bindParam(2, $listing_date, PDO::PARAM_STR);
	$is_case_proceeded->bindParam(3, $bench_no, PDO::PARAM_STR);
	$is_case_proceeded->bindParam(4, $court_no, PDO::PARAM_STR);
	$is_case_proceeded->execute();
	$res = $is_case_proceeded->fetchAll();
	//echo "<pre>"; print_r($res); die;
	 if(!empty($res)){
		$already_proceeded = true;
		$res = array_shift($res);
		$pen_dis_db = (!empty($res['todays_status']))?$res['todays_status']:'';
		$proceeded_next_list_date = (!empty($res['next_list_date']))?$res['next_list_date']:'';
		$next_list_date_selection_option = (!empty($res['next_list_date_selection_option']))?$res['next_list_date_selection_option']:0;
		$next_list_date_selection_type = (!empty($res['next_list_date_selection_type']))?$res['next_list_date_selection_type']:0;
		$next_list_date_selection_type_value = (!empty($res['next_list_date_selection_type_value']))?$res['next_list_date_selection_type_value']:'';
		$proceeded_next_list_date = (!empty($res['next_list_date']))?$res['next_list_date']:'';
		$next_list_court = (!empty($res['next_listing_court']))?$res['next_listing_court']:0;
		if($next_list_date_selection_option == '2'){
			$proceeded_next_list_date = '';
		}
		if($proceeded_next_list_date != ''){
			list($py,$pm,$pd) = explode('-',$proceeded_next_list_date);
			$proceeded_next_list_date_show = $pd.'/'.$pm.'/'.$py;
		}
		$proceeded_purpose = (!empty($res['purpose']))?$res['purpose']:'';
		$proceeded_next_list_purpose = (!empty($res['next_list_purpose']))?$res['next_list_purpose']:'';
		$proceeded_todays_action = (!empty($res['todays_action']))?$res['todays_action']:'';
		$proceeded_remarks = (!empty($res['remarks']))?$res['remarks']:'';
		$for_stay = (!empty($res['for_stay']))?$res['for_stay']:0;
	}else{
		$proceeded_todays_action=$proceeded_next_list_purpose=$proceeded_purpose=$proceeded_next_list_date=$status=$proceeded_next_list_date_show=$proceeded_remarks='';
		$next_list_date_selection_option = 1;
		$next_list_date_selection_type = 1;
		$next_list_date_selection_type_value = '';
	} ?> 
	<table>
		<tr>
			<td align="right" width="150"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">*Todays Status</font></td>

			<td align="left"><input type="radio" id='status_pen_<?php echo $filing_no; ?>' name="pen_dis<?php echo $filing_no; ?>" value="P" <?php echo ($pen_dis=="P")?'checked':'';?> onchange="return getform('<?php echo $checkbox_id; ?>'	,'<?php echo $filing_no; ?>','<?php echo $parent_filing_no; ?>','<?php echo $listing_date; ?>','<?php echo $bench_no; ?>','<?php echo $list_flag; ?>','<?php echo $court_no; ?>','<?php echo $child_or_connected ?>','P')"><b>Pending</b>
			<input type="radio" id='status_dis_<?php echo $filing_no; ?>' name="pen_dis<?php echo $filing_no; ?>" value="D" <?php echo ($pen_dis=="D" || $pen_dis=="X")?'checked':'';?> onchange="return getform('<?php echo $checkbox_id; ?>','<?php echo $filing_no; ?>','<?php echo $parent_filing_no; ?>','<?php echo $listing_date; ?>','<?php echo $bench_no; ?>','<?php echo $list_flag; ?>','<?php echo $court_no; ?>','<?php echo $child_or_connected ?>','D')"><b>Disposal</b>
			</td>
		</tr>
		<!--<tr><td align="right" width="150"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Stay by GSTAT
		</font></td>

		<td align="left"><input type="radio" id='for_stay_<?php echo $filing_no; ?>' name="for_stay<?php echo $filing_no; ?>" value="1" <?php if($for_stay=="1") print "checked";?> ><b>Yes</b>
		<input type="radio" id='for_stay_<?php echo $filing_no; ?>' name="for_stay<?php echo $filing_no; ?>" value="0" <?php if($for_stay=="0" or $for_stay=='')	print "checked";?> >
		<b>No</b></td>
		</tr>-->
		<?php

		//===================pending==============================================================
		if($pen_dis=='P' or $pen_dis=='')
		{?>
		<tr><td align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">* Todays Action Type</font></td>
		<?php
			if($list_with_defect == 1 && empty($registration_date) && $list_before_link == '3')
				$query = "select * from $schemas.master_action where status='P' and action_code in (45,47)";
			else if($list_with_defect == 1 && empty($registration_date) && $list_before_link != '3')
				$query = "select * from $schemas.master_action where status='P' and action_code in (44,45)";
			else
				$query = "select * from $schemas.master_action where status='P' and action_code not in (44,45,47)";

			$st= $db->prepare($query);
			$st->execute();
		?>
		<td align="left" colspan="2">
		<select name="action_type<?php echo $filing_no; ?>" id="action_type<?php echo $filing_no; ?>" style="width: 250px;" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
		<option value="">Select</option>
		<?php  //if($action_type=='') $action_type=3;

		while ($row2 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		$action_code=$row2['action_code'];
		?>
		<option <?php echo ($proceeded_todays_action==$action_code)?'selected':''; ?> value="<?php echo htmlspecialchars($action_code); ?>">
		<?php echo htmlspecialchars(ucwords($row2['action_type']));?></option><?php
		} ?>
		</select>
		</td>
		</tr>
		<?php
		$purpose_code='';
		$purpose_old='';
		$st= $db->prepare("select purpose from $schemas.case_allocation where filing_no=? order by listing_date desc limit 1");
		$st->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st->execute();
		$purpose_old =$st->fetchColumn();
		?>
		<?php  $purpose_old = isset($_REQUEST['purpose_old']) ? $_REQUEST['purpose_old'] :'';?>
		<?php  $purpose_code = isset($_REQUEST['purpose_code']) ? $_REQUEST['purpose_code'] :'';?>
		<input type="hidden" name="purpose_old<?php echo $filing_no; ?>" value="<?php echo htmlspecialchars($purpose_old); ?>">
		<tr>
		<td align="right" nowrap="nowrap"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" nowrap="nowrap">
		<span class="error">*</span>Next listing purpose</font>
		</td>

		<td colspan="2">
		<select name="purpose_code<?php echo $filing_no; ?>" id="purpose_code<?php echo $filing_no; ?>"  style="width: 250px" onChange="return set_remove_lisitng_date('<?php echo $filing_no; ?>',this.value)">
		<option value="">Select</option>
		<?php
		$display='Y';
		$st= $db->prepare("select * from $schemas.master_purpose where display=?  order by purpose_name asc");
		$st->bindParam(1, $display, PDO::PARAM_STR);
		$st->execute();
		while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		$purposecode=$row['purpose_code'];
		?>
		<option <?php echo ($proceeded_next_list_purpose==$purposecode)?'selected':''; ?> value="<?php echo htmlspecialchars($purposecode);?>" >
		<?php echo htmlspecialchars(ucwords($row['purpose_name']));?>
		</option>
		<?php
		}

		?>
		</select>


		</td>

		</tr>

		<tr id="listdate_option_element<?php echo $filing_no; ?>">
		<td align="left" nowrap="nowrap"><div align="right">
		<span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Chose Option </div></td>

		<td width="50">
		<?php  $choose_option = isset($_REQUEST['choose_option']) ? $_REQUEST['choose_option'] :1;?>
		<label class="radio-inline">
	      <input type="radio" class="choose_option" name="choose_option_<?php echo $filing_no; ?>" id="fixed_<?php echo $filing_no; ?>" value="1" <?php echo ($next_list_date_selection_option == '1')?'checked':''; ?> onChange="return changePendingForm(this.value,<?php echo $filing_no ?>);">Fixed
	    </label>
	    <label class="radio-inline">
	      <input type="radio" class="choose_option" name="choose_option_<?php echo $filing_no; ?>" id="not_fixed_<?php echo $filing_no; ?>" value="2" <?php echo ($next_list_date_selection_option == '2')?'checked':''; ?> onChange="return changePendingForm(this.value,<?php echo $filing_no ?>);">Not Fixed
	    </label>
		</td>

		</tr>

		<?php if($next_list_date_selection_option != '1'){
			$style = "display:none";
		}else{
			$style_not_fixed = "display:none";
		} ?>

		<tr style="<?php echo $style; ?>" id="list_date_element<?php echo $filing_no; ?>">
		<td align="left" nowrap="nowrap"><div align="right">
		<span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Next Listing Date </div></td>

		<td width="50">

		<input type="text" required  autocomplete="off" name="next_list_date<?php echo $filing_no; ?>"  class="datepickerGreater" readonly id="datepickerGreaterR<?php echo $filing_no; ?>" size="10" maxlength="10" value="<?php print htmlspecialchars($proceeded_next_list_date_show); ?>" data-date-format="dd/mm/yyyy"/>
		</td>

		</tr>

		<tr style="<?php echo $style_not_fixed; ?>" id="not_fixed_element<?php echo $filing_no; ?>">
		<td align="left" nowrap="nowrap"><div align="right">
		<span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Select Option </div></td>

		<td width="50">
		<?php  $not_fixed_element = isset($_REQUEST['not_fixed_element']) ? $_REQUEST['not_fixed_element'] :1;?>
		<label class="radio-inline">
	      <input type="radio" class="not_fixed_element" name="not_fixed_element_<?php echo $filing_no; ?>" id="days_<?php echo $filing_no; ?>" value="1" <?php echo ($next_list_date_selection_type == '1')?'checked':''; ?>>Days
	    </label>
	    <label class="radio-inline">
	      <input type="radio" class="not_fixed_element" name="not_fixed_element_<?php echo $filing_no; ?>" id="weeks_<?php echo $filing_no; ?>" value="2" <?php echo ($next_list_date_selection_type == '2')?'checked':''; ?>>Weeks
	    </label>
	    <label class="radio-inline">
	      <input type="radio" class="not_fixed_element" name="not_fixed_element_<?php echo $filing_no; ?>" id="month_<?php echo $filing_no; ?>" value="3" <?php echo ($next_list_date_selection_type == '3')?'checked':''; ?> >Months
	    </label>
	    <?php  $not_fixed_date = isset($_REQUEST['not_fixed_date']) ? $_REQUEST['not_fixed_date'] :'';?>
	    <label class="radio-inline">
	      <input type="number" class="not_fixed_date" name="not_fixed_date_<?php echo $filing_no; ?>" id="not_fixed_date_<?php echo $filing_no; ?>" value="<?php echo $next_list_date_selection_type_value; ?>">
	    </label>
		</td>

		</tr>

		<tr id="list_date_court<?php echo $filing_no; ?>">
		<td align="left" nowrap="nowrap"><div align="right">
		<span class="error"></span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
		Next Listing Court </div></td>

		<td width="50">
		<?php // $next_list_court = isset($_REQUEST['next_list_court']) ? $_REQUEST['next_list_court'] :'';?>
		<select name="next_list_court<?php echo $filing_no; ?>" id='next_list_court<?php echo $filing_no; ?>'>
		<option value="">Select</option>
		<?php
		$display='Y';
		$st= $db->prepare("select * from $schemas.court where court_no < 50  order by court_no asc");
		$st->execute();
		while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		$selected_court=$row['court_no'];
		?>
		<option <?php echo ($next_list_court==$selected_court)?'selected':''; ?> value="<?php echo htmlspecialchars($selected_court);?>" >
		<?php echo htmlspecialchars(ucwords($row['display_court_text']));?>
		</option>
		<?php
		}

		?>
		</select>
		</td>

		</tr>

			<?php 
			} 
		//=======================code for disposal=================================================
		else
		{
		?>

		<tr>
		<td align="left" nowrap="nowrap">
		<div align="right"><span class="error">*</span>Disposal Nature
		</div>
		</td>
		<td colspan="2">
		<select size="1" style="width: 250" name="disposal_nature<?php echo $filing_no; ?>" id="disposal_nature<?php echo $filing_no; ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)" onChange="return changeForm(this.value,'<?php echo $filing_no; ?>');">
		<option value="">Select</option>
		<?php
		$disposal_nature = '';
		if($list_with_defect == 1 && empty($registration_date)){
			if($supply_disputed_questions == '1' && $refile_count == '1'){
			if($_SESSION['menuaccess_codeall'] != '6' && $_SESSION['menuaccess_codeall'] != '11') {
			$query = "select * from $schemas.master_action where status='D' and action_code in (2,3,23,38,39,41,51,52)";
				}else{
					$query = "select * from $schemas.master_action where status='D' and action_code in (2,3,23,39,41,51)";
				}
			}else{
				$query = "select * from $schemas.master_action where status='D' and action_code in (2,3,23,38,39,41,52)";
			}
		}
		else{
			$query = "select * from $schemas.master_action where status='D' and action_code not in (38,52)";
		}
		
		$st= $db->prepare($query);
		$st->execute();
		while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		$actioncode = $row['action_code']; ?>
		<option value="<?php echo htmlspecialchars($row['action_code']) ?>" <?php echo ($disposal_nature == $actioncode)?'selected':''; ?>><?php echo htmlspecialchars($row['action_type']) ?></option>
		<?php }
		?>
		</select>
		</td>
		</tr>
		<tr id="set_disposal_date<?php echo $filing_no; ?>">
		<td align="left" nowrap="nowrap">
		<div align="right"><span class="error">*</span>Disposal Date
		</div>
		</td>
		
		<td colspan="2">
		<input type="text"  autocomplete="off" name="disposal_date<?php echo $filing_no; ?>" id="disposal_date<?php echo $filing_no; ?>" style="width: 250" readonly="readonly" size="10" maxlength="10" class="datepicker" value="<?php //print htmlspecialchars($d_date); ?>"/>
		</td>
		</tr>

		<tr style="display:none;" id="partial_disposal_date<?php echo $filing_no; ?>">
			<td align="left" nowrap="nowrap">
				<div align="right"><span class="error">*</span>Partial Disposal Time
				</div>
			</td>
			<td colspan="2">
				<input type="number" placeholder="Number Of Weeks" style="width: 250" name="partial_disposal_weeks<?php echo $filing_no; ?>" id="partial_disposal_weeks<?php echo $filing_no; ?>" class="form-control"> <span>Weeks</span> <input type="number" placeholder="Number Of Months" style="width: 250"  name="partial_disposal_months<?php echo $filing_no; ?>" id="partial_disposal_months<?php echo $filing_no; ?>" class="form-control"> Months
			</td>
		</tr>


		<?php
		}// close of else condition
		?>


		<tr>

	<tr>
	<td width="200" align="right"><font face="Verdana" size="2">Proceeding Remark</font></td>
	<td width="400" align="left">
	<textarea class="formInput" placeholder="Your Message" id="remarks<?php echo $filing_no; ?>" name="remarks<?php echo $filing_no; ?>"
	maxlength="1400" cols="50" rows="2" onFocus="SetBg(this)" onBlur="UnSetBg(this)"><?php echo $proceeded_remarks; ?></textarea>
	</td>
	</tr> 
</table>

<script>
//date is equal and less then current date
$( function() {
    $( ".datepickerToday" ).datepicker({
        inline: true,
        showOtherMonths: true,
        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        changeMonth: true,
        changeYear: true,
        maxDate: "getDate()",
        setDate: "myServerDate",
       // yearRange: "2000:'+(new Date).getFullYear()",
        dateFormat: "dd/mm/yy"
    });
    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }
        return date;
    }
} );



//date is equal and greater then current date
$( function() {
    $( ".datepickerGreater" ).datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: "getDate()",
        setDate: "myServerDate",
        //yearRange: "2000:'+(new Date).getFullYear()",
        dateFormat: "dd/mm/yy"
    });
    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }
        return date;
    }
} );
</script>
<?php 		
}
	/* }else if($status == 'D'){
		
	}else{
	} */

?>

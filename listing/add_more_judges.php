<?php
include("../db_inc1.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
$schemas=htmlspecialchars($_SESSION['schema_name']);
$display = 'TRUE';
$data=$db->prepare("select mj.judge_name, mj.judge_desg_code,mj.judge_code,md.desg_name from $schemas.master_judge as mj left join $schemas.master_desg as md on md.desg_code = mj.judge_desg_code where mj.display=? order by mj.judge_code");
$data->bindParam(1, $display, PDO::PARAM_STR);
$data->execute();
$data = $data->fetchAll();
$judge_count = rand(); ?>
<label for="bench" class="col-sm-2 col-form-label" id="judge_count_label<?php echo $judge_count; ?>"><font color="red">*</font></span></font>Select Member</label>
	<div class="col-sm-10" id="judge_count<?php echo $judge_count; ?>">
<select name="judge[]" class="form-control"  id="judge_count_select<?php echo $judge_count; ?>">
<?php foreach($data as $key=>$row){ ?>
		<option value="<?php echo $row['judge_code']; ?>"><?php echo $row['judge_name'].' '.$row['desg_name']; ?></option>
<?php } ?>
</select><button type="button" name="remove_judge" id="remove_judge_btn<?php echo $judge_count; ?>" onClick="return remove_judges('<?php echo $judge_count; ?>');" class="btn btn-sm btn-danger" style="float:right;">Remove</button><br>
	</div>
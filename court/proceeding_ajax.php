
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

	$type = $_POST['type'];

	if($type == 'additional_parameters'){
		$filing_no = $data['filing_no'];
		$action_type = $data['action_type'];
		if (in_array($action_type, array(31,8,38))){ ?>
			<tr class="additional_param">
				<td align="right">
					<font face="Verdana, Arial, Helvetica, sans-serif" size="2">* Todays Action Type
					</font>
				</td>
				<td>
					<input type="text" required  autocomplete="off" name="additional_param1<?php echo $filing_no_link1; ?>"   readonly id="additional_param1<?php echo $filing_no_link1; ?>" />
				</td>
			</tr>
			<tr class="additional_param">
				<td align="right">
					<font face="Verdana, Arial, Helvetica, sans-serif" size="2">* Todays Action Type
					</font>
				</td>
				<td>
					<input type="text" required  autocomplete="off" name="additional_param1<?php echo $filing_no_link1; ?>"   readonly id="additional_param1<?php echo $filing_no_link1; ?>" />
				</td>
			</tr>
			<tr class="additional_param">
				<td align="right">
					<font face="Verdana, Arial, Helvetica, sans-serif" size="2">* Todays Action Type
					</font>
				</td>
				<td>
					<input type="text" required  autocomplete="off" name="additional_param1<?php echo $filing_no_link1; ?>"   readonly id="additional_param1<?php echo $filing_no_link1; ?>" />
				</td>
			</tr>
	<?php	}else{
				echo 403;
			}
	}
	
}

?>
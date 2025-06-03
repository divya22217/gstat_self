<?php 

include "../db_inc1.php";

if (empty($_SESSION['user']) and $_SESSION['location'] == '') {

    die("Redirecting to login.php");
}
if (($_SESSION['user']) == '') {
    echo "you Can't access this page";
} else {

	$query = "select * from master_states where state_id != 0";
    $st = $db->prepare($query);
    $st->execute();
    $states = $st->fetchAll();

?>
	<tr>
        <td>
            <select name="tax_state_id[]" class="form-control select">
                <option value='0' selected>Select</option>
                <?php 
                foreach ($states as $key => $value) { ?>
	                <option value='<?php echo $value['state_id'] ?>'><?php echo $value['state_name']; ?></option>
	            <?php } ?>
            </select>
        </td>
        <td><input type="number" id="" name="tax_amount[]" class="form-control" value="" style="display: inline-block; width: 92%;">
        <span class="btn btn-sm btn-danger remove-row rounded" style="float:right">-</span>
        </td> 
    </tr>
	
<?php

}

?>
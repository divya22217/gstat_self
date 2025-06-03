<script type="text/javascript" language="javascript">
function DisableBackButton() {
window.history.forward()
}
DisableBackButton();
window.onload = DisableBackButton;
window.onpageshow = function(evt) {
if (evt.persisted) DisableBackButton()
}
window.onunload = function() {
void (0)
}
</script>
<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../includes/db_inc.php");//database connection
session_start();
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$deptloing=$_SESSION['dept'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem.....";
header("Location: ../login.php");
die();
}


setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
die("Redirecting to login.php");
}

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{

// This code not use next time .......	Schema session create Hear....

$sessionUserType=htmlspecialchars($_SESSION['id']);
$stlu = $db->prepare("select location,schema_id from users where id= ? ");
$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);
$stlu->execute();
while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$locationq=$row['location']; 
$schema_idrun=$row['schema_id'];
}


if($_SESSION['location'] != $locationq)
{
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
echo "You are not Valied User..... please login again";
header("Location: ../login.php");
die();
}
if($locationq =='')
{
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
echo "You are not Valied User..... please login again";
header("Location: ../login.php");
die();
}

$_SESSION['form_token'] = time().md5(uniqid(rand(), TRUE));

?>
<!DOCTYPE html>
<html>
<head>
<title>EPFO || <?php
$urlll = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
$url_var = explode('/' , $urlll);
echo ucwords(end($url_var)); ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?php include("../includes/common_js.php");?>
<link href="../includes/plugins/parsley/parsley.css" rel="stylesheet">
<script type="text/javascript" src="../includes/plugins/parsley/parsley.js"></script>
<link href="../includes/plugins/select_2/select2.min.css" rel="stylesheet" />
<script src="../includes/plugins/select_2/select2.min.js"></script>
<script>
function submitForm()
{
with(document.form)
{
action = "<?php echo $_SERVER[SCRIPT_NAME];?>";
submit();
}
}
</script>
</head>
<body id="top">
<?php include("../includes/header.php");?>

<div class="wrapper row3">
<main class="hoc container clear">
<?php if($_SESSION[suss_message] !=''){?>
<span style="color: red;">
<?php echo htmlspecialchars_decode(html_entity_decode(base64_decode($_SESSION[suss_message])));
unset($_SESSION[suss_message]);?></span>
<?php
} ?>

<!--  <fieldset><legend><b>Fresh Filing</b></legend>-->
<form data-parsley-validate="" name="form"action="transfer_user_action.php" method="post" id="demo-form">
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['form_token'];?>">    <!-- main body -->
<!--    <div class="sidebar one_quarter first">-->

<fieldset ><legend><b><u> TRANSFER USER </u></b></legend>

<div class="one_quarter first" style="display:<?php if($localadmin==2){ echo'block'; }else{echo 'none';} ?> ">
<label for="comment">Office Location:<span>*</span></label>
<?php  $epfo_name = isset($_REQUEST['epfo_name']) ? $_REQUEST['epfo_name'] :$schema_idrun;?>
<select name="epfo_name" required="required"  onchange="javascript:submitForm();" >
<option value="">SELECT EPFO LOCATION</option>
<?php
$st = $db->prepare("select schema_id,office_name from master_schema where display='Y' order by office_name ASC");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

$c_type = htmlspecialchars($row['schema_id']);
$office_name = htmlspecialchars(ucwords($row['office_name']));
if($epfo_name == $c_type )
{
print "<option value=".$c_type." selected>".htmlspecialchars(ucwords(strtoupper($office_name)))."</option>";
}
else
{
print "<option value=".$c_type.">".htmlspecialchars(ucwords(strtoupper($office_name)))."</option>";
}
}
?>
</select>
</div>





<div class="one_quarter first" <?php if($localadmin==0){ ?>style="display: none;"<?php } ?> >
<?php  $transfer_user_id = isset($_REQUEST['transfer_user_id']) ? $_REQUEST['transfer_user_id'] :$_SESSION[id];
 $transfer_user_id=htmlspecialchars($transfer_user_id);?>
<label for="comment">Username :<span>*</span></label>            
<select required="required" name="transfer_user_id" onchange="javascript:submitForm();" >
<option value="">--SELECT--</option>
<?php
if($epfo_name!=''){
$st = $db->prepare("select id,username from users where schema_id=? order by username ASC ");
$st->execute(array($epfo_name));
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$user_idddd = $row[id];
if($user_idddd== $transfer_user_id )
{
print "<option value=".$row[id]." selected>".htmlspecialchars(ucwords(strtoupper($row[username])))."</option>";
}
else
{
echo '<option value="'.$row[id].'">'.htmlspecialchars(ucwords(strtoupper($row[username]))).'</option>';
}
}
}
?>
</select>			
</div>



<?php 
if($epfo_name!=''){
$office_na = $db->prepare("select office_name from master_schema where schema_id=?");
$office_na->execute(array($epfo_name));
$office_name=$office_na->fetchColumn();
}
?>
<div class="two_quarter first">
	<label for="comment">Current Place of posting<span>*</span></label>
	<input type="text" disabled="disabled" readonly="readonly" value="<?php echo htmlspecialchars($office_name) ?>"/>
</div>


<div class="two_quarter">
<?php  $desired_location = isset($_REQUEST['desired_location']) ? $_REQUEST['desired_location'] :$_SESSION[schema_idccc]; $desired_location=htmlspecialchars($desired_location);?>
	<label for="comment">New Place of posting<span>*</span></label>            
	<select required="required" name="desired_location" style="width:80%" onchange="javascript:submitForm();">
	<option value="">SELECT OFFICE</option>
	<?php
	$st = $db->prepare("select * from master_schema where display='Y' order by office_name ASC ");
	$st->execute();
	while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{				
	$c_type = htmlspecialchars($row['schema_id']);
	//if($_SESSION[schema_idccc]!=$c_type){
	if($desired_location == $c_type )
	{
	print "<option value=".htmlspecialchars($row['schema_id'])." selected>".htmlspecialchars(ucwords(strtoupper($row['office_name'])))."</option>";
	}
	else
	{
	print "<option value=".htmlspecialchars($row['schema_id']).">".htmlspecialchars(ucwords(strtoupper($row['office_name'])))."</option>";
	}
	//}
	}
	?>
	</select>			
</div>


<div class="two_quarter first">
<label for="name">Designation<span color="red">*</span></label>
<?php $designation = isset($_REQUEST['designation']) ? $_REQUEST['designation'] :''; ?>
<select name="designation"  required="required" id="designation">
<option value="">--select--</option>
<?php
$q_desg ="select * from master_desg ORDER  by position ASC";
$desg_data = $db->prepare($q_desg);
$desg_data->execute();		
if($desg_data->rowCount()>0){
while($desg_row = $desg_data->fetch()){
?>
<option value="<?php echo htmlspecialchars($desg_row[desg_code])?>" 
<?php if($designation==$desg_row[desg_code]){echo 'selected';}?>  >
<?php echo htmlspecialchars($desg_row[desg_name]) ?></option>
<?php }} ?>
</select>
</div>

<div class="two_quarter">
	<label for="comment">Transfer Date<span>*</span></label>
	<input type="text" onKeyup="javascript:addNumbers('date_of_transfer',this.value)" maxlength="10" id="date_of_transfer" name="date_of_transfer" required="required" class="datepicker">
</div>


<div class="two_quarter first">
<label for="name">Remarks if Any<span></span></label>
<textarea name="reason_for"  ></textarea>
</div>


<div class="one_quarter first">
<label for="name">&nbsp;</label>
<input type="submit" name="btnsubmit"  value="Submit" class="btn btn-default ">
</div>

</fieldset>
</form>
<!-- / main body -->
<div class="clear"></div>
</main>
</div>





<?php include("../includes/footer.php");?>
<script type="text/javascript">
$('select').select2();
</script>
</body>
</html>
<?php //if($_SESSION[suss_message] !='' && $_SESSION[localadmin]==0){ session_destroy();session_unset();}?>
<?php }?>

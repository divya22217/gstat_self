<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();
$_SESSION['user'];
$location_access=$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem.....";
//header("Location: ../login.php");
die();
}



setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
die("#2E2E2Eirecting to login.php");
}

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{

// This code not use next time .......	Schema session create Hear....


$sessionUserType=htmlspecialchars($_SESSION['id']);


$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";


include '../inheader.php';
//include '../insidebar.php';

?>
<?php 

$form2 = sha1( uniqid('auth', true) );
$_SESSION['form2_scruniny'] = $form2;
$y='add';
    $display_name ="ADD";
    $display_action ="Submit";
if($_REQUEST[y_id]!='')
{
$y='modify';
$display_name ="MODIFY";
$display_action ="Save";
$sth = $db->prepare("select action_type,action_code,status from $schemas.master_action where action_code=?");
$sth->execute(array($_REQUEST[y_id]));
$get_data = $sth->fetch();
    extract($get_data);
}



 ?>
<div class="content-wrapper" style="min-height: 946px;">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
ACTION MASTER
<small><?php echo $display_name;?></small>
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-dashboard"></i> HOME</a></li>
<li><a href="#">ACTION MASTER</a></li>
<li class="active"><a href="./action_master_report.php">REPORT</a></li>
</ol>
</section>

<!-- Main content -->
<section class="content">
<div class="row">
<!-- left column -->
<div class="col-md-6">
<!-- general form elements -->
<div class="box box-primary">
    <?php if($_SESSION[suss_message] !=''){?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i><?php echo $_SESSION[suss_message]; unset($_SESSION[suss_message]);?></h4>
        </div>
        <?php
    } ?>
<!-- /.box-header -->
<!-- form start -->
<form name="frm" method="post" action="action_master_action.php" onSubmit="return validate();">
<div class="box-body">
<input type="hidden" class="form-control" id="" placeholder="" name="token" value="<?php echo $key; ?>">
<input type="hidden" class="form-control" id="" placeholder="" name="id_val" value="<?php echo $purpose_code; ?>">
<div class="form-group">
<label for="exampleInputEmail1">Action Name</label>
    <?php  $action_type = isset($_REQUEST['action_type']) ? $_REQUEST['action_type'] :$action_type; ?>
<input type="text" class="form-control" id="" placeholder="" name="action_type" value="<?php echo $action_type;?>">
</div>
<div class="form-group">
<label for="exampleInputEmail1">Action For</label>
    <?php  $status = isset($_REQUEST['status']) ? $_REQUEST['status'] :'P'; ?>
	<select class='form-control' name='status'>
		<option value='D' <?php echo ($status = 'D')?'selected':''; ?>>Dispose</option>
		<option value='P' <?php echo ($status = 'P')?'selected':''; ?>>Pending</option>
		
	</select>

</div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($_REQUEST[y_id]);?>">
    <input type="hidden" name="frmAction" value="<?php echo htmlspecialchars($y);?>">
<button type="submit" class="btn btn-primary" onClick="return validate();"><?php echo $display_action?></button>
</div>
</form>
</div>
</div>
</div>

</div>
<!-- /.row -->
<div>
</section>
<!-- /.content -->
</div>


<?php 
include '../infooter.php';
?>
<?php } ?>
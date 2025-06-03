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
$sth = $db->prepare("select * from check_list_local where id=?");
$sth->execute(array($_REQUEST[y_id]));
$get_data = $sth->fetch();
   // print_r($get_data);

}
 ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>NCLT | Dashboard</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
<!-- jvectormap -->
<link rel="stylesheet" href="../bower_components/jvectormap/jquery-jvectormap.css">
<!-- Theme style -->
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">




<script>

function submitForm()
{
with(document.frm)
{
action = "<?php echo $_SERVER['REQUEST_PATH'];?>";
submit();
}
}
</script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include("../inheader.php");
//include '../insidebar.php';
?>

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
SCRUTINY MASTER
<small><?php echo $display_name;?></small>
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-dashboard"></i> HOME</a></li>
<li><a href="#">SCRUTINY MASTER</a></li>
<li class="active"><a href="./scrutiny_master_report.php">REPORT</a></li>
</ol>
</section>

<!-- Main content -->
<section class="content">

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
<form name="frm" method="post" action="scrutiny_master_action.php" onSubmit="return validate();">
<div class="box-body">
<div class="form-group">
<label for="exampleInputEmail1">SELECT BENCH</label>
<?php $bench_id = isset($_REQUEST['bench_id']) ? $_REQUEST['bench_id'] :$get_data[location_all]; ?>
<select name="bench_id" onchange="javascript:submitForm();" class="form-control" >
<?php
$sql="select * from mater_location_city order by city_name ASC";
$schemaName = $db-> prepare($sql);
$schemaName -> execute();
?>
<option value=""> Select </option>
<?php
while ($row =$schemaName->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{
 $ctc=$row['city_id'];
if($bench_id == $ctc)
{
	print "<option value=".htmlspecialchars($row['city_id'])." selected>".htmlspecialchars($row['city_name'])."</option>";
}
else
{
	print "<option value=".htmlspecialchars($row['city_id']).">".htmlspecialchars($row['city_name'])."</option>";
}
}
?>
</select>
</div>

<div class="form-group">
<label for="exampleInputEmail1">Objection</label>
    <?php $objection = isset($_REQUEST['objection']) ? $_REQUEST['objection'] :$get_data[check_list]; ?>
    <textarea class="form-control" id="" placeholder="" name="objection">
<?php echo $objection;?>
    </textarea>

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

</section>
<!-- /.content -->
<?php include '../includes/footer.php'; ?>
<div class="control-sidebar-bg"></div>

</div>
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 

<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard2.js"></script>

<!-- FastClick -->

<!-- AdminLTE App -->

</body>
</html>



<?php } ?>
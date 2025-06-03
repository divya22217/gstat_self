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

if($_SESSION['user'] !='' and $_SESSION['location'] !=''){

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

 ?>
<div class="content-wrapper" style="min-height: 946px;">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
SCRUTINY MASTER
<small>REPORT</small>
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-dashboard"></i> HOME</a></li>
<li><a href="#">SCRUTINY MASTER</a></li>
<li class="active"><a href="./scrutiny_master.php">Add New</a></li>
</ol>
</section>
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


<div class="row">
<!-- left column -->
<div class="col-md-6">
<!-- general form elements -->

<form name="frm" method="post" action="scrutiny_master_action.php" onSubmit="return validate();">
<div class="box-body">
<div class="form-group">
<label for="exampleInputEmail1">SEARCH BY BENCH</label>
<?php $bench_id = isset($_REQUEST['bench_id']) ? $_REQUEST['bench_id'] :'all'; ?>
<select name="bench_id" onchange="javascript:submitForm();" class="form-control" >
<?php
$sql="select * from mater_location_city order by city_name ASC";
$schemaName = $db-> prepare($sql);
$schemaName -> execute();
?>
<option value="all">---ALL---</option>
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

</form>
</div></div>
<!-- Main content -->
<section class="content">



<div class="row"><div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
<thead>
<tr role="row">
<th class="sorting_asc">SR.NO.</th>
<th>Objection</th>
<!--<th>Bench</th>-->
</tr>
</thead>
<tbody>
<?php 
if($bench_id=='all')
{
$sth = $db->prepare("select * from check_list_local order by id ASC");
$sth->execute();
}else{
$sth = $db->prepare("select * from check_list_local where location_all=? order by id ASC");	
$sth->execute(array($bench_id));
}



if($sth->rowCount()==0){
?>                
<tr>
<td  >No Record Found</td>                  
</tr>
<?php } 
else{
$sr_no =1;
$sth_row = $sth->fetchAll();
foreach($sth_row as $row){
?>
<tr role="row" class="odd">
<td><?php echo $sr_no;?></td>
<td><?php echo $row[check_list];?></td>
<!--<td><?php echo $row[location_all];?></td>-->
 <td><a href="scrutiny_master.php?y_id=<?php echo $row[id];?>"><i class="fa fa-edit"></i></a></td>
</tr>


<?php 
$sr_no++;
}
}
?>
</tbody>

</table>
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
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
$y='add';
 ?>
<div class="content-wrapper" style="min-height: 946px;">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
 ACTION MASTER
<small>REPORT</small>
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-dashboard"></i> HOME</a></li>
<li><a href="#">ACTION MASTER</a></li>
<li class="active"><a href="./action_master.php">ADD NEW </a></li>
</ol>
</section>

<!-- Main content -->
<section class="content">
<div class="row">
 <div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
<thead>
<tr role="row">
<th class="sorting_asc">SR.NO.</th>
<th>Purpose Name</th>
<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php 
$sth = $db->prepare("select * from $schemas.master_action where display='TRUE' order by action_code ASC");
$sth->execute();
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
<td><?php echo $row['action_type'];?></td>
<td><?php echo ($row['status']=='P')?'Pending':'Dispose';?></td>
 <td><a href="action_master.php?y_id=<?php echo $row['action_code'];?>"><i class="fa fa-edit"></i></a></td>
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
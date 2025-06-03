<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/simplePagination.css">

<?php 
session_start();
ob_start();
include("../db_inc1.php");
//if we remove db_inc2 then it stops working
date_default_timezone_set("Asia/Kolkata");  

$bench_no='';
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$user_court = $_SESSION['user_court'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
include '../inheader.php';
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
$benchlocation =htmlentities($_REQUEST['bench_location']);
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	
	
date_default_timezone_set("Asia/Kolkata");

$query = "select a.filing_no,concat(ct.short_name,'/',cd.case_no,'/',mlc.short_name,'/',cd.case_year) 
as case_number, 'Hon''ble '||mj.judge_name as judge_name,
a.entry_date as created_at from $schemas.recused_case_judge as a
left join $schemas.case_detail as cd on cd.filing_no = a.filing_no
left join case_type as ct on ct.id = cd.case_type
left join mater_location_city as mlc on mlc.city_id = cd.location_code
left join $schemas.master_judge as mj on mj.judge_code = a.judge_code
where a.is_deleted = 0
order by a.id , a.filing_no";
$recused_judge_query = $db->prepare($query);
$recused_judge_query->execute();
$recused_judges = $recused_judge_query->fetchAll();

?>

	<style>
		.no-border{
		border:none;
		}
		#DataTables_Table_0_filter{
			padding-left:56%;
		}
	</style>
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css"/>
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
<script src="../src/calendar.js"></script>
</head>

<body>   
<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
			<div class="box box-success">
				<div class="box-body">
					<section class="content-header">
						<center><b>Recused Judges</b></center>
					</section>
						<div class="table-responsive" id="table_data">
							<table cellspacing="0" align="center" class="table no-margin table-bordered table-striped table-hover casealloc_table"   cellpadding="2" border="1" width="95%" class="std">
								<thead style="background-color:#846312;color:#ffffff; white-space: nowrap;">
									<th><b>Sr.No.</b></th>
									<th>Filing No</th>
									<th>Case No</th>
									<th>Judge Name</th>
									<th>Recuse Date</th>
								</thead>
								<tbody>
									<?php foreach($recused_judges as $k=>$v){ ?>
										<tr>
											<td><?php echo ++$k; ?></td>
											<td><?php echo $v['filing_no'] ?></td>
											<td><?php echo $v['case_number'] ?></td>
											<td><?php echo $v['judge_name'] ?></td>
											<td><?php echo date('d/m/Y',strtotime($v['created_at'])); ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
				</div>
			</div>
		</div>
	</div>
	
	<input type="hidden" id="all_records" value='<?php echo $total_records; ?>'>
	
<?php } ?>
	
	<!-- computation note modal -->
	<script src="../src/calendar.js"></script>
	<script src="../bower_components/bootstrap/dist/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/dataTables.bootstrap.min.js"></script>
 <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

	
</body>
  
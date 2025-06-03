<link rel="stylesheet" href="../css/bootstrap.min.css">


<?php 
session_start();
ob_start();
include("../db_inc1.php");
//if we remove db_inc2 then it stops working
date_default_timezone_set("Asia/Kolkata");
/* 
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */  

$bench_no='';
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
//$localadmin=$_SESSION['localadmin'];
//$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
include '../inheader.php';
include '../custom/custom_function.php';
//include '../insidebar.php';
//include ('../classes/pagination.class.php');
//$pagination = new pagination(100);
//$userid=$_SESSION['id'];
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
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	$link_scrutiny_idaccess='1';
	$location_id = $_SESSION['location'];
	
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 
$appeals = main_case_type();

function get_short_name($db,$table,$search_column_name,$condtion_column_name,$condtion_column_value){
		$short_name = $db->prepare("select $search_column_name from $table where $condtion_column_name = ? ");
		$short_name->bindParam(1, $condtion_column_value, PDO::PARAM_INT);
		$short_name->execute();
		$short_name = $short_name->fetchColumn();
		return $short_name;
	}


?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<title>Link Caveat</title>
<!-- Bootstrap 3.3.7 -->
<link href="../dist/css/sweetalert.css" rel="stylesheet"/>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../dist/js/sweetalert.min.js"></script>
<script src="../dist/js/sweetalert-dev.min.js"></script>

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
    <!-- Content Header (Page header) -->

					<section class="content-header">
						<center><b>Stay by NCLAT</b></center>
					</section>
					<?php
					
						function fn_judge_list($db, $schemas, $from_list_date, $bench_id)
						{
							 $coram_name = '';
							$coram_name_chairperson = '';
							$sql = "select hon_text,md.desg_name,jm.judge_name,bj.judge_code,jm.judge_desg_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm
						 Join
						 $schemas.master_desg md ON jm.judge_desg_code = md.desg_code
						 where bj.from_list_date ='$from_list_date' and bj.bench_no=? and jm.judge_code=bj.judge_code order by jm.judge_desg_code asc"; 
							$sth_judge = $db->prepare($sql);
							$sth_judge->bindParam(1, $bench_id, PDO::PARAM_STR);
							$sth_judge->execute();
							$judge_data = $sth_judge->fetchAll();
							//$coram_name = '';
							foreach ($judge_data as $value) {
								if($value['judge_desg_code'] == '1' || $value['judge_desg_code'] == '6'  || $value['judge_desg_code'] == '7' || $value['judge_desg_code'] == '8'){
									$coram_name_chairperson .= "<font size='2' >" . $value['hon_text'] . ' ' . $value['judge_name'] . ' (' . $value['desg_name'] . ')';
									$coram_name_chairperson .= "<br>";
								}else{
								$coram_name .= "<font size='2' >" . $value['hon_text'] . ' ' . $value['judge_name'] . ' (' . $value['desg_name'] . ')';
								$coram_name .= "<br>";
								}
							}
							$final_coram = $coram_name_chairperson.$coram_name;
							return $final_coram;

						}
						$msghash=$_REQUEST['msghash'];
						if($msghash !='')
						{
							$msghashz=(base64_decode($msghash));

							$msghashz = explode("@", $msghashz);

							$msg1 = $msghashz[0];
							$case_list_date = $msghashz[1];

							list($cyear,$cmonth,$cday)=explode('-',$case_list_date);

							$case_list_date_dis=$cday.'/'.$cmonth.'/'.$cyear;
						}
					?>

						<div class="table-responsive" id="table_data">
							<table cellspacing="0" align="center" class="table no-margin table-bordered table-striped table-hover casealloc_table"   cellpadding="2" border="1" width="95%" class="std">
								<thead>
									<th>Diary No</th>
									<th>Case No</th>
									<th>Title</th>
									<th>Date Of Filing</th>
									<th>Registration date</th>
									<th>Status</th>
									<th>Listing date</th>
									<th>Court no</th>
									<th>Coram</th>
									
								</thead>
								<tbody id="search_data_here">
								<?php
								$count=0;
								$status = 'W';
								$cases = $db->prepare("select cd.filing_no,ct.short_name||'/'||cd.case_no||'/'||cd.case_year as case_number,
														cd.dt_of_filing,cd.regis_date,ecp1.name||' VS '||ecp2.name as case_title,cp.listing_date, cp.court_no,cp.bench_no,cp.display_court_text
														from $schemas.case_detail as cd 
														inner join e_case_detail as ecd on ecd.filing_no = cd.filing_no
														left join case_type as ct on ct.id = cd.case_type
														left join (select cp2.filing_no,cp2.listing_date,cp2.court_no,cp2.bench_no,c.display_court_text from $schemas.case_proceeding as cp2 inner join 
																	(select cp1.filing_no,max(cp1.listing_date) as list_date from $schemas.case_proceeding as cp1 where cp1.for_stay = 1  group by filing_no) as cp3 on cp3.filing_no = cp2.filing_no
																	and cp2.listing_date = cp3.list_date
																	left join $schemas.court as c on c.court_no = cp2.court_no
																  ) as cp on cp.filing_no = cd.filing_no
														left join e_cases_party as ecp1 on ecp1.filing_no = case when length(ecd.filingnumberia) = '16' then ecd.filingnumberia else cd.filing_no end  and ecp1.party_flag = 'P' and ecp1.party_serial_no = 1
														left join e_cases_party as ecp2 on ecp2.filing_no = case when length(ecd.filingnumberia) = '16' then ecd.filingnumberia else cd.filing_no end and ecp2.party_flag = 'R' and ecp2.party_serial_no = 1
														where cd.filing_no in (select distinct(filing_no) from $schemas.case_proceeding where for_stay = 1);");
								$cases->execute();
								$cases = $cases->fetchAll();
								foreach($cases as $k=>$case_detail){ 
									$filing_no = $case_detail['filing_no'];
									$case_no = $case_detail['case_number'];
									$title = $case_detail['case_title'];
									$court_no = $case_detail['display_court_text'];
									$coram = fn_judge_list($db, $schemas, $case_detail['listing_date'],  $case_detail['bench_no']);
								?>
									<tr>
										<td><?php echo $filing_no; ?></td>
										<td><?php echo $case_no; ?></td>
										<td><?php echo $title; ?></td>
										<td><?php echo (!empty($case_detail['dt_of_filing']))?date('d/m/Y',strtotime($case_detail['dt_of_filing'])):''; ?></td>
										<td><?php echo (!empty($case_detail['regis_date']))?date('d/m/Y',strtotime($case_detail['regis_date'])):''; ?></td>
										<td><?php echo ($case_detail['status'] == 'D')?'Disposed':(($case_detail['status'] == 'W')?'Wrongly Updated':'Pending'); ?></td>
										<td><?php echo (!empty($case_detail['listing_date']))?date('d/m/Y',strtotime($case_detail['listing_date'])):''; ?></td>
										<td><?php echo $court_no; ?></td>
										<td><?php echo $coram; ?></td>
									</tr>
								
							<?php	}
								} 
								
								?>
								
								</tbody>
							</table>
						</div>
				</div>
			</div>
		</div>
	</div>

	<script src="../src/calendar.js"></script>
 <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
 <script src="../bower_components/bootstrap/dist/js/jquery.dataTables.min.js"></script>
 <script src="../bower_components/bootstrap/dist/js/dataTables.bootstrap.min.js"></script>

 <script src="../js/custom.js"></script>

</body>
  
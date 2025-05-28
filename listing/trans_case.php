<link rel="stylesheet" href="../css/bootstrap.min.css">


<?php 
session_start();
ob_start();
include("../db_inc1.php");
//if we remove db_inc2 then it stops working
date_default_timezone_set("Asia/Kolkata");

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);   */ 

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
	
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 
$appeals = main_case_type();
function generate_case_no($schemas,$db,$case_type,$case_year,$case_no,$location_code){
	$case_num = '';
	$case_short_name = $db->prepare("select short_name from case_type where id=?");
	$case_short_name->bindParam(1, $case_type, PDO::PARAM_INT);
	$case_short_name->execute();
	$case_short_name = $case_short_name->fetchColumn();

	$city_name = $db->prepare("select short_name from mater_location_city where city_id=?");
	$city_name->bindParam(1, $location_code, PDO::PARAM_INT);
	$city_name->execute();
	$city_name = $city_name->fetchColumn();	 

	if(!empty($case_type)){
		   
	$case_num=$case_short_name."/".$case_no."($city_name)"."/".$case_year;
	}
	return $case_num;
}

function main_case_filing_no($schemas,$db,$filing_no){
	$main_case_filing_no = $db->prepare("select main_case_ia_no from $schemas.case_detail where filing_no=?");
	$main_case_filing_no->bindParam(1, $filing_no, PDO::PARAM_INT);
	$main_case_filing_no->execute();
	$main_case_filing_no = $main_case_filing_no->fetchColumn();
	return $main_case_filing_no;
}

function display_date_format($date){
	list($year,$month,$day)=explode('-',$date);
	$converted_date=$day.'/'.$month.'/'.$year;
	return $converted_date;
}


function main_case_no($schemas,$db,$case_type,$filing_no){
	$case_type_array = array(35,36,37,38,39);
	if (in_array($case_type, $case_type_array)){
		
		$main_case_filing_no = main_case_filing_no($schemas,$db,$filing_no); 
		$main_case_record = $db->prepare("select case_type,case_year,case_no,location_code from $schemas.case_detail where filing_no=?");
		$main_case_record->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
		$main_case_record->execute();
		$main_case_record = $main_case_record->fetchAll();
		$main_case_record = array_shift($main_case_record);
		
		$main_case_type = $main_case_record['case_type'];
		$main_case_year = $main_case_record['case_year'];
		$main_case_no = $main_case_record['case_no'];
		$location_code = $main_case_record['location_code'];
		
		$main_case_no = generate_case_no($schemas,$db,$main_case_type,$main_case_year,$main_case_no,$location_code);
		
		/* $next_list_date = $db->prepare("select next_list_date from $schemas.case_allocation where filing_no=? order by id desc limit 1");
		$next_list_date->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
		$next_list_date->execute();
		$next_list_date = $next_list_date->fetchColumn();
		
		if($next_list_date && $next_list_date != ''){
			$main_case_no .= "/$next_list_date";
		} */
		
	}else{
		$main_case_no = '';
	}
	return $main_case_no;
}


function get_display_court_text($db,$schemas,$court_no){
	$select = $db->prepare("select causelist_court_text from $schemas.court where court_no = ?");
	$select->bindParam(1, $court_no, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
}

function get_court($db,$schemas){
	$courts = $db->prepare("select * from $schemas.court order by court_no");
	$courts->execute();
	$courts = $courts->fetchAll();
	return $courts;
}


?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<title>NCLAT</title>
<!-- Bootstrap 3.3.7 -->
<script src="../dist/js/adminlte.min.js"></script>
<script language="javascript">
	function MM_openBrWindow(theURL,winName,features) { //v2.0
	  window.open(theURL,winName,features);
	  return false;
	}

	function un_check()
	{
		for (var i = 0; i < document.frm.elements.length; i++)
		{
			var e = document.frm.elements[i];
			if ((e.name != 'allbox') && (e.type == 'checkbox'))
			{
				e.checked = document.frm.allbox.checked;
			}
		}
	}
	

</script>

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
				<?php 
					$s2 = 'list';
					if (strpos($_SESSION['user'], $s2) !== false){
						
				?>
					<section class="content-header">
						<center><b></b></center>
					</section>
					
					<?php } ?>
						<div class="table-responsive" id="table_data">
							<table cellspacing="0" align="center" class="table no-margin table-bordered table-striped table-hover casealloc_table"   cellpadding="2" border="1" width="95%" class="std">
								<thead style="background-color:#00a65a;color:#ffffff;">
									<th><b>Sr.No.</b></th>
									<?php if (strpos($_SESSION['user'], $s2) !== false){ ?>
									
									<?php } ?>
									<th align="left" width="150"><b>Diary No.</b></th>
									<th align="left" width="700"><B>Case No </b></th>
									<th align="left" width="700"><B>Main Case No </b></th>
									<th align="left" width="700"><B>Title </b></th>
									<th align="left" width="250"><B>Date Of Registration</b></th>
									<th align="left" width="250"><B>Listing Date</b></th>
								</thead>
								<tbody id="search_data_here">
								<?php
								$count=0;
								$recused_purpose = 29;
								$recused_next_date = '1111-11-11';
								$recused_status = 'P';
								
								$sql1=$db->prepare("select cp.*,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.regis_date,a.main_case_ia_no
								from $schemas.case_proceeding as cp left join $schemas.case_detail as a  on a.filing_no = cp.filing_no
								where cp.next_list_purpose = ? and cp.next_list_date = ? and cp.todays_status = ? order by cp.listing_date desc");
								$sql1->bindParam(1, $recused_purpose, PDO::PARAM_INT);
								$sql1->bindParam(2, $recused_next_date, PDO::PARAM_INT);
								$sql1->bindParam(3, $recused_status, PDO::PARAM_INT);
								$sql1->execute();
								while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
								{
								  $filing_no =$row1['filing_no'];
								  $main_case_number = $direct_parent_filing_no = $row1['main_case_ia_no'];
								  $filing_date =$row1['dt_of_filing'];
								  $listing_date =$row1['listing_date'];
								  $regis_date =$row1['regis_date'];
								  $case_type =$row1['case_type'];
								  $location_code =$row1['location_code'];
								  if (in_array($case_type, $appeals)){
									$show_party_filing_no = htmlspecialchars($filing_no);
									}else{
										$show_party_filing_no = htmlspecialchars($direct_parent_filing_no);
										if(empty($show_party_filing_no))
											$show_party_filing_no = htmlspecialchars($filing_no);
									}
								  $pet_name =get_party($db,$show_party_filing_no,'P',1);
								  $pet_name=strtoupper($pet_name);
								  $res_name =get_party($db,$show_party_filing_no,'R',1);
								  $res_name=strtoupper($res_name);
								  $case_no = $row1['case_no'];
								  $case_year = $row1['case_year'];
								  $status = $row1['status'];

									$count++;

								
								?>
								<tr>
									<td><?php echo $count;?></td>
									<td><?php echo $filing_no;?></td>

									<td><?php echo generate_case_no($schemas,$db,$case_type,$case_year,$case_no,$location_code); ?></td>								
									<td><?php echo main_case_no($schemas,$db,$case_type,$filing_no);
										//echo main_case_next_list_date($schemas,$db,$case_type,$filing_no);
										?>
										
									</td>								

									<td><?php echo $pet_name.' Vs. '.$res_name;?>
									</td>

									<td>
										<?php
										if($regis_date!='')
										{ echo display_date_format($regis_date); }?>
									</td>
									<td>
										<?php
										if($listing_date!='')
										{ echo display_date_format($listing_date); }?>
									</td>
									
								</tr>


								<?php
								
								} 
								} 
								
								?>
								
								</tbody>
							</table>
						</div>
						
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="../src/calendar.js"></script>
	<script src="../bower_components/bootstrap/dist/js/jquery.dataTables.min.js"></script>
 <script src="../bower_components/bootstrap/dist/js/dataTables.bootstrap.min.js"></script>
 <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
 <script src="../js/custom.js"></script>
	
</body>
  
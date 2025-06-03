<link rel="stylesheet" href="../css/bootstrap.min.css">


<?php 
session_start();
ob_start();
include("../db_inc1.php");
//if we remove db_inc2 then it stops working
date_default_timezone_set("Asia/Kolkata");

/*  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */   

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
		table{
			width:100% !important;
		}
	</style>
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css"/>
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
<script src="../src/calendar.js"></script>
<script>
function submitForm2()
{
 	with(document.frm)
	{
	
		if(list_date.value=="")
		{
			alert("Please Select Listing Date.");
			//next_list_date.focus();
			return false;
		}
		
		var bench = $("input[name='bench_no']").prop('checked');
		
		if(bench === "" || bench === undefined)
		{
			alert("please enter correct listing date and then select bench");
		}
		
		
		
		if(list_date.value=="")
		{
			alert("Please Select Listing Date.");
			//next_list_date.focus();
			return false;
		}
		
		if(purpose_id.value == "select")
		{
			alert("Please select Purpose");
			purpose_id.focus();
			return false;
		}
		var checkboxes = document.getElementsByName('bench_no');

		var selected = [];
		for (var i=0; i<checkboxes.length; i++) {
		if (checkboxes[i].checked) {selected.push(checkboxes[i].value);}
		}
		if(selected=="")
		{
		alert("Please choose From bench ");
		return false;
		}
		var filing_case = $(".checkbox").is(":checked");
		if (filing_case == false)
		{
		        alert("Please select at least one case.");
		        return false;
		}
		
		
		submit();
	}
}

function popsurety_pending_report(cfy)

    {
    	
    		var url = "./case_gen_view.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");   
    		
    }
</script>
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
						<center><b>Restored Cases</b></center>
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
									<!--<th align="left" width="700"><B>Main Case No </b></th>-->
									<th align="left" width="700"><B>Cause Title </b></th>
									<th align="left" width="250"><B>Date Of Registration</b></th>
									<th align="left" width="250"><B>Last Listing Date</b></th>
									<th align="left" width="250"><B>Restored Date</b></th>
									<th align="left" width="250"><B>Restoration Reason</b></th>
									<th align="left" width="700"><B>Action </b></th>
								</thead>
								<tbody id="search_data_here">
								<?php
								$count=0;

								$is_restored = 0;

								
								$sql1=$db->prepare("select cp.*,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.regis_date,a.main_case_ia_no
								from $schemas.restored_cases as cp left join $schemas.case_detail as a  on a.filing_no = cp.filing_no
								where cp.is_restored = ? order by cp.entry_date asc");
								$sql1->bindParam(1, $is_restored, PDO::PARAM_INT);
								$sql1->execute();
								while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
								{
									$restored_id = $row1['id'];
								  $filing_no = $row1['filing_no'];
								  $restored_app_filing_no = $row1['restoration_filing_no'];
								  $restoration_type = $row1['restoration_type'];
								  $last_listing_date = $disposal_date =$row1['disposal_date'];
								  $regis_date = $row1['regis_date'];
								  $case_type = $row1['case_type'];
								  $location_code =$row1['location_code'];
								  $restored_date = $row1['entry_date'];
								  
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
						

									<td><?php echo $pet_name.' Vs. '.$res_name;?>
									</td>

									<td>
										<?php
										if($regis_date!='')
										{ echo display_date_format($regis_date); }?>
									</td>
									<td>
										<?php
										if($last_listing_date!='')
										{ echo display_date_format($last_listing_date); }?>
									</td>
									
									<td>
										<?php
										if($restored_date!='')
										{ echo date('d/m/Y',strtotime($restored_date)); }?>
									</td>
									<td>
										<?php
										$st="select restore_type from restore_case_type where id = ?";
										$st=$db->prepare($st);
										$st->bindParam(1, $restoration_type, PDO::PARAM_STR);
										$st->execute();
										echo $restoration_Res = $st->fetchColumn(); ?>
									</td>
									<?php if($restoration_type != '3') { ?>
									<td id="list_date_<?php echo $filing_no; ?>">
									<?php 
									$st="select max(listing_date) from $schemas.case_proceeding where filing_no = ?";
									  $st=$db->prepare($st);
										$st->bindParam(1, $filing_no, PDO::PARAM_STR);
										$st->execute();
										$max_list_date = $st->fetchColumn();
										 if(!empty($max_list_date) && $max_list_date != $last_listing_date){
											echo "";
										 }else{  ?>
									Next List Date : <input class='datepicker' name="next_list_date_<?php echo $filing_no; ?>" type="text" autocomplete="off" id="next_list_date_<?php echo $filing_no; ?>" value="" >
									<br/>Next List Purpose : <select class='form_control' name="purpose_code_<?php echo $filing_no; ?>" id='purpose_code_<?php echo $filing_no; ?>'>
												<option value="">Select</option>
												<?php
												$display='Y';
												$st= $db->prepare("select * from $schemas.master_purpose where display=? and status = 1 order by purpose_name asc");
												$st->bindParam(1, $display, PDO::PARAM_STR);
												$st->execute();
												while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
												{
												$purposecode=$row['purpose_code'];
												?>
												<option <?php echo ($purposecode==$recused_purpose)?'selected':''; ?> value="<?php echo htmlspecialchars($purposecode);?>" >
												<?php echo htmlspecialchars(ucwords($row['purpose_name']));?>
												</option>
												<?php
												}

												?>
												</select>
										<input type="button" class="btn btn-sm btn-success" value="Save" onClick="save_listing_date('<?php echo $filing_no; ?>','<?php echo $last_listing_date; ?>','<?php echo $restored_id; ?>')">
										 <?php } ?>
									</td>
									<?php } else { ?>
									<td></td>
									<?php } ?>
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
	
	<!-- computation note modal -->
<div id="comp_note" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:90%;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="comp_note_body">
        <p>Some text in the modal.</p>
      </div>
    </div>

  </div>
</div>
	<script src="../src/calendar.js"></script>
	<script src="../bower_components/bootstrap/dist/js/jquery.dataTables.min.js"></script>
 <script src="../bower_components/bootstrap/dist/js/dataTables.bootstrap.min.js"></script>
 <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
 <script src="../js/custom.js"></script>
	<script>
	
		$(document).ready(function() {
			$('.casealloc_table').dataTable( {
				 "lengthMenu": [[50,100,200,-1], [50, 100, 200, "All"]],
				  "pageLength": 100
				} );
		} );
		
		function save_listing_date(filing_no,listing_date,restored_id){
		var next_listing_date = $("#next_list_date_"+filing_no).val();
		var next_list_purpose = $("#purpose_code_"+filing_no).val();
		$.ajax({
			type: "POST",
			url: "restored_ajax.php",
			data: {type:'save_next_list_date',filing_no:filing_no,listing_date:listing_date,next_listing_date:next_listing_date,next_list_purpose:next_list_purpose,restored_id:restored_id},
			success: function (data) {
				if(data){
				alert("Next listing date and purpose updated");
				//location.reload(true);
				}else{
					alert("some error occured");
				}
			},
			error: function (textStatus, errorThrown) {
			   alert("error");
			}
		});
	}
	
	</script>
</body>
  

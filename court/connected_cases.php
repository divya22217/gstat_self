
<?php 
session_start();
ob_start();
include("../db_inc1.php");
//if we remove db_inc2 then it stops working
date_default_timezone_set("Asia/Kolkata");

 /* ini_set('display_errors', 1);
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

function generate_case_no($schemas,$db,$case_type,$case_year,$case_no,$location_code){
	$case_short_name = $db->prepare("select short_name from case_type where id=?");
	$case_short_name->bindParam(1, $case_type, PDO::PARAM_INT);
	$case_short_name->execute();
	$case_short_name = $case_short_name->fetchColumn();

	$city_name = $db->prepare("select short_name from mater_location_city where city_id=?");
	$city_name->bindParam(1, $location_code, PDO::PARAM_INT);
	$city_name->execute();
	$city_name = $city_name->fetchColumn();	   
		   
	$case_no=$case_short_name."/".$case_no."($city_name)"."/".$case_year;
	return $case_no;
}

function main_case_filing_no($schemas,$db,$filing_no){
	$main_case_filing_no = $db->prepare("select ia_ma_filing_no from $schemas.case_detail where filing_no=?");
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
	$case_type_array = array(2,3,4,5,6,7);
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
		
		$next_list_date = $db->prepare("select next_list_date from $schemas.case_allocation where filing_no=? order by id desc limit 1");
		$next_list_date->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
		$next_list_date->execute();
		$next_list_date = $next_list_date->fetchColumn();
		
		if($next_list_date && $next_list_date != ''){
			$main_case_no .= "/$next_list_date";
		}
		
	}else{
		$main_case_no = '';
	}
	return $main_case_no;
}

function main_case_next_list_date($schemas,$db,$case_type,$filing_no){
	$main_case_next_listing_date = '';
	if($case_type == 6){
		$main_case_filing_no = main_case_filing_no($schemas,$db,$filing_no); 
		$main_case_record = $db->prepare("select next_list_date from $schemas.case_allocation where filing_no=? order by id desc limit 1");
		$main_case_record->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
		$main_case_record->execute();
		$main_case_next_listing_date = $main_case_record->fetchColumn();
		if($main_case_next_listing_date){
			$main_case_next_listing_date = display_date_format($main_case_next_listing_date);
			return " (".$main_case_next_listing_date.")";
		}	
	}
	return $main_case_next_listing_date;
}

?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<title>Connected Cases</title>
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
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

<script src="../assets/js/custom.js?v=1.0"></script>
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
					<section class="content-header">
						<center><b>Connect Cases</b></center>
					</section>
					<?php
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
						//echo $cday;



						if($msg1 !='')
						{
						?>
						<div class="form-group row">
						<center>
							<a href="javascript:popsurety_pending_report('<?php echo htmlspecialchars($case_list_date);?>');" ><b><font color = 'red'  ><?php echo $msg1;?>  Click Here for view  <?php echo $cyear ?></font></b></a></u>
						</center>
						</div>
						<?php
						}
						?>
					<!--<div class="search_bar">
							<select id="search_type" name="search_type" onchange="return get_form(this.value,'');" class="form-control" >
								<option value="1">Case No. Wise</option>
							</select>
					</div>	
					<br/>-->
						<div class="form-group row" id="search_form">
							<input type="hidden" name="searched_filing_number" id="filing_number_first">
							<div id="case_no_wise_search_form" style="display:none;">
								<div class="col-sm-3 col-md-3 col-lg-3">
									<select name="bench" class="form-control" id="bench">
										
									</select>
								</div>
								<div class="col-sm-3 col-md-3 col-lg-3">
									<select name="case_type" class="form-control" id="case_type">
										
									</select>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<input type="number" class="form-control" value="73" placeholder="Enter Case Number" name="case_no" id="case_no">
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<input type="number" class="form-control" value="2025" placeholder="Enter Case Year" name="case_year" id="case_year">
								</div>
							</div>
							<div id="filing_no_wise_search_form" style="display:none;">
								<div class="col-sm-5 col-md-3 col-lg-5">
									<select name="bench" class="form-control" id="bench">
										
									</select>	
								</div>
								
								<div class="col-sm-5 col-md-5 col-lg-5">
									<input type="number" class="form-control" placeholder="Enter Diary Number" name="filing_no" id="filing_no">
								</div>
							</div>
							<div class="col-sm-2 col-md-2 col-lg-2" id="search_button">
									<input type="button" name="submit" id="get_case" value="GO" onclick="return get_cases('','con');" class="btn btn-primary btn-sm">
							</div>
						</div>
						
						<div class="row hide" id="cause_title">
							<center><h4 style="color:red;"></h4></center>
							<input type='hidden' value='' id="set_filing_no" name="set_filing_no">
						</div>

						<div class="table-responsive" id='show_bulk_first' style="margin-bottom: 15px;"></div>

						
						<div class="form-group row" id="search_form_second">
						<input type="hidden" name="searched_filing_number_second" id="filing_number_first">
							<div id="case_no_wise_search_form_second" style="display:none;">
								<div class="col-sm-3 col-md-3 col-lg-3">
									<select name="bench_second" class="form-control" id="bench_second">
										
									</select>
								</div>
								<div class="col-sm-3 col-md-3 col-lg-3">
									<select name="case_type_second" class="form-control" id="case_type_second">
										
									</select>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<input type="text" class="form-control" placeholder="Enter Case Number" name="case_no_second" id="case_no_second">
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2">
									<input type="number" class="form-control" placeholder="Enter Case Year" name="case_year_second" id="case_year_second">
								</div>
							</div>
							<div id="filing_no_wise_search_form_second" style="display:none;">
								<div class="col-sm-5 col-md-3 col-lg-5">
									<select name="bench_second" class="form-control" id="bench_second">
										
									</select>	
								</div>
								
								<div class="col-sm-5 col-md-5 col-lg-5">
									<input type="number" class="form-control" placeholder="Enter Diary Number" name="filing_no_second" id="filing_no_second">
								</div>
							</div>
							<div class="col-sm-2 col-md-2 col-lg-2" id="search_button_second" style="display:none;">
									<input type="button" name="submit" id="get_case_second" value="GO" onclick="return get_cases('_second','con');" class="btn btn-primary btn-sm">
							</div>
						</div>
						
						<div class="row hide" id="cause_title_second">
								<center><h4 style="color:red;"></h4></center>
								<input type='hidden' value='' id="set_con_filing_no" name="set_con_filing_no[]">
								<center>
						</div>
						
						<div class="table-responsive" id='show_bulk'>
							
						</div>
						<div class="row hide" id="connect_button_second">
							<center><button type="button" id="connect_case" class="btn btn-success btn-sm" onClick="connect_case();">Connect</button></center>
						</div>
						
					
						
					<div class="row hide" id="view_connected_cases">
						<div><center><h3> Connected Cases </h3></center></div>
						<div class="table-responsive" id="table_data">
							<table cellspacing="0" align="center" class="table no-margin table-bordered table-striped table-hover casealloc_table"   cellpadding="2" border="1" width="95%" class="std">
								<thead style="background-color:#00a65a;color:#ffffff;">
									<th><b>Sr.No.</b></th>
									<th align="left" width="150"><b>Diary No.</b></th>
									<th align="left" width="700"><B>Case No </b></th>
									<th align="left" width="150"><B>Status </b></th>
								</thead>
								<tbody id="search_data_here">
								

								
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
	
	
		$(document).ready(function() {
			get_form(1,'');
		} );
	
		function getbench(listing_date_id){
			listing_date = $("#"+listing_date_id).val();
			if(listing_date === '')
			{
				alert("please enter listing date");
				return false;
			}
			 $("#benches").html("<center>loading......<center>");
			$.ajax({
            type: "POST",
            url: "getbench_by_listing_date.php",
            data: {listing_date:listing_date},
            success: function (data) {
			   $("#benches").html(data);
			   //alert("success");
            },
            error: function (textStatus, errorThrown) {
               alert("error");
            }

        });
		}
		
		function search_data(){
			var search_case_type = $("#search_case_type").val();
			var search_case_year = $("#search_case_year").val();
			var search_case_number = $("#search_case_number").val();
			if(search_case_type == '' && search_case_number == '' && search_case_year == ''){
				alert("please fill at least one details");
				return false;
			}
			$("#search_data_here").html("<tr><td colspan='7'><center>loading......<center></td></tr>");
			$.ajax({
            type: "POST",
            url: "get_data.php",
            data: {search_case_type:search_case_type,search_case_year:search_case_year,search_case_number:search_case_number},
            success: function (data) {
				$(".casealloc_table").dataTable().fnDestroy()
			   $("#search_data_here").html(data);
			    

				$('.casealloc_table').DataTable();
            },
            error: function (textStatus, errorThrown) {
               alert("error");
            }

        }); 
			
		}
		
		function search_bar(){
			$("#search_form").css("display","block");
			$(".search_bar").css("display","none");
		}
		
		function dismiss(){
			$("#search_form").css("display","none");
			$(".search_bar").css("display","block");
		}
	
	
	</script>
	
	
</body>

<?php } ?>
  
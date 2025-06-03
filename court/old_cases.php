<link rel="stylesheet" href="../assets/css/datatables.min.css">
<link rel="stylesheet" href="../assets/css/simplePagination.css">
<?php 
session_start();
ob_start();
include("../db_inc1.php");
//if we remove db_inc2 then it stops working
include("../db_inc2.php");
date_default_timezone_set("Asia/Kolkata");

/*   ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);    */

$pdfpath=$_SERVER['HTTP_HOST'].'/group/pdf/';

$bench_no='';
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
//$localadmin=$_SESSION['localadmin'];
//$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
include '../inheader.php';
//include '../insidebar.php';
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

?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<title>Old Cases</title>
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
	function submitForm2()
	{
		
		with(document.frm)
		{
			var ofc = $("#group").val();
			if(ofc === '0'){
				alert("please select a Group");
				return false;
			}
			var selected = [];
				$.each($("input[name='checkbox[]']:checked"), function(){            
					selected.push($(this).val());
				});
			if(selected=="")
			{
			alert("Please select at least one case.");
			return false;
			}
			//alert("test");
			//return false;
			submit();
		}
	}

</script>

	<style>
		.no-border{
		border:none;
		}
		#old_cases_list_filter{
			margin-right:6%;
		}
		.show_entries{
			display: inline-block;
			padding-left: 0;
			margin: 20px 0;
			border-radius: 4px;
			list-style-type: none;
		}
	</style>

</head>

<body>   
<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
			<div class="box box-success">
				<div class="box-body">
    <!-- Content Header (Page header) -->
					<section class="content-header">
						<center><strong>Cases</strong></center><br>
					</section>
 
					
						<div class="row">
							<div class="col-sm-2">
								<select class="form-control" name="number_of_records" id="number_of_records" onChange="return sort_by_number_of_rec(this.value);">
									<option value='10'>10</option>
									<option value='20'>20</option>
									<option value='50'>50</option>
									<option value='100'>100</option>
								</select>
							</div>
							<div class="col-sm-10">
								<form class="form-inline" id="search_form">
								  <div class='form-group'>
									<label class="radio-inline">
									  <input type="radio" name="selected_case_type" onChange="return filter_data();" value='1' checked>Backlog
									</label>
									<label class="radio-inline">
									  <input type="radio" name="selected_case_type" onChange="return filter_data();" value='0'>Fresh
									</label>
								  </div>
								  <div class="form-group">
									<?php 
									$dispaly_Case_type = 't';
										$search_case_types = $db->prepare("select * from case_type where status=? order by id asc");
										$search_case_types->bindParam(1, $dispaly_Case_type, PDO::PARAM_INT);
										$search_case_types->execute();
										$search_case_types = $search_case_types->fetchAll();
									?>
									<select class="form-control" id="search_case_type">
										<option value=''>Select Case Type</option>
										<?php foreach($search_case_types as $key=>$search_case_type) {?>
										<option value="<?php echo $search_case_type['id']; ?>"><?php echo $search_case_type['case_type_desc']; ?></option>
										<?php } ?>
									</select>
								  </div>
								  <div class="form-group">
									<input type="number" class="form-control" placeholder="Enter Case Number" id="search_case_number">
								  </div>
								  <div class="form-group">
									<input type="number" class="form-control" placeholder="Enter Case Year"  id="search_case_year">
								  </div>
								  <button type="button" class="btn btn-primary" onClick="return filter_data();">search</button>
								  <button type="button" class="btn btn-primary" onClick="return location.reload(true);">Reset</button><span>
								  <!--<button type="button" class="btn btn-primary" onClick="return export_pdf();">Export as pdf</button><span>-->
								  <button type="button" class="btn btn-primary" onClick="return export_csv();">Export as Csv</button><span>
								  
								</form>
							</div>
						</div>
						<div class="table-responsive">
							<div id="total_records_show">dfg</div>
							<table cellspacing="0" align="center" class="table no-margin table-bordered table-striped table-hover" id="old_cases_list"   cellpadding="2" border="1" width="100%" class="std">

								<thead style="background-color:#00a65a;color:#ffffff;">
									<th><b>Sr.No.</b></th>
									<th align="left" width="150"><b>Diary No.</b></th>
									<th align="left" width="150"><B>Case No. </b></th>
									<th align="left" width="700"><B>Cause Title </b></th>
									<th align="left" width="150"><B>Last Listing Date </b></th>
									<th align="left" width="150"><B>Next Date/ Disposed Date </b></th>
									<th align="left" width="100"><B>Status </b></th>
								</thead>
								<tbody id="target-content">
								
								</tbody>
							</table>
							<nav><ul class="pagination col-md-9 col-sm-9">
							<?php 
							$sql1=$db->prepare("select count(*) as count from $schemas.case_detail where backlog = 1");
							$sql1->execute();
							$total_records = $sql1->fetchColumn();
							
							if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++):  
										if($i == 1):?>
										<li class='active'  id="<?php echo $i;?>"><a href='pagination.php?page=<?php echo $i;?>'><?php echo $i;?></a></li> 
										<?php else:?>
										<li id="<?php echo $i;?>"><a href='pagination.php?page=<?php echo $i;?>'><?php echo $i;?></a></li>
									<?php endif;?>          
							<?php endfor;endif;?>
							</ul>
							<ul class="show_entries col-md-3 col-sm-3"><li id="total_rec" style="float:right;"></li></ul>
							</nav>
						</div>
					
				</div>
			</div>

		</div>
	</div>
	
	<input type="hidden" id="all_records" value='<?php echo $total_records; ?>'>
	
	<div id="iframemodal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg"  >

    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal_title">PDF</h4>
      </div>
      <div class="modal-body" id="modal_body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
	
	<script>
  
  function viewpdf(pdfpath,filing_no)
  {
	  $("#modal_body").html('');
	   var path = pdfpath+filing_no+".pdf";
	 // alert(path);
	 //var frame = "<iframe  src=https://docs.google.com/viewer?url="+path+"&embedded=true style='width:100%;height:500px;'></iframe>";
	 var frame = "<iframe  src=http://"+path+" style='width:100%;height:500px;' allowfullscreen></iframe>";
	 $("#modal_body").html(frame);
	 $("#iframemodal").modal('show');
  }
  
   /* $(document).ready(function() {
	$('#old_cases_list').DataTable({
		"paging":false,
		"ordering":false,
		"info":false
	});
} ); */

function sort_by_number_of_rec(item_per_page){
	var case_type = $("#search_case_type").val();
	var case_no = $("#search_case_number").val();
	var case_year = $("#search_case_year").val();
	var selected_case_type = $("input[name='selected_case_type']:checked").val();
	search_data(item_per_page,case_type,case_no,case_year,selected_case_type);
}

function filter_data(){
	var item_per_page = $("#number_of_records").val();
	var case_type = $("#search_case_type").val();
	var case_no = $("#search_case_number").val();
	var case_year = $("#search_case_year").val();
	var selected_case_type = $("input[name='selected_case_type']:checked").val();
	/* if(case_type === '' && case_year === '' && case_no === ''){
			location.reload(true);
	}else{ */
	search_data(item_per_page,case_type,case_no,case_year,selected_case_type);
	//}
}

function search_data(item_per_page,case_type,case_no,case_year,selected_case_type){
	$.ajax({
		type: "POST",
		url: "get_all_cases.php",
		data: {case_type:case_type,case_no:case_no,case_year:case_year,selected_case_type:selected_case_type},
		success: function (total_items) {
		   $('.pagination').pagination({
			items: total_items,
			itemsOnPage:item_per_page,
			cssStyle: 'light-theme',
			currentPage : 1,
			onPageClick : function(pageNumber) {
				jQuery("#target-content").html('<center>loading...</center>');
				jQuery("#target-content").load("pagination.php?page=" + pageNumber+"&limit="+ item_per_page+"&case_type="+ case_type+"&case_no="+ case_no+"&case_year="+ case_year+"&selected_case_type="+selected_case_type);
			},
			onInit :function() {
				jQuery("#target-content").html('loading...');
				jQuery("#target-content").load("pagination.php?page=1&limit="+ item_per_page+"&case_type="+ case_type+"&case_no="+ case_no+"&case_year="+ case_year+"&selected_case_type="+selected_case_type);
			}
		});
		$("#all_records").val(total_items);
		$("#total_records_show").html('Total Cases : '+total_items);
		},
		error: function (textStatus, errorThrown) {
		   alert("error");
		}

	});
}

$(document).ready(function(){
	var item_per_page = $("#number_of_records").val();
	var case_type = $("#search_case_type").val();
	var case_no = $("#search_case_number").val();
	var case_year = $("#search_case_year").val();
	var selected_case_type = $("input[name='selected_case_type']:checked").val();
	search_data(item_per_page,case_type,case_no,case_year,selected_case_type);
});

function export_pdf(){
	var case_type = $("#search_case_type").val();
	var case_no = $("#search_case_number").val();
	var case_year = $("#search_case_year").val();
	var selected_case_type = $("input[name='selected_case_type']:checked").val();
	window.open('./export_cases.php?case_type='+case_type+'&case_no='+case_no+'&case_year='+case_year+'&selected_case_type='+selected_case_type);
	/* $.ajax({
		type: "POST",
		url: "export_cases.php",
		data: {type:'pdf',case_type:case_type,case_no:case_no,case_year:case_year,selected_case_type:selected_case_type},
		success: function (total_items) {
		   
		},
		error: function (textStatus, errorThrown) {
		   alert("error");
		}

	}); */
}

function export_csv(){
	var case_type = $("#search_case_type").val();
	var case_no = $("#search_case_number").val();
	var case_year = $("#search_case_year").val();
	var selected_case_type = $("input[name='selected_case_type']:checked").val();
	window.open('./export_csv.php?case_type='+case_type+'&case_no='+case_no+'&case_year='+case_year+'&selected_case_type='+selected_case_type);
	/* $.ajax({
		type: "POST",
		url: "export_cases.php",
		data: {type:'pdf',case_type:case_type,case_no:case_no,case_year:case_year,selected_case_type:selected_case_type},
		success: function (total_items) {
		   
		},
		error: function (textStatus, errorThrown) {
		   alert("error");
		}

	}); */
}
			
  
  </script>
	
	<script src="../src/calendar.js"></script>
	 <script src="../assets/js/datatables.min.js"></script>
	 <script src="../assets/js/jquery.simplePagination.js"></script>
</body>

<?php } ?>
  
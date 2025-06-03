
<link rel="stylesheet" href="../assets/css/simplePagination.css">
<script type="text/javascript" language="javascript">
function DisableBackButton() {
	window.history.forward()
}
DisableBackButton();
window.onload = DisableBackButton;
window.onpageshow = function(evt) { if (evt.persisted) DisableBackButton() }
window.onunload = function() { void (0) }
function submitForm3()
{
 	with(document.frm)
	{		
	 action = "index.php";
	 submit();
	}
}
</script>
<?php 

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */ 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d');
include("../db_inc1.php");
session_start();

 $_SESSION['user'];

$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$sessionUserType=htmlspecialchars($_SESSION['id']);
$location_access=$_SESSION['location'];

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem....."; 
	header("Location: ./login.php");
	die();
}

    /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);    */
 
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
		
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	$link_scrutiny_idaccess='1';
	

	include '../inheader.php';
	//include '../insidebar.php';
	
	require('../formkey/formkey.class.php');
	//Start the class
	$formKey = new formKey();
	$hash_for_doc = $formKey->randomkey();
	
	$_SESSION['qqcc'] = rand();
	$qq1cc=$_SESSION['qqcc'];
	
	
	$total_doc=0;
	$status='P';
	
  ?>
  
  <style>
	body {
	background-color: white;
	}
	h1 {
	color: maroon;
	margin-left: 40px;
	}
	@media print{
		#testdiv{
			display: none;
		}
	}

.link {padding: 10px 15px;background: transparent;border:#bccfd8 1px solid;border-left:0px;cursor:pointer;color:#607d8b}
.disabled {cursor:not-allowed;color: #bccfd8;}
.current {background: #bccfd8;}
.first{border-left:#bccfd8 1px solid;}
.question {font-weight:bold;}
.answer{padding-top: 10px;}
#pagination{margin-top: 20px;padding-top: 30px;border-top: #F0F0F0 1px solid;}
.dot {padding: 10px 15px;background: transparent;border-right: #bccfd8 1px solid;}
#overlay {background-color: rgba(0, 0, 0, 0.6);z-index: 999;position: absolute;left: 0;top: 0;width: 100%;height: 100%;display: none;}
#overlay div {position:absolute;left:50%;top:10%;margin-top:-32px;margin-left:-32px;}
.page-content {padding: 20px;margin: 0 auto;}
.pagination-setting {padding:10px; margin:5px 0px 10px;border:#bccfd8  1px solid;color:#607d8b;}

</style>
<style type="text/css">
	div.hidden {
	display: none;
	}

</style>	
  <div id="overlay"><div><img src="../assets/loading.gif" width="64px" height="64px"/></div></div>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
	<?php 
	$hash=$_REQUEST['hash'];
        if($hash !='')
        {
        	$hash1=base64_decode($hash);
        	$hash1 = explode("||", $hash1);
        	$massage=$hash1[0];
        	
        	$token_filing_no_scrutiny= $hash1[1];
        
        ?>    
           <div class="alert alert-success" role="alert">
		  <?php echo $massage;?>
		</div> 
        <?php } ?>    
	
    <section class="content">
	
		<div id="testdiv" style="visibility: visible;"><a href="javascript:window.print();"><font size="4" color="red">
			Print</font></a>

		</div>
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-12">
	<div class="box box-success">
    <div class="box-body">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="label-control col-sm-6" for="start_date">Search By Filing No</label>
						<div class="form-group">
							<input type="number" autocomplete="off" class="form-control" name="filing_no" id="filing_no" placeholder="Enter filing no">
						</div>
					</div>
				</div>
				<div class="col-sm-6" style="margin-top:2%;">
					<div class="form-group">
						<label for="to_date" class="label-control col-sm-4"></label>
						<div class="form-group"><div class="form-group">
							<button type="button" class="btn btn-success" onClick="return search_case('fn');">Search</button>
							<button type="button" class="btn btn-primary" onClick="return reset_page();">Reset</button>
						</div>
					</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
				<div class="form-inline" id="search_form">
				  <div class="form-group">
					<?php 
					$dispaly_Case_type = 't';
						$search_case_types = $db->prepare("select * from case_type where status=? order by id asc");
						$search_case_types->bindParam(1, $dispaly_Case_type, PDO::PARAM_INT);
						$search_case_types->execute();
						$search_case_types = $search_case_types->fetchAll();
					?>
					<label>Case Type : </label>
					<select class="form-control" id="search_case_type">
						<option value=''>Select Case Type</option>
						<?php foreach($search_case_types as $key=>$search_case_type) {?>
						<option value="<?php echo $search_case_type['id']; ?>"><?php echo $search_case_type['case_type_desc']; ?></option>
						<?php } ?>
					</select>
				  </div>
				  <div class="form-group">
					<label>Case No : </label>
					<input type="text" class="form-control" placeholder="Enter Case Number" name="case_no" id="case_no" autocomplete="off" required="required">
				  </div>
				  <div class="form-group">
					<label>Case Year : </label>
					<input type="number" class="form-control" placeholder="Enter Case Year" onKeyPress="return number_validation(this.id,4)" name="case_year" id="case_year" autocomplete="off" required="required">
				  </div>
				  <!--<div class="form-group">
					<label>Order Date : </label>
					<input type="text" id="order_date" name="order_date" class="datepicker"  size="8" autocomplete="off" maxlength="10" />
				  </div>-->
				  <button type="button" class="btn btn-primary" onClick="return search_case('cn');">search</button>
				  <button type="button" class="btn btn-warning" onClick="window.location.reload();">Reset</button>
				</div> 
			</div>
		</div>
              <div class="table-responsive">
			  <input type="hidden" name="rowcount" id="rowcount" />
                <table class="table no-margin">
                  <thead>
                  <th>Sr No.</th>
                   <th>Date Of Filing</th>
                    <th>Diary No.</th>
					<th>Case No.</th>
                   <th>Title Of Case</th>
                   <th></th>
				   </thead>
				   <tbody id="document_scrutiny_body">
					
				   </tbody>
				  
	</table>
	</div>
	</div>
	</div>
</div>
	</section>
	</div>

	<script src="../assets/js/jquery.simplePagination.js"></script>
	<script>
	
	
	
	$( document ).ready(function() {
		//alert("sdfdf");
		/* var radioValue = $("input[name='cases']:checked").val();
		var defect_status = $("input[name='defect_status']:checked").val();
		var from_date = $("#start_date").val();
		var end_date = $("#to_date").val();
		var item_per_page = $("#number_of_records").val(); */
		getdata('fn','','','','');
		//return false;
	});
	
	function number_validation(element_id,number_length){
	  //called when key is pressed in textbox
	  $("#"+element_id).keypress(function (e) {
		var filing_no = $("#"+element_id).val();
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
			return false;
		}
		if(filing_no.length >= number_length){
			return false;
		}
	   });
	}
	
	function getresult(url){
	
	var filing_no = $("#filing_no").val();
	getdata('fn','','','','',url);
	}
	
	function search_case(type){
		if(type === 'fn'){
		var filing_no = $("#filing_no").val();
		getdata(type,filing_no,'','','');
		}else{
			if(type === 'cn'){
				var case_type = $("#search_case_type").val();
				var case_no = $("#case_no").val();
				var case_year = $("#case_year").val();
				getdata(type,'',case_type,case_no,case_year);
		}
		}
	}
	
	
	function reset_page(){
		location.reload(true);
	}
	function getdata(type,filing_no='',case_type,case_no,case_year,url='get_document.php?page='){
		var user_id = '<?php echo $sessionUserType ?>';
		var token = '<?php echo $hash_for_doc ?>';
		 $.ajax({
				type: "POST",
				url: url,
				data: {rowcount:$("#rowcount").val(),user_id:user_id,form_key:token,filing_no:filing_no,type:type,case_type:case_type,
						case_no:case_no,case_year:case_year},
				//beforeSend: function(){$("#overlay").show();},
				success: function (data) {
					//alert("sdf");
					/* $("#case_list").dataTable().fnDestroy()
					$("#case_list_body").html(data);
					

					$('#case_list').DataTable( {
						"order": [[ 3, "asc" ]]
					} ); */
					$("#document_scrutiny_body").html(data);
					$("#overlay").hide();
					//setInterval(function() {$("#overlay").hide(); },500);
				},
				error: function (textStatus, errorThrown) {
				   alert("error");
				}

			});  
	}
	
	function view_misc_docs(filing_no,miscellenous_no,url,pdf_rul){
		$.ajax({
				type: "POST",
				url: url,
				data: {action:'doc_list',filing_no:filing_no,miscellenous_no:miscellenous_no,url:url,pdf_rul:pdf_rul},
				beforeSend: function() {
					$("#view_doc").modal('show');
					$("#view_doc_body").html('loading....');
				},
				success: function (data) {
					//alert(data);
				   $("#view_doc_body").html(data);
				   //alert("success");
				},
				error: function (textStatus, errorThrown) {
					$("#view_doc_body").html('');
				   alert("error");
				}

			});
	}

	</script>
<?php	include '../infooter.php'; ?>
<div id="view_doc" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:90%;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="view_doc_body">
        <p>Some text in the modal.</p>
      </div>
    </div>

  </div>
</div>
<?php }

?>
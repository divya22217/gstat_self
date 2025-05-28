<link rel="stylesheet" href="../assets/css/datatables.min.css">
<link rel="stylesheet" href="../assets/css/simplePagination.css">
<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include("../formkey/formkey.class.php");
//session_start();

 /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */

$user = $_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];


if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
/*  $s2 = 'scrutinycler';
 $s3 = 'arfilin';
if (strpos($user, $s2) === false && strpos($user, $s3) === false){
	echo "Access Problem.....";
	header("Location: ../index.php");
	die();
} */

/* $s2 = 'scrutinycler';
				if (strpos($user, $s2) !== false)
					$column = 'user_id';
				$s3 = 'arfilin';
				if (strpos($user, $s3) !== false)
					$column = 'varifyed_userid';
 */


setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$form_key = new formKey();
$token = $form_key->randomkey();

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


	$link_scrutiny_idaccess='1';

        
?>
<?php 
include '../inheader.php';
//include '../insidebar.php';

?>
<style>
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


 <!-- Content Wrapper. Contains page content -->
 <div id="overlay"><div><img src="../assets/loading.gif" width="64px" height="64px"/></div></div>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
      
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Refiling Exceeds </h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
       <div class="col-md-12">
          <?php 
        $hash=(isset($_REQUEST['hash']))?htmlspecialchars($_REQUEST['hash']):'';
        if($hash !='')
        {
        	$hash1=htmlspecialchars(base64_decode($hash));
        	$hash1 = explode("-", $hash1);
        	$massage=$hash1[0];
        	
        	$token_filing_no_scrutiny= $hash1[1];
        
        ?>      
                  <div class="alert alert-success" role="alert">
  <?php echo htmlspecialchars($massage);?>
</div> 
        <?php } ?>  
        
        <div class="box-body">
			<!--<div align="center">
				<label class="radio-inline">
				  <input type="radio" value='0' name="defect_status" checked>All
				</label>
				<label class="radio-inline">
				  <input type="radio" name="defect_status" value='1'>Defective
				</label>
				<label class="radio-inline">
				  <input type="radio" value='2' name="defect_status">Defect Free
				</label>
			</div>-->
			<input type="hidden" name="cases" value='1' id='cases'>
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
					<label class="label-control col-sm-6" for="start_date">Show</label>
						<select class="form-control" name="number_of_records" id="number_of_records" onChange="return sort_by_number_of_rec(this.value);">
							<option value='20'>20</option>
							<option value='50'>50</option>
							<option value='100'>100</option>
							<option value='200'>200</option>
						</select>
					</div>
				</div>
				
			</div>
				
			 <div class="table-responsive">
				<input type="hidden" name="rowcount" id="rowcount" />
                <table class="table table-hovered table-bordered no-margin"  id="case_list">
                  <thead>
					  <tr>
					  <th>SN</th>
					  <th>Diary No</th>
					  <th>Case Type</th>
					  <th>Cause Title</th>
					  <th>Date Of Filing</th>
					  <th>Action</th>
					  </tr>
                  </thead>
                  <tbody id="case_list_body">
						
				  </tbody>				  
				</table>
				

				<!--<nav>
					<ul class="pagination">
					
					</ul>
				</nav>-->
			</div>
        </div>

        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Modal -->




<script>

$( document ).ready(function() {
    //var radioValue = $("input[name='cases']:checked").val();
	var defect_status = $("input[name='defect_status']:checked").val();
	var from_date = $("#start_date").val();
	var end_date = $("#to_date").val();
	var item_per_page = $("#number_of_records").val();
	var search_filing_no = $("#search_filing_no").val();
	getdata(defect_status,radioValue=1,item_per_page,from_date,end_date,search_filing_no);
});

/* $('input[type=radio][name=cases]').change(function() {
	var from_date = $("#start_date").val();
	var end_date = $("#to_date").val();
	var defect_status = $("input[name='defect_status']:checked").val();
	var item_per_page = $("#number_of_records").val();
	var search_filing_no = $("#search_filing_no").val();
	getdata(defect_status,this.value,item_per_page,from_date,end_date,search_filing_no);
}); */

$('input[type=radio][name=select_option]').change(function() {
	var radioValue = $(this).val();
	var item_per_page = $("#number_of_records").val();
	getdata(this.value,radioValue,item_per_page,from_date='',end_date='',search_filing_no='');
});

function sort_by_number_of_rec(item_per_page){
	var radioValue = $("input[name='select_option']:checked").val();
	var defect_status = $("input[name='defect_status']:checked").val();
	var from_date = $("#start_date").val();
	var end_date = $("#to_date").val();
	var search_filing_no = $("#search_filing_no").val();
	getdata(defect_status,radioValue=1,item_per_page,from_date,end_date,search_filing_no);
}

function reset(){
	$("#start_date").val('');
	$("#to_date").val('');
	$("#search_filing_no").val('');
	var defect_status = $("input[name='defect_status']:checked").val();
	getdata(defect_status,radioValue,20,from_date='',end_date='',search_filing_no='');
}

function search_by_date(){
	var radioValue = $("input[name='select_option']:checked").val();
	var defect_status = $("input[name='defect_status']:checked").val();
	var from_date = $("#start_date").val();
	var end_date = $("#to_date").val();
	var item_per_page = $("#number_of_records").val();
	var search_filing_no = $("#search_filing_no").val();
	getdata(defect_status,radioValue=1,item_per_page,from_date,end_date,search_filing_no);
}

function getresult(url){
	var radioValue = $("input[name='select_option']:checked").val();
	var defect_status = $("input[name='defect_status']:checked").val();
	var from_date = $("#start_date").val();
	var end_date = $("#to_date").val();
	var item_per_page = $("#number_of_records").val();
	var search_filing_no = $("#search_filing_no").val();
	getdata(defect_status,radioValue=1,item_per_page,from_date,end_date,search_filing_no,url);
}

function getdata(defect_status,radioValue,item_per_page,from_date,end_date,search_filing_no,url='refiling_exceeds_list.php?page=0'){
	
	
	var user_id = '<?php echo $sessionUserType; ?>';
	var token = '<?php echo $token ; ?>';
	 $.ajax({
            type: "POST",
            url: url,
            data: {rowcount:$("#rowcount").val(),user_id:user_id,form_key:token,radioValue:radioValue,from_date:from_date,end_date:end_date,defect_status:defect_status,item_per_page:item_per_page,search_filing_no:search_filing_no},
            beforeSend: function(){$("#overlay").show();},
			success: function (data) {
				/* $("#case_list").dataTable().fnDestroy()
			    $("#case_list_body").html(data);
			    

				$('#case_list').DataTable( {
					"order": [[ 3, "asc" ]]
				} ); */
				$("#case_list_body").html(data);
				$("#overlay").hide();
				//setInterval(function() {$("#overlay").hide(); },500);
            },
            error: function (textStatus, errorThrown) {
               alert("error");
            }

        });  
}
  
  
  function view_pdf(pdfpath)
  {
	  var loader = "<center><img src='../loader/loader.gif'></img></center>";
	  $.ajax({
            type: "POST",
            url: "../scrutiny/readpdf_file.php",
            data: {path:pdfpath},
			beforeSend: function() {
				$("#modal_body").html(loader);
				$("#iframemodal").modal('show');
			},
            success: function (data) {
			$("#modal_body").html(data);
			   //alert("success");
            },
            error: function (textStatus, errorThrown) {
				$("#modal_body").html('');
				$("#iframemodal").modal('hide');
               alert("error");
            }

        });
	  $("#modal_body").html('');
	   //var path = pdfpath+filing_no+"-"+count+".pdf";
	  //alert(path);
	 //var frame = "<iframe  src=https://docs.google.com/viewer?url="+path+"&embedded=true style='width:100%;height:500px;'></iframe>";
	 /* var frame = "<iframe  src=http://"+pdfpath+" style='width:100%;height:500px;' allowfullscreen></iframe>";
	 $("#modal_body").html(frame);
	 $("#iframemodal").modal('show'); */
  }
  

 $( document ).ready(function() {
  $('.datepicker').datepicker()
  });
 

</script>
  </script>
  <?php 
  include '../infooter.php';
  ?>
 

  </div>
</div>

   <script src="../assets/js/datatables.min.js"></script>
   <script src="../assets/js/jquery.simplePagination.js"></script>
  <?php } ?>
  
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

function get_display_court_text($db,$schemas,$court_no){
	$select = $db->prepare("select causelist_court_text from $schemas.court where court_no = ?");
	$select->bindParam(1, $court_no, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
}

function get_court($db,$schemas,$user_court,$user_type=''){
	if(empty($user_type))
		$query = "select * from $schemas.court where court_no = $user_court order by court_no";
	else
		$query = "select * from $schemas.court order by court_no";

	$courts = $db->prepare($query);
	$courts->execute();
	$courts = $courts->fetchAll();
	return $courts;
}

function main_case_court_no_from_note($db,$schemas,$main_case_number){
	$select = $db->prepare("select first_court_no from $schemas.scrutiny where filing_no = ?");
	$select->bindParam(1, $main_case_number, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
}

function main_case_court_no_from_allocation($db,$schemas,$main_case_number){
	$select = $db->prepare("select court_no from $schemas.case_allocation_temp where filing_no = ? limit 1");
	$select->bindParam(1, $main_case_number, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
}

?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<title>Case Allocation</title>
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
					$s2 = 'registrar';
					//if (strpos($_SESSION['user'], $s2) !== false){
						
				?>
					<section class="content-header">
						<center><b>Fresh Cases Allocation To Court</b></center>
					</section>
					<?php
						$msghash=$_REQUEST['msghash'];
						$courts = get_court($db,$schemas,$user_court,$_SESSION['menuaccess_codeall']);
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
							<b><font color = 'red'  ><?php echo $msg1;?> <br/> <a href="javascript:popsurety_pending_report('<?php echo htmlspecialchars($case_list_date);?>');" > Click here to view listed cases. </a></font></b></u>
						</center>
						</div>
						<?php
						}
						?>
						<?php if(($_SESSION['menuaccess_codeall'] == '11' || $_SESSION['menuaccess_codeall'] == '6') && $_SESSION['location'] == '1' ) {

							$search_type = 1; 
						?>
						<form class="form-inline" id="search_form" style="margin-left:43%;">
						  <div class="form-group">
							<label class="radio-inline">
						      <input type="radio" id="napa" name="search_type" value='1' checked onchange="return filter_case(this.value);">Napa
						    </label>
						    <label class="radio-inline">
						      <input type="radio" id="others" name="search_type" value='2' onchange="return filter_case(this.value);">Appeal/Application
						    </label>
						  </div>
						</form>
					<?php } else{
						$search_type = 2;
					} ?>
					<form name="frm" method="post" action="caseno_generation_action.php" >
						<div class="form-group row">
							<center>
								<!--<label class="radio-inline"><input type="radio" name="b_type" value="1" checked>Daily</label>
								<label class="radio-inline"><input type="radio" name="b_type" value="2">Supplementry</label>-->
							</center>
						</div>
						<div class="form-group row">
							<div class="col-sm-3 col-md-3 col-lg-3">
								<label for="court_no" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Court</label>
								<div class="col-sm-8">
									<select name='court_no' id='court_no' class='form-control' onchange="return getbench('from_list_date');">
										<option value=''>Select Court</option>
										<?php
											foreach($courts as $k=>$court){
												echo "<option value='$court[court_no]'>$court[display_court_text]</option>";
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-2 col-md-2 col-lg-2">
								<label for="listing_Date" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Listing Date</label>
								<div class="col-sm-8">
									<input type="text" autocomplete="off" id="from_list_date" name="list_date" onchange="return getbench(this.id);"  class="datepicker form-control" size="10" value=""/>
								</div>
							</div>
							<div class="col-sm-3 col-md-3 col-lg-3">
								<label for="purpose" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Purpose</label>
								<div class="col-sm-8">
									<select name="purpose_id" class="form-control" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
										<option>select</option>
										<?php
										$sql2=" select * from $schemas.master_purpose where display = 'TRUE' and status = 1";
										foreach($db->query($sql2) as $row)
										{

										  $purpose_code=$row['purpose_code'];
										 if($purpose_id == $purpose_code)
														{
												print "<option value=".htmlentities(htmlspecialchars($row['purpose_code']))." selected>".strtoupper(htmlentities(htmlspecialchars($row['purpose_name'])))."</option>";
														}
												   else
														{
														print "<option value=".htmlentities(htmlspecialchars($row['purpose_code'])).">".strtoupper(htmlentities(htmlspecialchars($row['purpose_name'])))."</option>";
												}
										 } 	?>
									</select>
								</div>
							</div>
							<!--------------Filing Search-------------------->
							<div class="col-sm-4 col-md-4 col-lg-4">
							  
									<label for="filing_no" class="col-sm-2 col-form-label"><font color="red">*</font></span></font>Filing No</label>
									<span id="error_msg"></span>
									<div class="col-sm-5"><input type="text" id="filing_no" class="form-control" name="filing_no" value="" ></div>
									<button type="button"  onClick=" return search_by_filing();" class="btn btn-primary">Search</button>
									<button type="button" id="reset_button" class="btn btn-warning" onClick="return reset_all();">Reset</button>
							  
								
							</div> 
							<!--------------Filing Search-------------------->
							<!--<div class="col-sm-4 col-md-4 col-lg-4">
								<button type="button" class="btn btn-primary" onClick="return show_all();">Show All Fresh Cases</button><span>
								<button type="button" class="btn btn-warning" onClick="return reset_all();">Reset to selection wise</button></span>
							</div>-->
						</div>
						<div class="table-responsive" id="benches">
						</div>
					<?php //} ?>
						<div class="table-responsive" id="table_data">
							<table cellspacing="0" align="center" class="table no-margin table-bordered table-striped table-hover casealloc_table"   cellpadding="2" border="1" width="95%" class="std">
								<thead style="background-color:#846312;color:#ffffff; white-space: nowrap;">
									<th><b>Sr.No.</b></th>
									<?php //if (strpos($_SESSION['user'], $s2) !== false){ ?>
									<th><input type="checkbox" name="allbox" onClick="un_check(this);" title="Select or Deselct ALL" style="background-color:#ccc;"/></th>
									<?php //} ?>
									<th align="left" width="150"><b>Diary No.</b></th>
									<th align="left" width="700"><B>Case No </b></th>
									<th align="left" width="700"><B>Main Case No </b></th>
									<th align="left" width="700"><B>Title </b></th>
									<th align="left" width="250"><B>Date Of Filing</b></th>
									<th align="left" width="250"><B>Date Of Registration</b></th>
									<th align="left" width="250"><B>Main Case Court No</b></th>
									<th align="left" width="250"><B>Remark</b></th>
									<!--<th align="left" width="700"><B>Action </b></th>-->
								</thead>
								<tbody id="search_data_here">
								</tbody>
							</table>
							<nav><ul class="pagination col-md-9 col-sm-9">
							<?php 
							$sql1=$db->prepare("select count(*) from $schemas.case_detail as a left join $schemas.scrutiny as s on s.filing_no = a.filing_no
					left join e_case_detail as ecd on ecd.filing_no = a.filing_no
					where (a.case_no is NOT NULL OR a.case_no != '') and (a.case_year is NOT NULL OR a.case_year != '') and (a.case_type is NOT NULL  and a.case_type != 60) and 
					(a.location_code is NOT NULL) and  (a.legal_aid IS NULL OR a.legal_aid = 'NULL') and a.status = 'P'");
							$sql1->execute();
							$total_records = $sql1->fetchColumn();
							
							if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++):  
										if($i == 1):?>
										<li class='active'  id="<?php echo $i;?>"><a href='get_cases_for_allocation.php?page=<?php echo $i;?>'><?php echo $i;?></a></li> 
										<?php else:?>
										<li id="<?php echo $i;?>"><a href='get_cases_for_allocation.php?page=<?php echo $i;?>'><?php echo $i;?></a></li>
									<?php endif;?>          
							<?php endfor;endif;?>
							</ul>
							<ul class="show_entries col-md-3 col-sm-3"><li id="total_rec" style="float:right;"></li></ul>
							</nav>
						</div>
						<br/>
						<div class="form-group">
							<center><input type="submit" id="allocate_case" value="Allocate" class="btn brn-sm btn-success" name="allocate" onClick="return submitForm2();"></center>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<input type="hidden" id="all_records" value='<?php echo $total_records; ?>'>
	
<?php } ?>
	
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
 <script src="../assets/js/jquery.simplePagination.js"></script>
	<script>
	
		$(document).ready(function() {
			let search_type = '<?php echo $search_type; ?>';
			search_data('all_cases',100,search_type);
			/* $('.casealloc_table').dataTable( {
				 "lengthMenu": [[50,100,200,-1], [50, 100, 200, "All"]],
				  "pageLength": 100
				} ); */
		} );
	
		function getbench(listing_date_id){
			listing_date = $("#"+listing_date_id).val();
			court_no = $("#court_no").val();
			if(listing_date === '')
			{
				//alert("please enter listing date");
				return false;
			}
			 $("#benches").html("<center>loading......<center>");
			$.ajax({
            type: "POST",
            url: "getbench_by_listing_date.php",
            data: {listing_date:listing_date,court_no:court_no},
            success: function (data) {
			   $("#benches").html(data);
			   if(court_no < 50){
			   	$('.cases').each(function() {
				  if ($(this).data('list_with_defect') == 'LWD') {
				    $(this).prop('disabled', true);
				    $(this).prop('checked', false);
				  }else{
				  	$(this).prop('disabled', false);
				  }
				});
			   }else{
			   	$('.cases').each(function() {
				  if ($(this).data('list_with_defect') == 'LWD') {
				    $(this).prop('disabled', false);
				  }else{
				  	$(this).prop('disabled', true);
				    $(this).prop('checked', false);
				  }
				});
			   }
			   //alert("success");
            },
            error: function (textStatus, errorThrown) {
               alert("error");
            }

        });
		//$("#search_data_here").html('<center>Loading...</center>');
		//search_data('by_court_and_listing_date',100,listing_date,court_no);
		/* $.ajax({
            type: "POST",
            url: "get_cases_for_allocation.php",
            data: {type:'by_court_and_listing_date',listing_date:listing_date,court_no:court_no},
            success: function (data) {
			   $(".casealloc_table").dataTable().fnDestroy()
			   $("#search_data_here").html(data);
			   $('.casealloc_table').dataTable( {
				  "lengthMenu": [[50,100,200,-1], [50, 100, 200, "All"]],
				  "pageLength": 100
				} );
            },
            error: function (textStatus, errorThrown) {
               alert("error");
            }

        });  */
		}

		function filter_case(search_type){
			search_data('all_cases',100,search_type);
		}
		
		
		function show_all(){
			let search_type = $('input[name="search_type"]:checked').val();
			search_data('all_cases',100,search_type);
		}
		
		function search_by_case_no(){
			var search_case_type = $("#search_case_type").val();
			var search_case_year = $("#search_case_year").val();
			var search_case_number = $("#search_case_number").val();
			let search_type = $('input[name="search_type"]:checked').val();
			if(search_case_type == '' && search_case_number == '' && search_case_year == ''){
				alert("please fill at least one details");
				return false;
			}
			search_data('case_no_search',1,search_type,'','',search_case_type,search_case_year,search_case_number); 
			
		}

		/********************Search BY Filing No*************************/
		function search_by_filing()
		{
		
			var filing_no = $("#filing_no").val();
			let search_type = $('input[name="search_type"]:checked').val();
			 var leng = $('#filing_no').val().length;
			
				if(leng == 0)
				{
					$('#error_msg').html('<font color="red">Please Enter Filing No</font>');	 
					return false;
				}
		
				  if(leng == 16 )
				  {
						$('#error_msg').html();
						if(filing_no!==''){
						search_data('search_filing_no',1,search_type,'','','','','',filing_no); 
						}
						
				  }	
				
		}
		
		
			/********************Search BY Filing No*************************/
		
		function search_data(type,item_per_page,search_type,listing_date,court_no,search_case_type,search_case_year,search_case_no,filing_no=''){
		$.ajax({
		type: "POST",
		url: "get_all_cases_count.php",
		data: {type:type,search_type:search_type,listing_date:listing_date,court_no:court_no,search_case_type:search_case_type,search_case_year:search_case_year,search_case_no:search_case_no,filing_no:filing_no},
		success: function (total_items) { 
		   $('.pagination').pagination({
			items: total_items,
			itemsOnPage:item_per_page,
			cssStyle: 'light-theme',
			currentPage : 1,
			onPageClick : function(pageNumber) {
				jQuery("#search_data_here").html('<center>loading...</center>');
				jQuery("#search_data_here").load("get_cases_for_allocation.php?page=" + pageNumber+"&limit="+ item_per_page+"&type="+type+"&search_type="+search_type+"&listing_date="+listing_date+"&court_no="+court_no+"&search_case_type="+search_case_type+"&search_case_year="+search_case_year+"&search_case_no="+search_case_no+"&filing_no="+filing_no);
			},
			onInit :function() {
				jQuery("#search_data_here").html('loading...');
				jQuery("#search_data_here").load("get_cases_for_allocation.php?page=1&limit="+ item_per_page+"&type="+type+"&search_type="+search_type+"&listing_date="+listing_date+"&court_no="+court_no+"&search_case_type="+search_case_type+"&search_case_year="+search_case_year+"&search_case_no="+search_case_no+"&filing_no="+filing_no);
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
		
		function reset_all(){
			
			$("#search_data_here").html('<center>Loading...</center>');
			$('#error_msg').html();
			$('#filing_no').val('');
			listing_date = $("#from_list_date").val();
			court_no = $("#court_no").val();
			let search_type = $('input[name="search_type"]:checked').val();
			
			//search_data('reset_cases',100,search_type,listing_date,court_no,'','','',''); 
			search_data('all_cases',100,search_type,listing_date,court_no,'','','',''); 
			
			/* $.ajax({
            type: "POST",
            url: "get_cases_for_allocation.php",
            data: {type:'reset_cases',listing_date:listing_date,court_no:court_no},
            success: function (data) {
			   $(".casealloc_table").dataTable().fnDestroy()
			   $("#search_data_here").html(data);
			   $('.casealloc_table').dataTable( {
				  "lengthMenu": [[50,100,200,-1], [50, 100, 200, "All"]],
				  "pageLength": 100
				} );
            },
            error: function (textStatus, errorThrown) {
               alert("error");
            }

			});  */
		}
		
		function search_bar(){
			$("#search_form").css("display","block");
			$(".search_bar").css("display","none");
		}
		
		
		function dismiss(){
			$("#search_form").css("display","none");
			$(".search_bar").css("display","block");
		}
		
		function save_reg_listing_date(filing_no){
		var reg_listing_date = $("#reg_list_date_"+filing_no).val();
		var reg_court_no = $("#reg_court_no_"+filing_no).val();
		if(reg_listing_date === ''){
			//swal('','Please enter listing date','error');
			alert('Please enter listing date');
			return false;
		}
		if(reg_court_no === ''){
			//swal('','Please select Court','error');
			alert('Please select Court');
			return false;
		}
		$.ajax({
			type: "POST",
			url: "get_cases_for_allocation.php",
			data: {type:'save_reg_listing_date',filing_no:filing_no,reg_listing_date:reg_listing_date,reg_court_no:reg_court_no},
			success: function (data) {
				if(data){
				alert("Listing date updated");
				location.reload(true);
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
  
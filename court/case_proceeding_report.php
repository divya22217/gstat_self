<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");

 /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */

include("../db_inc1.php");


 include("../inheader.php");
include("../master/functions.php");
	include '../classes/Editcase.class.php';
	$edit_Case_obj = new Editcase();

$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] :'';
$year=htmlspecialchars(date("Y"));
$msg_ip .= "User IP : ".$_SERVER["REMOTE_ADDR"]."\r\n"; //Sender's IP
$user = $_SESSION['user'];



// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	// If they are not, we redirect them to the login page.
	// Remember that this die statement is absolutely critical.  Without it,
	// people can view your members-only content without logging in.
	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}
else
 {
	
	
	
function remove_path($file, $path = UPLOAD_PATH) {
if(strpos($file, $path) !== FALSE) {
return substr($file, strlen($path));
}
}

$frm = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $frm;//csrf

$schemas=htmlspecialchars($_SESSION['schema_name']);
$user_id=$_SESSION['id'];
$main_cases = main_case_type();	
?>





	

	<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
	<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
	<script src="../plugins/jQueryUI/jquery-ui.js"></script>
	<script src="../plugins/jQueryUI/date.js"></script>
	
<link rel="stylesheet" type="text/css" href="../includes/highslide/highslide.css" />
<script type="text/javascript" src="../includes/highslide/highslide-with-html1.js"></script>
	<script language="javascript">
	//start of my script
	function submitForm3()
{
 	with(document.frm)
	{		
	 action = "case_proceeding_report.php";
	 submit();
	}
}
	
		function change(id, newClass)
		{
			identity=document.getElementById(id);
			identity.className=newClass;

		}
		function printPage()
		{
			change("testdiv","hidden");
			window.print();
		}

		function popsurety_pet_adv_name(cfy)

		{

			var url = "../public/details.php?filing_no="+cfy;
			var width = 800;
			var height = 1300;

			var left = (screen.width-width)/2;

			var top = (screen.height - height)/2;

			var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',resizable=1';

			window.open(url,"print",params);
		}





		function popsurety_pet_adv_name2(cfy)

		{

			var url = cfy;
			var width = 800;
			var height = 1300;

			var left = (screen.width-width)/2;

			var top = (screen.height - height)/2;

			var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',scrollbars=1';

			window.open(url,"print",params);
		}




	</script>
	<script>
		function submitForm()
		{
			with(document.frm)
			{


				if(next_list_date.value == "")
				{
					alert("Enter ORDER DATE....");
					next_list_date.value='';
					next_list_date.focus();
					return false;
				}

				action="<?php echo $_SERVER[SCRIPT_NAME];?>";
				submit();
				document.frm.submit1.disabled = true;
				document.frm.submit1.value = 'Please Wait...';
				return true;
			}

		}



	</script>

	<style>
		table, td, th {
			border: 1px solid #ffffff;
		}



		th {
			background-color: #846313;
			color: white;
		}
	</style>
</head>


<div class="wrapper" style="background-color:#ffffff;">

	

	<div class="content-wrapper" style="min-height: 946px;">
		<!-- Content Header (Page header) -->
		<section class="content">


<table class="table">
<tr>
	<th valign="top" align="center" colspan="16">
					<b><font face="Verdana" size="3"><u>Case Proceeding Reports</u></font> </b>
	</th>
	</tr>
<form name="frm" method="post" action="">

<tr><td colspan="16"></td></tr>

<?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>
<tr><td colspan="16"><font color="red">*</font><font size="1">LISTING DATE:</font>
<input type="text" id="next_list_date" name="next_list_date" class="datepicker"
readonly="readonly" size="8" autocomplete="off" maxlength="10" value="<?php print htmlspecialchars($next_list_date); ?>" />
<input type="submit" id="submit11" name="submit11" value="Search" />
</td>
</tr>
</form>

 
<tr>
<th>S No.</th>
<th>Case No.</th>
<th>
Party Detail
</th>
<th>Present Purpose</th>
<th>Next Purpose</th>
<th>Next Listing/Disposed date</th>
<!--<th>Update Next list Date</th>-->
<!--<th>Action</th>-->
</tr>
 
 <?php
 if(isset($_POST['submit11'])){
	 
	 $s2 = 'court';
	if (strpos($user, $s2) !== false){
		$show_user = FALSE;
	}else{
		$show_user = TRUE;
	}
	 //print_r($_POST);
	 //get  all post values to php variables
	 $listing_date_post = $_POST['next_list_date'];
	 $bench_nature_post = $_POST['list_before'];
	 $court_no_post = $_POST['court_no'];
	 
 list($day,$month,$year)=explode('/',$listing_date_post);
 $listing_date_post=$year.'-'.$month.'-'.$day;
 if($show_user){
 $query = "select * from $schemas.case_proceeding where listing_date=? and user_id = ?";
 }else{
 $query = "select * from $schemas.case_proceeding where listing_date=?";
 }
 $caseproceedingsql = $db->prepare($query);
 $caseproceedingsql->bindParam(1, $listing_date_post, PDO::PARAM_INT);
 if($show_user){
	$caseproceedingsql->bindParam(2, $user_id, PDO::PARAM_INT);
 }
 $caseproceedingsql->execute();
 


 if($caseproceedingsql->rowCount()==0)
 {
 ?>
 <tr>
<td align="center" colspan="16" ><font color="red" size="4"><b>No Record Found </b></font></td>
</tr>
 <?php
 } 
 if($caseproceedingsql->rowCount()>0)
 {
$counter=1;


 while ($caseproceedingsqlrow = $caseproceedingsql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
	$filing_no = htmlspecialchars($caseproceedingsqlrow['filing_no']);
	$purpose_code = htmlspecialchars($caseproceedingsqlrow['purpose']);
	$next_purpose = htmlspecialchars($caseproceedingsqlrow['next_list_purpose']);
	$next_list_date = htmlspecialchars($caseproceedingsqlrow['next_list_date']);
	$current_listing_date = htmlspecialchars($caseproceedingsqlrow['listing_date']);
	$case_status = htmlspecialchars($caseproceedingsqlrow['todays_status']);
	$court_no_display = htmlspecialchars($caseproceedingsqlrow['court_no']);
	
	//print_r($filing_no);
	
 	if($filing_no !='')
 	{
 	$stcn = $db->prepare("select * from $schemas.case_detail where filing_no=?");
 	$stcn->bindParam(1, $filing_no, PDO::PARAM_STR);
 	$stcn->execute();
 	while ($rw2 = $stcn->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 	{
 		$filing_no98 = htmlspecialchars($rw2['filing_no']);
        $case_type=htmlspecialchars($rw2['case_type']);
		if (in_array($case_type, $main_cases)){
			$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($rw2['filing_no']);
		}else{
			$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($rw2['main_case_ia_no']);
			if(empty($show_party_filing_no))
				$show_party_filing_no = htmlspecialchars($rw2['filing_no']);
		}
		$pt_name =get_party($db,$show_party_filing_no,'P',1);
		$rs_name =get_party($db,$show_party_filing_no,'R',1);		
 		$case_no=htmlspecialchars($rw2['case_no']);
 		$pet_code=htmlspecialchars($rw2['pet_org_type']);
 		$res_code=htmlspecialchars($rw2['res_org_type']);
 		$pet_type=htmlspecialchars($rw2['pet_type']);
 		$res_type=htmlspecialchars($rw2['res_type']);
 		$case_year=htmlspecialchars($rw2['case_year']);
		//$case_status = htmlspecialchars($rw2['status']);
		$location_code=htmlspecialchars($rw2['location_code']);
		//$location_code=htmlspecialchars($rw2['bench_location']);
		$st1 = $db->prepare("select short_name from mater_location_city where city_id=?");
$st1->execute(array($location_code));
$bech_code= $st1->fetchColumn();
 	}
 	if($filing_no98 !='')
 	{
 		if($case_type > 0)
 		{
 			$stQ = $db->prepare("select short_name from case_type where id = ?");
 			$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
 			$stQ->execute();
 			$case_type_short_name=$stQ->fetchColumn();
 		}	
 	}
 	}
 	$hash=base64_encode($item_no);
	 $hash2 = base64_encode($filing_no.'/'.$schemas); //is used to view page
 ?>
<tr>
 <td><?php echo $counter; ?></td>
<td><a href="javascript::void(0);" onClick="return case_proceedings('<?php echo $filing_no; ?>');">



<?php if ($case_no>0){ echo $case_type_short_name.'/'.htmlspecialchars(strtoupper($case_no).'/'.$bech_code.'/'.$case_year);}else{
echo "Dairy No ".$filing_no;
}

?>


</a></td>
<td>
<?php 
		echo "<h7><font color='red'><center>";
		if($pet_code >0){echo htmlspecialchars(html_entity_decode($pet_org_name));}else{echo html_entity_decode($pt_name);}
	echo "</font><br><font color='blue'>VS</font><br><font color='red'>";
		if($res_code >0){echo htmlspecialchars(html_entity_decode($res_org_name));}else{echo html_entity_decode($rs_name);}
	
	echo "</center></font></h7>";
		?>
</td>

<td>
<?php 
$puposesql = $db->prepare("select purpose_name from $schemas.master_purpose where purpose_code=?");
$puposesql->bindParam(1, $purpose_code, PDO::PARAM_STR);
$puposesql->execute();
$puposesql_result = $puposesql->fetch(PDO::FETCH_OBJ);
$purpose_name = $puposesql_result->purpose_name;
echo $purpose_name;
?>
</td>

<td>
<?php 
if($case_status == 'P'){
$puposesql = $db->prepare("select purpose_name from $schemas.master_purpose where purpose_code=?");
$puposesql->bindParam(1, $next_purpose, PDO::PARAM_STR);
$puposesql->execute();
$puposesql_result = $puposesql->fetch(PDO::FETCH_OBJ);
$purpose_name = $puposesql_result->purpose_name;
echo $purpose_name;
}else if($case_status == 'X'){
	echo "Partial Disposal";
}else{
	echo "Disposed";
}
?>
</td>
<td>
<?php 
if($case_status == 'P'){
echo htmlspecialchars(date('d/m/Y',strtotime($next_list_date)));
}else if ($case_status == 'D'){
	$disposal_date = $db->prepare("select disposal_date from $schemas.case_disposal where filing_no=? order by id desc limit 1");
	$disposal_date->bindParam(1, $filing_no, PDO::PARAM_STR);
	$disposal_date->execute();
	$disposal_date = $disposal_date->fetchColumn();
 echo htmlspecialchars(date('d/m/Y',strtotime($disposal_date)));
}else{
echo 'Report Awaited Till ';
	 $report_awaited_date = $db->prepare("select disposal_date from $schemas.case_disposal where filing_no=? order by id desc limit 1");
	$report_awaited_date->bindParam(1, $filing_no, PDO::PARAM_STR);
	$report_awaited_date->execute();
	$report_awaited_date = $report_awaited_date->fetchColumn();
	echo "<b>".date('d/m/Y',strtotime($report_awaited_date))."</b>";
}?>
</td>

<!--<td>
<?php 
if($case_status == 'P' && !$show_user){
echo $edit_Case_obj->nextListForm($filing_no,$court_no_display,$current_listing_date,$next_list_date);
}?>
</td>-->
<!--<td>
<button type="button" class="btn btn-primary edit_delete_proceeding_<?php echo $filing_no; ?>" onClick="return edit_delete_proceeding('<?php echo $filing_no; ?>','<?php echo $current_listing_date; ?>','<?php echo $court_no_post; ?>')">Reverting Proceeding Information</button>
</td>-->
</tr>
<?php 

 //while loop end all query....
$counter++;
 }
 }
  }
?>

</table>
	 </div>

</section>

<div class="control-sidebar-bg"></div>

</div>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<?php include '../infooter.php'; ?>
<script language="javascript">

    hs.graphicsDir = '../includes/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.wrapperClassName = 'draggable-header';
	
	function updateNextListDate(filing_no){
		var new_next_list_date = $("#next_list_date_update_"+filing_no).val();
		var court_no = $("#court_no_"+filing_no).val();
		var next_list_date = $("#next_list_date_"+filing_no).val();
		var current_listing_date = $("#current_listing_date_"+filing_no).val();
		if(new_next_list_date == ''){
			swal("","Please enter next list date","warning");
			return false;
		}
				$.ajax({
					type: "POST",
					url: "../edit/edit_case_ajax.php",
					data: {type:'update_next_list_date',filing_no:filing_no,court_no:court_no,next_list_date:next_list_date,current_listing_date:current_listing_date,new_next_list_date:new_next_list_date},
/* 					contentType: false,
					cache: false,
					processData:false, */
					dataType: 'json',
					beforeSend:function(){ 
						$('.update_btn_'+filing_no).attr("disabled","disabled");
						$('.update_btn_'+filing_no).css("opacity",".5");
					},
					success: function (response) {
						$('.update_btn_'+filing_no).css("opacity","");
						$('.update_btn_'+filing_no).removeAttr("disabled");
						if(response.status == 0){
							swal('',response.message,'warning');
						}
						else{
							swal('',response.message,'success');
							location.reload(true);
						}
					},
					error: function (textStatus, errorThrown) {
					  console.log(textStatus);
					   alert(errorThrown);
					}

				}); 
	}
	
	function edit_delete_proceeding(filing_no,listing_date,court_no){
		$.ajax({
            type: 'POST',
            url: '../edit/edit_case_ajax.php',
            data: {type:"proceeding_info",filing_no:filing_no,listing_date:listing_date,court_no:court_no},
            /*dataType: 'json',
            contentType: false,
            cache: false,
            processData:false, */
            beforeSend: function(){
                //$('.save_last_info').attr("disabled","disabled");
				$('#edit_delete_proceeding_modal_body').html("Loading.....");
                $('#edit_delete_proceeding_modal').modal("show");
            },
            success: function(response){ //console.log(response);
				$('#edit_delete_proceeding_modal_body').html(response);
            },
			error: function (textStatus, errorThrown) {
				console.log(textStatus);
			   swal("",errorThrown,'error');
			   
			}
        });
	}
	
	function delete_proceeding(filing_no,listing_date,court_no){
		swal({
                title: "Are you sure?",
                text: "Do you want to delete proceeding",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                   $.ajax({
						type: 'POST',
						dataType: 'json',
						url: '../edit/edit_case_ajax.php',
						data: {type:"delete_proceeding",filing_no:filing_no,listing_date:listing_date,court_no:court_no},
						beforeSend: function(){
						},
						success: function(response){ //console.log(response);
							if(response.status == 1){
							  swal('',response.message,'success');
							  $("#listing_data").hide();
							}else{
								swal('',response.message,'error');
							}
						},
						error: function (textStatus, errorThrown) {
							console.log(textStatus);
						   swal("",errorThrown,'error');
						   
						}
					});
                } else {
                }
            });
	}
	
	function delete_order(filing_no,item_no,hide_param){
		swal({
                title: "Are you sure?",
                text: "Do you want to delete this",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                   $.ajax({
						type: 'POST',
						dataType: 'json',
						url: '../edit/edit_case_ajax.php',
						data: {type:"delete_order",filing_no:filing_no,item_no:item_no},
						beforeSend: function(){
						},
						success: function(response){ //console.log(response);
							if(response.status == 1){
							  swal('',response.message,'success');
							  $("#"+hide_param+"_"+item_no).hide();
							}else{
								swal('',response.message,'error');
							}
						},
						error: function (textStatus, errorThrown) {
							console.log(textStatus);
						   swal("",errorThrown,'error');
						   
						}
					});
                } else {
                }
            });
	}
	
	function delete_judgement(filing_no,order_id){
		
		swal({
                title: "Are you sure?",
                text: "Do you want to delete this judgement",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                   $.ajax({
						type: 'POST',
						dataType: 'json',
						url: '../edit/edit_case_ajax.php',
						data: {type:"delete_judgement",filing_no:filing_no,order_id:order_id},
						beforeSend: function(){
						},
						success: function(response){ //console.log(response);
							if(response.status == 1){
							  swal('',response.message,'success');
							  $("#judgement_"+order_id).hide();
							}else{
								swal('',response.message,'error');
							}
						},
						error: function (textStatus, errorThrown) {
							console.log(textStatus);
						   swal("",errorThrown,'error');
						   
						}
					});
                } else {
                }
            });
		
	}
	
	function change_status(filing_no,order_id){
		
	}
	
function case_proceedings(filing_no){
	$.ajax({
            type: 'POST',
            url: '../edit/edit_case_ajax.php',
            data: {type:"all_proceedings",filing_no:filing_no},
            /*dataType: 'json',
            contentType: false,
            cache: false,
            processData:false, */
            beforeSend: function(){
                //$('.save_last_info').attr("disabled","disabled");
				$('#edit_delete_proceeding_modal_body').html("Loading.....");
                $('#edit_delete_proceeding_modal').modal("show");
            },
            success: function(response){ //console.log(response);
				$('#edit_delete_proceeding_modal_body').html(response);
            },
			error: function (textStatus, errorThrown) {
				console.log(textStatus);
			   swal("",errorThrown,'error');
			   
			}
        });
}


</script>



<?php
//count loop End
?>

<!-- edit status modal -->
<div id="edit_delete_proceeding_modal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:90%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Proceeding Information</h4>
      </div>
      <div class="modal-body" id="edit_delete_proceeding_modal_body">
        <p>Loading.........</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- end -->

<?php } ?>
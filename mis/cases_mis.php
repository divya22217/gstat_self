<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

include("../db_inc1.php");


 include("../inheader.php");
include("../custom/custom_function.php");

$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] :'';
$year=htmlspecialchars(date("Y"));
$msg_ip .= "User IP : ".$_SERVER["REMOTE_ADDR"]."\r\n"; //Sender's IP
$user = $_SESSION['user'];
$location_id = $_SESSION['location'];
$selected_location  = isset($_REQUEST['location']) ? $_REQUEST['location'] : $_SESSION['location'];



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

if($_SESSION['location'] == '1' && $_SESSION['menuaccess_codeall'] == '11'){
    $query = "select schema_name from mater_location_city where city_id = ?";
    $res = $db->prepare($query);
    $res->bindParam(1, $selected_location, PDO::PARAM_INT);
    $res->execute();
    $schemas = $res->fetchColumn();
    $location_id = $selected_location;
}else{

$schemas=htmlspecialchars($_SESSION['schema_name']);
}
$user_id=$_SESSION['id'];
$main_cases = main_case_type();	
?>







<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>


<link rel="stylesheet" type="text/css" href="../includes/highslide/highslide.css" />
<script type="text/javascript" src="../includes/highslide/highslide-with-html1.js"></script>
<link rel="stylesheet" type="text/css" href="../datatable/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../datatable/css/buttons.dataTables.min.css">
<script language="javascript">
//start of my script
function submitForm() {

    with(document.frm) {
        action = "cases_mis.php";
        submit();
    }
}
</script>


<style>
table,
td,
th {
    border: 1px solid #ffffff;
}



th {
    background-color: #846312;
    color: white;
}
.th-flex {
    display: flex;
    justify-content: center;
    align-items: center;
    white-space: nowrap;
}
.th-flex select, .th-flex input {
    margin-right: 20px;
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
                        <b>
                            <font face="Verdana" size="3">Cases Reports</font>
                        </b>
                    </th>
                </tr>
                <form name="frm" method="post" action="">

                    <tr>
                        <td colspan="16"></td>
                    </tr>

                    <?php  
                    $from_date = isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] :'';
		$to_date = isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] :'';
		$search_type = isset($_REQUEST['search_type']) ? $_REQUEST['search_type'] :'0';
		$selected_court_no = isset($_REQUEST['court_no']) ? $_REQUEST['court_no'] :'0';
		$selected_case_status = isset($_REQUEST['case_status']) ? $_REQUEST['case_status'] :'';
		$selected_order_type = isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] :'0';
		$selected_judge = isset($_REQUEST['judges']) ? $_REQUEST['judges'] :'0';
		$search_case_type  = isset($_REQUEST['search_case_type']) ? $_REQUEST['search_case_type'] : '';
       
		if($search_case_type == ''){
			$ct_qyery = '';
			$case_type_name_selected = '';
		}else{
			if($search_type == '1')
				$ct_qyery = "and a.case_type_nclat = ? ";
			else
				$ct_qyery = "and a.case_type = ? ";
			 
			    $query = "select case_type_desc from case_type where id = ?";
				$res = $db->prepare($query);
				$res->bindParam(1, $search_case_type, PDO::PARAM_STR);
				$res->execute();
				$case_type_name_selected = $res->fetchColumn();
			
		} 
 ?>                 <?php if($_SESSION['location'] == '1' && $_SESSION['menuaccess_codeall'] == '6' && $_SESSION['menuaccess_codeall'] == '7') { ?>
                    <tr>
                        <td colspan="16" class="th-flex">
                            <?php $query = "select * from mater_location_city where display=true order by city_id ";
                            $res = $db->prepare($query);
                            $res->execute();
                            $locations = $res->fetchAll(); ?>
                            <select style="margin-left: 43%;
    margin-right: 43%;" name='location' id="location" class="form-control" onChange="return submitForm();">
                                <?php foreach($locations as $k=>$location){ ?>

                                <option value='<?php echo $location['city_id']; ?>'
                                    <?php echo ($selected_location == $location['city_id'])?'selected':''; ?>>
                                    <?php echo strtoupper($location['schema_name']); ?></option>
                                <?php   }
 echo "</select>"; ?>
                        </td>
                    </tr>
                <?php } ?>
                    <tr>
                        <td colspan="16" class="th-flex">
                            <font color="red">*</font> 
                            <select name='search_type' class="form-control" onChange="return submitForm();">
                                <option value='0' <?php echo ($search_type == '0')?'selected':''; ?>>Search By</option>
                                <option value='1' <?php echo ($search_type == '1')?'selected':''; ?>>Date Of Filing
                                </option>
                                <option value='2' <?php echo ($search_type == '2')?'selected':''; ?>>Registred Date
                                </option>
                                <option value='3' <?php echo ($search_type == '3')?'selected':''; ?>>Disposed Date
                                </option>
                                <option value='4' <?php echo ($search_type == '4')?'selected':''; ?>>Court No</option>
                                <option value='5' <?php echo ($search_type == '5')?'selected':''; ?>>Order Type</option>
                                <option value='6' <?php echo ($search_type == '6')?'selected':''; ?>>Judge Wise</option>
                            </select>

                            <?php if($search_type == '3'){
	$query = "select * from $schemas.court order by court_no";
	$res = $db->prepare($query);
	$res->execute();
	$courts = $res->fetchAll(); ?>
                            <select name='court_no' class="form-control">
                                <?php foreach($courts as $k=>$court){ ?>

                                <option value='<?php echo $court['court_no']; ?>'
                                    <?php echo ($selected_court_no == $court['court_no'])?'selected':''; ?>>
                                    <?php echo $court['display_court_text']; ?></option>
                                <?php	}
 echo "</select>"; ?>

                                <?php }	

 if($search_type == '4'){
	$query = "select * from $schemas.court order by court_no";
	$res = $db->prepare($query);
	$res->execute();
	$courts = $res->fetchAll(); ?>
                                <select name='court_no' class="form-control">
                                    <?php foreach($courts as $k=>$court){ ?>

                                    <option value='<?php echo $court['court_no']; ?>'
                                        <?php echo ($selected_court_no == $court['court_no'])?'selected':''; ?>>
                                        <?php echo $court['display_court_text']; ?></option>
                                    <?php	}
 echo "</select>"; ?>

                                    <?php }	
if($search_type == '5'){ ?>
                                    <select name='order_type' class="form-control">
                                        <option value='D' <?php echo ($selected_order_type == 'D')?'selected':''; ?>>
                                            Daily Order</option>
                                        <option value='J' <?php echo ($selected_order_type == 'J')?'selected':''; ?>>
                                            Judgement</option>
                                    </select>
                                    <?php
}
 if($search_type == '6'){
	$query = "select * from $schemas.master_judge order by judge_desg_code";
	$res = $db->prepare($query);
	$res->execute();
	$judges = $res->fetchAll(); ?>
                                    <select name='judges' class="form-control">
                                        <?php foreach($judges as $k=>$judge){ ?>

                                        <option value='<?php echo $judge['judge_code']; ?>'
                                            <?php echo ($selected_judge == $judge['judge_code'])?'selected':''; ?>>
                                            <?php echo $judge['judge_name']; ?></option>
                                        <?php	}
 echo "</select>";
} ?>
                                        <font color="red">*</font>
                                        <font size="">From Date:</font>
                                        <input type="text" id="from_date" name="from_date" class="datepicker form-control"
                                            readonly="readonly" size="8" autocomplete="off" maxlength="10"
                                            value="<?php print htmlspecialchars($from_date); ?>" />
                                        <font color="red">*</font>
                                        <font size="">To Date:</font>
                                        <input type="text" id="to_date" name="to_date" class="datepicker form-control"
                                            readonly="readonly" size="8" autocomplete="off" maxlength="10"
                                            value="<?php print htmlspecialchars($to_date); ?>" />
                                        Case Type : <select name='search_case_type' id='search_case_type' class="form-control">
                                            <option value="" <?php echo ($search_case_type == '')?'selected':''; ?>>ALL
                                            </option>
                                            <?php 
				 $query = "select * from case_type where status = 't' or id = '60'";
				$res = $db->prepare($query);
				$res->execute();
				$all_case_types = $res->fetchAll();
				foreach($all_case_types as $case_types) { ?>
                                            <option value='<?php echo $case_types['id']; ?>'
                                                <?php echo ($search_case_type == $case_types['id'])?'selected':''; ?>>
                                                <?php echo $case_types['case_type_desc']; ?></option>
                                            <?php	} 
			?>
                                        </select>
                                        <input type="submit" id="submit11" name="submit11" class="btn btn-primary" value="Search" />
                        </td>
                    </tr>
                </form>
            </table>
            <div class='table-responsive'>

                <?php
 if(isset($_POST['submit11'])){ ?>
                <?php 

if($search_type == '0'){
	echo "<tr><td colspan='6' style='color:red;'>Please select search type</td></tr>";
}

list($day,$month,$year)=explode('/',$from_date);
 $from_date_post=$year.'-'.$month.'-'.$day;
 
 list($day_to,$month_to,$year_to)=explode('/',$to_date);
 $to_date_post=$year_to.'-'.$month_to.'-'.$day_to;
 $na = 'NA';
 $blank = '';
 $filing_no_length = '16';
 $wrongly_updated = 'W';
 if($search_type == '1'){
	 $e_file_backlog = 'N';
	 $query = "select a.filing_no,a.dt_of_filing as date_of_filing,b.regis_date as registration_date,c.case_type_desc as case_type_name,
	b.case_no,b.case_year,d.bench_location_name,b.status,a.act_id,ma.act_name,a.filingnumberia,b.transfrred_case_type_short from e_case_detail as a
	left join $schemas.case_detail as b on b.filing_no = a.filing_no
	left join master_act as ma on ma.act_id = a.act_id
	left join case_type as c on c.id = a.case_type_nclat
	left join $schemas.bench_location as d on d.city_id = a.location_id
	where date(a.dt_of_filing) between ? and ? and a.location_id =?
	and a.filing_no != ? and a.filing_no is not null and a.filing_no != ? and length(a.filing_no) = ? and a.back_log = ? and a.filing_new is null $ct_qyery order by a.dt_of_filing";
	$report_data = $db->prepare($query);
	$report_data->bindParam(1, $from_date_post, PDO::PARAM_STR);
	$report_data->bindParam(2, $to_date_post, PDO::PARAM_STR);
	$report_data->bindParam(3, $location_id, PDO::PARAM_STR);
	$report_data->bindParam(4, $na, PDO::PARAM_STR);
	$report_data->bindParam(5, $blank, PDO::PARAM_STR);
	$report_data->bindParam(6, $filing_no_length, PDO::PARAM_STR);
	$report_data->bindParam(7, $e_file_backlog, PDO::PARAM_STR);
	//$report_data->bindParam(8, $wrongly_updated, PDO::PARAM_STR);
	$snn = 8;
	if($search_case_type != ''){
	$report_data->bindParam($snn, $search_case_type, PDO::PARAM_STR);
	$snn++;
	} 
	$report_data->execute();
	$report_data = $report_data->fetchAll(); ?>
                <table class='table table-responsive table-hover table-bordered' id="case_details">
                    <thead>
                        <tr>
                            <th colspan='9'>
                                <center>Total efiled cases
                                    <?php echo ($case_type_name_selected != '')?"($case_type_name_selected)":""; ?> from
                                    <?php echo $from_date; ?> to <?php echo $to_date; ?> :
                                    <?php echo count($report_data); ?></center>
                            </th>
                        </tr>
                        <tr>
                            <th>SN</th>
                            <th>Diary No</th>
                            <th>Date of filing</th>
                            <th>Act</th>
                            <th>Case Type</th>
                            <th>Location</th>
                            <th>Case Title</th>
                            <th>Petitioner</th>
                            <th>Respondent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($report_data)){ 
		foreach($report_data as $k=>$report)
		{
			if($report['filingnumberia'] == '')
				$party_filing_no = $report['filing_no'];
			else
				$party_filing_no = $report['filingnumberia'];
			
			$pet_name = get_party($db,$party_filing_no,'P',1);
			$res_name = get_party($db,$party_filing_no,'R',1);
			$all_pet = all_party($db,$party_filing_no,'P');
			$all_res = all_party($db,$party_filing_no,'R');
			
	?>
                        <tr>
                            <td><?php echo ++$k; ?></td>
                            <td><?php echo $report['filing_no']; ?></td>
                            <td><?php echo date('d/m/Y',strtotime($report['date_of_filing'])); ?></td>
                            <td><?php echo $report['act_name']; ?></td>
                            <td><?php echo $report['case_type_name']; ?></td>
                            <td><?php echo $report['bench_location_name']; ?></td>
                            <td><?php echo $pet_name.' <br/>VS<br/> '.$res_name; ?></td>
                            <td><?php 
				foreach($all_pet as $s=>$pet_name){
					$s++;
					echo "<br/>$pet_name[name]";
				}
			?></td>
                            <td><?php 
				foreach($all_res as $s=>$res_name){
					$s++;
					echo "<br/>$res_name[name]";
				}
			?></td>

                        </tr>
                        <?php
	} }
	echo "</tbody></table>";
 }
 if($search_type == '2'){ ?>
                        <?php
	$backlog = 0;
	$query = "select a.filing_no,a.dt_of_filing as date_of_filing,a.regis_date as registration_date,c.case_type_desc as case_type_name,
	a.case_no,a.case_year,d.bench_location_name,a.status,ecd.act_id,ma.act_name,a.main_case_ia_no,a.transfrred_case_type_short from $schemas.case_detail as a
	left join e_case_detail as ecd on ecd.filing_no = a.filing_no
	left join master_act as ma on ma.act_id = ecd.act_id
	left join case_type as c on c.id = a.case_type
	left join $schemas.bench_location as d on d.city_id = a.location_code
	where date(a.regis_date) between ? and ? and a.location_code =?
	and a.filing_no != ? and a.filing_no is not null and a.filing_no != ? and length(a.filing_no) = ? and a.backlog = ? and a.status != ? $ct_qyery order by a.regis_date";
	//echo $query;
	$report_data = $db->prepare($query);
	$report_data->bindParam(1, $from_date_post, PDO::PARAM_STR);
	$report_data->bindParam(2, $to_date_post, PDO::PARAM_STR);
	$report_data->bindParam(3, $location_id, PDO::PARAM_STR);
	$report_data->bindParam(4, $na, PDO::PARAM_STR);
	$report_data->bindParam(5, $blank, PDO::PARAM_STR);
	$report_data->bindParam(6, $filing_no_length, PDO::PARAM_STR);
	$report_data->bindParam(7, $backlog, PDO::PARAM_STR);
	$report_data->bindParam(8, $wrongly_updated, PDO::PARAM_STR);
	$snn = 9;
	if($search_case_type != ''){
	$report_data->bindParam($snn, $search_case_type, PDO::PARAM_STR);
	$snn++;
	} 
	$report_data->execute();
	$report_data = $report_data->fetchAll(); ?>
                        <table class='table table-responsive table-hover table-bordered' id="case_details">
                            <thead>
                                <tr>
                                    <th colspan='10'>
                                        <center>Total registred cases
                                            <?php echo ($case_type_name_selected != '')?"($case_type_name_selected)":""; ?>
                                            from <?php echo $from_date; ?> to <?php echo $to_date; ?> :
                                            <?php echo count($report_data); ?></center>
                                    </th>
                                </tr>
                                <tr>
                                    <th>SN</th>
                                    <th>Diary No</th>
                                    <th>Date of filing</th>
                                    <th>Date of Registration</th>
                                    <th>Act</th>
                                    <th>Case no</th>
                                    <th>Location</th>
                                    <th>Case Title</th>
                                    <th>Petitioner</th>
                                    <th>Respondent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($report_data)){ 
		foreach($report_data as $k=>$report)
		{
			if($report['main_case_ia_no'] == '')
				$party_filing_no = $report['filing_no'];
			else
				$party_filing_no = $report['main_case_ia_no'];
			
			$pet_name = get_party($db,$party_filing_no,'P',1);
			$res_name = get_party($db,$party_filing_no,'R',1);
			$all_pet = all_party($db,$party_filing_no,'P');
			$all_res = all_party($db,$party_filing_no,'R');
			$transfrred_case_type_short = $report['transfrred_case_type_short'];
			$tr_short = '';
			if(!empty($transfrred_case_type_short)){
				$tr_short = " ($transfrred_case_type_short)";
			}
			
	?>
                                <tr>
                                    <td><?php echo ++$k; ?></td>
                                    <td><?php echo $report['filing_no']; ?></td>
                                    <td><?php echo date('d/m/Y',strtotime($report['date_of_filing'])); ?></td>
                                    <td><?php echo (!empty($report['registration_date'])) ?date('d/m/Y',strtotime($report['registration_date'])):''; ?>
                                    </td>
                                    <td><?php echo $report['act_name']; ?></td>
                                    <td><?php echo (!empty($report['registration_date']))?$report['case_type_name'].$tr_short.'/'.$report['case_no'].'/'.$report['case_year']:''; ?>
                                    </td>
                                    <td><?php echo $report['bench_location_name']; ?></td>
                                    <td><?php echo $pet_name.' <br/>VS<br/> '.$res_name; ?></td>
                                    <td><?php 
				foreach($all_pet as $s=>$pet_name){
					$s++;
					echo "<br/>$pet_name[name]";
				}
			?></td>
                                    <td><?php 
				foreach($all_res as $s=>$res_name){
					$s++;
					echo "<br/>$res_name[name]";
				}
			?></td>

                                </tr>
                                <?php
	} }
	echo "</tbody></table>";
 }
 
 
 if($search_type == '3'){ ?>

                                <?php
	$backlog = 0;
	$disposed = 'D';
	$query = "select dis.filing_no,dis.disposal_date,a.dt_of_filing as date_of_filing,a.regis_date as registration_date,c.case_type_desc as case_type_name,
	a.case_no,a.case_year,d.bench_location_name,a.status,ecd.act_id,ma.act_name,a.main_case_ia_no,a.transfrred_case_type_short from $schemas.case_disposal as dis
	left join $schemas.case_detail as a on a.filing_no = dis.filing_no
	left join e_case_detail as ecd on ecd.filing_no = a.filing_no
	left join master_act as ma on ma.act_id = ecd.act_id
	left join case_type as c on c.id = a.case_type
	left join $schemas.bench_location as d on d.city_id = a.location_code
	where (date(dis.disposal_date) between ? and ?) and a.location_code =? and dis.court_no = ?
	and a.filing_no != ? and a.filing_no is not null and a.filing_no != ? and length(a.filing_no) = ?  and a.status = ? $ct_qyery order by dis.disposal_date";
	//echo $query;
	$report_data = $db->prepare($query);
	$report_data->bindParam(1, $from_date_post, PDO::PARAM_STR);
	$report_data->bindParam(2, $to_date_post, PDO::PARAM_STR);
	$report_data->bindParam(3, $location_id, PDO::PARAM_STR);
	$report_data->bindParam(4, $selected_court_no, PDO::PARAM_STR);
	$report_data->bindParam(5, $na, PDO::PARAM_STR);
	$report_data->bindParam(6, $blank, PDO::PARAM_STR);
	$report_data->bindParam(7, $filing_no_length, PDO::PARAM_STR);
	//$report_data->bindParam(7, $backlog, PDO::PARAM_STR);
	$report_data->bindParam(8, $disposed, PDO::PARAM_STR);
	$snn = 9;
	if($search_case_type != ''){
	$report_data->bindParam($snn, $search_case_type, PDO::PARAM_STR);
	$snn++;
	} 
	$report_data->execute();
	$report_data = $report_data->fetchAll(); ?>
                                <table class='table table-responsive table-hover table-bordered' id="case_details">
                                    <thead>
                                        <tr>
                                            <th colspan='11'>
                                                <center>Total disposed cases
                                                    <?php echo ($case_type_name_selected != '')?"($case_type_name_selected)":""; ?>
                                                    from <?php echo $from_date; ?> to <?php echo $to_date; ?> :
                                                    <?php echo count($report_data); ?></center>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>SN</th>
                                            <th>Diary No</th>
                                            <th>Date of filing</th>
                                            <th>Date of Registration</th>
                                            <th>Dispose date</th>
                                            <th>Act</th>
                                            <th>Case no</th>
                                            <th>Location</th>
                                            <th>Case Title</th>
                                            <th>Petitioner</th>
                                            <th>Respondent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($report_data)){ 
		foreach($report_data as $k=>$report)
		{
			if($report['main_case_ia_no'] == '')
				$party_filing_no = $report['filing_no'];
			else
				$party_filing_no = $report['main_case_ia_no'];
			
			$pet_name = get_party($db,$party_filing_no,'P',1);
			$res_name = get_party($db,$party_filing_no,'R',1);
			$all_pet = all_party($db,$party_filing_no,'P');
			$all_res = all_party($db,$party_filing_no,'R');
			$transfrred_case_type_short = $report['transfrred_case_type_short'];
			$tr_short = '';
			if(!empty($transfrred_case_type_short)){
				$tr_short = " ($transfrred_case_type_short)";
			}
			
	?>
                                        <tr>
                                            <td><?php echo ++$k; ?></td>
                                            <td><?php echo $report['filing_no']; ?></td>
                                            <td><?php echo date('d/m/Y',strtotime($report['date_of_filing'])); ?></td>
                                            <td><?php echo (!empty($report['registration_date'])) ?date('d/m/Y',strtotime($report['registration_date'])):''; ?>
                                            </td>
                                            <td><?php echo date('d/m/Y',strtotime($report['disposal_date'])); ?></td>
                                            <td><?php echo $report['act_name']; ?></td>
                                            <td><?php echo (!empty($report['registration_date']))?$report['case_type_name'].$tr_short.'/'.$report['case_no'].'/'.$report['case_year']:''; ?>
                                            </td>
                                            <td><?php echo $report['bench_location_name']; ?></td>
                                            <td><?php echo $pet_name.' <br/>VS<br/> '.$res_name; ?></td>
                                            <td><?php 
				foreach($all_pet as $s=>$pet_name){
					$s++;
					echo "<br/>$pet_name[name]";
				}
			?></td>
                                            <td><?php 
				foreach($all_res as $s=>$res_name){
					$s++;
					echo "<br/>$res_name[name]";
				}
			?></td>

                                        </tr>
                                        <?php
	} }
	echo "</tbody></table>";
 }

 if($search_type == '4') { ?>

                                        <?php
	$backlog = 0;
	$selected_case_status = 'P';
	$query = "select dt.* from
			  ( select cp.filing_no, cp.court_no, cp.listing_date,a.dt_of_filing as date_of_filing,a.regis_date as registration_date,c.case_type_desc as case_type_name,
	a.case_no,a.case_year,d.bench_location_name,a.status,ecd.act_id,ma.act_name,a.main_case_ia_no,a.transfrred_case_type_short,ct.display_court_text,rank() over (partition by cp.filing_no
			                        order by listing_date desc) as rnk
			    from $schemas.case_proceeding as cp
			    left join $schemas.case_detail as a on a.filing_no = cp.filing_no
				left join e_case_detail as ecd on ecd.filing_no = a.filing_no
				left join master_act as ma on ma.act_id = ecd.act_id
				left join case_type as c on c.id = a.case_type
				left join $schemas.bench_location as d on d.city_id = a.location_code
				left join $schemas.court as ct on ct.court_no = cp.court_no
				where (date(cp.listing_date) between ? and ? ) and a.filing_no != ? and a.filing_no is not null and a.filing_no != ? and length(a.filing_no) = ? and a.status != ? $ct_qyery
						  ) as dt
			where rnk = 1 and dt.court_no = ? and dt.status = ? order by dt.date_of_filing desc";

	//echo $query;
	$report_data = $db->prepare($query);
	$report_data->bindParam(1, $from_date_post, PDO::PARAM_STR);
	$report_data->bindParam(2, $to_date_post, PDO::PARAM_STR);
	$report_data->bindParam(3, $na, PDO::PARAM_STR);
	$report_data->bindParam(4, $blank, PDO::PARAM_STR);
	$report_data->bindParam(5, $filing_no_length, PDO::PARAM_STR);
	$report_data->bindParam(6, $wrongly_updated, PDO::PARAM_STR);
	$snn = 7;
	if($search_case_type != ''){
	$report_data->bindParam($snn, $search_case_type, PDO::PARAM_STR);
	$snn++;
	} 
	$report_data->bindParam($snn, $selected_court_no, PDO::PARAM_STR);
	$snn++;
	$report_data->bindParam($snn, $selected_case_status, PDO::PARAM_STR);
	$report_data->execute();
	$report_data = $report_data->fetchAll(); ?>
                                        <table class='table table-responsive table-hover table-bordered'
                                            id="case_details">
                                            <thead>
                                                <tr>
                                                    <th colspan='13'>
                                                        <center>Total cases
                                                            <?php echo ($case_type_name_selected != '')?"($case_type_name_selected)":""; ?>
                                                            pending and listed between <?php echo $from_date; ?> and
                                                            <?php echo $to_date; ?> : <?php echo count($report_data); ?>
                                                        </center>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Diary No</th>
                                                    <th>Date of filing</th>
                                                    <th>Date of Registration</th>
                                                    <th>Listing date</th>
                                                    <th>Court No</th>
                                                    <th>Act</th>
                                                    <th>Case no</th>
                                                    <th>Location</th>
                                                    <th>Status</th>
                                                    <th>Case Title</th>
                                                    <th>Petitioner</th>
                                                    <th>Respondent</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(!empty($report_data)){ 
		foreach($report_data as $k=>$report)
		{
			if($report['main_case_ia_no'] == '')
				$party_filing_no = $report['filing_no'];
			else
				$party_filing_no = $report['main_case_ia_no'];
			
			$pet_name = get_party($db,$party_filing_no,'P',1);
			$res_name = get_party($db,$party_filing_no,'R',1);
			$all_pet = all_party($db,$party_filing_no,'P');
			$all_res = all_party($db,$party_filing_no,'R');
			$transfrred_case_type_short = $report['transfrred_case_type_short'];
			$tr_short = '';
			if(!empty($transfrred_case_type_short)){
				$tr_short = " ($transfrred_case_type_short)";
			}
			
	?>
                                                <tr>
                                                    <td><?php echo ++$k; ?></td>
                                                    <td><?php echo $report['filing_no']; ?></td>
                                                    <td><?php echo date('d/m/Y',strtotime($report['date_of_filing'])); ?>
                                                    </td>
                                                    <td><?php echo (!empty($report['registration_date'])) ?date('d/m/Y',strtotime($report['registration_date'])):''; ?>
                                                    </td>
                                                    <td><?php echo date('d/m/Y',strtotime($report['listing_date'])); ?>
                                                    </td>
                                                    <td><?php echo $report['display_court_text']; ?></td>
                                                    <td><?php echo $report['act_name']; ?></td>
                                                    <td><?php echo (!empty($report['registration_date']))?$report['case_type_name'].$tr_short.'/'.$report['case_no'].'/'.$report['case_year']:''; ?>
                                                    </td>
                                                    <td><?php echo $report['bench_location_name']; ?></td>
                                                    <td><?php echo ($report['status'] == 'D'?'Dispose':'Pending'); ?>
                                                    </td>
                                                    <td><?php echo $pet_name.' <br/>VS<br/> '.$res_name; ?></td>
                                                    <td><?php 
				foreach($all_pet as $s=>$pet_name){
					$s++;
					echo "<br/>$pet_name[name]";
				}
			?></td>
                                                    <td><?php 
				foreach($all_res as $s=>$res_name){
					$s++;
					echo "<br/>$res_name[name]";
				}
			?></td>

                                                </tr>
                                                <?php
	} }
	echo "</tbody></table>";
}
 
if($search_type == '5'){ ?>

                                                <?php
	$backlog = 0;
	$query = "select od.order_date,od.filing_no,a.dt_of_filing as date_of_filing,a.regis_date as registration_date,c.case_type_desc as case_type_name,
	a.case_no,a.case_year,d.bench_location_name,a.status,ecd.act_id,ma.act_name,a.main_case_ia_no,a.transfrred_case_type_short 
	from $schemas.order_daily as od
	left join $schemas.case_detail as a on a.filing_no = od.filing_no
	left join e_case_detail as ecd on ecd.filing_no = a.filing_no
	left join master_act as ma on ma.act_id = ecd.act_id
	left join case_type as c on c.id = a.case_type
	left join $schemas.bench_location as d on d.city_id = a.location_code
	where od.order_type = ? and (date(od.order_date) between ? and ?) and a.location_code =?
	and a.filing_no != ? and a.filing_no is not null and a.filing_no != ? and length(a.filing_no) = ?  and a.status != ? $ct_qyery order by od.order_date";
	//echo $query;
	$report_data = $db->prepare($query);
	$report_data->bindParam(1, $selected_order_type, PDO::PARAM_STR);
	$report_data->bindParam(2, $from_date_post, PDO::PARAM_STR);
	$report_data->bindParam(3, $to_date_post, PDO::PARAM_STR);
	$report_data->bindParam(4, $location_id, PDO::PARAM_STR);
	$report_data->bindParam(5, $na, PDO::PARAM_STR);
	$report_data->bindParam(6, $blank, PDO::PARAM_STR);
	$report_data->bindParam(7, $filing_no_length, PDO::PARAM_STR);
	//$report_data->bindParam(8, $backlog, PDO::PARAM_STR);
	$report_data->bindParam(8, $wrongly_updated, PDO::PARAM_STR);
	$snn = 9;
	if($search_case_type != ''){
	$report_data->bindParam($snn, $search_case_type, PDO::PARAM_STR);
	$snn++;
	} 
	$report_data->execute();
	$report_data = $report_data->fetchAll(); 
	if($selected_order_type == 'D' or $selected_order_type == 'DC') {
		$order_column = "Date of Order";
		$heading = "Orders";
	} else {
		$order_column = "Date of Judgement";
		$heading = "Judgements";
	}
	?>
                                                <table class='table table-responsive table-hover table-bordered'
                                                    id="case_details">
                                                    <thead>
                                                        <tr>
                                                            <th colspan='11'>
                                                                <center>Total <?php echo $heading; ?>
                                                                    <?php echo ($case_type_name_selected != '')?"($case_type_name_selected)":""; ?>
                                                                    from <?php echo $from_date; ?> to
                                                                    <?php echo $to_date; ?> :
                                                                    <?php echo count($report_data); ?></center>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>SN</th>
                                                            <th>Diary No</th>
                                                            <th>Date of filing</th>
                                                            <th>Date of Registration</th>
                                                            <th><?php echo $order_column; ?></th>
                                                            <th>Act</th>
                                                            <th>Case no</th>
                                                            <th>Location</th>
                                                            <th>Case Title</th>
                                                            <th>Petitioner</th>
                                                            <th>Respondent</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(!empty($report_data)){ 
		foreach($report_data as $k=>$report)
		{
			if($report['main_case_ia_no'] == '')
				$party_filing_no = $report['filing_no'];
			else
				$party_filing_no = $report['main_case_ia_no'];
			
			$pet_name = get_party($db,$party_filing_no,'P',1);
			$res_name = get_party($db,$party_filing_no,'R',1);
			$all_pet = all_party($db,$party_filing_no,'P');
			$all_res = all_party($db,$party_filing_no,'R');
			$transfrred_case_type_short = $report['transfrred_case_type_short'];
			$tr_short = '';
			if(!empty($transfrred_case_type_short)){
				$tr_short = " ($transfrred_case_type_short)";
			}
			
	?>
                                                        <tr>
                                                            <td><?php echo ++$k; ?></td>
                                                            <td><?php echo $report['filing_no']; ?></td>
                                                            <td><?php echo date('d/m/Y',strtotime($report['date_of_filing'])); ?>
                                                            </td>
                                                            <td><?php echo (!empty($report['registration_date'])) ?date('d/m/Y',strtotime($report['registration_date'])):''; ?>
                                                            </td>
                                                            <td><?php echo date('d/m/Y',strtotime($report['order_date'])); ?>
                                                            </td>
                                                            <td><?php echo $report['act_name']; ?></td>
                                                            <td><?php echo (!empty($report['registration_date']))?$report['case_type_name'].$tr_short.'/'.$report['case_no'].'/'.$report['case_year']:''; ?>
                                                            </td>
                                                            <td><?php echo $report['bench_location_name']; ?></td>
                                                            <td><?php echo $pet_name.' <br/>VS<br/> '.$res_name; ?></td>
                                                            <td><?php 
				foreach($all_pet as $s=>$pet_name){
					$s++;
					echo "<br/>$pet_name[name]";
				}
			?></td>
                                                            <td><?php 
				foreach($all_res as $s=>$res_name){
					$s++;
					echo "<br/>$res_name[name]";
				}
			?></td>

                                                        </tr>
                                                        <?php
	} }
	echo "</tbody></table>";
 }
 
 
 if($search_type == '6'){ ?>

                                                        <?php
	$backlog = 0;
	$query = "select od.order_date,od.filing_no,a.dt_of_filing as date_of_filing,a.regis_date as registration_date,c.case_type_desc as case_type_name,
	a.case_no,a.case_year,d.bench_location_name,a.status,ecd.act_id,ma.act_name,a.main_case_ia_no,a.transfrred_case_type_short 
	from $schemas.order_daily as od
	left join $schemas.case_detail as a on a.filing_no = od.filing_no
	left join e_case_detail as ecd on ecd.filing_no = a.filing_no
	left join master_act as ma on ma.act_id = ecd.act_id
	left join case_type as c on c.id = a.case_type
	left join $schemas.bench_location as d on d.city_id = a.location_code
	where od.author_by = ? and (date(od.order_date) between ? and ?) and a.location_code =?
	and a.filing_no != ? and a.filing_no is not null and a.filing_no != ? and length(a.filing_no) = ?  and a.status != ? $ct_qyery order by od.order_date";
	//echo $query;
	$report_data = $db->prepare($query);
	$report_data->bindParam(1, $selected_judge, PDO::PARAM_STR);
	$report_data->bindParam(2, $from_date_post, PDO::PARAM_STR);
	$report_data->bindParam(3, $to_date_post, PDO::PARAM_STR);
	$report_data->bindParam(4, $location_id, PDO::PARAM_STR);
	$report_data->bindParam(5, $na, PDO::PARAM_STR);
	$report_data->bindParam(6, $blank, PDO::PARAM_STR);
	$report_data->bindParam(7, $filing_no_length, PDO::PARAM_STR);
	//$report_data->bindParam(8, $backlog, PDO::PARAM_STR);
	$report_data->bindParam(8, $wrongly_updated, PDO::PARAM_STR);
	$snn = 9;
	if($search_case_type != ''){
	$report_data->bindParam($snn, $search_case_type, PDO::PARAM_STR);
	$snn++;
	} 
	$report_data->execute();
	$report_data = $report_data->fetchAll(); ?>
                                                        <table class='table table-responsive table-hover table-bordered'
                                                            id="case_details">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan='11'>
                                                                        <center>Total Judge wise cases
                                                                            <?php echo ($case_type_name_selected != '')?"($case_type_name_selected)":""; ?>
                                                                            from <?php echo $from_date; ?> to
                                                                            <?php echo $to_date; ?> :
                                                                            <?php echo count($report_data); ?></center>
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th>SN</th>
                                                                    <th>Diary No</th>
                                                                    <th>Date of filing</th>
                                                                    <th>Date of Registration</th>
                                                                    <th>Date of Order</th>
                                                                    <th>Act</th>
                                                                    <th>Case no</th>
                                                                    <th>Location</th>
                                                                    <th>Case Title</th>
                                                                    <th>Petitioner</th>
                                                                    <th>Respondent</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if(!empty($report_data)){ 
		foreach($report_data as $k=>$report)
		{
			if($report['main_case_ia_no'] == '')
				$party_filing_no = $report['filing_no'];
			else
				$party_filing_no = $report['main_case_ia_no'];
			
			$pet_name = get_party($db,$party_filing_no,'P',1);
			$res_name = get_party($db,$party_filing_no,'R',1);
			$all_pet = all_party($db,$party_filing_no,'P');
			$all_res = all_party($db,$party_filing_no,'R');
			$transfrred_case_type_short = $report['transfrred_case_type_short'];
			$tr_short = '';
			if(!empty($transfrred_case_type_short)){
				$tr_short = " ($transfrred_case_type_short)";
			}
			
	?>
                                                                <tr>
                                                                    <td><?php echo ++$k; ?></td>
                                                                    <td><?php echo $report['filing_no']; ?></td>
                                                                    <td><?php echo date('d/m/Y',strtotime($report['date_of_filing'])); ?>
                                                                    </td>
                                                                    <td><?php echo (!empty($report['registration_date'])) ?date('d/m/Y',strtotime($report['registration_date'])):''; ?>
                                                                    </td>
                                                                    <td><?php echo (!empty($report['order_date'])) ?date('d/m/Y',strtotime($report['order_date'])):''; ?>
                                                                    </td>
                                                                    <td><?php echo $report['act_name']; ?></td>
                                                                    <td><?php echo (!empty($report['registration_date']))?$report['case_type_name'].$tr_short.'/'.$report['case_no'].'/'.$report['case_year']:''; ?>
                                                                    </td>
                                                                    <td><?php echo $report['bench_location_name']; ?>
                                                                    </td>
                                                                    <td><?php echo $pet_name.' <br/>VS<br/> '.$res_name; ?>
                                                                    </td>
                                                                    <td><?php 
				foreach($all_pet as $s=>$pet_name){
					$s++;
					echo "<br/>$pet_name[name]";
				}
			?></td>
                                                                    <td><?php 
				foreach($all_res as $s=>$res_name){
					$s++;
					echo "<br/>$res_name[name]";
				}
			?></td>

                                                                </tr>
                                                                <?php
	} }
	echo "</tbody></table>";
 }
 

} ?>
            </div>
    </div>

    </section>


    <?php include '../infooter.php'; ?>
    <script src="../datatable/js/jquery.dataTables.min.js"></script>
    <script src="../datatable/js/dataTables.buttons.min.js"></script>
    <script src="../datatable/js/buttons.flash.min.js"></script>
    <script src="../datatable/js/jszip.min.js"></script>
    <script src="../datatable/js/pdfmake.min.js"></script>
    <script src="../datatable/js/vfs_fonts.js"></script>
    <script src="../datatable/js/buttons.html5.min.js"></script>
    <script src="../datatable/js/buttons.print.min.js"></script>
    <!-- Theme JS files -->
    <script src="../datatable/js/datatables_extension_buttons_html5.js"></script>
    <script>
    $(document).ready(function() {

        $('#case_details').DataTable({
            "pageLength": -1,
            "lengthMenu": [
                [100, 200, 500, -1],
                [100, 200, 500, "All"]
            ],
            buttons: [{
                    extend: 'excel',
                    title: 'excel_report'
                },
                {
                    extend: 'csv',
                    title: 'csv_report'
                }
            ]
        });
    });
    </script>

    <?php } ?>
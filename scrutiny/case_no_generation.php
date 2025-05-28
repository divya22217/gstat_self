<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
$server_date = date('Y-m-d');
include "../db_inc1.php";
include "../db_inc2.php";
include_once '../custom/custom_function.php';
$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];
$sessionUserType = htmlspecialchars($_SESSION['id']);
$location_access = $_SESSION['location'];
$schema_id = $_SESSION['schema_idccc'];
$user_court = $_SESSION['user_court'];
//print_r($_SESSEION);

function display_filing_no($filing_no_display)
{
    $lastFour = substr($filing_no_display, -4);
    $lastFive = substr($filing_no_display, -9, -4);
    $left = substr($filing_no_display, -16, -9);
    return $dis_fil_no = $left . '/<b>' . $lastFive . '/' . $lastFour . '</b>';

}

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    // header("Location: ./login.php");
    die();
}
if($userid != '81' and  $userid != '194') {
    echo "Access Problem.....";
    header("Location: ../index.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", true, true);
$_SESSION['csrf'] = md5(uniqid(rand(), true));
$key = $_SESSION['csrf'];
// At the top of the page we check to see whether the user is logged in or not
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
    die("#2E2E2Eirecting to login.php");
}
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    $sessionUserType = htmlspecialchars($_SESSION['id']);
    $curYear = htmlspecialchars(date("Y"));
    $curMonth = htmlspecialchars(date("m"));
    $curDay = htmlspecialchars(date("d"));
    $cur_date = "$curYear-$curMonth-$curDay";
    $cur_date1 = "$curDay/$curMonth/$curYear";
    $link_scrutiny_idaccess = '1';
    include '../inheader.php';
    //include '../insidebar.php';
	
	function get_court($db,$schemas){
	$courts = $db->prepare("select * from $schemas.court order by court_no");
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

function get_display_court_text($db,$schemas,$court_no){
	$select = $db->prepare("select causelist_court_text from $schemas.court where court_no = ?");
	$select->bindParam(1, $court_no, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
}
    ?>
<link rel="stylesheet" type="text/css" href="../datatable/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../datatable/css/buttons.dataTables.min.css">

<style>
.load_container {
    background: rgba(0, 0, 0, 0.50);
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    z-index: 99;
    left: 0;
}

.load_container .loader {
    display: block;
    width: 60px;
    height: 60px;
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    margin: auto;
}
</style>
<!--<div class="load_container">
    <img class="loader" src="../loading-indicator.gif">
</div>-->
<div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
            <?php 
	  $from_date = (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']))? date('Y-m-d',strtotime($_REQUEST['from_date'])) :'';
	  $to_date = (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))?date('Y-m-d',strtotime($_REQUEST['to_date']))  :'';
	  $from_date_display = (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']))?$_REQUEST['from_date'] :'';
	  $to_date_display = (isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))?$_REQUEST['to_date'] :'';
?>

<div class="box-body">
	<!--<table>
		<tr>
            <td colspan="16" align="left">
            
                    <font face="Verdana" size="2"> </font>
                	<font face="Verdana" size="2">From filing date:
                    <input type='date' id="from_date" name="from_date" value="<?php echo $from_date_display; ?>">
					

                    <font face="Verdana" size="2">To filing date:</font>
                    <input type='date' id="to_date" name="to_date" value="<?php echo $to_date_display; ?>">


                    <input type="button" onclick="submitForm3()" value="Search" >
					<input type="button" onclick="reset_case()" value="Reset" >
            </td>
        </tr>
    </table>-->

    <div class="table-responsive">
                <table id='example' class="table no-margin" >
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Date Of Filing</th>
                            <th>Case Type</th>
                            <th>Diary No.</th>
							<th>Main Case Diary No.</th>
                            <th>Title Of Case</th>
                            <th>Subject</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
    $case_data = get_cases_for_no_generation($db, $user_court,$schemas,$from_date,$to_date, $type='main');
	$main_cases = main_case_type();
    if (!empty($case_data) && is_array($case_data)) {
		$sn = 1;
        foreach ($case_data as $key=>$row) {
            $filing_no = htmlspecialchars($row['filing_no']);
			$main_filing_no = htmlspecialchars($row['filingnumberia']);
            $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
            $case_type = htmlspecialchars($row['case_type_nclat']);
			if (in_array($case_type, $main_cases)){
			$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row['filing_no']);
			}else{
				$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row['filingnumberia']);
				if(empty($show_party_filing_no))
					$show_party_filing_no = htmlspecialchars($row['filing_no']);
			}
			$pet_name = get_party($db,$show_party_filing_no,$party_flag='P',$party_serial_no=1);
			$res_name = get_party($db,$show_party_filing_no,$party_flag='R',$party_serial_no=1);
			$case_title = $pet_name." <b>VS</b> ".$res_name;
            $case_type_display = htmlspecialchars($row['case_type_desc_cis']);
            $filing_date_all = fn_date_formate($dt_of_filing);
			$patially_defective = htmlspecialchars($row['patially_defective']);
			$subject_id = $row['subjectia'];
			if($patially_defective == '1'){
				$back_color = '#f1df61';
			}else{
				$back_color = '#BDFCC9';
			}
			$tr_short = '';
			if($case_type == '40'){
				$old_case_info = get_old_case_info($db,$filing_no);
				if(!empty($old_case_info)){
					if(!empty($old_case_info)){
						$transfer_case_type = $old_case_info['transfer_case_type'];
						if($transfer_case_type == '32'){
							$tr_short = ' (Company)';
						}else if($transfer_case_type == '33'){
							$tr_short = ' (Ins.)';
						}else if($transfer_case_type == '34'){
							$tr_short = ' (Compt.)';
						}else{
							$tr_short = '';
						}
					}
				}
			}
                ?>
                        <tr style="background-color: <?php echo $back_color; ?>;">
                            <td><?php echo $sn; ?></td>
                            <td><?php echo fn_date_formate($dt_of_filing); ?> </td>
                            <td><?php echo $case_type_display.$tr_short; ?></td>
                            <td><?php echo display_filing_no($filing_no); ?></td>
							<td><?php echo (!empty($main_filing_no) && $main_filing_no != 'NA')?display_filing_no($main_filing_no):'NA';?>
                            <td><?php echo $case_title; ?> </td>
                            <td>
                                <div class="sparkbar" data-color="#00a65a" data-height="20">
                                    <?php echo get_subject($db,$subject_id); ?></div>
                            </td>
                            <td>
						
                            <button type='button' onClick="return revert_back_to_scrutiny('<?php echo $filing_no; ?>',2);" class='btn btn-sm btn-warning' name='revert_btn' id='revert_btn'>Revert case back to scrutiny</button>

							
							</td>
                        </tr>
						
						<?php
					$sn++;
				?>		
                        <?php
            }
        }
?>
                    </tbody>

                </table>
</div>
</div>

            </div>
        </div>
    </section>
</div>
<?php include '../infooter.php';?>

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




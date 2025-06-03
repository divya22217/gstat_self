
<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/exportPDF/tableexport.js"></script>
<script src="../assets/exportPDF/FileSaver.js"></script>
<script src="../assets/exportPDF/jspdf.min.js"></script>
<script src="../assets/exportPDF/libs/jspdf.plugin.autotable.js"></script>
<script src="../assets/exportPDF/tableexport.js"></script>
<?php 

session_start();
ob_start();
include("../db_inc1.php");
include("../master/functions.php");
date_default_timezone_set("Asia/Kolkata");
$current_date= date('d.m.Y');
$main_location_code = $_SESSION['location'];
if($main_location_code == 10){
	$other_schema = 'chennai';
	$from_location = 17;
	$to_location = 16;
	$to_actual_location = 5;
	$column1 = "Transfer from Chennai to Delhi Bench";
	$column2 = "Transfer to Chennai Bench";
}
if($main_location_code == 5){
	$other_schema = 'delhi';
	$from_location = 16;
	$to_location = 17;
	$to_actual_location = 10;
	$column1 = "Transfer from Delhi to Chennai Bench";
	$column2 = "Transfer to Delhi Bench";
}
$schema=htmlspecialchars($_SESSION['schema_name']);
 /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */


if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{ 
	$main_city_name = return_zone_name_for_causelist($main_location_code);
	
	function get_cases_count($db,$schema,$case_type,$from_location,$to_location,$to_actual_location){
		$p_st = 'P';
		$d_st = 'D';
		$w_st = 'W';
		$case_tp = 40;
		$one = 1;
		$query = "select a.case_year, count(a.*) as filed_cases,
					sum(case when a.status  = ? then 1 else 0 end) as pending, 
					sum(case when a.status  = ? then 1 else 0 end) as dispose
					from $schema.case_detail as a 
					where a.filing_no != 'NA' and a.filing_no != ''
					and a.case_year != ''  and a.status != ?  and a.case_type = ?
					group by a.case_year
					order by a.case_year";
		$short_name = $db->prepare($query);
		$short_name->bindParam(1, $p_st, PDO::PARAM_STR);
		$short_name->bindParam(2, $d_st, PDO::PARAM_STR);
		$short_name->bindParam(3, $w_st, PDO::PARAM_STR);
		$short_name->bindParam(4, $case_type, PDO::PARAM_INT);
		$short_name->execute();
		$data = $short_name->fetchAll();
		return $data;
	}
	
	function transfer_case_count($db,$schema,$from_location,$case_year,$tr_case_type){
		$query = "select count(*) as count from $schema.case_detail where transfrred_case_location = ? and case_year = ? and transfrred_case_type = ?";
		$short_name = $db->prepare($query);
		$short_name->bindParam(1, $from_location, PDO::PARAM_INT);
		$short_name->bindParam(2, $case_year, PDO::PARAM_INT);
		$short_name->bindParam(3, $tr_case_type, PDO::PARAM_INT);
		$short_name->execute();
		$data = $short_name->fetchColumn();
		return $data;
	}
	
	
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cases Report</title>
</head>
<style>
    body {
        padding: 0 50px;
    }
    p {
        text-align: center;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    table tr td, table tr th {
        border: 1px solid #000;
        padding: 3px 8px;
        text-align: center;
    }
    table tr th {
        font-weight: bold;
    }
    .big {
        font-size: 18px;
        font-weight: bold;
        font-style: italic;
    }
    .large {
        font-size: 20px;
        font-weight: bold;
        font-style: italic;
    }
</style>
<body>
<hr>
<div id="testdiv" style="visibility: visible;">
	<center>
	<a href="javascript:printDiv();"><font size="4"><button class="primary">Print</button></font></a>
	<a href="javascript:exportEXL();"><font size="4" ><button class="primary">Export Excel</button></font></a>
	<a href="javascript:exportCSV();"><font size="4" ><button class="primary">Export CSV</button></font></a>
	</center>
</div>

<hr>

    <table id="cases_report">
        <tbody>
		<tr>
			<td colspan="8">
				<p><b><u>Detail of total number of cases (YEAR WISE) Recevied/ Decided/ Pending in the NCLAT (as on <?php echo $current_date; ?>)&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $main_city_name; ?>     </u></b></p>
			</td>
		</tr>
            <tr>
                <td><b>S.No.</b></td>
                <td><b>Category of cases</b></td>
                <td><b>Year</b></td>
                <td><b>No. of Cases filed</b></td>
                <td><b><?php echo $column1; ?></b></td>
                <td><b><?php echo $column2; ?></b></td>
                <td><b>No. of Cases decided</b></td>
                <td><b>No. of Cases pending</b></td>
            </tr>
			<?php
				$ins = get_cases_count($db,$schema,$case_type=33,$from_location,$to_location,$to_actual_location);
				$ins_count = count($ins);
				if($ins_count != '0'){
				$first_case_year = $ins[0]['case_year'];
				$total_ins_transfer_in = transfer_case_count($db,$schema,$from_location,$first_case_year,$case_type=33);
				$total_ins_transfer_out = transfer_case_count($db,$other_schema,$to_location,$first_case_year,$case_type=33);
				$total_ins_filed = $total_ins_pending = $total_ins_disposed = 0;
				$total_ins_filed = $total_ins_filed+$ins[0]['filed_cases'];
				$total_ins_pending = $total_ins_pending+$ins[0]['pending'];
				$total_ins_disposed = $total_ins_disposed+$ins[0]['dispose'];
				}
			?>
            <tr>
                <td rowspan="<?php echo ($ins_count+1); ?>"><b>1.</b></td>
                <td rowspan="<?php echo ($ins_count); ?>"><b><i>Under I & B Code, 2016</i></b></td>
                <td><b><?php echo $ins[0]['case_year']; ?></b></td>
                <td><?php echo $ins[0]['filed_cases']; ?></td>
                <td><?php echo $total_ins_transfer_in; ?></td>
                <td><?php echo $total_ins_transfer_out; ?></td>
                <td><?php echo $ins[0]['dispose']; ?></td>
                <td><?php echo $ins[0]['pending']; ?></td>
            </tr>
			<?php
				$remove = array_shift($ins);
				if(!empty($ins)){
					foreach($ins as $k=>$value){ 
					$c_year = $value['case_year'];
						$total_ins_filed = $total_ins_filed+$value['filed_cases'];
						$total_ins_pending = $total_ins_pending+$value['pending'];
						$total_ins_disposed = $total_ins_disposed+$value['dispose'];
						$total_ins_transfer_i = transfer_case_count($db,$schema,$from_location,$c_year,$case_type=33);
						$total_ins_transfer_o = transfer_case_count($db,$other_schema,$to_location,$c_year,$case_type=33);
						$total_ins_transfer_in = $total_ins_transfer_in+$total_ins_transfer_i;
						$total_ins_transfer_out = $total_ins_transfer_out+$total_ins_transfer_o;
					?>
						<tr>
							<td><b><?php echo $value['case_year']; ?></b></td>
							<td><?php echo $value['filed_cases']; ?></td>
							<td><?php echo $total_ins_transfer_i; ?></td>
							<td><?php echo $total_ins_transfer_o; ?></td>
							<td><?php echo $value['dispose']; ?></td>
							<td><?php echo $value['pending']; ?></td>
						</tr>
				<?php	}
				}
			?>
            <tr>
                <td><b><i>Total</i></b></td>
                <td></td>
                <td class="big"><?php echo $total_ins_filed; ?></td>
                <td class="big"><?php echo $total_ins_transfer_in; ?></td>
                <td class="big"><?php echo $total_ins_transfer_out; ?></td>
                <td class="big"><?php echo $total_ins_disposed; ?></td>
                <td class="big"><?php echo $total_ins_pending; ?></td>
                
            </tr>
			<?php
				$appeal = get_cases_count($db,$schema,$case_type=32,$from_location,$to_location,$to_actual_location);
				$appeal_count = count($appeal);
				if($appeal_count != '0'){
				$first_case_year = $appeal[0]['case_year'];
				$total_appeal_transfer_in = transfer_case_count($db,$schema,$from_location,$first_case_year,$case_type=32);
				$total_appeal_transfer_out = transfer_case_count($db,$other_schema,$to_location,$first_case_year,$case_type=32);
				$total_appeal_filed = $total_appeal_pending = $total_appeal_disposed = 0;
				$total_appeal_filed = $total_appeal_filed+$appeal[0]['filed_cases'];
				$total_appeal_pending = $total_appeal_pending+$appeal[0]['pending'];
				$total_appeal_disposed = $total_appeal_disposed+$appeal[0]['dispose'];
				}
			?>
            <tr>
                <td rowspan="<?php echo ($appeal_count+1); ?>"><b>2.</b></td>
                <td rowspan="<?php echo ($appeal_count); ?>"><b><i>Under the Companies Act, 2013</i></b></td>
                <td><b><?php echo $appeal[0]['case_year']; ?></b></td>
                <td><?php echo $appeal[0]['filed_cases']; ?></td>
                <td><?php echo $total_appeal_transfer_in; ?></td>
                <td><?php echo $total_appeal_transfer_out; ?></td>
                <td><?php echo $appeal[0]['dispose']; ?></td>
                <td><?php echo $appeal[0]['pending']; ?></td>
            </tr>
            <?php
				$remove = array_shift($appeal);
				if(!empty($appeal)){
					foreach($appeal as $k=>$value){ 
						$c_year = $value['case_year'];
						$total_appeal_filed = $total_appeal_filed+$value['filed_cases'];
						$total_appeal_pending = $total_appeal_pending+$value['pending'];
						$total_appeal_disposed = $total_appeal_disposed+$value['dispose'];
						$total_appeal_transfer_i = transfer_case_count($db,$schema,$from_location,$c_year,$case_type=32);
						$total_appeal_transfer_o = transfer_case_count($db,$other_schema,$to_location,$c_year,$case_type=32);
						$total_appeal_transfer_in = $total_appeal_transfer_in+$total_appeal_transfer_i;
						$total_appeal_transfer_out = $total_appeal_transfer_out+$total_appeal_transfer_o;
					?>
						<tr>
							<td><b><?php echo $value['case_year']; ?></b></td>
							<td><?php echo $value['filed_cases']; ?></td>
							<td><?php echo $total_appeal_transfer_i; ?></td>
							<td><?php echo $total_appeal_transfer_o; ?></td>
							<td><?php echo $value['dispose'] ?></td>
							<td><?php echo $value['pending'] ?></td>
						</tr>
				<?php	}
				}
			?>
            <tr>
                <td><b><i>Total</i></b></td>
                <td></td>
                <td class="big"><?php echo $total_appeal_filed; ?></td>
                <td class="big"><?php echo $total_appeal_transfer_in; ?></td>
                <td class="big"><?php echo $total_appeal_transfer_out; ?></td>
                <td class="big"><?php echo $total_appeal_disposed; ?></td>
                <td class="big"><?php echo $total_appeal_pending; ?></td>
                
            </tr>
            <?php
				$compt_appeal = get_cases_count($db,$schema,$case_type=34,$from_location,$to_location,$to_actual_location);

				$compt_appeal_count = count($compt_appeal);
				if($compt_appeal_count != '0') {
				$first_case_year = $compt_appeal[0]['case_year'];
				$total_compt_appeal_transfer_in = transfer_case_count($db,$schema,$from_location,$first_case_year,$case_type=34);
				$total_compt_appeal_transfer_out = transfer_case_count($db,$other_schema,$to_location,$first_case_year,$case_type=34);
				$total_compt_appeal_filed = $total_compt_appeal_pending = $total_compt_appeal_disposed = 0;
				$total_compt_appeal_filed = $total_compt_appeal_filed+$compt_appeal[0]['filed_cases'];
				$total_compt_appeal_pending = $total_compt_appeal_pending+$compt_appeal[0]['pending'];
				$total_compt_appeal_disposed = $total_compt_appeal_disposed+$compt_appeal[0]['dispose'];
				}else{
					$compt_appeal_count = 1;
				}
				
			?>
            <tr>
                <td rowspan="<?php echo ($compt_appeal_count+1) ?>"><b>3.</b></td>
                <td rowspan="<?php echo ($compt_appeal_count) ?>"><b><i>Under the Competition Act, 2002</i></b></td>
                <td><b><?php echo $compt_appeal[0]['case_year']; ?></b></td>
                <td><?php echo $compt_appeal[0]['filed_cases']; ?></td>
                <td><?php echo $total_compt_appeal_transfer_in; ?></td>
                <td><?php echo $total_compt_appeal_transfer_out; ?></td>
                <td><?php echo $compt_appeal[0]['dispose']; ?></td>
                <td><?php echo $compt_appeal[0]['pending']; ?></td>
            </tr>
            <?php
				$remove = array_shift($compt_appeal);
				if(!empty($compt_appeal)){
					foreach($compt_appeal as $k=>$value){ 
						$c_year = $value['case_year'];
						$total_compt_appeal_filed = $total_compt_appeal_filed+$value['filed_cases'];
						$total_compt_appeal_pending = $total_compt_appeal_pending+$value['pending'];
						$total_compt_appeal_disposed = $total_compt_appeal_disposed+$value['dispose'];
						$total_compt_appeal_transfer_i = transfer_case_count($db,$schema,$from_location,$c_year,$case_type=34);
						$total_compt_appeal_transfer_o = transfer_case_count($db,$other_schema,$to_location,$c_year,$case_type=34);
						$total_compt_appeal_transfer_in = $total_compt_appeal_transfer_in+$total_compt_appeal_transfer_i;
						$total_compt_appeal_transfer_out = $total_compt_appeal_transfer_out+$total_compt_appeal_transfer_o;
					?>
						<tr>
							<td><b><?php echo $value['case_year']; ?></b></td>
							<td><?php echo $value['filed_cases']; ?></td>
							<td><?php echo $total_compt_appeal_transfer_i; ?></td>
							<td><?php echo $total_compt_appeal_transfer_o; ?></td>
							<td><?php echo $value['dispose']; ?></td>
							<td><?php echo $value['pending']; ?></td>
						</tr>
				<?php	}
				}
			?>
            <tr>
                <td><b><i>Total</i></b></td>
                <td></td>
                <td class="big"><?php echo $total_compt_appeal_filed; ?></td>
                <td class="big"><?php echo $total_compt_appeal_transfer_in; ?></td>
                <td class="big"><?php echo $total_compt_appeal_transfer_out; ?></td>
                <td class="big"><?php echo $total_compt_appeal_disposed; ?></td>
                <td class="big"><?php echo $total_compt_appeal_pending; ?></td>
                
            </tr>
            <?php
				$compensation = get_cases_count($db,$schema,$case_type=36,$from_location,$to_location,$to_actual_location);
				$compensation_count = count($compensation);
				if($compensation_count != '0') {
				$first_case_year = $compensation[0]['case_year'];
				$total_compensation_transfer_in = transfer_case_count($db,$schema,$from_location,$first_case_year,$case_type=36);
				$total_compensation_transfer_out = transfer_case_count($db,$other_schema,$to_location,$first_case_year,$case_type=36);
				$total_compensation_filed = $total_compensation_pending = $total_compensation_disposed = 0;
				$total_compensation_filed = $total_compensation_filed+$compensation[0]['filed_cases'];
				$total_compensation_pending = $total_compensation_pending+$compensation[0]['pending'];
				$total_compensation_disposed = $total_compensation_disposed+$compensation[0]['dispose'];
				}else{
					$compensation_count = 1;
				}
			?>
            <tr>
                <td rowspan="<?php echo ($compensation_count+1) ?>"><b>4.</b></td>
                <td rowspan="<?php echo ($compensation_count) ?>"><b><i>Compensation cases under Competition Act, 2002</i></b></td>
                <td><b><?php echo $compensation[0]['case_year']; ?></b></td>
                <td><?php echo $compensation[0]['filed_cases']; ?></td>
                <td><?php echo $total_compensation_transfer_in; ?></td>
                <td><?php echo $total_compensation_transfer_out; ?></td>
                <td><?php echo $compensation[0]['dispose']; ?></td>
                <td><?php echo $compensation[0]['pending']; ?></td>
            </tr>
            <?php
				$remove = array_shift($compensation);
				if(!empty($compensation)){
					foreach($compensation as $k=>$value){
						$c_year = $value['case_year'];
						$total_compensation_filed = $total_compensation_filed+$value['filed_cases'];
						$total_compensation_pending = $total_compensation_pending+$value['pending'];
						$total_compensation_disposed = $total_compensation_disposed+$value['dispose'];
						$total_compensation_transfer_i = transfer_case_count($db,$schema,$from_location,$c_year,$case_type=36);
						$total_compensation_transfer_o = transfer_case_count($db,$other_schema,$to_location,$c_year,$case_type=36);
						$total_compensation_transfer_in = $total_compensation_transfer_in+$total_compensation_transfer_i;
						$total_compensation_transfer_out = $total_compensation_transfer_out+$total_compensation_transfer_o;
					?>
						<tr>
							<td><b><?php echo $value['case_year']; ?></b></td>
							<td><?php echo $value['filed_cases']; ?></td>
							<td><?php echo $total_compensation_transfer_i; ?></td>
							<td><?php echo $total_compensation_transfer_o; ?></td>
							<td><?php echo $value['dispose']; ?></td>
							<td><?php echo $value['pending']; ?></td>
						</tr>
				<?php	}
				}
			?>
             <tr>
                <td><b><i>Total</i></b></td>
                <td></td>
                <td class="big"><?php echo $total_compensation_filed; ?></td>
                <td class="big"><?php echo $total_compensation_transfer_in; ?></td>
                <td class="big"><?php echo $total_compensation_transfer_out; ?></td>
                <td class="big"><?php echo $total_compensation_disposed; ?></td>
                <td class="big"><?php echo $total_compensation_pending; ?></td>
                
            </tr>
			<?php if($schema == 'delhi') { ?>
            <tr>
				<td rowspan="2"></td>
                <td rowspan="2"><b><i>MRTP Act</i></b></td>
                <td><b>T/F</b></td>
                <td>6</td>
                <td>0</td>
                <td>0</td>
                <td>2</td>
                <td>4</td>
            </tr>
            <tr>
                <td><b>2018</b></td>
                <td>1</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>1</td>
            </tr>
            <tr>
                <td><b><i></i></b></td>
                <td><b><i>Total</i></b></td>
                <td></td>
                <td class="big">7</td>
                <td class="big">0</td>
                <td class="big">0</td>
                <td class="big">2</td>
                <td class="big">1</td>
            </tr>
			<?php 
				$mrtp_filed = 7;
				$mrtp_dis = 3;
				$mrtp_pen = 1;
				$mrtp_in= $mrtp_out = 0;
			} else{
				$mrtp_i=$mrtp_dis=$mrtp_filed=$mrtp_in= $mrtp_out = 0;
			}				?>
            <tr>
                <td></td>
                <td class="large">Grand Total</td>
                <td></td>
                <td class="large"><?php echo ($total_ins_filed+$total_appeal_filed+$total_compt_appeal_filed+$total_compensation_filed+$mrtp_filed); ?></td>
                <td class="large"><?php echo ($total_ins_transfer_in+$total_appeal_transfer_in+$total_compt_appeal_transfer_in+$total_compensation_transfer_in+$mrtp_in); ?></td>
                <td class="large"><?php echo ($total_ins_transfer_out+$total_appeal_transfer_out+$total_compt_appeal_transfer_out+$total_compensation_transfer_out+$mrtp_out); ?></td>
                <td class="large"><?php echo ($total_ins_disposed+$total_appeal_disposed+$total_compt_appeal_disposed+$total_compensation_disposed+$mrtp_dis); ?></td>
                <td class="large"><?php echo ($total_ins_pending+$total_appeal_pending+$total_compt_appeal_pending+$total_compensation_pending+$mrtp_pen); ?></td>
            </tr>
        </tbody>
    </table>
</body>
<script>

 var current_date = '<?php echo $current_date; ?>'
   var unique_no = '<?php echo uniqid(); ?>'
  var xlsdocname = current_date+"-"+unique_no;
  
  function printDiv() {
		window.print();
	}
function exportEXL(){
  $('#cases_report').tableExport({
    type:'excel',
    fileName: xlsdocname,
    worksheetName: xlsdocname
  
  });
};

function exportCSV(){
  $('#cases_report').tableExport({
    type:'csv',
    fileName: xlsdocname
  });
};

function exportPDF(){
  $('#cases_report').tableExport({
    type:'pdf',
    fileName: xlsdocname
  });
};

function exportTXT(){
  $('#cases_report').tableExport({
    type:'txt',
    fileName: xlsdocname
  });
};
</script>
</html>
<?php 

}else{
	echo "Access Problem";
}



?>
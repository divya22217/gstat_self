<?php
  /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */ 
include("../db_inc1.php");

$schemas=htmlspecialchars($_SESSION['schema_name']);
//$limit = 10;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
if (isset($_GET["limit"])) { $limit  = $_GET["limit"]; } else { $limit=10; };  
if (isset($_GET["case_type"])) { $search_case_type  = $_GET["case_type"]; } else { $search_case_type=''; };  
if (isset($_GET["case_no"])) { $search_case_number  = $_GET["case_no"]; } else { $search_case_number=''; };  
if (isset($_GET["case_year"])) { $search_case_year  = $_GET["case_year"]; } else { $search_case_year=''; };  
$start_from = ($page-1) * $limit; 
if (isset($_GET["selected_case_type"])) { $backlog_flag  = $_GET["selected_case_type"]; } else { $backlog_flag=1; }; 

$count=$start_from;


function get_party($db,$filing_no,$party_flag,$party_serial_no){
	try {
	$query = "select name from e_cases_party where filing_no = ? and party_flag = ? and party_serial_no = ? limit 1";
	$party = $db->prepare($query);
    $party->bindParam(1, $filing_no, PDO::PARAM_STR);
	$party->bindParam(2, $party_flag, PDO::PARAM_STR);
	$party->bindParam(3, $party_serial_no, PDO::PARAM_STR);
    $party->execute();
	$name = $party->fetchColumn();
	return $name;
	} catch (PDOException $ex) {
        echo $ex;
    }
}


function generate_case_no($db,$schemas,$main_filing_no){
	$query = "select a.case_no,a.case_year,b.short_name from $schemas.case_detail as a left join case_type as b on b.id = a.case_type where a.filing_no = ?";
	$get_data = $db->prepare($query);
	$get_data->bindParam(1, $main_filing_no, PDO::PARAM_INT);
	$get_data->execute();
	$res = $get_data->fetchAll();
	$res = array_shift($res);
	if(!empty($res)){
	$case_no = "<br/>In<br/>";
	$case_no .= $res['short_name']."/".$res['case_no']."/".$res['case_year'];
	}else{
	$case_no = '';
	}
	return $case_no;
}

 function display_filing_no($filing_no_display){
      $lastFour =  substr($filing_no_display,-4);
      $lastFive = substr($filing_no_display,-9,-4);
      $left = substr($filing_no_display,-16,-9);
      return $dis_fil_no = $left.'/<b>'.$lastFive.'/'.$lastFour.'</b>';

  }

	
if($search_case_number == '' && $search_case_type == '' && $search_case_year == ''){
	$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.main_case_ia_no,
						b.next_list_date,b.listing_date from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no != '' order by  cast(a.case_year as integer) ,  cast(a.case_no as integer) asc LIMIT $limit offset $start_from";
}
if($search_case_number == '' && $search_case_type != '' && $search_case_year == ''){
	$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.main_case_ia_no,
						b.next_list_date,b.listing_date from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_type= '$search_case_type' and a.case_no != ''  order by  cast(a.case_year as integer) ,  cast(a.case_no as integer) asc LIMIT $limit offset $start_from";
}
if($search_case_number == '' && $search_case_type != '' && $search_case_year != ''){
	$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.main_case_ia_no,
						b.next_list_date,b.listing_date from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_type= '$search_case_type' AND a.case_year = '$search_case_year' and a.case_no != ''  order by  cast(a.case_year as integer) ,  cast(a.case_no as integer) asc LIMIT $limit offset $start_from";
}
if($search_case_number == '' && $search_case_type == '' && $search_case_year != ''){
	$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.main_case_ia_no,
						b.next_list_date,b.listing_date from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_year = '$search_case_year' and a.case_no != ''  order by  cast(a.case_year as integer) ,  cast(a.case_no as integer) asc LIMIT $limit offset $start_from";
}
if($search_case_number != '' && $search_case_type == '' && $search_case_year == ''){
	$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.main_case_ia_no,
						b.next_list_date,b.listing_date from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no = '$search_case_number' and a.case_no != ''  order by  cast(a.case_year as integer) ,  cast(a.case_no as integer) asc LIMIT $limit offset $start_from";
}
if($search_case_number != '' && $search_case_type != '' && $search_case_year == ''){
	$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.main_case_ia_no,
						b.next_list_date,b.listing_date from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and case_type= '$search_case_type' and a.case_no = '$search_case_number' and a.case_no != ''  order by  cast(a.case_year as integer) ,  cast(a.case_no as integer) asc LIMIT $limit offset $start_from";
}
if($search_case_number != '' && $search_case_type != '' && $search_case_year != ''){
	$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.main_case_ia_no,
						b.next_list_date,b.listing_date from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_type= '$search_case_type' AND a.case_year = '$search_case_year' and a.case_no = '$search_case_number' and a.case_no != ''  order by  cast(a.case_year as integer) ,  cast(a.case_no as integer) asc LIMIT $limit offset $start_from";
}
if($search_case_number != '' && $search_case_type == '' && $search_case_year != ''){
	$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.main_case_ia_no,
						b.next_list_date,b.listing_date from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_year= '$search_case_year' AND a.case_no = '$search_case_number' and a.case_no != ''  order by  cast(a.case_year as integer) ,  cast(a.case_no as integer) asc LIMIT $limit offset $start_from";
}
	$case_type_array = array(35,36,37,38,39);
	$sql1=$db->prepare($query);
	$sql1->execute();
	$all_rec= $sql1->fetchAll();
	$total_records = count($all_rec);
	if(!empty($all_rec)){
	foreach($all_rec as $key=>$row1)
	{
	  $filing_no =$row1['filing_no'];
	  $filing_date =$row1['dt_of_filing'];
	  $case_type =$row1['case_type'];
	  if(in_array($case_type,$case_type_array)){
		$main_case_no = $row1['main_case_ia_no'];
	}else {
		$main_case_no = $row1['filing_no'];
	}
	  $pet_name =get_party($db,$main_case_no,'P','1');
	  $pet_name=strtoupper($pet_name);
	  $res_name =get_party($db,$main_case_no,'R','1');
	  $res_name=strtoupper($res_name);
	  $location_code = $row1['location_code'];
	  $case_no = $row1['case_no'];
	  $case_year = $row1['case_year'];



	$count++
	?>
	<tr>
		<td><?php echo $count;?></td>

		
		<td><?php echo display_filing_no($filing_no);?></td>
		
		<?php 
		$stqq1234 = $db->prepare("select short_name from case_type where id=?");
			$stqq1234->bindParam(1, $case_type, PDO::PARAM_INT);
			$stqq1234->execute();
			$case_short_name = $stqq1234->fetchColumn();
		
		?>
		
		
		<td style="text-align:center;"><?php echo $case_short_name."/".$case_no."/".$case_year;
				if(in_array($case_type,$case_type_array)){
					if($row1['main_case_ia_no'] != ''){
				$main_case_ia_no = $row1['main_case_ia_no'];
				echo generate_case_no($db,$schemas,$main_case_ia_no);
					}
			}
		?></td>



		<td><?php echo "<center>". $pet_name.' <br/><font style="text-aling:center;color:red">Vs</font></br/>'.$res_name."</center>";?>
		</td>
		<td><?php echo ($row1['listing_date'] != '')?date('d/m/Y',strtotime($row1['listing_date'])):'';?></td>
		<td><?php echo ($row1['next_list_date'] != '' && $row1['next_list_date'] != '0001-01-01 BC')?date('d/m/Y',strtotime($row1['next_list_date'])):'';?></td>
		<td><?php echo ($row1['status'] == 'D')?'Disposed':(($row1['status'] == 'W')?'Wrongly Updated':'Pending'); ?></td>
		
	</tr>



	<?php
	
	} }
	else{
		echo "<tr><td colspan='7'><center><font style='color:red'>No Records Found</font></center></td></tr>";
	}
		
		$html = '';
		if(!empty($total_records)):for($i=1; $i<=$total_records	; $i++):  
					if($i == 1):
					$html .="<li class='active'  id=".$i."><a href='pagination.php?page=".$i."'>". $i."</a></li>";
					 else:
					$html .="<li id=".$i."><a href='pagination.php?page=".$i."'>".$i."</a></li>";
				 endif;       
		 endfor;endif;?>
		
		
		
		
		<script>
		var html = "<?php echo $html; ?>";
		var item_per_page = '<?php echo $limit; ?>';
		var total_items = $("#all_records").val();
		var current_page_no = $(".active .current").html();
		var showing_to = current_page_no*item_per_page;
		var showing_from = ((current_page_no-1)*item_per_page)+1;
		if(total_items <= showing_to){
			showing_to = total_items;
		}
		if(total_items == 0){
			showing_from = 0;
		}
		$("#total_rec").html("Showing "+showing_from+" to "+showing_to+" of "+total_items+" entries");
		//$(".pagination").html(html);
		
		
	
		</script>


<?php 


include("../db_inc1.php");
//if we remove db_inc2 then it stops working
$bench_no='';

$schemas=htmlspecialchars($_SESSION['schema_name']);
date_default_timezone_set("Asia/Kolkata");

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}

if($_SESSION['user'] !='' && $_SESSION['location'] !='' && (isset($_POST['listing_date'])))
{
	
	$listing_date = $_POST['listing_date'];
	$bench_no = $_POST['bench_no'];
	list($day,$month,$year)=explode('/',$listing_date);
	$listing_date=$year.'-'.$month.'-'.$day; 
	$query=" select cat.filing_no,cat.court_no,cat.id,cd.case_no,cd.case_year,cd.regis_date,cd.dt_of_filing,mlc.short_name,ct.short_name as case_type_short_name from $schemas.case_allocation_temp as cat 
			 left join $schemas.case_detail as cd on cd.filing_no = cat.filing_no
			 left join case_type as ct on ct.id = cd.case_type
			 left join mater_location_city as mlc on mlc.city_id = cd.location_code
			 where  listing_date =? and bench_no=? ";
	$all_cases= $db->prepare($query);
	$all_cases->bindParam(1, $listing_date, PDO::PARAM_STR);
	$all_cases->bindParam(2, $bench_no, PDO::PARAM_STR);
	$all_cases->execute();
	$all_cases = $all_cases->fetchAll();
	
	?>
	
		<table class="table no-margin table-bordered table-striped table-hover">	
			<input type="hidden" name="lis_date" value="<?php echo htmlspecialchars($listing_date);?>" />   
			<thead>
				<th>SN</th>
				<th><input type='checkbox' id='select_all' name='select_all' class='all_cases form_control'></th>
				<th>Filing No </font></th>
				<th>Case No </font></th>
			</thead>
			<tbody id="cases_table_body">
			<?php
				if(!empty($all_cases)){
					foreach($all_cases as $key=>$value){ ?>
						<tr>
							<td><?php echo $key+1; ?></td>
							<td><input type='checkbox' id="selected_cases<?php echo $value['filing_no']; ?>" name='selected_cases[]' class='cases form_control' value="<?php echo $value['filing_no']; ?>" class='cases form_control'></td>
							<td><?php echo $value['filing_no']; ?></td>
							<td><?php echo $value['case_type_short_name']."/".$value['case_no']."(".$value['short_name'].")".$value['case_year']; ?></td>
						</tr>
			<?php	}
				}else{
					echo "<tr><td colspan='3' style='color:red'>No Record Found</td></tr>";
				}
			?>
			</tbody>
		</table>
	
<?php 

}

?>
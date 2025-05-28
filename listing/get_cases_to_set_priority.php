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
	$type = $_POST['type'];
	if($type == 'get_cases'){
	$listing_date = $_POST['listing_date'];
	$bench_no = $_POST['bench_no'];
	$causelist_type = $_POST['causelist_type'];
	$table = ($causelist_type == '1')?'case_allocation_temp':'case_allocation';
	list($day,$month,$year)=explode('/',$listing_date);
	$listing_date=$year.'-'.$month.'-'.$day; 
	
	$query=" select cat.filing_no,cat.court_no,cat.id,cat.purpose,cat.priority_serial,mp.purpose_name,cd.case_no,cd.case_year,cd.regis_date,cd.dt_of_filing,mlc.short_name,ct.short_name as case_type_short_name from $schemas.$table as cat 
			 left join $schemas.case_detail as cd on cd.filing_no = cat.filing_no
			 left join case_type as ct on ct.id = cd.case_type
			 left join mater_location_city as mlc on mlc.city_id = cd.location_code
			 left join $schemas.master_purpose as mp on mp.purpose_code = cat.purpose
			 left join $schemas.bench_purpose_priority as bpp on bpp.purpose = cat.purpose
			 where  cat.listing_date =? and cat.bench_no=? and bpp.from_date = ? and bpp.bench_no = ? order by bpp.priority,cat.priority_serial";
	$all_cases= $db->prepare($query);
	$all_cases->bindParam(1, $listing_date, PDO::PARAM_STR);
	$all_cases->bindParam(2, $bench_no, PDO::PARAM_STR);
	$all_cases->bindParam(3, $listing_date, PDO::PARAM_STR);
	$all_cases->bindParam(4, $bench_no, PDO::PARAM_STR);
	$all_cases->execute();
	$all_cases = $all_cases->fetchAll();
	
	?>
	<form id='priority_form' method='POST'>
		<div class='table-responsive'>

		<table class="table no-margin table-bordered table-striped table-hover" id='causelist_priority_table'>	
			<input type="hidden" name="lis_date" value="<?php echo htmlspecialchars($listing_date);?>" />   
			<thead>
				<th>SN</th>
				<th>Filing No </font></th>
				<th>Case No </font></th>
				<th>Purpose </font></th>
				<th>Priority </font></th>
				<th  style="display:none;" id="res_scor">Sort</th>
				<th  style="display:none;" id="res_scor_2">Sort</th>
			</thead>
			<tbody id="cases_table_body">
			<?php
				if(!empty($all_cases)){
					foreach($all_cases as $key=>$value){
					$pp = $value['priority_serial'];
					if($pp==999)
					{
						  $pp='';
					}
					?>
						<tr id="row_<?php echo $value['filing_no']; ?>">
							<td><?php echo $key+1; ?></td>
							<td><?php echo $value['filing_no']; ?></td>
							<td><?php echo $value['case_type_short_name']."/".$value['case_no']."(".$value['short_name'].")".$value['case_year']; ?>
							<input type="hidden" name="fil[]" value="<?php echo $value['filing_no'];?>"/>
							</td>
							<td><?php echo $value['purpose_name']; ?></td>
							<td><font face="Verdana" size="2">
							<?php $prr = (isset($pp) && $pp != '')?$pp:1000000; ?>
							<input type="hidden" id="priority_<?php echo $value['filing_no']; ?>" value="<?php echo $prr; ?>">
							<input type="text" maxlength="3" class="priority" data-value-filing = <?php echo $value['filing_no'].'_'.$prr; ?> data-filingno = "<?php echo $value['filing_no']; ?>" size="3" name="pri[]" value="<?php 

							echo $pp;

							?>"/>
							</font>
							</td>
							<td  style="display:none;" id="hidden_sort_<?php echo $value['filing_no']; ?>">
							<?php echo $prr; ?>
							</td>
							<td  style="display:none;" id="hidden_sort_2_<?php echo $value['filing_no']; ?>">
							<?php echo $value['purpose']; ?>
							</td>
						</tr>
			<?php	}
				}else{
					echo "<tr><td style='color:red'>No Record Found</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
				}
			?>
			</tbody>
		</table>
		</div>
		<?php if(!empty($all_cases)){ ?>
		<center><button type="button" name="submit1" class="button btn btn-success" onClick='return submit_priority_form();'> Submit </button></center>
	<?php }  ?>
	</form>
		<script>
		
		var f_sl = 1;
   $(document).on("keyup",".priority",function(){
	  var value = this.value;
	  var filing_no = $(this).data('filingno');
	  var int_val = parseInt(value);
	  $("#hidden_sort_"+filing_no).html(int_val);
	   $("#causelist_priority_table").dataTable().fnDestroy()
	    $('#causelist_priority_table').DataTable({
			"language": [ {
        "decimal": ".",
        "thousands": ","
    } ] ,
			"paging":   true,
			"searching": true,
			"lengthMenu": [100,200,300,400,500],
		 "order": [[ 6, "asc" ],[ 5, "asc" ]]
	});
	$(this).focus();
	});
		
		
		$(document).ready(function() {
			$("#causelist_priority_table").dataTable().fnDestroy()
			$('#causelist_priority_table').DataTable({
				"language": [ {
				"decimal": ".",
				"thousands": ","
			} ] ,
					"paging":   true,
					"searching": true,
					"lengthMenu": [100,200,300,400,500],
				 "order": [[ 6, "asc" ],[ 5, "asc" ]]
			});
		} );	
		</script>
	
<?php 


}


}

?>
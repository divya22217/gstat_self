<?php 
  $schemas = $_REQUEST['schema_name'];
  //echo "<pre>"; print_r($_REQUEST);
?><style>
.accordion {
    margin-top: 30px;
}

.accordion .card-header h2 {
    font-size: 17px;
}

.accordion .card-header h2 {
    color: #fff;
}

.accordion .card-header.collapsed h2 {
    color: #fff;
}

.accordion .card-header {
    padding-right: 40px;
    position: relative;
    cursor: pointer;
    background: #4493cc;
}

.accordion .card-header::after {
    content: '_';
    font-size: 30px;
    color: #fff;
    position: absolute;
    right: 15px;
    top: -14px;
}

.accordion .card-header.collapsed::after {
    content: '+';
    top: -1px;
    right: 13px;
}

.accordion .card-body {
    font-size: 15px;
    color: #6f6f6f;
}
    </style>


<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php";
include "../db_inc2.php";
include "../master/functions.php";



function fn_getChild($db,$schemas, $filing_no)
{
    $mainCno = "select filing_no,case_no,case_year,short_name,cast(case_no as int) as case_nooo from $schemas.case_detail inner join case_type on case_type=id where main_case_ia_no= ? order by case_nooo,case_year asc";
    $mainCrs = $db->prepare($mainCno);
    $mainCrs->bindParam(1, $filing_no, PDO::PARAM_STR);
    $mainCrs->execute();
    $data_ia_main = $mainCrs->fetchAll();
    return $data_ia_main;
    //$case_no = '<br> IN <br> '.$data_ia_main['short_name'] . '/' . $data_ia_main['case_no'] . '/' . $data_ia_main['case_year'];
}

function case_type($db)
{
    $data_main = array();
    $st = $db->prepare("select * from case_type where status='t' order by id ASC");
    $st->execute();
    $i = 0;
    while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $tmp = array();
        if ($row['status'] == 't') {
            $tmp['case_type_desc'] = $row['case_type_desc'];
            $tmp['act_id'] = $row['act_id'];
            $tmp['inter_act_id'] = $row['inter_act_id'];
            $tmp['display'] = $row['display'];
            $tmp['short_name'] = $row['short_name'];
            $tmp['id'] = $row['id'];
            $data_main[$i] = $tmp;
            $i++;
        }
    }
    return $data_main;
}

function case_details($db, $schema, $case_status, $case_year)
{
    $case_status111 = 'status = ' . "'$case_status'" . ' and ';
    if ($case_status == 'All') {
        $case_status111 = '';
    }
    $case_detail_year_co = $db->prepare("select count(filing_no) as total_case_no from $schema.case_detail where $case_status111 case_year =  '" . $case_year . "'");
    $case_detail_year_co->execute();
    $total_case = $case_detail_year_co->fetchColumn();
    return $total_case;
}


function year_list($year)
{
    for ($i = date('Y'); $i >= 2016; $i--) {?>
<option <?php if ($year == $i) {
        echo 'selected';
    }?> value="<?php echo $i; ?>"><?php echo $i ?></option>
<?php }
}

// ravi stap 
 if ($_POST['action'] == 'case_status_search') {
    $schemas = $schemas;
    $answer = $_POST['answer'];
    if ($_SESSION['vercode'] != $answer or empty($answer)) {
        echo  $meaasge = "Captch Value is incorrect/Empty, kindly try again";
       die; 
    }
    $column = ' cast(a.case_no as int) as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,b.short_name,a.main_case_ia_no,a.backlog ';
     $global_where = " where a.case_no != '' and a.status != 'W'";
   $global_where2 = " and a.case_no != '' and a.status != 'W'";
	$case_year = $_POST['case_year'];
    $case_type = $_POST['case_type'];
    $case_number = $_POST['case_number'];
    $diary_no = $_POST['diary_no'];
    $party_name = $_POST['party_name'];
    $advocate_name = $_POST['advocate_name'];
    $select_party = $_POST['select_party'];
    $crn_no = $_POST['crn_no'];
    $case_data = array();
    try {
          if ($_POST['search_by'] == '3') {
            $whwre = '';
            if ($case_year != 'All') {
                $whwre = "and a.case_year = '$case_year'";
            }
            $query_q = $db->prepare("select $column from $schemas.case_detail  as a join case_type as b ON b.id = a.case_type where a.case_type = ? and a.case_no = ? $global_where2 $whwre  order by a.case_year asc, case_non asc, a.regis_date desc ");
            $query_q->bindParam(1, $case_type, PDO::PARAM_STR);
            $query_q->bindParam(2, $case_number, PDO::PARAM_STR);
        }  else if ($_POST['search_by'] == '5') {

            $exact_search_word = $_POST['exact_search_word'];
            $text_name = strtolower($_POST['text_name']);
            $data_search =   "LOWER(c.order_html) like '%$text_name%'";
            if($exact_search_word == '2') { 
				$text_name = $_POST['text_name'];
              $data_search =   "c.order_html like '%$text_name%'";
            }
             /* $query_q = "
            select d.short_name,c.order_date, cast(a.case_no as int) as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog,c.judge_codes
from $schemas.order_daily as c
inner join $schemas.case_detail as a on a.filing_no = c.filing_no
join case_type as d ON d.id = a.case_type
where a.case_no != '' and a.status != 'W' and $data_search order by c.order_date desc "; */
		$query_q = "select d.short_name,cast(a.case_no as int) as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog
from (select distinct(c.filing_no) as filing_no_order from $schemas.order_daily as c where LOWER(c.order_html) like '%$text_name%') as temp_table
inner join $schemas.case_detail as a on a.filing_no = temp_table.filing_no_order
join case_type as d ON d.id = a.case_type
where a.case_no != '' and a.status != 'W'";
            $query_q = $db->prepare($query_q);

        } else if ($_POST['search_by'] == '6') {
           $from_date = $_POST['from_date'];
           $to_date = $_POST['to_date'];
           $select_judge = $_POST['select_judge'];
		   $category = $_POST['category'];
			if($category == ''){
				echo "please select Category";
				die;
			}
			$case_type = $_POST['case_type'];
			if($case_type == ''){
				echo "please select case type";
				die;
			}
			if($case_type == 'all'){
				$case_type_query = '';
			}else{
				$case_type_query = " and a.case_type = '$case_type'";
			}
			if($category == 'all'){
				$cat_query = " a.case_no != '' and a.status != 'W'";
			}else if($category == 'D'){
				$cat_query = "  a.case_no != '' and a.status = 'D'";
			}else if($category == 'T'){
				$cat_query = "  a.case_no != '' and a.status = 'T'";
			}else {
				$cat_query = "  (a.case_no != '' and a.status != 'D' and a.status != 'W' and a.status != 'T')";
			}
		   //echo "<pre>"; print_r($select_judge);
            /* $query_q = $db->prepare("
            select d.short_name,c.order_date, cast(a.case_no as int) as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog,c.judge_codes  
from $schemas.order_daily as c
inner join $schemas.case_detail as a on a.filing_no = c.filing_no
join case_type as d ON d.id = a.case_type
where (order_date between '$from_date' and '$to_date') and (string_to_array(judge_codes, ',') && Array['$select_judge']) $cat_query $case_type_query
 order by c.order_date desc "); */
 $query_q = $db->prepare("
            select d.short_name, cast(a.case_no as int) as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog  
from (select distinct(filing_no) as filing_no_order from $schemas.order_daily as c where ((c.order_date between '$from_date' and '$to_date') and (string_to_array(c.judge_codes, ',') && Array['$select_judge']))) as temp_table
inner join $schemas.case_detail as a on a.filing_no = temp_table.filing_no_order
join case_type as d ON d.id = a.case_type
where $cat_query $case_type_query");
           // $query_q->bindParam(1, $crn_filing_no, PDO::PARAM_STR);
        } else if ($_POST['search_by'] == '7') {
			
			$from_date = $_POST['from_date'];
			$to_date = $_POST['to_date'];
			$court = $_POST['court'];
			if($court == ''){
				echo "please select court";
				die;
			}
			if($court == 'all'){
			 $court_query = '';
			}else{
				$court_query = " and c.court_no = '$court'";
			}
			/* $query_q = $db->prepare("
            select d.short_name,c.order_date, cast(a.case_no as int) as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog,c.judge_codes  
			from $schemas.order_daily as c
			inner join $schemas.case_detail as a on a.filing_no = c.filing_no
			join case_type as d ON d.id = a.case_type
			where a.case_no != '' and a.status != 'W' and order_date between '$from_date' and '$to_date' $court_query
			 order by c.order_date desc "); */
			 $query_q = $db->prepare("
            select d.short_name, cast(a.case_no as int) as case_non, a.regis_date,a.filing_no,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog  
from (select distinct(filing_no) as filing_no_order from $schemas.order_daily as c where c.order_date between '$from_date' and '$to_date' $court_query) as temp_table
inner join $schemas.case_detail as a on a.filing_no = temp_table.filing_no_order
join case_type as d ON d.id = a.case_type
where a.case_no != '' and a.status != 'W'");
		} else if ($_POST['search_by'] == '8') {
			$from_date = $_POST['from_date'];
			$to_date = $_POST['to_date'];
			$category = $_POST['category'];
			$case_type_search = $_POST['case_type'];
			if($category == ''){
				echo "please select Category";
				die;
			}
			if($case_type_search == 'all'){
                $case_type_search_query = "";
            }else{
                $case_type_search_query = "and a.case_type = $case_type_search";
            }
			if($category == 'all'){
				$query_text = "select d.short_name, cast(a.case_no as int) as case_non,a.filing_no, a.regis_date,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog 
			from (select distinct(filing_no) as filing_no_order from $schemas.order_daily as c where c.order_date between '$from_date' and '$to_date') as temp_table
			inner join $schemas.case_detail as a on a.filing_no = temp_table.filing_no_order
			join case_type as d ON d.id = a.case_type
			where (a.status != 'W' and a.case_no != '') $case_type_search_query";
			}else if($category == 'D'){
				$query_text = "select d.short_name, cast(a.case_no as int) as case_non,a.filing_no, a.regis_date,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog 
			from (select distinct(filing_no) as filing_no_order from $schemas.order_daily as c where c.order_date between '$from_date' and '$to_date') as temp_table
			inner join $schemas.case_detail as a on a.filing_no = temp_table.filing_no_order
			join case_type as d ON d.id = a.case_type
			where (a.status = 'D' and a.case_no != '') $case_type_search_query";
			}
			else if($category == 'T'){
				$query_text = "select d.short_name, cast(a.case_no as int) as case_non,a.filing_no, a.regis_date,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog 
			from (select distinct(filing_no) as filing_no_order from $schemas.order_daily as c where c.order_date between '$from_date' and '$to_date') as temp_table
			inner join $schemas.case_detail as a on a.filing_no = temp_table.filing_no_order
			join case_type as d ON d.id = a.case_type
			where (a.status = 'T' and a.case_no != '') $case_type_search_query";
			}else {
			
			$query_text  = "
			select d.short_name, cast(a.case_no as int) as case_non,a.filing_no, a.regis_date,a.case_no,a.case_year,a.case_type,a.pet_name,a.res_name,a.main_case_ia_no,a.backlog 
			from (select distinct(filing_no) as filing_no_order from $schemas.order_daily as c where c.order_date between '$from_date' and '$to_date') as temp_table
			inner join $schemas.case_detail as a on a.filing_no = temp_table.filing_no_order
			join case_type as d ON d.id = a.case_type
			where (a.case_no != '' and a.status != 'D' AND a.status != 'W' and a.status != 'T') $case_type_search_query ";
			}
			//echo $query_text;
			$query_q = $db->prepare($query_text);
		}else if ($_POST['search_by'] == '4') {
            if ($select_party == '1') {
                $query_q = $db->prepare("select $column from $schemas.case_detail   as a 
                    inner join $schemas.order_daily as c on c.filing_no = a.filing_no
                    join case_type as b ON b.id = a.case_type  $global_where and (a.pet_name like '%$party_name%' or a.res_name like '%$party_name%')   and a.status != 'W'  order by a.case_year asc, case_non asc, a.regis_date desc ");
            } else if ($select_party == '2') {
                
                $st_party = $db->prepare("SELECT filing_no FROM public.e_cases_party where (filing_no != 'NA' OR filing_no is not null) and  name like '%$party_name%' ");
                $st_party->execute();
                $filing_no_data = $st_party->fetchAll();
                $fiing_nos = '';
                if (!empty($filing_no_data) && is_array($filing_no_data)) {
                    foreach ($filing_no_data as $val) {
                        $filiddd = $val["filing_no"];
                        $fiing_nos .= "'$filiddd'" . ',';
                    }
                }
                $fiing_nos = rtrim($fiing_nos, ',');

                $filinf_noss = " in( $fiing_nos)";
                if ($fiing_nos == '') {
                    $filinf_noss = " = 'sssssss' ";
                }

                $query_q = $db->prepare("select $column from $schemas.case_detail   as a join 
                    inner join $schemas.order_daily as c on c.filing_no = a.filing_no
                    case_type as b ON b.id = a.case_type $global_where and a.filing_no  $filinf_noss  and a.status != 'W'  order by a.case_year asc, case_non asc, a.regis_date desc ");
            }
        }
        $query_q->execute();
        $case_data = $query_q->fetchAll();
    } catch (PDOException $ex) {
		echo $ex;
        echo "Something went wrong";
        die;
    }
    // print_r($case_data);
    ?>

<table class="table">
    <tr>
        <th colspan="5" style="text-align: center">
            <div class="form_heading">GSTAT </div>
        </th>
    </tr>
    <tr>
        <th style="width:5%">Sr. No.</th>
        <th style="width:5%">Filing No.</th>
        <th style="width:16%">Case No.</th>
        <th style="width:45%">Case Title</th>
        <th style="width:16%">Registration&nbsp;Date</th>
        <th>Action</th>
    </tr>
    <?php

  //  print_r($case_data);
if (!empty($case_data) && is_array($case_data)) {
        $i = 1;
        foreach ($case_data as $value) {
// Main File Child
            $case_no_child = '';
            //if ($value['case_type'] == '1' or $value['case_type'] == '2' or $value['case_type'] == '3') {
                $data_chield = fn_getChild($db,$schemas, $value['filing_no']);
                if (!empty($data_chield) && is_array($data_chield)) {
                    foreach ($data_chield as $chil) {
                        $case_no_child .= '<br> ' . $chil['short_name'] . '/' . $chil['case_no'] . '/' . $chil['case_year'];
                    }
                }
            //}

// Child Parent Condition
            if ($value['main_case_ia_no'] != '' or $value['main_case_ia_no'] != null) {
                $iama_no = $value['main_case_ia_no'];
                $mainCno = "select case_no,case_year,short_name from $schemas.case_detail inner join case_type on case_type=id where filing_no= ?";
                $mainCrs = $db->prepare($mainCno);
                $mainCrs->bindParam(1, $iama_no, PDO::PARAM_STR);
                $mainCrs->execute();
                $data_ia_main = $mainCrs->fetch();
                $case_no_child .= '<br>IN<br> ' . $data_ia_main['short_name'] . '/' . $data_ia_main['case_no'] . '/' . $data_ia_main['case_year'];
            }
			
			if($value['main_case_ia_no'] != '' or $value['main_case_ia_no'] != null)
				$party_fn = $value['main_case_ia_no'];
			else
				$party_fn = $value['filing_no'];
			
			$pet_name = get_party($db,$party_fn,'P',1);
			$res_name = get_party($db,$party_fn,'R',1);

            ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $value['filing_no']; ?></td>
        <td style="text-align: center;"><?php echo $value['short_name'] . ' - ' . $value['case_no'] . '/' . $value['case_year'];
            echo $case_no_child;
            ?></td>
        <td><?php echo $pet_name. ' VS '. $res_name; ?></td>
        <td><?php  if($value['regis_date'] !='' &&  $value['backlog'] != '1') { echo date('d/m/Y', strtotime($value['regis_date'])); }  ?></td>
        <td><button type="button" onclick="fn_case_details('<?php echo $value['filing_no']; ?>')"
                class="btn btn-success"><i class="fa fa-eye"></i> View</button></td>
    </tr>
    <?php
$i++;
        }} else {
        ?>
    <tr>
        <td colspan="5"> No Data Found
        <td>
    </tr>
    <?php
}
    die;
}

// ravi status

else if ($_POST['action'] == 'select_case_status') {
    $case_type_data = case_type($db);
    if ($_POST['search_by'] == '3') {?>
    <div class="form-group">

        <label>Select Case Type</label>
        <select required="required" class="form-control" name="case_type" id="case_type" onChange="return get_case_years(this.value);">
            <option value="">Select</option>
            <?php if (!empty($case_type_data)) {
        foreach ($case_type_data as $val) {
            echo '<option value="' . $val['id'] . '">' . $val['case_type_desc'] .  '</option>';
        }
    }?>
        </select>
    </div>
    <div class="form-group">
        <label>Enter Case No.</label>
        <input type="text" name="case_number" id="case_number" class="form-control required">
    </div>

    <div class="form-group">
        <label>Select Case Year</label>
        <select class="form-control required" name="case_year" id="case_year">
            <option value="">Select</option>
            <option value="All">All</option>
            <?php echo year_list(date('Y')); ?>
        </select>
    </div>
    
    <?php
} else  if ($_POST['search_by'] == '5') {?>

<div class="form-group">
        <label>Search By *</label>
<select name="exact_search_word" id="exact_search_word" class="form-control" id="" required="required">					
						<option value="1">Word Search</option>
						<option value="2">Exact Search</option>
				</select>
                </div>

                <div class="form-group">
				<label>Free Text <span style="color:red;">*</span></label>
				<input type="text" name="text_name" id="text_name" class="form-control" maxlength="50" value="" required="required">
			</div>

    <?php
} else if ($_POST['search_by'] == '6') {
                $chairperison_list = "SELECT * FROM $schemas.master_judge where judge_desg_code in (1,6,7,8) order by judge_desg_code desc";
                $chairperison_list = $db->prepare($chairperison_list);
                $chairperison_list->execute();
                $chairperison_list = $chairperison_list->fetchAll();
				
				$more_judge_list = "SELECT * FROM $schemas.master_judge where judge_desg_code not in (1,6,7,8) order by judge_desg_code asc";
                $more_judge_list = $db->prepare($more_judge_list);
                $more_judge_list->execute();
                $more_judge_list = $more_judge_list->fetchAll();
				
				$judge_list = array_merge($chairperison_list,$more_judge_list);
              //  print_r($judge_list);
    
    ?>

    <div class="form-group">
        <label>Select Judge</label>
        <select required="required" class="form-control" name="select_judge" id="select_judge">
            <option value="">Select Judge</option>
            <?php 
            if(!empty($judge_list) && is_array($judge_list)) {
                foreach($judge_list as $val_j) {  ?>
            <option value="<?php echo $val_j['judge_code'];?>"><?php echo $val_j['judge_name'];?></option>
            <?php  } } 
            ?>

        </select>
    </div>
	<div class="form-group">

        <label>Select Case Type</label>
        <select required="required" class="form-control" name="case_type" id="case_type" onChange="return get_case_years(this.value);">
            <option value="">Select</option>
			<option value="all">All</option>
            <?php if (!empty($case_type_data)) {
        foreach ($case_type_data as $val) {
            echo '<option value="' . $val['id'] . '">' . $val['case_type_desc'] .  '</option>';
        }
    }?>
        </select>
    </div>
	<div class="form-group">
        <label>Select Category</label>
        <select required="required" class="form-control" name="category" id="category">
			<option value="">Select</option>
			<option value="all">All</option>
			<option value="P">Daily Order/Pending</option>
			<option value="D">Judgement/Disposed</option>
			<option value="T">Transferred</option>
        </select>
    </div>
    <div class="form-group">
        <label>From Date</label>
        <input type="date" name="from_date" id="from_date" class="form-control required">
    </div>
    <div class="form-group">
        <label>To Date</label>
        <input type="date" name="to_date" id="to_date" class="form-control required">
    </div>

	

    <?php
}else if ($_POST['search_by'] == '7') {
    
    

                $court_list = "SELECT * FROM $schemas.court";
                $court_list = $db->prepare($court_list);
                $court_list->execute();
                $court_list = $court_list->fetchAll();
              //  print_r($judge_list);
    
    ?>

    <div class="form-group" id='courts'>
        <label>Select Court</label>
        <select required="required" class="form-control" name="court" id="court">
            <option value="">Select Court</option>
			<option value="all">All</option>
            <?php 
            if(!empty($court_list) && is_array($court_list)) {
                foreach($court_list as $court) {  ?>
            <option value="<?php echo $court['court_no'];?>"><?php echo $court['display_court_text'];?></option>
            <?php  } } 
            ?>
            
        </select>
    </div>
    <div class="form-group">
        <label>From Date</label>
        <input type="date" name="from_date" id="from_date" class="form-control required">
    </div>
    <div class="form-group">
        <label>To Date</label>
        <input type="date" name="to_date" id="to_date" class="form-control required">
    </div>
  
    <?php
}else if ($_POST['search_by'] == '8') {
    
    

                $court_list = "SELECT * FROM $schemas.court";
                $court_list = $db->prepare($court_list);
                $court_list->execute();
                $court_list = $court_list->fetchAll();
              //  print_r($judge_list);
    
    ?>
	
	 <div class="form-group">

        <label>Select Case Type</label>
        <select required="required" class="form-control" name="case_type" id="case_type">
        <option value="">Select</option>
        <option value="all">All</option>
            
            <?php if (!empty($case_type_data)) {
        foreach ($case_type_data as $val) {
            echo '<option value="' . $val['id'] . '">' . $val['case_type_desc'] .  '</option>';
        }
        }?>
        </select>
    </div>

    <div class="form-group">
        <label>Select Category</label>
        <select required="required" class="form-control" name="category" id="category">
			<option value="">Select</option>
			<option value="all">All</option>
			<option value="P">Daily Order</option>
			<option value="D">Final Order/Judgement</option>
			<option value="T">Transferred</option>
        </select>
    </div>
    <div class="form-group">
        <label>From Date</label>
        <input type="date" name="from_date" id="from_date" class="form-control required">
    </div>
    <div class="form-group">
        <label>To Date</label>
        <input type="date" name="to_date" id="to_date" class="form-control required">
    </div>
  
    <?php
}else if ($_POST['search_by'] == '4') { ?>

            <div class="form-group">
                <label>Select Type</label>
                <select required="required" class="form-control" name="select_party" id="select_party">
                    <option value="1">Main Party</option>
                    <option value="2">Addtional Party</option>
                </select>
            </div>
            <div class="form-group">
                <label>Party Name</label>
                <input type="text" name="party_name" id="party_name" class="form-control required">
            </div>
            <?php
            $data_party_app = parties($dbonline, 'P');
            $petitioners = json_encode($data_party_app);
            ?>
            <script>
                $(function() {
                    var availableTags = '';
                    $("#party_name").autocomplete({
                        source: <?php echo $petitioners; ?>
                    });
                });
            </script>

        <?php
        }

if ($_POST['search_by'] != '6') {
    ?>
  

    <?php }

$_SESSION['salt'] = sha1(microtime());
$saltbb = $_SESSION['salt'];
?>
    <input name="salt" type="hidden" value="<?php echo htmlspecialchars(htmlentities($saltbb)); ?>" />

    <div class="form-group captcha-div">
        <label><input name="answer" id="answer" type="text" placeholder="Captcha" class="form-control required input"
                size="18" maxlength="6" autocomplete="off"></label>
        <img src="captcha.php" class="captcha" alt="captcha" />
        <a href="javascript:void(0);">
            <img class="refresh" src="assets/img/icon_refresh.png">
        </a>
    </div>

    <div class="form-group text-right">
        <label>&nbsp;</label>
        <button type="button" onclick="fn_search_by_case_dfr()" class="btn btn-primary"><i class="fa fa-search"></i>
            Search</button>
    </div>

    <script>
    $(".refresh").click(function() {
        $(".captcha").attr("src", "captcha.php?_=" + ((new Date()).getTime()));
    });
    </script>
    <?php
} 

function parties($dbonline, $type)
{
    //echo "SELECT name FROM public.e_cases_party where filing_no = '$filing_no' and party_flag = 'P' ";
    $st_party = $dbonline->prepare("SELECT name FROM public.e_cases_party where (filing_no != 'NA' OR filing_no is not null) and  name != 'NA' ");
    $st_party->execute();
    $data_party = $st_party->fetchAll();

    $main_data = array();
    if (!empty($data_party) && is_array($data_party)) {
        foreach ($data_party as $val) {
            $main_data[] = $val['name'];
        }
    }
    return $main_data;
}
function fn_advocate($dbonline)
{
    $st_party = $dbonline->prepare("SELECT distinct(a.rep_code), b.rep_name,b.bar_council_number FROM public.e_more_representative as a
    join e_master_advocate as b ON a.rep_code = b.id
     where (a.filing_no != 'NA' OR a.filing_no is not null) and  a.display = true and (b.rep_name != 'NA' OR b.rep_name is not null) ");
    $st_party->execute();
    $data_party = $st_party->fetchAll();
    $main_data = array();
    if (!empty($data_party) && is_array($data_party)) {
        foreach ($data_party as $val) {
            //.'('.$val['bar_council_number'].')'
            $main_data[] = $val['rep_name'];
        }
    }
    return $main_data;
}

function fn_advocate_type($dbonline,$filing_no,$type)
{
    $st_party = $dbonline->prepare("SELECT distinct(a.rep_code), b.rep_name,b.bar_council_number FROM public.e_more_representative as a
    join e_master_advocate as b ON a.rep_code = b.id
     where  a.display = true and (b.rep_name != 'NA' OR b.rep_name is not null) and a.filing_no = '$filing_no' and a.party_flag = '$type' ");
    $st_party->execute();
    $data_party = $st_party->fetchAll();
    $main_data = array();
    if (!empty($data_party) && is_array($data_party)) {
        foreach ($data_party as $val) {
            //.'('.$val['bar_council_number'].')'
            $main_data[] = $val['rep_name'];
        }
    }
    return $main_data;
}



?>

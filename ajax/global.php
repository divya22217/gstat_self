<?php

function year_last_three($db, $schema)
{
    $data = array();
    $case_detail_year = $db->prepare("select DISTINCT(case_year) from $schema.case_detail order by case_year DESC limit 3");
    $case_detail_year->execute();
    $i = 0;
    while ($row = $case_detail_year->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $data[$i] = $row['case_year'];
        $i++;
    }
    return $data;
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


function getdata($db,$schema){
	$total_casse = 0;
	$year_llist = year_last_three($db, $schema);
	foreach ($year_llist as $year_val) {
		$to_cases = case_details($db, $schema, 'All', $year_val);
		$total_casse = $total_casse + $to_cases;
	}
	return $total_casse;
}



?>
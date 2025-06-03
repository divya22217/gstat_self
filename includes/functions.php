<?php
class Functions
{
    	function gen_case_no($filing_no,$db,$schemas){ 
		//echo "here";
		//global $db;
        //global $schemas;
		$get_case_detail_sql = "select case_no,case_type,case_year,location_code,case_type from $schemas.case_detail where filing_no=?";
		$get_case_detail = $db->prepare($get_case_detail_sql);
		$get_case_detail->bindParam(1, $filing_no, PDO::PARAM_STR);
		$get_case_detail->execute();
		//$count_c = $get_case_detail->rowCount();
		while ($row_gcd = $get_case_detail->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
			$case_no=$row_gcd['case_no'];
			$case_type=$row_gcd['case_type'];
			$case_year=$row_gcd['case_year'];
			$location_code =$row_gcd['location_code'];
			$case_type =$row_gcd['case_type'];
		}
		if($location_code){

      $lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
      $lcode=$db->prepare($lcode);
      $lcode->execute();
      $lcodename = $lcode->fetchColumn();
		}

//$sql="select short_name from case_type where id = ?";
if($case_type > 0)
{
$stQ = $db->prepare("select short_name from case_type where id = ?");
$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
$stQ->execute();
$case_type_short_name=$stQ->fetchColumn();
}

$case_numaa = $case_no;
$case_year1aa = $case_year;
		$case_num1aa=ltrim($case_numaa,0);
		if($case_no==''){
			return;
		}else{
		 return $CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_num1aa.'('.$lcodename.')'.$case_year1aa);
		}
	}
}
?>
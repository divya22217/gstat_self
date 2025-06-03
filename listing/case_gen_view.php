<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include("../db_inc1.php");
 
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
echo "you Can't access this page";
}
else {

$curYear = date('Y');
$curMonth = date('m');
$curDay = date('d');
$cur_date = "$curDay/$curMonth/$curYear";
$curdate="$curYear-$curMonth-$curDay";

$wday = mktime(0,0,0,date("$curMonth"),date("d")-5,date("Y"));
$wday1= date("Y-m-d");


$schemas=htmlspecialchars($_SESSION['schema_name']);
$frm = md5( uniqid('auth', true) );


$_SESSION['form_token'] = $frm;

$no =$_REQUEST['no'];



?>
<html>
<body class="hold-transition skin-blue sidebar-mini">
<div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead style="border: 1;background-color: silver;">
                  <tr>
                  <th width="10%"><b>Sr No.</th>
                    <th width="20%"><b>Diary No.</th>
                   
                    <th width="20%"><b>Case Number</th>
                    <th width="40%"><b>Title</th>
					<th width="30%"><b>Court No</th>
					<!--<th width="30%"><b>Item No</th>-->
                   
                  </tr>
                  </thead>
                  <tbody >
				
<?php 
			$srno =1;

			$sqlf1=$db->prepare("select * from $schemas.case_allocation_temp  where listing_date=?");
            $sqlf1->bindParam(1, $no, PDO::PARAM_STR);
			$sqlf1->execute();
			while ($row1 = $sqlf1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{
           		
			 $filing_no=$row1['filing_no'];
			 $court_no=$row1['court_no'];
			 $item_no=$row1['item_no'];
			
			 $sqlf11=$db->prepare("select * from $schemas.case_detail  where filing_no=?");
             $sqlf11->bindParam(1, $filing_no, PDO::PARAM_STR);
			 $sqlf11->execute();
			
			while ($row11 = $sqlf11->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{
         	 $case_type=$row11['case_type'];
			 $case_no=$row11['case_no'];
			 $case_year=$row11['case_year'];
			 $location_code =$row11['location_code'];
			 $pet_name =$row11['pet_name'];
			 $res_name =$row11['res_name'];
			  $case_title=$pet_name." VS ".$res_name;
			}
			  
$lcode ="select short_name from mater_location_city where city_id ='$location_code'";
$lcode=$db->prepare($lcode);
$lcode->execute();
$lcodename = $lcode->fetchColumn();

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
		
		 $CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_no.'/'.$lcodename.'/'.$case_year);
			

?>
<tr>

<td align="center">
<?php 
echo $srno++;
?>
</td>
<td align="center">
<?php echo $filing_no;?>
</td>
</td>
<td align="center">
<?php echo $CASE_NO;?>
</td>
<td align="center">
<?php echo $case_title;?>
</td>
<td align="center">
<?php echo $court_no;?>
</td>
<!--
<td align="center">


<?php //echo $item_no;?>
</td>

</tr>
-->
<?php 
}

	
			}

?>
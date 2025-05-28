<?php
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(E_ALL);
// At the top of the page we check to see whether the user is logged in or not
/*if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	// If they are not, we redirect them to the login page.
	// Remember that this die statement is absolutely critical.  Without it,
	// people can view your members-only content without logging in.
	die("Redirecting to login.php");
}*/
/*if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}
else
 {*/
$hash=$_REQUEST['print_details'];
$print_details=htmlspecialchars(base64_decode($hash));
//echo $print_details;die;
list($next_list_date,$court_no)=explode('//',$print_details);
include("../db_inc1.php");
//$next_list_date = '13/09/2019';
$schemas=htmlspecialchars($_SESSION['schema_name']);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>NCLT | Order Report</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
	<!-- jvectormap -->
	<link rel="stylesheet" href="../bower_components/jvectormap/jquery-jvectormap.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">



	

	<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
	<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
	<script src="../plugins/jQueryUI/jquery-ui.js"></script>
	<script src="../plugins/jQueryUI/date.js"></script>
	
<link rel="stylesheet" type="text/css" href="../includes/highslide/highslide.css" />
<script type="text/javascript" src="../includes/highslide/highslide-with-html1.js"></script>

<style>
		table, td, th {
			border: 1px solid #1d99d4;
		}



		th {
			background-color: #074c62;
			color: white;
		}
		
		@media print {
  #printPageButton {
    display: none;
  }
}
	</style>
</head>
<body>
<div class="wrapper">
<div class="">
<section class="content">
	<div id="printPageButton" style="visibility: visible;"><a href="javascript:window.print();"><font size="4" color="red">
Print</font></a>
</div>
<center><h4>Court No.<?php echo $court_no;?></center></h4>
<table cellspacing="1" cellpadding="1" border="0" width="95%"   align="center">
<tr>
<th><font><center>Case No.</center></font></th>
<th><font><center>
Party Detail</center></font>
</th>
<th><font><center>Order Date</center></font></th>
<!--th>View </th-->
<th><font><center>Order Body</center></font></th>
</tr>
 
 <?php
  if($next_list_date){
 list($day,$month,$year)=explode('/',$next_list_date);
 $list_cdate=$year.'-'.$month.'-'.$day;
 $stnq = $db->prepare("select * from $schemas.order_daily where order_date=? and court_no=?");
 $stnq->bindParam(1, $list_cdate, PDO::PARAM_INT);
 $stnq->bindParam(2, $court_no, PDO::PARAM_INT);
 $stnq->execute();

 if($stnq->rowCount()==0)
 {
 ?>
 <tr>
<td align="center" colspan="16" ><font color="red" size="4"><b>No Record Found </b></font></td>
</tr>
 <?php
 } 
 if($stnq->rowCount()>0)
 {
$counter=1;

$hash2all = array();
 while ($rw2 = $stnq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
	extract($rw2);
 	if($filing_no !='')
 	{
 	$stcn = $db->prepare("select * from $schemas.case_detail where filing_no=?");
 	$stcn->bindParam(1, $filing_no, PDO::PARAM_STR);
 	$stcn->execute();
 	while ($rw2 = $stcn->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 	{
 	
 		$filing_no98 = htmlspecialchars($rw2['filing_no']);
               
 		$case_no=htmlspecialchars($rw2['case_no']);
 		$pt_name=htmlspecialchars($rw2['pet_name']);
 		$rs_name=htmlspecialchars($rw2['res_name']);
 		$pet_code=htmlspecialchars($rw2['pet_org_type']);
 		$res_code=htmlspecialchars($rw2['res_org_type']);
 		$pet_type=htmlspecialchars($rw2['pet_type']);
 		$res_type=htmlspecialchars($rw2['res_type']);
 		$case_type=htmlspecialchars($rw2['case_type']);
 		$case_year=htmlspecialchars($rw2['case_year']);
		$location_code=htmlspecialchars($rw2['location_code']);
		$st1 = $db->prepare("select * from $schemas.bench_location where bench_location_code=?");
$st1->execute(array($location_code));
$bech_data= $st1->fetch();
$bech_code = $bech_data['short_name'];

 		
 	
 	}
 	
 	if($filing_no98 !='')
 	{
 		if($case_type > 0)
 		{
 			$stQ = $db->prepare("select case_type_desc from case_type where id = ?");
 			$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
 			$stQ->execute();
 			$case_type_short_name=$stQ->fetchColumn();
 		}
 		if($pet_type =='2')
 		{
 			if($pet_code > 0)
 			{
 	
 				$stn = $db->prepare("select org_name from $schemas.master_licensor where org_code=?");
 				$stn->bindParam(1, $pet_code, PDO::PARAM_INT);
 				$stn->execute();
 				$pet_org_name = $stn->fetchColumn();
 					
 	
 			}
 		}
 	
 	
 	
 		if($pet_code > 0 and $pet_type ==4)
 		{
 			$stm = $db->prepare("select org_name from $schemas.master_licensee where org_code=?");
 			$stm->bindParam(1, $pet_code, PDO::PARAM_INT);
 			$stm->execute();
 			$pet_org_name = $stm->fetchColumn();
 				
 				
 		}
 	
 		if($res_type =='2')
 		{
 			if($res_code > 0)
 			{
 	
 				$stnm = $db->prepare("select org_name from $schemas.master_licensor where org_code=?");
 				$stnm->bindParam(1, $res_code, PDO::PARAM_INT);
 				$stnm->execute();
 				$res_org_name = $stnm->fetchColumn();
 					
 	
 	
 			}
 		}
 			
 		if($res_type =='4')
 		{
 			if($res_code > 0)
 			{
 				$stmn = $db->prepare("select org_name from $schemas.master_licensee where org_code=?");
 				$stmn->bindParam(1, $res_code, PDO::PARAM_INT);
 				$stmn->execute();
 				$res_org_name = $stmn->fetchColumn();
 					
 			}
 		}

 	
 	}

 	}
 	$hash=base64_encode($item_no);
	 //$hash2 = base64_encode($filing_no.'/'.$schemas); //is used to view page
 $hash2 = base64_encode($item_no.'/'.$filing_no.'/'.$schemas); //is used to view page
 array_push($hash2all,$hash2);
 ?>
<tr>


<td> <?php echo " "."<b>".$counter.'.'."</b>"." ";?>
<?php if ($case_no>0){ echo $bech_code.'/'.htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_no.'/'.$case_year);}else{
echo "Dairy No ".$filing_no;
}
?>
</td>
<td>
<?php 
		echo "<h7><font color='red'><center>";
		if($pet_code >0){echo htmlspecialchars_decode(htmlspecialchars($pet_org_name));}else{echo htmlspecialchars_decode(htmlspecialchars($pt_name));}
	echo "</font><br><font color='blue'>VS</font><br><font color='red'>";
		if($res_code >0){echo htmlspecialchars_decode(htmlspecialchars($res_org_name));}else{echo htmlspecialchars_decode(htmlspecialchars($rs_name));}
	
	echo "</center></font></h7>";
		?>
</td>
<td>
<?php echo "<h7><font><center>".htmlspecialchars($next_list_date)."</center></font>";?>
</td>
<td>
<?php echo "<h7><font><center>".htmlspecialchars_decode($order_tribunal)."</center></font>";?>
</td>
</tr>
<?php 

 //while loop end all query....
$counter++;
 }
 }
  }
?>
</table>
</section>
</div>
</div>
</body>
</html>
<?php
 //}
?>
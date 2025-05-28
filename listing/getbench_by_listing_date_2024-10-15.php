<?php
 /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);   */
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
	$court_no = $_POST['court_no'];

	list($day,$month,$year)=explode('/',$listing_date);
	$court_date_new=$year.'-'.$month.'-'.$day;
	if(!empty($court_no)){
	$sql2=" select * from $schemas.bench where  from_list_date ='$court_date_new' and court_no = '$court_no' order by court_no asc";
	}else{
	$sql2=" select * from $schemas.bench where  from_list_date ='$court_date_new' order by court_no asc";
	}
	
	$bench_d=$db->prepare($sql2);
	$bench_d->execute();
	if($bench_d->rowCount()>0)
	{
		
		 $bench_data=$bench_d->fetchAll();
	 ?>
	<table cellspacing="0" align="center" class="table no-margin table-bordered table-striped table-hover"   cellpadding="2" border="1" width="95%" class="std">	
		<input type="hidden" name="lis_date" value="<?php echo htmlspecialchars($court_date_new);?>" />   
		<tr>
			<th ><font face="Verdana, Arial, Helvetica, sans-serif" >&nbsp;</font></th>
			<th   valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" >Court </font></th>
			<th   valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" >Time </font></th>
			

			<th colspan="6" align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Hon'ble Justice</font></th>
			<th colspan="6" align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Limit </font></th>
			<th colspan="6" align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Avalilable </font></th>
		</tr>
	<?php

		$flag=0;
	$case_limit_avail='0';
		
		foreach($bench_data as $row2)
		{
		
		$bench_code1='';
			
			$flag=1;
			$court_no =$row2['court_no']; 
			$bench_code1 = $row2['bench_no'];
			 $limit_case = $row2['limit_case'];
			 $from_time = $row2['from_time'];
			 $list_flag = $row2['list_flag'];
			 
		$sql1=" select count(*) from $schemas.case_allocation_temp where  listing_date ='$court_date_new' and bench_no=$bench_code1 ";
		$sql1= $db->prepare($sql1);
		$sql1->execute();
		 $counter = $sql1->fetchColumn();
		$case_limit_avail =$limit_case-$counter;	
			?>
			<tr>
			<?php $bench_nocheck = isset($_REQUEST['bench_no']) ? $_REQUEST['bench_no'] : ''; ?>
			<td valign="top"  align="center">
			<input type="radio"  name="bench_no"  class="bench_no"  value="<?php echo $bench_code1;?>" <?php if($bench_nocheck ==$bench_code1)echo 'checked';?> onchange="submitForm();UnSetBg(this);">
			</td>
			<td  valign="top" align="center"><?php  
			$sql2q=" select bench_name from $schemas.bench_nature where  bench_code ='$row2[bench_nature]'";
			$sth11= $db->prepare($sql2q);
			$sth11->execute();
			$sth11->fetchColumn(); ?>
			
			<?php 
				$display_court_value=$db->prepare("select display_court_text  from $schemas.court where court_no = ?");
				$display_court_value->bindParam(1, $court_no, PDO::PARAM_STR);
				$display_court_value->execute();
				$display_court_value = $display_court_value->fetchColumn();
				 echo $display_court_value;
				echo ($list_flag == '2')?' (Supplementry)':' (Daily)';
				?>
			</td>
			<td><b><?php echo $from_time; ?></b></td>
			
	<?php 
	if($bench_no=='')
	{
		$bench_no=0;
		}
		


	?>
			<td colspan="6" align="left">
			<?php
			$arr = array();
			$sql="select bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm where bj.from_list_date ='$court_date_new' and bj.bench_no='$bench_code1' and jm.judge_code=bj.judge_code";
			$sth = $db->prepare($sql);
			$m=0;
			foreach($db->query($sql) as $row)
			{
				$arr[$m]=$row['judge_code'];
				$m++;
			}
			$sql="select presiding from $schemas.bench where from_list_date ='$court_date_new'  and bench_no='$bench_code1'";
			$sth = $db->prepare($sql);
			$sth->execute();
			$presiding = $sth->fetchColumn();
			$arr1 = sizeof($arr);
			for($i=0;$i<$arr1;$i++)
			{
				$jcode =$arr[$i];
				$sql = "select judge_name from $schemas.master_judge where judge_code =$jcode";
				$sth = $db->prepare($sql);
				$sth->execute();
				$judge = $sth->fetchColumn();

				print "<font size='2' ><b>".strtoupper($judge);
				if($jcode == $presiding) print "<font color='red'><b> (PRESIDING JUSTICE )</b></font>";
				print"<br>";
			}echo'</td>';
			
			echo'<td>';
			print "<font size='2' ><b>".strtoupper($limit_case);
			echo'</td>';
			echo'<td colspan="12">';
			print "<font size='2' ><b>".strtoupper($case_limit_avail);
			echo'</td>';
		}
		echo'</tr></table>';
	} 
}

?>
<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 
include("../db_inc1.php");

include '../db_inc2.php';

session_start();
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}
 if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
$sessionUserType=htmlspecialchars($_SESSION['id']);
$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";
$link_scrutiny_idaccess='1';
$schemas=htmlspecialchars($_SESSION['schema_name']);
$entry_date = htmlspecialchars(date("F j, Y g:i a"));


$filing_no=$_REQUEST['filing_no'];
$comment=$_REQUEST['comment'];
$date=$_REQUEST['datetimepicker_mask'];
$hash1=explode(' ',$date);
 $date1=$hash1[0];
 $time=$hash1[1];
echo "</br>";
list($day,$month,$year)=explode('/',$date1);
$notification_date1=$year.'-'.$month.'-'.$day;
$notification_date_all=$_REQUEST['notification_date_all'];
  $action_taken=$_REQUEST['searchby'];
 

   $st11=$db->prepare("update $schemas.regvarify_sevenday set action=?,action_date=? where filing_no =? ");
    $st11->bindParam(1, $action_taken, PDO::PARAM_STR);
	$st11->bindParam(2, $server_date, PDO::PARAM_STR);
	 $st11->bindParam(3, $filing_no, PDO::PARAM_STR);
	 $st11->execute();


  $searchby=$_REQUEST['searchby'];
 $order_of_tribunal=$_REQUEST['order_of_tribunal'];



$st=$db->prepare("select * from e_case_detail_local where filing_no=? ");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$filing_no = ($row['filing_no']);
	$pet_name = ($row['pet_name']);
	$res_name = ($row['res_name']);
	$dt_of_filing = ($row['dt_of_filing']);
	$case_type=($row['case_type']);
	$pet_type=$row['pet_type'];
	$pet_adv=$row['pet_adv'];
	$pet_address=$row['pet_address'];
	$pet_state=$row['pet_state'];
	$pet_district=$row['pet_district'];
	$pet_email=$row['pet_email'];
	$pet_mobile=$row['pet_mobile'];
	$pet_phone=$row['pet_phone'];
	$pet_fax=$row['pet_fax'];
	$res_type=$row['res_type'];
	$res_adv=$row['res_adv'];
	$res_address=$row['res_address'];
	$res_state=$row['res_state'];
	$res_district=$row['res_district'];
	$res_email=$row['res_email'];
	$res_mobile=$row['res_mobile'];
	$res_phone=$row['res_phone'];
	$res_fax=$row['res_fax'];
	$amount=$row['amount'];
	$pet_code=$row['pet_code'];
	$res_code=$row['res_code'];
	$pet_adv_name=$row['pet_adv_name'];
	$res_adv_name=$row['res_adv_name'];
	$pet_pin=$row['pet_pin'];
	$res_pin=$row['res_pin'];
	$payment_status=$row['payment_status'];
	$e_reference_no=$row['e_reference_no'];

}
 if($pet_type =='' OR $pet_type =='0')
{$pet_type='1';}
if($pet_adv =='' OR $pet_adv =='0')
{$pet_adv ='0';}
if($pet_state =='' OR $pet_state =='0')
{$pet_state='0';}
if($pet_district =='' OR $pet_district =='0')
{$pet_district='0';}
if($res_type =='' OR $res_type =='0')
{$res_type='1';}
if($res_adv =='' OR $res_adv =='0')
{$res_adv='0';}
if($res_state =='' OR $res_state =='0')
{$res_state='0';}
if($res_district =='' OR $res_district =='0')
{$res_district='0';}
if($pet_code =='' OR $pet_code =='0')
{$pet_code='0';}
if($res_code =='' OR $res_code =='0')
{$res_code='0';}
if($pet_pin =='' OR $pet_pin =='0')
{$pet_pin='0';}
if($res_pin =='' OR $res_pin =='0')
{$res_pin='0';}

if($searchby=="A")
{
	
$sccc='1';

$st1x =$dbonline->prepare("update e_case_detail set scrutiny_comp = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();


$st1x =$dbonline->prepare("update e_case_detail set scrutiny_comp1 = '1' where filing_no=? ");
$st1x->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1x->execute();

$sccc='1';
$st1x =$db->prepare("update e_case_detail_local set scrutiny_comp = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();


$sccc='1';
$st1x =$db->prepare("update $schemas.scrutiny set scrutinu_comp = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();

$status11='P';
$st1=$db->prepare("select count(filing_no) from  $schemas.case_detail where filing_no =? ");
$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1->execute();
$ccount_filing_no= $st1->fetchColumn();
if($ccount_filing_no =='' OR $ccount_filing_no =='0')
{
	$aa=$db->prepare("insert into $schemas.case_detail(filing_no,case_type,dt_of_filing,pet_type,pet_name,pet_adv,
			pet_address,pet_state,pet_district,pet_email,pet_mobile,pet_phone,
			pet_fax,res_type,res_name,res_adv,res_address,res_state,
			res_district,res_email,res_mobile,res_phone,res_fax,amount_payment,
			pet_code,res_code,pet_adv_name,res_adv_name,pet_pin,res_pin,
			loginid,entry_date,case_year,e_reference_no,status)
			values
			('$filing_no','$case_type','$dt_of_filing','$pet_type','$pet_name','$pet_adv','$pet_address','$pet_state',
			'$pet_district','$pet_email','$pet_mobile','$pet_phone','$pet_fax','$res_type','$res_name','$res_adv',
			'$res_address','$res_state','$res_district','$res_email','$res_mobile','$res_phone','$res_fax','$amount',
			'$pet_code','$res_code','$pet_adv_name','$res_adv_name','$pet_pin','$res_pin','$userid','$cur_date',
			'$rgyear','$e_reference_no','$status11')");
	$aa->execute();
}
}

if($searchby=="D")
{
	
	$sccc='1';
$st1x =$db->prepare("update $schemas.scrutiny set scrutinu_comp = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
//$st1x->execute();
	
$aa1=$db->prepare("insert into $schemas.reg_seven_days_order(filing_no,entry_date,user_id,scrutiny_order,scrutiny_action,display)
			values
			('$filing_no','$server_date','$userid','$order_of_tribunal','$searchby','TRUE')");
	$aa1->execute();	
	$sqlf=$db->prepare("select * from mater_location_city  where schema_name=?");

            $sqlf->bindParam(1, $schemas, PDO::PARAM_STR);
            $sqlf->execute();
			while ($row = $sqlf->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{
           		
			$city_name=$row['city_name'];

		}
		 $sql="select * from $schemas.reg_seven_days_order where filing_no='$filing_no' ";
	$st22=$db->prepare("select * from $schemas.reg_seven_days_order where filing_no=? ");
$st22->bindParam(1, $filing_no, PDO::PARAM_STR);
$st22->execute();
while ($row22 = $st22->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	 $scrutiny_order = ($row22['scrutiny_order']);
}	
		
?>	
<style type="text/css">
	div.hidden {
	display: none;
	}
	</style>

	<script language="javascript">
	function change(id, newClass)
	{
		identity=document.getElementById(id);
		identity.className=newClass;

	}
	function printPage()
	{http://www.novell.com/linux/10.html
		change("testdiv","hidden");
		window.print();
	}


</script>

	<table  border="0" width="80%"   align="center" >
<tr>
	<td  align="left" >
	<div id="testdiv" style="visibility: visible;"><a href="javascript:printPage();"><font size="4" color="red">
Print</font></a></div>
<tr><td colspan="15"><a href="./review_defect_cases.php">
<font face="Verdana" color="red" size="3"><center><b>Home</b></center></font></a>

</td>

</tr>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
<u>IN THE NATIONAL COMPANY LAW TRIBUNAL <?php echo strtoupper($city_name);?></b></u>
</font>
</td>
</tr>
<tr>

<td align="right"><font face="Verdana" size="3">
Diary No.</td>
<td align="left"><?php echo strtoupper($filing_no);?>
</font>
</td>
</tr>
<tr>
<td align="left"><font face="Verdana" size="3">
IN THE MATTER OF:
</font>
</td>
</tr>
<tr>
<td align="center"><font face="Verdana" size="3">
<?php echo strtoupper($pet_name) ;?>
</font>
</td>
<td align="left">
Petitioners
</td>
</tr>
<tr>
<td align="center"><font face="Verdana" size="3">
<?php echo strtoupper($res_name) ;?>
</font>
</td>
<td align="left">
Respondents
</td>
</tr>

<?php 
                      $st2=$db->prepare("select * from e_case_detail_fees where filing_no=? ");
                      $st2->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st2->execute();
$i=0;$r='';
                      while ($row2= $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                          $E_sec_id=$row2['sec_id'];
                       
                      $st3=$db->prepare("select * from master_section_act where id=? ");
                      $st3->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                      $st3->execute();
                      
                      while ($row3 = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {
                      	  $E_add_sec_id=$row3['section_companies'];                      
                           $r.=$E_add_sec_id.',';
                       
                        
                       
                      }
                      
                      }?>
					  <tr>
					  <td>SECTION:
					  
<font face="Verdana" size="3">
<?php  echo rtrim($r,','); ;?>
</font>
</td>
</tr>
 <tr>
					  <td align="right">Order Delivered on:
		</td>
<td align="left">		
<font face="Verdana" size="3">
<?php 
list($year,$month,$day)=explode('-',$server_date);
$server_date1=$day.'/'.$month.'/'.$year;

echo $server_date1;?>
</font>
</td>
</tr>
<tr>
					  <td align="left">For the Applicant(s):<?php ?>
		</td>

</tr>
<tr>
					  <td align="center"><b><u>ORDER</b></u><?php ?>
		</td>

</tr>
<tr>
					  <td align="left"><?php echo $scrutiny_order;?>
		</td>

</tr>
<tr>
					  <td align="right"><br>
					  <br>
					  <br>(Registrar)
		</td>

</tr>
</table>

	<?php
die();	
}
  
 echo $message = 'Notification Send Successfully .....';
 $msg=base64_encode($filing_no);
 $msg1=htmlspecialchars($message.'-'.$msg);
 $hash=base64_encode($msg1);
 header("Location:./review_defect_cases.php?hash=$hash");
 unset($_SESSION['form2_scruniny']);
 die();
 
 }
 

 ?>
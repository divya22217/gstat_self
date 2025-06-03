<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
session_start();
include '../db_inc2.php';
$bench_no='';
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
$benchlocation =htmlentities($_REQUEST['bench_location']);
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	$link_scrutiny_idaccess='1';
	
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 

function gen_in_case_no($in_filing_no){
	global $db;
	global $schemas;
	$get_case_detail_sql = "select case_no,case_type,case_year,location_code,case_type from $schemas.case_detail where filing_no=?";
	$get_case_detail = $db->prepare($get_case_detail_sql);
	$get_case_detail->bindParam(1, $in_filing_no, PDO::PARAM_STR);
	$get_case_detail->execute();
	while ($row_gcd = $get_case_detail->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$case_no=$row_gcd['case_no'];
		$case_type=$row_gcd['case_type'];
		$case_year=$row_gcd['case_year'];
		$location_code =$row_gcd['location_code'];
		$case_type =$row_gcd['case_type'];
	}

		$lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
		$lcode=$db->prepare($lcode);
		$lcode->execute();
		$lcodename = $lcode->fetchColumn();

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
	
	 return $CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_num1aa.'('.$lcodename.')'.$case_year1aa);
}


$sql_prps = "select * from $schemas.master_purpose";
$sth5 = $dbh->prepare($sql_prps);
$sth5->execute();
$purposeAll = $sth5->fetchAll();


?>
<?php 
include '../inheader.php';
include '../insidebar.php';
?>

<script language="javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
  return false;
}
function submitForm()
{
 	with(document.frm)
	{
		
		action = "court_old_listing.php";
		submit();
	}
}
function popsurety_pending_report(cfy)

    {
    	
    		var url = "./case_gen_view.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");   
    		
    }
function submitForm21()
{
 	with(document.frm)
	{
		action = "court_old_listing.php";
		submit();
	}
}
function submitForm1()
{
 	with(document.frm)
	{

	action = "court_old_listing_action.php";
	submit();
	}
}
function un_check()
{
	for (var i = 0; i < document.frm.elements.length; i++)
	{
		var e = document.frm.elements[i];
		if ((e.name != 'allbox') && (e.type == 'checkbox'))
		{
			e.checked = document.frm.allbox.checked;
		}
	}
}
function submitForm2()
{
 	with(document.frm)
	{
	
		if(next_list_date.value=="")
		{
			alert("Please Select Listing Date.");
			next_list_date.focus();
			return false;
		}
		
		var checkboxes = document.getElementsByName('bench_no');

		var selected = [];
		for (var i=0; i<checkboxes.length; i++) {
		if (checkboxes[i].checked) {selected.push(checkboxes[i].value);}
		}
		if(selected=="")
		{
		alert("Please choose From bench ");
		return false;
		}
		var filing_case = $(".checkbox").is(":checked");
		if (filing_case == false)
		{
		        alert("Please select at least one case.");
		        return false;
		}
		
		
		submit();
	}
}

</script>

</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title> COURT OLD CASE LISTING</title>
   
    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    
        <center>COURT OLD CASE LISTING
        </center>
     
    </section>
 <p> <center>All <font color="red">*</font></span> is mandatory Field </center></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">

  <?php 
  
  include '../bfooter1.php';
  ?>
<form name="frm" method="post" action="court_old_listing_action.php" >

<?php

$sql2=" select * from users_cis where id='$sessionUserType' ";
foreach($dbh->query($sql2) as $row)
{

  $user_court=$row['dept'];
}
$msghash=$_REQUEST['msghash'];
if($msghash !='')
{
$msghashz=(base64_decode($msghash));

$msghashz = explode("@", $msghashz);

$msg1 = $msghashz[0];
$case_list_date = $msghashz[1];

list($cyear,$cmonth,$cday)=explode('-',$case_list_date);

	  $case_list_date_dis=$cday.'/'.$cmonth.'/'.$cyear;
}



/*
if($msg1 !='')
{
?>
<tr>
<td colspan="10"><center>

<a href="javascript:popsurety_pending_report('<?php echo htmlspecialchars($case_list_date);?>');" ><b><font color = 'red'  ><?php echo $msg;?> <br>Click Here for view <?php echo $case_list_date_dis ?></font></a></u>
 
</td>
</center>
</tr>
<?php
}
?>
*/
?>

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css"/>
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>





<tr>

<?php 


 $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>	
	
	<td align="left" colspan="15" ><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
<font color="red">*</font>Date of Listing</font>
		<input type="text"  readonly name="next_list_date" id="next_list_date"  maxlength="10" size="10" value="<?php echo  $next_list_date; ?>" onKeyup="javascript:addNumbers(this.value)" onchange="submitForm();UnSetBg(this);" class="datepicker" onFocus="SetBg(this)" >
		<b></b>
		</td>

<?php

if($next_list_date!='')
{
list($day,$month,$year)=explode('/',$next_list_date);
$court_date_new=$year.'-'.$month.'-'.$day;


 $sql2=" select * from $schemas.bench where  from_list_date ='$court_date_new' order by court_no asc";
$bench_d=$dbh->prepare($sql2);
 $bench_d->execute();
if($bench_d->rowCount()>0)
{
	
	 $bench_data=$bench_d->fetchAll();
 ?>

  <input type="hidden" name="lis_date" value="<?php echo htmlspecialchars($court_date_new);?>" />   
<tr>
<th ><font face="Verdana, Arial, Helvetica, sans-serif" >&nbsp;</font></th>
<th   valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" >Court </font></th>

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
		 
		  $sql1=" select count(*) from $schemas.case_allocation_temp where  listing_date ='$court_date_new' and bench_no=$bench_code1 ";
	$sql1= $dbh->prepare($sql1);
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
		$sth11= $dbh->prepare($sql2q);
		$sth11->execute();
		echo $sth11->fetchColumn(); ?>
		<?php echo '<br>Court No : '.$court_no;?>
		</td>
<?php 
if($bench_no=='')
{
	$bench_no=0;
	}
	


?>
		<td colspan="6" align="left">
		<?php
		
		$sql="select bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm where bj.from_list_date ='$court_date_new' and bj.bench_no='$bench_code1' and jm.judge_code=bj.judge_code ";
		$sth = $dbh->prepare($sql);
		$m=0;
		$arr=[];
		foreach($dbh->query($sql) as $row)
		{
			$arr[$m]=$row['judge_code'];
			$m++;
		}
		$sql="select presiding from $schemas.bench where from_list_date ='$court_date_new'  and bench_no='$bench_code1'";
		$sth = $dbh->prepare($sql);
		$sth->execute();
		$presiding = $sth->fetchColumn();
		$arr1 = sizeof($arr);
		for($i=0;$i<$arr1;$i++)
		{
			$jcode =$arr[$i];
			$sql = "select judge_name from $schemas.master_judge where judge_code =$jcode";
			$sth = $dbh->prepare($sql);
			$sth->execute();
			$judge = $sth->fetchColumn();

			print "<font size='2' ><b>".strtoupper($judge);
			if($jcode == $presiding) print "<font color='red'><b> (PRESIDING JUDGE )</b></font>";
			print"<br>";
		}echo'</td>';
		
		echo'<td>';
		print "<font size='2' ><b>".strtoupper($limit_case);
		echo'</td>';
		echo'<td colspan="12">';
		print "<font size='2' ><b>".strtoupper($case_limit_avail);
		echo'</td>';
	}
	echo'</tr>';
}
}
	?>
	
</table>
<br>

<table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">
  </table>
 <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">
  <tr>
  <td><font size="3"><b>
  Priority</b></font>
  <select name="pr_ord">  
  <option value="">Select</option>}  
   <option value="P">Priority</option> 
  </select>
  </td>
  </tr>
  </table>
   <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">

	<tr><th>
	<b>Sr.No.</b></th>
	<th><input type="checkbox" name="allbox" onClick="un_check(this);" title="Select or Deselct ALL" style="background-color:#ccc;"/></th>
	<th align="left" width="150"><b>Diary No.</b></th>
	<th align="left" width="150"><b>Case No.</b></th>
	<th align="left" width="500"><B>Cause Title </b></th>
	<th align="left" width="150"><B>Section</b></th>
<!--	<th align="left" width="150"><B>Last Listing Date</b></th>
	
	<th align="left" width="150"><B>Last Listing Coram</b></th>
	<th align="left" width="150"><B>Last Purpose</b></th>-->
	<th align="left" width="150"><B>List Date</b></th>
	<th align="left" width="150"><B>Next List Date</b></th>
	<th align="left" width="150"><B>Next Purpose</b></th>
	
	
	
	</tr>
	<?php

$count=0;

	if($court_date_new!='')
	{
//echo $sql="select distinct(filing_no) from $schemas.case_allocation_temp where next_list_date='$court_date_new' and listing_date!='$court_date_new' and court_no='$user_court' and listed='0' and list_criteria='N' order by filing_no asc";
$sql1=$db->prepare("select distinct(filing_no) from $schemas.case_allocation_temp where next_list_date='$court_date_new' and listing_date!='$court_date_new' and court_no='$user_court' and listed='0' and list_criteria='N' order by filing_no asc");
$sql1->execute();
}

while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	  $filing_no =$row1['filing_no'];
	
	$get_in_filing_no_sql = "select in_filingno from e_case_detail where filing_no=?";
	$get_in_filing_no = $dbonline->prepare($get_in_filing_no_sql);
	$get_in_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
	//$get_in_filing_no->execute();
	while ($row_gifn = $get_in_filing_no->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		 $in_filingno=$row_gifn['in_filingno'];
	}
	if($in_filingno!=''){
  $in_case_no = gen_in_case_no($in_filingno);
	}
 
  $st3=$dbh->prepare("select max(listing_date) as listing_date from $schemas.case_allocation_temp where filing_no=? ");
                      $st3->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st3->execute();

                      while ($row3= $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                            $old_list=$row3['listing_date'];
						  
					  }


$count++
?>
<tr>
<td>
<?php echo $count;
?>
</td>

<td>
<input class="checkbox" name="checkbox[<?php echo $x?>]" type="checkbox" id="checkbox[<?php echo $x?>]" value="<?php echo $filing_no."/".$old_list; ?>" <?php if($checkbox[$x]==$filing_no)echo 'checked';?> >
</td>

<td>
<?php echo $filing_no;
?>
</td>
<?php 
$st12=$dbh->prepare("select * from $schemas.case_detail where filing_no=? ");
                      $st12->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st12->execute();

                      while ($row12= $st12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                          $case_type=$row12['case_type'];
						  $dt_of_filing=$row12['dt_of_filing'];
						  $case_no=$row12['case_no'];
						  $case_year=$row12['case_year'];
						  $location_code=$row12['location_code'];
						  $pet_name =$row12['pet_name'];
						  $res_name =$row12['res_name'];
						  $case_title=$pet_name." VS ".$res_name;
						
					  
					  $lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
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
$CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_no.'('.$lcodename.')'.$case_year);
?>
<td>
<?php echo $CASE_NO."<br>";
if($in_filingno!=''){
	echo "<center><span>In<br></span></center>";
	echo $in_case_no;
}
?>
</td>
<td>
<?php echo $pet_name.' Vs. '.$res_name;;
?>
</td>
<td>
<?php
 $st2=$dbonline->prepare("select * from e_case_detail_fees where filing_no=? ");
                      $st2->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st2->execute();
$i=0;$r='';
                      while ($row2= $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                          $E_sec_id=$row2['sec_id'];
                       
                      $st3=$dbonline->prepare("select * from master_section_act where id=? ");
                      $st3->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                      $st3->execute();
                      
                      while ($row3 = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {
                      	  $E_add_sec_id=$row3['section_companies'];                      
                           $r.=$E_add_sec_id.',';
                       
                        
                       
                      }
                      
                      }
                      echo rtrim($r,',');

?>
</td>
<?php
$st3=$dbh->prepare("select max(listing_date) as listing_date from $schemas.case_proceeding where filing_no=? ");
                      $st3->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st3->execute();

                      while ($row3= $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                            $old_list=$row3['listing_date'];
						  
					  }
					  
					  
					  
					  $st13=$dbh->prepare("select * from $schemas.case_proceeding where filing_no=? and listing_date=?");
                      $st13->bindParam(1, $filing_no, PDO::PARAM_STR);
					  $st13->bindParam(2, $old_list, PDO::PARAM_STR);
                      $st13->execute();

                      while ($row13= $st13->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {  
							$next_list_purpose='';				  
                           $bench_nature=$row13['bench_nature'];
						   $old_bench_no=$row13['bench_no'];
						   $old_purpose=$row13['purpose'];
						    $next_list_date=$row13['next_list_date'];
							  $next_list_purpose=$row13['next_list_purpose'];
							
						  
					  
					  list($year,$month,$day)=explode('-',$old_list);
					$old_list_view =$day.'/'.$month.'/'.$year;	
					  list($year1,$month1,$day1)=explode('-',$next_list_date);
					 $next_list_date_view =$day1.'/'.$month1.'/'.$year1;	
?>
<!--<td>-->
<?php //echo $old_list_view;?>
<!--</td>-->
<!--<td>-->
<?php

/*
  $stat="select * from $schemas.bench where  bench_nature ='$bench_nature' and from_list_date='$old_list' and  bench_no='$old_bench_no'"; 

$stat=$db->prepare("$stat");

$stat->execute();
while ($row = $stat->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$bench_no= $row['bench_no'];
$court_no =$row['court_no'];
$presiding=$row['presiding'];
if($bench_no!='')
{
$stat1="select *  from $schemas.master_judge where judge_code ='$presiding'";
$stat1 = $db->prepare($stat1);
$stat1->execute();
$presiding1 = $stat1->fetch();
$gen=$presiding1['gen']."&nbsp";
echo  $presiding1['judge_name'];?>
<?php
$hon_text=$presiding1['hon_text'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$presiding1[judge_desg_code], PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ". $desg_name = $stat1->fetchColumn();

}




?><font color="Red"><b> (Presiding) </b></font>
<?php
}
 $stat2="select distinct(judge_code) as judge_code from $schemas.bench_judge where bench_nature='$bench_nature' and from_list_date='$old_list' and judge_code !='$presiding' and bench_no='$old_bench_no'";
$stat2=$db->prepare("$stat2");
//$stat->bindParam(1, $display, PDO::PARAM_STR);
$stat2->execute();
while ($row2 = $stat2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

$judge_code =$row2['judge_code'];
$stat1="select judge_name,judge_desg_code,gen,hon_text from $schemas.master_judge where judge_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$judge_code, PDO::PARAM_STR);
$stat1->execute();
 $judge_data = $stat1->fetch();
$gen = $judge_data['gen'];
 $hon_text = $judge_data['hon_text'];

  
echo $gen." ".$judge_name = $judge_data['judge_name'];
$desg_code = $judge_data['judge_desg_code'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$desg_code, PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ".$desg_name = $stat1->fetchColumn();
}

 */
 
 ?>
<!--</td>-->
<!--
<td>-->
<?php 
/*
$display='1';
$ref_stQ2 = $db->prepare("select purpose_name from $schemas.master_purpose where purpose_code = ? and display=?");
$ref_stQ2->bindParam(1, $old_purpose, PDO::PARAM_STR);
$ref_stQ2->bindParam(2, $display, PDO::PARAM_STR);
$ref_stQ2->execute();
echo $purpose_short_name=$ref_stQ2->fetchColumn();
*/

?>
<!--</td>-->
<td>
<?php echo $old_list;
?>
</td>
<td>
<?php echo $next_list_date_view;



?>
</td>


		<td>

<select name="purpose_id[<?php echo $filing_no?>]" style="width:200px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<?php  /* if($purpose_id=='')
	{ $purpose_id=$next_list_purpose; }   */?>
<?php
 $sql2=" select * from $schemas.master_purpose  ";
  if($next_list_purpose == 0 || $next_list_purpose == ''){
	echo "<option value='0'>Select</option>";
 } 
foreach($dbh->query($sql2) as $row)
{
  
  $purpose_code=$row['purpose_code'];
  //echo $purpose_code."    ".$purpose_id;
  //print "<option value=".htmlentities(htmlspecialchars($row['purpose_code']))."  ".($purpose_code == $purpose_id)?'selected':''.">".strtoupper(htmlentities(htmlspecialchars($row['purpose_name'])))."</option>";
  if($next_list_purpose == $purpose_code)
                {
		print "<option value=".htmlentities(htmlspecialchars($row['purpose_code']))." selected>".strtoupper(htmlentities(htmlspecialchars($row['purpose_name'])))."</option>";
                }
           else
                {
                print "<option value=".htmlentities(htmlspecialchars($row['purpose_code'])).">".strtoupper(htmlentities(htmlspecialchars($row['purpose_name'])))."</option>";
		} 
 }
					
 ?>

</td>
</tr>		
				</td>

</tr>




					  <?php
					  }
}  }
  }
						   ?>
</table>
  <table cellspacing="0" align="center" cellpadding="2" border="1" width="95%" class="std">

<?php 

if($bench_code1!='')
{
?>
<tr><td colspan="8" align="center"><br><br>
	<input type="submit" name="submit1" value="CASE FOR LIST" class="button" onClick="return submitForm2();"></td></tr>
	<?php 
}
?>
<script src="../src/calendar.js"></script>
  
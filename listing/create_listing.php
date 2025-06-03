<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
$notification_date=$_REQUEST['notification_date'];

include("../db_inc1.php");
session_start();
include '../db_inc2.php';
   
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

$flag =htmlentities($_REQUEST['flag']);
$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
/*
	if($main_id =='9999' and $localadmin =='0')
	{
		if($_SESSION['menuaccess_codeall'] !='3')
		{
			echo "Access Problem.....";
			header("Location: ../index.php");
			die();
		}
	}*/
	
	

	// This code not use next time .......	Schema session create Hear....


	$sessionUserType=htmlspecialchars($_SESSION['id']);


	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";


	$link_scrutiny_idaccess='1';

?>
<?php 
include '../inheader.php';
include '../insidebar.php';


?>

<?php 

$form2 = sha1( uniqid('auth', true) );
$_SESSION['form2_scruniny'] = $form2;
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
     
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><b>

<?php
if($flag =='')
{
echo "Fresh Case Allocation";
}
if($flag ==1)
{
list($year1,$month1,$day1)=explode('-',$notification_date);
	 $ndate =$day1.'-'.$month1.'-'.$year1;
echo "Fresh cases alloted as per bench limit for date:".$ndate ;
}
?>
</b>

<h3>
        <?php 
        		$hash=$_REQUEST['hash'];
        		
        		if($hash !='')
        		{
        			$hash1=htmlspecialchars(base64_decode($hash));
        			        			
        			echo "<center></br><font color='red' size='4'>".htmlspecialchars($hash1).'</br></font>';
        		}
        		
        ?>
</h4>

        
        </div>
              <script>
       function defect_submit()
       {
       	//validate3();
       	
        	with(document.form2)
       	{	/*
        		var validformat=/^\d{2}\/\d{2}\/\d{4}$/ 
        			if (!validformat.test(notification_date.value))
        			{	
        			alert("Invalid Date Format. Correct Date Format (dd/mm/yyyy)")
        			return false; 
        			}
        		*/
        		//alert('self submit');	
       	 action = "create_listing.php";
       	submit();
     	document.form2.submit_final.disabled = true;  
      	document.form2.submit_final.value = 'Please Wait...';  
      	return true;
       	}
       }
       </script>


<?php
if($flag ==1)
{
$srno =1;
?>
<div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead style="border: 1;background-color: silver;">
                  <tr>
                  <th width="10%"><b>Sr No.</th>
                    <th width="20%"><b>Diary No.</th>
                   
                    <th width="20%"><b>Case Number</th>
                    <th width="40%"><b>Title</th>
          <th width="10%"><b>Court No</th>
                     
                     
                        
                  
                  </tr>
                  </thead>
                  <tbody >


<?php

//create limit for case allocation
$notification_date=$_REQUEST['notification_date'];
$st="truncate $schemas.bench_wise_allocation";
$db->query($st);
$sql="select bench_no,limit_case,bench_nature,court_no,id,location_code from $schemas.bench where from_list_date='$notification_date'";
foreach($db->query($sql) as $row)
	{	
        $location_code =$row['location_code'];
	$bench_no =$row['bench_no'];
        $case_limit =$row['limit_case'];
	$bench_nature =$row['bench_nature'];
	$court_no       =$row['court_no'];
	$id =$row['id'];
        $sql1=$db->prepare("select count(*) as count from $schemas.case_allocation_temp where listing_date = '$notification_date' and bench_no='$bench_no'");
    $sql1->execute();
    $counter = $sql1->fetchColumn();
  $case_limit =$case_limit-$counter;
    $sql2="insert into $schemas.bench_wise_allocation(listing_date,bench_no,counter,case_limit,bench_nature,court_no,id,location_code) values('$notification_date','$bench_no','0','$case_limit','$bench_nature','$court_no','$id','$location_code')";
    $db->query($sql2) or die("not inserted");
	}
//end of data creation for bench allocation


//start of case allocation
$serial=1;
$newst="select filing_no,case_type,pet_name,res_name from $schemas.case_detail where legal_aid is null and case_no IS NULL and case_year='' order by filing_no asc";
foreach($db->query($newst) as $newrow)
{
 $freshfiling_no =$newrow['filing_no'];
$case_type =$newrow['case_type'];
$pet_name =$newrow['pet_name'];
$res_name=$newrow['res_name'];
$newst1="select min(counter) as counter from $schemas.bench_wise_allocation where case_limit>0 and listing_date='$notification_date'";
$newst1=$db->prepare($newst1);
$newst1->execute();
$mincounter = $newst1->fetchColumn();

$newst2="select id from $schemas.bench_wise_allocation where case_limit>0 and listing_date='$notification_date' and counter ='$mincounter'";
$newst2=$db->prepare($newst2);
$newst2->execute();
$minid = $newst2->fetchColumn();
if($minid >0)
{
$newst3="select bench_no,bench_nature,court_no,location_code,case_limit,counter from $schemas.bench_wise_allocation where id='$minid'";
$newst3=$db->prepare($newst3);
$newst3->execute();
$resultset = $newst3->fetch();
extract($resultset);



 /*$ref_newst="select filing_no from $schemas.case_detail where oa_ref_no='$freshfiling_no'";
$ref_newst=$db->prepare($ref_newst);
$ref_newst->execute();
$ref_resultset2 = $ref_newst->fetch();
extract($ref_resultset2);
echo $filing_no;


if($filing_no !='')
{
$ref_sql1="select bench_no,court_no,bench_nature,listing_date from $schemas.case_allocation where filing_no ='$filing_no'";
$ref_sql1=$db->prepare($ref_sql1);
$ref_sql1->execute();
$ref_sql1 = $ref_sql1->fetch();
extract($ref_sql1);





 $ref_insert ="insert into $schemas.case_allocation(filing_no,listing_date,purpose,entry_date,deal_cd,connected,priority_serial,bench_nature,bench_no,
		 court_no,id,list_flag) values('$filing_no','$notification_date','12','$cur_date','$sessionUserType','N','$serial','$bench_nature','$bench_no','$court_no','$id','1' )";
$db->query($ref_insert) or die("not inserted");
$serial++;
$counter =$counter + 1;



}
else
{*/

  $insert ="insert into $schemas.case_allocation_temp(filing_no,listing_date,purpose,entry_date,deal_cd,connected,priority_serial,bench_nature,bench_no,
		 court_no,id,list_flag,listed) values('$freshfiling_no','$notification_date','12','$cur_date','$sessionUserType','N','999','$bench_nature','$bench_no','$court_no','$id','1','1')";


$db->query($insert) or die("not inserted");
$serial++;
$counter =$counter + 1;
/*}
*/
$newst4=" update $schemas.bench_wise_allocation set counter ='$counter' where id ='$minid'";
$db->query($newst4) or die("error");
$case_limit =$case_limit -1;


$newst5=" update $schemas.bench_wise_allocation set case_limit ='$case_limit' where id ='$minid'";
$db->query($newst5) or die("error");

$newst5=" update $schemas.bench set available_quota ='$case_limit' where id ='$minid'";
$db->query($newst5) or die("error");


//case allocation completed


//generation of case number 


date_default_timezone_set("Asia/Kolkata");

$server_date= date('d-m-Y'); //Returns IST 
if($server_date !='')
{
	list($day,$month,$year)=explode('-',$server_date);
	 $reg_year_server=$year;	
}
$regis_date =$year.'-'.$month.'-'.$day;
$regis_date11 =$day.'/'.$month.'/'.$year;

$newst6="select reg_no from $schemas.case_type_reg where case_type='$case_type' and reg_year=' $reg_year_server' and location_code='$location_code' ";
$newst6=$db->prepare($newst6);
$newst6->execute();
$regis_no = $newst6->fetchColumn();
if($regis_no==0)
{
$regis_no=1;
}
else
{
$regis_no++;
}



$newst7 ="update $schemas.case_detail set case_no ='$regis_no',case_year ='$reg_year_server',case_type='$case_type',location_code ='$location_code',regis_date='$regis_date' where filing_no='$freshfiling_no' ";
$db->query($newst7) or die("case no not updated");
		
  $newst8 ="update $schemas.case_type_reg set reg_no ='$regis_no'  where case_type='$case_type' and location_code='$location_code' and reg_year='$reg_year_server'";
  $db->query($newst8) or die("filing counter not updated");
	 
	 
	 
	 

  $newst7 ="update $schemas.case_detail set legal_aid ='A',location_code ='$location_code' where filing_no='$freshfiling_no' ";

 $db->query($newst7) or die("case no not updated");

?>
<tr>
<td>
<?php 
echo $srno++;
?>
</td>
<td>
<?php
echo $filing_no= $freshfiling_no;
?>
</td>


<?php

$check_sql =$dbonline->prepare("select rep_code from e_more_representative where filing_no =? and party_flag='P' and party_serial_no='1'");
$check_sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$check_sql->execute();
 $pet_adv_code= $check_sql->fetchColumn();
 

if($pet_adv_code=='')
{
	$pet_adv_code='0';
}

if($pet_adv_code >0)
{
$stqq12 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq12->execute();
$pet_adv_name = $stqq12->fetchColumn();	

 
$stqq12 = $dbonline->prepare("select email from e_master_advocate where id=?");
$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq12->execute();
$pet_adv_email = $stqq12->fetchColumn();	
$stqq12 = $dbonline->prepare("select mobile from e_master_advocate where id=?");
$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq12->execute();
$pet_adv_mobile = $stqq12->fetchColumn();	
	
}
$adv_party='R';
$adv_party_serial='1';
$check_sql1 =$dbonline->prepare("select rep_code from e_more_representative where filing_no =? and party_flag=? and party_serial_no=?");
$check_sql1->bindParam(1, $filing_no, PDO::PARAM_STR);
$check_sql1->bindParam(2, $adv_party, PDO::PARAM_STR);
$check_sql1->bindParam(3, $adv_party_serial, PDO::PARAM_STR);
$check_sql1->execute();
$res_adv_code= $check_sql1->fetchColumn();
if($res_adv_code=='')
{
	$res_adv_code='0';
}
if($res_adv_code >0)
{
$stqq121 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
$stqq121->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq121->execute();
$res_adv_name = $stqq121->fetchColumn();	

 
$stqq122 = $dbonline->prepare("select email from e_master_advocate where id=?");
$stqq122->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq122->execute();
 $res_adv_email = $stqq122->fetchColumn();	
$stqq123 = $dbonline->prepare("select mobile from e_master_advocate where id=?");
$stqq123->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq123->execute();
$res_adv_mobile = $stqq123->fetchColumn();	
	
}
$pet_flag='P';
$pet_serial='1';
$sthr2=$dbonline->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=? ");
  $sthr2->bindParam(1, $filing_no, PDO::PARAM_STR);
  $sthr2->bindParam(2, $pet_flag, PDO::PARAM_STR);
  $sthr2->bindParam(3, $pet_serial, PDO::PARAM_STR);
  $sthr2->execute();
  while ($row1 = $sthr2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$pet_email=$row1['email'];
	$pet_mobile=$row1['mobile'];
	$pet_name1=$row1['name'];
  }
$res_flag='R';
$res_serial='1';
  $sthr3=$dbonline->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=?");
  $sthr3->bindParam(1, $filing_no, PDO::PARAM_STR);
  $sthr3->bindParam(2, $res_flag, PDO::PARAM_STR);
  $sthr3->bindParam(3, $res_serial, PDO::PARAM_STR);
  $sthr3->execute();
  while ($row3 = $sthr3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$res_email=$row3['email'];
	$res_mobile=$row3['mobile'];
	$res_name1=$row3['name'];
  }

$lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
$lcode=$db->prepare($lcode);
$lcode->execute();
$lcodename = $lcode->fetchColumn();



$lcode1 ="select short_name from case_type where id ='$case_type'";
$lcode1=$db->prepare($lcode1);
$lcode1->execute();
$case_type_short_name = $lcode1->fetchColumn();

$CASE_NO22 = htmlspecialchars(strtoupper($case_type_short_name).'/'.$regis_no.'('.$lcodename.')'.$reg_year_server);

$subject="Your Case Number Generated under diary no ".$filing_no;
$email_text="Case Number Generated under diary no ".$filing_no." is ".$CASE_NO22." on date ".$regis_date11." This is a computer generated message, Please do not reply "  ;

$msg555="Case Number Generation under diary no ".$filing_no." is ".$CASE_NO22." on " .$regis_date11;

 //$sql ="insert into sms(filing_no,case_number,msg,pet_adv_name,pet_adv_mob_no,pet_adv_email,pet_mobile,res_mobile,pet_name,res_name,res_adv_code,pet_adv_code,res_adv_name,res_adv_mob_no,subject,email_text,res_adv_email,pet_email,res_email,send_flag,entry_date,sms_flag) 
//VALUES('$filing_no','$CASE_NO22','$msg555','$pet_adv_name','$pet_adv_mobile','$pet_adv_email','$pet_mobile','$res_mobile','$pet_name','$res_name','$res_adv_code','$pet_adv_code','$res_adv_name','$res_adv_mobile','$subject','$email_text','$res_adv_email','$pet_email','$res_email','0','$regis_date','G')";

//$st = $dbonline->prepare($sql);
//$st->execute();   
	   

?>
<td>
<?php
echo $CASE_NO22;
?>
</td>



<td>
<?php
echo $pet_name.' Vs. '.$res_name;
?>
</td>

<td>
<?php
echo $court_no;
?>
</td>
</tr>
<?php


}


}

}

?>  

</tbody>
       <form name="form2" method="post" action="create_listing.php" >
       <input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2);?>" />   
 

 <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead style="border: 1;background-color: silver;">
                  
				  
				  <tr>
                  <th width="10%"><b>Sr No.</th>
                    <th width="20%"><b>Diary No.</th>
                   
                    <th width="40%"><b>Title</th>
                    <th width="30%"><b>Section</th>
                     
                        
                  
                  </tr>
                  </thead>
				  
                  <tbody >
				  
                   <tr><td colspan="6">

<input type="hidden" name ="flag" value ="1">
  <?php 
  //$st=$db->prepare("select count(*) from $schemas.case_detail where case_no is NULL");
   
   $st=$db->prepare("select count(*) from $schemas.case_detail  where legal_aid IS NULL and case_no IS NULL and case_year=''");
   $st->execute();
$filing_no_found = $st->fetchColumn();


  if($filing_no_found =='' OR $filing_no_found =='0')
  {
  
  	echo "</br><font color='red'><b>No record available for listing !!!</b></font>";

	
  	//die();
  
  }
  ?>
  
  </td></tr>
                  
      <?php 
      if($filing_no_found !='0')
      {
      //$st=$db->prepare("select * from $schemas.case_detail where  case_no is null ");
	  
	 
	   $st=$db->prepare("select * from $schemas.case_detail where  legal_aid IS NULL and case_no IS NULL and case_year='' ");
      $st->execute();
      $SER_COUT=1;
      while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
      {
          $filing_no_check = htmlspecialchars($row['filing_no']);
          $st1=$db->prepare("select * from $schemas.case_detail where filing_no=?");
          $st1->bindParam(1, $filing_no_check, PDO::PARAM_STR);
          $st1->execute();
          while ($row1 = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                {
              $pet_name = htmlspecialchars($row1['pet_name']);
              $res_name = htmlspecialchars($row1['res_name']);
              $dt_of_filing = htmlspecialchars($row1['dt_of_filing']);
              $case_type=htmlspecialchars($row1['case_type']);
                 }
                  ?>              
                <tr>
                  <td><?php echo htmlspecialchars($SER_COUT);?></td>
                    <td><?php echo htmlspecialchars($filing_no_check);?></td>
                    <td><?php echo htmlspecialchars_decode(strtoupper($pet_name)).'&nbsp; VS &nbsp;'.htmlspecialchars_decode(strtoupper($res_name));?></td>
                    
                    <td>
                      <div class="sparkbar" data-color="#00a65a" data-height="20">
                      <?php 
                      $st2=$dbonline->prepare("select * from e_case_detail_fees where filing_no=? ");
                      $st2->bindParam(1, $filing_no_check, PDO::PARAM_STR);
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
                  </tr>
                 
                  <input type="hidden" name="filing_no[]" value="<?php echo htmlspecialchars(htmlentities($filing_no));?>"/>  
  
                     <?php 
                    $SER_COUT++;
                    }

$stq="select min(from_list_date) from $schemas.bench where from_list_date > '$cur_date' and limit_case >0";
                    $madate_sql=$db->prepare("$stq");

       		//$madate_sql->bindParam(1, $cur_date, PDO::PARAM_STR);
 		$madate_sql->execute();
		$min_list_date = $madate_sql->fetchColumn();
?>     
<input  type="hidden" name="notification_date" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($min_list_date));?>" size="10" maxlength="10"/>
&nbsp;&nbsp;



<?php if($min_list_date !='' and $flag=='')
{

 $st="select max(available_quota) as max_limit from $schemas.bench where from_list_date='$min_list_date'";
$st=$db->prepare($st);
 		$st->execute();
		$max_limit = $st->fetchColumn();


?>
<tr>
<td colspan="3"><center>


<?php

if($max_limit>0)
{
?>
<input id="in_go" type="submit" name="submit_final"
class="button" value="Click to list fresh cases to next listing date : <?php 

list($year,$month,$day)=explode('-',$min_list_date);
$nd=$day.'/'.$month.'/'.$year;


echo $nd; ?>" style="
    background-color: green;
    color: #FFFFFF;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onClick="defect_submit();"/> 

<?php
}

?>
<?php if($min_list_date =='' and $flag =='')
{
?>

<?php
}
}

if($max_limit==0)
{
?>
<td colspan="4">
<script>
function openPopUp(url)
{
window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");	
}
</script>
<a href="#"onclick="javascript:openPopUp('create_bench.php');"><b><font color='red'><center>Create Bench for next listing date</center></font></b></a>
</td>

<?php
}

?>
</center>
 </td>
	 </tr>
  
   <?php 
      }
      //filing no is not 0
     ?>        
	 </tbody>

                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            </form>

      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
  <?php 
  include '../infooter.php';
  ?>
  <?php } ?>

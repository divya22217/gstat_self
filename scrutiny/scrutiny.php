<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();

$_SESSION['user'];
$location_access=$_SESSION['location'];

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


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{

	
	if($main_id =='9999' and $localadmin =='0')
	{
		if($_SESSION['menuaccess_codeall'] !='2')
		{
			echo "Access Problem.....";
			header("Location: ../index.php");
			die();
		}
	}

	// This code not use next time .......	Schema session create Hear....


	$sessionUserType=htmlspecialchars($_SESSION['id']);


	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";

 
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
  <?php 
        $hash=htmlspecialchars($_REQUEST['hash']);
        if($hash !='')
        {
        	$hash1=htmlspecialchars(base64_decode($hash));
        	$hash1 = explode("/", $hash1);
        	$massage=$hash1[0];
        	
        	$token_filing_no_scrutiny= $hash1[1];
        
        ?>    
           <div class="alert alert-success" role="alert">
  <?php echo htmlspecialchars($massage);?>
</div> 
        <?php } ?> 
        
      
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Case Scurtiny</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
         
 <div class="form-group">
				 <div class="col-md-6">
             
       <script>
       function submitForm()
       {
        	 with(document.form2)
       	{
        		 if(tokenno.value == "0")
        			{
        				alert("Select Dairy No !!!!!");
        				tokenno.focus();
        				return false;
        			}	
        	action = "scrutiny.php";
       	submit();
       	} 
       }

       </script>       
              <script>
       function defect_submit()
       {
       	//validate3();
       	
        	with(document.form2)
       	{	
        		var validformat=/^\d{2}\/\d{2}\/\d{4}$/ 
        			if (!validformat.test(notification_date.value))
        			{	
        			alert("Invalid Date Format. Correct Date Format (dd/mm/yyyy)")
        			return false; 
        			}

        		if(searchby.options[searchby.selectedIndex].value == "0")
         		{
         			alert("Please Select Defect/ Defect Free  ");
         			searchby.focus();
         			return false;
         		}

        		
        		//alert('self submit');	
       	 action = "scrutiny_action.php";
       	submit();
       	document.form2.submit_final.disabled = true;  
      	document.form2.submit_final.value = 'Please Wait...';  
      	return true;
       	}
       }
       </script>   
               <script>
function myFunction() {

	with(document.form2)
   	{			
   	 action = "scrutiny.php";
   	submit();
   	}
    
}
</script>     
                   
  <form name="form2" method="post" action="scrutiny_action.php" >
   <table width="100%" >
   <tr><td colspan="6">
  <input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2);?>" />
                <label>Dairy No</label>
                 <?php  $tokenno = isset($_REQUEST['tokenno']) ? $_REQUEST['tokenno'] :'';?>
                <select class="form-control select2" name="tokenno" style="width: 60%;">
                  <option selected="selected" value="0">Select</option>
                                    
						<?php

 $status ='0';
 $ll='0';
     $st=$db->prepare("select * from $schemas.scrutiny where scrutinu_comp=? and level_level= ?");
              $st->bindParam(1, $status, PDO::PARAM_STR);
              $st->bindParam(2, $ll, PDO::PARAM_STR);
             $st->execute();
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))


	  {
		$btype = htmlspecialchars($row['filing_no']);
	   if($tokenno == $btype)
                {
				print "<option value=".htmlspecialchars($row['filing_no'])." selected>".htmlspecialchars($row['filing_no'])."</option>";
                }
        else
                {
                print "<option value=".htmlspecialchars($row['filing_no']).">".htmlspecialchars($row['filing_no'])."</option>";
				}
	  }
?>


                </select>
        <button type="button" class="btn btn-info pull-right" name="go" id="go"
         value="Search !!!" onclick="javascript:submitForm();"> SEARCH </button>   
     </td></tr>    
  
    
			<tr><td colspan="6">
			<?php 
			
			$ll1='0';
			$ll='Y';
			$stqq = $db->prepare("select count(filing_no) from $schemas.scrutiny where filing_no=? and level_level=? and defects=?");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_INT);
			$stqq->bindParam(2, $ll1, PDO::PARAM_INT);
			$stqq->bindParam(3, $ll, PDO::PARAM_INT);
			$stqq->execute();
			$filing_no_foundd = $stqq->fetchColumn();
		if($filing_no_foundd > '0')
			{
			    echo "<center><font color='red'><br></br><b>Scrutiny Already Completed With Defacts !!!</b></font>";
			   echo "</br><font color='red'><br></br><b><a href='../scrutiny/remove_defacts.php'>ClICK HEAR TO REMOVE DEFACTS</a></b></font></br></center>";
			     
			}
			$ll='1';
			$stqq = $db->prepare("select count(filing_no) from $schemas.scrutiny where filing_no=? and level_level =? and defects ='N'");
			$stqq->bindParam(1, $tokenno, PDO::PARAM_INT);
			$stqq->bindParam(2, $ll, PDO::PARAM_INT);
			$stqq->execute();
			$filing_no_foundx = $stqq->fetchColumn();
			
			
			if($filing_no_foundx !='')
			{
			     
			    echo "<font color='red'><br></br><b>Scrutiny Already Completed !!!</b></font>";
			    //	die();
				  
			}
	  $ll1='0';
	  $ll='Y';
	  $stqq = $db->prepare("select count(filing_no) from $schemas.scrutiny where filing_no=? and level_level=?");
	  $stqq->bindParam(1, $tokenno, PDO::PARAM_INT);
	  $stqq->bindParam(2, $ll1, PDO::PARAM_INT);
	  $stqq->execute();
	  $filing_no_found = $stqq->fetchColumn();
	  
	  
	if($filing_no_found =='0')
	{
	  $status='P';
	  
	  $st=$db->prepare("select * from e_case_detail where filing_no=? and location_id=? ");
	  $st->bindParam(1, $tokenno, PDO::PARAM_STR);
	  $st->bindParam(2, $location_access, PDO::PARAM_STR);
	}
	if($filing_no_found !='0')
	{
		$status='P';
		$st=$db->prepare("select * from e_case_detail_local where filing_no=? and location_id=? ");
		$st->bindParam(1, $tokenno, PDO::PARAM_STR);
		$st->bindParam(2, $location_access, PDO::PARAM_STR);
	}
	  $st->execute();
	  while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	  {
	  	$filing_no_cc = htmlspecialchars($row['filing_no']);
	  	$pet_name = htmlspecialchars($row['pet_name']);
	  	$res_name = htmlspecialchars($row['res_name']);
	  	$dt_of_filing = htmlspecialchars($row['dt_of_filing']);
	  	$case_type=htmlspecialchars($row['case_type']);
	  	//$case_no=$row['case_no'];
	  	//=$row['location_id'];
	  }

  
	  
?>
			</td></tr>
        </table>
 <?php 
 	$ll='0';
 	$ll1='Y';
 $stqq = $db->prepare("select scrutinu_comp,defects,notification_date from $schemas.scrutiny where filing_no=? and level_level=? ");
 $stqq->bindParam(1, $tokenno, PDO::PARAM_INT);
 $stqq->bindParam(2, $ll, PDO::PARAM_INT);
 $stqq->execute();
 while ($row = $stqq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
 	$scrutinu_comp_q = htmlspecialchars($row['scrutinu_comp']);
 	$defects_q = htmlspecialchars($row['defects']);
 	$notification_date=$row['notification_date'];
 }
 if($scrutinu_comp_q =='0' and $defects_q =='')
 {
 ?>
	
<tr bgcolor="#CCCCCC">

<td colspan="4" align="left">

<font color="#0000FF" size="4">
      <?php if($pet_name!=''){echo htmlspecialchars_decode(strtoupper($pet_name));} ; ?></font>
      <font color="#FF0000" size="4">&nbsp;&nbsp;Vs&nbsp;&nbsp;</font><font color="#0000FF" size="4">
      <?php if($res_name !=''){echo htmlspecialchars_decode(strtoupper($res_name));} ; ?></font>

</td>

</tr>
<tr>
    <td colspan="8">
    <style>
.tbl-accordion {
  margin: 0 auto;
  width: 900px;
  border: 1px solid #d9d9d9;
}
.tbl-accordion thead {
  background: #d9d9d9;
}
.tbl-accordion .tbl-accordion-nested {
  width: 100%;
}
.tbl-accordion .tbl-accordion-nested tr:nth-child(even) {
  background-color: #eeeeee;
}
.tbl-accordion .tbl-accordion-nested td, .tbl-accordion .tbl-accordion-nested th {
  padding: 10px;
  border-bottom: 1px solid #d9d9d9;
}
.tbl-accordion .tbl-accordion-nested .tbl-accordion-section {
  background: #333;
  color: #fff;
  cursor: pointer;
}
  </style>
  
  <script>
  $('.tbl-accordion-nested').each(function(){
	  var thead = $(this).find('thead');
	  var tbody = $(this).find('tbody');
	  
	  tbody.hide();
	  thead.click(function(){
	    tbody. slideToggle();
	  })
	})
	
  </script>
  
<table cellpadding="0" cellspacing="0" class="tbl-accordion">
<tbody>
    <tr>
      <td colspan="3">
        <table cellpadding="0" cellspacing="0" class="tbl-accordion-nested">
         <thead>
            <tr>
              <td colspan="3" class="tbl-accordion-section">Documents</td>
            </tr>
          </thead>
          <tbody>
            <tr>
            
              <td>

   <?php 
   $stqq = $db->prepare("select count(filing_no) from document_upload  where filing_no=?");
   $stqq->bindParam(1, $tokenno, PDO::PARAM_INT);
   $stqq->execute();
   $filing_norevari = $stqq->fetchColumn();
   if($filing_norevari == '0' OR $filing_norevari == '')
   {
       echo "<center><font color='red' size='3'>Document Not Uploaded ....</font></center>";
   }
   if($filing_norevari > '0')
   {
  $sthr=$db->prepare("select * from document_upload  where filing_no=? ");
  $sthr->bindParam(1, $tokenno, PDO::PARAM_STR);
  $sthr->execute();
  while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$fileupload=$rowa['fileupload'];
  	$subdoctype=$rowa['subdoctype'];
  	
  	$sthrm=$db->prepare("select * from e_sub_document  where sub_doc_id=? order by sub_doc_id ASC ");
  	$sthrm->bindParam(1, $subdoctype, PDO::PARAM_STR);
  	$sthrm->execute();
  	while ($rowa = $sthrm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  	{
  			$sub_doc_type=$rowa['sub_doc_type'];
  	}
  	 	
  ?>
 
  <a href="<?php echo $fileupload; ?>" target="_blank"><font color="#900C3F" size="3">&nbsp;&nbsp;<?php echo htmlspecialchars($sub_doc_type);?>
  &nbsp;&nbsp;</a>
  </font>
<?php }} ?>

</td>

</tr>
</tbody>
</table>
</td></tr>
</table>
  </td>
</tr>
	
 </div>
 </div>
   </div>
 <div class="box-footer">
    
		 <table border="1" >
  <tr><td colspan="12"></td></tr>
  <tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
  <td width="3%">Sr. No</td><td width="67%">Description</td><td width="6%">Defect Free</td><td width="16%">Comments</td>
  </tr>
 <tr><td colspan="12">
 
    <?php 
    $status=$_REQUEST['status'];
$filing_no=$tokenno;
 ?>
 
 <?php 
 
  
  $display='TRUE';

  $sth=$db->prepare("select * from check_list_local where location_all=?  order by id ASC ");
  $sth->bindParam(1, $location_access, PDO::PARAM_STR);
$sth->execute();
$i=0;
$j=1;
while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$id_check=$rowa['id'];
 $check_list=$rowa['check_list'];
?> 
  <tr>
 
  <td width="5%">
 
  <?php echo htmlspecialchars($id_check);?>
   
  </td>

  
  <td width="60%" ><font color=" #1c2833 "><?php echo htmlspecialchars($check_list);?></font>
  
  <?php 
  if($status[$i]=='NO')
  {
  	$dhiraj ="OK";
  }
  
  ?>
 
  <td width="7%">

 
				 <select name="status[]" style="
    background-color: silver;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onchange="myFunction()" >
 				 	<option value="YES" <?php  if($status[$i]=='YES') echo "selected"; ?> >YES</option>
 				 	<option value="NO" <?php  if($status[$i]=='NO') echo "selected"; ?> ><font color="red">NO</font></option>
 				 	
 				 	<option value="OTHER" <?php  if($status[$i]=='OTHER') echo "selected"; ?>>OTHER</option>
                 </select>           
  
  <td width="20%">
  
   <textarea name="comment[]" onChange="makeUppercase(this)" cols="20" rows="1" style="background-color: silver;">
<?php if($comment[$i]!="") echo htmlspecialchars($comment[$i]);?></textarea>
  </td>

  
  </tr>
 
<input type="hidden" name="id_check[]" maxlength="3" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($id_check));?>"  size="2"/>

<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars(htmlentities($filing_no));?>"/>  
  
 <?php
$j++;
$i++;
}
?>
 
 </td></tr> 
 <tr><td colspan="12" >
Notification Date: <input id="in_notification_date" type="text" name="notification_date" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($cur_date1));?>" size="10" maxlength="10" />
&nbsp;&nbsp;
 Status: 

   <select id="in_searchby" name="searchby" style="
    background-color: #85929e;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;">
               
   <option value="2">DEFECT FREE</option>
   <option value="1" <?php if($dhiraj =='OK') { print " selected"; }?> style="color: red;">DEFECTIVE</option>
           
   </select>
&nbsp;&nbsp; 

 	<input id="submit_final" type="submit" name="submit_final"
 class="submit" value="UPDATE CASE" style="
    background-color: green;
    color: #FFFFFF;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onClick="defect_submit();"/>

 </td></tr> 
  
  
  
  </table>
  
  
   </div>
          <?php } ?>                         
      </form> 
        
        <!-- /.box-footer-->
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
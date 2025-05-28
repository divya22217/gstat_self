<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();

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
		if($_SESSION['menuaccess_codeall'] !='2')
		{
			echo "Access Problem.....";
			header("Location: ../index.php");
			die();
		}
	}*/
	

	$filing_no_next=$_REQUEST['filing_no_next'];
	$hash1=htmlspecialchars(base64_decode($filing_no_next));
	$hash1 = explode("-", $hash1);
	$filing_no_fou=$hash1[0];
	
	$token_fou= $hash1[1];
/*
if($_SESSION['qqcc'] != $token_fou)	
{
	echo "Access Problem.....";
	header("Location: ../login.php?aa=100");
	die();
}
	if($token_fou =='')
	{
		echo "Access Problem.....";
		header("Location: ../login.php?aa=100");
		die();
	}
	*/
	

	// This code not use next time .......	Schema session create Hear....

	$location_access=$_SESSION['location'];
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
        		$hash=$_REQUEST['hash'];
        		
        		if($hash !='')
        		{
        		 
        			$hash1=htmlspecialchars(base64_decode($hash));
        			$hash1 = explode("/", $hash1);
        			$massage=$hash1[0];
        			$filing_no_backpage = $hash1[1];
        			$filing_no_backpage_print=htmlspecialchars(base64_decode($filing_no_backpage));
        			
        			echo "<center></br><font color='red' size='4'>".htmlspecialchars($msg).'</br>';
        		
        		 } 
         ?>
      <!-- Default box -->
      <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h4 class="box-title">Case Scurtiny &nbsp;&nbsp;
 Dairy No : <?php echo htmlspecialchars($filing_no_fou);?>
 &nbsp;&nbsp;
 <?php 
	
	$st1=$db->prepare("select * from e_case_detail_local where filing_no=? and location_id=? order by filing_no DESC");
	$st1->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
	$st1->bindParam(2, $location_access, PDO::PARAM_STR);
	$st1->execute();
	while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
	    $filing_no = htmlspecialchars($row['filing_no']);
		$pet_name = htmlspecialchars($row['pet_name']);
		$res_name = htmlspecialchars($row['res_name']);
		$dt_of_filing = htmlspecialchars($row['dt_of_filing']);
		$case_type=htmlspecialchars($row['case_type']);
		$case_no=$row['case_no'];
		$location_id=$row['location_id'];
		$act_id=$row['act_id'];
	}
	$stqq = $db->prepare("select act_name from master_act where act_id=?");
	$stqq->bindParam(1, $act_id, PDO::PARAM_INT);
	$stqq->execute();
	$act_name_all = $stqq->fetchColumn();
	
	?>
	<font color="#0000FF" >
      <?php if($pet_name!=''){echo htmlspecialchars_decode(strtoupper($pet_name));} ; ?></font>
      <font color="#FF0000" size="2">&nbsp;&nbsp;Vs&nbsp;&nbsp;</font><font color="#0000FF" >
      <?php if($res_name !=''){echo htmlspecialchars_decode(strtoupper($res_name));} ; ?></font>
 </h4>
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

        		var status1="";
        		//alert('self submit');	
        			var tnl=document.getElementsByName("status");
        		for(i=0;i<tnl.length;i++)
            		{
        	        
                	var val= tnl[i].value;
                     var status1=status1+val+','
                       }
        		if(!document.getElementById('agree').checked)
        		 {
        		     alert('You must agree to the terms first.');
        		     //agree.focus();
        		     return false;
        		 }

        	
       	 action = "review_defactive_cases_action.php?test="+status1;
       	submit();
       	document.form2.submit_final.disabled = true;  
      	document.form2.submit_final.value = 'Please Wait...';  
      	return true;
       	}
       }
       </script> 
        <script>
/* function myFunction() {

	 with(document.form2)
   	{ 

		 var tnl = document.getElementById("status");
         
	        for(i=0;i<tnl.length;i++){
	            if(tnl[i].selected == true){
	                alert(tnl[i].value);
	            }
	        }


	
   	}
}
 */


function myFunction(){
	
	var tnl=document.getElementsByName("status");
	
    var val1=""
 
   for(i=0;i<tnl.length;i++){
        
        	var val= tnl[i].value;
             if(val=='NO')
                 {
                 val1='NO';
             } 
        
       
    }
    if(val1=="")
    {
    	val1='YES';
    }

	 if (window.XMLHttpRequest) {
         
         xmlhttp = new XMLHttpRequest();
     } else {
      
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
     }
     xmlhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {
             document.getElementById("befornotification1").innerHTML = this.responseText;
         }
     };
     xmlhttp.open("GET","notificationdate.php?val="+val1,true);
     xmlhttp.send();

  
	document.getElementById("befornotification1").style.display ='block';
	document.getElementById("befornotification").style.display ='none';

} 



</script>   
<script type="text/javascript" src="accordion.js"></script> 
<script type="text/javascript" src="jquery.min.js"></script>
<link href="demo.css" rel="stylesheet">
<style>
button.accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

button.accordion.active, button.accordion:hover {
    background-color: #ddd; 
}

div.panel {
    padding: 0 18px;
    display: none;
    background-color: white;
}
</style>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  }
}
</script>    
       <?php 
       $remove_defact = sha1( uniqid('auth', true) );
       $_SESSION['remove_defact'] = $remove_defact;
       
      
       ?>       
              
  <form name="form2" method="post" action="review_defactive_cases_action.php" >
  <input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2);?>" />
   <input type="hidden" name="filing_no_next" value="<?php echo htmlspecialchars($filing_no_next);?>" />
   <table width="100%" >
     
			<tr><td colspan="6">
			<?php 
			 $tokenno=$filing_no_fou;	  

	$ll='2';
	$ll1='N';
	$stqq = $db->prepare("select count(filing_no) from $schemas.scrutiny where filing_no=? and defects =? and level_level =?");
	$stqq->bindParam(1, $tokenno, PDO::PARAM_INT);
	$stqq->bindParam(2, $ll1, PDO::PARAM_INT);
	$stqq->bindParam(3, $ll, PDO::PARAM_INT);
	$stqq->execute();
	$filing_no_foundxcc = $stqq->fetchColumn();
	

	  if($filing_no_foundxcc !='')
	  {
	  	 
	  	echo "<font color='red'><br></br><b>Scrutiny Already Completed !!!</b></font>";
	  //	die();
	  	 
	  }
  
	  
?>
			</td></tr>
        </table>

 <?php 
 $ll='1';
 $ll1='Y';
 //echo $stqq = ("select filing_no from $schemas.scrutiny where filing_no='$tokenno' and level_level='$ll'");
 $stqq = $db->prepare("select filing_no from $schemas.scrutiny where filing_no=? and level_level=? and defects =?");
 $stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
 $stqq->bindParam(2, $ll, PDO::PARAM_STR);
 $stqq->bindParam(3, $ll1, PDO::PARAM_STR);
 $stqq->execute();
 $filing_norevari_var = $stqq->fetchColumn();
 if($filing_norevari_var !='')
 {
 $ll='1';
 $ll1='Y';
 //echo $stqq = ("select filing_no from $schemas.scrutiny where filing_no='$tokenno' and level_level='$ll'");
 $stqq = $db->prepare("select * from $schemas.scrutiny where filing_no=? and level_level=? and defects =?");
 $stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
 $stqq->bindParam(2, $ll, PDO::PARAM_STR);
 $stqq->bindParam(3, $ll1, PDO::PARAM_STR);
 $stqq->execute();
 while ($row = $stqq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
 	 $scrutinu_comp_q = htmlspecialchars($row['scrutinu_comp']);
 	 $defects_q = htmlspecialchars($row['defects']);
 	 $notification_datez=$row['notification_date'];
 }
 $filing_no=$tokenno;

 ?>

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
$('.tbl-accordion-nested').each(function()
		  {
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
   
include '../db_inc2.php';
   
   
   
   $stqq = $dbonline->prepare("select count(filing_no) from e_case_detail  where filing_no=?");
   $stqq->bindParam(1, $tokenno, PDO::PARAM_INT);
   $stqq->execute();
   $filing_norevari = $stqq->fetchColumn();
   if($filing_norevari == '0' OR $filing_norevari == '')
   {
       echo "<center><font color='red' size='3'>Document Not Uploaded ....</font></center>";
   }
   if($filing_norevari > '0')
   {
  $sthr=$dbonline->prepare("select * from e_case_detail  where filing_no=? ");
  $sthr->bindParam(1, $tokenno, PDO::PARAM_STR);
  $sthr->execute();
  while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$fileupload=$rowa['unique_id_no'];
  	  	
  	/*$sthrm=$dbonline->prepare("select * from e_sub_document  where sub_doc_id=? order by sub_doc_id ASC ");
  	$sthrm->bindParam(1, $subdoctype, PDO::PARAM_STR);
  	$sthrm->execute();
  	while ($rowa = $sthrm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  	{
  			$sub_doc_type=$rowa['sub_doc_type'];
  	}*/
  	 	
  ?>
 
  <a href="https://efiling.nclt.gov.in/dms-ecourt/ecourt-search-within-dms?applno=<?php echo htmlspecialchars(htmlentities($fileupload));?>&status=P&j_key=vVl%2FAz1yGsjOAG18WDeScg%3D%3D&j_securityKey=6D6C069D681B40DBF95CAD7B3ED71BE1A46F0A7036BC711860B00BAAE50FE8A4%21TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip%2FQ8Wns%3D" target="_blank"><font color="#900C3F" size="3">&nbsp;&nbsp;<?php echo htmlspecialchars("Show Document");?>
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
    <style>
    .greenText{ background-color:green; }

.blueText{ background-color:blue; }


    </style>
      <div class="main">
		<div class="accordion">
		
<?php 

?>		
		
     <div class="accordion-section">
				<a class="accordion-section-title" href="#accordion-1"><?php echo htmlspecialchars("General");?></a>
				<div id="accordion-1" class="accordion-section-content">
		 <table border="1" >
  <tr><td colspan="12"></td></tr>
  <tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
  <td width="3%">Sr. No</td><td width="67%">Description</td><td width="6%">Defect Free</td><td width="16%">Comments</td>
  </tr>
 <tr><td colspan="12">
 
    <?php $status=$_REQUEST['status'];

 ?>

 <?php 
 
 $ll='1';
 $lls='0';
 $flag="";
 
 
 $stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and
 		objection_sub_code=? order by cast(objection_code as integer) ASC ");
 $stv->bindParam(1, $filing_no, PDO::PARAM_STR);
 $stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
 $stv->bindParam(3, $ll, PDO::PARAM_STR);
 $stv->bindParam(4, $lls, PDO::PARAM_STR);
 $stv->execute();
 
 $i=0;
 $j=1;
 while ($rowcc = $stv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
 
 
 	$objection_codeobbj=$rowcc['objection_code'];
 	 $objection_codeststus=$rowcc['status'];
 	if($objection_codeststus=='NO')
 	{
 		$flag='NO';
 	}
 	$comments=$rowcc['comments'];
 
 
 	$sth=$db->prepare("select * from check_list_local where location_all=? and id=? order by id ASC ");
 	$sth->bindParam(1, $location_access, PDO::PARAM_STR);
 	$sth->bindParam(2, $objection_codeobbj, PDO::PARAM_STR);
 	$sth->execute();
 
 	while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 	{
 			
 			
 		$id_check=$rowa['id'];
 		$check_list=$rowa['check_list'];
 	}
 	?>
 <tr>
 
  <td width="5%">
 
  <?php echo htmlspecialchars($id_check);?>
   
  </td>

  
  <td width="60%" ><font color=" #1c2833 "><?php echo htmlspecialchars($check_list);?></font>
  </td>
  <?php 
  if($status[$j]=='NO')
  {
  	$dhiraj ="OK";
  }
 
  ?>
 
  <td width="7%">

<?php
 $status[$j] = isset($_REQUEST['status'][$j]) ? $_REQUEST['status'][$j] : $objection_codeststus; ?>
				 <select name="status" style="
    background-color: silver;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onchange="myFunction()" >
 				 	<option value="YES" <?php  if($objection_codeststus=='YES') echo "selected"; ?> >YES</option>
 				 	<option value="NO" <?php  if($objection_codeststus=='NO') echo "selected"; ?> >NO</option>
 				 	<option value="NA" <?php  if($objection_codeststus=='NA') echo "selected"; ?>>NA</option>
 				 	
                 </select>           
  
  <td width="20%">
 <?php
 $comment[$j] = isset($_REQUEST['comment'][$j]) ? $_REQUEST['comment'][$j] : $comments; ?> 
   <textarea name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" style="background-color: silver;">
<?php if($comments!="") echo htmlspecialchars($comments);?></textarea>

  </td>
<?php $comments!="" ?>
  
  </tr>
 
<input type="hidden" name="id_check[]" maxlength="3" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($id_check.','.'gen'));?>"  size="2"/>

<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars(htmlentities($filing_no));?>"/>  
  
 <?php
$j++;
$i++;
//}

}
 ?>
 </td></tr>
					

    </table>
   
  </div></div>
 <?php 
 $ll='1';
 $lls='1';
 $flag1="";
 
 
 $stv = $db->prepare("select count(*) from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and
 		objection_sub_code=? ");
 $stv->bindParam(1, $filing_no, PDO::PARAM_STR);
 $stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
 $stv->bindParam(3, $ll, PDO::PARAM_STR);
 $stv->bindParam(4, $lls, PDO::PARAM_STR);
 $stv->execute();
 $filing_count_zzz = $stv->fetchColumn();
 
 if($filing_count_zzz > '0')
 {
 
 ?> 
  
   <div class="accordion-section">
				<a class="accordion-section-title" href="#accordion-2"><?php echo htmlspecialchars($act_name_all);?></a>
				<div id="accordion-2" class="accordion-section-content">	
			 <table border="1" >	
	 <tr><td colspan="12"></td></tr>
  <tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
  <td width="3%">Sr. No</td><td width="67%">Description</td><td width="6%">Defect Free</td><td width="16%">Comments</td>
  </tr>
 <tr><td colspan="12">
 
    <?php $status=$_REQUEST['status'];

 ?>

 <?php 
 
 //$flag1="";
  $display='TRUE';

  //find to open scrutiny master local
  if($act_id =='1' OR $act_id =='2')
  { $act_id='1';}
  
  $ll='1';
  $lls='1';
  
  $stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and
  		objection_sub_code=? order by cast(objection_code as integer) ASC ");
  $stv->bindParam(1, $filing_no, PDO::PARAM_STR);
  $stv->bindParam(2, $notification_datez, PDO::PARAM_STR);
  $stv->bindParam(3, $ll, PDO::PARAM_STR);
  $stv->bindParam(4, $lls, PDO::PARAM_STR);
  $stv->execute();
  
  $i=0;
  $j=1;
  while ($rowcc = $stv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  
  
  	$objection_codeobbj=$rowcc['objection_code'];
   $objection_codeststus=$rowcc['status'];
  	if($objection_codeststus=='NO')
  	{
  		 $flag1='NO';
  	}
  	
  	$comments=$rowcc['comments'];
  
  
  $sth=$db->prepare("select * from master_scrutiny_local where location_all=? and id_serno=?  order by id_serno ASC ");
  $sth->bindParam(1, $location_access, PDO::PARAM_STR);
  $sth->bindParam(2, $objection_codeobbj, PDO::PARAM_STR);
$sth->execute();

while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
 $id_check=$rowa['id_serno'];
 $check_list=$rowa['name'];
 $display_all=$rowa['display'];
}
?> 
  <tr>
 
  <td width="5%">
 
  <?php echo htmlspecialchars($j);?>
   
  </td>

  
  <td width="60%" ><font color=" #1c2833 "><?php echo htmlspecialchars($check_list);?></font>
  
  
 
  <td width="7%">

 <?php 
// if($display_all  == '0')
// {
?>
<?php
 $status[$j] = isset($_REQUEST['status'][$j]) ? $_REQUEST['status'][$j] : $objection_codeststus; ?>
				 <select name="status" id="status"  onchange="myFunction()" style="
    background-color: silver;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;"   >
 				 	<option value="YES" <?php  if($status[$j]=='YES') echo "selected"; ?> >YES</option>
 				 	<option value="NO" <?php  if($status[$j]=='NO') echo "selected"; ?> ><font color="red">NO</font></option>
 				 	<option value="NA" <?php  if($status[$j]=='NA') echo "selected"; ?>>NA</option>
 				 	
                 </select>    
                 <?php 
  //}
  ?>       
  </td>
  
  <td width="20%">
   <?php 
// if($display_all == '0')
// {
?>
 <?php
 $comments[$j] = isset($_REQUEST['comment'][$j]) ? $_REQUEST['comment'][$j] : $comments; ?> 
   <textarea name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" style="background-color: silver;">
<?php if($comments!="") echo htmlspecialchars($comments);?></textarea>
<?php $comments=""; ?>
<?php
// }
 ?>
  </td>

  
  </tr>
 
<input type="hidden" name="id_check[]" maxlength="3" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($id_check.",".'IBC1'));?>"  size="2"/>

<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars(htmlentities($filing_no));?>"/>  
  
 <?php
$j++;
$i++;
}

?>
 
 </td></tr>	</table>
 	</div></div>
<?php } ?>	
			
				<table>		
		<tr><td colspan="2">
Notification Date: <input id="in_notification_date" type="text" name="notification_date" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($cur_date1));?>" size="10" maxlength="10" />
</br>
<input type="checkbox" value="0" id="agree" name="agree" required="required"> 
<b><font color="red">Information is Checked and Varified.</font></b>
</td>
<td style="display: block" colspan="4" id="befornotification1">
<td style="display: block" colspan="4" id="befornotification">


	<select id="in_searchby" name="searchby" style="display: block;background-color: red">
	    	  
	    		
	    		<?php if($flag1=='NO'|| $flag=='NO'){?>
	    		<option value="1">DEFECTIVE</option>	
	   			<?php }else { ?>
	   			<option value="2">DEFECT FREE</option>
	         <?php } ?>
	   </select>
	 </td><td>

 	<input id="submit_final" type="submit" name="submit_final"
 class="submit" value="UPDATE SCRUTINY" style="
    background-color: green;
    color: #FFFFFF;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onClick="return defect_submit();"/>

 </td></tr> </table>
			
          <?php }  ?>                         
      </form> 
        
        <!-- /.box-footer-->
      </div>
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
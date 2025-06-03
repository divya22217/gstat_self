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

	
function remove_path($file, $path = UPLOAD_PATH) {
if(strpos($file, $path) !== FALSE) {
return substr($file, strlen($path));
}
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
	$filing_no_next=$_REQUEST['filing_no_next'];
	$hash1=htmlspecialchars(base64_decode($filing_no_next));
	$hash1 = explode("-", $hash1);
	$filing_no_fou=$hash1[0];
	
	$token_fou= $hash1[1];

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


  <script>
  /*$( window ).load(function() {
	  alert('sadsad');
console.log( "window loaded" );
myFunction();

});
  */
  </script>
  <body onload="myFunction()">
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
 
include '../db_inc2.php';
	
	$st1=$dbonline->prepare("select * from e_case_detail where filing_no=? and location_id=? ");
	$st1->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
	$st1->bindParam(2, $location_access, PDO::PARAM_STR);
	$st1->execute();
	while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
	    $filing_no = htmlspecialchars($row['filing_no']);
		
		$dt_of_filing = htmlspecialchars($row['dt_of_filing']);
		$case_type=htmlspecialchars($row['case_type']);
		$case_no=$row['case_no'];
		$location_id=$row['state'];
		$act_id=$row['act_id'];
	}
	$stqq = $dbonline->prepare("select act_name from master_act where act_id=?");
	$stqq->bindParam(1, $act_id, PDO::PARAM_INT);
	$stqq->execute();
	$act_name_all = $stqq->fetchColumn();
        
	$E_party_flag1='P';
	  $E_party_serial_no1='1';
	
		$st33=$dbonline->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st33->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st33->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$st33->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$st33->execute();
		
		while ($row = $st33->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$E_party_flagP=$row['party_flag']; 
			$E_party_serial_noP=$row['party_serial_no']; //0
			$E_nameP=$row['name']; //0
			$E_party_org_typeP=$row['party_org_type']; //0
			$E_party_org_contact_personP=$row['party_org_contact_person']; 
			$E_party_addressP=$row['party_address']; 
			$E_pinP=$row['pin']; //0
			$E_state_codeP=$row['state_code']; //0
			$E_district_codeP=$row['district_code']; //0
			$E_nationalityP=$row['nationality']; 
			$E_emailP=$row['email']; 
			$E_mobileP=$row['mobile']; 
			$E_representative_codeP=$row['representative_code']; //0
			$E_aadhar_noP=$row['aadhar_no']; //0
			 $E_cin_noP=$row['cin_no']; //0
		}	
	
		$E_party_flag1='R';
		$E_party_serial_no1='1';
		$st34=$dbonline->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st34->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st34->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$st34->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$st34->execute();
		while ($row = $st34->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$E_party_flagR=$row['party_flag'];
			$E_party_serial_noR=$row['party_serial_no']; //0
			$E_nameR=$row['name']; //0
			$E_party_org_typeR=$row['party_org_type']; //0
			$E_party_org_contact_personR=$row['party_org_contact_person'];
			$E_party_addressR=$row['party_address'];
			$E_pinR=$row['pin']; //0
			$E_state_codeR=$row['state_code']; //0
			$E_district_codeR=$row['district_code']; //0
			$E_nationalityR=$row['nationality'];
			$E_emailR=$row['email'];
			$E_mobileR=$row['mobile'];
			$E_representative_codeR=$row['representative_code']; //0
			$E_aadhar_noR=$row['aadhar_no']; //0
			$E_cin_noR=$row['cin_no']; //0
		}
	?>
	<font color="#0000FF" >
     <?php echo htmlspecialchars_decode(strtoupper($E_nameP)).'&nbsp; VS &nbsp;'.htmlspecialchars_decode(strtoupper($E_nameR));?></font>
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

        		
       	 action = "scrutiny_action1.php?test="+status1;
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
              
  <form name="form2" method="post" action="scrutiny_action1.php" >
  <input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2);?>" />
   <input type="hidden" name="filing_no_next" value="<?php echo htmlspecialchars($filing_no_next);?>" />
   <table width="100%" >
     
			<tr><td colspan="6">
			<?php 
			
				
			
			 $tokenno=$filing_no_fou;	  

	 
?>
			</td></tr>
        </table>

 <?php 

 if($tokenno !='')
 {
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
  	$fileupload_uniqueid=$rowa['unique_id_no'];
  	  
  }  	
    $display='TRUE';
    $scrutiny='0';
   
  // echo $sql="select * from document_upload  where uniqueid='$fileupload_uniqueid' and filing_no='$tokenno' ";
  	
	$sthrm=$dbonline->prepare("select * from document_upload  where uniqueid=? and filing_no=? and scrutiny=? and display=? ");
  	$sthrm->bindParam(1, $fileupload_uniqueid, PDO::PARAM_STR);
     $sthrm->bindParam(2, $tokenno, PDO::PARAM_STR);
	 $sthrm->bindParam(3, $scrutiny, PDO::PARAM_STR);
     $sthrm->bindParam(4, $display, PDO::PARAM_STR);
     // $sthrm->bindParam(3, $display, PDO::PARAM_STR);
  	$sthrm->execute();
  	while ($rowa = $sthrm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  	{
		//$path='';
  		$sub_doc_type=$rowa['subdoctype'];
		 $scrut=$rowa['scrutiny'];
		  $scrdisplay=$rowa['display'];
          	 $path =$rowa['fileupload'];    
			 $returnfilename =$rowa['returnfilename']; 
	list($returnfilename,$ext)=explode('.',$returnfilename);
       $returnfilename1=$returnfilename;		 
                        
            $stqq = $dbonline->prepare("select e_document_name from e_document_type  where e_document_type=?");
            $stqq->bindParam(1, $sub_doc_type, PDO::PARAM_INT);
            $stqq->execute();
			
				$e_document_name_print = $stqq->fetchColumn(); 

			

         /*                
  	
  		if($scrdisplay==0 and $scrut==1)
		{
			
			
			//$scrdisplay=0;
			 $scrutiny=0;
			
			 
			
       $sthrm=$dbonline->prepare("select * from e_reply_details  where uniqueid=? and scrutiny=? and case_no=?  ");
	 
		   //$sthrm->bindParam(1, $tokenno, PDO::PARAM_STR);
  	$sthrm->bindParam(1, $fileupload_uniqueid, PDO::PARAM_STR);
     $sthrm->bindParam(2, $scrutiny, PDO::PARAM_STR);
	  $sthrm->bindParam(3, $tokenno, PDO::PARAM_STR);
      //$sthrm->bindParam(4, $scrdisplay, PDO::PARAM_STR);
  	$sthrm->execute();
	  
  	while ($rowa = $sthrm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  	{
		//$path='';
  		 $doc_type=$rowa['documenttype'];
		  $stqq = $dbonline->prepare("select e_document_name from e_document_type  where e_document_type=?");
            $stqq->bindParam(1, $doc_type, PDO::PARAM_INT);
            $stqq->execute();
              $e_document_name_print = $stqq->fetchColumn(); 


		}
		}
		*/

  ?>
<!--
<a href="http://efiling.nclt.gov.in:8080/dms-ecourt/ecourt-search-within-dms?applno=<?php echo htmlspecialchars(htmlentities($fileupload_uniqueid));?>&status=P&j_key=vVl%2FAz1yGsjOAG18WDeScg%3D%3D&j_securityKey=6D6C069D681B40DBF95CAD7B3ED71BE1A46F0A7036BC711860B00BAAE50FE8A4%21TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip%2FQ8Wns%3D" target="_blank">
      <font color="#900C3F" size="3">&nbsp;&nbsp;<?php echo htmlspecialchars(strtoupper($e_document_name_print));?>
  &nbsp;&nbsp;</a> 
  -->
 <a href="http://efiling.nclt.gov.in:8080/dms-ecourt2/ecourt-search-within-dms-individual?applno=<?php echo $returnfilename1?>&status=P&j_key=vVl%2FAz1yGsjOAG18WDeScg%3D%3D&j_securityKey=6D6C069D681B40DBF95CAD7B3ED71BE1A46F0A7036BC711860B00BAAE50FE8A4%21TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip%2FQ8Wns%3D"  target="_blank">
<font color="#900C3F" size="3">&nbsp;&nbsp;
  &nbsp;&nbsp;<?php echo htmlspecialchars(strtoupper($e_document_name_print));?></a>

<?php 
        }
        }
?>

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
 
  
  $display='TRUE';

  $sth=$db->prepare("select * from check_list_local where location_all=?  order by id ASC ");
  $sth->bindParam(1, $location_access, PDO::PARAM_STR);
$sth->execute();
$i=0;
$j=1;
while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$id_check=$rowa['id'];

$sql="select status from delhi.objection_details where filing_no='$filing_no' and objection_code='$id_check'";
$stq=$db->prepare($sql);
//$stq->bindParam(1, $filing_no, PDO::PARAM_INT);
//$stq->bindParam(2, $id_check, PDO::PARAM_INT);
$stq->execute();
$status = $stq->fetchColumn();


 $check_list=$rowa['check_list'];
?> 
  <tr>
 
  <td width="5%">
 
  <?php echo htmlspecialchars($id_check);?>
   
  </td>

  
  <td width="60%" ><font color=" #1c2833 "><?php echo htmlspecialchars($check_list);?></font>
  
  
 
  <td width="7%">

 
				 <select name="status" id="status"  onchange="myFunction()" style="
    background-color: silver;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;"   >
                <option value="YES" <?php  if($status=='YES') echo "selected"; ?> >YES</option>
                <option value="NO" <?php  if($status=='NO') echo "selected"; ?> ><font color="red">NO</font></option>

 				 	
                 </select>           
  
  <td width="20%">
  
   <textarea name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" style="background-color: silver;">
<?php if($comment[$i]!="") echo htmlspecialchars($comment[$i]);?></textarea>
  </td>

  
  </tr>
 
<input type="hidden" name="id_check[]" maxlength="3" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($id_check.','.'gen'));?>"  size="2"/>

<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars(htmlentities($filing_no));?>"/>  
  
 <?php
$j++;
$i++;
}

?>
 
 </td></tr>

    </table>
   
  </div></div>
  <table>
  <tr><td>
  <?php 
 $counterstatus =0;

 		    $st2=$dbonline->prepare("select * from e_case_detail_fees where filing_no=?");
			$st2->bindParam(1, $tokenno, PDO::PARAM_STR);
			$st2->execute();
			$i=0;
			while ($row2= $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				echo $E_sec_id=$row2['sec_id'];
				$E_act_id=$row2['act_id'];
				$inter_act_id=$row2['inter_act_id'];
				
						
		?>
		<input type="hidden" name='found_all[]' value='<?php echo $E_sec_id;?>' />
		
	 </td></tr>
					

    </table>
	<?php
	
 if(($E_sec_id=='34' OR $E_sec_id=='35' OR $E_sec_id=='36') and $counterstatus ==0) 
 {
 	    $counterstatus =$counterstatus +1;
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
 
    <?php
    $status=$_REQUEST['status'];

 ?>

 <?php 
 
  
  $display='TRUE';

 

$sth=$db->prepare("select * from master_scrutiny_local where  link_id=?  order by id_serno ASC ");
//$sth->bindParam(1, $location_access, PDO::PARAM_STR);
$sth->bindParam(1, $E_sec_id, PDO::PARAM_STR);
$sth->execute();



//}
$i=0;
$j=1;
while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	  $id_check=$rowa['id_serno'];
 $check_list=$rowa['name'];
 $display_all=$rowa['display'];
?> 
  <tr>
 
  <td width="5%">
 
  <?php echo htmlspecialchars($j);?>
   
  </td>

  
  <td width="60%" ><font color=" #1c2833 "><?php echo htmlspecialchars($check_list);?></font>
  
  
 
  <td width="7%">

 <?php 

 //if($display_all  == '0')
// {
?>
	<select name="status" id="status"  onchange="myFunction()" style="
    background-color: silver;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;"   >
 		<option value="YES" <?php  if($status[$i]=='YES') echo "selected"; ?> >YES</option>
 				 	<option value="NO" <?php  if($status[$i]=='NO') echo "selected"; ?> ><font color="red">NO</font></option>
 				 	
 				 	
                 </select>    
                 <?php 
//}
?>       
  </td>
  
  <td width="20%">
   <?php 
 //if($display_all == '0')
// {
?>
   <textarea name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" style="background-color: silver;">
<?php if($comment[$i]!="") echo htmlspecialchars($comment[$i]);?></textarea>
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
 
 </td></tr>			
	 </table>
				</div></div>
				
<?php 
 }
			
//aaa
?>		
		
	   <table>
	   
	   
	   <!-- company application section under section 230 - 240-->
	   
	   

	<?php 
	/* changes

 $counterstatus1=1;		//Motion one checklist 
 if($E_sec_id=='32' and $counterstatus =1)	 
 {
	 $counterstatus1=0
 ?>
<div class="accordion-section">
 <a class="accordion-section-title" href="#accordion-2"><?php echo htmlspecialchars($act_name_all)."( Motion 2)";?></a>
<div id="accordion-2" class="accordion-section-content">	
<table border="1" >	
	 <tr><td colspan="12"></td></tr>
  <tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
  <td width="3%">Sr. No</td><td width="67%">Description</td><td width="6%">Defect Free</td><td width="16%">Comments</td>
  </tr>
 <tr><td colspan="12">
 
    <?php
    $status=$_REQUEST['status'];

 ?>

 <?php 
 $e_case_type=$case_type;
   $e_case_type='2';
 $display='TRUE';
//$sql="select * from master_scrutiny_local where  link_id='$E_sec_id'  order by id_serno ASC"; 

$sth=$db->prepare("select * from master_scrutiny_local where  link_id=?  and case_type=? order by id_serno ASC ");
//$sth->bindParam(1, $location_access, PDO::PARAM_STR);
$sth->bindParam(1, $E_sec_id, PDO::PARAM_STR);
$sth->bindParam(2, $e_case_type, PDO::PARAM_STR);
$sth->execute();



//}
$i=0;
$j=1;
while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	  $id_check=$rowa['id_serno'];
 $check_list=$rowa['name'];
 $display_all=$rowa['display'];
?> 
  <tr>
 
  <td width="5%">
 
  <?php echo htmlspecialchars($j);?>
   
  </td>

  
  <td width="60%" ><font color=" #1c2833 "><?php echo htmlspecialchars($check_list);?></font>
  
  
 
  <td width="7%">

 <?php 

 //if($display_all  == '0')
// {
?>
	<select name="status" id="status"  onchange="myFunction()" style="
    background-color: silver;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;"   >
 		<option value="YES" <?php  if($status[$i]=='YES') echo "selected"; ?> >YES</option>
 				 	<option value="NO" <?php  if($status[$i]=='NO') echo "selected"; ?> ><font color="red">NO</font></option>
 				 	
 				 	
                 </select>    
                 <?php 
//}
?>       
  </td>
  
  <td width="20%">
   <?php 
 //if($display_all == '0')
// {
?>
   <textarea name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" style="background-color: silver;">
<?php if($comment[$i]!="") echo htmlspecialchars($comment[$i]);?></textarea>
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
 
 </td></tr>			
	 </table>
				</div></div>
				
<?php 
 }
			
//aaa
?>				
<?php 		
			}
			 changes*/
	?>	   <table>
	   
	   
	<?php 


 $counterstatus1=1;		//Motion1 one checklist 
 if($E_sec_id=='32' and $counterstatus =1 and $case_type='13')	 
 {
	 $counterstatus1=0
 ?>
<div class="accordion-section">
 <a class="accordion-section-title" href="#accordion-2"><?php echo htmlspecialchars($act_name_all)."( Motion 1)";?></a>
<div id="accordion-2" class="accordion-section-content">	
<table border="1" >	
	 <tr><td colspan="12"></td></tr>
  <tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
  <td width="3%">Sr. No</td><td width="67%">Description</td><td width="6%">Defect Free</td><td width="16%">Comments</td>
  </tr>
 <tr><td colspan="12">
 
    <?php
    $status=$_REQUEST['status'];

 ?>

 <?php 
 $e_case_type=$case_type;
   $e_case_type='13';
 $display='TRUE';
//$sql="select * from master_scrutiny_local where  link_id='$E_sec_id'  order by id_serno ASC"; 

$sth=$db->prepare("select * from master_scrutiny_local where  link_id=?  and case_type=? order by id_serno ASC ");
//$sth->bindParam(1, $location_access, PDO::PARAM_STR);
$sth->bindParam(1, $E_sec_id, PDO::PARAM_STR);
$sth->bindParam(2, $e_case_type, PDO::PARAM_STR);
$sth->execute();



//}
$i=0;
$j=1;
while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	  $id_check=$rowa['id_serno'];
 $check_list=$rowa['name'];
 $display_all=$rowa['display'];
?> 
  <tr>
 
  <td width="5%">
 
  <?php echo htmlspecialchars($j);?>
   
  </td>

  
  <td width="60%" ><font color=" #1c2833 "><?php echo htmlspecialchars($check_list);?></font>
  
  
 
  <td width="7%">

 <?php 

 //if($display_all  == '0')
// {
?>
	<select name="status" id="status"  onchange="myFunction()" style="
    background-color: silver;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;"   >
 		<option value="YES" <?php  if($status[$i]=='YES') echo "selected"; ?> >YES</option>
 				 	<option value="NO" <?php  if($status[$i]=='NO') echo "selected"; ?> ><font color="red">NO</font></option>
 				 	
 				 	
                 </select>    
                 <?php 
//}
?>       
  </td>
  
  <td width="20%">
   <?php 
 //if($display_all == '0')
// {
?>
   <textarea name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" style="background-color: silver;">
<?php if($comment[$i]!="") echo htmlspecialchars($comment[$i]);?></textarea>
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
 
 </td></tr>			
	 </table>
				</div></div>
				
<?php 
 }
			
//aaa
?>				
<?php 		
			}
	?>	   <table>
	      
	   
	   
	   
	   
	   
	   
         	<tr><td colspan="2">
Notification Date: <input id="in_notification_date" type="text" name="notification_date" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($cur_date1));?>" size="10" maxlength="10" />
</br>
<input type="checkbox" value="0" id="agree" name="agree" required="required"> 
<b><font color="red">Scrutiny Completed</font></b>
</td>
<td style="display: block" colspan="4" id="befornotification1">
<td style="display: block" colspan="4" id="befornotification">


	<select id="in_searchby" name="searchby" style="display: block">
	    	  
	    		<option value="2">DEFECT FREE</option>
	    		<option value="1">DEFECTIVE</option>	
	   
	           
	   </select>
	 </td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

 	<input id="submit_final" type="submit" name="submit_final"
 class="submit" value="UPDATE SCRUTINY" style="
    background-color: green;
    color: #FFFFFF;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onClick="return defect_submit();"/>

 </td></tr>
         </table>  			
					
          <?php } ?>     
          
                         
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

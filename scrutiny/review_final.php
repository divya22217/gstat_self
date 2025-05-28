<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
$server_date= date('d/m/Y');
include("../db_inc1.php");

include '../db_inc2.php';
session_start();
$_SESSION['user'];
$_SESSION['location'];
//$leveladd=$_SESSION['level_level'];
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
$form2 = sha1(uniqid('auth', true) );
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
          <h4 class="box-title">Case&nbsp;&nbsp;
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
       
        	
       	 action = "review_final_action.php?test="+status1;
       	submit();
       	document.form2.submit_final.disabled = true;  
      	document.form2.submit_final.value = 'Please Wait...';  
      	return true;
       	}
       }
       </script> 
        <script>
		function submitForm()
{
with(document.form2)
{

action = "review_final.php";
submit();
}
}
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
              
  <form name="form2" method="post" action="review_final_action.php" >
  <input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2);?>" />
   <input type="hidden" name="filing_no_next" value="<?php echo htmlspecialchars($filing_no_next);?>" />
<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars($filing_no_fou);?>" />
   
   <table width="100%" >
     
			<tr><td colspan="6">
			<?php 
			 $tokenno=$filing_no_fou;	  

	$ll='2';
	$ll1='N';
	//echo $sql="select * from $schemas.scrutiny where filing_no='$tokenno' ";
	$stqq = $db->prepare("select count(filing_no) from $schemas.scrutiny where filing_no=? and defects=?  ");
	$stqq->bindParam(1, $tokenno, PDO::PARAM_INT);
	$stqq->bindParam(2, $ll1, PDO::PARAM_INT);
	//$stqq->bindParam(3, $ll, PDO::PARAM_INT);
	$stqq->execute();
	$filing_no_foundxcc = $stqq->fetchColumn();
	

	  if($filing_no_foundxcc !=0 )
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
 $stqq = $db->prepare("select filing_no from $schemas.scrutiny where filing_no=?  and defects =?");
 $stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
// $stqq->bindParam(2, $ll, PDO::PARAM_STR);
 $stqq->bindParam(2, $ll1, PDO::PARAM_STR);
 $stqq->execute();
 $filing_norevari_var = $stqq->fetchColumn();
 if($filing_norevari_var !='')
 {
 $ll='1';
 $ll1='Y';
 //echo $stqq = ("select filing_no from $schemas.scrutiny where filing_no='$tokenno' and level_level='$ll'");
 $stqq = $db->prepare("select * from $schemas.scrutiny where filing_no=? and defects =?");
 $stqq->bindParam(1, $tokenno, PDO::PARAM_STR);
 //$stqq->bindParam(2, $ll, PDO::PARAM_STR);
 $stqq->bindParam(2, $ll1, PDO::PARAM_STR);
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
				
				<div id="accordion-1" class="accordion-section-content">
		
 </td></tr>	</table>
 	</div></div>
	
			
				<table>	


        <table cellpadding="0" cellspacing="0" class="tbl-accordion-nested">
         <thead>
            <tr>
              <td colspan="2" class="tbl-accordion-section">Documents:
            
         
           

   <?php 
   
   $stqq = $dbonline->prepare("select count(filing_no) from e_case_detail  where filing_no=?");
   $stqq->bindParam(1, $filing_no_fou, PDO::PARAM_INT);
   $stqq->execute();
   $filing_norevari = $stqq->fetchColumn();
   if($filing_norevari == '0' OR $filing_norevari == '')
   {
       echo "<center><font color='red' size='3'>Document Not Uploaded ....</font></center>";
   }
   if($filing_norevari > '0')
   {
  $sthr=$dbonline->prepare("select * from e_case_detail  where filing_no=? ");
  $sthr->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
  $sthr->execute();
  while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$fileupload_uniqueid=$rowa['unique_id_no'];
  	  
  }  	
    
   $display='FALSE';
    $scrutiny='1';



$sthrm1=$dbonline->prepare("select max(document_filed_date) as document_filed_date from document_upload  where uniqueid=? and filing_no=? and scrutiny=? and display=? ");
  	$sthrm1->bindParam(1, $fileupload_uniqueid, PDO::PARAM_STR);
     $sthrm1->bindParam(2, $filing_no_fou, PDO::PARAM_STR);
	 $sthrm1->bindParam(3, $scrutiny, PDO::PARAM_STR);
     $sthrm1->bindParam(4, $display, PDO::PARAM_STR);
     // $sthrm->bindParam(3, $display, PDO::PARAM_STR);
  	$sthrm1->execute();
  	while ($rowa = $sthrm1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  	{
		//$path='';
  		 $document_filed_date=$rowa['document_filed_date'];
         }


  	
	$sthrm=$dbonline->prepare("select * from document_upload  where uniqueid=? and filing_no=? and scrutiny=? and display=? and document_filed_date=?");
  	$sthrm->bindParam(1, $fileupload_uniqueid, PDO::PARAM_STR);
     $sthrm->bindParam(2, $filing_no_fou, PDO::PARAM_STR);
	 $sthrm->bindParam(3, $scrutiny, PDO::PARAM_STR);
     $sthrm->bindParam(4, $display, PDO::PARAM_STR);
		$sthrm->bindParam(5, $document_filed_date, PDO::PARAM_STR);
     // $sthrm->bindParam(3, $display, PDO::PARAM_STR);
  	$sthrm->execute();
  	while ($rowa = $sthrm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  	{
		//$path='';
  		$sub_doc_type=$rowa['subdoctype'];
		 //$scrut=$rowa['scrutiny'];
		  //$scrdisplay=$rowa['display'];
          	 //$path =$rowa['fileupload'];    
			 $returnfilename =$rowa['returnfilename']; 
	list($returnfilename,$ext)=explode('.',$returnfilename);
       $returnfilename1=$returnfilename;		 
                        
            $stqq = $dbonline->prepare("select e_document_name from e_document_type  where e_document_type=?");
            $stqq->bindParam(1, $sub_doc_type, PDO::PARAM_INT);
            $stqq->execute();
			
				$e_document_name_print = $stqq->fetchColumn(); 

			

         
  ?>
<!--
<a href="http://efiling.nclt.gov.in:8080/dms-ecourt/ecourt-search-within-dms?applno=<?php echo htmlspecialchars(htmlentities($fileupload_uniqueid));?>&status=P&j_key=vVl%2FAz1yGsjOAG18WDeScg%3D%3D&j_securityKey=6D6C069D681B40DBF95CAD7B3ED71BE1A46F0A7036BC711860B00BAAE50FE8A4%21TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip%2FQ8Wns%3D" target="_blank">
      <font color="#900C3F" size="3">&nbsp;&nbsp;<?php echo htmlspecialchars(strtoupper($e_document_name_print));?>
  &nbsp;&nbsp;</a> 
  -->
 <a href="https://efiling.nclt.gov.in/dms-ecourt2/ecourt-search-within-dms-individual?applno=<?php echo $returnfilename1?>&status=P&j_key=vVl%2FAz1yGsjOAG18WDeScg%3D%3D&j_securityKey=6D6C069D681B40DBF95CAD7B3ED71BE1A46F0A7036BC711860B00BAAE50FE8A4%21TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip%2FQ8Wns%3D"  target="_blank">
<font color="#900C3F" size="3">&nbsp;&nbsp;
  &nbsp;&nbsp;<?php echo htmlspecialchars(strtoupper($e_document_name_print));?></a>

<?php 
        }
        }
?>

</td>

</tr>




	
		<tr><td colspan="2">
 Date: <input id="in_notification_date" type="text" name="notification_date" readonly="readonly"
value="<?php echo htmlspecialchars(htmlentities($server_date));?>" size="10" maxlength="10" />
</br>
</td>
<td>
<b><font>Case</font></b>
</td>
<td>
<?php 


 $searchby = isset($_REQUEST['searchby']) ? $_REQUEST['searchby'] :'';?>
	<select  name="searchby" onchange="javascript:submitForm();" >
	    	  
	    	
	    		<option value="A" <?php if($searchby == "A") { print " selected"; } ?> >ALLOWED </option>	
	   			
	   			<option value="D" <?php if($searchby == "D") { print " selected"; } ?> >DISMISSED 
			</option>
	         
	   </select>
	 </td>
	 </tr>
	<br>
	<?php if($searchby=="D")
	{
		?>
<tr>
		
		 <td align="center" colspan="2">
		
  		<legend><b>ORDER:</b></legend>
  		
  </br>

		             
		                            
		                      <textarea id="tinymce_basic" name="order_of_tribunal" 
		                                cols="40" rows="10"><?php echo htmlspecialchars($order_of_tribunal);?></textarea>


<script type="text/javascript" src="vendors/tinymce/js/tinymce/tinymce.min.js"></script>

       <!--  <script src="assets/scripts.js"></script>-->
        <script>
       
        // Tiny MCE
        tinymce.init({
		    selector: "#tinymce_basic",
		    plugins: [
		        "advlist autolink lists link image charmap print preview anchor",
		        "searchreplace visualblocks code fullscreen",
		        "insertdatetime media table contextmenu paste"
		    ],
		    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
		});

		
        </script>	
		 </td>


	 </tr>
	<?php 
	}
	?>
<tr>


	 <tr align="center">
	 <td>

<br>
<br>
 	<input id="submit_final" type="submit" name="submit_final"
 class="submit" value="SUBMIT" style="
    background-color: green;
    color: #FFFFFF;
    padding: 7px 7px;
    margin: 8px 10;
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

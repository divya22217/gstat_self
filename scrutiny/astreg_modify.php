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
          <h4 class="box-title">

	
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
		
		function submitForm1()
{
with(document.form2)
{
action="astreg_modify.php";
submit();
}
}
	
		function submitForm()
{
with(document.form2)
{

action = "astreg_modify.php";
submit();
}
}
function submitForm2()
{
with(document.form2)
{

action = "astreg_modify.php";
submit();
}
}

function validate()
{
	
		with(document.form2)
{

action = "astreg_modify_action.php";
submit();

	}
}
</script>   
<script type="text/javascript" src="accordion.js"></script> 
<script type="text/javascript" src="jquery.min.js"></script>
<link href="demo.css" rel="stylesheet">

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
          
      
  <form name="form2" method="post" action="" >
  <input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2);?>" />

   
   <table width="100%" >
     
			<tr><td colspan="6">
			
			</td></tr>
        </table>

	<tr><td colspan="2">
	<?php  $diary_no = isset($_REQUEST['diary_no']) ? $_REQUEST['diary_no'] :'';?>


 Diary No: <input  type="text" name="diary_no" value="<?php echo htmlspecialchars(htmlentities($diary_no));?>" size="16" maxlength="16" />

 <td>
<input type="button"  size=5 name="go" id="gobtn" value="Go" onClick="javascript:submitForm1();">
 </td>
 </tr>
	
<?php


$sthrm=$dbonline->prepare("select * from e_case_detail  where filing_no=? ");
  	$sthrm->bindParam(1, $diary_no, PDO::PARAM_STR);
     $sthrm->execute();
  	while ($rowa = $sthrm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  	{
		//$path='';
  		 $filing_no=$rowa['filing_no'];
	
	}		
	if($filing_no=='' || $filing_no==0)
	{
		$msg="Record Not Found";
	}
	
	
	
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
			$E_party_serial_noP=$row['party_serial_no']; 
			$E_nameP=$row['name']; 
		}
		
		$E_party_flag2='R';
		$E_party_serial_no2='1';
		
	
		
		$st331=$dbonline->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st331->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st331->bindParam(2, $E_party_flag2, PDO::PARAM_STR);
		$st331->bindParam(3, $E_party_serial_no2, PDO::PARAM_STR);
		$st331->execute();
		
		while ($row1 = $st331->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$E_party_flagR=$row1['party_flag']; 
			$E_party_serial_noR=$row1['party_serial_no']; 
			 $E_nameR=$row1['name']; 
		}
		
		
		
		
?>

 </h4>
 </div>
 </div>
   </div>
 <div class="box-footer">

      <div class="main">
	  
		<div class="accordion">
	Case Title: <font color="#0000FF" >
      <?php if($E_nameP!=''){echo htmlspecialchars_decode(strtoupper($E_nameP));} ; ?></font>
      <font color="#FF0000" size="2">&nbsp;&nbsp;Vs&nbsp;&nbsp;</font><font color="#0000FF" >
      <?php if($E_nameR !=''){echo htmlspecialchars_decode(strtoupper($E_nameR));} ; ?></font>
     <div class="accordion-section">
				
				<div id="accordion-1" class="accordion-section-content">
		
 </td></tr>	</table>
 	</div></div>
	
			
		
  <input type="hidden" name="filing_no" value="<?php echo htmlspecialchars($filing_no);?>" />

        <table cellpadding="0" cellspacing="0" class="tbl-accordion-nested">
         <thead>
          
<tr align="center">
	 <td>


 	<input id="submit_final" type="submit" name="submit_final"
 class="submit" value="ROLL BACK CASE FROM SCRUTINY SECTION" style="
    background-color: green;
    color: #FFFFFF;
    padding: 7px 7px;
    margin: 8px 10;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onClick="return  validate();"/>

 </td></tr>

   
  

</td>

</tr>


</table>

	
            
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

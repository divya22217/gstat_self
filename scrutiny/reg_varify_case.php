<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
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



	// This code not use next time .......	Schema session create Hear....


	$sessionUserType=htmlspecialchars($_SESSION['id']);
	
	$location_access=$_SESSION['location'];

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


?>
      <script>
       function defect_submit()
       {
       	//validate3();
       	
        	with(document.form2)
       	{	
             	
        	
       	 action = "reg_varify_case_action.php";
       	submit();
       	document.form2.submit_final.disabled = true;  
      	document.form2.submit_final.value = 'Please Wait...';  
      	return true;
       	}
       }
       </script> 


 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content">
     
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Notice For Defective Cases (More Than 7 Days)</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
           <?php 
        $hash=htmlspecialchars($_REQUEST['hash']);
        if($hash !='')
        {
        	$hash1=htmlspecialchars(base64_decode($hash));
        	$hash1 = explode("-", $hash1);
        	$massage=$hash1[0];
        	
        	$token_filing_no_scrutiny= $hash1[1];
        
        ?>      
                  <div class="alert alert-success" role="alert">
  <?php echo htmlspecialchars($massage);?>
</div> 
        <?php } ?> 

				 <div class="col-md-12">
	  <form name="form2" method="post" action="reg_varify_case_action.php" >
  <input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2);?>" />
   <input type="hidden" name="filing_no" value="<?php echo htmlspecialchars($filing_no_fou);?>" />
     <h4>  
       Dairy No : <?php echo htmlspecialchars($filing_no_fou);?>
 &nbsp;&nbsp;
 <?php 
	
	$st1=$db->prepare("select * from e_case_detail_local where filing_no=? and location_id=? ");
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
	}
	?>
	<font color="#0000FF" >
      <?php if($pet_name!=''){echo htmlspecialchars_decode(strtoupper($pet_name));} ; ?></font>
      <font color="#FF0000" size="2">&nbsp;&nbsp;Vs.&nbsp;&nbsp;</font><font color="#0000FF" >
      <?php if($res_name !=''){echo htmlspecialchars_decode(strtoupper($res_name));} ; ?></font>  
   </h4>    
<?php 

$st=$db->prepare("select notification_date from $schemas.scrutiny where filing_no=?");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
$notification_date_all = $st->fetchColumn();

list($year,$month,$day)=explode('-',$notification_date_all);
$notification_date1=$day.'/'.$month.'/'.$year;

?>   
 <input type="hidden" name="notification_date_all" value="<?php echo htmlspecialchars($notification_date_all);?>" />
   <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  
                  <tbody>
                   <tr><td colspan="6" align="center" style="color: red;">
                  <b>Defect Date:&nbsp;
                   <?php echo htmlspecialchars($notification_date1);?></b>
                   </td></tr>



           <table width="100%" cellpadding="0" border='1' cellspacing="0" class="tbl-accordion-nested">
        
            <tr>
              <td align="center" colspan="6" ><b><font color="red">Documents</b></font></td>
            </tr>
			<tr>
              <td align="center" class="tbl-accordion-section"><b>Petitioner</b></td>
			  <td align="center" class="tbl-accordion-section"><b>Respondent</b></td>
            </tr>
         </table>
            <table cellpadding="0" border='1' cellspacing="0" class="tbl-accordion-nested"> 

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

		
$sqlsss = $dbonline->prepare("select * from e_reply_details as a,reply_document_status as b  where a.filing_no=b.filing_no and a.filing_no =? and b.filing_no =? and b.uploaded_date=? ");

			 
			 $sqlsss->execute(array($filing_no_fou,$filing_no_fou,$document_filed_date));
			
			while ($row2 = $sqlsss->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  	{
		//$path='';
  		  $party_flag=$row2['party_flag'];
		 $party_serial_no=$row2['party_serial_no'];
		 $miscellaneous_no=$row2['miscellaneous_no'];
		 
	}	
         
  ?>
<?php
if($party_flag =='P' or $party_flag =='' )
{
?>	 
<tr>
<td colspan="4" align="left">
 <a href="https://efiling.nclt.gov.in/dms-ecourt2/ecourt-search-within-dms-individual?applno=<?php echo $returnfilename1?>&status=P&j_key=vVl%2FAz1yGsjOAG18WDeScg%3D%3D&j_securityKey=6D6C069D681B40DBF95CAD7B3ED71BE1A46F0A7036BC711860B00BAAE50FE8A4%21TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip%2FQ8Wns%3D"  target="_blank">
<font color="#900C3F" size="3"><?php echo htmlspecialchars(strtoupper($e_document_name_print))." ".$miscellaneous_no;?></a>
</td>
  <?php
  }
  if($party_flag =='R')
	{
?>
		
<td colspan="4" align="right">
 <a href="http://164.100.59.89/dms-ecourt2/ecourt-search-within-dms-individual?applno=<?php echo $returnfilename1?>&status=P&j_key=vVl%2FAz1yGsjOAG18WDeScg%3D%3D&j_securityKey=6D6C069D681B40DBF95CAD7B3ED71BE1A46F0A7036BC711860B00BAAE50FE8A4%21TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip%2FQ8Wns%3D"  target="_blank">
<font color="#900C3F" size="3">&nbsp;&nbsp;
  &nbsp;&nbsp;<?php echo htmlspecialchars(strtoupper($e_document_name_print))." ".$miscellaneous_no;?></a>
</td>
		<?php
	}
	
  ?>
</tr>

<?php 
        }
        }
?>


   
          <tr><td align="left" valign="top">
       <tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
  <td width="3%">Sr. No</td><td width="60%">Description</td><td width="5%">Status</td><td width="32%">Remarks</td>
  </tr>
   <?php 
   $ll='2';
   $lls='NO';
   $llss='0';
  // $stv = "select * from $schemas.objection_details where filing_no= '$filing_no' and entry_dt='$notification_date_all' and level_level='$ll' and status='$lls'
   		//and objection_sub_code='$llss' order by cast(objection_code as integer) ASC ";
  
  $stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and status=? 
   	and objection_sub_code=? order by cast(objection_code as integer) ASC ");
   $stv->bindParam(1, $filing_no, PDO::PARAM_STR);
   $stv->bindParam(2, $notification_date_all, PDO::PARAM_STR);
   $stv->bindParam(3, $ll, PDO::PARAM_STR);
   $stv->bindParam(4, $lls, PDO::PARAM_STR);
   $stv->bindParam(5, $llss, PDO::PARAM_STR);
   $stv->execute();
   $i=0;
   $j=1;
   while ($rowcc = $stv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
   {
   	$objection_codeobbj=$rowcc['objection_code'];
   	$objection_codeststus=$rowcc['status'];
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
  <td width="3%"><?php echo $j;?></td>
  <td width="60%"><?php echo $check_list;?></td>
    <td width="7%"><?php echo $objection_codeststus;?></td>
  <td width="30%"><?php echo $comments;?></td>
  </tr>     
         
         <?php 
         $j++;
   }?>         
   <?php 
   
   if($id_check !='')
   {
   
   	?>
                  </td></tr>  
                  <tr><td colspan="12">
                  <hr color="green" size="2"/>
                  </td></tr> 
                  <?php
 }
                  ?>
                    <?php 
   $ll='1';
   $lls='NO';
   $llss='1';
/*   echo $stv = ("select * from $schemas.objection_details where filing_no= '$filing_no' and entry_dt='$notification_date_all' and level_level='$ll' and status='$lls'
   		and objection_sub_code='$llss' order by cast(objection_code as integer) ASC ");
 */  
   $stv = $db->prepare("select * from $schemas.objection_details where filing_no= ? and entry_dt=? and level_level=? and status=? 
   		and objection_sub_code=? order by cast(objection_code as integer) ASC ");
   $stv->bindParam(1, $filing_no, PDO::PARAM_STR);
   $stv->bindParam(2, $notification_date_all, PDO::PARAM_STR);
   $stv->bindParam(3, $ll, PDO::PARAM_STR);
   $stv->bindParam(4, $lls, PDO::PARAM_STR);
   $stv->bindParam(5, $llss, PDO::PARAM_STR);
   $stv->execute();
   $i=0;
   $j=1;
   while ($rowcc = $stv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
   {
   	 $objection_codeobbj1=$rowcc['objection_code'];
   	$objection_codeststus1=$rowcc['status'];
   	$comments1=$rowcc['comments'];

   	
     $sth=$db->prepare("select * from master_scrutiny_local where location_all=? and id_serno=?  order by id_serno ASC ");
  $sth->bindParam(1, $location_access, PDO::PARAM_STR);
  $sth->bindParam(2, $objection_codeobbj1, PDO::PARAM_STR);
  $sth->execute();
   while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
 $id_check1=$rowa['id_serno'];
 $check_list1=$rowa['name'];
 $display_all1=$rowa['display'];
}
   ?>       
          
            <tr>
  <td width="3%"><?php echo $j;?></td>
  <td width="60%"><?php echo $check_list1;?></td>
    <td width="7%"><?php echo $objection_codeststus1;?></td>
  <td width="30%"><?php echo $comments1;?></td>
  </tr>     
         
         <?php 
   	$j++;
   	} ?>         
                  </td></tr>
   
    
       
        <tr><td align="right" valign="top">
                  <b>Remarks:</b>
                  </td><td align="left">
  <textarea name="comment" onChange="makeUppercase(this)" cols="75" placeholder="Enter Remarks !!!!!" rows="5" 
  style="background-color: silver;">
</textarea>
                  </td></tr>  
                  
       <tr><td colspan="6"  >
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="./jquery.datetimepicker.css"/>
<style type="text/css">

.custom-date-style {
	background-color: red !important;
}

.input{	
}
.input-wide{
	width: 500px;
}

</style>
<h3>Select Date/Time : </h3>
	<input type="text" name="datetimepicker_mask" value="<?php echo $datetimepicker_mask;?>" id="datetimepicker_mask"/><br><br>

<script src="./jquery.js"></script>
<script src="./jquery.datetimepicker.full.js"></script>
<script>/*
window.onerror = function(errorMsg) {
	$('#console').html($('#console').html()+'<br>'+errorMsg)
}*/

$.datetimepicker.setLocale('en');

$('#datetimepicker_format').datetimepicker({value:'2015/04/15 05:03', format: $("#datetimepicker_format_value").val()});
$("#datetimepicker_format_change").on("click", function(e){
	$("#datetimepicker_format").data('xdsoft_datetimepicker').setOptions({format: $("#datetimepicker_format_value").val()});
});
$("#datetimepicker_format_locale").on("change", function(e){
	$.datetimepicker.setLocale($(e.currentTarget).val());
});

$('#datetimepicker').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
startDate:	'1986/01/05'
});
$('#datetimepicker').datetimepicker({value:'2015/04/15 05:03',step:10});

$('.some_class').datetimepicker();

$('#default_datetimepicker').datetimepicker({
	formatTime:'H:i',
	formatDate:'d.m.Y',
	//defaultDate:'8.12.1986', // it's my birthday
	defaultDate:'+03.01.1970', // it's my birthday
	defaultTime:'10:00',
	timepickerScrollbar:false
});

$('#datetimepicker10').datetimepicker({
	step:5,
	inline:true
});
/*
$('#datetimepicker_mask').datetimepicker({
	mask:'39/19/9999 29:59'
});*/
$('#datetimepicker_mask').datetimepicker({
	mask:'39/19/9999 29:59'
});

$('#datetimepicker1').datetimepicker({
	datepicker:false,
	format:'H:i',
	step:5
});
$('#datetimepicker2').datetimepicker({
	yearOffset:222,
	lang:'ch',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y/m/d',
	minDate:'-1970/01/02', // yesterday is minimum date
	maxDate:'+1970/01/02' // and tommorow is maximum date calendar
});
$('#datetimepicker3').datetimepicker({
	inline:true
});
$('#datetimepicker4').datetimepicker();
$('#open').click(function(){
	$('#datetimepicker4').datetimepicker('show');
});
$('#close').click(function(){
	$('#datetimepicker4').datetimepicker('hide');
});
$('#reset').click(function(){
	$('#datetimepicker4').datetimepicker('reset');
});
$('#datetimepicker5').datetimepicker({
	datepicker:false,
	allowTimes:['12:00','13:00','15:00','17:00','17:05','17:20','19:00','20:00'],
	step:5
});
$('#datetimepicker6').datetimepicker();
$('#destroy').click(function(){
	if( $('#datetimepicker6').data('xdsoft_datetimepicker') ){
		$('#datetimepicker6').datetimepicker('destroy');
		this.value = 'create';
	}else{
		$('#datetimepicker6').datetimepicker();
		this.value = 'destroy';
	}
});
var logic = function( currentDateTime ){
	if (currentDateTime && currentDateTime.getDay() == 6){
		this.setOptions({
			minTime:'11:00'
		});
	}else
		this.setOptions({
			minTime:'8:00'
		});
};
$('#datetimepicker7').datetimepicker({
	onChangeDateTime:logic,
	onShow:logic
});
$('#datetimepicker8').datetimepicker({
	onGenerate:function( ct ){
		$(this).find('.xdsoft_date')
			.toggleClass('xdsoft_disabled');
	},
	minDate:'-1970/01/2',
	maxDate:'+1970/01/2',
	timepicker:false
});
$('#datetimepicker9').datetimepicker({
	onGenerate:function( ct ){
		$(this).find('.xdsoft_date.xdsoft_weekend')
			.addClass('xdsoft_disabled');
	},
	weekends:['01.01.2014','02.01.2014','03.01.2014','04.01.2014','05.01.2014','06.01.2014'],
	timepicker:false
});
var dateToDisable = new Date();
	dateToDisable.setDate(dateToDisable.getDate() + 2);
$('#datetimepicker11').datetimepicker({
	beforeShowDay: function(date) {
		if (date.getMonth() == dateToDisable.getMonth() && date.getDate() == dateToDisable.getDate()) {
			return [false, ""]
		}

		return [true, ""];
	}
});
$('#datetimepicker12').datetimepicker({
	beforeShowDay: function(date) {
		if (date.getMonth() == dateToDisable.getMonth() && date.getDate() == dateToDisable.getDate()) {
			return [true, "custom-date-style"];
		}

		return [true, ""];
	}
});
$('#datetimepicker_dark').datetimepicker({theme:'dark'})


</script>
     </td></tr>  
 <tr><td colspan="6" >      
                	<input id="submit_final" type="submit" name="submit_final"
 class="submit" value=" SEND NOTICE " style="
    background-color: green;
    width:22%;
    color: #FFFFFF;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onClick="defect_submit();"/>  
    
                   </td></tr>                         
                   
                   </tbody></table></div></div>  
   
  </form> 
   
   
   
   
       




           
              </div>

        </div>
     
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

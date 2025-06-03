<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include("../db_inc2.php");
//session_start();

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


	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";


	$link_scrutiny_idaccess='1';

include '../db_inc2.php';
        
?>
<?php 
//include '../inheader.php';
//include '../insidebar.php';

?>
 <style>
body {
background-color: white;
}
h1 {
color: maroon;
margin-left: 40px;
}
@media print{
	#testdiv{
		display: none;
	}
}
</style>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
      
      <!-- Default box -->
      <div id="testdiv" style="visibility: visible;"><a href="javascript:window.print();"><font size="4" color="red">
Print</font></a>
</div>
      <div class="box">
        <div class="box-header with-border">
          <center><span><h3 class="box-title">Documents Report for <?php if($_SESSION['menuaccess_codeall'] =='2'){ echo "Scrutiny Clerk"; }else if($_SESSION['menuaccess_codeall'] =='11'){ echo "Assistant Registrar"; } ?></h3></span></center>
        </div>
        <div class="box-body">
       <div class="col-md-12">
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
        
        <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr style="background-color: #ccf2ff;">
                  <th>Sr No.</th>
                  <th>Date Of Filing</th>
                  <th>Diary No.</th>
				  <th>Document Upload Date</th>
                    <th>Miscellaneous No.</th>
                    <th>Case No.</th>
                    <th>Title Of Case</th>
                    <th>Section</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
				   <?php 				   				   
			$ll ='NA';
            $llpp='0';
            $SER_COUT='1'; 
			$SER_COUT3=1;		  
         
		  if($_SESSION['menuaccess_codeall'] =='2') //user 1
		  {
			 
		  $display='1';
		  $scrutiny_d='0';
		  //$doc_level='NULL';
		  
          $st51=$dbonline->prepare("select distinct(miscellaneous_ref_no) as miscellaneous_ref_no,filing_no,document_filed_date
          from document_upload where filing_no !=? and display=? and scrutiny=? 
          and doc_level IS NULL and miscellaneous_ref_no IS NOT NULL order by document_filed_date ASC");
          //$st51=$dbonline->prepare("select distinct(D.filing_no) as filing_no from document_upload D, e_case_detail E where D.filing_no=E.filing_no and E.location_id=? 
		  //and D.filing_no !=? and D.display=? and D.scrutiny=? and D.doc_level IS NULL order by filing_no DESC");
		  //$st51->bindParam(1, $schema_id, PDO::PARAM_STR);
		  $st51->bindParam(1, $ll, PDO::PARAM_STR);
          $st51->bindParam(2, $display, PDO::PARAM_STR);
		  $st51->bindParam(3, $scrutiny_d, PDO::PARAM_STR);
		  
		  //$st51->bindParam(4, $doc_level, PDO::PARAM_STR);
          $st51->execute();
		  }
		 

		 if($_SESSION['menuaccess_codeall'] =='11')//astt registar
		  { 
			
	     $display='1';
		 $scrutiny_d='0';
		 $doc_level='11';
		 
		
          $st51=$dbonline->prepare("select distinct(miscellaneous_ref_no) as miscellaneous_ref_no,filing_no,document_filed_date
          from document_upload  where filing_no !=?   and display=? and scrutiny=?
           and doc_level=? and miscellaneous_ref_no IS NOT NULL order by document_filed_date ASC");
		  $st51->bindParam(1, $ll, PDO::PARAM_STR);
          $st51->bindParam(2, $display, PDO::PARAM_STR);
		  $st51->bindParam(3, $scrutiny_d, PDO::PARAM_STR);
		  $st51->bindParam(4, $doc_level, PDO::PARAM_STR);
          $st51->execute();
		  }
          
          
          function get_caseno_doc($case_no,$casetype,$locode,$case_year){
            global $db;
            global $schemas;
            $casetypesql = $db->prepare("select case_type_desc_cis from case_type where id = '$casetype'");
                         $casetypesql->execute();
                         $case_type_short_name=$casetypesql->fetchColumn();
        
                          $case_type_short_name = strtoupper($case_type_short_name);
                        
                         $lcodesql ="select short_name from $schemas.bench_location where bench_location_code ='$locode'";
                         $lcodesql=$db->prepare($lcodesql);
                         $lcodesql->execute();
                         $lcodename = $lcodesql->fetchColumn();
                         $lcodename;
                         
                         return $case_no_final = $case_type_short_name.'/'.$case_no.'('.$lcodename.')'.$case_year;
                         
          }
          
	 $cou=1;
   while ($row51 = $st51->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
			
         
   $filing_no= htmlspecialchars($row51['filing_no']);
   $miscellaneous_ref_no= htmlspecialchars($row51['miscellaneous_ref_no']);
   $document_filed_date = htmlspecialchars($row51['document_filed_date']);
    list($year,$month,$day)=explode('-',$document_filed_date);
          $document_filed_date=$day.'/'.$month.'/'.$year;
   //$documentuploadmodelid= htmlspecialchars($row51['documentuploadmodelid']);
   //$uniqueid= htmlspecialchars($row51['uniqueid']);
 
   /*$st21=$db->prepare("select defects from $schemas.scrutiny_doc where filing_no =? ");
		
         
          $st21->bindParam(1, $filing_no, PDO::PARAM_STR);
          $st21->execute();
          while ($row2 = $st21->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
          
                  $doc_defects1 = htmlspecialchars($row2['defects']);
		
		  }*/

  
  $doc_case_no='';
		
		
		
         $st1=$db->prepare("select filing_no,dt_of_filing,case_type,pet_name,res_name,case_no,location_code,case_year from $schemas.case_detail where filing_no =? and case_no!=?  order by filing_no DESC");
         
          $st1->bindParam(1, $filing_no, PDO::PARAM_STR);
		   $st1->bindParam(2, $doc_case_no, PDO::PARAM_STR);
		  
          $st1->execute();
          while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
          
                  $filing_no2 = htmlspecialchars($row['filing_no']);
                  $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
                  $case_type=htmlspecialchars($row['case_type']);
			            $E_nameP=htmlspecialchars($row['pet_name']);
			            $E_nameR=htmlspecialchars($row['res_name']);
		     
                  $case_no = htmlspecialchars($row['case_no']);
				          $case_no = ltrim($case_no,0);
				          $casetype = htmlspecialchars($row['case_type']);
				          $locode = htmlspecialchars($row['location_code']);
                  $case_year = htmlspecialchars($row['case_year']);
                  
                  $case_no_doc = get_caseno_doc($case_no,$casetype,$locode,$case_year);
  
  
  list($year,$month,$day)=explode('-',$dt_of_filing);
          $filing_date_all2=$day.'/'.$month.'/'.$year;
          
       
		  
		  $st25=$dbonline->prepare("select * from e_case_detail_fees where filing_no =? ");
                      $st25->bindParam(1, $filing_no2, PDO::PARAM_STR);
                      $st25->execute();
$i=0;$r='';
                      while ($row25= $st25->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                            $E_sec_id=$row25['sec_id'];
                       if($E_sec_id == 0 || $E_sec_id == ''){
						   $r = '----';
					   }else
                          if($E_sec_id > '0')
                          {
                      $st35=$dbonline->prepare("select * from master_section_act where id=? ");
                      $st35->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                      $st35->execute();
                      
                      while ($row35 = $st35->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {
                      	  $E_add_sec_id=$row35['section_companies'];                      
                            $r.=$E_add_sec_id.',';
                         
                       
                      }
                          }
                      }
  
  $filing_nosend=$filing_no2.'-'.$qq1cc.'-'.$miscellaneous_ref_no;
                    $filing_no_send=base64_encode($filing_nosend); 

		if($filing_no!='')
		{
	//if($doc_defects1=='N')
//{
	
?>	
	<!--	 <tr style="background-color: #BDFCC9;">   -->
<?php
//}
//if($doc_defects1=='Y')
//{
?>
<tr style="background-color: #ccf2ff;">   
	<?php
///}
	
?>
<td><?php echo htmlspecialchars($SER_COUT3++).".";?></td>
		  <td><?php if($filing_date_all2 =='11/11/1111' OR $filing_date_all2 =='//'){$filing_date_all2="";}else {echo htmlspecialchars($filing_date_all2);}?></td>

		 <?php
       $countdocsql=$dbonline->prepare("select count(*) from document_upload where miscellaneous_ref_no='$miscellaneous_ref_no' and filing_no='$filing_no2' and scrutiny='0' and display='1'");
      $countdocsql->execute();
	  $number_of_rows = $countdocsql->fetchColumn();
		 ?>
		 <td><?php echo htmlspecialchars($filing_no2);
		 echo "<br><span style='color:red'>";
		 echo "(No.of Docs - ".$number_of_rows.")";
echo "</span>";		 ?></td>
<td>
<?php
if($document_filed_date!='' || $document_filed_date!=null){echo htmlspecialchars($document_filed_date);}else{echo "-----";}
?>
</td>
<td><?php echo htmlspecialchars($miscellaneous_ref_no); ?></td>
	 
<td><?php echo $case_no_doc; ?></td>
	 <td><?php echo htmlspecialchars_decode(strtoupper($E_nameP))."&nbsp;<font color='blue' size='2'><br> Vs. <br> </font>&nbsp;".htmlspecialchars_decode(strtoupper($E_nameR));?></td>        
				 <td><?php echo rtrim($r,','); ?></td>				
		     
<?php 					
		}
		  }
  
    }
    ?>           
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
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
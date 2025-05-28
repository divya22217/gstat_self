<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");

session_start();
$server_date= date('Y-m-d');
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
include '../inheader.php';
include '../insidebar.php';

?>

 <script >
 function OpenDEFECT(val1)
{
	alert(val1);
document.getElementById("applno1").value=val1; 
document.getElementById("def").submit();
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
          <h3 class="box-title">Defective Cases </h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
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
                  <tr>
                  <th>Sr No.</th>
                  <th>Date Of Presentation</th>
                  <th>Defective Date</th>
				  <th>No of Days </th>
                    <th>Dairy No.</th>
                    <th>Title Of Case</th>
                    <th>Section</th>
					
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
				 
                  <td colspan="6">
  <?php 
 
 
  
	$status ='Y';
	$statusa ='2';
	$st=$db->prepare("select count(filing_no) from $schemas.scrutiny where  defects=? and level_level=?");
	$st->bindParam(1, $status, PDO::PARAM_STR);
	$st->bindParam(2, $statusa, PDO::PARAM_STR);
	$st->execute();
        $filing_no_found = $st->fetchColumn();
            
  
  if($filing_no_found =='' OR $filing_no_found =='0')
  {
  
  	echo "</br><font color='red'><b>NO RECORD AVAILABLE !!!</b></font>";
  	
  	//die();
  
  }
  ?>
  
  </td></tr>
                  
      <?php 
      if($filing_no_found !='0')
      {
      	
	$status ='Y';
	$statusa ='2';
	$st=$db->prepare("select * from $schemas.scrutiny where  defects=? and level_level=? ");
	$st->bindParam(1, $status, PDO::PARAM_STR);
	$st->bindParam(2, $statusa, PDO::PARAM_STR);
	$st->execute();
          
      $SER_COUT=1;
      while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
      {
          $filing_no_check = htmlspecialchars($row['filing_no']);
          $notification_date_all=$row['notification_date'];
          
     
          $st1=$db->prepare("select * from e_case_detail_local where filing_no=? order by filing_no DESC");
          $st1->bindParam(1, $filing_no_check, PDO::PARAM_STR);
          //$st1->bindParam(2, $location_access, PDO::PARAM_STR);
          $st1->execute();
          while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
          
              $filing_no = htmlspecialchars($row['filing_no']);
              $pet_name = htmlspecialchars($row['pet_name']);
              $res_name = htmlspecialchars($row['res_name']);
              $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
              $case_type=htmlspecialchars($row['case_type']);
            //  $case_no=$row['case_no'];
            //  $location_id=$row['location_id'];
          }
          
          list($year,$month,$day)=explode('-',$dt_of_filing);
          $filing_date_all=$day.'/'.$month.'/'.$year;
          
          list($year,$month,$day)=explode('-',$notification_date_all);
          $defect_date_all=$day.'/'.$month.'/'.$year;
          
      ?>            
                  <?php 
                 $d3=$server_date;
				 $d2=$notification_date_all;//23-11-2017
				  $datetime1 = new DateTime($d2);
					$datetime2 = new DateTime($d3);
					$interval = $datetime1->diff($datetime2);
				$kk1= $interval->format('%R%a');
                  list($kk2,$kk)=explode('+',$kk1);
				 
                  ?>
                  <tr style="background-color: #f8c6bf;">
                  <td><?php echo htmlspecialchars($SER_COUT);?></td>
                  <td><?php echo htmlspecialchars($filing_date_all);?></td>
                  <td><?php echo htmlspecialchars($defect_date_all);?></td>
				  <td><?php echo $kk;?></td>
                    <td><?php echo htmlspecialchars($filing_no);?></td>
					
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
                    
      // echo $sql="select * from sms where filing_no='$filing_no'";               
 $st2=$dbonline->prepare("select * from sms where filing_no=? ");
                      $st2->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st2->execute();
$i=0;$r='';
                      while ($row2= $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                           $path=$row2['pdf_path'];
						   $filenm=$row2['file_name'];
						
					  }
                      ?>
                      </div>
                    </td>
					
				<td ><a href="viewpdf.php?path=<?php  echo $path.$filenm;?>" target="_blank">
			<img src="pdf.png" border='0' ></a></td>
                    <?php
	
	?>
                    <?php
                    $status ='0';
      	$statusa ='1';
      	$stccc=$dbonline->prepare("select count(scrutiny) from e_case_detail where scrutiny=? and scrutiny_comp=? and filing_no=? ");
      	$stccc->bindParam(1, $status, PDO::PARAM_STR);
      	$stccc->bindParam(2, $statusa, PDO::PARAM_STR);
        $stccc->bindParam(3, $filing_no, PDO::PARAM_STR);
        
      	$stccc->execute();
      	$scrutiny_varifyncltonline_all = $stccc->fetchColumn();
        if($scrutiny_varifyncltonline_all > '0')
        {
                    ?>
                    
                    <?php 
                   
                    $filing_nosend=$filing_no.'-'.$qq1cc;
                    $filing_no_send=base64_encode($filing_nosend);
                    ?>
                    <td><h3><span class="label label-info">
                    <a style="color: #FFFFFF;" href="./scrutiny_revarify_case.php?filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">Scrutiny Verify</a>
                    </span></h3></td>
        <?php
        }
        ?>
                    
                    
                  </tr>
      <?php 
                  //}
      $SER_COUT++;
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
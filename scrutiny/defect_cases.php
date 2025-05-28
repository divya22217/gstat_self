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


 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
      
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Defective Cases (More Than 7 Days)</h3>

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
                  <th>Date Of Filing</th>
                  <th>Defective Date</th>
                    <th>Dairy No.</th>
                    <th>Title Of Case</th>
                    <th>Section</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
                   <tr><td colspan="6">
  <?php 
 
 
  
	$status ='Y';
	$statusa ='1';
	$st=$db->prepare("select count(filing_no) from $schemas.scrutiny where  defects=? and level_level=?");
	$st->bindParam(1, $status, PDO::PARAM_STR);
	$st->bindParam(2, $statusa, PDO::PARAM_STR);
	$st->execute();
     $filing_no_found = $st->fetchColumn();
            
  
  if($filing_no_found =='' OR $filing_no_found =='0')
  {
  
  	//echo "</br><font color='red'><b>NO RECORD AVAILABLE !!!</b></font>";
  	
  	//die();
  
  }
  ?>
  
  </td></tr>
                  
      <?php 
      if(true)
      {
      	/*
      	$status ='Y';
      	$statusa ='1';
      	$st=$db->prepare("select notification_date from $schemas.scrutiny where  defects=? and level_level=?");
      	$st->bindParam(1, $status, PDO::PARAM_STR);
      	$st->bindParam(2, $statusa, PDO::PARAM_STR);
      	$st->execute();
      	$notification_date_all = $st->fetchColumn();
      	*/
      	
      	// $addDate=date('Y-m-d', strtotime($cur_date. ' - 8 days'));
      	 $addDate=date('Y-m-d', strtotime($cur_date. ' -8 days'));

   
         


	$status ='Y';
	$statusa ='3';
	$st=$db->prepare("select * from $schemas.scrutiny where  defects=? and level_level=?");
	$st->bindParam(1, $status, PDO::PARAM_STR);
	$st->bindParam(2, $statusa, PDO::PARAM_STR);
	$st->execute();
          
      $SER_COUT=1;
      while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
      {
          $filing_no_check = htmlspecialchars($row['filing_no']);
          $notification_date_all=$row['notification_date'];
          
     
          $st1=$db->prepare("select * from e_case_detail_local where scrutiny_comp3='3' and filing_no=? order by filing_no DESC");
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
                  if(true){
                  
                  ?>
                  <tr>
                  <td><?php echo htmlspecialchars($SER_COUT);?></td>
                  <td><?php echo htmlspecialchars($filing_date_all);?></td>
                  <td><?php echo htmlspecialchars($defect_date_all);?></td>
                    <td><?php echo htmlspecialchars($filing_no);?></td>
                    <td><?php echo htmlspecialchars_decode(strtoupper($pet_name)).'&nbsp; VS &nbsp;'.htmlspecialchars_decode(strtoupper($res_name));?></td>
                    
                    <td>
                      <div class="sparkbar" data-color="#00a65a" data-height="20">
                     <?php 
                      $st2=$db->prepare("select * from e_case_detail_fees where filing_no=? ");
                      $st2->bindParam(1, $filing_no_check, PDO::PARAM_STR);
                      $st2->execute();
$i=0;$r='';
                      while ($row2= $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                          $E_sec_id=$row2['sec_id'];
                       
                      $st3=$db->prepare("select * from master_section_act where id=? ");
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
                      </div>
                    </td>
                    <?php 
					
					
                   
                    $filing_nosend=$filing_no.'-'.$qq1cc;
                    $filing_no_send=base64_encode($filing_nosend);
					
					
					 $st21=$db->prepare("select * from $schemas.regvarify_sevenday where filing_no=?  ");
                      $st21->bindParam(1, $filing_no_check, PDO::PARAM_STR);
                      $st21->execute();
						$i=0;$r='';
                      while ($row21= $st21->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                          $sevenid=$row21['id'];
					  }
                    ?>
					<?php if($sevenid='' || $sevenid==0 )
					{
						?>
                    <td><h4 style="margin-top:0px;"><span class="label label-info">
                    <a style="color: #FFFFFF;" href="./reg_varify_case.php?filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">Scrutiny Verify</a>
                    </span></h4></td>
					<?php
					}
				
					
					else
					{
						?>
						<td><h4 style="margin-top:0px;"><span class="label label-info">
                    <a style="color: RED;">Scrutiny Verify</a>
                    </span></h4></td>
					<?php 
					}
					?>

                  </tr>
      <?php 
                  }
				  
      $SER_COUT++;
          }
		  
      }
     
      ?>   
        <?php if($addDate < $notification_date_all)
      {?>    
      <tr>
      <td colspan="10">
      <!--NO RECORD AVAILABLE !!!!!
-->      
</td>
      </tr>  
      <?php } ?>         
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

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
<script language="javascript">
function submitForm()
{
 	with(document.frm)
	{
		action = "review_defect_cases.php";
		submit();
	}
}
</script>
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Review Defect Cases More then 7 days </h3>
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
<form name="frm" method="post" action="review_defect.php" >
		<tr>
<td align="left">
<font color="red">*</font></span>Date</font>
</td>
<td>
<?php  $from_list_date = isset($_REQUEST['from_list_date']) ? $_REQUEST['from_list_date'] :'';?>
<input type="text" id="from_list_date" name="from_list_date"  class="datepicker"
 size="10" value="<?php print htmlspecialchars($from_list_date); ?>"  />


<script src="../src/calendar.js">


</script>
<input id="submit_final" type="submit" name="go"
 class="submit" value="GO" style="
    background-color: green;
    color: #FFFFFF;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;" onClick="return submitForm();"/>

 </td></tr>
<?php 

$sth = $db->prepare("select title,startdate from $schemas.calendar");
$sth->execute();
while ($rw2 = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$date=$rw2['startdate'];
	$dd=explode("-",$date);
$datenew=$dd[0]."+'-'+".$dd[1]."+'-'+".$dd[2];
	$title=$rw2['title'];
	 $datformat="{date:".$datenew.", value:'".$title."'},";
	  $datformat1=$datformat1.$datformat;
	  
  
}
$datformat1=rtrim($datformat1,",");
 $newdata="[".$datformat1."];";	
 
 
 
?>


 <script>
        var now = new Date();
        var year = now.getFullYear();
        var month = now.getMonth() + 1;
        var date = now.getDate();

       
        var data =<?php echo $newdata; ?>;
 		

        // inline
        var $ca = $('#one').calendar({
            // view: 'month',
            width: 320,
            height: 320,
            // startWeek: 0,
            // selectedRang: [new Date(), null],
            data: data,
            monthArray: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            date: new Date(2016, 9, 31),
            onSelected: function (view, date, data) {
                console.log('view:' + view)
                console.log('date:' + date)
                console.log('data:' + (data || '?'));
            },
            viewChange: function (view, y, m) {
                console.log(view, y, m)

            }
        });

        // picker
        $('#two').calendar({
            trigger: '#from_list_date',
            // offset: [0, 1],
            zIndex: 999,
            data: data,
            onSelected: function (view, date, data) {
                console.log('event: onSelected')
            },
            onClose: function (view, date, data) {
                console.log('event: onClose')
                console.log('view:' + view)
                console.log('date:' + date)
                console.log('data:' + (data || '?'));
            }
        });

        // Dynamic elements
        var $demo = $('#demo');
        var UID = 1;
        $('#add').click(function () {
            $demo.append('<input id="input-' + UID + '"><div id="ca-' + UID + '"></div>');
            $('#ca-' + UID).calendar({
                trigger: '#input-' + UID++
            })
        })
					
    </script>


                <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                   <thead>
                  <tr>
                  <th>Sr No.</th>
                  <th>Last Notice Date</th>
				  <th>Appear Date</th>
                    <th>Dairy No.</th>
                    <th>Title Of Case</th>
                    <th>Section</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
                   <tr><td colspan="6">
  <?php 
 //$action =NULL;
	$status ='Y';
	$statusa ='1';
list($day,$month,$year)=explode('/',$from_list_date);
$from_list_date=$year.'-'.$month.'-'.$day;
 $st1="select count(filing_no) from delhi.regvarify_sevenday where notify_date='$from_list_date' and action IS NULL";

	$st1=$db->prepare($st1);
	//$st->bindParam(1, $from_list_date, PDO::PARAM_STR);
	//$st->bindParam(2, $action, PDO::PARAM_STR);
	//$st->bindParam(2, $statusa, PDO::PARAM_STR);
	$st1->execute();
       $filing_no_found = $st1->fetchColumn();
            
  
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
	$statusa ='1';
	$st=$db->prepare("select * from $schemas.regvarify_sevenday where  notify_date=? ");
	$st->bindParam(1, $from_list_date, PDO::PARAM_STR);
	//$st->bindParam(2, $statusa, PDO::PARAM_STR);
	$st->execute();
        
      $SER_COUT=1;
      while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
      {
          $filing_no_check = htmlspecialchars($row['filing_no']);
		  $entry_date = htmlspecialchars($row['entry_date']);
		  $appear_date = htmlspecialchars($row['notification_date']);
         // $notification_date_all=$row['notification_date'];
          
     
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
          
          list($year,$month,$day)=explode('-',$entry_date);
          $entry_date=$day.'/'.$month.'/'.$year;
          
          list($year,$month,$day)=explode('-',$appear_date);
          $appear_date=$day.'/'.$month.'/'.$year;
          
      ?>            
                  <?php 
                  //if($addDate >= $notification_date_all){
                  
                  ?>
                  <tr>
                  <td><?php echo htmlspecialchars($SER_COUT);?></td>
                  <td><?php echo htmlspecialchars($entry_date);?></td>
                  <td><?php echo htmlspecialchars($appear_date);?></td>
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
                    ?>
                    <td><h3><span class="label label-info">
                    <a style="color: #FFFFFF;" href="./review_final.php?filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">Scrutiny Verify</a>
                    </span></h3></td>
                  </tr>
      <?php 
                 // }
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
  include '../bfooter.php';
  ?>
  <?php } ?>
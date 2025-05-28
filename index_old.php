<script type="text/javascript" language="javascript">
  function change()
  {
    with(document.frm)
    {
      action="index.php";
      submit();

    }
  }

  function submitForm()
  {
    with(document.frm)
    {
      action="index.php";
      submit();
      document.frm_doc_search.submit1.disabled = true;
      document.frm_doc_search.submit1.value = 'Please Wait...';
      return true;
    }
  }

  function DisableBackButton() {
    window.history.forward()
  }
  DisableBackButton();
  window.onload = DisableBackButton;
  window.onpageshow = function(evt) { if (evt.persisted) DisableBackButton() }
  window.onunload = function() { void (0) }
  function submitForm3()
  {
    with(document.frm)
    {		
      action = "index.php";
      submit();
    }
  }

  function reset_case()
  {
   $("#filing_no").val('');
   $("#selected_case_type").val('');
   $("#from_date").val('');
   $("#to_date").val('');
   with(document.frm)
   {		
    action = "index.php";
    submit();
  }
}
</script>

<style>
  .load_container {
    background: rgba(0, 0, 0, 0.50);
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    z-index: 99;
    left: 0;
  }
  .load_container .loader {
    display: block;
    width: 60px;
    height: 60px;
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    margin: auto;
  }
</style>
<div class="load_container" >
  <img class="loader" src="loading-indicator.gif">
</div>
<?php

//echo "new";
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d');

 include("./db_inc1.php");

 include_once('custom/custom_function.php');


 $_SESSION['user'];

 $_SESSION['location'];
 $leveladd=$_SESSION['level_level'];
 $localadmin=$_SESSION['localadmin'];
 $main_id=$_SESSION['main_id'];
 $schemas=htmlspecialchars($_SESSION['schema_name']);
 $userid=$_SESSION['id'];
 $sessionUserType=htmlspecialchars($_SESSION['id']);
 $location_access=$_SESSION['location'];
 $schema_id=$_SESSION['schema_idccc'];

 if($_SESSION['user'] == '' and $_SESSION['location'] =='')
 {
  echo "Access Problem....."; 
  header("Location: ./login.php");
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
	
  $password_changed = $db->prepare("select password_changed_at from users_cis where username = ?");
  $password_changed->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
  $password_changed->execute();
  $password_changed_at = $password_changed->fetchColumn();
  if(empty($password_changed_at)){
    header("Location: update_password.php");
  }

  if($_SESSION['menuaccess_codeall'] =='20'){
    header("location:filed.php");
    die;
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

  function display_filing_no($filing_no_display){
    $lastFour =  substr($filing_no_display,-4);
    $lastFive = substr($filing_no_display,-9,-4);
    $left = substr($filing_no_display,-16,-9);
    return $dis_fil_no = $left.'/<b>'.$lastFive.'/'.$lastFour.'</b>';

  }


     $sessionUserType=htmlspecialchars($_SESSION['id']);


     $curYear = htmlspecialchars(date("Y"));
     $curMonth = htmlspecialchars(date("m"));
     $curDay = htmlspecialchars(date("d"));
     $cur_date = "$curYear-$curMonth-$curDay";
     $cur_date1 ="$curDay/$curMonth/$curYear";


     $link_scrutiny_idaccess='1';



     ?>
     <?php

     include 'header.php';


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
           div.hidden {
            display: none;
          }
      </style>
     <!-- Content Wrapper. Contains page content -->
     <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <form name="frm" method="post" >
        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-12">
              <!-- MAP & BOX PANE -->

              <!-- /.box -->
              <div class="row">

                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- TABLE: LATEST ORDERS -->
              <?php 
              $hash2=$_REQUEST['hash2'];

              if($hash2)
              {
                $c_case=htmlspecialchars(base64_decode($hash2));
                if($c_case=='R'){ $showradio=4;}
                if($c_case=='C'){ $showradio=2;}
                if($c_case=='F'){ $showradio=1;}
        	//echo $c_case=$hash3[0];

              }else{
               $showradio=1;
             }

             ?>
             <?php 

             if($localadmin =='0' and $_SESSION['menuaccess_codeall'] =='11')
             {


               $_SESSION['qqcc'] = rand();
               $qq1cc=$_SESSION['qqcc'];

               ?>
               <div class="box box-info">


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
              <!-- /.box-header -->
              <div class="box-body">
                <div class="table-responsive">
                  <table class="table no-margin">
                    <thead>
      
        <div id="testdiv" style="visibility: visible;"><a href="javascript:window.print();"><font size="4" color="red">
        Print</font></a>

      </div>


      <?php  $c_case = isset($_REQUEST['c_case']) ? $_REQUEST['c_case'] : $showradio; ?>
      <tr>

       <td valign="top" align="center" colspan="12" >
        <input type="radio" name="c_case" value="1" onChange="javascript:submitForm3();" 
        <?php if($c_case==1)echo'checked'; ?> ><b>Fresh case for listing</b>&nbsp;&nbsp;

        <input type="radio" name="c_case" value="2" onChange="javascript:submitForm3();" 
        <?php if($c_case==2)echo'checked'; ?>
        ><b>Document scrutiny for court</b>&nbsp;&nbsp;

        <input type="radio" name="c_case" value="3" onChange="javascript:submitForm3();" 
        <?php if($c_case==3)echo'checked'; ?>
        ><b>IA Cases</b>&nbsp;&nbsp;
        <input type="radio" name="c_case" value="4" onChange="javascript:submitForm3();" 
        <?php if($c_case==4)echo'checked'; ?>
        ><b>Reports</b>&nbsp;&nbsp;
      </td>
    </tr>
    <?php
    if($c_case==1)
    {	  
      ?>
      <tr>
        <td  colspan="16" align="left">

          <font face="Verdana" size="2" > </font><font face="Verdana" size="2">Application/Petition:
            <?php $app_pet = isset($_REQUEST['app_pet']) ? $_REQUEST['app_pet'] :'P'; ?>

            <select id="app_pet" name="app_pet" onchange="javascript:submitForm3();" class="frm-field required" >
              <option value="A" <?php if($app_pet == 'A') { print " selected"; } ?> >Application</option>
              <option value="P" <?php if($app_pet == 'P') { print " selected"; } ?> >Petition</option>
            </select>

          </td>
        </tr>
        <tr>
          <td colspan="12"><font color="red">
          Total Petition/Application pending for scrutiny:</font><b><strong>
            <?php 
          }

          ?>
        </b>
      </strong>
    </td>
  </tr>
</form>
<?php if($c_case==1)
{	?>


  <tr>
    <th>Sr No.</th>
    <th>Date Of Filing</th>
    <th>Case Type</th>
    <th>Dairy No.</th>
    <th>Title Of Case</th>
    <th>Section</th>
    <th>Scrutiny Date</th>
    <th></th>
  </tr>
</thead>
<tbody>

  <?php 


  if(true)
  {


  	$defect_cases ='N';
   $status ='1';
   $statusa ='1';



   $st=$db->prepare("select * from $schemas.scrutiny where level_level=?");
   $st->bindParam(1, $status, PDO::PARAM_STR);
	//$st->bindParam(2, $defect_cases, PDO::PARAM_STR);
	//$st->bindParam(2, $statusa, PDO::PARAM_STR);
   $st->execute();

   $SER_COUT=1;
   while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
   {

    $filing_no_check = htmlspecialchars($row['filing_no']);
    $notif_date = htmlspecialchars($row['notification_date']);
    list($year,$month,$day)=explode('-',$notif_date);
    $notif_date=$day.'/'.$month.'/'.$year;
    $defects=$row['defects'];


    $scrut_comp3='3';

    if($app_pet=='A')
    {

      $st1=$db->prepare("select * from e_case_detail  where  filing_no=? AND (scrutiny_comp3=? OR scrutiny_comp3 IS NULL) and case_type  IN('13','5','6','8','10','11','12','18','20','21','22','24','26','27','28','31') order by dt_of_filing asc");
    }
    if($app_pet=='P')
    {

      $st1=$db->prepare("select * from e_case_detail  where  filing_no=? AND (scrutiny_comp3=? OR scrutiny_comp3 IS NULL) and case_type  IN('2','3','7','9','16','1','14','15','19','23','25','29','30') order by dt_of_filing asc");
    }


    $st1->bindParam(1, $filing_no_check, PDO::PARAM_STR);
    $st1->bindParam(2, $scrut_comp3, PDO::PARAM_STR);

          //$st1->bindParam(2, $location_access, PDO::PARAM_STR);
    $st1->execute();

    while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
    {

      $filing_no = htmlspecialchars($row['filing_no']);
      $pet_name = htmlspecialchars($row['pet_name']);
      $res_name = htmlspecialchars($row['res_name']);
      $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
      $case_type=htmlspecialchars($row['case_type']);

      if($case_type!='')
      {
        $st3=$db->prepare("select * from case_type where id=? ");
        $st3->bindParam(1, $case_type, PDO::PARAM_STR);
        $st3->execute();

        while ($row3 = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
        {
         $case_type_display=$row3['case_type_desc_cis'];  
       }
     }
            //  $case_no=$row['case_no'];
            //  $location_id=$row['location_id'];

     list($year,$month,$day)=explode('-',$dt_of_filing);
     $filing_date_all=$day.'/'.$month.'/'.$year;
     ?>            
     <?php if($defects =='N')
     { ?>  
      <tr style="background-color: #BDFCC9;">
      <?php }?>
      <?php if($defects =='Y')



      { ?>  
        <tr style="background-color: #f8c6bf;">
          <?php



          /*....*/

        }?>

        <td><?php echo htmlspecialchars($SER_COUT);?></td>
        <td><?php echo htmlspecialchars($filing_date_all);?></td>
        <td><?php echo $case_type_display;?></td>

        <!-- start of code for document count for fresh cases-->
        <?php
        $countfdocsql=$db->prepare("select count(*) from document_upload where filing_no='$filing_no' and scrutiny='0' and display='t'");
        $countfdocsql->execute();
        $number_of_rows_f = $countfdocsql->fetchColumn();
        ?>

        <td><?php echo display_filing_no($filing_no);
        if($case_type=='6' || $case_type=='22'){echo "<span style='color:red'><b>(R)</b></span>";}
        echo "<br><span style='color:red'>";
        echo "(No.of Docs - ".$number_of_rows_f.")";
        echo "</span>";	
        ?></td>				  

        <td><?php
        $pet_name= htmlspecialchars_decode($pet_name,ENT_NOQUOTES);
        $res_name= htmlspecialchars_decode($res_name,ENT_NOQUOTES);
        echo strtoupper($pet_name).'&nbsp; Vs. &nbsp;'.strtoupper($res_name);?></td>

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
       <td><?php echo htmlspecialchars($notif_date);?></td>
       <?php 

       $filing_nosend=$filing_no.'-'.$qq1cc;
       $filing_no_send=base64_encode($filing_nosend);

       ?>
       <td><h3 style="margin-top:5px;"><span class="label label-info">
        <a style="color: #FFFFFF;" href="./scrutiny/varify_cases.php?ccase=<?php echo htmlspecialchars($c_case);?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">Scrutiny Verify</a>
      </span></h3></td>
    </tr>
    <?php 
    $SER_COUT++;
  }
}
}
?>            

<tr><td colspan="6">
  <?php 
  

  $status ='1';
  $statusa ='2';
  $defect_cases='Y';
  $st=$db->prepare("select count(filing_no) from $schemas.scrutiny where  level_level=? and defects=?");
	//$st->bindParam(1, $status, PDO::PARAM_STR);
	//$st->bindParam(2, $defect_cases, PDO::PARAM_STR);
  $st->bindParam(1, $statusa, PDO::PARAM_STR);
  $st->bindParam(2, $defect_cases, PDO::PARAM_STR);
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
if($filing_no_found !='0')
{
 $defect_cases ='Y';
 $status ='2';
 $statusa ='1';
 $st=$db->prepare("select * from $schemas.scrutiny where level_level=? and defects=?");
 $st->bindParam(1, $status, PDO::PARAM_STR);
 $st->bindParam(2, $defect_cases, PDO::PARAM_STR);
	//$st->bindParam(2, $statusa, PDO::PARAM_STR);
 $st->execute();

 $SER_COUT=1;
 while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
   $filing_no_check = htmlspecialchars($row['filing_no']);
   $filing_no = htmlspecialchars($row['filing_no']);
   $notif_date = htmlspecialchars($row['notification_date']);
   list($year,$month,$day)=explode('-',$notif_date);
   $notif_date=$day.'/'.$month.'/'.$year;
   $defects=$row['defects'];


   if($app_pet=='A')
   {


     $st1="select * from e_case_detail  where  filing_no=? and case_type  IN('13','5','6','8','10','11','12','18','20','21','22','24','26','27','28','31') order by dt_of_filing asc";
   }
   if($app_pet=='P')
   {


    $st1="select * from e_case_detail  where  filing_no=? and case_type  IN('2','3','7','9','16','1','14','15','19','23','25','29','30') order by dt_of_filing asc";
  }



          //$st1=$db->prepare("select * from e_case_detail where filing_no=? order by dt_of_filing desc");
  $st1=$db->prepare($st1);
  $st1->bindParam(1, $filing_no_check, PDO::PARAM_STR);
          //$st1->bindParam(2, $location_access, PDO::PARAM_STR);
  $st1->execute();
  if($st1->rowCount()>0){
    $row = $st1->fetch();
          //{

             //echo  $filing_no = htmlspecialchars($row['filing_no']);
    $pet_name = htmlspecialchars($row['pet_name']);
    $res_name = htmlspecialchars($row['res_name']);
    $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
    $case_type=htmlspecialchars($row['case_type']);
    if($case_type!='')
    {
      $st3=$db->prepare("select * from case_type where id=? ");
      $st3->bindParam(1, $case_type, PDO::PARAM_STR);
      $st3->execute();

      while ($row3 = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
      {
       $case_type_display=$row3['case_type_desc_cis'];  
     }
   }

            //  $case_no=$row['case_no'];
            //  $location_id=$row['location_id'];
          //}

   list($year,$month,$day)=explode('-',$dt_of_filing);
   $filing_date_all=$day.'/'.$month.'/'.$year;
   ?>            
   <?php if($defects =='N')
   { ?>  
    <tr style="background-color: #BDFCC9;">
    <?php }?>
    <?php if($defects =='Y')
    { ?>  
      <tr style="background-color: #f8c6bf;">
      <?php }?>
      <td><?php echo htmlspecialchars($SER_COUT);?></td>
      <td><?php echo htmlspecialchars($filing_date_all);?></td>
      <td><?php echo htmlspecialchars($case_type_display);?></td>

      <td><?php echo display_filing_no($filing_no);?></td>
      <td><?php

      $pet_name= htmlspecialchars_decode($pet_name,ENT_NOQUOTES);
      $res_name= htmlspecialchars_decode($res_name,ENT_NOQUOTES);


      echo strtoupper($pet_name).'&nbsp; Vs. &nbsp;'.strtoupper($res_name);?></td>

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
     <td><?php echo htmlspecialchars($notif_date);?></td>
     <td><h3><span class="label label-info">
      <a style="color: yellow;">Action Taken</a>
    </span></h3></td>

  </tr>
  <?php 
  $SER_COUT++;
}

}
}
}
?> 
<!-- start for ia cases in AR-->
<?php if($c_case==3)
{	
  ?>


  <tr>
    <th>Main Filing No.</th>
    <th>D O F</th>
    <th>IA No.</th>
    <th>Title Of Case</th>
    <th>Section</th>
    <th>Date of Scrutiny</th>
  </tr>
</thead>
<tbody>
 <tr><td colspan="6">


 </td></tr>

 <?php 


 if(true)
 {


   $defect_cases ='N';
   $status ='111';
   $statusa ='1';


	//echo "select * from $schemas.scrutiny where level_level='$status'";
   $st=$db->prepare("select * from $schemas.scrutiny_ia where level_level=?");
   $st->bindParam(1, $status, PDO::PARAM_STR);
	//$st->bindParam(2, $defect_cases, PDO::PARAM_STR);
	//$st->bindParam(2, $statusa, PDO::PARAM_STR);
   $st->execute();

   $SER_COUT=1;
   while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
   {  
    $filing_no_check = htmlspecialchars($row['filing_no']);
    $defects=$row['defects'];
		   //$ref_no_ia=$row['ref_no_ia'];
    $main_filing_no=$row['filing_no'];
    $ia_id = $row['ia_id'];
    $note_date = $row['notification_date'];
    list($year,$month,$day)=explode('-',$note_date);
    $note_date=$day.'/'.$month.'/'.$year;

    $scrut_comp3='3';

		   //get ia_ref_no
    $get_ia_no_unique ="select ia_filing_no from e_ia_details where ia_id='$ia_id'";
    $get_ia_no_unique=$db->prepare($get_ia_no_unique);
    $get_ia_no_unique->execute();
    $get_ia_no_unique = $get_ia_no_unique->fetchColumn();


          //echo $rr = "select * from e_case_detail  where  filing_no='$filing_no_check' AND (scrutiny_comp3='$scrut_comp3' OR scrutiny_comp3 IS NULL) and case_type
		  //IN('13','5','6','8','10','11','12','18','20','21','22','24','26','27','28') order by dt_of_filing desc";

		  //$st1=$db->prepare("select * from e_case_detail  where  filing_no=? AND (scrutiny_comp3=? OR scrutiny_comp3 IS NULL) and case_type
		  //IN('13','5','6','8','10','11','12','18','20','21','22','24','26','27','28') order by dt_of_filing desc");

	      //$st1=$db->prepare("select * from e_case_detail  where  filing_no=?");
    $st1=$db->prepare("select * from $schemas.case_detail  where  filing_no=?");

    $st1->bindParam(1, $filing_no_check, PDO::PARAM_STR);
		  //$st1->bindParam(2, $scrut_comp3, PDO::PARAM_STR);

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



      list($year,$month,$day)=explode('-',$dt_of_filing);
      $filing_date_all=$day.'/'.$month.'/'.$year;
      ?>            
      <?php if($defects =='N')
      { ?>  
        <tr style="background-color: #BDFCC9;">
        <?php }?>
        <?php if($defects =='Y')



        { ?>  
          <!--<tr style="background-color: #BDFCC9;"><td><?php //echo $main_filing_no; ?></td></tr>-->
          <tr style="background-color: #f8c6bf;">

            <?php          

            /*....*/

          }?>

          <td><?php echo "<b>".htmlspecialchars($SER_COUT)."</b>.&nbsp".display_filing_no($main_filing_no);
          ?></td>
          <td><?php echo htmlspecialchars($filing_date_all);?></td>


          <!-- start of code for document count for ia cases-->
          <?php
		 //echo $rr = "select count(*) from document_upload where filing_no='$filing_no' and scrutiny='0' and display='t'";
          $countfdocsql=$db->prepare("select count(*) from document_upload where filing_no='$filing_no' and scrutiny='0' and display='t'");
          $countfdocsql->execute();
          $number_of_rows_f = $countfdocsql->fetchColumn();
          ?>

          <td><?php echo display_filing_no($get_ia_no_unique);
          echo "<br><span style='color:red'>";
          echo "(No.of Docs - ".$number_of_rows_f.")";
          echo "</span>";	
          ?></td>				  

          <td><?php 
          $pet_name= htmlspecialchars_decode($pet_name,ENT_NOQUOTES);
          $res_name= htmlspecialchars_decode($res_name,ENT_NOQUOTES);

          echo strtoupper($pet_name).'&nbsp; Vs. &nbsp;'.strtoupper($res_name);?></td>

          <td>
            <div class="sparkbar" data-color="#00a65a" data-height="20">
              <?php 
					  //echo $rr = "select * from e_case_detail_fees where filing_no='$filing_no_check' ";
              $st2=$db->prepare("select * from e_case_detail_fees where filing_no=? ");
              $st2->bindParam(1, $filing_no_check, PDO::PARAM_STR);
              $st2->execute();
					  //echo "ooooooooo";

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
         <td>
          <?php echo htmlspecialchars($note_date); ?>
        </td>
        <?php 

        $filing_nosend=$filing_no.'-'.$qq1cc.'-'.$ia_id;
        $filing_no_send=base64_encode($filing_nosend);

        ?>
        <td><h3 style="margin-top:5px;"><span class="label label-info">
          <a style="color: #FFFFFF;" href="./scrutiny/varify_cases.php?ccase=<?php echo htmlspecialchars($c_case);?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">Scrutiny Verify</a>
        </span></h3></td>
      </tr>
      <?php 
      $get_ia_details=$db->prepare("select * from e_case_detail  where  filing_no=?");

      $st1->bindParam(1, $filing_no_check, PDO::PARAM_STR);
		  //$st1->bindParam(2, $scrut_comp3, PDO::PARAM_STR);

          //$st1->bindParam(2, $location_access, PDO::PARAM_STR);
      $st1->execute();

      while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
      {

      }


      $SER_COUT++;
    }
  }
}
?>            

<tr><td colspan="6">
  <?php 
  

  $status ='1';
  $statusa ='2';
  $defect_cases='Y';
  $st=$db->prepare("select count(filing_no) from $schemas.scrutiny where  level_level=? and defects=?");
	//$st->bindParam(1, $status, PDO::PARAM_STR);
	//$st->bindParam(2, $defect_cases, PDO::PARAM_STR);
  $st->bindParam(1, $statusa, PDO::PARAM_STR);
  $st->bindParam(2, $defect_cases, PDO::PARAM_STR);
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
      //Action taken in IA Cases
      /*if($filing_no_found !='0')
      {
  	$defect_cases ='Y';
	$status ='2';
	$statusa ='1';
	$st=$db->prepare("select * from $schemas.scrutiny where level_level=? and defects=?");
	$st->bindParam(1, $status, PDO::PARAM_STR);
	$st->bindParam(2, $defect_cases, PDO::PARAM_STR);
	//$st->bindParam(2, $statusa, PDO::PARAM_STR);
	$st->execute();
          
      $SER_COUT=1;
      while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
      {
          $filing_no_check = htmlspecialchars($row['filing_no']);
          $defects=$row['defects'];
     
          $st1=$db->prepare("select * from e_case_detail where filing_no=? order by dt_of_filing desc");
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
      ?>            
                <?php if($defects =='N')
                { ?>  
                  <tr style="background-color: #BDFCC9;">
                  <?php }?>
                  <?php if($defects =='Y')
                { ?>  
                  <tr style="background-color: #f8c6bf;">
                  <?php }?>
                  <td><?php echo htmlspecialchars($SER_COUT);?></td>
                  <td><?php echo htmlspecialchars($filing_date_all);?></td>
                    <td><?php echo htmlspecialchars($filing_no);?></td>
                    <td><?php echo htmlspecialchars_decode(strtoupper($pet_name)).'&nbsp; Vs. &nbsp;'.htmlspecialchars_decode(strtoupper($res_name));?></td>
                    
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
                      <td><h3><span class="label label-info">
                    <a style="color: yellow;">Action Taken</a>
                    </span></h3></td>
                                       
                  </tr>
      <?php 
      $SER_COUT++;
          }
        }*/
      }
      ?> 


    </tbody>
  </table>
        <!-- defective cases detail             
                  
                  
              </div>
              <!-- /.table-responsive -->
            </div>
            
            
            
            <!-- /.box-footer -->
          </div>
          <?php
        } 



        ?>

        <!-- User1 Login -->     

        <!-- First level login Counter clerk scrutiny -->
        <?php 
        if($localadmin =='0' and $_SESSION['menuaccess_codeall'] =='2')
        {

          $_SESSION['qqcc'] = rand();
          $qq1cc = $_SESSION['qqcc'];
          ?>
          <div class="box box-info">
            <?php
            $hash = htmlspecialchars($_REQUEST['hash']);
            if ($hash != '') {
              $hash1 = htmlspecialchars(base64_decode($hash));
              $hash1 = explode("-", $hash1);
              $massage = $hash1[0];
              $token_filing_no_scrutiny = $hash1[1];
              ?>
              <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($massage); ?>
              </div>
            <?php } 
            $c_case = isset($_REQUEST['c_case']) ? $_REQUEST['c_case'] : $showradio; ?>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                    <tr>
                      <th>
                        <div id="testdiv" style="visibility: visible;">
                          <a href="javascript:window.print();">
                            <font size="4" color="red">
                            Print</font>
                          </a>
                        </div>
                      </th>
                      <th>
                        <input type="radio" name="c_case" value="1" onChange="javascript:submitForm3();" <?php if ($c_case == 1) {echo 'checked';} ?>><b>Fresh case for scrutiny</b>&nbsp;&nbsp;
                      </th>
                    <!--<th>
                        <input type="radio" name="c_case" value="2" onChange="javascript:submitForm3();" <?php if ($c_case == 2) { echo 'checked'; } ?>><b>Document scrutiny forcourt</b>&nbsp;&nbsp;
                    </th>
                    <th>
                        <input type="radio" name="c_case" value="3" onChange="javascript:submitForm3();" <?php if ($c_case == 3) { echo 'checked'; }?>><b>IA</b>
                    </th>
                    <th>
                        <input type="radio" name="c_case" value="4" onChange="javascript:submitForm3();" <?php if ($c_case == 4) {echo 'checked'; } ?>><b>Reports</b>
                      </th>-->

                      <th>
                        <input type="radio" name="c_case" value="5" onChange="javascript:submitForm3();" <?php if ($c_case == 5) {echo 'checked';} ?>><b>Defective cases</b>&nbsp;&nbsp;
                      </th>
                      <th>
                        <input type="radio" name="c_case" value="6" onChange="javascript:submitForm3();" <?php if ($c_case == 6) {echo 'checked';} ?>><b>Generate Computation Note</b>&nbsp;&nbsp;
                      </th>
                      <th>
                        <input type="radio" name="c_case" value="7" onChange="javascript:submitForm3();" <?php if ($c_case == 7) {echo 'checked';} ?>><b>Case No Generation</b>&nbsp;&nbsp;
                      </th>
                      <th>
                        <input type="radio" name="c_case" value="8" onChange="javascript:submitForm3();" <?php if ($c_case == 8) {echo 'checked';} ?>><b>Refiled Cases</b>&nbsp;&nbsp;
                      </th>
                      <th>
                        <input type="radio" name="c_case" value="9" onChange="javascript:submitForm3();" <?php if ($c_case == 9) {echo 'checked';} ?>><b>Caveat</b>&nbsp;&nbsp;
                      </th>

                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <?php 
            $app_pet = isset($_REQUEST['app_pet']) ? $_REQUEST['app_pet'] : 'P';
            if ($c_case == "1") {include_once('custom/fresh_cases.php'); }
            if ($c_case == "5") {include_once('custom/defective_cases.php');}
            if ($c_case == "6") {include_once('custom/computation_note.php');}
            if ($c_case == "7") {include_once('custom/case_no_generation.php');}
            if ($c_case == "8") {include_once('custom/refiled_cases.php');}
            if ($c_case == "9") {include_once('custom/filed_caveat.php');}
            ?>
          </div>
          <?php  

        }			  
      }
      ?>

      <?php
      if($c_case==2)
      {
        function url(){
          return sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME']
      //$_SERVER['REQUEST_URI']
          );
        }

        echo url();
        ?>
        <center><span><h3><a href="<?php echo url(); ?>/nclt/scrutiny/scrutiny_report.php" 
          target="popup" 
          onclick="window.open('<?php echo url(); ?>/nclt/scrutiny/scrutiny_report.php','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;">
          View Documents Report
        </a></h3></span></center>
        <div class="box-body">
          <div class="table-responsive">

            <div class="col-sm-2">
              <div class="form-group">
                <label for="email">SEARCH BY :</label>
                <?php 
                $search_wises = array(
                  "diary_wise"=>'DAIRY NO. WISE',
                  "case_wise"=>'CASE NO WISE '
                );
                $search_by = isset($_REQUEST['search_by']) ? $_REQUEST['search_by'] :'diary_wise'; 
                ?>
                <select name = "search_by" class="form-control"  onchange="change();">
                  <?php 
                  foreach($search_wises as $key => $search_wise) {?>
                    <option value="<?php echo $key;?>" <?php if($search_by==$key) echo 'selected';?>><?php echo $search_wise?></option>
                  <?php }?>
                </select>
              </div>
            </div>

<?php //diary wise
if($search_by =='diary_wise'){?>
  <div class="col-sm-2">
    <div class="form-group">
      <label for="email">DAIRY NO :</label>
      <?php  $dairy_no = isset($_REQUEST['dairy_no']) ? $_REQUEST['dairy_no'] :''; ?>
      <input type="text" class="form-control" id="dairy_no" onkeypress="return isNumberKey(event)" maxlength="16" size="5" autocomplete="off" name="dairy_no" value="<?php print htmlspecialchars(htmlentities($dairy_no)); ?>" style="color:#2E2E2E; width:180px;"/>
    </div>
  </div>

  <div class="col-sm-2">
    <div class="form-group">
      <label for="email">&nbsp;</label>
      <input id="submit1" type="button" class="form-control btn btn-primary"   name="submit2" value="SEARCH" onClick="return submitForm();" />
    </div>
  </div>
  <?php   
}
?>

  <?php //case no wise
  if($search_by =='case_wise'){?>
    <div class="col-sm-3">
      <div class="form-group">
        <label for="email">CASE TYPE :</label>
        <?php  $case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] :''; ?>
        <select name="case_type" class="form-control" id="case_type" >
          <?php
          $st = $db->prepare("select * from case_type where status = 't' order by case_type_desc_cis asc");
          $st->execute();
          while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
            $ctc=htmlspecialchars($row['id']);
            if($case_type == $ctc)
            {
              print "<option value=".htmlspecialchars($row['id'])." selected>".htmlspecialchars($row['case_type_desc_cis'])."</option>";
            }
            else
            {
              print "<option value=".htmlspecialchars($row['id']).">".htmlspecialchars($row['case_type_desc_cis']).'-'."</option>";
            }
          }
          ?>
        </select>
      </div>
    </div>

    <div class="col-sm-1">
      <div class="form-group">
        <label for="email">CASE NO :</label>
        <?php  $case_no = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] :''; ?>
        <input type="text" id="cno" class="form-control"  onkeypress="return isNumberKey(event)" maxlength="7" autocomplete="off" name="case_no" value="<?php print htmlspecialchars(htmlentities(ltrim($case_no,0))); ?>" />
      </div>
    </div>

    <div class="col-sm-2">
      <div class="form-group">
        <label for="email">CASE YEAR:</label>
        <?php  $case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] :''; ?>
        <input type="text" id="case_year" class="form-control"  onkeypress="return isNumberKey(event)" maxlength="4" autocomplete="off" size="5" name="case_year" value="<?php echo htmlspecialchars(htmlentities($case_year));?>" style="color:#2E2E2E; width:139px;" />
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group" style="margin-top: 8%;">
        <input id="submit1" type="button" class="form-ścontrol btn btn-primary" id="cryptstr"  name="submit1" value="SEARCH" onClick="return submitForm();" />
      </div>
    </div>
    <?php 

/*echo $casenumber ="4".str_pad($case_type,3, "0",STR_PAD_LEFT).str_pad($case_no,7,"0",STR_PAD_LEFT).$case_year; 

$stn = $db->prepare("select filing_no from $schemas.case_detail where case_no=? ");
$stn->bindParam(1, $casenumber, PDO::PARAM_INT);
$stn->execute();
$filing_no = $stn->fetchColumn();
$stnq = $db->prepare("select * from $schemas.case_detail where filing_no=?");
$stnq->bindParam(1,$filing_no,PDO::PARAM_INT);
$stnq->execute(); */
//echo $stnq="select * from $schemas.case_detail where case_no='$case_no' and case_year='$case_year' and case_type='$case_type'";
/*if($case_no!='' and $case_year!='')
{
$stnq = $db->prepare("select * from $schemas.case_detail where case_no=? and case_year=? and case_type=?");
$stnq->bindParam(1,$case_no,PDO::PARAM_INT);
$stnq->bindParam(2,$case_year,PDO::PARAM_INT);
$stnq->bindParam(3,$case_type,PDO::PARAM_INT);
$stnq->execute();
}*/
} 
?>
<?php
if($dairy_no!=''){  
  $filing_no_doc=$dairy_no;
    /*$ss1="select count(*) as count_doc from document_upload where  scrutiny=0 and display='1' and filing_no='$filing_no_doc' ";

    $st111=$db->prepare($ss1);
       $st111->execute();
       while ($row111 = $st111->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
       {
       
            $doc_fil= htmlspecialchars($row111['count_doc']);
       if($doc_fil >0)
   {
      $total_doc=$total_doc+$doc_fil;
   }
 }*/

//}

 ?>
 <table class="table no-margin">
   <thead>

    <tr>
     <tr>
       <th>Sr No.</th>
       <th>Date Of Filing</th>
       <th>Diary No.</th>
       <th>Miscellaneous No.</th>
       <th>Case No.</th>
       <th>Title Of Case</th>
       <th>Section</th>
       <?php if($_SESSION['menuaccess_codeall'] =='11') {?>
         <th>Scrutiny Date</th>
       <?php } ?>
       <th></th>
     </tr>
     <?php 


     $ll ='NA';
     $llpp='0';
     $SER_COUT='1'; 
     $SER_COUT3=1;		  

   if($_SESSION['menuaccess_codeall'] =='2') //user 1
   {
     ?>

     <?php
     if($filing_no_doc && (strlen($filing_no_doc) == '16')) { 
      $cname = 'filing_no';    
    }

    if($filing_no_doc && strlen($filing_no_doc) < '16' && strlen($filing_no_doc) > 0){
      $cname = 'fictitious_filing_no'; 
    }

   //$display='1';
   //$scrutiny_d='0';
   //$doc_level='NULL';
   //echo "here";   
    $st51=$db->prepare("select distinct(miscellaneous_ref_no) as miscellaneous_ref_no,filing_no,scrutiny,display,fictitious_filing_no,document_filed_date from document_upload where $cname =? and 
     (doc_level='' OR doc_level IS NULL) and miscellaneous_ref_no IS NOT NULL and party_type NOT IN (select party_flag from e_master_govt_body) order by filing_no DESC");
    $st51->bindParam(1, $filing_no_doc, PDO::PARAM_STR);
       //$st51->bindParam(2, $display, PDO::PARAM_STR);
   //$st51->bindParam(3, $scrutiny_d, PDO::PARAM_STR);
   //$st51->bindParam(4, $doc_level, PDO::PARAM_STR);

    $st51->execute();
  }
  

  if($_SESSION['menuaccess_codeall'] =='11')//astt registar
  {

    $display_ar='1';
    $scrutiny_ar='0';
    $doc_level='11';
    $doc_level_done='22';
         //$st51=$db->prepare("select distinct(miscellaneous_ref_no) as miscellaneous_ref_no,filing_no,scrutiny,display from document_upload  where filing_no =? and (doc_level=? or doc_level=?) and miscellaneous_ref_no IS NOT NULL and party_type NOT IN (select party_flag from e_master_govt_body) order by filing_no DESC");


    $st51=$db->prepare("select distinct(miscellaneous_ref_no) as miscellaneous_ref_no,filing_no,scrutiny,display,fictitious_filing_no,document_filed_date from document_upload  where filing_no =? and scrutiny=? and display=? and doc_level=? and miscellaneous_ref_no IS NOT NULL and party_type NOT IN (select party_flag from e_master_govt_body) order by filing_no DESC");
    $st51->bindParam(1, $filing_no_doc, PDO::PARAM_STR);

    $st51->bindParam(2, $scrutiny_ar, PDO::PARAM_STR);
    $st51->bindParam(3, $display_ar, PDO::PARAM_STR);

    $st51->bindParam(4, $doc_level, PDO::PARAM_STR);
  // $st51->bindParam(3, $doc_level_done, PDO::PARAM_STR);
    $st51->execute();

  }


  $cou=1;
  while ($row51 = $st51->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
    $filing_no= htmlspecialchars($row51['filing_no']);
    $miscellaneous_ref_no= htmlspecialchars($row51['miscellaneous_ref_no']);
    $scrutiny_value= htmlspecialchars($row51['scrutiny']);
    $display = htmlspecialchars($row51['display']);
    $doc_filed_date = $row51['document_filed_date'];
    list($year,$month,$day)=explode('-',$doc_filed_date);
    $filing_date_all2=$day.'/'.$month.'/'.$year;

   //$documentuploadmodelid= htmlspecialchars($row51['documentuploadmodelid']);
   //$uniqueid= htmlspecialchars($row51['uniqueid']);

/* $st21=$db->prepare("select defects from $schemas.scrutiny_doc where filing_no =? ");
 
      
       $st21->bindParam(1, $filing_no, PDO::PARAM_STR);
       $st21->execute();
       while ($row2 = $st21->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
       {
       
               $doc_defects1 = htmlspecialchars($row2['defects']);
 
             }*/


             $doc_case_no='';

             if($filing_no != 'NA') {

 //echo $r = "select filing_no,dt_of_filing,case_type,pet_name,res_name from $schemas.case_detail where filing_no ='$filing_no' and case_no!='$doc_case_no'  order by filing_no DESC";
              $st1=$db->prepare("select filing_no,dt_of_filing,case_type,pet_name,res_name from $schemas.case_detail where filing_no =? and case_no!=?  order by filing_no DESC");

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






        //code to get notification date

               $get_not_date = $db->prepare("select notification_date from $schemas.scrutiny_doc where filing_no=? and miscellaneous_ref_no=?");
               $get_not_date->bindParam(1, $filing_no2, PDO::PARAM_INT);
               $get_not_date->bindParam(2, $miscellaneous_ref_no, PDO::PARAM_INT);                 
               $get_not_date->execute();

               $not_date = $get_not_date->fetchColumn();	
               if($not_date!='')
               {
                list($year,$month,$day)=explode('-',$not_date);

                $not_date=$day.'/'.$month.'/'.$year;
              }
              if($not_date=='//'){
                $not_date='NA';
              }


              $st25=$db->prepare("select * from e_case_detail_fees where filing_no =? ");
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
               $st35=$db->prepare("select * from master_section_act where id=? ");
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
              <tr style="background-color: #f8c6bf;">   
                <?php
///}

                ?>
                <td><?php echo htmlspecialchars($SER_COUT3++).".";?></td>
                <td><?php if($filing_date_all2 =='11/11/1111' OR $filing_date_all2 =='//'){$filing_date_all2="";}else {echo htmlspecialchars($filing_date_all2);}?></td>

                <?php
                $countdocsql=$db->prepare("select count(*) from document_upload where miscellaneous_ref_no='$miscellaneous_ref_no' and filing_no='$filing_no' and scrutiny='0' and display='1' and party_type NOT IN (select party_flag from e_master_govt_body)");
                $countdocsql->execute();
                $number_of_rows = $countdocsql->fetchColumn();
                ?>
                <td><?php echo display_filing_no($filing_no2);
                echo "<br><span style='color:red'>";
                echo "(No.of Docs - ".$number_of_rows.")";
              echo "</span>";		 ?></td>
              <td><?php echo htmlspecialchars($miscellaneous_ref_no); ?></td>


              <?php

              $casenosql=$db->prepare("select case_type, case_no, case_year, location_code from $schemas.case_detail where filing_no='$filing_no2'");
              $casenosql->execute(); 
              $row = $casenosql->fetch();
              $case_no = htmlspecialchars($row['case_no']);
              $case_no = ltrim($case_no,0);
              $casetype = htmlspecialchars($row['case_type']);
              $locode = htmlspecialchars($row['location_code']);
              $case_year = htmlspecialchars($row['case_year']);
              if($locode == '' || $locode == NULL){
                $locode = 0;
              }

              $casetypesql = $db->prepare("select case_type_desc_cis from case_type where id = '$casetype'");
              $casetypesql->execute();
              $case_type_short_name=$casetypesql->fetchColumn();
              $case_type_short_name = strtoupper($case_type_short_name);

              $lcodesql ="select short_name from $schemas.bench_location where bench_location_code ='$locode'";
              $lcodesql=$db->prepare($lcodesql);
              $lcodesql->execute();
              $lcodename = $lcodesql->fetchColumn();

              $case_no_final = $case_type_short_name.'/'.$case_no.'('.$lcodename.')'.$case_year;

              ?>	
              <td><?php echo $case_no_final; ?></td>
              <td><?php
              $E_nameP= htmlspecialchars_decode($E_nameP,ENT_NOQUOTES);
              $E_nameR= htmlspecialchars_decode($E_nameR,ENT_NOQUOTES);


              echo strtoupper($E_nameP)."&nbsp;<font color='blue' size='2'><br> Vs. <br> </font>&nbsp;".strtoupper($E_nameR);?></td>        
              <td><?php echo rtrim($r,','); ?></td>
              <?php if($_SESSION['menuaccess_codeall'] =='11'){?>
                <td><?php echo htmlspecialchars($not_date); ?></td>
              <?php  } ?>
              <?php


              if($_SESSION['menuaccess_codeall'] =='2')
              {

                if($scrutiny_value=='0' && $display=='1'){
                  ?>

                  <td><h3><span class="label label-info">
                   <a style="color: #FFFFFF;" href="./scrutiny/user_scrutiny1.php?ccase=<?php echo 
                   htmlspecialchars($c_case);?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">
                 Scrutiny</a>
               </span></h3></td>
               <?php 
             }else if($scrutiny_value=='1' && $display=='1'){
              ?>
              <td><h3><span><button type="button" class="btn btn-success" disabled>Scrutiny Done Without Defects</button></span></h3></td>
              <?php
            }else if($scrutiny_value=='0' && $display==''){
              ?>
              <td><h3><span><button type="button" class="btn btn-success" disabled>Scrutiny Done With Defects</button></span></h3></td>
              <?php
            }
          }
          ?>
          <?php
          if($_SESSION['menuaccess_codeall'] =='11')
          {
            if($scrutiny_value=='0'  && $display=='1'){
              ?>

              <td><h3><span class="label label-info">
               <a style="color: #FFFFFF;" href="./scrutiny/varify_cases.php?ccase=<?php echo htmlspecialchars
               ($c_case);?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">Scrutiny Verify</a>
             </span></h3></td>
             <?php 
           }else if($scrutiny_value=='1' && $display=='1'){
            ?>
            <td><h3><span><button type="button" class="btn btn-success" disabled>Scrutiny Done without Defects</button></span></h3></td>
            <?php
          }else if($scrutiny_value=='0' && $display==''){
            ?>
            <td><h3><span><button type="button" class="btn btn-success" disabled>Scrutiny Done With Defects</button></span></h3></td>
            <?php
          }
        }
        ?>

        <?php 

// echo $filing_no."<br>".$cou;
//$cou++;	
      }
    }

  }

// for ficitious numbers
  if($_SESSION['menuaccess_codeall'] == '2' && $filing_no == 'NA'){

   $fictitious_filing_no = $row51['fictitious_filing_no'];
   $st21=$db->prepare("select location_id from e_not_found where fictitious_filing_no =? ");

   $st21->bindParam(1, $fictitious_filing_no, PDO::PARAM_STR);
   $st21->execute();
   $zone_id = $st21->fetchColumn();

   if($zone_id != $location_access){
     continue;
   } 

   $online_scrt = 0;
   $online_dis = true;
   $countdocsql=$db->prepare("select count(*) from document_upload where fictitious_filing_no= ?  and scrutiny= ? and display= ? and miscellaneous_ref_no is NOT NULL ");
   $countdocsql->bindParam(1, $fictitious_filing_no, PDO::PARAM_STR);
   $countdocsql->bindParam(2, $online_scrt, PDO::PARAM_STR);
   $countdocsql->bindParam(3, $online_dis, PDO::PARAM_STR);
   $countdocsql->execute();
   $number_of_rows = $countdocsql->fetchColumn();

   $e_not_found_detail=$db->prepare("select a.case_no,b.short_name,a.case_year from e_not_found a left join case_type b on b.id = a.case_type where a.fictitious_filing_no= ?");
   $e_not_found_detail->bindParam(1, $fictitious_filing_no, PDO::PARAM_STR);
   $e_not_found_detail->execute();
   $e_not_found_detail = array_shift($e_not_found_detail->fetchAll());
   $case_no_final = $e_not_found_detail['short_name']."/".$e_not_found_detail['case_no']."/".$e_not_found_detail['case_year'];

   ?>

   <tr style="background-color: #f8c6bf;">
     <td><?php echo htmlspecialchars($SER_COUT3++).".";?></td>
     <td><?php if ($filing_date_all2 == '11/11/1111' or $filing_date_all2 == '//') {$filing_date_all2 = "";} else {echo htmlspecialchars($filing_date_all2);}?>
   </td>
   <td><?php echo $fictitious_filing_no;
   echo "<br><span style='color:red'>";
   echo "(No.of Docs - " . $number_of_rows . ")";
 echo "</span>"; ?></td>
 <td><?php echo htmlspecialchars($miscellaneous_ref_no); ?></td>
 <td><?php echo $case_no_final; ?></td>
 <td>
 </td>
 <td></td>
 <?php if ($_SESSION['menuaccess_codeall'] == '11') {?>
  <td></td>
<?php }?>
<?php
if ($_SESSION['menuaccess_codeall'] == '2') { ?>

  <td>
    <h3><span class="label label-info">
      <a style="color: #FFFFFF;" href="javascript:void(0);">
      Enter Backlog Case</a>
    </span></h3>
  </td>
<?php }
if ($_SESSION['menuaccess_codeall'] == '11') { ?>

 <td>
  <h3><span class="label label-info">
    <a style="color: #FFFFFF;" href="javascript:void(0);">
    Enter Backlog Case</a>
  </span></h3>
</td>
<?php }

echo "</tr>";
	// end here
}

}
}
else if($case_no!='' && $case_year!=''){ 
      /*$ss1="select count(*) as count_doc from document_upload where  scrutiny=0 and display='1' and filing_no='$filing_no_doc' ";
  
      $st111=$db->prepare($ss1);
         $st111->execute();
         while ($row111 = $st111->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
         {
         
              $doc_fil= htmlspecialchars($row111['count_doc']);
         if($doc_fil >0)
     {
        $total_doc=$total_doc+$doc_fil;
     }
   }*/

  //}







   ?>
   <table class="table no-margin">
     <thead>

      <tr>
       <tr>
         <th>Sr No.</th>
         <th>Date Of Filing</th>
         <th>Diary No.</th>
         <th>Miscellaneous No.</th>
         <th>Case No.</th>
         <th>Title Of Case</th>
         <th>Section</th>
         <?php if($_SESSION['menuaccess_codeall'] =='11') {?>
           <th>Scrutiny Date</th>
         <?php } ?>
         <th></th>
       </tr>
       <?php 


       $ll ='NA';
       $llpp='0';
       $SER_COUT='1'; 
       $SER_COUT3=1;		  

     if($_SESSION['menuaccess_codeall'] =='2') //user 1
     {
       ?>

       <?php

     //$display='1';
     //$scrutiny_d='0';
     //$doc_level='NULL';
     //echo "here";      
       $st51=$db->prepare("select distinct(miscellaneous_ref_no) as miscellaneous_ref_no,filing_no,scrutiny,display,document_filed_date,fictitious_filing_no from document_upload where
         doc_level IS NULL and miscellaneous_ref_no IS NOT NULL and party_type NOT IN (select party_flag from e_master_govt_body) order by filing_no DESC");
         //$st51->bindParam(1, $display, PDO::PARAM_STR);
     //$st51->bindParam(2, $scrutiny_d, PDO::PARAM_STR);
     //$st51->bindParam(4, $doc_level, PDO::PARAM_STR);

       $st51->execute();
     }


    if($_SESSION['menuaccess_codeall'] =='11')//astt registar
    {

      //$display='1';
    //$scrutiny_d='0';
      $doc_level='11';


      $st51=$db->prepare("select distinct(miscellaneous_ref_no) as miscellaneous_ref_no,filing_no,scrutiny,display,document_filed_date,fictitious_filing_no from document_upload  where doc_level=? and miscellaneous_ref_no IS NOT NULL and  party_type NOT IN (select party_flag from e_master_govt_body)order by filing_no DESC");
         //$st51->bindParam(1, $display, PDO::PARAM_STR);
     //$st51->bindParam(2, $scrutiny_d, PDO::PARAM_STR);
      $st51->bindParam(1, $doc_level, PDO::PARAM_STR);
      $st51->execute();

    }


    $cou=1;
    while ($row51 = $st51->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
    {
     $filing_no= htmlspecialchars($row51['filing_no']);
     $miscellaneous_ref_no= htmlspecialchars($row51['miscellaneous_ref_no']);
     $scrutiny_value= htmlspecialchars($row51['scrutiny']);
     $display= htmlspecialchars($row51['display']);
     $document_filed_date= htmlspecialchars($row51['document_filed_date']);
     list($year,$month,$day)=explode('-',$document_filed_date);
     $filing_date_all2=$day.'/'.$month.'/'.$year;
     //$documentuploadmodelid= htmlspecialchars($row51['documentuploadmodelid']);
     //$uniqueid= htmlspecialchars($row51['uniqueid']);

  /* $st21=$db->prepare("select defects from $schemas.scrutiny_doc where filing_no =? ");
   
        
         $st21->bindParam(1, $filing_no, PDO::PARAM_STR);
         $st21->execute();
         while ($row2 = $st21->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
         {
         
                 $doc_defects1 = htmlspecialchars($row2['defects']);
   
               }*/


               $doc_case_no='';

               $st1=$db->prepare("select filing_no,dt_of_filing,case_type,pet_name,res_name from $schemas.case_detail where case_no=? and filing_no=? and case_type=? and case_year=?  order by filing_no DESC");

               $st1->bindParam(1, $case_no, PDO::PARAM_STR);
               $st1->bindParam(2, $filing_no, PDO::PARAM_STR);
               $st1->bindParam(3, $case_type, PDO::PARAM_STR);
               $st1->bindParam(4, $case_year, PDO::PARAM_STR);

               $st1->execute();
               $doccs = $st1->fetchAll();
               if(!empty($doccs)){
                 foreach($doccs as $k=>$row)
                 {

                   $filing_no2 = htmlspecialchars($row['filing_no']);
                   $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
                   $case_type=htmlspecialchars($row['case_type']);
                   $E_nameP=htmlspecialchars($row['pet_name']);
                   $E_nameR=htmlspecialchars($row['res_name']);



          //code to get notification date

                   $get_not_date = $db->prepare("select notification_date from $schemas.scrutiny_doc where filing_no=? and miscellaneous_ref_no=?");
                   $get_not_date->bindParam(1, $filing_no2, PDO::PARAM_INT);
                   $get_not_date->bindParam(2, $miscellaneous_ref_no, PDO::PARAM_INT);                 
                   $get_not_date->execute();

                   $not_date = $get_not_date->fetchColumn();	
                   list($year,$month,$day)=explode('-',$not_date);
                   $not_date=$day.'/'.$month.'/'.$year;
                   if($not_date='//'){
                    $not_date='NA';
                  }


                  $st25=$db->prepare("select * from e_case_detail_fees where filing_no =? ");
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
                   $st35=$db->prepare("select * from master_section_act where id=? ");
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
                  <tr style="background-color: #f8c6bf;">   
                    <?php
  ///}

                    ?>
                    <td><?php echo htmlspecialchars($SER_COUT3++).".";?></td>
                    <td><?php if($filing_date_all2 =='11/11/1111' OR $filing_date_all2 =='//'){$filing_date_all2="";}else {echo htmlspecialchars($filing_date_all2);}?></td>

                    <?php
                    $countdocsql=$db->prepare("select count(*) from document_upload where miscellaneous_ref_no='$miscellaneous_ref_no' and filing_no='$filing_no' and scrutiny='0' and display='1' and party_type NOT IN (select party_flag from e_master_govt_body)");
                    $countdocsql->execute();
                    $number_of_rows = $countdocsql->fetchColumn();
                    ?>
                    <td><?php echo display_filing_no($filing_no2);
                    echo "<br><span style='color:red'>";
                    echo "(No.of Docs - ".$number_of_rows.")";
                  echo "</span>";		 ?></td>
                  <td><?php echo htmlspecialchars($miscellaneous_ref_no); ?></td>


                  <?php

                  $casenosql=$db->prepare("select case_type, case_no, case_year, location_code from $schemas.case_detail where filing_no='$filing_no2'");
                  $casenosql->execute(); 
                  $row = $casenosql->fetch();
                  $case_no = htmlspecialchars($row['case_no']);
                  $case_no = ltrim($case_no,0);
                  $casetype = htmlspecialchars($row['case_type']);
                  $locode = htmlspecialchars($row['location_code']);
                  $case_year = htmlspecialchars($row['case_year']);

                  if($locode == '' || $locode == NULL){
                   $locode=0;
                 }

                 $casetypesql = $db->prepare("select case_type_desc_cis from case_type where id = '$casetype'");
                 $casetypesql->execute();
                 $case_type_short_name=$casetypesql->fetchColumn();
                 $case_type_short_name = strtoupper($case_type_short_name);

                 $lcodesql ="select short_name from $schemas.bench_location where bench_location_code ='$locode'";
                 $lcodesql=$db->prepare($lcodesql);
                 $lcodesql->execute();
                 $lcodename = $lcodesql->fetchColumn();

                 $case_no_final = $case_type_short_name.'/'.$case_no.'('.$lcodename.')'.$case_year;

                 ?>	
                 <td><?php echo $case_no_final; ?></td>
                 <td><?php 
                 $E_nameP= htmlspecialchars_decode($E_nameP,ENT_NOQUOTES);
                 $E_nameR= htmlspecialchars_decode($E_nameR,ENT_NOQUOTES);

                 echo strtoupper($E_nameP)."&nbsp;<font color='blue' size='2'><br> Vs. <br> </font>&nbsp;".strtoupper($E_nameR);?></td>        
                 <td><?php echo rtrim($r,','); ?></td>
                 <?php if($_SESSION['menuaccess_codeall'] =='11'){?>
                  <td><?php echo htmlspecialchars($not_date); ?></td>
                <?php  } ?>
                <?php


                if($_SESSION['menuaccess_codeall'] =='2')
                {     
                  if($scrutiny_value=='0'  && $display=='1'){
                    ?>

                    <td><h3><span class="label label-info">
                     <a style="color: #FFFFFF;" href="./scrutiny/user_scrutiny1.php?ccase=<?php echo 
                     htmlspecialchars($c_case);?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">
                   Scrutiny</a>
                 </span></h3></td>
                 <?php 
               }else if($scrutiny_value=='1' && $display=='1'){
                ?>
                <td><h3><span><button type="button" class="btn btn-success" disabled>Scrutiny Done Without Defects</button></span></h3></td>
                <?php
              }else if($scrutiny_value=='0' && $display==''){
                ?>
                <td><h3><span><button type="button" class="btn btn-success" disabled>Scrutiny Done With Defects</button></span></h3></td>
                <?php
              }
            }
            ?>
            <?php
            if($_SESSION['menuaccess_codeall'] =='11')
            {
              if($scrutiny_value=='0' && $display=='1'){ 
                ?>

                <td><h3><span class="label label-info">
                 <a style="color: #FFFFFF;" href="./scrutiny/varify_cases.php?ccase=<?php echo htmlspecialchars
                 ($c_case);?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">Scrutiny Verify</a>
               </span></h3></td>
               <?php 
             }else if($scrutiny_value=='1' && $display=='1'){
              ?>
              <td><h3><span><button type="button" class="btn btn-success" disabled>Scrutiny Done Without Defects</button></span></h3></td>
              <?php
            }
            else if($scrutiny_value=='0' && $display==''){
              ?>
              <td><h3><span><button type="button" class="btn btn-success" disabled>Scrutiny Done With Defects</button></span></h3></td>
              <?php
            }
          }
          ?>

          <?php 

  // echo $filing_no."<br>".$cou;
  //$cou++;	
        }
      }
    }
    else{
      $query = $db->prepare("select fictitious_filing_no from e_not_found where case_no = '$case_no' and  case_year = '$case_year' and  case_type = '$case_type' and location = '$location_access'");
      $query->execute();
      $data = $query->fetchColumn();
      $fictitious_filing_no = $data;
      if(!empty($fictitious_filing_no)){
        $fictitious_filing_no = $row51['fictitious_filing_no'];
        $st21=$db->prepare("select location from e_not_found where fictitious_filing_no =? ");

        $st21->bindParam(1, $fictitious_filing_no, PDO::PARAM_STR);
        $st21->execute();
        $zone_id = $st21->fetchColumn();

        if($zone_id != $location_access){
         continue;
       } 

       $online_scrt = 0;
       $online_dis = true;
       $countdocsql=$db->prepare("select count(*) from document_upload where fictitious_filing_no= ?  and scrutiny= ? and display= ? and miscellaneous_ref_no is NOT NULL ");
       $countdocsql->bindParam(1, $fictitious_filing_no, PDO::PARAM_STR);
       $countdocsql->bindParam(2, $online_scrt, PDO::PARAM_STR);
       $countdocsql->bindParam(3, $online_dis, PDO::PARAM_STR);
       $countdocsql->execute();
       $number_of_rows = $countdocsql->fetchColumn();

       $e_not_found_detail=$db->prepare("select a.case_no,b.short_name,a.case_year from e_not_found a left join case_type b on b.id = a.case_type where a.fictitious_filing_no= ?");
       $e_not_found_detail->bindParam(1, $fictitious_filing_no, PDO::PARAM_STR);
       $e_not_found_detail->execute();
       $e_not_found_detail = array_shift($e_not_found_detail->fetchAll());
       $case_no_final = $e_not_found_detail['short_name']."/".$e_not_found_detail['case_no']."/".$e_not_found_detail['case_year'];

       ?>

       <tr style="background-color: #f8c6bf;">
         <td><?php echo htmlspecialchars($SER_COUT3++).".";?></td>
         <td><?php if ($filing_date_all2 == '11/11/1111' or $filing_date_all2 == '//') {$filing_date_all2 = "";} else {echo htmlspecialchars($filing_date_all2);}?>
       </td>
       <td><?php echo $fictitious_filing_no;
       echo "<br><span style='color:red'>";
       echo "(No.of Docs - " . $number_of_rows . ")";
     echo "</span>"; ?></td>
     <td><?php echo htmlspecialchars($miscellaneous_ref_no); ?></td>
     <td><?php echo $case_no_final; ?></td>
     <td>
     </td>
     <td></td>
     <?php if ($_SESSION['menuaccess_codeall'] == '11') {?>
      <td></td>
    <?php }?>
    <?php
    if ($_SESSION['menuaccess_codeall'] == '2') { ?>

      <td>
        <h3><span class="label label-info">
          <a style="color: #FFFFFF;" href="javascript:void(0);">
          Enter Backlog Case</a>
        </span></h3>
      </td>
    <?php }
    if ($_SESSION['menuaccess_codeall'] == '11') { ?>

     <td>
      <h3><span class="label label-info">
        <a style="color: #FFFFFF;" href="javascript:void(0);">
        Enter Backlog Case</a>
      </span></h3>
    </td>
  <?php }

  echo "</tr>";
}
	// end here
}

}
}
}

?>
<?php
   //start of IA Cases in Scrutiny clerk
if(isset($c_case)!='')
  if($c_case==3)
  {
	if($_SESSION['menuaccess_codeall'] =='2') //user 1
  {

    ?>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table no-margin">
          <thead>


            <tr>
              <tr>
                <th>Sr No.</th>
                <th>Date of Filing</th>
                <th>IA Diary No.</th>
                <th>Main Case No.</th>
                <th>Title Of Case</th>
                <th>IA Filed By</th>


              </tr>
              <?php 
              $SER_COUT4=1;	

              $iscrutiny=0;
              $ia_level=0;
              $pstatus='TRUE';

              $st51=$db->prepare("select distinct(ia_filing_no) from e_ia_details where payment_status=? and scrutiny=? and doc_status=? and ia_filing_no IS NOT NULL and ia_filing_no!='' order by ia_filing_no asc");
              $st51->bindParam(1, $pstatus, PDO::PARAM_STR);
              $st51->bindParam(2, $iscrutiny, PDO::PARAM_STR);
              $st51->bindParam(3, $ia_level, PDO::PARAM_STR);

		  //$st51->bindParam(4, $doc_level, PDO::PARAM_STR);
              $st51->execute();

              while ($row51 = $st51->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
              {
			//$ia_s_no=1;
			//$ia_id =$row51['ia_id'];
			//$ref_no_ia =$row51['ref_no_ia'];
                $ia_filing_no =$row51['ia_filing_no'];	
		    //$ia_id =$row51['ia_id'];	
		    //$main_filing_no =$row51['filing_no'];
		    //$ia_party_flag =$row51['party_flag'];
		    //$ia_party_serial_no =$row51['party_serial_no'];
                $st52=$db->prepare("select * from e_ia_details where payment_status=? and scrutiny=? and doc_status=? and ia_filing_no IS NOT NULL and ia_filing_no='$ia_filing_no' LIMIT 1");
                $st52->bindParam(1, $pstatus, PDO::PARAM_STR);
                $st52->bindParam(2, $iscrutiny, PDO::PARAM_STR);
                $st52->bindParam(3, $ia_level, PDO::PARAM_STR);

		  //$st51->bindParam(4, $doc_level, PDO::PARAM_STR);
                $st52->execute();

                while ($row52 = $st52->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                {
                 $ia_id =$row52['ia_id'];
                 $ref_no_ia =$row52['ref_no_ia']; 
                 $main_filing_no =$row52['filing_no'];
                 $ia_party_flag =$row52['party_flag'];
                 $ia_party_serial_no =$row52['party_serial_no'];	
                 $dof_ia =$row52['uploaded_date'];
                 list($year,$month,$day)=explode('-',$dof_ia);
                 $dof_ia=$day.'/'.$month.'/'.$year;
               }


               ?>

               <?php 
               if($ia_filing_no!='')
               {
		 //echo "iii";

                 $st1=$db->prepare("select * from $schemas.case_detail where filing_no =?  order by filing_no DESC");
                 $st1->bindParam(1, $main_filing_no, PDO::PARAM_STR);
                 $st1->execute();
                 while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                 {


                  $filing_no2 = htmlspecialchars($row['filing_no']);
                  $ia_main_case_type=htmlspecialchars($row['case_type']);
                  $ia_main_case_no=htmlspecialchars($row['case_no']);
                  $ia_main_case_year=htmlspecialchars($row['case_year']);
                  $ia_main_location_code=htmlspecialchars($row['location_code']);

                  $casetypesql = $db->prepare("select case_type_desc_cis from case_type where id = '$ia_main_case_type'");
                  $casetypesql->execute();
                  $case_type_short_name=$casetypesql->fetchColumn();
                  $ia_main_case_type_short_name = strtoupper($case_type_short_name);

                  if($ia_main_location_code){
                   $lcodesql ="select short_name from $schemas.bench_location where bench_location_code ='$ia_main_location_code'";
                   $lcodesql=$db->prepare($lcodesql);
                   $lcodesql->execute();
                   $ia_main_lcodename = $lcodesql->fetchColumn();
                 }

                 $ia_main_case_no_final=$ia_main_case_type_short_name.'/'.$ia_main_case_no.'('.$ia_main_lcodename.')'.$ia_main_case_year;


                 $E_party_flag1='P';
                 $E_party_serial_no1='1';



                 $st33=$db->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
                 $st33->bindParam(1, $filing_no2, PDO::PARAM_STR);
                 $st33->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
                 $st33->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
                 $st33->execute();

                 while ($row = $st33->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                 {

                   $E_party_flagP=$row['party_flag']; 
			$E_party_serial_noP=$row['party_serial_no']; //0
			$E_nameP=$row['name']; //0
			
		}	
		
		
		$E_party_flag1='R';
		$E_party_serial_no1='1';
		$st34=$db->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st34->bindParam(1, $filing_no2, PDO::PARAM_STR);
		$st34->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$st34->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$st34->execute();
		while ($row = $st34->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{

			$E_party_flagR=$row['party_flag'];
			$E_party_serial_noR=$row['party_serial_no']; //0
			$E_nameR=$row['name']; //0
			
		}

		$IA_MAIN_E_party_serial_no1='1';	 
		$st331=$db->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st331->bindParam(1, $filing_no2, PDO::PARAM_STR);
		$st331->bindParam(2, $ia_party_flag, PDO::PARAM_STR);
		$st331->bindParam(3, $ia_party_serial_no, PDO::PARAM_STR);
		$st331->execute();
		
		while ($row31 = $st331->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{

			
			 $IA_MAIN_NAME=$row31['name']; //0

      }			 

      ?>
      <?php 

      $filing_nosend=$main_filing_no.'-'.$qq1cc.'-'.$ia_id;
      $filing_no_send=base64_encode($filing_nosend); 






      ?>	
      <tr style="background-color: #BDFCC9;">   


       <td><?php echo htmlspecialchars($SER_COUT4++);?></td>

       <td><?php echo htmlspecialchars($dof_ia);?></td>
       <td><?php echo display_filing_no($ia_filing_no);
       echo "<br><span style='color:red'>";

     echo "</span>";		 ?></td>


     <td><?php echo $ia_main_case_no_final; ?></td>

     <td><?php 
     $E_nameP= htmlspecialchars_decode($E_nameP,ENT_NOQUOTES);
     $E_nameR= htmlspecialchars_decode($E_nameR,ENT_NOQUOTES);


     echo strtoupper($E_nameP)."&nbsp;<font color='blue' size='2'><br> Vs. <br> </font>&nbsp;".strtoupper($E_nameR);?></td>        
     <td>
       <?php echo htmlspecialchars_decode(strtoupper($IA_MAIN_NAME))?>

     </td>
     <?php
     if($_SESSION['menuaccess_codeall'] =='2')
     {


       ?>

       <td><h3><span class="label label-info">
        <a style="color: #FFFFFF;" href="./scrutiny/user_scrutiny1.php?ccase=<?php echo 
        htmlspecialchars($c_case);?>&ia_id=<?php echo htmlspecialchars($ia_id);?>&filing_no_next=
        <?php echo htmlspecialchars($filing_no_send);?>">Scrutiny</a>
      </span></h3></td></tr>
      <?php 
    }
    ?>

    <?php
  }
}

}
if($ref_no_ia == ''){
  ?>
  <tr><td><font color='green'><b>No Record is Pending For Scrutiny...</b></font></td></tr>
  <?php
}
}
}
?>


<?php 
if(isset($c_case)!='')
 if($c_case==4)
 {

  ?>


  <table class="table no-margin">
   <thead>

    <tr>
     <tr>
       <th>Sr No.</th>
       <th>Doc. Filed Date</th>
       <th>Miscellaneous No .</th>
       <th>Filing No .</th>
       <th>Title Of Case</th>

       <th>Section</th>
       <th>Doc Type</th>
       <?php 		 
   if($_SESSION['menuaccess_codeall'] =='11')//astt registar
   {
     ?>
     <th>Scrutiny Date</th> 
     <?php
   }
   ?>	     

 </tr>
 <?php 


 $ll1 ='NA';
 $llpp1='0';
 $SER_COUT1='1'; 
 $SER_COUT31=1;		
 

 if($_SESSION['menuaccess_codeall'] =='2') //user 1
 {
   ?>

   <?php




	 //echo $filing_no_doc; die;


   $st51=$db->prepare("select distinct(miscellaneous_ref_no) from document_upload where  subdoctype In(17,33) and miscellaneous_ref_no IS NOT NULL and (doc_level IS NULL OR doc_level='')  and scrutiny='0' and display ='1' and party_type IN (select party_flag from e_master_govt_body)  " );
  // $st51->bindParam(1, $filing_no_doc, PDO::PARAM_STR);
   //$st51->bindParam(2, $party_flag_ef, PDO::PARAM_STR);
   
   $st51->execute();
	  // $all_res = $st51->fetchAll();

 }



 if($_SESSION['menuaccess_codeall'] =='11')//astt registar
 {

    //$display='1';
  //$scrutiny_d='0';
  $doc_level='4';
  //$doc_level_done='44';
       //  $st51=$db->prepare("select distinct(miscellaneous_ref_no) as miscellaneous_ref_no,filing_no,scrutiny,display,document_filed_date from document_upload  where filing_no =? and (doc_level=? or doc_level=?) and miscellaneous_ref_no IS NOT NULL and party_type IN (select party_flag from e_master_govt_body) ");

  $st51=$db->prepare(" select distinct(miscellaneous_ref_no) from document_upload where  subdoctype In(17,33) and miscellaneous_ref_no IS NOT NULL and doc_level='4'  and scrutiny='0' and display ='1' and party_type IN (select party_flag from e_master_govt_body) ");
  // $st51->bindParam(1, $filing_no_doc, PDO::PARAM_STR);
       //$st51->bindParam(2, $display, PDO::PARAM_STR);
   //$st51->bindParam(3, $scrutiny_d, PDO::PARAM_STR);
  //$st51->bindParam(2, $doc_level, PDO::PARAM_STR);
   //$st51->bindParam(3, $doc_level_done, PDO::PARAM_STR);
  $st51->execute();

}
$cou=1;
while ($row51 = $st51->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{


  $miscellaneous_ref_no= htmlspecialchars($row51['miscellaneous_ref_no']);

	   if($_SESSION['menuaccess_codeall'] =='2')//astt registar
	   {
     $st515=$db->prepare("select distinct(subdoctype),filing_no,scrutiny,document_filed_date,display from document_upload  where miscellaneous_ref_no=? and (doc_level IS NULL OR doc_level='')");
     $st515->bindParam(1, $miscellaneous_ref_no, PDO::PARAM_STR); 
     $st515->execute();
   }


	    if($_SESSION['menuaccess_codeall'] =='11')//astt registar
      {
       $doc_level='4';
       $st515=$db->prepare("select distinct(subdoctype),filing_no,scrutiny,document_filed_date,display from document_upload  where miscellaneous_ref_no=? and doc_level=?");
       $st515->bindParam(1, $miscellaneous_ref_no, PDO::PARAM_STR); 
       $st515->bindParam(2, $doc_level, PDO::PARAM_STR); 
       $st515->execute();
     }
     while ($row515 = $st515->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
     {
       $filing_no= htmlspecialchars($row515['filing_no']);
       $scrutiny_value= htmlspecialchars($row515['scrutiny']);
       $document_filed_date= htmlspecialchars($row515['document_filed_date']);
       $subdoctype= htmlspecialchars($row515['subdoctype']);
       if($subdoctype==33)
       {
        $subdoctype_name='Order';
        $form_type='O';
      }
      if($subdoctype==17)
      {
        $subdoctype_name='Report';
        $form_type='R';
      }
      $display = htmlspecialchars($row515['display']);
      $doc_case_no='';
      $st1=$db->prepare("select filing_no,dt_of_filing,case_type,pet_name,res_name from $schemas.case_detail where filing_no =? and case_no!=?  order by filing_no DESC");
      $st1->bindParam(1, $filing_no, PDO::PARAM_STR);
      $st1->bindParam(2, $doc_case_no, PDO::PARAM_STR);   
      $st1->execute();
      while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
      {

        $filing_no2 = htmlspecialchars($row['filing_no']);
    //$dt_of_filing = htmlspecialchars($row['dt_of_filing']);
        $case_type=htmlspecialchars($row['case_type']);
        $E_nameP=htmlspecialchars($row['pet_name']);
        $E_nameR=htmlspecialchars($row['res_name']);

//$party_type_inc='R';


        list($year,$month,$day)=explode('-',$document_filed_date);
        $document_filed_date=$day.'/'.$month.'/'.$year;

        //code to get notification date
        $get_not_date = $db->prepare("select notification_date,defects from $schemas.scrutiny_doc where filing_no=? and miscellaneous_ref_no=? and form_type=?");
        $get_not_date->bindParam(1, $filing_no2, PDO::PARAM_INT);
        $get_not_date->bindParam(2, $miscellaneous_ref_no, PDO::PARAM_INT);
        $get_not_date->bindParam(3, $form_type, PDO::PARAM_INT);		
        $get_not_date->execute();
        while ($row25=  $get_not_date->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
        {    
         $not_date=$row25['notification_date'];
         $def=$row25['defects'];

       }



       list($year,$month,$day)=explode('-',$not_date);
       $not_date=$day.'/'.$month.'/'.$year;
       if($not_date=='//'){
        $not_date='NA';
      }
	  // 2 loop
      $st25=$db->prepare("select * from e_case_detail_fees where filing_no =? ");
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
       $st35=$db->prepare("select * from master_section_act where id=? ");
       $st35->bindParam(1, $E_sec_id, PDO::PARAM_STR);
       $st35->execute();

       while ($row35 = $st35->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
       {
         $E_add_sec_id=$row35['section_companies'];                      
         $r.=$E_add_sec_id.',';


       }
     }
   }

		// 2 loop end		   
   $filing_nosend=$filing_no2.'-'.$qq1cc.'-'.$miscellaneous_ref_no;
   $filing_no_send=base64_encode($filing_nosend); 

   if($filing_no!='')
   {

    if($_SESSION['menuaccess_codeall'] =='2')
    {
      ?>
      <tr style="background-color: #BDFCC9;">
        <?php 
      }
      ?>
      <?php


   if($_SESSION['menuaccess_codeall'] =='11')//astt registar
   {

    if($def =='N')
    { 


     ?>  
     <tr style="background-color: #BDFCC9;">
     <?php }?>
     <?php if($def =='Y')



     { ?>  
      <tr style="background-color: #f8c6bf;">
        <?php
      }
      ?>

      <?php 
    }
    ?>




    <td><?php echo htmlspecialchars($SER_COUT31++).".";?></td>
    <td><?php if($document_filed_date =='11/11/1111' OR $document_filed_date =='//'){$document_filed_date="";}else {echo htmlspecialchars($document_filed_date);}?></td>

    <?php
    $countdocsql=$db->prepare("select count(*) from document_upload where miscellaneous_ref_no='$miscellaneous_ref_no' and filing_no='$filing_no' and scrutiny='0' and display='1' and subdoctype='$subdoctype' and party_type IN (select party_flag from e_master_govt_body)");
    $countdocsql->execute();
    $number_of_rows = $countdocsql->fetchColumn();
    ?>
    <td><?php echo htmlspecialchars($miscellaneous_ref_no);
    echo "<br><span style='color:red'>";
    echo "(No.of Docs - ".$number_of_rows.")";
  echo "</span>";		 ?></td>
  <td><?php echo htmlspecialchars($filing_no);?></td>


  <?php 
}
}

?>

<td><?php
$E_nameP= htmlspecialchars_decode($E_nameP,ENT_NOQUOTES);
$E_nameR= htmlspecialchars_decode($E_nameR,ENT_NOQUOTES);

echo strtoupper($E_nameP)."&nbsp;<font color='blue' size='2'><br> Vs. <br> </font>&nbsp;".strtoupper($E_nameR);?></td>        
<td><?php echo rtrim($r,','); ?></td>

<td><?php echo htmlspecialchars($subdoctype_name); ?></td>
<?php 
if($_SESSION['menuaccess_codeall'] =='11')
{
  ?>
  <td><?php echo htmlspecialchars($not_date); ?></td>
  <?php 
}
?>

<?php 


if($_SESSION['menuaccess_codeall'] =='2')
{

  if($scrutiny_value=='0' && $display=='1'){
    ?>

    <td><h3><span class="label label-info">
     <a style="color: #FFFFFF;" href="./scrutiny/user_scrutiny1.php?ccase=<?php echo 
     htmlspecialchars($c_case);?>&subdoctype=<?php echo htmlspecialchars($subdoctype);?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">
   Scrutiny</a>
 </span></h3></td>
 <?php 
}
?>

<?php

}  
?>
<?php 
if($_SESSION['menuaccess_codeall'] =='11')
{
  if($scrutiny_value=='0'  && $display=='1'){
    ?>

    <td><h3><span class="label label-info">
     <a style="color: #FFFFFF;" href="./scrutiny/varify_cases.php?ccase=<?php echo htmlspecialchars
     ($c_case);?>&subdoctype=<?php echo htmlspecialchars($subdoctype);?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>">Scrutiny Verify</a>
   </span></h3></td>
   <?php 
 }

}
?>


<?php 	  
}  
}
}

?>

<?php include 'footer1.php';?>
</div>

<script>
  $('.load_container').fadeOut(500);
</script>

<!-- computation note modal -->
<div id="comp_note" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:90%;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="comp_note_body">
        <p>Some text in the modal.</p>
      </div>
    </div>

  </div>
</div>

<!-- computation note modal -->
<div id="view_doc" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:90%;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="view_doc_body">
        <p>Some text in the modal.</p>
      </div>
    </div>

  </div>
</div>

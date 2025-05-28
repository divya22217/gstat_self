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
	 action = "filed.php";
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
	 action = "filed.php";
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
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d');
include("./db_inc1.php");
include("./db_inc2.php");
include_once('custom/custom_function.php');
//session_start();

/*  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */   


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

  /*if(isset($_POST['submit_doc'])){
    if(!empty($_POST['filing_no_doc'])) {
      //print_r($_POST);
       $filing_no_doc = $_POST['filing_no_doc'];
      //die('on top');
    }
  }*/
	
	/*if($main_id !='9999')
	{
		
			session_unset();     // unset $_SESSION variable for the run-time
				session_destroy();
				echo "You Are Not Access This Page......";
				header("Location: ../login.php?aa=100");
				die();
		
	}*/
	
	
// This code not use next time .......	Schema session create Hear....
	
	
	$sessionUserType=htmlspecialchars($_SESSION['id']);


$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";


$link_scrutiny_idaccess='1';

include './db_inc2.php';



?>
<?php

include 'header.php';
//include 'sidebar.php';


?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  
  <form name="frm" method="post" >
    <!-- Content Header (Page header) -->
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
				  
 <?php 

	  
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
<style type="text/css">
div.hidden {
display: none;
}

</style>			  				 

<?php
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
                        <input type="radio" name="c_case" value="1" onChange="javascript:submitForm3();" <?php if ($c_case == 1) {echo 'checked';} ?>><b>Fresh cases</b>&nbsp;&nbsp;
                    </th>
                    
					<th>
                        <input type="radio" name="c_case" value="8" onChange="javascript:submitForm3();" <?php if ($c_case == 8) {echo 'checked';} ?>><b>Refiled Cases</b>&nbsp;&nbsp;
                    </th>
                   <th>
                        <input type="radio" name="c_case" value="5" onChange="javascript:submitForm3();" <?php if ($c_case == 5) {echo 'checked';} ?>><b>Defective Cases</b>&nbsp;&nbsp;
                    </th>
					<th>
                        <input type="radio" name="c_case" value="12" onChange="javascript:submitForm3();" <?php if ($c_case == 12) {echo 'checked';} ?>><b>Filed Caveat</b>&nbsp;&nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php 
$_SESSION['qqcc'] = rand();
$qq1cc=$_SESSION['qqcc'];
 $app_pet = isset($_REQUEST['app_pet']) ? $_REQUEST['app_pet'] : 'P';
if ($c_case == "1") {include_once('custom/fresh_filed_cases.php'); }
if ($c_case == "8") {include_once('custom/refiled_cases.php');}
if ($c_case == "5") {include_once('custom/defective_filed_cases.php');}
if ($c_case == "12") {include_once('custom/view_filed_caveat.php');}
?>
                    </div>
 
  
  </div>
  </div>
  </div>
  </section>
  </form>
<?php include 'footer1.php';?>
  <script>
$('.load_container').fadeOut(500);
</script>

<script>
            function OpenDMSForm(step, filing_no, dms_type, misc_no) {
                document.getElementById("step").value = step;
                document.getElementById("filing_no").value = filing_no;
                document.getElementById("dms_type").value = dms_type;
                document.getElementById("misc_no").value = misc_no;
                document.getElementById("frm_dms").submit();

            }
            </script>

            <form action="https://efiling.nclat.gov.in/dmsnclat/dashboard" method="POST" target="_blank" id="frm_dms">
                <input type="hidden" id="step" name="step" value="" />
                <input type="hidden" id="filing_no" name="filing_no" value="" />
                <input type="hidden" id="dms_type" name="dms_type" value="" />
                <input type="hidden" id="misc_no" name="misc_no" value="" />
            </form>
			  
<?php		  }			  
  ?>

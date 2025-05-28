<link rel="stylesheet" href="../assets/css/datatables.min.css">
<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
ob_start();
date_default_timezone_set("Asia/Kolkata");

$notification_date=$_REQUEST['notification_date'];
include("../db_inc1.php");
session_start();
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$user_court = $_SESSION['user_court'];
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);

$flag =htmlentities($_REQUEST['flag']);
$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];



if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{

	$sessionUserType=htmlspecialchars($_SESSION['id']);


	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";


	$link_scrutiny_idaccess='1';

include '../inheader.php';
//include '../insidebar.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);  */

?>
<style>
.sorting, .sorting_asc, .sorting_desc {
    background : none;
}
</style>
<?php 

$form2 = sha1( uniqid('auth', true) );
$_SESSION['form2_scruniny'] = $form2;


$frmAction=$_REQUEST['frmAction'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
 
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";

if($_REQUEST['hash1']!='')
{
	$hasdata = htmlspecialchars(base64_decode($_REQUEST['hash1']));
	$hasdata = explode("/",$hasdata);	
	$idddel = $hasdata[0];
	$formactiondel = $hasdata[1];
	if($formactiondel =='delete')
	{

/*get bench delete*/
$st=$db->prepare("select from_list_date,bench_no,entry_date from $schemas.bench where id=? order by from_list_date asc ");
$st->bindParam(1, $idddel, PDO::PARAM_STR);
$st->execute();
while($benchdata = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)){
	$bench_listing_date =$benchdata['from_list_date'];
	$bench_nono = $benchdata['bench_no'];
	$bench_entry =$benchdata['entry_date'];
}



$s2t=$db->prepare("select * from $schemas.case_allocation_temp where listing_date=? and bench_no=?");
$s2t->bindParam(1, $bench_listing_date, PDO::PARAM_STR);
$s2t->bindParam(2, $bench_nono, PDO::PARAM_STR);
$s2t->execute();

/*while($benchdata1 = $s2t->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)){
	echo $filing_no =$benchdata1['filing_no'];
}*/
if($s2t->rowCount()>0)
{
	
	 echo $_SESSION['err_msg']="Please First Transfer the case then delete the bench";
	header("location:bench_composition_delete.php");
	
}	
if($s2t->rowCount()==0)
{
	 try {
    $db->beginTransaction();

	$st=$db->prepare("delete from $schemas.bench_judge where bench_no=? and from_list_date=? and entry_date=?");
$st->bindParam(1, $bench_nono, PDO::PARAM_STR);
$st->bindParam(2, $bench_listing_date, PDO::PARAM_STR);
$st->bindParam(3, $bench_entry, PDO::PARAM_STR);
$st->execute();


$st=$db->prepare("delete from $schemas.bench_purpose_priority where bench_no=? and from_date=? and entry_date=?");
$st->bindParam(1, $bench_nono, PDO::PARAM_STR);
$st->bindParam(2, $bench_listing_date, PDO::PARAM_STR);
$st->bindParam(3, $bench_entry, PDO::PARAM_STR);
$st->execute();


	$st=$db->prepare("delete from $schemas.bench where id=? ");
	$st->bindParam(1, $idddel, PDO::PARAM_STR);
	$st->execute();
	$db->commit();
echo "DELETED SUCCESSFULLY ";
//die();
	}catch(Exception $e){
		$db->rollBack();
		echo "Something went wrong "; 
		
	}
}
	}
}



/*******set single judge in dividion bench */

if($_REQUEST['hash']!=''){
	$hashdata= explode('/',trim(base64_decode($_REQUEST['hash'])));
	 $benchdate = $hashdata[0];
	 $judgeCode = $hashdata[1];
	 $benchno = $hashdata[2];
 $action_type = $hashdata[3];
	


$qry=$db->prepare("update $schemas.bench_judge set display='$action_type' where judge_code=? and
		from_list_date=? and to_list_date=? ");

		$qry->bindParam(1, $judgeCode, PDO::PARAM_STR);
		$qry->bindParam(2, $benchdate, PDO::PARAM_STR);
		$qry->bindParam(3, $benchdate, PDO::PARAM_STR);
		$qry->execute();



}



?>

<script>

	function doit(){
		if (!window.print){
			alert("You need NS4.x to use this print button!")
			return
		}
		window.print()
		window.close()
	}
	function submitForm()
	{
		with(document.frm)
		{
			action = "<?php echo $_SERVER['PHP_SELF'];?>";
			submit();
		}
	}
</script>

<script language="javascript">
function popsurety_pending_report(cfy)

    {
    	
    		var url = "./modify_bench.php?hash2="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");   
    		
    }



    function update_bench(cfy){
		swal({
            title: "Are you sure to modify this bench?",
            text: "", 
            icon: "warning",
            buttons: true,
            dangerMode: false,
			})
			.then((willDelete) => { 
				 if (willDelete) {	
					var url = "./bench_modify_r.php?hash2="+cfy;
					window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");   
				 }else{
				 }
			});			
    }
	
	function delete_bench(cfy){
		swal({
            title: "Are you sure to delete this bench?",
            text: "", 
            icon: "warning",
            buttons: true,
            dangerMode: false,
			})
			.then((willDelete) => { 
				 if (willDelete) {	
				 window.location.href = "./bench_composition_delete.php?hash1="+cfy;
				 }else{
				 }
			});			
    }
</script>
<!-- <link rel="stylesheet" href="../includes/style.css" type="text/css">
<link rel="stylesheet" href="../includes/jquery_ui/jquery-ui.css">
<script src="../includes/jquery_ui/jquery-1.12.4.js"></script>
<script src="../includes/jquery_ui/jquery-ui.js"></script>
<script src="../includes/jquery_ui/date.js"></script> -->
<script>
function changeTime(chtime,id)
{
		var schema = '<?php echo strtolower($schemas);?>';
		$.ajax({
		method: "GET",
		url: '../account/bank_ajax.php',
		data: {newtime:chtime,id:id,scid:schema}
		})
		.done(function (msg) {
		location.reload();
		});
}
</script>


  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
		

    <!-- Main content -->
    <section class="content">
     
      <!-- Default box -->
      <div class="box box-success">
        <div class="box-header with-border">
   
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="tbl"  align="center">
	
<tr>
<td colspan="10">



<?php 
if(isset($_SESSION['err_msg'])&& $_REQUEST['hash1']==''){
unset($_SESSION['err_msg']);
//echo $_SESSION['err_msg'];
echo "<script>alert('Please First Transfer the case then delete the bench');</script>";


}

?>

<font size = 3><b><CENTER>BENCH COMPOSITION </CENTER></b></font>

</table>
<?php

list($d,$m,$y) = explode('/',$fromdate);
$search_date = $y.'-'.$m.'-'.$d;
$count='0';


$older= date("Y-m-d",strtotime("-60 day"));
$newer= date("Y-m-d",strtotime("+60 day"));

if($_SESSION['menuaccess_codeall'] == 11 || $_SESSION['menuaccess_codeall'] == 6)
	$query = "select * from $schemas.bench where from_list_date between ? and ?  order by from_list_date desc";
else
	$query = "select * from $schemas.bench where court_no = $user_court and (from_list_date between ? and ? ) order by from_list_date desc"; 

$qry1=$db->prepare($query);
$qry1->bindParam(1, $older, PDO::PARAM_STR);
$qry1->bindParam(2, $newer, PDO::PARAM_STR);
$qry1->execute();
$flag=0;
?>

<!--<a href="javascript:doit()"><font color='red'>print </font></a>-->
              <div class="table-responsive" style="font-size:12px;">
                <table class="table table-hovered table-bordered table-striped" id="bench_list" >
					<thead style="background-color:#846313;color:#ffffff;">
					  
						<tr>

							<th >Sr. No.</th>
							<th>BENCH NO</th>
							<th><b>COURT NO</th>
							<th><b>Type</th>
							<th>FROM</th>
							<th>Members</font></th>
							<th>PRESIDING</th>
							<th>MODIFY BENCH</th>
							<th>DELETE</th>

						</tr>
					</thead>
				<tbody>
<?php
while ($judgerow1 = $qry1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$flag=1;
$count++;
?>

<tr>
<td><?php echo htmlspecialchars($count); ?></td>
<?php
$court_no=$judgerow1['court_no'];
$bench_nature=$judgerow1['bench_nature'];
$list_flag = $judgerow1['list_flag'];
?>

</b>
<td>
<?php echo htmlspecialchars($bc=$judgerow1['bench_no']); ?></td>

<td>
<?php
$display_court_value=$db->prepare("select display_court_text  from $schemas.court where court_no = ?");
$display_court_value->bindParam(1, $court_no, PDO::PARAM_STR);
$display_court_value->execute();
$display_court_value = $display_court_value->fetchColumn();
 echo $display_court_value; ?>
</td>
<td>
<?php
 echo ($list_flag == '2')?'Supplementry':'Daily'; ?>
</td>
<td><?php 
$ttt = $judgerow1['from_list_date'];
list($year,$month,$day)=explode('-',$judgerow1['from_list_date']);
$from_list_date=$day.'/'.$month.'/'.$year;
/* list($year,$month,$day)=explode('-',$judgerow1['to_list_date']);
$to_list_date=$day.'/'.$month.'/'.$year; */
echo htmlspecialchars($from_list_date); 
?>
</td>



<td nowrap="nowrap">
<?php
/* $qry11=$db->prepare("select *  from $schemas.bench_judge where bench_no=? and 
		from_list_date=? and from_time=? 
		and to_list_date=? and  to_time=?");  */
		
 $qry11=$db->prepare("select *  from $schemas.bench_judge where bench_no=? and 
						from_list_date=?  and to_list_date=?");
		
$cds=$judgerow1['from_list_date'];
$as=$judgerow1['from_time'];
$pp=$judgerow1['to_list_date'];
$sa=$judgerow1['to_time'];
$qry11->bindParam(1, $bc, PDO::PARAM_STR);
$qry11->bindParam(2, $cds, PDO::PARAM_STR);
//$qry11->bindParam(3, $as, PDO::PARAM_STR);
$qry11->bindParam(3, $pp, PDO::PARAM_STR);
//$qry11->bindParam(5, $sa, PDO::PARAM_STR);
$qry11->execute();
$alljudge = $qry11->fetchALl();

$record = count($alljudge);

//echo "<pre>"; print_r($alljudge); die;
foreach ($alljudge as $row2)

  {
//if($row2[display]==true){ $display11 ='Remove';}else{ $display11='Add';} 

	$cnt++;

	//if($cnt >1){ echo "&";}
	$jcode =$row2['judge_code'];
   $sql1 = "select judge_name from $schemas.master_judge where judge_code =? ";
	$sth = $db->prepare($sql1);
	$sth->bindParam(1, $jcode, PDO::PARAM_STR);
	$sth->execute();
	$judge = $sth->fetchColumn() ;
	echo htmlspecialchars($judge);


	  if($record >1){

if($display11 =='Add'){
 $hash = base64_encode($search_date.'/'.$row2['judge_code'].'/'.$bc.'/true');
}
else{
 $hash = base64_encode($search_date.'/'.$row2['judge_code'].'/'.$bc.'/false');
}




		  echo '&nbsp;&nbsp;<a href="bench_composition_delete.php?hash='.$hash.'">'.$display11.'</a>';}
	 ?> <br>
	 <?php 
  }

?>

</td>



</td>

<td nowrap="nowrap" style="color:red;">
<?php 
$sql = "select judge_name from $schemas.master_judge where judge_code=? ";
$sthq = $db->prepare($sql);
$zz=$judgerow1['presiding'];
$sthq->bindParam(1, $zz, PDO::PARAM_STR);
$sthq->execute();
$judgename = $sthq->fetchColumn();
echo htmlspecialchars($judgename);
?> 
</td>
<?php   $hash2 = $judgerow1["id"].'/'.$bench_nature.'/'.$court_no.'/'.$ttt.'/'.$judgerow1["location_code"];?>

<!--
<td align="center">

<a href="javascript:popsurety_pending_report('<?php //echo htmlspecialchars(base64_encode($hash2));?>');"   onclick="return confirm('Are you sure to modify this bench purpose priority?');"><img src="edit.png" name="imgCalendar" width="20" height="20" border="0" alt=""> </a>
</td>
-->
<?php   $hash34 = $judgerow1["id"].'/'.$bench_nature.'/'.$judgerow1["location_code"].'/'.$bc;?>
<td>

<a href="javascript:update_bench('<?php echo htmlspecialchars(base64_encode($hash34));?>');" ><img src="edit.png" name="imgCalendar" width="20" height="20" border="0" alt=""> </a>
</td>

<td align="center"> 
<?php $hash1 = $judgerow1["id"].'/delete';?>
<a href="javascript:delete_bench('<?php echo htmlspecialchars(base64_encode($hash1));?>')" ><img src="delete.png" name="imgCalendar" width="20" height="20" border="0" alt=""> </a>
</td>


	


<?php

//if($flag==0){ ?>
<!--<td colspan=7><p align="center"><font face="Verdana" size="2" color="red">No Record </font></p></td> <?php  //} ?>

</td>-->
</tr>
<?php
}
?>
				</tbody>
               </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            </form>

      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script>
$(document).ready(function() {
	$('#bench_list').DataTable();
} );
</script>
  
  <?php 
  include '../infooter.php';

  ?>
  <script src="../assets/js/datatables.min.js"></script>
  <?php } ?>

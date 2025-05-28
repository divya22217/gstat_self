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

	/*
	if($main_id =='9999' and $localadmin =='0')
	{
		if($_SESSION['menuaccess_codeall'] !='2')
		{
			echo "Access Problem.....";
			header("Location: ../index.php");
			die();
		}
	}*/
	

	$filing_no_next=$_REQUEST['filing_no_next'];
	$hash1=htmlspecialchars(base64_decode($filing_no_next));
	$hash1 = explode("-", $hash1);
	$filing_no_fou=$hash1[0];
	
	$token_fou= $hash1[1];



	// This code not use next time .......	Schema session create Hear....

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
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <section class="content">
    
  <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">  
    
     <div class="form-group">
				 <div class="col-md-12">
				 
<script language="javascript">
function submitForm1()
{
with(document.frm)
{
/*if(prt_type=='1')
{*/
if(case_type.value == "select")
{
alert("Select Case Type");
case_type.focus();
return false;
}
if(case_no.value == "")
{
alert("Enter Case Number");
case_no.value='';
case_no.focus();
return false;
}

if(case_year.value == "")
{
alert("Enter 4 digit Case Registration Year");
case_year.focus();
return false;
}

/*}
if(prt_type =='2')
{
if(dairyNo.value=='')
{
alert("Please Enter Dairy No... ");
dairyNo.focus();
return false;
}/*
if(dairyyear.value=='')
{
alert("Please Enter Case Year... ");
dairyyear.focus();
return false;
}
}*/

action="case_proceeding_with_connected.php";
submit();
}

}
function submitForm()
{
with(document.frm)
{

action = "case_proceeding_with_connected.php";
submit();
}
}
function run()
{
with(document.frm)
{


action = "case_proceeding_with_connected.php";
submit();
}
}
function validate()
{
with(document.frm)
{

if(case_type.value == "select")
{
alert("Select Case Type");
case_type.focus();
return false;
}
if(case_no.value == "")
{
alert("Enter  Case Number");
case_no.focus();
return false;
}
if(isNaN(case_no.value) == true)
{
alert("Please Enter Numeric Case No.");
case_no.select();
return false;
}
if(case_year.value == "")
{
alert("Enter 4 digit Case Registration Year");
case_year.focus();
return false;
}
if(case_year.value < 4)
{
alert("Enter 4 digit Case Registration Year");
case_year.focus();
return false;
}
if(isNaN(case_year.value) == true)
{
alert("Please Enter Numeric Case Year");
case_year.select();
return false;
}

//alert(pen_dis.value);
var status_value=0;
for (i=0;i< pen_dis.length;i++)
{
if(pen_dis[i].checked ==true)
{
status_value = pen_dis[i].value;
}

}

if(status_value=='P')
{

if(action_type.options[action_type.selectedIndex].value == "")
{
alert("Please select Action Type");
action_type.focus();
return false;
}

if(purpose_code.options[purpose_code.selectedIndex].value == "")
{
alert("Please select purpose of next hearing   ");
purpose_code.focus();
return false;
}



if(criteria.options[criteria.selectedIndex].value == "")
{
alert("Please Select Criteria");
criteria.focus();
return false;
}
if(criteria.value=='FD')
{
if(next_list_date.value =="")
{
alert("Please enter valid list it on Date ");
next_list_date.focus();
return false;
}

}

}

if(status_value=='D')
{
//code for disposal validation

if(disposal_nature.options[disposal_nature.selectedIndex].value == "")
{
alert("Please Select Disposal Nature");
disposal_nature.focus();
return false;
}
if(disposal_date.value =="")
{
alert("Please enter valid Disposal Date ");
disposal_date.value='';
disposal_date.focus();
return false;
}

}//close status_value=2



action="case_proceeding_with_connected_action.php";
submit();
document.frm.submit1.disabled = true;
document.frm.submit1.value = 'Please Wait...';
return true;
}

}
function addNumbers(val)
{
var c=document.getElementById("next_list_date").value.length;
if(c==2 || c==5  )
{
var newval = val+ "/";
document.getElementById("next_list_date").value=newval;
}

}
function addNumbers1(val)
{
var c=document.getElementById("disposal_date").value.length;
if(c==2 || c==5  )
{
var newval = val+ "/";
document.getElementById("disposal_date").value=newval;
}

}
function popsurety_pet_adv_name(cfy)

{

var url = "fram_advocate.php?filing_no="+cfy;
var width = 800;
var height = 800;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',resizable=1';

window.open(url,"print",params);
}
function popsurety_amicus_curiae(cfy)

{

var url = "amicus_curiae.php?filing_no="+cfy;
var width = 800;
var height = 800;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',resizable=1';

window.open(url,"print",params);
}
function popsurety_evidence_ref(cfy)

{

var url = "evidence_ref.php?filing_no="+cfy;
var width = 800;
var height = 800;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',resizable=1';

window.open(url,"print",params);
}




function SetBg(txt)
{
txt.style.backgroundColor='#ffff99';
}
function UnSetBg(txt)
{
txt.style.backgroundColor='white';
}


function popdashboard()
{

var url = "dashboard.php";
var width = 800;
var height = 1300;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',scrollbars=1';

window.open(url,"print",params);
}


function popdashboard1()
{

var url = "dashboard1.php";
var width = 800;
var height = 1300;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',scrollbars=1';

window.open(url,"print",params);
}



//  END
</script>
<style>
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding:0px;}

</style>



</head>
<body class="hold-transition skin-blue sidebar-mini">



<table  border="2" class="table"  align="center" >
<tr>
<td valign="top" align="right" colspan="16"><center>
<b><font face="Verdana" size="3"><u>DAILY CASE PROCEEDING </u></font> </b>
</td>
</tr>


<!--tr>
<td>
<b>
<a href="javascript:popdashboard();"><font size="5" color="red">Update Display Board</font>
</a>
</b>

</td>


<td>
<b>
<a href="javascript:popdashboard1();"><font size="5" color="red">Set Court To Not In Session </font>



</a>
</b>

</td>


</tr-->



<tr><td valign="top" align="center" colspan="16">
<font face="Verdana" size="2">Fields marked with a <font color='red'>*</font> are compulsory.</font> </td>
</tr>



<form name="frm" method="post" action="case_proceeding_with_connected_action.php">
<input type="hidden" name="listing_date" value="<?php echo htmlspecialchars($listing_date); ?>">

<?php

$msghash=$_REQUEST['msghash'];
if($msghash !='')
{
$msghashz=(base64_decode($msghash));

$msghashz = explode("-", $msghashz);

$msg1 = $msghashz[0];
$case_type = $msghashz[1];
$case_year = $msghashz[2];

if($msghashz != '')
{
?>
<tr>
<td height="30" align="center" cellpadding="0" colspan="16">
<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
<span class="error"> <b> <?php echo htmlspecialchars($msg1); ?></span></font>
</td>
</tr>
<?php
}
}

$case_type=$_REQUEST['case_type'];
$case_year=$_REQUEST['case_year'];
?>

<tr><td height="30" align="center"  colspan="16">
<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="black">
<b> <?php echo "SEARCH BY CASE TYPE/CASE NUMBER/CASE YEAR"; ?></b></font></td>
</tr>
<input type="hidden" name="judge" value="<?php echo htmlspecialchars($judgecode);?>" />
<input type="hidden" name="c_date" value="<?php echo htmlspecialchars($c_date);?>" />
<input type="hidden" name="c_case" value="<?php echo htmlspecialchars($c_case);?>" />
<input type="hidden" name="benchnature" value="<?php echo htmlspecialchars($benchnature);?>" />
<input type="hidden" name="courtno" value="<?php echo htmlspecialchars($courtno);?>" />





<tr>
<td  colspan="16" align="left"><font face="Verdana" size="2">

<!--font face="Verdana" size="2"><span class="error">*</span>Search Case:</font>
<?php $prt_type = isset($_REQUEST['prt_type']) ? $_REQUEST['prt_type'] :'1'; ?>

<select-- id="prt_type" name="prt_type" onchange="javascript:submitForm();" class="frm-field required" >
<option value="1" <?php if($prt_type == 1) { print " selected"; } ?> >Case No. Wise</option>
<option value="2" <?php if($prt_type == 2) { print " selected"; } ?> >Diary No. Wise</option>
</select-->



<?php if($prt_type =='1')
{?>
<?php  $bench_type = isset($_REQUEST['bench_type']) ? $_REQUEST['bench_type'] :'';?>
<?php  $case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] :'';?>
<?php  $case_no = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] :'';?>
<?php  $case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] :'';?>



<span class="error">*</span>Bench:
<select name="bench_type">

<?php
$st = $db->prepare("select * from $schemas.bench_location where display = 'TRUE' order by bench_location_name asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ctc=htmlspecialchars($row['bench_location_code']);
if($bench_type == $ctc)
{
print "<option value=".htmlspecialchars($row['bench_location_code'])." selected>".htmlspecialchars($row['bench_location_name'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['bench_location_code']).">".htmlspecialchars($row['bench_location_name'])."</option>";
}
}

?>

</select>
<span class="error">*</span>Case Type:
<select name="case_type">

<?php
$st = $db->prepare("select * from case_type where display = 'TRUE' order by case_type_desc asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ctc=htmlspecialchars($row['id']);
if($case_type == $ctc)
{
print "<option value=".htmlspecialchars($row['id'])." selected>".htmlspecialchars($row['case_type_desc'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['id']).">".htmlspecialchars($row['case_type_desc'])."</option>";
}
}

?>

</select>
<font face="Verdana" size="2"><span class="error">*</span>Case No:</font>

<input type="text" id="cno" maxlength="7" autocomplete="off" size="6" name="case_no" value="<?php print htmlspecialchars(ltrim($case_no,0)); ?>"/>&nbsp;&nbsp;
<font face="Verdana" size="2"><span class="error">*</span>Case Year:</font>
<input type="text" id="cy" maxlength="4" autocomplete="off" size="4" name="case_year" value="<?php if($case_year!=''){print htmlspecialchars($case_year);}else{ print htmlspecialchars($curYear);} ?>" />
<?php } ?>

<?php
if($prt_type =='2')
{
?>

<?php  $dairyNo = isset($_REQUEST['dairyNo']) ? $_REQUEST['dairyNo'] :'';?>
<?php  $dairyyear = isset($_REQUEST['dairyyear']) ? $_REQUEST['dairyyear'] :'';?>
<font face="Verdana" size="2"><span class="error">*</span>Dairy No:</font>
<input onkeypress="return isNumberKey(event)"  autocomplete="off" maxlength="16" name="dairyNo" value="<?php echo htmlspecialchars(htmlentities($dairyNo));?>"  placeholder="Dairy No." type="text" required="required">
<!--font face="Verdana" size="2"><span class="error">*</span>Dairy Year:</font>
<input onkeypress="return isNumberKey(event)" autocomplete="off" maxlength="4" name="dairyyear" value="<?php echo htmlspecialchars(htmlentities($dairyyear));?>"  placeholder="Dairy Year" type="text" required="required" size="4"-->

<?php } ?>



<input type="button"  size=5 name="go" id="gobtn" value="Go" onClick="javascript:submitForm1();"> </td>
</tr>

<?php 
if($case_year =='' and $case_no =='')
{
	?>
 </td></tr> </table>
			
                             
      </form> 
  </body></div></div></div>  
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


<tr><td align="left" valign="top" colspan="1" width="40%" >

<?php

//CODE CHANGE 

if($prt_type =='1')
{
/*$clen = strlen($case_type);
$clength = 3-$clen;
for($c=0;$c<$clength;$c++)
$case_type = "0".$case_type;

$clen = strlen($case_no);
$clength = 7-$clen;
for($c=0;$c<$clength;$c++)
$case_no = "0".$case_no;
if($case_no == 000000)
$case_no='';

$chr=4;// char for first hard code digit of filing no
$c_no=$chr.$case_type.$case_no.$case_year;*/

$st = $db->prepare("select filing_no from $schemas.case_detail where location_code=? and case_type=? and case_no=? and case_year=?");
$parm = array($bench_type,$case_type,$case_no,$case_year);
//print_r($parm);
//$st->bindParam(1, $c_no, PDO::PARAM_STR);
}
if($prt_type == '2')
{
//$fil_no = $hsc.str_pad($dairyNo, 6,'0',STR_PAD_LEFT).$dairyyear;
$fil_no = $dairyNo;

//$display_accept='P';
$st = $db->prepare("select filing_no from $schemas.case_detail where filing_no=?");
//$st->bindParam(1, $fil_no, PDO::PARAM_STR);
$parm = array($fil_no);
//$st->bindParam(2, $display_accept, PDO::PARAM_STR);

}

$st->execute($parm);
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

$filing_no = htmlspecialchars($row['filing_no']);
}
if(strlen($c_no)==16 and $filing_no =='')
{
echo "<h1><font color='red'><center>RECORD NOT FOUND CONTACT TO ADMIN....</center></font></h1>";

}

if($filing_no != '')
{
$st = $db->prepare("select * from $schemas.case_detail where filing_no=?");

$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

?>

<input type="hidden" name="filing_no" value="<?php print htmlspecialchars($filing_no);?>" />
<?php

while ($rw2 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

$filing_no=htmlspecialchars($rw2['filing_no']);
$pt_name=htmlspecialchars($rw2['pet_name']);
$rs_name=htmlspecialchars($rw2['res_name']);
$ci_cri=htmlspecialchars($rw2['ci_cri']);
$status=htmlspecialchars($rw2['status']);
$pet_code=htmlspecialchars($rw2['pet_org_type']);
$res_code=htmlspecialchars($rw2['res_org_type']);
$res_type=htmlspecialchars($rw2['res_type']);
$pet_type=htmlspecialchars($rw2['pet_type']);
$res_adv=htmlspecialchars($rw2['res_adv']);
$pet_adv=htmlspecialchars($rw2['pet_adv']);

}

if($pet_type =='2')
{
if($pet_code > 0)
{

$stp = $db->prepare("select org_name from $schemas.master_licensor where org_code=?");
$stp->bindParam(1, $pet_code, PDO::PARAM_INT);
$stp->execute();
$pet_org_name = $stp->fetchColumn();

}
}

if($pet_code > 0 and $pet_type ==4)
{
$stp1 = $db->prepare("select org_name from $schemas.master_licensee where org_code=?");
$stp1->bindParam(1, $pet_code, PDO::PARAM_INT);
$stp1->execute();
$pet_org_name = $stp1->fetchColumn();


}

if($res_type =='2')
{
if($res_code > 0)
{
$str = $db->prepare("select org_name from $schemas.master_licensor where org_code=?");
$str->bindParam(1, $res_code, PDO::PARAM_INT);
$str->execute();
$res_org_name = $str->fetchColumn();

}
}

if($res_type =='4')
{
if($res_code > 0)
{
$str1 = $db->prepare("select org_name from $schemas.master_licensee where org_code=?");
$str1->bindParam(1, $res_code, PDO::PARAM_INT);
$str1->execute();
$res_org_name = $str1->fetchColumn();

}
}


echo "<h2><font color='red'><center>";
if($pt_name ==''){echo htmlspecialchars(ucwords(strtoupper($pet_org_name)));}else{echo htmlspecialchars(ucwords(strtoupper($pt_name)));}
echo "</font><br><font color='blue'>VS</font><br><font color='red'>";
if($rs_name ==''){echo htmlspecialchars(ucwords(strtoupper($res_org_name)));}else{echo htmlspecialchars(ucwords(strtoupper($rs_name)));}

echo "</center></font></h2>";
?></td>	<td align="left" valign="top" colspan="8" >
<?php 
echo "<br>";
echo "<h2><font color='red'><center>";
if($status =='P'){echo htmlspecialchars("CASE IS PENDING")."</center></font></h2>";}

if($status=='D')
{
echo "</center><font color='red'></h2>";
echo htmlspecialchars("CASE IS DISPOSED");
echo "</center></font></h2>";
echo "<br>";

//Disposed Date

$st = $db->prepare("select * from $schemas.case_disposal where filing_no=? ");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
while ($rw4 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$filing_no=htmlspecialchars($rw4['filing_no']);
$disposal_date=htmlspecialchars($rw4['disposal_date']);
$disposal_nature=htmlspecialchars($rw4['disposal_nature']);
$court_no=htmlspecialchars($rw4['court_no']);
$bench_nature=htmlspecialchars($rw4['bench_nature']);
$bench_no=htmlspecialchars($rw4['bench_no']);
$remarks=htmlspecialchars($rw4['remarks']);

}

echo "<table  border='1' class='table'  align='center'> ";
echo "<tr><td >";
echo "</center><font color='blue'></h3>";
echo 'Disposal Date:'.htmlspecialchars($disposal_date);
echo "</center></font></h3>";
echo "</td></tr>";
echo "<tr><td>";
echo "</center><font color='blue'></h3>";
echo 'Court No.:'.htmlspecialchars($court_no);
echo "</center></font></h3>";
echo "</td></tr>";
echo "<tr><td>";
echo "</center><font color='blue'></h3>";
echo 'Bench:'.htmlspecialchars($bench_nature);
echo "</center></font></h3>";
echo "</td></tr>";
echo "<tr><td>";
echo "</center><font color='blue'></h3>";
echo 'Disposal Remarks:'.htmlspecialchars($remarks);
echo "</center></font></h3>";
echo "</td></tr>";

echo "</table>";
?>

<?php 
if($filing_no =='' and $case_no !='' and $status=='D')
{
	?>
 </td></tr> </table>
			
                             
      </form> 
  </body></div></div></div>  
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
<?php 
die();
}

?>
</td></tr>

<?php

//check where case is allocate in listing
if($filing_no !=''){
$sttrw = $db->prepare("select listing_date,court_no from $schemas.case_allocation where filing_no=?");
$sttrw->bindParam(1, $filing_no, PDO::PARAM_STR);
$sttrw->execute();
$rw2w = $sttrw->fetch(PDO::FETCH_ASSOC);
if(!empty($rw2w)){
if($rw2w[listing_date] !='' && $rw2w[listing_date] !='1111-11-11'){
list($y,$m,$d) = explode("-",$rw2w[listing_date]);
$listing_date_next = $d.'/'.$m.'/'.$y;
echo '<tr><td colspan="10"  face="Verdana" style="color:blue; font-size: 14px;"> This Case already listed on date '.$listing_date_next.' in court no. '.$rw2w[court_no].' </td></tr>';

}
if($rw2w[listing_date] !='' && $rw2w[listing_date] =='1111-11-11'){

echo '<tr><td colspan="10"  face="Verdana" style="color:blue; font-size: 14px;">Listing date not Fixed </td></tr>';

}
}}

?>
<tr><td></td></tr>

<tr><td colspan="10"><?php
$petq = $db->prepare("select pet_adv,res_adv from $schemas.case_detail where filing_no =?");
$petq->bindParam(1, $filing_no, PDO::PARAM_STR);
$petq->execute();
while ($rowpet = $petq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$pet_adv = $rowpet['pet_adv'];
$res_adv = $rowpet['res_adv'];

}
if($pet_adv > 0)
{
$petq2 = $db->prepare("select rep_name as adv_namep from e_master_advocate where id =? ");
$petq2->bindParam(1, $pet_adv, PDO::PARAM_STR);
$petq2->execute();
$rowpet2 = $petq2->fetch();
extract($rowpet2);

}


?>
Petitioner Legal Practitioner : &nbsp; <b><?php echo htmlspecialchars($adv_namep);?></b>
<?php if($fr_indp == '2')
{
?>
<a href="javascript:popsurety_pet_adv_name('<?php print htmlspecialchars($filing_no.'/1'); ?>');" >&nbsp;(Add Advocate)</a>
<?php 
}
/*if($pet_adv > 0)
{

$st= $db->prepare("select * from $schemas.fram_advocate where filing_no=? and party_type=? and 
main_adv_code=? and display=?");
$party_type='P';
$display='TRUE';
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $party_type, PDO::PARAM_STR);
$st->bindParam(3, $pet_adv, PDO::PARAM_STR);
$st->bindParam(4, $display, PDO::PARAM_STR);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$farm_adv_name=htmlspecialchars($row['farm_adv_name']);

echo "</br>";
echo htmlspecialchars($farm_adv_name);
}
}
*/
?>
</b></td></tr>
<tr><td colspan="10">
<?php
if($res_adv > 0)
{
$petq1 = $db->prepare("select rep_name as  adv_namer from e_master_advocate where id =? ");
$petq1->bindParam(1, $res_adv, PDO::PARAM_STR);
$petq1->execute();
$rowpet1 = $petq1->fetch();
extract($rowpet1);

}
?>
Respondent Legal Practitioner : &nbsp; <b><?php echo htmlspecialchars($adv_namer);?>
<?php /*if($fr_indr == '2')
{
?>
<a href="javascript:popsurety_pet_adv_name('<?php print htmlspecialchars($filing_no.'/2'); ?>');" >&nbsp;(Add Advocate)</a>
<?php 
$st= $db->prepare("select * from $schemas.fram_advocate where filing_no=? and party_type=? and 
main_adv_code=? and display=?");
$party_type='R';
$display='TRUE';
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $party_type, PDO::PARAM_STR);
$st->bindParam(3, $res_adv, PDO::PARAM_STR);
$st->bindParam(4, $display, PDO::PARAM_STR);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$farm_adv_name1=htmlspecialchars($row['farm_adv_name']);

echo "</br>";
echo htmlspecialchars($farm_adv_name1);
}

}*/
?>
</td></tr>
<?php /*if($filing_no !='')
{
?>
<tr><td align="left" colspan="13"><!--b>Subject Category:</b-->

<?php

$st = $db->prepare("select * from $schemas.case_category where filing_no=?");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$catcode =htmlspecialchars($row['subject']);
$subcat_code =htmlspecialchars($row['subj_catg']);
$subsub_cat_code =htmlspecialchars($row['subj_sub_catg']);
}
if($catcode > 0)
{
$st = $db->prepare("select sub_cat_name from $schemas.cat_master where cat_code=?");
$st->bindParam(1, $catcode, PDO::PARAM_INT);
$st->execute();
$name = $st->fetchColumn();
echo htmlspecialchars(ucwords(strtoupper($name)));
}
?>
</td>
</tr>
<?php } */?>

<?php /*if($filing_no !='')
{
?>
<tr><td align="left" colspan="13"><!--b>Amicus Curiae:</b>

<?php

$stac = $db->prepare("select * from $schemas.amicus_curiae where filing_no=? and date=? ");
$stac->bindParam(1, $filing_no, PDO::PARAM_STR);
$stac->bindParam(2, $curdate, PDO::PARAM_STR);
$stac->execute();
while ($row = $stac->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$adv_nameac =htmlspecialchars($row['adv_name']);

echo htmlspecialchars(ucwords(strtoupper($adv_nameac))).',';
}
?>
&nbsp;&nbsp;
<a href="javascript:popsurety_amicus_curiae('<?php print htmlspecialchars($filing_no); ?>');" >&nbsp;(Add Amicus Curiae)</a-->
</td>
</tr>
<?php 
}*/
//============case is runing with  objections ================->  

$st= $db->prepare("select * from $schemas.scrutiny where filing_no=? and objection_status=? and defects=?");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $objection_status, PDO::PARAM_STR);
$st->bindParam(3, $defects, PDO::PARAM_STR);
$objection_status='Y';
$defects='Y';
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$compliance_date=htmlspecialchars($row['compliance_date']);
$objection_status1=htmlspecialchars($row['objection_status']);
$defects1=htmlspecialchars($row['defects']);
$filing=htmlspecialchars($row['filing_no']);
}
?>
<tr><td colspan="16">
<?php

if($filing!='' and $defects1='Y' and $objection_status1='Y')
{
?>
<tr>
<td colspan="6" align="left" ><b>

<a href="../scrutiny/compliance.php?filing_no=<?php echo htmlspecialchars($filing_no);?>" onClick="NewWindow(this.href,'mywin','800','600','yes','center');return false" onFocus="this.blur()"><blink><font color ="blue" size="2">Case Is Running With Objection</blink></b></a>

</font></center>
</td>
</tr>

<?php
}

//==================end=====================->

//echo "select filing_no from $schemas.case_allocation where filing_no='$filing_no' ";
$st= $db->prepare("select filing_no from $schemas.case_allocation where filing_no=? ");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
//$st->bindParam(2, $curdate, PDO::PARAM_STR);
$st->execute();
$chk_list = $st->fetchColumn();

if($chk_list=='' and $case_no!='' and $filing_no!='' and $case_year !='')
{

$st= $db->prepare("select listing_date from $schemas.case_allocation where filing_no=?");
$st->bindParam(1, $chk_list, PDO::PARAM_STR);
$st->execute();
$chk_list_date = $st->fetchColumn();
if($chk_list_date !='' and ($chk_list_date !='1111-11-11' OR $chk_list_date ==''))
{
list($yy,$mm,$dd)=explode('-',$chk_list_date);
$chk_list_date_display=$dd.' / '.$mm.' / '.$yy;

$msgdisplay="CASE LISTED FOR DATE -$chk_list_date_display";

}
else
{
$msgdisplay="CASE NOT LISTED..... PLEASE LIST ABOVE CASE";
}


echo "<tr><td colspan=6><br><br><center><b><i><font size=2 color=red>".htmlspecialchars($msgdisplay)."</font></i></b></td></tr>";
$case_no='';
die();
}

?>



<?php  $pen_dis = isset($_REQUEST['pen_dis']) ? $_REQUEST['pen_dis'] :'';?>

<tr><td align="right" width="150"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">*Todays Status
</font></td>

<td align="left"><input type="radio" name="pen_dis" value="P" onChange="javascript:submitForm();"
<?php if($pen_dis=="P" or $pen_dis=='') print "checked";?>><b>Pending</b>
<input type="radio" name="pen_dis" value="D" onChange="javascript:submitForm(); "<?php if($pen_dis=="D")	print "checked";?>>
<b>Disposal</b></td>
</tr>
<?php

//===================pending==============================================================
if($pen_dis=='P' or $pen_dis=='')
{?>
<tr><td align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">* Todays Action Type</font></td>
<?php
$status='P';
$st= $db->prepare("select * from $schemas.master_action where status=?");
$st->bindParam(1, $status, PDO::PARAM_INT);
$st->execute();
?>
<td align="left" colspan="2"><select name="action_type" style="width: 250px;" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<option value="">Select</option>
<?php  if($action_type=='') $action_type=3;

while ($row2 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$action_code=$row2['action_code'];
?>
<option <?php if($action_type==$action_code){?> selected <?php } ?>value="<?php echo htmlspecialchars($action_code); ?>">
<?php echo htmlspecialchars(ucwords($row2['action_type']));?></option><?php
} ?>
</select>
</td>
</tr>

<?php
$purpose_code='';
$purpose_old='';
$st= $db->prepare("select purpose from $schemas.case_allocation where filing_no=?");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
$purpose_old =$st->fetchColumn();
?>
<?php  $purpose_old = isset($_REQUEST['purpose_old']) ? $_REQUEST['purpose_old'] :'';?>
<?php  $purpose_code = isset($_REQUEST['purpose_code']) ? $_REQUEST['purpose_code'] :'';?>
<input type="hidden" name="purpose_old" value="<?php echo htmlspecialchars($purpose_old); ?>">
<tr>
<td align="right" nowrap="nowrap"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" nowrap="nowrap">
<span class="error">*</span>Head of next hearing</font>
</td>

<td colspan="2"><select name="purpose_code" onchange="javascript:submitForm1();" style="width: 250px">
<?php if($purpose_code=='') { $purpose_code=$purpose_old; } ?>
<option value="">-select-</option>
<?php
$display='Y';
$st= $db->prepare("select * from $schemas.master_purpose where display=?  order by purpose_name asc");
$st->bindParam(1, $display, PDO::PARAM_STR);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$purposecode=$row['purpose_code'];
if($purposecode==$purpose_code)
{
?>
<option value="<?php echo htmlspecialchars($row['purpose_code']);?>" selected>
<?php echo htmlspecialchars(ucwords($row['purpose_name']));?>
</option>";
<?php
}
else
{
?>
<option value="<?php echo htmlspecialchars($row['purpose_code']);?>">
<?php echo htmlspecialchars($row['purpose_name']);?>
</option>";
<?php
}
}

?>
</select>

<?php
if($purpose_code =='7')
{
?>
<a href="javascript:popsurety_evidence_ref('<?php print htmlspecialchars($filing_no); ?>');" >&nbsp;(Add Commissioner/Other)</a>
<?php
}

?>

</td>

</tr>
<?php /*?>
<tr><td align="right" width="100">
<font face="Verdana, Arial, Helvetica, sans-serif" size="2" nowrap="nowrap"><span class="error">*</span>
Criteria (if any) for next hearing</font>
</td>
<?php  $criteria = isset($_REQUEST['criteria']) ? $_REQUEST['criteria'] :'';?>
<?php
if($criteria =='') $criteria ="FD";
$display='TRUE';
$st= $db->prepare("select * from $schemas.list_criteria where display=? order by criteria_name asc");
$st->bindParam(1, $display, PDO::PARAM_STR);
$st->execute();
?>
<td colspan="8"><select size="1" name="criteria" onChange="javascript:submitForm();">
<option value="">-select-</option>
<?php
while ($lc = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$c_code=$lc['criteria_code'];
if($criteria==$c_code)
{
?>
<option value="<?php echo htmlspecialchars($lc['criteria_code']);?>" selected>
<?php echo htmlspecialchars(ucwords($lc['criteria_name']));?>
</option>";
<?php
}
else
{
?>
<option value="<?php echo htmlspecialchars($lc['criteria_code']);?>">
<?php echo htmlspecialchars($lc['criteria_name']);?>
</option>";
<?php
}
}

?>
</select> <?php*/

if ($criteria=="FD" ||$criteria =='' )
{
?>

<tr>
<td align="left" nowrap="nowrap"><div align="right">
<span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
List it On </div></td>

<td width="50">
<!-- 
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
<?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :'';?>
<input type="text" id="next_list_date" autocomplete="off" name="next_list_date" class="datepickerGreater"
readonly="readonly" size="10" maxlength="10" value="<?php print htmlspecialchars($next_list_date); ?> data-date-format="mm/dd/yyyy""/>
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
-->
<?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :'';?>
<input type="text"  autocomplete="off" name="next_list_date" class="form-control pull-right" id="datepicker" readonly="readonly" size="10" maxlength="10" value="<?php print htmlspecialchars($next_list_date); ?>" data-date-format="dd/mm/yyyy""/>
</td>

</tr>
<?php
}


} //close pen_dis='P'

//=======================code for disposal=================================================
else
{
?>

<tr>
<td align="left" nowrap="nowrap">
<div align="right"><span class="error">*</span>Disposal Nature
</div>
</td>
<?php  $disposal_nature = isset($_REQUEST['disposal_nature']) ? $_REQUEST['disposal_nature'] :'';?>
<td colspan="2"><select size="1" style="width: 250" name="disposal_nature" id="disposal_nature" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<option value="">Select</option>
<?php
$status='D';
$st= $db->prepare("select * from $schemas.master_action where  status=?");
$st->bindParam(1, $status, PDO::PARAM_STR);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$actioncode = $row['action_code'];
if($actioncode == $disposal_nature)
{
print "<option value=".htmlspecialchars($row['action_code'])." selected>".htmlspecialchars($row['action_type'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['action_code']).">".htmlspecialchars($row['action_type'])."</option>";
}
}
?>
</select>
<?php  $disposal_date = isset($_REQUEST['disposal_date']) ? $_REQUEST['disposal_date'] :'';?>
<span class="error"></span><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> Disposal Date

<input type="text"  autocomplete="off" name="disposal_date" class="form-control pull-right" id="datepicker" readonly="readonly" size="10" maxlength="10" value="<?php print htmlspecialchars($disposal_date); ?>" data-date-format="dd/mm/yyyy""/>

</td>
</tr>


<?php
}// close of else condition

$st= $db->prepare("select remarks from $schemas.case_proceeding where filing_no=?");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
$remarks = $st->fetchColumn();

?>

<?php  $remarks = isset($_REQUEST['remarks']) ? $_REQUEST['remarks'] :'';?>
<tr>
<td width="200" align="right"><font face="Verdana" size="2">Remarks</font></td>
<td width="400" align="left">
<textarea class="formInput" placeholder="Your Message" id="remarks" name="remarks"
maxlength="1400" cols="50" rows="2" onFocus="SetBg(this)" onBlur="UnSetBg(this)"></textarea>

</td>
</tr>

<?php

//==========================for connected cases detail==================================
$status='C';
$st= $db->prepare("select count(filing_no) from $schemas.connected_cases where filing_no=? and status=?");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $status, PDO::PARAM_STR);
$st->execute();
$connected_count= $st->fetchColumn();
if($connected_count > 0)
{
?>


<tr><td colspan="14" >		
<li>CONNECTED CASE PROCEEDING</li>
<li>CLICK ON CHECKBOX FOR CONNECTED CASE PROCEEDING</li>
<li><input type="checkbox" id="selecctall1"/><font color="#B0171F" >Selecct All</font></li>
<?php
$status='C';
$stxx= $db->prepare("select * from $schemas.connected_cases where filing_no=? and status=?");
$stxx->bindParam(1, $filing_no, PDO::PARAM_STR);
$stxx->bindParam(2, $status, PDO::PARAM_STR);
$stxx->execute();
while ($con = $stxx->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$con_filing_no=$con['conn_filing_no'];
$status1='P';
$stcc= $db->prepare("select case_no, case_type, pet_name, res_name from $schemas.case_detail where filing_no=? and status=? ");
$stcc->bindParam(1, $con_filing_no, PDO::PARAM_STR);
$stcc->bindParam(2, $status1, PDO::PARAM_STR);
$stcc->execute();
while ($cd = $stcc->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$con_case_no=$cd['case_no'];
$con_case_type=$cd['case_type'];
$con_pet_name=$cd['pet_name'];
$con_res_name=$cd['res_name'];

$con_case_num = substr($con_case_no,4,7);
$case_num1=ltrim($con_case_num,0);
$con_case_year = substr($con_case_no,11,4);
$stvv= $db->prepare("select short_name, case_type_name from $schemas.master_case_type where case_type_code= ?");
$stvv->bindParam(1, $con_case_type, PDO::PARAM_INT);
$stvv->execute();
while ($rw15 = $stvv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$con_c_name=$rw15['short_name'];
$con_case_type_name=$rw15['case_type_name'];
}

$con_c_case_no="$con_c_name/$case_num1/$con_case_year";
$con_party_name="$con_pet_name <b>Vs. </b> $con_res_name";


?>
<li><input class="checkbox11" type="checkbox" name="checkbox[]" value="<?php echo htmlspecialchars($con_filing_no); ?>"> 
<?php echo htmlspecialchars($con_c_case_no); ?></li>
<?php }} ?>
</ul>
</td></tr>


<?php

}
//==================close connected cases detail===============================
?>

<?php
//==========================for reference cases detail==================================

if($filing_no !='' and $case_no !='')
{
$cno_no='';
$st= $db->prepare("select count(filing_no) from $schemas.case_detail where oa_ref_no=? and case_no !=?");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $cno_no, PDO::PARAM_STR);
$st->execute();
$reference_count= $st->fetchColumn();
if($reference_count>0)
{
?>
<script type="text/javascript">
$(document).ready(function() {
$('#selecctall').click(function(event) {  //on click
if(this.checked) { // check select status
$('.checkbox1').each(function() { //loop through each checkbox
this.checked = true;  //select all checkboxes with class "checkbox1"
});
}else{
$('.checkbox1').each(function() { //loop through each checkbox
this.checked = false; //deselect all checkboxes with class "checkbox1"
});
}
});

});
</script>
<tr><td colspan="14">
<ul class="chk-container">
<li>MAIN CASE REFERENCE CASE PROCEEDING</li>
<li>CLICK ON CHECKBOX FOR REFERENCE CASE PROCEEDING</li>
<li><input type="checkbox" id="selecctall"/> <font color="#B0171F">Selecct All</font></li>
<?php
$cno_no='';
$st= $db->prepare("select filing_no from $schemas.case_detail where oa_ref_no=? and
case_no !=?");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $cno_no, PDO::PARAM_STR);
$st->execute();
while ($ref = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ref_filing_no=$ref['filing_no'];

$sql_cd11=$st= $db->prepare("select case_no, case_type, pet_name, res_name from $schemas.case_detail where
filing_no=? ");
$sql_cd11->bindParam(1, $ref_filing_no, PDO::PARAM_STR);
$sql_cd11->execute();
while ($cd1 = $sql_cd11->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ref_case_no=$cd1['case_no'];
$ref_case_type=$cd1['case_type'];
$ref_pet_name=$cd1['pet_name'];
$ref_res_name=$cd1['res_name'];

$ref_case_num = substr($ref_case_no,4,7);
$case_num11=ltrim($ref_case_num,0);
$ref_case_year = substr($ref_case_no,11,4);

$sq16=$db->prepare("select short_name,case_type_name from $schemas.master_case_type
where case_type_code= ? ");
$sq16->bindParam(1, $ref_case_type, PDO::PARAM_STR);
$sq16->execute();
while ($rw16 = $sq16->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ref_c_name=$rw16['short_name'];
$ref_case_type_name=$rw16['case_type_name'];
}

$ref_c_case_no="$ref_c_name/$case_num11/$ref_case_year";
$ref_party_name="$ref_pet_name .'<b>Vs.</b>'. $ref_res_name";
}

?>

<li><input class="checkbox1" type="checkbox" name="ref_filing[]" value="<?php echo htmlspecialchars($ref_filing_no); ?>">
<?php echo htmlspecialchars($ref_c_case_no); ?></li>

</ul></td></tr>







<?php
}
?>
<?php
}
} // close if($filing_no !='' and $case_no !='')
//==================close reference cases detail===============================

?>
<?php  $transfercase = isset($_REQUEST['transfercase']) ? $_REQUEST['transfercase'] :''; ?>
<!--tr><td colspan="13" align="left" valign="top">
Transfer Case:&nbsp;<select name="transfercase" onchange="javascript: run();">
<option value="1"<?php if($transfercase ==1) { print " selected"; }?>>NO</option>
<option value="2"<?php if($transfercase ==2) { print " selected"; }?>>YES</option>
</select>
<?php if($transfercase =='2')
{

?>

<?php  $bn = isset($_REQUEST['bn']) ? $_REQUEST['bn'] :''; ?>
Bench Nature:&nbsp;<select name="bn" onchange="javascript: run();">
<option value="">Select</option>
<option value="99"<?php if($bn == 99) { print " selected"; }?>> Meditation Court Room</option>
<?php
$display='TRUE';
$st= $db->prepare("select * from $schemas.bench_nature  where  display=? ");
$st->bindParam(1, $display, PDO::PARAM_STR);

$st->execute();

while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))


{
$btype = htmlspecialchars($row['bench_code']);
if($bn == $btype)
{
print "<option value=".htmlspecialchars($row['bench_code'])." selected>".htmlspecialchars($row['bench_name'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['bench_code']).">".htmlspecialchars($row['bench_name'])."</option>";
}
}
?>
</select>
<?php
}
if($bn !='4' and $bn !='99' and $bn !='')
{
?>
<?php  $courtnotf = isset($_REQUEST['courtnotf']) ? $_REQUEST['courtnotf'] :''; ?>
<font color="red">*</font>Court No.:
<select  size="1" name="courtnotf">
<option value="1" <?php if($courtnotf == 1) { print " selected"; }?>>1</option>
<option value="2" <?php if($courtnotf == 2) { print " selected"; }?>>2</option>
<option value="3" <?php if($courtnotf == 3) { print " selected"; }?>>3</option>

</select>
<?php
} //

?>
</td></tr-->




<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<tr>
<td colspan="8" align="left" valign="top"><div align="center">
<input type="submit" name="submit1" value="Submit" class="button btn-primary" onClick="return validate();">
</div></td>
</tr>





</td>
</tr>

<?php }?>

 </td></tr> </table>
			
                             
      </form> 
  </body></div></div></div>  
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



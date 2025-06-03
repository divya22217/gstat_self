<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
//session_start();
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//$next_list_date =$_REQUEST['next_list_date'];

$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$schemas="Delhi";
$location="10";
$userid=$_SESSION['id'];

global $next_list_date;
/*
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
*/
//setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
/*
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}
*/


/*
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
*/


	// This code not use next time .......	Schema session create Hear....


	$sessionUserType=htmlspecialchars($_SESSION['id']);


	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";


	$link_scrutiny_idaccess='1';

?>
<div>

</div>
<script language="javascript">
function submitForm3()
{
 	with(document.frm)
	{		
	 action = "causelist.php";
	 submit();
	}
}
function goFinal1()
{
 	with(document.frm)
	{


 		/*if(listflag.options[listflag.selectedIndex].value == "")
 		{
 			alert("Please Select Type  ");
 			listflag.focus();
 			return false;
 		}
 		if(list_before.options[list_before.selectedIndex].value == "")
 		{
 			alert("Please Select Bench Nature  ");
 			list_before.focus();
 			return false;
 		}*/
		/*
 		if(courtno.options[courtno.selectedIndex].value == "")
 		{
 			alert("Please Select Court No.  ");
 			courtno.focus();
 			return false;
 		}
		*/
		
 		action="generate_cause_list.php";
 		submit();
 	    	document.frm.submit11.disabled = true;  
 	     	document.frm.submit11.value = 'Please Wait...';  
 	     	return true;


 		}
 	}
</script>
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
</head>
 
<body>








  <table cellspacing="0" align="center" cellpadding="2" border="1" width="50%"> 
<tr><td colspan="15"><a href="../index.php">
<font face="Verdana" color="red" size="3"><center><b>BACK</b></center></font></a>
</br>
<hr />
</td></tr>
<tr><td colspan="15">
<font face="Verdana" color="red" size="3"><center><b>Cause List Report</b></center></font>
</br>
<hr />
</td></tr>
<?php 
//hasing Tag .....
?>





<form name="frm" method="post" action="generate_cause_list.php">
<?php

    /*$sql="select min(from_list_date) as from_list_date  from $schemas.bench where from_list_date > '$cur_date'";
	foreach($db->query($sql) as $row)
	{	
		 $next_list_date =$row['from_list_date'];
	}
if($next_list_date!='')
	{
 list($year,$month,$day)=explode('-',$next_list_date);
$next_list_date1=$day.'/'.$month.'/'.$year;
	}
 */

$next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; 
?>

<tr><td colspan="2"><font color="red">*</font>Date:(DD/MM/YYYY)

<input type="text" id="next_list_date" name="next_list_date"  class="datepicker"
 size="10" value="<?php print htmlspecialchars($next_list_date); ?>"/>


<script src="../src/calendar.js"></script>


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
            trigger: '#next_list_date',
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



      
        
     </td>
	 </tr>
	 <tr>
      <td colspan="3">


&nbsp;<font color="red">*</font>Bench Nature:

<select id="list_before" size="1" name="list_before" onchange="javascript:submitForm3();">
             <option value="">Select</option>
<?php

  $list_before = isset($_REQUEST['list_before']) ? $_REQUEST['list_before'] :''; 

//$st= $db->prepare("select * from $schemas.bench  where  from_list_date=? ");
$st= $db->prepare("select * from $schemas.bench  ");
//$st->bindParam(1, $list_cdate, PDO::PARAM_STR);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$bench_nature = htmlspecialchars($row['bench_nature']);
}
$display='TRUE';
$st= $db->prepare("select * from $schemas.bench_nature  where  display=? ");
$st->bindParam(1, $display, PDO::PARAM_STR);

$st->execute();

while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))

    
	  {
	$btype = htmlspecialchars($row['bench_code']);
	   if($list_before == $btype)
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
</td>
<!--
 <td colspan="3">     

<?php  $listflag = isset($_REQUEST['listflag']) ? $_REQUEST['listflag'] :''; ?>
&nbsp;<font color="red">*</font>Type:
<select id="in_c_case" size="1" name="listflag" onchange="javascript:submitForm3();">
             <option value="">Select</option>
             <option value="1"<?php if($listflag ==1) { print " selected"; }?>>Main Cause List</option>
             <option value="2"<?php if($listflag ==2) { print " selected"; }?>>Supplementry Cause List</option>
<option value="3"<?php if($listflag ==3) { print " selected"; }?>>Vacation Cause List</option>
             
</select>             
</td>   
-->
</tr>    

<?php

if($next_list_date !='')
{
list($d,$m,$Y) =explode('/',$next_list_date);
$listing_date =$Y.'-'.$m.'-'.$d;


$sql="select distinct(court_no) as court_no from $schemas.bench where from_list_date='$listing_date' and bench_nature='$list_before' order by court_no ASC";

	
?>
<tr>
      <td  align="left"><font face="Verdana" size="2" ><font color="red">*</font> Court</font></td>
  <td align="left">
<select name="court_no" id="test"  style='width:150px;'>
<option value="">Select Court</option>
<?php
$sqlm=$db->prepare($sql);

            //$sqlm->bindParam(1, $listing_date, PDO::PARAM_STR);
           //$sqlm->bindParam(2, $list_before, PDO::PARAM_STR);
            $sqlm->execute();
		while ($row = $sqlm->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{

		
$btype = htmlspecialchars($row['court_no']);
	   if($court_no == $btype)
                {
				print "<option value=".htmlspecialchars($row['court_no'])." selected>".htmlspecialchars($row['court_no'])."</option>";
                }
        else
                {
                print "<option value=".htmlspecialchars($row['court_no']).">".htmlspecialchars($row['court_no'])."</option>";
	        }

}
?>
</select>
</td>



</tr>

     
</div>

<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />

<td colspan="3"> <font color="red">Search</font></label>
<input type="button" id="submit11" type="button"  name="submit11" value="Submit" onClick="return goFinal1();" />
</td></tr>
<?php }?>
</table></form>

</body>
</html>
  

<?php
/*}*/
?>

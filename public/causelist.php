<?php 


header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
//session_start();
global $next_list_date;
global $datformat1;

//$next_list_date =$_REQUEST['next_list_date'];

$next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] : '';

$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
//$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$schemas='delhi';

//setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not




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

 		if(court_no.options[court_no.selectedIndex].value == "")
 		{
 			alert("Please Select Court No.");
 			court_no.focus();
 			return false;
 		}
        
		 if ($('input[name=ctype]:checked').length <= 0) {
			alert("Please Select type of Causelist");
			ctype.focus();
 			return false;
        }
		
 		action="../mis/generate_causelist_html.php";
 		submit();
 	    	document.frm.submit11.disabled = true;  
 	     	document.frm.submit11.value = 'Please Wait...';  
 	     	return true;


 		}
 	}
</script>
<style>
div.ui-datepicker{
 font-size:20px;
}
</style>
<link href="../bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet"/>

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
</head>
 
<body>

<div>



  <table cellspacing="0" align="center" cellpadding="2" border="1" width="50%"> 
  <?php 
if(isset($_SESSION['schema_name'])){
	?>
<tr><td colspan="15"><a href="../index.php">
<font face="Verdana" color="red" size="3"><center><b>BACK</b></center></font></a>
<?php
}
?>
</br>
</br>
<hr />
</td></tr>
<tr><td colspan="15">
<font face="Verdana" color="red" size="4"><center><b><u>Cause List Report</u></b></center></font>
<hr />
</td></tr>
<?php 
//hasing Tag .....
?>





<form name="frm" method="post" action="../mis/generate_causelist_html.php">
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


?>
<tr><td><font color="red" size="5">*</font> Listing Date:(DD/MM/YYYY)</td><td>

<input type="text" id="next_list_date" name="next_list_date"  class="datepicker"
 size="20" value="<?php print htmlspecialchars($next_list_date); ?>" onchange="javascript:submitForm3();"/>


<script src="../src/calendar.js"></script>

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
<?php

if($next_list_date !='')
{
list($d,$m,$Y) =explode('/',$next_list_date);
$listing_date =$Y.'-'.$m.'-'.$d;

$sql="select distinct(court_no) as court_no from $schemas.bench where from_list_date='$listing_date' order by court_no ASC";	
?>
<tr>
<td><font color="red" size="5">*</font>Court</td>
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

<tr><td colspan="2" align="center"> <font color="red" size="5">*</font>
<input type="radio" name="ctype" class="ctype" value="draft">Draft Causelist&emsp;&emsp;
<input type="radio" name="ctype" class="ctype" value="final">Final Causelist
</tr>

<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<tr>
<td colspan="2" align="center">
<input type="button" id="submit11" class="btn btn-primary" name="submit11" value="Search" onClick="return goFinal1();" />
</td></tr>
<?php }?>
</table></form>
</div>
</body>
</html>
  


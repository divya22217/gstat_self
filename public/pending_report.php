<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");//database connection
session_start();

?>



<!DOCTYPE html>
<!-- saved from url=(0019)http://nclt.gov.in/ -->
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

 
<link id="Link1" rel="shortcut icon" href="http://nclt.gov.in/image/favicon.ico">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>NCLT</title>
<!-- Bootstrap -->
<link href="../APTEL_files/bootstrap.min.css" rel="stylesheet">
<!-- Important Owl stylesheet -->
<link rel="stylesheet" href="../APTEL_files/owl.carousel.css">	 
<!-- Default Theme -->
<link rel="stylesheet" href="../APTEL_files/owl.theme.css">
<link href="../APTEL_files/style3.css" rel="stylesheet">
<link href="../APTEL_files/ticker.css" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="../plugins/jQueryUI/jquery-ui.css">
<link href="../APTEL_files/font-awesome.min.css" rel="stylesheet">
<script type="text/javascript" src="../APTEL_files/html5.js.download"></script>
<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script> 	
<script src="../plugins/jQueryUI/jquery-ui.js"></script> 	
<script src="../plugins/jQueryUI/date.js"></script> 
<script language="javascript">
function change(id, newClass)
{
identity=document.getElementById(id);
identity.className=newClass;
}
function printPage()
{
change("testdiv","hidden");
window.print();
}

function submitForm()
{
	
with(document.frm)
{
if(schemaname.value == "")
{
alert("Choose Office Location....");
schemaname.focus();
return false;
}
if(next_list_date.value == "")
{
alert("SELECT START DATE....");
next_list_date.value='';
next_list_date.focus();
return false;
}

if(next_list_date1.value == "")
{
alert("SELECT END DATE....");
next_list_date1.value='';
next_list_date1.focus();
return false;
}

action="<?php echo $_SERVER[SCRIPT_NAME];?>";
submit();
document.frm.submit1.disabled = true;
document.frm.submit1.value = 'Please Wait...';
return true;
}
}

function change(){
with(document.frm){
action="<?php echo $_SERVER[SCRIPT_NAME];?>";
submit();

}
}

</script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Calendar demo</title>
    <link rel="stylesheet" href="../src/calendar.css">
    <style type="text/css">
        html {
            font: 500 14px "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #333;
            height: 100%;
        }

        body {
            height: 100%;
            margin: 0;
        }

        a {
            text-decoration: none;
        }

        ul,
        ol,
        li {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #demo {
            width: 400px;
            margin: 30px auto;
        }

        p {
            margin: 0;
        }

        input {
            margin: 10px 0;
            height: 28px;
            width: 200px;
            padding: 0 6px;
            border: 1px solid #ccc;
            outline: none;
        }
		#three,#two{
		top:72px !important;
		left :10px !important;
		}
    </style>
</head>
<body>

<?php include("./header.php");?> 	



<!--================= slider and Chairman message section ===================== -->
<div class="container margin-top-30">	
<form  method="post"  name="frm">

<div class="row">
<h4 style="text-align:center"><u>PENDING REPORT</u></h4>
</div>


<div class="row">
<div class="col-sm-4">
	<div class="form-group">

	<label for="schemaname">SELECT BENCH :</label>
	<select name = "schemaname" class="form-control"  id="schemaname"  onchange="change();">
	<?php
	$schemas = isset($_REQUEST['schemaname']) ? $_REQUEST['schemaname'] :''; 
	$sql="select * from mater_location_city order by city_name ASC";
	$schemaName = $db-> prepare($sql);												
	$schemaName -> execute();				
	?>	
	<option> Select </option>
	<?php
	while ($row =$schemaName->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
	{
	$ctc=$row['schema_name'];
	if($schemas == $ctc)
	{
	print "<option value=".htmlspecialchars($row['schema_name'])." selected>".htmlspecialchars($row['city_name'])."</option>";
	}
	else
	{
	print "<option value=".htmlspecialchars($row['schema_name']).">".htmlspecialchars($row['city_name'])."</option>";
	}
	}
	?>	
	</select>
	</div>
</div>

<div class="col-sm-4">
		<div id="" class="form-group">
	<label for="email">FROM DATE:</label>
	<?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>
	<!--  <input type="text" class="form-control" name="next_list_date" id="from" readonly="readonly"
	autocomplete="off" maxlength="10" value="<?php print htmlspecialchars(htmlentities($next_list_date)); ?>" />
	-->
		
			       
			       <!--  <div id="one"></div> -->
			
			        
			        <input type="text" id="from" placeholder="From Date" class="form-control" name="next_list_date" value="<?php print htmlspecialchars(htmlentities($next_list_date)); ?>">
			        <div id="three"></div>
			        
			        
			        <!-- <p><button type="button" id="add">Add input</button></p> -->
			   
			    <!--script src="https://cdn.bootcss.com/jquery/1.9.0/jquery.min.js"></script>
			    <script src="../src/calendar.js"></script>
			    
			    <script>
			    var now = new Date();
			    var year = now.getFullYear();
			    var month = now.getMonth() + 1;
			    var date = now.getDate();
			
			
			    var data = [{
			        date: year + '-' + month + '-' + (date - 1),
			        value: 'Holiday'
			    }, {
			        date: year + '-' + month + '-' + date,
			        value: 'Current Date'
			    }, {
			        date: new Date(year, month - 1, date + 1),
			        value: 'holiday'
			    }];
			
			    
			
			    
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
			    $('#three').calendar({
			        trigger: '#from',
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
			    var $demo = $('#demo1');
			    var UID = 1;
			    $('#add').click(function () {
			        $demo.append('<input id="input-' + UID + '"><div id="ca-' + UID + '"></div>');
			        $('#ca-' + UID).calendar({
			            trigger: '#input-' + UID++
			        })
			    })
			</script-->
			
		</div>
</div>

<div class="col-sm-4">
<div class="form-group">

<!--<?php  $next_list_date1 = isset($_REQUEST['next_list_date1']) ? $_REQUEST['next_list_date1'] :''; ?>
<input type="text"  class="form-control" class="form-control"  name="next_list_date1" id="to" readonly="readonly"
autocomplete="off" maxlength="10" value="<?php print htmlspecialchars(htmlentities($next_list_date1)); ?>" />
-->


       
       <!--  <div id="one"></div> -->
<label for="email">TO DATE:</label>
<input type="text" id="to" name="next_list_date1" class="form-control" placeholder="To Date" value="<?php print htmlspecialchars(htmlentities($next_list_date1)); ?>" >
<div id="two"></div>
        
        
        <!-- <p><button type="button" id="add">Add input</button></p> -->

    <!--script src="https://cdn.bootcss.com/jquery/1.9.0/jquery.min.js"></script>
    <script src="../src/calendar.js"></script>
<?php 

/*$sth = $db->prepare("select title,startdate from calendar");
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
 */
?>
    
        
    <script>
        var now = new Date();
        var year = now.getFullYear();
        var month = now.getMonth() + 1;
        var date = now.getDate();

       
        var data =<?php //echo $newdata; ?>;
 		

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
            trigger: '#to',
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
    </script-->
</div>
</div>

</div>
<div class="row">
<div class="col-sm-4">
<div class="form-group">
<label for="email">&nbsp;</label>
<input id="submit1" type="button" class="form-control btn btn-primary" id="cryptstr"  name="submit1" value="SEARCH" onClick="return submitForm();" />
</div>
</div>
</div>


<?php
if($next_list_date!=''){
list($day,$month,$year)=explode('/',$next_list_date);
$list_cdate=$year.'-'.$month.'-'.$day;
list($day,$month,$year)=explode('/',$next_list_date1);
$list_cdate1=$year.'-'.$month.'-'.$day;
$stnq = $db->prepare("select * from $schemas.case_detail where status ='P' and dt_of_filing  between ? and ? ");
$stnq->bindParam(1, $list_cdate, PDO::PARAM_STR);
$stnq->bindParam(2, $list_cdate1, PDO::PARAM_STR);
$stnq->execute();
?>

<table class="table">
<?php if($stnq->rowCount() =='0' && $next_list_date!='')
{?>
<tr>
<td colspan="5"> 
<div class="alert alert-danger">
<strong>!!!</strong> No Record Found.
</div>
</td></tr>	
<?php } ?>	
<?php
if($stnq->rowCount() > 0)
{

?>




<tr>
<th>SR. No</th>
<th>Case No</th>
<th>Diary No</th>
<th>Title of Case</th>
<th>Date of Presentation</th>

</tr>
<tbody>
<?php $iii=1;
while ($rw2 = $stnq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
extract($rw2);

if($location_code){
$st1 = $db->prepare("select * from $schemas.bench_location where bench_location_code=?");
$st1->execute(array($location_code));
$bech_data= $st1->fetch();
$bech_code = $bech_data[short_name];
}

if($case_type > 0)
{
$stQ = $db->prepare("select * from case_type where id = ?");
$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
$stQ->execute();
$case_type_data=$stQ->fetch();
$case_type_short_name =$case_type_data['case_type_desc'];

}







list($yy,$mm,$dd) = explode("-",$dt_of_filing);
$dt_of_filing = $dd.'/'.$mm.'/'.$yy;
$hash=base64_encode($filing_no.'/'.$schemas);

?>

<tr>
<td><?php echo $iii;?></td>
<td><a href="javascript:popsurety_pet_adv_name('<?php print htmlspecialchars($hash); ?>');">
<?php 
if($case_no !='')
{
echo $bech_code.'/'.htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_no.'/'.$case_year);
}

?>
</a>
</td>

<td>
<a href="javascript:popsurety_pet_adv_name('<?php print htmlspecialchars($hash); ?>');">
<?php
echo $filing_no;
?>
</td>

<td>
<?php 
echo "<h7><font color='red'>";
echo strtoupper(html_entity_decode($pet_name));
echo "</font><br><font color='blue'>VS</font><br><font color='red'>";
echo strtoupper(html_entity_decode($res_name));
echo "</font></h7>";
?>
</td>

<td><?php echo htmlspecialchars($dt_of_filing);?></td>


</tr>

<?php 

$iii++;
}// case_detail loop 
//while loop end all query....

?>


</tbody>

<?php }}?>	
</table>		

</form>
	  

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="../APTEL_files/owl.carousel.js.download"></script>
<script src="../APTEL_files/main.js.download"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../APTEL_files/bootstrap.min.js.download"></script>
<script src="../APTEL_files/ticker.js.download"></script>
<script type="text/javascript" src="../APTEL_files/font-modify.js.download"></script>
<script type="text/javascript" src="../APTEL_files/jquery.faded.js.download"></script> 
<script type="text/javascript" src="../APTEL_files/jquery.faded-options.js.download"></script>    
<script type="text/javascript" src="../APTEL_files/imagepreloader.js.download"></script>
<script type="text/javascript" src="../APTEL_files/load-window.js.download"></script>


</div>

</body></html>

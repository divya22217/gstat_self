<?php

include_once("../db_inc1.php");
date_default_timezone_set("Asia/Kolkata");
$server_date= date('d/m/Y'); //Returns IST 
 $schemas=htmlspecialchars($_SESSION['schema_name']);
include_once("../inheader.php");
include_once ("../insidebar.php");
extract($_REQUEST);

?>
<div class="content-wrapper">

<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
<!-- jvectormap -->
<link rel="stylesheet" href="../bower_components/jvectormap/jquery-jvectormap.css">
<!-- Theme style -->
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">



<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="../plugins/iCheck/all.css">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">



<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<!-- <script src="../bower_components/Chart.js/Chart.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>


<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->

<!-- AdminLTE App -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>



<!--[endif]-->
<style>
    input[type=text] {
        padding: 1px;
        margin: 8px 0;
        box-sizing: border-box;
    }
    select{padding: 1px;
    margin: 8px 0;
    box-sizing: border-box; }
    /*[type=text]{padding: 5px;        margin-bottom: 5px;}*/
</style>

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
</head>
<style>
table td{font-size: 10pt;}
table th{font-size: 10pt;}
</style>
<script language="javascript">
function un_check()
{
	for (var i = 0; i < document.frm.elements.length; i++)
	{
		var e = document.frm.elements[i];
		if ((e.name != 'allbox') && (e.type == 'checkbox'))
		{
			e.checked = document.frm.allbox.checked;
		}
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
function submitForm()
{
 	with(document.frm)
	{ 
		action = "transfer_bench.php";
		submit();
	}
}
function validate()
{
	with(document.frm)
	{
		
		if(next_list_date.value =="")
		{                     
			alert("Please Enter Valid Date of Listing");
			next_list_date.select();
			return false;
		}	
		
		
		var rgx = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;

		if(next_list_date.value !="")
		{        
		      if(!next_list_date.value.match(rgx))
			{
			       alert("Please enter valid Hearing Date ");
			       next_list_date.focus();
			       return false;
			}
		}
		
		var checkboxes = document.getElementsByName('bench_no');

		var selected = [];
		for (var i=0; i<checkboxes.length; i++) {
		if (checkboxes[i].checked) {selected.push(checkboxes[i].value);}
		}
		if(selected=="")
		{
		alert("Please choose From bench ");
		return false;
		}
		
		
		
		
		
		
		/*
		var str1 = next_list_date.value;
		var str2 = server_date.value;

		var dt1   = parseInt(str1.substring(0,2),10); 
		var mon1  = parseInt(str1.substring(3,5),10);
		var yr1   = parseInt(str1.substring(6,10),10); 

		var dt2   = parseInt(str2.substring(0,2),10); 
		var mon2  = parseInt(str2.substring(3,5),10); 
		var yr2   = parseInt(str2.substring(6,10),10);

		mon1 = mon1 -1 ;
		mon2 = mon2 -1 ;
		var nextlistdate = new Date(yr1, mon1, dt1); 
		var curdate = new Date(yr2, mon2, dt2); 

		if(nextlistdate<curdate)
		{
			alert("listing  date should be greater than current date ");
			next_list_date.focus();
			return false;
		}
		if(court_no_new.options[court_no_new.selectedIndex].value == "")
		{
			alert("Please Select court number !!!");
			court_no_new.focus();
			return false;
		}
		if(bench_no_new.options[bench_no_new.selectedIndex].value == "")
		{
			alert("Please Select Bench Number");
			bench_no_new.focus();
			return false;
		}
		if(court_no_transfer.options[court_no_transfer.selectedIndex].value == "")
		{
			alert("Please Select Court Number For Transfer");
			court_no_transfer.focus();
			return false;
		}
		if(bench_no_transfer.options[bench_no_transfer.selectedIndex].value == "")
		{
			alert("Please Select Bench Number For Transfer");
			bench_no_transfer.focus();
			return false;
		}*/


	
		
		/*var chks = document.getElementsByName('checkbox[]');
		 
		var hasChecked = false;
		for (var i = 0; i < chks.length; i++)
		{
		        if (chks[i].checked)
		        {
		                hasChecked = true;
		                break;
		        }
		}
		
		
		if (hasChecked == false)
		{
		        alert("Please select at least one case.");
		        return false;
		}
		*/
		
		
		var filing_case = $(".checkbox").is(":checked");
		if (filing_case == false)
		{
		        alert("Please select at least one case.");
		        return false;
		}
	  
		var chks1 = document.getElementsByName('bench_no_new');
		var hasChecked1 = false;
		for (var i = 0; i < chks1.length; i++)
		{
		        if (chks1[i].checked)
		        {
		                hasChecked1 = true;
		                break;
		        }
		}
		if (hasChecked1 == false)
		{
		        alert("Please select at least one bench to transfer.");
		        return false;
		}
		
		return confirm('Are you sure to Final Todays Cases?');
	}
}
function SetBg(txt)
{
      txt.style.backgroundColor='#ffff99';
}
function UnSetBg(txt)
{
       txt.style.backgroundColor='white';
}

</script>
<body>
<form name="frm" method="post" action="transfer_bench_action.php" onSubmit="return validate();">

<input type="hidden" name="server_date" value="<?php echo $server_date;?>">
<table cellspacing="2" cellpadding="2" border="1" width="95%" class="std" align="center"> 

<tr>
<td valign="top" align="right" colspan="7">
<p align="center"><b><font face="Verdana" size="3"><U>EDIT CAUSELIST MODULE ( MAIN CAUSE LIST)</U></font></b></td>
</tr>
<tr>
	<td valign="top" align="right" colspan="7">
	<p align="center"><font face="Verdana" size="2">Fields marked with a <span class="error">*</span> are compulsory.</font>
	</td>
</tr>


<?php  $msg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] :''; ?>
<?php 
if($msg != "")
{
	?>
	<tr>
	<td height="30" align="center" cellpadding="0" colspan="6" > <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red"> 		<span class="error"> 
	<b><?php echo "$msg";?>	</span></font>	</td>    
	</tr>
	<?php
}


$sql_prps = "select * from $schemas.master_purpose";
$sth5 = $dbh->prepare($sql_prps);
$sth5->execute();
$purposeAll = $sth5->fetchAll();
?>
<tr>
	<!--td  align="right" > </td-->
	<td align="left" colspan="7" ><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	<?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>
	<span class="error">*</span>Date of Listing</font>
		<input type="text"  name="next_list_date" id="next_list_date"  maxlength="10" size="10" value="<?php echo  $next_list_date; ?>" onKeyup="javascript:addNumbers(this.value)" class="datepicker" onFocus="SetBg(this)" onchange="submitForm();UnSetBg(this);">
		<b>(TRANSFER FROM BENCH)</b>
		</td>
</tr>
<tr>
	<!--td  align="right" > </td-->
	<td align="left" colspan="7" ><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	 <?php  $next_list_date1 = isset($_REQUEST['next_list_date1']) ? $_REQUEST['next_list_date1'] :''; ?>
	<span class="error">*</span>Next Listing Date</font>
		<input type="text"  name="next_list_date1" id="next_list_date1"  maxlength="10" size="10" value="<?php echo  $next_list_date1; ?>" onKeyup="javascript:addNumbers(this.value)" class="datepicker" onFocus="SetBg(this)" onchange="submitForm();UnSetBg(this);">
		<b>(TRANSFER TO BENCH)</b>
		
		</td>
		</tr>
			<?php
		if($next_list_date1!='')
		{
		list($dayn,$monthn,$yearn)=explode('/',$next_list_date1);
	$court_date_newn=$yearn.'-'.$monthn.'-'.$dayn;
		
	$flag=0;
	
	  $sql2=" select * from $schemas.bench where  from_list_date ='$court_date_newn' order by court_no asc";
	$sss=$db->prepare($sql2);
	$sss->execute();
		
	if($sss->rowCount()>0){
	foreach($dbh->query($sql2) as $row21)
	{
		$flag=1;
		$court_no1 = $row21['court_no'];
		?>
		<tr>
		<td valign="top"  align="center">
		<input type="radio"  name="bench_no_new" value="<?php echo $bench_code_n = $row21['bench_no']; ?>"></td>
		<td  valign="top" align="center"><?php 
		$sql2q=" select bench_name from $schemas.bench_nature where  bench_code ='$row21[bench_nature]'";
		$sth11= $dbh->prepare($sql2q);
		$sth11->execute();
		echo $sth11->fetchColumn();
		?><?php if($court_no1!=3)echo '<br>Court No : '.$court_no1;?></td>

		<td colspan="6" align="left">
		<?php
		
if($court_date_newn!='' and $bench_code_n!='')
		
		{
		$sql="select bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm where bj.from_list_date ='$court_date_newn' and bj.bench_no='$bench_code_n' and jm.judge_code=bj.judge_code ";
		$sth = $dbh->prepare($sql);
		$m=0;
		foreach($dbh->query($sql) as $row)
		{
			$arrn[$m]=$row['judge_code'];
			$m++;
		}
		$sql="select presiding from $schemas.bench where from_list_date ='$court_date_newn'  and bench_no='$bench_code_n'";
		$sth = $dbh->prepare($sql);
		$sth->execute();
		$presiding1='';
		$presiding1 = $sth->fetchColumn();
		$arr1 = sizeof($arrn);
		for($i=0;$i<$arr1;$i++)
		{
			$jcode1 =$arrn[$i];
			$sql = "select judge_name from $schemas.master_judge where judge_code =$jcode1";
			$sth = $dbh->prepare($sql);
			$sth->execute();
			$judge1 = $sth->fetchColumn();

			print "<font size='2' ><b>".strtoupper($judge1);
			if($jcode1 == $presiding1) print "<font color='red'><b> (PRESIDING JUDGE )</b></font>";
			print"<br>";
		}
		echo'</td>';
	}
	echo'</tr>';
	?>
	
	


	<?php }} }	
		
$court_date_new = isset( $_REQUEST['court_date_new'] )? $_REQUEST['court_date_new']: false;


if($next_list_date!='')
{
list($day,$month,$year)=explode('/',$next_list_date);
$court_date_new=$year.'-'.$month.'-'.$day;

$sql2=" select * from $schemas.bench where  from_list_date ='$court_date_new' order by court_no asc";
$bench_d=$dbh->prepare($sql2);
$bench_d->execute();

 
if($bench_d->rowCount()>0)
{
	$bench_data=$bench_d->fetchAll();
 ?>


<tr>
<th ><font face="Verdana, Arial, Helvetica, sans-serif" >&nbsp;</font></th>
<th   valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" >Court No.</font></th>

<th colspan="6" align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Hon'ble Justice</font></th>
</tr>
<?php

	$flag=0;
	
	foreach($bench_data as $row2)
	{
		$flag=1;
		
		$court_no =$row2['court_no']; 
		$bench_code1 = $row2['bench_no'];
		?>
		<?php  $bench_no = isset($_REQUEST['bench_no']) ? $_REQUEST['bench_no'] :''; ?>
		<tr >
		<td valign="top"  align="center">
		<input type="radio"  name="bench_no"  class="bench_no"onchange="submitForm();" value="<?php echo $bench_code1;?>" <?php if($bench_no ==$bench_code1)echo 'checked';?>>
		</td>
		<td  valign="top" align="center"><?php  
		$sql2q=" select bench_name from $schemas.bench_nature where  bench_code ='$row2[bench_nature]'";
		$sth11= $dbh->prepare($sql2q);
		$sth11->execute();
		echo $sth11->fetchColumn(); ?>
		<?php if($court_no!=3)echo '<br>Court No : '.$court_no;?>
		</td>

		<td colspan="6" align="left">
		<?php
		/*$sql6=" select court_no from $schemas.bench where from_list_date='$court_date_new' and bench_no='$bench_code1' ";
		$sth = $dbh->prepare($sql6);
		$sth->execute();
		$court_no = $sth->fetchColumn();

		$sql5=" select from_time,to_time from $schemas.bench where  from_list_date <='$court_date_new' and to_list_date >= '$court_date_new' and bench_no='$bench_code1' and court_no='$court_no'";
		foreach($dbh->query($sql5) as $row5)
		{
			$court_stime=$row5['from_time'];
			$court_etime=$row5['to_time'];
		}*/
		if($court_date_new!='')
			{
		$sql="select bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm where bj.from_list_date ='$court_date_new' and bj.bench_no='$bench_code1' and jm.judge_code=bj.judge_code ";
		$sth = $dbh->prepare($sql);
		$m=0;
		foreach($dbh->query($sql) as $row)
		{
			$arr[$m]=$row['judge_code'];
			$m++;
		}
		$sql="select presiding from $schemas.bench where from_list_date ='$court_date_new'  and bench_no='$bench_code1'";
		$sth = $dbh->prepare($sql);
		$sth->execute();
		$presiding = $sth->fetchColumn();
		$arr1 = sizeof($arr);
		for($i=0;$i<$arr1;$i++)
		{
			$jcode =$arr[$i];
			$sql = "select judge_name from $schemas.master_judge where judge_code =$jcode";
			$sth = $dbh->prepare($sql);
			$sth->execute();
			$judge = $sth->fetchColumn();

			print "<font size='2' ><b>".strtoupper($judge);
			if($jcode == $presiding) print "<font color='red'><b> (PRESIDING JUDGE )</b></font>";
			print"<br>";
		}echo'</td>';
	}
	echo'</tr>';
}
}
}
	?>
			
	
</table>

<?php  
if($court_date_new!='' and  $bench_no!='')
{
	 $sql_purpose="select distinct(purpose) as purpose from $schemas.case_allocation_temp where listing_date='$court_date_new'  and bench_no='$bench_no' ";
	$case_check = $db->prepare($sql_purpose);
	$case_check->execute();
	if($case_check->rowCount()>0){
	?>
	<table cellspacing="2" cellpadding="2" border="1" width="95%" class="std" align="center"> 
	<tr><th>
	<b>Sr.No.</b></th>
	<th><input type="checkbox" name="allbox" onClick="un_check(this);" title="Select or Deselct ALL" style="background-color:#ccc;"/></th>
	<!--<th align="left" width="100"><b>DIARY NO</b></th>-->
	<th align="left" width="150"><b>CASE NO</b></th>

	<th align="left" width="500"><B>CAUSE TITLE </b></th>
	<th align="left" width="150"><B>PURPOSE</b></th>
	</tr>

	<tr>
	<?php
	$sln=1;
	$x=0;
	
	while($row=$case_check->fetch())
	{
		$purpose_code_dis=$row['purpose'];
		

		if($row)
		{
			$sql_purpose_name="select purpose_name from $schemas.master_purpose where purpose_code='$purpose_code_dis'";
			$sth4=$dbh->prepare($sql_purpose_name);
			$sth4->execute();
			$purpose_name_dis=$sth4->fetchColumn();
			?>

<?php
	
			$sql_allocation="select a.filing_no from $schemas.case_allocation_temp a,$schemas.case_detail d where   a.listing_date='$court_date_new'   and a.filing_no=d.filing_no and d.status ='P' and bench_no ='$bench_no'  and a.purpose='$purpose_code_dis' order by a.priority_serial ";//  and a.court_no='$court_no_new' 
			?>
			
			<?php
			foreach($dbh->query($sql_allocation) as $row1)
			{
				$filing_no_new='';
				$filing_no_new=$row1['filing_no'];

		   		$sql10="select filing_no,bench_no,purpose,priority_serial from $schemas.case_allocation_temp where filing_no='$filing_no_new' and listing_date='$court_date_new' ";//and bench_no ='$bench_no_new'
				foreach($dbh->query($sql10) as $row10)
				{
					$filing_no='';
					$purpose_name_old='';$next_list_purpose='';
			  		$filing_no=$row10['filing_no'];
					$priority_serial=$row10['priority_serial'];
		
					$bench_code=$row10['bench_no'];
					//$next_list_purpose=$row10['purpose'];
				}//for case allocation 
				$sql30="select case_type,case_year,case_no,pet_name,res_name,location_code from $schemas.case_detail where filing_no ='$filing_no' ";
				foreach($dbh->query($sql30) as $row)
				{	
				
					$case_no='';
					$case_no =$row['case_no'];
					$case_type =$row['case_type'];
					$case_year =$row['case_year'];
					//$case_no_generate='';
					////$case_no_generate =$row['case_no_generate'];
					//if($case_no_generate!='N' and $case_no=='')continue;
					$location_code =$row['location_code'];
					$party_detail=$row['pet_name']."<b>  Vs.  </b>".$row['res_name'];


					$location_name='';
					$sql_location = "select short_name from $schemas.bench_location where bench_location_code='$location_code'";
					$ll=$dbh->prepare($sql_location);
					$ll->execute();
					$location_name=$ll->fetchColumn();
				}
				 $checkbox[$x] = isset($_REQUEST['checkbox'][$x]) ? $_REQUEST['checkbox'][$x] : ''; 
				?>
				<tr><td width="30" align="center" ><b><?php echo $sln;?></b></td>
				<td align="center" width="30" >
				
				<input class="checkbox"name="checkbox[<?php echo $x?>]" type="checkbox" id="checkbox[<?php echo $x?>]" value="<?php echo $filing_no; ?>" <?php if($checkbox[$x]==$filing_no)echo 'checked';?> >
				</td>
				</td>
 <!--<td style="padding-left: 15px;"><?php //echo $dno =ltrim(substr($filing_no,6,6),0).'/'.substr($filing_no,12,5);
				?></td>-->
				<td width="250" nowrap>
				<?php 
				$st="select case_type_desc from case_type where id='$case_type'";
				foreach($dbh->query($st) as $row)
				{
					$case_type_name=$row['case_type_desc'];	
				}
				echo "<b>".$case_type_name.'</b>/'.$location_name.'/'.$case_no.'/'.$case_year;
				?></td>
	                         
				<td width="400" align="left"><?php echo $party_detail; ?>
				</td>
				<td width="20%">
				<?php $purpose_code[$x] = isset($_REQUEST['purpose_code'][$x]) ? $_REQUEST['purpose_code'][$x] : $next_list_purpose;?>
				<select name="purpose_code[<?php $x?>]" style="width: 250px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
				<option value="">select</option>
				<?php
				foreach($purposeAll  as $purposeA)
				{		$selected='';
					if($purposeA[purpose_code]==$purpose_code[$x])$selected='selected';
					echo '<option value="'.$purposeA[purpose_code].'"'.$selected.' >'.$purposeA[purpose_name].'</option>';
				}
				?>
				</select>
				</td>

				</tr>
				<?php
			$x++;
			$sln++;	
			}	
		}
	}
	?>
	
	</table>
	<?php
 





}
}


	?>
	<?php
	$next_list_date1 = isset( $_REQUEST['next_list_date1'] )? $_REQUEST['next_list_date1']: false;
	if($next_list_date1!='')
	{
		?>
	<tr><td colspan="8" align="center"><br><br>
	<input type="submit" name="submit1" value="TRANSFER CASES" class="button"></td></tr>
	<?php 
	}
	?>
	</table>
</form>

 

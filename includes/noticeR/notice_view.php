<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include("../db_inc1.php");
 
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
echo "you Can't access this page";
}
else {

$curYear = date('Y');
$curMonth = date('m');
$curDay = date('d');
$cur_date = "$curDay/$curMonth/$curYear";
$curdate="$curYear-$curMonth-$curDay";

$wday = mktime(0,0,0,date("$curMonth"),date("d")-5,date("Y"));
$wday1= date("Y-m-d");


$schemas=htmlspecialchars($_SESSION['schema_name']);
$frm = md5( uniqid('auth', true) );


$_SESSION['form_token'] = $frm;

$no =$_REQUEST['no'];


$no = explode("@", $no);

$filing_no = $no[0];
$notice_date = $no[1];
$notice_id = $no[2];


 $notice_date1 =$_REQUEST['notice_date1'];
 $notice_id1 =$_REQUEST['notice_id1'];
 $filing_no1 =$_REQUEST['filing_no1'];
if($notice_date1!='' && $notice_id1!='' && $filing_no1!='')
{
list($day12,$month12,$year12)=explode('/',$notice_date1);
	 $notice_date1=$year12."-".$month12."-".$day12;
 $notice_date=$notice_date1;
 $notice_id=$notice_id1;
 $filing_no=$filing_no1;


}



$sqlf1=$db->prepare("select * from $schemas.notice_creation_details  where filing_no=? and notice_type=? and notice_date=?");

            $sqlf1->bindParam(1, $filing_no, PDO::PARAM_STR);
 	    $sqlf1->bindParam(2, $notice_id, PDO::PARAM_STR);
	    $sqlf1->bindParam(3, $notice_date, PDO::PARAM_STR);


            $sqlf1->execute();
			while ($row1 = $sqlf1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{
           		
			$party_admission=$row1['party_admission'];
			$ins_relief=$row1['ins_relief'];
			$sec_of_act=$row1['sec_of_act'];
			$concise_applicant=$row1['concise_applicant'];
			$behalf_applicant=$row1['behalf_applicant'];
			$address=$row1['address'];
			$tel_no=$row1['tel_no'];
			$fax=$row1['fax'];
			$email=$row1['email'];
			$prescribed_under_rule=$row1['prescribed_under_rule'];
			$reh_tr_petition_no=$row1['reh_tr_petition_no'];
			$matters_from=$row1['matters_from'];
			$other_matter=$row1['other_matter'];
			$reh_tr_type=$row1['reh_tr_type'];

 			 $dis_case_type=$row1['case_type'];
			 $dis_case_no=$row1['case_no'];
			 $dis_case_year=$row1['case_year'];
			 $dis_bench_loc=$row1['bench_loc'];
			 $on3a=$row1['on3a'];
			 $fixed3a=$row1['fixed3a'];
			 $bench3a=$row1['bench3a'];
 			
			 $to_3b=$row1['to_3b'];
			 $dated_3b=$row1['dated_3b'];
		         $presented_3b=$row1['presented_3b'];	
		 	 $bench_3b=$row1['bench_3b'];
 			 $state_3b=$row1['state_3b'];	
			 $before_3b=$row1['before_3b'];			 
			 $days_3b=$row1['days_3b'];
			 $applicant_3b=$row1['applicant_3b'];
			 $place_3b=$row1['place_3b'];
			 $us_3b=$row1['us_3b'];


  list($yy11,$mm11,$dd11)=explode('-',$dated_3b);
                $dated_3b=$dd11.'/'.$mm11.'/'.$yy11;






  list($yy1,$mm1,$dd1)=explode('-',$on3a);
                $on3a1=$dd1.'/'.$mm1.'/'.$yy1;
list($day2222,$month2222,$year2222)=explode('/',$on3a1);
          $on3a2=date(' j  \d\a\y   \o\f F Y', mktime(0, 0, 0, $month2222, $day2222, $year2222));  

	
		}
$sqlf2=$db->prepare("select * from $schemas.case_detail  where filing_no=? ");

            $sqlf2->bindParam(1, $filing_no, PDO::PARAM_STR);
 	   

            $sqlf2->execute();
			while ($row2 = $sqlf2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{
           		
			$pet_name=$row2['pet_name'];
			$res_name=$row2['res_name'];
			
			

	 	 	

		}


$sqlf3=$db->prepare("select * from case_type  where id=? ");

            $sqlf3->bindParam(1, $dis_case_type, PDO::PARAM_STR);
 	   

            $sqlf3->execute();
			while ($row3 = $sqlf3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{
           		
			$case_type_desc=$row3['case_type_desc'];
			 	 	

		}


$sqlf4=$db->prepare("select * from $schemas.bench_location  where bench_location_code=? ");

            $sqlf4->bindParam(1, $dis_bench_loc, PDO::PARAM_STR);
 	   

            $sqlf4->execute();
			while ($row4 = $sqlf4->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{
           		
			$bench_location_name=$row4['short_name'];
			 	 	

		}

 $case_number=$case_type_desc."/".$dis_case_no."/".$bench_location_name."/".$dis_case_year;


$sqlf=$db->prepare("select * from mater_location_city  where schema_name=?");

            $sqlf->bindParam(1, $schemas, PDO::PARAM_STR);
            $sqlf->execute();
			while ($row = $sqlf->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
           	{
           		
			$city_name=$row['city_name'];

		}





?>
<!DOCTYPE html>
<html>
<head>
<style type="text/css">
	div.hidden {
	display: none;
	}
	</style>

	<script language="javascript">
	function change(id, newClass)
	{
		identity=document.getElementById(id);
		identity.className=newClass;

	}
	function printPage()
	{http://www.novell.com/linux/10.html
		change("testdiv","hidden");
		window.print();
	}


</script>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>NCLT | Dashboard</title>





</head>
<body class="hold-transition skin-blue sidebar-mini">
<table    align="center" >
	<tr>
	<?php 
if($notice_date1!='')
{
?>
<td align="left"><a href="./notice.php">

<font face="Verdana" color="red" size="3"><center><b>BACK</b></center></font></a>

</td>




</tr>
<?php
}
?>
<tr>
	<td  align="left" >
	<div id="testdiv" style="visibility: visible;"><a href="javascript:printPage();"><font size="4" color="red">
Print</font></a></div>
</td>
</tr>
</table>
<table  border="0" width="80%"   align="center" >
<?php
if($notice_id==1)
{
?>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
FORM NO. NCLT.2</b>
</font>
</td>
</tr>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
[See rule 34]</b>
</font>
</td>
</tr>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
BEFORE THE NATIONAL COMPANY LAW TRIBUNAL</b>
</font>
</td>
</tr>



<tr>
<td align="center"><font face="Verdana" size="3"><b>
<?php echo strtoupper($city_name);?> BENCH</b>
</font>
</td>
</tr>
<tr>
<td>
<p>
<style>
hr {
    display: block;
    margin-top: 0.5em;
    margin-bottom: 0.5em;
   
    border-style: inset;
    
}
</style>
<hr>

</p>
</td>
</tr>
<tr>
<td align="center">
<font face="Verdana" size="3"><b>

NOTICE OF ADMISSION</font>
</td>
</tr>
<tr>
<td align="left'>
<font face="Verdana" size="3"><b>
<?php

if($notice_date !='')
{
	list($year2,$month2,$day2)=explode('-',$notice_date);
	 $notice_date=$day2."/".$month2."/".$year2;
	
}
?>
Date: <?php echo $notice_date;?></font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2"><b>

From: <?php echo $party_admission; ?></font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2"><b>

To: The Registrar,
</font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2"><b>

NCLT (<?php echo $city_name?> Bench )

</font>
</td>
</tr>
<tr>
<td  align="center">
<b>
<font face="Verdana" size="2"><?php echo $pet_name;?> 
</font></td>
<td align="right"><b>
<font face="Verdana" size="2">Applicant
</font></td>
</tr>
<tr>
<td align="center"><b>Vs
</td>
</tr>
<tr>
<td  align="center"><b>
<font face="Verdana" size="2"><?php echo $res_name;?> 
</font></td>
<td align="right">
<font face="Verdana" size="2"><b>Respondent
</font>
</b></td>
</tr>

<tr>
<td align="left"><br>
<font face="Verdana" size="2">

The Party named above requests that the Tribunal grant the following relief: <?php echo $ins_relief;?>
</font>
</td>
</tr>
<!--
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

(Insert the relief or order sought)
</font>
</td>
</tr>
-->
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

In terms of <?php echo $sec_of_act; ?></font>
</td>
</tr>
<!--
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

 (Insert the section of the Act, or the Rules/ Regulation , that provides for the order or relief sought)
</font>
</td>
</tr>
-->
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

 <b>For the following reasons:</b></font>
</td>
</tr>
</table>
<table  border="1" width="80%"   align="center" >

<tr>
<td align="left"><br>
<font face="Verdana" size="2">

<!-- (Insert a concise statement of the circumstances , and the particulars of the request) <br>--><?php echo $concise_applicant; 	?></font>
</td>
</tr>
</table>
<table align="center" width="80%"">
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

In support of this Application, the applicant has attached an affidavit setting out the facts on which the Applicant relies.</font>
</td>
</tr>
<tr>
<td align="left">
<b><br>
<font face="Verdana" size="2">

Name and Title of person signing on behalf of Applicant: 
<br>
<?php
echo $behalf_applicant; 
?>
</b>
</td>
</font>
</tr>
<tr>
<td align="left"><b><br>
<font face="Verdana" size="2">

Authorised Signature and Address:<br>
<?php
echo $address; 
?>
</td>
</font>
</tr>
<tr>
<td align="left"><b><br>
<font face="Verdana" size="2">

Tel No:
<?php
echo $tel_no; 
?>
</td>
</font>
</tr>
<tr>
<td align="right"><b><br>
<font face="Verdana" size="2">

Fax No.
<?php
echo $fax; 
?>
</td>
</font>
</tr>
<tr>
<td align="right"><b>
<font face="Verdana" size="2">

Email:
<?php
echo $email; 
?>
</font>
</td>

</tr>
<tr>
<td align="left"><b>
<font face="Verdana" size="2">

This form is prescribed under Rule <?php
echo $prescribed_under_rule; 
?> under NCLT Rules, 2016.
</td>
</font>
</tr>
<?php
if($reh_tr_type==1)
{
?>
<tr>
<td align="left"><b>
<font face="Verdana" size="2">
For rehabilitation: <?php?>

<font face="Verdana" size="2">
Rehab. Petition No <?php echo $reh_tr_petition_no;?>
</td>

</font>
</tr>
<?php
}
?>
<?php
if($reh_tr_type==2)
{
?>

<tr>
<td align="left"><b>
<font face="Verdana" size="2">
For Transferred : <?php?>

<font face="Verdana" size="2">
Transfer Petition (CLB/ BIFR/ AIFR/HHC) No: <?php echo $reh_tr_petition_no;?>
</td>

</font>
</tr>
<?php
}
?>
<tr>
<td align="left"><b>
<font face="Verdana" size="2">
Matters from the: <?php echo $case_number;?>

<font face="Verdana" size="2">
 

 
</td>

</font>
</tr>
<tr>
<td align="left"><b>
<font face="Verdana" size="2">
For Other matter: <?php?>

<font face="Verdana" size="2">
Company Petition No. <?php echo $other_matter;?>

 
</td>

</font>
</tr>
<?php
}

?>

  </table>
<table  border="0" width="80%"   align="center" >
<?php
if($notice_id==2)
{

?>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
FORM NO. NCLT. 3</b>
</font>
</td>
</tr>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
[See rule 34]</b>
</font>
</td>
</tr>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
BEFORE THE NATIONAL COMPANY LAW TRIBUNAL</b>
</font>
</td>
</tr>



<tr>
<td align="center"><font face="Verdana" size="3"><b>
<?php echo strtoupper($city_name);?> BENCH</b>
</font>
</td>
</tr>
<tr>
<td>
<p>
<style>
hr {
    display: block;
    margin-top: 0.5em;
    margin-bottom: 0.5em;
   
    border-style: inset;
    
}
</style>
<hr>

</p>
</td>
</tr>
<tr>
<td align="center">
<font face="Verdana" size="3"><b>

NOTICE OF MOTION</font>
</td>
</tr>
<tr>
<td align="left'>
<font face="Verdana" size="3">
<?php

if($notice_date !='')
{
	list($year2,$month2,$day2)=explode('-',$notice_date);
	 $notice_date=$day2."/".$month2."/".$year2;
	
}
?>
Date: <?php echo $notice_date;?></font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">

From: <?php echo $party_admission; ?></font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">

To: The National Company Law Tribunal,
</font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">

Concerning:

</font>
</td>
</tr>
</table>
<table  border="1" width="80%"   align="center" >

<tr>
<td align="left"><br>
<font face="Verdana" size="2">

Name: <?php echo $adm_rules;?>
</font>
</td>
</tr>
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

File No: <?php echo $fileno_3;?>
</font>
</td>
</tr>
</table>
<table   width="80%"   align="center" >
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

The Party Named above requests that the Tribunal grant the following relief: <?php echo $ins_relief; ?></font>
</td>
</tr>
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

In terms of : <?php echo $prescribed_under_rule; ?></font>
</td>
</tr>

<tr>
<td align="left"><br>
<font face="Verdana" size="2">

 <b>For the following reasons:</b></font>
</td>
</tr>
</table>
<table  border="1" width="80%"   align="center" >

<tr>
<td align="left"><br>
<font face="Verdana" size="2">

<?php echo $concise_applicant; 	?></font>
</td>
</tr>
</table>
<table align="center" width="80%"">
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

In support of this Application, the applicant has attached an affidavit setting out the facts on which the Applicant relies.</font>
</td>
</tr>
<tr>
<td align="left">
<b><br>
<font face="Verdana" size="2">

Name and Title of person signing on behalf of Applicant: 
<br>
<?php
echo $behalf_applicant; 
?>
</b>
</td>
</font>
</tr>
<tr>
<td align="left"><b><br>
<font face="Verdana" size="2">

Authorised Signature and Address:<br>
<?php
echo $address; 
?>
</td>
</font>
</tr>
<tr>
<td align="right"><b>
<font face="Verdana" size="2">

Tel No:
<?php
echo $tel_no; 
?>
</td>
</font>
</tr>
<tr>
<td align="right"><b>
<font face="Verdana" size="2">

Fax No.
<?php
echo $fax; 
?>
</td>
</font>
</tr>
<tr>
<td align="right"><b>
<font face="Verdana" size="2">

Email:
<?php
echo $email; 
?>
</font>
</td>

</tr>
<tr>
<td align="left"><b>
<font face="Verdana" size="2">

This form is prescribed under Rule 4 
NCLT Rules, 2016.
</td>
</font>
</tr>
<?php
if($reh_tr_type==1)
{
?>
<tr>
<td align="left"><b>
<font face="Verdana" size="2">
For rehabilitation: <?php?>

<font face="Verdana" size="2">
Rehab. Petition No <?php echo $reh_tr_petition_no;?>
</td>

</font>
</tr>
<?php
}
?>
<?php
if($reh_tr_type==2)
{
?>

<tr>
<td align="left"><b>
<font face="Verdana" size="2">
For Transferred : <?php?>

<font face="Verdana" size="2">
Transfer Petition (CLB/ BIFR/ AIFR/HHC) No: <?php echo $reh_tr_petition_no;?>
</td>

</font>
</tr>
<?php
}
?>
<tr>
<td align="left"><b>
<font face="Verdana" size="2">
Matters from the: <?php echo $case_number;?>

<font face="Verdana" size="2">
 

 
</td>

</font>
</tr>
<tr>
<td align="left"><b>
<font face="Verdana" size="2">
For Other matter: <?php?>

<font face="Verdana" size="2">
Company Petition No. <?php echo $other_matter;?>

 
</td>

</font>
</tr>
<?php
}
?>
  </table>
<table  border="0" width="60%"   align="center" >
<?php
if($notice_id==3)
{

?>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
FORM NO. NCLT. 3A</b>
</font>
</td>
</tr>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
Advertisement detailing petition</b>
</font>
</td>
</tr>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
[See rule 35]</b>
</font>
</td>
</tr>



<tr>
<td align="center"><font face="Verdana" size="3">
<br>
<br>
<?php echo $case_number;?></b>
</font>
</td>
</tr>
<tr>
<td align="center">

<font face="Verdana" size="3">
<p>
Notice of petition</font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">
<p align="justify">A petition/application/reference under section <?php echo $prescribed_under_rule; ?> of the companies Act,2013, for <?php echo $party_admission; ?>
 was presented by <?php echo $sec_of_act; ?> on the <?php echo $on3a2 ; ?> and the said petition is fixed for hearing before <?php echo $fixed3a; ?> bench of National Company Law Tribunal on
 <?php echo $bench3a; ?> Any person desirous of supporting or opposing the said petition/application/reference should send to the petitioner's advocate, notice of his intention, signed by him or his advocate, with his name and address, so as to reach the petitioner's advocate not later than two days before the date fixed for the hearing of the petition/application/reference. Where he seeks to oppose the petition/application/reference, the grounds of opposition or a copy of his affidavit shall be furnished with such notice. A copy of the petition/application/reference will be furnished by the undersigned to any person requiring the same on payment of the prescribed charges for the same. </p></font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">
<?php 
if($notice_date !='')
{
	list($year2,$month2,$day2)=explode('-',$notice_date);
	 $notice_date=$day2."/".$month2."/".$year2;
	
}
?>
Dated: <?php echo $notice_date; ?>
</font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">

(SD): <?php echo $concise_applicant; ?>

</font>
</td>
</tr>


<tr>
<td align="left">
<font face="Verdana" size="2">

(Name): <?php echo $behalf_applicant;?>
</font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">

(Advocate for Petitioner) 
</font>
</td>
</tr>
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

Address <?php echo $address;?>
</font>
</td>
</tr>
</table>
<?php
}
?>
   </table>
<table  border="0" width="60%"   align="center" >
<?php
if($notice_id==4)
{

?>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
FORM No. NCLT. 3B</b>
</font>
</td>
</tr>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
Individual Notice of petition/ application to creditors, members, etc.</b>
</font>
</td>
</tr>
<tr>
<td align="center"><font face="Verdana" size="3"><b>
[see rule 68]</b>
</font>
</td>
</tr>

		 

<tr>
<td align="left"><font face="Verdana" size="3">
To,

</font>
</td>
</tr>

<tr>
<td align="left"><font face="Verdana" size="3">
<?php echo $to_3b ;?>

</font>
</td>
</tr>
<tr>
<td align="center">

<font face="Verdana" size="3">

(sub: Notice of petition/ application filed under section <?php echo $us_3b;?> of Companies Act, 2013)</font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">
<p align="justify">Take notice that a petition/ application under section <?php echo $us_3b;?> of the Companies Act, 2013 dated <?php echo $dated_3b;?>
 was presented by <?php echo $presented_3b ;?>(name of the company before <?php echo $before_3b;?> Bench, National Company Law Tribunal, for <?php echo $state_3b;?>
(state the purpose of the petition).
</p>
</td>
</tr>
		
<tr>
<td align="left">
<font face="Verdana" size="2">
<p align="justify">The said petition/ application has been accepted and is fixed for hearing before the Bench on <?php echo $before_3b; ?>
</p>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">
<p align="justify">If you desire to support or oppose the petition at the hearing, you should give notice thereof in writing to the undersigned so as to reach him/ it not later than <?php echo $days_3b; ?> days before the date fixed for the hearing of the petition, and appear at the hearing in person or by your authorised representative.
</p>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">
<p align="justify">
Where such person seeks to oppose the petition/ application, the grounds of opposition or a copy of the affidavit shall be furnished with such notice.

</p>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">
<p align="justify">
A copy of the petition/application will be furnished by the undersigned to any requiring the same on payment of the prescribed charges for the same.
</p>
</td>
</tr>


<tr>
<td align="right">
<?php echo $applicant_3b; ?><br>
<br>
<font face="Verdana" size="2">

Signature 

</font>
</td>
</tr>


			
<tr>
<td align="right">
<font face="Verdana" size="2">

Name of the petitioner/ applicant
(& his authorised representative, if any)

</font>
</td>
</tr>
<tr>
<td align="left">
<font face="Verdana" size="2">

Date: <?php echo $cur_date;?>
</font>
</td>
</tr>
<tr>
<td align="left"><br>
<font face="Verdana" size="2">

Place: <?php echo  $place_3b;?>
</font>
</td>
</tr>
</table>
<?php
}
?>

<?php
}
?>




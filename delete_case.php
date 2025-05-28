	 <?php
	 include("./db_inc1.php");
	include './db_inc2.php';
	 
	 $filing_no='0710102020142018';
	
	
	$st=$db->prepare("select * from delhi.case_detail where filing_no=? ");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$filing_no1 = ($row['filing_no']);
}
if($filing_no1!='');
{
	$sql_pr1="delete from delhi.case_detail  where filing_no='$filing_no'";
	$sth=$dbh->prepare($sql_pr1);
	$sth->execute();
}

$st2=$db->prepare("select * from delhi.case_allocation_temp where filing_no=? ");
$st2->bindParam(1, $filing_no, PDO::PARAM_STR);
$st2->execute();

while ($row2 = $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$filing_no2 = ($row2['filing_no']);
}
if($filing_no2!='');
{
	$sql_pr2="delete from delhi.case_allocation_temp  where filing_no='$filing_no'";
	$sth2=$dbh->prepare($sql_pr2);
	$sth2->execute();
}	

$st3=$db->prepare("select * from delhi.case_allocation where filing_no=? ");
$st3->bindParam(1, $filing_no, PDO::PARAM_STR);
$st3->execute();

while ($row3 = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$filing_no3 = ($row3['filing_no']);
}


if($filing_no3!='');
{
	$sql_pr3="delete from delhi.case_allocation  where filing_no='$filing_no'";
	$sth3=$dbh->prepare($sql_pr3);
	$sth3->execute();
}	


$st4=$db->prepare("select * from delhi.scrutiny where filing_no=? ");
$st4->bindParam(1, $filing_no, PDO::PARAM_STR);
$st4->execute();

while ($row4 = $st4->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$filing_no4 = ($row4['filing_no']);
}

if($filing_no4!='');
{	
	$sql_pr4="delete from delhi.scrutiny  where filing_no='$filing_no'";
	$sth4=$dbh->prepare($sql_pr4);
	$sth4->execute();
}	


$st5=$db->prepare("select * from delhi.objection_details where filing_no=? ");
$st5->bindParam(1, $filing_no, PDO::PARAM_STR);
$st5->execute();

while ($row5 = $st5->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$filing_no5 = ($row5['filing_no']);
}

if($filing_no5!='');
{
	$sql_pr5="delete from delhi.objection_details  where filing_no='$filing_no'";
	$sth5=$dbh->prepare($sql_pr5);
	$sth5->execute();
}


$st6=$db->prepare("select * from delhi.case_proceeding where filing_no=? ");
$st6->bindParam(1, $filing_no, PDO::PARAM_STR);
$st6->execute();

while ($row6 = $st6->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$filing_no6 = ($row6['filing_no']);
}

if($filing_no6!='');
{
	$sql_pr6="delete from delhi.case_proceeding  where filing_no='$filing_no'";
	$sth6=$dbh->prepare($sql_pr6);
	$sth6->execute();
	
}


$st7=$db->prepare("select * from e_case_detail_local where filing_no=? ");
$st7->bindParam(1, $filing_no, PDO::PARAM_STR);
$st7->execute();

while ($row7 = $st7->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$filing_no7 = ($row7['filing_no']);
}

if($filing_no7!='');
{
	$sql_pr7="delete from e_case_detail_local  where filing_no='$filing_no'";
	$sth7=$dbh->prepare($sql_pr7);
	$sth7->execute();
}
	
		

  
  ?>
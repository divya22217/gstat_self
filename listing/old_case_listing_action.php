<?php
session_start();
ob_start();
include("../db_inc1.php");
$schemas=htmlspecialchars($_SESSION['schema_name']);

$next_list_date=$_REQUEST['next_list_date'];
list($d,$m,$Y) =explode('/',$next_list_date);
$list_date =$Y.'-'.$m.'-'.$d;
 $sql="select count(*) from $schemas.case_allocation_temp where listing_date ='$list_date'";
$aaa = $db->prepare("select count(*) from $schemas.case_allocation_temp where listing_date ='$list_date'");
$aaa->execute();

 $dd  =$aaa->fetchColumn();

if($dd>0)
{
	 $msg2="CASE ALREADY LISTED FOR  ";
	$hash2=base64_encode($msg2);
	
	
	 $msg=base64_encode($next_list_date);
       $msg1=htmlspecialchars($hash2.'-'.$msg);
 $hash3=base64_encode($msg1);
	
	
	

		header("Location:./old_case_listing.php?hash3=$hash3");
		die();
}




 $sql="select filing_no,purpose,bench_no,bench_nature,listing_date,court_no from $schemas.case_allocation_temp where next_list_date ='$list_date'";


foreach($dbh->query($sql) as $row)
{
	 $filing_no =$row['filing_no'];
	$purpose_code =$row['purpose'];
	$bench_no =$row['bench_no'];
	 $bench_nature =$row['bench_nature'];
	$listing_date =$row['listing_date'];
	 $court_no =$row['court_no'];

	/*part heard matters code
	*/
	if($purpose_code ==18)
	{
		
	 
	
 $sql="select presiding from $schemas.bench where bench_no ='$bench_no' and from_list_date='$listing_date'"; 
	 $sth=$dbh->prepare($sql);
	 $sth->execute();
	  $presiding_old=$sth->fetchColumn();
	 
	 if($bench_nature >1)
	 {
	 $sql="select judge_code from $schemas.bench_judge where bench_no ='$bench_no' and from_list_date='$listing_date' and judge_code !='$presiding_old'"; 
	 $sth=$dbh->prepare($sql);
	 $sth->execute();
	   $judge_old=$sth->fetchColumn();
	
	 }
	 
	 $sql="select bench_no,bench_nature  from $schemas.bench where  from_list_date='$list_date'"; 
  
	 foreach($dbh->query($sql) as $row)
     {
	 $bench_no_new =$row['bench_no'];	 
	 $bench_nature_new =$row['bench_nature'];	 
	   $sql="select presiding from $schemas.bench where bench_no ='$bench_no_new' and from_list_date='$list_date'"; 
	 $sth=$dbh->prepare($sql);
	 $sth->execute();
	  $presiding_new=$sth->fetchColumn();
	 if($bench_nature_new >1)
	 {
  $sql="select judge_code from $schemas.bench_judge where bench_no ='$bench_no_new' and from_list_date='$list_date' and judge_code !='$presiding_new'"; 
	 $sth=$dbh->prepare($sql);
	 $sth->execute();
	  $judge_new=$sth->fetchColumn();
	 }
	 
	
        if($bench_nature>1)
	 {
	
if(($presiding_old ==$presiding_new) && ($judge_old == $judge_new ) && ($judge_old !='' && $judge_new !='') )
	
	 {
	  	
		 
      $sql="update $schemas.case_allocation_temp set bench_no ='$bench_no_new',listing_date='$list_date',last_listing_date='$listing_date',last_bench_no='$bench_no',last_court_no='$court_no',last_bench_nature='$bench_nature',listed='1' where filing_no='$filing_no'";

	 $sth=$dbh->prepare($sql);
	 $sth->execute();
	 }
	 }
	 
	 if($bench_nature==1)
	 {
	 if($presiding_old ==$presiding_new)
	 {
		
		 
     $sql1="update $schemas.case_allocation_temp set bench_no ='$bench_no_new',listing_date='$list_date',last_listing_date='$listing_date',last_bench_no='$bench_no',last_court_no='$court_no',last_bench_nature='$bench_nature',listed='1' where filing_no='$filing_no'";
   
	 $sth1=$dbh->prepare($sql1);
	 $sth1->execute();
	 }
	 }
	 
	 }
	 
	
	 
	 
	 
} /* end of part heard matters*/




	/*other matters code
	*/
	if($purpose_code!=18)
	{
	 $sql="select presiding from $schemas.bench where bench_no ='$bench_no' and from_list_date='$listing_date'"; 
	
	 $sth=$dbh->prepare($sql);
	 $sth->execute();
	 $presiding_old=$sth->fetchColumn();
	 
	 if($bench_nature ==2)
	 {
	  $sql="select judge_code from $schemas.bench_judge where bench_no ='$bench_no' and from_list_date='$listing_date' and judge_code !='$presiding_old'"; 
	
	 $sth=$dbh->prepare($sql);
	 $sth->execute();
	   $judge_old=$sth->fetchColumn();
	 }
	 else
	 {
		 $judge_old =0;
	 }
	
	 
	  $sql="select bench_no,bench_nature  from $schemas.bench where  from_list_date='$list_date'"; 
	  
     foreach($dbh->query($sql) as $row)
     {
	 $bench_no_new =$row['bench_no'];	 
	 $bench_nature_new =$row['bench_nature'];	 
	 $sql="select presiding from $schemas.bench where bench_no ='$bench_no_new' and from_list_date='$list_date'"; 
	 $sth=$dbh->prepare($sql);
	 $sth->execute();
	 $presiding_new=$sth->fetchColumn();
	 if($bench_nature_new ==2)
	 {
	 $sql="select judge_code from $schemas.bench_judge where bench_no ='$bench_no_new' and from_list_date='$list_date' and judge_code !='$presiding_new'"; 
	 $sth=$dbh->prepare($sql);
	 $sth->execute();
	 $judge_new=$sth->fetchColumn();
	 }
	 else
	 {
		 $judge_new =0;
	 }
	 
	 if($bench_nature ==1)
	 {
$judge_old =$presiding;
	 }
	 
//echo "<Br>";
//echo $presiding_old.'-'.$presiding_new;
//echo "---".$judge_old.'-'.$judge_new;
//echo "<br>";	 



	 if($presiding_old == $presiding_new)
	 {	 //echo "A";
 
     if( ($judge_old == $judge_new) && ($judge_old !='' && $judge_new !=''))
     {
		 //echo "B";
 
     $sql="update $schemas.case_allocation_temp set bench_no ='$bench_no_new',bench_nature='$bench_nature_new',listing_date='$list_date',last_listing_date='$listing_date',last_bench_no='$bench_no',last_court_no='$court_no',last_bench_nature='$bench_nature',listed='1' where filing_no='$filing_no'";
     $sth=$dbh->prepare($sql);
	 $sth->execute();
	 }
	 }
	 	if($bench_nature==2)
	 { 
	 if(($presiding_old ==$presiding_new) && ($judge_old == $judge_new ) && ($judge_old !='' && $judge_new !='') )


		 {
	  $sql="update $schemas.case_allocation_temp set bench_no ='$bench_no_new',listing_date='$list_date',last_listing_date='$listing_date',last_bench_no='$bench_no',last_court_no='$court_no',last_bench_nature='$bench_nature',listed='1' where filing_no='$filing_no'";
     $sth=$dbh->prepare($sql);
	 $sth->execute();
			 
			 
		 }
	 }
	 
	 if($bench_nature==1)
	 { 
	 if($presiding_old ==$presiding_new) 


		 {
	 $sql="update $schemas.case_allocation_temp set bench_no ='$bench_no_new',listing_date='$list_date',last_listing_date='$listing_date',last_bench_no='$bench_no',last_court_no='$court_no',last_bench_nature='$bench_nature',listed='1' where filing_no='$filing_no'";
     $sth=$dbh->prepare($sql);
	$sth->execute();
			 
			 
		 }
	 }
	 
	 
	 

/*
if(($presiding_old !=$presiding_new) && ($judge_old == $judge_new ))
		 {
			 
			 
			 

	 $sql="update $schemas.case_allocation_temp set bench_no ='$bench_no_new',listing_date='$list_date',last_listing_date='$listing_date',last_bench_no='$bench_no',last_court_no='$court_no',last_bench_nature='$bench_nature',listed='1' where filing_no='$filing_no'";
     $sth=$dbh->prepare($sql);
	 $sth->execute();
			 
			 
		 }


*/
	 }
	 
	 
	 
	 
} /* others matters*/


}

$hash=base64_encode($next_list_date);

		header("Location:./old_case_listing.php?hash=$hash");
?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("./db_inc1.php");
include './db_inc2.php';

$chktype=

$location_access='10';

$filing_no='0710102024652018';
$comment1='';
$sessionUserType='78';
$notification_date1='2019-03-06';
$status1='YES';
$case_type='16';
$yes='Y';
$ss='11';
$ia_id=NULL;

$sth=$db->prepare("select * from check_list_local where location_all=?  order by id ASC ");
$sth->bindParam(1, $location_access, PDO::PARAM_STR);
$sth->execute();

while($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	echo $id_check=$rowa['id'];
	$aa='0';
	
	$adddef_sql = "insert into delhi.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,ia_id) values
(?,?,?,?,?,?,?,?,?,?,?)";
$sthaqq = $db->prepare($adddef_sql);

$sthaqq->execute(array($filing_no,$id_check,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$ss,$ia_id));
}


	
	if($case_type!='15' || $case_type!='14')
{
$sth=$db->prepare("select * from master_scrutiny_local where  link_id=?  order by id_serno ASC ");
//$sth->bindParam(1, $location_access, PDO::PARAM_STR);
$sth->bindParam(1, $E_sec_id, PDO::PARAM_STR);
$sth->execute();
}
if($case_type=='15' || $case_type=='14')
{
$sth=$db->prepare("select * from master_scrutiny_local where  case_type=?  order by id_serno ASC ");
//$sth->bindParam(1, $location_access, PDO::PARAM_STR);
$sth->bindParam(1, $case_type, PDO::PARAM_STR);
$sth->execute();
}

while ($rowa = $sth->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
echo $id_check=$rowa['id_serno'];
	$aa =1;
	
	$adddef_sql = "insert into delhi.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,ia_id) values
(?,?,?,?,?,?,?,?,?,?,?)";
$sthaqq = $db->prepare($adddef_sql);

$sthaqq->execute(array($filing_no,$id_check,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$ss,$ia_id));	
}
?>
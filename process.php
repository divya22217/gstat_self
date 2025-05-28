<?php
session_start();

$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
include('db_inc1.php');

$type = $_POST['type'];

if($type == 'new')
{
	//$startdate = $_POST['startdate'].'+'.$_POST['zone'];
	$startdate = $_POST['startdate'];
	$title = $_POST['title'];
echo 	$sql = "INSERT INTO $schemas.calendar(title,startdate,enddate,allday) VALUES('$title','$startdate','$startdate','false')";
	$sth = $db->prepare($sql);
	$sth->execute();
	$lastid = $db->lastInsertId(); 
	
	/*$insert = mysqli_query($con,"INSERT INTO calendar(`title`, `startdate`, `enddate`, `allDay`) VALUES('$title','$startdate','$startdate','false')");
	$lastid = mysqli_insert_id($con);*/
	echo json_encode(array('status'=>'success','eventid'=>$lastid));
}

if($type == 'changetitle')
{
	$eventid = $_POST['eventid'];
	$title = $_POST['title'];
	$sql = "UPDATE $schemas.calendar SET title='$title' where id='$eventid'";
	$sth = $db->prepare($sql);
	$sth->execute();
	//$update = mysqli_query($con,"UPDATE calendar SET title='$title' where id='$eventid'");
	if($sth->rowCount()>0)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'resetdate')
{
	$title = $_POST['title'];
	$startdate = $_POST['start'];
	$enddate = $_POST['end'];
	$eventid = $_POST['eventid'];
	$sql = "UPDATE $schemas.calendar SET title='$title', startdate = '$startdate', enddate = '$enddate' where id='$eventid'";
	$sth = $db->prepare($sql);
	$sth->execute();
	//$update = mysqli_query($con,"UPDATE calendar SET title='$title', startdate = '$startdate', enddate = '$enddate' where id='$eventid'");
	if($sth->rowCount()>0)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'remove')
{
	$eventid = $_POST['eventid'];
	$sql = "DELETE FROM $schemas.calendar where id='$eventid'";
	$sth = $db->prepare($sql);
	$sth->execute();
	//$delete = mysqli_query($con,"DELETE FROM calendar where id='$eventid'");
	if($delete)
		echo json_encode(array('status'=>'success'));
	else
		echo json_encode(array('status'=>'failed'));
}

if($type == 'fetch')
{
	$events = array();
	$sql = "SELECT * FROM $schemas.calendar";
	$sth = $db->prepare($sql);
	$sth->execute();
	//$query = mysqli_query($con, "SELECT * FROM calendar");
	while($fetch = $sth->fetch())
	{
	$e = array();
    $e['id'] = $fetch['id'];
    $e['title'] = $fetch['title'];
    $e['start'] = $fetch['startdate'];
    $e['end'] = $fetch['enddate'];

    $allday = ($fetch['allday'] == "true") ? true : false;
    $e['allday'] = $allday;

    array_push($events, $e);
	}
	echo json_encode($events);
}


?>
<?php

include("./db_inc1.php");
$query = "select holiday_date,reason from delhi.holidays where status = true order by holiday_date asc";
$query = $db->prepare($query);
$query->execute();
$data = $query->fetchAll();

$holidays = array();

foreach($data as $k=>$v){
	$a=array('description'=>$v['reason'],'color'=>'#ff0000');
	$holidays[$v['holiday_date']]=$a;
}

echo json_encode($holidays);


?>
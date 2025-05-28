<?php


$path = $_GET['path'];
$res = file_get_contents($path);
file_put_contents('defects.pdf',$res);
header("location:defects.pdf");


?>
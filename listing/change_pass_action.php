<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
$user = $_SESSION['user'];
$loc = $_SESSION['location'];
$sessionUserid = htmlspecialchars($_SESSION['id']);
$_SESSION['salt'];

if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
}
if (($_SESSION['user']) == '') {
  echo "you Can't access this page";
}
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
  $new_password = htmlspecialchars(htmlentities($_REQUEST['new_password']));
  $confirm_new_password = htmlspecialchars(htmlentities($_REQUEST['confirm_new_password']));
  $auser = htmlspecialchars(htmlentities($_REQUEST['auser']));
  if($_SESSION['id'] != $auser){
    echo "you Can't update the password";
    die();
  }
  $saltkj="saltzz";
  $localadmincont = htmlspecialchars(htmlentities($_REQUEST['localadmincont']));
  if ($new_password != $confirm_new_password) {
    header("Location:change_password.php?msg=2");
    die();
  }
  $salt_sat = '@2016@';
  $sql = "update users_cis set password=? where id =? ";
  $hscquery = $db->prepare($sql);
  $hscquery->bindValue(1, $new_password, PDO::PARAM_STR);
  $hscquery->bindParam(2, $auser, PDO::PARAM_INT);
  $hscquery->execute();


  header("Location: change_password.php?msg=3");
  die("Password Not Changed Please RE-Login.... ");
}

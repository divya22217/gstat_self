<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("db_inc1.php");
session_start();
$user = $_SESSION['user'];
$loc = $_SESSION['location'];
$sessionUserid = htmlspecialchars($_SESSION['id']);
$_SESSION['salt'];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
}
if (($_SESSION['user']) == '') {
  echo "you Can't access this page";
}
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
  $old_password = htmlspecialchars(htmlentities($_POST['old_password']));
  $new_password = htmlspecialchars(htmlentities($_POST['new_password']));
  $confirm_new_password = htmlspecialchars(htmlentities($_POST['confirm_new_password']));
  $auser = $_SESSION['id'];
  if($_SESSION['id'] != $auser){
    echo "you Can't update the password";
    die();
  }
  $sql = "select password from users_cis where id =? ";
  $old_pass = $db->prepare($sql);
  $old_pass->bindParam(1, $auser, PDO::PARAM_INT);
  $old_pass->execute();
  $old_pass = $old_pass->fetchColumn();

  $saltkj="saltzz";
  if ($old_password != $old_pass) {
    header("Location:update_password.php?msg=4");
    die();
  }
  if ($new_password != $confirm_new_password) {
    header("Location:update_password.php?msg=2");
    die();
  }
  $salt_sat = '@2016@';
  $sql = "update users_cis set password=? , password_changed_at = now() where id =? ";
  $hscquery = $db->prepare($sql);
  $hscquery->bindValue(1, $new_password, PDO::PARAM_STR);
  $hscquery->bindParam(2, $auser, PDO::PARAM_INT);
  $hscquery->execute();


  header("Location: update_password.php?msg=3");
  die("Password Not Changed Please RE-Login.... ");
}

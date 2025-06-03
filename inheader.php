<?php
require_once('validate_request.php');
#require_once('url_validation1.php');
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
include("./db_inc1.php");
session_start();

$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
  echo "Access Problem.....";
  header("Location: ../login.php");
  die();
}



setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key = $_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
  die("#2E2E2Eirecting to login.php");
}

if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {

  
  
    $password_changed = $db->prepare("select password_changed_at from users_cis where username = ?");
      $password_changed->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
      $password_changed->execute();
      $password_changed_at = $password_changed->fetchColumn();
      $last_password_changed_at = new DateTime($password_changed_at);
        $today_date = new DateTime(Date('Y-m-d'));
        $days_elapsed = $last_password_changed_at->diff($today_date)->days;
      if(empty($password_changed_at) || $days_elapsed == 90){
        header("Location: ../update_password.php");
      }

  // This code not use next time .......	Schema session create Hear....


  $sessionUserType = htmlspecialchars($_SESSION['id']);


  $curYear = htmlspecialchars(date("Y"));
  $curMonth = htmlspecialchars(date("m"));
  $curDay = htmlspecialchars(date("d"));
  $cur_date = "$curYear-$curMonth-$curDay";
  $cur_date1 = "$curDay/$curMonth/$curYear";


  $link_scrutiny_idaccess = '1';

  $st = $db->prepare("select user_creation_date from users_cis where id=? ");
  $st->bindParam(1, $userid, PDO::PARAM_STR);
  $st->execute();
  $user_creation_date = $st->fetchColumn();


?>

  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GSTAT</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
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
    <!-- sha256 password  -->
    <script src="../sha256.js"></script>
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- daterange picker -->
    <link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../plugins/iCheck/all.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">




    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <!--   <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
  
  <style>
 header .upper {
    background: linear-gradient(160deg, #ddeaf1 0%, #f4e6c6 100%); 
    border-bottom: 3px solid #294984;
}

header {
    width: 100%; 
    left:0;
    right:0;
    z-index:99;
}
header h1 {
    margin: 0;
}
header .site-title {
    font-size: 1.75em;
    display: inline;
    font-family: serif;
    vertical-align: middle;
    margin-left: 15px;
    float: left;
    padding-top: 30px;
    color: #846312;
}
header .inner {
    overflow: hidden;
    width: 100%;
    max-width: 90%;
    margin: 0 auto;
    padding: 7px 0px;
}

header .left_logo {
    height: 94px;
    float: left;
    margin-left: 10px;
    padding: 0px 0;
}

header .right_logo {
    float: right;
    padding: 20px 0;
}

header .right_logo img {
    padding: 0 10px;
    height: 40px;
}
</style>
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <header>
    <div class="upper">
            <div class="inner">
            <div>
                <img src="../APTEL_files/GSTAT-Logo.png" class="left_logo">
                <h1 class="site-title">GST Appellate Tribunal</h1>
                </div>
                <div class="right_logo">
                    <img src="../APTEL_files//logo_sb.png">
                    <img src="../APTEL_files//logo_di.png">
                </div>
                
            </div>
        </div>
</header>
    <div class="wrapper">

      <header class="main-header">
        <?php include 'insidebar.php'; ?>
      </header>
    <?php
  }
    ?>

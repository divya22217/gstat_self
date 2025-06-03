<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
date_default_timezone_set("Asia/Kolkata");
include("db_inc1.php"); //database connection
include "generate_jwt.php";
//session_start();
function getIPAddress()
{
    //whether ip is from the share internet  
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //whether ip is from the remote address  
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
$client_ip = getIPAddress();
?>

<?php
//validate and password encode
if (isset($_POST['username'])) {
    $username = htmlspecialchars($_POST['username']);
}
if (isset($_POST['password'])) {
    $password = htmlspecialchars($_POST['password']);
}
// value entered is correct
if (isset($_SESSION['salt'])) {
    $SA = $_SESSION['salt'];
}

$submitted_username = '';
if (!empty($_POST)) {
    if ($_SESSION['vercode'] != $_POST['answer'] or  empty($_POST['answer'])) {
        // echo "<center><font size='+2' COLOR='#000000'>value is incorrect/Empty, kindly try again</font></center>";
         $msg = "Invalid Captcha!";

        // ////////////////////////// CODE BY PREETI ////////////////////////////

        $msg = "<script>
       swal({
            title: 'Invalid Captcha!',
            icon: 'warning',
            button: 'close',
          }); </script>";

        // ///////////////////////// CODE ENDS HERE /////////////////////////////

    }

    if ($_SESSION['vercode'] == $_POST['answer']) {
        $ipaddress = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
        $unamevv = $_POST['username'];
        $newTime = date("Y-m-d H:i:s");
        $newdate = date("Y-m-d");

        $atxr = $db->prepare("select * from log_attempt where user_id=? and date=? ");
        $atxr->bindParam(1, $unamevv, PDO::PARAM_STR);
        $atxr->bindParam(2, $newdate, PDO::PARAM_STR);
        $atxr->execute();

        while ($row = $atxr->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

            $username111 = $row['user_id'];
            $lock111 = $row['lock']; //N reset no Y for Yes
            $failed111 = $row['failed'];
        }

        if ($username111 == '' and $failed111 == '') {
            $unamevv = $_POST['username'];
            $xxrr = '1';
            $xrr = 'N';
            $atrr = $db->prepare("insert into log_attempt(user_id,ipaddress,datetime,failed,lock,date,client_ip) values (?,?,?,?,?,?,?)");
            $atrr->bindParam(1, $unamevv, PDO::PARAM_STR);
            $atrr->bindParam(2, $ipaddress, PDO::PARAM_STR);
            $atrr->bindParam(3, $newTime, PDO::PARAM_STR);
            $atrr->bindParam(4, $xxrr, PDO::PARAM_STR);
            $atrr->bindParam(5, $xrr, PDO::PARAM_STR);
            $atrr->bindParam(6, $newdate, PDO::PARAM_STR);
            $atrr->bindParam(7, $client_ip, PDO::PARAM_STR);
            $atrr->execute();
        }
        if ($lock111 == 'Y') {
            /*echo "<center><font size='10px' COLOR='#545454'>
You have entered an invalid USERNAME/PASSWORD Five Times. We have locked your account for security reasons.
</br>Contact TO Technical Support Team....</font></center>";*/

            //$msg=" You have entered an invalid USERNAME/PASSWORD Five Times. We have locked your account for security reasons. Contact TO Technical Support Team....";

        }
        if ($failed111 < 10  and $lock111 = 'N') {

            $query = "
            SELECT
                id,
                username,
                password,
                email,location,menuaccess_codeall,level_level,dept,schema_id,fname,lname,password_changed_at,court
            FROM users_cis
            WHERE
                username = :username
        ";


            $query_params = array(
                ':username' => $_POST['username']
            );

            try {

                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            } catch (PDOException $ex) {
                die("Failed to run query: " . $ex->getMessage());
            }
            $login_ok = false;
            $row = $stmt->fetch();
            if (!$row) {
                $msg = "USERNAME/PASSWORD Is Incorrect.";
                $uname = $_POST['username'];
                $ipaddress = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
                $yy = date("Y/m/d h:i:s");
                $at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status,client_ip) values (?,?,?,?,?,?)");
                $xx = 'USER NAME INCORRECT';
                $x = 'Login Fail';
                $at->bindParam(1, $uname, PDO::PARAM_STR);
                $at->bindParam(2, $ipaddress, PDO::PARAM_STR);
                $at->bindParam(3, $yy, PDO::PARAM_STR);
                $at->bindParam(4, $xx, PDO::PARAM_STR);
                $at->bindParam(5, $x, PDO::PARAM_STR);
                $at->bindParam(6, $client_ip, PDO::PARAM_STR);
                $at->execute();
                $unamevv = $_POST['username'];
                $st = $db->prepare("select failed from log_attempt where user_id=? and date=?");
                $st->bindParam(1, $unamevv, PDO::PARAM_STR);
                $st->bindParam(2, $newdate, PDO::PARAM_STR);
                $st->execute();
                $account_filingc = $st->fetchColumn();

                $fil_noc = $account_filingc = (int)$account_filingc + 1;


                $atrr = $db->prepare(" update log_attempt set failed=? ,ipaddress=?,datetime=?,client_ip=? where user_id=? and date=? ");
                $atrr->bindParam(1, $fil_noc, PDO::PARAM_STR);
                $atrr->bindParam(2, $ipaddress, PDO::PARAM_STR);
                $atrr->bindParam(3, $newTime, PDO::PARAM_STR);
                $atrr->bindParam(4, $client_ip, PDO::PARAM_STR);
                $atrr->bindParam(5, $unamevv, PDO::PARAM_STR);
                $atrr->bindParam(6, $newdate, PDO::PARAM_STR);
                $atrr->execute();
                if ($fil_noc == '5') {
                    $llaa = 'Y';
                    $atrr1 = $db->prepare(" update log_attempt set lock=? where user_id=? and date=? ");
                    $atrr1->bindParam(1, $llaa, PDO::PARAM_STR);
                    $atrr1->bindParam(2, $unamevv, PDO::PARAM_STR);
                    $atrr1->bindParam(3, $newdate, PDO::PARAM_STR);
                    $atrr1->execute();
                }
            }
        }
        if ($row) {

            $check_password = $password;

            $_SESSION['salt'];
            // echo $_SESSION['salt']."<br>";


            $saltkj = "saltzz";
            $md_db =  hash('sha256', $row['password'] . $_SESSION['salt'] . $saltkj);
            // echo $check_password."<br>";
            // echo $md_db."<br>";
            // echo $row['password'];
            // die();
            //  $md_db=  md5($row['password'].$SA) ;

            if ($check_password === $md_db) {

                $login_ok = true;
            }


            if ($login_ok) {
                $uname = $_POST['username'];
                $ipaddress = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
                $yy = date("Y/m/d h:i:s");
                $at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status,client_ip) values (?,?,?,?,?,?)");
                $xx = 'login True';
                $x = 'Login Successfully';
                $at->bindParam(1, $uname, PDO::PARAM_STR);
                $at->bindParam(2, $ipaddress, PDO::PARAM_STR);
                $at->bindParam(3, $yy, PDO::PARAM_STR);
                $at->bindParam(4, $xx, PDO::PARAM_STR);
                $at->bindParam(5, $x, PDO::PARAM_STR);
                $at->bindParam(6, $client_ip, PDO::PARAM_STR);
                $at->execute();


                $yesaa = '1';
                $atrrs = $db->prepare(" update log_attempt set failed=? ,ipaddress=?,datetime=?,client_ip=? where user_id=? and date=? ");
                $atrrs->bindParam(1, $yesaa, PDO::PARAM_STR);
                $atrrs->bindParam(2, $ipaddress, PDO::PARAM_STR);
                $atrrs->bindParam(3, $newTime, PDO::PARAM_STR);
                $atrrs->bindParam(4, $client_ip, PDO::PARAM_STR);
                $atrrs->bindParam(5, $unamevv, PDO::PARAM_STR);
                $atrrs->bindParam(6, $newdate, PDO::PARAM_STR);
                $atrrs->execute();



                //  unset($row['salt']);
                unset($row['password']);
                //echo Session_id();
                session_unset();
                //session_id($session_id_to_destroy);
                //session_destroy();

                session_regenerate_id(true);

                $new_sessionid = session_id();


                $_SESSION['user'] = htmlspecialchars($row['username']);
                $_SESSION['location'] = htmlspecialchars($row['location']);
                $_SESSION['id'] = htmlspecialchars($row['id']);
                $_SESSION['email'] = htmlspecialchars($row['email']);
                $_SESSION['menuaccess_codeall'] = htmlspecialchars($row['menuaccess_codeall']);
                $_SESSION['level_level'] = $row['level_level'];
                //$_SESSION['dept']=$row['dept'];
                $_SESSION['salt_user'] = $row['salt'];
                $_SESSION['schema_idccc'] = $row['schema_id'];
                $_SESSION['user_court'] = $row['court'];

                $_SESSION['csrf222'] = md5(uniqid(rand(), TRUE));
                $key122 = $_SESSION['csrf222'];

                $idid = $_SESSION['id'];
                $_SESSION['user_actual_name'] = $row['fname'] . ' ' . $row['lname'];
                $_SESSION['jwt_token'] = jwt_token($row['id'], $row['username']);
				$password_changed_at = $row['password_changed_at'];

                //new code...START

                $display = 'Y';

                $stc = $db->prepare("select * from mater_location_city where city_id=?");
                $stc->bindParam(1, $_SESSION['schema_idccc'], PDO::PARAM_STR);
                $stc->execute();
                while ($row = $stc->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                    //$complex=$row['complex_id'];
                    $schemas = $row['schema_name'];
                    $bench_name = $row['city_name'];
                    //$state_id=$row['state_id'];
                }
                $_SESSION['schema_name'] = $schemas;
                $_SESSION['bench_name'] = $bench_name;



                $stlug = $db->prepare("select localadmin,main_id,level_level,fname,lname from users_cis where id= ? ");
                $stlug->bindParam(1, $_SESSION['id'], PDO::PARAM_STR);

                $stlug->execute();
                while ($row = $stlug->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                    $localadmin = $row['localadmin'];
                    $main_id = $row['main_id'];
                    $level_level = $row['level_level'];
                    $fname = $row['fname'];
                    $lname = $row['lname'];
                }


                $_SESSION['localadmin'] = $localadmin;
                $_SESSION['main_id'] = $main_id;
                $_SESSION['level_level'] = $level_level;
                $_SESSION['actual_username'] = $fname . ' ' . $lname;

                $atrrsv = $db->prepare(" select accesspoint1 from users_cis where id=? ");
                $atrrsv->bindParam(1, $idid, PDO::PARAM_STR);
                $atrrsv->execute();
                while ($rowv = $atrrsv->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                    $accesspoint1 = $rowv['accesspoint1'];
                }
            }
            //	echo $login_ok;


            else {
                // Tell the user they failed
                // print("</br><center><font size='+2' COLOR='#000000'>USERNAME/PASSWORD Is Incorrect.</font></center>");

                $msg = "USERNAME/PASSWORD Is Incorrect.";
                $submitted_username = htmlentities(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'));
                $uname = $_POST['username'];
                $ipaddress = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
                $yy = date("Y/m/d h:i:s");
                $at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status,client_ip) values (?,?,?,?,?,?)");
                $xx = 'login Fail';
                $x = 'Login Failed';
                $at->bindParam(1, $uname, PDO::PARAM_STR);
                $at->bindParam(2, $ipaddress, PDO::PARAM_STR);
                $at->bindParam(3, $yy, PDO::PARAM_STR);
                $at->bindParam(4, $xx, PDO::PARAM_STR);
                $at->bindParam(5, $x, PDO::PARAM_STR);
                $at->bindParam(6, $client_ip, PDO::PARAM_STR);
                $at->execute();

                $st = $db->prepare("select failed from log_attempt where user_id=? and date=?");
                $st->bindParam(1, $unamevv, PDO::PARAM_STR);
                $st->bindParam(2, $newdate, PDO::PARAM_STR);
                $st->execute();
                $account_filingc = $st->fetchColumn();


                $fil_nocv = $account_filingc = (int)$account_filingc + 1;



                $atrr = $db->prepare(" update log_attempt set failed=? ,ipaddress=?,datetime=?,client_ip=? where user_id=? and date=? ");
                $atrr->bindParam(1, $fil_nocv, PDO::PARAM_STR);
                $atrr->bindParam(2, $ipaddress, PDO::PARAM_STR);
                $atrr->bindParam(3, $newTime, PDO::PARAM_STR);
                $atrr->bindParam(4, $client_ip, PDO::PARAM_STR);
                $atrr->bindParam(5, $unamevv, PDO::PARAM_STR);
                $atrr->bindParam(6, $newdate, PDO::PARAM_STR);
                $atrr->execute();

                if ($fil_nocv == '5') {
                    $llaa = 'Y';
                    $atrr1 = $db->prepare(" update log_attempt set lock=? where user_id=? and date=? ");
                    $atrr1->bindParam(1, $llaa, PDO::PARAM_STR);
                    $atrr1->bindParam(2, $unamevv, PDO::PARAM_STR);
                    $atrr1->bindParam(3, $newdate, PDO::PARAM_STR);
                    $atrr1->execute();
                }
            }
        }
    }
    //row loop
    if ($_SESSION['user'] != '') {
		if(empty($password_changed_at)){
			header("Location: ./update_password.php");
		}else{
        header("Location: ./index.php");
        die("Redirecting to login.php");
		}
    }
} // post loop

//}// Math Close   
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link id="Link1" rel="shortcut icon" href="http://gstat.gov.in/image/favicon.ico">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>GSTAT</title>

    <!-- Bootstrap -->
    <link href="./APTEL_files/bootstrap.min.css" rel="stylesheet">
    <!-- Important Owl stylesheet -->
    <link rel="stylesheet" href="./APTEL_files/owl.carousel.css">
    <!-- Default Theme -->
    <link rel="stylesheet" href="./APTEL_files/owl.theme.css">
    <link href="./APTEL_files/style3.css" rel="stylesheet">
    <link href="./APTEL_files/ticker.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./APTEL_files/css">
    <link rel="stylesheet" type="text/css" href="./APTEL_files/css(1)">
    <link rel="stylesheet" type="text/css" href="./APTEL_files/css(2)">
    <link href="./APTEL_files/font-awesome.min.css" rel="stylesheet">
    <script type="text/javascript" src="./APTEL_files/html5.js.download"></script>
    <script src="./APTEL_files/jquery.min.js.download"></script>
    <script type="text/javascript" src="utf8_encode.js"></script>
    <script src="sha256.js" language="javascript"></script>
    <script type="text/javascript" src="./js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="slick/slick.css">
    <link rel="stylesheet" href="slick/slick theme.css">

    <!-- custum CSS -->
    <link rel="stylesheet" href="customR.css">



    <script type="text/javascript">
        function openPopUp(url) {
            window.open(url, "_blank", "directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");
        }

        function check_validation() {
            // alert(document.Form.salt.value);
            if (document.Form.username.value == "") {
                alert("Please enter the Valid User Name");
                return false;
            }
            if (document.Form.password.value == "") {
                alert("Please enter the Valid Password");
                return false;
            }
            var saltk = "saltzz";
            var md5password = sha256_digest(sha256_digest(document.Form.password.value) + (document.Form.salt.value) + saltk);
            document.Form.password.value = md5password;
            // alert(document.Form.password.value);
            document.Form.salt.value = "";
        }
    </script>
    <script>
        function loadmenuContent() {
            $("#Divmenu").load("menu.html");
        }

        function loadleftContent() {
            $("#left_sidebar").load("left_menu_index.htm");
        }

        function loadfooterContent() {
            $("#footer_bar").load("footer.html");
        }

        function show_password() {
            var x = document.getElementById("password");
            var show_reset = document.getElementById("resetpwd");
            var show_confirm = document.getElementById("confirmpwd");
            console.log(show_reset + "" + show_confirm);
            if (x.type === "password") {
                x.type = "text";

            } else {
                x.type = "password";

            }
            if ((show_reset.type === "password") && (show_confirm.type === "password")) {
                show_reset.type = "text";
                show_confirm.type = "text";
            } else {
                show_reset.type = "password";
                show_confirm.type = "password";
            }
        }
    </script>
</head>

<body class="blue_bg">

<header>
<div id="topHeader">
				<div class="links">
					<a id="skip">Skip to Main Content</a> <a class="fresize f-adjust"><i
						class="fa fa-adjust"></i></a> <a class="fresize f-up">A<sup>+</sup></a>
					<a class="fresize f-normal">A</a> <a class="fresize f-down">A<sup>-</sup></a>
				</div>
			</div>
    <div class="upper">
        <div class="inner">
            <div style="padding: 10px;">
                <span class="left-logo-gst"><img src="./APTEL_files/GSTAT-Logo.png" class="left_logo"></span>
                <div class="emb-logo">
                    <img src="./APTEL_files/emb-wt.png" class="emb-logo">
                    <div class="textLogo">GST Appellate Tribunal</div>
                </div>
            </div>
            <div class="right_logo">
                <img src="./APTEL_files/right-logo-header.png"> 
            </div>

        </div>
    </div>
</header>
    

    <header id="main-menu-container" style="display:none;">
        <nav class="navbar navbar-inverse main-menu navbar-inverse-bg" id="skip">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand hidden-sm hidden-md hidden-lg logo_on_mobile" href="#"><img src="./APTEL_files/logo_nclat.png" class="img-responsive"></a>
                </div>
                <div class="collapse navbar-collapse js-navbar-collapse" id="Divmenu">

                    </ul>
                </div>
            </div>
        </nav>
    </header>


    <!--================= slider and Chairman message section ===================== -->
    <div class="container" id="slick">
         <div class="slider">
            <div><img src="images/backgroundP1.jpg" alt="Image 1" id="img1"></div>
            <div><img src="images/banner.jpg" alt="Image 2" id="img2"></div>
            <div><img src="images/banner1.jpg" alt="Image 3" id="img3"></div>
         </div>

        <div class="formbox">
            <div class="errormsg">
                <?php
                $aa = $_REQUEST['aa'];
                if ($aa == '104') {
                    echo "<b>Invalid Access</b>";
                }
                if ($aa == '100') {
                    echo "<b>Invalid form submission</b>";
                }


                if ($msg != '') {
                    echo "<span style='padding:5px;display:block;'> ".$msg."</span>";
                }
                ?>
            </div>
            <div class="formleft">
                <div class="leftcontent">Welcome to Case Information System (CIS) for <br><span>GSTAT</span></div>
            </div>
            <div class="formright">
                <form action="login.php" method="post" name="Form">
                    <div class="formtitle">Login</div>
                    <div class="inputwrap">
                        <input type="text" size="35" name="username" placeholder="Enter Username" class="forminput username" autocomplete="off" />
                    </div>
                    <div class="inputwrap">
                        <input type="password" size="35" name="password" id="password" placeholder="Enter Password" class="forminput password" />
                    </div>

                    <?php $_SESSION['salt'] = sha1(microtime());
                    $saltbb = $_SESSION['salt'];
                    // echo $saltbb;
                    // die();
                    ?>

                    <input type="hidden" name="salt" value="<?php echo htmlspecialchars(htmlentities($saltbb)); ?>" />

                    <div class="inline" style="margin-left: 9%;">
                        <input type="checkbox" onclick="show_password()"><span> Show Password</span>
                    </div>

                    <script language="javascript">
                        $(document).ready(function() {
                            $(".refresh").click(function() {
                                $(".imgcaptcha").attr("src", "captcha.php?_=" + ((new Date()).getTime()));
                            });
                        });
                    </script>

                    <div id="captchatext" class="captchatext">
                        <table class="captcha_table">
                            <tr>
                                <td><img id="captcha_id" src="captcha.php" class="imgcaptcha" alt="captcha" /></td>
                                <td><a href="javascript:;" onclick="document.getElementById('captcha_id').src = 'captcha.php?' + Math.random(); return false">
                                        <img id="refreshbtn" src="assets/img/Button_refresh.png" height="25">
                                    </a></td>
                                <td>
                                    <input name="answer" class="txt forminput captcha" placeholder="Enter Captcha" required="required" type="text" size="10" maxlength="6" autocomplete="off" />
                                </td>
                            </tr>
                        </table>

                    </div>
                    <div class="chairman-image" align="center">

                        <input class="formsubmit" type="submit" value="Login" name="submit" onclick="return check_validation();" />
                        <!-- <a href="JavaScript:void(0);" id="fgpwd" name="forget_pwd" onclick="resetpwd()">For Password Reset</a> -->
                        <a href="JavaScript:void(0);" id="fgpwd" name="forget_pwd" class="fpMsg">For reset your password, please contact to (GSTAT) Technical Support Team.<br>
                        Email: itsupport[at]gstat[dot]nic[dot]in</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <footer id="contactSection">
			<div class="kode_footer_wrap wrap_2">
				<div class="footer-container">
					<div class="row">
						<div class="col-md-4">
							<div id="lawbase_opening_timing_widget-3"
								class=" widget widget_lawbase_opening_timing_widget kode-widget kode-widget-bg-footer">
								<figure>
									<a href="#"><img src="./APTEL_files/GSTAT-Logo.png"
										class="f-logo"></a>
								</figure><br><br><br><br><br>
								        <div class="kode_widget_service">
											<span><i class="fa fa-map-marker"></i></span>
											<div class="widget_service_text">
												<p>GSTAT, 6th Floor, LIC Jeevan Bharati, Tower I Janpath,
												Connaught Place, New Delhi, Delhi 110001.</p>
											</div>
										</div>
									
										<div class="kode_widget_service">
											<span><i class="fa fa-envelope"></i></span>
											<div class="widget_service_text">
												<a href="#">connect@gstn.org.in</a>
											</div>
										</div>
									
										<div class="kode_widget_service">
											<span><i class="fa fa-phone"></i></span>
											<div class="widget_service_text">
												<a href="#">1800-103-4786</a>
											</div>
										</div>
									
								
							</div>
						</div>
						<div class="col-md-8">
							<div class="kode_padding">
								<div id="text-5" class="col-md-6 widget widget_text kode-widget">
									<div class="textwidget">
                                    <h4 class="widget-title widget-heading-5" style="color:white; text-decoration:none; border-bottom:none;">Links</h4>
										<div class="kode_widget_link">

											<ul>
                                               						 <li><a target="_blank" href="causelist.php">Cause List</a></li>
                                               						 <li><a target="_blank"  href="case_status.php" target="_blank">Case Status</a></li>
                                               						 <li><a target="_blank"  href="judgement.php" target="_blank">Judgement</a></li>
                                               						 <!-- <li><a href="#">Useful Links</a></li> -->
                                         						 <!--  <li><a href="#" target="_blank">Members</a></li>-->
                                               						 <!-- <li><a href="#">Notice</a></li> -->
                                               						 <li><a target="_blank"  href="https://uat-efiling.gstat.gov.in/efiling/reportIssue.drt?system_issue=CIS">Help Center</a></li>
                                           						 </ul>
										<!--	<ul>
												<li><a href="#">Cause List</a></li>
												<li><a href="https://uat-cis.gstat.gov.in/gstat/case_status.php" target="_blank">Case Status</a></li>
												<li><a href="https://uat-cis.gstat.gov.in/gstat/judgement.php" target="_blank">Judgement</a></li>
												<li><a href="#">Notice</a></li>
												<li><a href="helpInner.drt">Help Center</a></li>
											</ul>-->
										</div>
									</div>
								</div>
								<div
									class="col-md-6 widget widget_lawbase_flickr_widget kode-widget">
									<h4 class="widget-title widget-heading-5" style="color:white; text-decoration:none; border-bottom:none;">Location</h4>
									<div class="clear"></div>
								</div>
								<div class="col-md-6" id="map">
									<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2744.1612711070297!2d77.21919871825234!3d28.630385889410334!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd360f44dedf%3A0x2a08ccbf524b68ba!2sGST%20Council!5e0!3m2!1sen!2sin!4v1734416281933!5m2!1sen!2sin"
                                     height="150" style="border:0; width:100%;" allowfullscreen=""
                                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
        <div class="copyright">Designed &amp; Developed by National Informatics Centre (NIC)</div>
        <!-- =========================================== CODE STARTS HERE BY PREETI======================================= -->

        <!-- ====================================FORGET PASSWORD====================================== -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-body">
                        <!-- <form id="form_id" method="POST"> -->
                        <div class="form-group">
                            <div class="fgtpwd">
                                <div style="margin-bottom: 15px;">

                                    <?php
                                    // if(forget_pwd==true){
                                    // $_SESSION['salt'] = sha1(microtime());
                                    // $saltbb = $_SESSION['salt']; }
                                    ?>

                                    <!-- <input name="salt" type="hidden" value="<?php echo htmlspecialchars(htmlentities($saltbb)); ?>" /> -->
                                    <span class="text-primary">Enter Username</span>
                                    <input type="text" class="form-control" placeholder="Enter username" id="uname" pattern="[a-zA-Z0-9]{4,50}" title="Kindly enter valid username" required />
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <span class="text-primary">Enter Email id</span>
                                    <input type="email" class="form-control" placeholder="Enter email id" id="emid" maxlength="100" required />
                                </div>

                            </div>

                            <div class="otpdiv">
                                <div style="margin-bottom: 15px;">
                                    <span class="text-primary">Enter the OTP</span>
                                    <input type="text" name="otp" id="otp" placeholder="Enter OTP" class="form-control" required />
                                </div>


                            </div>

                            <div class="restdiv">
                                <div style="margin-bottom: 15px;">
                                    <span class="text-primary">Reset Password</span>
                                    <input type="password" name="password" id="resetpwd" placeholder="Enter Password" class="forminput password" required />

                                </div>
                                <div style="margin-bottom: 15px;">
                                    <span class="text-primary">Confirm Password</span>
                                    <input type="password" name="password" id="confirmpwd" placeholder="Enter Password" class="forminput password" required />
                                </div>
                                <div class="inline" style="margin-left: 1%;">
                                    <input type="checkbox" onclick="show_password()">Show Password
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="submit_btn" value="submit">Submit</button>
                            </div>
                        </div>
                        <!-- </form> -->
                    </div>

                </div>
            </div>
        </div>

        <!-- ====================================CODE ENDS HERE BY PREETI================================= -->

        <!--==================================== Pop us Dialogue end ===================================== -->

        <!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
        <script src="./APTEL_files/jquery.min.js(1).download"></script>
        <script src="./APTEL_files/owl.carousel.js.download"></script>
        <script src="./APTEL_files/main.js.download"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="./APTEL_files/bootstrap.min.js.download"></script>
        <script src="./APTEL_files/ticker.js.download"></script>
        <script type="text/javascript" src="./APTEL_files/font-modify.js.download"></script>
        <script type="text/javascript" src="./APTEL_files/jquery.faded.js.download"></script>
        <script type="text/javascript" src="./APTEL_files/jquery.faded-options.js.download"></script>
        <script type="text/javascript" src="./APTEL_files/imagepreloader.js.download"></script>
        <script type="text/javascript" src="./APTEL_files/load-window.js.download"></script>
        <script type="text/javascript" src="slick/slick.js"></script>

        <script>
            $(function() {
                $('a[href*="#"]:not([href="#"])').click(function() {
                    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                        var target = $(this.hash);
                        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                        if (target.length) {
                            $('html, body').animate({
                                scrollTop: target.offset().top
                            }, 1000);
                            return false;
                        }
                    }
                });
            });
        </script>
        <!-- ==================================== CODE STARTS HERE BY PREETI =============================== -->
        <script>
            function resetpwd() {
                alert('fffff');
                $('#exampleModal').modal('show');
            }
                var increse_size = 0.05; 
                var currentZoom_g = 1;  
                var maxZoom = 1.15;    
                var minZoom = 0.95;   

            $('document').ready(function() {
                 
                $(".f-up").click(function () { 
            var $textElement = $("body");
            var currentZoom = parseFloat($textElement.css("zoom")) || 1;

             if (currentZoom < maxZoom) {
                currentZoom += increse_size;
                $textElement.css("zoom", currentZoom);
            }
            });

            $(".f-normal").click(function () {
            var $textElement = $("body");
            $textElement.css("zoom", currentZoom_g);
            });

            $(".f-down").click(function () {
            var $textElement = $("body");
            var currentZoom = parseFloat($textElement.css("zoom")) || 1;

            if (currentZoom > minZoom) {
                currentZoom -= increse_size;
                $textElement.css("zoom", currentZoom);
            }
            });

            $("#skip").click(function () {
                 window.scrollBy(0, 70); 
            });

            $(".fa-adjust").click(function () {
                var currentFilter = $("body").css("filter");
                if (currentFilter === "none" || currentFilter === "") {
                    $("body").css("filter", "grayscale(100%)"); 
            } else {
                $("body").css("filter", "none");
            }
            });

            $('.slider').slick({
                dots: false, 
                infinite: false, 
                speed: 500,
                autoplay: true,
                autoplaySpeed: 3000,
                fade: true, 
                slidesToShow: 1, 
                slidesToScroll: 1,
                adaptiveHeight: true, 
                variableWidth: false, 
                centerMode: false, 
                responsive: true,
                cssEase: 'linear',
                pauseOnHover: false,
                accessibility: true,
                focusOnSelect: true,
            });
                $("button#submit_btn").click(function() {

                    if ($('#otp').val() != '' && $('#otp').val() !== undefined) {
                        let otp_get = $('#otp').val();
                        let getname = $('#uname').val();
                        let getmail = $('#emid').val();
                        let data = {
                            type: 3,
                            uname: getname,
                            umail: getmail,
                            otp: otp_get,


                        };
                        changepwd(data);

                    } else if (($('#resetpwd').val() && $('#confirmpwd').val()) === '') {

                        let type = 1;

                        let getname = $('#uname').val();
                        let getmail = $('#emid').val();

                        let data = {
                            type: 1,
                            uname: getname,
                            umail: getmail
                        };

                        changepwd(data);

                    } else if (($('#resetpwd').val() && $('#confirmpwd').val()) != '') {

                        let type = 2;
                        let resetpwd = $('#resetpwd').val();
                        let confirmpwd = $('#confirmpwd').val();
                        // console.log(confirmpwd);
                        if ((resetpwd.match(/[a-z]/g) && resetpwd.match(
                                /[A-Z]/g) && resetpwd.match(
                                /[0-9]/g) && resetpwd.match(
                                /[^a-zA-Z\d]/g) && resetpwd.length >= 8) && (confirmpwd.match(/[a-z]/g) && confirmpwd.match(
                                /[A-Z]/g) && confirmpwd.match(
                                /[0-9]/g) && confirmpwd.match(
                                /[^a-zA-Z\d]/g) && confirmpwd.length >= 8)) {

                        } else {
                            alert('Password must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters');
                            $('#resetpwd').val(' ');
                            $('#confirmpwd').val(' ');
                            return false;
                        }


                        var saltk = "saltzz";
                        // alert(document.Form.salt.value);
                        var md5password = sha256_digest(resetpwd);
                        var md5password1 = sha256_digest(confirmpwd);
                        // alert(md5password + "####" + md5password1);

                        document.Form.password.value = md5password;
                        document.Form.salt.value = "";
                        if (md5password !== md5password1) {
                            alert('Please enter the corrrect password');
                            $('#confirmpwd').focus();
                        }
                        getname = $('#uname').val();
                        getmail = $('#emid').val();

                        data = {
                            type: 2,
                            uname: getname,
                            umail: getmail,
                            reset: md5password,
                            conpwd: md5password1
                        };

                        changepwd(data);

                    } else {
                        alert("Something went wrong, please try again!");
                        return false;
                    }
                });


                function changepwd(data) {

                    // var data = JSON.stringify(data);
                    // alert('function called ' + data);

                    $.ajax({
                        url: 'reset_password.php',
                        method: 'POST',
                        data: data,
                        // data: {
                        //     name: $getname,
                        //     email: $getmail,
                        //     reset: $resetpwd,
                        //     confirm: $confirmpwd,
                        //     check: $type
                        // },
                        // dataType: 'json',   // bydefault data json main ayega
                        success: function(data) {

                            var record = JSON.parse(data);
                            // console.log("message = "+record.msg);
                            // // alert(JSON.parse(data));
                            // console.log("jkldsjfs"+record.msg);

                            if (record.type === '2') {

                                if (record.msg !== 'updated successfully') {

                                    $('#resetpwd').val('');
                                    $('#confirmpwd').val('');
                                    $('#resetpwd').focus();

                                } else {

                                    alert('Password Updated Successfully');
                                    $('#exampleModal').modal('hide');
                                    window.location.reload();


                                }
                            } else if (record.type === '1') {

                                if (record.msg === 'Data Found') {
                                    $('.otpdiv').show();
                                    $('.fgtpwd').hide();


                                } else {

                                    $('#uname').val('');
                                    $('#emid').val('');
                                }
                            } else if (record.type === '3') {


                                if (record.msg === 'Otp matches') {
                                    $('.restdiv').show();
                                    $('.otpdiv').remove();
                                } else {
                                    alert('otp Not match');
                                    $('#otp').val('');
                                }
                            } else {
                                alert('Wrong input');
                                return false;
                            }

                        }


                    });
                }

            });

            //    function verifypwd()
            //    {
            //    var data = $("#verify").val();
            //    if(!empty(data))
            //    {
            //    var uname = document.getElementById('uname').value;
            //    var email = document.getElementById('emid').value;
            //    // alert(uname+"  "+email);
            //    $.ajax({

            //     type:'post',
            //     url:'rest_password.php',
            //     data:{
            //         username: uname,
            // 		email: email,
            //     },
            //     datatype:json,
            //     success:function()
            //     {
            //         alert('hu');
            //     }

            //    })


            //    }


            //    }
        </script>
        <!-- ===================================== CODE ENDS HERE BY PREETI ======================================== -->

    
</body>

</html>

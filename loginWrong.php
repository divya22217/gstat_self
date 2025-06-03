<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
include("db_inc1.php");//database connection
session_start();
?>

 <?php
        //validate and password encode

$username =htmlspecialchars($_POST['username']);
$password =htmlspecialchars($_POST['password']);

    // value entered is correct

 $SA=$_SESSION['salt'];
        
     $submitted_username = '';
	
    if(!empty($_POST))
    {
if ($_SESSION['vercode'] != $_POST['answer'] OR  empty($_POST['answer'])) 
  {  	
      echo "<center><font size='+2' COLOR='#000000'>value is incorrect/Empty, kindly try again</font></center>";
  }

 if ($_SESSION['vercode'] == $_POST['answer'] ) 
 {  
$ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
$unamevv=$_POST['username'];
$newTime = date("Y-m-d H:i:s");
$newdate = date("Y-m-d");
  
$atxr = $db->prepare("select * from log_attempt where user_id=? and date=? ");
        	$atxr->bindParam(1, $unamevv, PDO::PARAM_STR);
        	$atxr->bindParam(2, $newdate, PDO::PARAM_STR);
		$atxr->execute();

while ($row = $atxr->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	
	$username111=$row['user_id'];
	$lock111=$row['lock'];//N for no Y for Yes
	$failed111=$row['failed'];

}
 
if($username111 =='' and $failed111 =='')
{
	$unamevv=$_POST['username'];
$xxrr='1';
$xrr='N';
$atrr = $db->prepare("insert into log_attempt(user_id,ipaddress,datetime,failed,lock,date) values (?,?,?,?,?,?)");
        	$atrr->bindParam(1, $unamevv, PDO::PARAM_STR);
        	$atrr->bindParam(2, $ipaddress, PDO::PARAM_STR);
        	$atrr->bindParam(3, $newTime, PDO::PARAM_STR);
        	$atrr->bindParam(4, $xxrr, PDO::PARAM_STR);
        	$atrr->bindParam(5, $xrr, PDO::PARAM_STR);
		$atrr->bindParam(6, $newdate, PDO::PARAM_STR);
        	$atrr->execute();
}
if($lock111 == 'Y')
{
echo "<center><font size='10px' COLOR='#545454'>
You have entered an invalid USERNAME/PASSWORD Five Times. We have locked your account for security reasons.
</br>Contact TO Technical Support Team....</font></center>";

}
if($failed111 < 5  and $lock111='N')
{

          $query = "
            SELECT
                id,
                username,
                password,
                email,location,menuaccess_codeall,level_level,dept,schema_id
            FROM users
            WHERE
                username = :username
        ";
       
  
        $query_params = array(
            ':username' => $_POST['username']
        );
        
        try
        {
            
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
           
        }
        catch(PDOException $ex)
        {
             die("Failed to run query: " . $ex->getMessage());
        }
          $login_ok = false;
        $row = $stmt->fetch();
		if(!$row)
		{
		 echo "<center><font size='+2' COLOR='#000000'>USERNAME/PASSWORD is incorrect/Empty, kindly try again</font></center>";
$uname=$_POST['username'];
        	$ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
        	$yy= date("Y/m/d h:i:s");
        	$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status) values (?,?,?,?,?)");
        	$xx='USER NAME INCORRECT';
        	$x='Login Fail';
        	$at->bindParam(1, $uname, PDO::PARAM_STR);
        	$at->bindParam(2, $ipaddress, PDO::PARAM_STR);
        	$at->bindParam(3, $yy, PDO::PARAM_STR);
        	$at->bindParam(4, $xx, PDO::PARAM_STR);
        	$at->bindParam(5, $x, PDO::PARAM_STR);
        	$at->execute();
        	$unamevv=$_POST['username'];
$st = $db->prepare("select failed from log_attempt where user_id=? and date=?");
$st->bindParam(1, $unamevv, PDO::PARAM_STR);
$st->bindParam(2, $newdate, PDO::PARAM_STR);
$st->execute();
$account_filingc=$st->fetchColumn();

$fil_noc = $account_filingc = (int)$account_filingc+1;


$atrr = $db->prepare(" update log_attempt set failed=? ,ipaddress=?,datetime=? where user_id=? and date=? ");
        	$atrr->bindParam(1, $fil_noc, PDO::PARAM_STR);
        	$atrr->bindParam(2, $ipaddress, PDO::PARAM_STR);
        	$atrr->bindParam(3, $newTime, PDO::PARAM_STR);
			$atrr->bindParam(4, $unamevv, PDO::PARAM_STR);
        	$atrr->bindParam(5, $newdate, PDO::PARAM_STR);
        	$atrr->execute();
        	if($fil_noc == '5')
        	{
        		$llaa='Y';
        		$atrr1 = $db->prepare(" update log_attempt set lock=? where user_id=? and date=? ");
        		$atrr1->bindParam(1, $llaa, PDO::PARAM_STR);
        		$atrr1->bindParam(2, $unamevv, PDO::PARAM_STR);
        		$atrr1->bindParam(3, $newdate, PDO::PARAM_STR);
        		$atrr1->execute();
        	}
}

		}
        if($row)
        {
        	
          $check_password = $password ;
          
            $_SESSION['salt'];
          
           
            $saltkj="saltzz";
         $md_db=  hash('sha256',$row['password'].$_SESSION['salt'].$saltkj) ;
         //  $md_db=  md5($row['password'].$SA) ;
           
           if($check_password === $md_db)
            {
               
                $login_ok = true;
            }
      
        
        if($login_ok)
        {
        	$uname=$_POST['username'];
        	$ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
        	$yy= date("Y/m/d h:i:s");
        	$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status) values (?,?,?,?,?)");
        	$xx='login True';
        	$x='Login Successfully';
        	$at->bindParam(1, $uname, PDO::PARAM_STR);
        	$at->bindParam(2, $ipaddress, PDO::PARAM_STR);
        	$at->bindParam(3, $yy, PDO::PARAM_STR);
        	$at->bindParam(4, $xx, PDO::PARAM_STR);
        	$at->bindParam(5, $x, PDO::PARAM_STR);
        	$at->execute();


$yesaa='1';
$atrrs = $db->prepare(" update log_attempt set failed=? ,ipaddress=?,datetime=? where user_id=? and date=? ");
        	$atrrs->bindParam(1, $yesaa, PDO::PARAM_STR);
        	$atrrs->bindParam(2, $ipaddress, PDO::PARAM_STR);
        	$atrrs->bindParam(3, $newTime, PDO::PARAM_STR);
		$atrrs->bindParam(4, $unamevv, PDO::PARAM_STR);
        	$atrrs->bindParam(5, $newdate, PDO::PARAM_STR);
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
$_SESSION['level_level']=$row['level_level'];
//$_SESSION['dept']=$row['dept'];
$_SESSION['salt_user']=$row['salt'];
$_SESSION['schema_idccc']=$row['schema_id'];

$_SESSION['csrf222'] = md5(uniqid(rand(), TRUE));
$key122=$_SESSION['csrf222'];

$idid=$_SESSION['id'];

//new code...START

$display='Y';
/*
$aa=$_SESSION['location'];
$bb=$_SESSION['schema_idccc'];
echo $stc = ("select * from master_location where display = '$display'
		 and location_id='$aa' and schema_id='$bb'");

*/
$stc = $db->prepare("select * from mater_location_city where city_id=?");
//$stc->bindParam(1, $display, PDO::PARAM_STR);
//$stc->bindParam(1, $_SESSION['location'], PDO::PARAM_STR);
$stc->bindParam(1, $_SESSION['schema_idccc'], PDO::PARAM_STR);
$stc->execute();
while ($row = $stc->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	//$complex=$row['complex_id'];
	$schemas=$row['schema_name'];
	//$state_id=$row['state_id'];
}
 



$stlug = $db->prepare("select localadmin,main_id,level_level from users where id= ? ");
$stlug->bindParam(1, $_SESSION['id'], PDO::PARAM_STR);

$stlug->execute();
while ($row = $stlug->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$localadmin=$row['localadmin'];
	$main_id=$row['main_id'];
	$level_level=$row['level_level'];
}


$_SESSION['localadmin']=$localadmin;
$_SESSION['main_id']=$main_id;
$_SESSION['level_level']=$level_level;

/*
if($_SESSION['dept'] > 0)
{
$disppl='T';
$st = $db->prepare("select dept_name from master_dept where state_code=? and id=? and 
		display=? ");
$st->bindParam(1, $state_id, PDO::PARAM_STR);
$st->bindParam(2, $_SESSION['dept'], PDO::PARAM_STR);
$st->bindParam(3, $disppl, PDO::PARAM_STR);
$st->execute();
$dept_name=$st->fetchColumn();
$_SESSION['session_dept_name']=$dept_name;
}*/
//new code...END



$atrrsv = $db->prepare(" select accesspoint1 from users where id=? ");
$atrrsv->bindParam(1, $idid, PDO::PARAM_STR);
$atrrsv->execute();
while ($rowv = $atrrsv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$accesspoint1=$rowv['accesspoint1'];
}

if($accesspoint1 !='' and $accesspoint1 !='0' and $_SESSION['menuaccess_codeall']!='12' )
{
//	header("Location: index.php");
echo "<script type='text/javascript'>window.location.href = './scrutiny_allocation_action.php';</script>";
	die("Redirecting to: login.php");
}


            
        }
	//	echo $login_ok;

        
else
        {
            // Tell the user they failed
            print("</br><center><font size='+2' COLOR='#000000'>USERNAME/PASSWORD Is Incorrect.</font></center>");
               $submitted_username = htmlentities(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'));
			   	$uname=$_POST['username'];
$ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
$yy= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status) values (?,?,?,?,?)");
	  $xx='login Fail';
	  $x='Login Failed';
$at->bindParam(1, $uname, PDO::PARAM_STR);	  
$at->bindParam(2, $ipaddress, PDO::PARAM_STR);
$at->bindParam(3, $yy, PDO::PARAM_STR);
$at->bindParam(4, $xx, PDO::PARAM_STR);
$at->bindParam(5, $x, PDO::PARAM_STR);
$at->execute();

$st = $db->prepare("select failed from log_attempt where user_id=? and date=?");
$st->bindParam(1, $unamevv, PDO::PARAM_STR);
$st->bindParam(2, $newdate, PDO::PARAM_STR);
$st->execute();
$account_filingc=$st->fetchColumn();


$fil_nocv = $account_filingc = (int)$account_filingc+1;



$atrr = $db->prepare(" update log_attempt set failed=? ,ipaddress=?,datetime=? where user_id=? and date=? ");
        	$atrr->bindParam(1, $fil_nocv, PDO::PARAM_STR);
        	$atrr->bindParam(2, $ipaddress, PDO::PARAM_STR);
        	$atrr->bindParam(3, $newTime, PDO::PARAM_STR);
			$atrr->bindParam(4, $unamevv, PDO::PARAM_STR);
        	$atrr->bindParam(5, $newdate, PDO::PARAM_STR);
        	$atrr->execute();
        	
        	if($fil_nocv =='5')
        	{
        		$llaa='Y';
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
		if($_SESSION['user'] !='')
{
	header("Location: ./index.php");
	die("Redirecting to login.php");
}
    } // post loop

 //}// Math Close   
?>

<!DOCTYPE html>
<!-- saved from url=(0019)http://nclt.gov.in/ ]=$schemas;



$stlug = $db->prepare("select localadmin,main_id,level_level from users where id= ? ");
$stlug->bindParam(1, $_SESSION['id'], PDO::PARAM_STR);

$stlug->execute();
while ($row = $stlug->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$localadmin=$row['localadmin'];
	$main_id=$row['main_id'];
	$level_level=$row['level_level'];
}


$_SESSION['localadmin']=$localadmin;
$_SESSION['main_id']=$main_id;
$_SESSION['level_level']=$level_level;

/*
if($_SESSION['dept'] > 0)
{
$disppl='T';
$st = $db->prepare("select dept_name from master_dept where state_code=? and id=? and 
		display=? ");
$st->bindParam(1, $state_id, PDO::PARAM_STR);
$st->bindParam(2, $_SESSION['dept'], PDO::PARAM_STR);
$st->bindParam(3, $disppl, PDO::PARAM_STR);
$st->execute();
$dept_name=$st->fetchColumn();
$_SESSION['session_dept_name']=$dept_name;
}*/
//new code...END



$atrrsv = $db->prepare(" select accesspoint1 from users where id=? ");
$atrrsv->bindParam(1, $idid, PDO::PARAM_STR);
$atrrsv->execute();
while ($rowv = $atrrsv->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$accesspoint1=$rowv['accesspoint1'];
}

if($accesspoint1 !='' and $accesspoint1 !='0' and $_SESSION['menuaccess_codeall']!='12' )
{
//	header("Location: index.php");
echo "<script type='text/javascript'>window.location.href = './scrutiny_allocation_action.php';</script>";
	die("Redirecting to: login.php");
}


            
        }
	//	echo $login_ok;

        
else
        {
            // Tell the user they failed
            print("</br><center><font size='+2' COLOR='#000000'>USERNAME/PASSWORD Is Incorrect.</font></center>");
               $submitted_username = htmlentities(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'));
			   	$uname=$_POST['username'];
$ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
$yy= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status) values (?,?,?,?,?)");
	  $xx='login Fail';
	  $x='Login Failed';
$at->bindParam(1, $uname, PDO::PARAM_STR);	  
$at->bindParam(2, $ipaddress, PDO::PARAM_STR);
$at->bindParam(3, $yy, PDO::PARAM_STR);
$at->bindParam(4, $xx, PDO::PARAM_STR);
$at->bindParam(5, $x, PDO::PARAM_STR);
$at->execute();

$st = $db->prepare("select failed from log_attempt where user_id=? and date=?");
$st->bindParam(1, $unamevv, PDO::PARAM_STR);
$st->bindParam(2, $newdate, PDO::PARAM_STR);
$st->execute();
$account_filingc=$st->fetchColumn();


$fil_nocv = $account_filingc = (int)$account_filingc+1;



$atrr = $db->prepare(" update log_attempt set failed=? ,ipaddress=?,datetime=? where user_id=? and date=? ");
        	$atrr->bindParam(1, $fil_nocv, PDO::PARAM_STR);
        	$atrr->bindParam(2, $ipaddress, PDO::PARAM_STR);
        	$atrr->bindParam(3, $newTime, PDO::PARAM_STR);
			$atrr->bindParam(4, $unamevv, PDO::PARAM_STR);
        	$atrr->bindParam(5, $newdate, PDO::PARAM_STR);
        	$atrr->execute();
        	
        	if($fil_nocv =='5')
        	{
        		$llaa='Y';
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
		if($_SESSION['user'] !='')
{
	header("Location: ./index.php");
	die("Redirecting to login.php");
}
    } // post loop

 //}// Math Close   
?>

<!DOCTYPE html>
<!-- saved from url=(0019)http://nclt.gov.in/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link id="Link1" rel="shortcut icon" href="http://nclt.gov.in/image/favicon.ico">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>NCLT</title>

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
<script src="sha256.js" language="javascript" ></script>


<script type="text/javascript">
function openPopUp(url)
{
window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");	
}
function check_validation()
{
	if(document.Form.username.value=="")
	{
	alert("Please enter the Valid User Name");
	return false;
	}
if(document.Form.password.value=="")
{
alert("Please enter the Valid Password");
return false;
}
var saltk="saltzz";
var md5password = sha256_digest(sha256_digest(document.Form.password.value)+(document.Form.salt.value)+saltk);
document.Form.password.value = md5password;
document.Form.salt.value="";
}

</script>
<script> 
function loadmenuContent() 
{ 
   $("#Divmenu").load("menu.html"); 
} 

function loadleftContent() 
{ 
   $("#left_sidebar").load("left_menu_index.htm"); 
} 
function loadfooterContent(){
	 $("#footer_bar").load("footer.html"); 
	}
</script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  
    <![endif]-->
  </head>
  <body>
  
  	<div class="container-fluid top top-bar no-display-on-mobile">
		
	</div>
  
  	<section class="logo flag-bg no-display-on-mobile">
		<div class="container margin-bottom-15 padding-top-10">
			
				<img src="./APTEL_files/header1.png" >	
			
		</div>
	</section>
  	
	<header id="main-menu-container" class="">
		<nav class="navbar navbar-inverse main-menu navbar-inverse-bg" id="skip">
			<div class="container">
				<div class="navbar-header">
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand hidden-sm hidden-md hidden-lg logo_on_mobile" href="#"><img src="./APTEL_files/nclt2.jpg" class="img-responsive" style="width:110px;height:auto;"></a>
				</div>
				<div class="collapse navbar-collapse js-navbar-collapse" id="Divmenu">
			<ul id="top_menu">
			<ul class="nav navbar-nav">
				<li><a href="#" style="color:#fff; background:#337ab7;"><i class="fa fa-home" style=" font-size:17px;"></i></a></li>
				<li class="dropdown">
	        		<a href="#" class="dropdown-toggle" data-toggle="dropdown">MIS Report <b class="caret"></b></a>
		        	<ul class="dropdown-menu">
						<li><a href="#" onclick="javascript:openPopUp('./public/pending_report.php');" >Pending Report</a></li>
						<li><a href="#" onclick="javascript:openPopUp('./public/disposal_report.php');" >Disposal Report</a></li>
						
		        	</ul>
	      		</li>
	      		<li><a href="#">Cause List</a></li>
				<!--li class="dropdown">
	        		<a href="#" class="dropdown-toggle" data-toggle="dropdown">Case Status <b class="caret"></b></a>
		        	<ul class="dropdown-menu">
			         <li><a href="#">Test1</a></li>					 
			          <li><a href="#">Test</a></li>
		        	</ul>
	      		</li-->
				<li><a href="#" onclick="javascript:openPopUp('./public/case_status.php');" >Case Status</a></li>
				<li><a href="#" onclick="javascript:openPopUp('./public/order_report.php');">Order</a></li>
				<li><a href="#" onclick="javascript:openPopUp('./public/judgement_report.php');" >Judgement</a></li>
				
			</ul>
		
</ul></div>
			</div>
		</nav>	  
	</header>

     <style>

input[type=submit] {
    width: 35%;
    background-color: #4CAF50;
    color: #000000;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

input[type=submit]:hover {
    background-color: grey;
}

input[type=button] {
    width: 35%;
    background-color: #4CAF50;
    color: white;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
input[type=button]:hover {
    background-color: grey;
}
input[type=text] {
   /* width: 25%;*/
    background-color: silver;
    color: #000000;
    padding: 7px 7px;
    margin: 2px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

  


</style>
	
	<!--================= slider and Chairman message section ===================== -->
	  <div class="container margin-top-30">	
	 
		<div class="col-sm-3 ">
		<b>
		<?php 
		$aa=$_REQUEST['aa'];
		if($aa == '104')
		{echo "Invalid Access";}
		if($aa == '100')
		{echo "Invalid form submission";}
		
		
		?>
		</b>
		</div>		
				
	<form action="login.php" method="post"  name="Form">
	  <div class="col-sm-6 shadow">
					<h2 class="widget-title-1"><center>Login</h2></center>
					<div>
								<h3 class="widget-title-2"><center>User id</center></h3>
						
					</div>
					<div>
					
						<center><input type="text" size="35" na
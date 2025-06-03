<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");

include("../db_inc1.php");
include '../inheader.php';
date_default_timezone_set("Asia/Kolkata");
$server_date= date('d/m/Y'); //Returns IST 


$wday = mktime(0,0,0,date("$curMonth"),date("d")-5,date("Y"));
 $wday1= date("Y-m-d", $wday);

$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] :'';
$year=htmlspecialchars(date("Y"));
$msg_ip .= "User IP : ".$_SERVER["REMOTE_ADDR"]."\r\n"; //Sender's IP



// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	// If they are not, we redirect them to the login page.
	// Remember that this die statement is absolutely critical.  Without it,
	// people can view your members-only content without logging in.
	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}
else
 {
	
	
	
function remove_path($file, $path = UPLOAD_PATH) {
if(strpos($file, $path) !== FALSE) {
return substr($file, strlen($path));
}
}

$frm = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $frm;//csrf

$schemas=htmlspecialchars($_SESSION['schema_name']);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GSTAT | Dashboard</title>
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





    <script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
    <script src="../plugins/jQueryUI/jquery-ui.js"></script>
    <script src="../plugins/jQueryUI/date.js"></script>

    <script language="javascript">
    function change(id, newClass) {
        identity = document.getElementById(id);
        identity.className = newClass;

    }
    </script>
    <script>
    function submitForm() {
        with(document.frm) {






            action = "generate_cause_list.php";
            submit();

        }

    }

    function submitForm2() {
        with(document.frm) {
            if (next_list_date.value == "") {
                alert("Please Select listing Date ");
                next_list_date.focus();
                return false;
            }

            if (court.value == "") {
                alert("Please Select Court ");
                court.focus();
                return false;
            }

            var ldt = frm.next_list_date.value;
            var sdt = frm.server_date.value;

            var dt1 = parseInt(ldt.substring(0, 2), 10);
            var mon1 = parseInt(ldt.substring(3, 5), 10);
            var yr1 = parseInt(ldt.substring(6, 10), 10);
            mon1 = mon1 - 1;


            var fdt_date = new Date(yr1, mon1, dt1);

            var dt2 = parseInt(sdt.substring(0, 2), 10);
            var mon2 = parseInt(sdt.substring(3, 5), 10);
            var yr2 = parseInt(sdt.substring(6, 10), 10);
            mon2 = mon2 - 1;


            var serdt = new Date(yr2, mon2, dt2);

            submit();
        }
    }
    </script>





    <style>
    table,
    td,
    th {
        border: 1px solid grey;
    }



    th {
        background-color: #846312;
        color: #ffffff;
    }
    </style>
</head>

<body>

    <div class="wrapper">

        <div style="min-height: 946px;background-color: #ecf0f5;">
            <!-- Content Header (Page header) -->
            <section class="content">


                <table class="table">
                    <tr>
                        <?php
$msg =htmlentities($_REQUEST['msg']);
$msg = urldecode($msg);
if($msg !='')
{
?>
                    <tr>
                        <td colspan="6">
                            <center>
                                <font color='red' size='2'> <?php echo $msg;?></font>
                        </td>
                        </center>
                    </tr>
                    <?php
}
?>
                    <th valign="top" align="center" colspan="16">
                        <b>
                            <font face="Verdana" size="3">Save Cause List</font>
                        </b>
                    </th>
                    </tr>
                    <form name="frm" method="post" action="generate_cause_list_action.php">

                        <tr>
                            <td colspan="16"></td>
                        </tr>

                        <?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>
                        <tr>
                            <td colspan="16">
                                <font color="red">*</font>
                                <font face="Verdana" size="2">LISTING DATE:</font>

                                <input type="text" id="next_list_date" name="next_list_date" class="datepicker"
                                    readonly="readonly" onchange="submitForm()" size="8" autocomplete="off"
                                    maxlength="10" value="<?php print htmlspecialchars($next_list_date); ?>" />

                                <?php

 if($next_list_date!='')
   {
  list($d1,$m1,$Y1) =explode('/',$next_list_date);
$next_list_date1 =$Y1.'-'.$m1.'-'.$d1;
   }

?>

                                <font color="red">*</font>
                                <font face="Verdana" size="2"> Court:</font>
                                <?php  $court = isset($_REQUEST['court']) ? $_REQUEST['court'] :'';?>
                                <select name="court">
                                    <option value="">--Select Court --</option>
                                    <?php
									if($next_list_date!='')
									{
                                     $select_user_sql = $db->prepare("select distinct(a.court_no) as court_no,b.display_court_text from $schemas.bench as a 
										left join $schemas.court as b on b.court_no = a.court_no
										where from_list_date='$next_list_date1' order by court_no asc ");
                                   
									$select_user_sql->execute();
                                    while ($select_user_sql_result = $select_user_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                    {
                                        $court1=htmlspecialchars($select_user_sql_result['court_no']);
										$court_name = htmlspecialchars($select_user_sql_result['display_court_text']);
                                        if($court == $court1)
                                        {
                                            print "<option value=".htmlspecialchars($select_user_sql_result['court_no'])." selected>".htmlspecialchars(ucwords(strtoupper($court_name)))."</option>";
                                        }
                                        else
                                        {
                                            print "<option value=".htmlspecialchars($select_user_sql_result['court_no']).">".htmlspecialchars(ucwords(strtoupper($court_name)))."</option>";
                                        }
                                    }
									}
                                    ?>

                                </select>

                            </td>
                        </tr>


                        <tr>
                            <td>
                                <input type="hidden" name="server_date"
                                    value="<?php echo htmlspecialchars($server_date);?>">

                                <input id="submit1" type="button" name="submit1" class="btn btn-primary" value="Submit"
                                    onClick="submitForm2();" />
                                </br>
                            </td>
                        </tr>

                </table>
        </div>

        </section>
        <?php include '../footer.php'; ?>


    </div>
    <!-- Bootstrap 3.3.7 -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Sparkline -->
    <script src="../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <!-- jvectormap  -->
    <script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll -->
    <script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script language="javascript">
    hs.graphicsDir = '../includes/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.wrapperClassName = 'draggable-header';
    </script>


</body>

</html>

<?php
//count loop End
?>
<?php } ?>
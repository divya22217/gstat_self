<?php
 /*   ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */ 
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
//require_once('../SrcCauselist/Causelist.php');
include("../db_inc1.php");
include("../db_inc2.php");
$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] :'';
$year=htmlspecialchars(date("Y"));
$msg_ip .= "User IP : ".$_SERVER["REMOTE_ADDR"]."\r\n"; //Sender's IP
// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']==''){
	// If they are not, we redirect them to the login page.
	// Remember that this die statement is absolutely critical.  Without it,
	// people can view your members-only content without logging in.
	die("Redirecting to login.php");
}
if(($_SESSION['user'])==''){
	echo "you Can't access this page";
}else{	
function remove_path($file, $path = UPLOAD_PATH) {
    if(strpos($file, $path) !== FALSE) {
    return substr($file, strlen($path));
    }
}
$frm = md5( uniqid('auth', true) );
/*** set the session form token ***/
$_SESSION['form_token'] = $frm;//csrf
$schemas=htmlspecialchars($_SESSION['schema_name']);


$schema=$_SESSION['schema_idccc'];
$users_query = $db->prepare("select count(*) as count from menu_r");
$users_query->execute();
$count = $users_query->fetchColumn();


include '../inheader.php';
include '../insidebar.php';

?>

<html ng-app="my_app">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>NGT | Dashboard</title>
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

	    <!--JQuery DataTables-->
    <script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="../master1/js/bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="../master1/js/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../master1/js/dataTables/dataTables.bootstrap.min.js"></script>
    <!--/JQuery DataTables -->

    <!--Angualrjs -->
    <script type="text/javascript" src="../master1/js/angularjs/angular.min.js"></script>
    <script type="text/javascript" src="../master1/js/angularjs/angular-datatables.min.js"></script>
    <script type="text/javascript" src="../master1/js/angularjs/menu_list.js"></script>
	<style>
		table, td, th {
			border: 1px solid #1d99d4;
		}
		th {
			background-color: #074c62;
			color: white;
		}
		a.disabled {
  pointer-events: none;
  cursor: default;
}
	</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
	
	<div class="content-wrapper" style="min-height: 946px;">
		<!-- Content Header (Page header) -->
	<section class="content">
    <div class="margin-top-30">
        <div ng-controller="menus" data-ng-init="menuInformation()">           
             <div class="col-md-12">
                <div class="row add_panel"><h4><b> Menu List (Total - <span color="red"><?php echo $count;?></span>)</b></h4></div>
            </div>
            <div class="col-md-12">
                <div ng-if="success_msg" class="success_pop alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong> {{success_msg}} </strong>
                </div>
                <div class="table-responsive">
                    <table datatable="ng" id="examples" class="table table-striped table-bordered" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="menu in menu_list">
                                <td>{{$index + 1}}</td>
                                <td>{{menu.menu_name}}</td>
                                <td>
                                    <a href="javascript:void(0);" ng-click="EditModal(menu);" title='Edit' id="menu.menu_id">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a> &nbsp;&nbsp;&nbsp;
									
                                     <a href="javascript:void(0);" ng-if="menu.display == true" title='Delete' ng-click="DeleteModal(menu)" class="delete">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
									<a href="javascript:void(0);" ng-if="menu.display == false" title='Restore' ng-click="RestoreModal(menu)" class="restore">
                                        <i class="glyphicon glyphicon-repeat"></i>
                                    </a> 	
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>



            <!-- Form modal -->
            <div id="form_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="icon-paragraph-justify2"></i>
                                {{form_name}}</h4>
                        </div>
                        <!-- Form inside modal -->
						<form method="post" ng-submit="MenuAddUpdate(menus_form);" id="menus_form_id">
                            <div class="modal-body with-padding">
                                <input type="hidden" name="action_text" id="action_text"
                                    ng-model="menus_form.action" value="update">
                                <input type="hidden" name="id" ng-model="menus_form.id" id="id">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Menu Name :</label>
                                            <input type="text" name="short_name" ng-model="menus_form.menu_name"
                                             id="menu_name" required="required" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                <button type="submit" name="form_data" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /form modal -->
            
        </div>
    </div>

</section>
    <script>

	function isNumberKey(evt){
	    var charCode = (evt.which) ? evt.which : event.keyCode
	    if (charCode > 31 && (charCode < 48 || charCode > 57))
	        return false;
	    return true;
	}

    </script>

	 </div>

</section>
<?php include '../bfooter.php'; ?>

<?php } ?>

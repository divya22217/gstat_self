<?php
 /*  ini_set('display_errors', 1);
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
$users_query = $db->prepare("select count(id) from users_cis where schema_id='$schema'");
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
    <script type="text/javascript" src="../master1/js/angularjs/user_list.js"></script>
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
        <div ng-controller="users" data-ng-init="usersInformation()">           
             <div class="col-md-12">
                <div class="row add_panel"><h4><b> User List (Total - <span color="red"><?php echo $count;?></span>)</b></h4></div>
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
                                <th>User Name</th>
                                <!--<th>Short Name</th>-->
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Menu Access</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="user in users_list">
                                <td>{{$index + 1}}</td>
                                <td>{{user.username}}</td>
                               <!--<td>{{user.short_name}}</td>-->
                                <td>{{user.fname}} {{user.lname}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.mobile_no}}</td>
                                <td>
                                    <a href="javascript:void(0);" ng-click="menu_access(user.id);">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript:void(0);" ng-click="EditModal(user);" title='Edit' id="user.phone_no">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a> &nbsp;&nbsp;&nbsp;
									
                                     <a href="javascript:void(0);" ng-if="user.status == '1'" title='Delete' ng-click="DeleteModal(user)" class="delete">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
									<a href="javascript:void(0);" ng-if="user.status == '0'" title='Restore' ng-click="RestoreModal(user)" class="restore">
                                        <i class="glyphicon glyphicon-repeat"></i>
                                    </a> 	
								    &nbsp;&nbsp;&nbsp;
                                    <a href="javascript:void(0);" ng-click="EditPasswordModal(user)" title='Change Password'>
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>



            <!---- Menu Model --->

            <div id="menu_form_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="icon-paragraph-justify2"></i>
                                Menu Access</h4>


                                <h2 id="update_div_msg" style="color:green"> </h2>
                        </div>
                        <!-- Form inside modal -->
                        <form method="post" id="menu_users_form_id">

                            <!-- <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                <button type="submit" onclick="fn_menu_access_data()" class="btn btn-primary">Update
                                    Menu</button>
                            </div> -->
                            <div class="modal-body with-padding">
                                <input type="hidden" name="user_id" id="user_id" value="">
                                <div id="menu_access_id"> </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                            <button type="submit" onclick="fn_menu_access_data()" class="btn btn-primary">Save</button>
                        </div> 
                        </form>
                    </div>
                </div>
            </div>


            <!-- End Menu Access -->



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
                        <form method="post" ng-submit="UserAddUpdate(users_form);" id="users_form_id">
                            <div class="modal-body with-padding">
                                <input type="hidden" name="action_text" id="action_text"
                                    ng-model="users_form.action" value="update">
                                <input type="hidden" name="id" ng-model="users_form.id" id="id">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>User Name :</label>
                                            <input type="text" name="short_name" ng-model="users_form.username"
                                             id="username" required="required" class="form-control">
                                        </div>
                                        <div class="col-sm-6 org_name_div_id">
                                            <label>First Name :</label>
                                            <input type="text" name="fname" ng-model="users_form.fname" id="fname"
                                                required="required" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Last Name :</label>
                                            <input type="text" name="lname" ng-model="users_form.lname" id="lname"
                                                class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Email Id :</label>
                                            <input type="email" name="email" ng-model="users_form.email" id="email"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                       <!---<div class="col-sm-6">
                                            <label>Mobile No:</label>
                                            <input type="tel" name="mobile_no" ng-model="users_form.mobile_no"
                                                id="mobile_no" required="required" class="form-control"  maxlength="10" value="" onkeypress="return isNumberKey(event)" >
                                        </div>--->
                                        <div class="col-sm-6">
                                            <label> Gender : </label>
												<select name="gender" ng-model="users_form.gender" id="gender"
                                                class="form-control">
													<option value=''>Select</option>
													<option value='male'>Male</option>
													<option value='female'>Female</option>
													<option value='transgender'>transgender</option>
												</select>
                                        </div>
										<div class="col-sm-6">
                                            <label>Mobile :</label>
                                            <input type="text" name="mobile_no" ng-model="users_form.mobile_no" id="mobile_no"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label> Address :</label>
                                            <input type="text" name="address" ng-model="users_form.address" id="address"
                                                class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Country:</label>
                                            <input type="text" name="country" ng-model="users_form.country" id="country"
                                                required="required" class="form-control">
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
            <!-- --------password change -->
                <!-- Form modal -->
            <div id="form_Password_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="icon-paragraph-justify2"></i>
                                {{form_name}}</h4>
                        </div>
                        <!-- Form inside modal -->
                        <form method="post" ng-submit="ChangepasswordModal(users_form);" id="users_form_id">
                            <div class="modal-body with-padding">
                                <input type="hidden" name="action_password_text" id="action_password_text"   ng-model="users_form.action" value="password_update">
                                <input type="hidden" name="id" ng-model="users_form.id" id="id">  
                                  <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>User Name :</label>
                                            <input type="text" name="username" ng-model="users_form.username"   id="username" required="required" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Password :</label>
                                            <input type="text" name="new_password"  ng-model="users_form.new_password"
                                                id="password" required="required" class="form-control">
                                        </div>
                                        <div class="col-sm-6 org_name_div_id">
                                            <label>Confirm Password :</label>
                                            <input type="text" name="con_password" ng-model="users_form.con_password"  id="con_password"
                                                required="required" class="form-control">
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
            <!-- --------End password -->
            
            
            
            
            
            
            
            
            
            
            
            
          <!-----Add User------->
            <div id="form_Adduser_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="icon-paragraph-justify2"></i>
                                {{form_name}}</h4>
                        </div>
                          <!-- Form inside modal -->
                        <form method="post" ng-submit="AdduserModal(users_form);" id="users_form_id">
                            <div class="modal-body with-padding">
                                <input type="hidden" name="action_adduser_text" id="action_adduser_text"   ng-model="users_form.action" value="insert">
                                <input type="hidden" name="id" ng-model="users_form.id" id="id">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Short Name :</label>
                                            <input type="text" name="short_name" ng-model="users_form.short_name"
                                                id="short_name" required="required" class="form-control">
                                        </div>
                                        <div class="col-sm-6 org_name_div_id">
                                            <label>First Name :</label>
                                            <input type="text" name="fname" ng-model="users_form.fname" id="fname"
                                                required="required" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Last Name :</label>
                                            <input type="text" name="lname" ng-model="users_form.lname" id="lname"
                                                class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Email Id :</label>
                                            <input type="email" name="email" ng-model="users_form.email" id="email"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Mobile No:</label>
                                            <input type="tel" name="mobile_no" ng-model="users_form.mobile_no"
                                                id="mobile_no" required="required" class="form-control"  maxlength="10" value="" onkeypress="return isNumberKey(event)" >
                                        </div>
                                        <div class="col-sm-6">
                                            <label> Gender : </label>
                                            <input type="text" name="gender" ng-model="users_form.gender" id="gender"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label> Address :</label>
                                            <input type="text" name="address" ng-model="users_form.address" id="address"
                                                class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Country:</label>
                                            <input type="text" name="country" ng-model="users_form.country" id="country"
                                                required="required" class="form-control">
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
            <!-- --------End Add user -->
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

	
    function fn_menu_access_data() {
        var user_id = $("#menu_users_form_id #user_id").val();
        var menu_arrary = {};
        $('input:checkbox[name="menu_name[]"]:checked').each(function(i) {
            var menu_id = $(this).val();
            var sun_menu_arr = {};
            $('input:checkbox[name="sub_menu_name_' + menu_id + '[]"]:checked').each(function(i) {
                var sub_menu_id = $(this).val();
                sun_menu_arr[i] = sub_menu_id;
            });
            menu_arrary[menu_id] = sun_menu_arr;
        });
        var sub_menu_arrary = {};
            var tem_arr = {};
        $('input:checkbox[name="sub_sub_menu_name_2[]"]:checked').each(function(i) {
            var sub_sub_menu_id = $(this).val();
           
                tem_arr[i] = sub_sub_menu_id;
        });
        sub_menu_arrary[2] = tem_arr;
        $.ajax({
            type: "POST",
            url: "user_ajax.php",
            data: {
                action: 'update_menu',
                menu: menu_arrary,
                sub_menu: sub_menu_arrary,
                user_id: user_id,
            },
            success: function(data) {
               $("#update_div_msg").html(data);
               alert(data);
               location.reload(true);	
            },
            error: function(textStatus, errorThrown) {
                alert("error");
            }
        });
    }
    </script>
<?php
  if($next_list_date){
?>
 <tr><font size="4">
<input type="button" value="Print As Draft" onclick="window.open('daily_order_report_print.php?print_details=<?php  print_r($print_details); ?>')"/>
<input type="submit" name="submit1" value="Finalise" class="button" onClick="return submitForm2();">
</font>
</tr>
<?php
  }
?>
	 </div>

</section>
<?php include '../bfooter.php'; ?>

<?php } ?>

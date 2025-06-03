<?php 
date_default_timezone_set("Asia/Kolkata");
include("./db_inc1.php");
session_start();


$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

if(!is_numeric($userid)){
  echo "invalid User ID";
  die();
}

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
header("Location: ./login.php");
die();
}

/*
if($main_id =='9999' and $localadmin =='0')
{
		if($_SESSION['menuaccess_codeall'] !='2')
			{
				echo "You Are Not Access This Page......";
				die();
			}

}*/

setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	
	
	
// This code not use next time .......	Schema session create Hear....
	
	
	$sessionUserType=htmlspecialchars($_SESSION['id']);


$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";


$link_scrutiny_idaccess='1';

?>
<!-- Left side column. contains the logo and sidebar -->
<?php 
//if($main_id =='9999' and $localadmin !='0')
//{
?>

<style>
.topheader {
	background: #f7e0bb;
	text-align: center;
}
.topheader img {
	margin: auto;
	width: 30%;
}
.skin-blue .main-header .navbar {
  background: #5e3e15;
  background: -moz-linear-gradient(90deg, #846313 0%, #141d8b 100%);
      background: -webkit-linear-gradient(90deg, #846313 0%, #141d8b 100%);
      background: linear-gradient(90deg, #846313 0%, #141d8b 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#5e3e15",endColorstr="#0d0600",GradientType=1);
  margin-left: 0;
  min-height: 38px;
}
.skin-blue .main-header .logo {
    background-color: #7d7d7d;
}
.skin-blue .main-header li.user-header {
  background: #5e3e15;
  background: -moz-linear-gradient(90deg, #5e3e15 0%, #0d0600 100%);
  background: -webkit-linear-gradient(90deg, #5e3e15 0%, #0d0600 100%);
  background: linear-gradient(90deg, #5e3e15 0%, #0d0600 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#5e3e15",endColorstr="#0d0600",GradientType=1);
}
.skin-blue .main-header .navbar .nav>li>a {
    color: #f7e0bb;
}
.topmenu>li>a {
    padding: 9px 30px !important;
    border-left: 1px solid rgba(255, 255, 255, 0.10);
}
.topmenu li li a:hover {
    background-color: rgba(255, 255, 255, 0.05);
}
.topmenu .hassubmenu:hover .submenu, .topmenu .hassubsubmenu:hover .subsubmenu {
    display: block;
}
.topmenu li {
    position: relative
}
.topmenu .submenu {
  position: absolute;
  top: 38px;
  left: 0;
  margin: 0;
  padding: 0;
  list-style: none;
  background: #6f4715;
  box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.3);
  display: none;
}
.topmenu .submenu li a {
    color: #fff;
    display: block;
    padding: 9px 30px;
    width: 200px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}
.topmenu .subsubmenu {
    position: absolute;
    top: 0px;
    left: 200px;
    margin: 0;
    padding: 0;
    list-style: none;
    background: #5a3910;
    box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.3);
    display: none;
}
.main-footer {
    margin-left: 0 !important;
}
.content-wrapper {
	margin-left: 0;
}
@media (min-width: 768px) {
.navbar-nav>li>a {
    padding-top: 9px;
    padding-bottom: 9px;
}
}
</style>      

 <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
    
      <!-- Sidebar toggle button-->
      <!-- Navbar Right Menu -->

      <ul class="nav navbar-nav topmenu">
       <li><a href="../index.php">Home </a></li>
		  <?php
			if($localadmin == '1' || $localadmin == '2'){?>
				<li class="hassubmenu"><a href="">User</a>
					<ul class="submenu">
					  <li><a href="../reg/create_user_r.php">Create User</a></li>
					  <?php if($localadmin == '1'){ ?>
					  <li><a href="../reg/user_list.php">View User</a></li>
					  <?php } ?>
					</ul>
				 </li>
			<?php } 
			if($localadmin == '2'){ ?>
				<li class="hassubmenu"><a href="">Menu</a>
					<ul class="submenu">
						<li><a href="create_menu_r.php">Create Menu</a></li>
						<li><a href="create_submenu_r.php">Create Submenu</a></li>
						<li><a href="menu_list.php">View Menu</a></li>
						<li><a href="submenu_list.php">View Submenu</a></li>
					</ul>
				</li>
			<?php }
			if($localadmin == '1'){ ?>
				<li class="hassubmenu"><a href="">Menu</a>
					<ul class="submenu">
						<li><a href="../reg/assign_user_menu.php">Assign Menu</a></li>
					</ul>
				</li>
			<?php } 
			$topmenu_sql=$db->prepare("select distinct P.menu_id,P.menu_name, P.parent_menu, P.priority  from public.menu_r P, public.link_r L where P.menu_id=L.menu_id and L.user_id=? and P.display='TRUE' order by P.priority");
      $topmenu_sql->bindParam(1,$userid, PDO::PARAM_INT);
			$topmenu_sql->execute();
			while ($topmenu_sql_result = $topmenu_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{

			//$topmenu_sql->bindParam(1, $status, PDO::PARAM_STR);
			//$topmenu_sql->bindParam(2, $purpose_code, PDO::PARAM_STR);
			if($topmenu_sql_result['parent_menu'] == false){

					?>
					
				<li><a href="./<?php echo htmlspecialchars($topmenu_sql_result['menu_link']); ?>"><?php echo htmlspecialchars($topmenu_sql_result['menu_name']); ?></a></li>
			<?php }else if($topmenu_sql_result['parent_menu'] == true){
			$menu_id = htmlspecialchars($topmenu_sql_result['menu_id']);
      if(!is_numeric( $menu_id)){
        echo "Invalid Menu ID";
        die();
       }
			//print_r($menu_id);
				?>
				<li class="hassubmenu"><a href=""><?php echo htmlspecialchars($topmenu_sql_result['menu_name'])?></a>
					<ul class="submenu">
						 <?php 
					$submenu_sql=$db->prepare("select * from public.submenu_r S, public.link_r L where S.menu_id = ? and S.submenu_id=L.submenu_id and L.user_id=? and S.display='TRUE' order by S.priority");
					//print_r($submenu_sql);
          $submenu_sql->bindParam(1, $menu_id, PDO::PARAM_INT);
          $submenu_sql->bindParam(2, $userid, PDO::PARAM_INT);
					$submenu_sql->execute();
					while ($submenu_sql_result = $submenu_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
					{
							  ?>
								<li><a href="<?php $_SERVER['DOCUMENT_ROOT'].'gstat';?>/gstat/<?php echo htmlspecialchars($submenu_sql_result['submenu_link']); ?>"><?php echo htmlspecialchars($submenu_sql_result['submenu_name']); ?></a></li>			
					<?php } ?>
					</ul>
				</li>	
			<?php }} ?>
      </ul>

      <div class="navbar-custom-menu">
     
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->

          <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              
            
              <?php 
              
              ?>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header"></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <i class="fa fa-list-alt"></i>
                      </div>
                      <h4>
                      
                        <small><i></i> </small>
                      </h4>
                      <p></p>
                    </a>
                  </li>
                  <!-- end message -->
                 
                  </li>
                 
                </ul>
              </li>
              
              
              
              <li class="footer"><a href="#"></a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i></i>
            
              
              </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header"></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 
                    </a>
                  </li>
                                 
                </ul>
              </li>
              <li class="footer"><a href="#"></a></li>
            </ul>
          </li>
          
          
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i></i>
             
            </a>
            <ul class="dropdown-menu">
              <li class="header"></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                       
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only"></span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                 
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        
                        <small class="pull-right"></small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar"
                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only"></span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#"></a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-user-circle" aria-hidden="true"></i>

              <span class="hidden-xs">
<?php 
  echo htmlspecialchars(strtoupper($_SESSION['user']));
?>
</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
            <!--     <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
      <i class="fa fa-user-circle" aria-hidden="true"></i>
                <p>
                
                </p>
              </li>
            
              <!-- Menu Footer-->
              <li class="user-footer">
                
                <div class="pull-right">
                  <a href="../logout1.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>

    </nav>
  
  <?php 
}
  ?>

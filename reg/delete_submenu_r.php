<?php
include("../db_inc1.php");
if($_GET['del'] && $_GET['uid'])
   {
	//print_r($_GET);die('hmmhmm');
	
	 $id = $_GET['del'];
	 
	 $uid = $_GET['uid'];
	 
     $delete_submenu_sql = $db->prepare("delete from public.link_r where submenu_id=? and user_id=?");

     $delete_submenu_sql->bindParam(1, $id, PDO::PARAM_STR);
	 
	 $delete_submenu_sql->bindParam(2, $uid, PDO::PARAM_STR);

     $delete_submenu_sql->execute();
	 
	 header("location:modify_menu_r.php?id=$uid");

   }    
?>
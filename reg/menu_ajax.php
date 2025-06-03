<?php
include("../db_inc1.php");//database connection
$schema=$_SESSION['schema_idccc'];
ini_set('display_errors', 1);
if ($_REQUEST['action'] == 'menu_list') {
	$query  = "select menu_id,menu_name,display from menu_r order by display desc";
    $sth = $db->prepare($query);
    $sth->execute();
	$menu_list = $sth->fetchAll();
	echo json_encode($menu_list);
    
}


else if ($_REQUEST['action'] == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    //error_reporting(E_ALL);
    $data = json_decode(file_get_contents("php://input")); // form fields get 
    $menu_id = $data->menu_id;
    $menu_name = $data->menu_name;
   
   // $short_name=pg_escape_string($data->short_name);
 //  echo $org_id;die;
    $msg = 'Something Error. Please try again.';
    if ($menu_id != "") {
        $query = "UPDATE menu_r SET menu_name= ? WHERE menu_id =?";
        try {
            $update = $db->prepare($query);
			$update->bindParam(1, $menu_name, PDO::PARAM_STR);
			$update->bindParam(2, $menu_id, PDO::PARAM_STR);
            if ($update->execute() == '1') {
                $msg = "Successfully Updated Your Record";
            }
        } catch (PDOException $ex) {
            $msg = '>Failed to run query' . $ex->getMessage();
        }
    }
    echo $msg;
}


else if ($_REQUEST['action'] == 'delete') {
    $id = json_decode(file_get_contents("php://input"));
    $id = $id;
	$display = 0;
    $msg = 'Something Error. Please try again.';
    if ($id != "") {
        $query = "update menu_r set display = ?  WHERE menu_id =?";
        try {
            $delete = $db->prepare($query);
			$delete->bindParam(1, $display, PDO::PARAM_STR);
			$delete->bindParam(2, $id, PDO::PARAM_STR);
            if ($delete->execute() == '1') {
                $msg = "Successfully Deleted Your Record";
            }
        } catch (PDOException $ex) {
            $msg = '>Failed to run query' . $ex->getMessage();
        }
    }
    echo $msg;
}

else if ($_REQUEST['action'] == 'restore') {
    $id = json_decode(file_get_contents("php://input"));
    $id = $id;
	$display = 1;
    $msg = 'Something Error. Please try again.';
   if ($id != "") {
        $query = "update menu_r set display = ?  WHERE menu_id =?";
        try {
            $delete = $db->prepare($query);
			$delete->bindParam(1, $display, PDO::PARAM_STR);
			$delete->bindParam(2, $id, PDO::PARAM_STR);
            if ($delete->execute() == '1') {
                $msg = "Successfully Restored Your Record";
            }
        } catch (PDOException $ex) {
            $msg = '>Failed to run query' . $ex->getMessage();
        }
    }
    echo $msg;
}

   else {
    echo 'Something Error';
}


?>
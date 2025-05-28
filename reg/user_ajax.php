<?php
include("../db_inc1.php");//database connection
$schema=$_SESSION['schema_idccc'];
ini_set('display_errors', 1);
//if ($_REQUEST['action'] == 'organization_list') {
//    $users_list = array();
//    $users_query = $db->prepare("select * from users_cis  where schema_id='$schema' order by username asc ");
//    $users_query->execute();
//    while ($row = $users_query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
//        $users_list[] = $row;
//    }
//    echo json_encode($users_list);
//}

if ($_REQUEST['action'] == 'organization_list') {
    $users_list = array();

   // echo "select * from users_cis  where schema_id='$schema' order by username asc ";
    $schema12 = $schema;
    if($schema == '7') {
        $schema12 = '1,7';
    }
    $users_query = $db->prepare("select * from users_cis  where schema_id in($schema12) order by username asc ");
    $users_query->execute();
    while ($row = $users_query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $users_list[] = $row;
    }
    echo json_encode($users_list);
}

else if ($_REQUEST['action'] == 'password_update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input")); // form fields get
    $org_id = $data->id;
    $newPassword = $data->new_password;
    $confirm_pass = $data->con_password;
    $msg = 'Confirm Password Not Match.';
    if($newPassword == $confirm_pass){
        $password = hash('sha256',$newPassword);
        if ($org_id != "") {
            $query = "UPDATE users_cis SET password= '$password' WHERE id ='$org_id'";
            try {
                $hscquery = $db->prepare($query);
                if ($hscquery->execute() == '1') {
                    $msg = "Successfully Updated Your Record";
                }
            } catch (PDOException $ex) {
                $msg = '>Failed to run query' . $ex->getMessage();
            }
        }
    }
    echo $msg;
}



else if ($_REQUEST['action'] == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    //error_reporting(E_ALL);
    $data = json_decode(file_get_contents("php://input")); // form fields get 
    $org_id = $data->id;
    $fname = $data->fname;
    $lname = $data->lname;
  //  $mobile_no = pg_escape_string($data->mobile_no);
    $email = $data->email;
    $gender = $data->gender;
    $address = $data->address;
   $county=$data->country;
   $username=$data->username;
   $mobile_no=$data->mobile_no;
   $court=$data->court;
   
   // $short_name=pg_escape_string($data->short_name);
 //  echo $org_id;die;
    $msg = 'Something Error. Please try again.';
    if ($org_id != "") {
        $query = "UPDATE users_cis SET fname= '$fname', lname='$lname',gender='$gender',email='$email',address='$address',country='$county',username='$username', mobile_no='$mobile_no', court='$court' WHERE id ='$org_id'";
        try {
            $hscquery = $db->prepare($query);
            if ($hscquery->execute() == '1') {
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
    $msg = 'Something Error. Please try again.';
    if ($id != "") {
        $query = "update users_cis set status=0  WHERE id ='$id'";
        try {
            $hscquery = $db->prepare($query);
            if ($hscquery->execute() == '1') {
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
    $msg = 'Something Error. Please try again.';
    if ($id != "") {
        $query = "update users_cis set status=1  WHERE id ='$id'";
        try {
            $hscquery = $db->prepare($query);
            if ($hscquery->execute() == '1') {
                $msg = "Successfully Restored Your Record";
            }
        } catch (PDOException $ex) {
            $msg = '>Failed to run query' . $ex->getMessage();
        }
    }
    echo $msg;
}


else if ($_REQUEST['action'] == 'insert' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    
    $short_name= pg_escape_string($data->short_name);
    $fname= pg_escape_string($data->fname);
    $lname= pg_escape_string($data->lname);
    $email= pg_escape_string($data->email);
    $mobile_no= pg_escape_string($data->mobile_no);
    $gender= pg_escape_string($data->gender);
    $address = pg_escape_string($data->address);
    $country= pg_escape_string($data->country);
    $username=ucfirst($fname);
    $default='test@123';
    $password=hash('sha256',$default);
    
    $msg = 'Something Error. Please try again.';
    if ($org_id == "") {
        $query = "INSERT INTO users_cis (username,password,email,location,fname,lname,country,address,gender,short_name,mobile_no,menuaccess_codeall)  values
 ('$username','$password','$email', '100', '$fname', '$lname','$country','$address','$gender','$short_name', '$mobile_no',6)";
       
        try {
            $hscquery = $db->prepare($query);
            if ($hscquery->execute() == '1') {
                $msg = "Successfully Inserted Your Record";
            }
        } catch (PDOException $ex) {
            $msg = '>Failed to run query' . $ex->getMessage();
        }
    }
    echo $msg;
} 


else if ($_REQUEST['action'] == 'menu_access') {
$user_id = $_REQUEST['user_id'];
$users_query = $db->prepare("select string_agg(cast(menu_id as varchar),',') as menu_ids from link_r where user_id = ? ");
$users_query->bindParam(1, $user_id, PDO::PARAM_INT);
$users_query->execute();
$menu_arra =  $users_query->fetchColumn();
$users_query = $db->prepare("select string_agg(cast(submenu_id as varchar),',') as submenu_ids from link_r where user_id = ? ");
$users_query->bindParam(1, $user_id, PDO::PARAM_INT);
$users_query->execute();
$sub_sub_menu_data11 =  $users_query->fetchColumn();
$menu_arra = explode(',',$menu_arra);
$sub_sub_menu_data11 = explode(',',$sub_sub_menu_data11);

    $status = TRUE;
    $stng1 = $db->prepare("select menu_name as name,menu_id as id from menu_r where display= ?  order by menu_name ASC");
    $stng1->bindParam(1, $status, PDO::PARAM_INT);
    $stng1->execute();
   $menu_data =  $stng1->fetchAll();
  // echo "<pre>"; print_r($menu_data);
    ?>
<table class="table"> 
<tr>
<th>Sr. No</th>
<th>Menu</th>
<th>Action</th>
</tr>
<?php if(!empty($menu_data) && is_array($menu_data)) { 
    $i= 1;
    foreach($menu_data as $value) { 
      $checked = '';
      if(is_array($menu_arra)){
        if(in_array($value['id'],$menu_arra)) { 
            $checked = "checked='checked'";
        }
      }
    ?>
<tr>
<td><?php echo $i;?>.</td>
<td><?php echo $value['name'];?></td>
<td><input type="checkbox" name="menu_name[]" <?php echo $checked; ?> id="menu_name<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" > </td>
</tr>
<tr>
<td colspan="3">
<table class="table">
<?php 
$menu_id = $value['id'];
$display = TRUE;
$stng1 = $db->prepare("select submenu_id as id,submenu_name as name from submenu_r where display = ? and menu_id = ?  order by submenu_name ASC");
$stng1->bindParam(1, $display, PDO::PARAM_INT);
$stng1->bindParam(2, $menu_id, PDO::PARAM_INT);
$stng1->execute();
$sub_menu_data =  $stng1->fetchAll();
if(!empty($sub_menu_data) && is_array($sub_menu_data)) { 
    $ii= 1;
    foreach($sub_menu_data as $value_sub) { 
        $sub_checked = '';
         if((in_array($menu_id,$menu_arra)) && in_array($value_sub['id'],$sub_sub_menu_data11)) { 
                $sub_checked = "checked='checked'";
            } 
?>
<tr style='background-color: darkgrey;'>
<td><?php //echo $ii;?>. </td>
<td><?php echo $value_sub['name'];?></td>
<td><input type="checkbox" <?php echo $sub_checked; ?> name="sub_menu_name_<?php echo $value['id'];?>[]" id="sub_menu_name<?php echo $value_sub['id'];?>" value="<?php echo $value_sub['id'];?>" > </td>
</tr>

<?php 
$ii++;
} } ?>
</table>
</td>
</tr>
<?php 
$i++; } } ?>
</table>
<?php 
}  





else if ($_REQUEST['action'] == 'update_menu') {
    $menu_data = $_REQUEST['menu'];
    $user_id = $_REQUEST['user_id'];
    //$sub_menu = $_REQUEST['sub_menu'];
	$submenu_array = array();
    try { 
		foreach($menu_data as $key=>$submenu){
			$menu_id = $key;
			foreach($submenu as $k=>$value){
				$submenu_id = $value;
				$submenu_array[] = $value;
				$query = $db->prepare("select count(*) as count from link_r where user_id = ? and submenu_id = ? and menu_id = ?");
				$query->bindParam(1, $user_id, PDO::PARAM_INT);
				$query->bindParam(2, $submenu_id, PDO::PARAM_INT);
				$query->bindParam(3, $menu_id, PDO::PARAM_INT);
				$query->execute();
				$res= $query->fetchColumn();
				if($res < 1){
				 $query = $db->prepare("insert into link_r (user_id,menu_id,submenu_id) values (?,?,?)");
				$query->bindParam(1, $user_id, PDO::PARAM_INT);
				$query->bindParam(2, $menu_id, PDO::PARAM_INT);
				$query->bindParam(3, $submenu_id, PDO::PARAM_INT);
				$query->execute();
				}
			}
		}
		$submenu_ids = implode(',',$submenu_array);
        $query = $db->prepare("delete from link_r where user_id = ? and submenu_id not in ($submenu_ids)");
		$query->bindParam(1, $user_id, PDO::PARAM_INT);
		$query->execute();
        echo 'Update Menu Sucessfully.';
    } catch(Exception $ex) { 
        echo $ex;
    }
}    else {
    echo 'Something Error';
}


?>

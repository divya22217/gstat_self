<?php

$main_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$actual_link = $_SERVER['REQUEST_URI'];
$actual_link = str_replace("/nclat_audit/","",$actual_link);
$actual_link = strtok($actual_link, '?');
 $user_id = $_SESSION['id'];
//        print_r($_SESSION);
// echo $actual_link;

// echo "select submenu_link from public.submenu_r S, public.link_r L where S.submenu_id=L.submenu_id 
// and L.user_id=$user_id and S.display='TRUE' and submenu_link LIKE '$actual_link' order by S.priority";
 $query_check = "select submenu_link from public.submenu_r S, public.link_r L where S.submenu_id=L.submenu_id 
and L.user_id=? and S.display='TRUE' and submenu_link LIKE '$actual_link' order by S.priority";

/* $query_check = "select submenu_link from public.submenu_r S, public.link_r L where S.submenu_id=L.submenu_id 
and L.user_id=? and S.display='TRUE'  order by S.priority";
 */

$row_data = array();
try {
    $stmt_check = $db->prepare($query_check);
     $stmt_check->bindParam(1, $user_id, PDO::PARAM_STR);
     $query= $stmt_check->execute();
    $row_data = $stmt_check->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo $ex;
}
// print_r( $row_data );
/*$row_count = count( $row_data );
 $page_access_allow =false;
if($row_count > 0){
    foreach($row_data as $rd){
        if($rd["submenu_link"] == $actual_link){
            $page_access_allow=true;
        }        
    }
}else{
    $page_access_allow=false; 
}

if($page_access_allow==false){
    echo "Not Authorised";
    die();
} */
 $arry_direct_access = array('login.php','g/change_password.php','scrutiny/varify_ia_scrutiny.php', 
'scrutiny/verify_document_scrutiny.php', 'scrutiny/varify_cases.php', 'scrutiny/ia_scrutiny.php', 'scrutiny/user_scrutiny1.php', 'scrutiny/doc_scrutiny.php','dashboard.php','index.php','');
if (!in_array($actual_link,$arry_direct_access) && $_SESSION['id']!='9'){
   // echo "approved";
    if (empty($row_data)) {
        header("Location: /nclat_audit/403.php");
        die();
    }
 } 
 $arry_direct_access1 = array('login.php','reg/create_user_r.php','reg/user_list.php', 
 'reg/create_menu_r.php','reg/create_submenu_r.php', 'reg/menu_list.php', 'reg/submenu_list.php', 'reg/assign_user_menu.php','index.php','');
 if (!in_array($actual_link,$arry_direct_access1) && $_SESSION['localadmin']=='2'){
           header("Location: /nclat_audit/403.php");
            die();
  } 


?>
<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
date_default_timezone_set("Asia/Kolkata");
include("../includes/db_inc.php");//database connection
// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{

	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}
else {

$_SESSION[reg_form_act] = sha1(uniqid(rand()));
	$stlu = $db->prepare("select schema_id,main_id,localadmin,location from users where id= ? ");
	$stlu->bindParam(1, $_SESSION[id], PDO::PARAM_STR);
	$stlu->execute();
	while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$localadminzz=$row['localadmin'];
		$main_id=$row['main_id'];
		$locationq=$row['location'];
		$schema_id_db=$row['schema_id'];

	}
	$hash=$_REQUEST['ida'];
    $hash=htmlspecialchars(htmlentities(base64_decode($hash)));
    $hash=explode("#",$hash);
    $_token = $hash[0];
    $msg=$hash[1];
$link_schema=$hash[2];
$place=$hash[4];
    if($_token!=$_SESSION[reg_form]){
        session_unset();session_destroy();
        header("Location: ../login.php?aa=108");
    }
if($localadminzz==1) {
    if ($locationq != $place) {
        session_unset();
        session_destroy();
        header("Location: ../login.php?aa=108");
        die('Location2 Not Matched');
    }
    if ($schema_id_db != $link_schema) {
        session_unset();
        session_destroy();
        header("Location: ../login.php?aa=108");
        die('Location Not Matched');
    }
}

	

?>	
<head>
<script src="../includes/plugins/jquery_ui/jquery-1.12.4.js" type="text/javascript"></script>
<link href="../includes/plugins/jquery_ui/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="../includes/plugins/jquery_ui/jquery-ui.js" type="text/javascript"></script>
<script src="../includes/plugins/jquery_ui/date.js" type="text/javascript"></script>
<script src="../includes/js/custom.js" type="text/javascript"></script>
<script>
  $( function() {
    $( "#accordion" ).accordion();
  } );
  </script>
</head>
<body>


  <table border="0" width="100%" style="background-color: #e7e3e4; " >
  <tr><td align="center" colspan="2">
  <font color="#4a0910" size="4"><b><u>DELETE MENU</u></b></font>
  </td></tr> 
        <tr><td  align="center">
 </br>
 <?php 
 $msp=$_REQUEST['msggg'];
 if($msp !='')
 {
 	$msp=htmlspecialchars(base64_decode($msp));
 $code1 = explode("/", $msp);
 $msgg = $code1[0];
 $msg = $code1[1];
 if($msgg !='')
 {echo htmlspecialchars($msgg);}
 }
if($msg ==''){$msg=$msg;}
 ?>
 
 </br>
  </td></tr> 



      <!--menu delete -->
      <?php

      $menu_query = $db->prepare("select * from menu  where userid=? ");
      $menu_query->bindParam(1, $msg, PDO::PARAM_STR);
      $menu_query->execute();
      if($menu_query->rowCount()>0) {
          ?>
          <tr style="background-color: #067ab4;color: #FFFFFF;font-style:oblique; " >
              <td colspan="2" align="center"> Main Menu
          </tr>
          <?php
          while ($row = $menu_query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
              $id1x = htmlspecialchars_decode(html_entity_decode($row['id']));
              $menu_name = htmlspecialchars_decode(html_entity_decode($row['name']));
              $disable = $msg. '/' . $id1x.'/menu/F/Menu Deleted Successfully/'.$_SESSION[reg_form_act].'/'.$schema_id_db.'/'.$locationq;
              $enable = $msg. '/' . $id1x.'/menu/T/Menu Activated Successfully/'.$_SESSION[reg_form_act].'/'.$schema_id_db.'/'.$locationq;
              $disable = base64_encode($disable);
              $enable = base64_encode($enable);
              ?>
              <tr>
                  <td colspan="1" align="center"><?php echo $menu_name; ?> </td>
                  <td colspan="1" align="center">

                      <?php
                      if($row[display]=='T'){
                          ?>
                          <a href="menudelete.php?idaa=<?php echo $disable ;?>">Delete</a>
                      <?php }else{ ?>
                          <a href="menudelete.php?idaa=<?php echo $enable;?>">Active</a>
                      <?php } ?>
                  </td>
              </tr>
              <?php
          }
      }
      ?>







    <?php

    $sub_menu_query = $db->prepare("select * from sub_menu where userid=? ");
    $sub_menu_query->bindParam(1, $msg, PDO::PARAM_STR);
    $sub_menu_query->execute();
    if($sub_menu_query->rowCount()>0){
    ?>
   <tr style="background-color: #067ab4;color: #FFFFFF;font-style:oblique; " >
      <td colspan="2" align="center"> Sub Menu</tr>
    <?php
    while ($row = $sub_menu_query->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
    {
    $id1x=htmlspecialchars($row['id']);
    $sub_menu_name = htmlspecialchars_decode(html_entity_decode($row['sub_name']));
    $disable = $msg. '/' . $id1x.'/sub_menu/F/Sub Menu Deleted Successfully/'.$_SESSION[reg_form_act].'/'.$schema_id_db.'/'.$locationq;
    $enable = $msg. '/' . $id1x.'/sub_menu/T/Sub Menu Activated Successfully/'.$_SESSION[reg_form_act].'/'.$schema_id_db.'/'.$locationq;
    $disable = base64_encode($disable);
    $enable = base64_encode($enable);
    ?>
    <tr>
    <td colspan="1" align="center">
    <?php echo $sub_menu_name;?>
    </td>
    <td colspan="1" align="center">

        <?php
        if($row[display]=='T'){
            ?>

            <a href="menudelete.php?idaa=<?php echo $disable;?>">Delete</a>
        <?php }else{ ?>
            <a href="menudelete.php?idaa=<?php echo $enable;?>">Active</a>
        <?php } ?>
    </td>
    </tr>
    <?php
    }
    }
    ?>



      <?php

      $sub_sub_menu_query = $db->prepare("select * from sub_sub_menu where userid=?  ");
      $sub_sub_menu_query->bindParam(1, $msg, PDO::PARAM_STR);
      $sub_sub_menu_query->execute();
      if($sub_sub_menu_query->rowCount()>0){
          ?>
          <tr style="background-color: #067ab4;color: #FFFFFF;font-style:oblique; " >
              <td colspan="2" align="center"> Sub To Sub Menu</tr>
          <?php
          while ($row = $sub_sub_menu_query->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
              $id1x=htmlspecialchars($row['id']);
              $sub_sub_menu_name = htmlspecialchars_decode(html_entity_decode($row['sub_sub_name']));
              $disable = $msg. '/' . $id1x.'/sub_sub_menu/F/Sub To Sub Menu Deleted Successfully/'.$_SESSION[reg_form_act].'/'.$schema_id_db.'/'.$locationq;
              $enable = $msg. '/' . $id1x.'/sub_sub_menu/T/Sub To Sub Menu Activated Successfully/'.$_SESSION[reg_form_act].'/'.$schema_id_db.'/'.$locationq;
              $disable = base64_encode($disable);
              $enable = base64_encode($enable);
              ?>
              <tr>
                  <td colspan="1" align="center">  <?php echo $sub_sub_menu_name;?>             </td>
                  <td colspan="1" align="center">
                      <?php
                      if($row[display]==true){
                      ?>

                      <a href="menudelete.php?idaa=<?php echo  $disable;?>">Delete</a>
                          <?php }else{ ?>
                          <a href="menudelete.php?idaa=<?php echo $enable;?>">Active</a>
                      <?php } ?>
                  </td>
              </tr>
              <?php
          }
      }
      ?>








	<?php } ?>

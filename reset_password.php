<?php
// ///////////////////////////////////// CODE STARTS HERE BY PREETI /////////////////////////////////////////
include "db_inc1.php";
session_start();

if(isset($_POST))
{
  
   $data_value = htmlentities($_POST['type']);
//    var_dump($data_value);

// $ip = $_SERVER['HTTP_CLIENT_IP'];
// echo $ip;
function GetMAC()
	{
		ob_start();
		system('getmac');
		$Content = ob_get_contents();
		ob_clean();
		return substr($Content, strpos($Content, '\\') - 20, 17);
	}
   $ip = GetMac();
 
   


   if($data_value === '1')
   {
    
    $name = htmlentities($_POST['uname']);
    $mail = htmlentities($_POST['umail']);
    
    
    $sql = "select username, email from users_cis where username=? and email=?";
   $result = $db->prepare($sql);
   $result->bindParam(1, $name, PDO::PARAM_STR);
   $result->bindParam(2, $mail, PDO::PARAM_STR);
   $result->execute();
//    echo $sql;
  //    $record = $result->fetchAll();
   $count = $result->rowCount();
//    echo "</br>".$count."<br>";
   if($count>0)
   {
    $_SESSION[$mail]=123456;
    echo json_encode(['msg'=>'Data Found', 'status_code'=>'200', 'type'=>'1']);
   }
   else
   {
     echo json_encode(['msg'=>'Data Not Found', 'status_code'=>'400', 'type'=>'1']);
    
   }

   }
   else if($data_value === '3'){ 
   $otp_verify = $_POST['otp'];
   $name = htmlentities($_POST['uname']);
   $mail = htmlentities($_POST['umail']);
   
   if($_SESSION[$mail] == $otp_verify)
   {
      $sendOtp = true;
      echo json_encode(['msg'=>'Otp matches', 'status_code'=>'200', 'type'=>'3']);
      unset($_SESSION[$mail]);
   }else{
      $sendOtp = false;
      echo json_encode(['msg'=>'Otp does not match', 'status_code'=>'200', 'type'=>'3']);
   }
   die;
  }
  else{   
    
    $name = htmlentities($_POST['uname']);
    $mail = htmlentities($_POST['umail']);
    $resetpassword =  htmlentities($_POST['reset']);
    $confirmpassword = htmlentities($_POST['conpwd']);
   //  echo $resetpassword."<br>".$confirmpassword;
    $actiontype = 'forget password';

    if($resetpassword === $confirmpassword)
    {

     
   //   $resetpassword =  hash('sha256', (get_magic_quotes_gpc() ? stripslashes($resetpassword) : $resetpassword));
     $query = 'insert into password_log (username,ip_address,action_type) values(?,?,?)';
     $result_q = $db->prepare($query);
     $result_q->bindParam(1, $name, PDO::PARAM_STR);
     $result_q->bindParam(2, $ip, PDO::PARAM_STR);
     $result_q->bindParam(3, $actiontype, PDO::PARAM_STR);
     $result_q->execute();
     

     $sql1 = 'update users_cis set password=? where username=? and email=?';
     $result1 = $db->prepare($sql1);
     $result1->bindParam(1, $resetpassword, PDO::PARAM_STR);
     $result1->bindParam(2, $name, PDO::PARAM_STR);
     $result1->bindParam(3, $mail, PDO::PARAM_STR);
     $result1->execute();
     $count = $result1->rowCount();
     echo json_encode(['msg'=>'updated successfully', 'status_code'=>'200', 'type'=>'2']);
     
    }
    else
    {
     echo json_encode(['msg'=>'Please enter correct password', 'status_code'=>'500', 'type'=>'2']);
    }
   
    

   }

}

// //////////////////////////////////// CODE ENDS HERE BY PREETI //////////////////////////////////

?>
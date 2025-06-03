<?php

require_once('class.phpmailer.php');
    require_once('class.smtp.php');
    $yourname='binu';
    $message='test all';
    
     $html = "<center>
                <table width='500' cellpadding='4' cellspacing='1' bgcolor='#ffffff' style='border:1px solid;font-family:arial;font-size:14px;'>
                <tr bgcolor='#1b9dd9'>
                    <td colspan='2' align='center' style='color:#FFF; font-weight:bold; padding: 8px;'>Door step contact us</td>
                </tr>
                
                <tr>
                    <td width='150' bgcolor='#9AC0E1' style='color:#333; padding:8px;'>Name </td>
                    <td bgcolor='#d7eafb' style='padding:8px;'>$yourname</td>
                </tr>
                
                               
                <tr>
                    <td width='150' bgcolor='#9AC0E1' style='color:#333; padding:8px;'>Comment</td>
                    <td bgcolor='#d7eafb' style='padding:8px;'>$message</td>
                </tr>
                
            </table>
            </center>";
           $subject = "door step "; 

           $to = "binu.dhiraj@gmail.com"; 
            $from = "binu.dhiraj@gmail.com"; 
            
            
            
            $mail = new PHPMailer(); 
            $mail->IsSMTP();                                     
            $mail->Host = "smtp.gmail.com"; 
            $mail->SMTPAuth = true;    
            $mail->Username = "binu.dhiraj@gmail.com"; 
            $mail->Password = "E[I_3v6l4NDy";  
            $mail->From = $from;
            $mail->FromName = "door step";
            $mail->ConfirmReadingTo = $from;
            $mail->Sender = $from;
            $mail->AddAddress($to);      
            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html; 
            $mail->AddBcc("binu.dhiraj@gmail.com","binu.dhiraj@gmail.com");   
            $mail->Send();
            
            ?>
<?php

include('././db_inc1.php');
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}


    if (isset($_POST['method']) && $_POST['method'] != '') {
        $method = $_POST['method'];
        $response = '';
    
        switch ($method) {
            case "change_status":
                extract($_POST);
                 $msg_id=$_POST['msg_id'];
                $response=updateStatus($db,$msg_id); 
                break;
            case "allNotification":
                extract($_POST);
                $response = getallNotification($db);
                break;
            case "saveReminder";
                extract($_POST);
               $post_value=$_POST;
             
                $response = saveReminder($db,$post_value);
                break;
            default:
                break;
        }
        $response = mb_convert_encoding($response, 'UTF-8', 'UTF-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        die();
    } else {
      
                function getNotifications($db,$receiver_id)
                {
    
                    $result=[];
                    $totalQuery = $db->prepare("SELECT COUNT(*) FROM notifications Where is_read=false AND receiver_id=?");
                    $totalQuery->bindParam(1, $receiver_id, PDO::PARAM_INT);
                    $totalQuery->execute();
                    $total = $totalQuery->fetchColumn();
                    $sql = "SELECT n.msg_id, n.type, n.message, n.data, n.is_read, n.created_at FROM notifications n
                    WHERE n.receiver_id = ? ";

                    // if ($is_read !== null) {
                    //     $sql .= " AND n.is_read =?";
                    // }
                    $sql .= " ORDER BY n.created_at DESC LIMIT 10";
                
                    try {
                        $stmt =$db->prepare($sql);
                        $stmt->bindParam(1, $receiver_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
                        return $result=["data"=>$data,"total"=>$total];
                    } catch (PDOException $e) {
                        echo "Error fetching notifications: " . $e->getMessage();
                        return [];
                    }
                }
            if ($_GET['function'] === 'allNotification') {
 
                     $start  = $_GET['start'];
                     $length = $_GET['length'];
                     $search = $_GET['search']['value'];
                     $orderCol = $columns[$orderColIndex];
                     $orderDir = $_GET['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';
                     $columns = ['id', 'type', 'message', 'is_read', 'created_at'];
                     $order_by = $columns[$order_col];
             
                     // Count total records
                     $totalQuery = $db->query("SELECT COUNT(*) FROM notifications");
                     $total = $totalQuery->fetchColumn();
             
                     $sql ="SELECT n.msg_id as id ,n.type, n.message,n.is_read,n.created_at FROM notifications n";
             
                     $params = [];
             
                     if ($search) {
                         $sql .= " AND (type LIKE :search OR message LIKE :search)";
                         $params[':search'] = "%$search%";
                     }
             
                     $sql .= " ORDER BY created_at  $orderDir LIMIT :limit OFFSET :start";
                     try {
                         $stmt = $db->prepare($sql);
                     foreach ($params as $key => $value) {
                         $stmt->bindValue($key, $value);
                     }
                     $stmt->bindParam(':start', $start, PDO::PARAM_INT);
                     $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
             
                     $stmt->execute();
                     $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                     $filtered = $search ? count($data) : $total;
                     $response = [
                         "draw" => intval($_GET['draw']),
                         "recordsTotal" => $total,
                         "recordsFiltered" => $filtered,
                         "data" => $data
                     ];
                     } 
                     catch (PDOException $e) {
                         echo "Error fetching notifications: " . $e->getMessage();
                         return [];
                     }
                 
                         echo json_encode($response);
            }
            if($_GET['function'] ==='change_status')
            {
               // print_r($_GET);die;
               // print_r('divya');die;
               $msg_id=$_GET['msg_id'];
                $is_read=true;
                $sql="UPDATE notifications SET is_read=? Where msg_id=?";
                try{
                $update =$db->prepare($sql);
                $update->bindParam(1,$is_read,PDO::PARAM_BOOL);
                $update->bindParam(1,$msg_id,PDO::PARAM_INT);
                $update->execute();
                return 1;
                }
                catch (PDOException $e) {
                    echo "Error fetching notifications: " . $e->getMessage();
                    return 0;
                }
            }
        
          
     

    }

    function saveReminder($db,$post_value)
    {
       
        $cis_user_id=$post_value['cis_user_id'];
        $court_id=$post_value['court'];
        $schema_id=$post_value['schema_id'];
        $schema=$post_value['schemas'];
        $menu_accesscode =$post_value['menu_accesscode'];
        $check_scrutiny_user=getcaseDetail($db,$cis_user_id,$menu_accesscode);
           
        //print_r($check_scrutiny_user);die;
       
        foreach($check_scrutiny_user as $check_detail)
        {
           
            if($menu_accesscode==2)
           {
                if(isset($check_detail['assign_date']) & !empty($check_detail['assign_date']))
                {
                    $assign_date=$check_detail['assign_date'];
                }
                  $message='This is the reminder that your Scrutiny task is due after 2 Days for  appeal no '.$check_detail['filing_no'];
                  $category='Scrutiny';
           
                }
           elseif($menu_accesscode==11)
           {
                    $stmt = $db->prepare("SELECT notification_date FROM $schema.scrutiny WHERE filing_no = :filing_no");
                    $stmt->bindParam(':filing_no', $check_detail['filing_no'], PDO::PARAM_STR); // or PARAM_INT if it's an integer
                    $stmt->execute();
                    $scrutiny = $stmt->fetch(PDO::FETCH_ASSOC);  
            
                if(isset($scrutiny['notification_date']) & !empty($scrutiny['notification_date']))
                {
                    $assign_date=$check_detail['notification_date'];
                }
                  $message='This is the reminder that your Re-Scrutiny task is due after 2 Days for  appeal no '.$check_detail['filing_no'];
                  $category='Scrutiny';
           }
            elseif($menu_accesscode==6)
           {
                    $stmt = $db->prepare("SELECT entry_date FROM $schema.case_no_generation WHERE filing_no = :filing_no");
                    $stmt->bindParam(':filing_no', $check_detail['filing_no'], PDO::PARAM_STR); // or PARAM_INT if it's an integer
                    $stmt->execute();
                    $scrutiny = $stmt->fetch(PDO::FETCH_ASSOC);  
    
                if(isset($scrutiny['entry_date']) & !empty($scrutiny['entry_date']))
                {
                    $assign_date=$check_detail['entry_date'];
                }
                  $message='This is the reminder that Case No generation task is due after 2 Days for  appeal no '.$check_detail['filing_no'];
                   $message='Case No generation task is Escalated over  from Registrar for  appeal no '.$check_detail['filing_no'];
                  $category='Case No Generation';
           }
           
            
           $reminderDate= getDuedate($db,$assign_date);
           $today = new DateTime();
           $currentDate = clone $today;

           $reminderDate = new DateTime('2025-05-28'); // example reminder date
            $reminderDate->modify('+2 day'); // adds 2 days
            $currentDate = new DateTime(); // today's date

            $interval = $currentDate->diff($reminderDate);
            echo $interval->format('%R%a days'); die; // shows the difference in days with +/-


          
            if($reminderDate == $currentDate->format('Y-m-d')) {

                saveNotification($db,$cis_user_id,0,$check_detail['filing_no'],$schema_id,$court_id,'Reminder',$category,$message);
             }
              
             if ($reminderDate->modify('+2 day')->format('Y-m-d') < $currentDate->format('Y-m-d')) {
        
                saveNotification($db,$cis_user_id,0,$check_detail['filing_no'],$schema_id,$court_id,'Escalation',$category,$message);
             }
            
        }
      

    }

    // function getUser($db,$schema_id,$court_id,$menu_accesscode)
    // {
    //     $UserDetail = $db->prepare("Select id from users_cis where schema_id='.$schema_id.'  AND court='.$court_id.' AND menuaccess_codeall='.$menu_accesscode.'
    //       ");
    //     $UserDetail->execute();
    //     $detail = $UserDetail->fetch(PDO::FETCH_COLUMN);
    //     return $detail;
    // }
    function getDuedate($db,$assign_date)
    {
     
      $today = new DateTime();
      $trackingDate = new DateTime($assign_date);
      $menu_code_id=$_SESSION['menuaccess_codeall'];
      $schema_id=$_SESSION['schema_idccc'];
      $public_holidays_query = $db->prepare("SELECT holiday_date FROM delhi.holidays WHERE status = true 
              ORDER BY holiday_date ASC
          ");
        $public_holidays_query->execute();
        $holidays = $public_holidays_query->fetchAll(PDO::FETCH_COLUMN);
        $currentDate = clone $today;
        $direction = $today < $trackingDate ? 1 : -1;
        $workingDays = 0;

        while ($currentDate->format('Y-m-d') !== $trackingDate->format('Y-m-d')) {
            $currentDate->modify($direction . ' day');
            if (!in_array($currentDate->format('Y-m-d'), $holidays)) {
                $workingDays += $direction;
            }
        }

        $dueDate = clone $trackingDate;
        $due_days=$db->prepare("SELECT no_of_days FROM role_timeframes where menuaccess_code_all_id= $menu_code_id And schema_id='$schema_id' ");
        $due_days->execute();
        $duedays = $due_days->fetch(PDO::FETCH_COLUMN);  
        
        $addedDays = 0;
            while ($addedDays < $duedays) {
                $dueDate->modify('+1 day');
                if (!in_array($dueDate->format('Y-m-d'), $holidays)) {
                    $addedDays++;
                }
            }
            $reminderDate = clone $dueDate;
            $subtracted = 0;

            while ($subtracted < 2) {
                $reminderDate->modify('-1 day');
                if (!in_array($reminderDate->format('Y-m-d'), $holidays)) {
                    $subtracted++;
                }
            }
             return $reminderDate->format('Y-m-d');

    
    }


    function saveNotification($db,$receiver_id, $sender_id,$filing_no, $schema_id,$court_id, $type,$category, $message)
    {
        $sql = "INSERT INTO notifications
                (receiver_id,sender_id, filing_no, schema_id,type,message,court_id) 
                VALUES 
                (:receiver_id,:sender_id,:filing_no, :schema_id,:type,:category,:message,:court_id) ON CONFLICT (filing_no, type,receiver_id) DO NOTHING;";
            //  ON CONFLICT (filing_no, type, receiver_id) DO NOTHING";

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $receiver_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $sender_id, PDO::PARAM_INT);
            $stmt->bindParam(3, $filing_no, PDO::PARAM_STR);
            $stmt->bindParam(4, $schema_id, PDO::PARAM_INT);
            $stmt->bindParam(5, $type, PDO::PARAM_STR);
            $stmt->bindParam(6, $category,PDO::PARAM_STR);
            $stmt->bindParam(7, $message, PDO::PARAM_STR);
            $stmt->bindParam(8, $court_id, PDO::PARAM_INT);
            $stmt->execute();
           // print_r($run);die;
            return true;
        } catch (PDOException $e) {
            echo "Error inserting notification: " . $e->getMessage();
            return false;
        }
    }

   
    function getcaseDetail($db,$cis_user_id,$menu_accesscode)
    {
     
        $query_q = "SELECT ecd.filing_no,ecd.scrutiny,ecd.scrutiny_level,ecd.scrutiny_assign_date as assign_date
            FROM public.e_case_detail ecd";
           // $condition .="WHERE cis_user_id=?  AND scrutiny='0' AND scrutiny_level= 0 ";
            if($menu_accesscode== 2)
            {
                $query_q .= "WHERE cis_user_id=?  AND scrutiny='0' AND scrutiny_level= 0 " ;
            }
            if($menu_accesscode == 11)
            {
                $query_q .= "WHERE  scrutiny='0' AND scrutiny_level= 1 " ;
                
            }
            if($menu_accesscode == 6)
            {
                $query_q .= "WHERE  scrutiny='1' AND scrutiny_level= 2 AND case_no_generated=0  AND is_defective = 0" ;
                
            }
       
            try {
                $query = $db->prepare($query_q);
                $query->bindParam(1, $cis_user_id, PDO::PARAM_INT);
                $query->execute();
                $data = $query->fetchAll();
                return $data;
            } catch (PDOException $ex) {
                
                return $ex;
            }
    }

    
          

    

?>


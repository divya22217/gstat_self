<?php
include('../db_inc1.php');
    // Method to insert a new notification
    if($_GET['function']==='readNotifi')
    {
      
        $filing_no=$_REQUEST['filing_no'];
        $schema=$_REQUEST['schema'];
        readNotifi($filing_no,$schema);
       // insertNotification($db);
    }
    function readNotifi($filing_no,$schema)
{
  
    $url = "https://uat-efiling.gstat.gov.in/efiling/getdataapl02b.drt?filingNo=$filing_no&schema=$schema";
    $data = callApiSync($url);
    echo json_encode($data); // ensure $data is array or JSON
    die;
}
    
   
function  callApiSync($url)
{
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

$response = curl_exec($ch);
$response = trim($response);  
$response = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response); 

if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$data = json_decode($response, true);
//echo "<pre>";var_dump($data);
return $data;

if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'JSON Decode Error: ' . json_last_error_msg();
}

// echo "HTTP Code: $httpCode\n";
// var_dump($response);
}
    if($_GET['function']==='insertNotification')
    {
       
       // insertNotification($db);
    }
    function insertNotification($db,$receiver_id, $sender_id,$filing_no, $schema_id, $type, $message, $data = null)
    {
        $sql = "INSERT INTO notifications
                (receiver_id,sender_id, filing_no, schema_id, type, message, data) 
                VALUES 
           
                (:receiver_id,:sender_id,:filing_no, :schema_id, :type, :message, :data)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':receiver_id' => $receiver_id,
                ':sender_id'   => $sender_id,
                ':filing_no'   => $filing_no,
                ':schema_id'   => $schema_id,
                ':type'        => $type,
                ':message'     => $message,
                ':data'        => $data ? json_encode($data) : null
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Error inserting notification: " . $e->getMessage();
            return false;
        }
    }

   function getNotifications($db,$receiver_id,$type)
    {
        $result=[];
        $totalQuery = $db->query("SELECT COUNT(*) FROM notifications Where is_read=false");
        $total = $totalQuery->fetchColumn();
        $sql = "SELECT n.msg_id, n.type, n.message, n.data, n.is_read, n.created_at FROM notifications n
        WHERE n.receiver_id = ? AND n.type = ? ";

        // if ($is_read !== null) {
        //     $sql .= " AND n.is_read =?";
        // }
        $sql .= " ORDER BY n.created_at DESC LIMIT 10";
        try {
            $stmt =$db->prepare($sql);
            $stmt->bindParam(1, $receiver_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $type, PDO::PARAM_STR);
            
            // if ($is_read !== null) {
            //     $stmt->bindParam(3, $is_read, PDO::PARAM_BOOL);
            // }
            $stmt->execute();
            $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result=["data"=>$data,"total"=>$total];
        } catch (PDOException $e) {
            echo "Error fetching notifications: " . $e->getMessage();
            return [];
        }
    }

    if ($_GET['function'] === 'allNotification') {
        getallNotification($db); 
    }

    function getallNotification($db){

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

    if ($_GET['function'] === 'change_status') {
        updateStatus($db); 
    }

    function updateStatus($db)
    {
        $msg_id=$_REQUEST['id'];
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

    //Cron job for Reminder
    // function getTimeframe()
    // {
    //     $sql="SELECT r.menuaccess_code_all_id,r.no_of_days,r.schema_id"
    // }

?>

<?php
include("includes/common_function.php");
 $schemas = 'delhi';
 $flag = false;
if($_SERVER['REQUEST_METHOD'] == 'POST') { 
 $token =  $_REQUEST['token'];
        $e_token = get_data($db, 'e_token', array('token' => $token,'status' => '0'));
        if(count($e_token)) { 
   if ($_REQUEST['action'] == 'bench_nature_list') {
        $bench_nature = get_data($db, $schemas . '.bench_nature', array('display' => 'TRUE'));
        $msg =  json_encode($bench_nature);
        $flag = true;
    } else if ($_REQUEST['action'] == 'get_court_list') {
        list($d,$m,$Y) =explode('/',$_REQUEST['listing_date']);
        $listing_date =$Y.'-'.$m.'-'.$d;
        $bench_nature = $_REQUEST['bench_nature'];
        $where = array('from_list_date'=>$listing_date,'bench_nature'=>$bench_nature);
        $bench_nature = get_data($db, $schemas . '.bench', $where,array(),'distinct(court_no) as court_no','ASC','court_no');
        $msg =  json_encode($bench_nature);
        $flag = true;
    } else if($_REQUEST['action'] == 'generate_cause_list') {
require_once('generate_cause.php');
$flag = true;
    } }
} 
if($flag === true) { 
echo $msg;
} else { 
echo "{'message':'Error : Invalid Request Method Or API Token'}";
}

?>



<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
function get_data($db, $table_name, $where = array(), $where_like = array(), $column = '*', $order_by = null, $order_column = null, $limit = null)
{
    $data_main = array();
    $where_as = '';
    if (is_array($where) && !empty($where)) {
        foreach ($where as $key => $val) {
            if ($key != '' && $val != '') {
                $where_as .= $key . '=' . "'$val'" . ' and ';
            }
        }
        $where_as = rtrim($where_as, ' and ');
        $where_con = '';
        if ($where_as != '') {
            $where_con = 'where ' . $where_as;
        }
    }
    $where_as_like = '';
    if (is_array($where_like) && !empty($where_like)) {
        foreach ($where_like as $key1 => $val1) {
            if ($key1 != '' && $val1 != '') {
                $where_as_like .= $key1 . ' LIKE ' . "'%$val1'" . ' and ';
            }
        }
        $where_as_like = rtrim($where_as_like, ' and ');
        $where_con_like = '';
        if ($where_as_like != '') {
            $where_con_like = ' and ' . $where_as_like;
        }
    }
    $where_con = $where_con . $where_con_like;

    $order_by_con = '';
    if ($order_by != null) {
        $order_by_con = 'ORDER BY ' . $order_column . ' ' . $order_by;
    }
    $limit_con = '';
    if ($limit != '') {
        $limit_con = $limit;
    }
     $query = "select $column from $table_name $where_con $order_by_con $limit_con";
    try {
        $query_prepare = $db->prepare($query);
        $query_prepare->execute();
        while ($row = $query_prepare->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $data_main[] = $row;
        }
        return $data_main;
    } catch (PDOException $ex) {
        die("Failed to run query case additional_party Applicant: " . $ex->getMessage());
    }
}


?>


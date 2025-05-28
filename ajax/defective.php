<?php
require_once('../includes/helper.php');
deny_direct_access();

// ini_set('display_errors', 1);
//  ini_set('display_startup_errors', 1);
//  error_reporting(E_ALL); 

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

    header('Content-Type: text/html; charset=ISO-8859-1');

date_default_timezone_set("Asia/Kolkata");?>

<?php header('Content-Type: text/html; charset=iso-8859-15'); ?>
<?php
include "../db_inc1.php";




if ($_POST['action'] == 'defctive_data_search') {
    $filing_no = $_POST['filing_no'];
    $schema = $_POST['schema'];

    $sql1 = "SELECT * FROM $schema.objection_details_his where status_registrar = 'NO' and filing_no = '$filing_no' and level_level ='2' order by entry_date desc";
    $sql1 = $db->prepare($sql1);
    $sql1->execute();
    $check_list = $sql1->fetchAll();
    
    if (!empty($check_list)) {
        $i = 1;
        foreach ($check_list as $val) {
            $obej_code = $val['objection_code'];
            $check_list = $db->prepare("select check_list from check_list_local where id = ? order by id ASC ");
            $check_list->bindParam(1, $obej_code, PDO::PARAM_STR);
            $check_list->execute();
            $check_list_data = $check_list->fetchColumn();
            echo $corre_scrutiny = $val['scrutiny_correction'];
            $scrutiny_status_data12121 = '';
            $refiled_date = '';
            if ($corre_scrutiny != '') {
                $scrutiny_status = $db->prepare("select cause_name from scrutiny_status where id in($corre_scrutiny) order by id ASC ");
                $scrutiny_status->execute();
                $scrutiny_status_data1 = $scrutiny_status->fetchAll();
                if (!empty($scrutiny_status_data1) && is_array($scrutiny_status_data1)) {
                    foreach ($scrutiny_status_data1 as $val_ty) {
                        $scrutiny_status_data12121 .= $val_ty['cause_name'] . ', ';
                    }
                }

                $expl_corre_scrutiny = (explode(" ", $corre_scrutiny));
                $in_data = '';
                if (!empty($expl_corre_scrutiny) && is_array($expl_corre_scrutiny)) {
                    foreach ($expl_corre_scrutiny as $key_val) {
                        $in_data .= "'$key_val'" . ',';
                    }
                }

                $dsfdfsdf = rtrim($in_data, ',');

                $dfdfdfdf = $val['entry_date'];
                try {
                    $scrutiny_date = $db->prepare("select date from scrutiny_history where scrutiny_status in ($dsfdfsdf) AND
                    filing_no = '$filing_no' and  date >= '$dfdfdfdf' ");
                    $scrutiny_date->execute();
                    $refiled_date12 = $scrutiny_date->fetchColumn();
                    if($refiled_date12 != '') { 
                        $refiled_date = date('d-m-Y',strtotime($refiled_date12));
                    }
                
                } catch (PDOException $ex) {
                    echo $ex;
                }

            }

            if($refiled_date == '') { 
                $refiled_date = 'RA';
            }

             $scrutiny_status_data121215 = rtrim($scrutiny_status_data12121, ', ');
            echo '<tr><td>' . $i . '.</td>
<td>' . $check_list_data . '</td>
<td>' . $scrutiny_status_data121215 . '</td>
<td>' . strtr($val['comment_registrar'],['&amp;'=>'','amp;'=>'']) . '</td>
<td> ' . date('d/m/Y', strtotime($val['entry_date'])) . ' </td>
<td>' . $refiled_date . '</td></tr>';
            $i++;
        }
    } else {
        echo '<tr><td colspan="6" style="text-align: center;color: red;">No Defect Raised</td></tr>';
    }

}

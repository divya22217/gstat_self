<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
$server_date = date('Y-m-d');
include "../db_inc1.php";
include "../db_inc2.php";
include_once '../custom/custom_function.php';
$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];
$sessionUserType = htmlspecialchars($_SESSION['id']);
$location_access = $_SESSION['location'];
$schema_id = $_SESSION['schema_idccc'];
//print_r($_SESSEION);

function display_filing_no($filing_no_display)
{
    $lastFour = substr($filing_no_display, -4);
    $lastFive = substr($filing_no_display, -9, -4);
    $left = substr($filing_no_display, -16, -9);
    return $dis_fil_no = $left . '/<b>' . $lastFive . '/' . $lastFour . '</b>';

}

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    // header("Location: ./login.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", true, true);
$_SESSION['csrf'] = md5(uniqid(rand(), true));
$key = $_SESSION['csrf'];
// At the top of the page we check to see whether the user is logged in or not
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
    die("#2E2E2Eirecting to login.php");
}
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    $sessionUserType = htmlspecialchars($_SESSION['id']);
    $curYear = htmlspecialchars(date("Y"));
    $curMonth = htmlspecialchars(date("m"));
    $curDay = htmlspecialchars(date("d"));
    $cur_date = "$curYear-$curMonth-$curDay";
    $cur_date1 = "$curDay/$curMonth/$curYear";
    $link_scrutiny_idaccess = '1';
    include '../inheader.php';
    include '../insidebar.php';
    ?>
<link rel="stylesheet" type="text/css" href="../datatable/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../datatable/css/buttons.dataTables.min.css">

<style>
.load_container {
    background: rgba(0, 0, 0, 0.50);
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    z-index: 99;
    left: 0;
}

.load_container .loader {
    display: block;
    width: 60px;
    height: 60px;
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    margin: auto;
}
</style>
<div class="load_container">
    <img class="loader" src="../loading-indicator.gif">
</div>
<div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <?php
 $c_case = isset($_REQUEST['c_case']) ? $_REQUEST['c_case'] : '1';
    //print_r($_SESSION);
    ?>
                <form name="frm" method="post">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="radio" name="c_case" value="1" onChange="submitForm3();"
                                                <?php if ($c_case == 1) {echo 'checked';}?>><b>Fresh case for
                                                listing</b>&nbsp;&nbsp;
                                        </th>
                                        <th>
                                            <input type="radio" name="c_case" value="2" onChange="submitForm3();"
                                                <?php if ($c_case == 2) {echo 'checked';}?>><b>Document scrutiny
                                                for court</b>&nbsp;&nbsp;
                                        </th>
                                        <th>
                                            <input type="radio" name="c_case" value="3" onChange="submitForm3();"
                                                <?php if ($c_case == 3) {echo 'checked';}?>><b>IA</b>
                                        </th>
                                        <!-- <th>
                                            <input type="radio" name="c_case" value="4" onChange="submitForm3();"
                                                <?php if ($c_case == 4) {echo 'checked';}?>><b>Reports</b>
                                        </th> -->
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </form>
                <!-- Fresh Defective Start --->
                <?php 
if(isset($c_case) &&  $c_case == '1' &&  $c_case != '' &&  ($_SESSION['menuaccess_codeall'] =='2' || $_SESSION['menuaccess_codeall'] =='11')) { 
?>
                <table id="example" class="display" >
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Date Of Filing</th>
                            <th>Case Type</th>
                            <th>Diary No.</th>
                            <th>Title Of Case</th>
                            <th>Section</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
    $total_records = defective_cases($dbonline, $location_access, 'Y', 'NA', 'All', '');
    // print_r( $total_records);
    $limit = 500;
    $total_pages = ceil(count($total_records) / $limit);
    if (isset($_GET["page"])) {$page = $_GET["page"];} else { $page = 1;}
    $start_from = ($page - 1) * $limit;
    $case_data = defective_cases($dbonline, $location_access, 'Y', 'NA', $app_pet, $limit, $start_from);
    if (!empty($case_data) && is_array($case_data)) {
        $ii = 1;
        foreach ($case_data as $row) {
            $filing_no = htmlspecialchars($row['filing_no']);
            $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
            $case_type = htmlspecialchars($row['case_type']);
            $case_title = $row['case_title'];
            $case_type_display = fn_case_type_name($dbh, $case_type);
            $filing_date_all = fn_date_formate($dt_of_filing);
            // $E_nameP =  fn_case_party($dbonline,'P',$filing_no);
            // $E_nameR =  fn_case_party($dbonline,'R',$filing_no);
            $upload_check = fn_defective_objection($db, $dbonline, $schemas, $filing_no, $server_date);
            if ($upload_check == '0') {

                ?>
                        <tr style="background-color: #f8c6bf;">
                            <td><?php echo $ii; ?></td>
                            <td><?php echo fn_date_formate($dt_of_filing); ?> </td>
                            <td><?php echo $case_type_display; ?></td>
                            <td><?php echo display_filing_no($filing_no); ?></td>
                            <td><?php echo $case_title; ?> </td>
                            <td>
                                <div class="sparkbar" data-color="#00a65a" data-height="20">
                                    <?php echo fn_section($dbonline, $filing_no); ?></div>
                            </td>
                            <td>
                                <h3><span class="label label-info">
                                        <?php
$filing_nosend = $filing_no . '-' . $qq1cc;
                $filing_no_send = base64_encode($filing_nosend);

                if ($upload_check != '0') {
                    ?>
                                        <a style="color: #FFFFFF;"
                                            href="./scrutiny/user_scrutiny1.php?ccase=<?php echo htmlspecialchars($c_case); ?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send); ?>">Scrutiny</a>
                                        <?php
} else {?>

                                        <?php 
                                if($_SESSION['menuaccess_codeall'] =='2') { 
echo '<a style="color: red;">Rejected by A.R</a>';
                                } else { 
                                    echo '<a style="color: red;">Action Taken</a>';
                                } ?>

                                        <?php
}?>
                                    </span></h3>
                            </td>
                        </tr>


                        <?php
$ii++;
            }
        }

    }?>
                    </tbody>

                </table>
                <?php } ?>
                <!-- Fresh Defective End --->



                <!-- Document scrutiny for court Start --->
                <?php 
if(isset($c_case) &&  $c_case == '2' &&  $c_case != '' &&  ($_SESSION['menuaccess_codeall'] =='2' || $_SESSION['menuaccess_codeall'] =='11')) { 
?>
                <table id="example" class="display" >
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Date Of Filing</th>
                            <th>Diary No.</th>
                            <th>Miscellaneous No.</th>
                            <th>Case No.</th>
                            <th>Title Of Case</th>
                            <th>Section</th>
                            <th>Action By</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
$total_records = document_defective_cases($db,$dbonline,$schemas);
$limit =50;
$total_pages = ceil(count($total_records)/$limit);  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit; 
    $case_data = document_defective_cases($db,$dbonline,$schemas,$limit,$start_from);
    if (!empty($case_data) && is_array($case_data)) {
        $ii = 1;
        foreach ($case_data as $row_sc) {
              $query_q = "select  case_no, case_year, location_code,filing_no,dt_of_filing,case_type,pet_name,res_name from $schemas.case_detail where 
             filing_no ='".$row_sc['filing_no']."' and case_no!=''
            ";
                $query = $db->prepare($query_q);
                $query->execute();
                $row = $query->fetch();
            $filing_no = htmlspecialchars($row['filing_no']);
            $ia_filing_no = htmlspecialchars($row_sc['miscellaneous_ref_no']);
            $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
            $case_title = $row['pet_name'].' VS '. $row['pet_name'];
            $filing_date_all = fn_date_formate($dt_of_filing);
            $case_no = $row['case_no'];
            $case_no = ltrim($case_no,0);
            $casetype = $row['case_type'];
            $casetypeii = fn_case_type_name($db, $casetype);
            $locode = $row['location_code'];
            $case_year = $row['case_year'];
            $lcodesql ="select short_name from $schemas.bench_location where bench_location_code ='$locode'";
            $lcodesql=$db->prepare($lcodesql);
            $lcodesql->execute();
            $lcodename = $lcodesql->fetchColumn();
            $main_case_no = $casetypeii.'/'.$case_no.'('.$lcodename.')'.$case_year;
                ?>
                    <tr style="background-color: #f8c6bf;">
                        <td><?php echo $ii; ?></td>
                        <td><?php echo fn_date_formate($dt_of_filing); ?> </td>
                        <td><?php echo display_filing_no($filing_no); ?></td>
                        <td><?php echo display_filing_no($ia_filing_no); ?></td>
                        <td><?php echo display_filing_no($main_case_no); ?></td>
                        <td><?php echo $case_title; ?> </td>
                        <td>
                            <div class="sparkbar" data-color="#00a65a" data-height="20">
                                <?php echo fn_section($dbonline, $filing_no); ?></div>
                        </td>
                        <td>
                            <h3><span class="label label-info">
                                    <?php 
                                if($_SESSION['menuaccess_codeall'] =='2') { 
echo '<a style="color: red;">Rejected by A.R</a>';
                                } else { 
                                    echo '<a style="color: red;">Action Taken</a>';
                                } ?>
                                </span></h3>
                        </td>
                    </tr>
                    <?php
$ii++;
        }
    }?>
                    </tbody>
                </table>

                <tr>
                <td colspan="7">
                    <div align="center">
                        <ul class='pagination text-center' id="pagination">
                        <?php 
                        $page_no = isset($_REQUEST['page']) ? $_REQUEST['page'] : '1';
                        if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++):  
                            $clas_active='';
                            if($page_no == $i) { 
                                $clas_active = 'active';
                            }
			if($i == 1):?>
                            <li class='<?php echo $clas_active; ?>' id="<?php echo $i;?>"><a
                                    href='defective_cases.php?c_case=<?php echo $c_case;?>&page=<?php echo $i;?>'><?php echo $i;?></a></li>
                            <?php else:?>
                            <li class='<?php echo $clas_active; ?>' id="<?php echo $i;?>"><a href='defective_cases.php?c_case=<?php echo $c_case;?>&page=<?php echo $i;?>'><?php echo $i;?></a>
                            </li>
                            <?php endif;?>
                            <?php endfor;endif;?>
                        </ul>
                    </div>
                </td>
            </tr> 




                <?php } ?>
                <!-- Document scrutiny for court End --->



                <!-- IA Start --->
                <?php 
if(isset($c_case) &&  $c_case == '3' &&  $c_case != '' &&  ($_SESSION['menuaccess_codeall'] =='2' || $_SESSION['menuaccess_codeall'] =='11')) { 
?>
                <table  id="example" class="display" >
                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Main Filing No.</th>
                            <th>Date of Filing</th>
                            <th>IA No.</th>
                            <th>Title Of Case</th>
                            <th>Section</th>
                            <th>Date of Scrutiny</th>
                        </tr>
                    </thead>
                    <?php
    $case_data = ia_defective_cases($db,$dbonline,$schemas);
    if (!empty($case_data) && is_array($case_data)) {
        $ii = 1;
        foreach ($case_data as $row_sc) {
             $query_q = "select a.filing_no,a.ia_filing_no,a.dt_of_filing,b.case_title from e_ia_details as a
           inner join e_case_detail as b ON a.filing_no = b.filing_no
           where a.payment_status='TRUE' and a.scrutiny='0' 
           and a.doc_status='0' and a.ia_filing_no IS NOT NULL and a.ia_filing_no!='' and 
           
            a.ia_id = '".$row_sc['ia_id']."' and a.filing_no  = '".$row_sc['filing_no']."'
            ";
                $query = $dbonline->prepare($query_q);
                $query->execute();
                $row = $query->fetch();
            $filing_no = htmlspecialchars($row['filing_no']);
            $ia_filing_no = htmlspecialchars($row['ia_filing_no']);
            $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
            $case_title = $row['case_title'];
            $filing_date_all = fn_date_formate($dt_of_filing);
                ?>
                    <tr style="background-color: #f8c6bf;">
                        <td><?php echo $ii; ?></td>
                        <td><?php echo display_filing_no($filing_no); ?></td>
                        <td><?php echo fn_date_formate($dt_of_filing); ?> </td>

                        <td><?php echo display_filing_no($ia_filing_no); ?></td>
                        <td><?php echo $case_title; ?> </td>
                        <td>
                            <div class="sparkbar" data-color="#00a65a" data-height="20">
                                <?php echo fn_section($dbonline, $filing_no); ?></div>
                        </td>
                        <td>
                            <h3><span class="label label-info">



                                    <?php 
                                if($_SESSION['menuaccess_codeall'] =='2') { 
echo '<a style="color: red;">Rejected by A.R</a>';
                                } else { 
                                    echo '<a style="color: red;">Action Taken</a>';
                                } ?>

                                </span></h3>
                        </td>
                    </tr>


                    <?php
$ii++;
            
        }

    }?>




                    <tbody>
                    </tbody>
                </table>
                <?php } ?>
                <!-- IA End --->
            </div>
        </div>
    </section>
</div>
<?php include '../infooter.php';?>
<script src="../datatable/js/jquery.dataTables.min.js"></script>
<script src="../datatable/js/dataTables.buttons.min.js"></script>
<script src="../datatable/js/buttons.flash.min.js"></script>
<script src="../datatable/js/jszip.min.js"></script>
<script src="../datatable/js/pdfmake.min.js"></script>
<script src="../datatable/js/vfs_fonts.js"></script>
<script src="../datatable/js/buttons.html5.min.js"></script>
<script src="../datatable/js/buttons.print.min.js"></script>
	<!-- Theme JS files -->
    <script src="../datatable/js/datatables_extension_buttons_html5.js"></script>

</div>
<script type="text/javascript" language="javascript">
function submitForm3() {
    with(document.frm) {
        action = "defective_cases.php";
        submit();
    }
}
</script>
<script>
$(document).ready(function() {

    $('#example').DataTable({
    buttons: {            
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            }
});
});

$('.load_container').fadeOut(500);
</script>

<?php }?>
<!doctype html>

<html lang="en">

<head>
    <?php require_once '../mis/draft_causelist_new.php';
?>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="description" content="The HTML5 Herald">
    <meta name="author" content="SitePoint">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <style>
    /*

RESPONSTABLE 2.0 by jordyvanraaij
  Designed mobile first!

If you like this solution, you might also want to check out the 1.0 version:
  https://gist.github.com/jordyvanraaij/9069194

*/
    .responstable {
        margin: 1em 0;
        width: 100%;
        overflow: hidden;
        background: #FFF;
        color: #024457;
        border-radius: 10px;
        < !-border: 1px solid #167F92;
        -->
    }

    .responstable tr {
        border: 1px solid #D9E4E6;
    }

    .responstable tr:nth-child(odd) {
        background-color: #EAF3F3;
    }

    .responstable th {
        display: none;
        border: 1px solid #FFF;
        background-color: #808080;
        color: #FFF;
        padding: 1em;
    }

    .responstable th:first-child {
        display: table-cell;
        text-align: center;
    }

    .responstable th:nth-child(2) {
        display: table-cell;
    }

    .responstable th:nth-child(2) span {
        display: none;
    }

    .responstable th:nth-child(2):after {
        content: attr(data-th);
    }

    @media (min-width: 480px) {
        .responstable th:nth-child(2) span {
            display: block;
        }

        .responstable th:nth-child(2):after {
            display: none;
        }
    }

    .responstable td {
        display: block;
        word-wrap: break-word;
        max-width: 7em;
    }

    .responstable td:first-child {
        display: table-cell;
        text-align: left;
        border-right: 1px solid #D9E4E6;
    }

    @media (min-width: 480px) {
        .responstable td {
            border: 1px solid #D9E4E6;
        }
    }

    .responstable th,
    .responstable td {
        text-align: left;
        margin: .5em 1em;
    }

    @media (min-width: 480px) {

        .responstable th,
        .responstable td {
            display: table-cell;
            padding: 1em;
        }
    }

    body {
        < !-padding: 0 2em;
        -->font-family: Arial, sans-serif;
        color: #024457;
        background: #f2f2f2;
    }

    h1 {
        font-family: Verdana;
        font-weight: normal;
        color: #024457;
    }

    h1 span {
        color: #167F92;
    }
    </style>
</head>

<body>
    <span style="float:right;"><b>DATE: <?php echo " " . $causelist->getCourtdate(); ?> </b></span><br>
    <form id="from_id_set_priority" method="post">
        <table class="responstable table2excel">
            <tr>
                <th style="width: 40px;">S. No.</th>
                <th data-th="Driver details" style="width:220px;"><span>CP. No.</span></th>
                <th style="width:200px;">Purpose</th>
                <th style="width:150px;">Section</th>
                <th style="width:380px;">Name of Parties&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th style="width: 200px;">Name of Legal Practitioner</th>
                <th style="width:150px;">Priority</th>
            </tr>
            <?php
$counter = 0;
$sql = "select distinct(b.bench_no),b.bench_nature from $schemas.bench b, $schemas.case_allocation_temp t where b.court_no='$court_no' and
b.from_list_date='$list_date_db' and t.listing_date='$list_date_db' and t.court_no='$court_no' and b.bench_no=t.bench_no order by b.bench_no asc";
foreach ($db->query($sql) as $row) {
    $bench_noc = $row['bench_no'];
    $filing_no = '';
    $caseee = $_REQUEST['case_type'];
    $sql_allocation = "select  * from
    $schemas.cause_list_heading_sequence  where id = '$caseee' order by priority asc";
    $sth_j12c = $db->prepare($sql_allocation);
    $sth_j12c->execute();
    while ($row1 = $sth_j12c->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $pri_seq = $row1['priority'];
        $pri_name = $row1['name'];
        if ($pri_seq == 1) {
            $sql_allocation1 = "select a.priority_serial,a.pri_ord,a.filing_no ,a.remarks,a.purpose,d.case_type,a.remark_type from $schemas.$table a,
$schemas.case_detail d, $schemas.master_purpose m where a.listing_date='$list_date_db' and a.bench_no='$bench_noc'
and a.court_no='$courtnoc'  and a.filing_no=d.filing_no and a.purpose=m.purpose_code and a.purpose='12'
and (a.pri_ord IS NULL OR a.pri_ord='') order by m.purpose_priority asc, a.priority_serial asc";
            $sth_j12c1 = $db->prepare($sql_allocation1);
            $sth_j12c1->execute();
            $count_heading = $sth_j12c1->rowCount();
            $iii = 1;
            $snoc = 1;
        }
        if ($pri_seq == 2) {
            $sql_allocation1 = "select a.priority_serial,a.pri_ord,a.filing_no ,a.remarks,a.purpose,d.case_type,a.remark_type from $schemas.$table a,
$schemas.case_detail d, $schemas.master_purpose m where a.listing_date='$list_date_db' and
a.bench_no='$bench_noc' and a.court_no='$courtnoc'  and a.filing_no=d.filing_no and
a.purpose=m.purpose_code and a.pri_ord='P' order by m.purpose_priority asc, a.priority_serial asc";
            $sth_j12c1 = $db->prepare($sql_allocation1);
            $sth_j12c1->execute();
            $count_heading = $sth_j12c1->rowCount();
            $v1 = ceil($snoc / 100) * 100;
            $v1 = $v1 + 1;
            $causelist->setV1($v1);
            $iii = 101;
            $snoc = 101;
        }
        if ($pri_seq == 3) {

            $sql_allocation1_prio = "select a.priority_serial,a.pri_ord,a.filing_no ,a.remarks,a.purpose,d.case_type,a.remark_type from $schemas.$table a,
            $schemas.case_detail d, $schemas.master_purpose m where a.listing_date='$list_date_db' and
            a.bench_no='$bench_noc' and a.court_no='$courtnoc'  and a.filing_no=d.filing_no and
            a.purpose=m.purpose_code and a.pri_ord='P' order by m.purpose_priority asc, a.priority_serial asc";
            $sth_j12c1_prio = $db->prepare($sql_allocation1_prio);
            $sth_j12c1_prio->execute();
            $total_count = $sth_j12c1_prio->rowCount();

            $sql_allocation1 = "select a.priority_serial,a.pri_ord,a.filing_no ,a.remarks,a.purpose,d.case_type,a.remark_type from $schemas.$table a,
$schemas.case_detail d, $schemas.master_purpose m where a.listing_date='$list_date_db' and
a.bench_no='$bench_noc' and a.court_no='$courtnoc'
and a.filing_no=d.filing_no and a.purpose=m.purpose_code
and (a.pri_ord IS NULL OR a.pri_ord='') and a.purpose!='12'
order by m.purpose_priority asc, a.priority_serial asc ";
            $sth_j12c1 = $db->prepare($sql_allocation1);
            $sth_j12c1->execute();
            $count_heading = $sth_j12c1->rowCount();
            $iii = 100 + $total_count + 1;
            $snoc = 100 + $total_count + 1;
        }
        if ($count_heading != 0) {
            ?>
            <tr>
                <input type="hidden" name="listing_date" id="listing_date" value="<?php echo $list_date_db; ?>">
                <input type="hidden" name="bench_no" id="bench_no" value="<?php echo $bench_noc; ?>">
                <input type="hidden" name="court_no" id="court_no" value="<?php echo $courtnoc; ?>">
                <td colspan="7" align="left"><b><?php echo $pri_name ?></b></td>
            </tr>
            <?php
}

        //$iii = 1;
        //$snoc = 1;
        while ($row2 = $sth_j12c1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            //    echo '<pre>';
            //  print_r($row2);

            $priority_serial = $row2['priority_serial'];
            $filing_no = $row2['filing_no'];
            $filing_no_new = $row2['filing_no'];
            $remarks = $row2['remarks'];
            $purpose = $row2['purpose'];
            $case_type = $row2['case_type'];
            $crpf = $row2['remark_type'];
            $pri_ord = $row2['pri_ord'];
            $petnamec = $causelist->getPetname($filing_no, $schemas, $db, $dbonline);
            $resnamec = $causelist->getResname($filing_no, $schemas, $db, $dbonline);
            $fileupload = $causelist->getFileupload($filing_no, $dbonline, $case_type);
            $purpose_now = $causelist->getPurpose($list_date_db, $bench_noc, $courtnoc, $purpose, $db, $schemas);
            $main_case_no = $causelist->nclt_case_no($filing_no, $db, $schemas);
            /* start code to get main case no of IA */
            $get_ia_main_filing_no_sql = "select main_case_ia_no from $schemas.case_detail where filing_no=? and ia_flag='TRUE'";
            $get_ia_main_filing_no = $db->prepare($get_ia_main_filing_no_sql);
            $get_ia_main_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
            $get_ia_main_filing_no->execute();
            $ia_main_filingno = '';
            while ($row_gimfn = $get_ia_main_filing_no->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                $ia_main_filingno = $row_gimfn['main_case_ia_no'];
            }
            if ($ia_main_filingno != '') {
                $ia_main_case_type = $causelist->get_incase_type($ia_main_filingno, $db, $schemas);
                $ia_main_case_no = $causelist->nclt_case_no($ia_main_filingno, $db, $schemas);
            }
/* stop code to get main case no of IA */
/* start code to get all IA of main case */
            $ia_filingno_case_no = array();
            $get_all_ia_sql = "select filing_no from $schemas.case_detail where main_case_ia_no=? and ia_flag='TRUE'";
            $get_all_ia = $db->prepare($get_all_ia_sql);
            $get_all_ia->bindParam(1, $filing_no, PDO::PARAM_STR);
            $get_all_ia->execute();
            $ia_filingno = '';
            while ($row_gai = $get_all_ia->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                $ia_filingno = $row_gai['filing_no'];

                if ($ia_filingno != '') {
                    $get_iacase = $causelist->nclt_case_no($ia_filingno, $db, $schemas);
                    $get_iacase_type = $causelist->get_incase_type($ia_filingno, $db, $schemas);
                    array_push($ia_filingno_case_no, array("case_no" => $get_iacase, "fil_no" => $ia_filingno, "incase_type" => $get_iacase_type));
                }
            }
            $in_case_nor = array();
            $get_in_filing_no_sql = "select in_filingno from e_case_detail where filing_no=?";
            $get_in_filing_no = $dbonline->prepare($get_in_filing_no_sql);
            $get_in_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
            $get_in_filing_no->execute();
            $in_filingno = '';
            while ($row_gifn = $get_in_filing_no->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                $in_filingno = $row_gifn['in_filingno'];
                if ($in_filingno != '') {
                    $get_incase_type = $causelist->get_incase_type($in_filingno, $db, $schemas);
                    $get_incase = $causelist->nclt_case_no($in_filingno, $db, $schemas);
                    array_push($in_case_nor, array("case_no" => $get_incase, "fil_no" => $in_filingno, "incase_type" => $get_incase_type));
                    //print_r($in_case_no);
                }
            }
            $in_case_nocp = array();
            $get_in_filing_no_sql = "select filing_no from e_case_detail where in_filingno=?";
            $get_in_filing_no = $dbonline->prepare($get_in_filing_no_sql);
            $get_in_filing_no->bindParam(1, $filing_no, PDO::PARAM_STR);
            $get_in_filing_no->execute();
            $in_filingno = '';
            while ($row_gifn = $get_in_filing_no->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                $in_filingno = $row_gifn['filing_no'];
                if ($in_filingno != '') {
                    $get_incase_type = $causelist->get_incase_type($in_filingno, $db, $schemas);
                    $get_incase = $causelist->nclt_case_no($in_filingno, $db, $schemas);
                    array_push($in_case_nocp, array("case_no" => $get_incase, "fil_no" => $in_filingno, "incase_type" => $get_incase_type));
                }
            }
            $connected_cases = array();
            $get_conn_cases_sql = "select conn_filing_no from $schemas.connected_cases where filing_no=?";
            $get_conn_cases = $db->prepare($get_conn_cases_sql);
            $get_conn_cases->bindParam(1, $filing_no, PDO::PARAM_STR);
            $get_conn_cases->execute();
            $conn_filing_no = '';
            while ($row_gcc = $get_conn_cases->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                $conn_filing_no = $row_gcc['conn_filing_no'];
                if ($conn_filing_no != '') {
                    $get_incase_type = $causelist->get_incase_type($conn_filing_no, $db, $schemas);
                    $get_incase = $causelist->nclt_case_no($conn_filing_no, $db, $schemas);
                    array_push($connected_cases, array("case_no" => $get_incase, "fil_no" => $conn_filing_no, "incase_type" => $get_incase_type));
                }
            }
            // $snoc = $causelist->getSno($purpose, $snoc);
            ?>
            <tr>
                <td><?php echo $snoc . "."; ?></td>
                <td>
                    <font color="#900C3F" face="verdana" size="2"><?php echo '<b>' . $main_case_no . '</b>' ?></font>

                    <?php
if (!empty($in_case_nor)) {
                $len_array = count($in_case_nor);
                for ($i = 0; $i < $len_array; $i++) {
                    if ($in_case_nor[$i]['case_no'] != '') {
                        $fileupload = $causelist->getFileupload($in_case_nor[$i]['fil_no'], $dbonline, $in_case_nor[$i]['incase_type']);
                        if ($case_type != 16) {
                            echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size='2'><b>In</b></font>";
                        } else {
                            echo "<br>";
                        }
                        ?>
                    <br>
                    <font color="#900C3F" face="verdana" size="2">
                        <?php echo '<b>' . $in_case_nor[$i]['case_no'] . '</b>'; ?>
                    </font>
                    <?php
}
                }
            }

            if (!empty($in_case_nocp)) {
                $len_array = count($in_case_nocp);
                for ($i = 0; $i < $len_array; $i++) {
                    if ($in_case_nocp[$i]['case_no'] != '') {

                        $fileupload = $causelist->getFileupload($in_case_nocp[$i]['fil_no'], $dbonline, $in_case_nocp[$i]['incase_type']);
                        echo "<br>";?>
                    <br>

                    <font color="#900C3F" face="verdana" size="2">
                        <?php echo '<b>' . $in_case_nocp[$i]['case_no'] . '</b>'; ?>
                    </font>
                    <?php
}
                }
            }

            if ($ia_main_filingno != '') {
                $fileupload = $causelist->getFileupload($ia_main_filingno, $dbonline, $ia_main_case_type);
                ?>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="2"><b>In</b>
                    </font>
                    <br>

                    <font color="#900C3F" face="verdana" size="2"><?php echo '<b>' . $ia_main_case_no . '</b>'; ?>
                    </font>
                    <?php
$ia_main_filingno = '';
            }

            if (!empty($ia_filingno_case_no)) {
                $len_array = count($ia_filingno_case_no);
                //echo "<hr>";
                for ($i = 0; $i < $len_array; $i++) {
                    if ($ia_filingno_case_no[$i]['case_no'] != '') {

                        $fileupload = $causelist->getFileupload($ia_filingno_case_no[$i]['fil_no'], $dbonline, $ia_filingno_case_no[$i]['incase_type']);

                        //if($case_type != 16)
                        //echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size='2'>Incp</font>";
                        //else
                        echo "<br>";?>
                    <br>

                    <font color="#900C3F" face="verdana" size="2">
                        <?php echo '<b>' . $ia_filingno_case_no[$i]['case_no'] . '</b>'; ?>
                    </font>
                    <?php
}
                }
            }

            if (!empty($connected_cases)) {
                $len_array = count($connected_cases);
                echo "<br>";
                //echo "<hr>";
                echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size='2'><b>with</b></font>";
                for ($i = 0; $i < $len_array; $i++) {
                    if ($connected_cases[$i]['case_no'] != '') {
                        $fileupload = $causelist->getFileupload($connected_cases[$i]['fil_no'], $dbonline, $connected_cases[$i]['incase_type']);

                        ?>
                    <br>
                    <font color="#900C3F" face="verdana" size="2">
                        <?php echo '<b>' . $connected_cases[$i]['case_no'] . '</b>'; ?>
                    </font>
                    <br>
                    <?php
}
                }
            }
            ?>
                </td>

                <td><?php echo $purpose_now; ?></td>

                <td><?php
$sectionc = $causelist->getSections($filing_no, $dbonline);
            echo $sectionc . "<br>";
            if ($crpf != '') {
                echo "(" . $crpf . ")";
            }

            ?></td>

                <td><?php
if (!empty($in_case_nor)) {
                $len_array = count($in_case_nor);
                for ($i = 0; $i < $len_array; $i++) {
                    $petnamec = $causelist->getPetname($in_case_nor[$i]['fil_no'], $schemas, $db, $dbonline);
                    $resnamec = $causelist->getResname($in_case_nor[$i]['fil_no'], $schemas, $db, $dbonline);
                }
            } else {
                $petnamec = $causelist->getPetname($filing_no, $schemas, $db, $dbonline);
                $resnamec = $causelist->getResname($filing_no, $schemas, $db, $dbonline);
            }
            //$dit = 'And';

            $petpartyc = '';
            if ($case_type == 14 || $case_type == 15) {
                $petpartyc = $causelist->getPetParty($filing_no, $dbonline);
            }
            ?>
                    <center>
                        <font face="Verdana" size="2"><?php echo strtoupper($petnamec) . '<br>';
            if (!empty($petpartyc)) {
                foreach ($petpartyc as $key => $value) {
                    echo '<center><b>And</b></center><br>';
                    print_r(strtoupper($value));
                    echo '<br>';
                }
            }
            echo '<center><font color="red">Vs.</font></center><br>' . strtoupper($resnamec) ?></font>
                    </center>
                </td>

                <td><?php
$st12 = $dbonline->prepare("select distinct(rep_code) from e_more_representative where filing_no=? and party_flag='P' and display='t'");
            $st12->bindParam(1, $filing_no, PDO::PARAM_STR);

            $st12->execute();
            while ($row12 = $st12->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

                $adv_id = htmlspecialchars($row12['rep_code']);

                $stqq12 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
                $stqq12->bindParam(1, $adv_id, PDO::PARAM_INT);
                $stqq12->execute();
                $pet_advname22 = $stqq12->fetchColumn();
                ?>
                    <font face="Verdana" size="2">
                        <?php
echo strtoupper($pet_advname22) . '<br>';
            }
            ?>
                        <?php
$st121 = $dbonline->prepare("select distinct(rep_code) from e_more_representative where filing_no=? and party_flag='R' and display='t'");
            $st121->bindParam(1, $filing_no, PDO::PARAM_STR);

            $st121->execute();
            while ($row121 = $st121->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

                $res_adv_id = htmlspecialchars($row121['rep_code']);

                $stqq121 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
                $stqq121->bindParam(1, $res_adv_id, PDO::PARAM_INT);
                $stqq121->execute();
                $res_advname22 = $stqq121->fetchColumn();
                ?>
                        <font face="Verdana" size="2">
                            <?php
echo strtoupper($res_advname22) . '<br>';
            }
            $sset_priority = $priority_serial;
            if ($priority_serial == '999' || $priority_serial == '') {
                $sset_priority = $iii;
            }

            ?></td>
                <td>
                    <input type="hidden" name="filing_no[]" id="filing_no_<?php echo $filing_no_new; ?>"
                        value="<?php echo $filing_no_new; ?>">
                    <input type="hidden" name="purpose_<?php echo $filing_no_new; ?>"
                        id="purpose_<?php echo $filing_no_new; ?>" value="<?php echo $purpose; ?>">
                    <input style="width:50px" type="number" name="peiority_set_<?php echo $filing_no_new; ?>"
                        id="peiority_set_<?php echo $filing_no_new; ?>" value="<?php echo $sset_priority; ?>"></td>
            </tr>
            <?php
$snoc++;
            $iii++;
        }

        ?>
            <tr>
                <td colspan="7">
                    <input type="button" onclick="fn_set_priority()" value="Set Priority" class="btn btn-default">
                </td>
            </tr>
            <tr>


                <td colspan="7">
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                </td>
            </tr>


            <?php
//$snoc ++;
        $counter++;
    }
}

?>
        </table>
    </form>
</body>

<script>
function fn_set_priority() {
    var listing_date = $("#from_id_set_priority #listing_date").val();
    var bench_no = $("#from_id_set_priority #bench_no").val();
    var court_no = $("#from_id_set_priority #court_no").val();
    var inps = document.getElementsByName('filing_no[]');
    var data_arr = {};
    for (var i = 0; i < inps.length; i++) {
        var filing_no = inps[i].value;
        var temp_arr = {};
        var purpose = $("#from_id_set_priority #purpose_" + filing_no).val();
        var priority_set = $("#from_id_set_priority #peiority_set_" + filing_no).val();
        temp_arr['filing_no'] = filing_no;
        temp_arr['purpose'] = purpose;
        temp_arr['priority_set'] = priority_set;
        data_arr[i] = temp_arr;
    }
    var data = {};
    data['action'] = 'set_cases_priority';
    data['data_arr'] = data_arr;
    data['listing_date'] = listing_date;
    data['bench_no'] = bench_no;
    data['court_no'] = court_no;
    $.ajax({
        type: "POST",
        url: "set_priority_ajax.php",
        data: data,
        dataType: "html",
        success: function(data) {
            console.log(data);
            alert(data);
            location.reload();
        },
        error: function(request, error) {
            console.log("Somethin error.");
        }
    });

}
</script>


</html>
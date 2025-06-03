<!doctype html>
<html lang="en">
<head>
    <?php require_once '../mis/draft_causelist_new.php';
	$schema_id = $_SESSION['schema_idccc'];



?>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="description" content="The HTML5 Herald">
    <meta name="author" content="SitePoint">
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../export/dist/jquery.table2excel.js"></script>
    <script src="../export/dist/jquery.tabletoCSV.js"></script>
    <script>
    function OpenDMSForm(url, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10) {
        document.getElementById("frm").action = url;
        document.getElementById("itemno1").value = val1;
        document.getElementById("applno1").value = val2;
        document.getElementById("courtno1").value = val3;
        document.getElementById("caseno1").value = val4;
        document.getElementById("casetype1").value = val5;
        document.getElementById("partyname1").value = val6;
        document.getElementById("title1").value = val7;
        document.getElementById("status1").value = val8;
        document.getElementById("j_key1").value = val9;
        document.getElementById("j_securityKey1").value = val10;
        document.getElementById("frm").submit();
    }

    function toExcel(myMessage) {
        $(".table2excel").table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "myFileName" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }

    function toCsv(myMessage) {
        $(".table2excel").tableToCSV({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "myFileName" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
            fileext: ".csv",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }
    </script>

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
<form action="" method="POST" target="_blank" id="frm">
    <input type="hidden" id="itemno1" name="itemno"  value=""/>
    <input type="hidden" id="applno1" name="applno" value=""/>
    <input type="hidden" id="courtno1" name="courtno" value=""/>
    <input type="hidden" id="caseno1" name="caseno" value="" />
    <input type="hidden" id="casetype1" name="casetype" value=""/>
    <input type="hidden" id="partyname1" name="partyname" value="">
    <input type="hidden" id="title1"name="title" value="">
    <input type="hidden" id="status1" name="status" value="">	
    <input type="hidden" id="j_key1"name="j_key" value=""> 
    <input type="hidden" id="j_securityKey1" name="j_securityKey" value="">
   
</form>

    <table cellspacing="1" cellpadding="1" border="0" width="95%" align="center">
        <div id="testdiv" style="visibility: visible;"><a href="javascript:window.print();">
                <font size="4" color="red">
                    Print</font>
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:window.toExcel()"></b></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:window.toCsv()"><b>EXPORT CSV</b></a>
        </div>
    </table>

    <a href="./causelist.php">
        <font face="Verdana" color="red" size="3">
            <center><b>BACK</b></center>
        </font>
    </a>
    <center><span><b><?php echo $causelist->getCauselistTitle(); ?></b></span></center>
    <center><span><b><?php echo $causelist->getCauselistRegion(); ?></b></span></center>
	<?php
	if($schema_id==10)
	{
	?>
    <center><span><b>PRINCIPAL BENCH</b></span></center>
	<?php
	}
	?>
    <center><span><b><u><?php echo $causelist->getCauselistType(); ?></u></b></span></center>
    <center><span><b><?php echo "Court No " . $courtnoc; ?></b></span></center>
    <br>
    <span style="float:right;"><b>DATE: <?php echo " " . $causelist->getCourtdate(); ?> </b></span><br>
    <span style="float:right;"><b>TIME: 10.30 AM </b></span><br>
    <br>
    <?php
if (!empty($pjudgename)) {
    ?>
    <span><b><u>CORAM1:</u></b></span><span>1.</span><span><b><?php echo $pjudgename[0]['pjudgename']; ?></b></span><br>
    <?php
}
$jcount = 2;
foreach ($judgesname as $i => $item) {
    ?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $jcount;
    $jcount++; ?>.</span><span><b><?php echo $judgesname[$i]['alljudgesname']; ?></b></span><br>
    <?php
}
?>
    <table class="responstable table2excel">
        <tr>
            <th style="width: 40px;">S. No.</th>
            <th data-th="Driver details" style="width:220px;"><span>Case No.</span></th>
            <th style="width:200px;">Purpose</th>
            <th style="width:150px;">Section</th>
            <th style="width:380px;">Name of Parties&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th style="width: 200px;">Name of Legal Practitioner</th>
            <th style="width:150px;">Remarks</th>
        </tr>
        <?php





            






$counter = 0;
$sql = "select distinct(b.bench_no),b.bench_nature from $schemas.bench b, $schemas.case_allocation_temp t where b.court_no='$court_no' and
b.from_list_date='$list_date_db' and t.listing_date='$list_date_db' and t.court_no='$court_no' and b.bench_no=t.bench_no order by b.bench_no asc";
foreach ($db->query($sql) as $row) {
    $bench_noc = $row['bench_no'];
    $bench_naturec = $row['bench_nature'];
    $pjudgenamenxt = $causelist->getpJudges($schemas, $db, $list_date_db, $bench_noc, $courtnoc, $table);
    $presiding = $pjudgenamenxt[0]['presiding'];
    $judgesnamenxt = $causelist->getallJudges($schemas, $db, $list_date_db, $bench_noc, $courtnoc, $presiding, $table);
    if ($counter != 0 && !empty($pjudgenamenxt)) {
        ?>

        <tr>
            <td colspan='7' align='left'>
                <span><b><u>CORAM:</u></b></span><span>1.</span><span><b><?php echo $pjudgenamenxt[0]['pjudgename']; ?></b></span><br>
                <?php
$jcount = 2;
        foreach ($judgesnamenxt as $i => $item) {
            ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $jcount;
            $jcount++; ?>.</span><span><b><?php echo $judgesnamenxt[$i]['alljudgesname']; ?></b></span><br>
                <?php
}
        $v1 = ceil($snoc / 100) * 100;
        $v1 = $v1 + 1;
        $causelist->setSno($v1);

    }
    ?>

                <?php
$counterord = 0;
    $countersup = 0;
    $counterpri = 0;
    $filing_no = '';
    $status = 'P';
    $sql_allocation = "select  * from
$schemas.cause_list_heading_sequence order by priority asc";
    $sth_j12c = $db->prepare($sql_allocation);
    $sth_j12c->execute();
    while ($row1 = $sth_j12c->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $pri_seq = $row1['priority'];
        $pri_name = $row1['name'];
        if ($pri_seq == 1) {
            $sql_allocation1 = "select d.pet_name,d.res_name,a.priority_serial,a.pri_ord,a.filing_no ,a.remarks,a.purpose,d.case_type,a.remark_type,
            ct.case_type_desc,ct.short_name,ct.id,m.purpose_name,m.purpose_priority,m.purpose_code
             from $schemas.$table a

    inner join $schemas.case_detail d on d.filing_no=a.filing_no
	inner join case_type ct on ct.id=d.case_type
    inner join $schemas.master_purpose m on m.purpose_code=a.purpose

where a.listing_date='$list_date_db' and
a.bench_no='$bench_noc' and a.court_no='$courtnoc'
and a.purpose='12'and (a.pri_ord IS NULL OR a.pri_ord='')
order by m.purpose_priority asc,a.priority_serial asc";
        }
        if ($pri_seq == 2) {
            $sql_allocation1 = "select d.pet_name,d.res_name,a.priority_serial,a.pri_ord,a.filing_no ,a.remarks,a.purpose,d.case_type,a.remark_type,
            ct.case_type_desc,ct.short_name,ct.id,m.purpose_name,m.purpose_priority,m.purpose_code
             from $schemas.$table a
             inner join $schemas.case_detail d on d.filing_no=a.filing_no
             inner join case_type ct on ct.id=d.case_type
             inner join $schemas.master_purpose m on m.purpose_code=a.purpose
where a.listing_date='$list_date_db' and
a.bench_no='$bench_noc' and a.court_no='$courtnoc'
and a.pri_ord='P' order by m.purpose_priority asc, a.priority_serial asc";
            $v1 = ceil($snoc / 100) * 100;
            $v1 = $v1 + 1;
            $causelist->setV1($v1);
        }
        if ($pri_seq == 3) {
            $sql_allocation1 = "select d.pet_name,d.res_name,a.priority_serial,a.pri_ord,a.filing_no ,a.remarks,a.purpose,d.case_type,a.remark_type,
            ct.case_type_desc,ct.short_name,ct.id,m.purpose_name,m.purpose_priority,m.purpose_code
             from $schemas.$table a
             inner join $schemas.case_detail d on d.filing_no=a.filing_no
             inner join case_type ct on ct.id=d.case_type
             inner join $schemas.master_purpose m on m.purpose_code=a.purpose
where a.listing_date='$list_date_db' and
a.bench_no='$bench_noc' and a.court_no='$courtnoc'
and (a.pri_ord IS NULL OR a.pri_ord='') and a.purpose!='12'
order by m.purpose_priority asc, a.priority_serial asc";
        }
        $sth_j12c1 = $db->prepare($sql_allocation1);
        $sth_j12c1->execute();
        $count_heading = $sth_j12c1->rowCount();

        //  echo '<pre>';
        //  $dadadad = $sth_j12c1->fetchAll();

        if ($count_heading > 0) {
            ?>
        <tr>
            <td colspan="7" align="left"><b><?php echo $pri_name ?></b></td>
        </tr>
        <?php
}

        while ($row2 = $sth_j12c1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            //    echo '<pre>';

            $filing_no = $row2['filing_no'];
            $remarks = $row2['remarks'];
            $purpose = $row2['purpose'];
            $case_type = $row2['case_type'];
            $crpf = $row2['remark_type'];
            $pri_ord = $row2['pri_ord'];
            $petnamec = htmlentities($row2['pet_name']);
            $resnamec = htmlentities($row2['res_name']);
            $purpose_now = $row2['purpose_name'];

            // $petnamec .= $causelist->getPetname($filing_no, $schemas, $db, $dbonline);
            // $resnamec .= $causelist->getResname($filing_no, $schemas, $db, $dbonline);
            //  $purpose_now = $causelist->getPurpose($list_date_db, $bench_noc, $courtnoc, $purpose, $db, $schemas);
            $fileupload = $causelist->getFileupload($filing_no, $dbonline, $case_type);

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

/* stop code to get mall IA of main case */

/*start of code to get in_rst_cases and main case of CA*/
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

                }
            }
/*stop of code to get in_rst_cases and main case of CA*/

/*start of code to get all CA of CP*/
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
/*stop of code to get all CA of CP*/

/*start of code to get all Connected Cases*/
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

            $snoc = $causelist->getSno($purpose, $snoc);
            ?>
        <tr>
            <td><?php echo $snoc . "."; ?></td>

            <td>




                <?php 
           
           $causelist->fn_url_link($snoc, $fileupload, $courtnoc, $main_case_no, $petnamec, $resnamec);    
if (!empty($in_case_nor) && is_array($in_case_nor)) {
                //$len_array = count($in_case_nor);
                foreach ($in_case_nor as $value) {
                    if ($value['case_no'] != '') {
                        $fileupload = $causelist->getFileupload($value['fil_no'], $dbonline, $value['incase_type']);
                        if ($case_type != 16) {
                            echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size='2'><b>In</b></font>";
                        } else {
                            echo "<br>";
                        }
                        $causelist->fn_url_link($snoc, $fileupload, $courtnoc, $value['case_no'], $petnamec, $resnamec);                
}
                }
            }

            $causelist->fn_case_no_data($dbonline,$in_case_nocp,$snoc,$courtnoc,$petnamec,$resnamec);


            if ($ia_main_filingno != '') {
                $fileupload = $causelist->getFileupload($ia_main_filingno, $dbonline, $ia_main_case_type);
                ?>
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="2"><b>In</b>
                </font>


                <?php 
                 $causelist->fn_url_link($snoc, $fileupload, $courtnoc, $ia_main_case_no, $petnamec, $resnamec);
              
$ia_main_filingno = '';
            }

            $causelist->fn_case_no_data($dbonline,$ia_filingno_case_no,$snoc,$courtnoc,$petnamec,$resnamec);
            if (!empty($connected_cases) && is_array($connected_cases)) {
                echo "<br>";
                echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size='2'><b>with</b></font>";
                foreach ($connected_cases as $value) {
                    if ($value['case_no'] != '') {
                        $fileupload = $causelist->getFileupload($value['fil_no'], $dbonline, $value['incase_type']);
                        $causelist->fn_url_link($snoc, $fileupload, $courtnoc, $value['case_no'], $petnamec, $resnamec);
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
             //    if (!empty($in_case_nor)) {
            //         $len_array = count($in_case_nor);
            //         for ($i = 0; $i < $len_array; $i++) {
            //             $petnamec = $causelist->getPetname($in_case_nor[$i]['fil_no'], $schemas, $db, $dbonline);
            //             $resnamec = $causelist->getResname($in_case_nor[$i]['fil_no'], $schemas, $db, $dbonline);
            //         }
            //     } else {
            //         $petnamec = $causelist->getPetname($filing_no, $schemas, $db, $dbonline);
            //         $resnamec = $causelist->getResname($filing_no, $schemas, $db, $dbonline);
            //     }
            $petpartyc = '';
            if ($case_type == 14 || $case_type == 15) {
                $petpartyc = $causelist->getPetParty($filing_no, $dbonline);
            }
            ?>
                <center>
                    <font face="Verdana" size="2"><?php 
					$petnamec=htmlspecialchars_decode($petnamec,ENT_NOQUOTES);
					
					echo strtoupper($petnamec) . '<br>';
            if (!empty($petpartyc)) {
                foreach ($petpartyc as $key => $value) {
                    echo '<center><b>And</b></center><br>';
                    echo strtoupper($value);
                    echo '<br>';
                }
            }
			$resnamec=htmlspecialchars_decode($resnamec,ENT_NOQUOTES);
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
            ?></td>
            <td></td>
        </tr>
        <?php
$snoc++;
        }
        $counter++;
    }
}?>
    </table>
</body>

</html>

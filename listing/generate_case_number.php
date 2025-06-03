<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
$no = $_REQUEST['no'];
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php";
session_start();
$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
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
    $filing_no_next = $_REQUEST['filing_no_next'];
    $hash1 = htmlspecialchars(base64_decode($filing_no_next));
    $hash1 = explode("-", $hash1);
    $filing_no_fou = $hash1[0];
    $token_fou = $hash1[1];
    // This code not use next time .......    Schema session create Hear....
    $location_access = $_SESSION['location'];
    $sessionUserType = htmlspecialchars($_SESSION['id']);
    $curYear = htmlspecialchars(date("Y"));
    $curMonth = htmlspecialchars(date("m"));
    $curDay = htmlspecialchars(date("d"));
    $cur_date = "$curYear-$curMonth-$curDay";
    $cur_date1 = "$curDay/$curMonth/$curYear";
//include '../inheader.php';
    //include '../insidebar.php';
    ?>
<style>
select {
    border: solid 1px #ccc;
    border-radius: 5px;
    padding: 7px 14px;
    margin-bottom: 10px;
    width: 200px;
}

input {
    border: solid 1px #ccc;
    border-radius: 5px;
    padding: 7px 14px;
    margin-bottom: 10px;
    width: 200px;
}
</style>
<!-- Content Wrapper. Contains page content -->

<script language="javascript">
function submitForm() {
    with(document.frm) {
        action = "generate_case_number.php";
        submit();
    }
}

function validate() {
    with(document.frm) {
        if (case_no.value == "") {
            alert("Enter  Case Number");
            case_no.focus();
            return false;
        }
        //if(isNaN(case_no.value) == true)
        //{
        //alert("Please Enter Numeric Case No.");
        //case_no.select();
        //return false;
        //}

        
        if (case_year.value == "") {
            alert("Enter 4 digit Case Registration Year");
            case_year.focus();
            return false;
        }
        if (case_year.value < 4) {
            alert("Enter 4 digit Case Registration Year");
            case_year.focus();
            return false;
        }
        if (isNaN(case_year.value) == true) {
            alert("Please Enter Numeric Case Year");
            case_year.select();
            return false;
        }

        if (court_no.value == "") {
            alert("Select Court No");
            court_no.focus();
            return false;
        }



        
        if (regis_date.value == "") {
            alert("Please Enter Registration Date");
            regis_date.focus();
            return false;
        }

    }

}

function SetBg(txt) {
    txt.style.backgroundColor = '#ffff99';
}

function UnSetBg(txt) {
    txt.style.backgroundColor = 'white';
}









//  END
</script>





</head>

<body class="hold-transition skin-blue sidebar-mini">

    <?php

    if ($_REQUEST[no] != '') {
        $filing_no_link = $_REQUEST[no];

    }

    $st1 = $db->prepare("select * from $schemas.case_detail where  filing_no='$filing_no_link'");
    $st1->execute();
    while ($row = $st1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $case_type1 = htmlspecialchars($row['case_type']);
        $pet_name = htmlspecialchars($row['pet_name']);
        $res_name = htmlspecialchars($row['res_name']);
        $location_code = htmlspecialchars($row['location_code']);
        $case_title = $pet_name . "<br> VS  <br>" . $res_name;
        $ia_flag = htmlspecialchars($row['ia_flag']);
    }
    ?>



    <div class="content-wrapper">
        <section class="content">
            <div class="box">
                <div class="box-header with-border">


                    <table class="table" align="center">
                        <tr>
                            <td valign="top" align="right" colspan="16">
                                <center>
                                    <b>
                                        <font face="Verdana" size="3"><u>GENERATE CASE NUMBER </u></font>
                                    </b>
                            </td>
                        </tr>


                        <?php

    $msghash = $_REQUEST['msghash'];
    if ($msghash != '') {
        $msghashz = (base64_decode($msghash));

        $msghashz = explode("-", $msghashz);

        $msg1 = $msghashz[0];
        $case_type = $msghashz[1];
        $case_year = $msghashz[2];

        if ($msghashz != '') {
            ?>
                        <tr>
                            <td height="30" align="center" cellpadding="0" colspan="16">
                                <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
                                    <span class="error"> <b> <?php echo htmlspecialchars($msg1); ?></span>
                                </font>
                            </td>
                        </tr>
                        <?php
}
    }

    ?>


                        <tr>
                            <td valign="top" align="center" colspan="16">
                                <font face="Verdana" size="2">Fields marked with a <font color='red'>*
                                    </font> are compulsory.</font>
                            </td>
                        </tr>
                        <tr>
                            <td height="30" align="center" colspan="16">

                                <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="black">
                                    <b> <?php echo "SEARCH BY CASE TYPE/CASE NUMBER/CASE YEAR"; ?></b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <font face="Verdana" size="2" color="red">
                                    <?php echo $case_title; ?>
                                </font>
                            </td>
                        </tr>


                    </table>




                    <form name="frm" method="POST" action="generate_case_number_action.php">


                        <table class="table" align="center">
                            <input type="hidden" name="no" value="<?php echo $_REQUEST[no]; ?>" />


                            <tr>
                                <td colspan="16" align="left">

                                    <font face="Verdana" size="2" color="red">*</font>
                                    Bench:
                                </td>
                                <td colspan="16" align="left">
                                    <select name="bench_type">

                                        <?php

    $st = $db->prepare("select * from $schemas.bench_location where display = 'TRUE'  order by bench_location_name asc");
    $st->execute();
    while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $ctc = htmlspecialchars($row['bench_location_code']);
        if ($bench_type == $ctc) {
            print "<option value=" . htmlspecialchars($row['bench_location_code']) . " selected>" . htmlspecialchars($row['bench_location_name']) . "</option>";
        } else {
            print "<option value=" . htmlspecialchars($row['bench_location_code']) . ">" . htmlspecialchars($row['bench_location_name']) . "</option>";
        }
    }

    ?>

                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="16" align="left">
                                    <font face="Verdana" size="2" color="red">*</font>
                                    Case Type:
                                </td>
                                <td colspan="16" align="left">
                                    <?php if ($ia_flag == 1) {?>
                                    <select name="case_type">
                                        <option value="4" selected>IA</option>
                                    </select>
                                    <?php } else {?>
                                    <select name="case_type">
                                        <?php
$st = $db->prepare("select * from case_type where status = 't' and id='$case_type1' order by case_type_desc_cis asc");
        $st->execute();
        while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $ctc = htmlspecialchars($row['id']);
            if ($case_type == $ctc) {
                print "<option value=" . htmlspecialchars($row['id']) . " selected>" . htmlspecialchars($row['case_type_desc_cis']) . "</option>";
            } else {
                print "<option value=" . htmlspecialchars($row['id']) . ">" . htmlspecialchars($row['case_type_desc_cis']) . "</option>";
            }
        }

        ?>

                                    </select>
                                    <?php }?>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="16" align="left">
                                    <font face="Verdana" size="2" color="red">*</font>
                                    <font face="Verdana" size="2">Case No:</font>
                                </td>
                                <td colspan="16" align="left">
                                    <input type="text" id="cno" maxlength="7" autocomplete="off" size="6" name="case_no"
                                        value="<?php print htmlspecialchars(ltrim($case_no, 0));?>" />&nbsp;&nbsp;

                                </td>
                            </tr>
                            <tr>
                                <td colspan="16" align="left">
                                    <font face="Verdana" size="2" color="red">*</font>
                                    <font face="Verdana" size="2">Case Year:</font>
                                </td>
                                <td colspan="16" align="left">

                                    <select id="cy" name="case_year" id="case_year">
                                        <option value="">Select Case Year </option>
                                        <?php for ($i = 2000; $i < 2050; $i++) {?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php }?>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="16" align="left">
                                    <font face="Verdana" size="2" color="red">*</font>
                                    <font face="Verdana" size="2">Select Court No:</font>
                                </td>
                                <td colspan="16" align="left">

                                    <select name="court_no" id="court_no">
                                        <option value="">Select</option>
                                        <?php for ($i = 1; $i <= 10; $i++) {?>
                                        <option value="<?php echo $i; ?>">Court <?php echo $i; ?> </option>
                                        <?php }?>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="16" align="left">


                                    <script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
                                    <link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css" />
                                    <script src="../plugins/jQueryUI/jquery-ui.js"></script>
                                    <script src="../plugins/jQueryUI/date.js"></script>

                                    <font face="Verdana" size="2" color="red">*</font>
                                    <font face="Verdana" size="2">Registration Date:</font>
                                </td>
                                <td colspan="16" align="left">
                                    <input type="text" readonly autocomplete="off" name="regis_date" size="10"
                                        maxlength="10" class="datepicker"
                                        value="" />
                                </td>

                            </tr>
                            <input type="hidden" name="filing_no"
                                value="<?php echo htmlspecialchars(htmlentities($filing_no_link)); ?>" />
                            <tr>
                                <td colspan="16" align="left">
                                    &nbsp;
                                </td>
                                <td align="center">
                                    <input type="submit" name="submit" value="Submit" class="button btn-primary"
                                        onClick="return validate();">
                            </tr>

                            </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </section>
    </div>
</body>

<?php } else {
echo 'Session Expired. Please login Again';
	}?>
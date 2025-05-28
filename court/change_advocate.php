
<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
session_start();

//print_r($_SESSION);
$_SESSION['user'];
$_SESSION['location'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {


    function case_type($db)
    {
        $data_main = array();
        $st = $db->prepare("select * from case_type where display='TRUE' order by id desc");
        $st->execute();
        $i = 0;
        while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
            $tmp = array();
            if ($row['display'] == 'TRUE') {
                $tmp['case_type_desc'] = $row['case_type_desc'];
                $tmp['act_id'] = $row['act_id'];
                $tmp['inter_act_id'] = $row['inter_act_id'];
                $tmp['display'] = $row['display'];
                $tmp['short_name'] = $row['short_name'];
                $tmp['id'] = $row['id'];
                $data_main[$i] = $tmp;
                $i++;
            }
        }
        return $data_main;
    }

    $case_type_data = case_type($db);

    ?>
<?php
    include '../inheader.php';
   // include '../insidebar.php';
    ?>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<style>
.content-wrapper {
    padding: 15px 35px;
}

.load_container {
    background: rgba(0, 0, 0, 0.50);
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    z-index: 99;
    left: 0;
    display: none;
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

.error {
    color: red;
}
</style>




<div class="load_container">
    <img class="loader" src="images/loading-indicator.gif">
</div>





<div class="content-wrapper">
    <div class="row" style="">
        <div class="box box-success">
            <div class="box-body">
                <section class="content-header">
                    <h1>Add/Update Advocate</h1>
                    <ol class="breadcrumb">
                        <li><a href="index.php"><i class="fa fa-dashboard"></i> HOME</a></li>
                        <li><a href="change_advocate.php">Add/Update Advocate</a></li>
                    </ol>
                </section>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box box-success">
            <div class="box-body">
                <form method="post" id="case_proce_update_form_id">
                    <div class="modal-body with-padding">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Select Search BY :</label>
                                    <select required="required" class="form-control" name="search_by" id="search_by"
                                        onchange="fn_case_dfr_type(this.value)">
                                        <option value="">Select Search BY</option>
                                        <option value="1">Filing. No</option>
                                        <option selected value="2">Case No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="case_dfr_div_id"></div>
                    </div>

                </form>





                <div id="table_div_res4444" style="display: block">

                    <table class="table">
                        <tbody>

                            <tr>
                                <td colspan="8" id="ddsdsdsdsdsd"
                                    style="font-size: large; text-align: center; color: red;"></td>
                            </tr>

                        </tbody>
                    </table>

                </div>


                <div class="table-responsive" id="table_div_res" style="display: none">
                    <section class="content">
                        <div class="row">
                           
                                <div class="col-sm-12" id="content_div_id">

                                </div>
                        
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>


<form action="https://efilingreat.up.gov.in/dmsupreat/dms-service-calls?" method="POST" target="_blank" id="frm_dms">
    <input type="hidden" id="e_filing_no" name="e_filing_no" value="" />
    <input type="hidden" id="case_no" name="case_no" value="" />
    <input type="hidden" id="cause_title" name="cause_title" value="" />
    <input type="hidden" id="court_no" name="court_no" value="" />
    <input type="hidden" id="item_no" name="item_no" value="1" />
    <input type="hidden" id="step" name="step" value="5" />
</form>




<script src="../dist/js/adminlte.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
function OpenDMSForm(court_no) {
    var filing_no = $("#filing_no_change").val();
    document.getElementById("e_filing_no").value = filing_no;
    var case_no = $("#cause_no_" + filing_no).val();
    document.getElementById("case_no").value = case_no;
    var cause_title = $("#cause_title_" + filing_no).val();
    document.getElementById("cause_title").value = cause_title;
    document.getElementById("court_no").value = court_no;
    document.getElementById("frm_dms").submit();
}

$(document).ready(function() {
    fn_case_dfr_type(2);
});




function fn_change_adv(change_type) {
    $("#update_advocate_list_div").hide();
    if(change_type == 'U') { 
        $("#update_advocate_list_div").show();
		$('#update_advocate_list').select2();
    }
 

}


function fn_showing_data(party_value) {
	$("#after_select_data").html("Loading data ....");
    if (party_value == '') {
        $("#after_select_data").html('');
    }
    var data = {};
    data['action'] = 'showing_data';
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: data,
        dataType: "html",
        success: function(data) {
            $("#after_select_data").html(data);
			$('#add_advocate_list').select2();
			
        },
        error: function(request, error) {
            alert("something error . please try again");
        }
    });
}



function fn_get_party(party_type) {
    $("#after_select_data").html('');
    var data = {};
    data['action'] = 'get_party_by_filing_no';
    data['filing_no'] = $("#filing_no_change").val();
    data['party_type'] = party_type;
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: data,
        dataType: "html",
        success: function(data) {
            $("#select_party_option").html(data);
        },
        error: function(request, error) {
            alert("something error . please try again");
        }
    });
}


function fn_change_caseno_entry(filing_no, defect_listed, case_type) {
    $('.load_container').fadeIn(200);
    $("#changecase_entry_form_id #filing_no_change").val(filing_no);
    $("#changecase_entry_popup").modal('show');
    $('.load_container').fadeOut(200);
}

function fn_save_update_case_no() {
    var valid = $("#change_advocate_form_id").valid();
    if (valid) {
        var data = {};
        data['action'] = 'update_add_advocte';
        data['select_party_type'] = $("input[name='select_party_type']:checked").val();
        data['select_change_type'] = $("input[name='select_change_type']:checked").val();
        data['filing_no'] = $("#change_advocate_form_id #filing_no_change").val();
        data['select_party_option'] = $("#change_advocate_form_id #select_party_option").val();
        data['add_advocate_list'] = $("#change_advocate_form_id #add_advocate_list").val();
        data['update_advocate_list'] = $("#change_advocate_form_id #update_advocate_list").val();
        data['remarks_changes'] = $("#change_advocate_form_id #remarks_changes").val();
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: data,
            dataType: "html",
            success: function(data) {
                alert(data);
                 location.reload(true);
            },
            error: function(request, error) {
                alert("something error . please try again");
            }
        });
    }
}

function fn_case_dfr_type(value) {
    $("#ddsdsdsdsdsd").empty();
    $('.load_container').show();
    var data = {};
    data['action'] = 'select_type';
    data['type_id'] = value;
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: data,
        dataType: "html",
        success: function(data) {
            $("#case_dfr_div_id").html(data);
            $("#table_div_res").hide();
            $("#content_div_id").empty();
            $('.load_container').hide();
        },
        error: function(request, error) {
            alert("something error. please try again");

        }
    });
}

function fn_search_by_case_dfr() {
    var valid = $("#case_proce_update_form_id").valid();
    if (valid) {
        $('.load_container').show();
        var search_by = $("#case_proce_update_form_id #search_by").val();
        var data = {};
        data['action'] = 'change_advocate';
        data['search_by'] = $("#case_proce_update_form_id #search_by").val();
        if (search_by == '1') {
            data['dairy_no'] = $("#case_proce_update_form_id #dairy_no").val();
            //  data['dairy_year'] = $("#notice_form_id #dairy_year").val();
        } else if (search_by == '2') {
            data['case_type'] = $("#case_proce_update_form_id #case_type").val();
            data['case_no'] = $("#case_proce_update_form_id #case_no").val();
            data['case_year'] = $("#case_proce_update_form_id #case_year").val();
        }
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: data,
            dataType: "html",
            success: function(data) {
                $("#message_results").empty();
                if (data != 1) {
                    $("#table_div_res4444").hide();
                    $("#ddsdsdsdsdsd").empty();
                    $("#table_div_res").show();
                    $("#content_div_id").html(data);
                    $('.load_container').hide();
                } else {
                    $("#table_div_res").hide();
                    $("#table_div_res4444").show();
                    $("#ddsdsdsdsdsd").html('Data Not Found. Please Try Again.');
                    $('.load_container').hide();
                }

            },
            error: function(request, error) {
                alert("something error");
                $('.load_container').hide();
            }
        });
    }


}

function fn_unlick_adv(id,filing_no,party_flag){
	var data = {};
    data['action'] = 'unlink_adv';
    data['filing_no'] = filing_no;
    data['party_type'] = party_flag;
    data['id'] = id;
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: data,
        success: function(data) {
            
        },
        error: function(request, error) {
            alert("something error . please try again");
        }
    });
}

</script>

<script src="../bower_components/select2/dist/js/select2.min.js"></script>
<?php } ?>
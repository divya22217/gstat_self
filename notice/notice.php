<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL); 
session_start();
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


    ?>
<?php
    include '../inheader.php';
    ?>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<style>
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
button.btn-none {
    background-color: transparent;
    border: transparent;
    padding: 0;
}
</style>
<div class="load_container">
    <img class="loader" src="images/loading-indicator.gif">
</div>


<div class="content-wrapper">
    <div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
        <div class="box">
            <div class="box-header">
                <h1 class="bg">
                    <center>Notice/Summons Generation</center>
                </h1>
            </div>
            <div class="box-body">
                <form method="post" id="notice_form_id">
                    <div class="modal-body with-padding">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Select Search BY :</label>
                                    <select required="required" class="form-control" name="search_by" id="search_by"
                                        onchange="fn_case_dfr_type(this.value)">
                                        <option value="">Select Search BY</option>
                                        <option value="1">Filing No</option>
                                        <option selected value="2">Case No</option>
                                    </select>
                                </div>
                                <div class="col-sm-9" id="case_dfr_div_id"></div>
                            </div>
                        </div>

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


<div id="documents_s_modal" class="modal fade" role="dialog">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="document_name_id">Documnet Name</h4>
            </div>
            <div class="modal-body with-padding" id="document_div_pdf_file">
                teste test test test test test
            </div>
        </div>
    </div>
</div>


<div id="edit_group_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Generate Notice</h4>
            </div>
            <form method="post" id="generate_notice_form_id">

                <input type="hidden" name="filing_no_hid" id="filing_no_hid" value="">
                <div class="modal-body with-padding">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Notice Type :</label>
                                <select onchange="fn_notice_formate(this.value)" required="required"
                                    class="form-control" name="notice_type" id="notice_type">
                                    <option value=""> Select Notice Type</option>
                                    <?php
                                        $query = "select * from summon_type where status = '1' order by id asc";
                                        $query_prepare = $db->prepare($query);
                                        $query_prepare->execute();
                                        $i = 0;
                                        while ($row = $query_prepare->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                                            echo '<option value=' . $row["id"] . '>' . $row["name"] . '</option>';
                                            echo '<br>';
                                        }
                                        ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Select Party :</label>
                                <select onchange="fn_party_change(this.value)" required="required" class="form-control"
                                    name="party_flag" id="party_flag">
                                    <option value="R">Respondent</option>
                                    <option value="P"> Applicant/Appellant`s</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="onchange_div_id">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="generate_notice()" name="form_data" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </form>

        </div>

    </div>
</div>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<script src="../plugins/jQueryUI/jquery-ui.js"></script>

<script>
fn_case_dfr_type(2);

function view_document(doc_name, latest_file) {
    $("#document_name_id").html(doc_name);
    $("#documents_s_modal").modal('show');
    var loader = "<center><img src='../loader/loader.gif'></img></center>";
    var height = $("#accordion-1").height();
    height = height + 20;
    $.ajax({
        type: "POST",
        url: "../scrutiny/view_document.php",
        data: {
            path: latest_file
        },
        beforeSend: function() {
            $("#document_div_pdf_file").css('height', height);
            $("#document_div_pdf_file").html(loader);
        },
        success: function(data) {
            $("#document_div_pdf_file").css('height', height);
            $("#document_div_pdf_file").html(loader);
            $("#document_div_pdf_file").html(data);
            //alert("success");
        },
        error: function(textStatus, errorThrown) {
            $("#document_div_pdf_file").html('');
            alert("error");
        }
    });
}


function fn_party_change(party_flag) {
    var filing_no = $("#generate_notice_form_id #filing_no_hid").val();
    var notice_type = $("#generate_notice_form_id #notice_type").val();
    var data = {};
    data['action'] = 'party_drpdown';
    data['filing_no'] = filing_no;
    data['party_flag'] = party_flag;
    data['notice_type'] = notice_type;
    $.ajax({
        type: "POST",
        url: "ajax_notice.php",
        data: data,
        dataType: "html",
        success: function(data1212) {
            $("#generate_notice_form_id #to_party_id").html(data1212);
        },
        error: function(request, error) {
            alert("something error. please try again");
        }
    });
}


//  fn_notice_formate(1);

function fn_notice_formate(value) {
    var data = {};
    data['action'] = 'notice_formate_div';
    data['notice_type'] = value;
    $.ajax({
        type: "POST",
        url: "ajax_notice.php",
        data: data,
        dataType: "html",
        success: function(data11111) {
            fn_party_change('R');
            $("#onchange_div_id").html(data11111);
        },
        error: function(request, error) {
            alert("something error. please try again");
        }
    });

}

function generate_notice_popup(filing_no) {
    $("#edit_group_modal").modal('show');
    fn_notice_formate('');
    $("#generate_notice_form_id #filing_no_hid").val(filing_no);
}

function fn_trash_notice(notice_id) {
    if (confirm("Are you sure you want to delete this notice?")) {
        $('.load_container').show();
        var data = {};
        data['action'] = 'trash_notice';
        data['notice_id'] = notice_id;
        $.ajax({
            type: "POST",
            url: "ajax_notice.php",
            data: data,
            dataType: "html",
            success: function(data) {
                alert(data);
                fn_search_by_case_dfr();
                // location.reload(true);
            },
            error: function(request, error) {
                alert("something error. please try again");

            }
        });
    } else {
        console.log("Declined")
    }


}

function generate_notice() {
    var valid = $("#generate_notice_form_id").valid();
    if (valid) {
        var filing_no = $("#generate_notice_form_id #filing_no_hid").val();
        var data = {};
        data['action'] = 'generate_action';
        data['filing_no'] = filing_no;
        var notice_type = $("#generate_notice_form_id #notice_type").val();
        data['notice_type'] = $("#generate_notice_form_id #notice_type").val();
        data['party_flag'] = $("#generate_notice_form_id #party_flag").val();

        if (notice_type == '3' || notice_type == '4' || notice_type == '5' || notice_type == '6' || notice_type == '8') {
            data['against_order_date'] = $("#generate_notice_form_id #against_order_date").val();
            data['hearing_court_date'] = $("#generate_notice_form_id #hearing_court_date").val();
            data['case_fixed_hearing_on'] = $("#generate_notice_form_id #case_fixed_hearing_on").val();
        }
        if (notice_type == '1') {
            data['notice_html_data'] = $("#generate_notice_form_id #notice_html_data").val();
        }
        if (notice_type == '4' || notice_type == '5') {
            data['compilence'] = $('input[name="compilence"]:checked').val();


        }
        if (notice_type == '8') {
            data['time_to_present'] = $("#generate_notice_form_id #time_to_present").val();
            data['ask_document_person'] = $("#generate_notice_form_id #ask_document_person").val();
            data['name_of_documents'] = $("#generate_notice_form_id #name_of_documents").val();
        }
        data['to_party_id'] = $("#generate_notice_form_id #to_party_id").val();


        $.ajax({
            type: "POST",
            url: "ajax_notice.php",
            data: data,
            dataType: "json",
            success: function(data) {
                var datassss = {};
                alert('Notice Generated');
                fn_notice_formate('');
                fn_search_by_case_dfr();
                $("#edit_group_modal").modal('hide');
                $("#message_results").html(data);
                fn_search_by_case_dfr();
                //location.reload(true);
            },
            error: function(request, error) {
                console.log(error);
                alert("something error . please try again first");
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
        url: "ajax_notice.php",
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
    var valid = $("#notice_form_id").valid();
    if (valid) {
        $('.load_container').show();
        var search_by = $("#notice_form_id #search_by").val();
        var data = {};
        data['action'] = 'search_filing';
        data['search_by'] = $("#notice_form_id #search_by").val();
        if (search_by == '1') {
            data['dairy_no'] = $("#notice_form_id #dairy_no").val();
            //  data['dairy_year'] = $("#notice_form_id #dairy_year").val();
        } else if (search_by == '2') {
            data['case_type'] = $("#notice_form_id #case_type").val();
            data['case_no'] = $("#notice_form_id #case_no").val();
            data['case_year'] = $("#notice_form_id #case_year").val();
        }
        $.ajax({
            type: "POST",
            url: "ajax_notice.php",
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
	    error: function(request,error) {
		    console.log(error);
		    alert("something error");
		    $('.load_container').hide();
            }
        });
    }


}

function popitup(generate_id) {
    newwindow = window.open("notice_format.php?generate_id=" + generate_id, 'name', 'height=600,width=1000');
    if (window.focus) {
        newwindow.focus()
    }
    return false;
}


function complience(value){
  if(value === '1'){
    alert("You can't generate notice");
    location.reload(true);
  }
  
}



</script>


<?php } ?>

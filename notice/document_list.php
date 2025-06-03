<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
include("../db_inc2.php");
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

    include '../inheader.php';
    include '../insidebar.php';
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


#document_div_pdf_file {
            margin: 0;
            padding: 0;
            background: rgb(82, 86, 89);

        }

        .document_div_pdf_file_wrapper {
            margin: 0;
            padding: 0;
            background: rgb(82, 86, 89);
            position:relative;
        }

        .custom_toolbar {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            width: 100%;
            height: 0px;
        }
        .custom_toolbar::before {
            content: '';
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            height: 48px;
            width: 45%;
            background: rgb(82, 86, 89);
            background: -moz-linear-gradient(270deg, rgba(82, 86, 89, 0) 0%, rgba(82, 86, 89, 1) 37%, rgba(82, 86, 89, 1) 100%);
            background: -webkit-linear-gradient(270deg, rgba(82, 86, 89, 0) 0%, rgba(82, 86, 89, 1) 37%, rgba(82, 86, 89, 1) 100%);
            background: linear-gradient(270deg, rgba(82, 86, 89, 0) 0%, rgba(82, 86, 89, 1) 37%, rgba(82, 86, 89, 1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#525659", endColorstr="#525659", GradientType=1);
        }
        .custom_toolbar::after {
            content: '';
            display: block;
            position: absolute;
            right: 0;
            top: 0;
            height: 48px;
            width: 0;
            background: rgb(82, 86, 89);
            background: -moz-linear-gradient(90deg, rgba(82, 86, 89, 0) 0%, rgba(82, 86, 89, 1) 37%, rgba(82, 86, 89, 1) 100%);
            background: -webkit-linear-gradient(90deg, rgba(82, 86, 89, 0) 0%, rgba(82, 86, 89, 1) 37%, rgba(82, 86, 89, 1) 100%);
            background: linear-gradient(90deg, rgba(82, 86, 89, 0) 0%, rgba(82, 86, 89, 1) 37%, rgba(82, 86, 89, 1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#525659", endColorstr="#525659", GradientType=1);
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
                    <h1>Document List</h1>
                    <ol class="breadcrumb">
                        <li><a href="../index.php"><i class="fa fa-dashboard"></i> HOME</a></li>
                        <li><a href="document_list.php">Document List</a></li>
                    </ol>
                </section>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="box box-success">
            <div class="box-body">
                <form method="post" id="notice_form_id">
                    <div class="modal-body with-padding">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Select Search BY :</label>
                                    <select required="required" class="form-control" name="search_by" id="search_by"
                                        onchange="fn_case_dfr_type(this.value)">
                                        <option value="">Select Search BY</option>
                                        <option value="1">Filing. No</option>
                                        <option value="2">Case No</option>
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

<div id="edit_group_modal" class="modal fade" role="dialog">
    <div class="modal-dialog  modal-lg" style="width:1200px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="document_name_id">Documnet Name</h4>
            </div>
            <div class="modal-body with-padding" id="">
            <div class="document_div_pdf_file_wrapper">
           <div id="document_div_pdf_file"></div>
           <div class="custom_toolbar"></div>
           </div>
            </div>
        </div>
    </div>
</div>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<script src="../plugins/jQueryUI/jquery-ui.js"></script>

<script>
//  fn_notice_formate(1);
function view_document(doc_name,latest_file) {
    $("#document_name_id").html(doc_name);
    $("#edit_group_modal").modal('show');
    var loader = "<center><img src='../loader/loader.gif'></img></center>";
	var height = $("#accordion-1").height();
	height = height+20;
	   $.ajax({
            type: "POST",
            url: "../scrutiny/view_document.php",
            data: {path:latest_file},
			beforeSend: function() {
				$("#document_div_pdf_file").css('height',height);
				$("#document_div_pdf_file").html(loader);
			},
            success: function (data) {
				$("#document_div_pdf_file").css('height',height);
				$("#document_div_pdf_file").html(loader);
			   $("#document_div_pdf_file").html(data);
			   //alert("success");
            },
            error: function (textStatus, errorThrown) {
				$("#document_div_pdf_file").html('');
               alert("error");
            }
        });
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
        data['action'] = 'search_document_list';
        data['search_by'] = $("#notice_form_id #search_by").val();
        if (search_by == '1') {
            data['dairy_no'] = $("#notice_form_id #dairy_no").val();
            //data['dairy_year'] = $("#notice_form_id #dairy_year").val();
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
            error: function(request, error) {
                alert("something error");
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
$(function(){
    $('#download').hide();
});

</script>


<?php } ?>
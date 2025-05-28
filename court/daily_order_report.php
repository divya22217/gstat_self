<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include "../db_inc1.php";
//include "../db_inc2.php";
include '../inheader.php';



//include "../master/causelist_caseno.php";

$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] : '';
$year = htmlspecialchars(date("Y"));
$msg_ip .= "User IP : " . $_SERVER["REMOTE_ADDR"] . "\r\n"; //Sender's IP
$user_court = $_SESSION['user_court'];
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {

    die("Redirecting to login.php");
}
if (($_SESSION['user']) == '') {
    echo "you Can't access this page";
} else {

    function remove_path($file, $path = UPLOAD_PATH)
    {
        if (strpos($file, $path) !== false) {
            return substr($file, strlen($path));
        }
    }

    $frm = md5(uniqid('auth', true));

/*** set the session form token ***/
    $_SESSION['form_token'] = $frm; //csrf

    $schemas = htmlspecialchars($_SESSION['schema_name']);
    $role_id = $_SESSION['menuaccess_codeall'];

    ?>


</head>
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


.sidebar-menu {
    width: 100%;
}

.skin-blue .sidebar-menu>li>a {
    background: #9a3028;
}

.skin-blue .sidebar-menu>li>.treeview-menu {
    width: 100%;
}
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h1 class="bg">
                    <CENTER>Daily Order Report</CENTER>
                </h1>


                <div class="table-responsive">
                    <form name="frm" method="post" action="daily_order_report.php">


                        <?php $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] : '';?>
                        <div style="float: right; margin-bottom: 10px;">Order Date:
                            <input type="text" id="next_list_date" name="next_list_date" class="datepicker"
                                readonly="readonly" size="8" autocomplete="off" maxlength="10"
                                value="<?php print htmlspecialchars($next_list_date);?>" />
                            <input id="submit1" type="button" class="btn btn-sm grey" name="submit1"
                                value="Search" onClick="return submitForm();" />
                        </div>

                        <table class="table table-hovered table-bordered table-stripped">
                            <thead style="background-color:#444444;color:#ffffff;">
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Case No.</th>
                                    <th>Party Detail</th>
                                    <th>Order Date</th>
                                    <!-- <th>Action</th> -->
                                    <th><center>View</center></th>

                                </tr>
                            </thead>

                            <?php

// function fn_getChild($db, $filing_no)
// {
//     $mainCno = "select filing_no,case_no,case_year,short_name,cast(case_no as int) as case_nooo from lucknow.case_detail inner join case_type on case_type=id where ia_ma_filing_no= ? and status = 'P' order by case_nooo,case_year asc";
//     $mainCrs = $db->prepare($mainCno);
//     $mainCrs->bindParam(1, $filing_no, PDO::PARAM_STR);
//     $mainCrs->execute();
//     $data_ia_main = $mainCrs->fetchAll();
//     return $data_ia_main;
//    }

     if($role_id == '3' && empty($next_list_date)){
        $execute_code = 1;
        $stnq = $db->prepare("select * from $schemas.order_daily where court_no = ? and flag = 'N'");
        $stnq->bindParam(1, $user_court, PDO::PARAM_INT);
        $stnq->execute();

     }else if(empty($next_list_date)){
        $execute_code = 0;
     }else{
        $execute_code = 1;
        list($day, $month, $year) = explode('/', $next_list_date);
        $list_cdate = $year . '-' . $month . '-' . $day;
        $user_ids = $_SESSION['id'];

        if($_SESSION['menuaccess_codeall'] == '11' || $_SESSION['menuaccess_codeall'] == '6'){
            $stnq = $db->prepare("select court_no from $schemas.court where mapped_with = ?");
            $stnq->bindParam(1, $user_court, PDO::PARAM_INT);
            $stnq->execute();
            $new_user_court = $stnq->fetchColumn();
            $query = "select * from $schemas.order_daily where order_date=? and court_no = ?";
        }else{
            $new_user_court = $user_court;
            $query = "select * from $schemas.order_daily where order_date=? and court_no = ?";
        }
        //echo "select * from $schemas.order_daily where order_date= '$list_cdate'";
	// $stnq = $db->prepare("select * from $schemas.order_daily where user_id = '$user_ids' and  order_date=?");
	$stnq = $db->prepare($query);
        $stnq->bindParam(1, $list_cdate, PDO::PARAM_INT);
        $stnq->bindParam(2, $new_user_court, PDO::PARAM_INT);
        $stnq->execute();
    }

    if($execute_code){

        if ($stnq->rowCount() == 0) {
            ?>
                            <tr>
                                <td align="center" colspan="16">
                                    <font color="red" size="4"><b>No Record Found </b></font>
                                </td>
                            </tr>
                            <?php
}
        if ($stnq->rowCount() > 0) {
            $counter = 1;

            $ere = 1;
            while ($rw2 = $stnq->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                extract($rw2);

                $stcn_case = $db->prepare("select * from $schemas.case_detail where filing_no=?");
                $stcn_case->bindParam(1, $filing_no, PDO::PARAM_STR);
                $stcn_case->execute();

                $rw2_case = $stcn_case->fetch();
                //  while ($rw2 = $stcn->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {


                $filing_no98 = htmlspecialchars($rw2['filing_no']);

                $case_no = htmlspecialchars($rw2_case['case_no']);
                // $case_title = htmlspecialchars($rw2_case['case_title']);
                $pt_name = htmlspecialchars($rw2_case['pet_name']);
                $rs_name = htmlspecialchars($rw2_case['res_name']);
               
                $pet_type = htmlspecialchars($rw2_case['pet_type']);
                $res_type = htmlspecialchars($rw2_case['res_type']);
                $case_type = htmlspecialchars($rw2_case['case_type']);
                $case_year = htmlspecialchars($rw2_case['case_year']);
                $location_code = htmlspecialchars($rw2_case['location_code']);
                $ia_ma_filing_no = $rw2_case['ia_ma_filing_no'];

                $case_title1 = $pt_name . ' VS ' . $rs_name;

                $stQ = $db->prepare("select short_name from case_type where id = ?");
                $stQ->bindParam(1, $case_type, PDO::PARAM_STR);
                $stQ->execute();
                $case_type_short_name = $stQ->fetchColumn();
				
				$stQ = $db->prepare("select short_name from mater_location_city where city_id = ?");
                $stQ->bindParam(1, $location_code, PDO::PARAM_STR);
                $stQ->execute();
                $location_short_name = $stQ->fetchColumn();

                $hash = base64_encode($item_no);
                //$hash2 = base64_encode($filing_no.'/'.$schemas); //is used to view page
                $hash2 = base64_encode($item_no . '/' . $filing_no . '/' . $schemas); //is used to view page




                $case_no_child = '';
            //     if ($case_type == '1' or $case_type == '2' or $case_type == '3') {
            //         $data_chield = fn_getChild($db, $filing_no);
            //         if (!empty($data_chield) && is_array($data_chield)) {
            //             foreach ($data_chield as $chil) {
            //                 $case_no_child .= '<br> ' . $chil['short_name'] . '/' . $chil['case_no'] . '/' . $chil['case_year'];
            //             }
            //         }
            //     }
            
            // // Child Parent Condition
            //     if ($ia_ma_filing_no != '') {
            //         $iama_no = $ia_ma_filing_no;
            //         $mainCno = 'select case_no,case_year,short_name from lucknow.case_detail inner join case_type on case_type=id where filing_no= ?;';
            //         $mainCrs = $db->prepare($mainCno);
            //         $mainCrs->bindParam(1, $iama_no, PDO::PARAM_STR);
            //         $mainCrs->execute();
            //         $data_ia_main = $mainCrs->fetch();
            //         $case_no_child .= '<br>IN<br> ' . $data_ia_main['short_name'] . '/' . $data_ia_main['case_no'] . '/' . $data_ia_main['case_year'];
            //     }
            

                ?>
                            <tr>
                                <td><?php echo $ere; ?></td>
                                <td>

                                    <?php if ($flag == 'N') {
                                
                                ?>
                                    <a
                                        href="../public/daily_order_view.php?filing_no=<?php print htmlspecialchars($hash2);?>&title=<?php echo htmlspecialchars(strtoupper($case_type_short_name) . '/' . $case_no . '/' . $case_year); ?>">
                                        <?php
if ($case_no > 0) {
                    echo htmlspecialchars(strtoupper($case_type_short_name) . '/' . $case_no . '/' .$location_short_name.'/'. $case_year).$case_no_child;
                } else {
                    echo "Dairy No " . $filing_no.$case_no_child;
                }
                //  echo show_documents($db, $db, $schema, $filing_no, $scrutiny_d = 1, $display_d = 1, $doc_level_d = 2);

                ?>


                                    </a>

                                    <?php }  else { 
                                    
                                    
                                    
                                    
                                    if ($case_no > 0) {
                                                         echo htmlspecialchars(strtoupper($case_type_short_name) . '/' . $case_no . '/' .$location_short_name.'/'. $case_year).$case_no_child;
                                                    } else {
                                                        echo "Dairy No " . $filing_no. $case_no_child;
                                                    }
                                                    //  echo show_documents($db, $db, $schema, $filing_no, $scrutiny_d = 1, $display_d = 1, $doc_level_d = 2);
                                    
                                                    }?>
                                </td>
                                <td>
                                    <?php
echo "<h7><font>";

                echo $case_title1;

                echo "</font></h7>";
                ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($order_date); ?>
                                </td>
                                <!-- <td>
                                <?php if ($flag == 'N') {?>
                                <input type="button" data-id=<?php echo $item_no; ?>
                                    data-order-date=<?php echo $order_date; ?> id="modify_order<?php echo $item_no; ?>"
                                    data-filing-no=<?php echo $filing_no; ?> class="btn btn-sm btn-success modify_order"
                                    value="Modify" />
                                <?php } else {  echo '<span style="color:green">Uploaded</span>'; }?>
                            </td> -->
                                <td><center> <?php if ($role_id == '5' && $send_by_member == '1') {?> <input type="button"
                                        data-orderid=<?php echo $item_no; ?> id="final_publish<?php echo $item_no; ?>"
                                        class="btn btn-sm btn-success" value="Publish"
                                        onClick="publish_final('<?php echo $filing_no; ?>','<?php echo $item_no; ?>','<?php echo $order_date; ?>','3')" />
                                    <?php } else if(($role_id == '5' && $flag == 'Y' && $send_by_member == '2') || (($role_id == '11' || $role_id == '6') && $flag == 'Y')) {?>
                                    <a href="../scrutiny/readpdf.php?path=<?php echo urlencode($pdf_path) ?>"
                                        target="_blank" title="View"><button type="button"
                                            class="btn btn-none"><i class="fa fa-eye"></i></button></a>
                                    <?php } else if (($role_id == '3' || $role_id == '11' || $role_id == '6') && $flag == 'N' && $send_by_member == '0') { ?>
                                            <input type="button"
                                        data-orderid=<?php echo $item_no; ?> id="final_submit<?php echo $item_no; ?>"
                                        class="btn btn-sm btn-success" value="Upload and Publish"
                                        onClick="save_final('<?php echo $filing_no; ?>','<?php echo $item_no; ?>','<?php echo $order_date; ?>','1')" />
                                        <?php if($role_id != '6' && $role_id != '11') { ?>
                                        <input type="button"
                                        data-orderid=<?php echo $item_no; ?> id="final_submit<?php echo $item_no; ?>"
                                        class="btn btn-sm btn-success" value="Upload and Send to steno"
                                        onClick="save_final('<?php echo $filing_no; ?>','<?php echo $item_no; ?>','<?php echo $order_date; ?>','2')" />
                                        <?php } ?>

                                  <?php  } else if($role_id == '3' && $flag == 'Y'){ ?>
                                        <a href="../scrutiny/readpdf.php?path=<?php echo urlencode($pdf_path) ?>"
                                        target="_blank" title="View"><button type="button"
                                            class="btn btn-none"><i class="fa fa-eye"></i></button></a>
                                <?php  } ?></center>
                                </td>
                            </tr>
                            <?php
$counter++;
                $ere++;
            }
        }

    }
    
    ?>

                        </table>
                    </form>
                </div>
            </div>

    </section>
</div>
    <!-- <?php include '../infooter.php';?> -->


    <!-- edit order modal -->
    <div id="edit_order" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modify Order</h4>
                </div>
                <div class="modal-body" id="edit_order_body">
                    <div class="row">
                        <div id="pet_advocates" class="col-sm-6">
                            <label><b> Applicant/Appellant`s Legal Representative</b></label>
                            <div id="advocates">
                            </div>
                            <div id="add_more">
                            </div>

                        </div>
                        <div id="res_advocates" class="col-sm-6">
                            <label><b>Respondent Legal Representative</b></label>
                            <div id="advocates">
                            </div>
                            <div id="add_more">
                            </div>
                        </div>
                    </div>
                    <br />
                    <textarea id="myModal_textarea" name="edit_order_tribunal" cols="60" rows="40"></textarea>
                    <input type="hidden" name="edit_order_id" id="edit_order_id">
                    <input type="hidden" name="edit_order_date" id="edit_order_date">
                    <input type="hidden" name="edit_filing_no" id="edit_filing_no">
                    <input type="hidden" name="edit_bench_id" id="edit_bench_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onClick="update_order('update')">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>


    <!-- Modal -->
    <div id="edit_group_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload Order</h4>
                </div>

                <!-- //pdfForm -->
                <form id="order_final_form_id" name="order_final_form_id" method="post">
                    <input type="hidden" name="hidden_filling_no" id="hidden_filling_no" value="">
                    <input type="hidden" name="hidden_order_id" id="hidden_order_id" value="">
                    <input type="hidden" name="hidden_order_date" id="hidden_order_date" value="">
                    <input type="hidden" name="hidden_type" id="hidden_type" value="">
                    <div class="modal-body">
                        <p> <select required="required" class="required form-control" name="order_type" id="order_type">
                                <option value="D">Intrim Order</option>
                                <option value="F">Final Order</option>
                            </select>
                        </p>

                        <p> <input onchange="fn_dsc_pdf(this.value)" checked="checked" type="radio" name="dsc_radion"
                                id="non_dsc" value="non_dsc"> Non-DSC
                            <input onchange="fn_dsc_pdf(this.value)" type="radio" name="dsc_radion" id="with_dsc"
                                value="with_dsc"> with DSC
                        </p>
                        <p>&nbsp;</p>
                        <div id="pdf_signing_div"></div>
                    </div>
                </form>
                <div class="modal-footer">
                    <div id="footer_dsc">
                        <button type="button" id="upload_order_file" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
    /**
     * Convert a base64 string in a Blob according to the data and contentType.
     * 
     * @param b64Data {String} Pure base64 string without contentType
     * @param contentType {String} the content type of the file i.e (image/jpeg - image/png - text/plain)
     * @param sliceSize {Int} SliceSize to process the byteCharacters
     * @see http://stackoverflow.com/questions/16245767/creating-a-blob-from-a-base64-string-in-javascript
     * @return Blob
     */
    function b64toBlob(b64Data, contentType, sliceSize) {
        contentType = contentType || '';
        sliceSize = sliceSize || 512;

        var byteCharacters = atob(b64Data);
        var byteArrays = [];

        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);

            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            var byteArray = new Uint8Array(byteNumbers);

            byteArrays.push(byteArray);
        }

        var blob = new Blob(byteArrays, {
            type: contentType
        });
        return blob;
    }
    fn_dsc_pdf('non_dsc');

    function fn_dsc_pdf(value) {

        $("#pdf_signing_div").html('');
        if (value == 'non_dsc') {
            $("#footer_dsc").show();
        } else if (value == 'with_dsc') {
            $("#footer_dsc").hide();
        }
        var data = {};
        data['action'] = 'pdf_signing';
        data['radio_type'] = value;
        $.ajax({
            type: "POST",
            url: "dsc_ajax.php",
            data: data,
            dataType: 'html',
            success: function(data11) {
                $('.loader').fadeOut(200);
                //  alert(data11);
                $("#pdf_signing_div").html(data11);
            },
            error: function(request, error) {
                $('.loader').fadeOut(200);
                console.log("Something error.");
            }
        });
    }
    $(document).on('click', '#upload_order_file', function(e) {
        if (confirm('Are you sure ?')) {
            var dsc_radion = $("input[name='dsc_radion']:checked").val();
            e.preventDefault();
            var form_data = new FormData();
            var order_id = $("#hidden_order_id").val();
            var filing_no = $("#hidden_filling_no").val();
            var order_date = $("#hidden_order_date").val();
            var order_type = $("#order_type").val();
            var type = $("#hidden_type").val();
            if (dsc_radion == 'with_dsc') {
                var ImageURL = $("#downloadDiv").attr("href");
                var block = ImageURL.split(";");
                var contentType = block[0].split(":")[1];
                var realData = block[1].split(",")[1];
                var blob = b64toBlob(realData, contentType);
                form_data.append("file", blob);
            } else if (dsc_radion == 'non_dsc') {
                var file_data = $('#browse_file')[0].files[0];
                if (typeof file_data === "undefined") {
                    alert('Please Browse PDF File');
                    return false;
                }
                form_data.append('file', file_data);
            }
            form_data.append('order_id', order_id);
            form_data.append('filing_no', filing_no);
            form_data.append('order_date', order_date);
            form_data.append('dsc_radion', dsc_radion);
            form_data.append('order_type', order_type);
            form_data.append('type', type);
            form_data.append('action', 'upload_order');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: form_data,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                success: function(data11) {
                    $('.loader').fadeOut(200);
                    alert(data11);
                    location.reload(true);
                    // $('.loader').fadeOut(200);
                    //   $("#cases_data_list_details").html(data);
                },
                error: function(request, error) {
                    //   $('.loader').fadeOut(200);
                    // console.log("Something error.");
                }
            });
            // }
        }
    });




    function save_final(filing_no, order_id, order_date,type) {
        fn_dsc_pdf('non_dsc');
        $("input[name=dsc_radion][value=non_dsc]").prop('checked', true);
        $("#edit_group_modal").modal('show');
        $("#order_final_form_id #hidden_filling_no").val(filing_no);
        $("#order_final_form_id #hidden_order_id").val(order_id);
        $("#order_final_form_id #hidden_order_date").val(order_date);
        $("#order_final_form_id #hidden_type").val(type);
    }

    function publish_final(filing_no, order_id, order_date,type) {
         let result = confirm("Are you sure ?");
        if (result === true) {
        var form_data = new FormData();
        form_data.append('order_id', order_id);
        form_data.append('filing_no', filing_no);
        form_data.append('order_date', order_date);
        form_data.append('type', type);
        form_data.append('action', 'upload_order');
        $.ajax({
                type: "POST",
                url: "ajax.php",
                data: form_data,
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                success: function(data11) {
                    $('.loader').fadeOut(200);
                    alert(data11);
                    location.reload(true);
                    // $('.loader').fadeOut(200);
                    //   $("#cases_data_list_details").html(data);
                },
                error: function(request, error) {
                    //   $('.loader').fadeOut(200);
                    // console.log("Something error.");
                }
            });
        }
    }

    $(document).on('click', '.modify_order', function() {
        var filing_no = $(this).data('filing-no');
        var id = $(this).data('id');
        var order_date = $(this).data('order-date');
        $("#pet_advocates #advocates").html('');
        $("#pet_advocates #add_more").html('');
        $("#res_advocates #advocates").html('');
        $("#res_advocates #add_more").html('');
        $.ajax({
            type: "POST",
            url: "edit_order.php",
            data: {
                filing_no: filing_no,
                id: id,
                order_date: order_date
            },
            success: function(data) {

                var obj = JSON.parse(data);
                $.each(obj.petitioner_advocate, function(index, value) {
                    $("#pet_advocates #advocates").append("<span id='" + value.id +
                        "' style='display:block;position:relative;margin-bottom:20px;'><input type='text' name='pet_advocates[]' class='form-control' value='" +
                        value.advocate + "' id='petitioner_advocate" + value.id +
                        "'/><button type='button' style='position:absolute;top:0;right:0;' id='remove_advocate-" +
                        value.id +
                        "' class='btn btn-danger remove_advocate' >&times;</button></span>"
                    );
                });
                $("#pet_advocates #add_more").append(
                    "<button type='button' id='add_pet_advocate' class='btn btn-success add_pet_advocate' >Add More</button><br/>"
                );
                $.each(obj.respondent_advocate, function(index, value) {
                    $("#res_advocates #advocates").append("<span id='" + value.id +
                        "'  style='display:block;position:relative;margin-bottom:20px;'><input type='text' name='res_advocates[]' class='form-control' value='" +
                        value.advocate + "' id='respondent_advocate" + value.id +
                        "'/><button type='button' style='position:absolute;top:0;right:0;' id='remove_advocate-" +
                        value.id +
                        "' class='btn btn-danger remove_advocate' >&times;</button></span>"
                    );
                });
                $("#res_advocates #add_more").append(
                    "<button type='button' id='add_res_advocate' class='btn btn-success add_res_advocate' >Add More</button><br/>"
                );
                $("#myModal_textarea").html(obj.order_daily.order_tribunal);
                $("#edit_order_id").val(id);
                $("#edit_order_date").val(obj.order_daily.order_date);
                $("#edit_filing_no").val(obj.order_daily.filing_no);
                $("#edit_bench_id").val(obj.order_daily.bench_id);
                tinymce.init({
                    gecko_spellcheck: true,
                    width: "640",
                    selector: "#myModal_textarea",
                    theme: "modern",
                    //menubar: "edit insert view format table",

                    menu: {
                        edit: {
                            title: 'Edit',
                            items: 'undo redo  | cut copy paste selectall | searchreplace'
                        },
                        insert: {
                            title: 'Insert',
                            items: 'edit image charmap pagebreak insertdatetime hr  '
                        },
                        view: {
                            title: 'View',
                            items: 'preview fullscreen'
                        },
                        format: {
                            title: 'Format',
                            items: 'bold italic underline strikethrough superscript subscript | removeformat'
                        },
                        table: {
                            title: 'Table',
                            items: 'inserttable tableprops deletetable | cell row column'
                        }
                    },

                    plugins: [
                        "advlist autolink lists link image charmap print preview hr anchor pagebreak lineheight",
                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                        "insertdatetime media nonbreaking save table contextmenu directionality",
                        " template paste textcolor colorpicker textpattern"
                    ],
                    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent |  image fontselect fontsizeselect",
                    toolbar2: " lineheightselect  preview | forecolor backcolor  ",
                    removed_menuitems: 'newdocument',

                    image_advtab: true,
                    templates: [{
                            title: 'Test template 1',
                            content: 'Test 1'
                        },
                        {
                            title: 'Test template 2',
                            content: 'Test 2'
                        }
                    ]
                });
                $("#edit_order").modal('show');

            },
            error: function(textStatus, errorThrown) {
                alert("error");
            }

        });
    });

    function update_order(action) {
        tinymce.triggerSave();
        var order_tribunal = $("#myModal_textarea").val();
        var order_id = $("#edit_order_id").val();
        var order_date = $("#edit_order_date").val();
        var filing_no = $("#edit_filing_no").val();
        var bench_id = $("#edit_bench_id").val();
        var pet_advocates = $("input[name='pet_advocates[]']")
            .map(function() {
                return $(this).val();
            }).get();
        var res_advocates = $("input[name='res_advocates[]']")
            .map(function() {
                return $(this).val();
            }).get();
        $.ajax({
            type: "POST",
            url: "order_creation_bulk_ind_action.php",
            data: {
                action_type: action,
                order_id: order_id,
                order_date: order_date,
                bench_id: bench_id,
                filing_no: filing_no,
                order_tribunal: order_tribunal,
                pet_advocates: pet_advocates,
                res_advocates: res_advocates
            },
            success: function(data) {
                alert("Order Updated");
                $("#edit_order").modal('hide');
                //location.reload(true);

            },
            error: function(textStatus, errorThrown) {
                alert("error");
            }

        });
    }




    $(document).on('click', '.remove_advocate', function() {
        var id = this.id;
        var advocate_id = id.split('-')[1];
        $("#" + advocate_id).remove();
    })

    $(document).on('click', '.add_pet_advocate', function() {
        var random = Math.floor(Math.random() * 10000);
        $("#pet_advocates #advocates").append("<span id='" + random +
            "'  style='display:block;position:relative;margin-bottom:20px;'><input type='text' name='pet_advocates[]' class='form-control' value='' id='petitioner_advocate" +
            random +
            "'/><button type='button' style='position:absolute;top:0;right:0;' id='remove_advocate-" +
            random + "' class='btn btn-danger remove_advocate' >&times;</button></span>");
    })

    $(document).on('click', '.add_res_advocate', function() {
        var random = Math.floor(Math.random() * 10000);
        $("#res_advocates #advocates").append("<span id='" + random +
            "'  style='display:block;position:relative;margin-bottom:20px;'><input type='text' name='res_advocates[]' class='form-control' value='' id='respondent_advocate" +
            random +
            "'/><button type='button' style='position:absolute;top:0;right:0;' id='remove_advocate-" +
            random + "' class='btn btn-danger remove_advocate' >&times;</button></span>");
    })
    </script>



    <script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>

    <link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
    <script src="../plugins/jQueryUI/jquery-ui.js"></script>
    <script src="../plugins/jQueryUI/date.js"></script>

    <script language="javascript">
    function change(id, newClass) {
        identity = document.getElementById(id);
        identity.className = newClass;

    }

    function printPage() {
        change("testdiv", "hidden");
        window.print();
    }

    function popsurety_pet_adv_name(cfy, title)

    {

        var url = "../public/daily_order_view.php?filing_no=" + cfy + "&title=" + title;
        // var width = 800;
        // var height = 600;

        // var left = 200;

        // var top = 0;

        // var params = 'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ',resizable=1';

        // window.open(url, "print", params);
    }





    function popsurety_pet_adv_name2(cfy)

    {

        var url = "website_upload.php?itemno=" + cfy;
        var width = 800;
        var height = 1300;

        var left = (screen.width - width) / 2;

        var top = (screen.height - height) / 2;

        var params = 'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ',scrollbars=1';

        window.open(url, "print", params);
    }
    </script>
    <script>
    function submitForm() {
        with(document.frm) {


            if (next_list_date.value == "") {
                alert("Enter ORDER DATE....");
                next_list_date.value = '';
                next_list_date.focus();
                return false;
            }

            action = "daily_order_report.php";
            submit();
            document.frm.submit1.disabled = true;
            document.frm.submit1.value = 'Please Wait...';
            return true;
        }

    }
    </script>
    <?php }?>

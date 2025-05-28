<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php";
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 if($_REQUEST['action'] == 'pdf_signing') {

        $radio_type = $_POST['radio_type'];
        if($radio_type == 'non_dsc') { 
            echo ' <p><input required="" type="file" class="required form-control" name="browse_file" id="browse_file"></p>';
        } else if($radio_type == 'with_dsc') { ?>
<script src="resources/js/dsc-signer.js" type="text/javascript"></script>
<script src="resources/js/dscapi-conf.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="resources/css/dsc-signer.css">

<style>
button.btn.btn-default {
    top: 8px;
}
.p-0 {
    padding: 0;
}
.well-sm {
    padding: 0px;
}
.btn-signpdf {
    float: right;
    margin-bottom: 10px;
    margin-right: 0;
}
#iframepdf_pdf {
    width: 100%;
}
.well{
    width: 100%;  
}
.modal-footer {
    border: none;
}
</style>

<div class="col-sm-12 p-0">
    <div class="well-sm">
        <!-- <form id="pdfForm"> -->

            <div class="col-md-4 p-0">
                <label for="data">Choose Local File : </label><br /> <input type="file" name="pdfFile" id="pdfFile"
                    accept="application/pdf" onchange="previewFile()" />
            </div>
        
            <div style="display:none;" class="col-md-8" id="iframepdf_pdf">
                <iframe id="iframepdf" src="files/example.pdf" style="width:100%;height:400px;"></iframe>
            </div>

            <div style="display:none" <label for="pdfData">Pdf Data (Base64):</label> <br />
                <textarea id="pdfData" cols="60" rows="8" readonly="readonly"></textarea>
                <br />Reason : <input type="text" id="signingReason" name="signingReason" maxlength="20" />
                <br />
                Location : <input type="text" id="signingLocation" name="signingLocation" maxlength="20" />
                <br />
                stampingX : <input type="text" id="stampingX" name="stampingX" maxlength="20" value="200" />
                <br />
                stampingY
                : <input type="text" id="stampingY" name="stampingY" maxlength="20" value="200" /><br />
                Select
                TSA URL : <select name="tsaurls" id="tsaurls" onchange="myFunction()">
                    <option value="0">--------------------------SELECT---------------------------------
                    </option>
                    <option value="http://sha256timestamp.ws.symantec.com/sha256/timestamp">
                        http://sha256timestamp.ws.symantec.com/sha256/timestamp</option>
                    <option value="http://timestamp.comodoca.com/rfc3161">
                        http://timestamp.comodoca.com/rfc3161
                    </option>
                    <option value="http://tsa.startssl.com/rfc3161">http://tsa.startssl.com/rfc3161
                    </option>
                    <option value="http://timestamp.digicert.com">http://timestamp.digicert.com</option>
                    <option value="http://tsa.safecreative.org">http://tsa.safecreative.org</option>
                </select> <br /> TSA URL (Optional) : <input type="text" id="tsaURL" name="tsaURL" value=""
                    maxlength="100" style="width: 400px;" /> <br />Time
                Server URL (Optional) :
                <input type="text" id="timeServerURL" name="timeServerURL"
                    value="https://nicdsign.kerala.nic.in/dscapi/getServerTime" maxlength="100" style="width: 400px;" />

            </div>
            <br />
            <div class="col-md-12 p-0">
            <a id="downloadDiv" href='#' type="application/pdf" download="SignedPdf.pdf"></a>
            <input  id="signPdf" type="button" value="Sign PDF " class="btn btn-success btn-signpdf">
             <input id="submitPdf" type="Submit" style="display: none;">
             <!-- <input id="signPdf" type="button" value="Sign PDF " class="btn btn-success btn-signpdf"> -->
             
                                        
              <!-- <a id="downloadDiv" href='#' type="application/pdf" download="SignedPdf.pdf"></a> -->
               <input id="verifyPdfBtn" type="button" value=" Verify Pdf " class="btn btn-danger"> <br />
</div>
        <!-- </form> -->
    </div>
</div>
<div id="panel"></div>

<script type="text/javascript">
function previewFile() {
    $("#iframepdf_pdf").hide();
    var preview = document.querySelector('iframe');
    var file = document.querySelector('input[type=file]').files[0];
    var reader = new FileReader();
    reader.onloadend = function() {
        preview.src = reader.result;
    }
    if (file) {
        reader.readAsDataURL(file);
        $("#iframepdf_pdf").show();
    } else {
        preview.src = "";
    }
}

function myFunction() {
    var x = document.getElementById("tsaurls").value;
    if (x != 0) {
        document.getElementById("tsaURL").value = x;
    } else {
        document.getElementById("tsaURL").value = "";
    }
}
$(document).ready(function() {

            $('#verifyPdfBtn').hide();

            var initConfig = {
                "preSignCallback": function() {
                    // do something
                    // based on the return sign will be invoked
                    return true;
                },
                "postSignCallback": function(alias, sign, key) {
                    $('#signedPdfData').val(sign);
                    $('#lblEncryptedKey').val(key);
                    // Implement signed pdf upload and pdf Download here
                    var requestData = {
                        action: "DECRYPT",
                        en_sig: sign,
                        ek: key
                    };
                    $
                        .ajax({
                            url: dscapibaseurl +
                                "/pdfsignature",
                            type: "post",
                            dataType: "json",
                            contentType: 'application/json',
                            data: JSON
                                .stringify(requestData),
                            async: false
                        })
                        .done(
                            function(data) {
                                if (data.status_cd == 1) {
                                    //get data.data -> decode base64 -> get json->check status == SUCCESS
                                    //get data.data.sig -> add pdf header and append to link
                                    var jsonData = JSON
                                        .parse(atob(data.data));
                                    if (jsonData.status === "SUCCESS") {
                                        $(
                                                '#verifyPdfBtn')
                                            .show();

                                        $('#verifyPdfBtn').hide();
                                        //Set Class to download link
                                        $('#downloadDiv').addClass('btn btn-info');
                                        
                                        $('#signPdf').hide();
                                        $('#footer_dsc').show();

                                        var pdfData = jsonData.sig;
                                        var dlnk = document.getElementById('downloadDiv');
                                        dlnk.href = 'data:application/pdf;base64,' + pdfData;
                                        $( "#downloadDiv") .text( "Download Signed PDF File");

                                    }

                                } else {
                                    if (data.error.error_cd == 1002) {
                                        alert(data.error.message);
                                        return false;
                                    } else {
                                        alert("Decryption Failed for Signed PDF File");
                                        return false;
                                    }

                                }
                            }).fail(
                            function(jqXHR, textStatus,
                                errorThrown) {
                                alert(textStatus);
                            });
                },
                signType: 'pdf',
                mode: 'nostampingv2'
                //"certificateSno" : 13705892,
            };
            dscSigner.configure(initConfig);

            $('#signPdf').click(function() {
                var data = $("#pdfData").val();

                if (data != null || data != '') {
                    dscSigner.sign(data);
                }
            });

            $('#verifyPdfBtn')
                .click(
                    function() {
                        var signedPdfData = $(
                            '#signedPdfData').val();
                        var key = $('#lblEncryptedKey')
                            .val();

                        // Implement Verify here
                        var requestData = {
                            action: "VERIFY",
                            en_sig: signedPdfData,
                            ek: key
                        };
                        $
                            .ajax({
                                url: dscapibaseurl +
                                    "/pdfsignature",
                                type: "post",
                                dataType: "json",
                                contentType: 'application/json',
                                data: JSON
                                    .stringify(requestData),
                                async: false
                            })
                            .done(
                                function(data) {
                                    if (data.status_cd == 1) {
                                        //get pdfSignatureVerificationResponse
                                        $(
                                                '#verificationResponse')
                                            .val(
                                                atob(data.data));
                                    } else {
                                        alert("Verification Failed");
                                    }

                                })
                            .fail(
                                function(
                                    jqXHR,
                                    textStatus,
                                    errorThrown) {
                                    alert(textStatus);
                                });
                    });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var data = e.target.result;
                        var base64 = data
                            .replace(/^[^,]*,/, '');
                        $("#pdfData").val(base64);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#pdfFile").change(function() {
                readURL(this);
            });

        });
</script>

<?php }
       
    
    }

?>
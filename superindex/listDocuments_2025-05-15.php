<?php
session_start();
date_default_timezone_set("Asia/Kolkata");

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);
$security_code = isset($_POST['security_code']) ? $_POST['security_code'] : '';
$security_code12 = isset($_SESSION['user']) ? $_SESSION['user'] : '';
if ($security_code != '' && $security_code == '&^%@B*&**%GBJHVUYGUV&^@&^@GB@^$#EFGVB') {
    $security_code12 = 'sccode';
}
if ($security_code12 != '') {
    $intFilingNumber = isset($_REQUEST['filing_no']) ? $_REQUEST['filing_no'] : '';
    $strSchemaName = isset($_REQUEST['schema_name']) ? $_REQUEST['schema_name'] : 'delhipb';
   
?>

    <!DOCTYPE html>
    <html>

    <head>
        <script src="jquery-3.7.0.min.js"></script>
        <script src="jspdf.min.js"></script>
        <style>
            body {
                padding: 0 40px;
            }

            th {
                white-space: nowrap;
            }

            .nowrap {
                white-space: nowrap;
            }

            h2 {
                font-size: 22px !important;
            }

            .table-bordered>thead>tr>th {
                font-size: 14px;
            }

            .table-bordered>tbody>tr>td {
                border: 1px solid #ddd !important;
                color: #333;
                font-weight: 400;
                font-size: 14px;
            }

            .file_download_img {
                cursor: pointer;
            }

            header {
                position: fixed;
                top: 0 !important;
                left: 0;
                z-index: 5;
                width: 100%;
                height: 102px;
                box-shadow: 0px 1px 15px 0 rgba(0, 0, 0, 0.3);
                border-bottom: 1px solid rgba(255, 255, 255, 0.70);
                background-color: rgba(228, 150, 67, 0.90);
                background-repeat: no-repeat;
                background-size: cover;
                background-position: bottom center;
                overflow: hidden;
            }

            .logo img {
                width: 100%;
            }

            .mainlogo {
                margin: 10px 20px 0;
            }

            .otherlogo {
                position: absolute;
                right: 0;
                top: 0;
            }

            .headnav {
                position: fixed;
                display: flex;
                align-items: center;
                justify-content: space-between;
                top: 102px;
                z-index: 4;
                width: 100%;
                left: 0;
                background: #717171;
                padding: 0px 25px;
                color: #fff;
                box-shadow: 0px 0px 10px rgb(0 0 0 / 30%);
                font-size: 14px;
            }

            .headnav a {
                display: inline-block;
                padding: 8px 15px;
                color: #fff;
                font-weight: bold;
                background: rgb(255 255 255 / 10%);
            }

            .headnav .headinfo {
                float: right;
                padding: 8px 15px;
                font-weight: normal;
            }
            
            .headnav .headTitle {
                margin-left: 130px;
                padding: 10px 25px;
                color: #fff;
                font-size: 15px;
                text-align: center;
                font-weight: bold;
                margin-bottom: 15px; 
            }


            .form-control {
                display: block;
                width: 100%;
                height: 34px;
                padding: 6px 12px;
                font-size: 14px;
                line-height: 1.42857143;
                color: #555;
                background-color: #fff;
                background-image: none;
                border: 1px solid #ccc;
                border-radius: 4px;
                -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
                -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
                -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
                transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            }

            ul.scroll {
                /* margin:4px, 4px; */
                /* padding:4px; */
                /* width: 500px; */
                height: 250px;
                overflow-x: hidden;
                overflow-y: auto;
                /* text-align:justify; */
            }
        </style>
        <title>Document List Form</title>
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
        <!-- <link rel="stylesheet" href="../bower_components/jvectormap/jquery-jvectormap.css"> -->
        <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
        <!-- <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css"> -->
        <!-- <link rel="stylesheet" href="../assets/css/header.css"> -->
        <link rel="stylesheet" href="../assets/css/newstyle.css">
        <link rel="stylesheet" type="text/css" href="css/listDocument.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://efilingreat.up.gov.in/upreat/superindex/jquery-3.7.0.min.js"></script>
        <script src="https://efilingreat.up.gov.in/upreat/superindex/html2pdf.bundle.min.js"></script>
    </head>
    </head>

    <body>


    <style>
 header .upper {
    background: linear-gradient(160deg, #ddeaf1 0%, #f4e6c6 100%); 
    border-bottom: 3px solid #294984;
}

header {
    width: 100%; 
    left:0;
    right:0;
    z-index:99;
}
header h1 {
    margin: 0;
}
header .site-title {
    font-size: 1.75em;
    display: inline;
    font-family: serif;
    vertical-align: middle;
    margin-left: 15px;
    float: left;
    padding-top: 30px;
    color: #846312;
}
header .inner {
    overflow: hidden;
    width: 100%;
    max-width: 90%;
    margin: 0 auto;
    padding: 7px 0px;
}

header .left_logo {
    height: 94px;
    float: left;
    margin-left: 10px;
    padding: 0px 0;
}

header .right_logo {
    float: right;
    padding: 20px 0;
}

header .right_logo img {
    padding: 0 10px;
    height: 40px;
}
.form-inline {
    height: auto;
}
.fixed_header {
  margin-top: 152px;
  padding: 0 30px;
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  z-index: 99;
  background: #fdfbcb;
  box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.20);
}
.filter-table {
    background: #fff;
}
.form_center {
  margin: auto;
  justify-content: center;
}

  .table-header-filter-message {
    color: #2980b9;
    font-size: 14px;
    font-weight: bold;
    margin: 7px 0;
  }
  body {
    padding-top: 355px;
    padding-bottom: 100px;
  }
  .vspace {
    height: 20px;
  }
  .select-btn.open ~ .list-items {
    position: absolute;
  }
</style>
<header>
    <div class="upper">
            <div class="inner">
            <div>
                <img src="../APTEL_files/GSTAT-Logo.png" class="left_logo">
                <h1 class="site-title">GST Appellate Tribunal</h1>
                </div>
                <div class="right_logo">
                    <img src="../APTEL_files//logo_sb.png">
                    <img src="../APTEL_files//logo_di.png">
                </div>
                
            </div>
        </div>
</header>
        <div class="headnav">
            <ul class="nav navbar-nav topmenu">
                <li><a href="../index.php">Home </a></li>
            </ul>
            <div class="headTitle" style="margin-bottom:0;">SUPER INDEX</div>
                <div class="headinfo"> <?php echo date("l jS \of F Y h:i:s A"); ?></div>
        </div>

        <div class="fixed_header">
            <form method="post" action="" name="document-list-form" id="document-list-form">
                <input type="hidden" name="owner_id" id="owner_id" />
                <input type="hidden" name="filing_no" id="filing_no" value="<?php echo $intFilingNumber; ?>" />
                <input type="hidden" name="schema_name" id="schema_name" value="<?php echo $strSchemaName; ?>" />
                <h1 class="bg" align='center' colspan='5' id="super_index_title"></h1>
                <div class="filter-table">
                   
                    <row class="form-inline form_center">
                        <div class="cell">
                            <label for="Uploaded On">Filing Duration : From :</label>
                            <div class="select">
                                <input class="form-control" type="date" name="uploaded_date_from" id="uploaded_date_from">
                            </div>
                        </div>
                        <div class="cell">
                            <label>To : </label>
                            <div class="select date-select">
                                <input class="form-control" type="date" name="uploaded_date_to" id="uploaded_date_to">
                            </div>
                        </div>
                        <div class="cell">
                            <label for="Filed By">Document Owner : </label>
                            <div class="select dropdown-select">
                                <div class="select-btn">
                                    <span class="btn-text">Select</span>
                                    <span class="arrow-dwn">
                                        <i class="fa-sharp fa-solid fa-chevron-down"></i>
                                    </span>
                                </div>

                                <ul class="list-items scroll" id="filed-by-list"></ul>
                                <input type="hidden" name="filed_by" id="hidden-filed-by-list" />
                                <input type="hidden" name="hidden_document_owner_list" id="hidden_document_owner_list" />
                            </div>
                        </div>
                        <div class="cell">
                            <label for="Document Type">Document Type : </label>
                            <div class="select dropdown-select">
                                <div class="select-btn doc-type-select-btn" id="doc-type-select-btn">
                                    <span class="btn-text doc-type-btn-text">Select</span>
                                    <span class="arrow-dwn">
                                        <i class="fa-sharp fa-solid fa-chevron-down"></i>
                                    </span>
                                </div>
                                <ul class="list-items scroll" id="document-type-list"></ul>
                                <input type="hidden" name="document_type" id="hidden-doc-type-list" />
                                <input type="hidden" name="hidden_document_type_list" id="hidden_document_type_list" />
                            </div>
                        </div>
                        <div class="cell" style="padding-top: 35px;">
                            <label for="Search Button"> </label>
                            <button type="submit" name="reset" id="btnReset" value="Reset"
                                class="btn btn-warning">Reset</button>
                        </div>
        
                    </row>
                </div>

                <div id="filedByHeaderMsg" class="table-header-filter-message" align='center' colspan='8'></div>
                <div id="documentTypeHeaderMsg" class="table-header-filter-message" align='center' colspan='8'></div>

        </div>

        <div id="errorMsg" class="error-message" align='center' colspan='8'></div>

        
        <!-- <div class="wrapper"> -->
        <table class="table table-bordered table-responsive table-hover" id="userTable">
            <thead style="background-color:#444444;color:#ffffff;">
                <tr class="row header" id="tableHeader">
                    <th class="cell" width="1%">Sr. No.</th>
                    <th class="cell" width="22%">Filed/Issued In</th>
                    <th id="table_header_document_type" class="cell" width="16%">Document/Note Type</th>
                    <th class="cell" width="15%">Document Name</th>
                     <th width="20%">Filing Date 
                            <i class="fa fa-long-arrow-up" id="sortAsc" onclick="sortTableByFilingDate('asc')"></i>
                            <i class="fa fa-long-arrow-down" id="sortDesc" onclick="sortTableByFilingDate('desc')"></i>
                            </th>
                    <th class="cell" width="17%">UserName</th>
                    <th class="cell" width="1%">Document View</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
        </table>
        <div class="vspace"></div>
        <!-- </div> -->
        </form>
        </div>
    </body>

    </html>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        var arrstrDocOwners = [];
        var arrstrDocTypes = [];

        function camelCase(str) {
            return str.replace(/(?:^\w|[A-Z]|\b\w)/g, function(word, index) {
                return index == 0 ? word.toUpperCase() : word.toLowerCase();
            }).replace(/\s+/g, '');
        }

        function titleCase(string) {
            var sentence = string.toLowerCase().split(" ");
            sentence[i] = null;
            for (var i = 0; i < sentence.length; i++) {
                sentence[i] = (typeof sentence[i][0] === 'string') ?
                    sentence[i][0].toUpperCase() + sentence[i].slice(1) :
                    sentence[i][0];
            }

            return sentence.join(" ");
        }

        function padTo2Digits(num) {
            return num.toString().padStart(2, '0');
        }

        // function formatDateddmmyyyy(date) {
        //     return (
        //         [
        //             padTo2Digits(date.getDate()),
        //             padTo2Digits(date.getMonth() + 1),
        //             date.getFullYear(),
        //         ].join('/') +
        //         ' ' + [
        //             padTo2Digits(date.getHours()),
        //             padTo2Digits(date.getMinutes()),
        //             padTo2Digits(date.getSeconds()),
        //         ].join(':')
        //     );
        // }

        function sendDocumentsListRequest(formData) {
            var filingNumber = "<?php echo "$intFilingNumber" ?>";
            var schemaName = "<?php echo "$strSchemaName" ?>";
            $("#errorMsg").html("");
            $.ajax({
                method: 'POST',
                url: 'api/documentsApiService.php?action=list',
                data: formData,
                headers: {
                    "Authorization": localStorage.getItem('authToken')
                },
                beforeSend: function(xhr) {
                    $("<div class='loader'></div>").appendTo("#userTable tbody");
                   
                },
                success: function(res) {
               
                    $('.loader').remove();
                    let objError = JSON.parse(res).data;
                    if (objError.error) {
                        $("#errorMsg").html(objError.error);
                        var tr_str = "<tr class=\"row\">" +
                            "<td class=\"cell text-align-center\" data-title=\"id\" colspan=\"9\" align=\"center\">" +
                            "<strong>No Record Found</strong></td>" +
                            "</tr>";
                        $('#userTable tbody').empty().append(tr_str);
                        $('#documentTypeHeaderMsg').html('');
                        $('#filedByHeaderMsg').html('');
                        $('#table_header_document_type').show();
                        $('#table_header_filed_by').show();
                        return;
                    }
                    let documentDetailResponse = JSON.parse(res).data['document_Detail'];
                
                   

                    if (documentDetailResponse.error) {
                        if ('Expired token' == documentDetailResponse.error) {
                           
                            $("#errorMsg").html('Invalid user token. Please relogin!');
                            var tr_str = "<tr class=\"row\">" +
                                "<td class=\"cell text-align-center\" data-title=\"id\" colspan=\"9\" align=\"center\">" +
                                "<strong>No Record Found</strong></td>" +
                                "</tr>";
                            $('#userTable tbody').empty().append(tr_str);
                            $('#documentTypeHeaderMsg').html('');
                            $('#filedByHeaderMsg').html('');
                            $('#table_header_document_type').show();
                            $('#table_header_filed_by').show();
                        } else {
                            $("#errorMsg").html(documentDetailResponse.error);
                        }

                    } else {

                        let caseDetailResponse = JSON.parse(res).data.case_detail;

                        var strSuperIndexTitle = "<h2> Filing No. " + filingNumber +
                            " and Case " + caseDetailResponse.formated_case_number + "</h2>"
                        var strReraComplaintNo = null;
                       
                        let finalReraComplaintNumber = (null !== strReraComplaintNo) ? strReraComplaintNo : '';
                        $('#super_index_title').html(strSuperIndexTitle + finalReraComplaintNumber);

                        var len = documentDetailResponse.length;
                        if (0 == len || undefined == len || 'undefined' == len) {
                            
                            var tr_str = "<tr class=\"row\">" +
                                "<td class=\"cell text-align-center\" data-title=\"id\" colspan=\"9\" align=\"center\">" +
                                "<strong>No Record Found</strong></td>" +
                                "</tr>";
                            $('#userTable tbody').empty().append(tr_str);
                            $('#documentTypeHeaderMsg').html('');
                            $('#filedByHeaderMsg').html('');
                            $('#table_header_document_type').show();
                            $('#table_header_filed_by').show();
                        } else {
                            $('#userTable tbody').empty();

                            var boolHideFiledByColumn = false;
                            var boolHideDocumentTypeColumn = false;

                            var arrstrExplodedPostedData = ('string' === typeof formData) ? formData.split("&") : [];

                            var arrstrFiledBy = arrstrExplodedPostedData.filter(str => str.includes('filed_by='));
                            var arrstrDocumentType = arrstrExplodedPostedData.filter(str => str.includes(
                                'document_type='));

                            if (0 < arrstrDocumentType.length) {
                                var strDocumentTypeValues = arrstrDocumentType[0].split('=')[1];
                                if ('' !== strDocumentTypeValues && !strDocumentTypeValues.includes('%2C')) {
                                    boolHideDocumentTypeColumn = true;
                                }
                            }

                            if (0 < arrstrFiledBy.length) {
                                var strFiledByValues = arrstrFiledBy[0].split('=')[1];
                                if ('string' === typeof strFiledByValues) {
                                    boolHideFiledByColumn = true;
                                }
                            }

                            var arrstrFiledBy = [];
                            for (var i = 0; i < len; i++) {
                                var id = i + 1;
                                var filedIn = documentDetailResponse[i].filed_in ? documentDetailResponse[i]
                                    .filed_in : '-';
                                var documentType = documentDetailResponse[i].document_type ?
                                    documentDetailResponse[i].document_type : 'Not available in record';
                                var documentName = documentDetailResponse[i].document_name ?
                                    documentDetailResponse[i].document_name : '-';
                                var filedByName = documentDetailResponse[i].filed_by_name ?
                                    documentDetailResponse[i].filed_by_name : documentDetailResponse[0].filed_by_name;
                                var filedByParty = documentDetailResponse[i].filed_by_party ?
                                    documentDetailResponse[i].filed_by_party : '';
                                var filingDate = documentDetailResponse[i].filing_date ?
                                    documentDetailResponse[i].filing_date : '-';
                                var username = documentDetailResponse[i].username ?
                                    documentDetailResponse[i].username : '-';
                                var filePath = documentDetailResponse[i].file_upload_path ?
                                    String(documentDetailResponse[i].file_upload_path) : '-';
                                var purpose = documentDetailResponse[i].purpose ?
                                    String(documentDetailResponse[i].purpose) : '-';
                                var htmlContent = documentDetailResponse[i].notice_html ?
                                    String(documentDetailResponse[i].notice_html) : '';

                                var main_filePath = documentDetailResponse[i].doc_path ?
                                    String(documentDetailResponse[i].doc_path) : '-';
                                var main_fileSize = documentDetailResponse[i].doc_size ?
                                    documentDetailResponse[i].doc_size : '0 ';
                                
                                   

                                var isSummonOrNotice = false;
                                var blobUrl = null;
                                if ('' == htmlContent) {
                                    isSummonOrNotice = false;
                                } else if ('' != htmlContent) {
                                    isSummonOrNotice = true;
                                    const blob = new Blob([htmlContent.toString()], {
                                        type: 'text/html'
                                    });
                                    blobUrl = URL.createObjectURL(blob);
                                }

                                const strFilingDate = new Date(filingDate);
                               // let formattedFilingDate = formatDateddmmyyyy(strFilingDate);
                               let formattedFilingDate = convertToAMPMFormat(strFilingDate);

                                arrstrFiledBy[filedByName] = filedByName;
                                tr_str = "<tr class=\"row\"><td class=\"cell\" data-title=\"id\">" + id +
                                    "</td><td class=\"cell\" data-title=\"FilingDate\">" + filedIn + "</td>" +
                                    (!boolHideDocumentTypeColumn ?
                                        "<td class=\"cell\" id=\"document_type_value\" data-title=\"Document Type\">" +
                                        documentType + "</td>" : "") +
                                    "<td class=\"cell\" data-title=\"Document Name\">" + documentName +
                                    "</td>" +
                                    // "<td class=\"cell\" data-title=\"Filed By \">" + filedByName+
                                     "<td class=\"cell\" data-title=\"Filed Date\">" + formattedFilingDate +
                                     "</td>" +
                                    "<td class=\"cell\" data-title=\"Username\">" + username +
                                    "</td><td class=\"cell\" data-title=\"Document\" align=\"center\">" +
                                    "<a href=\'" + main_filePath + "\' target=\"_blank\" id=\"filePath\">" +
                                    "<img file_path=\'" + main_filePath + "\' value=\'" + blobUrl + "\' align=\"center\" src=\"./assets/document.png\" height=\"24\" width=\"24\"/>" +
                                    "</a>" + "<div class=\"hello\" style=\"margin:10px;\">"+ main_fileSize +"</div>" +
                                    "</td></tr>";
                            
                            //  console.log(tr_str);
                                $('#userTable tbody').append(tr_str);
                            }

                            if (boolHideDocumentTypeColumn) {
                                $('#documentTypeHeaderMsg').html('Document Type : ' + documentType);
                                $('#table_header_document_type').hide();
                            } else {
                                $('#documentTypeHeaderMsg').html('');
                                $('#table_header_document_type').show();
                            }

                            //if (1 === Object.keys(arrstrFiledBy).length && boolHideFiledByColumn) {
                                if (Object.keys(arrstrFiledBy).length > 0 && boolHideFiledByColumn) {
   
                                $('#filedByHeaderMsg').html('Filed By : ' + titleCase(filedByName));
                                $('#table_header_filed_by').hide();
                                $('.filed_by_value_column').hide();
                            } else {
                               
                                $('#filedByHeaderMsg').html('');
                                $('#table_header_filed_by').show();
                                $('.filed_by_value_column').show();
                            }
                        }
                    }

                    const downloafImages = document.querySelectorAll(".file_download_img");
                    downloafImages.forEach(downloafImage => {
                        downloafImage.addEventListener('click', (event) => {
                            event.preventDefault();
                            if ('' === downloafImage.getAttribute('value') || 'null' === downloafImage.getAttribute('value')) {
                                var link = document.createElement('a');
                                link.href = downloafImage.getAttribute('file_path');
                                link.download = downloafImage.getAttribute('file_name') + '.pdf';
                                link.dispatchEvent(new MouseEvent('click'));
                            } else {
                                // var pdfContent = downloafImage.getAttribute('value');
                                var pdfLink = downloafImage.getAttribute('value');
                                const doc = new jsPDF();
                                const blobPDF = doc.output(pdfLink);
                                window.open(pdfLink, '_system', 'location=yes');
                            }
                        });
                    });
                },
                error: function(response) {
                    let res = JSON.parse(response.responseText);
                    if (res.data) {
                       
                        $("#errorMsg").append(res.data.error);
                        var tr_str = "<tr class=\"row\">" +
                            "<td class=\"cell text-align-center\" data-title=\"Filed By\" colspan=\"9\" align=\"center\"><strong>No Record Found</strong></td>" +
                            "</tr>";
                        $('#tableBody').append(tr_str);
                        $('#documentTypeHeaderMsg').html('');
                        $('#filedByHeaderMsg').html('');
                        $('#table_header_document_type').show();
                        $('#table_header_filed_by').show();
                    }
                },
            });
        }

        var reader = new FileReader();
        reader.onload = function() {
            alert(reader.result);
        }



        const base64ToArrayBuffer = (base64) => {
            const binaryString = window.atob(base64);
            const binaryLen = binaryString.length;
            const bytes = new Uint8Array(binaryLen);
            for (let i = 0; i < binaryLen; i++) {
                const ascii = binaryString.charCodeAt(i);
                bytes[i] = ascii;
            }
            return bytes;
        };

        const downloadFile = (fileName, byte) => {
            const link = document.createElement("a");
            link.href = window.URL.createObjectURL(byte);
            link.download = fileName;
            link.click();
        };

        function downloadPdf(filePath, htmlContent, documentName) {}

        function getDocumentOwnersList() {
            $.ajax({
                method: 'POST',
                url: 'api/eDocumentOwnerApiService.php?action=listAllEDocumentOwners',
                data: null,
                success: function(res) {
                    let response = JSON.parse(res).data;
                    var len = response.length;
                    var strOption = "";
                    for (var i = 0; i < len; i++) {
                        var partyFlag = "\'" + response[i].party_flag + "\'";
                        var eDocumentOwnerName = response[i].document_owner_name;
                        var strLi = "<li class=\"item  \" value=" +partyFlag + "\" >"+
                            "<span class=\"checkbox myCheckbox\" data-value=" + partyFlag + "\" >" +
                            "<i class=\"fa-solid fa-check check-icon\"></i>" +
                            "</span>" +
                            "<span class=\"item-text\">" + eDocumentOwnerName + "</span>" +
                            "</li>";
                        $('#filed-by-list').append(strLi);
                    }

                    const selectBtn = document.querySelector(".select-btn");
                    const items = document.querySelectorAll(".item");

                    selectBtn.addEventListener('click', () => {
                        selectBtn.classList.toggle('open');
                    });
                    items.forEach(item => {
                        item.addEventListener('click', () => {
                            item.classList.toggle('checked');
                            let checked = document.querySelectorAll('.checked');
                            let btnText = document.querySelector('.btn-text');

                            if (checked && checked.length > 0) {
                                btnText.innerText = checked.length + ' Selected';
                            } else {
                                btnText.innerText = 'Select';
                            }

                            if ('item checked' === item.getAttribute("class")) {
                                arrstrDocOwners.push(item.getAttribute("value"));
                                getAllDocumentTypes('', '', arrstrDocOwners);
                            } else {
                                var index = arrstrDocOwners.indexOf(item.getAttribute("value"));
                                if (index != -1) {
                                    arrstrDocOwners.splice(index, 1);
                                    getAllDocumentTypes('', '', arrstrDocOwners);
                                }
                            }
                            $('#hidden-filed-by-list').val(arrstrDocOwners);
                            $('#hidden_document_owner_list').val(arrstrDocOwners);
                            let formData = $('#document-list-form').serialize();
                            sendDocumentsListRequest(formData);
                        });
                    });
                    $("#filed-by-list").mouseleave(function() {
                        $(this).prev().removeClass('open');
                    });

                },
                error: function(response) {
                    let res = JSON.parse(response.responseText);
                },
            });
        }

        function searchDataByFilter(e) {
            let formData = $('#document-list-form').serialize();
            e.preventDefault();
            sendDocumentsListRequest(formData);
        }


        function getAllDocumentTypes(objDocumentOwner, strAllDocumentOwners = null,doc_owner) {
         
            var filingNumber = "<?php echo "$intFilingNumber" ?>";
            var schemaName = "<?php echo "$strSchemaName" ?>";
            if(doc_owner==undefined){
                var doc_owner= 'P';
            }
       
            $('#owner_id').val(objDocumentOwner.value);
            let formData = $('#document-list-form').serialize();
            $.ajax({
                method: 'POST',
                url: 'api/documentTypeApiService.php?action=listAllDocumentTypes&schema_name=' + schemaName +'&doc_owner='+doc_owner +'&filling_no='+filingNumber,
                data: formData,
                success: function(res) {
                    $("#document_type").empty();
                    $('#document-type-list').empty();
                    let response = JSON.parse(res).data;
                    var len = response.length;
                  
                    if(len === 0)
                {
                    var tr_str = "<tr class=\"row\">" +
                            "<td class=\"cell text-align-center\" data-title=\"Document Type\" colspan=\"9\" align=\"center\"><strong>No Record Found</strong></td>" +
                            "</tr>";
                        $('#document-type-list').append(tr_str);
                }
                    var strDocTypeList = null;
                    var strOption = "";

                    const option = new Option(camelCase('Select'), '');
                    $('#document_type').append(option, undefined);
                    for (var i = 0; i < len; i++) {
                        var documentTypeId = response[i].e_document_type;
                        var documentNameValue = "\'" + response[i].e_document_name + "\'";
                        var documentTypeName = response[i].e_document_name;

                        strDocTypeList = "<li class=\"item doc-type-item\" id=\"doc-type-item\" value=" +
                            documentNameValue + "\" onclick=\"searchDataByFilter( event )\">" +
                            "<div class=\"wrapper_chkbox\">" +
                            "<span class=\"checkbox\" value=" + documentNameValue + "\" onclick=\"searchDataByFilter( event )\">" +
                            "<i class=\"fa-solid fa-check check-icon doc-type-check-icon\"></i>" +
                            "</span>" +
                            "</div>" +
                            "<span class=\"item-text doc-type-item-text\" id=\"doc-type-item-text\">" +
                            documentTypeName + "</span>" +
                            "</li>"

                        $('#document-type-list').append(strDocTypeList);
                    }

                    // const docTypeSelectBtn = document.querySelector(".doc-type-select-btn");
                    const docTypeSelectBtn = document.getElementById("doc-type-select-btn");
                    const docTypeItems = document.querySelectorAll(".doc-type-item");
                    docTypeSelectBtn.addEventListener('click', () => {
                        document.getElementById("doc-type-select-btn").classList.add("open");
                        // docTypeSelectBtn.classList.toggle('open');
                    });
                    docTypeItems.forEach(docTypeItem => {
                        docTypeItem.addEventListener('click', () => {
                            docTypeItem.classList.toggle('doc-type-checked');
                            let docTypeChecked = document.querySelectorAll('.doc-type-checked');
                            let docTypeBtnText = document.querySelector('.doc-type-btn-text');

                            if (docTypeChecked && docTypeChecked.length > 0) {
                                docTypeBtnText.innerText = docTypeChecked.length + ' Selected';
                            } else {
                                docTypeBtnText.innerText = 'Select';
                            }

                            if ('item doc-type-item doc-type-checked' === docTypeItem.getAttribute("class")) {
                                arrstrDocTypes.push(docTypeItem.getAttribute("value"));
                            } else {
                                var index = arrstrDocTypes.indexOf(docTypeItem.getAttribute(
                                    "value"));
                                if (index != -1) {
                                    arrstrDocTypes.splice(index, 1);
                                }
                            }

                            $('#hidden-doc-type-list').val(arrstrDocTypes);
                            let formData = $('#document-list-form').serialize();
                            sendDocumentsListRequest(formData);
                        });
                    });

                    $("#document-type-list").mouseleave(function() {
                        $(this).prev().removeClass('open');
                    });
                },
                error: function(response) {
                    let res = JSON.parse(response.responseText);
                },
            });
        }

        $(document).ready(function() {

            let formData = $('#document-list-form').serialize();
            getDocumentOwnersList();
            getAllDocumentTypes(formData, null);
            sendDocumentsListRequest(formData);

            $('#btnSearch').click(function(e) {
                let formData = $('#document-list-form').serialize();
                e.preventDefault();
                sendDocumentsListRequest(formData);
            });

            $('#uploaded_date_from').change(function(e) {
                let formData = $('#document-list-form').serialize();
                e.preventDefault();
                sendDocumentsListRequest(formData);
            });

            $('#uploaded_date_to').change(function(e) {
                let formData = $('#document-list-form').serialize();
                e.preventDefault();
                sendDocumentsListRequest(formData);
            });
        });
    </script>

<!----------Sorting Code------->



<script>
function parseDateTime(str) {
  // Split date and time
  let [datePart, timePart] = str.split(' ');
  if (!datePart || !timePart) return new Date(NaN);

  let [day, month, year] = datePart.split('/');
  let [hours, minutes, seconds] = timePart.split(':');

  return new Date(year, month - 1, day, hours, minutes, seconds);
}

function sortTableByFilingDate(order = 'asc') {
  let rows = $('#userTable tbody tr').get();

  rows.sort(function (a, b) {
    let dateStrA = $(a).find('td:eq(4)').text().trim();
    let dateStrB = $(b).find('td:eq(4)').text().trim();

    let dateA = parseDateTime(dateStrA);
    let dateB = parseDateTime(dateStrB);

    return order === 'asc' ? dateA - dateB : dateB - dateA;
  });

  $('#userTable tbody').empty().append(rows);
}




 /*******Sorting Code***********/

 /********* AM/PM **********/
 function convertToAMPMFormat(dateStr) {
  if (dateStr instanceof Date) {
    const day = String(dateStr.getDate()).padStart(2, '0');
    const month = String(dateStr.getMonth() + 1).padStart(2, '0');
    const year = dateStr.getFullYear();

    let hour = dateStr.getHours();
   
    const minute = String(dateStr.getMinutes()).padStart(2, '0');
    const second = String(dateStr.getSeconds()).padStart(2, '0');

    let ampm = 'AM';
    if (hour >= 12) {
      ampm = 'PM';
     
    }
 
    return `${day}/${month}/${year} ${hour}:${minute}:${second} ${ampm}`;
  }


  return dateStr;
}


 /******* AM/PM ********/
 </script>

<?php } else {
    echo  'Not Authorized User. Please try again';
}  ?>

<link rel="stylesheet" href="../assets/css/simplePagination.css">

<?php 

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */ 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d');
include("../db_inc1.php");
 
 
	

	include '../inheader.php';
	//include '../insidebar.php'; 
	
  ?>

<style>
body {
    background-color: white;
}

h1 {
    color: maroon;
    margin-left: 40px;
}

@media print {
    #testdiv {
        display: none;
    }
}

.link {
    padding: 10px 15px;
    background: transparent;
    border: #bccfd8 1px solid;
    border-left: 0px;
    cursor: pointer;
    color: #607d8b
}

.disabled {
    cursor: not-allowed;
    color: #bccfd8;
}

.current {
    background: #bccfd8;
}

.first {
    border-left: #bccfd8 1px solid;
}

.question {
    font-weight: bold;
}

.answer {
    padding-top: 10px;
}

#pagination {
    margin-top: 20px;
    padding-top: 30px;
    border-top: #F0F0F0 1px solid;
}

.dot {
    padding: 10px 15px;
    background: transparent;
    border-right: #bccfd8 1px solid;
}

#overlay {
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 999;
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    display: none;
}

#overlay div {
    position: absolute;
    left: 50%;
    top: 10%;
    margin-top: -32px;
    margin-left: -32px;
}

.page-content {
    padding: 20px;
    margin: 0 auto;
}

.pagination-setting {
    padding: 10px;
    margin: 5px 0px 10px;
    border: #bccfd8 1px solid;
    color: #607d8b;
}

div.hidden {
    display: none;
}

.d-none {
    display: none;
}

.table-bordered>tbody>tr>td,
.table-bordered>tfoot>tr>td {
    border: 1px solid #cccccc;
}

.table-striped>tbody>tr:nth-of-type(odd) {
    background-color: #e0e9e9;
}

.table tr td {
    font-size: 14px;
}

select.form-control.select {
    display: inline;
    width: 200px;
    height: 24px;
    font-size: 12px;
    padding: 2px;
}

.red span {
    color: red;
}
</style>
<div id="overlay">
    <div><img src="../assets/loading.gif" width="64px" height="64px" /></div>
</div>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->



    <section class="content">

        <div id="testdiv" style="visibility: visible;" class="d-none">
            <a href="javascript:window.print();">
                <font size="4" color="red">Print</font>
            </a>
        </div>
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="row red">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <p><b>Annexure 2.4:</b> Form GST APL-04</p>
                                    <p>Form GST APL-04</p>
                                    <p>[See rules 113(1) & 115]</p>
                                    <p>Summary of the demand after issue of order by the Appellate Authority, Tribunal
                                        or Court</p>
                                </div>
                            </div>
                            <div class="col-md-6"><b>Order no. :</b> <span>123456789</span></div>
                            <div class="col-md-6 text-right"><b>Date of order :</b> <span>03-07-2024</span></div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><b>1.</b></td>
                                            <td colspan="10">GSTIN/Temporary ID/UIN - <span>content placed here</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>2.</b></td>
                                            <td colspan="10">Name of the appellant - <span>content placed here</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>3.</b></td>
                                            <td colspan="10">Address of the appellant - <span>content placed here</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>4.</b></td>
                                            <td colspan="4">Order appealed against - <span>content placed here</span>
                                            </td>
                                            <td colspan="3">Number - <span>content placed here</span></td>
                                            <td colspan="3">Date - <span>content placed here</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>5.</b></td>
                                            <td colspan="4">Appeal no. - <span>content placed here</span></td>
                                            <td colspan="6">Date - <span>content placed here</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>6.</b></td>
                                            <td colspan="10">Personal Hearing - <span>content placed here</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>7.</b></td>
                                            <td colspan="10">Order in brief - <textarea
                                                    class="form-control">content placed here</textarea></td>
                                        </tr>
                                        <tr>
                                            <td><b>8.</b></td>
                                            <td colspan="10">Status of order -
                                                <select class="form-control select">
                                                    <option value=''>Select Status</option>
                                                    <option value="">Confirmed</option>
                                                    <option value="">Modified</option>
                                                    <option value="">Rejected</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>9.</b></td>
                                            <td colspan="10">Amount of demand confirmed: - <span>content placed
                                                    here</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>Particulars</b></td>
                                            <td colspan="2">Central tax</td>
                                            <td colspan="2">State/UT tax</td>
                                            <td colspan="2">Integrated tax</td>
                                            <td colspan="2">Cess</td>
                                            <td colspan="2">Total</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>Disputed Amount</td>
                                            <td>Determined Amount</td>
                                            <td>Disputed Amount</td>
                                            <td>Determined Amount</td>
                                            <td>Disputed Amount</td>
                                            <td>Determined Amount</td>
                                            <td>Disputed Amount</td>
                                            <td>Determined Amount</td>
                                            <td>Disputed Amount</td>
                                            <td>Amount</td>
                                        </tr>
                                        <tr>
                                            <td><b>1</b></td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6</td>
                                            <td>7</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            <td>11</td>
                                        </tr>
                                        <tr>
                                            <td><b>(a) Tax</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>(b) Interest</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>(c) Penalty</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>(d) Fees</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>(e) Others</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>(f) Refund</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Place :</p>
                                <p>Date :</p>
                            </div>
                            <div class="col-md-12 text-right">
                                <p><span>Signature</span></p>
                                <p><span>Name of the Appellate Authority/Tribunal/ Jurisdictional Officer</span></p>
                                <p><span>Designation :</span></p>
                                <p><span>Jurisdiction :</span></p>
                            </div>
                        </div>


                        <div class="row red">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <p><b>Acknowledgment for submission of appeal/application/Cross-objections</b></p>
                                    <p>Form GST APL-02A</p>
                                    <p>Acknowledgment for submission of Appeal/Application</p>
                                    <p>Name of applicant/GSTIN/Temp ID/UIN/Reference Number with date</p>
                                    <p><b class="text-success">Your appeal has been successfully filed against :</b>
                                        <span>Application Reference Number</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><b>GSTIN/Temporary ID/UIN/ENR - </b> <span>text placed here</span></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Date of filing - </b></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Name of the person filing the appeal - </b></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Amount of pre-deposit - </b></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Transaction ID - </b></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Amount of Appeal Fee - </b></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Transaction ID(s) - </b></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="../assets/js/jquery.simplePagination.js"></script>



<?php	include '../infooter.php'; ?>
<div id="view_doc" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:90%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="view_doc_body">
                <p>Some text in the modal.</p>
            </div>
        </div>

    </div>
</div>
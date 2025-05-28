<?php

$access_token = (isset($_POST['srfOrdersJudgement']) && $_POST['srfOrdersJudgement'] == 'e86f78a8a3caf0b60d8e74e5942aa6d86dc150cd3c03338aef25b7d2d7e3acc7')?$_POST['srfOrdersJudgement']:'';

if(empty($access_token)){
	//echo "Forbidden";
	//die;
}

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata"); 
include "db_inc1.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judgement/Orders</title>
    <link rel="stylesheet" href="ajax/css/style.css">
    <link rel="stylesheet" href="ajax/css/fontawesome.css">

    <link rel="stylesheet" href="css/bootstrap.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/fontawesome.css">

    <script src="ajax/js/jquery.min.js"></script>
    <link rel="stylesheet" href="ajax/css/jquery-ui.css">
    <script src="ajax/js/jquery-ui.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="ajax/js/jquery.validate.min.js"></script>
        <style>
    .btn-primary,
    .btn-success {
        background-color: #846313;
        border-color: #846313;
    }

    .btn-primary:hover,
    .btn-success:hover {
        background-color: #294984;
        border-color: #294984;
    }

    .btn-primary:not(:disabled):not(.disabled):active,
    .btn-primary:not(:disabled):not(.disabled).active,
    .show>.btn-primary.dropdown-toggle,
    .btn-success:not(:disabled):not(.disabled):active,
    .btn-success:not(:disabled):not(.disabled).active,
    .show>.btn-success.dropdown-toggle {
        background-color: #da8a33;
        border-color: #b96c18;
    }

    header {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 5;
        width: 100%;
        height: 102px;
        box-shadow: 0px 1px 15px 0 rgba(0, 0, 0, 0.3);
        border-bottom: 1px solid rgba(255, 255, 255, 0.70);
        background-color: #fff;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: bottom center;
        overflow: hidden;
    }
	
	header .inner {
		overflow: hidden;
		width: 100%;
		max-width: 90%;
		margin: 0 auto;
		position: relative;
	}

    .logo img {
        width: 100%;
    }

    .bg-white {
        background: #FFF;
        border-bottom: 3px solid #30a569;
    }

    .fade.in {
        overflow-y: auto;
    }

    .modal-header .close {
        margin: 0;
        position: absolute;
        top: 0px;
        right: 8px;
    }

    .load_container {
        background: rgba(0, 0, 0, 0.50);
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        z-index: 99999;
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

    .form-group .error {
        color: red;

    }

    .modal_ngt .modal-header {
        background: #294984;
    }

    .form_box {
        max-width: 100%;
        margin: 40px auto 0 auto;
        background: rgba(255, 255, 255, 0.76);
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.35);
        border: 1px solid #846312;
        position: relative;
        z-index: 2;
    }

    .modal-header-inner {
        padding: 5px;
        font-size: 12px;
        background: #846313;
        border-radius: 5px;
        margin-top: 15px;
    }

    .modal-header-inner .modal-title {
        font-size: 15px;
        text-transform: uppercase;
        font-weight: bold;
    }

    footer {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        text-align: center;
        padding: 10px 15px;
        font-size: 13px;
        background: rgba(0, 84, 50, 0.50);
        color: #fff;
        text-shadow: 1px 1px 1px #000;
        z-index: 1;
    }

    .otherlogo1 {
		position: absolute;
		right: 158px;
		top: 22px;
		width: 138px;
    }

    .otherlogo2 {
        position: absolute;
        right: 16px;
        top: 20px;
		width: 112px;
    }

    .mainlogo {
        margin: 10px 10px 0;
		height: 80px;
    }

    .form_heading {
        font-size: 20px;
    font-weight: bold;
    color: #846312;
    text-transform: uppercase;
    margin: 0 0 10px 8px;
    display: inline-block;
    }

    .headnav {
        position: fixed;
        top: 102px;
        z-index: 4;
        width: 100%;
        padding: 0px 25px;
        color: #fff;
        box-shadow: 0px 0px 10px rgb(0 0 0 / 30%);
        font-size: 14px;
		background: rgb(51,82,160);
		background: -moz-linear-gradient(180deg, rgba(51,82,160,1) 0%, rgba(39,60,99,1) 100%);
		background: -webkit-linear-gradient(180deg, rgba(51,82,160,1) 0%, rgba(39,60,99,1) 100%);
		background: linear-gradient(180deg, rgba(51,82,160,1) 0%, rgba(39,60,99,1) 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#3352a0",endColorstr="#273c63",GradientType=1);
    }

    .headnav a {
        display: inline-block;
        padding: 8px 15px;
        color: #fff;
        font-weight: bold;
    }

    .headnav .headinfo {
        float: right;
        padding: 8px 15px;
    }

    .form_box1 {
        margin: 40px auto 30px auto;
        background: rgba(255, 255, 255, 0.76);
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.35);
        border: 1px solid #846312;
        position: relative;
        z-index: 2;
    }

    .fade {
        overflow-y: auto !important;
    }

    header .upper {
        background: linear-gradient(160deg, #ddeaf1 0%, #f4e6c6 100%);
        border-bottom: 3px solid #294984;
    }

    header {
        width: 100%;
        height: 106px;
        position: fixed;
        left: 0;
        right: 0;
        z-index: 99;
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

    .fade {
        overflow-y: auto !important;
    }
    #form_id_case_status {
        overflow: hidden;
    }
    .form_box .form-group {
    width: 31%;
    float: left;
    margin: 10px;
}
img.captcha {
    width: 100px;
}
#case_dfr_div_id .captcha-div{
    margin-top: 38px;
}
.form-group.text-right {
    display: block;
    width: 100%;
}
.form-group [type="button"] {
    display: block;
    margin: auto;
} 

@media (max-width:991px){
    .form_box .form-group {
    width: 45%;
    float: left;
    margin: 10px;
}
}

@media (max-width:667px){
    .form_box .form-group {
    width: 100%;
    float: left;
    margin: 10px 0;
}
}
    </style>
</head>
<body class="bg_green">
    </section>
    <header>
    <div class="upper">
            <div class="inner">
                <div>
                    <img src="./APTEL_files/GSTAT-Logo.png" class="left_logo">
                    <h1 class="site-title">GST Appellate Tribunal</h1>
                </div>
                <div class="right_logo">
                    <img src="./APTEL_files//logo_sb.png">
                    <img src="./APTEL_files//logo_di.png">
                </div>

            </div>
        </div>
    </header>
    <div class="headnav">
        <a href="https://uat-cis.gstat.gov.in/gstat"><i class="fa fa-home"></i> Home</a>
        <div class="headinfo"> <?php echo date("l jS \of F Y h:i:s A"); ?></div>
    </div>
    <div class="load_container">
        <img class="loader" src="ajax/images/loading-indicator.gif">
    </div>
    <div class="container" style="margin-top: 160px;"> 
        <div class="form_box">
        <div class="form_heading">Judgement/Orders</div>
            <form method="post" id="form_id_case_status" name="form_id_case_status">
			
				<?php 
					$dispaly_city = true;
					$cities = $db->prepare("select * from mater_location_city where display=? order by city_id desc");
					$cities->bindParam(1, $dispaly_city, PDO::PARAM_BOOL);
					$cities->execute();
					$cities = $cities->fetchAll();
				?>
				<div class="form-group">
                <label for="location">Select Location</label>
                    <select required="required" class="form-control" name="location" id="location">
                        <?php foreach($cities as $key=>$city) {?>
					<option value="<?php echo $city['schema_name']; ?>"><?php echo $city['city_name']; ?></option>
					<?php } ?>
                    </select>
                </div>
				
                <div class="form-group">
                    <label for="search_by">Select Search By</label>
                    <select required="required" class="form-control" name="search_by" id="search_by"
                        onchange="fn_case_dfr_type(this.value)">
                        <option value="">Select</option>
                        <option value="3" selected>Case Number</option>
                        <option value="5">Free Text</option>
                        <option value="6">Judges/Member</option>
						<option value="7">Court</option>
                        <option value="4">By Party</option>
						<option value="8">Category</option>
                    </select>
                </div>
                <div id="case_dfr_div_id"></div>
            </form>
        </div>
    </div>


    <div class="container" id="listing_case_detaisl" style="display:none;">
        <div class="form_box1" id="view_case_type_details">
        </div>
    </div>

    <!-- Modal View Case Type Details -->


    <div class="modal fade modal_ngt" id="case_details_popup" role="dialog">
        <div class="modal-dialog modal-dialog-centered  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="case_title_status" style="text-align: center">
                        GSTAT
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container_inner" id="div_case_details_popup">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal_ngt" id="case_details_popup_hearing" role="dialog">
        <div class="modal-dialog modal-dialog-centered  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="case_title_status">Hearing Daily Status</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container_inner" id="div_case_details_popup_hearing"></div>
                </div>
            </div>
        </div>
    </div>
<?php $search_by = isset($_REQUEST['search_by']) ? $_REQUEST['search_by'] : 3; ?>
    <script>
    $(document).ready(function() {
        fn_case_dfr_type('<?php echo $search_by; ?>');
		var selected_city = $("#location").val();
		get_court(selected_city);
    });
	
	function get_court(schema){
			$('.load_container').show();
            var data = {};
			data['action'] = 'get_court';
			data['schema_name'] = schema;
		$.ajax({
                type: "POST",
                url: "ajax/ajax.php",
                data: data,
                dataType: "html",
                success: function(data) {
					 $('.load_container').hide();
                    $("#courts").html(data);
                },
                error: function(request, error) {
                    alert("something error");
                     $('.load_container').hide();
                }
            });
	}
    </script>

    <script>
    $(".refresh").click(function() {
        $(".captcha").attr("src", "captcha.php?_=" + ((new Date()).getTime()));
    });
    </script>
    <script type="text/javascript" src="ajax/js/judgement.js?v=2"></script>

</body>

</html>

<?php

$access_token = (isset($_POST['srfCaseStatus']) && $_POST['srfCaseStatus'] == '26ff47e7809612824cc7a1c0e4815c9b32cf9b7dc1aad0c3a38c7113627b632b')?$_POST['srfCaseStatus']:'';

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
    <title>Causelist</title>
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
    <!-- <section class="logo bg-white no-display-on-mobile">
        <div class="container">
            <img src="APTEL_files/header1.png">
        </div> -->
    </section>
    <header>
		<!-- <div class="inner">
        <img src="ajax/images/logo_nclat.png" class="mainlogo">
        <img src="ajax/images/logo_sb.png" class="otherlogo1">
		<img src="ajax/images/logo_di.png" class="otherlogo2">
		</div> -->
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
        <!-- <a href="index.php"><i class="fa fa-home"></i> Home</a>
        <a href="case_status.php"> Case Status</a>
        <a href="cause_list.php"> Cause List</a> -->
        <div class="headinfo"> <?php echo date("l jS \of F Y h:i:s A"); ?></div>
    </div>

    <div class="load_container">
        <img class="loader" src="ajax/images/loading-indicator.gif">
    </div>

    <div class="container" style="margin-top: 160px;">

         
        <div class="form_box">
        <div class="form_heading">Causelist</div>
            <form method="post" id="form_id_case_status" name="form_id_case_status">
				<?php 
					$dispaly_city = true;
					$cities = $db->prepare("select * from mater_location_city where display=? order by city_id");
					$cities->bindParam(1, $dispaly_city, PDO::PARAM_BOOL);
					$cities->execute();
					$cities = $cities->fetchAll();
				?>
				<div class="form-group">
                <label for="location">Select Location</label>
                    <select required="required" class="form-control" name="location" id="location" onchange="get_court(this.value);">
                        <?php foreach($cities as $key=>$city) {?>
					<option value="<?php echo $city['schema_name']; ?>"><?php echo $city['city_name']; ?></option>
					<?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="search_by">Select Court</label>
                    <select required="required" class="form-control" name="bench_court" id="bench_court">
                        
                        
                    </select>
                </div>
                <div class="form-group">
                    <label for="search_by">Listing Date</label>
                    <input type="text" required id="listing_date" name="listing_date" autocomplete="off"  class="form-control datepicker" size="10" value="<?php print htmlspecialchars($listing_date); ?>"/>
                </div>
                <div class="form-group captcha-div">
		            <label><input name="answer" id="answer" type="text" placeholder="Captcha" class="form-control required input"
		                    size="18" maxlength="6" autocomplete="off"></label>
		            <img src="captcha.php" class="captcha" alt="captcha" />
		            <a href="javascript:void(0);">
		                <img class="refresh" src="assets/img/icon_refresh.png">
		            </a>
		        </div>
                <div class="form-group text-right">
		            <label>&nbsp;</label>
		            <button type="button" onclick="get_causelist()" class="btn btn-primary"><i class="fa fa-search"></i>
		                Search</button>
		        </div>
                <div id="case_dfr_div_id"></div>
            </form>
        </div>
    </div>


    <div class="container" id="listing_case_detaisl" style="display:none;">
        <div class="form_box1" id="view_case_type_details">
        </div>
    </div>



    <?php 
$location = isset($_REQUEST['location']) ? $_REQUEST['location'] : 'delhipb';
?>	
	<script src="plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="plugins/jQueryUI/jquery-ui.css">
<script src="plugins/jQueryUI/jquery-ui.js"></script>
<script src="plugins/jQueryUI/date.js"></script>

	<script src="src/calendar.js"></script>
    <script>
    $(document).ready(function() {
        get_court('<?php echo $location; ?>');
    });
    </script>

    <script>
    $(".refresh").click(function() {
        $(".captcha").attr("src", "captcha.php?_=" + ((new Date()).getTime()));
    });
    </script>
    <script type="text/javascript" src="ajax/js/custom_search.js?v=2.1"></script>

</body>

</html>

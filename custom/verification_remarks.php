<?php
session_start();
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
$filing_no=base64_decode($_REQUEST['filing_no']);

function getBORemarks($db,$filing_no)
{
   $filing_no = trim((string) $filing_no);
    $query_q="SELECT areapldetailscorrect,apl02rejectedapl04verfied,remarks FROM gst_ecase_validation_for_Apl0204 WHERE filingno = ?";
        try {
            $query = $db->prepare($query_q);
            $query->bindParam(1, $filing_no, PDO::PARAM_STR);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        } catch (PDOException $ex) {
            return $ex;
        }
}

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
                background:#1c3054;
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
                font-size: 16px;
                text-align: center;
                font-weight: bold;
            }
            ul.scroll {
                height: 250px;
                overflow-x: hidden;
                overflow-y: auto;
            }
         
        </style>
        <title>Document List Form</title>
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="../assets/css/newstyle.css">
        <link rel="stylesheet" type="text/css" href="css/listDocument.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://efilingreat.up.gov.in/upreat/superindex/jquery-3.7.0.min.js"></script>
        <script src="https://efilingreat.up.gov.in/upreat/superindex/html2pdf.bundle.min.js"></script>
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
        height: 85px;
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
</style>
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
            <ul class="nav navbar-nav topmenu">
            <li><a href="../index.php">Home </a></li></ul>
            <div class="headTitle">Verification Detail</div>
                <div class="headinfo"> <?php echo date("l jS \of F Y h:i:s A"); ?></div>
        </div>
    <body>
        <div style="width: 100%; float: left; height: auto; "><head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>GST Appellate Tribunal</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="assets/css/app.min.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">

  <!-- Fonts -->
  <link
    href="http://fonts.googleapis.com/css?family=Raleway:100,300,400,500,600,800%7COpen+Sans:300,400,500,600,700,800%7CMontserrat:400,700"
    rel="stylesheet" type="text/css">
  <!-- Favicons -->
  <link rel="icon" href="assets/img/favicon.ico">
	
	
</head>

<body>
<div>
	<header class="navheader">
    <div class="upper">
      <div class="inner">
        <img src="../APTEL_files/GSTAT-logo.png" class="left_logo">
        <h1 class="site-title">GST Appellate Tribunal</h1>
        <div class="right_logo">
          <img src="../APTEL_files/logo_sb.png">
          <img src="../APTEL_files/logo_di.png">
        </div>
      </div>
    </div>
    
  </header>
  
</div>

<style>

header .site-title {
    font-size: 2.75em;
    display: inline;
    font-family: serif;
    vertical-align: middle;
    margin-left: 15px;
}
.navmenu a{
 color:white !important;
}
body {
    margin: 0;
    max-width: 100%;
}

header {
    width: 100%;
}

header .upper {
    background: #fff;
    background-repeat: no-repeat;
    background-size: cover;
    border-bottom: 1px solid #3352a0;
}

header .inner {
    overflow: hidden;
    width: 100%;
    max-width: 90%;
    margin: 0 auto;
    padding: 14px 0px;
}

header .left_logo {
    height: 94px;
    float: left;
    margin-left: 10px;
}

header .right_logo {
    float: right;
    padding: 10px 0;
}

header .right_logo img {
    padding: 0 18px;
}

header .title {
    font-family: 'Times New Roman', Times, serif;
    color: #00863d;
    font-size: 30px;
    float: left;
    margin-top: 22px;
}

@media screen and (max-width: 1600px) {
	body {
		zoom: 0.85;
		-moz-zoom: 0.85;
		-webkit-zoom: 0.85;
	}
}

@media screen and (max-width: 991px) {
    header .inner {
        text-align: center;
    }

    header .left_logo {
        float: none;
        margin-left: 0;
    }

    header .title {
        font-size: 20px;
        margin-top: 13px;
        text-align: center;
        float: none;
    }

    header .right_logo {
        display: none;
    }
}

@media screen and (max-width: 670px) {
    header .left_logo {
        height: auto;
        width: 100%;
        padding: 0 20px;
    }
}</style>
</body></div>
        <div style="width:  100%; float: left; height:auto;  min-height: 570px;background-color: #fcf8e3;">




<style>
.listview ol li {
	margin-bottom: 10px;
}

.listview {
	border: 1px solid #ccc;
    border-radius: 10px;
    padding: 20px 20px;
}
.listview ol {
	margin-left: 0;
    padding-left: 20px;
}

.loading {
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    overflow: visible;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    display: none;
}

/* Transparent Overlay */
.loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
}

.loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.5em;
    -webkit-animation: spinner 1500ms infinite linear;
    -moz-animation: spinner 1500ms infinite linear;
    -ms-animation: spinner 1500ms infinite linear;
    -o-animation: spinner 1500ms infinite linear;
    animation: spinner 1500ms infinite linear;
    border-radius: 0.5em;
    box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
}
</style>
<script type="text/javascript"
	src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
<div class="loading">Loading&#8230;</div>
<div class="container padding120">
	
	
	
	<div class="demo" style="margin-top: 200px;" >
		
		<div class="panel-group" id="accordion" role="tablist"
			aria-multiselectable="true">
			<div class="panel panel-default" id="cont">
				<a class="collapsed" role="button" data-toggle="collapse"
					data-parent="#accordion" href="#collapseFour" aria-expanded="false"
					aria-controls="collapseThree">
					<div class="panel-heading" role="tab" id="headingThree"
						style="background-color: #4a565d;">
						<h4 class="panel-title" style="color: white; text-align: center">
						VERIFICATION REMARKS </h4>
					</div>
				</a>
				<div id="collapseFour1" role="tabpanel"
					aria-labelledby="headingThree" style="display: block;">
                        <div class="panel-body">
                            <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <div class="listview">
                                                <?php $remarks= getBORemarks($db,$filing_no); ?>
                                                        <li style="list-style: none!important; font-size:15px;">
                                                            <b>Are the APL Details Correct:</b>&nbsp;&nbsp;<?php echo $remarks['areapldetailscorrect']; ?>
                                                        </li>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="listview">
                                                        <li style="list-style: none!important; font-size:15px;">
                                                        <b>APL-02(Rejected)/APL-04 Verified: </b>&nbsp;&nbsp;<?php echo $remarks['apl02rejectedapl04verfied']; ?>
                                                        </li>
                                            </div>
                                        </div>
                                    
                                    </div>
                                
                                <div class="col-md-12" style="margin-top: 8px;">
                                    <div class="listview">
                                        <li style="list-style: none!important; font-size:15px;word-wrap: break-word;overflow-wrap: break-word;white-space: normal;">
                                                    <b>Remarks: </b>&nbsp;&nbsp;<?php  echo $remarks['remarks'];?>
                                        </li>
                                    </div>
                                </div> 
                            </div>
                        </div>    
                </div>
            </div>
        <div>
    </div>		<!-- container -->
</div>
</div>
</body>
   

</html>

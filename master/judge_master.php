<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");

session_start();
$_SESSION['user'];
$location_access=$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem.....";
//header("Location: ../login.php");
die();
}



setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
die("#2E2E2Eirecting to login.php");
}

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{

// This code not use next time .......	Schema session create Hear....


$sessionUserType=htmlspecialchars($_SESSION['id']);


$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";


include '../inheader.php';
//include '../insidebar.php';

?>
<?php 

$form2 = sha1( uniqid('auth', true) );
$_SESSION['form2_scruniny'] = $form2;
$y='add';
    $display_name ="ADD";
    $display_action ="Submit";
if($_REQUEST[y_id]!='')
{
$y='modify';
$display_name ="MODIFY";
$display_action ="Save";
$sth = $db->prepare("select * from $schemas.master_judge where judge_code=?");
$sth->execute(array($_REQUEST[y_id]));
$get_data = $sth->fetch();
    extract($get_data);
}



 ?>
<div class="content-wrapper" style="min-height: 946px;">
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    JUDGE MASTER
<small><?php echo $display_name;?></small>
</h1>
<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-dashboard"></i> HOME</a></li>
<li><a href="#">JUDGE MASTER</a></li>
<li class="active"><a href="./judge_master_report.php">REPORT</a></li>
</ol>
</section>

<!-- Main content -->
<section class="content">
<div class="row">
<!-- left column -->
<div class="col-md-6">
<!-- general form elements -->
<div class="box box-primary">
    <?php if($_SESSION[suss_message] !=''){?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
            <h4><i class="icon fa fa-check"></i><?php echo $_SESSION[suss_message]; unset($_SESSION[suss_message]);?></h4>
        </div>
        <?php
    } ?>
<!-- /.box-header -->
<!-- form start -->
<form name="frm" id="judge_form" method="post" action="judge_master_action.php">





<div class="box-body">
    <div class="form-group">
        <label for="exampleInputEmail1"> DESIGNATION</label>
        <?php   $judeg_desg_code = isset($_REQUEST['judeg_desg_code']) ? $_REQUEST['judeg_desg_code'] :$judge_desg_code; ?>
        <select name="judeg_desg_code"  id="judeg_desg_code" class="form-control" >
            <?php
            $sql="select * from $schemas.master_desg order by desg_name ASC";
            $schemaName = $db-> prepare($sql);
            $schemaName -> execute();
            ?>
            <option value="0"> Select </option>
            <?php
            while ($row =$schemaName->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
            {
                $ctc=$row['desg_code'];
                if($judeg_desg_code == $ctc)
                {
                    print "<option value=".htmlspecialchars($row['desg_code'])." selected>".htmlspecialchars($row['desg_name'])."</option>";
                }
                else
                {
                    print "<option value=".htmlspecialchars($row['desg_code']).">".htmlspecialchars($row['desg_name'])."</option>";
                }
            }
            ?>
        </select>
    </div>





<div class="form-group">
<label for="exampleInputEmail1">Mr/Ms/Smt/Shri</label>
<?php  $gen = isset($_REQUEST['gen']) ? $_REQUEST['gen'] :$gen; ?>
<input type="text" class="form-control" placeholder="" name="gen" id="gen" value="<?php echo $gen;?>">
<label for="exampleInputEmail1">Hon'ble</label>
<?php  $hon_text = isset($_REQUEST['hon_text']) ? $_REQUEST['hon_text'] :$hon_text; ?>
<input type="text" class="form-control"  placeholder="" name="hon_text" id="hon_text" value="<?php echo $hon_text;?>">
<label for="exampleInputEmail1">Judge Name</label>
    <?php  $judge_name = isset($_REQUEST['judge_name']) ? $_REQUEST['judge_name'] :$judge_name; ?>
<input type="text" class="form-control" id="judge_name" placeholder="" name="judge_name" value="<?php echo $judge_name;?>">


</div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    <input type="hidden" name="edit_id" id="edit_id" value="<?php echo htmlspecialchars($_REQUEST[y_id]);?>">
    <input type="hidden" name="frmAction" id="frmAction" value="<?php echo htmlspecialchars($y);?>">
    <input type="hidden" id="token" name="token" value="<?php echo htmlspecialchars($key);?>">
    <button type="submit" class="btn btn-primary"><?php echo $display_action?></button>
</div>
</form>
</div>
</div>
</div>

</div>
<!-- /.row -->
<div>
</section>
<!-- /.content -->
</div>

<script>

$(document).ready(function(){
    $('#judge_form').on('submit', function(e){
        e.preventDefault();
        var judeg_desg_code = $('#judeg_desg_code').val();
        var judge_name = $('#judge_name').val();
        var frmAction = $('#frmAction').val();
        var execution_no = $('#execution_no').val();
        if(judeg_desg_code == "0")
		{
			alert("Select designation");
			$("#judeg_desg_code").focus();
			return false;
		}
		if(judge_name == "")
		{
			alert("Enter judge name");
			$("#judge_name").focus();
			return false;
		}
        if(frmAction == 'add'){
            var postdata = {};
            postdata['frmAction'] = 'check_judge_name';
            postdata['judeg_desg_code'] = judeg_desg_code;
            postdata['judge_name'] = judge_name;
			postdata['token'] ='<?php echo htmlspecialchars($key);?>';
            $.ajax({
                url: "judge_master_action.php", 
                method : 'POST',
                data : postdata,
                success: function(result){
                    if(result == 0){
                        let text = "This name already exist are you still want to continue ?";
                        if (confirm(text) == true) {
                            save_data(frmAction);
                        }else{
                            return false;
                        }
                    }else{
                        save_data(frmAction);
                    }
                }
            }); 
        }else{
            save_data(frmAction);
        }
        
    });
});


function save_data(type){
    var postdata = {};
    postdata['frmAction'] = type;
    postdata['token'] = '<?php echo htmlspecialchars($key);?>';
    postdata['judeg_desg_code'] = $('#judeg_desg_code').val();
    postdata['judge_name'] = $('#judge_name').val();
    postdata['edit_id'] = $('#edit_id').val();
    postdata['hon_text'] = $('#hon_text').val();
    postdata['gen'] = $('#gen').val();
    $.ajax({
        url: "judge_master_action.php", 
        method : 'POST',
        data : postdata,
        success: function(result){
                    if(type == 'modify'){
                alert('judge updated');
                
                }else{
                    alert('judge added');
                }
              location.href = 'judge_master_report.php';

        }
    });
}


</script>

<?php 
include '../infooter.php';
?>
<?php } ?>
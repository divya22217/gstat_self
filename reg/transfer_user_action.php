<script type="text/javascript" language="javascript">
function DisableBackButton() {
window.history.forward()
}
DisableBackButton();
window.onload = DisableBackButton;
window.onpageshow = function(evt) { if (evt.persisted) DisableBackButton() }
window.onunload = function() { void (0) }
</script>
<?php
//header( "refresh:5;url=wherever.php" );
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
include("../includes/db_inc.php");//database connection
session_start();
$localadmin=$_SESSION['localadmin'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$schemas_id=htmlspecialchars($_SESSION['schema_idccc']);
$user_id=htmlspecialchars($_SESSION['id']);



$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status)
values (?,?,?,?,?)");
$xx='User transfer';
$x='security True Access';
$at->bindParam(1, $_SESSION[user], PDO::PARAM_STR);
$at->bindParam(2, $_SERVER[REMOTE_ADDR], PDO::PARAM_STR);
$at->bindParam(3, date("Y/m/d h:i:s"), PDO::PARAM_STR);
$at->bindParam(4, $xx, PDO::PARAM_STR);
$at->bindParam(5, $x, PDO::PARAM_STR);
$at->execute();


if($_SESSION['user'] == '' || empty($_SESSION[user]))
{
echo "Access Problem.....";
header("Location: ../login.php");
die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
if($_SESSION['user'] !=''|| !empty($_SESSION[user]))
{
//print_r($_SESSION);

/*csrf validate*/

if($_REQUEST[csrf_token]!=$_SESSION[form_token])
{
echo "You are not Valied User..... please login again";
header("Location: ../index.php");
die();
}
$epfo_name = htmlspecialchars($_REQUEST[epfo_name]);
$transfer_user_id= htmlspecialchars($_REQUEST[transfer_user_id]);
$desired_location = htmlspecialchars($_REQUEST[desired_location]);
$date_of_transfer = htmlspecialchars($_REQUEST[date_of_transfer]);
$designation_new = htmlspecialchars($_REQUEST[designation]);
$reason_for = htmlspecialchars($_REQUEST[reason_for]);

/*transfer date validate*/
$curent_date_no = date('Ymd');
list($dd,$mm,$yy)=explode("/",$date_of_transfer);
if($curent_date_no < $yy.$mm.$dd)
{
echo "Transfer date is not greater then current date";
header("Location: ../index.php");
die();
}


/*check location exist or not*/
$ms_sth=$db->prepare("select m_s.schema_name as newschema,m_l.location_id as new_loc_id from master_schema as m_s,master_location as m_l where m_l.schema_id=m_s.schema_id and m_s.schema_id=? and m_s.display='Y'");
$ms_sth->execute(array($desired_location));
if($ms_sth->rowCount()==0)
{
echo "This location not found at this moment";
header("Location: ../index.php");
die();
}
else
{
	$master_schema_data = $ms_sth->fetch();
	//print_r($master_schema_data);
	extract($master_schema_data);
}
//$db->beginTransaction();
//print_r($_POST);die();
switch($localadmin)
{
case'0':
	//echo'<h2>This is User</h2>';
	$messqge="<br>Please login again to view the changes<br><a href='../index.php'>click here</a>";
	$para1=$user_id;$para2=$schemas_id;
	if($epfo_name!= $schemas_id){ header("Location: ../index.php"); die("You dont have access for this request");}
	if($transfer_user_id!=$user_id){ header("Location: ../index.php"); die("You dont have access for this request");}
break;
case'1':
	//echo'<h2>This is nodel/admin</h2>';
	$messqge="<br>user successfully transfered";
	$para1=$transfer_user_id;$para2=$schemas_id;
break;
case '2':
	//echo'<h2>This is headoffice/superadmin</h2>';
	$messqge="<br>user successfully transfered";
	$schemas="";
	$ms_sth=$db->prepare("select schema_name as schemas from master_schema where schema_id=?");
	$ms_sth->execute(array($epfo_name));
	echo $schemas=$ms_sth->fetchColumn();
	$schema_id=$epfo_name;
	$para1=$transfer_user_id;$para2=$epfo_name;
break;
default:
header("Location: ../index.php");
die("You have enter wrong request");

}
/*get user data*/
$chek_his_user = $db->prepare("select id as db_user_id,schema_id as db_schema_id ,location as db_location,designation as old_designation,officer_id,fname as officer_name from users where id= ? and schema_id=?");
$chek_his_user->execute(array($para1,$para2));
if($chek_his_user->rowCount()==0){ header("Location: ../index.php");die("You dont have access for this request");}
else{$chek_his_user_row = $chek_his_user->fetch(); extract($chek_his_user_row);}
//print_r($chek_his_user_row);

echo '<pre>';
/*get menu per*/
$menu_per_q =$db->prepare("select * from menu where userid=? ");
if($menu_per_q->execute(array($db_user_id))){ $menu_per = serialize($menu_per_q->fetchAll());}
$sub_menu_per_q =$db->prepare("select * from sub_menu where userid=? ");
if($sub_menu_per_q->execute(array($db_user_id))){  $sub_menu_per = serialize($sub_menu_per_q->fetchAll());}
$sub_sub_menu_per_q =$db->prepare("select * from sub_sub_menu where userid=? ");
if($sub_sub_menu_per_q->execute(array($db_user_id))){  $sub_sub_menu_per = serialize($sub_sub_menu_per_q->fetchAll());}



/*get the officer table data*/
$officer_q = $db->prepare("select id as officer_idd,officer_name,court_no,from_date,to_date,title from $schemas.master_officer where user_id=?");
$officer_q->execute(array($db_user_id));
if($officer_q->rowCount()>0)
{
/*officer entry not found*/
$officer_data = $officer_q->fetch();
//print_r($officer_data);
extract($officer_data);
$up_officer = $db->prepare("update $schemas.master_officer set to_date =?,display='false' WHERE id=?");
$up_officer->execute(array($date_of_transfer,$officer_idd));
$del_officer = $db->prepare("insert into $schemas.master_officer_his (SELECT * from $schemas.master_officer where id=?)");
$del_officer->execute(array($officer_idd));
$del_officer = $db->prepare("delete from $schemas.master_officer WHERE id=?");
$del_officer->execute(array($officer_idd));
}



$sql="insert into user_transfer(transfer_id,from_date,old_schema_id,new_schema_id,transfer_date,old_desg,new_desg,datetime,remarks,user_id,user_name,old_menu,old_sub_menu,old_sub_sub_menu)
values(:transfer_id,:from_date,:old_schema_id,:new_schema_id,:transfer_date,:old_desg,:new_desg,:datetime,:remarks,:user_id,:user_name,:menu,:sub_menu,:sub_sub_menu)";
$sth = $db->prepare($sql);
$params = array(
':transfer_id'=>$db_user_id,
':from_date'=>$from_date,
':old_schema_id'=>$schemas_id,
':new_schema_id'=>$desired_location,
':transfer_date'=>$date_of_transfer,
':old_desg'=>$old_designation,
':new_desg'=>$designation_new,
':datetime'=>date('d/m/Y h:i:s'),
':remarks'=>$reason_for,
':user_id'=>$user_id,
':user_name'=>$_SESSION[user],
':menu'=>$menu_per,
':sub_menu'=>$sub_menu_per,
':sub_sub_menu'=>$sub_sub_menu_per
);
//print_r($params);
$sth->execute($params);



$desg_check_array = array('1','2','3');
if (in_array($designation_new, $desg_check_array))
{
$max_code_q=$db->prepare("select max(id) from $newschema.master_officer");
$max_code_q->execute();
$officer_idd = $max_code_q->fetchColumn()+1;
$ins_officer = $db->prepare("insert into $newschema.master_officer(id,officer_name,court_no,display,schema_id,desg_code,from_date,title,user_id)
VALUES(:id,:officer_name,:court_no,:display,:schema_id,:desg_code,:from_date,:title,:user_id)");
$officer_param = array($officer_idd,$officer_name,$officer_idd,'true',$desired_location,$designation_new,$date_of_transfer,$title,$db_user_id);
//print_r($officer_param);
$ins_officer->execute($officer_param);
}
$sth = $db->prepare("update users set schema_id=?,location=?,designation=?,officer_id=? where id=?");
$params = array($desired_location,$new_loc_id,$designation_new,$officer_idd,$db_user_id);
//	print_r($params);
$sth->execute($params);

/*delete permision*/
$menu_per_q =$db->prepare("delete from menu where userid=? ");
$menu_per_q->execute(array($db_user_id));
$sub_menu_per_q =$db->prepare("delete from sub_menu where userid=? ");
$sub_menu_per_q->execute(array($db_user_id));
$sub_sub_menu_per_q =$db->prepare("delete from sub_sub_menu where userid=? ");
$sub_sub_menu_per_q->execute(array($db_user_id));



unset($_SESSION[form_token]);
$_SESSION[suss_message]= base64_encode($messqge);
header("Location: ./transfer_user.php");
//$db->rollBack();
} //main cont. close
?>

<?php
	 include("./db_inc1.php");
include './db_inc2.php';

$filing_no='0710102099472019';
$cur_date = '2019-07-01';
$userid = '80';

$st=$db->prepare("select * from e_case_detail_local where filing_no=? ");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$filing_no = ($row['filing_no']);
	$pet_name = ($row['pet_name']);
	$res_name = ($row['res_name']);
	$dt_of_filing = ($row['dt_of_filing']);
	$case_type=($row['case_type']);
	$pet_type=$row['pet_type'];
	$pet_adv=$row['pet_adv'];
	$pet_address=$row['pet_address'];
	$pet_state=$row['pet_state'];
	$pet_district=$row['pet_district'];
	$pet_email=$row['pet_email'];
	$pet_mobile=$row['pet_mobile'];
	$pet_phone=$row['pet_phone'];
	$pet_fax=$row['pet_fax'];
	$res_type=$row['res_type'];
	$res_adv=$row['res_adv'];
	$res_address=$row['res_address'];
	$res_state=$row['res_state'];
	$res_district=$row['res_district'];
	$res_email=$row['res_email'];
	$res_mobile=$row['res_mobile'];
	$res_phone=$row['res_phone'];
	$res_fax=$row['res_fax'];
	$amount=$row['amount'];
	$pet_code=$row['pet_code'];
	$res_code=$row['res_code'];
	$pet_adv_name=$row['pet_adv_name'];
	$res_adv_name=$row['res_adv_name'];
	$pet_pin=$row['pet_pin'];
	$res_pin=$row['res_pin'];
	$payment_status=$row['payment_status'];
	$e_reference_no=$row['e_reference_no'];
}

if($pet_type =='' OR $pet_type =='0')
{$pet_type='1';}
if($pet_adv =='' OR $pet_adv =='0')
{$pet_adv ='0';}
if($pet_state =='' OR $pet_state =='0')
{$pet_state='0';}
if($pet_district =='' OR $pet_district =='0')
{$pet_district='0';}
if($res_type =='' OR $res_type =='0')
{$res_type='1';}
if($res_adv =='' OR $res_adv =='0')
{$res_adv='0';}
if($res_state =='' OR $res_state =='0')
{$res_state='0';}
if($res_district =='' OR $res_district =='0')
{$res_district='0';}
if($pet_code =='' OR $pet_code =='0')
{$pet_code='0';}
if($res_code =='' OR $res_code =='0')
{$res_code='0';}
if($pet_pin =='' OR $pet_pin =='0')
{$pet_pin='0';}
if($res_pin =='' OR $res_pin =='0')
{$res_pin='0';}



$status11='P';

	$aa=$db->prepare("insert into delhi.case_detail(filing_no,case_type,dt_of_filing,pet_type,pet_name,pet_adv,
			pet_address,pet_state,pet_district,pet_email,pet_mobile,pet_phone,
			pet_fax,res_type,res_name,res_adv,res_address,res_state,
			res_district,res_email,res_mobile,res_phone,res_fax,amount_payment,
			pet_code,res_code,pet_adv_name,res_adv_name,pet_pin,res_pin,
			loginid,entry_date,case_year,e_reference_no,status)
			values
			('$filing_no','$case_type','$dt_of_filing','$pet_type','$pet_name','$pet_adv','$pet_address','$pet_state',
			'$pet_district','$pet_email','$pet_mobile','$pet_phone','$pet_fax','$res_type','$res_name','$res_adv',
			'$res_address','$res_state','$res_district','$res_email','$res_mobile','$res_phone','$res_fax','$amount',
			'$pet_code','$res_code','$pet_adv_name','$res_adv_name','$pet_pin','$res_pin','$userid','$cur_date',
			'$rgyear','$e_reference_no','$status11')");
	$aa->execute();
	
?>
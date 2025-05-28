	 <?php
	 include("./db_inc1.php");
include './db_inc2.php';
	 
	 $status='0';
	 $payment_accept='Y';
	 $sxx='NA';
	$st3=$dbonline->prepare("select * from e_case_detail where filing_no='0710102001752019'");
	$st3->execute();


	while ($row = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
	        $E_filing_no=$row['filing_no'];
	
		 $E_e_reference_no=$row['e_reference_no'];
		$E_location_id=$row['location_id']; //0
		$E_bench_id=$row['bench_id']; //0
		//$E_inter_act_id=$row['inter_act_id']; 
		$E_act_id=$row['act_id']; //0
		 $E_case_type=$row['case_type']; //0
		$E_dt_of_filing=$row['dt_of_filing']; //1111-11-11
		$E_relief_claimed=$row['relief_claimed'];
		$E_status=$row['status'];
		$E_pet_code=$row['pet_code']; //0
		$E_res_code=$row['res_code']; //0
		$E_priority=$row['priority']; //0
		$E_amount=$row['amount']; //0
		$E_stage=$row['stage'];
		$E_hc_dc=$row['hc_dc'];
		$E_case_title=$row['case_title'];
		$E_subject_matter=$row['subject_matter'];
		$E_subject_matter_id=$row['subject_matter_id'];
		$E_remarks=$row['remarks'];
		$E_payment_status=$row['payment_status'];
		$E_payment_accept=$row['payment_accept'];
		//$E_dt_of_payment=$row['dt_of_payment'];
		$E_payment_reference_no=$row['payment_reference_no'];
		$E_display=$row['display'];
				if($E_dt_of_payment ==''){$E_dt_of_payment=$E_dt_of_filing;}
		$E_party_flag1='P';
		$E_party_serial_no1='1';
		
	
		
		$st33=$dbonline->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st33->bindParam(1, $E_filing_no, PDO::PARAM_STR);
		$st33->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$st33->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$st33->execute();
		
		while ($row = $st33->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$E_party_flagP=$row['party_flag']; 
			$E_party_serial_noP=$row['party_serial_no']; //0
			$E_nameP=$row['name']; //0
			$E_party_org_typeP=$row['party_org_type']; //0
			$E_party_org_contact_personP=$row['party_org_contact_person']; 
			$E_party_addressP=$row['party_address']; 
			$E_pinP=$row['pin']; //0
			$E_state_codeP=$row['state_code']; //0
			$E_district_codeP=$row['district_code']; //0
			$E_nationalityP=$row['nationality']; 
			$E_emailP=$row['email']; 
			$E_mobileP=$row['mobile']; 
			$E_representative_codeP=$row['representative_code']; //0
			$E_aadhar_noP=$row['aadhar_no']; //0
			 $E_cin_noP=$row['cin_no']; //0
		}	
		
		
		$E_party_flag1='R';
		$E_party_serial_no1='1';
		$st34=$dbonline->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st34->bindParam(1, $E_filing_no, PDO::PARAM_STR);
		$st34->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$st34->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$st34->execute();
		while ($row = $st34->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$E_party_flagR=$row['party_flag'];
			$E_party_serial_noR=$row['party_serial_no']; //0
			$E_nameR=$row['name']; //0
			$E_party_org_typeR=$row['party_org_type']; //0
			$E_party_org_contact_personR=$row['party_org_contact_person'];
			$E_party_addressR=$row['party_address'];
			$E_pinR=$row['pin']; //0
			$E_state_codeR=$row['state_code']; //0
			$E_district_codeR=$row['district_code']; //0
			$E_nationalityR=$row['nationality'];
			$E_emailR=$row['email'];
			$E_mobileR=$row['mobile'];
			$E_representative_codeR=$row['representative_code']; //0
			$E_aadhar_noR=$row['aadhar_no']; //0
			$E_cin_noR=$row['cin_no']; //0
		}	
		
				
		if($E_location_id ==''){$E_location_id='0';}
		if($E_case_type ==''){$E_case_type='0';}
		if($E_dt_of_filing ==''){$E_dt_of_filing='1111-11-11';}
		$E_pet_type='1';$E_res_type='1';$E_status_for='P';$EE_scrutiny_comp='0';
	if($E_representative_codeP ==''){$E_representative_codeP='0';}
	if($E_representative_codeR ==''){$E_representative_codeR='0';}
	if($E_state_codeP ==''){$E_state_codeP='0';}if($E_state_codeR ==''){$E_state_codeR ='0';}
	if($E_district_codeR == ''){$E_district_codeR='0';}if($E_district_codeP == ''){$E_district_codeP='0';}
	if($E_mobileP ==''){$E_mobileP='0';}if($E_mobileR ==''){$E_mobileR='0';}

	if($E_pet_code ==''){$E_pet_code='0';}if($E_res_code ==''){$E_res_code='0';}
	if($E_pinP ==''){$E_pinP='0';}if($E_pinR ==''){$E_pinR='0';}
	if($E_act_id ==''){$E_act_id='0';}
	if($E_nationalityP ==''){$E_nationalityP='0';}
	if($E_nationalityR ==''){$E_nationalityR='0';}


	
	
		$st39=$db->prepare("insert into e_case_detail_local (filing_no,location_id,case_type,dt_of_filing,
				pet_type,pet_name,pet_adv,pet_address,pet_state,pet_district,pet_nationality,pet_email,pet_mobile,
				res_type,res_name,res_adv,res_address,res_state,res_district,res_nationality,res_email,res_mobile,
				amount,status,pet_code,res_code,pet_pin,res_pin,scrutiny_comp,section_id_add,act_id)
				values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ");
		$st39->bindParam(1, $E_filing_no, PDO::PARAM_STR);
		$st39->bindParam(2, $E_location_id, PDO::PARAM_STR);
		$st39->bindParam(3, $E_case_type, PDO::PARAM_STR);
		$st39->bindParam(4, $E_dt_of_filing, PDO::PARAM_STR);
		$st39->bindParam(5, $E_pet_type, PDO::PARAM_STR);
		$st39->bindParam(6, $E_nameP, PDO::PARAM_STR);
		$st39->bindParam(7, $E_representative_codeP, PDO::PARAM_STR);
		$st39->bindParam(8, $E_party_addressP, PDO::PARAM_STR);
		$st39->bindParam(9, $E_state_codeP, PDO::PARAM_STR);
		$st39->bindParam(10, $E_district_codeP, PDO::PARAM_STR);
		$st39->bindParam(11, $E_nationalityP, PDO::PARAM_STR);
		$st39->bindParam(12, $E_emailP, PDO::PARAM_STR);
		$st39->bindParam(13, $E_mobileP, PDO::PARAM_STR);
		$st39->bindParam(14, $E_res_type, PDO::PARAM_STR);
		$st39->bindParam(15, $E_nameR, PDO::PARAM_STR);
		$st39->bindParam(16, $E_representative_codeR, PDO::PARAM_STR);
		$st39->bindParam(17, $E_party_addressR, PDO::PARAM_STR);
		$st39->bindParam(18, $E_state_codeR, PDO::PARAM_STR);
		$st39->bindParam(19, $E_district_codeR, PDO::PARAM_STR);
		$st39->bindParam(20, $E_nationalityR, PDO::PARAM_STR);
		$st39->bindParam(21, $E_emailR, PDO::PARAM_STR);
		$st39->bindParam(22, $E_mobileR, PDO::PARAM_STR);
		$st39->bindParam(23, $E_amount, PDO::PARAM_STR);
		$st39->bindParam(24, $E_status_for, PDO::PARAM_STR);
		$st39->bindParam(25, $E_pet_code, PDO::PARAM_STR);
		$st39->bindParam(26, $E_res_code, PDO::PARAM_STR);
		$st39->bindParam(27, $E_pinP, PDO::PARAM_STR);
		$st39->bindParam(28, $E_pinR, PDO::PARAM_STR);
		$st39->bindParam(29, $EE_scrutiny_comp, PDO::PARAM_STR);
		$st39->bindParam(30, $EE_scrutiny_comp, PDO::PARAM_STR);
		$st39->bindParam(31, $E_act_id, PDO::PARAM_STR);
		$st39->execute();			
		

  }
  ?>
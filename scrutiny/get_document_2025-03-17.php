 <?php 

//    ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);   
include("../db_inc1.php");
$schemas=htmlspecialchars($_SESSION['schema_name']);	
$_SESSION['qqcc'] = rand();
$qq1cc=$_SESSION['qqcc'];	
$location_access=$_SESSION['location'];	
date_default_timezone_set("Asia/Kolkata");
require_once("../classes/Pagination.class.php");
include("../formkey/formkey.class.php");
$form_key = new formKey();
$user_court = $_SESSION['user_court'];


function get_filing_no($db,$schema,$case_no,$case_year,$case_type,$status){

		$query = "select filing_no from $schema.case_detail where case_type = ? and case_no = ? and case_year = ? and status = ?";
		$case_detail = $db->prepare($query);
		$case_detail->bindParam(1, $case_type, PDO::PARAM_INT);
		$case_detail->bindParam(2, $case_no, PDO::PARAM_INT);
		$case_detail->bindParam(3, $case_year, PDO::PARAM_INT);
		$case_detail->bindParam(4, $status, PDO::PARAM_INT);
		$case_detail->execute();
		$fn = $case_detail->fetchColumn();
		return $fn;
	}
	//var_dump($validatekey);
		$hash_for_doc = $form_key->randomkey();
				   
				$ll ='NA';
          $llpp='0';
          $SER_COUT='1'; 
			$SER_COUT3=1;		  
           $doc_flag = 1;
		   $fictitious = '00';
		   
		   
		   
		$perPage = new PerPage(50);
		$paginationlink = "get_document.php?page=";
		$page = 1;
		if(!empty($_GET["page"])) {
		$page = $_GET["page"];
		}

		$start = ($page-1)*$perPage->perpage;
		if($start < 0) $start = 0;
		
		
		$type = isset($_POST['type'])?$_POST['type']:'';
		if(!empty($type)){
			if($type == 'fn'){
				$filing_no = trim($_POST['filing_no']);
				if (!is_numeric($filing_no) && !empty($filing_no_no) ) {
					echo "Invalid Input";
					die();
					}
				if($filing_no != ''){
					$filing_no_query = "and (du.filing_no = '$filing_no')";
				}else{
					$filing_no_query = "";
				}
			}
			if($type == 'cn'){
				$case_type = $_POST['case_type'];
				$case_no = $_POST['case_no'];
				$case_year = $_POST['case_year'];
				if (!is_numeric($case_type) || !is_numeric($case_no)  || !is_numeric($case_year)) {
					echo "Invalid Input";
					die();
					}
				$filing_no = get_filing_no($db,$schemas,$case_no,$case_year,$case_type,'P');
				if($filing_no != ''){
					$filing_no_query = "and (du.filing_no = '$filing_no')";
				}else{
					echo "case no not registred."; die;
				}
			}
		}
		else{
			$filing_no_query = "";
		}
		
		if(empty($_GET["rowcount"])) {
			if($_SESSION['menuaccess_codeall'] =='2' || $_SESSION['menuaccess_codeall'] =='20'){
					  
			 
				  $display='1';
				  $scrutiny_d='0';
				
				  //$doc_level='NULL';
				  $st_count=$db->prepare("select count(distinct(miscellenous_no)) as count from document_upload as du 
				  	left join e_case_detail as ecd on ecd.filing_no = du.filing_no
				  	left join e_not_found as enf on enf.fictitious_filing_no = du.filing_no where du.filing_no !=? and du.display=? and du.scrutiny=? and (du.doc_level IS NULL or du.doc_level = '') and du.miscellaneous_ref_no is NOT NULL and du.doc_flag = ? and du.miscellenous_no != ? and ((ecd.location_id =? and ecd.court = ?) or (substring(du.filing_no from 1 for 2)=? AND enf.location_id = ?)) $filing_no_query");
				  $st_count->bindParam(1, $ll, PDO::PARAM_STR);
				  $st_count->bindParam(2, $display, PDO::PARAM_STR);
				  $st_count->bindParam(3, $scrutiny_d, PDO::PARAM_STR);
				  $st_count->bindParam(4, $doc_flag, PDO::PARAM_STR);
				  $st_count->bindParam(5, $ll, PDO::PARAM_STR);
				  $st_count->bindParam(6, $location_access, PDO::PARAM_STR);
				  $st_count->bindParam(7, $user_court, PDO::PARAM_STR);
				  $st_count->bindParam(8, $fictitious, PDO::PARAM_STR);
				  $st_count->bindParam(9, $location_access, PDO::PARAM_STR);
				  $st_count->execute();
			}
			if($_SESSION['menuaccess_codeall'] =='11'){
				$display='1';
				 $scrutiny_d='0';
				 $doc_level='1';
				 
				
				  $st_count=$db->prepare("select count(distinct(miscellenous_no)) as count from document_upload as du 
				  	left join e_case_detail as ecd on ecd.filing_no = du.filing_no
				  	left join e_not_found as enf on enf.fictitious_filing_no = du.filing_no  where du.filing_no !=?   and du.display=? and du.scrutiny=? and du.doc_level=? and du.miscellaneous_ref_no is NOT NULL and du.doc_flag = ? and du.miscellenous_no != ? and ((ecd.location_id = ? and ecd.court = ?) or (substring(du.filing_no from 1 for 2)=? AND enf.location_id = ?)) $filing_no_query");
				  $st_count->bindParam(1, $ll, PDO::PARAM_STR);
				  $st_count->bindParam(2, $display, PDO::PARAM_STR);
				  $st_count->bindParam(3, $scrutiny_d, PDO::PARAM_STR);
				  $st_count->bindParam(4, $doc_level, PDO::PARAM_STR);
				  $st_count->bindParam(5, $doc_flag, PDO::PARAM_STR);
				  $st_count->bindParam(6, $ll, PDO::PARAM_STR);
				  $st_count->bindParam(7, $location_access, PDO::PARAM_STR);
				  $st_count->bindParam(8, $user_court, PDO::PARAM_STR);
				  $st_count->bindParam(9, $fictitious, PDO::PARAM_STR);
				  $st_count->bindParam(10, $location_access, PDO::PARAM_STR);
				  $st_count->execute();
			}
			$st_count->execute();
			$_GET["rowcount"] = $st_count->fetchColumn();
			$total_docs = $_GET["rowcount"];
		}
			
		$perpageresult = $perPage->getAllPageLinks($_GET["rowcount"], $paginationlink,$pagination_setting='');   
		  if($_SESSION['menuaccess_codeall'] =='2' || $_SESSION['menuaccess_codeall'] =='20') //user 1
		  {
			  
			 
		  $display='1';
		  $scrutiny_d='0';
		
		  //$doc_level='NULL';
		  
          $st51=$db->prepare("select distinct(du.miscellenous_no) as miscellaneous_no,du.document_filed_date from document_upload as du left join e_case_detail as ecd on ecd.filing_no = du.filing_no left join e_not_found as enf on enf.fictitious_filing_no = du.filing_no where du.filing_no !=? and du.display=? and du.scrutiny=? and (du.doc_level IS NULL or du.doc_level = '') and du.miscellaneous_ref_no is NOT NULL and du.doc_flag = ? and du.miscellenous_no != ? and ((ecd.location_id = ? and ecd.court = ?) or (substring(du.filing_no from 1 for 2)= ? AND enf.location_id = ?)) $filing_no_query order by du.document_filed_date desc limit $perPage->perpage offset $start");
		  $st51->bindParam(1, $ll, PDO::PARAM_STR);
          $st51->bindParam(2, $display, PDO::PARAM_STR);
		  $st51->bindParam(3, $scrutiny_d, PDO::PARAM_STR);
		  $st51->bindParam(4, $doc_flag, PDO::PARAM_STR);
		  $st51->bindParam(5, $ll, PDO::PARAM_STR);
		  $st51->bindParam(6, $location_access, PDO::PARAM_STR);
		  $st51->bindParam(7, $user_court, PDO::PARAM_STR);
		  $st51->bindParam(8, $fictitious, PDO::PARAM_STR);
		  $st51->bindParam(9, $location_access, PDO::PARAM_STR);
		  //$st51->bindParam(4, $doc_level, PDO::PARAM_STR);
          $st51->execute();
		  }
		 

		 if($_SESSION['menuaccess_codeall'] =='11')//astt registar
		  {
			
	     $display='1';
		 $scrutiny_d='0';
		 $doc_level='1';
		 
		
          $st51=$db->prepare("select distinct(du.miscellenous_no) as miscellaneous_no,du.document_filed_date from document_upload as du left join e_case_detail as ecd on ecd.filing_no = du.filing_no left join e_not_found as enf on enf.fictitious_filing_no = du.filing_no  where du.filing_no !=?   and du.display=? and du.scrutiny=? and du.doc_level=? and du.miscellaneous_ref_no is NOT NULL and du.doc_flag = ? and du.miscellenous_no != ? and ((ecd.location_id = ? and ecd.court = ?) or (substring(du.filing_no from 1 for 2)=? AND enf.location_id = ?)) $filing_no_query order by du.document_filed_date desc limit $perPage->perpage offset $start");
		  $st51->bindParam(1, $ll, PDO::PARAM_STR);
          $st51->bindParam(2, $display, PDO::PARAM_STR);
		  $st51->bindParam(3, $scrutiny_d, PDO::PARAM_STR);
		  $st51->bindParam(4, $doc_level, PDO::PARAM_STR);
		  $st51->bindParam(5, $doc_flag, PDO::PARAM_STR);
		  $st51->bindParam(6, $ll, PDO::PARAM_STR);
		  $st51->bindParam(7, $location_access, PDO::PARAM_STR);
		  $st51->bindParam(8, $user_court, PDO::PARAM_STR);
		  $st51->bindParam(9, $fictitious, PDO::PARAM_STR);
		  $st51->bindParam(10, $location_access, PDO::PARAM_STR);
          $st51->execute();
		  
		  }  
		  
		   /* $r = $st51->fetchAll();
          echo "<pre>"; print_r($r);   */
	 $cou=1;
   while ($row51 = $st51->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
		  $miscellaneous_no= htmlspecialchars($row51['miscellaneous_no']); 
		  $document_filed_date= htmlspecialchars($row51['document_filed_date']); 
		  $get_filing_type=$db->prepare("select filing_no,documentuploadmodelid,doctype,uniqueid,document_filed_date,crossobjectiondoc from document_upload  where miscellenous_no =? and doc_flag = ? and document_filed_date = ?");
		  $get_filing_type->bindParam(1, $miscellaneous_no, PDO::PARAM_STR);
		  $get_filing_type->bindParam(2, $doc_flag, PDO::PARAM_STR);
		  $get_filing_type->bindParam(3, $document_filed_date, PDO::PARAM_STR);
          $get_filing_type->execute();
		  $rec = $get_filing_type->fetchAll();
		  $rec = array_shift($rec);
		  $filing_no= htmlspecialchars($rec['filing_no']);
		  $document_filed_date = $rec['document_filed_date'];
		  $crossobjectiondoc = $rec['crossobjectiondoc'];
		  $is_cross = '';
		  if($crossobjectiondoc)
		  	$is_cross = "(CROSS OBJECTION)";
   
		$doc_defects1 = '';
    $st21=$db->prepare("select defects from $schemas.scrutiny_doc where miscellaneous_no =? ");
		
         
          $st21->bindParam(1, $miscellaneous_no, PDO::PARAM_STR);
          $st21->execute();
          while ($row2 = $st21->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
				
                  $doc_defects1 = htmlspecialchars($row2['defects']);
		
		  } 

  
		$doc_case_no='';
		$doc_status = 'D';
			
		 if($filing_no!='' && strlen($filing_no) > 14){
		
         $st1=$db->prepare("select filing_no,dt_of_filing,case_type,pet_name,res_name from $schemas.case_detail where filing_no =? and case_no!=?  and status != ? order by filing_no DESC");
         
          $st1->bindParam(1, $filing_no, PDO::PARAM_STR);
		   $st1->bindParam(2, $doc_case_no, PDO::PARAM_STR);
		   $st1->bindParam(3, $doc_status, PDO::PARAM_STR);
		  
          $st1->execute();
		  
          while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
          
                 $filing_no2 = htmlspecialchars($row['filing_no']);
               $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
              $case_type=htmlspecialchars($row['case_type']);
			  $E_nameP=htmlspecialchars($row['pet_name']);
			  $E_nameR=htmlspecialchars($row['res_name']);
		     
  
  
  
  list($year,$month,$day)=explode('-',$dt_of_filing);
          $filing_date_all2=$day.'/'.$month.'/'.$year;

  
  $filing_nosend=$miscellaneous_no.'-'.$filing_no2.'-'.$qq1cc;
                    $filing_no_send=base64_encode($filing_nosend); 

		if($filing_no!='')
		{
	if($doc_defects1=='N' || $doc_defects1 == ''){
	
?>	
		 <tr style="background-color: #BDFCC9;"> 
<?php
}
if($doc_defects1=='Y')
{
?>
<tr style="background-color: #f8c6bf;">   
	<?php
}

	
?>
<td><?php echo htmlspecialchars($SER_COUT3++);?></td>
		  <td><?php if($document_filed_date =='11/11/1111' OR $document_filed_date =='//'){$document_filed_date="";}else {echo htmlspecialchars(date('d/m/Y',strtotime($document_filed_date)));}?></td>

		 <?php
		 $online_scrt = 0;
		 $online_dis = 1;
		 $doc_flag = 1;
       $countdocsql=$db->prepare("select count(*) from document_upload where miscellenous_no= ?  and scrutiny= ? and display= ? and miscellaneous_ref_no is NOT NULL and doc_flag = ?");
	   $countdocsql->bindParam(1, $miscellaneous_no, PDO::PARAM_STR);
	   $countdocsql->bindParam(2, $online_scrt, PDO::PARAM_STR);
	   $countdocsql->bindParam(3, $online_dis, PDO::PARAM_STR);
	   $countdocsql->bindParam(4, $doc_flag, PDO::PARAM_STR);
      $countdocsql->execute();
	  $number_of_rows = $countdocsql->fetchColumn();
		 ?>
		 <td><?php echo htmlspecialchars($filing_no2);
		 echo "<br><span style='color:red'>";
		 echo $is_cross;
echo "</span>";	
		 echo "<br><span style='color:red'>";
		 echo "(Misc No - ".$miscellaneous_no.")";
echo "</span>";	
		 echo "<br><span style='color:red'>";
		 echo "(No.of Docs - ".$number_of_rows.")";
echo "</span>";		 ?></td>


	  <?php
		   
		   $casenosql=$db->prepare("select case_type, case_no, case_year, location_code,transfrred_case_type_short from $schemas.case_detail where filing_no= ? ");
		   $casenosql->bindParam(1, $filing_no2, PDO::PARAM_STR);
                 $casenosql->execute(); 
				 $row = $casenosql->fetch();
				 $case_no = htmlspecialchars($row['case_no']);
				 $case_no = ltrim($case_no,0);
				 $casetype = htmlspecialchars($row['case_type']);
				 $locode = htmlspecialchars($row['location_code']);
				 $case_year = htmlspecialchars($row['case_year']);
				
				 $casetypesql = $db->prepare("select case_type_desc from case_type where id = ?");
				 $casetypesql->bindParam(1, $casetype, PDO::PARAM_STR);
                 $casetypesql->execute();
                 $case_type_short_name=$casetypesql->fetchColumn();
				 $case_type_short_name = strtoupper($case_type_short_name);
				
				 //$lcodesql ="select short_name from $schemas.bench_location where bench_location_code ='$locode'";
				 $lcodesql ="select short_name from $schemas.bench_location where city_id = ?";
                 $lcodesql=$db->prepare($lcodesql);
				 $lcodesql->bindParam(1, $locode, PDO::PARAM_STR);
                 $lcodesql->execute();
                 $lcodename = $lcodesql->fetchColumn();
				 
				 $tr_short = '';
				 $transfrred_case_type_short = $row['transfrred_case_type_short'];
				 if(!empty($transfrred_case_type_short)){
						$tr_short = " ($transfrred_case_type_short)";
				 }
				 
				 $case_no_final = $case_type_short_name.$tr_short.'/'.$case_no.'('.$lcodename.')'.$case_year;
				
	  ?>	
<td><?php echo $case_no_final; ?></td>
	 <td><?php echo htmlspecialchars_decode(strtoupper($E_nameP))."&nbsp;<font color='blue' size='2'><br> Vs. <br> </font>&nbsp;".htmlspecialchars_decode(strtoupper($E_nameR));?></td>        
				 <!--<td><?php //echo rtrim($r,','); ?></td>-->
				 <?php

					
				
				   if($_SESSION['menuaccess_codeall'] =='2' || $_SESSION['menuaccess_codeall'] =='20')
		  {
				 
				 ?>
			
                  <td><h3><span class="label label-success">
                    <a style="color: #FFFFFF;" href="../scrutiny/doc_scrutiny.php?ccase=<?php echo htmlspecialchars($c_case);?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>&doc_hash=<?php echo htmlspecialchars(base64_encode($hash_for_doc)); ?>">Scrutiny</a>
                    </span></h3></td>
					<?php 
				 }
				 ?>
				  <?php
				if($_SESSION['menuaccess_codeall'] =='11')
				 {
					 
				 ?>
			
                  <td><h3><span class="label label-success">
                    <a style="color: #FFFFFF;" href="../scrutiny/verify_document_scrutiny.php?ccase=<?php echo htmlspecialchars($c_case);?>&filing_no_next=<?php echo htmlspecialchars($filing_no_send);?>&doc_hash=<?php echo htmlspecialchars(base64_encode($hash_for_doc)); ?>">Verify Scrutiny</a>
                    </span></h3></td>
					<?php 
				 }
				 ?>
		     
<?php 
					
  // echo $filing_no."<br>".$cou;
  //$cou++;	
		}
		  }
		 }
		 
	if($filing_no!='' && strlen($filing_no) < 14){
		
		
		  $st21=$db->prepare("select location_id from e_not_found where fictitious_filing_no =? ");
		
          $st21->bindParam(1, $filing_no, PDO::PARAM_STR);
          $st21->execute();
		  $zone_id = $st21->fetchColumn();
		  if($zone_id != $location_access){
			continue;
		  }
		  
		  $filing_nosend=$miscellaneous_no.'-'.$filing_no.'-'.$qq1cc;
                    $filing_no_send=base64_encode($filing_nosend); 

		if($filing_no!='')
		{
			if($doc_defects1=='N' || $doc_defects1 == ''){
			
		?>	
				 <tr style="background-color: #BDFCC9;"> 
		<?php
		}
		if($doc_defects1=='Y')
		{
		?>
		<tr style="background-color: #f8c6bf;">   
			<?php
		}

			
		?>
		<td><?php echo htmlspecialchars($SER_COUT3++);?></td>
				   <td><?php if($document_filed_date =='11/11/1111' OR $document_filed_date =='//'){$document_filed_date="";}else {echo htmlspecialchars(date('d/m/Y',strtotime($document_filed_date)));}?></td>

				 <?php
				 $online_scrt = 0;
				 $online_dis = 1;
				 $doc_flag = 1;
			   $countdocsql=$db->prepare("select count(*) from document_upload where miscellenous_no= ?  and scrutiny= ? and display= ? and miscellaneous_ref_no is NOT NULL and doc_flag = ?");
			   $countdocsql->bindParam(1, $miscellaneous_no, PDO::PARAM_STR);
			   $countdocsql->bindParam(2, $online_scrt, PDO::PARAM_STR);
			   $countdocsql->bindParam(3, $online_dis, PDO::PARAM_STR);
			   $countdocsql->bindParam(4, $doc_flag, PDO::PARAM_STR);
			  $countdocsql->execute();
			  $number_of_rows = $countdocsql->fetchColumn();
				 ?>
				 <td><?php echo htmlspecialchars($filing_no);
				  echo "<br><span style='color:red'>";
				  echo "(Misc No - ".$miscellaneous_no.")";
				  echo "</span>";
				 echo "<br><span style='color:red'>";
				 echo "(No.of Docs - ".$number_of_rows.")";
		echo "</span>";		 ?></td>
		
		<?php 
			$e_not_found_detail=$db->prepare("select a.case_no,b.case_type_desc,a.case_year from e_not_found a left join case_type b on b.id = a.case_type where a.fictitious_filing_no= ?");
		   $e_not_found_detail->bindParam(1, $filing_no, PDO::PARAM_STR);
		  $e_not_found_detail->execute();
		  $e_not_found_detail_doc = $e_not_found_detail->fetchAll();
		  $e_not_found_detail = array_shift($e_not_found_detail_doc);
		  $case_no_final = $e_not_found_detail['case_type_desc']."/".$e_not_found_detail['case_no']."/".$e_not_found_detail['case_year'];
		?>

		<td><?php echo $case_no_final; ?></td>
			 <td><?php //echo htmlspecialchars_decode(strtoupper($E_nameP))."&nbsp;<font color='blue' size='2'><br> Vs. <br> </font>&nbsp;".htmlspecialchars_decode(strtoupper($E_nameR));?></td>        
						<!-- <td><?php //echo rtrim($r,','); ?></td>-->
						 <?php

							
						
						   if($_SESSION['menuaccess_codeall'] =='2' || $_SESSION['menuaccess_codeall'] =='20')
				  {
						 
						 ?>
					
						  <td><h3><span class="label label-success">
							<a style="color: #FFFFFF;" href="#">Enter Backlog Case</a>
							</span></h3>
							<!--<br/>
								<a  href="javascript:void(0);" onClick="return view_misc_docs('<?php echo $filing_no; ?>','<?php echo $miscellaneous_no; ?>','../ajax/computation_note.php','../scrutiny/readpdf_file.php');" >View Docs</a></td>-->
							<?php 
						 }
						 ?>
						  <?php
						if($_SESSION['menuaccess_codeall'] =='11')
						 {
							 
						 ?>
					
						  <td><h3><span class="label label-success">
							<a style="color: #FFFFFF;" href="#">Enter Backlog Case</a>
							</span></h3></td>
							<?php 
						 }
						 ?>
					 
		<?php 
							
		  // echo $filing_no."<br>".$cou;
		  //$cou++;	
				}
		  
	}
  
	}
	if(!empty($perpageresult)) {
		echo '<tr><td id="pagination" colspan="6">' . $perpageresult . '</td></tr>';
	}
	?>
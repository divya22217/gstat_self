<?php	
session_start();
$schemas = 'delhi';
include '../db_inc2.php';
include("../db_inc1.php");
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d');

if(isset($_POST['submit'])){
	if(!empty($_POST['check_list'])) {
    // Counting number of checked checkboxes.
    $checked_count = count($_POST['check_list']);
		//echo "You have selected following ".$checked_count." case(s): <br/>";
		foreach($_POST['check_list'] as $selected) {
			$filing_no_post = $selected;
			$pieces = explode("@", $filing_no_post);
			 $filing_no = $pieces[0]; // piece1
			//echo "---";
			 $miscellaneous_ref_no_post = $pieces[1]; // piece2
			//echo "<br>";
      
      $doc_sc='1';
	    $ef_sc='0';
      $ef_dis='1';
      $doc_level='22';


	$upd_doc_upl =$dbonline->prepare("update document_upload set scrutiny=?,doc_level=? where filing_no=? and scrutiny=? and display=? and miscellaneous_ref_no=?");
	$upd_doc_upl->bindParam(1, $doc_sc, PDO::PARAM_STR);
	$upd_doc_upl->bindParam(2, $doc_level, PDO::PARAM_STR);
	$upd_doc_upl->bindParam(3, $filing_no, PDO::PARAM_STR);
	$upd_doc_upl->bindParam(4, $ef_sc, PDO::PARAM_STR);
	$upd_doc_upl->bindParam(5, $ef_dis, PDO::PARAM_STR);
	$upd_doc_upl->bindParam(6, $miscellaneous_ref_no_post, PDO::PARAM_STR);
	//$st12x->bindParam(4, $ef_dis, PDO::PARAM_STR);
	$upd_doc_upl->execute();

	 
  $localIP = getHostByName(getHostName());
	$timestamp = date("Y-m-d H:i:s");
	$userid=$_SESSION['id'];
	$upd_doc_upl_track =$db->prepare("insert into $schemas.ar_scrutiny_doc_his(filing_no,miscellaneous_no,updated,ip_track,user_id) values(?,?,?,?,?)");
	$upd_doc_upl_track->bindParam(1, $filing_no, PDO::PARAM_STR);
	$upd_doc_upl_track->bindParam(2, $miscellaneous_ref_no_post, PDO::PARAM_STR);
	$upd_doc_upl_track->bindParam(3, $timestamp, PDO::PARAM_STR);
	$upd_doc_upl_track->bindParam(4, $localIP, PDO::PARAM_STR);
	$upd_doc_upl_track->bindParam(5, $userid, PDO::PARAM_STR);
	$upd_doc_upl_track->execute();
		
		}

		echo "<b>Successfully Updated Document Upload</b>";

	}
}
?>


<!----------------------------------------------------------------------------------------------------------->
<!----------------------------------------------------------------------------------------------------------->

<html>
<head>
</head>
<body>
<form action="ardocscrutiny.php" method="post">
<table>
<tr><th>Action</th><th>S no.</th><th>DocUplDate</th><th>DOF</th><th>Diary No.</th><th>Miscellaneous No.</th><th>Title</th><th>Section</th></tr>
<?php
		 
$SER_COUT3=1;
	   $display='1';
		 $scrutiny_d='0';
		 $doc_level='11';
		 $ll ='NA';
		
          $st51=$dbonline->prepare("select distinct(miscellaneous_ref_no) as miscellaneous_ref_no,filing_no,document_filed_date
          from document_upload  where filing_no !=?   and display=? and scrutiny=?
          and doc_level=? and miscellaneous_ref_no IS NOT NULL order by document_filed_date asc");
		  $st51->bindParam(1, $ll, PDO::PARAM_STR);
           $st51->bindParam(2, $display, PDO::PARAM_STR);
		   $st51->bindParam(3, $scrutiny_d, PDO::PARAM_STR);
		   $st51->bindParam(4, $doc_level, PDO::PARAM_STR);
					$st51->execute();
					
 
					/*$gt = array();
					foreach($st51->fetchAll() as $key=>$value){
						$gt[] = $value['filing_no'];
					}
					echo "<pre>"; print_r($gt); 
					echo "<_______________________________>";*/

				/*	$st513=$dbonline->prepare("select distinct(C.filing_no) from document_upload D INNER JOIN delhi.case_detail C ON D.filing_no=C.filing_no where D.filing_no!='NA' and D.display='TRUE' and D.document_filed_date <= '2019-01-01' and D.scrutiny='0' and D.doc_level='11' and C.case_no!=''");
					 $st51->bindParam(1, $ll, PDO::PARAM_STR);
					     $st51->bindParam(2, $display, PDO::PARAM_STR);
					 $st51->bindParam(3, $scrutiny_d, PDO::PARAM_STR);
					 $st51->bindParam(4, $doc_level, PDO::PARAM_STR);
							$st513->execute();*/

							/*$lt = array();
					foreach($st513->fetchAll() as $key=>$value){
						$lt[] = $value['filing_no'];
					}
							
							echo "<pre>"; print_r($lt); 
						echo "<br/>   common data <br/>";
							$result=array_intersect($gt,$lt);
							echo "<pre>"; print_r($result); die("dfdf");*/
		  
          
	 $cou=1;
   while ($row51 = $st51->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
			
         
	 $filing_no= htmlspecialchars($row51['filing_no']);
	 $miscellaneous_ref_no= htmlspecialchars($row51['miscellaneous_ref_no']);
	 $doc_fil_date= htmlspecialchars($row51['document_filed_date']);
	 list($year,$month,$day)=explode('-',$doc_fil_date);
     $doc_fil_date=$day.'/'.$month.'/'.$year;

   $doc_case_no='';
		
		
		
         $st1=$db->prepare("select filing_no,dt_of_filing,case_type,pet_name,res_name,case_no,location_code,case_year from $schemas.case_detail where filing_no =? and case_no!=?  order by filing_no DESC");
         
          $st1->bindParam(1, $filing_no, PDO::PARAM_STR);
		      $st1->bindParam(2, $doc_case_no, PDO::PARAM_STR);
		  
          $st1->execute();
          while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
          
                  $filing_no2 = htmlspecialchars($row['filing_no']);
                  $dt_of_filing = htmlspecialchars($row['dt_of_filing']);
                  $case_type=htmlspecialchars($row['case_type']);
			            $E_nameP=htmlspecialchars($row['pet_name']);
			            $E_nameR=htmlspecialchars($row['res_name']);
		     
                  $case_no = htmlspecialchars($row['case_no']);
				          $case_no = ltrim($case_no,0);
				          $casetype = htmlspecialchars($row['case_type']);
				          $locode = htmlspecialchars($row['location_code']);
                  $case_year = htmlspecialchars($row['case_year']);
                  
                  //$case_no_doc = get_caseno_doc($case_no,$casetype,$locode,$case_year);
  
  
          list($year,$month,$day)=explode('-',$dt_of_filing);
          $filing_date_all2=$day.'/'.$month.'/'.$year;
          		  
		      $st25=$dbonline->prepare("select * from e_case_detail_fees where filing_no =? ");
                      $st25->bindParam(1, $filing_no2, PDO::PARAM_STR);
                      $st25->execute();
                      $i=0;$r='';
                      while ($row25= $st25->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                            $E_sec_id=$row25['sec_id'];
                       if($E_sec_id == 0 || $E_sec_id == ''){
						   $r = '----';
					   }else
                          if($E_sec_id > '0')
                          {
                      $st35=$dbonline->prepare("select * from master_section_act where id=? ");
                      $st35->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                      $st35->execute();
                      
                      while ($row35 = $st35->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {
                      	  $E_add_sec_id=$row35['section_companies'];                      
                            $r.=$E_add_sec_id.',';
                         
                       
                      }
                          }
                      }
  
  $filing_nosend=$filing_no2.'-'.$qq1cc;
                    $filing_no_send=base64_encode($filing_nosend); 

		if($filing_no!='')
		{	
?>	
<tr style="background-color: #f8c6bf;">   
<td><input type="checkbox" name="check_list[]" class="chk_boxes1" value="<?php echo htmlspecialchars($filing_no2).'@'.htmlspecialchars($miscellaneous_ref_no);  ?>"></td>
<td><?php echo htmlspecialchars($SER_COUT3++).".";?></td>
<td><?php echo htmlspecialchars($doc_fil_date);?></td>
		  <td><?php if($filing_date_all2 =='11/11/1111' OR $filing_date_all2 =='//'){$filing_date_all2="";}else {echo htmlspecialchars($filing_date_all2);}?></td>

		 
		 <td><?php echo htmlspecialchars($filing_no2);?></td>
		 <td><?php echo htmlspecialchars($miscellaneous_ref_no);?></td>
		 
	 <td><?php echo htmlspecialchars_decode(strtoupper($E_nameP))."&nbsp;<font color='blue' size='2'> Vs. </font>&nbsp;".htmlspecialchars_decode(strtoupper($E_nameR));?></td>        
				 <td><?php echo rtrim($r,','); ?></td></tr>
				 <?php
		}
		  }
  
	}
 ?>
</table>
<br>
<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<span align="center"><input type="submit" name="submit" Value="Submit"/></span>
 </form>
 </body>
 </html>
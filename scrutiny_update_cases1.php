 <?php	 
	 include("./db_inc1.php");
include './db_inc2.php';
 
	 $filing_no = '0710102161042019';
	$st3=$db->prepare("select * from delhi.objection_details where filing_no=?");
	$st3->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st3->execute();


	while ($row = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		//die('kk');
	        $E_filing_no=$row['filing_no'];
			$entry_date=$row['entry_dt'];
			$user_id=$row['userid'];
			$completed_flag=$row['completed_flag'];
			$level_level=$row['level_level'];
			$scrutinu_comp = '1';
			
               $st13=$db->prepare("insert into  delhi.scrutiny (filing_no,notification_date,user_id,
			   objection_status,defects,level_level,scrutinu_comp,varifyed_userid)values (?,?,?,?,?,?,?,?)");
  		$st13->bindParam(1, $E_filing_no, PDO::PARAM_STR);
		$st13->bindParam(2, $entry_date, PDO::PARAM_STR);
		$st13->bindParam(3, $user_id, PDO::PARAM_STR);
		$st13->bindParam(4, $completed_flag, PDO::PARAM_STR);
		$st13->bindParam(5, $completed_flag, PDO::PARAM_STR);
		$st13->bindParam(6, $level_level, PDO::PARAM_STR);
		$st13->bindParam(7, $scrutinu_comp, PDO::PARAM_STR);
		$st13->bindParam(8, $user_id, PDO::PARAM_STR);
  		$st13->execute();
		
	}
	?>
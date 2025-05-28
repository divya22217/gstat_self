<?php
class CaseStatus{
  
    // database connection and table name
    private $conn;
	private $connonline;
  
    // object properties
    public $filing_no;
    public $status;
    public $pet_name;
    public $res_name;
    public $ia_ma_filing_no;
    public $has_keyword;
    public $group_scrutiny;
	public $backlog;
	public $case_no;
	public $case_type;
	public $case_year;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	
	public function get_basic_info($schema,$type,$filing_no,$case_no='',$case_year='',$case_type=''){
		if($type == 'cn')
			$query = "select cd.filing_no,cd.status,cd.case_no,cd.case_year,cd.case_type,cd.dt_of_filing,cd.regis_date,cd.main_case_ia_no,ct.short_name as case_type_short_name, mlc.short_name as location_short_name,mlc.city_name as bench_location_name
			from $schema.case_detail cd
			left join case_type ct on ct.id = cd.case_type
			left join mater_location_city mlc on mlc.city_id = cd.location_code
			where cd.case_no = ? and cd.case_year = ? and cd.case_type = ?";
		else
			$query = "select cd.filing_no,cd.status,cd.case_no,cd.case_year,cd.case_type,cd.dt_of_filing,cd.regis_date,cd.main_case_ia_no,ct.short_name as case_type_short_name, mlc.short_name as location_short_name,mlc.city_name as bench_location_name
			from $schema.case_detail cd
			left join case_type ct on ct.id = cd.case_type
			left join mater_location_city mlc on mlc.city_id = cd.location_code		
			where cd.filing_no = ?";
		

		$stmt = $this->conn->prepare($query);
		if($type == 'cn'){
			$stmt->bindParam(1, $case_no, PDO::PARAM_STR);
			$stmt->bindParam(2, $case_year, PDO::PARAM_STR);
			$stmt->bindParam(3, $case_type, PDO::PARAM_STR);
		}else{
			$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		}
		$stmt->execute();
		$case_info = $stmt->fetchAll();
		return $case_info;
	}
	
	public function get_party($filing_no,$party_flag,$type,$party_serial_no = ''){
		if($type == 'one'){
			$query = 'select name,id,mobile,email from e_cases_party where party_serial_no = ? and party_flag = ? and filing_no = ? limit 1';
		}else{
			$query = 'select name,id,mobile,email from e_cases_party where party_flag = ? and filing_no = ? order by party_serial_no';
		}
		$stmt = $this->conn->prepare($query);
		if($type == 'one'){
			$stmt->bindParam(1, $party_serial_no, PDO::PARAM_STR);
			$stmt->bindParam(2, $party_flag, PDO::PARAM_STR);
			$stmt->bindParam(3, $filing_no, PDO::PARAM_STR);
		}else{
			$stmt->bindParam(1, $party_flag, PDO::PARAM_STR);
			$stmt->bindParam(2, $filing_no, PDO::PARAM_STR);
		}
		$stmt->execute();
		$parties = $stmt->fetchAll();
		return $parties;
	}
	
	public function cause_title($filing_no){
		$petitioner = $this->get_party($filing_no,$party_flag='P',$type = 'one',$party_serial_no = 1);
		$petitioner_name = $petitioner['name'];
		$respondent = $this->get_party($filing_no,$party_flag='R',$type = 'one',$party_serial_no = 1);
		$respondent_name = $respondent['name'];
		$title = $petitioner_name." VS ".$respondent_name;
		return $title;
	}
	
	public function get_listing_info($schema,$filing_no,$type='')
	{
		$limit  = '';
		$query = "select cp.filing_no,cp.court_no,cp.bench_no,cp.listing_date,cp.next_list_date,cp.bench_nature,cp.purpose
				,cp.todays_status,cp.remarks,mp.purpose_name,mp2.purpose_name as next_purpose,mj.judge_name,ma.action_type
				from $schema.case_proceeding as cp 
				left join $schema.master_purpose mp on mp.purpose_code = cp.purpose
				left join $schema.master_action ma on ma.action_code= cp.todays_action
				left join $schema.master_purpose mp2 on mp2.purpose_code = cp.next_list_purpose
				left join $schema.bench bn on bn.from_list_date = cp.listing_date and bn.bench_no = cp.bench_no and bn.court_no = cp.court_no
				left join $schema.master_judge mj on mj.judge_code = bn.presiding
				where cp.filing_no = ? order by cp.listing_date asc";
		if($type == 'first'){
			$limit = " limit 1";
		}
		$query .= $limit;
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->execute();
		$listing_info = $stmt->fetchAll();
		return $listing_info;
	}
	
	public function get_listing_history($schema,$filing_no,$type='')
	{
		$limit  = '';
		$query = "select mj.judge_name as judge_name,cp.listing_date as hearing_date,mp.purpose_name,ma.action_type
				from $schema.case_proceeding as cp 
				left join $schema.master_purpose mp on mp.purpose_code = cp.purpose
				left join $schema.master_action ma on ma.action_code= cp.todays_action
				left join $schema.bench bn on bn.from_list_date = cp.listing_date and bn.bench_no = cp.bench_no and bn.court_no = cp.court_no
				left join $schema.master_judge mj on mj.judge_code = bn.presiding
				where cp.filing_no = ? order by cp.listing_date asc";
		if($type == 'first'){
			$limit = " limit 1";
		}
		
		echo $query;
		$query .= $limit;
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->execute();
		$listing_info = $stmt->fetchAll();
		return $listing_info;
	}
	
	public function first_listing_date($schema,$filing_no,$type='')
	{
		$limit  = '';
		$query = "select cp.listing_date
				from $schema.case_proceeding as cp 
				where cp.filing_no = ? order by cp.listing_date asc limit 1";
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->execute();
		$listing_info = $stmt->fetchColumn();
		return $listing_info;
	}
	
	public function last_listing_date($schema,$filing_no,$type='')
	{
		$limit  = '';
		$query = "select cp.listing_date
				from $schema.case_proceeding as cp 
				where cp.filing_no = ? order by cp.listing_date desc limit 1";
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->execute();
		$listing_info = $stmt->fetchColumn();
		return $listing_info;
	}
	
	public function last_listing_info($schema,$filing_no,$type='')
	{
		$query = "select cp.filing_no,cp.court_no,cp.bench_no,cp.bench_nature,cp.listing_date,cp.next_list_date,cp.bench_nature,cp.purpose
				,cp.todays_status,cp.remarks,mp.purpose_name,mp2.purpose_name as next_purpose,mj.judge_name
				from $schema.case_proceeding as cp 
				left join $schema.master_purpose mp on mp.purpose_code = cp.purpose
				left join $schema.master_purpose mp2 on mp2.purpose_code = cp.next_list_purpose
				left join $schema.bench bn on bn.from_list_date = cp.listing_date and bn.bench_no = cp.bench_no and bn.court_no = cp.court_no
				left join $schema.master_judge mj on mj.judge_code = bn.presiding
				where cp.filing_no = ? order by cp.listing_date desc limit 1";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->execute();
		$listing_info = $stmt->fetchAll();
		return $listing_info;
	}
	
	public function orders($schema,$filing_no,$case_status,$flag,$type=''){
		$limit = '';
		$query = "select order_date as date_of_order,order_type as distingues_type from $schema.order_daily where filing_no = ? and flag = ? order by order_date asc";
		if($type == 'first'){
			$limit = " limit 1";
		}
		$query .= $limit;
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->bindParam(2, $flag, PDO::PARAM_STR);
		$stmt->execute();
		$orders = $stmt->fetchAll();
		return $orders;
		
	}
	
	public function judgements($schema,$filing_no,$display){
		$query = "select date_of_order as order_date,upload_date as order_upload_date,order_type,'J' as distingues_type from $schema.order_detail where filing_no = ? and display = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->bindParam(2, $display, PDO::PARAM_STR);
		$stmt->execute();
		$judgements = $stmt->fetchAll();
		return $judgements;
	}
	
	public function get_child_cases($schema,$filing_no){
		$query = "select cd.filing_no,cd.status,cd.case_no,cd.case_year,cd.case_type,cd.dt_of_filing,cd.regis_date,cd.main_case_ia_no,ct.short_name as case_type_short_name, mlc.short_name as location_short_name
			from $schema.case_detail cd
			left join case_type ct on ct.id = cd.case_type
			left join mater_location_city mlc on mlc.city_id = cd.location_code			
			where cd.main_case_ia_no = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->execute();
		$case_info = $stmt->fetchAll();
		return $case_info;
	}
	
	public function get_connected_cases($schema,$filing_no){
		$display = true;
		$query = "select cc.*,cd.dt_of_filing,cd.status as case_status,cd.case_no,cd.case_year,cd.case_type,cd.regis_date,cd.main_case_ia_no,ct.short_name as case_type_short_name, mlc.short_name as location_short_name
			from $schema.connected_cases cc
			left join $schema.case_detail cd on cd.filing_no = cc.conn_filing_no
			left join case_type ct on ct.id = cd.case_type
			left join mater_location_city mlc on mlc.city_id = cd.location_code			
			where cc.filing_no = ? and cc.display = ?";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
			$stmt->bindParam(2, $display, PDO::PARAM_STR);
			$stmt->execute();
			$case_info = $stmt->fetchAll();
			return $case_info;
	}
	
	public function get_disposed_date($schema,$filing_no){
		$query = "select cd.*,ma.action_type as action_name from $schema.case_disposal as cd 
					left join $schema.master_action ma on ma.action_code = cd.disposal_nature
					where cd.filing_no = ?
					order by cd.id desc limit 1";
		//echo $query;
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
			$stmt->execute();
			$case_info = $stmt->fetchAll();
			return $case_info;
	}
	
	public function get_adv_details($filing_no){
		$query = "select distinct on (ecd.filing_no) ecd.filing_no,ecp.name as petname,ecp.mobile as petmobile,ecp.email as petemail,ecp1.name as resname,ecp1.mobile as resmobile,ecp1.email as resemail,case_title,
ema.rep_name as petadvname,ema.mobile as petadvmobile,ema.email as petadvemail,ema1.rep_name as resadvname,ema1.mobile as resadvmobile,ema1.email as resadvemail,string_agg( msa.section_companies,',') as maincase_section from e_case_detail ecd
left join e_cases_party ecp on ecp.id=ecd.pet_code
left join e_cases_party ecp1 on ecp1.id=ecd.res_code
left join e_more_representative er on er.party_code=ecd.pet_code
left join e_more_representative er1 on er1.party_code=ecd.res_code
left join e_master_advocate ema on ema.id=er.rep_code
left join e_master_advocate ema1 on ema1.id=er1.rep_code
left join e_case_detail_fees ecdf on ecdf.filing_no=ecd.filing_no
left join master_section_act msa on msa.id=ecdf.sec_id
 where ecd.filing_no=? 
 group by ecd.filing_no,ecp.name,ecp.mobile,ecp.email,ecp1.name,ecp1.mobile,ecp1.email,case_title,ema.rep_name,ema.mobile,ema.email,ema1.rep_name,ema1.mobile,ema1.email,msa.section_companies";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->execute();
		$case_info = $stmt->fetchAll();
		return $case_info;
	}
	
	public function get_latest_hearing_filing_no($schema,$implode_fn){
		$query = "select cp.filing_no
				from $schema.case_proceeding as cp 
				where cp.filing_no in ($implode_fn) order by cp.listing_date desc limit 1";
		
		$stmt = $this->conn->prepare($query);
		
		$stmt->execute();
		$listing_info = $stmt->fetchColumn();
		return $listing_info;
	}
	
	
}
?>

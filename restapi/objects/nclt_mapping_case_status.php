<?php
class NcltMappingCaseStatus{
  
    // database connection and table name
    private $conn;
	private $connonline;
	private $filing_no_length;
	private $backlog;
	private $wrong_status;
	private $blank;
	private $pet_party_flag;
	private $res_party_flag;
	private $party_serial_no;
  
    // object properties
    public $filing_no;
    public $status;
    public $pet_name;
    public $res_name;
    public $ia_ma_filing_no;
    public $has_keyword;
    public $group_scrutiny;
	public $case_no;
	public $case_type;
	public $case_year;
  
    // constructor with $db as database connection
    public function __construct($db,$filing_no_length,$backlog,$wrong_status,$blank,$pet_party_flag,$res_party_flag,$party_serial_no){
        $this->conn = $db;
		$this->filing_no_length = $filing_no_length;
		$this->backlog = $backlog;
		$this->wrong_status = $wrong_status;
		$this->blank = $blank;
		$this->pet_party_flag = $pet_party_flag;
		$this->res_party_flag = $res_party_flag;
		$this->party_serial_no = $party_serial_no;
    }
	
	public function get_cases_filing_no_wise($db,$schema,$filing_no){
		$query = "select temp1.filing_no,temp1.case_no,temp1.case_type,temp1.case_year,(coalesce(ecp1.name,'')|| ' VS ' ||coalesce(ecp2.name,'')) as case_title,
					temp1.date_of_registration, temp1.status,temp1.loc_name from  (select 
					a.filing_no,cast(a.case_no as int) as case_no ,a.case_year,b.short_name as case_type,
					case when (length(a.main_case_ia_no) = ?) then a.main_case_ia_no else a.filing_no end as party_filing_no,
					a.regis_date as date_of_registration,a.status,a.main_case_ia_no,bl.short_name as loc_name
					from $schema.case_detail as a join case_type as b ON b.id = a.case_type 
					left join $schema.bench_location as bl on bl.city_id = a.location_code
					where a.filing_no = ? and a.case_no != ? and a.status != ?
					order by a.case_year asc, case_no asc, a.regis_date desc
					) as temp1 
					left join e_cases_party as ecp1 on ecp1.filing_no = temp1.party_filing_no and ecp1.party_flag = ? and ecp1.party_serial_no = ?
					left join e_cases_party as ecp2 on ecp2.filing_no = temp1.party_filing_no and ecp2.party_flag = ? and ecp2.party_serial_no = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->filing_no_length, PDO::PARAM_STR);
		$stmt->bindParam(2, $filing_no, PDO::PARAM_STR);
		$stmt->bindParam(3, $this->blank, PDO::PARAM_STR);
		$stmt->bindParam(4, $this->wrong_status, PDO::PARAM_STR);
		$stmt->bindParam(5, $this->pet_party_flag, PDO::PARAM_STR);
		$stmt->bindParam(6, $this->party_serial_no, PDO::PARAM_STR);
		$stmt->bindParam(7, $this->res_party_flag, PDO::PARAM_STR);
		$stmt->bindParam(8, $this->party_serial_no, PDO::PARAM_STR);
		$stmt->execute();
		$case_info = $stmt->fetch();
		return $case_info;
	}
	
	public function get_case_info_by_nclt($db,$schema,$case_type,$case_no,$case_year,$filing_no){
		$query = "select ecd.filing_no, ecd.dt_of_filing,ecd.location_id from  e_case_detail as ecd
					where length(ecd.filing_no) = ? and ecd.case_type = ? and ecd.nclt_case_number = ? and ecd.nclt_case_year = ? and ecd.nclt_filing_no = ? order by ecd.dt_of_filing limit 1
					";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->filing_no_length, PDO::PARAM_STR);
		$stmt->bindParam(2, $case_type, PDO::PARAM_STR);
		$stmt->bindParam(3, $case_no, PDO::PARAM_STR);
		$stmt->bindParam(4, $case_year, PDO::PARAM_STR);
		$stmt->bindParam(5, $filing_no, PDO::PARAM_STR);
		$stmt->execute();
		$case_info = $stmt->fetch();
		return $case_info;
	}
	
	

	public function get_hearing_details($db,$schema,$filing_no,$type=''){
		$query = "select cp.court_no,cp.listing_date as hearing_date,mp.purpose_name as stage_of_case,cp.bench_no,ma.action_type,od.pdf_path,
				CASE WHEN cp.next_list_date != '1111-11-11' THEN cp.next_list_date ELSE null END as next_hearing_date
				from $schema.case_proceeding as cp 
				left join $schema.master_purpose mp on mp.purpose_code = cp.purpose
				left join $schema.master_action ma on ma.action_code = cp.todays_action
				left join $schema.order_daily od on od.filing_no = cp.filing_no AND od.order_date = cp.listing_date
				where cp.filing_no = ?";
		
		if($type == 'first'){
			$order_by = " order by cp.listing_date asc limit 1";
		}elseif($type == 'last'){
			$order_by = " order by cp.listing_date desc limit 1";
		}else{
			$order_by  = ' order by cp.listing_date desc';
		}
		$query .= $order_by;
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->execute();
		$listing_info = $stmt->fetch();
		return $listing_info;

	}
	
	public function get_registred_cases($db,$schema){
		$query = "select '$schema' as schema_name,temp1.filing_no,temp1.case_no,temp1.case_type,temp1.case_year,(coalesce(ecp1.name,'')|| ' VS ' ||coalesce(ecp2.name,'')) as case_title,
					temp1.date_of_registration, temp1.status,temp1.loc_name,temp1.nclt_case_type,temp1.nclt_case_number,temp1.nclt_case_year,temp1.nclt_filing_no from  (select 
					a.filing_no,cast(a.case_no as int) as case_no ,a.case_year,b.short_name as case_type,
					case when (length(a.main_case_ia_no) = ?) then a.main_case_ia_no else a.filing_no end as party_filing_no,
					a.regis_date as date_of_registration,a.status,a.main_case_ia_no,bl.short_name as loc_name,ecd.case_type as nclt_case_type,ecd.nclt_case_number,ecd.nclt_case_year,ecd.nclt_filing_no
					from $schema.case_detail as a join case_type as b ON b.id = a.case_type
					inner join e_case_detail as ecd on ecd.filing_no = a.filing_no  and ecd.case_type is not null and ecd.nclt_case_number is not null and ecd.nclt_case_year is not null and (ecd.nclt_filing_no is not null AND ecd.nclt_filing_no != '' AND length(ecd.nclt_filing_no) = 16 AND length(ecd.filing_no) = 16)
					left join $schema.bench_location as bl on bl.city_id = a.location_code
					where a.case_no != ? and a.status != ? and a.regis_date <= current_date and is_mapped_with_nclt = false
					order by a.case_year asc, case_no asc, a.regis_date desc
					) as temp1 
					left join e_cases_party as ecp1 on ecp1.filing_no = temp1.party_filing_no and ecp1.party_flag = ? and ecp1.party_serial_no = ?
					left join e_cases_party as ecp2 on ecp2.filing_no = temp1.party_filing_no and ecp2.party_flag = ? and ecp2.party_serial_no = ?";
	$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->filing_no_length, PDO::PARAM_STR);
		$stmt->bindParam(2, $this->blank, PDO::PARAM_STR);
		$stmt->bindParam(3, $this->wrong_status, PDO::PARAM_STR);
		$stmt->bindParam(4, $this->pet_party_flag, PDO::PARAM_STR);
		$stmt->bindParam(5, $this->party_serial_no, PDO::PARAM_STR);
		$stmt->bindParam(6, $this->res_party_flag, PDO::PARAM_STR);
		$stmt->bindParam(7, $this->party_serial_no, PDO::PARAM_STR);
		$stmt->execute();
		$case_info = $stmt->fetchAll();
		return $case_info;
	}
	
	public function get_dispose_details($db,$schema,$filing_no){
		$query = "select cd.court_no,cd.disposal_date,ma.action_type as disposal_nature
				from $schema.case_disposal as cd 
				left join $schema.master_action ma on ma.action_code = cd.disposal_nature
				where cd.filing_no = ? order by cd.disposal_date desc limit 1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$stmt->execute();
		$disposal_info = $stmt->fetch();
		return $disposal_info;

	}
	
	public function update_fetched_status($db,$filing_no,$schema){
		$query = "update $schema.case_detail set is_mapped_with_nclt = true 
				where filing_no = ? ";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $stmt->execute();
		return $res;
	}

	
}
?>

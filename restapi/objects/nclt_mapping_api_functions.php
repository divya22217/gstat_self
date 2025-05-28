<?php
class NcltMappingApi{

 // database connection and table name
    private $conn;

// constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

	public function get_schema($column,$loc_code){
		$query = "select schema_name from mater_location_city where $column = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $loc_code, PDO::PARAM_STR);
		$stmt->execute();
		$schema = $stmt->fetchColumn();
		return $schema;
	}
	
	public function get_schema_detail($column,$loc_code){
		$query = "select a.schema_name,a.city_name,b.state_name from mater_location_city as a left join master_states as b on b.state_id = a.state_id where $column = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $loc_code, PDO::PARAM_STR);
		$stmt->execute();
		$schema = $stmt->fetchAll();
		$schema = array_shift($schema);
		return $schema;
	}
	
	public function get_all_zone(){
		$true = '1';
		$query = "select city_id as code_bench,city_name as name_of_bench,'' as name_circuit_bench,'' as name from mater_location_city where display = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $true, PDO::PARAM_STR);
		$stmt->execute();
		$schemas = $stmt->fetchAll();
		return $schemas;
	}
	
	public function get_case_types(){
		$true = 't';
		$query = "select id as case_type_code,case_type_desc_cis as case_type_name from case_type where status = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $true, PDO::PARAM_STR);
		$stmt->execute();
		$case_types = $stmt->fetchAll();
		return $case_types;
	}
	
	public function get_all_zone_nclat(){
		$true = '1';
		$query = "select city_id ,city_name, schema_name from mater_location_city where display = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $true, PDO::PARAM_STR);
		$stmt->execute();
		$schemas = $stmt->fetchAll();
		return $schemas;
	}

	public function get_case_types_nclat(){
		$true = 't';
		$query = "select id as case_type_code,case_type_desc as case_type_name from case_type where status = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $true, PDO::PARAM_STR);
		$stmt->execute();
		$case_types = $stmt->fetchAll();
		return $case_types;
	}

	function advocate_lists()
	{
		$query = "SELECT distinct(a.rep_code), b.rep_name FROM public.e_more_representative as a
					join e_master_advocate as b ON a.rep_code = b.id
					where (a.filing_no != 'NA' OR a.filing_no is not null) and  a.display = true and (b.rep_name != 'NA' OR b.rep_name is not null) ";
		$advocates = $this->conn->prepare($query);
		$advocates->execute();
		$advocates = $advocates->fetchAll();
		$main_data = array();
		if (!empty($advocates) && is_array($advocates)) {
			foreach ($advocates as $val) {
				$main_data[] = $val['rep_name'];
			}
		}
		return $main_data;
	}

	function get_judges($schema){
		$chairperison_list = "SELECT judge_code,judge_name FROM $schema.master_judge where judge_desg_code in (1,6,7,8) order by judge_desg_code desc";
		$chairperison_list = $this->conn->prepare($chairperison_list);
		$chairperison_list->execute();
		$chairperison_list = $chairperison_list->fetchAll();
		
		$more_judge_list = "SELECT judge_code,judge_name  FROM $schema.master_judge where judge_desg_code not in (1,6,7,8) order by judge_desg_code asc";
		$more_judge_list = $this->conn->prepare($more_judge_list);
		$more_judge_list->execute();
		$more_judge_list = $more_judge_list->fetchAll();
		
		$judge_list = array_merge($chairperison_list,$more_judge_list);

		return $judge_list;
	}

	public function get_court($schema){
		$query = "select court_no,display_court_text from $schema.court order by court_no";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$courts = $stmt->fetchAll();
		return $courts;
	}

	public function get_categories(){
		return array('all'=>'All','P'=>'Daily Order/Pending','D'=>'Judgement/Disposed','T'=>'Transferred');
	}

	public function free_text_search(){
		return array('1'=>'word_search','2'=>'exact_search');
	}

	public function party_search(){
		return array('1'=>'main_party','2'=>'additional_party');
	}

	public function search_by($page){
		if($page == 'case_status')
			$search_by = array(''=>'Select','filing_no_wise'=>'Filing No','case_no_wise'=>'Case Number','case_type_wise'=>'Case Type','party_wise'=>'By Party','advocate_wise'=>'By Advocate');
		elseif($page == 'judgements' || $page == 'orders')
			$search_by = array(''=>'Select','case_no_wise'=>'Case Number','free_text_wise'=>'Free Text','judges_member_wise'=>'Judges','efiling_no_wise'=>'Filing Number','order_date_wise'=>'Judgement date');
		elseif($page == 'orders')
			$search_by = array(''=>'Select','case_no_wise'=>'Case Number','free_text_wise'=>'Free Text','judges_member_wise'=>'Judges','efiling_no_wise'=>'Filing Number','order_date_wise'=>'Order date');
		else
			$search_by = array();
		return $search_by;
	}
	

}
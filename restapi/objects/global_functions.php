<?php
class GlobalFunctions{

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

}
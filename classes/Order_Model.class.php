<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */
//You can of course choose any name for your class or integrate it in something like a functions or base class
include("../classes/DBConnection.class.php");

class Order_Model
{ 

	protected $table = "order_daily";
	
	protected $schema;
	
	protected $advocate_order_table = "order_daily_advocate";

    function __construct($schema)
    {
		$this->schema = $schema;

    }
	
	public function getOrderByDateAndFiling($filing_no,$date)
	{
		$conn = new DBConnection();
		$sql="select item_no from $this->schema.$this->table where filing_no = ? and order_date = ?";
		$order_data=$conn->prepare($sql);
		$order_data->bindParam(1, $filing_no, PDO::PARAM_STR);
		$order_data->bindParam(2, $date, PDO::PARAM_STR);
		$order_data->execute();
		$order_data=$order_data->fetchColumn();
		$conn=null;
		return $order_data;
	}
	
	public function orderData($id,$filing_no,$date,$columns = '*')
	{
		$conn = new DBConnection();
		$sql="select $columns from $this->schema.$this->table where item_no = ? and filing_no = ? and order_date = ?";
		$order_data=$conn->prepare($sql);
		$order_data->bindParam(1, $id, PDO::PARAM_STR);
		$order_data->bindParam(2, $filing_no, PDO::PARAM_STR);
		$order_data->bindParam(3, $date, PDO::PARAM_STR);
		$order_data->execute();
		$order_data=$order_data->fetchAll();
		$conn=null;
		return array_shift($order_data);
	}
	
	public function orderDataByOrderId($order_id,$columns="*")
	{
		$conn = new DBConnection();
		$sql="select $columns from $this->schema.$this->table where item_no = ?";
		$order_data=$conn->prepare($sql);
		$order_data->bindParam(1, $order_id, PDO::PARAM_STR);
		$order_data->execute();
		$order_data=$order_data->fetchAll();
		$conn=null;
		return array_shift($order_data);
	}
	
	public function advocateData($id,$filing_no,$date,$advocate_type)
	{
		$conn = new DBConnection();
		$sql="select * from $this->schema.$this->advocate_order_table where filing_no = ? and order_date = ? and advocate_type = ? and order_id = ? order by id asc";
		$order_data=$conn->prepare($sql);
		$order_data->bindParam(1, $filing_no, PDO::PARAM_STR);
		$order_data->bindParam(2, $date, PDO::PARAM_STR);
		$order_data->bindParam(3, $advocate_type, PDO::PARAM_STR);
		$order_data->bindParam(4, $id, PDO::PARAM_STR);
		$order_data->execute();
		$order_data=$order_data->fetchAll();
		$conn=null;
		return $order_data;
	}
	
	public function count_order_by_filing_no($filing_no)
	{
		$conn = new DBConnection();
		$query="select count(*) from $this->schema.$this->table where filing_no= ?";
		$count_order=$conn->prepare($query);
		$count_order->bindParam(1, $filing_no, PDO::PARAM_STR);
		$count_order->execute();
		$count_order = $count_order->fetchColumn();
		$conn=null;
		return $count_order;
		
	}
	
}
?>
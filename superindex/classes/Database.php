<?php
class Database {
    // CHANGE THE DB INFO ACCORDING TO YOUR DATABASE
    private $strDbHost = 'localhost';
    private $strDbName = 'gstat';
    private $strDbUsername = 'postgres';
    private $strDbPassword = 'admin';
    public function dbConnection() {
        try{
            $objConn = new PDO('pgsql:host='.$this->strDbHost.';dbname='.$this->strDbName,$this->strDbUsername,$this->strDbPassword);
            $objConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $objConn;
        }
        catch(PDOException $objException){
            echo "Connection error ".$objException->getMessage();
            exit;
        } 
    }
}
?>

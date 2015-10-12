<?php

class MySqlDatabase{
	private $connection;
	private $database;
	public $lastQuery=null;
	public $lastSql=false;
	public function __construct(){
		$this->openConnection();
	}
	public function openConnection(){
		global $page;
		$this->connection = mysql_connect(DatabaseServer, DatabaseUsername, DatabasePassword);
		if (!$this->connection){
			$page->end('<li>The connection to the database failed.</li>');
		} else {
			$this->database = mysql_select_db(DatabaseName, $this->connection);
			if (!$this->database){
				$page->end('<li>The connection to the database failed.</li>');
			}
		}
	}
	public function closeConnection(){
		if(isset($this->connection)){
			mysql_close($this->connection);
			unset($this->connection);
		}
	}
	private function confirmSql($sql){
		global $page;
		if(!$sql){
			$page->end('This query failed');
		}
	}
	public function query($query,$save=true){
		if($save){
			$this->lastQuery = $query;
			$this->lastSql = mysql_query($query, $this->connection);
			$this->confirmSql($this->lastSql);
			return $this->lastSql;
		}
		else{
			$sql = mysql_query($query, $this->connection);
			$this->confirmSql($sql);
			return $sql;		
		}
	}
	public function fetchAssoc($sql=null){
		$sql == null ? $sql = $this->lastSql : null;
		$this->confirmSql($sql);
		return mysql_fetch_assoc($sql);
	}
	public function fetchArray($sql=null){
		$sql == null ? $sql = $this->lastSql : null;
		$this->confirmSql($sql);
		return mysql_fetch_array($sql);
	}
	public function insertId(){
		return mysql_insert_id();
	}
	public function affectedRows(){
		return mysql_affected_rows();
	}
	public function numRows($sql=null){
		$sql == null ? $sql = $this->lastSql : null;
		$this->confirmSql($sql);
		return mysql_num_rows($sql);
	}
	public function totalRows($tableName){
		$query = "SELECT * FROM `$tableName` WHERE 1";
		$sql = $this->query($query);
		$this->confirmSql($sql);
		return $this->numRows($sql);
	}
	public function simpleEscape($string){
		global $page;
		if(function_exists("mysql_real_escape_string")){
			$string = mysql_real_escape_string($string);
		}
		return $string;
	}
	public function escapeString($string){
		global $page;
		if(function_exists("mysql_real_escape_string")){
			$string = mysql_real_escape_string($string);
		}
		if(get_magic_quotes_gpc()){
			$string = stripslashes($string);
		}
		else{
			$string = addslashes($string);
		}
		if(function_exists("htmlentities")){
			$string = htmlentities($string);
		}
		return htmlspecialchars_decode($string);
	}
	public function checkValue($tableName, $colName, $value){
		$value = $this->escapeString($value);
		$query = "SELECT * FROM `$tableName` WHERE `$colName`='$value'";
		$sql = $this->query($query, false);
		$count = $this->numRows($sql);
		return $count > 0 ? true : false;
	}
	public function otherValue($tableName, $colName, $value, $otherCol){
		$value = $this->escapeString($value);
		$query = "SELECT `$otherCol` FROM `$tableName` WHERE `$colName`='$value'";
		$sql = $this->query($query, false);
		$count = $this->numRows($sql);
		if($count > 0){
			$data = $this->fetchAssoc($sql);
			return $data[$otherCol];
		}
		else{
			return false;
		}
	}
}

$database = new MySqlDatabase();

?>
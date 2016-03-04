<?php

include 'dbconfig.php';

class pdo_client
{
	// the database connection object
	protected $db;

	function __construct()
	{
		$attributes = array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		$this->db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS, $attributes);
	}

	public function getColumnNamesForTable($table)
	{
		try
		{
			$query = "DESCRIBE $table";
		
			$stmt = $this->db->prepare($query);
			$stmt->execute();
	        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
	        
	        return $rows;
		} 
		catch (PDOException $ex) 
		{
			$errInfo = $this->handleException($ex);

			// return our custom error message
			return $errInfo;
		}
	}
}


?>
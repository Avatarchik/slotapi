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

	protected function runQuery($query)
	{
		try
		{
			$stmt = $this->db->prepare($query);
			$stmt->execute();
	        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	        
	        return $result;
		} 
		catch (PDOException $ex) 
		{
			$errInfo = $this->handleException($ex);
			// return our custom error message
			return $errInfo;
		}	
	}

	public function getRowForUserId($user_id)
	{
		return $this->runQuery("SELECT user_id FROM members_device WHERE user_id = $user_id");
	}

	public function getUserIdForDeviceId($device_id)
	{
		return $this->runQuery("SELECT user_id FROM members_device WHERE device_id = $device_id");
	}

	public function insertNewDeviceId($device_id)
	{
		$result = $this->runQuery("INSERT INTO members_device (device_id, col2 , col3) values ('$device_id', 1234, 123)");
		if(isset($result))
		{
			$user_id = $this->db->lastInsertId();
			return $user_id;
		}
		else
		{
			throw new Exception('device_id could not be inserted :'.$device_id);
		}
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
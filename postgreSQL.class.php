<?php

//Definition der Klasse postgreSQL
class postgreSQL_query
{	
	private $connection;
	private $query;
	
	//Funktionen erzeugen
	public function __construct()
	{
		$this->open_connection;
		$this->query_db;
	}
	
	//Verbindungsaufbau
	public function open_connection()
	{
		$this->connection = pg_connect('host= port= dbname= user= password=');
		
		if (!$this->connection)
		{
			echo '<script>window.alert("Connection failed");</script>';
		}
	}
	
	//Query
	public function query_db($sSQL)
	{
		$this->query = pg_query($this->connection, $sSQL);
		
		if(!$this->query)
		{
			echo '<script>window.alert("Query failed");</script>';
			return false;
		}
		else 
		{
			return true;	
		}
	}
}


?>
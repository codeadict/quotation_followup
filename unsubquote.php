<?php

/**
 * @author Dairon Medina <info@gydsystems.com>
 *
 */

define('BASE_URL',dirname(__DIR__));
ob_start();

class OpenERPConn{
	private $server;
	private $port;
	private $db;
	private $username;
	private $password;

	private $connection;
	private $result;

	public function __construct($server = 'localhost', $port = '5432', $db = 'brash', $username = 'openerp', $password = 'admin'){
		$this->server = $server;
		$this->port = $port;
		$this->db = $db;
		$this->username = $username;
		$this->password = $password;
	}

	private function connect(){
		$conn_str = 'host=' . $this->server . ' port=' . $this->port . ' dbname=' . $this->db . ' user=' . $this->username . ' password=' . $this->password;
		$this->connection = pg_connect($conn_str);
	}

	public function run_query($sql){
		if (!$this->is_connected()) {
			$this->connect();
			if(!$this->is_connected())
				die('Error: We can not connect to Database: ' . pg_last_error());
		}
		$this->result = pg_query($this->connection,$sql);
		if (!$this->result)
		{	$error_capt=pg_last_error($this->connection);
			throw new Exception($error_capt);
		}
		else {
			return $this->result;
		}
	}

	private function is_connected(){
		return (!$this->connection)? false : true;
	}

	public function disconnect(){
		if ($this->is_connected()) {
			pg_free_result($this->result);
			pg_close($this->connection);
		}
	}

	public function is_unsuscribed($company_id, $quote_id){

	}

	public function unsubscribe($company_id, $quote_id){

	}
}

?>
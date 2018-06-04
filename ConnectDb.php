<?php

// Singleton to connect db.
class ConnectDb {
	private $_connection;
	private static $_instance; //The single instance
        private $_host = "localhost";
            private $_username = "root";
            private $_password = "";
            private $_database = "chat01";
	
	/*
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	// Constructor
	private function __construct() {
            if($_SERVER['REMOTE_ADDR']=='127.0.0.1' || $_SERVER['REMOTE_ADDR']=='::1'){
                $this->_host = "localhost";
                $this->_username = "root";
                $this->_password = "";
                $this->_database = "chat01";
            }else{
                $this->_host = "localhost";
                $this->_username = "sovoce01";
                $this->_password = "abondr@1984";
                $this->_database = "sovoce_practice01";
            }
            $this->_connection = new mysqli($this->_host, $this->_username, 
			$this->_password, $this->_database);
	
		// Error handling
		if(mysqli_connect_error()) {
			trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
				 E_USER_ERROR);
		}
	}
	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }
	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
	}
}
//ALTER TABLE  `chat` ADD  `is_link` ENUM(  '1',  '0' ) NOT NULL DEFAULT  '0' AFTER  `to_id` ;

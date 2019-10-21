<?php
		
		namespace app;

		/*$servername = "127.0.0.1";
		$username = "root";
		$password = "";
		$dbname = "tech_db";

	// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);



	// Check connection
		if (!$conn) {
    		die("Connection failed: " . mysqli_connect_error());
		}*/


class db_conn {

	public $servername;
	public $username;
	public $password;
	public $dbname;

	public function connect() {
		$this->servername = "127.0.0.1";
		$this->username = "root";
		$this->password = "";
		$this->dbname = "tech_db";

		$conn = new mysqli ($this->servername, $this->username, $this->password, $this->dbname);
		return $conn;
	}
}

?>
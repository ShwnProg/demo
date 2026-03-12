<?php

class Database {

    public $conn;

    public function __construct() {

        $this->conn = new mysqli("localhost", "root", "pil1298ap", "pmis");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}

?>

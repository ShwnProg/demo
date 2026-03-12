<?php
class Database {

    private $host = "localhost";
    private $db = "integrative_db";
    private $user = "root";
    private $pass = "shawnmarlogaldo@1122";

    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=".$this->host.";dbname=".$this->db,
                $this->user,
                $this->pass
            );

            // Set PDO to throw exceptions on errors
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
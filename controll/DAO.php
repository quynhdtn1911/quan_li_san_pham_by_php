<?php
class DAO{
    public $conn;
    
    public function __construct(){
        $this->conn = new mysqli("localhost", "root", "123456789", "laptrinhweb");
        if($this->conn->connect_error){
            die("Connection failed: ".$this->conn->connect_error);
        }else{
            mysqli_set_charset($this->conn, "utf-8");
        }
    }
}
<?php
include '../model/User.php';
class UserDAO extends DAO{

    public function checkLogin($user){
        $username = $user->getUsername();
        $password = $user->getPassword();
        $sql = "SELECT * FROM tblUser WHERE username = '".$username."' AND password = '".$password."';";
        $result = $this->conn->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_array();
            $user->setFullName($row["fullname"]);
            $user->setAddress($row["address"]);
            $user->setEmail($row["email"]);
            $user->setTel($row["tel"]);
            $user->setRole($row["role"]);
            $user->setId($row["id"]);
            $this->conn->close();
            return $user;
        }
        $this->conn->close();
        return null;
    }
}
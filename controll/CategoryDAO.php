<?php
include '../model/Category.php';
class CategoryDAO extends DAO{

    public function searchCategory($key){
        $sql = "SELECT * FROM tblCategory WHERE name like '%".$key."%';";
        $listCategory = $this->resultExecuteQuery($sql);
        return $listCategory;
    }

    public function getListCategory(){
        $sql = "SELECT * FROM tblCategory";
        $listCategory = $this->resultExecuteQuery($sql);
        return $listCategory;
    }

    public function getCategoryById($id){
        $sql = "SELECT * FROM tblCategory WHERE id = '".$id."';";
        $listCategory = $this->resultExecuteQuery($sql);
        if(sizeof($listCategory) > 0) return $listCategory[0];
        else return null;
    }

    private function resultExecuteQuery($sql){
        $result = $this->conn->query($sql);
        $listCategory = array();
        while($row = $result->fetch_array()){
            $category = new Category();
            $category->setId($row["id"]);
            $category->setName($row["name"]);
            $category->setImage($row["image"]);
            $category->setDesc($row["des"]);
            $category->setCreateDay($row["createDay"]);
            $listCategory[] = $category;
        }
        return $listCategory;
    }

    public function addCategory($category){
        $name = $category->getName();
        $image = $category->getImage();
        $desc = $category->getDesc();
        $createDay = $category->getCreateDay();
        $sql = "INSERT INTO tblCategory(name, image, des, createDay)
                VALUES('".$name."', '".$image."', '".$desc."', '".$createDay."');";
        $this->executeQuery($sql);
        return true;
    }

    public function editCategory($category){
        $id = $category->getId();
        $name = $category->getName();
        $image = $category->getImage();
        $desc = $category->getDesc();
        $sql = "UPDATE tblCategory
                SET name = '".$name."', image = '".$image."', des = '".$desc."'
                WHERE id = '".$id."';";
        $this->executeQuery($sql);
        return true;
    }

    public function deleteCategory($idCategory){
        $sql = "SELECT COUNT(id) as count FROM tblProduct WHERE idCategory = '".$idCategory."';";
        $result = $this->conn->query($sql);
        $count = 0;
        if($row = $result->fetch_array()) $count = $row["count"];
        if($count == 0){
            $sql = "DELETE FROM tblCategory
                    WHERE id = '".$idCategory."';";
            $this->executeQuery($sql);
            return true;
        }else return false;
    }

    private function executeQuery($sql){
        $this->conn->query($sql);
    }
}
<?php
include '../model/Product.php';
class ProductDAO extends DAO{

    public function searchProduct($key, $order){
        if($order == 0) $order = ';';
        else if($order == 1) $order = 'ORDER BY p.price;';
        else if($order == 2) $order = 'ORDER BY p.price DESC;';
        else if($order == 3) $order = 'ORDER BY p.id DESC';
        $sql = "SELECT p.id as pId, p.name as pName, p.image as pImage, price, p.des as pDesc,
                        c.id as cId, c.name as cName, c.image as cImage, c.des as cDesc, createDay
                FROM tblProduct as p, tblCategory as c
                WHERE p.name LIKE '%".$key."%' AND p.idCategory = c.id ".$order;
        $listProduct = $this->resultExecuteQuery($sql);
        return $listProduct;
    }

    public function getListProduct($order){
        if($order == 0) $order = ';';
        else if($order == 1) $order = 'ORDER BY p.price;';
        else if($order == 2) $order = 'ORDER BY p.price DESC;';
        else if($order == 3) $order = 'ORDER BY p.id DESC';
        $sql = "SELECT p.id as pId, p.name as pName, p.image as pImage, price, p.des as pDesc,
                c.id as cId, c.name as cName, c.image as cImage, c.des as cDesc, createDay
                FROM tblProduct as p, tblCategory as c
                WHERE p.idCategory = c.id ".$order;
        $listProduct = $this->resultExecuteQuery($sql);
        return $listProduct;
    }

    public function getProductByCategory($idCategory, $order){
        if($order == 0) $order = ';';
        else if($order == 1) $order = 'ORDER BY p.price;';
        else if($order == 2) $order = 'ORDER BY p.price DESC;';
        else if($order == 3) $order = 'ORDER BY p.id DESC';
        $sql = "SELECT p.id as pId, p.name as pName, p.image as pImage, price, p.des as pDesc,
                        c.id as cId, c.name as cName, c.image as cImage, c.des as cDesc, createDay
                FROM tblProduct as p, tblCategory as c
                WHERE p.idCategory = c.id AND c.id = '".$idCategory."' ".$order;
        $listProduct = $this->resultExecuteQuery($sql);
        return $listProduct;
    }

    public function getProductById($id){
        $sql = "SELECT p.id as pId, p.name as pName, p.image as pImage, price, p.des as pDesc,
                        c.id as cId, c.name as cName, c.image as cImage, c.des as cDesc, createDay
                FROM tblProduct as p, tblCategory as c
                WHERE  p.id = '".$id."' AND p.idCategory = c.id;";
        $listProduct = $this->resultExecuteQuery($sql);
        if(sizeof($listProduct) > 0) return $listProduct[0];
        else return null;
    }

    public function getListOldProduct(){
        $sql = "SELECT p.id as pId, p.name as pName, p.image as pImage, price, p.des as pDesc,
                        c.id as cId, c.name as cName, c.image as cImage, c.des as cDesc, createDay
                FROM tblProduct as p, tblCategory as c, (SELECT min(id) as pMin from tblProduct) as p1
                WHERE ((p.id = p1.pMin) OR (p.id = p1.pMin + 1) OR (p.id = p1.pMin + 2))
                      AND p.idCategory = c.id;"; 
        $listProduct = $this->resultExecuteQuery($sql);
        return $listProduct;
    }

    public function getListNewProduct(){
        $sql = "SELECT p.id as pId, p.name as pName, p.image as pImage, price, p.des as pDesc,
                        c.id as cId, c.name as cName, c.image as cImage, c.des as cDesc, createDay
                FROM tblProduct as p, tblCategory as c, (SELECT max(id) as pMax from tblProduct) as p1
                WHERE ((p.id = p1.pMax) OR (p.id = p1.pMax - 1) OR (p.id = p1.pMax - 2))
                    AND p.idCategory = c.id;"; 
        $listProduct = $this->resultExecuteQuery($sql);
        return $listProduct;
    }

    private function resultExecuteQuery($sql){
        $result = $this->conn->query($sql);
        $listProduct = array();
        while($row = $result->fetch_array()){
            $product = new Product();
            $product->setId($row["pId"]);
            $product->setName($row["pName"]);
            $product->setImage($row["pImage"]);
            $product->setPrice($row["price"]);
            $product->setDesc($row["pDesc"]);
            $category = new Category();
            $category->setId($row["cId"]);
            $category->setName($row["cName"]);
            $category->setImage($row["cImage"]);
            $category->setCreateDay($row["createDay"]);
            $category->setDesc($row["cDesc"]);
            $product->setCategory($category);
            $listProduct[] = $product;
        }
        return $listProduct;
    }

    public function addProduct($product){
        $name = $product->getName();
        $image = $product->getImage();
        $price = $product->getPrice();
        $desc = $product->getDesc();
        $idCategory = $product->getCategory()->getId();
        $sql = "INSERT INTO tblProduct(name, image, price, des, idCategory)
                VALUES('".$name."', '".$image."', '".$price."', '".$desc."', '".$idCategory."');";
        $this->executeQuery($sql);
        return true;
    }

    public function editProduct($product){
        $id = $product->getId();
        $name = $product->getName();
        $image = $product->getImage();
        $price = $product->getPrice();
        $desc = $product->getDesc();
        $idCategory = $product->getCategory()->getId();
        $sql = "UPDATE tblProduct
                SET name = '".$name."', image = '".$image."', price = '".$price."', des = '".$desc."', idCategory = '".$idCategory."'
                WHERE id = '".$id."';";
        $this->executeQuery($sql);
        return true;
    }

    public function deleteProduct($id){
        $sql = "DELETE FROM tblProduct WHERE id = '".$id."';";
        $this->executeQuery($sql);
        return true;
    }

    private function executeQuery($sql){
        $this->conn->query($sql);
    }
}
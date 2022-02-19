<?php
class Product{
    private $id;
    private $name;
    private $image;
    private $price;
    private $desc;
    private $category;

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

    public function setImage($image){
        $this->image = $image;
    }

    public function getImage(){
        return $this->image;
    }

    public function setPrice($price){
        $this->price = $price;
    }

    public function getPrice(){
        return $this->price;
    }

    public function setDesc($desc){
        $this->desc = $desc;
    }

    public function getDesc(){
        return $this->desc;
    }

    public function setCreateTime($createTime){
        $this->createTime = $createTime;
    }

    public function getCreateTime(){
        return $this->createTime;
    }

    public function setCategory($category){
        $this->category = $category;
    }

    public function getCategory(){
        return $this->category;
    }
}

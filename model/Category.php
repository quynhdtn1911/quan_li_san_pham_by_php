<?php
class Category{
    private $id;
    private $name;
    private $image;
    private $desc;
    private $createDay;

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

    public function setDesc($desc){
        $this->desc = $desc;
    }

    public function getDesc(){
        return $this->desc;
    }

    public function setCreateDay($createDay){
        $this->createDay = $createDay;
    }

    public function getCreateDay(){
        return $this->createDay;
    }
}
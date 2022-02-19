<?php
include 'DAO.php';
include 'CategoryDAO.php';
include 'ProductDAO.php';

$listProduct = $key = $idCurrentCategory = $productNumber = $pages = $currentPage = '';
$sort = 0;
$pd = new ProductDAO();
if(isset($_GET)){
    // lay danh saach san pham
    if(isset($_GET["key"]) && !empty($_GET["key"])){
        $key = $_GET["key"];
        $listProduct = $pd->searchProduct($key, $sort);
    }else if(isset($_GET["idCurrentCategory"]) && !empty($_GET["idCurrentCategory"])){
        $idCurrentCategory = $_GET["idCurrentCategory"];
        $listProduct = $pd->getProductByCategory($idCurrentCategory, $sort);
    }else{
        $listProduct = $pd->getListProduct($sort);
    }
    
    // lay trang hien tai
    if(isset($_GET["currentPage"]) && !empty($_GET["currentPage"])){
        $currentPage = $_GET["currentPage"];
    }else{
        $currentPage = 1;
    }
    $productNumber = sizeof($listProduct);
    $pages = ceil($productNumber / 6);

    $str = '<div class="menu_content">';
               
    if($productNumber == 0){
        $str = $str.'<h2 class="text-center mt-5" style="font-size: 2.4rem;">Không có sản phẩm nào!</h2>';
    }else{ 
        $str = $str.'<div class="menu-list row">';
        for($i = ($currentPage-1)*6; $i < min($productNumber, ($currentPage-1)*6 + 6);++$i){
            $product = $listProduct[$i];
            $str = $str.'<div class="menu-item col-4">
                            <div class="menu-item_content">
                                <div class="food_img position-relative">
                                    <img src="'.$product->getImage().'" alt="">
                                    <div class="food_img_abs position-absolute">
                                        <a href="" class="btn btn-danger">Xem chi tiết</a>
                                    </div>
                                </div>
                                <div class="food_info pl-2 pr-2 mt-2">
                                    <p class="food_name">'.$product->getName().'</p>
                                    <p class="food_price_current text-danger">'.$product->getPrice()*0.95.'<span>đ</span></p>
                                </div>
                                <p class="food_price_old text-right pr-2">'.$product->getPrice().'<span>đ</span></p>
                            </div>
                        </div>';
        }
        $str = $str.'</div>
                </div>';
        $str = $str.'<nav class="d-flex justify-content-end mt-4">
                        <ul class="pagination">';
        if($productNumber > 0){
            if($currentPage > 1) $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.($currentPage - 1).')"> Trang trước</a></li>';
            $font = $back = 0;
            for($i = 1; $i<=$pages; ++$i){
                if($i == $currentPage || $i == $currentPage -1 || $i == $currentPage + 1){
                    $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">'.$i.'</a></li>';
                }else if($font == 0 && $i < $currentPage){
                    $font = 1;
                    $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">...</a></li>';
                }else if($back == 0 && $i > $currentPage){
                    $back = 1;
                    $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">...</a></li>';
                }
            }
            if($currentPage < $pages ) $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.($currentPage + 1).')">Trang sau</a></li>';
        }
        $str = $str.'</ul>
                    </nav>';
    }
    echo $str;
}
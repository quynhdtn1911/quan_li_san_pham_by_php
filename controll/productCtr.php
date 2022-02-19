<?php
include 'DAO.php';
include 'CategoryDAO.php';
include 'ProductDAO.php';

$listProduct = $key = $idCurrentCategory = $productNumber = $pages = $currentPage = '';
$sort = 0;

$pd = new ProductDAO();
if(isset($_GET)){
    if(isset($_GET["action"])){
        // xu ly xoa san pham
        if($_GET["action"] == 1){
            $id = $_GET["id"];
            $io = $pd->deleteProduct($id);
            if($io == true){
                echo 1;
            }else echo 0;
        }
    }else{
        // lay danh sach san pham
        if(isset($_GET["key"]) && !empty($_GET["key"])){
            $key = $_GET["key"];
            if(isset($_GET["sort"]) && !empty($_GET["sort"])){
                $sort = $_GET["sort"];
            }
            $listProduct = $pd->searchProduct($key, $sort);
        }else if(isset($_GET["idCurrentCategory"]) && !empty($_GET["idCurrentCategory"])){
            $idCurrentCategory = $_GET["idCurrentCategory"];
            if(isset($_GET["sort"]) && !empty($_GET["sort"])){
                $sort = $_GET["sort"];
            }
            $listProduct = $pd->getProductByCategory($idCurrentCategory, $sort);
        }else{
            if(isset($_GET["sort"]) && !empty($_GET["sort"])){
                $sort = $_GET["sort"];
            }
            $listProduct = $pd->getListProduct($sort);
        }
        
        // lay trang hien tai
        if(isset($_GET["currentPage"]) && !empty($_GET["currentPage"])){
            $currentPage = $_GET["currentPage"];
        }else{
            $currentPage = 1;
        }
        $productNumber = sizeof($listProduct);
        $pages = ceil($productNumber / 3);

        $str = '<h2 class="title">Danh sách sản phẩm</h2>
                <div class="product_header">
                    <a href="addProductForm.php?action=0" class="link btn btn-success btn-add">Thêm sản phẩm</a>';
        
        if($productNumber != 0){
            $str = $str.'<div class="form-sort">
                            <form action="" class="pt-2 pb-1">
                                <select name="sort" id="">';

            if($sort == 1) $str = $str.'<option value="1" selected>Sắp xếp theo giá tăng dần</option>';
            else $str = $str.'<option value="1">Sắp xếp theo giá tăng dần</option>';

            if($sort == 2) $str = $str.'<option value="2" selected>Sắp xếp theo giá giảm dần</option>';
            else $str = $str.'<option value="2">Sắp xếp theo giá giảm dần</option>';
            
            if($sort == 3) $str = $str.'<option value="3" selected>Sắp xếp theo thời gian thêm mới</option>';
            else $str = $str.'<option value="3">Sắp xếp theo thời gian thêm mới</option>';
            $str = $str.'</select>
                            </form>
                            <a href="javascript:sortProduct()" class="link btn btn-secondary ml-2">Sắp xếp</a>
                        </div>';
        };
        $str = $str.'</div>    
                <table width="100%" class="table table-striped tblProduct">
                    <tr class="row">
                        <th class="col-2">Hình ảnh</th>
                        <th class="col-2">Tên</th>
                        <th class="col-1">Giá</th>
                        <th class="col-3">Mô tả</th>
                        <th class="col-2">Danh mục</th>
                        <th class="col-2"></th>
                    </tr>';
        if($productNumber == 0){
            $str = $str.'<h2 class="text-center mt-5" style="font-size: 2.4rem;">Không có sản phẩm nào!</h2>';
        }else{
            for($i = ($currentPage -1)*3 ; $i < min($productNumber, ($currentPage - 1)*3 + 3) ; ++$i){
                $product = $listProduct[$i];
                $str = $str.'<tr class="row">
                                <td class="col-2">
                                    <img src="'.$product->getImage().'" style="width:120px;" alt="">
                                </td>
                                <td class="col-2">'.$product->getName().'</td>
                                <td class="col-1">'.$product->getPrice().'</td>
                                <td class="col-3">'.$product->getDesc().'</td>
                                <td class="col-2">'.$product->getCategory()->getName().'</td>
                                <td class="col-2">
                                    <a href="addProductForm.php?action=1&id='.$product->getId().'" class="btn btn-warning">Sửa</a>
                                    <a href="javascript:deleteProduct('.$product->getId().')" class="btn btn-danger">Xóa</a>
                                </td>
                            </tr>';
            }
        }
        $str = $str.'</table>';
        $str = $str.'<nav class="d-flex justify-content-end mt-4">
                        <ul class="pagination">';
        if($currentPage > 1) $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.($currentPage - 1).')"> Trang trước</a></li>';
        $font = $back = 0;
        for($i = 1; $i<=$pages; ++$i){
            if($i == $currentPage || $i == $currentPage -1 || $i == $currentPage + 1){
                $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">'.$i.'</a></li>';
            }else if($i < $currentPage && $font == 0){
                $font = 1;
                $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">...</a></li>';
            }else if($i > $currentPage && $back == 0){
                $back = 1;
                $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">...</a></li>';
            }
        }
        if($currentPage < $pages ) $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.($currentPage + 1).')">Trang sau</a></li>';
        $str = $str.'</ul>
                    </nav>';
        echo $str;
    }
}
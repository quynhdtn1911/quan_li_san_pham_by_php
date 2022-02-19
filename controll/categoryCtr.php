<?php
include 'DAO.php';
include 'CategoryDAO.php';

    $listCategory = $key = $categoryNumber = $pages = $currentPage = $str = '';

    $cd = new CategoryDAO();

    if(isset($_GET)){
        if(isset($_GET["action"])){
            if($_GET["action"] == 1){
                $id = $_GET["id"];
                $io = $cd->deleteCategory($id);
                if($io == true){
                    echo 1;
                }else{
                    echo 'Không thể xóa danh mục trong khi tồn tại một sản phẩm thuộc danh mục!';
                }
            }
        }else{
            // lay danh muc category
            if(isset($_GET["key"]) && $_GET["key"] != ''){
                $key = $_GET["key"];
                $listCategory = $cd->searchCategory($key);
            }
            else{
                $listCategory = $cd->getListCategory();
            }

            //lay trang danh muc hien tai
            if(isset($_GET["currentPage"])) $currentPage = $_GET["currentPage"];
            else $currentPage = 1;
            $categoryNumber = sizeof($listCategory);
            $pages = ceil($categoryNumber/3);

            $str = '<h2 class="title">Quản lý danh mục sản phẩm</h2>
                    <a href="addCategoryForm.php?action=0" class="btn btn-success btn-add">Thêm danh mục</a>
                    <table id="result" width="100%" class="table table-striped">
                        <tr class="row">
                            <th class="col-2">Hình ảnh minh họa</th>
                            <th class="col-2">Tên danh mục</th>
                            <th class="col-4">Mô tả</th>
                            <th class="col-2">Ngày lập danh mục</th>
                            <th class="col-2"></th>
                        </tr>';
            if($categoryNumber == 0){
                $str = $str.'<h2 class="text-center mt-5" style="font-size: 2.4rem;">Không có sản phẩm nào!</h2>';
            }else{
                for($i = ($currentPage-1)*3; $i < min($categoryNumber, ($currentPage-1)*3 + 3);++$i){
                    $category = $listCategory[$i];
                    $str = $str.'<tr class="row">
                                <td class="col-2">
                                    <img src="'.$category->getImage().'" alt="">
                                </td>
                                <td class="col-2">'.$category->getName().'</td>
                                <td class="col-4">'.$category->getDesc().'</td>
                                <td class="col-2">'.$category->getCreateDay().'</td>
                                <td class="col-2">
                                    <a href="addCategoryForm.php?action=1&id='.($category->getId()).'" class="btn btn-warning btn-edit">Sửa</a>
                                    <a href="javascript:deleteCategory('.$category->getId().')" class="btn btn-danger btn-delete">Xóa</a>
                                </td>
                            </tr>';
                }
            }
            $str = $str.'</table>';
            $str = $str.'<nav class="d-flex justify-content-end mt-4">
                        <ul class="pagination">';
            if($currentPage > 1) $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListCategoryByPage('.($currentPage - 1).')"> Trang trước</a></li>';
            $font = $back = 0;
            for($i = 1; $i<=$pages; ++$i){
                if($i == $currentPage || $i == $currentPage -1 || $i == $currentPage + 1){
                    $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListCategoryByPage('.$i.')">'.$i.'</a></li>';
                }else if($i < $currentPage && $font == 0){
                    $font = 1;
                    $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListCategoryByPage('.$i.')">...</a></li>';
                }else if($i > $currentPage && $back == 0){
                    $back = 1;
                    $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListCategoryByPage('.$i.')">...</a></li>';
                }
            }
            if($currentPage < $pages ) $str = $str.'<li class="page-item"><a class="page-link" href="javascript:getListCategoryByPage('.($currentPage + 1).')">Trang sau</a></li>';
            $str = $str.'</ul>
                </nav>';
            echo $str;
        }
    }
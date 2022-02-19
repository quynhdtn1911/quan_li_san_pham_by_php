<?php
session_start();
include '../controll/DAO.php';
include '../controll/CategoryDAO.php';

    $fullname = $_SESSION["fullname"];

    $listCategory = $key = $categoryNumber = $pages = $currentPage = '';

    $cd = new CategoryDAO();

    $listCategory = $cd->getListCategory();

    // lay trang danh muc hien tai
    $currentPage = 1;
    $categoryNumber = sizeof($listCategory);
    $pages = ceil($categoryNumber/3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/managementStyle.css">
</head>
<body>
    <div id="header">
        <div id="header_nav" class="position-fixed container-fluid pl-0 pr-0">
            <div id="header_user">
                <nav class="navbar navbar-expand container d-flex justify-content-between pl-0 pr-0">
                    <p class="text-light">
                        Xin chào, <?=$fullname?>
                    </p>
                    <div class="navbar-nav">
                        <div class="nav-item mr-2">
                            <a href="#" class="nav-link">
                                <img src="../assets/images/information.png" alt="" class="mr-1 align-middle nav-icon">
                                <span class="text-light align-middle">Thông báo</span>
                            </a>
                        </div>
                        <div class="nav-item dropdown mr-2">
                            <a class="nav-link" href="#" id="navbarDropdownMenuLink1" role="button" data-toggle="dropdown" aria-expanded="false">
                                <img src="../assets/images/user (2).png" alt="" class="mr-1 align-middle nav-icon">
                                <span class="text-light align-middle">Tài khoản</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink1">
                                <a class="dropdown-item" href="#">Đăng xuất</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown mr-2">
                            <a class="nav-link" href="#" id="navbarDropdownMenuLink2" role="button" data-toggle="dropdown" aria-expanded="false">
                                <span class="text-light align-middle">Tùy chọn</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                                <a class="dropdown-item" href="index.php">Trang chủ</a>
                                <a class="dropdown-item" href="productManagementHome.php?action=0">Quản lý sản phẩm</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div id="header_search" class="position-relative d-flex justify-content-center align-items-center row">
            <!-- <div class="overlay position-absolute">
            </div> -->
            <form action="" class="col-6 row position-relative">
                <input type="hidden" name="type" value="">
                <input type="text" name="key" placeholder="Nhập từ khóa tìm kiếm" class="col-12 pt-3 pl-5 pr-3 pb-3">
                <a href="javascript:searchCategory()" class="link d-block position-absolute icon-search" style="border: none;background: #fff">
                    <img src="../assets/images/search.png" alt="">
                </a>
            </form>
        </div>
    </div>
    <div class="container-fluid pl-0 pr-0 content_session row">
        <div id="category_container" class="container pl-0 pr-0 content_container col-10">
            <h2 class="title">Quản lý danh mục sản phẩm</h2>
            <a href="addCategoryForm.php?action=0" class="btn btn-success btn-add">Thêm danh mục</a>
            <table width="100%" class="table table-striped">
                <tr class="row">
                    <th class="col-2">Hình ảnh minh họa</th>
                    <th class="col-2">Tên danh mục</th>
                    <th class="col-4">Mô tả</th>
                    <th class="col-2">Ngày lập danh mục</th>
                    <th class="col-2"></th>
                </tr>
                <?php
                    if($categoryNumber == 0){
                        echo '<h2 class="text-center mt-5" style="font-size: 2.4rem;">Không có sản phẩm nào!</h2>';
                    }else{
                        for($i = ($currentPage-1)*3; $i < min($categoryNumber, ($currentPage-1)*3 + 3);++$i){
                            $category = $listCategory[$i];
                            echo '<tr class="row">
                                    <td class="col-2">
                                        <img src="'.$category->getImage().'" alt="">
                                    </td>
                                    <td class="col-2">'.$category->getName().'</td>
                                    <td class="col-4">'.$category->getDesc().'</td>
                                    <td class="col-2">'.$category->getCreateDay().'</td>
                                    <td class="col-2">
                                        <a href="addCategoryForm.php?action=1&id='.$category->getId().'" class="btn btn-warning btn-edit">Sửa</a>
                                        <a href="javascript:deleteCategory('.$category->getId().')" class="btn btn-danger btn-delete">Xóa</a>
                                    </td>
                                </tr>';
                        }
                    }
                ?>
            </table>
            <nav class="d-flex justify-content-end mt-4">
                <ul class="pagination">
                    <?php
                        if($currentPage > 1) echo '<li class="page-item"><a class="page-link" href="?currentPage='.($currentPage - 1).'"> Trang trước</a></li>';
                        $font = $back = 0;
                        for($i = 1; $i<=$pages; ++$i){
                            if($i == $currentPage || $i == $currentPage -1 || $i == $currentPage + 1){
                                echo '<li class="page-item"><a class="page-link" href="javascript:getListCategoryByPage('.$i.')">'.$i.'</a></li>';
                            }else if($i < $currentPage && $font == 0){
                                $font = 1;
                                echo '<li class="page-item"><a class="page-link" href="javascript:getListCategoryByPage('.$i.')">...</a></li>';
                            }else if($i > $currentPage && $back == 0){
                                $back = 1;
                                echo '<li class="page-item"><a class="page-link" href="javascript:getListCategoryByPage('.$i.')">...</a></li>';
                            }
                        }
                        if($currentPage < $pages ) echo '<li class="page-item"><a class="page-link" href="javascript:getListCategoryByPage('.($currentPage + 1).')">Trang sau</a></li>';
                    ?>
                </ul>
            </nav>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        var key = '';
        var xmlhttp = new XMLHttpRequest();
        function searchCategory(){
            key = document.querySelector("input[name='key']").value;
            document.querySelector("input[name='type']").value = key;
            if(key == '') getListCategory();
            else{
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("category_container").innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("GET","../controll/categoryCtr.php?key="+key,true);
                xmlhttp.send();
            }
        }

        function getListCategory(){
            document.querySelector("input[name='type']").value = '';
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("category_container").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET","../controll/categoryCtr.php",true);
            xmlhttp.send();
        }

        function getListCategoryByPage(currentPage){
            var currentType = document.querySelector("input[name='type']").value;
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("category_container").innerHTML = this.responseText;
                }
            }
            if(currentType == '') xmlhttp.open("GET","../controll/categoryCtr.php?currentPage="+currentPage,true);
            else xmlhttp.open("GET","../controll/categoryCtr.php?key="+currentType + "&currentPage=" + currentPage,true);
            xmlhttp.send();
        }

        function deleteCategory(id){
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if(this.responseText == 1){
                        alert('Xóa danh mục sản phẩm thành công!');
                        getListCategory();
                    }
                    else{
                        alert('Không thể xóa danh mục trong khi tồn tại một sản phẩm thuộc danh mục!');
                    }
                }
            }
            xmlhttp.open("GET","../controll/categoryCtr.php?action=1&id="+id,true);
            xmlhttp.send();
        }
    </script>
</body>
</html>
<?php
session_start();

include '../controll/DAO.php';
include '../controll/CategoryDAO.php';
include '../controll/ProductDAO.php';

    $fullname = $_SESSION["fullname"];
    
    $listProduct = $listCategoy = $productNumber = $pages = $currentPage = '';

    $pd = new ProductDAO();
    $cd = new CategoryDAO();
    
    // lay danh sach danh muc san pham
    $listCategory = $cd->getListCategory();

    // lay danh sach san pham
    $listProduct = $pd->getListProduct(0);

    // lay trang san pham hien tai
    $currentPage = 1;
    $productNumber = sizeof($listProduct);
    $pages = ceil($productNumber/3);
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
    <link rel="stylesheet" href="../assets/css/productManagementStyle.css">
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
                                <a class="dropdown-item" href="#">Đặng nhập</a>
                                <a class="dropdown-item" href="#">Đăng ký</a>
                                <a class="dropdown-item" href="#">Đăng xuất</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown mr-2">
                            <a class="nav-link" href="#" id="navbarDropdownMenuLink2" role="button" data-toggle="dropdown" aria-expanded="false">
                                <span class="text-light align-middle">Tùy chọn</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                                <a class="dropdown-item" href="index.php">Trang chủ</a>
                                <a class="dropdown-item" href="managementHome.php">Quản lý danh mục sản phẩm</a>
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
                <input type="hidden" name="type">
                <input type="hidden" name="type_sort">
                <input type="text" name="key" placeholder="Nhập từ khóa tìm kiếm" class="col-12 pt-3 pl-5 pr-3 pb-3">
                <a href="javascript:searchProduct()" class="link d-block position-absolute icon-search" style="border: none;background: #fff">
                    <img src="../assets/images/search.png" alt="">
                </a>
            </form>
        </div>
    </div>
    <div id="category" class="container-fluid pl-0 pr-0 content_session row">
        <div class="content_container container pl-0 pr-0 col-6">
            <h2 class="title">Danh mục sản phẩm</h2>
            <div class="category_list row justify-content-center">
                <?php
                    for($i = 0; $i < sizeof($listCategory); ++$i){
                        $category = $listCategory[$i];
                        echo '<div class="category_item col-4">
                                <a href="javascript:getListProductByCategory('.$category->getId().')" class="link">
                                    <img src="'.$category->getImage().'" alt="">
                                    <p>'.$category->getName().'</p>
                                </a>
                            </div>';
                    }
                ?>
            </div>
        </div>
    </div>
    <div id="product" class="container-fluid pl-0 pr-0 content_session row">
        <div id="product_container" class="content_container container pl-0 pr-0 col-10">
            <h2 class="title">Danh sách sản phẩm</h2>
            <div class="product_header">
                <a href="addProductForm.php?action=0" class="link btn btn-success btn-add">Thêm sản phẩm</a>
                <div class="form-sort">
                    <form action="">
                        <select name="sort" id="" class="pt-2 pb-1">
                            <option value="1">Sắp xếp theo giá tăng dần</option>
                            <option value="2">Sắp xếp theo giá giảm dần</option>
                            <option value="3">Sắp xếp theo thời gian thêm mới</option>
                        </select>
                    </form>
                    <a href="javascript:sortProduct()" class="link btn btn-secondary ml-2">Sắp xếp</a>
                </div>
            </div>    
            <table width="100%" class="table table-striped tblProduct">
                <tr class="row">
                    <th class="col-2">Hình ảnh</th>
                    <th class="col-2">Tên</th>
                    <th class="col-1">Giá</th>
                    <th class="col-3">Mô tả</th>
                    <th class="col-2">Danh mục</th>
                    <th class="col-2"></th>
                </tr>
                <?php
                    if($productNumber == 0){
                        echo '<h2 class="text-center mt-5" style="font-size: 2.4rem;">Không có sản phẩm nào!</h2>';
                    }else{
                        for($i = ($currentPage -1)*3 ; $i < min($productNumber, ($currentPage - 1)*3 + 3) ; ++$i){
                            $product = $listProduct[$i];
                            echo '<tr class="row">
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
                ?>
            </table>
            <nav class="d-flex justify-content-end mt-4">
                <ul class="pagination">
                    <?php
                        if($currentPage > 1) echo '<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.($currentPage - 1).')"> Trang trước</a></li>';
                        $font = $back = 0;
                        for($i = 1; $i<=$pages; ++$i){
                            if($i == $currentPage || $i == $currentPage -1 || $i == $currentPage + 1){
                                echo '<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">'.$i.'</a></li>';
                            }else if($i < $currentPage && $font == 0){
                                $font = 1;
                                echo '<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">...</a></li>';
                            }else if($i > $currentPage && $back == 0){
                                $back = 1;
                                echo '<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">...</a></li>';
                            }
                        }
                        if($currentPage < $pages ) echo '<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.($currentPage + 1).')">Trang sau</a></li>';
                    ?>
                </ul>
            </nav>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        var xmlHttpRequest = new XMLHttpRequest();
        var product_container = document.getElementById("product_container");

        function searchProduct(){
            var key = document.querySelector("input[name='key']").value;
            document.querySelector("input[name='type_sort']").value = 0;
            if(key == ''){
                getListProduct();
            }else{
                if(isNaN(key)){
                    document.querySelector("input[name='type']").value = key;
                    xmlHttpRequest.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            product_container.innerHTML = this.responseText;
                        }
                    }
                    xmlHttpRequest.open("GET","../controll/productCtr.php?key="+key,true);
                    xmlHttpRequest.send();
                }else{
                    getListProductByCategory(key);
                };
            }
        }

        function getListProduct(){
            document.querySelector("input[name='type']").value = '';
            document.querySelector("input[name='type_sort']").value = 0;
            xmlHttpRequest.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    product_container.innerHTML = this.responseText;
                }
            }
            xmlHttpRequest.open("GET","../controll/productCtr.php", true);
            xmlHttpRequest.send();
        }

        function getListProductByCategory(idCategory){
            document.querySelector("input[name='type']").value = idCategory;
            document.querySelector("input[name='type_sort']").value = 0;
            xmlHttpRequest.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    product_container.innerHTML = this.responseText;
                }
            }
            xmlHttpRequest.open("GET","../controll/productCtr.php?idCurrentCategory="+idCategory, true);
            xmlHttpRequest.send();
        }

        function getListProductByPage(page){
            var type = document.querySelector("input[name='type']").value;
            var sort = document.querySelector("input[name='type_sort']").value;
            xmlHttpRequest.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    product_container.innerHTML = this.responseText;
                }
            }
            if(!isNaN(type)){
                xmlHttpRequest.open("GET","../controll/productCtr.php?idCurrentCategory=" + type + "&currentPage=" + page + "&sort=" + sort, true);
            }else if(type != ''){
                xmlHttpRequest.open("GET","../controll/productCtr.php?key=" + type + "&currentPage=" + page + "&sort=" +sort, true);
            }else{
                xmlHttpRequest.open("GET","../controll/productCtr.php?currentPage=" + page + "&sort=" + sort, true);
            }
            xmlHttpRequest.send();
        }

        function sortProduct(){
            var type = document.querySelector("input[name='type']").value;
            var option = document.querySelector("select[name='sort']").value;
            document.querySelector("input[name='type_sort']").value = option;
            xmlHttpRequest.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    product_container.innerHTML = this.responseText;
                }
            }
            if(!isNaN(type)){
                xmlHttpRequest.open("GET","../controll/productCtr.php?idCurrentCategory=" + type + "&sort=" + option, true);
            }else if(type != ''){
                xmlHttpRequest.open("GET","../controll/productCtr.php?key=" + type + "&sort=" + option, true);
            }else{
                xmlHttpRequest.open("GET","../controll/productCtr.php?sort=" + sort, true);
            }
            xmlHttpRequest.send();
        }

        function deleteProduct(id){
            xmlHttpRequest.onreadystatechange = function(){
                if(this.readyState == 4 && xmlHttpRequest.status == 200){
                    if(this.responseText == 1){
                        alert("Xóa sản phẩm thành công!");
                        getListProduct();
                    }else{
                        alert("Xóa sản phẩm không thành công!");
                    }
                }
            }
            xmlHttpRequest.open("GET", "../controll/productCtr.php?action=1&id=" + id, true);
            xmlHttpRequest.send();
        }
    </script>
</body>
</html>
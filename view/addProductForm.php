<?php
session_start();

include '../controll/DAO.php';
include '../controll/CategoryDAO.php';
include '../controll/ProductDAO.php';

    $fullname = $_SESSION['fullname'];

    $pd = new ProductDAO();
    $cd = new CategoryDAO();

    $name = $image = $price = $desc = $idCategory = $message_error = $btnStatus = $btnValue = $title = $id = $oldCategory = '';
    if(isset($_GET) && !empty($_GET)){
        if(isset($_GET["action"])){
            if($_GET["action"] == 0){
                initForm("Thêm sản phẩm", "success", "Thêm");
            }else if($_GET["action"] == 1){
                initForm("Sửa sản phẩm", "warning", "Sửa");

                $id = $_GET["id"];

                $product = $pd->getProductById($id);
                $name = $product->getName();
                $image = $product->getImage();
                $price = $product->getPrice();
                $desc = $product->getDesc();
                $idCategory = $product->getCategory()->getId();
            }else if($_GET["action"] == 2){
                $id = $_GET["id"];
                $name = $_GET["name"];
                $image = $_GET["image"];
                $price = $_GET["price"];
                $desc = $_GET["desc"];
                $idCategory = $_GET["idCategory"];
                if($name == '' || $image == '' || $price == '' || $desc == '' || $idCategory == ''){
                    if($id == '') initForm("Thêm sản phẩm", "success", "Thêm");
                    else initForm("Sửa sản phẩm", "warning", "Sửa");
                    $message_error = 'Vui lòng điền đầy đủ thông tin!';
                }else if($id == ''){
                    initForm("Thêm sản phẩm", "success", "Thêm");

                    $product = new Product();
                    $product->setName($name);
                    $product->setPrice($price);
                    $product->setImage($image);
                    $product->setDesc($desc);

                    $idCategory = $_GET["idCategory"];
                    $category2 = $cd->getCategoryById($idCategory);

                    $product->setCategory($category2);
                    $io = $pd->addProduct($product);
                    if($io == true){
                        header('Location: productManagementHome.php');
                    }else{
                        $message_error = 'Lỗi khi thêm sản phẩm!';
                    }
                }else{
                    initForm("Sửa sản phẩm", "warning", "Sửa");
                    $product = new Product();
                    $product->setId($id);
                    $product->setName($name);
                    $product->setPrice($price);
                    $product->setImage($image);
                    $product->setDesc($desc);

                    $idCategory = $_GET["idCategory"];
                    $category = $cd->getCategoryById($idCategory);

                    $product->setCategory($category);
                    $io = $pd->editProduct($product);
                    if($io == true){
                        header('Location: productManagementHome.php');
                    }else{
                        $message_error = 'Lỗi khi sửa sản phẩm!';
                    }
                }
            }
        }
    }

    function initForm($t, $s, $v){
        global $title, $btnStatus, $btnValue;
        $title = $t;
        $btnStatus = $s;
        $btnValue = $v;
    }

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
    <link rel="stylesheet" href="../assets/css/addFormStyle.css">
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
                                <img src="assets/images/information.png" alt="" class="mr-1 align-middle nav-icon">
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
                                <a class="dropdown-item" href="productManagementHome.php">Quản lý sản phẩm</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid  pl-0 pr-0 row content_session">
        <div class="content_container col-6">
            <h2 class="title"><?=$title?></h2>
            <form action="" method="GET" class="row">
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="action" value="2">
                <div class="form-group col-12 row">
                    <label for="name" class="col-3 align-middle">Tên sản phẩm:</label>
                    <input type="text" name="name" id="" class="col-8" value="<?=$name?>" placeholder="Tên sản phẩm">
                </div>
                <div class="form-group col-12 row">
                    <label for="image" class="col-3">Hình ảnh:</label>
                    <input type="text" name="image" id="" class="col-8" value="<?=$image?>" placeholder="Đường dẫn tới hình ảnh">
                </div>
                <div class="form-group col-12 row">
                    <label for="price" class="col-3">Giá:</label>
                    <input type="text" name="price" id="" class="col-8" value="<?=$price?>" placeholder="Giá sản phẩm">
                </div>
                <div class="form-group col-12 row">
                    <label for="" class="col-3">Danh mục sản phẩm:</label>
                    <select name="idCategory" id="" class="col-8">
                        <?php
                            $listCategory = $cd->getListCategory();
                            if(empty($oldCategory)){
                                $oldCategory = $listCategory[0];
                            }
                            for($i = 0 ; $i < sizeof($listCategory) ; ++$i){
                                $ctg = $listCategory[$i];
                                if($ctg->getId() == $idCategory) echo '<option value="'.$ctg->getId().'" selected>'.$ctg->getName().'</option>';
                                else echo '<option value="'.$ctg->getId().'">'.$ctg->getName().'</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-12 row">
                    <label for="desc" class="col-3">Mô tả:</label>
                    <textarea name="desc" id="" class="col-8" rows="10" placeholder="Mô tả sản phẩm"><?=$desc?></textarea>
                </div>
                <p class="message_error"><?=$message_error?></p>
                <div class="form-group col-12 text-center">
                    <a href="productManagementHome.php" class="btn btn-secondary">Thoát</a>
                    <input type="submit" class="btn btn-<?=$btnStatus?>" value="<?=$btnValue?>">
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
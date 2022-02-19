<?php
session_start();

include '../controll/DAO.php';
include '../controll/ProductDAO.php';
include '../controll/UserDAO.php';
include '../controll/CategoryDAO.php';

    $username = $password = $message_error = $active = '';
    
    if(isset($_POST) && !empty($_POST)){
        if(isset($_POST['username'])) $username = $_POST['username'];
        if(isset($_POST['password'])) $password = $_POST['password'];
        if(empty($username) || empty($password)){
            $message_error = 'Vui lòng điền đầy đủ thông tin!';
            $active = "active";
        }else{
            $ud = new UserDAO();
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($password);
            $user = $ud->checkLogin($user);
            if($user === null){
                $message_error = "Tên tài khoản hoặc mật khẩu không chính xác!";
                $active = "active";
            }else{
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = $user->getFullname();
                header("Location: managementHome.php");
            }
        }
    }

    $listProduct = $listCategoy = $idCurrentCategory = $productNumber = $pages = $currentPage = '';

    $pd = new ProductDAO();
    $cd = new CategoryDAO();

    // lay danh sach danh muc san pham
    $listCategory = $cd->getListCategory();

    // lay danh sach san pham
    $listProduct = $pd->getListProduct($idCurrentCategory);

    // lay trang san pham hien tai
    $currentPage = 1;
    $productNumber = sizeof($listProduct);
    $pages = ceil($productNumber/6);
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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="body_container">
        <div id="header">
            <div id="header_nav" class="position-fixed container-fluid pl-0 pr-0">
                <div id="header_user">
                    <nav class="navbar navbar-expand container d-flex justify-content-between pl-0 pr-0">
                        <p class="text-light">
                            Hân hạnh phục vụ quý khách
                        </p>
                        <div class="navbar-nav">
                            <div class="nav-item dropdown mr-2">
                                <a class="nav-link" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                                    <img src="../assets/images/user (2).png" alt="" class="mr-1 align-middle nav-icon">
                                    <span class="text-light align-middle">Tài khoản</span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a id="btn-login" class="dropdown-item" href="javascript:btnLoginOnclick()">Đăng nhập</a>
                                    <a class="dropdown-item" href="#">Đăng ký</a>
                                    <a class="dropdown-item" href="#">Đăng xuất</a>
                                </div>
                            </div>
                            <div class="nav-item mr-2">
                                <a href="#" class="nav-link">
                                    <img src="../assets/images/check.png" alt="" class="mr-1 align-middle nav-icon">
                                    <span class="text-light align-middle">Thanh toán</span>
                                </a>
                            </div>
                            <div class="nav-item mr-2">
                                <a href="#" class="nav-link">
                                    <img src="../assets/images/cart.png" alt="" class="mr-1 align-middle nav-icon">
                                    <span class="text-light align-middle">Giỏ hàng</span>
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
                <div id="header_menu" class="pt-3 pb-3 pl-0 pr-0">
                    <nav class="container navbar navbar-expand d-flex justify-content-between pl-0 pr-0">
                        <a href="#" class="logo nav-link align-midde text-light">
                            <img src="../assets/images/chef.png" alt="" class="align-middle mr-2 pl-0">
                            Nhà hàng Hương Quê
                        </a>
                        <div class="navbar-nav">
                            <a href="#" class="nav-link text-light ml-2 mr-2">Trang chủ</a>
                            <a href="#menu" class="nav-link text-light ml-2 mr-2">Thực đơn</a>
                            <a href="#about" class="nav-link text-light ml-2 mr-2">Giới thiệu</a>
                            <a href="#comment" class="nav-link text-light ml-2 mr-2">Nhận xét</a>
                            <a href="#contact" class="nav-link text-light ml-2 mr-2">Liên hệ</a>
                        </div>
                    </nav>
                </div>
            </div>
            <div id="header_search" class="position-relative d-flex justify-content-center align-items-center row"
                style="background-image: url('../assets/images/banner.jpg');
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center;
                    height: 90vh;">
                <!-- <div class="overlay position-absolute">
                </div> -->
                <form action="" class="col-6 row position-relative">
                    <input type="hidden" name="type">
                    <input type="text" name="key" placeholder="Nhập từ khóa tìm kiếm" class="col-12 pt-3 pl-5 pr-3 pb-3">
                    <a href="javascript:searchProduct()" class="link d-block position-absolute icon-search" style="border: none;background: #fff">
                        <img src="../assets/images/search.png" alt="">
                    </a>   
                </form>
            </div>
        </div>
        <div id="menu" class="content_session">
            <div class="container">
                <div class="menu_header">
                    <h2 class="title_content">Thực đơn của nhà hàng</h2>
                    <p class="sub_title_content">Thực đơn phong phú do chính tay đầu bếp giàu kinh nghiệm lựa chọn</p>
                    <div class="menu-buttons text-center">
                        <?php
                            for($i = 0 ; $i < sizeof($listCategory); ++$i){
                                $category = $listCategory[$i];
                                echo '<a href="javascript:getListProductByCategory('.$category->getId().')" class="btn btn-secondary" idCategory="'.$category->getId().'">'.$category->getName().'</a>';
                            }
                        ?> 
                    </div>
                </div>
                <div id="menu_main" class="pt-5">
                    <div class="menu_content">
                        <div class="menu-list row">
                            <?php
                                for($i = ($currentPage-1)*6; $i < min($productNumber, ($currentPage-1)*6 + 6);++$i){
                                    $product = $listProduct[$i];
                                    echo '<div class="menu-item col-4">
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
                            ?>
                        </div>
                    </div>
                    <nav class="d-flex justify-content-end mt-4">
                        <ul class="pagination">
                            <?php
                                if($currentPage > 1) echo '<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.($currentPage - 1).')"> Trang trước</a></li>';
                                $font = $back = 0;
                                for($i = 1; $i<=$pages; ++$i){
                                    if($i == $currentPage || $i == $currentPage -1 || $i == $currentPage + 1){
                                        echo '<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">'.$i.'</a></li>';
                                    }else if($font == 0 && $i < $currentPage){
                                        $font = 1;
                                        echo '<li class="page-item"><a class="page-link" href="javascript:getListProductByPage('.$i.')">...</a></li>';
                                    }else if($back == 0 && $i > $currentPage){
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
        </div>
        <div id="menu_popular" class="content_session bg-black">
            <div class="container">
                <h2 class="title_content text-light">Những món ăn truyền thống của nhà hàng</h2>
                <div class="menu-list row">
                    <?php
                        $listProduct = $pd->getListProduct(0);
                        for($i = 0; $i < 3; ++$i){
                            $product = $listProduct[$i];
                            echo '<div class="menu-item col-4">
                                    <div class="menu-item_content">
                                        <div class="food_img position-relative">
                                            <img src="'.$product->getImage().'" alt="">
                                            <div class="food_img_abs position-absolute">
                                                <a href="" class="btn btn-danger">Xem chi tiết</a>
                                            </div>
                                        </div>
                                        <div class="food_info pl-2 pr-2 mt-2">
                                            <p class="food_name">'.$product->getName().'</p>
                                            <p class="food_price_current text-danger">'.($product->getPrice()*0.95).'<span>đ</span></p>
                                        </div>
                                        <p class="food_price_old text-right pr-2">'.$product->getPrice().'<span>đ</span></p>
                                    </div>
                                </div>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div id="menu_delicous" class="content_session">
            <div class="container">
                <h2 class="title_content">Những món ăn mới trong thực đơn</h2>
                <div class="menu-list row">
                    <?php
                        $listProduct = $pd->getListProduct(0);
                        for($i = sizeof($listProduct) - 1; $i >= sizeof($listProduct) - 3; --$i){
                            $product = $listProduct[$i];
                            echo '<div class="menu-item col-4">
                                    <div class="menu-item_content">
                                        <div class="food_img position-relative">
                                            <img src="'.$product->getImage().'" alt="">
                                            <div class="food_img_abs position-absolute">
                                                <a href="" class="btn btn-danger">Xem chi tiết</a>
                                            </div>
                                        </div>
                                        <div class="food_info pl-2 pr-2 mt-2">
                                            <p class="food_name">'.$product->getName().'</p>
                                            <p class="food_price_current text-danger">'.($product->getPrice()*0.95).'<span>đ</span></p>
                                        </div>
                                        <p class="food_price_old text-right pr-2">'.$product->getPrice().'<span>đ</span></p>
                                    </div>
                                </div>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div id="about" class="content_session">
            <div class="container-fluid pl-0 pr-0">
                <h2 class="title_content">Nơi hội tụ những đầu bếp chuyên nghiệp</h2>
                <div id="chefCarousel" class="container pl-0 pr-0 mt-5 carousel slide position-relative" data-ride="carousel" style="height: 550px;">
                    <ol class="carousel-indicators">
                        <li data-target="#chefCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#chefCarousel" data-slide-to="1"></li>
                        <li data-target="#chefCarousel" data-slide-to="2"></li>
                        <li data-target="#chefCarousel" data-slide-to="3"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="../assets/images/chef1.jpg" class="d-block w-100" style="height: 550px;" alt="">
                            <div class="carousel-caption d-none d-md-block">
                                <h2 class="chef_name"></h2>
                                <p class="chef_maxim font-italic">"Món quà lớn nhất người đầu bếp có được là sự hài lòng của người ăn."</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="../assets/images/chef2.jpg" class="d-block w-100" style="height: 550px;" alt="">
                            <div class="carousel-caption d-none d-md-block">
                                <h2 class="chef_name"></h2>
                                <p class="chef_maxim font-italic">"Tôi thích nấu ăn vì mỗi ngày có một trải nghiệm khác nhau."</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="../assets/images/chef3.jpg" class="d-block w-100" style="height: 550px;" alt="">
                            <div class="carousel-caption d-none d-md-block">
                                <h2 class="chef_name"></h2>
                                <p class="chef_maxim font-italic">"Tôi là đầu bếp, một ngày làm đầu bếp, cả đời làm đầu bếp."</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="../assets/images/chef4.jpg" class="d-block w-100" style="height: 550px;" alt="">
                            <div class="carousel-caption d-none d-md-block">
                                <h2 class="chef_name"></h2>
                                <p class="chef_maxim font-italic">"Sự hài lòng của khách hàng là sự theo đuổi mãi mãi của chúng tôi."</p>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#chefCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </a>
                    <a class="carousel-control-next" href="#chefCarousel" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </a>
                    <!-- <div class="overlay position-absolute"></div> -->
                </div>
            </div>
        </div>
        <div id="comment" class="content_session">
            <div class="container">
                <h2 class="title_content">Nhận xét từ khách hàng</h2>
                <div class="menu-list row">
                    <div class="menu-item col-4">
                        <div class="menu-item_content">
                            <div class="customer_img text-center">
                                <img src="../assets/images/customer1.jpg" alt="">
                            </div>
                            <p class="customer_comment text-center">
                            Tôi rất thích các món hải sản được chế biến tại đây, vì chúng tất tươi và cách trang trí cũng rất đẹp mắt.
                            </p>
                        </div>
                    </div>
                    <div class="menu-item col-4">
                        <div class="menu-item_content">
                            <div class="customer_img text-center">
                                <img src="../assets/images/customer2.jpg" alt="">
                            </div>
                            <p class="customer_comment text-center">
                            Giá cả ở đấy rất phải chăng, hơn nữa thái độ phục vụ của nhân viên cũng rất nhiệt tình.Tôi thực sự thích nơi này.
                            </p>
                        </div>
                    </div>
                    <div class="menu-item col-4">
                        <div class="menu-item_content">
                            <div class="customer_img text-center">
                                <img src="../assets/images/customer3.jpg" alt="">
                            </div>
                            <p class="customer_comment text-center">
                            Tôi sẽ đến đây ăn thường xuyên cùng gia đình của tôi, đồ ăn ngon và view ở đây cũng rất đẹp.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="contact" class="content_session position-relative">
            <div class="overlay position-absolute">
            </div>
            <div class="container position-relative">
                <h2 class="title_content">Bản tin</h2>
                <p class="sub_title_content">Đăng ký nhận bản tin ngay để nhận được những thông tin ưu đãi sớm nhất</p>
                <div class="form_regist">
                    <form action="">
                        <div class="input-group row d-flex justify-content-center">
                            <input type="text" placeholder="Email của bạn" class="col-6">
                            <button class="btn btn-danger">Đăng ký</button>
                        </div>
                    </form>
                </div>
                <div class="contact_content row">
                    <div class="contact_time col-4">
                        <h2 class="title_content text-left">Thời gian</h2>
                        <div class="contact_time_list">
                            <div class="contact_time_item d-flex justify-content-between">
                                <span>Thứ 2</span>
                                <span>8:00 - 21:00</span>
                            </div>
                            <div class="contact_time_item d-flex justify-content-between">
                                <span>Thứ 3</span>
                                <span>8:00 - 21:00</span>
                            </div>
                            <div class="contact_time_item d-flex justify-content-between">
                                <span>Thứ 4</span>
                                <span>8:00 - 21:00</span>
                            </div>
                            <div class="contact_time_item d-flex justify-content-between">
                                <span>Thứ 5</span>
                                <span>8:00 - 21:00</span>
                            </div>
                        </div>
                    </div>
                    <div class="contact_social col-4">
                        <h2 class="title_content text-left">Liên hệ</h2>
                        <div class="contact_social_list">
                            <div class="contact_social_item">
                                <img src="../assets/images/home.png" alt="">
                                <span>Địa chỉ: phường Hoàng Liệt, Hoàng Mai, Hà Nội</span>
                            </div>
                            <div class="contact_social_item">
                                <img src="../assets/images/email.png" alt="">
                                <span>Email: dangthinhuquynh71@gmail.com</span>
                            </div>
                            <div class="contact_social_item">
                                <img src="../assets/images/phone-call.png" alt="">
                                <span>SĐT: 0123456789</span>
                            </div>
                        </div>
                    </div>
                    <div class="contact_comment col-4">
                        <h2 class="title_content text-left">Gửi liên hệ</h2>
                        <form action="">
                            <div class="d-flex justify-content-between ml-0 mr-0 mt-3">
                                <input type="text" placeholder="Tên của bạn" style="width: 48%;">
                                <input type="email" placeholder="Email của bạn" style="width: 48%;">
                            </div>
                            <div class="mt-3 w-100 ml-0 mr-0">
                                <textarea class="pt-2 pb-2 w-100" name="" id="" rows="6" placeholder="Lời nhắn của bạn"></textarea>
                            </div>
                            <div class="form-group justify-content-end mr-0">
                                <button class="btn btn-danger pl-5 pr-5 mt-4 ml-0 btn_send">Gửi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="login" class= "content_session position-fixed row <?=$active?>">
            <form action="index.php" method="POST" class="form_login position-relative col-4">
                <h2 class="form_header text-center">Đăng nhập</h2>
                <div class="form-group">
                    <label for="username">Tên tài khoản:</label>
                    <input type="text" name="username" value="<?=$username?>" placeholder="Tên tài khoản">
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" name="password" value="<?=$password?>" placeholder="Mật khẩu">
                </div>
                <p class="message-error">
                    <?=$message_error?>
                </p>
                <div class="form-group form-submit">
                    <a href="javascript:btnLoginOnclick()" class="btn btn-secondary">Thoát</a>
                    <input type="submit" value="Đăng nhập" class="btn btn-danger">
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        const loginSession = document.querySelector('#login');
        const btnLogin = document.querySelector('#btn-login');

        function btnLoginOnclick(){
            loginSession.classList.toggle('active');
        };

        var xmlHttpRequest = new XMLHttpRequest();
        var mainMenu = document.querySelector("#menu_main");
        function searchProduct(){
            var key = document.querySelector("input[name='key']").value;
            if(key == ''){
                getListProduct();
            }else{
                if(isNaN(key)){
                    document.querySelector("input[name='type']").value = key;
                    xmlHttpRequest.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            mainMenu.innerHTML = this.responseText;
                        }
                    }
                    xmlHttpRequest.open("GET","../controll/indexCtr.php?key="+key,true);
                    xmlHttpRequest.send();
                }else getListProductByCategory(key);
            }
        }

        function getListProduct(){
            document.querySelector("input[name='type']").value = '';
            xmlHttpRequest.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    mainMenu.innerHTML = this.responseText;
                }
            }
            xmlHttpRequest.open("GET","../controll/indexCtr.php", true);
            xmlHttpRequest.send();
        }

        function getListProductByCategory(idCategory){
            document.querySelector("input[name='type']").value = idCategory;
            var listBtnCategory = document.querySelectorAll("[idCategory]");
            listBtnCategory.forEach(function(btnCategory){
                btnCategory.classList.remove("active");
            })
            document.querySelector("[idCategory='" + idCategory + "']").classList.add("active");
            xmlHttpRequest.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    mainMenu.innerHTML = this.responseText;
                }
            }
            xmlHttpRequest.open("GET","../controll/indexCtr.php?idCurrentCategory="+idCategory, true);
            xmlHttpRequest.send();
        }

        function getListProductByPage(page){
            var type = document.querySelector("input[name='type']").value;
            xmlHttpRequest.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    mainMenu.innerHTML = this.responseText;
                }
            }
            if(!isNaN(type)){
                xmlHttpRequest.open("GET","../controll/indexCtr.php?idCurrentCategory=" + type + "&currentPage=" + page, true);
            }else if(type != ''){
                xmlHttpRequest.open("GET","../controll/indexCtr.php?key=" + type + "&currentPage=" + page, true);
            }else{
                xmlHttpRequest.open("GET","../controll/indexCtr.php?currentPage=" + page, true);
            }
            xmlHttpRequest.send();
        }
    </script>
</body>
</html>
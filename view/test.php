<?php
include '../controll/DAO.php';
include '../controll/CategoryDAO.php';

$key = '';
$cd = new CategoryDAO();
$listCategory = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="">
        <input type="hidden" name="type">
        <input type="text" name="key">
        <a href="javascript:searchProduct()" class="btn">Search</a>
        <a href="javascript:getListProduct()" class="btn">Lấy danh sách sản phẩm</a>
    </form>
    <div id="result">
    </div>
    <?php echo $key?>
    <script>
        function searchProduct(){
            var key = document.querySelector('input[name="key"]').value;
            var result = document.querySelector('#result');
            if(key == ''){
                result.innerHTML = 'Khong co san pham nao!';
            }else{
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        result.innerHTML = this.responseText;
                    }
                };
                xmlHttp.open('GET', "testDAO.php?key="+key, true);
                xmlHttp.send();
            }
        }
    </script>
</body>
</html>
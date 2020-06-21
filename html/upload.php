<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>上传</title>
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <link href="../css/upload.css" rel="stylesheet" type="text/css">
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        $(function () {
            var url = '../php/dbconnect.php'; //后台地址
            $("#currentcountry").change(function () { //监听下拉列表的change事件
                var address = $(this).val(); //获取下拉列表选中的值
                //发送一个post请求
                $.ajax({
                    type: 'post',
                    url: url,
                    data: {key: address},
                    dataType: 'text',
                    success: function (data) {
                        //请求成功回调函数
                        var option = '<option value="">请选择城市</option>'; //默认值
                        if (data != null) {
                            data1 = data.substring(2, data.length - 3);
                            var address = data1.split("\",\"");
                            for (var i = 0; i < address.length; i++) { //循环获取返回值，并组装成html代码
                                option += '<option value="' + address[i] + '">' + address[i] + '</option>';
                            }
                        }
                        $("#currentcity").html(option); //js刷新第二个下拉框的值
                    },
                });
            });
        });//二级联动回调
    </script>
    <?php
    require_once("../php/config.php");
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $sqlcountries = "SELECT * FROM `geocountries` ORDER BY `geocountries`.`Population` DESC";
    $statecountries = $pdo->prepare($sqlcountries);
    $statecountries->execute();
    $resultcountries = $statecountries->fetchAll();
    $countryNum = $statecountries->rowCount();
    ?>
</head>
<body>
<header>
    <ul class="header">
        <li class="left"><a href="../index.php">首页</a></li>
        <li class="left"><a href="browse.php">浏览页</a></li>
        <li class="left"><a href="search.php">搜索页</a></li>
        <li style="float: right">
            <?php
            if (isset($_COOKIE["username"])) {
                echo '<div class="dropdown">
                    <button class="dropdownbtn  regBtn">个人中心</button>
                    <div class="dropdown-content">
                        <a href="upload.php"><img src="../images/上传.png" /><p>上传</p></a>
                        <a href="myPhoto.php"><img src="../images/我的照片.png"/><p>我的照片</p></a>
                        <a href="myCollection.php"><img src="../images/我的收藏.png"/><p>我的收藏</p></a>
                        <a href="login.php"><img src="../images/登录.png"/><p>登出</p></a>
                    </div> 
                    </div>';
            } else {
                echo ' <button onclick="window.location.href=\'login.php\';" class="regBtn">登录</button>';
            }
            ?>
        </li>
    </ul>
</header>
<h2>上传</h2>
<div class="main">
    <form action="../php/uploadInf.php"  method="POST" enctype="multipart/form-data">
        <input name="imgsrc" required id="imgsrc" value="0" style="height: 0;padding:0;overflow: hidden">
        <input name="imgid" required value="<?php echo (isset($_COOKIE['facpicid'])) ? $_COOKIE['facpicid'] : '0' ?>"
               style="height: 0;padding:0;overflow: hidden">
        <ul>
            <?php
            if (isset($_COOKIE['facpicid'])) {
                $sql1 = "SELECT * FROM travelimage where imageid ='" . $_COOKIE['facpicid'] . "'";
                $statement1 = $pdo->prepare($sql1);
                $statement1->execute();
                $result1 = $statement1->fetchAll();
                echo '
            <li class="upImg" >
                <img  id = "img" src = "../travel-images/large/' . $result1[0][8] . '" >
            <li ><p > 图片标题</p >
                <input name = "title" value="' . $result1[0][1] . '" required >
            </li >
            <li ><p > 图片描述</p >
                <input name = "detail"value="' . $result1[0][2] . '" required >
            </li >';
            } else {
                echo '
            <li class="upImg" >
                <img  id = "imgPre" src = "" >
                <input type="file" class="file" name="image" onchange="previewFile(this);"/>
            </li >
            <li ><p > 图片标题</p >
                <input name = "title" required >
            </li >
            <li ><p > 图片描述</p >
                <input name = "detail" required >
            </li > ';
            }
            ?>
            <li><p>拍摄国家</p>
                <select name="country" id="currentcountry">
                    <option value="0">--国家--</option>
                    <?php
                    for ($i = 0; $i < $countryNum; $i++) {
                        global $resultcountries;
                        echo '<option value="' . $resultcountries[$i][0] . '">' . $resultcountries[$i][4] . '</option>';
                        //添加国家
                    }
                    ?>
                </select>
            </li>
            <li><p>拍摄城市</p>
                <select name="city" id="currentcity">
                    <option value="0">--城市--</option>
                </select>
            </li>
            <li><p>图片类型</p>
                <select name="feature">
                    <option value="scenery">Scenery</option>
                    <option value="city">City</option>
                    <option value="people">People</option>
                    <option value="animal">Animal</option>
                    <option value="building">Building</option>
                    <option value="wonder">Wonder</option>
                    <option value="other">Other</option>
                </select>

            </li>
        </ul>
        <input class="submitbtn" type="submit" value="<?php echo (isset($_COOKIE['facpicid'])) ? '修改' : '上传' ?>">
    </form>
</div>
<script>
//     function upload() {
//         alert("1");
//         var flag = document.cookie.indexOf("uploadimgid=");
//         if (flag != -1) {
//             document.cookie = "uploadimgid=' '; expires=-1; path=/PJ2/";
//             return true;
//         } else {
//             alert("2");
//             var src = document.getElementById(img).src;
//             document.getElementById('imgsrc').value = src;
//         }
//         return true;
//     }
//     function move_file($fileFolder, $newPath, $reNameflag = false) {
// //1、首先先读取文件夹
//         $temp = @scandir($fileFolder);
// //遍历文件夹
//         foreach($temp as $v)
//         {
//             $a = $fileFolder.
//             '/'.$v;
//             if (is_dir($a)) {//如果是文件夹则执行
//                 echo
//                 "<font color='red'>$a</font>", "<br/>"; //把文件夹红名输出
// //因为是文件夹所以再次调用自己这个函数，把这个文件夹下的文件遍历出来
//                 move_file($a, $newPath, $reNameflag);
//             } else {
// //echo $v,"<br/>";
//                 $newName = $v;
//                 if ($reNameflag) {
//                     $newName = uniqid().
//                     '.'.explode('.', $v)[1];
//                 }
//                 echo
//                 "已完成--", $newPath.
//                 '/'.$newName, "<br/>";
//                 copy($a, $newPath.
//                 '/'.$newName
//             )
//                 ;
//             }
//         }
//     }
// $("input[type='file']").change(function(){
//     var file = this.files[0];
//     if (window.FileReader) {
//         var reader = new FileReader();
//         reader.readAsDataURL(file);
//         //监听文件读取结束后事件
//         reader.onloadend = function (e) {
//             $(".img").attr("src",e.target.result);
//         };
//     }
// });
// <form action='xx.php' method='post' enctype='multipart/form-data'><input type='' name='' /><input type='button' name='btn' /></form>
</script>
<script>
    /**
     * 显示选择上传的图片略缩图
     * 当选择了图片文件时触发这个方法
     */
    /*实现图片预览*/
    function previewFile(imgFile) {
        /*允许下列文件格式*/
        var pattern = /(\.*.jpg$)|(\.*.JPG$)|(\.*.png$)|(\.*.PNG$)|(\.*.jpeg$)|(\.*.JPEG$)|(\.*.gif$)|(\.*.GIF$)/;
        if (!pattern.test(imgFile.value)) {
            alert("系统仅支持jpg/jpeg/png/gif格式的照片！");
        }
        else
        {
            var path;
            path = URL.createObjectURL(imgFile.files[0]);
            var filename1=(imgFile.value).substring(imgFile.value.lastIndexOf('\\')+1);
            document.getElementById('imgsrc').value=filename1;
            document.getElementById("imgPre").src=path;
        }
    }
</script>
<footer>
    <p>2020.4|备案号：19302010051|作者：YiXiuD</p>
    <p>如有疑问请联系作者：q1758324883</p>
</footer>
</body>
</html>
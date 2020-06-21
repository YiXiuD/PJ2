<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我的照片</title>
    <link href="../css/myPhoto.css" rel="stylesheet" type="text/css">
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <script>
        function throwinf(id) {
            var pic = document.getElementById(id);
            var picpresrc = pic.src + '';
            var picsrc = picpresrc.replace("square-medium", "large");
            var expTime = new Date().getTime() + 60 * 60 * 10;
            document.cookie = "picSrc=" + picsrc + "; expires=" + expTime + "; path=/PJ2/";
            document.cookie = "picId=" + id + "; expires=" + expTime + "; path=/PJ2/";
            window.location.href = "pictureDetail.php";
        }

        function delpic(id) {
            var expTime = new Date().getTime() + 60 * 60 * 10;
            document.cookie = "delpicid=" + id + "; expires=" + expTime + "; path=/PJ2/";
            window.location.href = "../php/deletePhoto.php";
        }
        function factorpic(id) {
            var expTime = new Date().getTime() + 60 * 60 * 10;
            document.cookie = "facpicid=" + id + "; expires=" + expTime + "; path=/PJ2/";
            window.location.href = "upload.php";
        }
    </script>
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
<div class="main">
    <h2>我的照片</h2>
    <div class="content">
        <div id="wrap" class="wrap" style="top: 0">
            <ul class="myCollection">
                <?php
                setcookie("facpicid",'',-1,'/PJ2/');
                require_once("../php/config.php");
                $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                $sql = "SELECT uid FROM traveluser WHERE username='" . $_COOKIE['username'] . "'";
                $statement = $pdo->prepare($sql);
                $statement->execute();//用户信息
                $result = $statement->fetchAll();
                $sql1 = "SELECT * FROM travelimage WHERE uid=" . $result[0][0];
                $statement1 = $pdo->prepare($sql1);
                $statement1->execute();
                if ($statement1->rowCount() < 1)
                    echo "你未发布任何照片！";
                else {
                    $result1 = $statement1->fetchAll();
                    for ($i = 0; $i < sizeof($result1); $i++) {
                        echo '<li><div class="image"onclick="throwinf(' . $result1[$i][0] . ')">
              <img id="' . $result1[$i][0] . '" src="../travel-images/square-medium/' . $result1[$i][8] . '" >
              <h5>' . $result1[$i][1] . '</h5>
              <p>' . $result1[$i][2] . '</p>
              </div>
              <input type="button" value="编辑" onclick=factorpic(' . $result1[$i][0] . ');>
              <input type="button" value="删除" onclick=delpic(' . $result1[$i][0] . ');>
        </li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
    if (sizeof($result1) > 4) {
        $aclpage = ceil(sizeof($result1) / 4);
        $pagenum = ($aclpage > 5) ? 5 : $aclpage;
        echo '<div class="changePage"><button onclick="press_prev(' . $pagenum . ');">↑</button>';
        echo '<button id="btn1" onclick="jumpTo(1)" class="on">1</button>';
        for ($i = 2; $i <= $pagenum; $i++) {
            echo '<button id="btn' . $i . '" onclick="jumpTo(' . $i . ')">' . $i . '</button>';
        }
        echo '<button onclick="press_next(' . $pagenum . ');">↓</button> </div>';
    }
    ?>
</div>


<footer>
    <p>2020.4|备案号：19302010051|作者：YiXiuD</p>
    <p>如有疑问请联系作者：q1758324883</p>
</footer>
</body>
</html>
<script>
    var wrap = document.getElementById("wrap");
    var index = 1;

    function press_prev(num) {
        document.getElementById("btn" + index).classList.remove("on");
        index--;
        if (index == 0) {
            index = num;
        }
        document.getElementById("btn" + index).setAttribute("class", "on");
            wrap.style.top = (696 - (index * 696)) + "px";
    }

    function press_next(num) {
        document.getElementById("btn" + index).classList.remove("on");
        index++;
        if (index == (num + 1)) {
            index = 1;
        }
        document.getElementById("btn" + index).setAttribute("class", "on");
            wrap.style.top = (696 - (index * 696)) + "px";
    }

    function jumpTo(i) {
        document.getElementById("btn" + index).classList.remove("on");
        index = i;
        document.getElementById("btn" + index).setAttribute("class", "on");
            wrap.style.top = (696 - (index * 696)) + "px";
    }
</script>
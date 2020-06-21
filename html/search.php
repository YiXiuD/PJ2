<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>搜索</title>
    <link href="../css/search.css" rel="stylesheet" type="text/css">
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<header>
    <ul class="header">
        <li class="left"><a href="../index.php">首页</a></li>
        <li class="left"><a href="../html/browse.php">浏览页</a></li>
        <li class="left"><a href="../html/search.php" style="background-color: rgba(146,188,212,0.56);">搜索页</a></li>
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
    <div class="search">
        <form id="search" action="../php/searchsql.php" method="post">
            <div class="searchType">
                <input checked="checked" name="searchType" value='title' type="radio">
                <p>标题筛选</p>
                <input name="searchType" value="decription" type="radio">
                <p>描述筛选</p>
            </div>
            <div class="searchContent">
                <input placeholder="资源搜索" name='content' type="text" required>
                <input onclick="" type="submit" value="搜索">
            </div>
        </form>
    </div>
    <div class="content">
        <div id="wrap" class="wrap" style="top: 0">
            <ul class="searchResult" id="sechresult">
                <?php
                require_once("../php/config.php");
                $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                if (!isset($_COOKIE['searchresult'])) {
                    echo '无匹配图片';
                } else {
                    $data = $_COOKIE['searchresult'];
                    $data1 = substr($data, 2, -2);
                    $address = explode("\",\"", $data1);
                    for ($i = 0; $i < sizeof($address); $i++) {
                        $sql = "SELECT ImageID,Title,Description,PATH FROM travelimage WHERE ImageID=" . $address[$i];
                        $statement = $pdo->prepare($sql);
                        $statement->execute();
                        if ($statement->rowCount() > 0) {
                            $result[$i] = $statement->fetchAll();
                            echo '<li>
              <div class="image"onclick="throwinf(' . $result[$i][0][0] . ')">
              <img id="' . $result[$i][0][0] . '" src="../travel-images/square-medium/' . $result[$i][0][3] . '" ></div>
              <h>' . $result[$i][0][1] . '</h>
              <p>' . $result[$i][0][2] . '</p>
              </li>';
                        }
                    }
                }
                ?>
                <script>
                    function throwinf(imid) {
                        var pic = document.getElementById(imid);
                        var picpresrc = pic.src + '';
                        var picsrc = picpresrc.replace("square-medium", "large");
                        var expTime = new Date().getTime() + 60 * 60 * 10;
                        document.cookie = "picSrc=" + picsrc + "; expires=" + expTime + "; path=/PJ2/";
                        document.cookie = "picId=" + imid + "; expires=" + expTime + "; path=/PJ2/";
                        window.location.href = "../html/pictureDetail.php";
                    }
                </script>
            </ul>
        </div>
        <?php
        if(isset($_COOKIE['searchresult'])) {
            if (sizeof($address) > 5) {
                $aclpage = ceil(sizeof($address) / 5);
                $pagenum = ($aclpage > 5) ? 5 : $aclpage;
                echo '<div class="changePage"><button onclick="press_prev(' . $pagenum . ');">↑</button>';
                echo '<button id="btn1" onclick="jumpTo(1)" class="on">1</button>';
                for ($i = 2; $i <= $pagenum; $i++) {
                    echo '<button id="btn' . $i . '" onclick="jumpTo(' . $i . ')">' . $i . '</button>';
                }
                echo '<button onclick="press_next(' . $pagenum . ');">↓</button> </div>';
            }
        }
        ?>
    </div>
</div>
<footer>
    <p>2020.4|备案号：19302010051|作者：YiXiuD</p>
    <p>如有疑问请联系作者：q1758324883</p>
</footer>
</body>
</html>
<script>
    var wrap=document.getElementById("wrap");
    var index = 1;
    function press_prev(num) {
        document.getElementById("btn" + index).classList.remove("on");
        index--;
        if (index == 0) {
            index = num;
        }
        document.getElementById("btn" + index).setAttribute("class", "on");
        wrap.style.top = (770-(index * 770)) + "px";
    }

    function press_next(num) {
        document.getElementById("btn" + index).classList.remove("on");
        index++;
        if (index == (num+1)) {
            index = 1;
        }
        document.getElementById("btn" + index).setAttribute("class", "on");
        wrap.style.top = (770-(index * 770)) + "px";
    }
    function jumpTo(i) {
        document.getElementById("btn"+index).classList.remove("on");
        index=i;
        document.getElementById("btn"+index).setAttribute("class","on");
            wrap.style.top = (770-(index * 770)) + "px";
    }
</script>



































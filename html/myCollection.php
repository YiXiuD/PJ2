<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>收藏</title>
    <link href="../css/myCollection.css" rel="stylesheet" type="text/css">
    <link href="../css/reset.css" rel="stylesheet" type="text/css">

    <?php
    require_once("../php/config.php");
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    ?>
</head>
<body>
<header>
    <ul class="header">
        <li class="left"><a href="../index.php">首页</a></li>
        <li class="left"><a href="../html/browse.php">浏览页</a></li>
        <li class="left"><a href="../html/search.php">搜索页</a></li>
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
    <h2>我的收藏</h2>
    <div class="content">
        <div id="wrap" class="wrap" style="top: 0">
        <ul class="myCollection" >
            <script>
                function clctout(imgid) {
                    var expTime = new Date().getTime() + 60 * 60 * 10;
                    document.cookie = "fvimid=" + imgid + "; expires=" + expTime + "; path=/PJ2/";
                    window.location.href = '../php/clctout.php';
                }
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
            <?php
            $sql = "SELECT uid FROM traveluser WHERE username='" . $_COOKIE['username'] . "'";
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $uid = $statement->fetchAll()[0][0];
            $sql1 = "SELECT ImageID FROM travelimagefavor WHERE UID=" . $uid;
            $statement1 = $pdo->prepare($sql1);
            $statement1->execute();
            if ($statement1->rowCount() < 1)
                echo '未收藏任何图片';
            else {
                $result1 = $statement1->fetchAll();
                for ($i = 0; $i < sizeof($result1); $i++) {
                    $sql2 = "SELECT ImageID,Title,Description,PATH FROM travelimage WHERE ImageID=" . $result1[$i][0];
                    $statement2 = $pdo->prepare($sql2);
                    $statement2->execute();
                    $result2[$i] = $statement2->fetchAll();
                    echo '<li>
              <div class="image"onclick="throwinf(' . $result2[$i][0][0] . ')">
              <img id="' . $result2[$i][0][0] . '" src="../travel-images/square-medium/' . $result2[$i][0][3] . '" ></div>
              <h5>' . $result2[$i][0][1] . '</h5>
              <p>' . $result2[$i][0][2] . '</p>
              <input type="button" value="取消收藏" onclick="clctout(' . $result2[$i][0][0] . ');">
              </li>';
                }
            }
            ?>
        </ul>
        </div>
    </div>
    <?php
    if(sizeof($result1)>4){
        $aclpage=ceil(sizeof($result1)/4);
        $pagenum=($aclpage>5)?5:$aclpage;
        echo '<div class="changePage"><button onclick="press_prev('.$pagenum.');">↑</button>';
        echo '<button id="btn1" onclick="jumpTo(1)" class="on">1</button>';
        for($i=2;$i<=$pagenum;$i++){
            echo '<button id="btn'.$i.'" onclick="jumpTo('.$i.')">'.$i.'</button>';
        }
        echo '<button onclick="press_next('.$pagenum.');">↓</button> </div>';
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
    var wrap=document.getElementById("wrap");
    var index = 1;
    function press_prev(num) {
        document.getElementById("btn" + index).classList.remove("on");
        index--;
        if (index == 0) {
            index = num;
        }
        document.getElementById("btn" + index).setAttribute("class", "on");
        if(wrap)
            wrap.style.top = (748-(index * 748)) + "px";
        else
            alert('wu');
    }

    function press_next(num) {
        document.getElementById("btn" + index).classList.remove("on");
        index++;
        if (index == (num+1)) {
            index = 1;
        }
        document.getElementById("btn" + index).setAttribute("class", "on");
        if(wrap)
            wrap.style.top = (748-(index * 748)) + "px";
        else
            alert('wu');
    }
    function jumpTo(i) {
        document.getElementById("btn"+index).classList.remove("on");
        index=i;
        document.getElementById("btn"+index).setAttribute("class","on");
        if(wrap)
            wrap.style.top = (748-(index * 748)) + "px";
        else
            alert('wu');
    }
</script>
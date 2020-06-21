<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <link href="css/reset.css" rel="stylesheet" type="text/css">
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <script type="application/javascript">
        function refresh() {
            location.reload();
        }
        function backTop() {
            scrollTo(0, 0);
        }
    </script>
</head>
<body>
<header>
    <ul class="header">
        <li class="left"><a href="index.php" style="background-color: rgba(146,188,212,0.56);">首页</a></li>
        <li class="left"><a href="html/browse.php">浏览页</a></li>
        <li class="left"><a href="html/search.php">搜索页</a></li>
        <li style="float: right">
            <?php
            if (isset($_COOKIE['username'])) {
                echo '<div class="dropdown">
                    <button class="dropdownbtn  regBtn">个人中心</button>
                    <div class="dropdown-content">
                        <a href="html/upload.php"><img src="images/上传.png" /><p>上传</p></a>
                        <a href="html/myPhoto.php"><img src="images/我的照片.png"/><p>我的照片</p></a>
                        <a href="html/myCollection.php"><img src="images/我的收藏.png"/><p>我的收藏</p></a>
                        <a href="php/loginout.php"><img src="images/登录.png"/><p>登出</p></a>
                    </div> 
                    </div>';
            } else {
                echo ' <button onclick="window.location.href=\'html/login.php\';" class="regBtn">登录</button>';
            }
            ?>
        </li>
    </ul>
    <?php
    require_once("php/config.php");
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $sql1 = "SELECT * FROM travelimage";
    $statement1 = $pdo->prepare($sql1);
    $statement1->execute();
    $imgNum = $statement1->rowCount();
    $imgId = 0;
    $maxNum = 0;
    for ($i = 1; $i <= $imgNum; $i++) {
        global $imgId, $maxNum;
        $sql = "SELECT * FROM travelimagefavor WHERE ImageID= $i";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        if ($statement->rowCount() > $maxNum) {
            $maxNum = $statement->rowCount();
            $imgId = $i;
        }
    }
    $sql2 = "SELECT ImageID,PATH FROM travelimage WHERE ImageID=$imgId";
    $statement2 = $pdo->prepare($sql2);
    $statement2->execute();
    $result2= $statement2->fetchAll();
    echo '<img src="travel-images/large/' . $result2[0][1] . '" class="headp">';
    ?>
    <!--    收藏最多的图片-->
</header>
<ul class="image">
    <script>
        function throwinf(imid) {
            var pic=document.getElementById(imid);
                var picpresrc = pic.src + '';
                var picsrc = picpresrc.replace("square-medium", "large");
                var expTime = new Date().getTime() + 60 * 60 * 10;
                document.cookie = "picSrc=" + picsrc + "; expires=" + expTime + "; path=/PJ2/";
                document.cookie = "picId=" + imid + "; expires=" + expTime + "; path=/PJ2/";
                window.location.href = "html/pictureDetail.php";
        }
    </script>
    <?php
    for($i=0;$i<9;$i++){
        $num=mt_rand(1, $imgNum/2);
        $sql = "SELECT ImageID,Title,Description,PATH FROM travelimage WHERE ImageID=".$num;
        $statement= $pdo->prepare($sql);
        $statement->execute();
        $result[$i] = $statement->fetchAll();
        echo '<li>
              <div class="image"onclick="throwinf('.$result[$i][0][0].')">
              <img id="'.$result[$i][0][0].'" src="travel-images/square-medium/'.$result[$i][0][3].'" >
              <h5>'.$result[$i][0][1].'</h5>
              <p>'.$result[$i][0][2].'</p>
              </div>
              </li>';
        }
    ?>
<!--    -->
</ul>
<div class="assist">
    <img src="images/回顶.png" onclick="backTop()"/>
    <img src="images/刷新.png" onclick="refresh()"/>
</div>
<footer>
    <p>2020.4|备案号：19302010051|作者：YiXiuD</p>
    <p>如有疑问请联系作者：q1758324883</p>
</footer>
</body>
</html>
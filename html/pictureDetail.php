<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>图片详情</title>
    <link href="../css/pictureDetail.css" rel="stylesheet" type="text/css">
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script>
        function changeCollect(i, imgid) {
            if (i == 0)
                alert("登录后即可收藏！");
            else {
                var iscollected = (i == 1) ? 'col' : 'uncol' ;
                var expTime = new Date().getTime() + 60 * 60 * 10;
                document.cookie = "fvimid=" + imgid + "; expires=" + expTime + "; path=/PJ2/";
                document.cookie = "isclctd=" + iscollected + "; expires=" + expTime + "; path=/PJ2/";
                window.location.href='../php/collection.php';
            }
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
<h2>图片详情</h2>

<div class="main">
    <?php
    require_once("../php/config.php");
    if (!((isset($_COOKIE['picId'])) && (isset($_COOKIE['picSrc'])))) {
        echo '<script>console.log(' . isset($_COOKIE['picId']) . ');</script>';
        echo '<script>console.log(' . isset($_COOKIE['picSrc']) . ');</script>';

//        echo '<script>alert("无法获取图片完整信息！");window.history(-2);</script>';
    } else {
        $imid = $_COOKIE['picId'];
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $sql1 = "SELECT ImageID,Title,UID,Description,CountryCodeISO,citycode,feature FROM travelimage WHERE ImageID=" . $imid;
        $statement1 = $pdo->prepare($sql1);
        $statement1->execute();//图片信息
        $result1 = $statement1->fetchAll();
        $sql2 = "SELECT username FROM traveluser WHERE UID=" . $result1[0][2];
        $statement2 = $pdo->prepare($sql2);
        $statement2->execute();//作者信息
        $result2 = $statement2->fetchAll();
        $sql3 = "SELECT * FROM geocountries WHERE ISO='" . $result1[0][4] . "'";
        $statement3 = $pdo->prepare($sql3);
        $statement3->execute();//国家信息
        if($statement3->rowCount()>0)
            $result3 = $statement3->fetchAll();
        else
            $result3[0][4]="unknown";
        $sql4 = "SELECT * FROM geocities WHERE geonameid='" . $result1[0][5] . "'";
        $statement4 = $pdo->prepare($sql4);
        $statement4->execute();//城市信息
        if($statement4->rowCount()>0)
            $result4 = $statement4->fetchAll();
        else
            $result4[0][1]="unknown";
        $sql5 = "SELECT * FROM travelimagefavor WHERE imageid=" . $result1[0][0];
        $statement5 = $pdo->prepare($sql5);
        $statement5->execute();//收藏信息
        $result5 = $statement5->rowCount();
        $result6[0] = "+收藏";
        $result6[1] = 0;
        if (isset($_COOKIE['username'])) {
            global $result1, $result6;
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            $sql = "SELECT uid FROM traveluser WHERE username='".$_COOKIE['username']."'";
            $statement = $pdo->prepare($sql);
            $statement->execute();//用户信息
            $result = $statement->fetchAll();
            $sql1 = "SELECT * FROM travelimagefavor WHERE uid=".$result[0][0]." and imageid=".$result1[0][0];
            $statement1 = $pdo->prepare($sql1);
            $statement1->execute();
            $result6[0] = ($statement1->rowCount() > 0) ? "已收藏" : "+收藏";
            $result6[1] = ($statement1->rowCount() > 0) ? "1" : "2";
        }
        echo '<h3>' . $result1[0][1] . '</h3>
    <table class="main">
        <tr class="main">
        <td class="main"><img src="' . $_COOKIE['picSrc'] . '"/></td>
        <td><table class="detail">
            <tr>
                <td>作者</td>
                <td>' . $result2[0][0] . '</td>
            </tr>
            <tr>
                <td>主题</td>
                <td>' . $result1[0][6] . '</td>
            </tr>
            <tr>
                <td>拍摄国家</td>
                <td>' . $result3[0][4] . '</td>
            </tr>
            <tr>
                <td>拍摄城市</td>
                <td>' . $result4[0][1] . '</td>
            </tr>
            <tr>
                <td>收藏人数</td>
                <td>' . $result5 . '</td>
            </tr>
        </table></td>
        </tr>
        <tr>
            <td>
                <p><strong>详情：</strong>' . $result1[0][3] . '</p>
            </td>
            <td>
                <input id="cltbtn" type="button" value="' . $result6[0] . '" onclick="changeCollect('.$result6[1] .','.$result1[0][0].');">
            </td>
        </tr>
    </table>';
    }
    ?>
</div>

<footer>
    <p>2020.4|备案号：19302010051|作者：YiXiuD</p>
    <p>如有疑问请联系作者：q1758324883</p>
</footer>
</body>
</html>
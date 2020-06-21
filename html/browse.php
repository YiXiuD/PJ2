<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>浏览</title>
    <link href="../css/browse.css" rel="stylesheet" type="text/css">
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="application/javascript">
        $(function(){
            //初始化数据
            var url ='../php/dbconnect.php'; //后台地址
            $("#currentcountry").change(function(){ //监听下拉列表的change事件
                var address = $(this).val(); //获取下拉列表选中的值
                //发送一个post请求
                $.ajax({
                    type:'post',
                    url:url,
                    data:{key:address},
                    dataType:'text',
                    success:function(data){
                        //请求成功回调函数
                        var option = '<option value="0">请选择城市</option>'; //默认值
                        if(data!=null) {
                            data1 = data.substring(2, data.length - 3);
                            var address = data1.split("\",\"");
                            for (var i = 0; i < address.length; i++) { //循环获取返回值，并组装成html代码
                                option += '<option value="'+address[i]+'">' + address[i] + '</option>';
                            }
                        }
                        $("#currentcity").html(option); //js刷新第二个下拉框的值
                    },
                });
            });
        });
        </script>

</head>
<body>
<header>
    <ul class="header">
        <li class="left"><a href="../index.php">首页</a></li>
        <li class="left"><a href="browse.php" style="background-color: rgba(146,188,212,0.56);">浏览页</a></li>
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
<ul class="aside">
    <li class="hotSech">
        <form name="form1" method="post" action="../php/brsearch.php">
        <input type="text" name="content" required placeholder="资源搜索">
        <input type="submit" value="搜索">
    </li>
    <li class="hotExp">
        <p> 热门国家</p>
        <ul>
            <?php
            require_once("../php/config.php");
            $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            $sqlcountries = "SELECT * FROM `geocountries` ORDER BY `geocountries`.`Population` DESC";
            $statecountries = $pdo->prepare($sqlcountries);
            $statecountries->execute();
            $resultcountries = $statecountries->fetchAll();
            $countryNum = $statecountries->rowCount();
            for ($i = 1; $i <= 8; $i++) {
                global $countryNum, $resultcountries;
                $num = mt_rand(0, $countryNum / 3);
                echo '<li onclick=hotcountry("'.$resultcountries[$num][0].'");>' . $resultcountries[$num][4] . '</li>';
            }
            ?>
        </ul>
    </li>
    <li class="hotExp">
        <p> 热门城市</p>
        <ul>
            <?php
            $sqlcities = "SELECT * FROM `geocities` ORDER BY `geocities`.`Population` DESC";
            $statecities = $pdo->prepare($sqlcities);
            $statecities->execute();
            $resultcities = $statecities->fetchAll();
            $cityNum = $statecities->rowCount();
            for ($i = 1; $i <= 8; $i++) {
                global $cityNum, $resultcities;
                $num = mt_rand(0, $cityNum / 3);
                echo '<li onclick=hotcity("'.$resultcities[$num][0].'");>' . $resultcities[$num][1] . '</li>';
            }
            ?>
        </ul>
    </li>
    <li class="hotExp">
        <p> 热门内容</p>
        <ul>
            <?php
            $sqlcontinent = "SELECT * FROM geocontinents";
            $statecontinent = $pdo->prepare($sqlcontinent);
            $statecontinent->execute();
            $resultcontinent = $statecontinent->fetchAll();
            $continentNum = $statecontinent->rowCount();
            for ($i = 1; $i <= 8; $i++) {
                global $countryNum, $resultcontinent;
                $num = mt_rand(0, $continentNum - 1);
                echo '<li onclick=hotcontent("'.$resultcontinent[$num][0].'");>' . $resultcontinent[$num][1] . '</li>';
            }
            ?>
        </ul>
    </li>
    <script>
        function hotcountry(id) {
            var expTime = new Date().getTime() + 60 * 60 * 10;
            document.cookie = "hottype=country; expires=" + expTime + "; path=/PJ2/";
            document.cookie = "hotid=" + id + "; expires=" + expTime + "; path=/PJ2/";
            window.location.href = "../php/hotsearch.php";
        }
        function hotcity(id) {
            var expTime = new Date().getTime() + 60 * 60 * 10;
            document.cookie = "hottype=city; expires=" + expTime + "; path=/PJ2/";
            document.cookie = "hotid=" + id + "; expires=" + expTime + "; path=/PJ2/";
            window.location.href = "../php/hotsearch.php";
        }
        function hotcontent(id) {
            var expTime = new Date().getTime() + 60 * 60 * 10;
            document.cookie = "hottype=content; expires=" + expTime + "; path=/PJ2/";
            document.cookie = "hotid=" + id + "; expires=" + expTime + "; path=/PJ2/";
            window.location.href = "../php/hotsearch.php";
        }
    </script>
</ul>
<div class="main">
    <div class="selections">
        <form name="form2">
            <select name="country" id="currentcountry" required>
                <option value="0">--国家--</option>
                <?php
                for ($i = 0; $i < $countryNum; $i++) {
                    global $resultcountries;
                    echo '<option value="' . $resultcountries[$i][0] . '">' . $resultcountries[$i][4] . '</option>';
                    //添加国家
                }
                ?>
            </select>
            <select name="city" id="currentcity">
                <option value="0">--城市--</option>
            </select>
            <select name="feature" id="currentfeature">
                <option value="0">--主题--</option>
                <option value="Scenery">Scenery</option>
                <option value="City">City</option>
                <option value="People">People</option>
                <option value="Animal">Animal</option>
                <option value="Building">Building</option>
                <option value="Wonder">Wonder</option>
                <option value="Other">Other</option>
            </select>
            <input type="button" class="screen" onclick="screen();" value="筛选" >
        </form>
        <script>
            function screen() {
                var expTime = new Date().getTime() + 60 * 60 * 10;
                var country=document.getElementById('currentcountry').value;
                var city=document.getElementById('currentcity').value;
                var feature=document.getElementById('currentfeature').value;
                if((country=='0')&(city=='0'||city=='')&(feature=='0')){
                    alert('至少选择一项！');
                }else {
                    document.cookie = "screencountry=" + country + "; expires=" + expTime + "; path=/PJ2/";
                    document.cookie = "screencity=" + city + "; expires=" + expTime + "; path=/PJ2/";
                    document.cookie = "screenfeature=" + feature + "; expires=" + expTime + "; path=/PJ2/";
                    window.location.href = "../php/screensearch.php";
                }
            }
        </script>
    </div>
   <div class="content">
        <div id="wrap" class="wrap" style="top: 0">
            <ul class="searchResult" id="sechresult">
                <?php
                require_once("../php/config.php");
                $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                if (!isset($_COOKIE['browseresult'])) {
                    echo '无匹配图片';
                } else {
                    $data = $_COOKIE['browseresult'];
                    $data1 = substr($data, 2, -2);
                    $address = explode("\",\"", $data1);
                    for ($i = 0; $i < sizeof($address); $i++) {
                        $sql = "SELECT ImageID,PATH FROM travelimage WHERE ImageID=" . $address[$i];
                        $statement = $pdo->prepare($sql);
                        $statement->execute();
                        if ($statement->rowCount() > 0) {
                            $result[$i] = $statement->fetchAll();
                            echo '<li>
              <div class="image" onclick="throwinf(' . $result[$i][0][0] . ')">
              <img id="' . $result[$i][0][0] . '" src="../travel-images/square-medium/' . $result[$i][0][1] . '" ></div>
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
        if(isset($_COOKIE['browseresult'])) {
            if (sizeof($address) > 9) {
                $aclpage = ceil(sizeof($address) / 9);
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
        wrap.style.top = (810-(index * 810)) + "px";
    }

    function press_next(num) {
        document.getElementById("btn" + index).classList.remove("on");
        index++;
        if (index == (num+1)) {
            index = 1;
        }
        document.getElementById("btn" + index).setAttribute("class", "on");
        wrap.style.top = (810-(index * 810)) + "px";
    }
    function jumpTo(i) {
        document.getElementById("btn"+index).classList.remove("on");
        index=i;
        document.getElementById("btn"+index).setAttribute("class","on");
        wrap.style.top = (810-(index * 810)) + "px";
    }
</script>
<footer>
    <p>2020.4|备案号：19302010051|作者：YiXiuD</p>
    <p>如有疑问请联系作者：q1758324883</p>
</footer>
</body>
</html>

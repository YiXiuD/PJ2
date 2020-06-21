<!DOCTYPE html>
<?php
setcookie("username", "", -1);
?>
<html>
<head>
    <meta charset="utf8">
    <title>登录</title>
    <link href="../css/reset.css" rel="stylesheet" type="text/css">
    <link href="../css/register.css" rel="stylesheet" type="text/css">
</head>
<body background="../images/regbg.jpg">
<header>
</header>
<h1>登录</h1>
<div class="content">
    <form action="../php/register.php"  method="POST">
        <ul class="content">
            <li>
                <input name="username" placeholder="Username" required type="username">
            </li>
            <li>
                <input name="password" placeholder="Password" required type="password">
            </li>
        </ul>
        <input  class="regBtn" name="continue" type="submit" value="登录">
    </form>
    <a   class="enrBtn" href="enroll.html"><p>新用户</p></a>
</div>
</body>
</html>
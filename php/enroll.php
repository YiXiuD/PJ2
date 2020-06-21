<?php
require_once("config.php");
function validLogin()
{
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $sql = "SELECT * FROM traveluser WHERE username=:user";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user',$_POST['username']);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        return false;
    }
    return true;
}
if(validLogin()){
    $pdo1 = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $sql1= "SELECT * FROM traveluser";
    $statement1 = $pdo1->query($sql1);
    $rowcount=$statement1->rowCount()+1;
    $sql2= "INSERT INTO traveluser (UID,UserName,Pass) VALUES ('{$rowcount}','{$_POST['username']}','{$_POST['password']}')";
    $statement2 = $pdo1->exec($sql2);
    echo "<script>alert('注册成功！')</script>";
    $url = "../html/login.php";
    Header("Location:".$url);
}else{
    echo "<script>alert('用户名已存在！')</script>";
    echo "<script language=JavaScript>window.history.go(-1);</script>";
}
?>

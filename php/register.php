<?php
require_once("config.php");
function validLogin(){
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $sql = "SELECT * FROM traveluser WHERE UserName=:user and Pass=:pass";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user',$_POST['username']);
    $statement->bindValue(':pass',$_POST['password']);
    $statement->execute();
    if($statement->rowCount()>0){
        return true;
    }
    return false;
}
if(validLogin()){
    $expiryTime = time()+60*60;
    setcookie("username", $_POST['username'], $expiryTime,'/PJ2/');
    $url = "../index.php";
    Header("Location: ".$url);
}
else {
    echo "<script>alert('用户名或密码错误')</script>";
    echo "<script language=JavaScript>window.history.go(-1);</script>";
}
?>

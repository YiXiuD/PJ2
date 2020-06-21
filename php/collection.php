<?php
require_once("config.php");
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$key[0]=$_COOKIE['username'];
$key[1]=$_COOKIE['fvimid'];
$key[2]=$_COOKIE['isclctd'];
echo '<script>console.log('.gettype($key[2]).')';
$sql1="Select uid from traveluser where username='".$key[0]."'";
$statement1=$pdo->prepare($sql1);
$statement1->execute();
$uid=$statement1->fetchall();
if($key[2]=='uncol'){
    $sql='INSERT into travelimagefavor (imageId,uid) values ('.$key[1].','.$uid[0][0].')';
    $statement=$pdo->prepare($sql);
    $statement->execute();
}else{
    $sql='DElEte from travelimagefavor WHERE imageId="'.$key[1].'" and uid="'.$uid[0][0].'"';
    $statement=$pdo->prepare($sql);
    $statement->execute();
}
Header('Location: ../html/pictureDetail.php');
?>
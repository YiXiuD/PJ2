<?php
require_once("../php/config.php");
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$sql = "SELECT uid FROM traveluser WHERE username='" . $_COOKIE['username'] . "'";
$statement = $pdo->prepare($sql);
$statement->execute();//用户信息
$result=$statement->fetchAll();
$sql1 = "DELETE FROM travelimage WHERE uid='" .$result[0][0]. "'and imageid='".$_COOKIE['delpicid']."'";
$statement1 = $pdo->prepare($sql1);
$statement1->execute();//用户信息
Header('Location:../html/myPhoto.php');
<?php
require_once("config.php");
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$key[0] = $_COOKIE['username'];
$key[1] = $_COOKIE['fvimid'];
echo '<script>console.log(' . gettype($key[2]) . ')';
$sql1 = "Select uid from traveluser where username='" . $key[0] . "'";
$statement1 = $pdo->prepare($sql1);
$statement1->execute();
$uid = $statement1->fetchall();
$sql = 'DElEte from travelimagefavor WHERE imageId="' . $key[1] . '" and uid="' . $uid[0][0] . '"';
$statement = $pdo->prepare($sql);
$statement->execute();
Header('Location: ../html/myCollection.php');
?>

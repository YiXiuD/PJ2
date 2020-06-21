<?php
require_once("config.php");
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$content = $_POST['content'];
$sql = 'SELECT imageid FROM travelimage WHERE title like"%' . $content . '%"';
$statement = $pdo->prepare($sql);
$statement->execute();
if ($statement->rowCount() > 0) {
    $result = $statement->fetchAll();
    $finresult = array();
    for ($i = 0; $i < $statement->rowCount(); $i++)
        $finresult[$i] = $result[$i][0];
    $expiryTime = time() + 60 * 60;
    setcookie('browseresult', json_encode($finresult), $expiryTime, '/PJ2/');
} else {
    setcookie('browseresult', '', -1, '/PJ2/');
}
Header('Location:../html/browse.php');
<?php
require_once("config.php");
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$type=$_COOKIE['hottype'];
$id=$_COOKIE['hotid'];
if($type=='country') {
    $sql = 'SELECT imageid FROM travelimage WHERE countrycodeiso ="'.$id.'"';
}
else if($type=='city') {
    $sql = 'SELECT imageid FROM travelimage WHERE citycode ="'.$id.'"';
}
else{
    $sql1 = 'SELECT iso FROM geocountries WHERE continent ="' . $id.'"';
    $statement1 = $pdo->prepare($sql1);
    $statement1->execute();
    if($statement1->rowCount()>0) {
        $result1=$statement1->fetchAll();
        $sql = 'SELECT imageid FROM travelimage WHERE ';
        for ($i = 0; $i < $statement1->rowCount(); $i++) {
            $sql = $sql . 'countrycodeiso ="' .$result1[$i][0].'"';
            if ($i != $statement1->rowCount() - 1)
                $sql = $sql . ' or ';
        }
    }else{
        $sql = 'SELECT imageid FROM travelimage WHERE countrycodeiso =a and countrycodeiso=b' ;

    }
}
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

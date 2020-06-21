<?php
require_once("config.php");
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$country= $_COOKIE['screencountry'];
$city= $_COOKIE['screencity'];
$feature= $_COOKIE['screenfeature'];
if($country=='0') {
    $sql = 'SELECT imageid FROM travelimage WHERE feature ="'.$feature.'"';
}else if($city=='0'||$city==''){
    if($feature!='0')
        $sql = 'SELECT imageid FROM travelimage WHERE feature ="'.$feature.'" and countrycodeiso ="'.$country.'"';
    else
        $sql = 'SELECT imageid FROM travelimage WHERE countrycodeiso="'.$country.'"';
}else{
    $sql1='SELECT geonameid from geocities where asciiname="'.$city.'"';
    $statement1 = $pdo->prepare($sql1);
    $statement1->execute();
    if($statement1->rowCount()>0)
        $result1=$statement1->fetchAll();
    else
        $result1[0][0]=0;
    if($feature!='0')
        $sql = 'SELECT imageid FROM travelimage WHERE feature ="'.$feature.'" and citycode ="'.$result1[0][0].'"';
    else
        $sql = 'SELECT imageid FROM travelimage WHERE citycode ="'.$result1[0][0].'"';
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
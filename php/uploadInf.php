<?php
require_once("../php/config.php");
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
if($_POST['country']=='0'){
    $country='AA';
}else {
    $country=$_POST['country'];
}
$sqlcities = "SELECT geonameid FROM `geocities` WHERE asciiname='".$_POST['city']."'";
$statecities = $pdo->prepare($sqlcities);
$statecities->execute();
if($statecities->rowCount()>0)
    $resultcities = $statecities->fetchAll();
else
    $resultcities[0][0]='null';

$sqluid = "SELECT uid FROM `traveluser` WHERE username='".$_COOKIE['username']."'";
$stateuid = $pdo->prepare($sqluid);
$stateuid->execute();
$resultuid = $stateuid->fetchAll();

$sqlimgid='SELECT * FROM `travelimage` ORDER BY `travelimage`.`ImageID`  DESC';
$stateimgid = $pdo->prepare($sqlimgid);
$stateimgid->execute();
$resultimgid = $stateimgid->fetchAll();
$imageid=$resultimgid[0][0]+1;


if($_POST['imgid']==0){
    $sql = 'INSERT into travelimage (imageid,title,description,citycode,countrycodeiso,feature,uid,path)
 values ('.$imageid.',"'.$_POST['title'].'","'.$_POST['detail'].'","'.$resultcities[0][0].'","'.$country.'","'.$_POST['feature'].'","'.$resultuid[0][0].'","'.$_POST['imgsrc'].'")';
}else{
    $sql='UPdate travelimage set title="'.$_POST['title'].'",description="'.$_POST['detail'].'",citycode="'.$resultcities[0][0].'",countrycodeiso="'.$country.'",feature="'.$_POST['feature'].'" where imageid="'.$_POST['imgid'].'"';

}
$state = $pdo->prepare($sql);
$state->execute();
setcookie("facpicid",'',-1,'/PJ2/');
if(is_uploaded_file($_FILES['image']['tmp_name'])) {
    move_uploaded_file($_FILES['image']['tmp_name'], 'C:\Users\86188\Desktop\PJ2/travel-images/square-medium/' . basename($_FILES['image']['tmp_name']));
    rename('C:\Users\86188\Desktop\PJ2/travel-images/square-medium/' . basename($_FILES['image']['tmp_name']),
        'C:\Users\86188\Desktop\PJ2/travel-images/square-medium/' . $_POST['imgsrc']);
    copy('C:\Users\86188\Desktop\PJ2/travel-images/square-medium/'.$_POST['imgsrc'], 'C:\Users\86188\Desktop\PJ2/travel-images/large/' . basename($_FILES['image']['tmp_name']));
    rename('C:\Users\86188\Desktop\PJ2/travel-images/large/' . basename($_FILES['image']['tmp_name']),
        'C:\Users\86188\Desktop\PJ2/travel-images/large/' . $_POST['imgsrc']);
}
    Header('Location:../html/myPhoto.php');
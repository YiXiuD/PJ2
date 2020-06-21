<?php
require_once("config.php");
$pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
$sqlcountries = "SELECT * FROM `geocountries` ORDER BY `geocountries`.`Population` DESC";
$statecountries = $pdo->prepare($sqlcountries);
$statecountries->execute();
$resultcountries = $statecountries->fetchAll();
$countryNum = $statecountries->rowCount();
$citytotal = array("0" => null);
for ($i = 0; $i < $countryNum; $i++) {
    global $resultcountries;
    $sqlcities2 = "SELECT AsciiName FROM geocities WHERE CountryCodeISO='" . $resultcountries[$i][0]."'";
    $statecities2 = $pdo->prepare($sqlcities2);
    $statecities2->execute();
    $citytotal[$resultcountries[$i][0]] = $statecities2->fetchall(PDO::FETCH_NUM);
}
$key = $_POST['key']; //获取值
//$key = 'CN';
if(!$key==0){
    $array = $citytotal[$key];
    $result = array();
    for ($i = 0; $i < sizeof($array); $i++) {
        $result[$i] = $array[$i][0];
    }
    echo json_encode($result); //返回JSON数据
}else{
    echo null;
}
?>
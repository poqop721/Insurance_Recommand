<?php
// # 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )

# 입력 변수 선언
$age = (int)$_POST["age"];
$height = (int)$_POST["height"];
$weight = (int)$_POST["weight"];
$sex = (int)$_POST["sex"];

$BP = $_POST["BP"];
$BOS = $_POST["BOS"];
$BFP = $_POST["BFP"];
$SMM = $_POST["SMM"];
$MBW = $_POST["MBW"];
$BM = $_POST["BM"];

$_SESSION['warning'] = $_POST["warning"];
$_SESSION['danger'] = $_POST["danger"];
$_SESSION['dangerFeedback'] = $_POST["dangerFeedback"];
$_SESSION['warningFeedback'] = $_POST["warningFeedback"];

if ($_POST["warningFeedback"] != null) {
    $healthWarning = array_unique($_POST["warningFeedback"]);
    foreach($healthWarning as $key => $value){
        switch($value){
            case '혈압':
                $healthWarning[$key] = '1';
                break;
            case '혈중 산소 포화도':
                $healthWarning[$key] = '2';
                break;
            case '체지방률':
                $healthWarning[$key] = '3';
                break;
            case '골격근량':
                $healthWarning[$key] = '4';
                break;
            case '체수분':
                $healthWarning[$key] = '5';
                break;
            case '기초대사량':
                $healthWarning[$key] = '6';
                break;
        }
    }
    $healthWarning = implode(',', $healthWarning);
} else{
    $healthWarning = NULL;
}

if ($_POST["dangerFeedback"] != null) {
    $healthDanger = array_unique($_POST["dangerFeedback"]);
    foreach($healthDanger as $key => $value){
        switch($value){
            case '혈압':
                $healthDanger[$key] = '1';
                break;
            case '혈중 산소 포화도':
                $healthDanger[$key] = '2';
                break;
            case '체지방률':
                $healthDanger[$key] = '3';
                break;
            case '골격근량':
                $healthDanger[$key] = '4';
                break;
            case '체수분':
                $healthDanger[$key] = '5';
                break;
            case '기초대사량':
                $healthDanger[$key] = '6';
                break;
        }
    }
    $healthDanger = implode(',', $healthDanger);
} else {
    $healthDanger = NULL;
}




if ($BP == 0)
    $BP = "NULL";
if ($BOS == 0)
    $BOS = "NULL";
if ($SMM == 0)
    $SMM = "NULL";
if ($MBW == 0)
    $MBW = "NULL";

# Oracle DB 서버 접속
include('./login_register/connect.php');

$id = $_SESSION['ID'];
# sql문
$healthSQL = "INSERT INTO HEALTH VALUES ($BP, $BOS, $BFP, $SMM, $MBW, $BM, (SELECT nextval('health_info') FROM DUAL),'$id',now(),'$healthWarning','$healthDanger')";
mysqli_query($connect, $healthSQL);
$_SESSION['isResult'] = 1;
echo "<script type='text/javascript'>location.replace('result.php');</script>";
// DB 메모리 할당 및 연결 해제 
mysqli_close($connect);

?>
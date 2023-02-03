<?php
// # 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )

# 입력 변수 선언

$zzim = $_POST["zzim"];

if ($zzim != null) {
    $zzimList = array();

    for ($i = 0; $i < count($zzim); $i++) {
        array_push($zzimList, $zzim[$i]);
    }

    # DB 접속 부분 
    include('./login_register/connect.php');

    $id = $_SESSION['ID'];
    # sql문
    foreach ($zzimList as $key => $value) {
        $zzimSQL = "INSERT INTO REFERENCETABLE VALUES ((SELECT PRODUCTID FROM PRODUCT WHERE PRODUCTNAME = '$value'),'$id')";
        mysqli_query($connect, $zzimSQL);
    }


    echo "<script type='text/javascript'>alert('선택하신 보험을 찜했습니다. 찜한 보험은 마이페이지에서 확인 가능합니다.');location.replace('result.php');</script>";
}
else echo "<script type='text/javascript'>alert('먼저 찜 할 보험 상품을 선택해세요.');location.replace('result.php');</script>";


// DB 메모리 할당 및 연결 해제 
mysqli_close($connect);

exit;
?>
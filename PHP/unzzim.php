<?php
// # 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )

# 입력 변수 선언

$unzzim = $_POST["unzzim"];
$id = $_SESSION['ID'];


# DB 접속 부분 
include('./login_register/connect.php');

if ($unzzim != null) {

    mysqli_query($connect, "DELETE FROM REFERENCETABLE WHERE REFERENCETABLE.PRODUCTID = '$unzzim' AND REFERENCETABLE.CUSTOMER_ID = '$id'");

    echo "<script type='text/javascript'>alert('찜 목록에서 제거했습니다.');location.replace('myPage.php');</script>";
}
else {
    mysqli_query($connect, "DELETE FROM REFERENCETABLE WHERE REFERENCETABLE.CUSTOMER_ID = '$id'");
    echo "<script type='text/javascript'>alert('찜 목록을 비웠습니다.');location.replace('myPage.php');</script>";
}


// DB 메모리 할당 및 연결 해제 
mysqli_close($connect);

exit;
?>
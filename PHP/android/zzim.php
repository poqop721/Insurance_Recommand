<?php

# 세션 스타트
session_start();

include('../login_register/connect.php');

$id = $_POST["ID"];
$zzim = $_POST["zzim"];

if ($zzim == "") {
    echo "오류가 났습니다. 다시 시도해주세요.";
}
else {
    $zzimSQL = "INSERT INTO REFERENCETABLE VALUES ((SELECT PRODUCTID FROM PRODUCT WHERE PRODUCTNAME = '$zzim'),'$id')";
    mysqli_query($connect, $zzimSQL);
    echo "선택한 보험 상품을 찜했습니다.";
}


// DB 메모리 할당 및 연결 해제 
mysqli_close($connect);

exit;
?>
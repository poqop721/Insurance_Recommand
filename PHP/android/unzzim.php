<?php

# 세션 스타트
session_start();

include('../login_register/connect.php');

$id = $_POST["ID"];
$zzim = $_POST["zzim"];
$reset = $_POST["reset"];

if ($zzim == "") {
    echo "오류가 났습니다. 다시 시도해주세요.";
}
else if($zzim != null && $reset == "false"){
    $zzimSQL = "DELETE FROM REFERENCETABLE WHERE REFERENCETABLE.PRODUCTID = (SELECT PRODUCTID FROM PRODUCT WHERE PRODUCTNAME = '$zzim') AND REFERENCETABLE.CUSTOMER_ID = '$id'";
    mysqli_query($connect, $zzimSQL);
    echo "찜 목록에서 제거했습니다.";
}
else if ($reset == "true"){
    mysqli_query($connect, "DELETE FROM REFERENCETABLE WHERE REFERENCETABLE.CUSTOMER_ID = '$id'");
    echo "찜 목록을 비웠습니다.";
}


// DB 메모리 할당 및 연결 해제 
mysqli_close($connect);

exit;
?>
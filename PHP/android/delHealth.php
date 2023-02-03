<?php
$delHealth = $_POST["del_num"];
$reset = $_POST["reset"];
$id = $_POST['ID'];
# 세션 스타트
session_start();
include('../login_register/connect.php');

if ($delHealth != null && $reset == "false") {
    mysqli_query($connect, "DELETE FROM HEALTH WHERE HEALTH_INFO = $delHealth AND CUSTOMER_ID = '$id'");
    echo "$reset";
}
else if($reset == "true") {
    mysqli_query($connect, "DELETE FROM HEALTH WHERE CUSTOMER_ID = '$id'");
    echo "'건강 기록이 초기화 되었습니다.";
}


// DB 메모리 할당 및 연결 해제 
mysqli_close($connect);

exit;
?>
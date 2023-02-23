<?php
# 세션 스타트
session_start();

include('../login_register/connect.php');

# 세션 아이디 기준 
$id = $_POST['ID'];


if (!is_null($id)) {

    # sql문 (ID확인)
    $sql = "SELECT CUSTOMER_ID,CUSTOMER_PASSWORD FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$id'";

    # sql문 DB로 파싱 후 전송
    $send = mysqli_query($connect, $sql);
    
    while ($row = mysqli_fetch_array($send,MYSQLI_ASSOC)) {
        $id = $row["CUSTOMER_ID"];
        $encrypted_password = $row["CUSTOMER_PASSWORD"];
    }
}

# 패스워드 확인
$password = $_POST['PW'];

if (!is_null($password)) {
    if (password_verify($password, $encrypted_password)) {

    # sql문 (ID확인)
    $sql = "DELETE FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$id'";

    # sql문 DB로 파싱 후 전송
    $send = mysqli_query($connect, $sql);
    
    # js 함수 호출 
    echo "yes";
    }

    else{
        echo "$password";
    }
    # DB 메모리 할당 및 연결 해제 
    mysqli_close($connect);
} 
?>
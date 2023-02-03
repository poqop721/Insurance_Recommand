<?php
// # 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )
include('../login_register/connect.php');

# 세션(현재 접속한) 아이디 
$id = $_POST['ID'];

# DB 접속 부분 

if (!is_null($id )) {

    # sql문 (ID확인)
    $sql = "SELECT CUSTOMER_PASSWORD FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$id'";

    # sql문 DB로 파싱 후 전송
    $send = mysqli_query($connect, $sql);

    # 배열의 모든 아이템(요소)을 한 번에 하나씩 참조하여 처리
    $row = mysqli_fetch_array($send, MYSQLI_ASSOC);
    $encrypted_password = $row["CUSTOMER_PASSWORD"];
}

# 수정받을 입력 변수 선언
$current_password = $_POST["CPW"];
$new_password = $_POST["NPW"];
$new_password_confirm = $_POST["NPWC"];
$new_name = $_POST["name"];
$new_birth = $_POST["birth"];
$new_height = $_POST["height"];
$new_weight = $_POST["weight"];
$new_sex = $_POST["sex"];
$new_email = $_POST["email"];
$new_phone = $_POST["phone"];



# 현재 비번이 NULL이 아닌 경우
if (!is_null($current_password)){
    # 현재 비번과 암호화된 패스워드가 일치하는지 확인
    if ( password_verify( $current_password, $encrypted_password ) ) {
        # 새로운 비번이 NULL이 아닌경우
        if(!is_null($new_password)){
        # 새로운 비번과 새로 암호화된 패스워드가 일치하는지 확인
            if ( $new_password == $new_password_confirm ) {
            $encrypted_new_password = password_hash( $new_password, PASSWORD_DEFAULT);
                
            $sql_change = "UPDATE CUSTOMERINFO SET CUSTOMER_PASSWORD = '$encrypted_new_password', CUSTOMER_NAME = '$new_name', CUSTOMER_BIRTH = str_to_date('$new_birth', '%Y-%m-%d'), CUSTOMER_HEIGHT = '$new_height', CUSTOMER_WEIGHT = '$new_weight', CUSTOMER_SEX = '$new_sex', CUSTOMER_EMAIL = '$new_email', CUSTOMER_PHONENUM = '$new_phone' WHERE CUSTOMER_ID = '$id'";
                
            # sql문 DB로 파싱 후 전송
            mysqli_query($connect, $sql_change);

            echo "true";
            }

            else {
                # 새로운 비밀번호가 일치한지 확인
                echo "새 비밀번호가 일치하지 않습니다.";
                }     
        } 
        # 현재 비번과 암호화된 패스워드가 일치하는지 하지 않을 경우   
        else{

        if(!is_null($new_password_confirm)){
            echo "새 비밀번호가 입력되지 않았습니다.";
        }
        else{
            # 새로운 비밀번호 수정 필요 X
            $sql_change2 = "UPDATE CUSTOMERINFO SET CUSTOMER_NAME = '$new_name', CUSTOMER_BIRTH = str_to_date('$new_birth', '%Y-%m-%d'), CUSTOMER_HEIGHT = '$new_height', CUSTOMER_WEIGHT = '$new_weight', CUSTOMER_SEX = '$new_sex', CUSTOMER_EMAIL = '$new_email', CUSTOMER_PHONENUM = '$new_phone' WHERE CUSTOMER_ID = '$id'";
            
            # sql문 DB로 파싱 후 전송
            mysqli_query($connect, $sql_change2);

            # 현재 트랜잭션을 종료하고 모든 변경 사항을 영구적으로 반영
            mysqli_commit($connect);

            # Oracle 연결 해제
            mysqli_close($connect);   
            
            echo "true";
            } 
        }
    }  
    else {
        # 새로운 비밀번호가 일치한지 확인
        echo "기존 비밀번호가 일치하지 않습니다.";
        }           
}    

?>
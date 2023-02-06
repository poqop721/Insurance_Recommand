<?php
// # 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# 패스워드 암호화를 위해 php 버전 5.5 미만일 때 사용 하지 못 할때 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )
include_once("./password_compat.php");

# POST 변수로 저장
$id = $_POST["ID"];
$password = $_POST["PW"];
$password_confirm = $_POST["PWC"];
$name = $_POST["name"];
$birth = $_POST["birth"];
$height = $_POST["height"];
$weight = $_POST["weight"];
$sex = $_POST["sex"];
$email = $_POST["email"];
$domain = $_POST["domain"];
$phone = $_POST["phone"];

if ($sex == '남성')
    $sex = '남성';
else if ($sex == '여성')
    $sex = '여성';
if ($domain != '직접입력')
    $email = $email.$domain;

# id 체크값 변수 선언
$check = 0;

# DB 접속 부분 
$db =
    '(DESCRIPTION =
        (ADDRESS_LIST=
            (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
        )
            (CONNECT_DATA =
                (SID = orcl)
            )
        )';

# Oracle 학교 DB 서버 ID/PW
$username = "DBA2022G1";
$userpassword = "test1234";


if (!is_null($id)) {

    # Oracle DB 서버 접속
    $connect = oci_connect($username, $userpassword, $db,'AL32UTF8');

    # 연결 오류 시 Oracle 오류 메시지 표시
    if (!$connect) {
        $e = oci_error(); // For oci_connect errors do not pass a handle
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    # sql문 (ID확인)
    $sql = "SELECT CUSTOMER_ID FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$id'";

    # sql문 DB로 파싱 후 전송
    $send = oci_parse($connect, $sql);
    oci_execute($send);

    # 쿼리의 결과 배열을 반환 
    while ($row = oci_fetch_array($send, OCI_ASSOC)) {
        # 배열의 원소 값을 가져온다
        foreach ($row as $item) {
            $userid_exist = $item;
            # 그 중 동일 id가 있으면 체크 값 증가 
            if ($userid_exist == $id) {
                $check++;
            }
        }
    }
    if ($check > 0) {
        echo "<script>alert('아이디가 이미 존재합니다');location.replace('../signup.php');</script>";
        exit;
    } else if ($password != $password_confirm) {
        echo "<script>alert('비밀번호가 일치하지 않습니다');location.replace('../signup.php');</script>";
        exit;
    } else {
        # 패스워드 Hash 형식으로 암호화
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

        # 유저 추가 (INSERT 문)
        $sql_add_user = "INSERT INTO CUSTOMERINFO VALUES ('$id', '$encrypted_password','$name',to_date('$birth', 'YYYY-MM-DD'),'$height','$weight','$sex','$email','$phone',to_date(sysdate,'YYYY-MM-DD'))";

        # SQL문 DB 전송
        $send = oci_parse($connect, $sql_add_user);
        oci_execute($send);

        # js 함수 호출 
        echo "<script>alert('회원가입이 완료 되었습니다');</script>";
        echo "<script type='text/javascript'>location.replace('../main.php');</script>";
        exit;
    }
    // DB 메모리 할당 및 연결 해제 
    oci_free_statement($send);
    oci_close($connect);
}
else {
    echo "<script>alert('아이디를 입력해주세요.');location.replace('../main.php');</script>";
    exit;
}
?>
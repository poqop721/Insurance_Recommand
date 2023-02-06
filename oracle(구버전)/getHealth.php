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




if ($BP == 0)
    $BP = null;
if ($BOS == 0)
    $BOS = null;
if ($SMM == 0)
    $SMM = null;
if ($MBW == 0)
    $MBW = null;


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


# Oracle DB 서버 접속
$connect = oci_connect($username, $userpassword, $db);

# 연결 오류 시 Oracle 오류 메시지 표시
if (!$connect) {
    $e = oci_error(); // For oci_connect errors do not pass a handle
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$id = $_SESSION['ID'];
# sql문
$healthSQL = "INSERT INTO HEALTH VALUES ('$BP', '$BOS', '$BFP', '$SMM', '$MBW', '$BM', HEALTH_SEQ.NEXTVAL,'$id',to_date(sysdate,'YYYY-MM-DD hh24:mi:ss'))";
oci_execute(oci_parse($connect, $healthSQL));
echo "<script type='text/javascript'>localStorage.setItem('isResult', 'true');location.replace('result.php');</script>";

// DB 메모리 할당 및 연결 해제 
oci_free_statement($send);
oci_close($connect);

?>
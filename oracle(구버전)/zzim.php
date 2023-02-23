<?php
// # 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )

# 입력 변수 선언

$zzim = $_POST["zzim"];
$zzimList = array();

for ($i = 0; $i < count($zzim); $i++) {
    $zzim[$i] = iconv("UTF-8", "EUC-KR", $zzim[$i]);
    array_push($zzimList, $zzim[$i]);
}



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
$connect = oci_connect($username, $userpassword, $db, 'KO16MSWIN949');

# 연결 오류 시 Oracle 오류 메시지 표시
if (!$connect) {
    $e = oci_error(); // For oci_connect errors do not pass a handle
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$id = $_SESSION['ID'];
# sql문
foreach ($zzimList as $key => $value) {
    $zzimSQL = "INSERT INTO REFERENCETABLE VALUES ((SELECT INSID FROM INS WHERE NAME = '$value'),'$id')";
    oci_execute(oci_parse($connect, $zzimSQL));
}


echo "<script type='text/javascript'>alert('보험사를 찜했습니다. 마이페이지에서 찜한 보험 상품의 다른 상품들도 확인해보세요.');location.replace('result.php');</script>";


// DB 메모리 할당 및 연결 해제 
oci_free_statement($send);
oci_close($connect);

exit;
?>
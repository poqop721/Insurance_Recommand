<?php
# 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )
include_once("./password_compat.php");

# 세션 로그인 값을 통해 로그인 여부 확인
$id = $_SESSION["ID"];

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

if (!is_null($_SESSION["ID"])) {

    # Oracle DB 서버 접속
    $connect = oci_connect($username, $userpassword, $db,'AL32UTF8');

    # 연결 오류 시 Oracle 오류 메시지 표시
    if (!$connect) {
        $e = oci_error(); // For oci_connect errors do not pass a handle
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    # sql문 (ID확인)
    $sql = "SELECT * FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$id'";

    # sql문 DB로 파싱 후 전송
    $send = oci_parse($connect, $sql);
    oci_execute($send);
    
    while ($row = oci_fetch_array($send,OCI_ASSOC)) {
        $id = $row["CUSTOMER_ID"];
        $name = $row["CUSTOMER_NAME"];
        $birth = $row["CUSTOMER_BIRTH"];
        $weight = $row["CUSTOMER_WEIGHT"];
        $height = $row["CUSTOMER_HEIGHT"];
        $sex = $row["CUSTOMER_SEX"];
        $email = $row["CUSTOMER_EMAIL"];
        $phone = $row["CUSTOMER_PHONENUM"];
        $regdate = $row["REGDATE"];
    }


}
else{
    echo "<script>alert('아이디를 입력해주세요.');location.replace('../main.php');</script>";
}
?>


<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지</title>
    <script type="text/javascript">
        window.onload = function(){
            getNames();
        }
       </script>
    <link rel="stylesheet" href="css&js/mypage.css">
    <link rel="stylesheet" href="css&js/header.css">
    <link rel="stylesheet" href="css&js/curheader.css">
    <link rel="stylesheet" href="css&js/signup.css">

    <script src="css&js/modal.js"></script>
    <script src="css&js/goTo.js"></script>

</head>

<body>
    <!--header-->
    <div>
        <!--Header Title (Left)-->
        <div class="headertitleleft">
            <h2><a href="main.php">보험추천</a></h2>
        </div>
        <!--Header Button (Right)-->
        <div class="headertitleright">
            <button type="button" onclick="location.href='login_register/logOut.php'"><a id="logout">로그아웃</a><img
                    src="img/logout.png" alt="button" width="32px"></button>
            <a class="name"></a><a class="sla">님</a>
        </div>
        <!--header-->
        <header class="header">
            <!--header - nav-->
            <nav class="headernav">
                <span>
                    <a class="otherheader" onclick="location.href='main.php'">보험 추천받기</a>
                </span>
                <span>
                    <a class="otherheader" onclick="toResult()">추천 받은 보험</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='allIns.php'">전체 보험 상품</a>
                </span> 
                <span>
                    <a class="otherheader" onclick="location.href='myHealthInfo.php'">나의 건강정보</a>
                </span>
                <span>
                    <a class="curheader" onclick=>마이페이지</a>
                </span>
            </nav>
        </header>
    </div>
    
    <h1 class="resultH1"><a class="name" id="resultname"></a>님의 개인정보</h1>
    <div class="result">
        <div class='mypageCon'>
            <div>
                <table class='table'>
                    <th></th><th></th>
                <tr>
                    <td>아이디</td>
                    <td><?php echo $id ?></td>
                </tr>
                <tr>
                    <td>생일</td>
                    <td><?php $birth = strtotime($birth); echo date("Y-M-D", $birth) ?></td>
                </tr>
                <tr>
                    <td>키</td>
                    <td><?php echo $height ?></td>
                </tr>
                <tr>
                    <td>몸무게</td>
                    <td><?php echo $weight ?></td>
                </tr>
                <tr>
                    <td>성별</td>
                    <td><?php echo $sex ?></td>
                </tr>
                <tr>
                    <td>이메일</td>
                    <td><?php echo $email ?></td>
                </tr>
                <tr>
                    <td>전화번호</td>
                    <td><?php echo $phone ?></td>
                </tr>
                <tr>
                    <td>회원 가입 일자</td>
                    <td><?php $regdate = strtotime($regdate); echo date("Y-M-D", $regdate) ?></td>
                </tr>
                </table>
            </div>
            <h2>내가 찜한 보험사의 다른 상품들</h2>
        <div class="myPageTable">
            <?php
    # sql문 (ID확인)
    $sql = "SELECT INSID from REFERENCETABLE where CUSTOMER_ID = '$id'";

    # sql문 DB로 파싱 후 전송
    $send = oci_parse($connect, $sql);
    oci_execute($send);

    while ($row = oci_fetch_array($send,OCI_ASSOC)) {
        foreach ($row as $item){
            $sql2 = "SELECT PRODUCTNAME, PRICE, COMP ,KIND from PRODUCT where INSID = (SELECT INSID from INS where INSID = '$item')";
            $send2 = oci_parse($connect, $sql2);
            oci_execute($send2);
            echo "<table class='table'>\n<th>보험명</th><th>가입금액(원)</th><th>보장금액(원)</th><th>주 보장 항목</th>";
            while (($row2 = oci_fetch_array($send2, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                echo "<tr>\n";
                foreach ($row2 as $item2) {
                echo "    <td>".($item2 !== null ? htmlentities($item2, ENT_QUOTES) : "&nbsp;")."</td>\n";
            }
            echo "</tr>\n";
        }
        echo "</table>\n";
            }

                // DB 메모리 할당 및 연결 해제 
    oci_free_statement($send);
    oci_close($connect);
        }
    

    // DB 메모리 할당 및 연결 해제 
    oci_free_statement($send);
    oci_close($connect);

    ?>
        </div>
        <br>
        <input type="button" onClick="location.href='./update_signup.php'" value="개인정보 수정" class="signupbtn">
        <input type="button" onClick="location.href='./delete_signup.php'" value="회원 탈퇴" class="signupbtn">
    </div>
    </div>

    <!--footer-->
    <footer>
    데이터베이스 및 실습 1조 B789055 전성태, B789071 현동엽, B789033오현석, B789049 이현진<br>
        주제: 개인 건강정보를 통한 맞춤형 보험 상품 추천 시스템    
    </footer>
</body>

</html>    
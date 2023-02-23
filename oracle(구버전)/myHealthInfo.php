<?php

# 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

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

}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>나의 건강정보</title>
    <script type="text/javascript">
        window.onload = function(){
            getNames();
        }
       </script>
    <link rel="stylesheet" href="css&js/myhealthinfo.css">
    <link rel="stylesheet" href="css&js/header.css">
    <link rel="stylesheet" href="css&js/curheader.css">

    <script src="css&js/modal.js"></script>
    <script src="css&js/goTo.js"></script>
    <script src="css&js/recommand.js"></script>

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
                    <a class="curheader" onclick=>나의 건강정보</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='myPage.php'">마이페이지</a>
                </span>
            </nav>
        </header>
    </div>
    </div>

    <!--세부건강정보-->
    <h1 class="resultH1"><a class="name" id="resultname">@@</a>님의 건강 정보 기록</h1>
    <div class="result">
        <div class="resultContainer">
            <?php
            $connect = oci_connect($username, $userpassword, $db);
            
            $query = "SELECT CREATED_DATE, CUSTOMERINFO.CUSTOMER_BIRTH, HEALTH.HEALTH_BP, HEALTH.HEALTH_BOS,HEALTH.HEALTH_BFP, HEALTH.HEALTH_SMM, HEALTH.HEALTH_MBW, HEALTH.HEALTH_BM
                        FROM CUSTOMERINFO JOIN HEALTH ON CUSTOMERINFO.CUSTOMER_ID = HEALTH.CUSTOMER_ID
                        WHERE CUSTOMERINFO.CUSTOMER_ID = '$id'
                        ORDER BY HEALTH.CREATED_DATE DESC";
            $info = oci_parse($connect,$query);
            oci_execute($info);

            echo "<table class='table'>\n<th>입력된 날짜</th><th>생년월일</th><th>혈압</th><th>혈중산소포화도</th><th>체지방률</th><th>골격근량</th><th>체수분</th><th>기초대사량</th>";
            while (($in = oci_fetch_array($info, OCI_ASSOC)) != false) {
                echo ("
                <tr>\n
                    <td>{$in['CREATED_DATE']}</td>
                    <td>{$in['CUSTOMER_BIRTH']}</td>
                    <td class='BP'>{$in['HEALTH_BP']}</td>
                    <td class='BOS'>{$in['HEALTH_BOS']}</td>
                    <td class='BFP'>{$in['HEALTH_BFP']}</td>
                    <td class='SMM'>{$in['HEALTH_SMM']}</td>
                    <td class='MBW'>{$in['HEALTH_MBW']}</td>
                    <td class='BM'>{$in['HEALTH_BM']}</td>
                </tr>
                 ");
            };
            echo "</table>\n";
                    
            oci_free_statement ($info);
            oci_close ($connect)
            ?>
        </div>
    </div>

    <script>
        let BP = document.querySelectorAll('.BP');
        BP.forEach((e) => {
            if(120 <= e.innerText && e.innerText<= 140){
                e.style.color='rgb(18, 99, 239)';
            }else if (e.innerText > 140) e.style.color='rgb(235, 73, 73)';
        });
        let BOS = document.querySelectorAll('.BOS');
        BOS.forEach((e) => {
            if (80 < e.innerText && e.innerText < 95) e.style.color='rgb(18, 99, 239)';
            else if (e.innerText <= 80) e.style.color='rgb(235, 73, 73)';
        });

        let BFP = document.querySelectorAll('.BFP');
        console.log(localStorage.getItem('sex'));
        BFP.forEach((e) => {
            if(localStorage.getItem('sex') === 'man'){
                if(localStorage.getItem('age') >= 30){
                if(e.innerText < 17) e.style.color='rgb(18, 99, 239)';
                else if(17 <= e.innerText && e.innerText < 23) e.style.color='black';
                else if (23 <= e.innerText && e.innerText < 28) e.style.color='rgb(18, 99, 239)';
                else if (28 <= e.innerText && e.innerText < 38) e.style.color='rgb(18, 99, 239)';
                else e.style.color='rgb(235, 73, 73)';
        }        else{
            if(e.innerText < 14) e.style.color='rgb(18, 99, 239)';
            else if(14 <= e.innerText && e.innerText < 20) e.style.color='black';
            else if (20 <= e.innerText && e.innerText < 25) e.style.color='rgb(18, 99, 239)';
            else if (25 <= e.innerText && e.innerText < 35) e.style.color='rgb(18, 99, 239)';
            else e.style.color='rgb(235, 73, 73)';
        }
            } else {
                if(localStorage.getItem('age') >= 30){
            if(e.innerText < 20) e.style.color='rgb(18, 99, 239)';
            else if(20 <= e.innerText && e.innerText < 27) e.style.color='black';
            else if (27 <= e.innerText && e.innerText < 33) e.style.color='rgb(18, 99, 239)';
            else if (33 <= e.innerText && e.innerText < 43) e.style.color='rgb(18, 99, 239)';
            else e.style.color='rgb(235, 73, 73)';
        }        else{
            if(e.innerText < 17) e.style.color='rgb(18, 99, 239)';
            else if(17 <= e.innerText && e.innerText < 24) e.style.color='black';
            else if (24 <= e.innerText && e.innerText < 30) e.style.color='rgb(18, 99, 239)';
            else if (30 <= e.innerText && e.innerText < 40)e.style.color='rgb(18, 99, 239)';
            else e.style.color='rgb(235, 73, 73)';
        }
            }
        });

        let MBW = document.querySelectorAll('.MBW');
        MBW.forEach((e) => {
            if (45 < e.innerText && e.innerText < 70) e.style.color='black';
            else e.style.color='rgb(18, 99, 239)';
        });

    </script>
    
    <!--footer-->
    <footer>
    데이터베이스 및 실습 1조 B789055 전성태, B789071 현동엽, B789033오현석, B789049 이현진<br>
        주제: 개인 건강정보를 통한 맞춤형 보험 상품 추천 시스템    </footer>
    <script>make_feedback()</script>
</body>
    
</html>    
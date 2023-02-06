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

# id가 없을 시 접근 불가하도록 하는 거임
if (!is_null($_SESSION["ID"])) {

    # Oracle DB 서버 접속
    $connect = oci_connect($username, $userpassword, $db, 'KO16MSWIN949');

    # 연결 오류 시 Oracle 오류 메시지 표시
    if (!$connect) {
        $e = oci_error(); // For oci_connect errors do not pass a handle
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $warnarr = $_SESSION['warning'];
    $danarr = $_SESSION['danger'];
    $warningFeedback = $_SESSION['warningFeedback'];
    $dangerFeedback = $_SESSION['dangerFeedback'];



    $len = (int) count($warnarr);

    $warnarr = array_slice($warnarr, 0, $len / 2);

    $len2 = (int) count($danarr);

    $danarr = array_slice($danarr, 0, $len2 / 2);

    $checkWarn = array();

    for ($i = 0; $i < count($warnarr); $i++) {
        if(in_array($warnarr[$i], $checkWarn)){
            $warnobj[$warnarr[$i]] = $warnobj[$warnarr[$i]] . ' , ' . $warningFeedback[$i];
        }
        else {
            $warnobj[$warnarr[$i]] = $warningFeedback[$i];
            array_push($checkWarn, $warnarr[$i]);
        }
        // echo "<a>$warnarr[$i] == $warningFeedback[$i]</a>";
    }

    $checkDan = array();
    for ($i = 0; $i < count($danarr); $i++) {
        if(in_array($danarr[$i], $checkDan)){
            $danobj[$danarr[$i]] = $danobj[$danarr[$i]] . ' , ' . $dangerFeedback[$i];
        } else {
            $danobj[$danarr[$i]] = $dangerFeedback[$i];
            array_push($checkDan, $danarr[$i]);
        }
        // echo "<a>$danarr[$i] == $dangerFeedback[$i]</a>";
    }

    
    //warn
    $warnList = array();

    for ($i = 0; $i < count($warnarr); $i++) {
        $warnarr[$i] = iconv("UTF-8", "EUC-KR", $warnarr[$i]);
        array_push($warnList, $warnarr[$i]);
    }

    // dan
    $danList = array();

    for ($i = 0; $i < count($danarr); $i++) {
        $danarr[$i] = iconv("UTF-8", "EUC-KR", $danarr[$i]);
        array_push($danList, $danarr[$i]);
    }

    
    $warnList = array_unique($warnList);
    $danList = array_unique($danList);

    # DB 메모리 할당 및 연결 해제 

} else {
    echo "<script>alert('아이디를 입력해주세요.');location.replace('../main.php');</script>";
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>추천 받은 보험</title>
    <script type="text/javascript">
        window.onload = function () {
            getNames();
        }
    </script>
    <link rel="stylesheet" href="css&js/result.css">
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
                    <a class="curheader" onclick=>추천 받은 보험</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='allIns.php'">전체 보험 상품</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='myHealthInfo.php'">나의 건강정보</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='myPage.php'">마이페이지</a>
                </span>
            </nav>
        </header>
    </div>
    </div>
    <!--추천-->
    <h1 class="resultH1">&nbsp;&nbsp;<a class="name" id="resultname">@@</a> 님의 건강 상태</h1>
    <div class="result">
        <div class="healthinfoContainer">
        </div>
    </div>
    <div class="result">
    <p class="labeldan">위험</p>
        <div class="resultContainer">
            <div>
                <h2><a style="color: rgb(235, 73, 73); font-size: 1em;font-weight: 700;">위험</a> 요소에 대한 보험 추천</h2>
                <?php
                $count = 0;
                foreach ($danList as $key => $value) {
                    $danger = "SELECT NAME,PRODUCTNAME,PRICE,COMP,KIND,URL FROM PRODUCT,INS WHERE KIND = '$value' AND PRODUCT.INSID = INS.INSID ORDER BY COMP DESC";
                    # sql문 DB로 파싱 후 전송
                    $danger_Result = oci_parse($connect, $danger);
                    oci_execute($danger_Result);
                    $convalue = iconv("EUC-KR", "UTF-8", $value);
                    echo "<br><h3><a>$danobj[$convalue]</a> 에 대한 <a>$convalue</a> 보험추천</h3><br>";
                    echo "<table class='table'>\n<th>보험사</th><th>보험명</th><th>가입금액(원)</th><th>보장금액(원)</th><th>주 보장 항목</th><th>보험사 URL</th>";
                    while (($dan = oci_fetch_array($danger_Result, OCI_ASSOC)) != false) {
                        $name = iconv("EUC-KR", "UTF-8", $dan['NAME']);
                        $productname = iconv("EUC-KR", "UTF-8", $dan['PRODUCTNAME']);
                        $price = iconv("EUC-KR", "UTF-8", $dan['PRICE']);
                        $comp = iconv("EUC-KR", "UTF-8", $dan['COMP']);
                        $kind = iconv("EUC-KR", "UTF-8", $dan['KIND']);
                        $url = iconv("EUC-KR", "UTF-8", $dan['URL']);
                        echo ("
                            <tr>\n
                                <td><div class='insnameDiv'><p align='center' onclick='zzim($count);' class='insname'>{$name}</p></div></td>
                                <td><div><p align='center'>{$productname}</p></div></td>
                                <td><div>{$price}</div></td>
                                <td><div>{$comp}</div></td>
                                <td><div><p align='center'>{$kind}</p></div></td>
                                <td><div><a href='$url'>{$url}</a></div></td>
                            </tr>
                             ");
                        $count = $count + 1;
                            }
                    echo "</table>\n";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="result">
    <p class="labelwarn">주의</p>
        <div class="resultContainer">
            <div>
                <h2><a style="color: rgb(18, 99, 239);font-size: 1em; font-weight: 700;">주의</a> 요소에 대한 보험 추천</h2>
                <?php
                foreach ($warnList as $key => $value) {
                    $warning = "SELECT NAME,PRODUCTNAME,PRICE,COMP,KIND,URL FROM PRODUCT,INS WHERE KIND = '$value' AND PRODUCT.INSID = INS.INSID ORDER BY PRICE ASC";
                    # sql문 DB로 파싱 후 전송
                    $warning_Result = oci_parse($connect, $warning);
                    oci_execute($warning_Result);
                    $convalue = iconv("EUC-KR", "UTF-8", $value);
                    echo "<br><h3><a>$warnobj[$convalue]</a> 에 대한 <a>$convalue</a> 보험추천</h3><br>";
                    echo "<table class='table'>\n<th>보험사</th><th>보험명</th><th>가입금액(원)</th><th>보장금액(원)</th><th>주 보장 항목</th><th>보험사 URL</th>";
                    while (($warn = oci_fetch_array($warning_Result, OCI_ASSOC)) != false) {
                        $name = iconv("EUC-KR", "UTF-8", $warn['NAME']);
                        $productname = iconv("EUC-KR", "UTF-8", $warn['PRODUCTNAME']);
                        $price = iconv("EUC-KR", "UTF-8", $warn['PRICE']);
                        $comp = iconv("EUC-KR", "UTF-8", $warn['COMP']);
                        $kind = iconv("EUC-KR", "UTF-8", $warn['KIND']);
                        $url = iconv("EUC-KR", "UTF-8", $warn['URL']);
                        echo ("
                            <tr>\n
                                <td><div align='center' onclick='zzim($count);' class='insname'>{$name}</div></td>
                                <td><div><p align='center'>{$productname}</p></div></td>
                                <td><div>{$price}</div></td>
                                <td><div>{$comp}</div></td>
                                <td><div><p align='center'>{$kind}</p></div></td>
                                <td><div><a href='$url'>{$url}</a></div></td>
                            </tr>
                             ");
                        $count = $count + 1;
                            }
                    echo "</table>\n";
                }

                    # DB 메모리 할당 및 연결 해제 
                    oci_free_statement($send);
                    oci_close($connect);
                    ?>

            </div>
        </div>
    </div>
    <form method="post" action="zzim.php" >
        <fieldset>
            <div class='hidden'></div>
            <input type='submit' value='선택한 보험사 찜하기' class='zzimbtn'>
        </fieldset>
    </form>
    <script>
        function zzim(int){
            let insname = document.querySelectorAll('.insname');
            console.log(insname.item(int).innerText)
            let result = document.querySelector('.hidden');
                                    varwarn = document.createElement('input');
                                    varwarn.setAttribute('type', 'hidden');
                                    varwarn.setAttribute('name', 'zzim[]');
                                    varwarn.setAttribute('value', insname.item(int).innerText);
                                    result.appendChild(varwarn);
        };
        let insnameDiv = document.querySelectorAll('.table tbody tr td:nth-child(1)');
        function handleClick(event) {
        // div에서 모든 "click" 클래스 제거
        insnameDiv.forEach((e) => {
            e.classList.remove("click");
        });
        // 클릭한 div만 "click"클래스 추가
        event.target.classList.add("click");
        }

        insnameDiv.forEach((e) => {
        e.addEventListener("click", handleClick);
        });
    </script>
    <!--footer-->
    <footer id="foot">
        데이터베이스 및 실습 1조 B789055 전성태, B789071 현동엽, B789033오현석, B789049 이현진<br>
        주제: 개인 건강정보를 통한 맞춤형 보험 상품 추천 시스템 </footer>
    <script>make_feedback()</script>
</body>

</html>
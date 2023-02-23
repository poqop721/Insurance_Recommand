<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>전체 보험 상품</title>
    <script type="text/javascript">
        window.onload = function(){
            getNames();
        }
    </script>
    <link rel="stylesheet" href="css&js/header.css">
    <link rel="stylesheet" href="css&js/curheader.css">
    <link rel="stylesheet" href="css&js/allins.css">

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
                    <a class="curheader" onclick=>전체 보험 상품</a>
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
    <h1 class="resultH1">검색된 보험상품들</h1>
    <div class="result">
        <div class="resultContainer">
            <div>
            <form action = "allIns_ok.php" method="post" class="option">
                        <select name="kind" class="select">
                            <option>고혈압</option>
                            <option>뇌혈관</option>
                            <option>당뇨</option>
                            <option>실비</option>
                            <option>심혈관</option>
                        </select>
                        <td><input type="submit" value="조회" class="allinsbtn"></td>
                        <td><input type="button" onClick="location.href='allIns.php'" value="전체확인" class="allinsbtn"></td>
                    </form>
                    <?php
                        # 500 에러 시 확인 (디버깅용)
                        // error_reporting(E_ALL);
                        // ini_set('display_errors', '1');
                        
                        # 요청받은 항목 변수 확인
                        $kind = $_POST['kind'];
                        
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
                                
                        # Oracle DB 서버 ID/PW
                        $username = "DBA2022G1";
                        $userpassword = "test1234";

                        # Oracle DB 서버 접속
                        $connect = oci_connect($username, $userpassword, $db,'AL32UTF8');

                        # 연결 오류 시 Oracle 오류 메시지 표시
                        if (!$connect) {
                            $e = oci_error();   // For oci_connect errors do not pass a handle
                            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                        }
                        
                        # 두 테이블 Inner 조인 (INSID 중복이므로)
                        $sql = "SELECT INS.NAME, PRODUCT.PRODUCTNAME, PRODUCT.PRICE, PRODUCT.COMP, PRODUCT.KIND, INS.URL, INSNUMBER.TELNUMBER FROM INS JOIN PRODUCT ON INS.INSID = PRODUCT.INSID JOIN INSNUMBER ON INS.TEL = INSNUMBER.TEL WHERE PRODUCT.KIND = '$kind'";
                        
                        # sql문 DB로 파싱 후 전송
                        $send = oci_parse($connect, $sql);
                        oci_execute($send);
                        
                        # 테이블 형태
                        echo "<table class='table'>\n<th>보험사</th><th>보험명</th><th>가입금액(원)</th><th>보장금액(원)</th><th>주 보장 항목</th><th>보험사 URL</th><th>전화번호</th>";
                        while (($row = oci_fetch_array($send, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                            echo "<tr >\n";
                            foreach ($row as $item) {
                                echo "<td><div>".($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;")."</div></td>\n";
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";
                        
                        // DB 메모리 할당 및 연결 해제 
                        oci_free_statement($send);
                        oci_close($connect);
                    ?>
            </div>
        </div>
    </div>
    <!--footer-->
    <footer id='foot'>
        데이터베이스 및 실습 1조 B789055 전성태, B789071 현동엽, B789033 오현석, B789049 이현진<br>
        주제: 개인 건강정보를 통한 보험 상품 추천 시스템    
    </footer>
</body>

</html>    



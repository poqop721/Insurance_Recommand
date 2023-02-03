<?php
# 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();


if (!is_null($_SESSION["ID"])) {

    # Oracle DB 서버 접속
    include('./login_register/connect.php');

    $id = $_SESSION["ID"];

    # sql문 
    $sql = "SELECT * FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$id'";

    # sql문 DB로 파싱 후 전송
    $send = mysqli_query($connect, $sql);

    while ($row = mysqli_fetch_array($send, MYSQLI_ASSOC)) {
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


} else {
    echo "<script>alert('먼저 로그인 해주세요.');location.replace('main.php');</script>";
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
        window.onload = function () {
            getNames();
        }
    </script>
    <link rel="stylesheet" href="css&js/mypage.css">
    <link rel="stylesheet" href="css&js/header.css">
    <link rel="stylesheet" href="css&js/curheader.css">
    <link rel="stylesheet" href="css&js/table.css">

    <script src="css&js/modal.js"></script>
    <script src="css&js/goTo.js"></script>

</head>
<script type='text/javascript'>
        window.onload = function () {
            getNames('<?php echo $_SESSION['name']?>');
        }
    </script>
<body>
    <div id='wrap'>
        <!--header-->
        <header class="header">
            <!--header - nav-->
            <div class="headertitleleft">
                <h2><a href='main.php'>보험추천</a></h2>
            </div>
            <nav class="headernav">
                <span>
                    <a class="otherheader" onclick="location.href='main.php'">보험 추천받기</a>
                </span>
                <span>
                    <a class="otherheader" onclick="toResult(<?php echo $_SESSION['isResult']?>)">추천 받은 보험</a>
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
            <div class="headertitleright">
                <div><a class="name"></a><a>님&nbsp;</a></div>
                <button type="button" onclick="location.href='login_register/logOut.php'"><a id="logout">로그아웃</a><img
                        src="img/logout.png" alt="button" width="32px"></button>
            </div>
        </header>
        <div id='content'>
            <h1 class="resultH1"><a class="name" id="resultname"></a>님이 찜한 보험상품들</h1>
            <div class="result">
                <div class='mypageCon'>
                    <div class="myPageTable">
                        <?php
                        # sql문 (ID확인)
                        $sql = "SELECT PRODUCTID from REFERENCETABLE where CUSTOMER_ID = '$id'";

                        # sql문 DB로 파싱 후 전송
                        $send = mysqli_query($connect, $sql);

                        $isnull = 0;

                        echo "<table class='table' id='zzimTable'>\n<th>보험사</th><th>보험명</th><th>가입금액(원)</th><th>보장금액(원)</th><th>주 보장 항목</th><th></th>";
                        while ($row = mysqli_fetch_array($send, MYSQLI_ASSOC)) {
                            foreach ($row as $item) {
                                $sql2 = "SELECT (SELECT NAME from INS where INSID = (select INSID from PRODUCT where PRODUCTID = '$item'))AS NAME, PRODUCTNAME, PRICE, COMP ,KIND from PRODUCT where PRODUCTID = (SELECT PRODUCTID from PRODUCT where PRODUCTID = '$item')";
                                $send2 = mysqli_query($connect, $sql2);
                                while ($row2 = mysqli_fetch_array($send2, MYSQLI_ASSOC)) {
                                    echo "<tr class='tableDel'>\n";
                                    foreach ($row2 as $item2) {
                                        echo "    <td>" . ($item2 !== null ? htmlentities($item2, ENT_QUOTES) : "&nbsp;") . "</td>\n";
                                        $isnull++;
                                    }
                                    echo "<td><form method='post' action='unzzim.php' ><fieldset class='hid$item'><input type='submit' onClick='del$item();' value='제거' class='unzzimbtn'></fieldset></form></td>";
                                    echo "</tr>\n";
                                }
                                echo "<script>function del$item(){";
                                echo "
                        let hidden = document.querySelector('.hid$item');
                        unzzim = document.createElement('input');
                        unzzim.setAttribute('type', 'hidden');
                        unzzim.setAttribute('name', 'unzzim');
                        unzzim.setAttribute('value', '$item');
                        hidden.appendChild(unzzim);                        
                        ";
                                echo "}</script>";
                            }
                        }
                        echo "</table>\n";

                        if ($isnull == 0) {
                            echo "<a>찜 한 보험사가 아직 없습니다. \n 보험을 추천받고 찜해보세요.</a>";
                            echo "<script>
                    const element = document.getElementById('zzimTable');
                    element.remove();
                    </script>";
                        } else
                            echo "<form method='post' action='unzzim.php' ><fieldset><input type='submit' value='찜 목록 비우기' class='unzzimall'></fieldset></form>";


                        // DB 메모리 할당 및 연결 해제 
                        mysqli_close($connect);

                        ?>
                    </div>
                </div>
            </div><br><br>
            <h1 class="resultH1"><a class="name" id="resultname"></a>님의 개인정보</h1>
            <div class="result">
                <div class='mypageCon'>
                    <div>
                        <table class='table'>
                            <th></th>
                            <th></th>
                            <tr>
                                <td>아이디</td>
                                <td>
                                    <?php echo $id ?>
                                </td>
                            </tr>
                            <tr>
                                <td>생일</td>
                                <td>
                                    <?php $birth = strtotime($birth);
                                    echo date("Y-M-D", $birth) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>키</td>
                                <td>
                                    <?php echo $height ?>
                                </td>
                            </tr>
                            <tr>
                                <td>몸무게</td>
                                <td>
                                    <?php echo $weight ?>
                                </td>
                            </tr>
                            <tr>
                                <td>성별</td>
                                <td>
                                    <?php echo $sex ?>
                                </td>
                            </tr>
                            <tr>
                                <td>이메일</td>
                                <td>
                                    <?php echo $email ?>
                                </td>
                            </tr>
                            <tr>
                                <td>전화번호</td>
                                <td>
                                    <?php echo $phone ?>
                                </td>
                            </tr>
                            <tr>
                                <td>회원 가입 일자</td>
                                <td>
                                    <?php $regdate = strtotime($regdate);
                                    echo date("Y-M-D", $regdate) ?>
                                </td>
                            </tr>
                        </table>
                        <input type="button" onClick="location.href='./update_signup.php'" value="개인정보 수정"
                            class="signupbtn">
                        <input type="button" onClick="location.href='./delete_signup.php'" value="회원 탈퇴"
                            class="signupbtn">
                    </div>
                </div>
            </div>
        </div>
    </div><br><br>
    <!--footer-->
    <footer>
        제작 : 전성태&emsp;|&emsp;전화 : 010-2498-8175&emsp;|&emsp;E-Mail : <a href="mailto:poqop721@naver.com"
            class="footerAtag">poqop721@naver.com</a>
        &emsp;|&emsp; 카카오톡 ID : kdcrafter &emsp;|&emsp; GitHub : <a href="https://github.com/poqop721"
            class="footerAtag">https://github.com/poqop721</a>
    </footer>
</body>

</html>
<?php 
session_start();
include('./login_register/connect.php');
$kind = $_POST['kind'];
$order = $_POST['order'];
# id가 없을 시 접근 불가하도록 하는 거임
if (is_null($_SESSION["ID"])) {
    echo "<script>alert('먼저 로그인 해주세요.');location.replace('main.php');</script>";
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>전체 보험 상품</title>
    <script type="text/javascript">
        window.onload = function () {
            getNames();
        }
    </script>
    <link rel="stylesheet" href="css&js/header.css">
    <link rel="stylesheet" href="css&js/curheader.css">
    <link rel="stylesheet" href="css&js/allins.css">
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
                    <a class="curheader" onclick=>전체 보험 상품</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='myHealthInfo.php'">나의 건강정보</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='myPage.php'">마이페이지</a>
                </span>
            </nav>
            <div class="headertitleright">
                <div><a class="name"></a><a>님&nbsp;</a></div>
                <button type="button" onclick="location.href='login_register/logOut.php'"><a id="logout">로그아웃</a><img
                        src="img/logout.png" alt="button" width="32px"></button>
            </div>
        </header>
        <div id='content'>
            <h1 class="resultH1">전체 보험상품들</h1>
            <div class="result">
                <div class="resultContainer">
                    <div>
                        <form action="allIns.php" method="post" class="option">
                            <select name="kind" class="select">
                                <option disabled <?php if ($kind == null)
                                    echo "SELECTED"; ?>>전체 항목</option>
                                <option <?php if ($kind == "고혈압")
                                    echo "SELECTED"; ?>>고혈압</option>
                                <option <?php if ($kind == "뇌혈관")
                                    echo "SELECTED"; ?>>뇌혈관</option>
                                <option <?php if ($kind == "당뇨")
                                    echo "SELECTED"; ?>>당뇨</option>
                                <option <?php if ($kind == "실비")
                                    echo "SELECTED"; ?>>실비</option>
                                <option <?php if ($kind == "심혈관")
                                    echo "SELECTED"; ?>>심혈관</option>
                            </select>
                            <select name='order' class='select'>
                                <option disabled>정렬 선택</option>
                                <?php 
                                if ($order == null || $order == '보험사별') {
                                $orderStr = 'ORDER BY NAME ASC';
                                    echo "<option selected>보험사별</option>
                                    <option >보장금액 높은순</option>
                                <option>가입금액 낮은순</option>";
                                } else if ($order == '가입금액 낮은순') {
                                $orderStr = 'ORDER BY PRICE ASC';
                                echo "<option>보험사별</option>
                                <option>보장금액 높은순</option>
                                <option selected>가입금액 낮은순</option>";
                                } else {
                                $orderStr = 'ORDER BY COMP DESC';
                                echo "<option >보험사별</option>
                                <option selected>보장금액 높은순</option>
                                <option>가입금액 낮은순</option>";
                                }
                                ?>
                            </select>
                            <td><input type="submit" value="조회" class="allinsbtn"></td>
                            <td><input type="button" onClick="location.href='allIns.php'" value="전체확인"
                                    class="allinsbtn">
                            </td>
                        </form>
                        <?php
                        # 두 테이블 Inner 조인 (INSID 중복이므로)
                        if ($kind == null)
                            $sql = "SELECT INS.NAME, PRODUCT.PRODUCTNAME, PRODUCT.PRICE, PRODUCT.COMP, PRODUCT.KIND, INS.URL, INS.TEL FROM INS JOIN PRODUCT ON INS.INSID = PRODUCT.INSID $orderStr";
                        else
                            $sql = "SELECT INS.NAME, PRODUCT.PRODUCTNAME, PRODUCT.PRICE, PRODUCT.COMP, PRODUCT.KIND, INS.URL, INS.TEL FROM INS JOIN PRODUCT ON INS.INSID = PRODUCT.INSID WHERE PRODUCT.KIND = '$kind' $orderStr";
                        # sql문 DB로 파싱 후 전송
                        $send = mysqli_query($connect, $sql);
                        # 테이블 형태
                        echo "<table class='table'>\n<th>보험사</th><th>보험명</th><th>가입금액(원)</th><th>보장금액(원)</th><th>주 보장 항목</th><th>보험사 URL</th><th>전화번호</th>";
                        while ($row = mysqli_fetch_array($send, MYSQLI_ASSOC)) {
                            echo "<tr >\n";
                            foreach ($row as $item) {
                                echo "<td><div>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</div></td>\n";
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";

                        // DB 메모리 할당 및 연결 해제 
                        mysqli_close($connect);
                        ?>
                    </div>
                </div>
            </div>
            <script>
                let first = document.querySelectorAll('td:nth-child(3)>div');
                let sec = document.querySelectorAll('td:nth-child(4)>div');
                let url = document.querySelectorAll('td:nth-child(6)>div');

                const changeTag = async () => {
                    for (let elem3 of url) {
                        let inner = elem3.innerText;
                        await delInnerText(elem3)
                        urldiv = document.createElement('a');
                        urldiv.setAttribute('href', inner)
                        urldiv.setAttribute('style', 'cursor:pointer;');
                        urldiv.innerText = inner;
                        elem3.appendChild(urldiv)
                    }
                }

                function delInnerText(elem) {
                    elem.innerText = '';
                }

                for (let elem1 of first) {
                    elem1.innerText += ' 원';
                }
                for (let elem2 of sec) {
                    elem2.innerText += ' 원';
                }
                changeTag();

            </script>
        </div>
    </div>
    <!--footer-->
    <footer>
        제작 : 전성태&emsp;|&emsp;전화 : 010-2498-8175&emsp;|&emsp;E-Mail : <a href="mailto:poqop721@naver.com"
            class="footerAtag">poqop721@naver.com</a>
        &emsp;|&emsp; 카카오톡 ID : kdcrafter &emsp;|&emsp; GitHub : <a href="https://github.com/poqop721"
            class="footerAtag">https://github.com/poqop721</a>
    </footer>
</body>

</html>
<?php

# 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# 세션 로그인 값을 통해 로그인 여부 확인
$id = $_SESSION["ID"];


# id가 없을 시 접근 불가하도록 하는 거임
if (!is_null($_SESSION["ID"])) {

    # Oracle DB 서버 접속
    include('./login_register/connect.php');


    $warnarr = $_SESSION['warning'];
    $danarr = $_SESSION['danger'];
    $warningFeedback = $_SESSION['warningFeedback'];
    $dangerFeedback = $_SESSION['dangerFeedback'];

    $orderDan = $_POST['orderDan'];
    $orderWarn = $_POST['orderWarn'];



    if ($warnarr == null) {
        $len = 0;
    } else {
        $len = (int) count($warnarr);
        $warnarr = array_slice($warnarr, 0, $len / 2);
        $checkWarn = array();
        for ($i = 0; $i < count($warnarr); $i++) {
            if (in_array($warnarr[$i], $checkWarn)) {
                if ($warnobj[$warnarr[$i]] != $warningFeedback[$i])
                    $warnobj[$warnarr[$i]] = $warnobj[$warnarr[$i]] . ' , ' . $warningFeedback[$i];
            } else {
                $warnobj[$warnarr[$i]] = $warningFeedback[$i];
                array_push($checkWarn, $warnarr[$i]);
            }
            // echo "<a>$warnarr[$i] == $warningFeedback[$i]</a>";
        }
        $warnList = array();
        for ($i = 0; $i < count($warnarr); $i++) {
            array_push($warnList, $warnarr[$i]);
        }
        $warnList = array_unique($warnList);
    }


    if ($danarr == null) {
        $len2 = 0;
    } else {
        $len2 = (int) count($danarr);
        $danarr = array_slice($danarr, 0, $len2 / 2);
        $checkDan = array();
        for ($i = 0; $i < count($danarr); $i++) {
            if (in_array($danarr[$i], $checkDan)) {
                if ($danobj[$danarr[$i]] != $dangerFeedback[$i])
                    $danobj[$danarr[$i]] = $danobj[$danarr[$i]] . ' , ' . $dangerFeedback[$i];
            } else {
                $danobj[$danarr[$i]] = $dangerFeedback[$i];
                array_push($checkDan, $danarr[$i]);
            }
            // echo "<a>$danarr[$i] == $dangerFeedback[$i]</a>";
        }
        $danList = array();
        for ($i = 0; $i < count($danarr); $i++) {
            array_push($danList, $danarr[$i]);
        }
        $danList = array_unique($danList);
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
    <title>추천 받은 보험</title>
    <script type="text/javascript">
        window.onload = function () {
            getNames();
        }
    </script>
    <link rel="stylesheet" href="css&js/result.css">
    <link rel="stylesheet" href="css&js/header.css">
    <link rel="stylesheet" href="css&js/curheader.css">
    <link rel="stylesheet" href="css&js/table.css">

    <script src="css&js/modal.js"></script>
    <script src="css&js/goTo.js"></script>
    <script src="css&js/recommand.js"></script>

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
            <div class="headertitleright">
                <div><a class="name"></a><a>님&nbsp;</a></div>
                <button type="button" onclick="location.href='login_register/logOut.php'"><a id="logout">로그아웃</a><img
                        src="img/logout.png" alt="button" width="32px"></button>
            </div>
        </header>
        <div id='content'>
            <!--추천-->
            <h1 class="resultH1">&nbsp;&nbsp;<a class="name" id="resultname"></a> 님의 건강 상태</h1>
            <div class="result">
                <div class="healthinfoContainer">
                </div>
            </div>
            <?php
            if ($len2 != 0) {
                echo '<div class="result"><p class="labeldan">위험</p><div class="resultContainer"><div><h2><a style="color: rgb(235, 73, 73); font-size: 1em;font-weight: 700;">위험</a> 요소에 대한 보험 추천</h2>';
                echo "     
                <form action='result.php' method='post' class='option'>                   
                <a>정렬 : </a><select name='orderDan' class='select'>
                <option disabled >선택</option>";
                if ($orderDan == null || $orderDan == '보장금액 높은순') {
                    $orderStrDan = 'ORDER BY COMP DESC';
                    echo "<option selected>보장금액 높은순</option>
                    <option >가입금액 낮은순</option>
                    <option >보험사별</option>";
                } else if ($orderDan == '가입금액 낮은순') {
                    $orderStrDan = 'ORDER BY PRICE ASC';
                    echo "<option >보장금액 높은순</option>
                    <option selected>가입금액 낮은순</option>
                    <option >보험사별</option>";
                } else {
                    $orderStrDan = 'ORDER BY NAME ASC';
                    echo "<option >보장금액 높은순</option>
                    <option >가입금액 낮은순</option>
                    <option selected>보험사별</option>";
                }
                echo "</select>
                <td><input type='submit' value='조회' class='selectbtn'></td>
                </form>";
            }
            if ($len2 != 0) {
                $count = 0;
                foreach ($danList as $key => $value) {
                    $danger = "SELECT NAME,PRODUCTNAME,PRICE,COMP,KIND,URL,TEL FROM PRODUCT,INS WHERE KIND = '$value' AND PRODUCT.INSID = INS.INSID $orderStrDan";
                    # sql문 DB로 파싱 후 전송
                    $danger_Result = mysqli_query($connect, $danger);
                    echo "<h3><a>$danobj[$value]</a> 에 대한 <a>$value</a> 보험추천</h3>";
                    echo "<table class='table'>\n<tr class='trFirst'><th>보험사</th><th>보험명</th><th>보장금액</th><th>가입금액</th><th>주 보장 항목</th><th>보험사 URL</th><th>전화번호</th></tr>";
                    while (($dan = mysqli_fetch_array($danger_Result)) != false) {
                        $name = $dan['NAME'];
                        $productname = $dan['PRODUCTNAME'];
                        $price = $dan['PRICE'];
                        $comp = $dan['COMP'];
                        $kind = $dan['KIND'];
                        $url = $dan['URL'];
                        $tel = $dan['TEL'];
                        echo ("
                            <tr onclick='javascript:trClick(this,\"d\");'>\n
                                <td id='danName'><div align='center'>{$name}</div></td>
                                <td><div><p align='center'>{$productname}</p></div></td>
                                <td><div class='greenbck'>{$comp} 원</div></td>
                                <td><div class='redbck'>{$price} 원</div></td>
                                <td><div><p align='center'>{$kind}</p></div></td>
                                <td><div><a href='$url'>{$url}</a></div></td>
                                <td><div>{$tel}</div></td>
                                <td><div>찜</div></td>
                            </tr>
                             ");
                        $count = $count + 1;
                    }
                    echo "</table>\n";
                }
                echo "</div></div></div>";
            }
            ?>
            <?php
            if ($len != 0) {
                echo '<div class="result"><p class="labelwarn">주의</p><div class="resultContainer"><div><h2><a style="color: rgb(18, 99, 239);font-size: 1em; font-weight: 700;">주의</a> 요소에 대한 보험 추천</h2>';
                echo "     
                <form action='result.php' method='post' class='option'>                   
                <a>정렬 : </a><select name='orderWarn' class='select2'>
                <option disabled >선택</option>";
                if ($orderWarn == null || $orderWarn == '가입금액 낮은순') {
                    $orderStrWarn = 'ORDER BY PRICE ASC';
                    echo "<option selected>가입금액 낮은순</option>
                    <option >보장금액 높은순</option>
                    <option >보험사별</option>";
                } else if ($orderWarn == '보장금액 높은순') {
                    $orderStrWarn = 'ORDER BY COMP DESC';
                    echo "<option >가입금액 낮은순</option>
                    <option selected>보장금액 높은순</option>
                    <option >보험사별</option>";
                } else {
                    $orderStrWarn = 'ORDER BY NAME ASC';
                    echo "<option >가입금액 낮은순</option>
                    <option >보장금액 높은순</option>
                    <option selected>보험사별</option>";
                }
                echo "</select>
                <td><input type='submit' value='조회' class='selectbtn2'></td>
                </form>";
            }
            if ($len != 0) {
                foreach ($warnList as $key => $value) {
                    $warning = "SELECT NAME,PRODUCTNAME,PRICE,COMP,KIND,URL,TEL FROM PRODUCT,INS WHERE KIND = '$value' AND PRODUCT.INSID = INS.INSID $orderStrWarn";
                    # sql문 DB로 파싱 후 전송
                    $warning_Result = mysqli_query($connect, $warning);
                    echo "<br><h3><a>$warnobj[$value]</a> 에 대한 <a>$value</a> 보험추천</h3>";
                    echo "<table class='table'>\n<tr class='trFirst'><th>보험사</th><th>보험명</th><th>보장금액</th><th>가입금액</th><th>주 보장 항목</th><th>보험사 URL</th><th>전화번호</th></tr>";
                    while (($warn = mysqli_fetch_array($warning_Result)) != false) {
                        $name = $warn['NAME'];
                        $productname = $warn['PRODUCTNAME'];
                        $price = $warn['PRICE'];
                        $comp = $warn['COMP'];
                        $kind = $warn['KIND'];
                        $url = $warn['URL'];
                        $tel = $warn['TEL'];
                        echo ("
                            <tr onclick='javascript:trClick(this,\"w\");'>\n
                                <td id='warnName'><div align='center'>{$name}</div></td>
                                <td><div><p align='center'>{$productname}</p></div></td>
                                <td><div class='greenbck'>{$comp} 원</div></td>
                                <td><div class='redbck'>{$price} 원</div></td>
                                <td><div><p align='center'>{$kind}</p></div></td>
                                <td><div><a href='$url'>{$url}</a></div></td>
                                <td><div>{$tel}</div></td>
                                <td><div>찜</div></td>
                            </tr>
                             ");
                        $count = $count + 1;
                    }
                    echo "</table>\n";
                }
                echo "</div></div></div>";
            }

            if ($len == 0 && $len2 == 0) {
                echo "<div class='result'><div class='resultContainer'><div class='emptyCon'><a id='noHealth'>추천드릴 보험이 없습니다.</a></div></div></div>";
            }
            if ($len != 0 || $len2 != 0) {
                echo "<form method='post' action='zzim.php' class='zzimCon'>
    <fieldset>
        <div class='hidden'></div>
        <input type='submit' value='선택한 보험 찜하기' class='zzimbtn'>
    </fieldset>
</form>";
            }
            # DB 메모리 할당 및 연결 해제 
            mysqli_close($connect);
            ?>
            <script>
                function trClick(tr, sort) {
                    var i;
                    var tabNum = tr.parentNode.children.length;

                    if (sort === 'd') {
                        if (tr.children[7].className != 'click') {
                            tr.children[7].className = 'click';
                            tr.children[0].className = 'click';
                            console.log(tr.children[1].innerText)
                            let result = document.querySelector('.hidden');
                            varwarn = document.createElement('input');
                            varwarn.setAttribute('type', 'hidden');
                            varwarn.setAttribute('name', 'zzim[]');
                            varwarn.setAttribute('value', tr.children[1].innerText);
                            varwarn.setAttribute('id', tr.children[1].innerText);
                            result.appendChild(varwarn);
                        }
                        else {
                            tr.children[7].className = '';
                            tr.children[0].className = '';
                            const element = document.getElementById(tr.children[1].innerText);
                            element.remove();
                        }
                    }
                    else if (sort === 'w') {
                        if (tr.children[7].className != 'click2') {
                            tr.children[7].className = 'click2';
                            tr.children[0].className = 'click2';
                            console.log(tr.children[1].innerText)
                            let result = document.querySelector('.hidden');
                            varwarn = document.createElement('input');
                            varwarn.setAttribute('type', 'hidden');
                            varwarn.setAttribute('name', 'zzim[]');
                            varwarn.setAttribute('value', tr.children[1].innerText);
                            varwarn.setAttribute('id', tr.children[1].innerText);
                            result.appendChild(varwarn);
                        }
                        else {
                            tr.children[7].className = '';
                            tr.children[0].className = '';
                            const element = document.getElementById(tr.children[1].innerText);
                            element.remove();
                        }
                    }
                }
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
    <script>make_feedback()</script>
</body>

</html>
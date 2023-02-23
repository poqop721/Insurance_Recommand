<?php 
    session_start();
    include('./login_register/connect.php');
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
    <title>나의 건강정보</title>
    <script type="text/javascript">
        window.onload = function () {
            getNames();
        }
    </script>
    <link rel="stylesheet" href="css&js/myhealthinfo.css">
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
                    <a class="otherheader" onclick="toResult(<?php echo $_SESSION['isResult']?>)">추천 받은 보험</a>
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
            <div class="headertitleright">
                <div><a class="name"></a><a>님&nbsp;</a></div>
                <button type="button" onclick="location.href='login_register/logOut.php'"><a id="logout">로그아웃</a><img
                        src="img/logout.png" alt="button" width="32px"></button>
            </div>
        </header>
        <div id='content'>
            <!--세부건강정보-->
            <h1 class="resultH1"><a class="name" id="resultname"></a>님의 건강 정보 기록</h1>
            <div class="result">
                <div class="resultContainer">
                    <?php
                    $id = $_SESSION["ID"];

                    function healthColor($int, $warnarr, $danarr) {
                        if (in_array($int, $warnarr))
                            echo "style='color:rgb(18, 99, 239);'";
                        else if (in_array($int, $danarr))
                            echo "style='color:rgb(235, 73, 73);'";
                    };



                        $query = "SELECT HEALTH_INFO,CREATED_DATE, HEALTH.HEALTH_BP, HEALTH.HEALTH_BOS,HEALTH.HEALTH_BFP, HEALTH.HEALTH_SMM, HEALTH.HEALTH_MBW, HEALTH.HEALTH_BM,WARN_LIST,DAN_LIST
                        FROM CUSTOMERINFO JOIN HEALTH ON CUSTOMERINFO.CUSTOMER_ID = HEALTH.CUSTOMER_ID
                        WHERE CUSTOMERINFO.CUSTOMER_ID = '$id'
                        ORDER BY HEALTH.CREATED_DATE DESC";
                        $info = mysqli_query($connect, $query);

                        $isnull = 0;

                        echo "<table id='healthTable' class='table'>\n<th>입력된 날짜</th><th>혈압</th><th>혈중산소포화도</th><th>체지방률</th><th>골격근량</th><th>체수분</th><th>기초대사량</th><th></th>";
                        while (($in = mysqli_fetch_array($info, MYSQLI_ASSOC)) != false) {
                            $warnArr = explode( ',', $in['WARN_LIST'] );
                            $danArr = explode( ',', $in['DAN_LIST'] );
                            $healthINFO = $in['HEALTH_INFO'];
                            echo "
                            <tr>\n
                                <td>{$in['CREATED_DATE']}</td>
                                <td class='BP'"; healthColor('1',$warnArr,$danArr); echo ">{$in['HEALTH_BP']}</td>
                                <td class='BOS' "; healthColor('2',$warnArr,$danArr); echo ">{$in['HEALTH_BOS']}</td>
                                <td class='BFP' "; healthColor('3',$warnArr,$danArr); echo ">{$in['HEALTH_BFP']}</td>
                                <td class='SMM' "; healthColor('4',$warnArr,$danArr); echo ">{$in['HEALTH_SMM']}</td>
                                <td class='MBW' "; healthColor('5',$warnArr,$danArr); echo ">{$in['HEALTH_MBW']}</td>
                                <td class='BM' "; healthColor('6',$warnArr,$danArr); echo ">{$in['HEALTH_BM']}</td>
                                <td><form method='post' action='delHealth.php' ><fieldset class='hid$healthINFO'><input type='submit' onClick='del$healthINFO();' value='제거' class='delHealthbtn'></fieldset></form></td>
                            </tr>";
                            echo "<script>function del$healthINFO(){";
                                echo "
                        let hidden = document.querySelector('.hid$healthINFO');
                        unzzim = document.createElement('input');
                        unzzim.setAttribute('type', 'hidden');
                        unzzim.setAttribute('name', 'delHealth');
                        unzzim.setAttribute('value', '$healthINFO');
                        hidden.appendChild(unzzim);                        
                        ";
                                echo "}</script>";
                            $isnull++;
                        }
                        ;
                        echo "</table>\n";


                    if ($isnull == 0) {
                        echo "<div class='emptyCon'><a id='noHealth'>건강정보가 아직 없습니다. <br> '보험 추천받기' 탭에서 건강정보를 입력해주세요.</a></div>";
                        echo "<script>
            const element = document.getElementById('healthTable');
            element.remove();
            </script>";
                    } else
                        echo "<form method='post' action='delHealth.php' ><fieldset><input type='submit' name='reset' value='건강 기록 초기화' class='resetAll'></fieldset></form>";

                    mysqli_close($connect);
                    ?>
                </div>
            </div>
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
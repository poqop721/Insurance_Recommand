<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>보험상품추천사이트</title>
    <link rel="stylesheet" href="css&js/main.css">
    <link rel="stylesheet" href="css&js/header.css">
    <link rel="stylesheet" href="css&js/healthData.css">

    <link rel="stylesheet" href="css&js/curheader.css">

    <script src="css&js/modal.js"></script>
    <script src="css&js/goTo.js"></script>
    <script src="css&js/healthInput.js"></script>
    <script src="css&js/recommand.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    <!--modal-->
    <div id="modal" class="modalbackground">
        <div class="modalbox">
            <div class="content">
                <!-- POST 방식으로 넘겨야 됨 -->
                <form method="post" action="login_register/checkLogin.php" class="loginform">
                    <fieldset class="loginfieldset">
                        <h1 class="loginH1">로그인</h1>
                        <!--아이디-->
                        아이디<br><input type="text" name="ID" placeholder="ID를 입력하세요."><br><br>
                        <!--비밀번호-->
                        비밀번호 <br><input type="password" name="PW" id="PW" placeholder="비밀번호를 입력하세요."><br><br>
                        <hr>
                        <br>
                        <!--회원가입 버튼-->
                        <input type="submit" value="로그인">
                        <input type="button" class="signupbtn" onclick="location.href='./signup.php'" value="회원가입">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        window.onload = function () {
            loginmodal();
            getNames();
        }
    </script>
    <!--header-->
    <div>
        <!--Header Title (Left)-->
        <div class="headertitleleft">
            <h2><a href='main.php'>보험추천</a></h2>
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
                    <a class="curheader" onclick=>보험 추천받기</a>
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
                    <a class="otherheader" onclick="location.href='myPage.php'">마이페이지</a>
                </span>
            </nav>
        </header>
    </div>
    <div class="blank"></div>
    <div class="healthContainer">
        <form method="post" action="getHealth.php" class="section">
            <input type="radio" name="slide" id="slide01" checked>
            <input type="radio" name="slide" id="slide02">
            <input type="radio" name="slide" id="slide03">
            <input type="radio" name="slide" id="slide04">
            <input type="radio" name="slide" id="slide05">
            <input type="radio" name="slide" id="slide06">
            <input type="radio" name="slide" id="slide07">
            <input type="radio" name="slide" id="slide08">
            <fieldset class="slidewrap">
                <ul class="slidelist">
                    <!-- 슬라이드 영역 -->
                    <li class="slideitem">
                        <div class="textbox">
                            <h3>시작하기</h3>
                            <p><a></a>다음은 <a class="name"></a> 회원님의 신체 정보입니다.<br>수정이 필요하신 경우 수정하신 후 오른쪽 화살표를 클릭해주세요.</p>
                            <div>나이 : <input type="number" name="age" id="age" value="">
                                <!--value 부분에 나이-->
                                <a>&nbsp; 살</a><br>
                                &nbsp;&nbsp;&nbsp;&nbsp; 키 : <input type="number" name="height" id="height" value=""
                                    step="0.1">
                                <!--value부분에 키-->
                                <a>&nbsp; cm</a><br>
                                체중 : <input type="number" name="weight" id="weight" value="" step="0.1">
                                <!--value부분에 몸무게-->
                                <a>&nbsp; kg</a><br>
                                <div class="radio">
                                    <a>성별 : </a>
                                    <!--이부분에 성별-->
                                    <span id="sex">남</span>
                                    <input type="radio" name="sex" value="man" checked />
                                    <span id="sex">여</span>
                                    <input type="radio" name="sex" value="woman" />
                                </div>
                            </div>
                        </div>
                        <script>
                            document.getElementById("age").value = localStorage.getItem('age');
                            document.getElementById("weight").value = localStorage.getItem('weight');
                            document.getElementById("height").value = localStorage.getItem('height');
                            document.getElementById("sex").value = localStorage.getItem('sex');
                        </script>
                    </li>
                    <li class="slideitem">
                        <a>
                            <div class="textbox">
                                <h3>혈압</h3>
                                <ul>
                                    <li>혈압 측정 전 최소 5분 동안 안정하며, 조용한 환경에서 측정합니다.</li>
                                    <li>측정 30분 전 카페인 섭취, 운동, 흡연, 목욕, 음주를 삼가야 합니다.</li>
                                    <li>혈압 측정 중에는 이야기를 하지 않아야 합니다.</li>
                                    <li>등은 바르게 기대고 앉아서 측정합니다.</li>
                                    <li>양발은 평평한 평지 위에 내리고, 발을 꼬지 앉습니다.</li>
                                    <li>위팔을 테이블에 놓고 와이셔츠 정도의 얇은 옷 위에서 측정합니다. </li>
                                </ul>
                                <div><input type="number" name="BP" id="BP" placeholder="혈압을 입력해주세요." step="0.1">
                                    <a>&nbsp; mmHg</a>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="slideitem">
                        <a>

                            <div class="textbox">
                                <h3>혈중산소포화도(SpO2)</h3>
                                <ul>
                                    <li>
                                        혈중산소포화도란 혈중 산소 농도를 가리킵니다.
                                    </li>
                                    <li>
                                        혈중산소포화도의 정상범위는 95~100 입니다.
                                    </li>
                                    <li>
                                        혈중산소포화도가 90~95일 때는 저산소증 주의 단계입니다.
                                    </li>
                                    <li>
                                        혈중산소포화도가 80~90 일 때는 저산소증으로 호흡이 곤란한 상태가 됩니다.
                                    </li>
                                    <li>
                                        혈중산소포화도가 80 이하로 떨어지면 위독한 상태 입니다.
                                    </li>
                                </ul>
                                <div><input type="number" name="BOS" id="BOS" placeholder="혈중산소포화도를 입력해주세요." step="0.1">
                                    <a>&nbsp; %</a>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="slideitem">
                        <a>

                            <div class="textbox">
                                <h3>체지방률</h3>
                                <p><a class="name">@@</a> 회원님의 키와 체중으로 계산된 체지방률은 <a id="calcBFP"></a> % 입니다.<br>
                                    맞으시다면 다음으로 넘어가 주시고 수정이 필요할 시 수정해주세요.
                                </p>
                                <ul>
                                    <li>계산법 : 체지방률 = 체중(kg)를 키(m)의 제곱으로 나눈 값</li>
                                </ul>
                                <div><input type="number" name="BFP" id="BFP" value="0" step="0.01">
                                    <a>&nbsp; %</a>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="slideitem">
                        <a>

                            <div class="textbox">
                                <h3>골격근량</h3>
                                <p>골격근량 계산법은 근육량 X 0.577입니다.<br>
                                    근육량을 입력하시면 골격근량을 계산해드립니다.<br>
                                    혹은 골격근량을 아시는 경우 '골격근량'칸에 바로 입력해주세요.<br>
                                </p>
                                <div>
                                    근육량 : <input type="number" name="muscle" id="muscle" placeholder="근육량">
                                    <a>&nbsp; kg</a><br>
                                    골격근량 : <input type="number" name="SMM" id="SMM" placeholder="골격근량" step="0.001">
                                    <a>&nbsp; kg</a>
                                </div>
                            </div>
                            <script>
                                $("#muscle").on("propertychange change paste input", function () {
                                    var smm = fnReplace($("#muscle").val()) * 0.577;
                                    smm = smm.toFixed(3);
                                    $("#SMM").val(smm);
                                });
                                function fnReplace(val) {
                                    var ret = 0;
                                    if (typeof val != "undefined" && val != null && val != "") {
                                        ret = Number(val.replace(/,/gi, ''));
                                    }
                                    return ret;
                                }
                            </script>
                        </a>
                    </li>
                    <li class="slideitem">
                        <a>

                            <div class="textbox">
                                <h3>체수분량</h3>
                                <ul>
                                    <li>체수분량은 신체의 조직, 혈액, 근육 등 모든 곳에 존재하는 수분의 양을 말합니다.</li>
                                    <li>남성의 경우 적정 체수분량이 60%며, 여성은 50~55%입니다.</li>
                                    <li>본인 몸에 맞는 하루 물 섭취량을 알아보기 위해서는 체중(kg)에 0.03을 곱하면 됩니다. </li>
                                </ul>
                                <div><input type="number" name="MBW" id="MBW" placeholder="체수분량을 입력해주세요." step="0.1">
                                    <a>&nbsp; %</a>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="slideitem">
                        <a>

                            <div class="textbox">
                                <h3>기초대사량</h3>
                                <p><a class="name">@@</a> 회원님의 체중, 키, 나이, 성별로 계산된 기초대사량은 <a id="calcBM"></a> kcal
                                    입니다.<br>
                                    맞으시다면 다음으로 넘어가 주시고 수정이 필요할 시 수정해주세요.
                                </p>
                                <ul>
                                    <li>계산법 - 남자 : 66.47 + (13.75 X 체중) + (5 X 키) - (6.76 X 나이) )</li>
                                    <li>계산법 - 여자 : 655.1 + (9.56 X 체중) + (1.85 X 키) - (4.68 X 나이) )</li>
                                </ul>
                                <div><input type="number" name="BM" id="BM" value="1728" step="0.01">
                                    <a>&nbsp; kcal</a>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="slideitem">
                        <a>

                            <div class="textbox">
                                <h3>최종 확인</h3>
                                <p><a class="name">@@</a> 회원님의 건강 정보입니다.</p>
                                <ul>
                                    <li><a class="resultAge"></a>세, <a class="resultSex"></a></li>
                                    <li>키는 <a class="resultHeight"></a> cm, 체중은 <a class="resultWeight"></a> kg</li>
                                    <li>혈압 : <a class="resultBP"></a> mmhg</li>
                                    <li>혈중산소포화도 : <a class="resultBOS"></a> %</li>
                                    <li>체지방률 : <a class="resultBFP"></a> %</li>
                                    <li>골격근량 : <a class="resultSMM"></a> kg</li>
                                    <li>체수분량 : <a class="resultMBW"></a> %</li>
                                    <li>기초대사량 : <a class="resultBM"></a> kcal</li>
                                </ul>
                                <p class="p2">전부 맞으시다면 오른쪽 제출 버튼을 눌러주세요.</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a>
                            <img src="img/paging/slide.jpg" />
                        </a>
                    </li>

                    <!-- 좌,우 슬라이드 버튼 -->
                    <div class="slide-control">
                        <div>
                            <label id="label1" for="slide02" class="right"></label>
                        </div>
                        <div>
                            <label for="slide01" class="left"></label>
                            <label id="label2" for="slide03" class="right"></label>
                        </div>
                        <div>
                            <label for="slide02" class="left"></label>
                            <label id="label3" for="slide04" class="right"></label>
                        </div>
                        <div>
                            <label for="slide03" class="left"></label>
                            <label id="label4" for="slide05" class="right"></label>
                        </div>
                        <div>
                            <label for="slide04" class="left"></label>
                            <label id="label5" for="slide06" class="right"></label>
                        </div>
                        <div>
                            <label for="slide05" class="left"></label>
                            <label id="label6" for="slide07" class="right"></label>
                        </div>
                        <div>
                            <label for="slide06" class="left"></label>
                            <label id="label7" for="slide08" class="right"></label>
                        </div>
                        <div>
                            <label for="slide07" class="left"></label>
                            <label id="label8" for="slide09" class="finalsubmit">
                            <input type="submit" value="" class="submitbtn" onclick="showResult()"></label>
                        </div>
                    </div>
                    <script>
                        document.getElementById('label1').onclick = function (event) {
                            if (document.getElementById('age').value !== '' && document.getElementById('height').value !== '' &&
                                document.getElementById('weight').value !== '') {
                                document.querySelector(".disabled1").style.pointerEvents = "all";
                                document.querySelector(".disabled1").style.opacity = "1";
                                calHealthInput();
                                return true;
                            }
                            else {
                                alert("먼저 빈칸을 채워주세요.");
                                return false;
                            }
                        };

                        document.getElementById('label2').onclick = function (event) {
                            if (document.getElementById('BP').value !== '') {
                                document.querySelector(".disabled2").style.pointerEvents = "all";
                                document.querySelector(".disabled2").style.opacity = "1";
                                return true;
                            }
                            else {
                                if (BPCheck() === true) return true;
                                else return false;
                            }
                        };

                        document.getElementById('label3').onclick = function (event) {
                            if (document.getElementById('BOS').value !== '') {
                                document.querySelector(".disabled3").style.pointerEvents = "all";
                                document.querySelector(".disabled3").style.opacity = "1";
                                return true;
                            }
                            else {
                                if (BOSCheck() === true) return true;
                                else return false;
                            }
                        };

                        document.getElementById('label4').onclick = function (event) {
                            if (document.getElementById('BFP').value !== '' && document.getElementById('BFP').value !== '0') {
                                document.querySelector(".disabled4").style.pointerEvents = "all";
                                document.querySelector(".disabled4").style.opacity = "1";
                                return true;
                            }
                            else {
                                document.getElementById('BFP').value =
                                    document.getElementById('calcBFP').innerHTML;
                                return true;
                            }
                        };

                        document.getElementById('label5').onclick = function (event) {
                            if (document.getElementById('SMM').value !== '' && document.getElementById('SMM').value !== '0'
                                && document.getElementById('SMM').value !== '0.000') {
                                document.querySelector(".disabled5").style.pointerEvents = "all";
                                document.querySelector(".disabled5").style.opacity = "1";
                                return true;
                            }
                            else {
                                if (SMMCheck() === true) return true;
                                else return false;
                            }
                        };

                        document.getElementById('label6').onclick = function (event) {
                            if (document.getElementById('MBW').value !== '') {
                                document.querySelector(".disabled6").style.pointerEvents = "all";
                                document.querySelector(".disabled6").style.opacity = "1";
                                return true;
                            }
                            else {
                                if (MBWCheck() === true) return true;
                                else return false;
                            }
                        };
                        document.getElementById('label7').onclick = function (event) {
                            if (document.getElementById('BM').value !== '' && document.getElementById('BM').value !== '0') {
                                document.querySelector(".disabled7").style.pointerEvents = "all";
                                document.querySelector(".disabled7").style.opacity = "1";
                                setResult();
                                return true;
                            }
                            else {
                                document.getElementById('BM').value =
                                    document.getElementById('calcBM').innerHTML;
                                return true;
                            }
                        };
                    </script>

                </ul>
                <!-- <input type = "hidden" name = "warning" value ="diabetes" />
                <input type = "hidden" name = "danger" value ="expenses" /> -->
                <?php 
                foreach($postvalue as $value)
                {
                    echo '<input type="hidden" name="result[]" value="'. $value. '">';
                } ?>
            </fieldset>
            <!-- 페이징 -->
            <ul class="slide-pagelist">
                <li><label for="slide01">시작하기</label></li>
                <li><label class="disabled1" for="slide02">혈압</label></li>
                <li><label class="disabled2" for="slide03">혈중산소포화도</label></li>
                <li><label class="disabled3" for="slide04">체지방률</label></li>
                <li><label class="disabled4" for="slide05">골격근량</label></li>
                <li><label class="disabled5" for="slide06">체수분</label></li>
                <li><label class="disabled6" for="slide07">기초대사량</label></li>
                <li><label id="selectFinalBar" class="disabled7" for="slide08">최종확인</label></li>
                <script>
                    document.getElementById('selectFinalBar').onclick = function (event) {
                        setResult();
                    };
                </script>
            </ul>
        </form>
    </div>
    <!--footer-->
    <footer>
        데이터베이스 및 실습 1조 B789055 전성태, B789071 현동엽, B789033 오현석, B789049 이현진<br>
        주제: 개인 건강정보를 통한 보험 상품 추천 시스템
    </footer>
</body>

</html>
<!DOCTYPE html>
<html lang="kr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>회원가입</title>
        <link rel="stylesheet" id="signup" href="css&js/signup.css">
        <script src="css&js/pwdcheck.js"></script>
    </head>
    
    <body>
        <!-- POST 방식으로 값을 넘겨야 됨 -->
        <form action = "./login_register/signup_ok.php" method="POST">
            <fieldset>
            <div>
            <h1>회원가입</h1><br>
            </div>
            <!--아이디-->
            아이디<br>
            <input type="text" name="ID" placeholder="ID를 입력하세요." required><br><br>
            <!--비밀번호-->
            비밀번호 <br><input type="password" name="PW"  placeholder="비밀번호를 입력하세요." required><br><br>
            비밀번호 확인<br><input type="password" name="PWC"  placeholder="한번 더 입력하세요." required><br><br>
            <!--이름-->
            이름<br><input type="text" name="name" placeholder="이름을 입력하세요." required><br><br>
            <!--생년월일-->
            생년월일<br><input type="date" name="birth" required> <br><br>
            <!-- 키 -->
            키<br><input type="number" name="height" placeholder="키를 입력하세요(cm제외)" required><br><br>
            <!-- 몸무게 -->
            몸무게<br><input type="number" name="weight" placeholder="몸무게를 입력하세요(kg제외)" required><br><br>
            <!--성별-->
            성별<br>
            <select name='sex' required>
                <option>남성</option>
                <option>여성</option>
            </select><br><br>
            <!-- 이메일 -->
            이메일<br><input type="text" name="email" class="email" placeholder="이메일을 입력하세요"> 
            <select name='domain' class="selectEmail" aria-placeholder="도메인 선택" required>
                <option value="" disabled selected>도메인 선택</option>
                <option>@naver.com</option>
                <option>@hanmail.net</option>
                <option>@daum.net</option>
                <option>@nate.com</option>
                <option>@gmail.com</option>
                <option>@hotmail.com</option>
                <option>@lycos.co.kr</option>
                <option>@empal.com</option>
                <option>@cyworld.com</option>
                <option>@yahoo.com</option>
                <option>@paran.com</option>
                <option>@dreamwiz.com</option>
                <option>직접입력</option>
            </select><br><br>
            휴대폰<br><input type="text" name="phone" placeholder="ex) 000-0000-0000"><br><br><br>
            <!--회원가입 버튼-->
            <input type="submit" value="회원가입" class="signupbtn">
            <br>   
            <br>
            <hr>
                <input type="button" onClick="location.href='./main.php'" value="돌아가기" class="signupbtn">
            </fieldset>
        </form>
    </body>
</html>




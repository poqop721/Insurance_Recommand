<?php
// # 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )
include_once("./login_register/password_compat.php");

# 세션(현재 접속한) 아이디 
$session_id = $_SESSION['ID'];

# DB 접속 부분 

if (!is_null($session_id )) {

    include('./login_register/connect.php');

    # sql문 (ID확인)
    $sql = "SELECT * FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$session_id'";

    # sql문 DB로 파싱 후 전송
    $send = mysqli_query($connect, $sql);

    # 배열의 모든 아이템(요소)을 한 번에 하나씩 참조하여 처리
    while ($row = mysqli_fetch_array($send, MYSQLI_ASSOC)) {
            # session_id에 해당되는 사용자의 패스워드를 가져온다 
            $encrypted_password = $row["CUSTOMER_PASSWORD"];
            $name = $row["CUSTOMER_NAME"];
            $birth = $row["CUSTOMER_BIRTH"];
            $weight = $row["CUSTOMER_WEIGHT"];
            $height = $row["CUSTOMER_HEIGHT"];
            $sex = $row["CUSTOMER_SEX"];
            $email = $row["CUSTOMER_EMAIL"];
            $phone = $row["CUSTOMER_PHONENUM"];
        }
}

# 수정받을 입력 변수 선언
$current_password = $_POST["CPW"];
$new_password = $_POST["NPW"];
$new_password_confirm = $_POST["NPWC"];
$new_name = $_POST["name"];
$new_birth = $_POST["birth"];
$new_height = $_POST["height"];
$new_weight = $_POST["weight"];
$new_sex = $_POST["sex"];
$new_email = $_POST["email"];
$new_domain = $_POST["domain"];
$new_phone = $_POST["phone"];

if ($new_domain != '직접입력')
    $new_email = $new_email.$new_domain;



# 현재 비번이 NULL이 아닌 경우
if (!is_null($current_password)){
    # 현재 비번과 암호화된 패스워드가 일치하는지 확인
    if ( password_verify( $current_password, $encrypted_password ) ) {
        # 새로운 비번이 NULL이 아닌경우
        if(!is_null($new_password)){
        # 새로운 비번과 새로 암호화된 패스워드가 일치하는지 확인
            if ( $new_password == $new_password_confirm ) {
            $encrypted_new_password = password_hash( $new_password, PASSWORD_DEFAULT);
                
            $sql_change = "UPDATE CUSTOMERINFO SET CUSTOMER_PASSWORD = '$encrypted_new_password', CUSTOMER_NAME = '$new_name', CUSTOMER_BIRTH = str_to_date('$new_birth', '%Y-%m-%d'), CUSTOMER_HEIGHT = '$new_height', CUSTOMER_WEIGHT = '$new_weight', CUSTOMER_SEX = '$new_sex', CUSTOMER_EMAIL = '$new_email', CUSTOMER_PHONENUM = '$new_phone' WHERE CUSTOMER_ID = '$session_id'";
                
            # sql문 DB로 파싱 후 전송
            mysqli_query($connect, $sql_change);

            echo "<script>alert('정보가 수정되었습니다. 다시 로그인해주세요.');</script>";
            echo "<script type='text/javascript'>location.href='./login_register/logOut.php';</script>"; 
            }

            else {
                # 새로운 비밀번호가 일치한지 확인
                echo "<script>alert('새 비밀번호가 일치하지 않습니다');</script>";
                }     
        } 
        # 현재 비번과 암호화된 패스워드가 일치하는지 하지 않을 경우   
        else{

        if(!is_null($new_password_confirm)){
            echo "<script>alert('새 비밀번호가 입력되지 않았습니다');</script>";
        }
        else{
            # 새로운 비밀번호 수정 필요 X
            $sql_change2 = "UPDATE CUSTOMERINFO SET CUSTOMER_NAME = '$new_name', CUSTOMER_BIRTH = str_to_date('$new_birth', '%Y-%m-%d'), CUSTOMER_HEIGHT = '$new_height', CUSTOMER_WEIGHT = '$new_weight', CUSTOMER_SEX = '$new_sex', CUSTOMER_EMAIL = '$new_email', CUSTOMER_PHONENUM = '$new_phone' WHERE CUSTOMER_ID = '$session_id'";
            
            # sql문 DB로 파싱 후 전송
            mysqli_query($connect, $sql_change2);

            # 현재 트랜잭션을 종료하고 모든 변경 사항을 영구적으로 반영
            mysqli_commit($connect);

            # Oracle 연결 해제
            mysqli_close($connect);   
            
            echo "<script>alert('정보가 수정되었습니다. 다시 로그인해주세요.');</script>";
            echo "<script type='text/javascript'>location.href='./login_register/logOut.php';</script>";
            } 
        }
    }  
    else {
        # 새로운 비밀번호가 일치한지 확인
        echo "<script>alert('기존 비밀번호가 일치하지 않습니다');</script>";
        }           
}    

?>

<!DOCTYPE html>
<html lang="kr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>회원 정보 수정</title>
        <link rel="stylesheet" id="updatesignup" href="css&js/signup.css">
    </head>
    
    <body>
        <!-- POST 방식으로 값을 넘겨야 됨 -->
        <form action = "./update_signup.php" method="POST">
            <fieldset>
            <div>
            <h1 style="color: rgb(72 73 217 / 86%);">정보 수정</h1><br>
            </div>
            <!--비밀번호-->
            현재 비밀번호 <br><input type="password" name="CPW"  placeholder="비밀번호를 입력하세요." required><br><br>
            비밀번호 변경하기 <input type="checkbox" id='cngPW' onClick="changePW()"><br><br>
            <div id="newPWDiv" hidden='true' style="border:0.5px solid gray; border-radius:10px; padding:5%; margin-bottom: 5%;">
            새로운 비밀번호 <br><input type="password" id="newPW" name="NPW"  placeholder="새로운 비밀번호를 입력하세요." disabled required><br><br>
            비밀번호 확인<br><input type="password" id="renewPW" name="NPWC"  placeholder="한번 더 입력하세요." disabled required><br><br>
            </div>
            <!--이름-->
            이름<br><input type="text" name="name" placeholder="<?php echo $name ?>" value="<?php echo $name ?>" required><br><br>
            <!--생년월일-->
            생년월일<br><input type="date" name="birth" placeholder="<?php echo $birth ?>" value="<?php echo $birth ?>" required> <br><br>
            <!-- 키 -->
            키<br><input type="number" name="height" placeholder="<?php echo $height ?>" value="<?php echo $height ?>" required><br><br>
            <!-- 몸무게 -->
            몸무게<br><input type="number" name="weight" placeholder="<?php echo $weight ?>" value="<?php echo $weight ?>" required><br><br>
            <!--성별-->
            성별<br>
            <select name='sex' required>
            <option disabled>선택하세요.</option>
                <option <?php if($sex == '남성')echo "selected"  ?>>남성</option>
                <option <?php if($sex == '여성')echo "selected"  ?>>여성</option>
            </select><br><br>
            <!-- 이메일 -->
            이메일<br><input type="text" name="email" class="email" placeholder="<?php echo $email ?>" value="<?php echo $email ?>" required> 
            <select name='domain' class="selectEmail" aria-placeholder="도메인 선택" required>
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
                <option selected>직접입력</option>
            </select><br><br>
            휴대폰<br><input type="text" name="phone" placeholder="<?php echo $phone ?>" value="<?php echo $phone ?>" required><br><br>
            <!--회원수정 버튼-->
            <input type="submit" value="회원정보수정"  class="signupbtn" style="background-color: rgb(72 73 217 / 86%);">
            <br>   
            <br>
            <hr><br>
                <input type="button" onClick="location.href='./myPage.php'" value="돌아가기" class="signupbtn" style="background-color: rgb(72 73 217 / 86%);">
            </fieldset>
        </form>
        <script>function changePW(){
            const checkbox = document.getElementById('cngPW');
                document.getElementById('newPWDiv').hidden = !checkbox.checked;
                document.getElementById('newPW').disabled = !checkbox.checked;
                document.getElementById('newPW').value = null;
                document.getElementById('renewPW').disabled = !checkbox.checked;
                document.getElementById('renewPW').value = null;
        }</script>
    </body>
</html>
<?php
# 세션 스타트
session_start();

# php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )
include_once("login_register/password_compat.php");

# 세션 아이디 기준 
$session_id = $_SESSION['ID'];


if (!is_null($_SESSION["ID"])) {

    include('./login_register/connect.php');

    # sql문 (ID확인)
    $sql = "SELECT * FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$session_id'";

    # sql문 DB로 파싱 후 전송
    $send = mysqli_query($connect, $sql);
    
    while ($row = mysqli_fetch_array($send,MYSQLI_ASSOC)) {
        $id = $row["CUSTOMER_ID"];
        $encrypted_password = $row["CUSTOMER_PASSWORD"];
    }
}

# 패스워드 확인
$password = $_POST['PW'];
$wp = 0;

if (!is_null($password)) {
    if (password_verify($password, $encrypted_password)) {

    # sql문 (ID확인)
    $sql = "DELETE FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$session_id'";

    # sql문 DB로 파싱 후 전송
    $send = mysqli_query($connect, $sql);
    
    # js 함수 호출 
    echo "<script>alert('회원탈퇴 완료 되었습니다');</script>";
    echo "<script type='text/javascript'>location.replace('./login_register/logOut.php');</script>";
    }

    else{
        $wp=1;
    }
    # DB 메모리 할당 및 연결 해제 
    mysqli_close($connect);
} 
?>


<!DOCTYPE html>
<html lang="kr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>회원 탈퇴</title>
        <link rel="stylesheet" id="deletesignup" href="css&js/signup.css">
        <script src="css&js/pwdcheck.js"></script>
    </head>
    
    <body>
        <!-- POST 방식으로 값을 넘겨야 됨 -->
        <form action = "./delete_signup.php" method="POST">
            <fieldset>
            <div>
            <h1 style="color: rgb(72 73 217 / 86%);">회원 탈퇴</h1><br>
            </div>
            <!--아이디-->
            아이디<br>
            <input type="text" name="ID" value=<?php echo $id ?> disabled ><br><br>
            <!--비밀번호-->
            비밀번호 <br><input type="password" name="PW"  placeholder="비밀번호를 입력하세요." required><br><br>
            <!--회원탈퇴 버튼-->
            <input type="submit" value="회원탈퇴" class="signupbtn" style="background-color: rgb(72 73 217 / 86%);">
            <br>   
            <br>
            <hr><br>
                <input type="button" onClick="location.href='./myPage.php'" value="돌아가기" class="signupbtn" style="background-color: rgb(72 73 217 / 86%);">
            </fieldset>
        </form>
        
        <?php
        if ($wp == 1) {
            echo "<script>alert('비밀번호가 일치하지 않습니다');</script>";
        } ?>

    </body>
</html>
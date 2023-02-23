<?php    
    // # 500 에러 시 확인 (디버깅용)
    // error_reporting(E_ALL);
    // ini_set('display_errors', '1');
    
    # 세션 스타트
    session_start();

    # php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )
    include_once("./password_compat.php");
        
    # POST 방식으로 넘어온 ID/PW 저장 및 Hash 암호화 변수 선언 
    $id = $_POST['ID']; 
    $password = $_POST['PW'];
    $encrypted_password = null;

    # ID 부분 NULL 아닐 시 
    if(!is_null($id)) {

    include('./connect.php');
        
        # Oracle DB 서버 접속
        
        # SQL문
        $sql = "SELECT CUSTOMER_PASSWORD from CUSTOMERINFO WHERE CUSTOMER_ID='$id'";

        # SQL문 DB로 파싱 후 전송
        $result = mysqli_query($connect, $sql);
        

        # id 값 있으면 DB에서 id에 해당하는 password를 가져와서 $encrypted_password에 저장
        while ($row = mysqli_fetch_array($result) ) {
                $encrypted_password = $row["CUSTOMER_PASSWORD"];
            }
        # id 값 있으면 $encrypted_password의 값 X
        if ( is_null( $encrypted_password ) ) {
            echo "<script>alert('아이디가 존재하지 않습니다.');location.replace('../main.php');</script>";
        }
        
        # id 값 있으면 password와 encrypted_password를 비교 일치 시 로그인 성공
        else if ( password_verify( $password, $encrypted_password )) {

            $_SESSION['ID'] = $id;
            $_SESSION['isLogin'] = true;

            $sql2 = "SELECT CUSTOMER_NAME,ROUND((TO_DAYS(NOW()) - (TO_DAYS(CUSTOMER_BIRTH))) / 365),CUSTOMER_HEIGHT,CUSTOMER_WEIGHT,CUSTOMER_SEX from CUSTOMERINFO WHERE CUSTOMER_ID='$id'";

            # SQL문 DB로 파싱 후 전송
            $result2 = mysqli_query($connect, $sql2);
            $row = mysqli_fetch_array($result2);

            # 이름,나이,키,몸무게 JS로 변수 전달
            $name = $row[0];
            $age = $row[1];
            $height = $row[2];
            $weight = $row[3];
            $sex = $row[4];

            echo "<script>
                    alert('$name"."님 환영합니다.');
                    localStorage.setItem('name', '$name');
                    location.replace('../main.php');
                </script>";
            $_SESSION['sex'] = $sex;
            $_SESSION['height'] = $height;
            $_SESSION['weight'] = $weight;
            $_SESSION['age'] = $age;
            $_SESSION['name'] = $name;
            exit;
        }
        # 패스워드 아닐 경우
        else{
            echo "<script>alert('비밀번호가 틀렸습니다');location.replace('../main.php');</script>";
            exit;
        }
    } 
    # 아이디 없을 경우
    else {
        echo "<script>alert('아이디를 입력해주세요.');location.replace('../main.php');</script>";
        exit;
    }
    mysqli_close($connect);

?>
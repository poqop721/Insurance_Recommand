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
    
    # DB 접속 부분 
    $db = 
        '(DESCRIPTION =
            (ADDRESS_LIST=
                (ADDRESS = (PROTOCOL = TCP)(HOST = poqop721.dothome.co.kr)(PORT = 21))
            )
                (CONNECT_DATA =
                    (SID = orcl)
                )
            )';
            
    # Oracle 학교 DB 서버 ID/PW
    $username = "poqop721";
    $userpassword = "crafter1#";

    # ID 부분 NULL 아닐 시 
    if(!is_null($id)) {
        
        # Oracle DB 서버 접속
        $connect = oci_connect($username, $userpassword , $db, 'AL32UTF8'); 
        
        # SQL문
        $sql = "SELECT CUSTOMER_PASSWORD from CUSTOMERINFO WHERE CUSTOMER_ID='$id'";

        # SQL문 DB로 파싱 후 전송
        $result = oci_parse($connect, $sql);
        oci_execute($result);
        

        # id 값 있으면 DB에서 id에 해당하는 password를 가져와서 $encrypted_password에 저장
        while ($row = oci_fetch_array($result, OCI_ASSOC) ) {
                $encrypted_password = $row["CUSTOMER_PASSWORD"];
            }
        # id 값 있으면 $encrypted_password의 값 X
        if ( is_null( $encrypted_password ) ) {
            echo "<script>alert('아이디가 존재하지 않습니다.');location.replace('../main.php');</script>";
        }
        
        # id 값 있으면 password와 encrypted_password를 비교 일치 시 로그인 성공
        else if ( password_verify( $password, $encrypted_password )) {

            $_SESSION['ID'] = $id;

            $sql2 = "SELECT CUSTOMER_NAME,TRUNC(MONTHS_BETWEEN(TRUNC(SYSDATE), CUSTOMER_BIRTH) / 12),CUSTOMER_HEIGHT,CUSTOMER_WEIGHT from CUSTOMERINFO WHERE CUSTOMER_ID='$id'";

            # SQL문 DB로 파싱 후 전송
            $result2 = oci_parse($connect, $sql2);
            oci_execute($result2);
            $row = oci_fetch_array($result2, OCI_NUM);

            # 이름,나이,키,몸무게 JS로 변수 전달
            $name = $row[0];
            $age = $row[1];
            $height = $row[2];
            $weight = $row[3];

            echo "<script>
                    alert('$name 님 환영합니다.');
                    localStorage.setItem('age', '$age');
                    localStorage.setItem('height', '$height');
                    localStorage.setItem('weight', '$weight');
                    localStorage.setItem('name', '$name');
                    localStorage.setItem('loggin', 'true');
                    location.replace('../main.php');
                </script>";
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
    oci_close($connect);

?>
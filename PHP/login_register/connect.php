<?php   
    /* 서버 접속 */
    $DB_servername = "localhost";
    $DB_user = "poqop721";
    $DB_password = "crafter1#";
    $DB_dbname = "poqop721";
    $connect = mysqli_connect($DB_servername, $DB_user, $DB_password, $DB_dbname);

    
    /* 서버 접속 확인 */
    if (!$connect) {
        die ("서버와의 연결 실패!: ".mysqli_connect_error());
    }
?>
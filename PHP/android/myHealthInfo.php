<?php
# 세션 스타트

$id = $_POST["ID"];

session_start();
include('../login_register/connect.php');

$health = array();
$query = "SELECT HEALTH_INFO,CREATED_DATE, HEALTH.HEALTH_BP, HEALTH.HEALTH_BOS,HEALTH.HEALTH_BFP, HEALTH.HEALTH_SMM, HEALTH.HEALTH_MBW, HEALTH.HEALTH_BM,WARN_LIST,DAN_LIST
                        FROM CUSTOMERINFO JOIN HEALTH ON CUSTOMERINFO.CUSTOMER_ID = HEALTH.CUSTOMER_ID
                        WHERE CUSTOMERINFO.CUSTOMER_ID = '$id'
                        ORDER BY HEALTH.CREATED_DATE DESC";
$send = mysqli_query($connect, $query);
while (($ins = mysqli_fetch_array($send)) != false) {
    array_push($health, array(
        'DEL_NUM' => $ins['HEALTH_INFO'],
        'CREATED_DATE' => $ins['CREATED_DATE'],
        'BP' => $ins['HEALTH_BP'],
        'BOS' => $ins['HEALTH_BOS'],
        'BFP' => $ins['HEALTH_BFP']
        ,
        'SMM' => $ins['HEALTH_SMM'],
        'MBW' => $ins['HEALTH_MBW'],
        'BM' => $ins['HEALTH_BM'],
        'WARN_LIST' => $ins['WARN_LIST'],
        'DAN_LIST' => $ins['DAN_LIST']
    )
    );
}

function my_json_encode($arr)
{

    //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding

    array_walk_recursive($arr, function (&$item, $key) {
        if (is_string($item))
            $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
    });

    return mb_decode_numericentity(json_encode($arr), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
}

echo my_json_encode($health);

// DB 메모리 할당 및 연결 해제 
mysqli_close($connect);

?>
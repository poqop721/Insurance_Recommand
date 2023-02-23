<?php

# 세션 스타트
session_start();

include('../login_register/connect.php');

$id = $_POST["ID"];

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

function my_json_encode($arr)
{

    //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding

    array_walk_recursive($arr, function (&$item, $key) {
        if (is_string($item))
            $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
    });

    return mb_decode_numericentity(json_encode($arr), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
}

echo my_json_encode(
    array(
        'sex' => $sex,
        'height' => $height,
        'weight' => $weight,
        'age' => $age,
        'name' => $name
    )
);

mysqli_close($connect);

?>
<?php
# 세션 스타트

$id = $_POST["ID"];

session_start();
include('../login_register/connect.php');

$zzim = array();
$sql = "SELECT PRODUCTID from REFERENCETABLE where CUSTOMER_ID = '$id'";
$send = mysqli_query($connect, $sql);
while ($row = mysqli_fetch_array($send, MYSQLI_ASSOC)) {
    foreach ($row as $item) {
        $sql2 = "SELECT (SELECT NAME from INS where INSID = (select INSID from PRODUCT where PRODUCTID = '$item'))AS NAME
        , PRODUCTNAME, PRICE, COMP ,KIND, (SELECT URL from INS where INSID = (select INSID from PRODUCT where PRODUCTID = '$item'))AS URL, 
        (SELECT TEL from INS where INSID = (select INSID from PRODUCT where PRODUCTID = '$item'))AS TEL 
        from PRODUCT where PRODUCTID = (SELECT PRODUCTID from PRODUCT where PRODUCTID = '$item')";
        $send2 = mysqli_query($connect, $sql2);
        $detailZzim = array();
        while ($row = mysqli_fetch_array($send2, MYSQLI_ASSOC)) {
            $detailZzim=array('name'=>$row['NAME'],'productname'=>$row['PRODUCTNAME'],'price'=>$row['PRICE'],'comp'=>$row['COMP']
            ,'kind'=>$row['KIND'],'url'=>$row['URL'],'tel'=>$row['TEL']);
        }
        array_push($zzim, $detailZzim);
    }
}


$sql = "SELECT * FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$id'";

# sql문 DB로 파싱 후 전송
$send = mysqli_query($connect, $sql);

while ($row = mysqli_fetch_array($send, MYSQLI_ASSOC)) {
    $myInfo = array(
    'id' => $row["CUSTOMER_ID"],
    'name' => $row["CUSTOMER_NAME"],
    'birth' => $row["CUSTOMER_BIRTH"],
    'height' => $row["CUSTOMER_HEIGHT"],
    'weight' => $row["CUSTOMER_WEIGHT"],
    'sex' => $row["CUSTOMER_SEX"],
    'email' => $row["CUSTOMER_EMAIL"],
    'phone' => $row["CUSTOMER_PHONENUM"],
    'regdate' => $row["REGDATE"]
    );
}

echo str_replace('\\/', '/', my_json_encode(array('zzim' => $zzim, 'myinfo' => $myInfo)));
function my_json_encode($arr)
{

    //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding

    array_walk_recursive($arr, function (&$item, $key) {
        if (is_string($item))
            $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
    });

    return mb_decode_numericentity(json_encode($arr), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
}

// DB 메모리 할당 및 연결 해제 
mysqli_close($connect);

?>
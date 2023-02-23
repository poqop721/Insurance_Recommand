<?php
# 세션 스타트
session_start();
include('../login_register/connect.php');
$kind = ["고혈압","뇌혈관","당뇨","실비","심혈관"];

$allins = array();
for ($i = 0; $i < count($kind); $i++) {
    $kindarr = array();
    $sql = "SELECT INS.NAME, PRODUCT.PRODUCTNAME, PRODUCT.PRICE, PRODUCT.COMP, PRODUCT.KIND, INS.URL, INS.TEL FROM INS JOIN PRODUCT ON INS.INSID = PRODUCT.INSID WHERE PRODUCT.KIND = '$kind[$i]' ORDER BY NAME ASC";
    $send = mysqli_query($connect, $sql);
    while (($ins = mysqli_fetch_array($send)) != false) {
        array_push($kindarr,array('name'=>$ins['NAME'],'productname'=>$ins['PRODUCTNAME'],'price'=>$ins['PRICE'],'comp'=>$ins['COMP']
                                ,'kind'=>$ins['KIND'],'url'=>$ins['URL'],'tel'=>$ins['TEL']));
        $count = $count + 1;
    }
    $item = changeKor("$kind[$i]");
    array_push($allins,array("$item"=>$kindarr));
}

function my_json_encode($arr){

    //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding

    array_walk_recursive($arr, function (&$item, $key) {
        if (is_string($item))
            $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
    });

    return mb_decode_numericentity(json_encode($arr), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
}

function changeKor($item){
    return mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
}

echo str_replace('\\/', '/', my_json_encode($allins));

// DB 메모리 할당 및 연결 해제 
mysqli_close($connect);

?>
<?php
// # 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )

# 입력 변수 선언

$id = $_POST["ID"];
$BP = $_POST["BP"];
$BOS = $_POST["BOS"];
$BFP = $_POST["BFP"];
$SMM = $_POST["SMM"];
$MBW = $_POST["MBW"];
$BM = $_POST["BM"];

$warnarrStr = $_POST["warning"];
$danarrStr = $_POST["danger"];
$warningFeedback = $_POST["warningFeedback"];
$dangerFeedback = $_POST["dangerFeedback"];

$warningFeedbackArr;
$dangerFeedbackArr;
if ($warningFeedback != '[]') {
    $warningFeedback = str_replace('[', '', $warningFeedback);
    $warningFeedback = str_replace(']', '', $warningFeedback);
    $warningFeedback = str_replace(' ', '', $warningFeedback);
    $warningFeedbackArr = explode(',', $warningFeedback);
}

if ($dangerFeedback != '[]') {
    $dangerFeedback = str_replace('[', '', $dangerFeedback);
    $dangerFeedback = str_replace(']', '', $dangerFeedback);
    $dangerFeedback = str_replace(' ', '', $dangerFeedback);
    $dangerFeedbackArr = explode(',', $dangerFeedback);
}

$warnarr = array();
$danarr = array();
if ($warnarrStr != '[]') {
    $warnarrStr = str_replace('[', '', $warnarrStr);
    $warnarrStr = str_replace(']', '', $warnarrStr);
    $warnarrStr = str_replace(' ', '', $warnarrStr);
    $warnarr = explode(',', $warnarrStr);
}
if ($danarrStr != '[]') {
    $danarrStr = str_replace('[', '', $danarrStr);
    $danarrStr = str_replace(']', '', $danarrStr);
    $danarrStr = str_replace(' ', '', $danarrStr);
    $danarr = explode(',', $danarrStr);
}

if ($warningFeedbackArr != null) {
    $healthWarning = array_unique($warningFeedbackArr);
    foreach ($healthWarning as $key => $value) {
        switch ($value) {
            case '혈압':
                $healthWarning[$key] = '1';
                break;
            case '혈중산소포화도':
                $healthWarning[$key] = '2';
                break;
            case '체지방률':
                $healthWarning[$key] = '3';
                break;
            case '골격근량':
                $healthWarning[$key] = '4';
                break;
            case '체수분':
                $healthWarning[$key] = '5';
                break;
            case '기초대사량':
                $healthWarning[$key] = '6';
                break;
        }
    }
    $healthWarning = implode(',', $healthWarning);
} else {
    $healthWarning = NULL;
}

if ($dangerFeedbackArr != null) {
    $healthDanger = array_unique($dangerFeedbackArr);
    foreach ($healthDanger as $key => $value) {
        switch ($value) {
            case '혈압':
                $healthDanger[$key] = '1';
                break;
            case '혈중산소포화도':
                $healthDanger[$key] = '2';
                break;
            case '체지방률':
                $healthDanger[$key] = '3';
                break;
            case '골격근량':
                $healthDanger[$key] = '4';
                break;
            case '체수분':
                $healthDanger[$key] = '5';
                break;
            case '기초대사량':
                $healthDanger[$key] = '6';
                break;
        }
    }
    $healthDanger = implode(',', $healthDanger);
} else {
    $healthDanger = NULL;
}


if ($BP == 0)
    $BP = "NULL";
if ($BOS == 0)
    $BOS = "NULL";
if ($SMM == 0)
    $SMM = "NULL";
if ($MBW == 0)
    $MBW = "NULL";

# Oracle DB 서버 접속
include('../login_register/connect.php');

# sql문
$healthSQL = "INSERT INTO HEALTH VALUES ($BP, $BOS, $BFP, $SMM, $MBW, $BM, (SELECT nextval('health_info') FROM DUAL),'$id',now(),'$healthWarning','$healthDanger')";
mysqli_query($connect, $healthSQL);


if ($warnarr == null) {
    $len = 0;
} else {
    $len = (int) count($warnarr);
    for ($i = 0; $i < count($warnarr); $i++) {
        $warnarr[$i] = str_replace(' ', '', $warnarr[$i]);
    }
    $checkWarn = array();
    for ($i = 0; $i < count($warnarr); $i++) {
        if (in_array($warnarr[$i], $checkWarn)) {
            if ($warnobj[$warnarr[$i]] != $warningFeedbackArr[$i])
                $warnobj[$warnarr[$i]] = $warnobj[$warnarr[$i]] . ' , ' . $warningFeedbackArr[$i];
        } else {
            $warnobj[$warnarr[$i]] = $warningFeedbackArr[$i];
            array_push($checkWarn, $warnarr[$i]);
        }
    }
    $warnList = array();
    for ($i = 0; $i < count($warnarr); $i++) {
        array_push($warnList, $warnarr[$i]);
    }
    $warnList = array_unique($warnList);
}

////
if ($danarr == null) {
    $len2 = 0;
} else {
    $len2 = (int) count($danarr);
    for ($i = 0; $i < count($danarr); $i++) {
        $danarr[$i] = str_replace(' ', '', $danarr[$i]);
    }
    $checkDan = array();
    for ($i = 0; $i < count($danarr); $i++) {
        if (in_array($danarr[$i], $checkDan)) {
            if ($danobj[$danarr[$i]] != $dangerFeedbackArr[$i])
                $danobj[$danarr[$i]] = $danobj[$danarr[$i]] . ' , ' . $dangerFeedbackArr[$i];
        } else {
            $danobj[$danarr[$i]] = $dangerFeedbackArr[$i];
            array_push($checkDan, $danarr[$i]);
        }
    }
    $danList = array();
    for ($i = 0; $i < count($danarr); $i++) {
        array_push($danList, $danarr[$i]);
    }
    $danList = array_unique($danList);
}

$result = array();
//warn
if ($len2 != 0) {
    $count = 0;
    foreach ($danList as $key => $value) {
        $arrd = array();
        $danger = "SELECT NAME,PRODUCTNAME,PRICE,COMP,KIND,URL,TEL FROM PRODUCT,INS WHERE KIND = '$value' AND PRODUCT.INSID = INS.INSID ORDER BY COMP DESC";
        # sql문 DB로 파싱 후 전송
        $danger_Result = mysqli_query($connect, $danger);
        // echo "<h3><a>$danobj[$value]</a> 에 대한 <a>$value</a> 보험추천</h3>";
        // echo "<table class='table'>\n<tr class='trFirst'><th>보험사</th><th>보험명</th><th>보장금액</th><th>가입금액</th><th>주 보장 항목</th><th>보험사 URL</th><th>전화번호</th></tr>";
        while (($dan = mysqli_fetch_array($danger_Result)) != false) {
            array_push($arrd,array('name'=>$dan['NAME'],'productname'=>$dan['PRODUCTNAME'],'price'=>$dan['PRICE'],'comp'=>$dan['COMP']
                                    ,'kind'=>$dan['KIND'],'url'=>$dan['URL'],'tel'=>$dan['TEL']));
            $count = $count + 1;
        }
        $itemd = changeKor("$danobj[$value]에 대한 $value 보험추천");
        array_push($result, array("$itemd" => $arrd));
    }
}

$result2 = array();
//dan
if ($len != 0) {
    foreach ($warnList as $key => $value) {
        $arrw = array();
        $warning = "SELECT NAME,PRODUCTNAME,PRICE,COMP,KIND,URL,TEL FROM PRODUCT,INS WHERE KIND = '$value' AND PRODUCT.INSID = INS.INSID ORDER BY PRICE ASC";
        # sql문 DB로 파싱 후 전송
        $warning_Result = mysqli_query($connect, $warning);
        // echo "<br><h3><a>$warnobj[$value]</a> 에 대한 <a>$value</a> 보험추천</h3>";
        // echo "<table class='table'>\n<tr class='trFirst'><th>보험사</th><th>보험명</th><th>보장금액</th><th>가입금액</th><th>주 보장 항목</th><th>보험사 URL</th><th>전화번호</th></tr>";
        while (($warn = mysqli_fetch_array($warning_Result)) != false) {
            array_push($arrw,array('name'=>$warn['NAME'],'productname'=>$warn['PRODUCTNAME'],'price'=>$warn['PRICE'],'comp'=>$warn['COMP']
                                    ,'kind'=>$warn['KIND'],'url'=>$warn['URL'],'tel'=>$warn['TEL']));
            $count = $count + 1;
        }
        $itemw = changeKor("$warnobj[$value] 에 대한 $value 보험추천");
        array_push($result2,array("$itemw"=>$arrw));
    }
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

$strDan = changeKor("위험");
$strwarn = changeKor("주의");

if ($len == 0 && $len2 == 0) {
    echo "no";
} else echo str_replace('\\/', '/', my_json_encode(array("$strDan" => $result,"$strwarn" => $result2)));


// DB 메모리 할당 및 연결 해제 
mysqli_close($connect);

?>
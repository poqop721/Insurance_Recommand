function pwdcheck() {
    var p1 = document.getElementById('PW').value;
    var p2 = document.getElementById('PWC').value;
    if (p1.length < 8) {
        alert("비밀번호는 8글자 이상을 사용하세요.");
        return false;
    }
    if (p1 != p2) {
        alert("비밀번호가 일치하지 않습니다.");
        return false;
    }
    else {
        idncheck();
    }
}
//주민번호 확인
function idncheck() {
    var i1 = document.getElementById('idn').value;
    var i2 = document.getElementById('idnb').value;
    
    if (i1.length == 6 && i2.length == 7) {
        location.href = "main.php";
        return true;

    }
    else {
        alert("주민번호를 다시 확인해주세요");
        return false;
    }
}

function login(){
    localStorage.setItem('loggin', 'true');
}
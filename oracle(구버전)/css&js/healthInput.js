function calHealthInput() { 

    var height = document.getElementById('height').value;
    var weight = document.getElementById('weight').value;
    var age = document.getElementById('age').value;

    // 체지방률
    var meterHeight = height * 0.01
    var calcBFP = weight / (meterHeight*meterHeight);
    document.getElementById('BFP').value = calcBFP.toFixed(2);
    document.getElementById('calcBFP').innerHTML = calcBFP.toFixed(2);

    //기처대사량
    var sex = document.querySelector('input[name="sex"]:checked').value;
    if(sex === 'man'){
        var calcBM = 66.47+(13.75*weight)+(5*height)-(6.76*age)
    } else if(sex === 'woman'){
        var calcBM = 655.1+(9.56*weight)+(1.85*height)-(4.68*age)
    }

    document.getElementById('BM').value = calcBM.toFixed(2);
    document.getElementById('calcBM').innerHTML = calcBM.toFixed(2);
}


function setResult(){
    document.querySelector('.resultAge').innerHTML = document.getElementById('age').value
    document.querySelector('.resultHeight').innerHTML = document.getElementById('height').value
    document.querySelector('.resultWeight').innerHTML = document.getElementById('weight').value
    var sex = document.querySelector('input[name="sex"]:checked').value;
    if(sex === 'man'){
        document.querySelector('.resultSex').innerHTML = '남'
    } else if(sex === 'woman'){
        document.querySelector('.resultSex').innerHTML = '여'
    }
    if(document.getElementById('BP').value==='0'||document.getElementById('BP').value==='') 
    document.querySelector('.resultBP').innerHTML = '입력 안됨'
    else document.querySelector('.resultBP').innerHTML = document.getElementById('BP').value
    if(document.getElementById('BOS').value === '0'||document.getElementById('BOS').value === '') 
    document.querySelector('.resultBOS').innerHTML = '입력 안됨'
    else document.querySelector('.resultBOS').innerHTML = document.getElementById('BOS').value
    if(document.getElementById('BFP').value === '0' || document.getElementById('BFP').value === '')
    document.getElementById('BFP').value = document.getElementById('calcBFP').innerHTML;
    document.querySelector('.resultBFP').innerHTML = document.getElementById('BFP').value
    if(document.getElementById('SMM').value === '0' || document.getElementById('SMM').value === ''
    || document.getElementById('SMM').value === '0.000')
    document.querySelector('.resultSMM').innerHTML = '입력 안됨'
    else document.querySelector('.resultSMM').innerHTML = document.getElementById('SMM').value
    if(document.getElementById('MBW').value === '0'||document.getElementById('MBW').value === '') 
    document.querySelector('.resultMBW').innerHTML = '입력 안됨'
    else document.querySelector('.resultMBW').innerHTML = document.getElementById('MBW').value
    if(document.getElementById('BM').value === '0' || document.getElementById('BM').value === '')
    document.getElementById('BM').value = document.getElementById('calcBM').innerHTML;
    document.querySelector('.resultBM').innerHTML = document.getElementById('BM').value

    showResult()
}

function BPCheck() {
    if (confirm("혈압이 입력되지 않았습니다. 이 부분을 넘기시겠습니까?\n(넘기시는 경우 보험 추천에 방영되지 않습니다.)") == true){    //확인
        document.querySelector(".disabled2").style.pointerEvents = "all";
        document.querySelector(".disabled2").style.opacity = "1";
        document.getElementById('BP').value = 0;
        return true;
    }else{   //취소
        return false;
    }
}


function BOSCheck() {
    if (confirm("혈중산소포화도가 입력되지 않았습니다. 이 부분을 넘기시겠습니까?\n(넘기시는 경우 보험 추천에 방영되지 않습니다.)") == true){    //확인
        document.querySelector(".disabled3").style.pointerEvents = "all";
        document.querySelector(".disabled3").style.opacity = "1";
        document.getElementById('BOS').value = 0;
        return true;
    }else{   //취소
        return false;
    }
}

function SMMCheck() {
    if (confirm("골격근량이 입력되지 않았습니다. 이 부분을 넘기시겠습니까?\n(넘기시는 경우 보험 추천에 방영되지 않습니다.)") == true){    //확인
        document.querySelector(".disabled5").style.pointerEvents = "all";
        document.querySelector(".disabled5").style.opacity = "1";
        document.getElementById('SMM').value = 0;
        return true;
    }else{   //취소
        return false;
    }
}

function MBWCheck() {
    if (confirm("체수분량이 입력되지 않았습니다. 이 부분을 넘기시겠습니까?\n(넘기시는 경우 보험 추천에 방영되지 않습니다.)") == true){    //확인
        document.querySelector(".disabled6").style.pointerEvents = "all";
        document.querySelector(".disabled6").style.opacity = "1";
        document.getElementById('MBW').value = 0;
        return true;
    }else{   //취소
        return false;
    }
}
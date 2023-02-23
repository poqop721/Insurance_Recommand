function recommand() {
    let BP = parseFloat(document.getElementById('BP').value)
    let BOS = parseFloat(document.getElementById('BOS').value)
    let BFP = parseFloat(document.getElementById('BFP').value)
    let SMM = parseFloat(document.getElementById('SMM').value)
    let MBW = parseFloat(document.getElementById('MBW').value)
    let BM = parseFloat(document.getElementById('BM').value)
    // console.log('BP : ' + BP + ' BOS : ' + BOS + ' BFP : ' + BFP + ' SMM : ' + SMM + ' MBW : ' + MBW + ' BM : ' + BM)

    var sex = document.querySelector('input[name="sex"]:checked').value;
    var weight = document.getElementById('weight').value;

    var healthDict = {}
    //혈압 추천
    if(BP === 0) healthDict['BP'] = '입력 안됨'
    else if(BP < 120) healthDict['BP'] = '정상'
    else if(120 <= BP && BP <= 140) healthDict['BP'] = '주의'
    else healthDict['BP'] = '고혈압'

    //혈중산소포화도
    if(BOS === 0) healthDict['BOS'] = '입력 안됨'
    else if(BOS >= 95) healthDict['BOS'] = '정상'
    else if (90 <= BOS && BOS < 95) healthDict['BOS'] = '주의'
    else if (80 < BOS && BOS < 90) healthDict['BOS'] = '저산소증'
    else healthDict['BOS'] = '위독'


    var age = document.getElementById('age').value
    if(sex === '남성'){
        //체지방률
        if(age >= 30){
            if(BFP < 17) healthDict['BFP'] = '여윔'
            else if(17 <= BFP && BFP < 23) healthDict['BFP'] = '표준'
            else if (23 <= BFP && BFP < 28) healthDict['BFP'] = '경비만'
            else if (28 <= BFP && BFP < 38) healthDict['BFP'] = '중비만'
            else healthDict['BFP'] = '과비만'
        }
        else{
            if(BFP < 14) healthDict['BFP'] = '여윔'
            else if(14 <= BFP && BFP < 20) healthDict['BFP'] = '표준'
            else if (20 <= BFP && BFP < 25) healthDict['BFP'] = '경비만'
            else if (25 <= BFP && BFP < 35) healthDict['BFP'] = '중비만'
            else healthDict['BFP'] = '과비만'
        }
        //골격근량
        if(SMM === 0) healthDict['SMM'] = '입력 안됨'
        else if(weight*0.4 <= SMM) healthDict['SMM'] = '정상'
        else healthDict['SMM'] = '위험'
        //체수분
        if(MBW === 0) healthDict['MBW'] = '입력 안됨'
        else if(50 <= MBW && MBW <= 70) healthDict['MBW'] = '정상'
        else if (MBW < 50) healthDict['MBW'] = '낮음'
        else if (MBW > 50) healthDict['MBW'] = '높음'
        //기초대사량
        if(age < 30){
            if((1728-368.2)<=BM && BM <=(1728+368.2)) healthDict['BM'] = '정상'
            else if (BM > (1728+368.2)) healthDict['BM'] = '높음'
            else healthDict['BM'] = '낮음'
        } else if (30 <= age && age < 50){
            if((1669.5-302.1)<=BM && BM <=(1669.5+302.1)) healthDict['BM'] = '정상'
            else if (BM > (1669.5+302.1)) healthDict['BM'] = '높음'
            else healthDict['BM'] = '낮음'
        } else {
            if((1493.8-315.3)<=BM && BM <=(1493.8+315.3)) healthDict['BM'] = '정상'
            else if (BM > (1493.8+315.3)) healthDict['BM'] = '높음'
            else healthDict['BM'] = '낮음'
        }
    } else if(sex === '여성'){
        //체지방률
        if(age >= 30){
            if(BFP < 20) healthDict['BFP'] = '여윔'
            else if(20 <= BFP && BFP < 27) healthDict['BFP'] = '표준'
            else if (27 <= BFP && BFP < 33) healthDict['BFP'] = '경비만'
            else if (33 <= BFP && BFP < 43) healthDict['BFP'] = '중비만'
            else healthDict['BFP'] = '과비만'
        }
        else{
            if(BFP < 17) healthDict['BFP'] = '여윔'
            else if(17 <= BFP && BFP < 24) healthDict['BFP'] = '표준'
            else if (24 <= BFP && BFP < 30) healthDict['BFP'] = '경비만'
            else if (30 <= BFP && BFP < 40) healthDict['BFP'] = '중비만'
            else healthDict['BFP'] = '과비만'
        }
        //골격근량
        if(SMM === 0) healthDict['SMM'] = '입력 안됨'
        else if(weight*0.35 <= SMM) healthDict['SMM'] = '정상'
        else healthDict['SMM'] = '위험'
        //체수분
        if(45 <= MBW && MBW <= 65) healthDict['MBW'] = '정상'
        else if (MBW < 45) healthDict['MBW'] = '낮음'
        else if (MBW > 65) healthDict['MBW'] = '높음'        
        //기초대사량
        if(age < 30){
            if((1311.5-233)<=BM && BM <=(1311.5+233)) healthDict['BM'] = '정상'
            else if (BM > (1311.5+233)) healthDict['BM'] = '높음'
            else healthDict['BM'] = '낮음'
        } else if (30 <= age && age < 50){
            if((1316.8-225.9)<=BM && BM <=(1316.8+225.9)) healthDict['BM'] = '정상'
            else if (BM > (1316.8+225.9)) healthDict['BM'] = '높음'
            else healthDict['BM'] = '낮음'
        } else {
            if((1252.5-228.6)<=BM && BM <=(1252.5+228.6)) healthDict['BM'] = '정상'
            else if (BM > (1252.5+228.6)) healthDict['BM'] = '높음'
            else healthDict['BM'] = '낮음'
        }        
    }

    let warningList = []
    let dangerList = []
    let warningFeedback = []
    let dangerFeedback = []
    var feedback = ''
    for (const [key, value] of Object.entries(healthDict)) {
        if(value !== '입력 안됨'){
            // console.log(key, value)
            switch (key) {
                case 'BP':
                    name = '혈압'
                    dan = '고혈압'
                    dan2 = '뇌혈관'
                    break;
                case 'BOS':
                    name = '혈중 산소 포화도'
                    dan = '심혈관'
                    dan2 = '뇌혈관'
                    break;
                case 'BFP' :
                    name = '체지방률'
                    dan = '당뇨'
                    dan2 = '실비'
                    break;
                case 'SMM' : 
                    name = '골격근량'
                    dan = '실비'
                    dan2 = null
                    break;
                case 'MBW' :
                    name = '체수분'
                    if(value === '낮음') dan = '고혈압'
                    else if (value === '높음') dan = '심혈관'
                    else dan = null
                    dan2 = null
                    break;
                case 'BM' :
                    name = '기초대사량'   
                    dan = '심혈관' 
                    dan2 = null
                    break;
            }
            if(value === '정상' || value === '표준' || value === '입력안됨') var str = name + ' : ' + value + '<br>'
            else if (value ==='고혈압' || value ==='위독' || value === '과비만' || value ==='위험'){
                var str = name + ' : <a class="danger">'+ value + '</a><br>'
                if (dan !== null) {
                    dangerList.push(dan);
                    dangerFeedback.push(name);
                }
                if(dan2 !== null) {
                    dangerList.push(dan2);
                    dangerFeedback.push(name);
                }
            }
            else {
                var str = name + ' : <a class="notNormal">'+ value + '</a><br>'
                if (dan !== null) {
                    warningList.push(dan);
                    warningFeedback.push(name);
                }
                if(dan2 !== null) {
                    warningList.push(dan2);
                    warningFeedback.push(name);
                }
            }
            feedback = feedback + str
        }
      }

      let section = document.querySelector('.section');

    warningList.forEach(function(val){
        varwarn = document.createElement('input');
        varwarn.setAttribute('type', 'hidden');
        varwarn.setAttribute('name', 'warning[]');
        varwarn.setAttribute('value', val);
        section.appendChild(varwarn);
    });

    dangerList.forEach(function(val){
    vardan = document.createElement('input');
    vardan.setAttribute('type', 'hidden');
    vardan.setAttribute('name', 'danger[]');
    vardan.setAttribute('value', val);
    section.appendChild(vardan);
    });

    warningFeedback.forEach(function(val){
        varwarfeed = document.createElement('input');
        varwarfeed.setAttribute('type', 'hidden');
        varwarfeed.setAttribute('name', 'warningFeedback[]');
        varwarfeed.setAttribute('value', val);
        section.appendChild(varwarfeed);
        });

    dangerFeedback.forEach(function(val){
        vardanfeed = document.createElement('input');
        vardanfeed.setAttribute('type', 'hidden');
        vardanfeed.setAttribute('name', 'dangerFeedback[]');
        vardanfeed.setAttribute('value', val);
        section.appendChild(vardanfeed);
        });

    localStorage.setItem('warning', warningList); //
    localStorage.setItem('danger', dangerList); //
    localStorage.setItem('feedback', feedback);
}
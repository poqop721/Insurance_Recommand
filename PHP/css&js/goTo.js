function showResult() {
    if(document.getElementById('BP').value == '')
    document.getElementById('BP').value = 0;
    if(document.getElementById('BOS').value == '') 
    document.getElementById('BOS').value = 0;
    if(document.getElementById('SMM').value == '')
    document.getElementById('SMM').value = 0;
    if(document.getElementById('MBW').value == '')
    document.getElementById('MBW').value = 0;
    
    recommand();
}

function make_feedback(){
    warning = localStorage.getItem('warning')
    danger = localStorage.getItem('danger')
    console.log('warning : ' + warning)
    console.log('danger : ' + danger)

    feedback = localStorage.getItem('feedback')
    let infoContainer = document.querySelector('.healthinfoContainer');
    let your_problem = document.createElement('p');
  
    your_problem.setAttribute('class', 'problem');
    console.log(feedback)
    your_problem.innerHTML = feedback
  
    infoContainer.appendChild(your_problem);
}

function toResult(isResult){
    if(isResult === 1){
        location.href = "result.php";
    }
    else {
        alert("먼저 건강 정보를 입력하여 보험을 추천받아주세요.");
        location.href = "main.php";

}
}

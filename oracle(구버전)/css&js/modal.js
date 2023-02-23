

function loginmodal(){
    if(localStorage.getItem('loggin') === 'true'){
        document.querySelector('.modalbackground').style.display ='none';
    }
}
function getNames(){
    var name = localStorage.getItem('name');
    console.log(name);
    let names = document.querySelectorAll('.name');
    [...names].forEach(nm => {
        nm.innerHTML = name;
    })
}
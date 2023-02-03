function getNames(name){
    console.log(name);
    let names = document.querySelectorAll('.name');
    [...names].forEach(nm => {
        nm.innerHTML = name;
    })
}
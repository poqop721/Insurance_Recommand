const btn = document.querySelector(".btn-toggle"); 
const theme = document.querySelector("#light"); 
const newTheme = localStorage.getItem('currentTheme');

    if (newTheme == "black") { 
        theme.href = "css&js/black.css"; 
    } 
    else{
        theme.href = "css&js/main.css"; 
    }

    function Darkmode() { 
    if (theme.getAttribute("href") == "css&js/white.css") { 
        theme.href = "css&js/black.css"; 
        localStorage.setItem('currentTheme', 'black');
        } else { 
        theme.href = "css&js/white.css"; 
        localStorage.setItem('currentTheme', 'white');
        } 
    };


    

    
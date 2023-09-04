// Fonction affichage menu responsive
function myNavBarFunction() {
    let y = document.getElementById("nav1");
    let x = document.getElementById("left_nav");
    if (x.className === "left_navBar") {
    x.className += " responsive";
    y.className += " responsive";
    } else {
    x.className = "left_navBar";
    y.className = "navBar";
    }
}


//  Fonction affichage sous menu profil
function myNavBarFunctionProfil() {
    let x = document.getElementById("contentProfil");
    if (x.style.display === "none") {
        x.style.display = "block";
    }else{
        x.style.display = "none";
    }
}




// Fonction affichage menu responsive -- navbar --
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


//  Fonction affichage sous menu profil -- navbar --
function myNavBarFunctionProfil() {
    let x = document.getElementById("contentProfil");
    if (x.style.display === "none") {
        x.style.display = "block";
    }else{
        x.style.display = "none";
    }
}

// Fonction incrémentation et décrémentation de la quantité -- formulaire détail produit --  
let input = document.getElementById("cart_quantity");
const minus = document.getElementById("minusForm");
const plus = document.getElementById("plusForm");

if (minus && plus) {
    minus.addEventListener("click", function(){
        if (input.value>1){
            input.value--;
        } 
    })
    plus.addEventListener("click", function(){
        if (input.value<99){
            input.value++;
        }
        
    })
} 


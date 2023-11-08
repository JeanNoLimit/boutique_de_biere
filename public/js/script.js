// Fonction affichage menu responsive -- navbar --
function myNavBarFunction() {
    const y = document.getElementById("nav1");
    const x = document.getElementById("left_nav");
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
    const z = document.getElementById("contentProfil");
    if (z.style.display === "none") {
        z.style.display = "block";
    }else{
        z.style.display = "none";
    }
}

// Fonction incrémentation et décrémentation de la quantité -- formulaire détail produit --  
const cart_qty = document.getElementById("cart_quantity");
const minus = document.getElementById("minusForm");
const plus = document.getElementById("plusForm");

if (minus && plus && cart_qty) {
    minus.addEventListener("click", function(){
        if (cart_qty.value>1){
            cart_qty.value--;
        } 
    })
    plus.addEventListener("click", function(){
        if (cart_qty.value<99){
            cart_qty.value++;
        }
        
    })
} 


// Affichage modal détail produit
const flash = document.getElementById('flash_#1');
if(flash) {
  flash.addEventListener("click", function(){ flash.style.display ="none";});
}


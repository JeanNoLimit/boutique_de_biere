// Fonction affichage sous menu filtres
function filterDropdownFunction(param) {
    
    //Si taille écran inférieur à 1104 px
    if (window.innerWidth <= 1104) {

        //paramètres menu filtre
        let btn = document.getElementById("btn_filters");
        let chevron = document.getElementById("chevron1");
        let filterMenu = document.getElementById("filters_container");

        //paramètres menu tri
        let sortContent = document.getElementById("dropdown_blop");
        let chevron2 = document.getElementById("chevron2");

        if (param) {
            if (param==='filter') {
                //Si menu filtre fermé et menu tri fermé
                if (btn.className === "dropdown_btn_products filterBtn" && sortContent.style.display === "none") {
                    btn.className = "dropdown_btn_products filterBtnResp";
                    chevron.className = "fa-solid fa-chevron-up";
                    filterMenu.className += "filterMenuResponsive";
                //Sinon si menu filtre fermé et menu tri ouvert
                } else if ((btn.className === "dropdown_btn_products filterBtn" && sortContent.style.display === "block")){
                    sortContent.style.display = "none";
                    chevron2.className = "fa-solid fa-chevron-down";
                    btn.className = "dropdown_btn_products filterBtnResp";
                    chevron.className = "fa-solid fa-chevron-up";
                    filterMenu.className += "filterMenuResponsive";
                //sinon on ferme filtre
                } else {    
                    btn.className = "dropdown_btn_products filterBtn";
                    chevron.className = "fa-solid fa-chevron-down";
                    filterMenu.className = "";
                }
            } else if (param==="sort") {
                //Si menu filtre fermé et menu tri fermé
                if (sortContent.style.display === "none" && btn.className === "dropdown_btn_products filterBtn") {
                    sortContent.style.display = "block";
                    chevron2.className = "fa-solid fa-chevron-up";
                //Sinon si menu filtre ouvert et menu tri fermé
                }else if (sortContent.style.display === "none" && btn.className === "dropdown_btn_products filterBtnResp") {
                    btn.className = "dropdown_btn_products filterBtn";
                    chevron.className = "fa-solid fa-chevron-down";
                    filterMenu.className = "";
                    sortContent.style.display = "block";
                    chevron2.className = "fa-solid fa-chevron-up";
                //Sinon on ferme tri
                }else{
                    sortContent.style.display = "none";
                    chevron2.className = "fa-solid fa-chevron-down";
                }
            }else {
                console.log('paramètre non reconnu')
            }
        }
    } else {
        sortByDropdownFunction()
    }
}


function sortByDropdownFunction() {
    let sortContent = document.getElementById("dropdown_blop");
    let chevron2 = document.getElementById("chevron2");
    if (sortContent.style.display === "none") {
        sortContent.style.display = "block";
        chevron2.className = "fa-solid fa-chevron-up";
    }else{
        sortContent.style.display = "none";
        chevron2.className = "fa-solid fa-chevron-down";
    }
}
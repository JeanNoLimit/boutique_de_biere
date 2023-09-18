// Fonction affichage sous menu filtres
function filterDropdownFunction() {
    if (window.innerWidth <= 1104) {

        let btn = document.getElementById("btn_filters");
        let chevron = document.getElementById("chevron1");
        let filterMenu = document.getElementById("filters_container");


        if (btn.className === "dropdown_btn_products filterBtn") {
            btn.className = "dropdown_btn_products filterBtnResp";
            chevron.className = "fa-solid fa-chevron-up";
            filterMenu.className += "filterMenuResponsive";
        } else {
            btn.className = "dropdown_btn_products filterBtn";
            chevron.className = "fa-solid fa-chevron-down";
            filterMenu.className = "";
            }
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
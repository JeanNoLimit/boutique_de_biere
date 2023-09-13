// Fonction affichage sous menu filtres
function filterDropdownFunction() {
    if ( screen.width <= 1104) {
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
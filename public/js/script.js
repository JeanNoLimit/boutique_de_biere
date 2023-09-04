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
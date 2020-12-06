function initHoverImg(){
    let menu = document.getElementById('hover-image');
    let isToggled = false;

    window.onclick = function (event) {
        if (event.target.matches('.film-main__poster') && isToggled === false) {
            menu.style.display = "block";
            isToggled = true;
        } else if (!event.target.matches('.film-main__poster') && isToggled === true) {
            menu.style.display = "none";
            isToggled = false;
        }
    }
}
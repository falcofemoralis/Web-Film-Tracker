let isOpened = 0;
let last = "";

function close(event) {
    let isClickInside = false;
    let controls = document.getElementsByClassName("mobile-controls");

    for (let i = 0; i < controls.length; ++i) {
        isClickInside = controls[i].contains(event.target);
        if (isClickInside) break;
    }

    if (!isClickInside) {
        toggle(last);
    }
}

function toggle(id) {
    if (last !== id) {
        let lastMenu = document.getElementsByClassName(last)[0];
        if (lastMenu != null) {
            lastMenu.style.visibility = "hidden";
            lastMenu.style.opacity = "0";
            isOpened = 0;
        }
    }

    let menu = document.getElementsByClassName(id)[0];

    if (isOpened) {
        menu.style.visibility = "hidden";
        menu.style.opacity = "0";
        isOpened = 0;
        document.removeEventListener('click', close, false);
    } else {
        menu.style.visibility = "visible";
        menu.style.opacity = "1";
        isOpened = 1;
        last = id;
        document.addEventListener('click', close, false);
    }
}

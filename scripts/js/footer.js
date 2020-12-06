document.body.onload = () => {
    let screenHeight = window.screen.height;
    let docHeight = document.body.offsetHeight;

    if (docHeight > screenHeight) {
        let footer = document.getElementById("footer");
        footer.style.position = "relative";
    }
}
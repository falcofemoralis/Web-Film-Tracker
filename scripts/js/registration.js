function register() {
    let formData = new FormData(document.getElementById("register"));

    let request = new XMLHttpRequest();
    request.open('POST', '/auth', true);
    request.addEventListener('readystatechange', function () {
        if ((request.readyState === 4) && (request.status === 200)) {
            if (request.responseText === "") {
                window.location = "/";
            } else {
                document.getElementById("error-text").innerText = "Ошибка: " + request.responseText;
            }
        }
    });
    request.send(formData);

    return false;
}
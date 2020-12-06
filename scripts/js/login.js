function login() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const isSave = document.getElementById("isSave").value;

    if (username !== "" && password !== "") {
        let request = new XMLHttpRequest();
        request.open('GET', '/auth?username=' + username + "&password=" + password + "&isSave=" + isSave, true);
        request.addEventListener('readystatechange', function () {
            if ((request.readyState === 4) && (request.status === 200)) {
                if (request.responseText === "") {
                    window.location = "/";
                } else {
                    document.getElementById("error-text").innerText = "Ошибка: " + request.responseText;
                }
            }
        });
        request.send();

        return false;
    }
}
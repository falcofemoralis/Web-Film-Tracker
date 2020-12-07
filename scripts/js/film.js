function deleteComment(filmId, time, parentId) {
    let parent = document.getElementById(parentId);

    let request = new XMLHttpRequest();
    request.open('DELETE', '/comments/' + filmId + '/' + time, true);
    request.addEventListener('readystatechange', function () {
        if ((request.readyState === 4) && (request.status === 200)) {
            if (request.responseText === "") {
                parent.remove();
            } else {
                alert("Ошибка удаления: " + request.responseText)
            }
        }
    });
    request.send();
}

function addComment(filmId) {
    let textarea = document.getElementById("comment");
    let parent = document.getElementById("comments-block");
    let request = new XMLHttpRequest();
    let error = document.getElementById("error");

    if (textarea.value !== "") {
        error.style.display = "none";
        request.open('POST', '/comments', true);
        request.addEventListener('readystatechange', function () {
            if ((request.readyState === 4) && (request.status === 200)) {
                if (request.responseText === "") {
                    let requestComment = new XMLHttpRequest();

                    // Определение последнего комментарий id
                    let id = 0;
                    let childrenArray = parent.children;
                    let lastComment = childrenArray[parent.childElementCount - 1];
                    if (lastComment != null)
                        id = +lastComment.id.split("_")[1] + 1;

                    // получаем блок комментария
                    requestComment.open('GET', '/comments?filmId=' + filmId + "&comment=" + textarea.value + "&id=" + id, true);
                    requestComment.addEventListener('readystatechange', function () {
                        if ((requestComment.readyState === 4) && (requestComment.status === 200)) {
                            let div = document.createElement("div");
                            div.innerHTML = requestComment.responseText;
                            parent.insertBefore(div, parent.firstChild);

                            checkComment(id);
                            textarea.value = "";
                        }
                    });
                    requestComment.send();
                } else {
                    alert("Ошибка добавления: " + request.responseText);
                }
            }
        });
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        request.send('filmId=' + window.encodeURIComponent(filmId) + '&comment=' + window.encodeURIComponent(textarea.value));
    } else {
        error.style.display = "block";
    }
}

function changeBookmark(filmId) {
    let img = document.getElementById("bookmark_img");
    let btn = document.getElementsByClassName("bookmark__btn")[0];

    const toBookmarks = "bookmark.svg";
    const unBookmark = "unbookmark.svg";

    let method;
    if (btn.value === "false") {
        method = "PUT";
        btn.value = "true";
    } else {
        method = "DELETE";
        btn.value = "false";
    }

    let request = new XMLHttpRequest();
    request.open(method, '/bookmarks/' + filmId, true);
    request.addEventListener('readystatechange', function () {
        if ((request.readyState === 4) && (request.status === 200)) {
            const base = "/images/";
            if (method === "PUT") img.src = base + unBookmark;
            else img.src = base + toBookmarks;
        }
    });
    request.send();
}
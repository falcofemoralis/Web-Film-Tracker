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

function putRating(stars, filmId) {
    let request = new XMLHttpRequest();

    request.open('POST', '/ratings', true);
    request.addEventListener('readystatechange', function () {
        if ((request.readyState === 4) && (request.status === 200)) {
            if (request.responseText !== "")
                alert("Ошибка добавления: " + request.responseText);
            else
                updateRatings(filmId);
        }
    });
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.send('filmId=' + window.encodeURIComponent(filmId) + '&rating=' + window.encodeURIComponent(stars));
}

function updateRatings(filmId) {
    for (let i = 0; i < 10; ++i) {
        let star = document.getElementsByClassName("r" + (i + 1));
        star[0].style.display = "none";
    }

    let request = new XMLHttpRequest();
    request.open('GET', '/ratings?filmId=' + filmId, true);
    request.addEventListener('readystatechange', function () {
        if ((request.readyState === 4) && (request.status === 200)) {
            if (request.responseText !== "") {
                let ratingEl = document.getElementById("tracker-rating");
                let votesEl = document.getElementById("tracker-votes");

                let rating = request.responseText.split(" ")[0];
                let votes = request.responseText.split(" ")[1];
                ratingEl.innerText = rating;
                votesEl.innerText = votes;

                let currentRating = document.getElementsByClassName("current-rating")[0];
                currentRating.style.width = ((parseFloat(rating) * 100) / 10) + "%";
            } else {
                alert("Ошибка получения: " + request.responseText);
            }
        }
    });
    request.send();
}
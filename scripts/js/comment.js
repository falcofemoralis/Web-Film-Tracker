let isRevealed = false;

function revealComment(commentId, commentText) {
    let inside = document.getElementById(commentId);

    if (isRevealed) {
        inside.style.height = "107px";
        inside.style.overflow = "hidden";
        isRevealed = false;
    } else {
        let text = document.getElementById(commentText);
        let h = text.offsetHeight + 40;
        inside.style.height = h.toString() + "px";
        inside.style.overflow = "none";
        isRevealed = true;
    }
}

function checkComment(id) {
    let text = document.getElementById("commentText_" + id);

    // выключаем кнопку
    if (text.offsetHeight < 69) {
        let commentReveal = document.getElementById("commentReveal_" + id);
        commentReveal.style.display = "none";
    }
}

function initComments(){
    let parent = document.getElementById("comments-block");
    let childrenArray = parent.children;

    for (let i = 0; i < childrenArray.length; ++i)
        checkComment(i);
}

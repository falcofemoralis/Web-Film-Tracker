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

let parent = document.getElementById("comments-block");
let childrenArray = parent.children;

for (let i = 0; i < childrenArray.length; ++i) {
    let text = document.getElementById("commentText_" + i);

    // выключаем кнопку
    if (text.offsetHeight < 69) {
        let commentReveal = document.getElementById("commentReveal_" + i);
        commentReveal.style.display = "none";
    }
}


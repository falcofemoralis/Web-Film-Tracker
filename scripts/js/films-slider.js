let slideIndex = 1;

// Next/previous controls
function plusSlides(n) {
    showSlides(slideIndex += n);
}

function showSlides(n) {
    let slides = document.getElementsByClassName("films-slider__item");

    if (n > slides.length)
        slideIndex = 1

    if (n < 1) slideIndex = slides.length

    for (let i = 0; i < slides.length; ++i) {
        let posNext = 840 * (slideIndex - 1);
        slides[i].style.right = `${posNext}px`
    }
}
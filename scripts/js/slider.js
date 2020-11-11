let slideIndex = 1;
let width

function sliderInit() {
    let slides = document.getElementsByClassName("slider__item");
    let slider = document.getElementsByClassName("slider__container");
    width = slides[0].offsetWidth;
    slider[0].style.width = `${width}px`
}

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function showSlides(n) {
    let slides = document.getElementsByClassName("slider__item");

    if (n > slides.length)
        slideIndex = 1

    if (n < 1) slideIndex = slides.length

    for (let i = 0; i < slides.length; ++i) {
        let posNext = width * (slideIndex - 1);
        slides[i].style.right = `${posNext}px`
    }
}
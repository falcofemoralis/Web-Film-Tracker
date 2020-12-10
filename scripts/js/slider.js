let slideIndex = 1;
let width;
let container;
let sizeOfPage;

function getPages() {
    let film = document.getElementsByClassName("film"); //объект фильма, нужен для получения размера
    let slider = document.getElementsByClassName("slider"); //слайдер (кнопки + конйтейнер)

    //получем размер страницы (кол-во фильмов на странице)
    //cwd - ширина слайдера fwd - ширина фильма
    let cwd = slider[0].offsetWidth;
    let fwd = film[0].offsetWidth;
    sizeOfPage = Math.trunc(cwd / fwd) - 1;
}

function reportWindowSize() {
    sliderInit(false);
}

function sliderInit(isBefore) {
    //необходимые объекты
    container = document.getElementsByClassName("slider__container"); //тут нахоядтся страницы слайдера

    getPages();

    if (sizeOfPage !== 0) {
        //обновление фильмов в слайдере
        let filmsChildren;
        if (isBefore) {
            filmsChildren = container[0].children; //объекты фильмов в слайдере

            for (let i = 0; i < filmsChildren.length; i++) {
                filmsChildren[i].classList.add("slider__item");
            }
        }

        //получение фильмов как non-Live лист
        let films = document.querySelectorAll(".slider__item");

        if (!isBefore) container[0].innerHTML = "";

        //разбиение фильмов на страницы
        let div;
        for (let i = 0; i < films.length; i++) {
            if (i % sizeOfPage === 0) {
                div = document.createElement("div");
                div.classList.add("slider__page");
            }

            div.appendChild(films[i]);

            if ((i % (sizeOfPage - 1)) === 0 || i === films.length - 1) {
                container[0].appendChild(div);
            } else if (sizeOfPage === 1) {
                container[0].appendChild(div);
            }
        }

        let slides = document.getElementsByClassName("slider__page");
        width = slides[0].offsetWidth;
        container[0].style.width = `${width}px`;
    }
}

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function showSlides(n) {
    let slides = document.getElementsByClassName("slider__page");

    if (n > slides.length)
        slideIndex = 1

    if (n < 1) slideIndex = slides.length

    for (let i = 0; i < slides.length; ++i) {
        let posNext = width * (slideIndex - 1);
        slides[i].style.right = `${posNext}px`
    }

    container[0].style.width = `${slides[slideIndex - 1].offsetWidth}px`;
}
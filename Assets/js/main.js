let menu = document.querySelector("#menu-bars");
let navbar = document.querySelector(".navbar");


menu.onclick = () => {
    menu.classList.toggle('fa-times');
    navbar.classList.toggle('active');
};

let navLinks = document.querySelectorAll('.navbar a');

navLinks.forEach(link => {
    link.addEventListener('click', function () {

        navLinks.forEach(item => item.classList.remove('active'));

        this.classList.add('active');
    });
});

var swiper1 = new Swiper(".home-slider", {
    loop: true,
    grabCursor: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});

var swiper2 = new Swiper(".anime-slider", {
    spaceBetween: 20,
    slidesPerView: 4,
    loop: true,
    grabCursor: true,
    breakpoints: {
        1024: { slidesPerView: 4 },
        768: { slidesPerView: 2 },
        480: { slidesPerView: 1 },
    },
});
var swiper3 = new Swiper(".new-release-slider", {
    spaceBetween: 20,
    slidesPerView: 4,
    loop: true,
    grabCursor: true,
    breakpoints: {
        1024: { slidesPerView: 4 },
        768: { slidesPerView: 2 },
        480: { slidesPerView: 1 },
    },
});

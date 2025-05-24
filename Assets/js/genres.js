
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

var swiper = new Swiper(".anime-slider-anime", {
  slidesPerView: 4,
  spaceBetween: 30,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    0: { slidesPerView: 1, spaceBetween: 10 },
    480: { slidesPerView: 2, spaceBetween: 15 },
    768: { slidesPerView: 3, spaceBetween: 20 },
    1024: { slidesPerView: 4, spaceBetween: 25 },
  },
  loop: true,
});



const comedySlider = new Swiper(".anime-slider-comedy", {
  slidesPerView: 4,
  spaceBetween: 20,
  grabCursor: true,
  loop: true,
  breakpoints: {
    0: { slidesPerView: 1 },
    640: { slidesPerView: 2 },
    991: { slidesPerView: 4 },
  },
});

const dramaSlider = new Swiper(".anime-slider-drama", {
  slidesPerView: 4,
  spaceBetween: 20,
  grabCursor: true,
  loop: true,
  breakpoints: {
    0: { slidesPerView: 1 },
    640: { slidesPerView: 2 },
    991: { slidesPerView: 4 },
  },
});


var romanceSlide = new Swiper(".anime-slider-romance", {
  spaceBetween: 20,
  slidesPerView: 4,
  loop: true,
  grabCursor: true,
  breakpoints: {
    0: { slidesPerView: 1 },
    640: { slidesPerView: 2 },
    991: { slidesPerView: 4 },
  },
});

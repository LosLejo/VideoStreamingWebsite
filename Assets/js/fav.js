const animeSwiper = new Swiper('.anime-slider', {
  slidesPerView: 4,
  spaceBetween: 20,
  loop: true,
  autoplay: {
    delay: 3000,
  },
  breakpoints: {
    320: { slidesPerView: 1 },
    640: { slidesPerView: 2 },
    991: { slidesPerView: 3 },
    1200: { slidesPerView: 4 }
  }
});

document.querySelectorAll('.like-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const countSpan = btn.nextElementSibling;
    let count = parseInt(countSpan.textContent);

    if (btn.classList.contains('liked')) {
      btn.classList.remove('liked');
      count--;
    } else {
      btn.classList.add('liked');
      count++;
    }

    countSpan.textContent = count;
  });
});
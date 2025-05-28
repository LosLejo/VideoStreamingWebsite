// Home slider configuration
var homeSlider = new Swiper('.home-slider', {
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    effect: 'fade',
    fadeEffect: {
        crossFade: true
    }
});

// Browse/Anime slider configuration
var animeSlider = new Swiper('.anime-slider', {
    loop: true,
    grabCursor: true,
    spaceBetween: 20,
    slidesPerView: 'auto',
    centeredSlides: false,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
            spaceBetween: 10,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 15,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
        1200: {
            slidesPerView: 4,
            spaceBetween: 20,
        }
    }
});

// New releases slider configuration
var newReleaseSlider = new Swiper('.new-release-slider', {
    loop: true,
    grabCursor: true,
    spaceBetween: 20,
    slidesPerView: 'auto',
    centeredSlides: false,
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
            spaceBetween: 10,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 15,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
        1200: {
            slidesPerView: 4,
            spaceBetween: 20,
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchIcon = document.getElementById('searchIcon');
    let searchTimeout;
    if (searchInput && searchIcon) {
        const searchWrapper = searchInput.parentElement;
        searchWrapper.addEventListener('mouseenter', function () {
            clearTimeout(searchTimeout);
            searchWrapper.classList.add('active');
            setTimeout(() => searchInput.focus(), 100);
        });
        searchWrapper.addEventListener('mouseleave', function () {
            if (document.activeElement !== searchInput) {
                searchTimeout = setTimeout(() => {
                    searchWrapper.classList.remove('active');
                }, 200);
            }
        });
        searchInput.addEventListener('blur', function () {
            searchTimeout = setTimeout(() => {
                searchWrapper.classList.remove('active');
            }, 200);
        });
        searchIcon.addEventListener('click', function (e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = `search.php?q=${encodeURIComponent(query)}`;
            } else {
                searchWrapper.classList.add('active');
                setTimeout(() => searchInput.focus(), 100);
            }
        });
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter' && this.value.trim()) {
                window.location.href = `search.php?q=${encodeURIComponent(this.value.trim())}`;
            }
        });
    }

    // Dropdown functionality (unified desktop & mobile)
    const userDropdown = document.getElementById('userDropdown');
    const userIcon = document.getElementById('userIcon');
    const dropdownContent = document.getElementById('dropdownContent');
    let dropdownTimeout;

    if (userDropdown && dropdownContent && userIcon) {
        // Desktop: hover
        userDropdown.addEventListener('mouseenter', function () {
            if (window.innerWidth > 992) {
                clearTimeout(dropdownTimeout);
                dropdownContent.style.display = 'block';
            }
        });
        userDropdown.addEventListener('mouseleave', function () {
            if (window.innerWidth > 992) {
                dropdownTimeout = setTimeout(() => {
                    dropdownContent.style.display = 'none';
                }, 100);
            }
        });

        // Mobile: click
        userIcon.addEventListener('click', function (e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                e.stopPropagation();
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
            }
        });

        // Click outside to close on mobile
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 992 && !userDropdown.contains(e.target)) {
                dropdownContent.style.display = 'none';
            }
        });

        // Hide dropdown on resize (prevents stuck open on mode switch)
        window.addEventListener('resize', function () {
            dropdownContent.style.display = 'none';
        });
    }

    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirm = document.getElementById('passwordConfirm');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    if (togglePasswordConfirm && passwordConfirm) {
        togglePasswordConfirm.addEventListener('click', function () {
            const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    // Handle favorites button if user is logged in
    const favoritesBtn = document.getElementById('favoritesBtn');
    if (favoritesBtn) {
        favoritesBtn.addEventListener('click', function () {
            // Add your favorites functionality here
            console.log('Favorites clicked');
        });
    }

    // Mobile menu toggle
    const menuBars = document.getElementById('menu-bars');
    const navbar = document.querySelector('.navbar');

    if (menuBars && navbar) {
        menuBars.addEventListener('click', function () {
            navbar.classList.toggle('active');
        });
    }

    // Form validation (optional)
    const form = document.querySelector('.form');
    if (form) {
        form.addEventListener('submit', function (e) {
            const username = document.querySelector('input[name="username"]');
            const email = document.querySelector('input[name="email"]');
            const password = document.querySelector('input[name="password"]');
            const passwordConfirm = document.querySelector('input[name="password_confirm"]');

            // Basic validation
            if (password && passwordConfirm && password.value !== passwordConfirm.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }

            if (password && password.value.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
        });
    }
});
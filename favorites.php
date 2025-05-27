<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>StrikeFlix</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<link rel="stylesheet" href="Assets/css/fav.css">
</head>

<body>
<header>
  <a href="#" class="logo"><i class="fa-solid fa-bolt"></i>StrikeFlix</a>
  <nav class="navbar">
    <a href="#anime" class="active">Anime</a>
    <a href="#comedy">Comedy</a>
    <a href="#drama">Drama</a>
    <a href="#romance">Romance</a>
  </nav>
  <div class="icons">
    <i class="fa-solid fa-magnifying-glass"></i>
    <i class="fa-solid fa-bell"></i>
    <a href="#" class="fa-solid fa-user"></a>
  </div>
</header>

<section class="anime" id="anime">
  <h1 class="heading"><i class="fa-solid fa-heart"></i>Favorites</h1>
  <hr />
  <div class="swiper anime-slider">
    <div class="swiper-wrapper">

      <div class="swiper-slide">
        <div class="box" style="background-image: url('Assets/images/dbz.jpg');"></div>
        <div class="content">
          <h3>Aho-Girl</h3>
          <p>Yoshiko Hanabatake is an idiot beyond all belief. <br>
            Somehow managing to consistently score zeroes on <br>
            all and consumed by an absurd obsession with bananas, <br>
            her senseless acts have caused even her own mother to lose all hope.</p>
          <a href="#" class="btn">Watch</a>
          
        </div>
      </div>

      <div class="swiper-slide">
        <div class="box" style="background-image: url('Assets/images/dbz.jpg');"></div>
        <div class="content">
          <h3>Shadows House</h3>
          <p>Yoshiko Hanabatake is an idiot beyond all belief. <br>
            Somehow managing to consistently score zeroes on <br>
            all and consumed by an absurd obsession with bananas, <br>
            her senseless acts have caused even her own mother to lose all hope.</p>
          <a href="#" class="btn">Watch</a>
          <div class="like-container">
            <button class="like-btn" aria-label="Like button">
              <i class="fa-regular fa-heart"></i>
              <i class="fa-solid fa-heart liked"></i>
            </button>
            <span class="like-count">0</span>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="box" style="background-image: url('Assets/images/dbz.jpg');"></div>
        <div class="content">
          <h3>Shadows House</h3>
          <p>Yoshiko Hanabatake is an idiot beyond all belief. <br>
            Somehow managing to consistently score zeroes on <br>
            all and consumed by an absurd obsession with bananas, <br>
            her senseless acts have caused even her own mother to lose all hope.</p>
          <a href="#" class="btn">Watch</a>
          <div class="like-container">
            <button class="like-btn" aria-label="Like button">
              <i class="fa-regular fa-heart"></i>
              <i class="fa-solid fa-heart liked"></i>
            </button>
            <span class="like-count">0</span>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="box" style="background-image: url('Assets/images/dbz.jpg');"></div>
        <div class="content">
          <h3>Shadows House</h3>
         <p>Yoshiko Hanabatake is an idiot beyond all belief. <br>
            Somehow managing to consistently score zeroes on <br>
            all and consumed by an absurd obsession with bananas, <br>
            her senseless acts have caused even her own mother to lose all hope.</p>
          <a href="#" class="btn">Watch</a>
          <div class="like-container">
            <button class="like-btn" aria-label="Like button">
              <i class="fa-regular fa-heart"></i>
              <i class="fa-solid fa-heart liked"></i>
            </button>
            <span class="like-count">0</span>
          </div>
        </div>
      </div>


      <div class="swiper-slide">
        <div class="box" style="background-image: url('Assets/images/dbz.jpg');"></div>
        <div class="content">
          <h3>Shadows House</h3>
         <p>Yoshiko Hanabatake is an idiot beyond all belief. <br>
            Somehow managing to consistently score zeroes on <br>
            all and consumed by an absurd obsession with bananas, <br>
            her senseless acts have caused even her own mother to lose all hope.</p>
          <a href="#" class="btn">Watch</a>
          <div class="like-container">
            <button class="like-btn" aria-label="Like button">
              <i class="fa-regular fa-heart"></i>
              <i class="fa-solid fa-heart liked"></i>
            </button>
            <span class="like-count">0</span>
          </div>
        </div>
      </div>


    </div>
  </div>
</section>

    <?php include 'Assets/HTML/footer.html' ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</body>

</html>

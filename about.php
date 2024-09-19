<?php

include 'config.php';

session_start();

// $user_id = $_SESSION['id'];

// if(!isset($user_id)){
//    header('location:login.php');
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>about us</h3>
   <p> <a href="index.php">home</a> / about </p>
</div>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>

      <div class="content">
         <h3>why choose us?</h3>
         <p>At BookNook, we believe in the power of books to transform lives. Our curated selection ensures that you receive the best in literature, handpicked by our team of experts. We strive to bring you titles that will inspire, educate, and entertain.</p>
         <p>Our commitment to quality and customer satisfaction sets us apart. We offer personalized recommendations, fast delivery, and exceptional customer service to make your reading experience enjoyable and hassle-free.</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>

   </div>

</section>

<section class="reviews">

   <h1 class="title">client's reviews</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.jpg" alt="">
         <p>"BookNook has completely transformed my reading experience! The handpicked selections are always spot-on, and their service is impeccable. I love the convenience of having amazing books delivered right to my door. Highly recommend!".</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Reewaz Aryal</h3>
      </div>

      <div class="box">
         <img src="images/pic-2.jpg" alt="">
         <p>"BookNook has reignited my passion for reading! The carefully curated books they deliver are not only captivating but also expand my horizons and knowledge. There's nothing quite like getting lost in a great book, and BookNook makes it so easy to discover new favorites. ".</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3> Basu D. Lamichhane</h3>
      </div>

      <div class="box">
         <img src="images/pic-3.jpg" alt="">
         <p>"Thanks to BookNook, I've discovered so many incredible books I wouldn't have found on my own. Their personalized recommendations are always on point, and I look forward to each delivery with excitement. Reading has become a delightful escape, and I couldn't be happier!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Bikal Aryal</h3>
      </div>

      <div class="box">
         <img src="images/pic-4.jpg" alt="">
         <p>"I've always loved reading, but finding new books was a challenge until I found BookNook. Their curated selections are fantastic, and the quality of their service is top-notch. Every book I've received has been a joy to read, and I can't wait for my next delivery. BookNook is game-changer!".</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Himanchal Timalsina</h3>
      </div>

      <div class="box">
         <img src="images/pic-5.jpg" alt="">
         <p>"BookNook has made reading a part of my daily routine again. The convenience of having amazing books delivered to my doorstep is unmatched. Each selection is a gem, and I've learned so much from the diverse range of genres they offer. Highly recommended for all book lovers!".</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Karan Sharma</h3>
      </div>

      <div class="box">
         <img src="images/pic-6.jpg" alt="">
         <p>"BookNook is perfect for busy readers like me. Their expertly chosen books have rekindled my love for reading, even with my hectic schedule. The excitement of discovering new authors and genres through their service is unbeatable. If you want to make reading a priority, BookNook is the way to go!".</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Amit Ramdam</h3>
      </div>

   </div>

</section>

<section class="authors">

   <h1 class="title">Great Authors</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/author-1.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fa-brands fa-wikipedia-w"></a>
         </div>
         <h3>Amar Neupane</h3>
      </div>

      <div class="box">
         <img src="images/author-2.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fa-brands fa-wikipedia-w"></a>
         </div>
         <h3>Chetan Bhagat</h3>
      </div>

      <div class="box">
         <img src="images/author-3.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a> 
            <a href="#" class="fa-brands fa-wikipedia-w"></a>
         </div>
         <h3>Jhamak Kumari Ghimire</h3>
      </div>

      <div class="box">
         <img src="images/author-4.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fa-brands fa-wikipedia-w"></a>
         </div>
         <h3>Laxmi Prasad Devkota</h3>
      </div>

      <div class="box">
         <img src="images/author-5.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fa-brands fa-wikipedia-w"></a>
         </div>
         <h3>William Shakespeare </h3>
      </div>

      <div class="box">
         <img src="images/author-6.jpg" alt="">
         <div class="share">
            <a href="https://www.facebook.com/hari.bamsha.acharya" class="fab fa-facebook-f"></a>
            <a href="https://www.youtube.com/@MahaSanchar?sub_confirmation=1 " class="fa-brands fa-youtube"></a>
            <a href="https://www.instagram.com/hari_bansha_acharya/" class="fab fa-instagram"></a>
            <a href="https://en.wikipedia.org/wiki/Hari_Bansha_Acharya" class="fa-brands fa-wikipedia-w"></a>
         </div>
         <h3>Hari Bansha Acharya</h3>
      </div>

   </div>

</section>







<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
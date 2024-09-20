<?php

include 'config.php';

session_start();
$select_authors = mysqli_query($conn, "SELECT * FROM `authors`") or die('Query failed');


// Assuming user is logged in and their ID is stored in the session
$user_id = $_SESSION['id'];
// Fetch user name from user_details table
$user_query = mysqli_query($conn, "SELECT username FROM user_details WHERE id='$user_id'");
$user_data = mysqli_fetch_assoc($user_query);
$user_name = $user_data['username'] ?? 'Guest';

// Handle review submission
if (isset($_POST['submit_review'])) {
    $review_text = $_POST['review_text'];
    $ratings = $_POST['rating'] ?? []; // Handle multiple ratings as an array
    $rating = count($ratings); // Get the number of checked boxes (rating)
    
    // Handle image upload
    $image = $_FILES['review_image']['name'];
    $image_temp = $_FILES['review_image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    // Move the uploaded image to the designated folder
    if (!empty($image)) {
        move_uploaded_file($image_temp, $image_folder);
    }

    // Insert the review into the database
    $insert_review = mysqli_query($conn, "INSERT INTO reviews (user_id, user_name, review_text, rating, review_image) VALUES ('$user_id', '$user_name', '$review_text', '$rating', '$image')") or die('Query failed');

    if ($insert_review) {
        echo "<script>alert('Review submitted successfully!');</script>";
    } else {
        echo "<script>alert('Review submission failed!');</script>";
    }
}

// Fetch reviews from the database
$select_reviews = mysqli_query($conn, "SELECT * FROM reviews") or die('Query failed');

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
       .review-form {
           display: flex;
           flex-direction: column;
           gap: 10px;
           margin-top: 20px;
       }
       .review-form input, .review-form textarea {
           padding: 10px;
           border-radius: 5px;
           border: 1px solid #ccc;
       }
       .rating {
           display: flex;
           gap: 5px;
           justify-content: flex-start; /* Align stars to the left */
       }
       .rating input {
           display: none;
       }
       .rating label {
           cursor: pointer;
           font-size: 20px;
           color: #ccc;
       }
       .rating input:checked + label {
           color: #f39c12; /* Highlight color for clicked star */
       }
       .review-container {
           min-height: 10vh;
           background-color: var(--light-bg);
           display: flex;
           align-items: center;
           justify-content: center;
           padding: 2rem;
       }
       .review-container form {
           padding: 2rem;
           width: 50rem;
           border-radius: .5rem;
           box-shadow: var(--box-shadow);
           border: var(--border);
           background-color: var(--white);
           text-align: center;
       }
       .review-container form .box {
           width: 100%;
           border-radius: .5rem;
           background-color: var(--light-bg);
           padding: 1.2rem 1.4rem;
           font-size: 1.8rem;
           color: var(--black);
           border: var(--border);
           margin: 1rem 0;
       }
       .rating-title {
           text-align: center;
           margin: 2rem;
           text-transform: uppercase;
           color: var(--black);
           font-size: 4rem;
       }
       .rating {
    display: flex;
    gap: 5px;
    justify-content: flex-start; /* Align stars to the left */
}

.rating input[type="checkbox"] {
    display: none; /* Hide checkboxes */
}

.rating label {
    cursor: pointer;
    font-size: 30px; /* Adjust size for better visibility */
    color: #ccc; /* Default color for unselected stars */
    transition: color 0.3s; /* Smooth transition for color change */
}

.rating input[type="checkbox"]:checked + label {
    color: #f39c12; /* Color for selected stars */
}
label{
   font-size: 20px;
}

   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>About Us</h3>
   <p><a href="index.php">Home</a> / About</p>
</div>

<section class="about">
   <div class="flex">
      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>
      <div class="content">
         <h3>Why Choose Us?</h3>
         <p>At BookNook, we believe in the power of books to transform lives. Our curated selection ensures that you receive the best in literature, handpicked by our team of experts. We strive to bring you titles that will inspire, educate, and entertain.</p>
         <p>Our commitment to quality and customer satisfaction sets us apart. We offer personalized recommendations, fast delivery, and exceptional customer service to make your reading experience enjoyable and hassle-free.</p>
         <a href="contact.php" class="btn">Contact Us</a>
      </div>
   </div>
</section>

<section class="reviews">
   <h1 class="rating-title">Client's Reviews</h1>

   <div class="box-container">
    <!-- Display existing reviews -->
    <?php
    if (mysqli_num_rows($select_reviews) > 0) {
        while ($row = mysqli_fetch_assoc($select_reviews)) {
            $user_image = !empty($row['review_image']) ? 'uploaded_img/' . $row['review_image'] : 'images/default-user.png';
    ?>
            <div class="box">
                <img src="<?php echo $user_image; ?>" alt="<?php echo $row['user_name']; ?>">
                <p><?php echo $row['review_text']; ?></p>
                <div class="stars">
                    <?php for ($i = 0; $i < $row['rating']; $i++) { ?>
                        <i class="fas fa-star"></i>
                    <?php } ?>
                    <?php for ($i = $row['rating']; $i < 5; $i++) { ?>
                        <i class="far fa-star"></i>
                    <?php } ?>
                </div>
                <h3><?php echo $row['user_name']; ?></h3>
            </div>
    <?php
        }
    } else {
        echo '<p class="empty">No reviews found!</p>';
    }
    ?>
</div>


   <h1 class="rating-title">Submit Your Review</h1>
   <div class="review-container">
      <form class="review-form" method="POST" enctype="multipart/form-data">
         <label for="review">Explain your Experience</label>
         <input type="text" class="box" name="review_text" placeholder="Write your review..." required>
         <label for="image">Upload Your Image</label>
         <input type="file" name="review_image" accept="image/*">
         <div class="rating">
            <input type="checkbox" name="rating[]" id="star1" value="1">
            <label for="star1">&#9733;</label>
            <input type="checkbox" name="rating[]" id="star2" value="2">
            <label for="star2">&#9733;</label>
            <input type="checkbox" name="rating[]" id="star3" value="3">
            <label for="star3">&#9733;</label>
            <input type="checkbox" name="rating[]" id="star4" value="4">
            <label for="star4">&#9733;</label>
            <input type="checkbox" name="rating[]" id="star5" value="5">
            <label for="star5">&#9733;</label>
         </div>
         <button type="submit" name="submit_review" class="btn">Submit Review</button>
      </form>
   </div>
</section>



<section class="authors">

   <h1 class="title">Great Authors</h1>

   <div class="box-container">

      <?php
      if(mysqli_num_rows($select_authors) > 0){
         while($row = mysqli_fetch_assoc($select_authors)){
      ?>
         <div class="box">
            <img src="uploaded_img/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
         </div>
      <?php
         }
      } else {
         echo '<p class="empty">No authors found!</p>';
      }
      ?>

   </div>

</section>
<?php include 'footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

<script>
    const stars = document.querySelectorAll('.rating input[type="checkbox"]');
    stars.forEach((star, index) => {
        star.addEventListener('change', () => {
            for (let i = 0; i <= index; i++) {
                stars[i].checked = true; // Check all previous stars
            }
            for (let i = index + 1; i < stars.length; i++) {
                stars[i].checked = false; // Uncheck all subsequent stars
            }
        });
    });
</script>

</body>
</html>

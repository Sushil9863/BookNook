<?php

include 'config.php';

session_start();

// $user_id = $_SESSION['id'];

// if(!isset($user_id)){
//    header('location:login.php');
// }

if(isset($_POST['add_to_cart'])){
   $user_id = $_SESSION['id'];
   if(!isset($user_id)){
      header('location:login.php');
   }

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }

}

if (isset($_POST['add_to_favorites'])) {
   $user_id = $_SESSION['id'];
   if (!isset($user_id)) {
       header('location:login.php');
   }

   $product_id = $_POST['product_id'];

   // Check if the product is already in favorites
   $check_favorites = mysqli_query($conn, "SELECT * FROM `favorites` WHERE user_id = '$user_id' AND product_id = '$product_id'") or die('query failed');

   if (mysqli_num_rows($check_favorites) > 0) {
       // If it is, remove it from favorites
       mysqli_query($conn, "DELETE FROM `favorites` WHERE user_id = '$user_id' AND product_id = '$product_id'") or die('query failed');
       $message[] = 'product removed from favorites!';
   } else {
       // If it is not, add it to favorites
       mysqli_query($conn, "INSERT INTO `favorites`(user_id, product_id) VALUES('$user_id', '$product_id')") or die('query failed');
       $message[] = 'product added to favorites!';
   }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      .heart-container {
         position: absolute;
         top: 10px;
         right: 10px;
      }

      .heart-btn {
         background: none;
         border: none;
         cursor: pointer;
         color: red;
         font-size: 20px;
      }

      .heart-btn i {
         color: #ff6666;
      }

      .heart-btn:hover i {
         color: #ff3333;
      }
   </style>

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>our shop</h3>
   <p> <a href="index.php">home</a> / shop </p>
</div>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">

      <?php  
         $user_id = $_SESSION['id']; // Assuming you store the user ID in session
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
               // Check if the product is already in favorites
               $product_id = $fetch_products['id'];
               $check_favorite = mysqli_query($conn, "SELECT * FROM `favorites` WHERE `user_id` = '$user_id' AND `product_id` = '$product_id'") or die('query failed');
               $is_favorite = mysqli_num_rows($check_favorite) > 0;
      ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="heart-container">
         <button type="submit" name="add_to_favorites" class="heart-btn">
            <i class="<?php echo $is_favorite ? 'fa-solid fa-heart' : 'fa-regular fa-heart'; ?>"></i>
         </button>
      </div>
      <div class="name"><?php echo $fetch_products['name']; ?></div>
      <div class="price">Rs.<?php echo $fetch_products['price']; ?>/-</div>
      <input type="number" min="1" name="product_quantity" value="1" class="qty">
      <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
      <input type="submit" value="add to cart" name="add_to_cart" class="btn">
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>










<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
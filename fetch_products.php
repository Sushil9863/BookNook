<?php
include 'config.php';

$genre = isset($_POST['genre']) ? $_POST['genre'] : '';
$author = isset($_POST['author']) ? $_POST['author'] : '';

$query = "SELECT * FROM products WHERE 1";

// Filter by genre if selected
if (!empty($genre)) {
   $query .= " AND genre = '$genre'";
}

// Filter by author if selected
if (!empty($author)) {
   $query .= " AND author_id = '$author'";
}

$query .= " ORDER BY id DESC LIMIT 6"; // You can change the limit as needed
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
   while ($fetch_products = mysqli_fetch_assoc($result)) {
      $product_id = $fetch_products['id'];
      $available_stock = $fetch_products['stocks'];
      ?>
      <form action="" method="post" class="box">
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="heart-container">
            <button type="submit" name="add_to_favorites" class="heart-btn">
               <i class="fa-regular fa-heart"></i>
            </button>
         </div>
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="price">Rs.<?php echo $fetch_products['price']; ?>/-</div>
         <div class="stock">Stock: <?php echo $available_stock; ?></div>
         <input type="number" min="1" name="product_quantity" value="1" class="qty">
         <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
         <input type="hidden" name="product_stock" value="<?php echo $available_stock; ?>">

         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </form>
      <?php
   }
} else {
   echo '<p class="empty">No products found!</p>';
}
?>

</html>
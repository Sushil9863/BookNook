<?php
include 'config.php';
session_start();

$user_id = $_SESSION['id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['update_cart'])) {
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];

   // Get the product name from the cart
   $cart_query = mysqli_query($conn, "SELECT name FROM `cart` WHERE id = '$cart_id'") or die('query failed');
   $cart_item = mysqli_fetch_assoc($cart_query);
   $product_name = $cart_item['name'];

   // Get the available stock from the products table
   $product_query = mysqli_query($conn, "SELECT stocks FROM `products` WHERE name = '$product_name'") or die('query failed');
   $product_info = mysqli_fetch_assoc($product_query);
   $available_stock = $product_info['stocks'];

   // Check if the requested quantity exceeds available stock
   if ($cart_quantity > $available_stock) {
      $cart_quantity = $available_stock; // Update to maximum available quantity
      $message[] = "Updated quantity for $product_name to available stock ($available_stock).";
      $message[] = "Warning: $product_name quantity exceeds available stock. Adjusted to $available_stock.";
   }

   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .stock {
         font-size: 15px;
      }
   </style>
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>shopping cart</h3>
      <p> <a href="index.php">home</a> / cart </p>
   </div>

   <section class="shopping-cart">
      <h1 class="title">Products Added</h1>

      <div class="box-container">
         <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
               $product_name = $fetch_cart['name'];
               $product_query = mysqli_query($conn, "SELECT stocks FROM `products` WHERE name = '$product_name'") or die('query failed');
               $product_info = mysqli_fetch_assoc($product_query);
               $available_stock = $product_info['stocks'];

               // Display warning if cart quantity exceeds available stock
               if ($fetch_cart['quantity'] > $available_stock) {
                  $warning[] = "Warning: $product_name quantity exceeds available stock ($available_stock). Please adjust the quantity.";
               }

               $sub_total = $fetch_cart['quantity'] * $fetch_cart['price'];
               $grand_total += $sub_total;
               ?>
               <div class="box">
                  <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times"
                     onclick="return confirm('delete this from cart?');"></a>
                  <img src="./uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_cart['name']; ?></div>
                  <div class="price">Rs.<?php echo $fetch_cart['price']; ?>/-</div>
                  <div class="stock"><?php echo "Stock: ", $available_stock ?></div>
                  <form action="" method="post">
                     <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                     <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                     <input type="submit" name="update_cart" value="update" class="option-btn">
                  </form>
                  <div class="sub-total"> sub total : <span>Rs.<?php echo $sub_total; ?>/-</span> </div>
               </div>
               <?php
            }
         } else {
            echo '<p class="empty">your cart is empty</p>';
         }
         ?>
      </div>

      <!-- Display warnings if any -->
      <?php if (isset($warning) && !empty($warning)) { ?>
         <p class="empty">
            <?php
            foreach ($warning as $warn) {
               echo $warn . "<br>"; // Use <br> to separate multiple warnings
            } ?>
         </p>
      <?php } ?>


      <div style="margin-top: 2rem; text-align:center;">
         <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>"
            onclick="return confirm('delete all from cart?');">delete all</a>
      </div>

      <div class="cart-total">
         <p>Grand Total : <span>Rs.<?php echo $grand_total; ?>/-</span></p>
         <div class="flex">
            <a href="shop.php" class="option-btn">Continue Shopping</a>
            <a href="checkout.php"
               class="btn <?php echo ($grand_total > 1 && empty($warning)) ? '' : 'disabled'; ?>">Proceed to
               Checkout</a>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>
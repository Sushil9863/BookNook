<?php

include 'config.php';

session_start();

$user_id = $_SESSION['id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['order_btn'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, '  '. $_POST['city'].', '. $_POST['district']);
   $placed_on = date('d-M-Y');
   $payment_status = "pending";

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
         $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(', ',$cart_products);

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   if($cart_total == 0){
      $message[] = 'your cart is empty';
   }else{
      if(mysqli_num_rows($order_query) > 0){
        $message[] = 'order already placed!'; 
      }else{
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on , payment_status) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on' ,'$payment_status')") or die('query failed');
         $message[] = 'order placed successfully!';
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      }
   }
   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">home</a> / checkout </p>
</div>

<section class="display-order">

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo 'Rs.'.$fetch_cart['price'].'/-'.' x '. $fetch_cart['quantity']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   <div class="grand-total"> Grand Total : <span>Rs.<?php echo $grand_total; ?>/-</span> </div>

</section>

<section class="checkout">

<form action="" method="post" onsubmit="return validateForm()">
    <h3>place your order</h3>
    <div class="flex">
        <div class="inputBox">
            <span>Your Name :</span>
            <input type="text" name="name" id="name" required placeholder="Enter your Name">
        </div>
        <div class="inputBox">
            <span>Your Number :</span>
            <input type="number" name="number" id="number" required placeholder="Enter your Number">
        </div>
        <div class="inputBox">
            <span>Your E-mail :</span>
            <input type="email" name="email" id="email" required placeholder="Enter your email">
        </div>
        <div class="inputBox">
            <span>Payment Method :</span>
            <select name="method" id="method">
                <option value="cash on delivery">Cash on Delivery</option>
                <option value="esewa">e-Sewa</option>
            </select>
        </div>
        <div class="inputBox">
            <span>City :</span>
            <input type="text" name="city" id="city" required placeholder="e.g. Parsa">
        </div>
        <div class="inputBox">
            <span>District :</span>
            <input type="text" name="district" id="district" required placeholder="e.g. Chitwan">
        </div>
    </div>
    <input type="submit" value="order now" class="btn" name="order_btn">
</form>

<script>
function validateForm() {
    // Get form inputs
    const name = document.getElementById("name").value;
    const number = document.getElementById("number").value;
    const email = document.getElementById("email").value;
    const city = document.getElementById("city").value;
    const district = document.getElementById("district").value;
    
    // Regular expressions for validation
    const nameRegex = /^[a-zA-Z\s]+$/;  // Letters and spaces only
    const numberRegex = /^(97|98)[0-9]{8}$/; 
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Validate name (letters only)
    if (!name.match(nameRegex)) {
        alert("Please enter a valid name.");
        return false;
    }

    // Validate phone number (7-15 digits)
    if (!number.match(numberRegex)) {
        alert("Please enter a valid phone number (10 digits).");
        return false;
    }

    // Validate email
    if (!email.match(emailRegex)) {
        alert("Please enter a valid email address.");
        return false;
    }

    // Validate city and district (both required, non-empty)
    if (city.trim() === "" || district.trim() === "") {
        alert("Please enter your city and district.");
        return false;
    }

    // If all validations pass
    return true;
}
</script>


</section>









<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
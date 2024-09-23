<?php

include 'config.php';
session_start();

$user_id = $_SESSION['id'];

if (!isset($user_id)) {
    header('location:login.php');
}


// Check if the order data is provided (reordering case)
if (isset($_GET['order_id']) && isset($_GET['products']) && isset($_GET['total_price'])) {
    $order_id = $_GET['order_id'];
    $products = $_GET['products'];
    $total_price = $_GET['total_price'];
    $user_name = $_GET['name'];    // Pre-fill name from the order
    $user_email = $_GET['email'];  // Pre-fill email from the order
    $user_address = $_GET['address'];  // Pre-fill address from the order
    $user_phone = $_GET['phone'];
} else {
    // Fetch user details for pre-filling the form
    $user_details_query = mysqli_query($conn, "SELECT * FROM `user_details` WHERE id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($user_details_query) > 0) {
        $user_details = mysqli_fetch_assoc($user_details_query);
        $user_name = $user_details['name'];
        $user_email = $user_details['email'];
        $user_address = $user_details['address'];
        $user_phone = $user_details['number'];
    } else {
        $user_name = '';
        $user_email = '';
        $user_address = '';
        $user_phone = '';
    }
    $products = ''; // If no reorder, leave products empty
    $total_price = 0;
}
if (isset($_POST['order_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['method']);
    $selected_items = mysqli_real_escape_string($conn, $_POST['selected_items']);
    $total_price = mysqli_real_escape_string($conn, $_POST['total_price']);
    
    // Insert the order into the orders table
    $place_order = mysqli_query($conn, "INSERT INTO `orders`(user_id, placed_on, name, number, email, address, method, total_products, total_price, payment_status) VALUES('$user_id', NOW(), '$name', '$phone', '$email', '$address', '$payment_method', '$selected_items', '$total_price', 'Pending')") or die('query failed');
    
    if ($place_order) {
        // Redirect or show success message
        echo '<script>alert("Your order has been placed successfully!");</script>';
        header('location:orders.php'); // Redirect to orders page after placing the order
        exit();
    } else {
        echo '<script>alert("Failed to place the order.");</script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Review Checkout</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Review Your Order</h3>
   <p><a href="index.php">Home</a> / Review Checkout</p>
   <h1>Your Order Summary</h1>
   <div class="order-summary">
      <p>Products: <strong><?php echo htmlspecialchars($products); ?></strong></p>
      <p>Total Price: <strong>Rs.<?php echo htmlspecialchars($total_price); ?>/-</strong></p>
   </div>
</div>

<section class="checkout">
   
   <!-- Checkout form -->
   <form action="" method="post" onsubmit="return validateForm()">
      <h3>Place Your Order</h3>
      <div class="flex">
   <div class="inputBox">
      <span>Your Name :</span>
      <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($user_name); ?>" placeholder="Enter your Name">
   </div>
   <div class="inputBox">
      <span>Your E-mail :</span>
      <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($user_email); ?>" placeholder="Enter your email">
   </div>
   <div class="inputBox">
      <span>Your Address :</span>
      <input type="text" name="address" id="address" required value="<?php echo htmlspecialchars($user_address); ?>" placeholder="Enter your Address">
   </div>
   <div class="inputBox">
      <span>Your Phone Number :</span>
      <input type="text" name="phone" id="number" required value="<?php echo htmlspecialchars($user_phone); ?>" placeholder="Enter your Phone Number">
   </div>
   <div class="inputBox">
      <span>Payment Method :</span>
      <select name="method" id="method">
         <option value="cash on delivery">Cash on Delivery</option>
         <option value="esewa">e-Sewa</option>
      </select>
   </div>
</div>

      <!-- Hidden fields to pass order details -->
      <input type="hidden" name="selected_items" value="<?php echo htmlspecialchars($products); ?>">
      <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($total_price); ?>">
      <input type="submit" value="Order Now" class="btn" name="order_btn">
   </form>
   <script>
function validateForm() {
   // Get form values
   const name = document.getElementById('name').value.trim();
   const email = document.getElementById('email').value.trim();
   const address = document.getElementById('address').value.trim();
   const phone = document.getElementById('number').value.trim();
   const method = document.getElementById('method').value;

   const nameRegex = /^[a-zA-Z\s]+$/;  // Letters and spaces only
    const numberRegex = /^(97|98)[0-9]{8}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

   // Name validation
   if (!name.match(nameRegex)) {
        alert("Please enter a valid name.");
        return false;
    }

   // Email validation (basic regex)
   if (!email.match(emailRegex)) {
        alert("Please enter a valid email address.");
        return false;
    }

   // Address validation
   if (address === "") {
      alert("Address is required");
      return false;
   }

   // Phone number validation (only digits and length)
   if (!phone.match(numberRegex)) {
        alert("Please enter a valid phone number (10 digits) start with 97 or 98 .");
        return false;
    }


   // Payment method validation
   if (method === "") {
      alert("Please select a payment method");
      return false;
   }

   // If all validations pass
   return true;
}
</script>
</section>

<?php include 'footer.php'; ?>

</body>
</html>

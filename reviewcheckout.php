<?php

include 'config.php';

session_start();

$user_id = $_SESSION['id']; // Assuming session stores the user ID

if (!isset($user_id)) {
   header('location:login.php');
}

// Fetch the products and total price from the URL
$products = isset($_GET['products']) ? urldecode($_GET['products']) : '';
$total_price = isset($_GET['total_price']) ? $_GET['total_price'] : '0';

// You can fetch additional user details if required (e.g., from a user_details table)
// For example, if user details exist in `user_details`, you can fetch the name, email, etc. from the database
$user_details_query = mysqli_query($conn, "SELECT * FROM `user_details` WHERE id = '$user_id'") or die('query failed');
if (mysqli_num_rows($user_details_query) > 0) {
   $user_details = mysqli_fetch_assoc($user_details_query);
   $user_name = $user_details['name']; // Assuming the column name is 'name'
   $user_email = $user_details['email']; // Assuming the column name is 'email'
   $user_address = $user_details['address']; // Assuming the column name is 'address'
} else {
   $user_name = '';
   $user_email = '';
   $user_address = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Review Checkout</title>
   <!-- Include your CSS files -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .form-container {
         max-width: 600px;
         margin: 0 auto;
         padding: 20px;
         background-color: #f4f4f4;
         border-radius: 10px;
      }

      .form-container h1 {
         text-align: center;
         margin-bottom: 20px;
      }

      .form-container form {
         display: flex;
         flex-direction: column;
      }

      .form-container input,
      .form-container textarea {
         margin-bottom: 15px;
         padding: 10px;
         font-size: 16px;
         border: 1px solid #ccc;
         border-radius: 5px;
      }

      .form-container button {
         padding: 10px;
         background-color: #4CAF50;
         color: white;
         border: none;
         cursor: pointer;
         border-radius: 5px;
         font-size: 18px;
      }

      .form-container button:hover {
         background-color: #45a049;
      }

      .order-summary {
         background-color: #fff;
         padding: 15px;
         border-radius: 10px;
         margin-bottom: 20px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }

      .order-summary p {
         font-size: 16px;
         margin-bottom: 10px;
      }
   </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="heading">
   <h3>Review Your Order</h3>
   <p><a href="home.php">Home</a> / Review Checkout</p>
   <h1>Your Order Summary</h1>
   <div class="order-summary">
      <p>Products: <strong><?php echo $products; ?></strong></p>
      <p>Total Price: <strong>Rs.<?php echo $total_price; ?>/-</strong></p>
   </div>
</div>

<section class="form-container">
   
   <!-- Checkout form -->
   <form action="checkout.php" method="post">
      <!-- Pre-fill the user details if available -->
      <input type="text" name="name" placeholder="Enter your name" value="<?php echo $user_name; ?>" required>
      <input type="email" name="email" placeholder="Enter your email" value="<?php echo $user_email; ?>" required>
      <textarea name="address" placeholder="Enter your address" rows="4" required><?php echo $user_address; ?></textarea>

      <!-- Hidden fields to pass the product and price information -->
      <input type="hidden" name="products" value="<?php echo htmlspecialchars($products); ?>">
      <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

      <!-- Submit button for completing the checkout -->
      <input type="submit" value="order now" class="btn" name="order_btn">
   </form>
</section>

<?php include 'footer.php'; ?>

</body>
</html>

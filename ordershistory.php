<?php

include 'config.php';

session_start();

$user_id = $_SESSION['id']; // Assuming session stores the user ID

if (!isset($user_id)) {
   header('location:login.php');
}

// Handle the Clear History button
if (isset($_POST['clear_history'])) {
   mysqli_query($conn, "DELETE FROM `orders` WHERE user_id = '$user_id' AND payment_status = 'Completed'") or die('query failed');
   header('location:ordershistory.php');
}

// Handle individual order deletion
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id' AND user_id = '$user_id'") or die('query failed');
   header('location:ordershistory.php');
}

// Fetch completed orders for the current user
$order_query = mysqli_query($conn, "
    SELECT * FROM `orders`
    WHERE user_id = '$user_id' 
    AND payment_status = 'Completed'
") or die('query failed');


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Order History</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      .action-btn {
         background-color: #4CAF50; /* Green */
         color: white;
         border: none;
         padding: 10px 20px;
         border-radius: 5px;
         cursor: pointer;
         transition: background-color 0.3s;
         margin-right: 10px;
      }

      .action-btn:hover {
         background-color: #45a049;
      }

      .delete-btn {
         background-color: #e62222;
      }

      .delete-btn:hover {
         background-color: #ff3636;
      }

      .clear-history-btn {
         margin-left: 45%;
         margin-top: 20px;
         background-color: #ff6b6b;
         padding: 12px 25px;
         cursor: pointer;
         color: white;
         border-radius: 5px;
         text-align: center;
         display: block;
      }

   </style>
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Your Order History</h3>
      <p><a href="index.php">Home</a> / Order History</p>
   </div>

   <section class="placed-orders">

      <h1 class="title">Completed Orders</h1>

      

      <div class="box-container">
         <?php
         if (mysqli_num_rows($order_query) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
         ?>
               <div class="box">
               <p> placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                  <p> name : <span><?php echo $fetch_orders['name']; ?></span> </p>
                  <p> number : <span><?php echo $fetch_orders['number']; ?></span> </p>
                  <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                  <p> address : <span><?php echo $fetch_orders['address']; ?></span> </p>
                  <p> payment method : <span><?php echo $fetch_orders['method']; ?></span> </p>
                  <p> your orders : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
                  <p> total price : <span>Rs.<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
                  <!-- Reorder and Delete buttons -->
                  <div style="margin-top: 2rem; text-align:center;">
                  <form action="reviewcheckout.php" method="get" style="display:inline;">
                     <!-- Pass the necessary order data as query parameters -->
                    <!-- Inside ordershistory.php -->
                  <form action="reviewcheckout.php" method="get" style="display:inline;">
                     <!-- Pass the necessary order data as query parameters -->
                     <input type="hidden" name="order_id" value="<?php echo $fetch_orders['user_id']; ?>">
                     <input type="hidden" name="products" value="<?php echo ($fetch_orders['total_products']); ?>">
                     <input type="hidden" name="total_price" value="<?php echo $fetch_orders['total_price']; ?>">
                     <input type="hidden" name="name" value="<?php echo ($fetch_orders['name']); ?>">
                     <input type="hidden" name="email" value="<?php echo ($fetch_orders['email']); ?>">
                     <input type="hidden" name="address" value="<?php echo ($fetch_orders['address']); ?>">
                     <input type="hidden" name="phone" value="<?php echo ($fetch_orders['number']); ?>">
                     <button type="submit" class="action-btn">Re-Order</button>
                  </form>

                  </form>



                     
                     <!-- Delete button -->
                     <form action="" method="get" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this order?');">
                        <input type="hidden" name="delete" value="<?php echo $fetch_orders['id']; ?>">
                        <button type="submit" class="action-btn delete-btn">Delete</button>
                     </form>
                  </div>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">No completed orders found!</p>';
         }
         ?>
      </div>
      </div style="text-align: center; margin-bottom: 20px;">
      <form method="post" action="" onsubmit="return confirm('Are you sure you want to clear your entire history?');">
         <button type="submit" name="clear_history" class="clear-history-btn">Clear History</button>
      </form>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Custom JS file link -->
   <script src="js/script.js"></script>

</body>

</html>

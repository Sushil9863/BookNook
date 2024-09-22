<?php

include 'config.php';

session_start();

$user_id = $_SESSION['id'];

if (!isset($user_id)) {
   header('location:login.php');
}

// If delete is set, delete the order based on the order_id instead of user_id
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id' AND user_id = '$user_id'") or die('query failed');
   header('location:orders.php');
}

// Handle the "Delete All" button
if (isset($_POST['delete_all'])) {
   mysqli_query($conn, "DELETE FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
   header('location:orders.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      /* Style for the delete button */
      .delete-btn {
         background-color: #e62222;
         color: white;
         border: none;
         padding: 10px 20px;
         border-radius: 5px;
         cursor: pointer;
         transition: background-color 0.3s;
      }

      .delete-btn:hover {
         background-color: #ff3636;
      }
   </style>
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>your orders</h3>
      <p> <a href="index.php">home</a> / orders </p>
   </div>

   <section class="placed-orders">

      <h1 class="title">placed orders</h1>

      <!-- Delete All Orders Button -->
     

      <div class="box-container">
         <?php
         $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
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
                  <p> payment status : <span style="color:<?php if ($fetch_orders['payment_status'] == 'Pending') {
                    echo 'red';
                     } 
                     else {
                   echo 'green';
                    }
                     ?>;"><?php echo $fetch_orders['payment_status']; ?></span> </p>

                  <!-- Delete button to remove individual order -->
                  <div style="margin-top: 2rem; text-align:center;">
                     <form action="orders.php" method="get" onsubmit="return confirm('delete this order?');">
                        <input type="hidden" name="delete" value="<?php echo $fetch_orders['id']; ?>">
                        <button type="submit" class="delete-btn">Delete Order</button>
                     </form>
                  </div>

               </div>
            
         <?php
            }
         } else {
            echo '<p class="empty">no orders placed yet!</p>';
         }
         ?>
      </div>
      <div style="text-align: center; margin-bottom: 20px;">
         <form action="orders.php" method="post" onsubmit="return confirm('Are you sure you want to delete all orders?');">
            <button type="submit" name="delete_all" class="delete-btn">Delete All Orders</button>
         </form>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Product List</title>

   <!-- font awesome cdn link for icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
   <!-- custom CSS -->
   <style>
      body {
         font-family: Arial, sans-serif;
         background-color: #f4f4f4;
         margin: 0;
         padding: 0;
      }

      .container {
         width: 80%;
         margin: 20px auto;
         display: flex;
         flex-wrap: wrap;
         justify-content: space-between;
      }

      .box {
         width: 300px;
         padding: 20px;
         border: 1px solid #ddd;
         margin: 15px;
         float: left;
         background: #fff;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         text-align: center;
         transition: transform 0.3s ease;
         position: relative;
      }

      .box:hover {
         transform: translateY(-10px);
      }

      .box img {
         width: 100%;
         height: auto;
         object-fit: cover;
         border-radius: 10px;
      }

      .name, .price, .stock {
         margin: 10px 0;
         font-size: 18px;
         font-weight: bold;
      }

      .heart-container {
         position: absolute;
         top: 10px;
         right: 10px;
      }

      .heart-btn {
         background: none;
         border: none;
         cursor: pointer;
         font-size: 20px;
         color: #e74c3c;
         transition: color 0.3s;
      }

      .heart-btn:hover {
         color: #ff6f61;
      }

      .btn {
         display: inline-block;
         padding: 10px 20px;
         background-color: #27ae60;
         color: white;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         transition: background-color 0.3s;
      }

      .btn:hover {
         background-color: #2ecc71;
      }

      .qty {
         width: 60px;
         padding: 5px;
         margin: 10px 0;
         border-radius: 5px;
         border: 1px solid #ddd;
         text-align: center;
      }

      .empty {
         font-size: 20px;
         font-weight: bold;
         text-align: center;
         margin-top: 20px;
      }
   </style>
</head>
<body>

<div class="container">
<?php
if (mysqli_num_rows($result) > 0) {
   while ($fetch_products = mysqli_fetch_assoc($result)) {
      $product_id = $fetch_products['id'];
      $available_stock = $fetch_products['stocks'];
      ?>
      <form action="" method="post" class="box">
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="Product Image">
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

         <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
      </form>
      <?php
   }
} else {
   echo '<p class="empty">No products found!</p>';
}
?>
</div>

</body>
</html>

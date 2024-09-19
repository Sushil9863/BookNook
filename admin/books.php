<?php
session_start();
include "./adminHeader.php";
include "./sidebar.php";
include_once "config/dbconnect.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is already logged in
if(!isset($_SESSION['adminname'])) {
    header("location: index.php");
    exit;
}

if(isset($_POST['add_product'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $price = $_POST['price'];
  $author_id = $_POST['author'];
  $stock = $_POST['stock'];
  $image = $_FILES['image']['name'];
  $genre = $_POST['genre'];
  $image_size = $_FILES['image']['size'];
  $image_tmp_name = $_FILES['image']['tmp_name'];
  $image_folder = '../uploaded_img/'.$image;

  $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

  if(mysqli_num_rows($select_product_name) > 0) {
     $message[] = 'product name already added';
  } else {
     $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, genre, price, author_id, image, stock) VALUES('$name','$genre', '$price', '$author_id', '$image', '$stock')") or die('query failed');

     if($add_product_query) {
        if($image_size > 2000000) {
           $message[] = 'image size is too large';
        } else {
           move_uploaded_file($image_tmp_name, $image_folder);
           $message[] = 'product added successfully!';
        }
     } else {
        $message[] = 'product could not be added!';
     }
  }
}

if(isset($_POST['update_product'])) {
  $update_p_id = $_POST['update_p_id'];
  $update_name = $_POST['update_name'];
  $update_price = $_POST['update_price'];
  $update_author_id = $_POST['update_author'];
  $update_stock = $_POST['update_stock'];

  mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', author_id = '$update_author_id', stocks = '$update_stock' WHERE id = '$update_p_id'") or die('query failed');

  $update_image = $_FILES['update_image']['name'];
  $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
  $update_image_size = $_FILES['update_image']['size'];
  $update_folder = '../uploaded_img/'.$update_image;
  $update_old_image = $_POST['update_old_image'];

  if(!empty($update_image)) {
     if($update_image_size > 2000000) {
        $message[] = 'image file size is too large';
     } else {
        mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
        move_uploaded_file($update_image_tmp_name, $update_folder);
        unlink('../uploaded_img/'.$update_old_image);
     }
  }

  header('location:books.php');
}

if(isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
  $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
  unlink('../uploaded_img/'.$fetch_delete_image['image']);
  mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
  header('location:books.php');
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Books</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="admin_style.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {
      background:url("assets/images/logo.png") no-repeat center;
      background-color:#475a99; 
    }
    table {
      align-self: center;
      text-align: center;
      border: 2px solid #fff;
      margin-top: 30px;
      margin-left: 80;
      width: calc(100% - 200px); /* Adjust width to fit container */
      border-collapse: separate; /* Ensure borders are separated */
      border-spacing: 0; /* Remove spacing between cells */
    }
    .table th, .table td {
      font-size: 1.2rem;
      color: #fff;
    }
    .table thead th {
      background-color: #4a5568; /* Darker grey background for the header */
    }
    .table tbody tr:nth-child(even) {
      background-color: rgba(74, 85, 104, 0.5); /* Slightly lighter background for even rows */
    }
    .table tbody tr:nth-child(odd) {
      background-color: rgba(74, 85, 104, 0.7); /* Slightly darker background for odd rows */
    }
    /* Action Buttons */
    .action-buttons {
      display: flex;
      gap: 10px; /* Add some space between the buttons */
    }
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s, transform 0.3s;
    }
    .btn-edit {
      background-color: #007bff;
      color: #fff;
    }
    .btn-edit:hover {
      background-color: #0056b3;
      transform: scale(1.05);
    }
    .btn-delete {
      background-color: #dc3545;
      color: #fff;
    }
    .btn-delete:hover {
      background-color: #c82333;
      transform: scale(1.05);
    }
    .btn-add {
      background-color: #28a745;
      color: #fff;
    }
    .btn-add:hover {
      background-color: #218838;
      transform: scale(1.05);
    }
    .add-book-container {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }
    .btn-add-book {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      background-color: #28a745;
      color: #fff;
      transition: background-color 0.3s, transform 0.3s;
    }
    .btn-add-book:hover {
      background-color: #218838;
      transform: scale(1.05);
    }
  </style>
</head>
<body>
  <div id="main">
    <button class="openbtn" onclick="openNav();" style="width:90px; border-radius:10px;">
      <i class="fa fa-book" style="font-size:60px;"></i>
    </button>
  </div>

  <section class="add-products">
    <h1 class="title">Shop Books</h1>
    <form action="" method="post" enctype="multipart/form-data">
      <h3>Add Books</h3>
      <input type="text" name="name" class="box" placeholder="Enter Book Name" required>
      <input type="number" min="150" name="price" class="box" placeholder="Enter Book Price" required>
      <select name="author" class="box" required>
        <option value="" disabled selected>Select Authors</option>
        <?php
        $select_authors = mysqli_query($conn, "SELECT * FROM `authors`") or die('query failed');
        if(mysqli_num_rows($select_authors) > 0){
          while($fetch_authors = mysqli_fetch_assoc($select_authors)){
            echo '<option value="'.$fetch_authors['id'].'">'.$fetch_authors['name'].'</option>';
          }
        } else {
          echo '<option value="" disabled>No authors available</option>';
        }
        ?>
      </select>
      <select name="genre" class="box" required>
      <option value="" disabled selected>Select Genre</option>
              <?php
              $select_genres = mysqli_query($conn, "SELECT * FROM `genres`") or die('query failed');
              if(mysqli_num_rows($select_genres) > 0){
                while($fetch_genres = mysqli_fetch_assoc($select_genres)){
                  $selected_genres = ($fetch_genres['id'] == $fetch_update['genre_id']) ? 'selected' : '';
                  echo '<option value="'.$fetch_genres['id'].'" '.$selected_genres.'>'.$fetch_genres['name'].'</option>';
                }
              } else {
                echo '<option value="" disabled>No genres available</option>';
              }
              ?>
            </select>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="number" name="stock" class="box" placeholder="Enter Book Stock" min="0" required>
      <input type="submit" value="Add Books" name="add_product" class="btn">
    </form>
  </section>

  <section class="show-products">
    <div class="box-container">
      <?php
      $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
      if(mysqli_num_rows($select_products) > 0){
        while($fetch_products = mysqli_fetch_assoc($select_products)){
          ?>
          <div class="box">
            <img src="../uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <div class="price">Rs.<?php echo $fetch_products['price']; ?>/-</div>
            <a href="books.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
            <a href="books.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
          </div>
          <?php
        }
      } else {
        echo '<p class="empty">No products added yet!</p>';
      }
      ?>
    </div>
  </section>

  <section class="edit-product-form">
    <?php
    if(isset($_GET['update'])){
      $update_id = $_GET['update'];
      $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
      if(mysqli_num_rows($update_query) > 0){
        while($fetch_update = mysqli_fetch_assoc($update_query)){
          ?>
          <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
            <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
            <img src="../uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
            <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Enter book name">
            <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="Enter book price">
            <select name="update_author" class="box" required>
              <?php
              $select_authors = mysqli_query($conn, "SELECT * FROM `authors`") or die('query failed');
              if(mysqli_num_rows($select_authors) > 0){
                while($fetch_authors = mysqli_fetch_assoc($select_authors)){
                  $selected = ($fetch_authors['id'] == $fetch_update['author_id']) ? 'selected' : '';
                  echo '<option value="'.$fetch_authors['id'].'" '.$selected.'>'.$fetch_authors['name'].'</option>';
                }
              } else {
                echo '<option value="" disabled>No authors available</option>';
              }
              ?>
            </select>
            <select name="update_genre" class="box" required>
              <?php
              $select_genres = mysqli_query($conn, "SELECT * FROM `genres`") or die('query failed');
              if(mysqli_num_rows($select_genres) > 0){
                while($fetch_genres = mysqli_fetch_assoc($select_genres)){
                  $selected_genres = ($fetch_genres['id'] == $fetch_update['genre_id']) ? 'selected' : '';
                  echo '<option value="'.$fetch_genres['id'].'" '.$selected_genres.'>'.$fetch_genres['name'].'</option>';
                }
              } else {
                echo '<option value="" disabled>No genres available</option>';
              }
              ?>
            </select>
            <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
            <input type="number" name="update_stock" value="<?php echo $fetch_update['stocks']; ?>" class="box" required placeholder="Enter book stock">

            <input type="submit" value="Update" name="update_product" class="option-btn btn-primary">
            <input type="reset" value="Cancel" id="close-update" class="option-btn" onclick="location.href = 'books.php'">
          </form>
          <?php
        }
      }
    } else {
      echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
    }
    ?>
  </section>

  <script type="text/javascript" src="assets/js/script.js"></script>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

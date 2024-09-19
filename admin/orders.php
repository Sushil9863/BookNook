<?php
            session_start();
            include "./adminHeader.php";
            include "./sidebar.php";
            include_once "config/dbconnect.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);


// check if the user is already logged in
if(!isset($_SESSION['adminname']))
{
    header("location: index.php");
    exit;
}



if(isset($_POST['update_genre'])){

  $update_g_id = $_POST['update_g_id'];
  $update_status = $_POST['update_status'];

  mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_status' WHERE id = '$update_g_id'") or die('query failed');


  header('location:orders.php');

}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
  header('location:orders.php');
}

?>



<!DOCTYPE html>
<html>
<head>
  <title>Orders</title>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
       <link rel="stylesheet" href="assets/css/style.css"></link>
       <link rel="stylesheet" href="admin_style.css"></link>
       <style>
    body{
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


.add-product-container {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.btn-add-product {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
  background-color: #28a745;
  color: #fff;
  transition: background-color 0.3s, transform 0.3s;
}

.btn-add-product:hover {
  background-color: #218838;
  transform: scale(1.05);
}
.bg-white{
  background:white;
}

  </style>
</head>
<body>
  <div id="main">
    <button class="openbtn" onclick="openNav();" style="width:90px; border-radius:10px;"><i class="fa fa-list" style="font-size:60px;"></i></button>
  </div>

<section class="show-products">

<div class="box-container">

   <?php
      $select_genres = mysqli_query($conn, "SELECT * FROM `orders` where payment_status = 'Pending'") or die('query failed');
      if(mysqli_num_rows($select_genres) > 0){
         while($fetch_genres = mysqli_fetch_assoc($select_genres)){
   ?>
   <div class="box bg-white">
      <div class="name"><?php echo"Name: ", $fetch_genres['name']; ?></div>
      <div class="name"><?php echo"Method: ", $fetch_genres['method']; ?></div>
      <div class="name"><?php echo"Products: ", $fetch_genres['total_products']; ?></div>
      <div class="name"><?php echo"Price: ", $fetch_genres['total_price']; ?></div>
      <div class="name"><?php echo"Payment Status: ", $fetch_genres['payment_status']; ?></div>
      <a href="orders.php?update=<?php echo $fetch_genres['id']; ?>" class="option-btn">update</a>
      <a href="orders.php?delete=<?php echo $fetch_genres['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">No Pending Orders For Now!</p>';
   }
   ?>
</div>
</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_g_id" value="<?php echo $fetch_update['id']; ?>">
      
      <select name="update_status" class="box" required>
        <option value="" disabled selected>Select Status</option>
        <option value="Pending">Pending</option>
        <option value="Completed">Completed</option>
      </select>



      <input type="submit" value="update" name="update_genre" class="option-btn btn-primary">
      <input type="reset" value="cancel" id="close-update" class="option-btn" onclick="location.href = 'orders.php'">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>
    <!-- <script type="text/javascript" src="./assets/js/ajaxWork.js"></script>     -->
    <script type="text/javascript" src="assets/js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
 
</html>
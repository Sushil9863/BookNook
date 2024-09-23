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

if(isset($_POST['update_genre'])){
  $update_g_id = $_POST['update_g_id'];
  $update_status = $_POST['update_status'];
  mysqli_query($conn, "UPDATE `user_details` SET Status = '$update_status' WHERE id = '$update_g_id'") or die('query failed');
  header('location:users.php');
}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM `user_details` WHERE id = '$delete_id'") or die('query failed');
  header('location:users.php');
}

$statusFilter = "";
if (isset($_GET['status'])) {
    $statusFilter = $_GET['status'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Users</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/style.css"></link>
  <link rel="stylesheet" href="admin_style.css"></link>
  <style>
    body {
      background: url("assets/images/logo.png") no-repeat center;
      background-color: #475a99;
    }
    table {
      text-align: center;
      border: 2px solid #fff;
      margin-top: 30px;
      margin-left: 80px;
      width: calc(100% - 200px);
      border-collapse: separate;
      border-spacing: 0;
    }
    .table th, .table td {
      font-size: 1.2rem;
      color: #fff;
    }
    .table thead th {
      background-color: #4a5568;
    }
    .table tbody tr:nth-child(even) {
      background-color: rgba(74, 85, 104, 0.5);
    }
    .table tbody tr:nth-child(odd) {
      background-color: rgba(74, 85, 104, 0.7);
    }
    .action-buttons {
      display: flex;
      gap: 10px;
    }
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s, transform 0.3s;
    }
    .btn-filter {
      background-color: #28a745;
      color: #fff;
    }
    .btn-filter:hover {
      background-color: #218838;
      transform: scale(1.05);
    }
    .box-users {
      text-align: center;
      color: white;
      padding: 2rem;
      border-radius: 0.5rem;
      background-color: rgba(5, 5, 5, 0.53);
    }
  </style>
</head>
<body>
  <div id="main">
    <button class="openbtn" onclick="openNav();" style="width:90px; border-radius:10px;"><i class="fa fa-users" style="font-size:60px;"></i></button>
  </div>

  <section class="add-products">
    <h1 class="title">Our Users</h1>

    <!-- Status Filter Buttons -->
    <div class="action-buttons" style="justify-content: center; margin-bottom: 20px;">
      <a href="users.php?status=Active" class="btn btn-filter">Active Users</a>
      <a href="users.php?status=Passive" class="btn btn-filter">Passive Users</a>
      <a href="users.php" class="btn btn-filter">All Users</a>
    </div>
  </section>

  <section class="show-products">
    <div class="box-container">
      <?php
      $query = "SELECT * FROM `user_details`";
      if ($statusFilter) {
          $query .= " WHERE Status = '$statusFilter'";
      }
      $select_users = mysqli_query($conn, $query) or die('query failed');
      if(mysqli_num_rows($select_users) > 0){
         while($fetch_users = mysqli_fetch_assoc($select_users)){
      ?>
      <div class="box-users">
        <div class="name"><?php echo "Name: ", $fetch_users['username']; ?></div>
        <div class="name"><?php echo "Email: ", $fetch_users['email']; ?></div>
        <div class="name"><?php echo "Contact: ", $fetch_users['contact_number']; ?></div>
        <div class="name"><?php echo "Status: ", $fetch_users['Status']; ?></div>
        <a href="users.php?update=<?php echo $fetch_users['id']; ?>" class="option-btn">Update</a>
        <a href="users.php?delete=<?php echo $fetch_users['id']; ?>" class="delete-btn" onclick="return confirm('delete this user?');">Delete</a>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">No users found!</p>';
      }
      ?>
    </div>
  </section>

  <section class="edit-product-form">
    <?php
    if(isset($_GET['update'])){
      $update_id = $_GET['update'];
      $update_query = mysqli_query($conn, "SELECT * FROM `user_details` WHERE id = '$update_id'") or die('query failed');
      if(mysqli_num_rows($update_query) > 0){
        while($fetch_update = mysqli_fetch_assoc($update_query)){
    ?>
    <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_g_id" value="<?php echo $fetch_update['id']; ?>">
      <div class="name box"><?php echo $fetch_update['username']; ?></div>
      <div class="name box"><?php echo $fetch_update['email']; ?></div>
      <div class="name box"><?php echo $fetch_update['contact_number']; ?></div>
      <select name="update_status" class="box" required>
        <option value="" disabled selected>Select Status</option>
        <option value="Active">Active</option>
        <option value="Passive">Passive</option>
      </select>
      <input type="submit" value="Update" name="update_genre" class="option-btn btn-primary">
      <input type="reset" value="Cancel" id="close-update" class="option-btn" onclick="location.href = 'users.php'">
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

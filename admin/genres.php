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



if(isset($_POST['add_genre'])){

  $name = mysqli_real_escape_string($conn, $_POST['name']);
//   $image = $_FILES['image']['name'];
//   $image_size = $_FILES['image']['size'];
//   $image_tmp_name = $_FILES['image']['tmp_name'];
//   $image_folder = '../uploaded_img/'.$image;

  $select_genre_name = mysqli_query($conn, "SELECT name FROM `genres` WHERE name = '$name'") or die('query failed');

  if(mysqli_num_rows($select_genre_name) > 0){
     $message[] = 'Genre name already added';
  }else{
     $add_genre_query = mysqli_query($conn, "INSERT INTO `genres`(name) VALUES('$name')") or die('query failed');

     if($add_genre_query){
           $message[] = 'Genre added successfully!';
     }else{
        $message[] = 'Genre could not be added!';
     }
  }
}

if(isset($_POST['update_genre'])){

  $update_g_id = $_POST['update_g_id'];
  $update_name = $_POST['update_name'];

  mysqli_query($conn, "UPDATE `genres` SET name = '$update_name' WHERE id = '$update_g_id'") or die('query failed');


  header('location:genres.php');

}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM `genres` WHERE id = '$delete_id'") or die('query failed');
  header('location:genres.php');
}

?>



<!DOCTYPE html>
<html>
<head>
  <title>Genres</title>
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


.show-products .box-container .box-users {
    text-align: center;
    color: white;
    padding: 2rem;
    border-radius: .5rem;
    border: var(--border);
    box-shadow: var(--box-shadow);
    background-color: rgba(5, 5, 5, 0.53);
}
  </style>
</head>
<body>
  <div id="main">
    <button class="openbtn" onclick="openNav();" style="width:90px; border-radius:10px;"><i class="fa fa-list" style="font-size:60px;"></i></button>
  </div>


  <section class="add-products">

   <h1 class="title">Our Genres</h1>

   <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateGenre()">
   <h3>Add Genre</h3>
   <input type="text" name="name" id="genre-name" class="box" placeholder="Enter Genre" required>
   <input type="submit" value="Add Genre" name="add_genre" class="option-btn">
</form>

<script>
   function validateGenre() {
      const genreName = document.getElementById('genre-name').value.trim();
      const genreRegex = /^[a-zA-Z\s]+$/; // Only allows letters and spaces
      if (!genreRegex.test(genreName)) {
         alert("Genre must not contain numbers or special characters.");
         return false;
      }
      return true;
   }
</script>


</section>






<section class="show-products">

<div class="box-container">

   <?php
      $select_genres = mysqli_query($conn, "SELECT * FROM `genres`") or die('query failed');
      if(mysqli_num_rows($select_genres) > 0){
         while($fetch_genres = mysqli_fetch_assoc($select_genres)){
   ?>
   <div class="box-users">
      <div class="name"><?php echo $fetch_genres['name']; ?></div>
      <a href="genres.php?update=<?php echo $fetch_genres['id']; ?>" class="option-btn">update</a>
      <a href="genres.php?delete=<?php echo $fetch_genres['id']; ?>" class="delete-btn" onclick="return confirm('delete this genre?');">delete</a>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">No genres added yet!</p>';
   }
   ?>
</div>
</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `genres` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_g_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter genre">
      <input type="submit" value="update" name="update_genre" class="option-btn btn-primary">
      <input type="reset" value="cancel" id="close-update" class="option-btn" onclick="location.href = 'genres.php'">
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
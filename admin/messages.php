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

  mysqli_query($conn, "UPDATE `user_details` SET Status = '$update_status' WHERE id = '$update_g_id'") or die('query failed');


  header('location:users.php');

}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
  header('location:messages.php');
}

?>



<!DOCTYPE html>
<html>
<head>
  <title>Messages</title>
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

.show-products .box-container .box-users{
   text-align: center;
   color:white;
   padding:2rem;
   border-radius: .5rem;
   border:var(--border);
   box-shadow: var(--box-shadow);
   background-color: rgba(5, 5, 5, 0.53);
}

  </style>
</head>
<body>
  <div id="main">
    <button class="openbtn" onclick="openNav();" style="width:90px; border-radius:10px;"><i class="fa regular fa-envelope" style="font-size:60px;"></i></button>
  </div>


  <section class="add-products">

   <h1 class="title">Message from Users</h1>
  </section>


  <section class="show-products">

<div class="box-container">

   <?php
      $select_users = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
      if(mysqli_num_rows($select_users) > 0){
         while($fetch_users = mysqli_fetch_assoc($select_users)){
   ?>
   <div class="box-users">
      <div class="name"><?php echo "Name: ", $fetch_users['name']; ?></div>
      <div class="name"><?php echo "Email: ", $fetch_users['email']; ?></div>
      <div class="name"><?php echo "Contact: ", $fetch_users['number']; ?></div>
      <div class="name"><?php echo "Message: ", $fetch_users['message']; ?></div>
      <!-- Reply Button -->
      <button class="option-btn" onclick="showReplyForm(<?php echo $fetch_users['id']; ?>)">Reply</button>
      <a href="messages.php?delete=<?php echo $fetch_users['id']; ?>" class="delete-btn" onclick="return confirm('delete this message?');">Delete</a>

      <!-- Hidden Reply Form -->
      <div id="reply-form-<?php echo $fetch_users['id']; ?>" class="reply-form" style="display:none; margin-top: 20px;">
        <h4>Reply to <?php echo $fetch_users['name']; ?></h4>
        <textarea id="reply-message-<?php echo $fetch_users['id']; ?>" class="form-control" rows="4" placeholder="Enter your reply"></textarea>
        <br>
        <button class="option-btn" onclick="replyViaGmail('<?php echo $fetch_users['email']; ?>', '<?php echo $fetch_users['name']; ?>', <?php echo $fetch_users['id']; ?>)">Reply via Gmail</button>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">No messages yet!</p>';
   }
   ?>
</div>
</section>

<!-- JavaScript to Handle Reply Form Display and Email -->
<script type="text/javascript">
  // Function to show the reply form
  function showReplyForm(id) {
    var form = document.getElementById('reply-form-' + id);
    if (form.style.display === 'none') {
      form.style.display = 'block';
    } else {
      form.style.display = 'none';
    }
  }

  // Function to open Gmail with the reply message
  function replyViaGmail(email, name, id) {
    var replyMessage = document.getElementById('reply-message-' + id).value;
    if (replyMessage === "") {
      alert("Please enter a reply message.");
      return;
    }

    var subject = "Re: Thank you for contacting us!";
    var body = "Dear " + name + ",\n\n" + replyMessage + "\n\nBest regards,\nBookNook Team";
    
    // Encode the subject and body to make them URL-safe
    subject = encodeURIComponent(subject);
    body = encodeURIComponent(body);

    // Redirect to Gmail with the pre-filled email
    var gmailUrl = "https://mail.google.com/mail/?view=cm&fs=1&to=" + email + "&su=" + subject + "&body=" + body;
    window.open(gmailUrl, '_blank'); // Open Gmail in a new tab
  }
</script>







    <!-- <script type="text/javascript" src="./assets/js/ajaxWork.js"></script>     -->
    <script type="text/javascript" src="assets/js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
 
</html>
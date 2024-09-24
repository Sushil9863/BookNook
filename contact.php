<?php

include 'config.php';

session_start();



if(isset($_POST['send'])){
   $user_id = $_SESSION['id'];

if(!isset($user_id)){
   header('location:login.php');
}

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'message sent successfully!';
   }

}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $email = $_POST['email'];

   // Query to check if the email exists in the database
   $query = "SELECT * FROM users WHERE email = '$email'";
   $result = mysqli_query($conn, $query);

   if (mysqli_num_rows($result) == 0) {
       echo "<script>alert('Email is not registered!');</script>";
   } else {
       // Proceed with form processing
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>contact us</h3>
   <p> <a href="index.php">home</a> / contact </p>
</div>

<section class="contact">

<form action="" method="post" onsubmit="return validateForm()">
   <h3>Say something!</h3>
   
   <input type="text" name="name" id="name" required placeholder="Enter your name" class="box">
   <input type="email" name="email" id="email" required placeholder="Enter your email" class="box">
   <input type="text" name="number" id="number" required placeholder="Enter your number" class="box">
   <textarea name="message" class="box" placeholder="Enter your message" id="message" cols="30" rows="10"></textarea>
   
   <input type="submit" value="Send Message" name="send" class="btn">
</form>

<script>
   function validateForm() {
      // Name validation: Must not start with a number, and must only contain letters, spaces, or valid characters
      const name = document.getElementById('name').value.trim();
      const nameRegex = /^[^\d][a-zA-Z\s]*$/;  // Ensures name doesn't start with a number and contains only valid characters
      if (!nameRegex.test(name)) {
         alert("Name must not start with a number and can only contain letters and spaces.");
         return false;
      }

      // Email validation: Must not start with a number and must contain @gmail.com
      const email = document.getElementById('email').value.trim();
      const emailRegex = /^[^\d][a-zA-Z0-9._%+-]+@gmail\.com$/; // Ensures email doesn't start with a number and ends with @gmail.com
      if (!emailRegex.test(email)) {
         alert("Email must not start with a number and must end with '@gmail.com'.");
         return false;
      }

      // Phone number validation: Must be exactly 10 digits long and start with 97 or 98
      const number = document.getElementById('number').value.trim();
      const numberRegex = /^(97|98)[0-9]{8}$/; // Ensures number starts with 97 or 98 and is exactly 10 digits
      if (!numberRegex.test(number)) {
         alert("Phone number must start with 97 or 98 and be exactly 10 digits long.");
         return false;
      }

      // All validations passed
      return true;
   }
</script>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
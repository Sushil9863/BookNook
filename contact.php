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
      // Name validation: must not start with a number
      const name = document.getElementById('name').value;
      const nameRegex = /^[^\d][\w\s]*$/;  // Ensures name doesn't start with a number
      if (!nameRegex.test(name)) {
         alert("Name must not start with a number.");
         return false;
      }

      // Email validation (handled by HTML5 input type email)
      
      // Number validation: Must start with 97 or 98 and be exactly 10 digits
      const number = document.getElementById('number').value;
      const numberRegex = /^(97|98)[0-9]{8}$/;
      if (!numberRegex.test(number)) {
         alert("Number must start with 97 or 98 and be 10 digits long.");
         return false;
      }

      // Message is optional but can add specific validation if needed
      return true;
   }
</script>


</section>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
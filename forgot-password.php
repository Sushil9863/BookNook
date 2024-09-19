<?php
// Include database connection file
include 'config.php'; // Assumes you have a config file for DB connection

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    // Check if the email exists in the database
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Store the token in the database with an expiration time (optional)
        $updateQuery = "UPDATE users SET reset_token='$token', token_expire=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email='$email'";
        mysqli_query($conn, $updateQuery);

        // Prepare the password reset link
        $resetLink = "http://yourwebsite.com/reset-password.php?token=$token";

        // Send reset email (you need a mail server for this)
        $subject = "Password Reset";
        $message = "Click the following link to reset your password: $resetLink";
        $headers = "From: noreply@yourwebsite.com";
        mail($email, $subject, $message, $headers);

        echo "A password reset link has been sent to your email.";
    } else {
        echo "No account found with that email address.";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="send-reset-link.php" method="post">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Send Reset Link">
    </form>
</body>
</html>

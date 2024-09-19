<?php
// Include database connection file
include 'config.php'; 

if (isset($_POST['token']) && isset($_POST['new_password'])) {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT); // Hash the password

    // Find the user with the matching token
    $query = "SELECT * FROM users WHERE reset_token='$token' AND token_expire > NOW()";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Update the password in the database
        $updateQuery = "UPDATE users SET password='$new_password', reset_token=NULL, token_expire=NULL WHERE reset_token='$token'";
        mysqli_query($conn, $updateQuery);
        echo "Your password has been reset successfully!";
    } else {
        echo "Invalid or expired token.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    <form action="update-password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>" required>
        <label for="new-password">New Password:</label>
        <input type="password" id="new-password" name="new_password" required>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

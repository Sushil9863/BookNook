<?php
// Include database connection file
include 'config.php'; // Assumes you have a config file for DB connection
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // For Composer
// If using manual download, use: require 'path-to-your-phpmailer-folder/src/PHPMailer.php';
// require 'path-to-your-phpmailer-folder/src/SMTP.php'; // For SMTP

session_start();

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if the email exists in the database
    $query = "SELECT * FROM user_details WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Generate a 4-digit OTP
        $otp = rand(1000, 9999);
        
        // Store the OTP in the session temporarily
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send OTP to the user's email using PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                    // Specify main SMTP server (Gmail, for example, smtp.gmail.com)
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'book.nook986@gmail.com';           // SMTP username
            $mail->Password = 'ftch pnwl ljse gffm';              // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            // Recipients
            $mail->setFrom('noreply@BookNook.com', 'BookNook');
            $mail->addAddress($email);                            // Add recipient email

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Password Reset OTP';
            $mail->Body    = "Your OTP for password reset is: <strong>$otp</strong>";

            // Send the email
            $mail->send();

            echo "<script>
                alert('A 4-digit OTP has been sent to your email.');
                window.location.href = 'forgot-password.php?step=verify';
            </script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No account found with that email address.";
    }
}

// Verification and reset password logic continues here...

if (isset($_POST['otp_verify'])) {
    $otp_input = $_POST['otp'];
    
    // Check if the OTP matches
    if ($_SESSION['otp'] == $otp_input) {
        // Redirect to password reset page
        echo "<script>
            alert('OTP verified successfully.');
            window.location.href = 'forgot-password.php?step=reset';
        </script>";
    } else {
        echo "Invalid OTP. Please try again.";
    }
}

if (isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];
    $email = $_SESSION['email'];
    
    // Update the password in the database (make sure to hash it)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $updateQuery = "UPDATE user_details SET password='$hashed_password' WHERE email='$email'";
    mysqli_query($conn, $updateQuery);
    
    // Clear session variables
    unset($_SESSION['otp']);
    unset($_SESSION['email']);
    
    echo "<script>
        alert('Password has been reset successfully.');
        window.location.href = 'login.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        /* Styles for the form */
        .form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 350px;
            padding: 20px;
            border-radius: 20px;
            background-color: #1a1a1a;
            color: #fff;
            border: 1px solid #333;
            margin-top: 30vh;
            margin-left: auto;
            margin-right: auto;
        }
        .title {
            font-size: 28px;
            font-weight: 600;
            text-align: center;
            color: #00bfff;
        }
        .message {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
        }
        .input {
            background-color: #333;
            color: #fff;
            width: 100%;
            padding: 10px;
            outline: 0;
            border: 1px solid rgba(105, 105, 105, 0.397);
            border-radius: 10px;
        }
        .submit {
            border: none;
            padding: 10px;
            border-radius: 10px;
            color: #fff;
            background-color: #00bfff;
            cursor: pointer;
        }
        .submit:hover {
            background-color: #00bfff96;
        }
    </style>
</head>
<body>
    <center>
    <form class="form" method="POST">
        <?php
        // Step 1: Enter Email
        if (!isset($_GET['step'])) {
        ?>
            <p class="title">Forgot Password</p>
            <p class="message">Enter your registered Email</p>
            <label>
                <input class="input" type="email" name="email" placeholder="Enter Email" required>
            </label> 
            <button class="submit" type="submit">Submit</button>

        <?php
        // Step 2: Verify OTP
        } elseif ($_GET['step'] == 'verify') {
        ?>
            <p class="title">Verify OTP</p>
            <p class="message">Enter the 4-digit OTP sent to your email</p>
            <label>
                <input class="input" type="text" name="otp" placeholder="Enter OTP" required>
            </label> 
            <button class="submit" name="otp_verify" type="submit">Verify OTP</button>

        <?php
        // Step 3: Reset Password
        } elseif ($_GET['step'] == 'reset') {
        ?>
            <p class="title">Reset Password</p>
            <p class="message">Enter your new password</p>
            <label>
                <input class="input" type="password" name="new_password" placeholder="New Password" required>
            </label> 
            <button class="submit" name="reset_password" type="submit">Reset Password</button>
        <?php
        }
        ?>
    </form>
    </center>
</body>
</html>

<?php
session_start();

// check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("location: index.php");
    exit;
}

require_once "config.php";

function custom_hash($password) {
    $salt = 'abc123!@#';
    $hashed = '';
    for ($i = 0; $i < strlen($password); $i++) {
        $hashed .= dechex(ord($password[$i]) + ord($salt[$i % strlen($salt)]));
    }
    return $hashed;
}

$username = $password = "";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty(trim($_POST['username'])) || empty(trim($_POST['password']))) {
        $err = "Please enter username and password";
        echo "<script>alert('$err');</script>";
    } else {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }

    if (empty($err)) {
        $sql = "SELECT id, username, password, status FROM user_details WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        $param_username = $username;
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // Try to execute this statement
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $stored_hashed_password, $status);
                if (mysqli_stmt_fetch($stmt)) {
                    if ($status === 'Active') {
                        // Compare the custom hashed password
                        if (custom_hash($password) === $stored_hashed_password) {
                            // Password is correct, allow login
                            session_start();
                            $_SESSION["username"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["loggedin"] = true;

                            // Redirect user to welcome page
                            header("location: index.php");
                        } else {
                            $err = "Username and password do not match.";
                        }
                    } else {
                        $err = "Your account is blocked due to some activity.";
                    }
                }
            } else {
                $err = "This user is not registered.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body, html {
        height: 100%;
        font-family: Arial, sans-serif;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(to bottom, #b0e0ff, #5f9ea0); /* Lighter blue gradient background */
    }

    .wrapper {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%; /* Full height */
    }

    .login-form-container {
        background-color: #1e73be;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        width: 320px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease; /* Add transition for smooth hover */
    }

    .login-form-container:hover {
        transform: translateY(-10px); /* Lift effect on hover */
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4); /* More shadow for hover effect */
    }

    .login-form-container h2 {
        margin-bottom: 20px;
        color: #fff;
    }

    .login-form-container label {
        display: block;
        text-align: left;
        color: #fff;
        margin-bottom: 8px;
    }

    .login-form-container input {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #5f9ea0;
        background-color: #4b59c1;
        color: white;
    }

    .login-form-container input::placeholder {
        color: #bbb;
    }

    .login-form-container .login-btn {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #f0c14b;
        color: #333;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .login-form-container .login-btn:hover {
        background-color: #ddb347;
    }

    .login-form-container p {
        color: #fff;
        font-size: 14px;
        margin-top: 10px;
    }

    .login-form-container a {
        color: #f0c14b;
        text-decoration: none;
        font-weight: bold;
    }

    .login-form-container a:hover {
        text-decoration: underline;
    }

    /* Error message styling */
    .error-message {
        color: red;
        font-size: 14px;
        margin-bottom: 20px;
        display: none; /* Initially hidden */
    }

    .error-message.visible {
        display: block;
    }
</style>

    </style>
    <title>Login</title>
</head>
<body>
    <div class="login-form-container">
        <h2>Login</h2>

        <!-- Error message placeholder -->
        <div class="error-message <?php echo !empty($err) ? 'visible' : ''; ?>">
            <?php echo $err; ?>
        </div>

        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Username" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <button type="submit" class="login-btn">Login Now</button>
        </form>

        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
    </div>
</body>
</html>

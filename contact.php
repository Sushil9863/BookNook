<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .contact form {
            margin-bottom: 50px;
        }

        /* Error message styling */
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
            display: none; /* Initially hidden */
        }

        .box.error {
            border-color: red;
        }

        .success-message {
            color: green;
            font-size: 12px;
            display: none; /* Initially hidden */
        }
    </style>
</head>
<body>


<?php include 'header.php'; ?>
    <div class="heading">
        <h3>Contact Us</h3>
        <p><a href="index.php">home</a> / Contact</p>
    </div>

    <section class="contact">
        <form id="contactForm" action="" method="post" onsubmit="return validateForm()">
            <h3>Say Something!</h3>

            <input type="text" name="name" id="name" required placeholder="Enter your name" class="box">
            <div id="nameError" class="error-message">Name must not start with a number and can only contain letters and spaces.</div>

            <input type="email" name="email" id="email" required placeholder="Enter your email" class="box">
            <div id="emailError" class="error-message">Email must not start with a number and must end with '@gmail.com'.</div>
            <div id="registeredError" class="error-message">This email is not registered.</div>

            <input type="text" name="number" id="number" required placeholder="Enter your number" class="box">
            <div id="numberError" class="error-message">Phone number must start with 97 or 98 and be exactly 10 digits long.</div>

            <textarea name="message" class="box" placeholder="Enter your message" id="message" cols="30" rows="10"></textarea>

            <input type="submit" value="Send Message" name="send" class="btn">
            <div id="successMessage" class="success-message">Message sent successfully!</div>
        </form>
    </section>

    <script>
        // Simulated registered emails for demonstration purposes
        const registeredEmails = ['test@gmail.com', 'example@gmail.com']; // Replace with your actual registered emails

        function validateForm() {
            // Clear previous error messages
            document.querySelectorAll('.error-message').forEach(element => element.style.display = 'none');
            document.querySelectorAll('.box').forEach(element => element.classList.remove('error'));
            let valid = true;

            // Name validation
            const name = document.getElementById('name').value.trim();
            const nameRegex = /^[^\d][a-zA-Z\s]*$/; 
            if (!nameRegex.test(name)) {
                document.getElementById('nameError').style.display = 'block';
                document.getElementById('name').classList.add('error');
                valid = false;
            }

            // Email validation
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\d][a-zA-Z0-9._%+-]+@gmail\.com$/;
            if (!emailRegex.test(email)) {
                document.getElementById('emailError').style.display = 'block';
                document.getElementById('email').classList.add('error');
                valid = false;
            } else {
                // Check if email is registered
                if (!checkEmailRegistered(email)) {
                    document.getElementById('registeredError').style.display = 'block';
                    document.getElementById('email').classList.add('error');
                    valid = false; // Set valid to false if the email is not registered
                }
            }

            // Phone number validation
            const number = document.getElementById('number').value.trim();
            const numberRegex = /^(97|98)[0-9]{8}$/; 
            if (!numberRegex.test(number)) {
                document.getElementById('numberError').style.display = 'block';
                document.getElementById('number').classList.add('error');
                valid = false;
            }

            // Prevent form submission if any validation failed
            return valid;
        }

        function checkEmailRegistered(email) {
            return registeredEmails.includes(email);
        }
    </script>

    <?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
        // Check if the form passed validation (you can implement server-side validation here too)
        if (validateForm()) {
            // Sanitize and retrieve form inputs
            $name = htmlspecialchars(trim($_POST['name']));
            $email = htmlspecialchars(trim($_POST['email']));
            $number = htmlspecialchars(trim($_POST['number']));
            $message = htmlspecialchars(trim($_POST['message']));

            // Insert the message into the database or send it to admin (dummy code here)
            // Assuming you have a database connection setup already
            $sql = "INSERT INTO contact_messages (name, email, number, message) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $number, $message);
            if ($stmt->execute()) {
                echo '<script>document.getElementById("successMessage").style.display = "block";</script>';
            } else {
                echo '<script>alert("There was an error sending your message. Please try again.");</script>';
            }
            $stmt->close();
        }
    }
    ?>

    <?php include 'footer.php'; ?>

    <!-- Custom JS file link -->
    <script src="js/script.js"></script>

</body>

</html>

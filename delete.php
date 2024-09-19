<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // Sanitize and retrieve the order ID
    $order_id = intval($_POST['order_id']);
    
    // Connect to the database
    $servername = "localhost";  // Change to your database server
    $username = "root";         // Change to your database username
    $password = "";             // Change to your database password
    $dbname = "booknook";  // Change to your database name
    
    // Create a new connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    if(isset($_GET['delete'])){
        $delete_id = $_GET['delete'];
        mysqli_query($conn, "DELETE FROM `orders` WHERE user_id = '$delete_id'") or die('query failed');
        header('location:orders.php');
     }
     if(isset($_GET['delete_all'])){
        mysqli_query($conn, "DELETE FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
        header('location:orders.php');
     }
     
    
    // if ($conn->query($sql) === TRUE) {
    //     // Redirect to orders.php after successful deletion
    //     header("Location: orders.php");
    //     exit();
    // } 
    else {
        echo "Error deleting record: " . $conn->error;
    }
    
    // Close the connection
    $conn->close();
}
?>

<?php
include 'config.php';

if (isset($_POST['author_id'])) {
    $author_id = mysqli_real_escape_string($conn, $_POST['author_id']);

    // Fetch books for this author
    $query = "SELECT name, price FROM `products` WHERE author_id = '$author_id'";
    $result = mysqli_query($conn, $query) or die('Query failed');

    $books = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row; // Add book to array
        }
    }

    // Send the book list as JSON
    echo json_encode($books);
}
?>

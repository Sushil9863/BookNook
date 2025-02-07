<?php

include 'config.php';

session_start();

$user_id = $_SESSION['id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['order_btn'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, '  ' . $_POST['city'] . ', ' . $_POST['district']);
    $placed_on = date('d-M-Y');
    $payment_status = "Pending";
    $grand_total = $_POST['grand_total'];

    echo $method;
    // echo $name;
    echo $grand_total . "<br>";

    if ($method == "esewa") {
        header('location:esewa.php?amount=' . $grand_total);
    }

    $cart_total = 0;
    $cart_products = [];

    $selected_items = explode(',', $_POST['selected_items']); // Get selected item IDs from hidden input

    if (count($selected_items) > 0) {
        foreach ($selected_items as $item_id) {
            $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' AND id = '$item_id'") or die('query failed');
            if (mysqli_num_rows($cart_query) > 0) {
                while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                    $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
                    $sub_total = ($cart_item['price'] * $cart_item['quantity']);
                    $cart_total += $sub_total;
                }
            }
        }
    }

    $total_products = implode(', ', $cart_products);

    if ($cart_total == 0) {
        $message[] = 'No items selected for the order!';
    } else {
        $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'order already placed!';
        } else {
            // Insert the order into the database
            mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on', '$payment_status')") or die('query failed');
            $message[] = 'order placed successfully!';

            // Loop through selected items to remove from the cart and update stock
            foreach ($selected_items as $item_id) {
                // Fetch item details from the cart
                $cart_item = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' AND id = '$item_id'") or die('query failed');
                if ($cart_item_row = mysqli_fetch_assoc($cart_item)) {
                    $product_name = $cart_item_row['name'];
                    $product_quantity = $cart_item_row['quantity'];

                    // Update the product stock
                    mysqli_query($conn, "UPDATE `products` SET stocks = stocks - $product_quantity WHERE name = '$product_name'") or die('query failed');
                }

                // Remove the item from the cart
                mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id' AND id = '$item_id'") or die('query failed');
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
    <title>Checkout</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .cart-items-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }

        .cart-item {
            background-color: #f7f7f7;
            /* Box background */
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin: 10px;
            width: 250px;
            /* Width for each book box */
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cart-item input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.5);
            /* Make checkbox bigger */
        }

        .cart-item label {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        .cart-item .item-price {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }

        .cart-item img {
            max-width: 100px;
            margin-bottom: 10px;
            align-self: center;
        }

        .cart-items-container .cart-item:nth-child(3n+1) {
            clear: both;
            /* Clear float every 3rd item */
        }

        /* Center the grand total and checkout section */
        #grand-total {
            font-size: 22px;
            color: #e74c3c;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .checkout-container {
            text-align: center;
            margin-top: 30px;
        }

        .checkout-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="heading">
        <h3>Checkout</h3>
        <p> <a href="home.php">Home</a> / Checkout </p>
    </div>

    <!-- Display Order Section -->
    <section class="display-order">
        <div class="cart-items-container">

            <?php
            $grand_total = 0;
            $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                    $grand_total += $total_price;
            ?>
                    <div class="cart-item">
                        <label>
                            <input type="checkbox" name="cart_items[]" value="<?php echo $fetch_cart['id']; ?>" checked onclick="updateTotal()"> <!-- Checkbox for each cart item -->
                            <?php echo $fetch_cart['name']; ?>
                            <span>(<?php echo 'Rs.' . $fetch_cart['price'] . '/-' . ' x ' . $fetch_cart['quantity']; ?>)</span>
                        </label>
                        <input type="hidden" class="item-price" value="<?php echo $total_price; ?>">
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">Your cart is empty</p>';
            }
            ?>
            <div class="grand-total">Grand Total: <span id="grand-total">Rs.<?php echo $grand_total; ?>/-</span></div>
        </div>

    </section>

    <!-- Checkout Section -->
    <section class="checkout">

        <form action="" method="post" onsubmit="return validateForm()">
            <h3>Place Your Order</h3>
            <div class="flex">
                <div class="inputBox">
                    <span>Your Name :</span>
                    <input type="text" name="name" id="name" required placeholder="Enter your Name">
                </div>
                <div class="inputBox">
                    <span>Your Number :</span>
                    <input type="number" name="number" id="number" required placeholder="Enter your Number">
                </div>
                <div class="inputBox">
                    <span>Your E-mail :</span>
                    <input type="email" name="email" id="email" required placeholder="Enter your email">
                </div>
                <div class="inputBox">
                    <span>Payment Method :</span>
                    <select name="method" id="method">
                        <option value="cash on delivery">Cash on Delivery</option>
                        <option value="esewa">e-Sewa</option>
                    </select>
                </div>

                <div class="inputBox">
                    <span>District :</span>
                    <select name="district" id="district" required onchange="updateCities()">
                        <option value="">Select District</option>
                        <option value="Bhaktapur">Bhaktapur</option>
                        <option value="Chitwan">Chitwan</option>
                        <option value="Dhading">Dhading</option>
                        <option value="Dolakha">Dolakha</option>
                        <option value="Kathmandu">Kathmandu</option>
                        <option value="Lalitpur">Lalitpur</option>
                        <option value="Makwanpur">Makwanpur</option>
                        <option value="Nuwakot">Nuwakot</option>
                        <option value="Rasuwa">Rasuwa</option>
                        <option value="Sindhuli">Sindhuli</option>
                        <option value="Sindhupalchok">Sindhupalchok</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>City :</span>
                    <select name="city" id="city" required>
                        <option value="">Select City</option>
                    </select>
                </div>
            </div>

            <input type="hidden" value="<?= $grand_total ?>" name="grand_total">
            <input type="hidden" name="selected_items" id="selected_items"> <!-- Hidden input for selected items -->
            <input type="submit" value="Order Now" class="btn" name="order_btn">
        </form>

        <!-- JavaScript for Grand Total Calculation and Validation -->
        <script>
            function updateTotal() {
                const checkboxes = document.querySelectorAll('input[name="cart_items[]"]');
                let grandTotal = 0;

                checkboxes.forEach(function(checkbox, index) {
                    if (checkbox.checked) {
                        const price = document.querySelectorAll('.item-price')[index].value;
                        grandTotal += parseFloat(price);
                    }
                });

                document.getElementById('grand-total').innerText = 'Rs.' + grandTotal + '/-';
            }

            function validateForm() {
                // Get form inputs
                const name = document.getElementById("name").value;
                const number = document.getElementById("number").value;
                const email = document.getElementById("email").value;
                const city = document.getElementById("city").value;
                const district = document.getElementById("district").value;

                // Regular expressions for validation
                const nameRegex = /^[a-zA-Z\s]+$/; // Letters and spaces only
                const numberRegex = /^(97|98)[0-9]{8}$/;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                // Validate name (letters only)
                if (!name.match(nameRegex)) {
                    alert("Please enter a valid name.");
                    return false;
                }

                // Validate phone number (7-15 digits)
                if (!number.match(numberRegex)) {
                    alert("Please enter a valid phone number (10 digits) start with 97 or 98 .");
                    return false;
                }

                // Validate email
                if (!email.match(emailRegex)) {
                    alert("Please enter a valid email address.");
                    return false;
                }

                // Validate city and district (both required, non-empty)
                if (city.trim() === "" || district.trim() === "") {
                    alert("Please enter your city and district.");
                    return false;
                }

                // Validate that at least one cart item is selected
                const checkboxes = document.querySelectorAll('input[name="cart_items[]"]:checked');
                const selectedItems = Array.from(checkboxes).map(checkbox => checkbox.value);

                if (selectedItems.length === 0) {
                    alert('Please select at least one item to proceed with the order.');
                    return false;
                }

                document.getElementById('selected_items').value = selectedItems.join(','); // Join selected item IDs into a comma-separated string

                // If all validations pass
                return true;
            }

            const cities = {
                Bhaktapur: ["Bhaktapur", "Suryabinayak", "Changunarayan", "Nagadesh", "Sallaghari"],
                Chitwan: ["Bharatpur", "Khairahani", "Ratnanagar", "Madi", "Meghauli", "Rapti"],
                Dhading: ["Dhading Besi", "Gajuri", "Khadichaur", "Thakre", "Arukharka"],
                Dolakha: ["Charikot", "Bhimeshwor", "Dolakha", "Kshamawati", "Suri"],
                Kathmandu: ["Baneshwor", "Maitighar", "Teku", "Kalimati", "Nagarjun", "Swayambhu", "Taudaha"],
                Lalitpur: ["Lalitpur", "Lagankhel", "Pulchowk", "Jawalkhel", "Sanepa"],
                Makwanpur: ["Hetauda", "Makwanpur Gadhi", "Bharta", "Thaha", "Manahari"],
                Nuwakot: ["Bidur", "Nuwakot", "Tadi", "Kharanitar", "Rudi"],
                Rasuwa: ["Dhunche", "Syafru Besi", "Bhorle", "Ramche", "Bhotechaur"],
                Sindhuli: ["Sindhuli", "Kamalamai", "Kuntabesi", "Duwakot", "Gajuri"],
                Sindhupalchok: ["Chautara", "Barabise", "Melamchi", "Dolakha", "Panchpokhari"]
            };

            function updateCities() {
                const districtSelect = document.getElementById("district");
                const citySelect = document.getElementById("city");
                const selectedDistrict = districtSelect.value;

                // Clear previous options
                citySelect.innerHTML = '<option value="">Select City</option>';

                if (selectedDistrict) {
                    // Populate cities based on selected district
                    cities[selectedDistrict].forEach(city => {
                        const option = document.createElement("option");
                        option.value = city;
                        option.textContent = city;
                        citySelect.appendChild(option);
                    });
                }
            }
        </script>


    </section>

    <?php include 'footer.php'; ?>

    <!-- Custom JS File Link -->
    <script src="js/script.js"></script>

</body>

</html>
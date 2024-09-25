<?php
session_start();

// error_reporting(E_ALL);
// ini_set('display_errors', 0);

// check if the user is already logged in
if (!isset($_SESSION['adminname'])) {
    header("location: index.php");
    exit;
}

include_once "config/dbconnect.php";

// Fetch genre distribution data
$sql = "
    SELECT g.name AS genre_name, COUNT(p.id) AS book_count
    FROM products p
    JOIN genres g ON p.genre = g.id
    GROUP BY g.name
";
$result = $conn->query($sql);

$genres = [];
$bookCounts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $genres[] = $row['genre_name'];
        $bookCounts[] = $row['book_count'];
    }
}

// Fetch overall purchase data
// Fetch purchase data
// Fetching total purchases from the orders table
$sql = "
    SELECT placed_on, SUM(CAST(total_price AS DECIMAL(10, 2))) AS total_amount
    FROM orders
    WHERE payment_status = 'completed'
    GROUP BY placed_on
    ORDER BY STR_TO_DATE(placed_on, '%d-%b-%Y')
";
$result = $conn->query($sql);

$purchaseDates = [];
$totalAmounts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $purchaseDates[] = $row['placed_on']; // Dates for x-axis
        $totalAmounts[] = (float)$row['total_amount']; // Total amounts for y-axis
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: #475a99;
        }
        .content {
            height: 70vh;
            background: url("assets/images/logo.png") no-repeat center;
        }

        #booksByGenreChart, #overallPurchasesChart {
            padding: 15px;
            background: white;
            border-radius: 25px;
        }
    </style>
</head>
<body>
    <?php include "./adminHeader.php"; ?>
    <?php include "./sidebar.php"; ?>
    <div id="main">
        <button class="openbtn" onclick="openNav();" style="width:85px; border-radius:10px;">
            <i class="fa fa-home" style="font-size:60px;"></i>
        </button>
    </div>
    <div class="content">
        <div id="main-content" class="container allContent-section py-4">
            <div class="row">
                <!-- Existing cards -->
                <div class="col-sm-3">
                    <div class="card">
                        <i class="fa fa-book mb-2" style="font-size: 70px; color:white;"></i>
                        <h4 style="color:white;">Books</h4>
                        <h5 style="color:white;">
                            <?php
                            $sql = "SELECT * from products";
                            $result = $conn->query($sql);
                            $count = 0;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $count++;
                                }
                            }
                            echo $count;
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <i class="fa fa-list mb-2" style="font-size: 70px; color:white;"></i>
                        <h4 style="color:white;">Genres</h4>
                        <h5 style="color:white;">
                            <?php
                            $sql = "SELECT * from genres";
                            $result = $conn->query($sql);
                            $count = 0;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $count++;
                                }
                            }
                            echo $count;
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <i class="fa fa-male mb-2" style="font-size: 70px; color:white;"></i>
                        <h4 style="color:white;">Authors</h4>
                        <h5 style="color:white;">
                            <?php
                            $sql = "SELECT * from authors";
                            $result = $conn->query($sql);
                            $count = 0;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $count++;
                                }
                            }
                            echo $count;
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <i class="fa fa-users mb-2" style="font-size: 70px; color:white;"></i>
                        <h4 style="color:white;">Users</h4>
                        <h5 style="color:white;">
                            <?php
                            $sql = "SELECT * from user_details";
                            $result = $conn->query($sql);
                            $count = 0;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $count++;
                                }
                            }
                            echo $count;
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <i class="fa fa-list mb-2" style="font-size: 70px; color:white;"></i>
                        <h4 style="color:white;">Orders</h4>
                        <h5 style="color:white;">
                            <?php
                            $sql = "SELECT * from orders";
                            $result = $conn->query($sql);
                            $count = 0;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $count++;
                                }
                            }
                            echo $count;
                            ?>
                        </h5>
                    </div>
                </div>
            </div>
            <center>
                <div class="col-sm-25" style="margin-top:50px;">
                    <div class="card-chart">
                        <h4 style="color:white;">Books by Genre</h4>
                        <canvas id="booksByGenreChart" style="width: 100%; height: 400px;"></canvas>
                    </div>
                </div>
            </center>
            <center>
                <div class="col-sm-25" style="margin-top:50px;">
                    <div class="card-chart">
                        <h4 style="color:white;">Overall Purchases</h4>
                        <canvas id="overallPurchasesChart" style="width: 100%; height: 400px;"></canvas>
                    </div>
                </div>
            </center>
        </div>
        <?php include 'adminfooter.php' ?>
    </div>
    <script>
        var ctx = document.getElementById('booksByGenreChart').getContext('2d');
        var booksByGenreChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($genres); ?>,
                datasets: [{
                    label: 'Number of Books',
                    data: <?php echo json_encode($bookCounts); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Books',
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Genres',
                        }
                    }
                }
            }
        });

        var ctx2 = document.getElementById('overallPurchasesChart').getContext('2d');
        var purchasesLineChart = new Chart(ctx2, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($purchaseDates); ?>,
        datasets: [{
            label: 'Total Purchases',
            data: <?php echo json_encode($totalAmounts); ?>,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Total Amount'
                },
                ticks: {
                    stepSize: 50, // Adjust as needed
                    beginAtZero: true
                }
            }
        }
    }
});


    </script>
    
    <script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>

<?php
session_start();

// error_reporting(E_ALL);
// ini_set('display_errors', 0);

// Check if the user is already logged in
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
    <link rel="stylesheet" href="admin_style.css">
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
            margin-left: 0px;
            padding: 15px;
            background: white;
            border-radius: 25px;
        }
        .card-chart {
            width: 100vw;
        }
        .btn-filter{
            width: 100px;
            color:white;
            padding: 5px;
            border-radius: 5px;
            background-color: blue;
            align-self: center;
        }
        .row{
            margin-top: 20px;
        }
        .row input{
            font-size: 15px;
            height: 35px;
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
                        <h3 style="color:white;">Books</h3>
                        <h5 style="color:white;">
                            <?php
                            $sql = "SELECT * from products";
                            $result = $conn->query($sql);
                            echo $result->num_rows; // Display the count of books directly
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <i class="fa fa-list mb-2" style="font-size: 70px; color:white;"></i>
                        <h3 style="color:white;">Genres</h3>
                        <h5 style="color:white;">
                            <?php
                            $sql = "SELECT * from genres";
                            $result = $conn->query($sql);
                            echo $result->num_rows; // Display the count of genres directly
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <i class="fa fa-male mb-2" style="font-size: 70px; color:white;"></i>
                        <h3 style="color:white;">Authors</h3>
                        <h5 style="color:white;">
                            <?php
                            $sql = "SELECT * from authors";
                            $result = $conn->query($sql);
                            echo $result->num_rows; // Display the count of authors directly
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <i class="fa fa-users mb-2" style="font-size: 70px; color:white;"></i>
                        <h3 style="color:white;">Users</h3>
                        <h5 style="color:white;">
                            <?php
                            $sql = "SELECT * from user_details";
                            $result = $conn->query($sql);
                            echo $result->num_rows; // Display the count of users directly
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <i class="fa fa-list mb-2" style="font-size: 70px; color:white;"></i>
                        <h3 style="color:white;">Orders</h3>
                        <h5 style="color:white;">
                            <?php
                            $sql = "SELECT * from orders";
                            $result = $conn->query($sql);
                            echo $result->num_rows; // Display the count of orders directly
                            ?>
                        </h5>
                    </div>
                </div>
            </div>
            
                
        </div>
    </div>
            <center>

                <h1 style="color:white;">Books by Genre</h1>
                <div class="col-sm-25" style="margin-top:50px;">
                    <div class="card-chart">
                        <canvas id="booksByGenreChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
                <h1 style="color:white;">Overall Purchases</h1>
                <h3 style="color:white;">Filter Sales by Date</h3>
                <div class="row" style="margin-left: 30%;">
                    <div class="col-sm-3">
                       <input type="date" id="startDate" class="form-control" placeholder="Start Date">
                    </div>
                    <div class="col-sm-3">
                        <input type="date" id="endDate" class="form-control" placeholder="End Date">
                    </div>
                    <div class="col-sm-2">
                        <button class="btn-filter" onclick="filterSales()">Filter</button>
                    </div>
                </div>
                <div class="col-sm-25" style="margin-top:50px;">
                    <div class="card-chart">
                        <canvas id="overallPurchasesChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </center>
    <?php include 'adminfooter.php' ?>
    <script src="assets\js\script.js"></script>
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
                        display: true,
                    title: {
                        display: true,
                        text: 'Date of Purchase'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Total Amount (in currency)'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    function filterSales() {
        var startDate = document.getElementById('startDate').value;
        var endDate = document.getElementById('endDate').value;

        if (!startDate || !endDate) {
            alert('Please select both start and end dates.');
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "fetchSalesData.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                purchasesLineChart.data.labels = response.dates;
                purchasesLineChart.data.datasets[0].data = response.amounts;
                purchasesLineChart.update();
            }
        };
        xhr.send("startDate=" + startDate + "&endDate=" + endDate);
    }
</script>

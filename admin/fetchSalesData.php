<?php
include_once "config/dbconnect.php";

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

$sql = "
    SELECT placed_on, SUM(CAST(total_price AS DECIMAL(10, 2))) AS total_amount
    FROM orders
    WHERE payment_status = 'completed'
    AND STR_TO_DATE(placed_on, '%d-%b-%Y') BETWEEN STR_TO_DATE('$startDate', '%Y-%m-%d') AND STR_TO_DATE('$endDate', '%Y-%m-%d')
    GROUP BY placed_on
    ORDER BY STR_TO_DATE(placed_on, '%d-%b-%Y')
";
$result = $conn->query($sql);

$purchaseDates = [];
$totalAmounts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $purchaseDates[] = $row['placed_on'];
        $totalAmounts[] = (float)$row['total_amount'];
    }
}

echo json_encode([
    'dates' => $purchaseDates,
    'amounts' => $totalAmounts
]);
?>

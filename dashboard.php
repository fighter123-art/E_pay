<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user data from session
$email = $_SESSION['email'];
$fullname = $_SESSION['fullname'];
$userid = $_SESSION['userid'];
$contact = $_SESSION['contact'];
$counter = $_SESSION['counter'];
$snno = $_SESSION['snno'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | E-Pay</title>
    <link rel="icon" type="image/png" href="../Icon.png">
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <div class="Header">
        <div class="logo">
            <h1>E-<br>Pay</h1>
        </div>
        <div class="welcome-text">
            <p>Welcome, <span><?php echo htmlspecialchars($fullname); ?></span></p>
        </div>
    </div>
    <div class="Dashboard">
        <h2>Dashboard</h2>
        <div class="Dashboard-line"></div>
        <div class="due-payment">
            <h3>Due Payment</h3>
            <div class="customer-info">
                <p>Customer Name: <span><?php echo htmlspecialchars($fullname); ?></span></p>
                <p>Counter: <span><?php echo htmlspecialchars($counter); ?></span></p>
                <p>Customer ID: <span><?php echo htmlspecialchars($userid); ?></span></p>
                <p>SC.No: <span><?php echo htmlspecialchars($snno); ?></span></p>
            </div>
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>Due Date</th>
                        <th>Bill Amt</th>
                        <th>Rebate(R)/Fine(F)</th>
                        <th>Payable Amt</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example row, replace with actual data -->
                    <tr>
                        <td>Ashwin, 2081</td>
                        <td>1,569.00</td>
                        <td>(F)95</td>
                        <td>1,664.00</td>
                    </tr>
                </tbody>
            </table>
            <button class="pay-now">PAY NOW</button>
        </div>
        <div class="bill-history">
            <h3>Bill History</h3>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Bill No.</th>
                        <th>Customer ID</th>
                        <th>Bill Date</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example row, replace with actual data -->
                    <tr>
                        <td>1</td>
                        <td><?php echo htmlspecialchars($userid); ?></td>
                        <td>12/09/2024</td>
                        <td>2000.00</td>
                        <td>25/09/2024</td>
                        <td><a href="#" class="view-details">View Details</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

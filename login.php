<?php
session_start();
include 'db_connection.php'; // Include your database connection file

$error_message = "";  // Variable to store error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // Sanitize email input
    $password = trim($_POST['password']); // Sanitize password input

    if (!empty($username) && !empty($password)) {
        // Query to check if the email exists
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Set session variables for the logged-in user
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['userid'] = $user['userid'];
                    $_SESSION['contact'] = $user['contact'];
                    $_SESSION['counter'] = $user['counter'];
                    $_SESSION['snno'] = $user['snno'];

                    // Redirect to the dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error_message = "Invalid email or password.";
                }
            } else {
                $error_message = "User does not exist.";
            }
            $stmt->close();
        } else {
            $error_message = "Database query failed: " . $conn->error;
        }
    } else {
        $error_message = "Please fill in all fields.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Pay | Login</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>E-<span>Pay</span></h1>

            <!-- Display error message if login fails -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="login.php" method="post">
                <div class="input">
                    <label for="username">Email</label>
                    <input type="email" name="username" id="username" placeholder="Enter your Email" required>
                </div>
                <div class="input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your Password" required>
                </div>
                <button type="submit" class="login-btn">Log In</button>
            </form>

            <div class="register-link">
                <p>New to E-Pay? <a href="register.php">Register Now</a></p>
            </div>
        </div>
    </div>
</body>
</html>

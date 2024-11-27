<?php
// Include the connection file at the top
include 'db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variables to hold error messages for each field
$fullname_error = "";
$email_error = "";
$contact_error = "";
$userid_error = "";
$password_error = "";
$gender_error = "";
$general_error = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $counter = trim($_POST['counter']);
    $userid = trim($_POST['userid']);
    $snno = trim($_POST['snno']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $gender = trim($_POST['gender']);

    // Validation for each field
    $is_valid = true;

    if (empty($fullname)) {
        $fullname_error = "Full name is required.";
        $is_valid = false;
    }

    if (empty($email)) {
        $email_error = "Email is required.";
        $is_valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
        $is_valid = false;
    }

    if (empty($contact)) {
        $contact_error = "Contact number is required.";
        $is_valid = false;
    } elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $contact_error = "Invalid contact number. It must be 10 digits.";
        $is_valid = false;
    }

    if (empty($userid)) {
        $userid_error = "User ID is required.";
        $is_valid = false;
    }

    if (empty($password)) {
        $password_error = "Password is required.";
        $is_valid = false;
    } elseif (strlen($password) < 6) {
        $password_error = "Password must be at least 6 characters long.";
        $is_valid = false;
    } elseif ($password !== $confirm_password) {
        $password_error = "Passwords do not match.";
        $is_valid = false;
    }

    if (empty($gender)) {
        $gender_error = "Gender is required.";
        $is_valid = false;
    }

    // If all fields are valid, proceed with the database checks
    if ($is_valid) {
        // Check for duplicate contact, email, or UserID
        $check_query = "SELECT * FROM users WHERE contact = ? OR email = ? OR userid = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("sss", $contact, $email, $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $general_error = "The contact number, email, or UserID is already registered.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user data
            $insert_query = "INSERT INTO users (fullname, email, contact, counter, userid, snno, password, gender) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param(
                "ssssssss",
                $fullname,
                $email,
                $contact,
                $counter,
                $userid,
                $snno,
                $hashed_password,
                $gender
            );

            if ($stmt->execute()) {
                $success_message = "Registration successful! Redirecting to login page...";
                header("Refresh: 2; url=login.php");
                exit();
            } else {
                $general_error = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Pay | Registration</title>
    <link rel="icon" type="image/png" href="../Icon.png">
    <link rel="stylesheet" href="register.css">
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .success-message {
            color: green;
            font-size: 1em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Welcome to<br><span>E-Pay</span></h1>
            <p>Pay Smart, Pay Easy</p>
            <div class="login-prompt">
                <p>Already have an account?</p>
                <a href="login.php" class="cta-button">Login</a>
            </div>
        </div>
        <div class="white-shape"></div>
        <div class="form-container">
            <h2>Register Your Account</h2>

            <!-- Display general error or success message -->
            <?php if (!empty($general_error)): ?>
                <div class="alert error">
                    <p class="error-message"><?php echo htmlspecialchars($general_error); ?></p>
                </div>
            <?php elseif (!empty($success_message)): ?>
                <div class="alert success">
                    <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <!-- Full Name -->
                <div class="form-group">
                    <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname ?? ''); ?>" placeholder="Full Name *" required>
                    <?php if (!empty($fullname_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($fullname_error); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" placeholder="E-mail *" required>
                    <?php if (!empty($email_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($email_error); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Contact Number -->
                <div class="form-group">
                    <input type="tel" name="contact" value="<?php echo htmlspecialchars($contact ?? ''); ?>" placeholder="Mobile No. *" required>
                    <?php if (!empty($contact_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($contact_error); ?></p>
                    <?php endif; ?>
                </div>

                <!-- NEA Counter -->
                <div class="form-group">
                    <select name="counter" required>
                        <option value="" disabled selected>---- NEA Counter ----</option>
                        <option value="243" <?php echo (isset($counter) && $counter == "243") ? 'selected' : ''; ?>>AANBU</option>
                        <option value="391" <?php echo (isset($counter) && $counter == "391") ? 'selected' : ''; ?>>ACHHAM</option>
                        <option value="273" <?php echo (isset($counter) && $counter == "273") ? 'selected' : ''; ?>>AMUWA</option>
                        <option value="268" <?php echo (isset($counter) && $counter == "268") ? 'selected' : ''; ?>>ANARMANI</option>
                        <!-- Add more options here -->
                    </select>
                </div>

                <!-- Customer ID -->
                <div class="form-group">
                    <input type="text" name="userid" value="<?php echo htmlspecialchars($userid ?? ''); ?>" placeholder="Customer ID *" required>
                    <?php if (!empty($userid_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($userid_error); ?></p>
                    <?php endif; ?>
                </div>

                <!-- SC Number -->
                <div class="form-group">
                    <input type="text" name="snno" value="<?php echo htmlspecialchars($snno ?? ''); ?>" placeholder="SC No. *" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <input type="password" name="password" id="password" placeholder="Password *" required>
                    <?php if (!empty($password_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($password_error); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password *" required>
                </div>

                <!-- Gender -->
                <div class="gender-group">
                    <label>
                        <input type="radio" name="gender" value="Male" <?php echo (isset($gender) && $gender == "Male") ? 'checked' : ''; ?> required> Male
                    </label>
                    <label>
                        <input type="radio" name="gender" value="Female" <?php echo (isset($gender) && $gender == "Female") ? 'checked' : ''; ?> required> Female
                    </label>
                    <label>
                        <input type="radio" name="gender" value="Other" <?php echo (isset($gender) && $gender == "Other") ? 'checked' : ''; ?> required> Other
                    </label>
                    <?php if (!empty($gender_error)): ?>
                        <p class="error-message"><?php echo htmlspecialchars($gender_error); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="cta-button2" name="register_btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

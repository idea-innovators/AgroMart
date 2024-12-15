<?php
session_start();
ob_start(); 
include 'config.php';


// Check if the form is submitted
if (isset($_POST['register'])) {
    // Collect form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $confirm_email = $_POST['confirm_email'];
    $contact_number = $_POST['contact'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Validate email confirmation
    if ($email != $confirm_email) {
        echo "Emails do not match!";
        exit;
    }

    // Check if the user already exists
    $user_check_sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($user_check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already registered!";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (username, email, password, contact_number, address) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $password, $contact_number, $address);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            echo "Error registering user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'navbar.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }

        /* Centered Wrapper */
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }



    </style>
</head>
<body>
    <div class="wrapper">
        <div class="register-container">
            <div class="plant-image"></div>
            <div class="register-form">
                <h2>Register</h2>
                <form action="register.php" method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="email" name="confirm_email" placeholder="Confirm Email" required>
                    <input type="text" name="contact" placeholder="Contact Number" required>
                    <input type="text" name="address" placeholder="Address" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="register">Register</button>
                </form>
                <p class="p1">Already have an account? <a class="link" href="login.php">Login here</a>.</p>
            </div>
        </div>
    </div>
</body>
</html>

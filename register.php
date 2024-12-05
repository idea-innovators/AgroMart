<?php
session_start();
ob_start(); // Start output buffering
include 'config.php'; 
include 'navbar.php';


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
            // Get the inserted user's ID
            $user_id = $stmt->insert_id;

            // Set session variables to log the user in
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
            // Registration successful, redirect to the user's ads page
            header("Location: my_ads.php");
            exit;
        }
        else {
            echo "Error registering user.";
        }
        
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>

<body>
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="email" name="confirm_email" placeholder="Confirm Email" required><br>
        <input type="text" name="contact" placeholder="Contact Number" required><br>
        <input type="text" name="address" placeholder="Address" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="register">Register</button>
    </form>
</body>

</html>
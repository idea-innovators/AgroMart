<?php
session_start();
include 'config.php'; 

if (isset($_POST['submit_email'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Email found, set a session to allow password reset
        $_SESSION['reset_email'] = $email;
        header("Location: forgotpw.php?reset_password=true");
        exit;
    } else {
        echo "Email not found!";
    }
}

// Handle password reset when email is verified
if (isset($_POST['reset_password'])) {
    if (isset($_SESSION['reset_email'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];

        // Update the password in the database
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_password, $email);
        $stmt->execute();

        // Unset session and confirm success
        unset($_SESSION['reset_email']);
        echo "Password has been reset. <a href='login.php'>Login here</a>";
        exit;
    } else {
        echo "Session expired or no reset request found!";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>

<body>
    <h2>Forgot Password</h2>
    <?php if (!isset($_GET['reset_password'])): ?>
    <!-- Email form -->
    <form action="forgotpw.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required><br>
        <button type="submit" name="submit_email">Submit</button>
    </form>
    <?php else: ?>
    <!--  Reset Password form (after email verification) -->
    <form action="forgotpw.php" method="POST">
        <input type="password" name="new_password" placeholder="New password" required><br>
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
    <?php endif; ?>
</body>

</html>
<?php
session_start();
include 'config.php'; 

// Check if the token is valid
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Retrieve the user with this token
    $sql = "SELECT * FROM users WHERE reset_token = ? AND reset_expiry >= ?";
    $stmt = $conn->prepare($sql);
    $currentTime = date("U");
    $stmt->bind_param("si", $token, $currentTime);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Proceed with password reset
        if (isset($_POST['reset'])) {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $email = $user['email'];

            // Update the new password in the database
            $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $new_password, $email);
            $stmt->execute();

            echo "Password has been reset. <a href='login.php'>Login here</a>";
            exit;
        }
    } else {
        echo "Invalid or expired token!";
        exit;
    }
} else {
    echo "No token provided!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>

<body>
    <h2>Reset Password</h2>
    <form action="resetpw.php?token=<?= $token ?>" method="POST">
        <input type="password" name="password" placeholder="New password" required><br>
        <button type="submit" name="reset">Reset Password</button>
    </form>
</body>

</html>
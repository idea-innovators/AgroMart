<?php
include '../config.php';

if (isset($_POST['reset_password'])) {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $new_password = bin2hex(random_bytes(4)); // Generate a random password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password in the database
        $update_stmt = $conn->prepare("UPDATE admins SET password = ? WHERE email = ?");
        $update_stmt->bind_param('ss', $hashed_password, $email);

        if ($update_stmt->execute()) {
            echo "New password: $new_password";
        } else {
            echo "Failed to reset password.";
        }
    } else {
        echo "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="admin_forgotpw.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required><br>
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
</body>
</html>

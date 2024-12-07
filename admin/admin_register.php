<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $confirm_email = $_POST['confirm_email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if emails match
    if ($email !== $confirm_email) {
        echo "Emails do not match.";
        exit;
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $check_email->bind_param('s', $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo "Email already exists. Please use the 'forgot password' option.";
    } else {
        // Insert new admin
        $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $email, $password);
        if ($stmt->execute()) {
            echo "Admin registered successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<form method="POST" action="admin_register.php">
    <input type="text" name="username" placeholder="Admin Username" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="email" name="confirm_email" placeholder="Confirm Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
</form>
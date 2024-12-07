<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

    // Delete the user from the database
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Failed to delete user.";
    }
}

// Fetch all users from the database
$result = $conn->query("SELECT * FROM users");
?>

!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
</head>
<body>
    <h2>Manage Users</h2>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Address</th>
            <th>Action</th>
        </tr>
        <?php while ($user = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $user['username'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['contact_number'] ?></td>
            <td><?= $user['address'] ?></td>
            <td><a href="view_users.php?delete_user=<?= $user['user_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
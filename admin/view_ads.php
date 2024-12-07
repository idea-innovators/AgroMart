<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['delete_ad'])) {
    $ad_id = $_GET['delete_ad'];

    // Delete ad from the database
    $stmt = $conn->prepare("DELETE FROM ads WHERE ad_id = ?");
    $stmt->bind_param('i', $ad_id);
    if ($stmt->execute()) {
        echo "Ad deleted successfully!";
    } else {
        echo "Failed to delete ad.";
    }
}

// Fetch all ads from the database
$result = $conn->query("SELECT ads.*, users.username FROM ads JOIN users ON ads.user_id = users.user_id");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View & Delete Ads</title>
</head>
<body>
    <h2>View & Delete Ads</h2>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Ad ID</th>
            <th>Description</th>
            <th>Price</th>
            <th>Posted By</th>
            <th>Action</th>
        </tr>
        <?php while ($ad = $result->fetch_assoc()) { ?>
        <tr>
        <td><?= $ad['title'] ?></td>
        <td><?= $ad['ad_id'] ?></td>
        <td><?= $ad['description'] ?></td>
        <td><?= $ad['price'] ?></td>
        <td><?= $ad['username'] ?></td>
        <td><a href="view_ads.php?delete_ad=<?= $ad['ad_id'] ?>" onclick="return confirm('Are you sure want to delete this Ad?')">Delete</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>

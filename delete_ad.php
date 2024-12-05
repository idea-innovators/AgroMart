<?php
session_start();
include 'config.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['ad_id'])) {
    $ad_id = $_GET['ad_id'];

    // Ensure the ad belongs to the logged-in user
    $sql = "DELETE FROM ads WHERE ad_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $ad_id, $_SESSION['user_id']);
    $stmt->execute();

    // Optionally, delete associated images from the `ad_images` table
    $img_sql = "DELETE FROM ad_images WHERE ad_id = ?";
    $stmt_img = $conn->prepare($img_sql);
    $stmt_img->bind_param("i", $ad_id);
    $stmt_img->execute();

    header("Location: my_ads.php");
    exit();
} else {
    echo "Ad ID is missing!";
}
?>
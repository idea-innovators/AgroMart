<?php
session_start();
include 'config.php';

$ad_id = $_GET['ad_id'];

// Fetch the current ad details
$ad_sql = "SELECT * FROM ads WHERE ad_id = ?";
$stmt = $conn->prepare($ad_sql);
$stmt->bind_param("i", $ad_id);
$stmt->execute();
$ad_result = $stmt->get_result();
$ad = $ad_result->fetch_assoc();

// Fetch ad images
$img_sql = "SELECT * FROM ad_images WHERE ad_id = ?";
$stmt_img = $conn->prepare($img_sql);
$stmt_img->bind_param("i", $ad_id);
$stmt_img->execute();
$img_result = $stmt_img->get_result();

// Fetch categories
$categories_sql = "SELECT * FROM categories";
$categories_result = $conn->query($categories_sql);

?>

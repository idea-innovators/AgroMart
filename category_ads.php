<?php
session_start();
include 'config.php';
include 'navbar.php'; 

// Check if category_id is provided
if (isset($_GET['category_id_qp'])) {
    $category_id = $_GET['category_id_qp'];

    // Fetch all ads for the selected category
    $ad_sql = "SELECT * FROM ads WHERE category_id = ?";
    $stmt = $conn->prepare($ad_sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
?>

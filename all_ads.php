<?php
session_start();
include 'config.php';
include 'navbar.php';

// Fetch all ads from the database
$ads_sql = "
    SELECT ads.*, categories.category_name, 
        (SELECT image_path FROM ad_images WHERE ad_id = ads.ad_id LIMIT 1) AS image 
    FROM ads 
    JOIN categories ON ads.category_id = categories.category_id 
    ORDER BY ads.created_at DESC";
$result = $conn->query($ads_sql);
?>

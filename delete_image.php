<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    echo 'error';
    exit();
}

if (isset($_POST['image_id'])) {
    $image_id = $_POST['image_id'];

    // Fetch the image path to delete the file
    $sql = "SELECT image_path FROM ad_images WHERE image_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $image = $result->fetch_assoc();
        $image_path = $image['image_path'];

        // Delete the image file
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete the image from the database
        $delete_sql = "DELETE FROM ad_images WHERE image_id = ?";
        $stmt_delete = $conn->prepare($delete_sql);
        $stmt_delete->bind_param("i", $image_id);
        $stmt_delete->execute();

        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conn->close();
}
?>
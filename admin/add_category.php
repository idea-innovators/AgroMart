
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
</head>
<body>
    <h2>Add Category</h2>

    <form action="add_category.php" method="POST" enctype="multipart/form-data">
        <label for="category_name">Category Name:</label>
        <input type="text" name="category_name" required><br>

        <label for="category_image">Category Image:</label>
        <input type="file" name="category_image" accept="image/*" required><br>

        <input type="submit" name="add_category" value="Add Category">
    </form>

    <?php
    include '../config.php';

    if (isset($_POST['add_category'])) {
        $category_name = $_POST['category_name'];
        $category_image = $_FILES['category_image']['name'];
        $image_temp_name = $_FILES['category_image']['tmp_name'];

        // Set target directory for the image
        $target_dir = "../uploads/categories/";
        $target_file = $target_dir . basename($category_image);

        // Check if image upload is successful
        if (move_uploaded_file($image_temp_name, $target_file)) {
            // Insert category details into the database
            $stmt = $conn->prepare("INSERT INTO categories (category_name, category_image) VALUES (?, ?)");
            $stmt->bind_param("ss", $category_name, $target_file);

            if ($stmt->execute()) {
                echo "Category added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Failed to upload image.";
        }
    }
    ?>
</body>
</html>
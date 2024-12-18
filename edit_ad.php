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

// Handle form submission
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $phone_number = $_POST['phone_number'];
    $category_id = $_POST['category'];
    $district = $_POST['district'];  

    $update_sql = "UPDATE ads SET title = ?, description = ?, price = ?, phone_number = ?, category_id = ?, district = ? WHERE ad_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssdsisi", $title, $description, $price, $phone_number, $category_id, $district, $ad_id);
    $stmt->execute();

    // Handle new image uploads
    if (!empty($_FILES['new_images']['name'][0])) {
        for ($i = 0; $i < count($_FILES['new_images']['name']); $i++) {
            if ($_FILES['new_images']['error'][$i] == 0) {
                $image_name = basename($_FILES['new_images']['name'][$i]);
                $target_path = 'uploads/' . $image_name;

                if (move_uploaded_file($_FILES['new_images']['tmp_name'][$i], $target_path)) {
                    $insert_img_sql = "INSERT INTO ad_images (ad_id, image_path) VALUES (?, ?)";
                    $stmt_img = $conn->prepare($insert_img_sql);
                    $stmt_img->bind_param("is", $ad_id, $target_path);
                    $stmt_img->execute();
                }
            }
        }
    }

    // Handle image deletions
    if (isset($_POST['delete_images'])) {
        foreach ($_POST['delete_images'] as $image_id) {
            $del_img_sql = "SELECT image_path FROM ad_images WHERE image_id = ?";
            $stmt = $conn->prepare($del_img_sql);
            $stmt->bind_param("i", $image_id);
            $stmt->execute();
            $img_path = $stmt->get_result()->fetch_assoc()['image_path'];

            if (file_exists($img_path)) {
                unlink($img_path);
            }

            $delete_sql = "DELETE FROM ad_images WHERE image_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $image_id);
            $stmt->execute();
        }
    }

    header("Location: edit_ad.php?ad_id=" . $ad_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'navbar.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ad</title>
    <style>
        /* Basic reset to remove default margin/padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Center the form title */
        h2 {
            text-align: center;
            
            margin-bottom: 20px;
        }

        /* Form container */
        .ad-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #E1E1E1;
            border-radius: 5px;
        }

        /* Flex layout for labels and inputs */
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        /* Label styles */
        .ad-form label {
            width: 150px; 
            text-align: right; 
            padding-right: 10px; 
            font-weight: bold;
        }

        /* Input, textarea, and select styles */
        .ad-form input[type="text"],
        .ad-form input[type="number"],
        .ad-form select,
        .ad-form textarea {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Submit button styling */
        .ad-form button[type="submit"] {
            display: block;
            margin: 20px auto; 
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        /* Style for image section */
      
        .image-wrapper {
            display: inline-block;
            position: relative;
            margin: 10px;
        }

        .image-wrapper img {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }

        .image-wrapper button {
            position: absolute;
            top: 0;
            right: 0;
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <h2>Edit Your Advertisement</h2>
    <form action="edit_ad.php?ad_id=<?= $ad_id ?>" method="POST" enctype="multipart/form-data" class="ad-form">
        <div class="form-group">
            <label for="title">Ad Title</label>
            <input type="text" name="title" value="<?= $ad['title'] ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Ad Description</label>
            <textarea name="description" required><?= $ad['description'] ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" value="<?= $ad['price'] ?>" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" value="<?= $ad['phone_number'] ?>" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" required>
                <?php while ($category = $categories_result->fetch_assoc()): ?>
                    <option value="<?= $category['category_id'] ?>" <?= $category['category_id'] == $ad['category_id'] ? 'selected' : '' ?>>
                        <?= $category['category_name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="district">District</label>
            <select name="district" required>
                <option value="">Select District</option>
                <option value="Galle" <?= $ad['district'] == 'Galle' ? 'selected' : '' ?>>Galle</option>
               
            </select>
        </div>

        <h3>Current Images</h3>
       
        <?php while ($img_row = $img_result->fetch_assoc()): ?>
            <div class="image-wrapper">
                <img src="<?= $img_row['image_path'] ?>" alt="Ad Image">
                <button type="button" onclick="removeImage(this)">X</button>
                <input type="checkbox" name="delete_images[]" value="<?= $img_row['image_id'] ?>" class="hidden">
         
        </div>
        <?php endwhile; ?>

        <h3>Add New Images</h3>
        <input type="file" name="new_images[]" multiple>

        <button type="submit" name="submit">Update Ad</button>
    </form>
</body>
</html>

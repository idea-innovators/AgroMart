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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ad</title>
    <style>
        .ad-form label { display: block; margin-top: 10px; }
        .image-wrapper { display: inline-block; position: relative; margin: 10px; }
        .image-wrapper img { width: 100px; height: 100px; object-fit: cover; }
        .image-wrapper button { position: absolute; top: 0; right: 0; background-color: red; color: white; border: none; cursor: pointer; }
        .hidden { display: none; }
    </style>
    <script>
        function removeImage(button) {
            // Remove image from view
            button.parentElement.classList.add('hidden');
            // Enable hidden checkbox for the image to mark it for deletion
            button.nextElementSibling.checked = true;
        }
    </script>
</head>
<body>
<?php include 'navbar.php'; ?>

<h2>Edit Ad</h2>

<form action="edit_ad.php?ad_id=<?= $ad_id ?>" method="POST" enctype="multipart/form-data" class="ad-form">
    <!-- Existing fields -->
    <label for="title">Ad Title</label>
    <input type="text" name="title" value="<?= $ad['title'] ?>" required>

    <label for="description">Ad Description</label>
    <textarea name="description" required><?= $ad['description'] ?></textarea>

    <label for="price">Price</label>
    <input type="number" name="price" value="<?= $ad['price'] ?>" required>

    <label for="phone_number">Phone Number</label>
    <input type="text" name="phone_number" value="<?= $ad['phone_number'] ?>" required>

    <label for="category">Category</label>
    <select name="category" required>
        <?php while ($category = $categories_result->fetch_assoc()): ?>
            <option value="<?= $category['category_id'] ?>" <?= $category['category_id'] == $ad['category_id'] ? 'selected' : '' ?>>
                <?= $category['category_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <!-- Add the district dropdown -->
    <label for="district">District</label>
    <select name="district" required>
        <option value="">Select District</option>
        <option value="Ampara" <?= $ad['district'] == 'Ampara' ? 'selected' : '' ?>>Ampara</option>
        <option value="Anuradhapura" <?= $ad['district'] == 'Anuradhapura' ? 'selected' : '' ?>>Anuradhapura</option>
        <option value="Badulla" <?= $ad['district'] == 'Badulla' ? 'selected' : '' ?>>Badulla</option>
        <option value="Batticaloa" <?= $ad['district'] == 'Batticaloa' ? 'selected' : '' ?>>Batticaloa</option>
        <option value="Colombo" <?= $ad['district'] == 'Colombo' ? 'selected' : '' ?>>Colombo</option>
        <option value="Galle" <?= $ad['district'] == 'Galle' ? 'selected' : '' ?>>Galle</option>
        <option value="Gampaha" <?= $ad['district'] == 'Gampaha' ? 'selected' : '' ?>>Gampaha</option>
        <option value="Hambantota" <?= $ad['district'] == 'Hambantota' ? 'selected' : '' ?>>Hambantota</option>
        <option value="Jaffna" <?= $ad['district'] == 'Jaffna' ? 'selected' : '' ?>>Jaffna</option>
        <option value="Kalutara" <?= $ad['district'] == 'Kalutara' ? 'selected' : '' ?>>Kalutara</option>
        <option value="Kandy" <?= $ad['district'] == 'Kandy' ? 'selected' : '' ?>>Kandy</option>
        <option value="Kegalle" <?= $ad['district'] == 'Kegalle' ? 'selected' : '' ?>>Kegalle</option>
        <option value="Kilinochchi" <?= $ad['district'] == 'Kilinochchi' ? 'selected' : '' ?>>Kilinochchi</option>
        <option value="Kurunegala" <?= $ad['district'] == 'Kurunegala' ? 'selected' : '' ?>>Kurunegala</option>
        <option value="Mannar" <?= $ad['district'] == 'Mannar' ? 'selected' : '' ?>>Mannar</option>
        <option value="Matale" <?= $ad['district'] == 'Matale' ? 'selected' : '' ?>>Matale</option>
        <option value="Matara" <?= $ad['district'] == 'Matara' ? 'selected' : '' ?>>Matara</option>
        <option value="Monaragala" <?= $ad['district'] == 'Monaragala' ? 'selected' : '' ?>>Monaragala</option>
        <option value="Mullaitivu" <?= $ad['district'] == 'Mullaitivu' ? 'selected' : '' ?>>Mullaitivu</option>
        <option value="Nuwara Eliya" <?= $ad['district'] == 'Nuwara Eliya' ? 'selected' : '' ?>>Nuwara Eliya</option>
        <option value="Polonnaruwa" <?= $ad['district'] == 'Polonnaruwa' ? 'selected' : '' ?>>Polonnaruwa</option>
        <option value="Puttalam" <?= $ad['district'] == 'Puttalam' ? 'selected' : '' ?>>Puttalam</option>
        <option value="Ratnapura" <?= $ad['district'] == 'Ratnapura' ? 'selected' : '' ?>>Ratnapura</option>
        <option value="Trincomalee" <?= $ad['district'] == 'Trincomalee' ? 'selected' : '' ?>>Trincomalee</option>
        <option value="Vavuniya" <?= $ad['district'] == 'Vavuniya' ? 'selected' : '' ?>>Vavuniya</option>
    </select>

    <!-- Existing fields for images and submit button -->
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

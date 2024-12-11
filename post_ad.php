<?php

session_start();
include 'config.php';



// Checks if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


if (isset($_POST['submit'])) {
    // Collect other ad details
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $phone_number = $_POST['phone_number'];
    $district = $_POST['district']; 
    // Assuming the user ID is stored in session after login
    $user_id = $_SESSION['user_id']; 
    $category_id = $_POST['category'];

    // Insert ad details into the 'ads' table
    $ad_sql = "INSERT INTO ads (title, description, price, phone_number, user_id, category_id, district) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($ad_sql);
    $stmt->bind_param("ssdsiss", $title, $description, $price, $phone_number, $user_id, $category_id, $district);
    $stmt->execute();
    $ad_id = $conn->insert_id; // Get the ID of the newly inserted ad


    //Upload multiple images
    for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
        if ($_FILES['images']['error'][$i] == 0) {
            $image_name = basename($_FILES['images']['name'][$i]);
            $target_path = 'uploads/' . $image_name;
            
            // Move the uploaded file to the server directory
            if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_path)) {
                // Insert each image path into the 'ad_images' table
                $img_sql = "INSERT INTO ad_images (ad_id, image_path) VALUES (?, ?)";
                $stmt_img = $conn->prepare($img_sql);
                $stmt_img->bind_param("is", $ad_id, $target_path);
                $stmt_img->execute();
            }
        }
    }

    echo "Ad and images uploaded successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include 'navbar.php'; ?>

<form action="post_ad.php" method="POST" enctype="multipart/form-data">
    <!-- Other ad input fields here -->
    <input type="text" name="title" placeholder="Ad Title" required><br>
    <textarea name="description" placeholder="Ad Description" required></textarea><br>
    <input type="number" name="price" placeholder="Price" required><br>
    <label>Category:</label>
    <select name="category" required>
        <?php
            $categories = $conn->query("SELECT * FROM categories");
            while ($category = $categories->fetch_assoc()) {
                echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
            }
            ?>
    </select><br>
    <label>District:</label>
    <select name="district" required>
        <option value="">Select District</option>
        <option value="Ampara">Ampara</option>
        <option value="Anuradhapura">Anuradhapura</option>
        <option value="Badulla">Badulla</option>
        <option value="Batticaloa">Batticaloa</option>
        <option value="Colombo">Colombo</option>
        <option value="Galle">Galle</option>
        <option value="Gampaha">Gampaha</option>
        <option value="Hambantota">Hambantota</option>
        <option value="Jaffna">Jaffna</option>
        <option value="Kalutara">Kalutara</option>
        <option value="Kandy">Kandy</option>
        <option value="Kegalle">Kegalle</option>
        <option value="Kilinochchi">Kilinochchi</option>
        <option value="Kurunegala">Kurunegala</option>
        <option value="Mannar">Mannar</option>
        <option value="Matale">Matale</option>
        <option value="Matara">Matara</option>
        <option value="Monaragala">Monaragala</option>
        <option value="Mullaitivu">Mullaitivu</option>
        <option value="Nuwara Eliya">Nuwara Eliya</option>
        <option value="Polonnaruwa">Polonnaruwa</option>
        <option value="Puttalam">Puttalam</option>
        <option value="Ratnapura">Ratnapura</option>
        <option value="Trincomalee">Trincomalee</option>
        <option value="Vavuniya">Vavuniya</option>
    </select><br>
    <input type="text" name="phone_number" placeholder="Phone number" required><br>

    <!-- Input for uploading multiple images -->
    <input type="file" name="images[]" multiple required><br>

    <button type="submit" name="submit">Submit Ad</button>
</form>

</html>
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
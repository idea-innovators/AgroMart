<?php
session_start();

include 'config.php'; 
include 'navbar.php'; 
// Fetch categories from the database
$query = "SELECT * FROM categories";
$result = $conn->query($query);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Categories</title>

</head>

<body>

    <h1>Our Categories</h1>
    <div class="category-container">
        <?php 
    while ($category = $result->fetch_assoc()): ?>


        <div class="category-card">
            <!-- Make image and category name clickable -->
            <a href="category_ads.php?category_id_qp=<?php echo $category['category_id']; ?>">
                <img src="uploads/<?php echo $category['category_image']; ?>"
                    alt="<?php echo $category['category_name']; ?>">
                <h3><?php echo $category['category_name']; ?></h3>
            </a>
        </div>
        <?php endwhile;
    ?>
    </div>

</body>

</html>

<?php
    $conn->close(); 
?>
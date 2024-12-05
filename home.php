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
    <style>
    /* styling for the categories */
    .category-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    .category-card {
        width: 200px;
        margin: 10px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        background-color: #f9f9f9;
    }

    .category-card img {
        width: 100%;
        height: auto;
        max-height: 150px;
        object-fit: cover;
        border-radius: 5px;
    }

    .category-card h3 {
        font-size: 1.2rem;
        margin: 10px 0;
    }

    .category-card a {
        text-decoration: none;
        color: black;
    }

    .category-card a:hover {
        color: #007bff;
    }
    </style>
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
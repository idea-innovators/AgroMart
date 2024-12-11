<?php
session_start();

include 'config.php'; 
include 'navbar.php'; 
// Fetch categories from the database
$query = "SELECT * FROM categories";
$result = $conn->query($query);

// Fetch random ads from the database (limit to 8 for 2 rows of 4)
$ads_query = "
    SELECT ads.*, 
        categories.category_name, 
        (SELECT image_path FROM ad_images WHERE ad_id = ads.ad_id LIMIT 1) AS image 
    FROM ads 
    JOIN categories ON ads.category_id = categories.category_id 
    ORDER BY RAND() LIMIT 8"; // Fetch 8 random ads
$ads_result = $conn->query($ads_query);
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

































</div>
<h2>Find What you want here</h2>
<div class="ads-container">
    <?php if ($ads_result->num_rows > 0): ?>
        <?php while ($ad = $ads_result->fetch_assoc()): ?>
            <div class="ad-card" onclick="window.location.href='view_ad.php?ad_id=<?= $ad['ad_id']; ?>'">
                <img src="<?= htmlspecialchars($ad['image']); ?>" alt="Ad Image">
                <h4><?= htmlspecialchars($ad['title']); ?></h4>
                <p class="ad-description"><?= htmlspecialchars(substr($ad['description'], 0, 200)) . '...'; ?></p>
                <p>Rs <?= htmlspecialchars($ad['price']); ?></p>
                <p><strong>District:</strong> <?= htmlspecialchars($ad['district']); ?></p>
                <p><strong>Posted on:</strong> <?= htmlspecialchars(date('Y-m-d', strtotime($ad['created_at']))); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No ads available at the moment.</p>
    <?php endif; ?>
</div>
<!-- view all ads button -->
<div style="text-align: center; margin: 20px 0;">
    <a href="all_ads.php" style="text-decoration: none;">
        <button style="padding: 10px 20px; font-size: 16px; border: none; border-radius: 5px; background-color: #007bff; color: white; cursor: pointer;">
            View All Ads
        </button>
    </a>
</div>
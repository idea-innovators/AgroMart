<?php
session_start();
include 'config.php'; // Database connection
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
        /* Basic styling for the categories */
        .category-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between; /* Use space-between for better distribution */
    margin-top: 20px; /* Optional: Add margin to the top for spacing */
}

.category-card {
    width: calc(25% - 20px); /* Set width for 4 columns */
    margin: 10px; /* Space around each card */
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    background-color: #f9f9f9;
    box-sizing: border-box; /* Ensure padding and borders are included in the width */
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
    color: #007bff; /* Optional: hover effect for the link */
}

/* Prevent horizontal scroll on mobile */
body, html {
    overflow-x: hidden;
}

        /* Basic styling for random ads */
        .ads-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between; /* Adjust to space-between for better column distribution */
            margin-top: 20px;
        }

        .ad-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            width: calc(25% - 20px); /* 4 items per row with margin space */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            text-align: center;
            cursor: pointer; /* Make card clickable */
            margin-bottom: 20px; /* Add margin at the bottom for spacing between rows */
        }

        .ad-card img {
            width: 100%;  /* Full width */
            height: 200px; /* Fixed height for square aspect ratio */
            object-fit: cover; /* Cover without stretching */
            border-radius: 8px; /* Optional: keep the rounded corners */
        }


        .ad-card h4 {
            font-size: 1.1rem;
            margin: 10px 0 5px 0;
        }

        .ad-card p {
            font-size: 0.9rem;
            color: #555;
            margin: 5px 0;
        }

        /* Description styling */

    </style>
</head>
<body>

<h1>Our Categories</h1>
<div class="category-container">
    <?php while ($category = $result->fetch_assoc()): ?>
        <div class="category-card">
            <a href="category_ads.php?category_id_qp=<?php echo $category['category_id']; ?>">
                <img src="uploads/<?php echo $category['category_image']; ?>" alt="<?php echo $category['category_name']; ?>">
                <h3><?php echo $category['category_name']; ?></h3>
            </a>
        </div>
    <?php endwhile; ?>
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


<?php
$conn->close(); 
?>

</body>
</html>

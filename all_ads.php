<?php
session_start();
include 'config.php';
include 'navbar.php'; // Including the navbar

// Set the number of ads per page
$ads_per_page = 16;

// Get the current page number from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $ads_per_page;

// Fetch the total number of ads
$total_ads_sql = "SELECT COUNT(*) AS total FROM ads";
$total_ads_result = $conn->query($total_ads_sql);
$total_ads = $total_ads_result->fetch_assoc()['total'];
$total_pages = ceil($total_ads / $ads_per_page);

// Fetch ads for the current page
$ads_sql = "
    SELECT ads.*, categories.category_name, 
        (SELECT image_path FROM ad_images WHERE ad_id = ads.ad_id LIMIT 1) AS image 
    FROM ads 
    JOIN categories ON ads.category_id = categories.category_id 
    ORDER BY ads.created_at DESC 
    LIMIT $ads_per_page OFFSET $offset";
$result = $conn->query($ads_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Ads</title>
    <style>
    /* Card layout for ads */
    .ads-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin: 20px;
    }

    .ad-card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        width: 23%;
        /* 4 items per row */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        text-align: center;
        cursor: pointer;
        /* Make card clickable */
    }

    .ad-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .ad-card img {
        width: 100%;
        /* Make the image take the full width of the card */
        height: 200px;
        /* Set a fixed height for square aspect ratio */
        object-fit: cover;
        /* Ensure the image covers the area without stretching */
        border-radius: 8px;
        /* Optional: keep the rounded corners */
    }


    .ad-card h4 {
        font-size: 16px;
        color: #333;
        margin: 10px 0 5px 0;
        font-weight: 600;
        text-transform: capitalize;
    }

    .ad-card p {
        font-size: 14px;
        color: #007b00;
        font-weight: 500;
        margin: 5px 0;
    }

    /* Prevent horizontal scroll on mobile */
    body,
    html {
        overflow-x: hidden;
    }
    </style>

</head>

<body>

    <div class="container">
        <h2 class="title1">All Ads</h2>
        <div class="ads-container">
            <?php if ($result->num_rows > 0): ?>
            <?php while ($ad = $result->fetch_assoc()): 
                // Limit the description to 200 characters
                $description = $ad['description'];
                if (strlen($description) > 200) {
                    $description = substr($description, 0, 200) . '...';
                }
            ?>
            <div class="ad-card" onclick="window.location.href='view_ad.php?ad_id=<?= $ad['ad_id']; ?>'">
                <img src="<?= htmlspecialchars($ad['image']); ?>" alt="Product Image">
                <h4><?= htmlspecialchars($ad['title']); ?></h4>
                <p><?= htmlspecialchars($description); ?></p>

                <div class="ad-details">
                    <p><span class="title2">Price:</span> Rs <?= htmlspecialchars($ad['price']); ?></p>
                    <p><span class="title2">District:</span> <?= htmlspecialchars($ad['district']); ?></p>
                    <p><span class="title2">Posted on:</span> <?= date('F j, Y', strtotime($ad['created_at'])); ?></p>
                </div>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <p>No ads found.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination Links Below the Ads Container -->
        <div class="pagination">
            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
            <a href="?page=<?= $page; ?>" class="<?= $page == $current_page ? 'active' : ''; ?>">
                <?= $page; ?>
            </a>
            <?php endfor; ?>
        </div>
    </div>

</body>

</html>
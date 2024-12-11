<?php
session_start();
include 'config.php';
include 'navbar.php';

// Fetch all ads from the database
$ads_sql = "
    SELECT ads.*, categories.category_name, 
        (SELECT image_path FROM ad_images WHERE ad_id = ads.ad_id LIMIT 1) AS image 
    FROM ads 
    JOIN categories ON ads.category_id = categories.category_id 
    ORDER BY ads.created_at DESC";
$result = $conn->query($ads_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Ads</title>

</head>
<body>

<div class="container">
    <h2>All Ads</h2>
    <div class="ads-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($ad = $result->fetch_assoc()): 
                // Limit the description to 200 characters
                $description = $ad['description'];
                if (strlen($description) > 200) {
                    $description = substr($description, 0, 200) . '...'; // Limit to 200 characters and add ellipsis
                }
            ?>
                <div class="ad-card" onclick="window.location.href='view_ad.php?ad_id=<?= $ad['ad_id']; ?>'">
                    <img src="<?= htmlspecialchars($ad['image']); ?>" alt="Product Image">
                    <h4><?= htmlspecialchars($ad['title']); ?></h4>
                    <p><?= htmlspecialchars($description); ?></p>
                    <p><strong>Price:</strong> Rs <?= htmlspecialchars($ad['price']); ?></p>
                    <p><strong>District:</strong> <?= htmlspecialchars($ad['district']); ?></p>
                    <p><strong>Posted on:</strong> <?= date('F j, Y', strtotime($ad['created_at'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No ads found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

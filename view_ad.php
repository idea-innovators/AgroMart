<?php
session_start();
include 'config.php';
include 'navbar.php';

// Get the ad ID from the URL
if (isset($_GET['ad_id'])) {
    $ad_id = $_GET['ad_id'];

    // Fetch ad details from the database
    $ad_sql = "
        SELECT ads.*, categories.category_name, DATE_FORMAT(ads.created_at, '%M %d, %Y %h:%i %p') AS formatted_date 
        FROM ads 
        JOIN categories ON ads.category_id = categories.category_id 
        WHERE ads.ad_id = ?";
    $stmt = $conn->prepare($ad_sql);
    $stmt->bind_param("i", $ad_id);
    $stmt->execute();
    $ad_result = $stmt->get_result();
    $ad = $ad_result->fetch_assoc();

    if ($ad) {
        $category_id = $ad['category_id']; // Get the category of the current ad
    } else {
        echo "Ad not found.";
        exit;
    }

    // Fetch all images for this ad from the 'ad_images' table
    $img_sql = "SELECT image_path FROM ad_images WHERE ad_id = ?";
    $stmt_img = $conn->prepare($img_sql);
    $stmt_img->bind_param("i", $ad_id);
    $stmt_img->execute();
    $img_result = $stmt_img->get_result();
    $images = [];
    while ($image = $img_result->fetch_assoc()) {
        $images[] = $image['image_path'];
    }

    // Fetch latest 4 ads from the same category for the "Similar Products" section
    $similar_ads_sql = "
        SELECT ads.*, categories.category_name, 
            (SELECT image_path FROM ad_images WHERE ad_id = ads.ad_id LIMIT 1) AS image 
        FROM ads 
        JOIN categories ON ads.category_id = categories.category_id 
        WHERE ads.category_id = ? AND ads.ad_id != ? 
        ORDER BY ads.created_at DESC 
        LIMIT 4";
    $similar_stmt = $conn->prepare($similar_ads_sql);
    $similar_stmt->bind_param("ii", $category_id, $ad_id);
    $similar_stmt->execute();
    $similar_ads_result = $similar_stmt->get_result();
}
else {
    echo "No ad selected.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ad['title']); ?></title>
    <style>
        .ad-details {
            text-align: center;
            margin: 20px;
        }

        .ad-images {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .ad-images img {
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer; 
        }

       
        #imageModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        #imageModal img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            border-radius: 8px;
        }

        #closeModal {
            position: absolute;
            top: 10px;
            right: 20px;
            color: white;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Card layout for similar items */
        .more-items-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
        }

        .more-item-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            width: 23%; /* 4 items per row */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            text-align: center;
            padding: 10px;
            cursor: pointer; 
        }

        .more-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .more-item-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .more-item-card h4 {
            font-size: 16px;
            color: #333;
            margin: 10px 0 5px 0;
            font-weight: 600;
            text-transform: capitalize;
        }

        .more-item-card p {
            font-size: 14px;
            color: #007b00;
            font-weight: 500;
        }

        /* Prevent horizontal scroll on mobile */
        body, html {
            overflow-x: hidden;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><?= htmlspecialchars($ad['title']); ?></h2>
    <div class="ad-details">
        <p><strong>Description:</strong> <?= htmlspecialchars($ad['description']); ?></p>
        <p><strong>Price:</strong> Rs <?= htmlspecialchars($ad['price']); ?></p>
        <p><strong>Contact Number:</strong> <?= htmlspecialchars($ad['phone_number']); ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($ad['category_name']); ?></p>
        <p><strong>Posted On:</strong> <?= htmlspecialchars($ad['formatted_date']); ?></p>
        <p><strong>District:</strong> <?= htmlspecialchars($ad['district']); ?></p>
    </div>

    <div class="ad-images">
        <?php foreach ($images as $image): ?>
            <img src="<?= htmlspecialchars($image); ?>" alt="Ad Image" onclick="openModal(this.src)">
        <?php endforeach; ?>
    </div>

    <div id="imageModal" onclick="closeModal()">
        <span id="closeModal">&times;</span>
        <img id="modalImage" src="" alt="Full Size Image">
    </div>

    <h3>Similar Products</h3>
    <div class="more-items-container">
        <?php while ($similar_ad = $similar_ads_result->fetch_assoc()): ?>
            <div class="more-item-card" onclick="window.location.href='view_ad.php?ad_id=<?= $similar_ad['ad_id']; ?>'">
                <img src="<?= htmlspecialchars($similar_ad['image']); ?>" alt="Product Image">
                <h4><?= htmlspecialchars($similar_ad['title']); ?></h4>
                <p>Rs <?= htmlspecialchars($similar_ad['price']); ?></p>
                <p><strong>District:</strong> <?= htmlspecialchars($similar_ad['district']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
    function openModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
</script>

</body>
</html>

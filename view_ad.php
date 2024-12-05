<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ad['title']); ?></title>
    <style>
    /* Styling for ad details */
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

    /* Modal overlay for full-size image */
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

    /* Card layout for more items */
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
        width: 18%;
        /* 5 items per row */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        text-align: center;
        padding: 10px;
        min-width: 200px;
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
    body,
    html {
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
            <p><strong>id:</strong> <?= htmlspecialchars($ad['ad_id']); ?></p>
            <p><strong>District:</strong> <?= htmlspecialchars($ad['district']); ?></p>
        </div>

        <!-- Display ad images -->
        <div class="ad-images">
            <?php foreach ($images as $image): ?>
            <img src="<?= htmlspecialchars($image); ?>" alt="Product Image"
                onclick="openImageModal('<?= htmlspecialchars($image); ?>')">
            <?php endforeach; ?>
        </div>

        <!-- Modal for full-size image -->
        <div id="imageModal">
            <span id="closeModal" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" src="" alt="Full-size Image">
        </div>

        <!-- Display more items from the same category -->
        <h3>More items from this category</h3>
        <div class="more-items-container">
            <?php
        // Fetch up to 5 random other ads from the same category
        $more_sql = "SELECT * FROM ads WHERE category_id = ? AND ad_id != ? ORDER BY RAND() LIMIT 5";
        $stmt_more = $conn->prepare($more_sql);
        $stmt_more->bind_param("ii", $category_id, $ad_id);
        $stmt_more->execute();
        $more_result = $stmt_more->get_result();

        if ($more_result->num_rows > 0) {
            while ($more_ad = $more_result->fetch_assoc()) {
                $more_ad_id = $more_ad['ad_id'];

                // Fetch the first image of the ad from the ad_images table
                $more_img_sql = "SELECT image_path FROM ad_images WHERE ad_id = ? LIMIT 1";
                $stmt_more_img = $conn->prepare($more_img_sql);
                $stmt_more_img->bind_param("i", $more_ad_id);
                $stmt_more_img->execute();
                $more_img_result = $stmt_more_img->get_result();
                $more_image = $more_img_result->fetch_assoc();
                ?>
            <div class="more-item-card">
                <a href="view_ad.php?ad_id=<?= $more_ad_id; ?>">
                    <?php if ($more_image): ?>
                    <img src="<?= htmlspecialchars($more_image['image_path']); ?>"
                        alt="<?= htmlspecialchars($more_ad['title']); ?>">
                    <?php else: ?>
                    <img src="placeholder.png" alt="No Image Available">
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($more_ad['title']); ?></h4>
                    <p>Price: $<?= htmlspecialchars($more_ad['price']); ?></p>
                </a>
            </div>
            <?php
            }
        } else {
            echo "<p>No other items in this category.</p>";
        }
        ?>
        </div>
    </div>

    <script>
    // Function to open the image modal
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').style.display = 'flex';
    }

    // Function to close the image modal
    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
    </script>

</body>

</html>
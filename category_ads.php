<?php
session_start();
include 'config.php';
include 'navbar.php'; 

// Check if category_id is provided
if (isset($_GET['category_id_qp'])) {
    $category_id = $_GET['category_id_qp'];

    // Fetch all ads for the selected category
    $ad_sql = "SELECT * FROM ads WHERE category_id = ?";
    $stmt = $conn->prepare($ad_sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Ads</title>
    <style>
    /* General container for the ads */
    .ad-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin: 20px auto;
        max-width: 1200px;
    }

    /* Individual ad card */
    .ad-card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        width: 18%;
        /* 5 columns layout */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        text-align: center;
        padding: 10px;
    }

    /* On hover effect for the ad card */
    .ad-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    /* Image styling inside the ad card  */
    .ad-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Title styling */
    .ad-card h4 {
        font-size: 18px;
        color: #333;
        margin: 10px 0 5px 0;
        font-weight: 600;
        text-transform: capitalize;
    }

    /* Price styling */
    .ad-card p {
        font-size: 16px;
        color: #007b00;
        font-weight: 500;
    }

    /* Responsive design for smaller screens */
    @media (max-width: 768px) {
        .ad-card {
            width: 45%;
        }
    }

    @media (max-width: 480px) {
        .ad-card {
            width: 90%;
        }
    }
    </style>

</head>

<body>

    <div class="container">
        <h2>Ads for Category</h2>
        <div class="ad-container">
            <?php
                if ($result->num_rows > 0) {
                    while ($ad = $result->fetch_assoc()) {
                        $ad_id = $ad['ad_id'];

                        // Fetch the first image of the ad from the ad_images table
                        $img_sql = "SELECT image_path FROM ad_images WHERE ad_id = ? LIMIT 1";
                        $stmt_img = $conn->prepare($img_sql);
                        $stmt_img->bind_param("i", $ad_id);
                        $stmt_img->execute();
                        $img_result = $stmt_img->get_result();
                        $image = $img_result->fetch_assoc(); // Fetch the first image
                        ?>
            <div class="ad-card">
                <a href="view_ad.php?ad_id=<?= $ad_id; ?>">
                    <!-- Display the first image if available, else show a placeholder -->
                    <?php if ($image): ?>
                    <img src="<?= htmlspecialchars($image['image_path']); ?>"
                        alt="<?= htmlspecialchars($ad['title']); ?>">
                    <?php else: ?>
                    <img src="placeholder.png" alt="No Image Available">
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($ad['title']); ?></h4>
                    <p>Price: $<?= htmlspecialchars($ad['price']); ?></p>
                </a>
            </div>
            <?php
                    }
                } else {
                    echo "<p>No ads found in this category.</p>";
                }
                ?>
        </div>
    </div>

</body>

</html>

<?php
} else {
    echo "No category selected.";
}
?>

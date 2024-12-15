<?php
session_start();
include 'config.php';
include 'navbar.php'; 

// Fetch categories from the database
$query = "SELECT * FROM categories";
$result = $conn->query($query);

// Fetch random ads from the database
$ads_query = "
    SELECT ads.*, 
        categories.category_name, 
        (SELECT image_path FROM ad_images WHERE ad_id = ads.ad_id LIMIT 1) AS image 
    FROM ads 
    JOIN categories ON ads.category_id = categories.category_id 
    ORDER BY RAND() LIMIT 8";
$ads_result = $conn->query($ads_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Categories</title>
    <style>
      
      body {
            font-family: Arial, sans-serif;
            margin: 0;    
        }

        .main-container {
            width: 75%; 
            margin: 0 auto; 
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        h1 {
            margin-top: 20px;
        }

        /* Banner Image */
        .banner-slides { 
          
            width: 100%;
            height: 600px;
            object-fit: cover;
        }

             /* Welcome Section */
             .welcome-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 40px;
            background-color: #f9f9f9; /* Light background for contrast */
        }

        .welcome-text {
            flex: 1;
            padding-left: 20px;
        }

        .welcome-text h2 {
            color: #ff8c00; /* Adjust to match your theme */
        }

        .welcome-text p {
            color: #666;
        }

        .about-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff8c00;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .welcome-image {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .welcome-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

    .category-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-top: 20px;
    }

    .category-card {
        width: calc(25% - 20px);
        margin: 10px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        background-color: #f9f9f9;
        box-sizing: border-box;
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

    body,
    html {
        overflow-x: hidden;
    }

    .ads-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-top: 20px;
    }

    .ad-card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        width: calc(25% - 20px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        text-align: center;
        cursor: pointer;
        margin-bottom: 20px;
    }

    .ad-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
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
    </style>
</head>

<body>

<div class="banner-image">
    <img class="banner-slides" src="images/cover.jpg" >
    <img class="banner-slides" src="images/lettuce-plant-on-field-vegetable-and-agriculture-sunset-and-light-free-photo.jpg" >
    <img class="banner-slides" src="images/iStock-531690340_c_valentinrussanov.webp">
</div>

<script>
var myIndex = 0;
carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName("banner-slides");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";  
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}    
    x[myIndex-1].style.display = "block";  
    setTimeout(carousel, 2000); 
}
</script>

<div class="main-container">

<section class="welcome-section">
    <div class="welcome-image">
        <img src="images/inner_home_05-1024x768.jpg" alt="Gardening Image">
    </div>
    <div class="welcome-text">
        <h2>Nature In Your House</h2>
        <p>Welcome to AgroMart Online Plants Store Sri Lanka. We offer the best plants and agricultural products with expert guidance in gardening.</p>
        <a href="#" class="about-btn">About Us</a>
    </div>
    
</section>

    <h1>Our Categories</h1>
    <div class="category-container">
        <?php while ($category = $result->fetch_assoc()): ?>
        <div class="category-card">
            <a href="category_ads.php?category_id_qp=<?php echo $category['category_id']; ?>">
                <img src="uploads/<?php echo $category['category_image']; ?>"
                    alt="<?php echo $category['category_name']; ?>">
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
            <button
                style="padding: 10px 20px; font-size: 16px; border: none; border-radius: 5px; background-color: #007bff; color: white; cursor: pointer;">
                View All Ads
            </button>
        </a>
    </div>
    </div>


    <?php
$conn->close(); 
?>

</body>

</html>
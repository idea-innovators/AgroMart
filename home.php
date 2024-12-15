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
    <title>AgroMart Home</title>
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
            background-color: #f9f9f9; 
        }

        .welcome-text {
            flex: 1;
            padding-left: 20px;
        }

        .welcome-text h2 {
            color: #ff8c00; 
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

        /* Category Section */
        .category-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px; 
            padding: 20px;
            background-color: #fff;
        }

        /* Individual category card */
        .category-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 200px; }

        .category-card a {
            display: block;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #F5F5A9;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .category-card:hover {
            transform: scale(1.05);
        }

        .category-card img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        /* Category name styling */
        .category-name {
            margin-top: 10px;
            font-size: 1rem;
            color: #333;
            font-weight: bold;
        }

        /* Ads Section */
        .ads-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .ad-card {
            width: calc(21% - 20px); 
            margin: 10px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .ad-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .ad-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .ad-card h4 {
            font-size: 1rem;
            color: #333;
            margin: 5px 0;
        }

        .ad-card p {
            font-size: 0.9rem;
            color: #555;
            margin: 5px 0;
        }

        /* View All Products Button */
        .view-all-btn {
            text-align: center;
            margin: 20px 0;
        }

        .view-all-btn button {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .view-all-btn button:hover {
            background-color: #0056b3;
        }

        /* Footer */
        footer {
            background-color: #006400;
            color: white;
            padding: 20px;
            text-align: center;
        }

        footer a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 1200px) {
            .main-container {
                width: 90%; 
            }

            .ads-container {
                justify-content: center; 
            }

            .ad-card {
                width: calc(45% - 20px); 
            }

            .category-card {
                width: 150px; 
            }
        }

        @media (max-width: 768px) {
            .welcome-section {
                flex-direction: column; 
                align-items: center; 
            }

            .welcome-image {
                order: 1; 
            }

            .welcome-text {
                order: 2; /
                text-align: center; 
            }

            .ad-card {
                width: calc(100% - 20px); /* Full width for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .banner-slides {
                height: 300px; /* Adjust banner height for small screens */
            }

            h1, h2 {
                font-size: 1.5rem; /* Smaller headings */
            }

            .about-btn, .view-all-btn button {
                width: 100%; 
                font-size: 1rem; 
            }

            .category-card {
                width: 120px; 
            }

            .category-name {
                font-size: 0.9rem;
            }
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

<h1>Categories</h1>
<div class="category-container">
    <?php while ($category = $result->fetch_assoc()): ?>
        <div class="category-card">
            <a href="category_ads.php?category_id_qp=<?php echo $category['category_id']; ?>">
                <img src="uploads/<?php echo $category['category_image']; ?>" alt="<?php echo $category['category_name']; ?>">
            </a>
            <h3 class="category-name"><?php echo $category['category_name']; ?></h3>
        </div>
    <?php endwhile; ?>
</div>

<h2>Find What You Want Here</h2>
<div class="ads-container">
    <?php if ($ads_result->num_rows > 0): ?>
        <?php while ($ad = $ads_result->fetch_assoc()): ?>
            <div class="ad-card" onclick="window.location.href='view_ad.php?ad_id=<?= $ad['ad_id']; ?>'">
                <img src="<?= htmlspecialchars($ad['image']); ?>" alt="Ad Image">
                <h4><?= htmlspecialchars($ad['title']); ?></h4>
                <p><?= htmlspecialchars(substr($ad['description'], 0, 100)) . '...'; ?></p>
                <p>Rs <?= htmlspecialchars($ad['price']); ?></p>
                <p><strong>District:</strong> <?= htmlspecialchars($ad['district']); ?></p>
                <p><strong>Posted on:</strong> <?= htmlspecialchars(date('Y-m-d', strtotime($ad['created_at']))); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No ads available at the moment.</p>
    <?php endif; ?>
</div>

<div class="view-all-btn">
    <a href="all_ads.php"><button>View All Ads</button></a>
</div>
    </div>

<footer>
    <p>Contact Us | 076 44 55 658</p>
    <p>&copy; 2024 AgroMart. All rights reserved.</p>
</footer>

<?php
$conn->close(); 
?>

</body>
</html>

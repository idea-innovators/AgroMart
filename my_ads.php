<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$ads_per_page = 16; // Number of ads per page

// Get the current page number from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $ads_per_page;

// Fetch total number of ads for the user
$total_ads_sql = "SELECT COUNT(*) AS total FROM ads WHERE user_id = ?";
$total_stmt = $conn->prepare($total_ads_sql);
$total_stmt->bind_param("i", $user_id);
$total_stmt->execute();
$total_ads_result = $total_stmt->get_result();
$total_ads = $total_ads_result->fetch_assoc()['total'];
$total_pages = ceil($total_ads / $ads_per_page);

// Fetch ads for the current page
$sql = "SELECT ads.*, GROUP_CONCAT(ad_images.image_path) AS images 
        FROM ads 
        LEFT JOIN ad_images ON ads.ad_id = ad_images.ad_id 
        WHERE ads.user_id = ? 
        GROUP BY ads.ad_id 
        ORDER BY ads.created_at DESC 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $ads_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'navbar.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ads</title>
    <style>
        /* Container width */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .title {
            text-align: center;
        }

        /* Card layout for ads */
        .ads-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 20px 0;
        }

        .ad-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            flex: 1 1 calc(25% - 20px); /* 4 cards in a row */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            text-align: center;
            cursor: pointer;
            min-width: 220px; /* Minimum width for cards */
        }

        .ad-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .ad-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
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
            color: #555;
            font-weight: 500;
            margin: 5px 0;
        }

        /* Button styles */
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
            margin: 5px; /* Add margin for spacing */
            display: inline-block; /* To align them in a row */
        }

        .btn:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: red;
            cursor: pointer;
        }

        .btn-danger:hover {
            background-color: darkred;
        }

        .no-ads {
            text-align: center;
            font-size: 1.5rem;
            margin-top: 50px;
        }

        /* Pagination styling */
        .pagination {
            text-align: center;
            margin: 20px 0;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .pagination a.active {
            background-color: #333;
            color: #fff;
            border-color: #333;
        }

        .pagination a:hover {
            background-color: #555;
            color: #fff;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .ad-card {
                flex: 1 1 calc(50% - 20px); /* 2 cards in a row */
            }
        }

        @media (max-width: 480px) {
            .ad-card {
                flex: 1 1 100%; /* 1 card in a row */
            }
            .container {
                width: 95%; /* Slightly wider on small screens */
            }
        }
    </style>

    <script>
        // Function to show a confirmation dialog before deleting an ad
        function confirmDelete(adId) {
            if (confirm("Are you sure you want to delete this ad?")) {
                window.location.href = "delete_ad.php?ad_id=" + adId;
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h2 class="title">My Ads</h2>

    <div class="ads-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): 
                $images = explode(',', $row['images']);
                $first_image = !empty($images[0]) ? $images[0] : 'default_image.jpg';
            ?>
                <div class="ad-card">
                    <img src="<?= htmlspecialchars($first_image) ?>" alt="Ad Image">
                    <h4><?= htmlspecialchars($row['title']) ?></h4>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p>Price: $<?= number_format($row['price'], 2) ?></p>
                    
                    <!-- Buttons positioned inside the ad card, below the content -->
                    <div class="ad-buttons" style="margin-top: 10px;">
                        <a href="view_ad.php?ad_id=<?= $row['ad_id'] ?>" class="btn">View Ad</a>
                        <a href="edit_ad.php?ad_id=<?= $row['ad_id'] ?>" class="btn">Edit Ad</a>
                        <button class="btn btn-danger" onclick="confirmDelete(<?= $row['ad_id'] ?>)">Delete Ad</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-ads">You haven't placed any ads yet!</p>
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

<?php
$stmt->close();
$total_stmt->close();
$conn->close();
?>

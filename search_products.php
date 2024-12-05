<?php
include 'config.php';

$q = isset($_GET['q']) ? $_GET['q'] : '';

if (!empty($q)) {
    // Search query to find matching products
    $stmt = $conn->prepare("SELECT * FROM ads WHERE title LIKE CONCAT(?, '%') LIMIT 10");
    $stmt->bind_param('s', $q);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($ad = $result->fetch_assoc()) {
            echo "<a href='view_ad.php?ad_id={$ad['ad_id']}'>{$ad['title']}</a>";
        }
    } else {
        echo "<a>No results found</a>";
    }
}
?>


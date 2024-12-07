



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View & Delete Ads</title>
</head>
<body>
    <h2>View & Delete Ads</h2>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Ad ID</th>
            <th>Description</th>
            <th>Price</th>
            <th>Posted By</th>
            <th>Action</th>
        </tr>
        <?php while ($ad = $result->fetch_assoc()) { ?>
        <tr>
        <td><?= $ad['title'] ?></td>
        <td><?= $ad['ad_id'] ?></td>
        <td><?= $ad['description'] ?></td>
        <td><?= $ad['price'] ?></td>
        <td><?= $ad['username'] ?></td>
        <td><a href="view_ads.php?delete_ad=<?= $ad['ad_id'] ?>" onclick="return confirm('Are you sure want to delete this Ad?')">Delete</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>

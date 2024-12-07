


!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
</head>
<body>
    <h2>Manage Users</h2>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Address</th>
            <th>Action</th>
        </tr>
        <?php while ($user = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $user['username'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['contact_number'] ?></td>
            <td><?= $user['address'] ?></td>
            <td><a href="view_users.php?delete_user=<?= $user['user_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
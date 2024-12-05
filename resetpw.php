<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>

<body>
    <h2>Reset Password</h2>
    <form action="resetpw.php?token=<?= $token ?>" method="POST">
        <input type="password" name="password" placeholder="New password" required><br>
        <button type="submit" name="reset">Reset Password</button>
    </form>
</body>

</html>
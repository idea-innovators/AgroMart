<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>

<body>
    <h2>Admin Login</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form action="admin_login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
    <p><a href="admin_forgotpw.php">Forgot Password?</a></p>
</body>

</html>
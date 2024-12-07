<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<nav>
    <div class="navbar">
        <div class="logo">
            <a href="admin_dashboard.php">MyAgroMart - Admin Dashboard</a>
        </div>
        <div class="nav-right">
            <?php if (isset($_SESSION['admin_username'])): ?>
                <!-- Display Welcome message and Logout button when admin is logged in -->
                <span>Welcome, <?= htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="admin_logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <!-- This block should no longer be needed because of the redirection -->
                <a href="admin_login.php" class="btn-login">Login</a>
                <a href="admin_register.php" class="btn-register">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="navbar.css">
    <style>
        /* Navbar CSS */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar .logo {
            font-size: 22px;
            font-weight: bold;
            color: #f2f2f2;
        }

        .navbar-right {
            float: right;
        }

        /* Search Bar */
        .search-container {
            float: left;
            padding: 14px 20px;
        }

        .search-container input[type="text"] {
            padding: 6px;
            margin-top: 8px;
            font-size: 17px;
            border: none;
            width: 200px;
        }

        .search-container button {
            padding: 6px;
            margin-top: 8px;
            margin-left: -4px;
            background: #ddd;
            font-size: 17px;
            border: none;
            cursor: pointer;
        }

        .search-container button:hover {
            background: #ccc;
        }

        /* Search Results */
        .search-results {
            position: relative;
            background-color: white;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 999;
            max-height: 300px;
            overflow-y: auto;
            width: 200px;
            display: none;
        }

        .search-results a {
            padding: 12px;
            display: inline;
            text-decoration: none;
            color: black;
        }

        .search-results a:hover {
            background-color: #ddd;
        }

        /* Responsive Navbar */
        @media screen and (max-width: 600px) {
            .navbar a, .navbar-right, .dropdown .dropbtn {
                float: none;
                display: block;
                text-align: left;
            }

            .navbar-right {
                text-align: right;
            }

            .search-container input[type="text"] {
                width: 100%;
            }
        }

    </style>
</head>
<body>

<script>
    function confirmLogout() {
        var confirmAction = confirm("Are you sure you want to log out?");
        if (confirmAction) {
            window.location.href = "logout.php";
        }
    }

    function searchProducts(query) {
        if (query.length == 0) {
            document.getElementById("search-results").style.display = "none";
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "search_products.php?q=" + query, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const results = document.getElementById("search-results");
                results.innerHTML = xhr.responseText;
                results.style.display = "block";
            }
        };
        xhr.send();
    }
</script>

<div class="navbar">
    <a href="home.php" class="logo">Plant Nursery</a>

    <!-- Search Bar -->
    <div class="search-container">
        <input type="text" placeholder="Search products..." onkeyup="searchProducts(this.value)">
        <div class="search-results" id="search-results"></div>
    </div>

    <div class="navbar-right">
    <a href="my_ads.php">My Ads</a>
    <a href="post_ad.php">Post Ad</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="#">Welcome, <?= $_SESSION['username']; ?></a>
            <a href="#" onclick="confirmLogout(); return false;">Log Out</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

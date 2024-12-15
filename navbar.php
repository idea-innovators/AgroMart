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
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            .navbar {
                background-color: #006400;
                color: white;
                display: flex;
                justify-content: space-between;
                padding: 10px 15%;
            }

            /* Logo */
            .logo {
                font-size: 24px;
                font-weight: bold;
                color: #f2f2f2;
                text-decoration: none;
                
            }


            /* Search Bar */
        
            .search-container input[type="text"] {
                padding: 8px;
                border-radius: 5px;
                border: 1px solid #ffffff;
                font-size: 17px;
                width: 350px;
            }

            
            /* Search Results */
            .search-results {
                position: absolute;
                background-color: #DBFFC7;
                box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
                max-height: 300px;
                width: 200px;
                display: none;
            }

            .search-results a {
                padding: 12px;
                display: block;
                text-decoration: none;
                color: black;
            }

            .search-results a:hover {
                background-color: #ddd;
            }


            /* Navebar Right */
            .navbar-right{
                padding-top:10px;
                padding-bottom: 10px;
            }

            .navbar-right a {
                color: white;
                text-decoration: none;
                margin-left: 20px;
                font-weight: bold;
                transition: background-color 0.5s;
            }

            .navbar-right a.place-ad {
                background-color: #FFD700;
                padding: 5px 5px;
                border-radius: 5px;
                color: black;
            }

            .navbar-right a:hover {
                background-color: #dbdbdb;
                padding: 8px 8px;
                border-radius: 10px;
                color: black;
            }

            /* Responsive Navbar */
            @media screen and (max-width: 600px) {
                .navbar {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .navbar-right {
                    margin-top: 10px;
                    width: 100%;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }

                .navbar-right a {   
                    margin: 5px 0;}
            }
            
            /* Responsive Navbar */
            @media screen and (max-width: 600px) {
                .logo{
                    padding: 5px 0 20px 0;
                }
                .navbar a, .navbar-right,{
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
            <a href="home.php" class="logo">AgroMart</a>

            <!-- Search Bar -->
            <div class="search-container">
                <input type="text" placeholder="Search what you want" onkeyup="searchProducts(this.value)">
                <div class="search-results" id="search-results"></div>
            </div>

            <div class="navbar-right">
            <a href="my_ads.php">My Ads</a>
            <a href="post_ad.php" class="place-ad">Post Ad</a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="#">Welcome,<?= $_SESSION['username']; ?></a>
                    <a href="#" onclick="confirmLogout(); return false;">Log Out</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>

    </body>
</html>
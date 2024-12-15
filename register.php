<?php
session_start();
ob_start(); 
include 'config.php';


// Check if the form is submitted
if (isset($_POST['register'])) {
    // Collect form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $confirm_email = $_POST['confirm_email'];
    $contact_number = $_POST['contact'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Validate email confirmation
    if ($email != $confirm_email) {
        echo "Emails do not match!";
        exit;
    }

    // Check if the user already exists
    $user_check_sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($user_check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already registered!";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (username, email, password, contact_number, address) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $password, $contact_number, $address);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            echo "Error registering user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'navbar.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }

        /* Centered Wrapper */
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }

        /* Register Container */
        .register-container {
            align-items: center;
            display: flex;
            gap: 30px;
            background-color: #e2e6eb;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 50%;
        }

        .plant-image {
            width: 300px;
            height: 300px;
            background-image: url('images/register image.png'); 
            background-size: cover;
            background-position: center;
        }

        .register-form {
            flex: 1;
            max-width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-left: auto;
        }

        .register-form h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .register-form form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .register-form input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .register-form button {
            background-color: #007a33;
            color: #fff;
            padding: 10px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .register-form button:hover {
            background-color: #005922;
        }

        .p1 {
            font-size: 14px;
            color: black;
            margin-top: 10px;
        }

        .link {
            text-decoration: none;
            color: #0917EE;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <div class="register-container">
            <div class="plant-image"></div>
            <div class="register-form">
                <h2>Register</h2>
                <form action="register.php" method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="email" name="confirm_email" placeholder="Confirm Email" required>
                    <input type="text" name="contact" placeholder="Contact Number" required>
                    <input type="text" name="address" placeholder="Address" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="register">Register</button>
                </form>
                <p class="p1">Already have an account? <a class="link" href="login.php">Login here</a>.</p>
            </div>
        </div>
    </div>
</body>
</html>

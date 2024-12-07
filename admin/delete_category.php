<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';  // Database connection
include 'admin_navbar.php'; 

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Check if the category has any ads associated with it
    $check_ads = $conn->query("SELECT * FROM ads WHERE category_id = $category_id");
    if ($check_ads->num_rows > 0) {
        // Block deletion if ads exist
        $_SESSION['error'] = "Category cannot be deleted as it has associated ads.";
        header("Location: admin_dashboard.php");
        exit();
    }

    // Delete the category if no ads are found
    $delete_category = $conn->query("DELETE FROM categories WHERE category_id = $category_id");

    if ($delete_category) {
        echo "Category deleted successfully.";
    } else {
        echo "Failed to delete the category.";
    }

    //header("Location: admin_dashboard.php");
    exit();
}
?>


<table>
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch categories from the database
                $categories = $conn->query("SELECT * FROM categories");
                while ($category = $categories->fetch_assoc()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($category['category_name']); ?></td>
                    <td>
                        <a href="delete_category.php?category_id=<?= $category['category_id']; ?>" 
                           class="delete-button" 
                           onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>




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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
</head>
<body>
    <h2>Add Category</h2>

    <form action="add_category.php" method="POST" enctype="multipart/form-data">
        <label for="category_name">Category Name:</label>
        <input type="text" name="category_name" required><br>

        <label for="category_image">Category Image:</label>
        <input type="file" name="category_image" accept="image/*" required><br>

        <input type="submit" name="add_category" value="Add Category">
    </form>
**create_العقار-property.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $address = trim($_POST['address']);
    $price = trim($_POST['price']);
    $area = trim($_POST['area']);
    $bedrooms = trim($_POST['bedrooms']);
    $bathrooms = trim($_POST['bathrooms']);
    $features = trim($_POST['features']);

    // Insert data into database
    $query = "INSERT INTO properties (title, description, address, price, area, bedrooms, bathrooms, features) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssssss", $title, $description, $address, $price, $area, $bedrooms, $bathrooms, $features);
    $stmt->execute();

    // Redirect to list page
    header('Location: list_العقار.php');
    exit;
}

// Include header
require_once '../includes/header.php';

// Include form
require_once '../includes/create_العقار-property-form.php';

// Include footer
require_once '../includes/footer.php';
?>


**create_العقار-property-form.php**

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New Property</h2>
    <form id="create-property-form" method="post" action="">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-slate-900">Title:</label>
            <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-slate-900">Address:</label>
            <input type="text" id="address" name="address" class="block w-full p-2 pl-10 text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-slate-900">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 pl-10 text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="area" class="block text-sm font-medium text-slate-900">Area:</label>
            <input type="number" id="area" name="area" class="block w-full p-2 pl-10 text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="bedrooms" class="block text-sm font-medium text-slate-900">Bedrooms:</label>
            <input type="number" id="bedrooms" name="bedrooms" class="block w-full p-2 pl-10 text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="bathrooms" class="block text-sm font-medium text-slate-900">Bathrooms:</label>
            <input type="number" id="bathrooms" name="bathrooms" class="block w-full p-2 pl-10 text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="features" class="block text-sm font-medium text-slate-900">Features:</label>
            <textarea id="features" name="features" class="block w-full p-2 pl-10 text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Property</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-property-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/العقار-property.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_العقار.php';
                    } else {
                        alert('Error creating property');
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Property</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <header class="bg-slate-900 py-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-lg font-bold text-white">Property Management</a>
            <ul class="flex items-center space-x-4">
                <li><a href="#" class="text-white hover:text-indigo-500">Home</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">List Properties</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">Create Property</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">Logout</a></li>
            </ul>
        </nav>
    </header>
    <?php echo $content; ?>
</body>
</html>


**footer.php**

<footer class="bg-slate-900 py-4">
    <div class="container mx-auto text-center text-white">
        &copy; 2023 Property Management. All rights reserved.
    </div>
</footer>


**list_العقار.php**

<?php
// Include header
require_once '../includes/header.php';

// Include list properties table
require_once '../includes/list_العقار-table.php';

// Include footer
require_once '../includes/footer.php';
?>


**list_العقار-table.php**

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">List Properties</h2>
    <table class="w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Address</th>
                <th class="px-4 py-2">Price</th>
                <th class="px-4 py-2">Area</th>
                <th class="px-4 py-2">Bedrooms</th>
                <th class="px-4 py-2">Bathrooms</th>
                <th class="px-4 py-2">Features</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query database to retrieve properties
            $query = "SELECT * FROM properties";
            $result = $mysqli->query($query);
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td class="px-4 py-2"><?php echo $row['title']; ?></td>
                    <td class="px-4 py-2"><?php echo $row['address']; ?></td>
                    <td class="px-4 py-2"><?php echo $row['price']; ?></td>
                    <td class="px-4 py-2"><?php echo $row['area']; ?></td>
                    <td class="px-4 py-2"><?php echo $row['bedrooms']; ?></td>
                    <td class="px-4 py-2"><?php echo
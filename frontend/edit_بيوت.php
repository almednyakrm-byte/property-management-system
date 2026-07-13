**edit_بيوت.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/بيوت.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit ' . $existingRecord['name'];
$modSlug = 'بيوت';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $pageTitle ?></h1>

    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input id="name" type="text" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
                <input id="address" type="text" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['address'] ?>">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
                <input id="phone" type="text" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['phone'] ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
                <input id="email" type="email" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['email'] ?>">
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/بيوت.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('address').value = data.address;
            document.getElementById('phone').value = data.phone;
            document.getElementById('email').value = data.email;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);

        // Send AJAX PUT request
        fetch('../backend/بيوت.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>'
            }
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page
                window.location.href = 'list_<?= $modSlug ?>.php';
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <header class="bg-slate-900 py-4">
        <nav class="container mx-auto p-4 flex justify-between items-center">
            <a href="#" class="text-white font-bold text-2xl">Logo</a>
            <ul class="flex items-center space-x-4">
                <li><a href="#" class="text-white hover:text-indigo-500">Home</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">About</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">Contact</a></li>
            </ul>
        </nav>
    </header>


**navigation.php**

<nav class="bg-slate-900 py-4">
    <div class="container mx-auto p-4 flex justify-between items-center">
        <ul class="flex items-center space-x-4">
            <li><a href="#" class="text-white hover:text-indigo-500">Dashboard</a></li>
            <li><a href="#" class="text-white hover:text-indigo-500">Settings</a></li>
            <li><a href="#" class="text-white hover:text-indigo-500">Logout</a></li>
        </ul>
    </div>
</nav>


**footer.php**

<footer class="bg-slate-900 py-4">
    <div class="container mx-auto p-4 text-center text-white">
        &copy; 2023 <?= $pageTitle ?>
    </div>
</footer>


Note: This code assumes that you have a `backend/بيوت.php` file that handles the GET and PUT requests for the `بيوت` records. You will need to implement this file separately.
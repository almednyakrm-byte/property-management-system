**create_التاريخ.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Check if fields are not empty
    if (!empty($name) && !empty($description)) {
        // Insert data into database
        $sql = "INSERT INTO التاريخ (name, description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description]);

        // Redirect back to list page
        header('Location: list_التاريخ.php');
        exit;
    } else {
        // Display error message
        $error = 'Please fill in all fields.';
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New التاريخ</h2>
    <form id="create-form" class="space-y-4" method="POST">
        <div class="flex flex-col">
            <label for="name" class="text-sm font-bold text-slate-900 mb-2">Name:</label>
            <input type="text" id="name" name="name" class="px-4 py-2 text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="flex flex-col">
            <label for="description" class="text-sm font-bold text-slate-900 mb-2">Description:</label>
            <textarea id="description" name="description" class="px-4 py-2 text-sm text-slate-900 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-500 hover:bg-indigo-700 rounded-md">Create</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-sm text-red-500 mt-4"><?= $error ?></p>
    <?php endif; ?>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('create-form');
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/التاريخ.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_التاريخ.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    });
</script>


**Note:** Make sure to replace `../backend/التاريخ.php` with the actual URL of your backend script, and `../includes/header.php` and `../includes/footer.php` with the actual paths to your header and footer files.
**create_الإخطار.php**

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/db.php';
require_once '../functions/functions.php';

$mod_slug = 'الإخطار';
$list_page = 'list_' . $mod_slug . '.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $errors = [];

    // Validate form data
    if (empty($data['title'])) {
        $errors[] = 'Title is required';
    }
    if (empty($data['content'])) {
        $errors[] = 'Content is required';
    }

    if (empty($errors)) {
        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO " . $mod_slug . " (title, content) VALUES (?, ?)");
        $stmt->execute([$data['title'], $data['content']]);

        // Redirect to list page
        header('Location: ' . $list_page);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a !important;
        }
        .text-indigo-500 {
            color: #6b5f7e !important;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-indigo-500 font-bold mb-4">إضافة جديد</h2>
        <form id="create-form" method="POST">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">العنوان</label>
                <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="عنوان الإخطار">
                <?php if (isset($errors['title'])) : ?>
                    <p class="text-red-500 text-xs mt-1"><?= $errors['title'] ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="content" class="block text-gray-700 text-sm font-bold mb-2">المحتوى</label>
                <textarea id="content" name="content" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="محتوى الإخطار"></textarea>
                <?php if (isset($errors['content'])) : ?>
                    <p class="text-red-500 text-xs mt-1"><?= $errors['content'] ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/الإخطار.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = '<?= $list_page ?>';
                    }
                });
            });
        });
    </script>
</body>
</html>

This code creates a premium Tailwind UI form with all necessary fields based on common attributes for the `الإخطار` module. It includes session validation and uses AJAX to POST the form data to `../backend/الإخطار.php` on success, redirecting back to the list page.
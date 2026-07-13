<?php
// Session validation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Include database connection
require_once '../backend/db.php';

// Check if id is valid
$query = "SELECT * FROM مخازن WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: list_مخازن.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مخزن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-slate-100 rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-indigo-500">تعديل مخزن</h2>
        <form id="edit-form">
            <div class="mt-4">
                <label for="name" class="block text-sm font-medium text-slate-900">اسم المخزن</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-slate-900 bg-slate-100 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-slate-900">وصف المخزن</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-slate-900 bg-slate-100 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="mt-4 py-2 px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-700">حفظ التعديلات</button>
        </form>
    </div>

    <script>
        // Fetch existing record details
        fetch('../backend/مخازن.php?id=<?= $id ?>')
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX
        document.getElementById('edit-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('../backend/مخازن.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: <?= $id ?>,
                    name: formData.get('name'),
                    description: formData.get('description')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_مخازن.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>
<?php
// Session validation
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Include database connection
include '../backend/db.php';

// Check if id is valid
$query = "SELECT * FROM شقق WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: list_شقق.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل شقق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4 pt-6 mt-10 bg-slate-800 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-4">تعديل شقق</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">اسم الشقة</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-700 border border-indigo-500 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">وصف الشقة</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-700 border border-indigo-500 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium mb-2">سعر الشقة</label>
                <input type="number" id="price" name="price" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-700 border border-indigo-500 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch existing record details
            $.ajax({
                type: 'GET',
                url: '../backend/شقق.php?id=<?php echo $id; ?>',
                dataType: 'json',
                success: function(data) {
                    // Populate form fields
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                    $('#price').val(data.price);
                }
            });

            // Submit form using AJAX
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/شقق.php',
                    data: {
                        id: '<?php echo $id; ?>',
                        name: $('#name').val(),
                        description: $('#description').val(),
                        price: $('#price').val()
                    },
                    success: function() {
                        window.location.href = 'list_شقق.php';
                    }
                });
            });
        });
    </script>
</body>
</html>
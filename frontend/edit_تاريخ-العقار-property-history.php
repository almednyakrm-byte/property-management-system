**edit_تاريخ-العقار-property-history.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get property history ID from URL
$id = $_GET['id'];

// Validate ID
if (empty($id)) {
    header('Location: list_تاريخ-العقار-property-history.php');
    exit;
}

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/تاريخ-العقار-property-history.php?id=' . $id), true);

// Validate existing record
if (empty($existingRecord)) {
    header('Location: list_تاريخ-العقار-property-history.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property History</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-lg font-bold text-slate-900 mb-4">Edit Property History</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['title'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-md hover:bg-indigo-700 focus:ring-indigo-500 focus:border-indigo-500">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/تاريخ-العقار-property-history.php',
                    data: formData,
                    success: function(data) {
                        if (data.success) {
                            window.location.href = 'list_تاريخ-العقار-property-history.php';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/تاريخ-العقار-property-history.php**

<?php
// Validate ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$existingRecord = array(
    'id' => $id,
    'title' => 'Existing Record Title',
    'description' => 'Existing Record Description'
);

// Return existing record details as JSON
header('Content-Type: application/json');
echo json_encode($existingRecord);
exit;
?>


Note: This code assumes that you have a database query to fetch the existing record details. Replace the `$existingRecord` array in the `../backend/تاريخ-العقار-property-history.php` file with your actual database query.
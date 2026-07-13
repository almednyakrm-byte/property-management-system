**edit_العقار.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch the existing record details via GET
$url = '../backend/العقار.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if the record exists
if (empty($data)) {
    echo 'Record not found!';
    exit;
}

// Set the page title and content
$page_title = 'Edit العقار';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-2xl font-bold text-slate-900 mb-4"><?= $page_title ?></h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save Changes</button>
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
                    url: '../backend/العقار.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record!');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/العقار.php**

<?php
// Check if the ID is set
if (!isset($_GET['id'])) {
    echo 'Invalid request!';
    exit;
}

// Get the ID
$id = $_GET['id'];

// Check if the record exists
$record = get_record($id);
if (empty($record)) {
    echo 'Record not found!';
    exit;
}

// Update the record via PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $data);
    update_record($id, $data);
    echo 'success';
    exit;
}

// Fetch the existing record details via GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $record = get_record($id);
    echo json_encode($record);
    exit;
}

// Helper functions
function get_record($id) {
    // Implement your database query to fetch the record
    // For demonstration purposes, assume it's an array
    return [
        'id' => $id,
        'name' => 'Record Name',
        'description' => 'Record Description'
    ];
}

function update_record($id, $data) {
    // Implement your database query to update the record
    // For demonstration purposes, assume it's a success
    echo 'Record updated successfully!';
}
?>
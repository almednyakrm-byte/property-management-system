**edit_إخطار-notifications.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get notification ID from URL
$id = $_GET['id'];

// Fetch existing notification details via AJAX
$existingNotification = json_decode(file_get_contents('../backend/إخطار-notifications.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit إخطار (Notifications)</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">Edit إخطار (Notifications)</h1>
        <form id="edit-notification-form" class="space-y-4">
            <div class="flex flex-col">
                <label for="title" class="text-slate-900">Title:</label>
                <input type="text" id="title" name="title" class="border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingNotification['title'] ?>">
            </div>
            <div class="flex flex-col">
                <label for="description" class="text-slate-900">Description:</label>
                <textarea id="description" name="description" class="border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $existingNotification['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Update Notification</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-notification-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/إخطار-notifications.php',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating notification');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/إخطار-notifications.php**

<?php
// Check if notification ID is provided
if (!isset($_GET['id'])) {
    header('Location: edit_إخطار-notifications.php');
    exit;
}

// Fetch existing notification details from database
$notification = get_notification_by_id($_GET['id']);

// Return notification details as JSON
echo json_encode($notification);
exit;

function get_notification_by_id($id) {
    // Replace with your actual database query
    $notification = array(
        'id' => $id,
        'title' => 'Notification Title',
        'description' => 'Notification Description'
    );
    return $notification;
}
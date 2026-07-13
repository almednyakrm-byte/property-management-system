**create_إخطار-notifications.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // Check if all fields are filled
    if (!empty($title) && !empty($description) && !empty($status)) {
        // Insert new record into database
        $query = "INSERT INTO notifications (title, description, status) VALUES ('$title', '$description', '$status')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_إخطار.php');
            exit;
        } else {
            echo 'Error inserting record';
        }
    } else {
        echo 'Please fill all fields';
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">إضافة إخطار جديد</h2>
    <form id="create-notification-form" method="post">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-slate-900">عنوان الإخطار</label>
            <input type="text" id="title" name="title" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">وصف الإخطار</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-slate-900">حالة الإخطار</label>
            <select id="status" name="status" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="">اختر حالة</option>
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
            </select>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة إخطار</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-notification-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/إخطار-notifications.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_إخطار.php';
                    } else {
                        alert('Error creating notification');
                    }
                }
            });
        });
    });
</script>


**backend/إخطار-notifications.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['submit'])) {
    // Validate form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // Insert new record into database
    $query = "INSERT INTO notifications (title, description, status) VALUES ('$title', '$description', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error inserting record';
    }
}
**edit_التاريخ.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/التاريخ.php?id=' . $id), true);

// Check if record exists
if (empty($record)) {
    echo 'Record not found';
    exit;
}

// Set page title
$page_title = 'Edit ' . $record['title'];

// Include header
include 'header.php';

?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold leading-tight text-slate-900 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-slate-700">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['title'] ?>">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $record['description'] ?></textarea>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
    </form>
</main>

<!-- Script -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/التاريخ.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('title').value = data.title;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Form submission handler
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData(this);

        // Send PUT request to backend
        fetch('../backend/التاريخ.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page on success
                window.location.href = 'list_التاريخ.php';
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/التاريخ.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Get id
$id = $_GET['id'];

// Connect to database
$conn = new PDO('dsn', 'username', 'password');

// Query database
$stmt = $conn->prepare('SELECT * FROM التاريخ WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();

// Fetch record
$record = $stmt->fetch();

// Return record as JSON
echo json_encode($record);

// Close connection
$conn = null;
?>


**backend/edit_التاريخ.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Get id
$id = $_GET['id'];

// Connect to database
$conn = new PDO('dsn', 'username', 'password');

// Check if record exists
$stmt = $conn->prepare('SELECT * FROM التاريخ WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$record = $stmt->fetch();

// Check if record exists
if (empty($record)) {
    echo 'Record not found';
    exit;
}

// Update record
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Update record in database
    $stmt = $conn->prepare('UPDATE التاريخ SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return success message
    echo 'Record updated successfully';
}

// Close connection
$conn = null;
?>
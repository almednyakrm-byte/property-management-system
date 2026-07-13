**create_تاريخ-العقار-property-history.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    $property_history = array(
        'property_id' => $_POST['property_id'],
        'date' => $_POST['date'],
        'description' => $_POST['description'],
        'created_by' => $_SESSION['user_id']
    );

    // Insert data into database
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
    $stmt = $db->prepare('INSERT INTO property_history SET property_id = :property_id, date = :date, description = :description, created_by = :created_by');
    $stmt->execute($property_history);
    $db = null;

    // Redirect back to list page
    header('Location: list_تاريخ-العقار-property-history.php');
    exit;
}

// Get property list
$db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
$stmt = $db->query('SELECT * FROM properties');
$properties = $stmt->fetchAll();
$db = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Property History</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Create Property History</h1>
        <form id="create-property-history-form" class="bg-white p-4 rounded shadow-md" method="POST">
            <div class="mb-4">
                <label for="property_id" class="block text-gray-700 text-sm font-bold mb-2">Property:</label>
                <select id="property_id" name="property_id" class="block w-full p-2 border border-gray-300 rounded-md">
                    <?php foreach ($properties as $property) { ?>
                        <option value="<?php echo $property['id']; ?>"><?php echo $property['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
                <input id="date" name="date" type="date" class="block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-gray-300 rounded-md"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-property-history-form').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/تاريخ-العقار-property-history.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = 'list_تاريخ-العقار-property-history.php';
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Replace `your_database`, `your_username`, and `your_password` with your actual database credentials. Also, make sure to update the `list_تاريخ-العقار-property-history.php` URL to point to the correct page.
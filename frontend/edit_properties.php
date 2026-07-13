**edit_properties.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get property ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$properties = json_decode(file_get_contents('../backend/properties.php?id=' . $id), true);

// Check if record exists
if (empty($properties)) {
    echo 'Error: Property not found.';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Property';
$mod_slug = 'properties';

// Include header and navigation
include 'header.php';
?>

<main class="max-w-7xl mx-auto px-4 py-4">
    <h1 class="text-3xl font-bold mb-4"><?= $page_title ?></h1>
    <form id="edit-property-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name:</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Property Name" value="<?= $properties['name'] ?>">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description:</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" placeholder="Property Description"><?= $properties['description'] ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Price:</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price" type="number" placeholder="Property Price" value="<?= $properties['price'] ?>">
        </div>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Update Property</button>
    </form>
</main>

<script>
    // Fetch existing record details via GET
    fetch('../backend/properties.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
            document.getElementById('price').value = data.price;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-property-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/properties.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_' + '<?= $mod_slug ?>' + '.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/properties.php**

<?php
// Check if property ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Property ID not set.'));
    exit;
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    echo json_encode(array('error' => 'Connection failed.'));
    exit;
}

// Get property details
$id = $_GET['id'];
$query = "SELECT * FROM properties WHERE id = '$id'";
$result = mysqli_query($conn, $query);

// Check if record exists
if (mysqli_num_rows($result) > 0) {
    $property = mysqli_fetch_assoc($result);
    echo json_encode($property);
} else {
    echo json_encode(array('error' => 'Property not found.'));
}

// Close database connection
mysqli_close($conn);
?>


**backend/update_property.php**

<?php
// Check if property ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Property ID not set.'));
    exit;
}

// Check if form data is set
if (!isset($_POST['name']) || !isset($_POST['description']) || !isset($_POST['price'])) {
    echo json_encode(array('error' => 'Form data not set.'));
    exit;
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    echo json_encode(array('error' => 'Connection failed.'));
    exit;
}

// Get property details
$id = $_GET['id'];
$query = "UPDATE properties SET name = '".$_POST['name']."', description = '".$_POST['description']."', price = '".$_POST['price']."' WHERE id = '$id'";
$result = mysqli_query($conn, $query);

// Check if update was successful
if ($result) {
    echo json_encode(array('success' => 'Property updated successfully.'));
} else {
    echo json_encode(array('error' => 'Update failed.'));
}

// Close database connection
mysqli_close($conn);
?>
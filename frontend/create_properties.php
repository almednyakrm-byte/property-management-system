**create_properties.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Create Properties</h2>

        <form id="create-properties-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Title</label>
                <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                <input type="text" id="address" name="address" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>

            <div class="mb-4">
                <label for="city" class="block text-sm font-bold text-gray-700 mb-2">City</label>
                <input type="text" id="city" name="city" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>

            <div class="mb-4">
                <label for="state" class="block text-sm font-bold text-gray-700 mb-2">State</label>
                <input type="text" id="state" name="state" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>

            <div class="mb-4">
                <label for="zip" class="block text-sm font-bold text-gray-700 mb-2">Zip</label>
                <input type="text" id="zip" name="zip" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>

            <div class="mb-4">
                <label for="price" class="block text-sm font-bold text-gray-700 mb-2">Price</label>
                <input type="number" id="price" name="price" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>

            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">Create Properties</button>
        </form>
    </div>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**create_properties.js**
javascript
// Get the form element
const form = document.getElementById('create-properties-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    // Prevent default form submission
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/properties.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        // Redirect to list page on success
        window.location.href = 'list_properties.php';
    })
    .catch((error) => {
        console.error(error);
    });
});


**Note:** Make sure to replace `../backend/properties.php` with the actual URL of your backend script that handles the form submission. Also, ensure that the `list_properties.php` page is created and configured correctly to handle the redirect.
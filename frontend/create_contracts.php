**create_contracts.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $contract_name = trim($_POST['contract_name']);
    $contract_description = trim($_POST['contract_description']);
    $contract_start_date = trim($_POST['contract_start_date']);
    $contract_end_date = trim($_POST['contract_end_date']);

    if (!empty($contract_name) && !empty($contract_description) && !empty($contract_start_date) && !empty($contract_end_date)) {
        // Insert data into database
        $query = "INSERT INTO contracts (contract_name, contract_description, contract_start_date, contract_end_date) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$contract_name, $contract_description, $contract_start_date, $contract_end_date]);

        // Redirect to list page
        header('Location: list_contracts.php');
        exit;
    } else {
        $error = 'Please fill in all fields.';
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create Contracts Page -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8 xl:p-10 2xl:p-12">
    <div class="flex justify-center">
        <div class="w-full max-w-lg p-4 pt-6 md:p-6 lg:p-8 xl:p-10 2xl:p-12 bg-white rounded-lg shadow-md">
            <h2 class="text-lg font-bold text-emerald-600 mb-4">Create Contract</h2>
            <form id="create-contract-form" method="post">
                <div class="mb-4">
                    <label for="contract_name" class="block text-sm font-bold text-gray-700 mb-2">Contract Name:</label>
                    <input type="text" id="contract_name" name="contract_name" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" required>
                </div>
                <div class="mb-4">
                    <label for="contract_description" class="block text-sm font-bold text-gray-700 mb-2">Contract Description:</label>
                    <textarea id="contract_description" name="contract_description" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="contract_start_date" class="block text-sm font-bold text-gray-700 mb-2">Contract Start Date:</label>
                    <input type="date" id="contract_start_date" name="contract_start_date" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" required>
                </div>
                <div class="mb-4">
                    <label for="contract_end_date" class="block text-sm font-bold text-gray-700 mb-2">Contract End Date:</label>
                    <input type="date" id="contract_end_date" name="contract_end_date" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" required>
                </div>
                <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Create Contract</button>
                <?php if (isset($error)) : ?>
                    <p class="text-red-500 mt-2"><?= $error ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#create-contract-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/contracts.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_contracts.php';
                    } else {
                        alert('Error creating contract.');
                    }
                }
            });
        });
    });
</script>


**contracts.php**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been submitted
if (isset($_POST['contract_name']) && isset($_POST['contract_description']) && isset($_POST['contract_start_date']) && isset($_POST['contract_end_date'])) {
    // Insert data into database
    $query = "INSERT INTO contracts (contract_name, contract_description, contract_start_date, contract_end_date) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$_POST['contract_name'], $_POST['contract_description'], $_POST['contract_start_date'], $_POST['contract_end_date']]);

    // Return success message
    echo 'success';
}
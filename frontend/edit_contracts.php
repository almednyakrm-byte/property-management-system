**edit_contracts.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get contract ID from URL
$id = $_GET['id'];

// Fetch existing contract details via AJAX
$js = "
<script>
    $(document).ready(function() {
        $.get('../backend/contracts.php?id=" . $id . "')
            .done(function(data) {
                $('#contract_name').val(data.contract_name);
                $('#contract_type').val(data.contract_type);
                $('#contract_start_date').val(data.contract_start_date);
                $('#contract_end_date').val(data.contract_end_date);
                $('#contract_description').val(data.contract_description);
            })
            .fail(function() {
                console.error('Failed to fetch contract details');
            });
    });
</script>
";

// Include header and JavaScript
include 'header.php';
echo $js;

// Form
?>
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit Contract</h2>
    <form id="edit-contract-form" class="space-y-4">
        <div>
            <label for="contract_name" class="block text-sm font-medium text-gray-700">Contract Name</label>
            <input type="text" id="contract_name" name="contract_name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
        </div>
        <div>
            <label for="contract_type" class="block text-sm font-medium text-gray-700">Contract Type</label>
            <input type="text" id="contract_type" name="contract_type" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
        </div>
        <div>
            <label for="contract_start_date" class="block text-sm font-medium text-gray-700">Contract Start Date</label>
            <input type="date" id="contract_start_date" name="contract_start_date" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
        </div>
        <div>
            <label for="contract_end_date" class="block text-sm font-medium text-gray-700">Contract End Date</label>
            <input type="date" id="contract_end_date" name="contract_end_date" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
        </div>
        <div>
            <label for="contract_description" class="block text-sm font-medium text-gray-700">Contract Description</label>
            <textarea id="contract_description" name="contract_description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"></textarea>
        </div>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Update Contract</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#edit-contract-form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../backend/contracts.php',
                data: $(this).serialize() + '&id=' + <?php echo $id; ?>,
                success: function(data) {
                    if (data === 'success') {
                        window.location.href = 'list_contracts.php';
                    } else {
                        console.error('Failed to update contract');
                    }
                },
                error: function() {
                    console.error('Failed to update contract');
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contract</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php echo $js; ?>


**footer.php**

</body>
</html>


**contracts.php (backend)**

<?php
// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

// Get contract ID from URL
$id = $_GET['id'];

// Fetch contract details
$stmt = $conn->prepare('SELECT * FROM contracts WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$contract = $stmt->fetch();

// Update contract
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $data);
    $stmt = $conn->prepare('UPDATE contracts SET contract_name = :contract_name, contract_type = :contract_type, contract_start_date = :contract_start_date, contract_end_date = :contract_end_date, contract_description = :contract_description WHERE id = :id');
    $stmt->bindParam(':contract_name', $data['contract_name']);
    $stmt->bindParam(':contract_type', $data['contract_type']);
    $stmt->bindParam(':contract_start_date', $data['contract_start_date']);
    $stmt->bindParam(':contract_end_date', $data['contract_end_date']);
    $stmt->bindParam(':contract_description', $data['contract_description']);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo 'success';
} else {
    echo json_encode($contract);
}

// Close database connection
$conn = null;
?>
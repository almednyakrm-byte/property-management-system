**edit_transactions.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get transaction ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$transaction = json_decode(file_get_contents('../backend/transactions.php?id=' . $id), true);

// Check if transaction exists
if (empty($transaction)) {
    echo 'Transaction not found.';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit Transaction</h2>
        <form id="edit-transaction-form">
            <div class="mb-4">
                <label for="transaction_date" class="block text-sm font-medium text-gray-700">Transaction Date:</label>
                <input type="date" id="transaction_date" name="transaction_date" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" value="<?= $transaction['transaction_date']; ?>">
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount:</label>
                <input type="number" id="amount" name="amount" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" value="<?= $transaction['amount']; ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600"><?= $transaction['description']; ?></textarea>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">Update Transaction</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-transaction-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/transactions.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_transactions.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**transactions.php (backend)**

<?php
// Check if transaction ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get transaction ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get transaction details
$stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch transaction details
$transaction = $result->fetch_assoc();

// Close connection
$conn->close();

// Output transaction details as JSON
echo json_encode($transaction);
?>


**update_transaction.php (backend)**

<?php
// Check if transaction ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get transaction ID
$id = $_GET['id'];

// Get updated transaction details
$transaction_date = $_POST['transaction_date'];
$amount = $_POST['amount'];
$description = $_POST['description'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update transaction
$stmt = $conn->prepare("UPDATE transactions SET transaction_date = ?, amount = ?, description = ? WHERE id = ?");
$stmt->bind_param("sssi", $transaction_date, $amount, $description, $id);
$stmt->execute();

// Close connection
$conn->close();

// Output success message
http_response_code(200);
echo 'Transaction updated successfully.';
?>
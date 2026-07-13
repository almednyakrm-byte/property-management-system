**edit_payments.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get payment ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$payment = json_decode(file_get_contents('../backend/payments.php?id=' . $id), true);

// Check if payment exists
if (empty($payment)) {
    echo 'Payment not found.';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Edit Payment</h1>
        <form id="edit-payment-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount:</label>
                <input type="number" id="amount" name="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $payment['amount'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $payment['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
                <input type="date" id="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $payment['date'] ?>">
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Update Payment</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-payment-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/payments.php',
                    data: $(this).serialize() + '&id=' + <?= $id ?>,
                    success: function(response) {
                        window.location.href = 'list_payments.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/payments.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

// Get payment ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
$payment = get_payment($id);

// Return payment data as JSON
echo json_encode($payment);

function get_payment($id) {
    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

    // Prepare query
    $stmt = $db->prepare('SELECT * FROM payments WHERE id = :id');

    // Bind parameters
    $stmt->bindParam(':id', $id);

    // Execute query
    $stmt->execute();

    // Fetch payment data
    $payment = $stmt->fetch();

    // Close database connection
    $db = null;

    return $payment;
}
?>
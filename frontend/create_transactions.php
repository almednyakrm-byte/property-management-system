**create_transactions.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include Tailwind CSS
?>

<style>
    .bg-emerald-600 {
        background-color: #03C896;
    }
    .text-teal-500 {
        color: #009688;
    }
</style>

<div class="container mx-auto p-4 mt-12">
    <div class="bg-emerald-600 p-4 rounded-lg">
        <h2 class="text-lg font-bold text-white mb-4">Create New Transaction</h2>
        <form id="create-transaction-form">
            <div class="mb-4">
                <label for="transaction_date" class="block text-sm font-medium text-white">Transaction Date:</label>
                <input type="date" id="transaction_date" name="transaction_date" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="transaction_type" class="block text-sm font-medium text-white">Transaction Type:</label>
                <select id="transaction_type" name="transaction_type" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Transaction Type</option>
                    <option value="Income">Income</option>
                    <option value="Expense">Expense</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-white">Amount:</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-white">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Create Transaction</button>
        </form>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-transaction-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/transactions.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_transactions.php';
                    } else {
                        alert('Error creating transaction');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**transactions.php** (backend file)

<?php
// Include database connection
include 'db_connection.php';

// Check if form data is submitted
if (isset($_POST['transaction_date']) && isset($_POST['transaction_type']) && isset($_POST['amount']) && isset($_POST['description'])) {
    // Prepare SQL query
    $query = "INSERT INTO transactions (transaction_date, transaction_type, amount, description) VALUES (?, ?, ?, ?)";
    
    // Prepare statement
    $stmt = $conn->prepare($query);
    
    // Bind parameters
    $stmt->bind_param("ssds", $_POST['transaction_date'], $_POST['transaction_type'], $_POST['amount'], $_POST['description']);
    
    // Execute query
    $stmt->execute();
    
    // Check if query is successful
    if ($stmt->affected_rows > 0) {
        echo 'success';
    } else {
        echo 'Error creating transaction';
    }
    
    // Close statement
    $stmt->close();
    
    // Close connection
    $conn->close();
}
?>
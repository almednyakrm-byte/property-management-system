**create_payments.php**

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
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Create Payment</h2>
        <form id="create-payment-form">
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2" for="payment_date">Payment Date:</label>
                <input class="appearance-none block w-full bg-white border border-gray-300 rounded-lg py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="payment_date" type="date" name="payment_date" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2" for="payment_amount">Payment Amount:</label>
                <input class="appearance-none block w-full bg-white border border-gray-300 rounded-lg py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="payment_amount" type="number" name="payment_amount" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2" for="payment_method">Payment Method:</label>
                <select class="appearance-none block w-full bg-white border border-gray-300 rounded-lg py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="payment_method" name="payment_method" required>
                    <option value="">Select Payment Method</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cash">Cash</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2" for="payment_status">Payment Status:</label>
                <select class="appearance-none block w-full bg-white border border-gray-300 rounded-lg py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="payment_status" name="payment_status" required>
                    <option value="">Select Payment Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Paid">Paid</option>
                    <option value="Failed">Failed</option>
                </select>
            </div>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" type="submit">Create Payment</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-payment-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/payments.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_payments.php';
                    } else {
                        alert('Error creating payment');
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


**payments.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['payment_date']) && isset($_POST['payment_amount']) && isset($_POST['payment_method']) && isset($_POST['payment_status'])) {
    // Insert data into payments table
    $payment_date = $_POST['payment_date'];
    $payment_amount = $_POST['payment_amount'];
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];

    $query = "INSERT INTO payments (payment_date, payment_amount, payment_method, payment_status) VALUES ('$payment_date', '$payment_amount', '$payment_method', '$payment_status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating payment';
    }
}
?>
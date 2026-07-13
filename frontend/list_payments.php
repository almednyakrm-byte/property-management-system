**list_payments.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <header class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <p class="mr-2">Welcome, <?php echo $_SESSION['username']; ?></p>
                <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
            </div>
        </header>
        <main class="bg-white rounded-lg shadow-md p-4">
            <h2 class="text-lg font-bold mb-2">Payments</h2>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-2" onclick="location.href='create_payments.php'">Add New Item</button>
            <div class="flex justify-between items-center mb-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Search...">
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchPayments()">Search</button>
            </div>
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="payments-table">
                    <?php
                    // Fetch payments data from backend
                    $payments = fetchPayments();
                    foreach ($payments as $payment) {
                        ?>
                        <tr>
                            <td class="px-4 py-2"><?php echo $payment['id']; ?></td>
                            <td class="px-4 py-2"><?php echo $payment['date']; ?></td>
                            <td class="px-4 py-2"><?php echo $payment['amount']; ?></td>
                            <td class="px-4 py-2">
                                <a href="edit_payments.php?id=<?php echo $payment['id']; ?>" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <button class="text-red-600 hover:text-red-800" onclick="deletePayment(<?php echo $payment['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
        // Fetch payments data from backend
        function fetchPayments() {
            return fetch('../backend/payments.php')
                .then(response => response.json())
                .then(data => data.payments);
        }

        // Search payments
        function searchPayments() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetchPayments().then(payments => {
                    const tableBody = document.getElementById('payments-table');
                    tableBody.innerHTML = '';
                    payments.forEach(payment => {
                        if (payment.date.includes(searchQuery) || payment.amount.includes(searchQuery)) {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${payment.id}</td>
                                <td class="px-4 py-2">${payment.date}</td>
                                <td class="px-4 py-2">${payment.amount}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_payments.php?id=${payment.id}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                    <button class="text-red-600 hover:text-red-800" onclick="deletePayment(${payment.id})">Delete</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        }
                    });
                });
            } else {
                fetchPayments().then(payments => {
                    const tableBody = document.getElementById('payments-table');
                    tableBody.innerHTML = '';
                    payments.forEach(payment => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2">${payment.id}</td>
                            <td class="px-4 py-2">${payment.date}</td>
                            <td class="px-4 py-2">${payment.amount}</td>
                            <td class="px-4 py-2">
                                <a href="edit_payments.php?id=${payment.id}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <button class="text-red-600 hover:text-red-800" onclick="deletePayment(${payment.id})">Delete</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                });
            }
        }

        // Delete payment
        function deletePayment(id) {
            if (confirm('Are you sure you want to delete this payment?')) {
                fetch(`../backend/payments.php?delete=${id}`, { method: 'DELETE' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Payment deleted successfully!');
                            location.reload();
                        } else {
                            alert('Error deleting payment!');
                        }
                    })
                    .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

<?php
// Function to fetch payments data from backend
function fetchPayments() {
    $url = '../backend/payments.php';
    $options = array(
        'http' => array(
            'method'  => 'GET',
            'header'  => 'Content-Type: application/json'
        )
    );
    $context  = stream_context_create($options);
    $response = json_decode(file_get_contents($url, false, $context), true);
    return $response;
}
?>


**backend/payments.php**

<?php
// Fetch payments data from database
$payments = array();
$payments[] = array(
    'id' => 1,
    'date' => '2022-01-01',
    'amount' => 100.00
);
$payments[] = array(
    'id' => 2,
    'date' => '2022-01-15',
    'amount' => 200.00
);
$payments[] = array(
    'id' => 3,
    'date' => '2022-02-01',
    'amount' => 300.00
);

// Search query
$searchQuery = $_GET['search'] ?? '';
if ($searchQuery) {
    $filteredPayments = array_filter($payments, function($payment) use ($searchQuery) {
        return strpos($payment['date'], $searchQuery) !== false || strpos($payment['amount'], $searchQuery) !== false;
    });
    $payments = $filteredPayments;
}

// Delete payment
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Delete payment from database
    // ...
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('payments' => $payments));
}
?>

Note: This is a basic implementation and you should replace the `fetchPayments()` function in the `list_payments.php` file with your actual database query to fetch payments data. Also, you should implement the delete payment logic in the `backend/payments.php` file.
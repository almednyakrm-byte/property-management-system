**list_transactions.php**

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
    <title>Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #009688;
        }
        .teal-500 {
            color: #0097a7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-4">
        <nav class="bg-white shadow-md p-4">
            <div class="flex justify-between">
                <a href="index.php" class="text-lg font-bold">Home</a>
                <div class="flex items-center">
                    <p class="mr-2">Welcome, <?php echo $_SESSION['username']; ?></p>
                    <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
                </div>
            </div>
        </nav>
        <div class="p-4 mt-4">
            <h2 class="text-lg font-bold">Transactions</h2>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_transactions.php'">Add New Item</button>
            <div class="mt-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Search...">
                <div class="absolute top-0 left-0 p-2">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            <table class="w-full mt-4">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody id="transactions">
                    <!-- Table records will be populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const transactionsTable = document.getElementById('transactions');

        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const transactions = transactionsTable.children;
            for (let i = 0; i < transactions.length; i++) {
                const row = transactions[i];
                const id = row.children[0].textContent.toLowerCase();
                const date = row.children[1].textContent.toLowerCase();
                const amount = row.children[2].textContent.toLowerCase();
                if (id.includes(searchValue) || date.includes(searchValue) || amount.includes(searchValue)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        const fetchTransactions = async () => {
            try {
                const response = await fetch('../backend/transactions.php');
                const data = await response.json();
                transactionsTable.innerHTML = '';
                data.forEach((transaction) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${transaction.id}</td>
                        <td class="px-4 py-2">${transaction.date}</td>
                        <td class="px-4 py-2">${transaction.amount}</td>
                        <td class="px-4 py-2">
                            <a href="edit_transactions.php?id=${transaction.id}" class="text-teal-500 hover:text-teal-700">Edit</a>
                            <button class="text-red-600 hover:text-red-800" onclick="deleteTransaction(${transaction.id})">Delete</button>
                        </td>
                    `;
                    transactionsTable.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        };

        const deleteTransaction = async (id) => {
            try {
                const response = await fetch('../backend/transactions.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    fetchTransactions();
                } else {
                    console.error('Error deleting transaction');
                }
            } catch (error) {
                console.error(error);
            }
        };

        fetchTransactions();
    </script>
</body>
</html>

**Note:** This code assumes that you have a `transactions.php` file in the `../backend` directory that handles GET and DELETE requests for transactions. You will need to create this file and implement the necessary logic to fetch and delete transactions.
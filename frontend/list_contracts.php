**list_contracts.php**

<?php
session_start();

// Check if user is authenticated
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
    <title>Contracts</title>
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
        <header class="bg-white shadow-md p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-lg font-bold">Home</a>
                <div class="flex items-center">
                    <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">Logout</button>
                </div>
            </nav>
        </header>
        <div class="bg-white shadow-md p-4 mb-4">
            <h2 class="text-lg font-bold">Contracts</h2>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_contracts.php'">Add New Item</button>
            <div class="flex justify-between mb-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Search...">
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">Search</button>
            </div>
            <table class="w-full border-collapse border border-gray-400">
                <thead>
                    <tr>
                        <th class="border border-gray-400 p-2">ID</th>
                        <th class="border border-gray-400 p-2">Name</th>
                        <th class="border border-gray-400 p-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <?php
                    // Fetch records from backend
                    $records = fetchRecords();
                    foreach ($records as $record) {
                        ?>
                        <tr>
                            <td class="border border-gray-400 p-2"><?= $record['id'] ?></td>
                            <td class="border border-gray-400 p-2"><?= $record['name'] ?></td>
                            <td class="border border-gray-400 p-2">
                                <a href="edit_contracts.php?id=<?= $record['id'] ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        async function fetchRecords() {
            const response = await fetch('../backend/contracts.php');
            const data = await response.json();
            return data;
        }

        async function deleteRecord(id) {
            const response = await fetch('../backend/contracts.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            });
            if (response.ok) {
                const records = await fetchRecords();
                const recordsElement = document.getElementById('records');
                recordsElement.innerHTML = '';
                records.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-400 p-2">${record.id}</td>
                        <td class="border border-gray-400 p-2">${record.name}</td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_contracts.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    `;
                    recordsElement.appendChild(row);
                });
            } else {
                alert('Error deleting record');
            }
        }

        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetchRecords().then(records => {
                    const recordsElement = document.getElementById('records');
                    recordsElement.innerHTML = '';
                    records.forEach(record => {
                        if (record.name.toLowerCase().includes(searchQuery.toLowerCase())) {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 p-2">${record.id}</td>
                                <td class="border border-gray-400 p-2">${record.name}</td>
                                <td class="border border-gray-400 p-2">
                                    <a href="edit_contracts.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">Delete</button>
                                </td>
                            `;
                            recordsElement.appendChild(row);
                        }
                    });
                });
            } else {
                fetchRecords().then(records => {
                    const recordsElement = document.getElementById('records');
                    recordsElement.innerHTML = '';
                    records.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-400 p-2">${record.id}</td>
                            <td class="border border-gray-400 p-2">${record.name}</td>
                            <td class="border border-gray-400 p-2">
                                <a href="edit_contracts.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">Delete</button>
                            </td>
                        `;
                        recordsElement.appendChild(row);
                    });
                });
            }
        }
    </script>
</body>
</html>


**contracts.php (backend)**

<?php
// Fetch records from database
$records = array(
    array('id' => 1, 'name' => 'Contract 1'),
    array('id' => 2, 'name' => 'Contract 2'),
    array('id' => 3, 'name' => 'Contract 3')
);

// Handle DELETE request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    // Delete record from database
    // ...
    echo json_encode(array('message' => 'Record deleted successfully'));
    exit;
}

// Return records as JSON
header('Content-Type: application/json');
echo json_encode($records);
exit;
?>
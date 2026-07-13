**list_تاريخ-العقار-property-history.php**

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
    <title>تاريخ العقار (Property History)</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #333;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .table th {
            background-color: #333;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Back to Index</a>
        <span class="text-white">Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تاريخ العقار (Property History)</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_تاريخ-العقار-property-history.php'">Add New Item</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="Search...">
            <button onclick="searchRecords()">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Property Name</th>
                    <th>History</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be fetched from AJAX call -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/تاريخ-العقار-property-history.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.property_name}</td>
                                <td>${record.history}</td>
                                <td>
                                    <a href="edit_تاريخ-العقار-property-history.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">Delete</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/تاريخ-العقار-property-history.php')
                    .then(response => response.json())
                    .then(data => {
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.property_name}</td>
                                <td>${record.history}</td>
                                <td>
                                    <a href="edit_تاريخ-العقار-property-history.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">Delete</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch('../backend/تاريخ-العقار-property-history.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Record deleted successfully!');
                        searchRecords();
                    } else {
                        alert('Error deleting record!');
                    }
                });
            }
        }

        searchRecords();
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP script (`../backend/تاريخ-العقار-property-history.php`) that handles the GET and DELETE requests. You will need to create this script to handle the data fetching and deletion logic.
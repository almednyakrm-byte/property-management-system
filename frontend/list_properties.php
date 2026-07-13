**list_properties.php**

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
    <title>Properties</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E73;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <p class="mr-2">Welcome, <?php echo $_SESSION['username']; ?></p>
                <a href="logout.php" class="text-red-600 hover:text-red-800">Logout</a>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Properties</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_properties.php'">Add New Item</button>
        <div class="flex justify-between mt-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Search...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchProperties()">Search</button>
        </div>
        <table class="w-full mt-4">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody id="properties-table">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get properties list
        async function getProperties() {
            try {
                const response = await fetch('../backend/properties.php');
                const data = await response.json();
                const tableBody = document.getElementById('properties-table');
                tableBody.innerHTML = '';
                data.forEach((property) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${property.id}</td>
                        <td class="px-4 py-2">${property.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_properties.php?id=${property.id}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <button class="text-red-600 hover:text-red-800" onclick="deleteProperty(${property.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search properties
        function searchProperties() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/properties.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('properties-table');
                        tableBody.innerHTML = '';
                        data.forEach((property) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${property.id}</td>
                                <td class="px-4 py-2">${property.name}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_properties.php?id=${property.id}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                    <button class="text-red-600 hover:text-red-800" onclick="deleteProperty(${property.id})">Delete</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                getProperties();
            }
        }

        // Delete property
        async function deleteProperty(id) {
            if (confirm('Are you sure you want to delete this property?')) {
                try {
                    const response = await fetch('../backend/properties.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });
                    if (response.ok) {
                        getProperties();
                    } else {
                        alert('Error deleting property');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        getProperties();
    </script>
</body>
</html>

**Note:** This code assumes that you have a `properties.php` file in the `../backend` directory that handles GET and DELETE requests for properties. You will need to create this file and implement the necessary logic to retrieve and delete properties.
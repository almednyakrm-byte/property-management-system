<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];

// HTML content
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>العقار (Property)</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white">
    <header class="bg-indigo-500 py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Current User: <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 mt-10">
        <h1 class="text-3xl font-bold mb-4">العقار (Property) List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_العقار-property.php'">Add New Item</button>
            <input type="text" id="search" class="bg-slate-800 text-white font-bold py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="property-list" class="w-full text-white">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="property-list-body">
                <!-- Table content will be populated by JavaScript -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get property list
        fetch('../backend/العقار-property.php')
            .then(response => response.json())
            .then(data => {
                const propertyListBody = document.getElementById('property-list-body');
                data.forEach(property => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${property.id}</td>
                        <td class="px-4 py-2">${property.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_العقار-property.php?id=${property.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                            <button class="text-indigo-500 hover:text-indigo-700" onclick="deleteProperty(${property.id})">Delete</button>
                        </td>
                    `;
                    propertyListBody.appendChild(row);
                });
            });

        // Delete property using Fetch API
        function deleteProperty(id) {
            fetch('../backend/العقار-property.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove deleted property from table
                    const propertyListBody = document.getElementById('property-list-body');
                    const rows = propertyListBody.children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            propertyListBody.removeChild(rows[i]);
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting property:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.getElementById('property-list-body').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const nameCell = row.children[1];
                if (nameCell.textContent.toLowerCase().includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
**list_الإخطار.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإخطار</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            color: #ffffff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #ffffff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ffffff;
        }
        .table-container {
            max-width: 800px;
            margin: 2rem auto;
        }
        .table-container table {
            border-collapse: collapse;
            width: 100%;
        }
        .table-container table th, .table-container table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table-container table th {
            background-color: #1f2937;
            color: #ffffff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            margin: 1rem auto;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button[type="submit"] {
            background-color: #1f2937;
            color: #ffffff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #1f2937;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مرحباً <?= $_SESSION['username'] ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>عنوان الإخطار</th>
                    <th>تاريخ الإخطار</th>
                    <th>حالة الإخطار</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table records will be populated here -->
            </tbody>
        </table>
    </div>
    <div class="search-bar">
        <input type="search" id="search-input" placeholder="بحث...">
        <button type="submit" id="search-button">بحث</button>
    </div>
    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_الإخطار.php'">إضافة جديد</button>

    <script>
        // Fetch API to get list records
        fetch('../backend/الإخطار.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.title}</td>
                        <td>${item.date}</td>
                        <td>${item.status}</td>
                        <td>
                            <a href="edit_الإخطار.php?id=${item.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteItem(${item.id})">حذف</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Search bar functionality
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        searchButton.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                fetch('../backend/الإخطار.php?search=' + searchTerm)
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('table-body');
                        tableBody.innerHTML = '';
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.title}</td>
                                <td>${item.date}</td>
                                <td>${item.status}</td>
                                <td>
                                    <a href="edit_الإخطار.php?id=${item.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteItem(${item.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                fetch('../backend/الإخطار.php')
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('table-body');
                        tableBody.innerHTML = '';
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.title}</td>
                                <td>${item.date}</td>
                                <td>${item.status}</td>
                                <td>
                                    <a href="edit_الإخطار.php?id=${item.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteItem(${item.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        });

        // Delete item functionality
        function deleteItem(id) {
            if (confirm('هل تريد حذف هذا العنصر؟')) {
                fetch('../backend/الإخطار.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف العنصر بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code creates a premium Tailwind UI layout with a header navigation, table showing list of records, search bar, and "Add New Item" button. The table records are populated using Fetch API to fetch data from the backend. The search bar functionality is also implemented using Fetch API. The delete item functionality is implemented using AJAX call to the backend.
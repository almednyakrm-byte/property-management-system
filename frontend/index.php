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
    <title>نظام إدارة عقارات مع إدارة العقود والpayments والتسويات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="flex flex-col h-screen">
        <header class="bg-emerald-600 text-white py-4">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <h1 class="text-3xl font-bold">نظام إدارة عقارات مع إدارة العقود والpayments والتسويات</h1>
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
            </div>
        </header>
        <main class="flex-1 p-4">
            <div class="glassmorphism-card p-4 mb-4">
                <h2 class="text-2xl font-bold mb-2">مرحباً</h2>
                <p>إدارة عقارات مع إدارة العقود والpayments والتسويات</p>
            </div>
            <div class="glassmorphism-card p-4 mb-4">
                <h2 class="text-2xl font-bold mb-2">إحصائيات</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h3 class="text-lg font-bold mb-2">عقارات</h3>
                        <p id="properties-count"></p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h3 class="text-lg font-bold mb-2">عقود</h3>
                        <p id="contracts-count"></p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h3 class="text-lg font-bold mb-2">دفعات</h3>
                        <p id="payments-count"></p>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h3 class="text-lg font-bold mb-2">تسويات</h3>
                        <p id="transactions-count"></p>
                    </div>
                </div>
            </div>
            <div class="glassmorphism-card p-4 mb-4">
                <h2 class="text-2xl font-bold mb-2">روابط سريعة</h2>
                <ul class="list-none mb-0">
                    <li class="mb-2"><a href="properties.php" class="text-emerald-600 hover:text-emerald-800">إدارة عقارات</a></li>
                    <li class="mb-2"><a href="contracts.php" class="text-emerald-600 hover:text-emerald-800">إدارة عقود</a></li>
                    <li class="mb-2"><a href="payments.php" class="text-emerald-600 hover:text-emerald-800">إدارة دفعات</a></li>
                    <li class="mb-2"><a href="transactions.php" class="text-emerald-600 hover:text-emerald-800">إدارة تسويات</a></li>
                </ul>
            </div>
        </main>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('properties-count').textContent = data.properties_count;
                document.getElementById('contracts-count').textContent = data.contracts_count;
                document.getElementById('payments-count').textContent = data.payments_count;
                document.getElementById('transactions-count').textContent = data.transactions_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code assumes you have a backend API that returns the stats data in JSON format. You'll need to replace `/api/stats` with the actual URL of your API endpoint.

Also, make sure to create a `logout.php` file that handles the logout logic and redirects the user to the login page.

Note: This code uses Tailwind CSS for styling, so make sure to include the Tailwind CSS file in your HTML header.
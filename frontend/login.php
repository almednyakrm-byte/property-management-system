<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #f7f7f7, #f7f7f7);
            background-size: 100% 300px;
            background-position: 0% 100%;
            -webkit-transition: background-position 2s linear;
            transition: background-position 2s linear;
        }
        .glassmorphic {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .gradient {
            background: linear-gradient(90deg, #4ade80, #1a8dfe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <div class="h-screen flex justify-center items-center bg-gray-100">
        <div class="glassmorphic w-96 p-10 bg-white rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-emerald-600 mb-4">Login</h2>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <div id="username-error" class="text-red-500 hidden"></div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <div id="password-error" class="text-red-500 hidden"></div>
                </div>
                <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
                <p class="text-gray-600 text-sm mt-2">Don't have an account? <a href="register.php" class="text-teal-500 hover:text-teal-700">Register</a></p>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('username-error');
        const passwordError = document.getElementById('password-error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            usernameError.classList.remove('text-red-500');
            passwordError.classList.remove('text-red-500');
            usernameError.textContent = '';
            passwordError.textContent = '';

            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();

            if (username === '') {
                usernameError.textContent = 'Username is required';
                usernameError.classList.add('text-red-500');
            } else if (!username.match(pattern)) {
                usernameError.textContent = 'Invalid username';
                usernameError.classList.add('text-red-500');
            }

            if (password === '') {
                passwordError.textContent = 'Password is required';
                passwordError.classList.add('text-red-500');
            }

            if (username === '' || !username.match(pattern) || password === '') {
                return;
            }

            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in');
            }
        });
    </script>
</body>
</html>


Note: The `pattern` variable is not defined in the code snippet. You need to define it before using it in the input field. You can define it as follows:


const pattern = "[A-Za-z\u0600-\u06FF0-9\s]+";
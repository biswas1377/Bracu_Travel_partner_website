<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - BRACu TravelBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-center items-center">
<header class="bg-blue-600 text-white p-4 shadow-md w-full flex justify-between items-center">
    <h1 class="text-3xl font-bold">Admin Panel</h1>
</header>
<main class="flex-grow flex flex-col justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md text-center">
        <h2 class="text-2xl font-bold mb-6">Admin Access</h2>
        <a href="admin_register.php" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition duration-300 mb-4 inline-block w-full">Register as Admin</a>
        <a href="admin_login.php" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 inline-block w-full">Login as Admin</a>
    </div>
</main>
<footer class="bg-white text-center py-4 shadow-md w-full">
    <p class="text-gray-600">&copy; 2024 BRACu TravelBuddy. All rights reserved.</p>
</footer>
</body>
</html>
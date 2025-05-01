<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete Account</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 min-h-screen flex flex-col">
<main class="flex flex-col items-center mt-16 flex-grow">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Confirm Delete Account</h2>
        <form method="post" action="delete_account.php">
            <label for="delete_password" class="block text-gray-600 font-semibold mb-2">Enter Password to Delete Account:</label>
            <input type="password" id="delete_password" name="delete_password" required class="bg-gray-50 p-2 rounded-md w-full mb-4">
            <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 transition duration-300 w-full">Confirm Delete</button>
        </form>
        <a href="dashboard.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Cancel</a>
    </div>
</main>
</body>
</html>
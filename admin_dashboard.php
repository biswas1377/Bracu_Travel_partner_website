<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Include database connection
include 'admindb.php';

// Query to get the total number of users
$result = $conn->query("SELECT COUNT(*) AS total_users FROM student");
$row = $result->fetch_assoc();
$total_users = $row['total_users'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BRACu TravelBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex">
<!-- Sidebar -->
<div class="bg-blue-800 text-white w-64 min-h-screen p-6">
    <h2 class="text-2xl font-bold mb-6">Admin Dashboard</h2>
    <nav>
        <ul>
            <li class="mb-4">
                <a href="admin_dashboard.php" class="block py-2 px-4 rounded hover:bg-blue-700">Dashboard</a>
            </li>
            <li class="mb-4">
                <a href="manage_users.php" class="block py-2 px-4 rounded hover:bg-blue-700">Manage Users</a>
            </li>
            <li class="mb-4">
                <a href="admin_logout.php" class="block py-2 px-4 rounded hover:bg-blue-700">Logout</a>
            </li>
        </ul>
    </nav>
</div>

<!-- Main Content -->
<div class="flex-1 p-10">
    <h1 class="text-3xl font-bold mb-6">Welcome, Admin!</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-4">Total Users</h2>
            <p class="text-3xl"><?php echo $total_users; ?></p>
        </div>
    </div>
</div>
</body>
</html>
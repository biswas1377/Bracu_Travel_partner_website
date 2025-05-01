<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'admindb.php';

$result = $conn->query("SELECT r.id, u.name, u.g_suite, p.content 
                        FROM reports r 
                        JOIN posts p ON r.post_id = p.id 
                        JOIN users u ON p.user_id = u.id");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reports - BRACu TravelBuddy</title>
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
                <a href="all_reports.php" class="block py-2 px-4 rounded hover:bg-blue-700">All Reports</a>
            </li>
            <li class="mb-4">
                <a href="logout.php" class="block py-2 px-4 rounded hover:bg-blue-700">Logout</a>
            </li>
        </ul>
    </nav>
</div>

<!-- Main Content -->
<div class="flex-1 p-10">
    <h1 class="text-3xl font-bold mb-6">All Reports</h1>
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Reported Users</h2>
        <table class="min-w-full bg-white">
            <thead>
            <tr>
                <th class="py-2">User Name</th>
                <th class="py-2">G-Suite</th>
                <th class="py-2">Post Content</th>
                <th class="py-2">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="py-2"><?php echo htmlspecialchars($row['g_suite']); ?></td>
                    <td class="py-2"><?php echo htmlspecialchars($row['content']); ?></td>
                    <td class="py-2">
                        <form action="restrict_user.php" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded">Restrict</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
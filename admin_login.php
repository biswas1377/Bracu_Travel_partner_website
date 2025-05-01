<?php
include 'admindb.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $g_suite = $_POST['g_suite'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM admins WHERE g_suite=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $g_suite);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['admin_id'] = $id;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $message = "Invalid credentials!";
        }
    } else {
        $message = "Invalid credentials!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BRACu TravelBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-center items-center">
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>
    <?php if ($message): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form action="admin_login.php" method="POST">
        <div class="mb-4">
            <label class="block text-gray-600 font-semibold">G-Suite:</label>
            <input type="email" name="g_suite" class="bg-gray-50 p-2 rounded-md w-full" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-semibold">Password:</label>
            <input type="password" name="password" class="bg-gray-50 p-2 rounded-md w-full" required>
        </div>
        <button type="submit" name="login" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 w-full">Login</button>
    </form>
    <a href="admin_register.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Don't have an account? Register</a>
</div>
</body>
</html>
<?php
session_start();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Include database connection
    include 'admindb.php';

    // Check the number of registered admins
    $result = $conn->query("SELECT COUNT(*) AS total_admins FROM admins");
    $row = $result->fetch_assoc();
    $total_admins = $row['total_admins'];

    if ($total_admins >= 4) {
        $error = "Maximum number of admins (4) already registered!";
    } else {
        $name = $_POST['name'];
        $g_suite = $_POST['g_suite'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $special_code = $_POST['special_code'];
        $correct_special_code = 'boom chaka-laka';

        if ($password !== $confirm_password) {
            $error = "Passwords do not match!";
        } elseif ($special_code !== $correct_special_code) {
            $error = "Invalid special code!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO admins (name, g_suite, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $g_suite, $hashed_password);

            if ($stmt->execute()) {
                $success = "Registration successful! You can now log in.";
            } else {
                $error = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register - BRACu TravelBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-center items-center">
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Admin Register</h2>
    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>
    <form action="admin_register.php" method="POST">
        <div class="mb-4">
            <label class="block text-gray-600 font-semibold">Name:</label>
            <input type="text" name="name" class="bg-gray-50 p-2 rounded-md w-full" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-semibold">G-Suite:</label>
            <input type="email" name="g_suite" class="bg-gray-50 p-2 rounded-md w-full" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-semibold">Password:</label>
            <input type="password" name="password" class="bg-gray-50 p-2 rounded-md w-full" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-semibold">Confirm Password:</label>
            <input type="password" name="confirm_password" class="bg-gray-50 p-2 rounded-md w-full" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-600 font-semibold">Special Code:</label>
            <input type="password" name="special_code" class="bg-gray-50 p-2 rounded-md w-full" required>
        </div>
        <button type="submit" name="register" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 w-full">Register</button>
    </form>
    <a href="admin_login.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Already have an account? Login</a>
</div>
</body>
</html>
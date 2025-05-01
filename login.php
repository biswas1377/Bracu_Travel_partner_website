<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $g_suite = $_POST['g_suite'];
    $university_sID = $_POST['university_sID'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Student WHERE g_suite='$g_suite' AND university_sID='$university_sID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['user_id'] = $row['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Password error.";
        }
    } else {
        $message = "User doesn't exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 min-h-screen flex flex-col">
<header class="bg-white shadow-md p-4 flex justify-between items-center">
    <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-purple-500">BRACu TravelBuddy</h1>
    <a href="index.html" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">Home</a>
</header>
<main class="flex flex-col items-center mt-16 flex-grow">
    <section class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Login</h2>
        <form method="post" action="">
            <div class="mb-4">
                <label for="g_suite" class="block text-gray-600 font-semibold mb-2">G-Suite:</label>
                <input type="email" id="g_suite" name="g_suite" required class="bg-gray-50 p-2 rounded-md w-full">
            </div>
            <div class="mb-4">
                <label for="university_sID" class="block text-gray-600 font-semibold mb-2">University ID:</label>
                <input type="text" id="university_sID" name="university_sID" required class="bg-gray-50 p-2 rounded-md w-full">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-600 font-semibold mb-2">Password:</label>
                <input type="password" id="password" name="password" required class="bg-gray-50 p-2 rounded-md w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 w-full">Login</button>
        </form>
        <?php if ($message): ?>
            <div class="<?php echo strpos($message, 'successful') !== false ? 'message' : 'error'; ?> mt-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <a href="register.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Register</a>
    </section>
</main>
</body>
</html>
<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$password = $_POST['delete_password'];

// Fetch the user's current password hash from the database
$sql = "SELECT password FROM Student WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        // Password is correct, delete related posts first
        $delete_posts_sql = "DELETE FROM Posts WHERE user_id='$user_id'";
        if ($conn->query($delete_posts_sql) === TRUE) {
            // Now delete the account
            $delete_sql = "DELETE FROM Student WHERE id='$user_id'";
            if ($conn->query($delete_sql) === TRUE) {
                session_unset();
                session_destroy();
                header("Location: register.php?message=Account deleted successfully.");
                exit();
            } else {
                $message = "Error deleting account: " . $conn->error;
            }
        } else {
            $message = "Error deleting posts: " . $conn->error;
        }
    } else {
        $message = "Incorrect password.";
    }
} else {
    $message = "User not found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Delete Account</h2>
        <?php if (isset($message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?php echo $message; ?></span>
            </div>
        <?php endif; ?>
        <a href="dashboard.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Back to Dashboard</a>
    </div>
</body>
</html>
<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Student WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

$message = "";
$message_type = ""; // To track the type of message (success or error)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $g_suite = $_POST['g_suite'];
        $semester_done = $_POST['semester_done'];
        $phone = $_POST['phone'];
        $contact_profile_link = $_POST['contact_profile_link'];

        $update_sql = "UPDATE Student SET name='$name', g_suite='$g_suite', semester_done='$semester_done', phone='$phone', contact_profile_link='$contact_profile_link' WHERE id='$user_id'";
        if ($conn->query($update_sql) === TRUE) {
            $message = "Profile updated successfully.";
            $message_type = "success";
        } else {
            $message = "Error updating profile: " . $conn->error;
            $message_type = "error";
        }
    }

    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password_sql = "UPDATE Student SET password='$hashed_password' WHERE id='$user_id'";
                if ($conn->query($update_password_sql) === TRUE) {
                    $message .= " Password updated successfully.";
                    $message_type = "success";
                } else {
                    $message .= " Error updating password: " . $conn->error;
                    $message_type = "error";
                }
            } else {
                $message .= " New password and confirm password do not match.";
                $message_type = "error";
            }
        } else {
            $message .= " Current password is incorrect.";
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 min-h-screen flex flex-col">
<main class="flex flex-col items-center mt-16 flex-grow">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Update Profile</h2>
        <?php if ($message): ?>
            <div class="<?php echo $message_type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' : 'bg-green-100 border border-green-400 text-green-700'; ?> px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold"><?php echo $message_type === 'error' ? 'Error!' : 'Success!'; ?></strong>
                <span class="block sm:inline"><?php echo $message; ?></span>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-600 font-semibold">Name:</label>
                    <input type="text" name="name" value="<?php echo $user['name']; ?>" class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div>
                    <label class="block text-gray-600 font-semibold">G-Suite:</label>
                    <input type="text" name="g_suite" value="<?php echo $user['g_suite']; ?>" class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div>
                    <label class="block text-gray-600 font-semibold">Semester Done:</label>
                    <input type="text" name="semester_done" value="<?php echo $user['semester_done']; ?>" class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div>
                    <label class="block text-gray-600 font-semibold">Phone:</label>
                    <input type="text" name="phone" value="<?php echo $user['phone']; ?>" class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div>
                    <label class="block text-gray-600 font-semibold">Contact Profile Link:</label>
                    <input type="text" name="contact_profile_link" value="<?php echo $user['contact_profile_link']; ?>" class="bg-gray-50 p-2 rounded-md w-full">
                </div>
            </div>
            <button type="submit" name="update_profile" class="mt-6 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 w-full">Update Profile</button>
        </form>
        <h2 class="text-2xl font-bold text-center text-gray-800 mt-8 mb-4">Update Password</h2>
        <form method="post">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-600 font-semibold">Current Password:</label>
                    <input type="password" name="current_password" class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div>
                    <label class="block text-gray-600 font-semibold">New Password:</label>
                    <input type="password" name="new_password" class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div>
                    <label class="block text-gray-600 font-semibold">Confirm New Password:</label>
                    <input type="password" name="confirm_password" class="bg-gray-50 p-2 rounded-md w-full">
                </div>
            </div>
            <button type="submit" name="update_password" class="mt-6 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 w-full">Update Password</button>
        </form>
        <a href="dashboard.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Back to Dashboard</a>
    </div>
</main>
</body>
</html>
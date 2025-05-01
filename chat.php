<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$chat_user_id = $_GET['user_id'];
$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : null;

if ($post_id === null) {
    die("Error: post_id is required.");
}

// Fetch chat messages between the two users for the specific post
$sql = "SELECT * FROM Messages
        WHERE post_id = '$post_id' AND
        ((sender_id = '$current_user_id' AND receiver_id = '$chat_user_id')
        OR (sender_id = '$chat_user_id' AND receiver_id = '$current_user_id'))
        ORDER BY created_at ASC";
$result = $conn->query($sql);

// Fetch chat user details
$user_sql = "SELECT name, contact_profile_link FROM student WHERE id='$chat_user_id'";
$user_result = $conn->query($user_sql);
$user_row = $user_result->fetch_assoc();

// Handle new message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $sql = "INSERT INTO Messages (sender_id, receiver_id, post_id, message, created_at)
            VALUES ('$current_user_id', '$chat_user_id', '$post_id', '$message', NOW())";
    $conn->query($sql);
    header("Location: chat.php?user_id=$chat_user_id&post_id=$post_id");
    exit();
}

// Handle chat deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_chat'])) {
    $sql = "DELETE FROM Messages
            WHERE post_id = '$post_id' AND
            ((sender_id = '$current_user_id' AND receiver_id = '$chat_user_id')
            OR (sender_id = '$chat_user_id' AND receiver_id = '$current_user_id'))";
    $conn->query($sql);
    header("Location: dashboard.php?message=Chat deleted successfully.");
    exit();
}

// Check if the current user is the post owner
$post_owner_sql = "SELECT user_id FROM Posts WHERE id='$post_id'";
$post_owner_result = $conn->query($post_owner_sql);
$post_owner_row = $post_owner_result->fetch_assoc();
$is_post_owner = $post_owner_row ? $post_owner_row['user_id'] == $current_user_id : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Box</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 min-h-screen flex flex-col">
<main class="flex items-center justify-center flex-grow">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Chat Box</h2>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-semibold text-gray-800"><?php echo $user_row['name']; ?></h3>
            </div>
        </div>
        <div class="mt-8 h-96 overflow-y-auto bg-gray-50 p-4 rounded-lg">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="message <?php echo $row['sender_id'] == $current_user_id ? 'text-right' : 'text-left'; ?> mb-4">
                        <div class="inline-block p-4 rounded-lg <?php echo $row['sender_id'] == $current_user_id ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'; ?>">
                            <p><?php echo $row['message']; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-600">No messages yet.</p>
            <?php endif; ?>
        </div>
        <form method="post" class="mt-4">
            <textarea name="message" class="w-full p-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Type your message..."></textarea>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mt-2">Send</button>
        </form>
        <?php if ($is_post_owner): ?>
            <form method="post" class="mt-4">
                <input type="hidden" name="delete_chat" value="1">
                <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 mt-2">Delete Chat</button>
            </form>
        <?php endif; ?>
        <a href="dashboard.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Back to Dashboard</a>
    </div>
</main>
</body>
</html>
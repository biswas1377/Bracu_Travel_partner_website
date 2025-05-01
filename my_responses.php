<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's responses
$sql = "SELECT Posts.description, Posts.id AS post_id, Responses.user_id AS post_owner_id
        FROM Responses
        JOIN Posts ON Responses.post_id = Posts.id
        WHERE Responses.interested_user_id = '$user_id'
        ORDER BY Responses.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Responses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 min-h-screen flex flex-col">
<main class="flex flex-col items-center mt-16 flex-grow">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">My Responses</h2>
        <div class="mt-8 h-96 overflow-y-auto">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="response bg-gray-50 p-4 rounded-md mb-4">
                        <p class="text-gray-800">You showed interest in the post: "<?php echo $row['description']; ?>"</p>
                        <a href="chat.php?user_id=<?php echo $row['post_owner_id']; ?>&post_id=<?php echo $row['post_id']; ?>" class="bg-blue-500 text-white py-1 px-2 rounded-md hover:bg-blue-600">Chat Box</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-600">No responses yet.</p>
            <?php endif; ?>
        </div>
        <a href="dashboard.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Back to Dashboard</a>
    </div>
</main>
</body>
</html>
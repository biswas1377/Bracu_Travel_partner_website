```php
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Include database connection
include 'admindb.php';

$user_id = $_GET['id'];

// Delete user's posts
$stmt = $conn->prepare("DELETE FROM posts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Delete user's messages
$stmt = $conn->prepare("DELETE FROM messages WHERE sender_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Delete user from student table
$stmt = $conn->prepare("DELETE FROM student WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: manage_users.php");
exit();
?>
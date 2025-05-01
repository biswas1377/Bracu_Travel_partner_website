<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_POST['post_id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM Posts WHERE id='$post_id' AND user_id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $delete_sql = "DELETE FROM Posts WHERE id='$post_id'";
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: all_posts.php?message=Post deleted successfully.");
        exit();
    } else {
        $message = "Error deleting post: " . $conn->error;
    }
} else {
    $message = "You are not authorized to delete this post.";
}

header("Location: all_posts.php?message=$message");
exit();
?>
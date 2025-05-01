<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch "BRAC University" first
$brac_university_sql = "SELECT id, name FROM Area WHERE name = 'BRAC University'";
$brac_university_result = $conn->query($brac_university_sql);

// Fetch other areas sorted alphabetically
$other_areas_sql = "SELECT id, name FROM Area WHERE name != 'BRAC University' ORDER BY name ASC";
$other_areas_result = $conn->query($other_areas_sql);

// Combine results
$areas = [];
if ($brac_university_result->num_rows > 0) {
    $areas[] = $brac_university_result->fetch_assoc();
}
while ($area = $other_areas_result->fetch_assoc()) {
    $areas[] = $area;
}

// Handle filtering
$filter_start_area = isset($_GET['filter_start_area']) ? $_GET['filter_start_area'] : 'all';
$filter_end_area = isset($_GET['filter_end_area']) ? $_GET['filter_end_area'] : 'all';

$filter_sql = "";
if ($filter_start_area !== 'all') {
    $filter_sql .= "WHERE start_area.name = '$filter_start_area'";
}
if ($filter_end_area !== 'all') {
    if ($filter_sql !== "") {
        $filter_sql .= " AND ";
    } else {
        $filter_sql .= "WHERE ";
    }
    $filter_sql .= "end_area.name = '$filter_end_area'";
}

// Fetch all posts with filtering
$posts_sql = "SELECT Posts.id, Posts.description, Posts.travel_time, Posts.travel_date, start_area.name AS start_area, end_area.name AS end_area, student.name, Posts.user_id, vehicle.name AS vehicle, Posts.gender_preference
              FROM Posts
              JOIN Area AS start_area ON Posts.start_area = start_area.id
              JOIN Area AS end_area ON Posts.end_area = end_area.id
              JOIN student ON Posts.user_id = student.id
              JOIN vehicle ON Posts.vehicle = vehicle.vehicle_id
              $filter_sql
              ORDER BY Posts.id DESC";
$posts_result = $conn->query($posts_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center h-screen">
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">All Posts</h2>
    <div class="mt-8 flex">
        <div class="w-1/2 pr-2">
            <form method="get" action="all_posts.php">
                <label for="filter_start_area" class="block text-gray-600 font-semibold mb-2">Filter by Starting Area:</label>
                <select id="filter_start_area" name="filter_start_area" class="bg-gray-50 p-2 rounded-md w-full" onchange="this.form.submit()">
                    <option value="all">All Areas</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?php echo $area['name']; ?>" <?php if ($filter_start_area == $area['name']) echo 'selected'; ?>><?php echo $area['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div class="w-1/2 pl-2">
            <form method="get" action="all_posts.php">
                <label for="filter_end_area" class="block text-gray-600 font-semibold mb-2">Filter by End Area:</label>
                <select id="filter_end_area" name="filter_end_area" class="bg-gray-50 p-2 rounded-md w-full" onchange="this.form.submit()">
                    <option value="all">All Areas</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?php echo $area['name']; ?>" <?php if ($filter_end_area == $area['name']) echo 'selected'; ?>><?php echo $area['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>
    <div class="mt-8 h-96 overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Posts</h3>
        <?php if ($posts_result->num_rows > 0): ?>
            <?php while($post = $posts_result->fetch_assoc()): ?>
                <div class="post bg-gray-50 p-4 rounded-md mb-4" data-start-area="<?php echo $post['start_area']; ?>">
                    <p class="text-gray-800"><strong><?php echo $post['name']; ?>:</strong> <?php echo $post['description']; ?></p>
                    <p class="text-gray-600">Travel Time: <?php echo date("H:i", strtotime($post['travel_time'])); ?></p>
                    <p class="text-gray-600">Travel Date: <?php echo $post['travel_date']; ?></p>
                    <p class="text-gray-600">Preferred Vehicle: <?php echo $post['vehicle']; ?></p>
                    <p class="text-gray-600">Gender Preference: <strong class="text-black"><?php echo $post['gender_preference']; ?></strong></p>
                    <p class="text-black-600">From: <strong><?php echo $post['start_area']; ?></strong> To: <strong><?php echo $post['end_area']; ?></strong></p>

                    <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                        <form method="post" action="delete_post.php" class="mt-2 inline-block">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded-md hover:bg-red-600">Delete</button>
                        </form>
                        <a href="edit_post.php?post_id=<?php echo $post['id']; ?>" class="bg-blue-500 text-white py-1 px-2 rounded-md hover:bg-blue-600 inline-block ml-2">Edit</a>
                    <?php else: ?>
                        <form method="post" action="interested.php" class="mt-2">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <button type="submit" class="bg-green-500 text-white py-1 px-2 rounded-md hover:bg-green-600">Interested</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-gray-600">No posts yet.</p>
        <?php endif; ?>
    </div>
    <a href="group.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Back to Forum</a>
</div>
</body>
</html>
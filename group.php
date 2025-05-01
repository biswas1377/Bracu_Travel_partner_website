<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $travel_time = $_POST['travel_time'];
    $start_area = $_POST['start_area'];
    $end_area = $_POST['end_area'];
    $travel_date = $_POST['travel_date'];
    $description = $_POST['description'];
    $vehicle = $_POST['vehicle'];
    $gender_preference = $_POST['gender_preference'];

    $sql = "INSERT INTO Posts (user_id, travel_time, start_area, end_area, travel_date, description, vehicle, gender_preference)
            VALUES ('$user_id', '$travel_time', '$start_area', '$end_area', '$travel_date', '$description', '$vehicle', '$gender_preference')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Post submitted successfully.";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch vehicle names
$vehicle_sql = "SELECT vehicle_id, name FROM vehicle";
$vehicle_result = $conn->query($vehicle_sql);
$vehicles = [];
while ($vehicle = $vehicle_result->fetch_assoc()) {
    $vehicles[] = $vehicle;
}

// Fetch areas
$area_sql = "SELECT id, name FROM Area";
$area_result = $conn->query($area_sql);
$areas = [];
while ($area = $area_result->fetch_assoc()) {
    $areas[] = $area;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center h-screen">
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Forum</h2>
    <?php if (isset($error_message)): ?>
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="group.php" class="flex flex-wrap">
        <div class="w-full md:w-1/2 pr-2">
            <div class="mb-4">
                <label for="travel_time" class="block text-gray-600 font-semibold mb-2">Travel Time:</label>
                <select id="travel_time" name="travel_time" required class="bg-gray-50 p-2 rounded-md w-full">
                    <?php for ($i = 0; $i < 24; $i++): ?>
                        <?php
                        $time = sprintf("%02d:00", $i);
                        ?>
                        <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                        <?php $time = sprintf("%02d:30", $i); ?>
                        <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="start_area" class="block text-gray-600 font-semibold mb-2">Travel Starting Area:</label>
                <select id="start_area" name="start_area" required class="bg-gray-50 p-2 rounded-md w-full">
                    <?php foreach ($areas as $area): ?>
                        <option value="<?php echo $area['id']; ?>"><?php echo $area['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="end_area" class="block text-gray-600 font-semibold mb-2">Travel End Area:</label>
                <select id="end_area" name="end_area" required class="bg-gray-50 p-2 rounded-md w-full">
                    <?php foreach ($areas as $area): ?>
                        <option value="<?php echo $area['id']; ?>"><?php echo $area['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="w-full md:w-1/2 pl-2">
            <div class="mb-4">
                <label for="travel_date" class="block text-gray-600 font-semibold mb-2">Travel Date:</label>
                <input type="date" id="travel_date" name="travel_date" required class="bg-gray-50 p-2 rounded-md w-full">
            </div>
            <div class="mb-4">
                <label for="vehicle" class="block text-gray-600 font-semibold mb-2">Preferred Vehicle:</label>
                <select id="vehicle" name="vehicle" required class="bg-gray-50 p-2 rounded-md w-full">
                    <?php foreach ($vehicles as $vehicle): ?>
                        <option value="<?php echo $vehicle['vehicle_id']; ?>"><?php echo $vehicle['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="gender_preference" class="block text-gray-600 font-semibold mb-2">Gender Preference:</label>
                <select id="gender_preference" name="gender_preference" required class="bg-gray-50 p-2 rounded-md w-full">
                    <option value="Any">Any</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-600 font-semibold mb-2">Description:</label>
                <textarea id="description" name="description" rows="3" required class="bg-gray-50 p-2 rounded-md w-full" maxlength="50"></textarea>
            </div>
        </div>
        <div class="w-full flex justify-center mt-4">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Submit Post</button>
        </div>
    </form>
    <a href="all_posts.php" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 w-full text-center">View All Posts</a>
    <a href="dashboard.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Back to Dashboard</a>
</div>
</body>
</html>
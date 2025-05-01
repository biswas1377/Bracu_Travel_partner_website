<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT Student.*, Area.name AS area_name FROM Student
        JOIN Area ON Student.area_id = Area.id
        WHERE Student.id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 min-h-screen flex flex-col">
<header class="bg-white shadow-md p-4 flex justify-between items-center">
    <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-purple-500">BRACu TravelBuddy</h1>
</header>
<main class="flex flex-col items-center mt-16 flex-grow">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl relative">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Dashboard</h2>
        <div class="text-center text-xl text-green-600 mb-6">
            Welcome, <?php echo $user['name']; ?>!
        </div>
        <div class="flex justify-center mb-6">
            <a href="group.php" class="inline-block bg-blue-500 hover:bg-blue-600 text-white py-3 px-5 rounded-lg text-lg font-bold transition duration-300">Forum</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-600 font-semibold">Name:</label>
                <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['name']; ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-semibold">G-Suite:</label>
                <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['g_suite']; ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-semibold">University ID:</label>
                <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['university_sID']; ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-semibold">Gender:</label>
                <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['gender']; ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-semibold">Date of Birth:</label>
                <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['dob']; ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-semibold">Semester Done:</label>
                <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['semester_done']; ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-semibold">Phone:</label>
                <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['phone']; ?></p>
            </div>
            <div>
                <label class="block text-gray-600 font-semibold">Contact Profile Link:</label>
                <p class="bg-gray-50 p-2 rounded-md">
                    <a href="<?php echo $user['contact_profile_link']; ?>" target="_blank" class="bg-blue-500 text-white py-1 px-3 rounded-md hover:bg-blue-600">
                        Visit Profile
                    </a>
                </p>
            </div>
            <div>
                <label class="block text-gray-600 font-semibold">Area:</label>
                <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['area_name']; ?></p>
            </div>
        </div>
        <a href="logout.php" class="mt-6 inline-block bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">Log Out</a>
        <a href="settings.php" class="mt-6 inline-block bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Settings</a>
        <a href="other_responses.php" class="absolute top-4 right-4 bg-blue-500 text-white py-1 px-2 rounded-md hover:bg-blue-600">Other's Responses</a>
        <a href="my_responses.php" class="absolute top-4 left-4 bg-blue-500 text-white py-1 px-2 rounded-md hover:bg-blue-600">My Responses</a>
    </div>
</main>
</body>
</html>
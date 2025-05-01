<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "User ID not provided.";
    exit();
}

$user_id = $_GET['id'];
$sql = "SELECT Student.name, Student.g_suite, Student.contact_profile_link, Student.dob, Student.gender, Student.semester_done, Area.name AS area_name
        FROM Student
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
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Profile</h2>
    <div>
        <label class="block text-gray-600 font-semibold">Name:</label>
        <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['name']; ?></p>
    </div>
    <div>
        <label class="block text-gray-600 font-semibold">G-Suite:</label>
        <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['g_suite']; ?></p>
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
        <label class="block text-gray-600 font-semibold">Date of Birth:</label>
        <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['dob']; ?></p>
    </div>
    <div>
        <label class="block text-gray-600 font-semibold">Gender:</label>
        <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['gender']; ?></p>
    </div>
    <div>
        <label class="block text-gray-600 font-semibold">Semester Done:</label>
        <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['semester_done']; ?></p>
    </div>
    <div>
        <label class="block text-gray-600 font-semibold">Area:</label>
        <p class="bg-gray-50 p-2 rounded-md"><?php echo $user['area_name']; ?></p>
    </div>
    <a href="group.php" class="mt-6 inline-block text-blue-500 hover:underline w-full text-center">Back to Forum</a>
</div>
</body>
</html>

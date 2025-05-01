<?php
include 'db.php';
session_start();

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $g_suite = $_POST['g_suite'];
    $university_sID = $_POST['university_sID'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $semester_done = $_POST['semester_done'];
    $phone = $_POST['phone'];
    $contact_profile_link = $_POST['contact_profile_link'];
    $area_id = $_POST['area_id'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO Student (name, g_suite, university_sID, gender, dob, semester_done, phone, contact_profile_link, area_id, password) VALUES ('$name', '$g_suite', '$university_sID', '$gender', '$dob', '$semester_done', '$phone', '$contact_profile_link', '$area_id', '$hashed_password')";
        if ($conn->query($insert_sql) === TRUE) {
            $success = true;
            $message = "Registration successful. You can now <a href='login.php' class='text-blue-500 hover:underline'>login</a>.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 min-h-screen flex flex-col">
<header class="bg-white shadow-md p-4 flex justify-between items-center">
    <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-purple-500">BRACu TravelBuddy</h1>
    <a href="index.html" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">Home</a>
</header>
<main class="flex flex-col items-center mt-16 flex-grow">
    <section class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Registration</h2>
        <?php if ($message): ?>
            <div class="mb-4 p-4 rounded-md <?php echo $success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="name" class="block text-gray-600 font-semibold mb-2">Name:</label>
                    <input type="text" id="name" name="name" required class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="g_suite" class="block text-gray-600 font-semibold mb-2">G-Suite:</label>
                    <input type="email" id="g_suite" name="g_suite" required class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="university_sID" class="block text-gray-600 font-semibold mb-2">University ID:</label>
                    <input type="text" id="university_sID" name="university_sID" required class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="gender" class="block text-gray-600 font-semibold mb-2">Gender:</label>
                    <select id="gender" name="gender" required class="bg-gray-50 p-2 rounded-md w-full">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="dob" class="block text-gray-600 font-semibold mb-2">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="semester_done" class="block text-gray-600 font-semibold mb-2">Semester Done:</label>
                    <input type="text" id="semester_done" name="semester_done" required class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-600 font-semibold mb-2">Phone:</label>
                    <input type="text" id="phone" name="phone" required class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="contact_profile_link" class="block text-gray-600 font-semibold mb-2">Contact Profile Link:</label>
                    <input type="text" id="contact_profile_link" name="contact_profile_link" required class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="area_id" class="block text-gray-600 font-semibold mb-2">Area:</label>
                    <select id="area_id" name="area_id" required class="bg-gray-50 p-2 rounded-md w-full">
                        <?php
                        $area_sql = "SELECT * FROM Area";
                        $area_result = $conn->query($area_sql);
                        while ($area = $area_result->fetch_assoc()) {
                            echo "<option value='" . $area['id'] . "'>" . $area['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-600 font-semibold mb-2">Password:</label>
                    <input type="password" id="password" name="password" required class="bg-gray-50 p-2 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-600 font-semibold mb-2">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="bg-gray-50 p-2 rounded-md w-full">
                </div>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 w-full">Register</button>
        </form>
        <a href="login.php" class="mt-4 inline-block text-blue-500 hover:underline w-full text-center">Already have an account? Login</a>
    </section>
</main>
</body>
</html>
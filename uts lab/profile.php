<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php'; 

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email, age, dob, address, gender, hobbies, photo FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: login.php");
    exit();
}

$username = $user['username'];
$email = $user['email'];
$age = $user['age'];
$dob = $user['dob'];
$address = $user['address'];
$gender = $user['gender'];
$hobbies = $user['hobbies'];
$photo = $user['photo'] ?: "photos/default.png"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 max-w-md"> 
        <h1 class="text-2xl font-bold mb-4 text-center">Your Profile</h1>

        <div class="text-center mb-4">
            <img src="<?php echo $photo; ?>" alt="Profile Photo" class="w-24 h-24 rounded-full mx-auto">
        </div>

        <p class="text-center"><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p class="text-center"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p class="text-center"><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
        <p class="text-center"><strong>Date of Birth:</strong> <?php echo htmlspecialchars($dob); ?></p>
        <p class="text-center"><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
        <p class="text-center"><strong>Gender:</strong> <?php echo htmlspecialchars($gender); ?></p>
        <p class="text-center"><strong>Hobbies:</strong> <?php echo htmlspecialchars($hobbies); ?></p>

        <div class="mt-4 text-center">
            <form method="GET" action="edit_profile.php" class="inline">
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700">Edit Profile</button>
            </form>

            <form method="POST" action="logout.php" class="inline">
                <button type="submit" name="logout" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700" onclick="return confirm('Are you sure you want to logout?');">Logout</button>
            </form>
        </div>

        <div class="mt-4 text-center">
            <form method="GET" action="index.php"> 
                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-700">Back to Dashboard</button>
            </form>
        </div>
    </div>
</body>
</html>

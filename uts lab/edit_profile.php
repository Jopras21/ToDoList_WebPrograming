<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$dbname = 'uts';
$username = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

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

if (isset($_POST['updateProfile'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $hobbies = $_POST['hobbies'];
    $photo = $_FILES['photo']['name'] ? $_FILES['photo']['name'] : null;
    $password = $_POST['password'];

    if ($photo) {
        $target_dir = "uploads/"; 
        $target_file = $target_dir . basename($photo);
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
    }

    $query = "UPDATE users SET username = ?, email = ?, age = ?, dob = ?, address = ?, gender = ?, hobbies = ?";
    
    if ($photo) {
        $query .= ", photo = ?";
    }
    
    if (!empty($password)) {
        $query .= ", password = ?";
    }
    
    $query .= " WHERE id = ?";

    $stmt = $pdo->prepare($query);
    
    $params = [
        $username,
        $email,
        $age,
        $dob,
        $address,
        $gender,
        $hobbies,
    ];

    if ($photo) {
        $params[] = $target_file;
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $params[] = $hashed_password;
    }

    $params[] = $user_id;

    $stmt->execute($params);

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 max-w-lg">
        <h1 class="text-2xl font-bold mb-4 text-center">Edit Profile</h1>

        <form method="POST" action="edit_profile.php" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required class="border border-gray-300 rounded p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="border border-gray-300 rounded p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">New Password (leave blank to keep current):</label>
                <input type="password" name="password" placeholder="New password" class="border border-gray-300 rounded p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="age" class="block text-sm font-medium text-gray-700">Age:</label>
                <input type="number" name="age" value="<?php echo htmlspecialchars($age); ?>" class="border border-gray-300 rounded p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth:</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($dob); ?>" class="border border-gray-300 rounded p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" class="border border-gray-300 rounded p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender:</label>
                <select name="gender" required class="border border-gray-300 rounded p-2 w-full">
                    <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="hobbies" class="block text-sm font-medium text-gray-700">Hobbies:</label>
                <input type="text" name="hobbies" value="<?php echo htmlspecialchars($hobbies); ?>" class="border border-gray-300 rounded p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="photo" class="block text-sm font-medium text-gray-700">Profile Photo:</label>
                <input type="file" name="photo" accept="image/*" class="border border-gray-300 rounded p-2 w-full">
            </div>
            <button type="submit" name="updateProfile" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="return confirm('Are you sure you want to update profile?');">Update Profile</button>
        </form>

        <form method="GET" action="profile.php" class="mt-4 text-center">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700" onclick="return confirm('Are you sure you want to go back?');">Back to Profile</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'UTS';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, email, age, dob, address, gender, hobbies, photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $age, $dob, $address, $gender, $hobbies, $photo);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
        }

        .profile-container {
            margin-top: 50px;
            margin-bottom: 50px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="login-box">
            <h1>Your Profile</h1>

            <div class="form-group">
                <?php if ($photo): ?>
                    <img src="<?php echo $photo; ?>" alt="Profile Photo" class="profile-photo">
                <?php else: ?>
                    <img src="photos/default.png" alt="Default Photo" class="profile-photo">
                <?php endif; ?>
            </div>

            <p><strong>Username:</strong> <?php echo $username; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Age:</strong> <?php echo $age; ?></p>
            <p><strong>Date of Birth:</strong> <?php echo $dob; ?></p>
            <p><strong>Address:</strong> <?php echo $address; ?></p>
            <p><strong>Gender:</strong> <?php echo $gender; ?></p>
            <p><strong>Hobbies:</strong> <?php echo $hobbies; ?></p>

            <form method="GET" action="edit_profile.php">
                <button type="submit" class="blue-button">Edit Profile</button>
            </form>

            <form method="POST" action="index.php">
                <button type="submit" name="logout" class="red-button">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>

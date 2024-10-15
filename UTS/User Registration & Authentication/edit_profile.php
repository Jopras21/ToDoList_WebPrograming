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

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['updateProfile'])) {
    $new_username = clean_input($_POST['username']);
    $new_email = clean_input($_POST['email']);
    $new_age = clean_input($_POST['age']);
    $new_dob = clean_input($_POST['dob']);
    $new_address = clean_input($_POST['address']);
    $new_gender = clean_input($_POST['gender']);
    $new_hobbies = clean_input($_POST['hobbies']);
    $new_password = clean_input($_POST['password']);
    $photo_file = $_FILES['photo'];

    if ($photo_file['size'] > 0) {
        $target_dir = "photos/";
        $target_file = $target_dir . basename($photo_file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($photo_file["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($photo_file["tmp_name"], $target_file)) {
                $photo = $target_file;
            } else {
                echo "<script>alert('Error uploading photo.');</script>";
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, age=?, dob=?, address=?, gender=?, hobbies=?, password=?, photo=? WHERE id=?");
        $stmt->bind_param("ssissssssi", $new_username, $new_email, $new_age, $new_dob, $new_address, $new_gender, $new_hobbies, $hashed_password, $photo, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, age=?, dob=?, address=?, gender=?, hobbies=?, photo=? WHERE id=?");
        $stmt->bind_param("ssisssssi", $new_username, $new_email, $new_age, $new_dob, $new_address, $new_gender, $new_hobbies, $photo, $user_id);
    }
    
    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully.');</script>";
        header("Location: profile.php");
        exit();
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
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
            <h1>Edit Profile</h1>

            <form method="POST" action="edit_profile.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" value="<?php echo $username; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo $email; ?>" required>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" name="age" value="<?php echo $age; ?>" required>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" name="dob" value="<?php echo $dob; ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" name="address" value="<?php echo $address; ?>" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select name="gender" required>
                        <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="hobbies">Hobbies:</label>
                    <input type="text" name="hobbies" value="<?php echo $hobbies; ?>">
                </div>
                <div class="form-group">
                    <label for="photo">Profile Photo:</label>
                    <input type="file" name="photo" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current):</label>
                    <input type="password" name="password" placeholder="New password">
                </div>
                <button type="submit" name="updateProfile" class="blue-button">Update Profile</button>
            </form>

            <form method="GET" action="profile.php">
                <button type="submit" class="green-button">Back to Profile</button>
            </form>
        </div>
    </div>
</body>
</html>

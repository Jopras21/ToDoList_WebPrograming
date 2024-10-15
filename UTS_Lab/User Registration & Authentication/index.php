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

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['signup'])) {
    $username = clean_input($_POST['username']);
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);

    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "<script>showNotification('Email already registered!');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();
        $stmt->close();

        echo "<script>showNotification('Registration successful! You can now log in.');</script>";
    }

    $checkEmail->close();
}

if (isset($_POST['login'])) {
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();
    
    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        echo "<script>showNotification('Login successful!');</script>";
    } else {
        echo "<script>showNotification('Invalid login credentials!');</script>";
    }
    
    $stmt->close();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication System</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleSignUpForm() {
            var signupForm = document.getElementById("signupForm");
            if (signupForm.style.display === "none") {
                signupForm.style.display = "block";
            } else {
                signupForm.style.display = "none";
            }
        }

        function showNotification(message) {
            var notificationContainer = document.getElementById('notification-container');
            
            var notification = document.createElement('div');
            notification.className = 'notification';
            notification.innerHTML = `
                <span>${message}</span>
                <button class="close-btn" onclick="closeNotification(this)">×</button>
            `;
            
            notificationContainer.appendChild(notification);

            setTimeout(function() {
                notification.classList.add('show');
            }, 100);

            setTimeout(function() {
                closeNotification(notification.querySelector('.close-btn'));
            }, 5000);
        }

        function closeNotification(btn) {
            var notification = btn.parentElement;
            notification.classList.remove('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }
    </script>
</head>
<body>
    <div id="notification-container"></div>

    <div class="login-container">
        <div class="login-box">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <h1>Sign in</h1>
                <p>Stay updated on your professional world</p>

                <form method="POST" action="">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email or Phone" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" name="login" class="blue-button">Sign in</button>
                </form>

                <p>Don't have an account? <button class="blue-button" onclick="toggleSignUpForm()">Sign up here</button></p>

                <div id="signupForm" style="display: none;">
                    <h1>Sign Up</h1>
                    <form method="POST" action="">
                        <div class="form-group">
                            <input type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" name="signup" class="blue-button">Sign up</button>
                    </form>
                </div>

            <?php else: ?>
                <h1>Welcome!</h1>
                <p>You are logged in.</p>

                <form method="GET" action="profile.php">
                    <button type="submit" class="blue-button">View Profile</button>
                </form>

                <form method="POST" action="">
                    <button type="submit" name="logout" class="red-button">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html
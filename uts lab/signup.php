<?php
session_start();

require 'connect.php'; 

$error_message = '';

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $error_message = "Email sudah terdaftar"; 
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $password])) {
            header("Location: login.php?signup=success");
            exit();
        } else {
            $error_message = "Pendaftaran gagal. Silakan coba lagi."; 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 p-6 rounded shadow-md w-96">
        <?php if ($error_message): ?>
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <h1 class="text-2xl font-bold mb-4 text-center text-white">Sign Up</h1>

        <form method="POST" action="signup.php">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-300">Username:</label>
                <input type="text" name="username" placeholder="username" required class="border border-gray-600 bg-gray-700 text-white rounded p-2 w-full" autocomplete="off">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-300">Email:</label>
                <input type="email" name="email" placeholder="email" required class="border border-gray-600 bg-gray-700 text-white rounded p-2 w-full" autocomplete="off">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-300">Password:</label>
                <input type="password" name="password" placeholder="password" required class="border border-gray-600 bg-gray-700 text-white rounded p-2 w-full" autocomplete="new-password">
            </div>
            <button type="submit" name="signup" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Sign up</button>
        </form>
        <p class="mt-4 text-gray-300">Already have an account? <a href="login.php" class="text-blue-400 hover:underline">Log in here</a></p>
    </div>
</body>
</html>

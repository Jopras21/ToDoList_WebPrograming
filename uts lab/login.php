<?php
session_start();

require 'connect.php'; 

$error_message = ''; 

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $error_message = "Email belum terdaftar."; 
    } elseif (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id']; 
        header("Location: index.php"); 
        exit();
    } else {
        $error_message = "Email atau password salah."; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-6 rounded shadow-md w-96"> 
            <?php if ($error_message): ?>
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>
            <h1 class="text-2xl font-bold mb-6 text-center">Sign in</h1>
            <form method="POST" action="">
                <div class="mb-4">
                    <input type="email" name="email" placeholder="Email or Phone" required class="border border-gray-300 p-2 w-full rounded" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="mb-4">
                    <input type="password" name="password" placeholder="Password" required class="border border-gray-300 p-2 w-full rounded" autocomplete="new-password">
                </div>
                <button type="submit" name="login" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">Sign in</button>
            </form>
            <p class="mt-4 text-center">Don't have an account? <a href="signup.php" class="text-blue-500 hover:underline">Sign up here</a></p>
        </div>
    </div>
</body>
</html>

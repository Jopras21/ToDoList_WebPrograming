<?php
session_start();
require 'connect.php'; 

$random_number = null;
$error_message = '';
$success_message = '';

if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $random_number = rand(100000, 999999);
        $_SESSION['reset_random_number'] = $random_number;
        $_SESSION['reset_email'] = $email;
        $_SESSION['user_id'] = $user['id']; 

        $success_message = "Masukkan angka berikut untuk melanjutkan: $random_number";
    } else {
        $error_message = "Email tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 p-6 rounded-lg shadow-md w-96">
        <h1 class="text-3xl font-bold mb-4 text-center text-white">Lupa Password</h1>

        <?php if ($error_message): ?>
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                <?= htmlspecialchars($success_message) ?>
            </div>
            <form method="POST" action="reset_password.php" class="mt-4">
                <div class="mb-4">
                    <label for="random_number" class="block text-sm font-medium text-gray-300">Masukkan Angka:</label>
                    <input type="text" name="random_number" placeholder="Angka yang ditampilkan" required class="border border-gray-700 bg-gray-800 text-white rounded p-2 w-full" autocomplete="off">
                </div>
                <button type="submit" name="verify_number" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Verifikasi</button>
            </form>
        <?php else: ?>
            <form method="POST" action="forgot_password.php">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300">Email:</label>
                    <input type="email" name="email" placeholder="Email" required class="border border-gray-700 bg-gray-800 text-white rounded p-2 w-full" autocomplete="on">
                </div>
                <button type="submit" name="forgot_password" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim Link Reset</button>
            </form>
        <?php endif; ?>
        <a href="login.php" class="mt-4 inline-block text-blue-400 hover:underline text-center">Back to Login</a>
    </div>
</body>
</html>

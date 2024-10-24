<?php
session_start();
require 'connect.php';

$error_message = '';
$success_message = '';

if (isset($_POST['verify_number'])) {
    $input_number = $_POST['random_number'];
    if ($input_number == $_SESSION['reset_random_number']) {
        $success_message = "Verifikasi berhasil. Silakan lanjutkan untuk mengatur ulang password.";
    } else {
        $random_number = rand(100000, 999999);
        $_SESSION['reset_random_number'] = $random_number; 
        $error_message = "Angka verifikasi salah. Angka baru telah dihasilkan: $random_number";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Angka</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 p-6 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-4 text-center text-white">Verifikasi Angka</h1>

        <?php if ($error_message): ?>
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                <?= htmlspecialchars($success_message) ?>
            </div>
            <form method="POST" action="update_password.php" class="mt-4">
                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-medium text-gray-300">Password Baru:</label>
                    <input type="password" name="new_password" required class="border border-gray-600 bg-gray-700 text-white rounded p-2 w-full">
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-300">Konfirmasi Password:</label>
                    <input type="password" name="confirm_password" required class="border border-gray-600 bg-gray-700 text-white rounded p-2 w-full">
                </div>
                <button type="submit" name="reset_password" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Atur Ulang Password</button>
            </form>
        <a href="login.php" class="mt-4 inline-block text-blue-400 hover:underline text-center">Back to Login</a>
        <?php else: ?>
            <form method="POST" action="reset_password.php" class="mt-4">
                <div class="mb-4">
                    <label for="random_number" class="block text-sm font-medium text-gray-300">Masukkan Angka:</label>
                    <input type="text" name="random_number" placeholder="Angka yang ditampilkan" required class="border border-gray-600 bg-gray-700 text-white rounded p-2 w-full">
                </div>
                <button type="submit" name="verify_number" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Verifikasi</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
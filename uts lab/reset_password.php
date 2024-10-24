<?php
session_start();
require 'connect.php';

$error = '';
$success = '';

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_random_number'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$stored_random_number = $_SESSION['reset_random_number'];

if (isset($_POST['verify_number'])) {
    $input_random_number = $_POST['random_number'];

    if ($input_random_number == $stored_random_number) {
        $verification_success = true;
    } else {
        $error = "Angka verifikasi salah.";
    }
}

if (isset($_POST['resetPassword'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed_password, $email]);

        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_random_number']);

        header("Location: login.php");
        exit();
    } else {
        $error = "Password tidak cocok. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 max-w-lg">
        <h1 class="text-2xl font-bold mb-4 text-center">Reset Password</h1>

        <?php if ($error): ?>
            <div class="text-red-500 mb-4 text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($verification_success)): ?>
            <form method="POST" action="reset_password.php" class="bg-white p-6 rounded shadow-md mt-4">
                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru:</label>
                    <input type="password" name="new_password" required class="border border-gray-300 rounded p-2 w-full">
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Konfirmasi Password:</label>
                    <input type="password" name="confirm_password" required class="border border-gray-300 rounded p-2 w-full">
                </div>
                <button type="submit" name="resetPassword" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Reset Password</button>
            </form>
        <?php else: ?>
            <form method="POST" action="reset_password.php" class="bg-white p-6 rounded shadow-md">
                <div class="mb-4">
                    <label for="random_number" class="block text-sm font-medium text-gray-700">Masukkan Angka Verifikasi:</label>
                    <input type="text" name="random_number" required class="border border-gray-300 rounded p-2 w-full">
                </div>
                <button type="submit" name="verify_number" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Verifikasi</button>
            </form>
        <?php endif; ?>

        <form method="GET" action="login.php" class="mt-4 text-center">
            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">Kembali ke Halaman Login</button>
        </form>
    </div>
</body>
</html>
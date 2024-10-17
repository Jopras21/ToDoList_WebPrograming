<?php
session_start();
require 'connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['todo_id'])) {
    $todo_id = $_GET['todo_id'];
    $stmt = $pdo->prepare("SELECT * FROM todo_lists WHERE id = ? AND user_id = ?");
    $stmt->execute([$todo_id, $_SESSION['user_id']]);
    $todo = $stmt->fetch();

    if (!$todo) {
        die("To-Do not found.");
    }
} else {
    die("No To-Do ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View To-Do</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md">
        <h1 class="text-3xl font-bold mb-4 text-gray-800"><?= htmlspecialchars($todo['title']) ?></h1>
        <p class="text-gray-600 mb-2"><strong>Description:</strong> <?= htmlspecialchars($todo['description']) ?></p>
        <p class="text-gray-600 mb-4"><strong>Created at:</strong> <?= htmlspecialchars($todo['created_at']) ?></p>
        <a href="index.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">Back to Dashboard</a>
    </div>
</body>
</html>

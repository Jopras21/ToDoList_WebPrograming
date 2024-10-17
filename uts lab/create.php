<?php
session_start();

require 'connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['todoTitle'] ?? '';
    $description = $_POST['todoDescription'] ?? '';

    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO todo_lists (title, description, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $_SESSION['user_id']]); 
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans bg-gray-100">
    <div class="create-todo p-5 m-5 bg-white rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-700">Create New To-Do List</h2>
        <form id="createTodoForm" class="mt-4" method="POST">
            <div class="mb-4">
                <label for="todoTitle" class="block text-gray-700">To-Do Title</label>
                <input type="text" id="todoTitle" name="todoTitle" class="w-full p-2 border border-gray-300 rounded" placeholder="Enter to-do title" required maxlength="100">
            </div>
            <div class="mb-4">
                <label for="todoDescription" class="block text-gray-700">Description</label>
                <textarea id="todoDescription" name="todoDescription" class="w-full p-2 border border-gray-300 rounded" placeholder="Enter description" maxlength="500"></textarea>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700" aria-label="Add To-Do">Add To-Do</button>
        </form>
    </div>
</body>
</html>

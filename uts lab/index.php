<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); 
}

require 'connect.php'; 

$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';
$status_filter = isset($_POST['status_filter']) ? $_POST['status_filter'] : 'not_done';

$query = "SELECT * FROM todo_lists WHERE user_id = ?";

if ($search_query) {
    $query .= " AND title LIKE ?";
}

if ($status_filter === 'done') {
    $query .= " AND is_done = 1";
} elseif ($status_filter === 'not_done') {
    $query .= " AND is_done = 0";
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$params = [$_SESSION['user_id']];

if ($search_query) {
    $params[] = '%' . $search_query . '%';
}

$stmt->execute($params);
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['todo_id'])) {
    $todo_id = $_POST['todo_id'];

    $stmt = $pdo->prepare("DELETE FROM todo_lists WHERE id = ? AND user_id = ?");
    $stmt->execute([$todo_id, $_SESSION['user_id']]);

    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['done_id'])) {
    $done_id = $_POST['done_id'];

    $stmt = $pdo->prepare("UPDATE todo_lists SET is_done = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$done_id, $_SESSION['user_id']]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">To-Do List</h1>

        <a href="profile.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 mb-4">Profile</a>

        <form action="" method="post" class="mb-4 mt-4">
            <input type="text" name="search_query" placeholder="Search..." value="<?= htmlspecialchars($search_query) ?>" class="border border-gray-300 rounded p-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Search</button>
        </form>

        <div class="flex items-center justify-between mb-4"> 
            <a href="create.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Create New To-Do</a>

            <form action="" method="post" class="flex items-center">
                <label for="status_filter" class="mr-2">Show:</label>
                <select name="status_filter" id="status_filter" class="border border-gray-300 rounded p-2" onchange="this.form.submit()">
                    <option value="done" <?= $status_filter === 'done' ? 'selected' : '' ?>>Done</option>
                    <option value="not_done" <?= $status_filter === 'not_done' ? 'selected' : '' ?>>Not Done</option>
                    <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All</option>
                </select>
            </form>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($todos as $todo): ?>
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"> 
                    <h2 class="text-xl font-semibold"><?= htmlspecialchars($todo['title']) ?></h2>
                    <p class="text-gray-600"><?= htmlspecialchars($todo['description']) ?></p>
                    <div class="mt-4 flex justify-between"> 
                        <form action="" method="post" class="inline">
                            <input type="hidden" name="done_id" value="<?= $todo['id'] ?>">
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">Done</button>
                        </form>
                        <form action="" method="post" class="inline">
                            <input type="hidden" name="todo_id" value="<?= $todo['id'] ?>">
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this to-do?');">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

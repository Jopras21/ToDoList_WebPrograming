<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $todoTitle = $_POST['todoTitle'];
    $todoDescription = $_POST['todoDescription'];
    header('Location: index.php');
    exit();
} else {
    header('Location: login.php');
    exit();
}
?>

<?php
$host = 'sql304.infinityfree.com';
$dbname = 'if0_37529085_utslab';
$username = 'if0_37529085';
$password = 'L5LhIQkdjCBR'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

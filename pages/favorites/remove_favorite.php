<?php
require 'config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Nincs bejelentkezve!");
}

$user_id = $_SESSION["user_id"];

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["favorite_id"])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }
    
    $favorite_id = $_POST["favorite_id"];
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND favorite_id = ?");
    $stmt->execute([$user_id, $favorite_id]);
    
    if ($stmt->rowCount() > 0) {
        header("Location: favorites.php?success=removed");
    } else {
        header("Location: favorites.php?error=not_found");
    }
    exit();
}
?>

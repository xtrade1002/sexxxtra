<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Nincs bejelentkezve.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['image_id'])) {
    $image_id = $_POST['image_id'];
    
    // Ellenőrizzük, hogy a kép valóban a felhasználóhoz tartozik
    $stmt = $pdo->prepare("SELECT id FROM images WHERE id = ? AND user_id = ?");
    $stmt->execute([$image_id, $user_id]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($image) {
        $stmt = $pdo->prepare("UPDATE images SET archived = 1 WHERE id = ?");
        $stmt->execute([$image_id]);
        header("Location: profile.php?success=image_archived");
        exit();
    } else {
        die("Nem található a kép, vagy nincs jogosultságod archiválni.");
    }
}
?>

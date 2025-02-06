<?php
require 'config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Nincs bejelentkezve!");
}

$user_id = $_SESSION["user_id"];

// Napi megtekintések
$stmt = $pdo->prepare("SELECT COUNT(*) FROM profile_views WHERE user_id = ? AND DATE(view_date) = CURDATE()");
$stmt->execute([$user_id]);
$views_today = $stmt->fetchColumn();

// Heti megtekintések
$stmt = $pdo->prepare("SELECT COUNT(*) FROM profile_views WHERE user_id = ? AND view_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$stmt->execute([$user_id]);
$views_week = $stmt->fetchColumn();

// Havi megtekintések
$stmt = $pdo->prepare("SELECT COUNT(*) FROM profile_views WHERE user_id = ? AND view_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
$stmt->execute([$user_id]);
$views_month = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilmegtekintések</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <div class="profile-views-container">
        <h2>Profilmegtekintések</h2>
        <p>📅 **Ma**: <strong><?= $views_today ?></strong></p>
        <p>📆 **Elmúlt 7 nap**: <strong><?= $views_week ?></strong></p>
        <p>📊 **Elmúlt 30 nap**: <strong><?= $views_month ?></strong></p>
    </div>

</body>
</html>
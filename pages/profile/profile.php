<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM service_providers WHERE id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    die("A profil nem található!");
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($profile['username']); ?> profilja</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body>

    <div class="profile-container">
        <nav class="sidebar">
            <ul>
                <li><a href="update_profile.php">Beállítások</a></li>
                <li><a href="profile_images.php">Képek</a></li>
                <li><a href="availability.php">Elérhetőség</a></li>
                <li><a href="pricing.php">Árak</a></li>
                <li><a href="logout.php">Kijelentkezés</a></li>
            </ul>
        </nav>

        <div class="profile-details">
            <h2><?php echo htmlspecialchars($profile['username']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($profile['email']); ?></p>
            <p><strong>Telefonszám:</strong> <?php echo htmlspecialchars($profile['phone'] ?? 'Nincs megadva'); ?></p>
            <p><strong>Leírás:</strong> <?php echo nl2br(htmlspecialchars($profile['description'] ?? 'Nincs megadva')); ?></p>
            
            <div class="profile-image">
                <img src="<?php echo !empty($profile['profile_picture']) ? htmlspecialchars($profile['profile_picture']) : 'assets/pictures/default.jpg'; ?>" alt="Profilkép">
            </div>
        </div>
    </div>

</body>
</html>

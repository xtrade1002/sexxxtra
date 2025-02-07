<?php
session_start();
require __DIR__ . '/../../config.php';

if (!isset($_GET['id'])) {
    die("\Érvénytelen profil azonosító.");
}

$user_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM service_providers WHERE id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    die("A profil nem található!");
}

// Ellenőrizzük, hogy a profile_images tábla létezik-e
$table_check = $pdo->query("SHOW TABLES LIKE 'profile_images'")->rowCount();
if ($table_check) {
    // Képek lekérése, ha a tábla létezik
    $img_stmt = $pdo->prepare("SELECT image_path FROM profile_images WHERE user_id = ?");
    $img_stmt->execute([$user_id]);
    $images = $img_stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $images = [];
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($profile['username']); ?> profilja</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/profile.css">
    <link rel="stylesheet" href="../../assets/css/profile_view.css">
    <script>
        function changeMainImage(src) {
            document.getElementById("main-profile-image").src = src;
        }
    </script>
</head>
<body>

<div class="profile-container">
    <div class="profile-header">
        <h1><?php echo htmlspecialchars($profile['username']); ?></h1>
        <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($profile['location']); ?></p>
    </div>

    <div class="profile-gallery">
        <div class="main-image">
            <img id="main-profile-image" src="<?php echo htmlspecialchars($profile['profile_picture'] ?? '../../assets/pictures/default.jpg'); ?>" alt="Profilkép">
        </div>
        <div class="thumbnail-gallery">
            <?php foreach ($images as $image): ?>
                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Profilkép" onclick="changeMainImage(this.src)">
            <?php endforeach; ?>
        </div>
    </div>

    <div class="profile-details">
        <h2>Rólam</h2>
        <p><?php echo nl2br(htmlspecialchars($profile['description'] ?? 'Nincs megadva')); ?></p>

        <h2>Szolgáltatások és árak</h2>
        <p><?php echo nl2br(htmlspecialchars($profile['services'] ?? 'Nincs megadva')); ?></p>

        <h2>Elérhetőség</h2>
        <ul>
            <li>Hétfő: <?php echo htmlspecialchars($profile['monday_availability'] ?? 'Nincs megadva'); ?></li>
            <li>Kedd: <?php echo htmlspecialchars($profile['tuesday_availability'] ?? 'Nincs megadva'); ?></li>
            <li>Szerda: <?php echo htmlspecialchars($profile['wednesday_availability'] ?? 'Nincs megadva'); ?></li>
            <li>Csütörtök: <?php echo htmlspecialchars($profile['thursday_availability'] ?? 'Nincs megadva'); ?></li>
            <li>Péntek: <?php echo htmlspecialchars($profile['friday_availability'] ?? 'Nincs megadva'); ?></li>
            <li>Szombat: <?php echo htmlspecialchars($profile['saturday_availability'] ?? 'Nincs megadva'); ?></li>
            <li>Vasárnap: <?php echo htmlspecialchars($profile['sunday_availability'] ?? 'Nincs megadva'); ?></li>
        </ul>

        <h2>Kapcsolatfelvétel</h2>
        <button class="message-btn">Üzenet küldése</button>
    </div>
</div>

</body>
</html>

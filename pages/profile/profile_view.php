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

$table_check = $pdo->query("SHOW TABLES LIKE 'profile_images'")->rowCount();
if ($table_check) {
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
    <link rel="stylesheet" href="../../assets/css/profle_view.css">
    <script>
        function changeMainImage(src) {
            document.getElementById("main-profile-image").src = src;
        }
    </script>
</head>
<body>

<div class="profile-wrapper">
    <div class="profile-container">
        <div class="profile-left">
            <div class="profile-image">
                <img id="main-profile-image" src="<?php echo htmlspecialchars($profile['profile_picture'] ?? '../../assets/pictures/default.jpg'); ?>" alt="Profilkép">
            </div>
            <div class="thumbnail-container">
        <?php
        $imageCount = 0;
        foreach ($images as $image):
            if ($imageCount < 6): ?>
                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Profilkép" onclick="changeMainImage(this.src)">
        <?php 
            $imageCount++;
            endif;
        endforeach;
        ?>
    </div>

        <?php if (count($images) > 6): ?>
            <button class="show-more">Mutasd a többit</button>
        <?php endif; ?>

        <div class="profile-description">
            <h2>Bemutatkozás</h2>
            <p><?php echo nl2br(htmlspecialchars($profile['description'] ?? 'Nincs megadva')); ?></p>
        </div>
        <div class="services">
            <h2>Szolgáltatások</h2>
            <ul>
                <?php 
                $services = explode(",", $profile['services'] ?? '');
                foreach ($services as $service): ?>
                    <li><img src="../../assets/pictures/ok.png" alt="Pipa" class="checkmark-icon">
                    <?php echo htmlspecialchars($service); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="profile-right">
    <div class="contact-info">
        <h2 style="color:white;">Név</h2>
            <p style="display: flex; align-items: center;justify-content: center;">
                <img src="../../assets/pictures/phone.png" alt="Telefon" class="contact-icon">
                <span><?php echo htmlspecialchars($profile['phone_number'] ?? 'Nincs megadva'); ?></span>
            </p>

            
            <div class="social-icons">
                <?php if (!empty($profile['whatsapp'])): ?>
                    <img src="../../assets/picture/whatsapp.png" alt="WhatsApp">
                <?php endif; ?>
                <?php if (!empty($profile['viber'])): ?>
                    <img src="../../assets/picture/viber.png" alt="Viber">
                <?php endif; ?>
                <?php if (!empty($profile['telegram'])): ?>
                    <img src="../../assets/picture/telegram.png" alt="Telegram">
                <?php endif; ?>
            </div>
        </div>

        <div class="user-details">
        <h2>Adataim</h2>
        <ul>
            <li><strong>Magasság: </strong> <?php echo htmlspecialchars($profile['height'] ?? 'Nincs megadva'); ?></li>
            <li><strong>Szemszín: </strong> <?php echo htmlspecialchars($profile['eye_color'] ?? 'Nincs megadva'); ?></li>
            <li><strong>Hajszín: </strong> <?php echo htmlspecialchars($profile['hair_color'] ?? 'Nincs megadva'); ?></li>
            <li><strong>Testalkat: </strong> <?php echo htmlspecialchars($profile['body_type'] ?? 'Nincs megadva'); ?></li>
            <li><strong>Etnikum: </strong> <?php echo htmlspecialchars($profile['ethnicity'] ?? 'Nincs megadva'); ?></li>
            <li><strong>Szexualitás: </strong> <?php echo htmlspecialchars($profile['sexuality'] ?? 'Nincs megadva'); ?></li>
            <li><strong>Kinek nyújt szolgáltatást: </strong> <?php echo htmlspecialchars($profile['services_for'] ?? 'Nincs megadva'); ?></li>
        </ul>
</div>


        <div class="availability">
            <h2>Elérhetőség</h2>
            <ul>
                <li><strong>Hétfő: </strong><?php echo htmlspecialchars($profile['monday_availability'] ?? 'Nincs megadva'); ?></li>
                <li><strong>Kedd: </strong><?php echo htmlspecialchars($profile['tuesday_availability'] ?? 'Nincs megadva'); ?></li>
                <li><strong>Szerda:</strong> <?php echo htmlspecialchars($profile['wednesday_availability'] ?? 'Nincs megadva'); ?></li>
                <li><strong>Csütörtök:</strong> <?php echo htmlspecialchars($profile['thursday_availability'] ?? 'Nincs megadva'); ?></li>
                <li><strong>Péntek: </strong><?php echo htmlspecialchars($profile['friday_availability'] ?? 'Nincs megadva'); ?></li>
                <li><strong>Szombat: </strong><?php echo htmlspecialchars($profile['saturday_availability'] ?? 'Nincs megadva'); ?></li>
                <li><strong>Vasárnap: </strong><?php echo htmlspecialchars($profile['sunday_availability'] ?? 'Nincs megadva'); ?></li>
            </ul>
        </div>
        <div class="pricing">
            <h2>Árak</h2>
            <p>1 óra: <?php echo htmlspecialchars($profile['price_one_hour'] ?? 'Nincs megadva'); ?></p>
            <p>2 óra: <?php echo htmlspecialchars($profile['price_two_hours'] ?? 'Nincs megadva'); ?></p>
            <p>Egész éjszaka: <?php echo htmlspecialchars($profile['price_night'] ?? 'Nincs megadva'); ?></p>
        </div>
    </div>
</div>
</div>




<script>
document.addEventListener("DOMContentLoaded", function () {
    const showMoreBtn = document.querySelector(".show-more");
    const hiddenImages = document.querySelectorAll(".thumbnail-container img.hidden");

    if (showMoreBtn) {
        showMoreBtn.addEventListener("click", function () {
            hiddenImages.forEach(img => img.classList.remove("hidden"));
            showMoreBtn.style.display = "none"; // Gomb eltüntetése kattintás után
        });
    }
});
</script>
</body>
</html>

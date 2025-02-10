<?php
session_start();
require __DIR__ . '/../../config.php';
require_once BASE_PATH . 'includes/header.php';



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
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/profle_view.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">

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
        <div class="location-favorite-container">
    <div class="location">
        <img src="../../assets/pictures/location.png" alt="Hely">
        <span><?php echo htmlspecialchars($profile['city'] ?? 'Nincs megadva'); ?></span>
    </div>
    <button class="favorite favorite-btn">
        <img src="../../assets/pictures/like.png" alt="Mentés">
        <span class="favorite-text">Mentés</span>
    </button>
</div>


            <p style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                <img src="../../assets/pictures/phone.png" alt="Telefon" class="contact-icon phone-number">
                <a href="tel:<?php echo htmlspecialchars($profile['phone_number'] ?? ''); ?>" class="phone-link">
                    <?php echo htmlspecialchars($profile['phone_number'] ?? 'Nincs megadva'); ?>
                </a>
            </p>
            
            <div class="social-icons">
                <?php if (!empty($profile['whatsapp'])): ?>
                    <a href="https://wa.me/<?php echo htmlspecialchars($profile['phone_number']); ?>" target="_blank">
                        <img src="../../assets/pictures/whatsapp.png" alt="WhatsApp">
                    </a>
                <?php endif; ?>
                <?php if (!empty($profile['viber'])): ?>
                    <a href="viber://chat?number=<?php echo htmlspecialchars($profile['phone_number']); ?>" target="_blank">
                        <img src="../../assets/pictures/viber.png" alt="Viber">
                    </a>
                <?php endif; ?>
                <?php if (!empty($profile['telegram'])): ?>
                    <a href="https://t.me/<?php echo htmlspecialchars($profile['telegram']); ?>" target="_blank">
                        <img src="../../assets/pictures/telegram.png" alt="Telegram">
                    </a>
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
function toggleFavorite(button, userId) {
    let icon = document.getElementById("fav-icon-" + userId);
    
    if (icon.src.includes("like.png")) {
        icon.src = "../../assets/pictures/liked.png"; // Ha még nincs hozzáadva
    } else {
        icon.src = "../../assets/pictures/like.png"; // Ha már hozzá van adva
    }

    // AJAX kérés küldése a szerverre, hogy elmentsük az állapotot
    fetch("../../pages/profile/toggle_favorite.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "user_id=" + userId
    }).then(response => response.text())
      .then(data => console.log(data));
}
</script>

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
<?php require_once BASE_PATH . 'includes/footer.php'; ?>
</body>
</html>

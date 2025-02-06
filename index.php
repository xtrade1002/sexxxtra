<?php
require_once 'includes/header.php';
require_once 'pages/search/search.php';
require_once 'config.php';

// Lapozáshoz szükséges változók
$profiles_per_page = 48; // 6 oszlop * 8 sor = 48 profil oldalanként
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $profiles_per_page;

// Lekérdezzük a profilokat lapozással
$stmt = $pdo->prepare("SELECT id, username, profile_picture, location, age FROM service_providers ORDER BY RAND() LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $profiles_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Megszámoljuk az összes profilt
$total_profiles_stmt = $pdo->query("SELECT COUNT(*) FROM service_providers");
$total_profiles = $total_profiles_stmt->fetchColumn();
$total_pages = ceil($total_profiles / $profiles_per_page);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Szexpartner kereső kiemelt profilokkal és részletes szűrőkkel.">
    <title>Kezdőlap - Szexpartner Kereső</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="assets/css/search.css">
</head>
<body>

<section class="featured">
    <h2 class="section-title">Kiemelt Profilok</h2>
    <div class="profile-grid">
        <?php foreach ($profiles as $profile): ?>
            <div class="profile-card">
                <img src="<?php echo !empty($profile['profile_picture']) ? htmlspecialchars($profile['profile_picture']) : 'assets/pictures/default.jpg'; ?>" alt="Profilkép">
                <h3><?php echo htmlspecialchars($profile['username']); ?></h3>
                <p><?php echo htmlspecialchars($profile['location'] ?? 'Nincs megadva'); ?>, <?php echo htmlspecialchars($profile['age'] ?? 'Életkor nincs megadva'); ?> éves</p>
                <a href="profile.php?id=<?php echo $profile['id']; ?>" class="profile-btn">Megnézem</a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Lapozás -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>" class="prev">Előző</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>" class="next">Következő</a>
        <?php endif; ?>
    </div>
</section>

</body>
</html>

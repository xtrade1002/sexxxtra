<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Nincs bejelentkezve.");
}

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }
    
    // Kapcsolódó adatok törlése
    $pdo->prepare("DELETE FROM favorites WHERE user_id = ? OR favorite_id = ?")->execute([$user_id, $user_id]);
    $pdo->prepare("DELETE FROM profile_views WHERE user_id = ?")->execute([$user_id]);
    $pdo->prepare("DELETE FROM availability WHERE provider_id = ?")->execute([$user_id]);
    $pdo->prepare("DELETE FROM pricing WHERE provider_id = ?")->execute([$user_id]);
    $pdo->prepare("DELETE FROM images WHERE user_id = ?")->execute([$user_id]);
    
    // Profil törlése
    $pdo->prepare("DELETE FROM service_providers WHERE id = ?")->execute([$user_id]);
    
    session_destroy();
    header("Location: index.php?success=profile_deleted");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Profil törlése</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <div class="delete-profile-container">
        <h2>Profil törlése</h2>
        <p>Biztosan törölni szeretnéd a profilodat? Ez az összes adatodat véglegesen eltávolítja!</p>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit">Végleges törlés</button>
        </form>
    </div>
</body>
</html>

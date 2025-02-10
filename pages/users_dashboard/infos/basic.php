<?php
session_start();
require_once '../../../config.php';
include '../../../../includes/header.php';

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../auth/login.php");
    exit();
}

// Felhasználói adatok lekérése
$user_id = $_SESSION["user_id"];
$stmt = $pdo->prepare("SELECT name, nationality, ethnicity, age, hair_color, eye_color, height, weight, clothing_size, shoe_size, breast_size, intimate_hair, smoking, alcohol FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alapadatok</title>
    <link rel="stylesheet" href="../../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../../assets/css/users_dashboard.css">
    <link rel="stylesheet" href="../../../../assets/css/basic.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Alapadatok</h2>
        <table class="user-info">
            <tr><th>Név:</th><td><?php echo htmlspecialchars($user["name"] ?? "Nincs megadva"); ?></td></tr>
            <tr><th>Nemzetiség:</th><td><?php echo htmlspecialchars($user["nationality"] ?? "Nincs megadva"); ?></td></tr>
            <tr><th>Etnikum:</th><td><?php echo htmlspecialchars($user["ethnicity"] ?? "Nincs megadva"); ?></td></tr>
            <tr><th>Életkor:</th><td><?php echo htmlspecialchars($user["age"] ?? "Nincs megadva"); ?> éves</td></tr>
            <tr><th>Hajszín:</th><td><?php echo htmlspecialchars($user["hair_color"] ?? "Nincs megadva"); ?></td></tr>
            <tr><th>Szemszín:</th><td><?php echo htmlspecialchars($user["eye_color"] ?? "Nincs megadva"); ?></td></tr>
            <tr><th>Magasság:</th><td><?php echo htmlspecialchars($user["height"] ?? "Nincs megadva"); ?> cm</td></tr>
            <tr><th>Súly:</th><td><?php echo htmlspecialchars($user["weight"] ?? "Nincs megadva"); ?> kg</td></tr>
            <tr><th>Ruhaméret:</th><td><?php echo htmlspecialchars($user["clothing_size"] ?? "Nincs megadva"); ?></td></tr>
            <tr><th>Cipőméret:</th><td><?php echo htmlspecialchars($user["shoe_size"] ?? "Nincs megadva"); ?></td></tr>
            <tr><th>Mellméret:</th><td><?php echo htmlspecialchars($user["breast_size"] ?? "Nincs megadva"); ?></td></tr>
            <tr><th>Intim szőrzet:</th><td><?php echo htmlspecialchars($user["intimate_hair"] ?? "Nincs megadva"); ?></td></tr>
            <tr><th>Dohányzás:</th><td><?php echo htmlspecialchars($user["smoking"] ?? "Nincs megadva"); ?></td></tr>
            <tr><th>Alkoholfogyasztás:</th><td><?php echo htmlspecialchars($user["alcohol"] ?? "Nincs megadva"); ?></td></tr>
        </table>
    </div>
    <?php include '../../../../includes/footer.php'; ?>
</body>
</html>

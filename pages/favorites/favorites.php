<?php
require 'config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Nincs bejelentkezve!");
}

$user_id = $_SESSION["user_id"];

// Új kedvenc hozzáadása
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["favorite_id"])) {
    $favorite_id = $_POST["favorite_id"];
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, favorite_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $favorite_id]);
    header("Location: favorites.php?success=added");
    exit();
}

// Kedvencek lekérdezése
$stmt = $pdo->prepare("SELECT users.id, users.username, users.email FROM favorites 
                       JOIN users ON favorites.favorite_id = users.id 
                       WHERE favorites.user_id = ?");
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kedvencek</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="favorites-container">
        <h2>Kedvencek</h2>
        <?php if (isset($_GET['success']) && $_GET['success'] == 'added'): ?>
            <p class="success-message">Felhasználó sikeresen hozzáadva a kedvencekhez!</p>
        <?php endif; ?>
        
        <table class="favorites-table">
            <tr>
                <th>Név</th>
                <th>Email</th>
                <th>Műveletek</th>
            </tr>
            <?php foreach ($favorites as $fav): ?>
            <tr>
                <td><?= htmlspecialchars($fav['username']); ?></td>
                <td><?= htmlspecialchars($fav['email']); ?></td>
                <td>
                    <form action="remove_favorite.php" method="POST">
                        <input type="hidden" name="favorite_id" value="<?= $fav['id']; ?>">
                        <button type="submit" class="remove-btn">Eltávolítás</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>

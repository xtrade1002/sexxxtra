<?php
require 'config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Nincs bejelentkezve!");
}

$user_id = $_SESSION["user_id"];

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Új kedvenc hozzáadása
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["favorite_id"])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }
    
    $favorite_id = $_POST["favorite_id"];
    
    $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND favorite_id = ?");
    $stmt->execute([$user_id, $favorite_id]);
    
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, favorite_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $favorite_id]);
    }
    header("Location: favorites.php?success=added");
    exit();
}

// Kedvencek eltávolítása
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_id"])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }
    
    $remove_id = $_POST["remove_id"];
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND favorite_id = ?");
    $stmt->execute([$user_id, $remove_id]);
    header("Location: favorites.php?success=removed");
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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="favorites-container">
    <h2>Kedvencek</h2>
    <table>
        <tr>
            <th>Felhasználónév</th>
            <th>Email</th>
            <th>Műveletek</th>
        </tr>
        <?php foreach ($favorites as $fav): ?>
        <tr>
            <td><?php echo htmlspecialchars($fav['username']); ?></td>
            <td><?php echo htmlspecialchars($fav['email']); ?></td>
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="remove_id" value="<?php echo $fav['id']; ?>">
                    <button type="submit">Eltávolítás</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
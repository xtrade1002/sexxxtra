<?php
require 'config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Nincs bejelentkezve!");
}

$user_id = $_SESSION["user_id"];

// Ha a felhasználó beküldte az űrlapot
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $display_name = trim($_POST["display_name"]);
    $nationality = $_POST["nationality"];
    $eye_color = $_POST["eye_color"];

    $stmt = $pdo->prepare("UPDATE users SET display_name = ?, nationality = ?, eye_color = ? WHERE id = ?");
    $stmt->execute([$display_name, $nationality, $eye_color, $user_id]);

    header("Location: profile.php?success=profile_updated");
    exit();
}

// Felhasználói adatok lekérése
$stmt = $pdo->prepare("SELECT display_name, nationality, eye_color FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil frissítése</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="profile-update-container">
        <h2>Profil frissítése</h2>
        <form action="update_profile.php" method="POST">
            <label for="display_name">Megjelenítendő név:</label>
            <input type="text" name="display_name" value="<?= htmlspecialchars($user['display_name']); ?>" required>

            <label for="nationality">Nemzetiség:</label>
            <select name="nationality">
                <option value="Magyar" <?= $user['nationality'] == "Magyar" ? "selected" : ""; ?>>Magyar</option>
                <option value="Német" <?= $user['nationality'] == "Német" ? "selected" : ""; ?>>Német</option>
                <option value="Francia" <?= $user['nationality'] == "Francia" ? "selected" : ""; ?>>Francia</option>
            </select>

            <label for="eye_color">Szemszín:</label>
            <select name="eye_color">
                <option value="Kék" <?= $user['eye_color'] == "Kék" ? "selected" : ""; ?>>Kék</option>
                <option value="Barna" <?= $user['eye_color'] == "Barna" ? "selected" : ""; ?>>Barna</option>
                <option value="Zöld" <?= $user['eye_color'] == "Zöld" ? "selected" : ""; ?>>Zöld</option>
            </select>

            <button type="submit">Mentés</button>
        </form>
    </div>

</body>
</html>

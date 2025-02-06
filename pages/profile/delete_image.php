<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Nincs bejelentkezve.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['image_id'])) {
    $image_id = $_POST['image_id'];
    
    // Ellenőrizzük, hogy a kép valóban a felhasználóhoz tartozik
    $stmt = $pdo->prepare("SELECT file_path FROM images WHERE id = ? AND user_id = ?");
    $stmt->execute([$image_id, $user_id]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($image) {
        unlink($image['file_path']); // Kép fájl törlése
        $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
        $stmt->execute([$image_id]);
        header("Location: profile.php?success=image_deleted");
        exit();
    } else {
        die("Nem található a kép, vagy nincs jogosultságod törölni.");
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Kép törlése</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="delete-image-container">
        <h2>Kép törlése</h2>
        <form method="POST" action="">
            <input type="hidden" name="image_id" value="<?php echo $_GET['image_id']; ?>">
            <button type="submit">Törlés</button>
        </form>
    </div>
</body>
</html>

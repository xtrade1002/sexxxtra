<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Nincs bejelentkezve.");
}

$user_id = $_SESSION['user_id'];

// Ellenőrizzük, hogy a felhasználónak van-e már profilképe
$stmt = $pdo->prepare("SELECT COUNT(*) FROM images WHERE user_id = ? AND is_profile = 1");
$stmt->execute([$user_id]);
$profile_image_count = $stmt->fetchColumn();

// Ellenőrizzük, hogy a felhasználó hány képet töltött már fel
$stmt = $pdo->prepare("SELECT COUNT(*) FROM images WHERE user_id = ? AND archived = 0");
$stmt->execute([$user_id]);
$image_count = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    if ($image_count >= 30) {
        die("Maximum 30 képet tölthetsz fel. Törölj egyet, hogy újat adhass hozzá.");
    }
    
    $image = $_FILES['image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
    $upload_dir = 'uploads/';

    if (!in_array($image['type'], $allowed_types)) {
        die("Csak JPG, PNG vagy WEBP formátum tölthető fel.");
    }

    $file_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $new_file_name = $upload_dir . uniqid('img_') . '.' . $file_ext;

    if (move_uploaded_file($image['tmp_name'], $new_file_name)) {
        $is_profile = ($profile_image_count == 0) ? 1 : 0; // Ha nincs profilkép, az első feltöltött kép legyen az
        $stmt = $pdo->prepare("INSERT INTO images (user_id, file_path, uploaded_at, is_profile) VALUES (?, ?, NOW(), ?)");
        $stmt->execute([$user_id, $new_file_name, $is_profile]);
        header("Location: profile.php?success=image_uploaded");
        exit();
    } else {
        die("Hiba történt a kép feltöltése során.");
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Kép feltöltése</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="upload-container">
        <h2>Kép feltöltése</h2>
        <p>Profilkép feltöltése kötelező. Maximum 30 kép tölthető fel (az archívumban bármennyi lehet).</p>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <button type="submit">Feltöltés</button>
        </form>
    </div>
</body>
</html>
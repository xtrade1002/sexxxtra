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
    
    if (!$image) {
        die("Nem található a kép, vagy nincs jogosultságod szerkeszteni.");
    }

    $image_path = $image['file_path'];

    if (isset($_POST['blur'])) {
        $img = imagecreatefromjpeg($image_path);
        for ($i = 0; $i < 3; $i++) {
            imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
        }
        imagejpeg($img, $image_path);
        imagedestroy($img);
    }

    if (isset($_POST['crop'])) {
        $crop_x = $_POST['crop_x'];
        $crop_y = $_POST['crop_y'];
        $crop_width = $_POST['crop_width'];
        $crop_height = $_POST['crop_height'];
        
        $img = imagecreatefromjpeg($image_path);
        $cropped_img = imagecrop($img, ['x' => $crop_x, 'y' => $crop_y, 'width' => $crop_width, 'height' => $crop_height]);
        if ($cropped_img) {
            imagejpeg($cropped_img, $image_path);
            imagedestroy($cropped_img);
        }
        imagedestroy($img);
    }
    
    header("Location: profile.php?success=image_edited");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Kép szerkesztése</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="edit-image-container">
        <h2>Kép szerkesztése</h2>
        <form method="POST" action="">
            <input type="hidden" name="image_id" value="<?php echo $_GET['image_id']; ?>">
            <label>Arc homályosítása:</label>
            <button type="submit" name="blur">Homályosítás</button>
            
            <label>Kép vágása:</label>
            <input type="number" name="crop_x" placeholder="X koordináta">
            <input type="number" name="crop_y" placeholder="Y koordináta">
            <input type="number" name="crop_width" placeholder="Szélesség">
            <input type="number" name="crop_height" placeholder="Magasság">
            <button type="submit" name="crop">Vágás</button>
        </form>
    </div>
</body>
</html>
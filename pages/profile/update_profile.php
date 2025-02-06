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
    
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $description = trim($_POST['description']);
    
    $stmt = $pdo->prepare("UPDATE service_providers SET username = ?, email = ?, phone = ?, description = ? WHERE id = ?");
    $stmt->execute([$username, $email, $phone, $description, $user_id]);
    
    header("Location: profile.php?success=updated");
    exit();
}

$stmt = $pdo->prepare("SELECT username, email, phone, description FROM service_providers WHERE id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Profil módosítása</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <div class="update-profile-container">
        <h2>Profil módosítása</h2>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label>Felhasználónév</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($profile['username']); ?>" required>
            
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($profile['email']); ?>" required>
            
            <label>Telefonszám</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($profile['phone']); ?>">
            
            <label>Leírás</label>
            <textarea name="description"><?php echo htmlspecialchars($profile['description']); ?></textarea>
            
            <button type="submit">Mentés</button>
        </form>
    </div>
</body>
</html>
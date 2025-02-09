<?php
require __DIR__ . '/../../config.php';
session_start();
session_regenerate_id(true);

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }

    $email = trim($_POST["email"]);
    
    // Ellenőrizzük, hogy az email létezik-e az adatbázisban
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
        $stmt->execute([$token, $expires, $email]);

        // Email küldése a felhasználónak
        $reset_link = "http://localhost/sexxxtra/pages/auth/reset_password.php?token=" . $token;
        $subject = "Jelszó visszaállítás";
        $message = "Kedves felhasználó!\n\nA jelszó visszaállításához kattints az alábbi linkre:\n\n$reset_link\n\nEz a link 1 órán belül lejár.";
        $headers = "From: no-reply@sexxxtra.com\r\nContent-Type: text/plain; charset=UTF-8";

        mail($email, $subject, $message, $headers);
    }

    $message = "A megadott email címre elküldtük a helyreállító linket!";
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelszó visszaállítása</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/reset_password.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-box">
        <div class="image-container"></div>
        <div class="form-container">
            <div class="close-btn" onclick="window.location.href='../../index.php'">&times;</div>
            <div class="logo">
                <img src="../../assets/pictures/sexxxtra_logo.png" alt="Sexxxtra Logo">
            </div>
            <h2 class="setbackpwd-text">Jelszó visszaállítása</h2>
            <?php if (!empty($message)): ?>
                <p class="success-msg"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit">Jelszó visszaállítása</button>
            </form>
            <a href="../auth/login.php">Vissza a bejelentkezéshez</a>
        </div>
    </div>
</div>
</body>
</html>

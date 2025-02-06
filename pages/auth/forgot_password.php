<?php
require 'config.php';
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }
    
    $email = trim($_POST['email']);
    
    $stmt = $pdo->prepare("SELECT id FROM general_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $reset_token = bin2hex(random_bytes(16));
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));
        $stmt = $pdo->prepare("UPDATE general_users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
        $stmt->execute([$reset_token, $expires_at, $email]);

        $reset_link = "https://sexxxtra.hu/reset_password.php?token=$reset_token";
        $subject = "Jelszó visszaállítása";
        $message = "Kattints az alábbi linkre a jelszavad visszaállításához:\n\n$reset_link\n\nA link 1 órán belül érvényes.";
        
        // Biztonságosabb email küldés PHPMailerrel (konfiguráció szükséges)
        mail($email, $subject, $message, "From: no-reply@sexxxtra.hu");
    }
    
    echo "Ha az email létezik, elküldtünk egy jelszó-visszaállítási linket.";
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelszó visszaállítása</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="password-reset-container">
        <h2>Jelszó visszaállítása</h2>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Jelszó visszaállítás</button>
        </form>
    </div>
</body>
</html>

<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Ellenőrizzük, hogy létezik-e az email
    $stmt = $pdo->prepare("SELECT id FROM general_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Egyedi jelszó visszaállító token létrehozása
        $reset_token = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("UPDATE general_users SET reset_token = ? WHERE email = ?");
        $stmt->execute([$reset_token, $email]);

        // Email küldése a felhasználónak (ehhez SMTP beállítás kell)
        $reset_link = "http://sexxxtra.hu/reset_password.php?token=$reset_token";
        $subject = "Jelszó visszaállítása";
        $message = "Kattints az alábbi linkre a jelszavad visszaállításához:\n\n$reset_link";
        $headers = "From: no-reply@sexxxtra.hu";

        mail($email, $subject, $message, $headers);

        echo "Ellenőrizd az emailed a jelszó-visszaállító linkért!";
    } else {
        echo "Ez az email cím nincs regisztrálva!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Elfelejtett jelszó</title>
    <link rel="stylesheet" href="css/password.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="forgot-password-container">
        <h2>Elfelejtetted a jelszavad?</h2>
        <form action="forgot_password.php" method="POST">
            <input type="email" name="email" placeholder="Email címed" required>
            <button type="submit">Jelszó-visszaállítás</button>
        </form>
    </div>
</body>
</html>

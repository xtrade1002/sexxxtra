<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $terms_accepted = isset($_POST['terms']);

    // Ellenőrizzük, hogy minden mező ki van-e töltve
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || !$terms_accepted) {
        $error = "Minden mezőt ki kell tölteni és el kell fogadni az ÁSZF-et!";
    } elseif ($password !== $confirm_password) {
        $error = "A jelszavak nem egyeznek!";
    } else {
        // Jelszó titkosítása
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Ellenőrizzük, hogy az email már létezik-e
        $stmt = $pdo->prepare("SELECT id FROM general_users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Ez az email cím már regisztrálva van!";
        } else {
            // Új felhasználó mentése az adatbázisba
            $stmt = $pdo->prepare("INSERT INTO general_users (username, email, password_hash) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $password_hash])) {
                header("Location: verify_email.php?email=$email");
                exit();
            } else {
                $error = "Hiba történt a regisztráció során!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="auth-container">
        <h2>Regisztráció</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Felhasználónév" required>
            <input type="email" name="email" placeholder="Email cím" required>
            <input type="password" name="password" placeholder="Jelszó" required>
            <input type="password" name="confirm_password" placeholder="Jelszó megerősítése" required>
            <label><input type="checkbox" name="terms" required> Elfogadom az <a href="#">ÁSZF-et</a> és az <a href="#">Adatvédelmi Szabályzatot</a></label>
            <button type="submit">Regisztráció</button>
        </form>
    </div>

</body>
</html>

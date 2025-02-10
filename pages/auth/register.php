<?php
session_start();
session_regenerate_id(true);
require_once __DIR__ . '/../../config.php';


// CSRF token ellenőrzése
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }

    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $terms = isset($_POST["terms"]);

    // ÁSZF ellenőrzése
    if (!$terms) {
        die("Az ÁSZF elfogadása kötelező!");
    }

    // Jelszavak ellenőrzése
    if ($password !== $confirm_password) {
        die("A jelszavak nem egyeznek!");
    }

    // Megnézzük, hogy az email már létezik-e
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        die("Ez az email cím már regisztrálva van!");
    }

    // Jelszó titkosítása
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Felhasználó beszúrása az adatbázisba
    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
    $stmt->execute([$email, $hashed_password]);

    // Email küldése a felhasználónak
    $subject = "Sikeres regisztráció";
    $message = "Kedves felhasználó!\n\nSikeresen regisztráltál az oldalunkra.\n\nÜdvözlettel,\nA Sexxxtra Csapat";
    $headers = "From: no-reply@sexxxtra.com";
    mail($email, $subject, $message, $headers);

    // Átirányítás a bejelentkezési oldalra
    header("Location: pages/auth/login.php?success=1");
    exit();
}
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/register.css">
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
            <h2 class="register-text">Regisztráció</h2>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="email" name="email" placeholder="Email" required>
                <!-- Jelszó mező szem ikonnal -->
                <div class="password-container">
                    <input type="password" name="password" id="register-password" placeholder="Jelszó" required>
                    <img src="../../assets/pictures/visible.png" id="toggle-register-password" class="toggle-password" onclick="toggleRegisterPassword()">
                </div>
                <!-- Jelszó megerősítés mező szem ikonnal -->
                <div class="password-container">
                    <input type="password" name="confirm_password" id="confirm-password" placeholder="Jelszó megerősítése" required>
                    <img src="../../assets/pictures/visible.png" id="toggle-confirm-password" class="toggle-password" onclick="toggleConfirmPassword()">
                </div>

                <div class="terms-container">
                    <input type="checkbox" name="terms" id="terms" required>
                    <label for="terms">Elfogadom az <a href="../pages/menu/terms.php"> ÁSZF-et</a> és az <a href="pages/menu/privacy.php"> Adatvédelmi szerődést</a> </label>
                </div>
                <button type="submit">Regisztráció</button>
            </form>
            <a href="../auth/login.php">Már van fiókod? Jelentkezz be!</a>
        </div>
    </div>
</div>

<script>
// Regisztrációs jelszó láthatóság váltása
function toggleRegisterPassword() {
    var passwordInput = document.getElementById("register-password");
    var toggleIcon = document.getElementById("toggle-register-password");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.src = "../../assets/pictures/hidden.png"; // Jelszó látható → váltás elrejtett ikonra
    } else {
        passwordInput.type = "password";
        toggleIcon.src = "../../assets/pictures/visible.png"; // Jelszó elrejtve → váltás látható ikonra
    }
}

// Jelszó megerősítés láthatóság váltása
function toggleConfirmPassword() {
    var passwordInput = document.getElementById("confirm-password");
    var toggleIcon = document.getElementById("toggle-confirm-password");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.src = "../../assets/pictures/hidden.png"; // Jelszó látható → váltás elrejtett ikonra
    } else {
        passwordInput.type = "password";
        toggleIcon.src = "../../assets/pictures/visible.png"; // Jelszó elrejtve → váltás látható ikonra
    }
}
</script>
</body>
</html>

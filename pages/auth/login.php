<?php
session_start();
require_once '../../config.php'; // Adatbázis kapcsolat

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }

    $input = trim($_POST["email_or_username"]); // Lehet e-mail vagy felhasználónév
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT id, username, email, password_hash FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$input, $input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password_hash"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: ../users_dashboard.php");
        exit();
    } else {
        $error = "Hibás felhasználónév/e-mail vagy jelszó!";
    }
}
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/register.css">
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-box">
        <div class="image-container"></div>
        <div class="form-container">
            <div class="close-btn" onclick="window.location.href='../../index.php'">&times;</div>

            <img src="../../assets/pictures/sexxxtra_logo.png" alt="Sexxxtra Logo" class="logo">
            <h2 class="login-text">Bejelentkezés</h2>

            <?php if (isset($error)): ?>
                <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <!-- Felhasználónév vagy Email mező -->
                <input type="text" name="email_or_username" placeholder="Felhasználónév vagy Email" required>
                
                <!-- Jelszó mező szem ikonnal -->
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Jelszó" required>
                    <img src="../../assets/pictures/visible.png" id="toggle-password" class="toggle-password" onclick="togglePasswordVisibility()">
                </div>

                <div class="remember-container">
                    <input type="checkbox" name="remember">
                    <label>Emlékezz rám</label>
                </div>
                <button type="submit">Belépés</button>
            </form>

            <a href="../auth/reset_password.php">Elfelejtetted a jelszavad?</a>
            <a href="../auth/register.php">Még nincs fiókod? Regisztrálj!</a>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var toggleIcon = document.getElementById("toggle-password");

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

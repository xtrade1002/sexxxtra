<?php
require 'config.php';
session_start();
session_regenerate_id(true);

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $remember = isset($_POST["remember"]);

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        setcookie("user_session", session_id(), ["httponly" => true, "secure" => true, "samesite" => "Strict"]);
        
        if ($remember) {
            setcookie("remember_me", $email, time() + (86400 * 30), "/", "", true, true);
        }
        
        header("Location: profile.php");
        exit();
    } else {
        $error = "Hibás bejelentkezési adatok!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-box">
        <h2>Bejelentkezés</h2>
        <?php if (isset($error)): ?>
            <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Jelszó" required>
            <label><input type="checkbox" name="remember"> Emlékezz rám</label>
            <button type="submit">Belépés</button>
        </form>
        <a href="forgot_password.php">Elfelejtetted a jelszavad?</a>
    </div>
</div>
</body>
</html>

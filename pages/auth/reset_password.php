<?php
require 'config.php';
session_start();

if (!isset($_GET['token'])) {
    die("Hibás token!");
}

$token = $_GET['token'];
$stmt = $pdo->prepare("SELECT id, reset_token_expires FROM general_users WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user || strtotime($user['reset_token_expires']) < time()) {
    die("Érvénytelen vagy lejárt token!");
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Érvénytelen CSRF token!");
    }
    
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "A jelszavak nem egyeznek!";
    } elseif (strlen($new_password) < 8 || !preg_match('/[A-Za-z]/', $new_password) || !preg_match('/\d/', $new_password)) {
        $error = "A jelszónak legalább 8 karakter hosszúnak kell lennie, és tartalmaznia kell betűt és számot!";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE general_users SET password_hash = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
        $stmt->execute([$hashed_password, $user['id']]);

        echo "Jelszó sikeresen megváltoztatva! <a href='login.php'>Belépés</a>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Jelszó visszaállítása</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="password-reset-container">
    <h2>Jelszó visszaállítása</h2>
    <?php if (isset($error)): ?>
        <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="password" name="new_password" placeholder="Új jelszó" required>
        <input type="password" name="confirm_password" placeholder="Jelszó megerősítése" required>
        <button type="submit">Jelszó visszaállítása</button>
    </form>
</div>
</body>
</html>

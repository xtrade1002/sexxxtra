<?php
require 'config.php';

if (!isset($_GET['token'])) {
    die("Hibás token!");
}

$token = $_GET['token'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "A jelszavak nem egyeznek!";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE general_users SET password_hash = ?, reset_token = NULL WHERE reset_token = ?");
        $stmt->execute([$hashed_password, $token]);

        echo "Jelszó sikeresen megváltoztatva! <a href='login.php'>Belépés</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Jelszó visszaállítása</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/password.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Új jelszó megadása</h2>
        <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <input type="password" name="new_password" placeholder="Új jelszó" required>
            <input type="password" name="confirm_password" placeholder="Jelszó megerősítése" required>
            <button type="submit">Jelszó mentése</button>
        </form>
    </div>
</body>
</html>

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
            <img src="../../assets/pictures/sexxxtra_logo.png" alt="Sexxxtra Logo" class="logo">
            <h2 class="login-text">Bejelentkezés</h2>
            <?php if (isset($error)): ?>
                <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Jelszó" required>
                <div class="remember-container">
                    <input type="checkbox" name="remember">
                    <label>Emlékezz rám</label>
                </div>
                <button type="submit">Belépés</button>
            </form>
            <a href="pages/auth/forgot_password.php">Elfelejtetted a jelszavad?</a>
            <a href="pages/auth/register.php">Még nincs fiókod? Regisztrálj!</a>
        </div>
    </div>
</div>
</body>
</html>

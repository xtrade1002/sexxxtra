<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sexxxtra</title>

    <!-- CSS Fájlok -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css"> 
</head>
<header class="top-header">
    <div class="logo">
        <img src="assets/pictures/sexxxtra_logo.png" alt="Sexxxtra Logo">
    </div>
    <div class="auth-section">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="pages/auth/logout.php" class="logout-btn">Kijelentkezés</a>
        <?php else: ?>
            <a href="pages/auth/login.php" class="login-btn" onclick="toggleForgotPassword()">Bejelentkezés</a>
            <a href="pages/auth/register.php" class="register-btn">Regisztráció</a>
            
        <?php endif; ?>
    </div>
    <div class="language-selector">
        <select>
            <option value="hu">🇭🇺 Magyar</option>
            <option value="en">🇬🇧 English</option>
            <option value="de">🇩🇪 Deutsch</option>
        </select>
    </div>
</header>

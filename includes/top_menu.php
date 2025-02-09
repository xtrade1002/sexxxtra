<?php include __DIR__ . '/includes/header.php'; ?>

<header class="top-header">
    <div class="logo">
        <img src="assets/pictures/sexxxtra_logo.png" alt="Sexxxtra Logo">
    </div>
    <div class="auth-section">
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="welcome-msg">Üdv, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="logout-btn">Kijelentkezés</a>
        <?php else: ?>
            <a href="register.php" class="register-btn">REGISZTRÁCIÓ</a>
            <input type="text" placeholder="Felhasználónév" name="username" class="login-input">
            <input type="password" placeholder="••••••" name="password" class="login-input">
            <button class="login-btn">BELÉPÉS</button>
        <?php endif; ?>
    </div>
    <a href="forgot_password.php">Elfelejtetted a jelszavad?</a>

</header>

<header class="top-header">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logó középre igazítva -->
            <div class="col-12 text-center">
                <div class="logo">
                    <img src="/sexxxtra/assets/pictures/sexxxtra_logo.png" alt="Sexxxtra Logo">
                </div>
            </div>
        </div>

        <!-- Bejelentkezés és nyelvválasztó (jobb felső sarok) -->
        <div class="row">
            <div class="col-12 d-flex justify-content-end align-items-center auth-container">
                <div class="auth-section">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="pages/auth/logout.php" class="logout-btn">Kijelentkezés</a>
                    <?php else: ?>
                        <a href="/sexxxtra/pages/auth/login.php" class="login-btn">Bejelentkezés</a>
                        <a href="/sexxxtra/pages/auth/register.php" class="register-btn">Regisztráció</a>
                    <?php endif; ?>
                </div>

                <!-- Nyelvválasztó a jobb sarokban -->
                <div class="language-dropdown">
                    <button id="selected-language">
                        <img src="/sexxxtra/assets/pictures/flag_hu.png" alt="Magyar">
                    </button>
                    <div class="dropdown-content" id="dropdown-menu">
                        <img src="/sexxxtra/assets/pictures/flag_hu.png" alt="Magyar" data-lang="hu">
                        <img src="/sexxxtra/assets/pictures/flag_us.png" alt="English" data-lang="en">
                        <img src="/sexxxtra/assets/pictures/flag_de.png" alt="Deutsch" data-lang="de">
                        <img src="/sexxxtra/assets/pictures/flag_fr.png" alt="Français" data-lang="fr">
                        <img src="/sexxxtra/assets/pictures/flag_it.png" alt="Italiano" data-lang="it">
                        <img src="/sexxxtra/assets/pictures/flag_ru.png" alt="Русский" data-lang="ru">
                        <img src="/sexxxtra/assets/pictures/flag_ro.png" alt="Română" data-lang="ro">
                        <img src="/sexxxtra/assets/pictures/flag_sk.png" alt="Slovenčina" data-lang="sk">
                    </div>
                </div>
            </div>
        </div>

        <!-- Menü a header aljára helyezve -->
        <div class="row">
            <div class="col-12">
                <nav class="menu">
                    <ul class="menu-list">
                        <li><a href="#">Masszázs</a></li>
                        <li><a href="#">Webcam</a></li>
                        <li><a href="#">BDSM</a></li>
                        <li><a href="#">Domina</a></li>
                        <li><a href="#">Club</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

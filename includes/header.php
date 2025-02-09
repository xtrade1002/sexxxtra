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
    <div class="language-dropdown">
    <button id="selected-language">
        <img src="assets/pictures/flag_hu.png" alt="Magyar">
    </button>
    <div class="dropdown-content" id="dropdown-menu"> <!-- ID hozzáadva -->
        <img src="assets/pictures/flag_hu.png" alt="Magyar" data-lang="hu">
        <img src="assets/pictures/flag_us.png" alt="English" data-lang="en">
        <img src="assets/pictures/flag_de.png" alt="Deutsch" data-lang="de">
        <img src="assets/pictures/flag_fr.png" alt="Français" data-lang="fr">
        <img src="assets/pictures/flag_it.png" alt="Italiano" data-lang="it">
        <img src="assets/pictures/flag_ru.png" alt="Русский" data-lang="ru">
        <img src="assets/pictures/flag_ro.png" alt="Română" data-lang="ro">
        <img src="assets/pictures/flag_sk.png" alt="Slovenčina" data-lang="sk">
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let dropdownButton = document.getElementById("selected-language");
    let dropdownMenu = document.getElementById("dropdown-menu");

    if (!dropdownButton || !dropdownMenu) {
        console.error("HIBA: A nyelvválasztó elemei nem találhatóak!");
        return;
    }

    // Kattintásra a menü nyitása és zárása
    dropdownButton.addEventListener("click", function (event) {
        event.stopPropagation(); // Megakadályozza, hogy a kattintás tovaterjedjen
        dropdownMenu.classList.toggle("show");
        dropdownButton.classList.toggle("open");
    });

    // Ha egy zászlót választunk, akkor frissítse a zászlót és zárja be a menüt
    document.querySelectorAll(".dropdown-content img").forEach(img => {
        img.addEventListener("click", function () {
            let selectedImg = dropdownButton.querySelector("img");
            selectedImg.src = this.src;
            dropdownMenu.classList.remove("show");
            dropdownButton.classList.remove("open");
        });
    });

    // Ha bárhova máshova kattintunk, a menü bezáródik
    document.addEventListener("click", function (event) {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove("show");
            dropdownButton.classList.remove("open");
        }
    });
});
</script>

</header>

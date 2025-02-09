<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználói profil</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/loggedin_users.css">
    <script>
        function toggleMenu(id) {
            let submenu = document.getElementById(id);
            submenu.classList.toggle("show");
        }
    </script>
</head>
<body>

<div class="container">
    <!-- Bal oldali menüsor -->
    <nav class="sidebar">
        <ul>
            <li>
                <button onclick="toggleMenu('profil-submenu')">Profilom ▼</button>
                <ul id="profil-submenu" class="submenu">
                    <li><a href="profile.php">Adatlapom</a></li>
                    <li><a href="edit_profile.php">Profil szerkesztése</a></li>
                </ul>
            </li>
            <li>
                <button onclick="toggleMenu('kepek-submenu')">Képek ▼</button>
                <ul id="kepek-submenu" class="submenu">
                    <li><a href="upload_image.php">Képfeltöltés</a></li>
                    <li><a href="manage_images.php">Képek kezelése</a></li>
                </ul>
            </li>
            <li>
                <button onclick="toggleMenu('hirdetes-submenu')">Hirdetéseim ▼</button>
                <ul id="hirdetes-submenu" class="submenu">
                    <li><a href="new_ad.php">Új hirdetés</a></li>
                    <li><a href="manage_ads.php">Hirdetések kezelése</a></li>
                </ul>
            </li>
            <li>
                <button onclick="toggleMenu('beallitasok-submenu')">Beállítások ▼</button>
                <ul id="beallitasok-submenu" class="submenu">
                    <li><a href="privacy.php">Adatvédelem</a></li>
                    <li><a href="notifications.php">Értesítések</a></li>
                </ul>
            </li>
            <li><a href="logout.php" class="logout-btn">Kijelentkezés</a></li>
        </ul>
    </nav>

    <!-- Fő tartalom -->
    <div class="content">
        <h1>Üdv, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Válaszd ki a kívánt menüpontot.</p>
    </div>
</div>

</body>
</html>

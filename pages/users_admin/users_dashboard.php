<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználói Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/header.css">
    <link rel="stylesheet" href="../../assets/css/users_dashboard.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>

    <div class="dashboard-wrapper">
         <!-- Bal oldali hirdetések -->
         <aside class="sidebar banners">
            <div class="banner-ad">Hirdetési hely</div>
            <div class="banner-ad">Hirdetési hely</div>
            <div class="banner-ad">Hirdetési hely</div>
        </aside>
        <!-- Bal oldali menü -->
        <aside class="sidebar">
            <div class="menu-item">
                <button class="menu-toggle">Adataim</button>
                <div class="submenu">
                    <a href="#">Alapadatok</a>
                    <a href="#">Szolgáltatások</a>
                    <a href="#">Elérhetőség</a>
                    <a href="#">Magamról</a>
                </div>
            </div>
            <div class="menu-item">
                <button class="menu-toggle">Média</button>
                <div class="submenu">
                    <a href="#">Képek</a>
                    <a href="#">Videók</a>
                    <a href="#">Archívum</a>
                </div>
            </div>
            <div class="menu-item">
                <button class="menu-toggle">Hirdetéskezelés</button>
                <div class="submenu">
                    <a href="#">Hirdetés feladása</a>
                    <a href="#">Aktív hirdetések</a>
                </div>
            </div>
            <div class="menu-item">
                <button class="menu-toggle">Üzenetek</button>
            </div>
            <div class="menu-item">
                <button class="menu-toggle">Statisztikák</button>
            </div>
            <div class="menu-item">
                <button class="menu-toggle">Beállítások</button>
                <div class="submenu">
                    <a href="#">Email cím</a>
                    <a href="#">Jelszómódosítás</a>
                    <a href="#">Számlázási adatok</a>
                    <a href="#">Inaktiválás</a>
                    <a href="#">Profilom törlése</a>
                </div>
            </div>
            <div class="menu-item">
                <a href="#">Kijelentkezés</a>
            </div>
        </aside>

        <!-- Fő tartalom -->
        <main class="dashboard-content">
            <div class="dashboard-header">
                <h1>Üdvözlünk kedves <span id="username">Felhasználó</span>!</h1>
                <div class="notifications">
                    <i class="fa fa-bell"></i>
                </div>
            </div>
            <p>Itt kezelheted a hirdetéseidet, beállításaidat és profilodat.</p>
        </main>

        <!-- Jobb oldali hirdetések -->
        <aside class="sidebar banners">
            <div class="banner-ad">Hirdetési hely</div>
            <div class="banner-ad">Hirdetési hely</div>
            <div class="banner-ad">Hirdetési hely</div>
        </aside>
    </div>

    <?php include '../../includes/footer.php'; ?>

    <script>
        document.querySelectorAll('.menu-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const submenu = button.nextElementSibling;
                if (submenu) submenu.classList.toggle('active');
            });
        });
    </script>
</body>
</html>

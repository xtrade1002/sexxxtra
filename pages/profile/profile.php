<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM service_providers WHERE id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

if (!$profile) {
    die("A profil nem található!");
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($profile['username']); ?> profilja</title>
    <link rel="stylesheet" href="css/header_menu.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="profile-container">
        <!-- Oldalsó menü -->
        <nav class="sidebar">
            <ul>
                <li><a href="#">Beállítások</a></li>
                <li><a href="#">Képek</a></li>
                <li><a href="#">Adataim</a></li>
                <li><a href="#">Üzenetek</a></li>
                <li><a href="#">Előfizetés</a></li>
                <li><a href="#">Számláim</a></li>
                <li><a href="logout.php">Kijelentkezés</a></li>
            </ul>
        </nav>

        <!-- Központi tartalom -->
        <div class="profile-content">
            <h1>Üdvözöllek, <?php echo htmlspecialchars($profile['username']); ?>!</h1>
            <button onclick="viewProfile()">Profil előnézete</button>

            <!-- Elérhetőség beállítás -->
            <div class="availability">
                <h2>Elérhetőség</h2>
                <form action="update_availability.php" method="POST">
                    <table>
                        <tr>
                            <th>Nap</th>
                            <th>Elérhető</th>
                            <th>Idősáv</th>
                        </tr>
                        <?php
                        $days = ['Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat', 'Vasárnap'];
                        foreach ($days as $day) {
                            echo "<tr>
                                    <td>$day</td>
                                    <td><input type='checkbox' name='availability[$day]'></td>
                                    <td>
                                        <input type='time' name='start_time[$day]'> - 
                                        <input type='time' name='end_time[$day]'>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </table>
                    <button type="submit">Mentés</button>
                </form>
            </div>

            <!-- Árak beállítása -->
            <div class="pricing">
                <h2>Áraim</h2>
                <form action="update_pricing.php" method="POST">
                    <table>
                        <tr>
                            <th>Időtartam</th>
                            <th>Incall ár (CHF)</th>
                            <th>Outcall ár (CHF)</th>
                        </tr>
                        <?php
                        for ($i = 15; $i <= 1440; $i += 15) {
                            $hours = floor($i / 60);
                            $minutes = $i % 60;
                            $displayTime = $hours > 0 ? "$hours óra" : "";
                            $displayTime .= $minutes > 0 ? " $minutes perc" : "";

                            echo "<tr>
                                    <td>$displayTime</td>
                                    <td><input type='text' name='incall_price[$i]'></td>
                                    <td><input type='text' name='outcall_price[$i]'></td>
                                  </tr>";
                        }
                        ?>
                    </table>
                    <button type="submit">Mentés</button>
                </form>
            </div>

        </div>
    </div>

    <script>
        function viewProfile() {
            window.location.href = "profile_preview.php?id=<?php echo $user_id; ?>";
        }
    </script>

</body>
</html>

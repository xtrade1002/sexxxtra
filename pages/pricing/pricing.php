<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Nincs bejelentkezve.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Töröljük az előző árazási adatokat
    $pdo->prepare("DELETE FROM pricing WHERE provider_id = ?")->execute([$user_id]);

    // Új adatok beszúrása
    $stmt = $pdo->prepare("INSERT INTO pricing (provider_id, duration, incall_price, outcall_price) VALUES (?, ?, ?, ?)");

    foreach ($_POST['incall_price'] as $duration => $incall_price) {
        $outcall_price = $_POST['outcall_price'][$duration];
        if (!empty($incall_price) || !empty($outcall_price)) {
            $stmt->execute([$user_id, $duration, $incall_price, $outcall_price]);
        }
    }

    header("Location: profile.php?success=pricing_updated");
    exit();
}

// Lekérdezzük a felhasználó jelenlegi árait
$stmt = $pdo->prepare("SELECT duration, incall_price, outcall_price FROM pricing WHERE provider_id = ?");
$stmt->execute([$user_id]);
$pricing_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Az árakat egy tömbbe rendezzük
$prices = [];
foreach ($pricing_data as $row) {
    $prices[$row['duration']] = [
        'incall_price' => $row['incall_price'],
        'outcall_price' => $row['outcall_price']
    ];
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Árak Beállítása</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="pricing-container">
        <h2>Árak Beállítása</h2>
        <form action="pricing.php" method="POST">
            <table>
                <tr>
                    <th>Időtartam</th>
                    <th>Incall ár (CHF)</th>
                    <th>Outcall ár (CHF)</th>
                </tr>
                <?php
                for ($i = 15; $i <= 1440; $i += 15):
                    $incall_value = isset($prices[$i]) ? $prices[$i]['incall_price'] : "";
                    $outcall_value = isset($prices[$i]) ? $prices[$i]['outcall_price'] : "";
                ?>
                <tr>
                    <td><?= floor($i / 60) . " óra " . ($i % 60) . " perc" ?></td>
                    <td><input type="text" name="incall_price[<?= $i ?>]" value="<?= $incall_value ?>"></td>
                    <td><input type="text" name="outcall_price[<?= $i ?>]" value="<?= $outcall_value ?>"></td>
                </tr>
                <?php endfor; ?>
            </table>
            <button type="submit">Mentés</button>
        </form>
    </div>

</body>
</html>

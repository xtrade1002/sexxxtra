<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Nincs bejelentkezve.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Töröljük az előző elérhetőségi adatokat
    $pdo->prepare("DELETE FROM availability WHERE provider_id = ?")->execute([$user_id]);

    // Új adatok beszúrása
    $stmt = $pdo->prepare("INSERT INTO availability (provider_id, day, start_time, end_time) VALUES (?, ?, ?, ?)");
    
    foreach ($_POST['availability'] as $day => $checked) {
        if (isset($_POST['start_time'][$day]) && isset($_POST['end_time'][$day])) {
            $start_time = $_POST['start_time'][$day];
            $end_time = $_POST['end_time'][$day];
            $stmt->execute([$user_id, $day, $start_time, $end_time]);
        }
    }

    header("Location: profile.php?success=availability_updated");
    exit();
}

// Elérhetőségi adatok lekérése az adatbázisból
$stmt = $pdo->prepare("SELECT day, start_time, end_time FROM availability WHERE provider_id = ?");
$stmt->execute([$user_id]);
$availability = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Adatok előkészítése a megjelenítéshez
$availability_data = [];
foreach ($availability as $row) {
    $availability_data[$row['day']] = [
        'start_time' => $row['start_time'],
        'end_time' => $row['end_time']
    ];
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elérhetőség Beállítása</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="availability-container">
        <h2>Elérhetőség Beállítása</h2>
        <form action="availability.php" method="POST">
            <table>
                <tr>
                    <th>Nap</th>
                    <th>Elérhető</th>
                    <th>Idősáv</th>
                </tr>
                <?php
                $days = ['Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat', 'Vasárnap'];
                foreach ($days as $day):
                    $checked = isset($availability_data[$day]) ? "checked" : "";
                    $start_value = isset($availability_data[$day]) ? $availability_data[$day]['start_time'] : "";
                    $end_value = isset($availability_data[$day]) ? $availability_data[$day]['end_time'] : "";
                ?>
                <tr>
                    <td><?= $day ?></td>
                    <td><input type="checkbox" name="availability[<?= $day ?>]" <?= $checked ?>></td>
                    <td>
                        <input type="time" name="start_time[<?= $day ?>]" value="<?= $start_value ?>"> -
                        <input type="time" name="end_time[<?= $day ?>]" value="<?= $end_value ?>">
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <button type="submit">Mentés</button>
        </form>
    </div>

</body>
</html>

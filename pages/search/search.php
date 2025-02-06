<?php 
require 'config.php';

$query = "SELECT * FROM users WHERE 1=1";
$params = [];

if (!empty($_GET["nationality"])) {
    $query .= " AND nationality = ?";
    $params[] = $_GET["nationality"];
}
if (!empty($_GET["eye_color"])) {
    $query .= " AND eye_color = ?";
    $params[] = $_GET["eye_color"];
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keresés</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="search-container">
        <h2>Felhasználó keresése</h2>
        <form action="search.php" method="GET">
            <label for="nationality">Nemzetiség:</label>
            <select name="nationality">
                <option value="">Válassz</option>
                <option value="Magyar">Magyar</option>
                <option value="Német">Német</option>
                <option value="Francia">Francia</option>
            </select>

            <label for="eye_color">Szemszín:</label>
            <select name="eye_color">
                <option value="">Válassz</option>
                <option value="Kék">Kék</option>
                <option value="Barna">Barna</option>
                <option value="Zöld">Zöld</option>
            </select>

            <button type="submit">Keresés</button>
        </form>
    </div>

    <div class="search-results">
        <h3>Eredmények:</h3>
        <ul>
            <?php foreach ($results as $user): ?>
                <li><?= htmlspecialchars($user['username']) ?> - <?= htmlspecialchars($user['nationality']) ?> - <?= htmlspecialchars($user['eye_color']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

</body>
</html>

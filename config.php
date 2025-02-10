<?php
// Alapbeállítások
define('BASE_PATH', __DIR__ . '/'); // A projekt gyökérkönyvtára

$host = "localhost";
$dbname = "sexxxtra_db";
$username = "root";
$password = ""; // Ha van jelszó az adatbázishoz, írd be!

// Adatbázis kapcsolat létrehozása biztonságosabb beállításokkal
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    die("❌ Adatbázis hiba: " . htmlspecialchars($e->getMessage()));
}

// Biztonságos kilépés
register_shutdown_function(function() use (&$pdo) {
    $pdo = null;
});
?>

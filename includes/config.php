<?php
$host = "localhost";
$dbname = "sexxxtra_db";
$username = "root";
$password = "";

// Adatbázis kapcsolat létrehozása
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Adatbázis hiba: " . $e->getMessage());
}
?>

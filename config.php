<?php
// config.php - Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Ganti dengan username database Anda
define('DB_PASS', '');      // Ganti dengan password database Anda (biasanya kosong di XAMPP)
define('DB_NAME', 'personal_website');

// Fungsi untuk membuat koneksi database
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
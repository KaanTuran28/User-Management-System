<?php
$servername = "localhost";
$username = "root"; // Kullanıcı adınız
$password = ""; // Şifreniz
$dbname = "secmen"; // Bağlanmak istediğiniz veritabanı

// PDO ile veritabanına bağlanma
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Hata raporlama modunu ayarla
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
?>

<?php
$servername = "localhost";
$username = "root"; // Veritabanı kullanıcı adı
$password = ""; // Veritabanı şifresi
$dbname = "panelogin"; // Veritabanı adı

// Veritabanı bağlantısı
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    error_log("Bağlantı başarısız: " . $conn->connect_error); // Hata günlükleme
    die("Bağlantı hatası. Lütfen daha sonra tekrar deneyin."); // Kullanıcıya daha genel bir hata mesajı
}

// Karakter setini ayarla
$conn->set_charset("utf8mb4"); // UTF-8 karakter seti kullanımı
?>

<?php
// Hata raporlamasını aç
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Veritabanı bağlantısını ekle
include 'config-index.php';

// Giriş işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kullanıcıyı veritabanında email ile kontrol et
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Şifreyi kontrol et
        if (password_verify($password, $row['password'])) {
            // Oturumu başlat ve kullanıcı e-posta adresini sakla
            session_start();
            $_SESSION['user_email'] = $email;

            // "En son giriş" tarihini güncelle
            $update_query = "UPDATE users SET en_son_giris = NOW() WHERE email = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("s", $email);
            $update_stmt->execute();

            // Giriş başarılıysa anasayfaya yönlendir
            header('Location: anasayfa.php');
            exit();
        } else {
            $error_message = "Hatalı email veya şifre!";
        }
    } else {
        $error_message = "Hatalı email veya şifre!";
    }
}

$conn->close(); // Bağlantıyı kapat
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

      <!-- Müzik Oynatıcı -->
      <iframe src="audio-player.html" style="display:none;" scrolling="no"></iframe>

    <div class="giris">
        <form action="index.php" method="post" autocomplete="off">
            <h2>Giriş Yap</h2>
            <?php if (isset($error_message)) : ?>
                <p style='color: red;'><?php echo $error_message; ?></p>
            <?php endif; ?>
            <div class="inputBox">
                <input type="email" name="email" required />
                <span>Email</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="password" name="password" required />
                <span>Şifre</span>
                <i></i>
            </div>
            <div class="linkss">
                <a href="sifre.php">Şifreni mi unuttun?</a>
                <a href="kayit.php">Kayıt ol?</a>
            </div>
            <input type="submit" value="Giriş Yap" />
        </form>
    </div>
</body>
</html>

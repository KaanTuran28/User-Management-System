<?php
include("config-index.php"); // Veritabanı bağlantısı için ayar dosyasını ekle

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad']);
    $soyad = trim($_POST['soyad']);
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre'];
    $sifre_tekrar = $_POST['sifre_tekrar'];

    // Boş alan kontrolü
    if (empty($ad) || empty($soyad) || empty($email) || empty($sifre) || empty($sifre_tekrar)) {
        echo "Lütfen tüm alanları doldurun!";
        exit();
    }

    // E-posta formatını kontrol et
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Geçersiz e-posta adresi!";
        exit();
    }

    // Şifreleri kontrol et
    if ($sifre !== $sifre_tekrar) {
        echo "Şifreler eşleşmiyor!";
        exit();
    }

    // E-posta adresinin zaten kayıtlı olup olmadığını kontrol et
    $sql_check = "SELECT * FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "Bu e-posta adresi zaten kayıtlı!";
        exit();
    }

    // Şifreyi hashle
    $hashed_password = password_hash($sifre, PASSWORD_DEFAULT);

    // Kullanıcıyı veritabanına ekle
    $sql = "INSERT INTO users (ad, soyad, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $ad, $soyad, $email, $hashed_password);

    if ($stmt->execute()) {
        echo '<div style="color: green; font-weight: bold;">Kayıt başarıyla tamamlandı!</div>';
    } else {
        echo '<div style="color: red; font-weight: bold;">Kayıt sırasında bir hata oluştu: ' . $stmt->error . '</div>';
    }
    

    $stmt->close();
    $stmt_check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Kayıt Ol</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/kayit.css" />
</head>
<body>

      <!-- Müzik Oynatıcı -->
      <iframe src="audio-player.html" style="display:none;" scrolling="no"></iframe>

    <div class="kayit">
        <form action="kayit.php" method="post" autocomplete="off">
            <h2>Kayıt Ol</h2>
            <div class="inputBox">
                <input type="text" name="ad" required />
                <span>Ad</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="text" name="soyad" required />
                <span>Soyad</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="email" name="email" required />
                <span>E-Posta</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="password" name="sifre" required />
                <span>Şifre</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="password" name="sifre_tekrar" required />
                <span>Şifreyi tekrar gir</span>
                <i></i>
            </div>
            <input type="submit" value="Kayıt Ol" />
            <div class="links">
                Zaten hesabın var mı? <a href="index.php">Giriş yap</a>
            </div>
        </form>
    </div>
</body>
</html>

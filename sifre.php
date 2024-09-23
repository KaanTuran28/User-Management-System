<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Giriş Bağlantısı Gönder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/sifre.css" />
    <script src="main.js" defer></script>
</head>
<body>

      <!-- Müzik Oynatıcı -->
      <iframe src="audio-player.html" style="display:none;" scrolling="no"></iframe>

    <div class="giris">
        <form autocomplete="off" action="giris.php" method="POST">
            <h3>Giriş Yaparken Sorun mu Yaşıyorsun?</h3>
            <div class="inputBox">
                <div class="post">E-posta adresini gir ve hesabına yeniden girebilmen için sana bir bağlantı gönderelim.</div>
            </div>
            <div class="inputBox">
                <input type="email" name="email" required />
                <span>E-posta Adresi</span>
                <i></i>
            </div>
            <input type="submit" value="Giriş Bağlantısı Gönder" />
            <div class="alt">
                <a class="sf" href="index.php">Giriş ekranına dön?</a>
                <a class="sf" href="kayit.php">Yeni hesap oluştur?</a>
            </div>
        </form>
    </div>

    <?php
session_start(); // Oturumu başlat

// Veri tabanı bağlantısını dahil et
include 'config-index.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Deneme sayısını kontrol et
    if (isset($_SESSION['deneme_sayisi']) && $_SESSION['deneme_sayisi'] >= 3) {
        $son_deneme = $_SESSION['son_deneme'] ?? time();
        $bekleme_suresi = 30; // 30 saniye bekleme süresi
        if (time() - $son_deneme < $bekleme_suresi) {
            $kalan_sure = $bekleme_suresi - (time() - $son_deneme);
            echo "Lütfen " . $kalan_sure . " saniye bekleyin.";
            exit;
        } else {
            // Bekleme süresi dolduysa, deneme sayısını sıfırla
            $_SESSION['deneme_sayisi'] = 0;
        }
    }

    // E-posta adresini kontrol et
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // E-posta varsa, bağlantı gönder
        $reset_link = "https://yourdomain.com/sifre_yenile.php?token=" . bin2hex(random_bytes(50)); // Token oluştur
        mail($email, "Şifre Yenileme Bağlantısı", "Şifre yenilemek için bu bağlantıya tıklayın: " . $reset_link);
        echo "Şifre yenileme bağlantısı e-posta adresinize gönderildi.";
        
        // Deneme sayısını artır
        $_SESSION['deneme_sayisi'] = ($_SESSION['deneme_sayisi'] ?? 0) + 1;
        $_SESSION['son_deneme'] = time(); // Son deneme zamanını güncelle
    } else {
        // E-posta yoksa hata mesajı
        echo "Bu e-posta adresi sistemde bulunamadı.";
        
        // Deneme sayısını artır
        $_SESSION['deneme_sayisi'] = ($_SESSION['deneme_sayisi'] ?? 0) + 1;
        $_SESSION['son_deneme'] = time(); // Son deneme zamanını güncelle
    }

    $stmt->close();
    $conn->close();
}
?>

</body>
</html>

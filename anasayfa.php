<?php
// Veritabanı bağlantısını ekle
include 'config-index.php';

session_start();

// Kullanıcının oturum açıp açmadığını kontrol et
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php"); // Giriş sayfasına yönlendir
    exit();
}

// Kullanıcı e-posta adresini al
$user_email = $_SESSION['user_email'];

// Kullanıcı bilgilerini veritabanından çek
$query = "SELECT ad, soyad, kayit_tarihi, en_son_giris FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Kullanıcı bilgileri varsa, "en son giriş" tarihini güncelle
if ($user) {
    $update_query = "UPDATE users SET en_son_giris = NOW() WHERE email = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("s", $user_email);
    $update_stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa</title>
    <link rel="stylesheet" href="css/anasayfa.css">
</head>
<body>
    <header>
        <h1>Ana Sayfa</h1>
    </header>

    <div class="container">
        <nav class="sidebar">
            <ul>
                <li><a href="anasayfa.php">Ana Sayfa</a></li>
                <li><a href="101m-sorgu.php">101m Sorgu</a></li>
                <li><a href="adres-sorgu.php">Adres Sorgu</a></li>
                <li><a href="numara-sorgu.php">Numara Sorgu</a></li>
                <li><a href="index.php">Çıkış</a></li>
            </ul>
        </nav>

        <main>
    <h2>Hoş geldiniz, <?php echo htmlspecialchars($user['ad']) . ' ' . htmlspecialchars($user['soyad']); ?>!</h2>
    <div class="dashboard">
        <h3>Kullanıcı Bilgileri</h3>
        <p>Aşağıdaki tabloda hesabınıza ait bilgileri görebilirsiniz:</p>
        
        <table>
            <tr>
                <th>Ad</th>
                <th>Soyad</th>
                <th>Kayıt Tarihi</th>
                <th>E-posta</th>
                <th>En Son Giriş</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($user['ad']); ?></td>
                <td><?php echo htmlspecialchars($user['soyad']); ?></td>
                <td><?php echo htmlspecialchars($user['kayit_tarihi']); ?></td>
                <td><?php echo htmlspecialchars($user_email); ?></td>
                <td><?php echo htmlspecialchars($user['en_son_giris']); ?></td>
            </tr>
        </table>
        
        <p>Bu bilgiler hesabınızın güvenliği için önemlidir. Aşağıda kullanıcı bilgilerinizi güncel tutmanın yollarını bulabilirsiniz:</p>
        <ul>
            <li><strong>Kayıt Tarihi:</strong> Hesabınızın ne zaman oluşturulduğunu gösterir. Bu tarih, hesabınızın güvenliği için önemlidir.</li>
            <li><strong>E-posta Adresi:</strong> Hesabınıza erişim sağlamak için kullandığınız e-posta adresidir. Bu adres üzerinden şifre sıfırlama gibi işlemler yapılır.</li>
            <li><strong>En Son Giriş:</strong> Son kez ne zaman giriş yaptığınızı gösterir. Eğer bu tarih tanımadığınız bir zaman dilimiyse, güvenlik için hemen şifre değişikliği yapmanızı öneririz.</li>
        </ul>
        
        <p>Eğer bilgilerinizi güncellemek isterseniz, lütfen profil ayarlarınıza gidin. Hesap güvenliğiniz için güçlü bir şifre kullandığınızdan emin olun.</p>
        
        <p>Herhangi bir sorunla karşılaşırsanız veya yardım ihtiyacınız olursa, destek ekibimizle iletişime geçmekten çekinmeyin. Size yardımcı olmaktan mutluluk duyarız!</p>
    </div>
</main>

    </div>

    <footer>
        <p>BY ThearOfFear.</p>
        <br>
        <p>© 2024 Ana Sayfa. Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>

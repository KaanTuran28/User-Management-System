<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adres Sorgu</title>
    <link rel="stylesheet" href="css/adres-sorgu.css">
</head>
<body>
    <header>
        <h1>Adres Sorgu</h1>
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
            <h2>Adres Bilgilerinizi Girin</h2>
            <form method="post">
                <div>
                    <label for="tc">T.C. Kimlik No:</label>
                    <input type="text" id="tc" name="tc" placeholder="T.C. Kimlik No">
                </div>
                <div>
                    <label for="adi">Adı:</label>
                    <input type="text" id="adi" name="adi" placeholder="Adınızı girin">
                </div>
                <div>
                    <label for="soyadi">Soyadı:</label>
                    <input type="text" id="soyadi" name="soyadi" placeholder="Soyadınızı girin">
                </div>
                <div>
                    <label for="dogumtarihi">Doğum Tarihi:</label>
                    <input type="date" id="dogumtarihi" name="dogumtarihi">
                </div>
                <div>
                    <label for="nufusili">Nüfus İli:</label>
                    <input type="text" id="nufusili" name="nufusili" placeholder="Nüfus ilini girin">
                </div>
                <div>
                    <label for="nufusilcesi">Nüfus İlçesi:</label>
                    <input type="text" id="nufusilcesi" name="nufusilcesi" placeholder="Nüfus ilçesini girin">
                </div>
                <button type="submit">Sorgula</button>
            </form>

            <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config-adres.php'; // Veritabanı bağlantısı

    // Form verilerini al
    $tc = $_POST['tc'] ?? '';
    $adi = $_POST['adi'] ?? '';
    $soyadi = $_POST['soyadi'] ?? '';
    $dogumtarihi = $_POST['dogumtarihi'] ?? '';
    $nufusili = $_POST['nufusili'] ?? '';
    $nufusilcesi = $_POST['nufusilcesi'] ?? '';

    // En az bir alanın dolu olup olmadığını kontrol et
    if (empty($tc) && empty($adi) && empty($soyadi) && empty($dogumtarihi) && empty($nufusili) && empty($nufusilcesi)) {
        echo "<p>Lütfen en az bir arama kriteri girin.</p>";
    } else {
        // SQL sorgusu
        $sql = "SELECT * FROM secmen2015 WHERE 
                (TC LIKE :tc OR :tc = '') AND
                (ADI LIKE :adi OR :adi = '') AND
                (SOYADI LIKE :soyadi OR :soyadi = '') AND
                (DOGUMTARIHI = :dogumtarihi OR :dogumtarihi = '') AND
                (NUFUSILI LIKE :nufusili OR :nufusili = '') AND
                (NUFUSILCESI LIKE :nufusilcesi OR :nufusilcesi = '')";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':tc' => "%$tc%",
            ':adi' => "%$adi%",
            ':soyadi' => "%$soyadi%",
            ':dogumtarihi' => $dogumtarihi,
            ':nufusili' => "%$nufusili%",
            ':nufusilcesi' => "%$nufusilcesi%"
        ]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Sorgu sonuçlarını tablo şeklinde göster
        if ($results) {
            echo "<h3>Sorgu Sonuçları:</h3>";
            echo "<div class='results-container'>";
            echo "<table>";
            echo "<thead>
                    <tr>
                        <th>Adı</th>
                        <th>Soyadı</th>
                        <th>T.C.</th>
                        <th>Doğum Yeri</th>
                        <th>Doğum Tarihi</th>
                        <th>Cinsiyeti</th>
                        <th>Nüfus İli</th>
                        <th>Nüfus İlçesi</th>
                        <th>Adres İl</th>
                        <th>Adres İlçe</th>
                        <th>Mahalle</th>
                        <th>Cadde</th>
                        <th>Kapı No</th>
                        <th>Daire No</th>
                        <th>Engel</th>
                    </tr>
                  </thead>";
            echo "<tbody>";
            foreach ($results as $result) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($result['ADI']) . "</td>";
                echo "<td>" . htmlspecialchars($result['SOYADI']) . "</td>";
                echo "<td>" . htmlspecialchars($result['TC']) . "</td>";
                echo "<td>" . htmlspecialchars($result['DOGUMYERI']) . "</td>";
                echo "<td>" . htmlspecialchars($result['DOGUMTARIHI']) . "</td>";
                echo "<td>" . htmlspecialchars($result['CINSIYETI']) . "</td>";
                echo "<td>" . htmlspecialchars($result['NUFUSILI']) . "</td>";
                echo "<td>" . htmlspecialchars($result['NUFUSILCESI']) . "</td>";
                echo "<td>" . htmlspecialchars($result['ADRESIL']) . "</td>";
                echo "<td>" . htmlspecialchars($result['ADRESILCE']) . "</td>";
                echo "<td>" . htmlspecialchars($result['MAHALLE']) . "</td>";
                echo "<td>" . htmlspecialchars($result['CADDE']) . "</td>";
                echo "<td>" . htmlspecialchars($result['KAPINO']) . "</td>";
                echo "<td>" . htmlspecialchars($result['DAIRENO']) . "</td>";
                echo "<td>" . htmlspecialchars($result['ENGEL']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>Kullanıcı bulunamadı.</p>";
        }
    }
}
?>

        </main>
    </div>

    <footer>
        <p>BY ThearOfFear.</p>
        <br>
        <p>© 2024 Ana Sayfa. Tüm hakları saklıdır.</p>
    </footer>
</body>
</html>

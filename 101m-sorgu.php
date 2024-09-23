<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>101m Sorgu</title>
    <link rel="stylesheet" href="css/101m-sorgu.css">
</head>
<body>
    <header>
        <h1>101m Sorgu</h1>
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
            <h2>Kullanıcı Bilgisi Girin</h2>
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
                    <label for="nufusil">Nüfus İli:</label>
                    <input type="text" id="nufusil" name="nufusil" placeholder="Nüfus ilini girin">
                </div>
                <div>
                    <label for="nufusilce">Nüfus İlçesi:</label>
                    <input type="text" id="nufusilce" name="nufusilce" placeholder="Nüfus ilçesini girin">
                </div>
                <div>
                    <label for="anneadi">Anne Adı:</label>
                    <input type="text" id="anneadi" name="anneadi" placeholder="Anne adını girin">
                </div>
                <div>
                    <label for="annetc">Anne T.C. Kimlik No:</label>
                    <input type="text" id="annetc" name="annetc" placeholder="Anne T.C. Kimlik No">
                </div>
                <div>
                    <label for="babaadi">Baba Adı:</label>
                    <input type="text" id="babaadi" name="babaadi" placeholder="Baba adını girin">
                </div>
                <div>
                    <label for="babatc">Baba T.C. Kimlik No:</label>
                    <input type="text" id="babatc" name="babatc" placeholder="Baba T.C. Kimlik No">
                </div>
                <div>
                    <label for="uyruk">Uyruk:</label>
                    <input type="text" id="uyruk" name="uyruk" placeholder="Uyruk girin">
                </div>
                <button type="submit" style="width: 100%;">Sorgula</button> <!-- Butonu altta yerleştirdim -->
            </form>

            <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config-101m.php'; // Database connection

    // Retrieve form data
    $tc = $_POST['tc'] ?? '';
    $adi = $_POST['adi'] ?? '';
    $soyadi = $_POST['soyadi'] ?? '';
    $dogumtarihi = $_POST['dogumtarihi'] ?? '';
    $nufusil = $_POST['nufusil'] ?? '';
    $nufusilce = $_POST['nufusilce'] ?? '';
    $anneadi = $_POST['anneadi'] ?? '';
    $annetc = $_POST['annetc'] ?? '';
    $babaadi = $_POST['babaadi'] ?? '';
    $babatc = $_POST['babatc'] ?? '';
    $uyruk = $_POST['uyruk'] ?? '';

    // Kontrol: En az bir alanın doldurulmuş olması gerekiyor
    if (empty($tc) && empty($adi) && empty($soyadi) && empty($dogumtarihi) && 
        empty($nufusil) && empty($nufusilce) && empty($anneadi) && 
        empty($annetc) && empty($babaadi) && empty($babatc) && 
        empty($uyruk)) {
        echo "<p style='color:red;'>Lütfen en az bir bilgi giriniz.</p>";
    } else {
        // SQL query
        $sql = "SELECT * FROM 101m WHERE 
                (TC LIKE '%$tc%' OR '$tc' = '') AND
                (ADI LIKE '%$adi%' OR '$adi' = '') AND
                (SOYADI LIKE '%$soyadi%' OR '$soyadi' = '') AND
                (DOGUMTARIHI = '$dogumtarihi' OR '$dogumtarihi' = '') AND
                (NUFUSIL LIKE '%$nufusil%' OR '$nufusil' = '') AND
                (NUFUSILCE LIKE '%$nufusilce%' OR '$nufusilce' = '') AND
                (ANNEADI LIKE '%$anneadi%' OR '$anneadi' = '') AND
                (ANNETC LIKE '%$annetc%' OR '$annetc' = '') AND
                (BABAADI LIKE '%$babaadi%' OR '$babaadi' = '') AND
                (BABATC LIKE '%$babatc%' OR '$babatc' = '') AND
                (UYRUK LIKE '%$uyruk%' OR '$uyruk' = '')";
        
        $result = $conn->query($sql);
        $results = [];

        if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $results[] = $row;
            }
        } else {
            echo "<p>Kullanıcı bulunamadı.</p>";
        }

        // Display results
        if (!empty($results)) {
            echo "<h3>Sorgu Sonuçları:</h3>";
            echo "<div class='results-container'>";
            echo "<table>";
            echo "<thead>
                    <tr>
                        <th>Adı</th>
                        <th>Soyadı</th>
                        <th>T.C.</th>
                        <th>Doğum Tarihi</th>
                        <th>Nüfus İli</th>
                        <th>Nüfus İlçesi</th>
                        <th>Anne Adı</th>
                        <th>Anne T.C.</th>
                        <th>Baba Adı</th>
                        <th>Baba T.C.</th>
                        <th>Uyruk</th>
                    </tr>
                  </thead>
                  <tbody>";

            foreach ($results as $result) {
                echo "<tr>" .
                    "<td>" . htmlspecialchars($result['ADI']) . "</td>" .
                    "<td>" . htmlspecialchars($result['SOYADI']) . "</td>" .
                    "<td>" . htmlspecialchars($result['TC']) . "</td>" .
                    "<td>" . htmlspecialchars($result['DOGUMTARIHI']) . "</td>" .
                    "<td>" . htmlspecialchars($result['NUFUSIL']) . "</td>" .
                    "<td>" . htmlspecialchars($result['NUFUSILCE']) . "</td>" .
                    "<td>" . htmlspecialchars($result['ANNEADI']) . "</td>" .
                    "<td>" . htmlspecialchars($result['ANNETC']) . "</td>" .
                    "<td>" . htmlspecialchars($result['BABAADI']) . "</td>" .
                    "<td>" . htmlspecialchars($result['BABATC']) . "</td>" .
                    "<td>" . htmlspecialchars($result['UYRUK']) . "</td>" .
                    "</tr>";
            }

            echo "</tbody></table>";
            echo "</div>"; // results-container sonu
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

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numara Sorgu</title>
    <link rel="stylesheet" href="css/numara-sorgu.css">
</head>
<body>
    <header>
        <h1>Numara Sorgu</h1>
    </header>

    <div class="container">
        <nav class="sidebar">
            <ul>
                <li><a href="anasayfa.php">Ana Sayfa</a></li>
                <li><a href="101m-sorgu.php">101m Sorgu</a></li>
                <li><a href="adres-sorgu.php">Adres Sorgu</a></li>
                <li><a href="numara-sorgu.php">Numara Sorgu</a></li>
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
                    <label for="gsm">GSM No:</label>
                    <input type="text" id="gsm" name="gsm" placeholder="GSM No">
                </div>
                <div>
                    <label for="BY">BY:</label>
                    <input type="text" id="BY" name="BY" placeholder="BY">
                </div>
                <button type="submit">Sorgula</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                include 'config-numara.php'; // Veritabanı bağlantısı

                // Form verilerini al
                $tc = $_POST['tc'] ?? '';
                $gsm = $_POST['gsm'] ?? '';
                $by = $_POST['BY'] ?? '';

                // En az bir alanın dolu olup olmadığını kontrol et
                if (empty($tc) && empty($gsm) && empty($by)) {
                    echo "<p>Lütfen en az bir arama kriteri girin.</p>";
                } else {
                    // SQL sorgusu
                    $sql = "SELECT * FROM gsm WHERE 
                            (TC LIKE :tc OR :tc = '') AND
                            (GSM LIKE :gsm OR :gsm = '') AND
                            (`BY` LIKE :by OR :by = '')";
                    
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':tc' => "%$tc%",
                        ':gsm' => "%$gsm%",
                        ':by' => "%$by%"
                    ]);

                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Sorgu sonuçlarını tablo şeklinde göster
                    if ($results) {
                        echo "<h3>Sorgu Sonuçları:</h3>";
                        echo "<table>";
                        echo "<thead>
                                <tr>
                                    <th>T.C.</th>
                                    <th>GSM No</th>
                                    <th>BY</th>
                                </tr>
                              </thead>";
                        echo "<tbody>";
                        foreach ($results as $result) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($result['TC']) . "</td>";
                            echo "<td>" . htmlspecialchars($result['GSM']) . "</td>";
                            echo "<td>" . htmlspecialchars($result['BY']) . "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
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
